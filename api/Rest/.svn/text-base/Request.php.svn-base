<?php

abstract class Rest_Request
{
    private $controller = null; // class which has to be istantiated
    
    private $id = null; // optional value (eg users/18)
    
    private $responseType = null; // json or xml
    
    private $params = null; // optional parameters
    
    public final function __construct($controller, $id, array $params, $responseType)
    {
        $this->controller = $controller;
        $this->id = $id;
        $this->params = $params;
        $this->responseType = $responseType;
    }
    
    // return post|get|put|delete
    public final function getMethod()
    {
        return strtolower(str_replace('Rest_Request_', '', __CLASS__));
    }
    
    public final function getController()
    {
        return $this->controller;
    }
    
    public final function getId()
    {
        return $this->id;
    }
    
    public final function getResponseType()
    {
        return $this->responseType;
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function getParam($key, $default = false)
    {
        return (array_key_exists($key, $this->params)) ? $this->params[$key] : $default;
    }
}