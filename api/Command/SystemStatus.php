<?php

include 'bootstrap.php';

class Command_SystemStatus extends Rest_Command
{
    public function get()
    {
        $res = exec("C:\\xampp\htdocs\inspectworld/rest/bin/opensim_status.bat");
        
        if($res == 'true')
        {
            $this->getResponse()->setBody(array(
                'status' => 'success',
                'message' => 'running'
            ));
        }
        else
        {
            $this->getResponse()->setBody(array(
                'status' => 'success',
                'message' => 'not running'
            ));
        }
    }
    
    public function post()
    {
        
    }
    
    public function put()
    {
        
    }
    
    public function delete()
    {
        
    }
}