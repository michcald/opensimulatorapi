<?php

class OpenSimSdk
{
    private $baseUrl = null;
    
    private $publicKey = null;
    
    private $privateKey = null;
    
    private $responseContentType = 'application/json'; // What kind of content we’ll accept as a response
    
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }
    
    public function setAuth($publicKey, $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }
    
    public function setResponseContentType($contentType)
    {
        $this->responseContentType = $contentType;
    }
    
    public function get($url, $params = array())
    {
        if(count($params) > 0) {
            $url .= '?' . http_build_query($params);
        }
        
        return $this->execute('GET', $url, $params);
    }
    
    public function post($url, $params = array())
    {
        return $this->execute('POST', $url, $params);
    }
    
    public function put($url, $params = array())
    {
        return $this->execute('PUT', $url, $params);
    }
    
    public function delete($url, $params = array())
    {
        return $this->execute('DELETE', $url, $params);
    }
    
    private function execute($method, $url, array $params)
    {
        $request = http_build_query($params);
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/' . $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $header = array();
        
        $header[] = 'Accept: ' . $this->responseContentType;
        
        // sending the authentification data
        if($this->publicKey && $this->privateKey)
        {
            $header[] = 'X-Auth: ' . $this->publicKey;
            $header[] = 'X-Auth-Hash: ' . hash_hmac('sha256', $request, $this->privateKey);
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        
        switch($method)
        {
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                curl_setopt($ch, CURLOPT_POST, 1);
                break;
            case 'PUT':
                /* Prepare the data for HTTP PUT. */  
                $putData = tmpfile();
                // $putData = fopen('php://memory', 'rw');
                fwrite($putData, $request);
                fseek($putData, 0);
                /* Set cURL options. */ 
                curl_setopt($ch, CURLOPT_PUT, true);
                curl_setopt($ch, CURLOPT_INFILE, $putData);
                curl_setopt($ch, CURLOPT_INFILESIZE, strlen($request));
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        
        $response = curl_exec($ch);
	//$this->responseInfo = curl_getinfo($curlHandle);

        curl_close($ch);
        
        if($method == 'PUT') {
            fclose($putData); 
        }
        //echo '<pre>' . print_r(json_decode($response), true) . '</pre>';die();

        return $response;
    }
}