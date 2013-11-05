<?php

// HMAC protocol

// in the db we can store also the url from where the request come
// in this way we filter also it

class Auth
{
    private static $instance = null;
    
    private $db = null;
    
    private $tableName = null;
    
    private $publicKeyFieldName = null;
    
    private $privateKeyFieldName = null;
    
    private final function __construct() {}
    
    /**
     *
     * @return Auth
     */
    public static final function getInstance()
    {
        if(self::$instance === null) {
            self::$instance = new Auth();
        }
        
        return self::$instance;
    }
    
    public final function setDb($dbHost, $dbUser, $dbPwd, $dbName)
    {
        $this->db = mysql_connect($dbHost, $dbUser, $dbPwd);
        mysql_select_db($dbName, $this->db);
    }
    
    public function setDbInfo($tableName, $publicKeyFieldName, $privateKeyFieldName)
    {
        $this->tableName = $tableName;
        $this->publicKeyFieldName = $publicKeyFieldName;
        $this->privateKeyFieldName = $privateKeyFieldName;
    }
    
    public final function authenticate()
    {
        // check the intevall between the request and the process of the request
        if(time() - $_SERVER['REQUEST_TIME'] > 60*5) {
            return false;
        }
        
        $publicKey = $_SERVER['HTTP_X_AUTH'];
        $clientHash = $_SERVER['HTTP_X_AUTH_HASH'];
        
        if(!$publicKey || !$clientHash) {
            return false;
        }

        $privateKey = $this->getPrimaryKeyFromDb($publicKey);
        
        if(!$privateKey) {
            return false;
        }

        $serverHash = hash_hmac('sha256', file_get_contents('php://input'), $privateKey);

        // validate the hash
        if($serverHash !== $clientHash) {
            return false;
        }

        return true;
    }
    
    private function getPrimaryKeyFromDb($publicKey)
    {
        $result = mysql_query("SELECT {$this->privateKeyFieldName} FROM {$this->tableName} WHERE {$this->publicKeyFieldName}=\"{$publicKey}\"", $this->db);
        $rows = mysql_fetch_assoc($result);
        
        // if the public key is not found or there are multiple rows matching
        if(!$rows || count($rows) == 0 || count($rows) > 1) {
            return false;
        }
        
        return $rows[0][$this->privateKeyFieldName];
    }
}