<?php

abstract class Rest_Command
{
    private $request = null;
    
    private $response = null;
    
    public final function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    /**
     *
     * @return Rest_Request
     */
    protected final function getRequest()
    {
        return $this->request;
    }
    
    /**
     *
     * @return Rest_Response
     */
    public final function getResponse()
    {
        return $this->response;
    }
    
    public function preAction() {}
    
    abstract public function get();
    
    abstract public function post();
    
    abstract public function put();
    
    abstract public function delete();
    
    public function postAction() {}
}