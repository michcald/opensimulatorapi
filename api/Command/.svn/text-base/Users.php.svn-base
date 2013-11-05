<?php

include 'bootstrap.php';

class Command_Users extends Rest_Command
{
    /**
     *
     * @var Model_Users
     */
    private $users = null;
    
    public function preAction()
    {
        $this->users = new Model_Users();
    }
    
    public function get()
    {
        if($id = $this->getRequest()->getId())
        {
            $this->getResponse()->setBody($this->users->getUser($id));
            return;
        }
        
        $fields = $this->getRequest()->getParam('fields', false);
        $where = $this->getRequest()->getParam('where', false);
        $order = $this->getRequest()->getParam('order', false);
        $count = $this->getRequest()->getParam('count', false);
        $onlyOnline = $this->getRequest()->getParam('online', false);
        
        $this->getResponse()->setBody($this->users->getUsers($fields, urldecode($where), $order, $count, $onlyOnline));
    }
    
    public function post()
    {
        $userLevel = (int)$this->getRequest()->getParam('user-level', false);
        $firstName = $this->getRequest()->getParam('first-name', false);
        $lastName = $this->getRequest()->getParam('last-name', false);
        $email = $this->getRequest()->getParam('email', false);
        $password = $this->getRequest()->getParam('password', false);
        
        if(!$firstName || !$lastName || !$email || !$password)
        {
            $this->getResponse()->setBody(array(
                'status' => 'failed',
                'message' => 'Required parameters: user-level,first-name,last-name,email,password'
            ));
            
            return;
        }
        
        $uuid = $this->users->add($firstName, $lastName, $email, $password, $userLevel);
        
        $this->getResponse()->setBody(array(
            'status' => 'success',
            'id' => $uuid
        ));
    }

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
        
        $firstName = $this->getRequest()->getParam('first-name', false);
        $lastName = $this->getRequest()->getParam('last-name', false);
        $email = $this->getRequest()->getParam('email', false);
        $userLevel = (int)$this->getRequest()->getParam('user-level', false);
        
        if(!$firstName || !$lastName || !$email)
        {
            $this->getResponse()->setBody(array(
                'status' => 'failed',
                'message' => 'Required parameters: first-name,last-name,email,user-level'
            ));
            
            return;
        }
        
        $this->users->edit($id, $firstName, $lastName, $email, $userLevel);
        
        $this->getResponse()->setBody(array(
            'status' => 'success',
            'message' => 'User updated'
        ));
    }
    
    public function delete()
    {
        if(!$id = $this->getRequest()->getId())
        {
            $this->getResponse()->setBody(array(
                'status' => 'failed',
                'message' => 'ID required'
            ));
            
            return;
        }
        
        $this->users->delete(addslashes($id));
        
        $this->getResponse()->setBody(array(
            'status' => 'success',
            'message' => 'User deleted'
        ));
    }
}