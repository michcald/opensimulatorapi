<?php

include 'bootstrap.php';

class Command_Prims extends Rest_Command
{
    /**
     *
     * @var Model_Prims
     */
    private $prims = null;
    
    public function preAction()
    {
        $this->prims = new Model_Prims();
    }
    
    public function get()
    {
        $fields = $this->getRequest()->getParam('fields', false);
        
        if($id = $this->getRequest()->getId())
        {
            $this->getResponse()->setBody($this->prims->getPrim($id, $fields));
            return;
        }
        
        $order = $this->getRequest()->getParam('order', false);
        $count = $this->getRequest()->getParam('count', false);
        
        $this->getResponse()->setBody($this->prims->getPrims($fields, $order, $count));
    }
    
    public function post()
    {
        $data = $this->getRequest()->getParams();
        
        $id = $this->prims->add($data);
        
        if(!$id)
        {
            $this->getResponse()->setBody(array(
                'status' => 'failed',
                'message' => 'Creator ID or Region ID wrong'
            ));
            return false;
        }
        
        $this->getResponse()->setBody(array(
            'status' => 'success',
            'message' => 'Prim created',
            'UUID' => $id
        ));
    }

    public function put()
    {
        
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
        
        $this->prims->delete(addslashes($id));
        
        $this->getResponse()->setBody(array(
            'status' => 'success',
            'message' => 'Prim deleted'
        ));
    }
}