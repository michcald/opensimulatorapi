<?php

class Rest_Api
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'autoload'));
    }
    
    private static function autoload($className)
    {
        $fileName = str_replace('_', '/', $className) . '.php';

        if(is_file($fileName)) {
            include $fileName;
        }
    }
    
    public function process()
    {
        $request = Rest_Router::route();
        
        $response = Rest_Dispatcher::dispatch($request);
        
        echo $response;
    }
}