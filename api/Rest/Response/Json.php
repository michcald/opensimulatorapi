<?php

class Rest_Response_Json extends Rest_Response
{
    protected $contentType = 'application/json';
    
    public function formatData($data)
    {
        return json_encode($data);
    }
}