<?php

include 'bootstrap.php';

class Command_Regions extends Rest_Command
{
    /**
     *
     * @var Model_Users
     */
    private $regions = null;
    
    public function preAction()
    {
        $this->regions = new Model_Regions();
    }
    
    public function get()
    {
        $fields = $this->getRequest()->getParam('fields', false);
        
        if($id = $this->getRequest()->getId())
        {
            $this->getResponse()->setBody($this->regions->getRegion($id, $fields));
            return;
        }
        
        $order = $this->getRequest()->getParam('order', false);
        $count = $this->getRequest()->getParam('count', false);
        
        $this->getResponse()->setBody($this->regions->getRegions($fields, $order, $count));
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