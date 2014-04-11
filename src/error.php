<?php


use GuzzleHttp\Client;

try {
	ini_set("display_errors", 1);
	ini_set("track_errors", 1);
	ini_set("html_errors", 1);
	error_reporting(E_ALL);

	require 'vendor/autoload.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Parse JSON Request
		$inputJSON = file_get_contents('php://input');
		$data = json_decode( $inputJSON, TRUE ); //convert JSON into array
	
		if ($data !== NULL) {
			$_POST = $data;
		}
	
		// Remote system logging.
		// TODO: replace with classmates logging api using Guzzle for HTTP requests.- Replaced with custom php http POST(post_req(args*))
		function send_remote_syslog($message, $component = 'HomeAutomationSystem', $program = 'ERR')
		{
			$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
			foreach (explode('\n', $message) as $line) {
				$syslog_message = '<22>' . date('M d H:i:s ') . $program . ' ' . $component . ': ' . $line;
			
				// Actually log to papertrail on assigned port 57238.
				socket_sendto($sock, $syslog_message, strlen($syslog_message), 0, 'logs.papertrailapp.com', 57238);
			}
			socket_close($sock);
		}
	
		function post_req($message)
		{
			$RESQUEST_BODY = array(
				"dbPort" 		=> '3306',
				"dbHost" 		=> "ticketmanager.mysoftware.io",
				"dbPassword" 	=> "password",
				"dbName" 		=> "projecthas",
				"dbUsername"	=> "projecthas-db",
			
				"eventId" 		=> '5',
				"userName" 		=> "projecthas-error",
				"comment" 		=> $message
			);
		
			$AUTING_ENDPOINT = 'http://uwicsc.jacxtech.com/Auditing/Audit/';
		
			$client = new Client();

			// Send request to DB Component
			$res = $client->post($AUTING_ENDPOINT . '?jsondata=' . json_encode($RESQUEST_BODY), array(
				'body' => array()
			));
		
		
			if (isset($_GET['_debug'])) {
				var_dump($res);
				
				var_dump(array('body' => (string)$res->getBody()));
			}
		
			// Check if it succeeded
			if ($res->getStatusCode() == 200) {
				$body = $res->getBody();
				$body = json_decode($body, TRUE);
		
				if ($body == NULL || !isset($body['code'])) {
					return FALSE;
				}
			
				if ($body['code'] == 200) {
					return TRUE;
				}
			
				return FALSE;
			} else {
				return FALSE;
			}
		}
	
		if (isset($_POST['message'])) { // && isset['data'])
		
			$message  = $_POST['message'];
		
			if (isset($_POST['metadata'])) {
				$metadata = $_POST['metadata'];
				$message = $message . "\n Metadata: " . json_encode($metadata, JSON_PRETTY_PRINT);
			}
		
			send_remote_syslog($message);
			$loggedToAuditing = post_req($message);
			if ($loggedToAuditing) {
				$response = array(
					'code' => 200,
					'data' => array(
						'error-logged' => $message,
						'message' => "Success"
					),
					'debug' => new stdClass
				);
			} else {
				$response = array(
					'code' => 500,
					'data' => new stdClass,
					'debug' => array(
						'data' => new stdClass,
						'message' => 'The request to the auditing component failed.'
					)
				);
			}
		} else {
			// Exception for no data/message
			$response = array(
				'code' => 400,
				'data' => new stdClass,
				'debug' => array(
					'data' => new stdClass,
					'message' => 'Incorrect request parameters. Required Parameters [message], Optional Parameters [metadata]'
				)
			);
		}
	} else {
		$response = array(
			'code' => 400,
			'data' => new stdClass,
			'debug' => array(
				'data' => new stdClass,
				'message' => 'This service only accepts a POST Request.'
			)
		);
	}
} catch (Exception $e) {
	$response = array(
		'code' => 500,
		'data' => new stdClass,
		'debug' => array(
			'data' => array(
				'Caught exception: ' => $e->getMessage()
			),
			'message' => 'An exception has occured.'
		)
	);
}

// In every scenario a $response object is produced - return it.
echo json_encode($response, JSON_PRETTY_PRINT);
// http://uwicsc.jacxtech.com/Auditing/Audit/
// { "dbHost":"localhost", "dbPassword":"password", "eventId":5, "dbName":"errorlog", "userName":"HASerroe", "comment":"User Crisp Technologies registered with username jcarey.", "dbUsername":"root", "dbPort":3306  }
