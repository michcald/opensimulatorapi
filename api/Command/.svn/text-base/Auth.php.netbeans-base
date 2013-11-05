<?php

include 'bootstrap.php';

class Command_Auth extends Rest_Command
{
    public function get()
    {
        $db = Lib_Registry::get('db');
        
        $email = $this->getRequest()->getParam('email', false);
        $password = $this->getRequest()->getParam('password', false);
        
        if(!$email || !$password)
        {
            $this->getResponse()->setBody(array(
                'status' => 'failed',
                'message' => 'Required parameters: ' . (!$email) ? 'email' : 'password'
            ));
            return;
        }
        
        
        // verify if the email exixts
        $res = $db->fetchRow(
                "SELECT UUID,passwordHash,passwordSalt " .
                "FROM auth,useraccounts " .
                "WHERE UUID=PrincipalID AND Email=\"" . addslashes($email) . "\"");
        
        if(count($res) == 0)
        {
            $this->getResponse()->setBody(array(
                'status' => 'failed',
                'message' => 'Invalid email address'
            ));
            return;
        }

        $currentPasswordHash = md5(md5($password) . ':' . $res['passwordSalt']);
        
        if($currentPasswordHash == $res['passwordHash'])
        {
            $this->getResponse()->setBody(array(
                'status' => 'success',
                'message' => 'Authenticated',
                'uuid' => $res['UUID']
            ));
        }
        else
        {
            $this->getResponse()->setBody(array(
                'status' => 'failed',
                'message' => 'Invalid password'
            ));
        }
    }
    
    public function post()
    {
        
    }
    
    // updating the password
    public function put()
    {
        if(!$id = $this->getRequest()->getId())
        {
            $this->getResponse()->setBody(array(
                'status' => 'failed',
                'message' => 'ID required'
            ));
            
            return;
        }
        
        $db = Lib_Registry::get('db');

        $password = $this->getRequest()->getParam('password', false);
        
        if(!$password)
        {
            $this->getResponse()->setBody(array(
                'status' => 'failed',
                'message' => 'Required parameter: password'
            ));
            return;
        }
        
        // verify if the account exists
        $res = $db->fetchRow("SELECT UUID,passwordHash,passwordSalt FROM auth WHERE UUID=\"$id\"");
        
        if(!$res)
        {
            $this->getResponse()->setBody(array(
                'status' => 'failed',
                'message' => 'Account not found'
            ));
            return;
        }

        Lib_Registry::get('db')->update('auth', array(
            'passwordHash' => md5(md5($password) . ':' . $res['passwordSalt'])
        ), "UUID=\"$id\"");
        
        $this->getResponse()->setBody(array(
            'status' => 'success',
            'message' => 'Password updated'
        ));
    }
    
    public function delete()
    {
        
    }
}