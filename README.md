Project H.A.S Component Documentation - Error Handling Component
-----------------------------------------------------------------
Author Group: **Home Automation Systems**

Prepared for: Dr. Curtis Busby-Earle

Prepared by: Aston Hamilton, Renee Whitelocke, Orane Edwards

March 18, 2014

Version number: 000-0002


##Component Description
The purpose of this component is to allow other components to log application specific errors. 
This component uses the functionality from the Auditing component that was built by the **Motor Vehicle Registration System** team.

Note: The `message` and `metadata` input to this component are combined and mapped to the `comment` input of the Auditing component.

    Auditing component: https://github.com/uwi-mase-2014-ccd/component-motor-vehicle-registration-system-auditing-services


##Services
The _Error Logging_ web service is exposed by this component.

###Endpoint
This component has been deployed to the UWI server at the endpoint: 

    POST http://cs-proj-srv:8083/component-error-logging/src/error.php

This component has been deployed to a public server at the endpoint: 

    POST http://ticketmanager.mysoftware.io:8100/component-error-logging/src/error.php

Note: The UWI Server deployment will not work at the moment because the auditing component is not hosted on the UWI server

###Arguments
    message: 
        This arguement refers to a comprehensive summary of the error in human readable text that will be displayed in the logs.

    metadata: 
        This argument refers to other metadata associated with the error. This parameter is optional and can contain any fields and be of any valid datatype the user wants. Valid datatypes are determined by the encodeing used for the request body. Both application/json and url-form-encoded are supported by this service.

    
###Description:
Error Logging
    This web service will convert the metadata inputed to a JSON string and append it to the inputted message.
    It then calls the service exposed by the Auditing component to store the comound message in the autiding database.

###Responses:
####Error Successfully Logged
If the error is successfully logged, a response similar to the following sample response is returned:
```javascript    
{
    "code": 200,
    "data": {
        "error-logged": "test-log\\n Metadata: \"test\"",
        "message": "Success"
    },
    "debug": {}
}
```
    Refer to schema: response-200.json

####Error Unsuccessfully Logged
If the error cannot be logged because the Auditing component is not working as expected, a response similar to the following sample response is returned:
```javascript    
{
    "code": 500,
    "data": {},
    "debug": {
        "data": {},
        "message": "The request to the auditing component failed."
    }
}
```
    Refer to schema: response-500.json

####Invalid HTTP Method
On An Invalid HTTP Method a response similar to the following sample response is returned:
```javascript
{
    "code": 400,
    "data": {},
    "debug": {
        "data": {},
        "message": "This service only accepts a POST Request."
    }
}
```
    Refer to schema: response-400.json
    
####Missing Arguments
If a required argument is not submitted, a response similar to the following sample response is returned:
```javascript
{
    "code": 400,
    "data": {},
    "debug": {
        "data": {},
        "message": "Incorrect request parameters. Required Parameters [message], Optional Parameters [metadata]"
    }
}
```
    Refer to schema: response-400.json
    
####Unexpected Error
On Any Unexpected Error a response similar to the following sample response is returned:
```javascript
{
    "code": 500,
    "data": {},
    "debug": {
        "data": {},
        "message": "An exception has occured"
    }
}
```
    Refer to schema: response-500.json

