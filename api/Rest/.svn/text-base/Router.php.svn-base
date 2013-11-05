<?php

/*
 * URL patterns
 * GET users - list all users
 * GET users/1 - list info for user 1
 * POST users - create a new user
 * PUT users/1 - update user 1
 * DELETE users/1 - delete user 1
 */

abstract class Rest_Router
{
    public static function route()
    {
        $uri = self::getUri();
        
        // reading response type (by default is json)
        $responseType = (strstr($uri, '.xml')) ? 'xml' : 'json';
        $uri = str_replace(array('.json','.xml'), array('',''), $uri);
        
        $chunks = explode('/', $uri);

        // the first required element is the controller
        $controller = array_shift($chunks);
        $id = array_shift($chunks); // this could be null
        
        switch(strtoupper($_SERVER['REQUEST_METHOD']))
        {
            case 'POST':
                return new Rest_Request_Post($controller, $id, $_POST, $responseType);
            case 'HEAD':
            case 'GET':
                return new Rest_Request_Get($controller, $id, $_GET, $responseType);
            case 'DELETE':
                parse_str(file_get_contents("php://input"), $params);
                return new Rest_Request_Delete($controller, $id, $params, $responseType);
            case "PUT":
                parse_str(file_get_contents("php://input"), $params);
                return new Rest_Request_Put($controller, $id, $params, $responseType);
            default:
                return new Rest_Request_Bad(null, null, array(), $responseType);
        }
    }
    
    private static function getUri()
    {
        // controller or controller/id
        $uri = strtolower($_SERVER['REQUEST_URI']);
        $info = pathinfo($_SERVER['PHP_SELF']);
        $uri = str_replace($info['dirname'] . '/', '', $uri);
        
        // remove all possible $_GET data
        return preg_replace("#\?.*$#", '', $uri);
    }
}