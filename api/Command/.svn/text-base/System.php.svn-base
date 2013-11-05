<?php

include 'bootstrap.php';

class Command_System extends Rest_Command
{
    public function get()
    {
        $cmd = $this->getRequest()->getParam('cmd', false);
        
        if(!$cmd)
        {
            $this->getResponse()->setBody(array(
                'status' => 'failed',
                'message' => 'cmd parameter is required (start|stop|reboot)'
            ));
            return;
        }
        
        switch($cmd)
        {
            case 'start':
                $res = exec("C:\\xampp\htdocs\inspectworld/rest/bin/opensim_start.bat");
                if($res == 'false')
                {
                    $this->getResponse()->setBody(array(
                        'status' => 'failed',
                        'message' => 'The system is already running!'
                    ));
                    return;
                }
                break;
            case 'stop':
                $res = exec("C:\\xampp\htdocs\inspectworld/rest/bin/opensim_stop.bat");
                if($res == 'false')
                {
                    $this->getResponse()->setBody(array(
                        'status' => 'failed',
                        'message' => 'The system is not running!'
                    ));
                    return;
                }
                break;
            case 'reboot':
                exec("C:\\xampp\htdocs\inspectworld/rest/bin/opensim_reboot.bat");
                break;
            default:
                $this->getResponse()->setBody(array(
                    'status' => 'failed',
                    'message' => 'cmd needs to be one of these: start|stop|reboot'
                ));
                return;
        }
        
        $this->getResponse()->setBody(array(
            'status' => 'success',
            'message' => 'Operation done'
        ));
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