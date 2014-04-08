Project H.A.S Component - Error Handling Documentation
------------------------------------------------
Prepared for: Dr. Curtis Busby-Earle

Prepared by: Aston Hamilton, Renee Whitelocke, Orane Edwards

March 18, 2014

Version number: 000-0001


##Component Description
The purpose of this component is to provide a client with Error
Logging services.

##Services
+ Error Logging Web Service
	<insert description of service.>
	The Error Logging web service was created to integrate with other web services to allow other applications to handler errors.


###Endpoint
+ Error Logging : http:uwi-has.appspot.com/v1/error/

###Arguments
+ Error Logging 
	<insert args>
	+ message: 
		This arguement refers to a comprehensive summary of the error in human readable text that will be displayed in the logs.

	+ metadata: 
		This argument refers to other metadata associated with the error. This is accepted in JSON format with parameters:  

			description: 
				A more detailed description of the error. This is where machine generated error codes should go.  

			component:
				The component/application that posted the error.
	
###Description:
+ Error Logging
<insert description of the functionality provided by the error logging web service>
	This web service currently integrates with PaterTrail - an external log archiving service.

###Success Schema:
<A reference to a schema defined using JSONSchema maintained in the 
‘schema’ folder of the repository that maintains the subject component component.
 This schema shall define the expected data payload when this service returns an HTTP 200.>



	
	
