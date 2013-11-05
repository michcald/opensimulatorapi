<?php

abstract class Rest_Dispatcher
{
    public static function dispatch(Rest_Request $request)
    {
        $response = null;
        
        // creating the proper response
        if($request->getResponseType() == 'json') {
            $response = new Rest_Response_Json();
        } else if($request->getResponseType() == 'xml') {
            $response = new Rest_Response_Xml();
        }
        
        // check if the method is correct
        if($request instanceof Rest_Request_Bad) {
            return $response->setStatus(Rest_Response::STATUS_NOT_ACCEPTABLE);
        }
        
        // check if the controller is specified
        if(!$request->getController()) {
            return $response->setStatus(Rest_Response::STATUS_NOT_FOUND);
        }
        
        // defining the controller complete class name
        $class = 'Command_' . self::strToCamelCase($request->getController());
        
        $obj = new $class($request, $response);
        
        if(!$obj instanceof Rest_Command) {
            return $response->setStatus(Rest_Response::STATUS_BAD_GATEWAY);
        }

        try
        {
            $obj->preAction();

            // execute the right method depending by the http method used
            switch(get_class($request))
            {
                case 'Rest_Request_Get': $obj->get(); break;
                case 'Rest_Request_Post': $obj->post(); break;
                case 'Rest_Request_Put': $obj->put(); break;
                case 'Rest_Request_Delete': $obj->delete(); break;
            }

            $obj->postAction();
        }
        catch(Exception $e)
        {
            $obj->getResponse()->setBody(array(
                'status' => 'Failed',
                'message' => $e->getMessage()
            ));
            
            return $obj->getResponse();
        }
        
        return $obj->getResponse();
    }
    
    private static function strToCamelCase($string)
    {
        $string = strtolower($string);
        return str_replace(' ', '', ucwords(strtolower(str_replace('-', ' ', $string))));
    }
}