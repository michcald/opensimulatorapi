<?php

class Lib_Db
{
    private $adapter;
    
    private $host;

    private $username;

    private $password;

    private $dbname;
    
    /**
     *
     * @var PDO
     */
    private $db = null;

    public function __construct($adapter, $host, $username, $password, $dbname)
    {
        $this->adapter = strtolower($adapter);
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        
        $dsn = "{$this->adapter}:dbname={$this->dbname};host={$this->host}";
        
        try
        {
            $this->db = new PDO($dsn, $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            throw new Exception('Connection failed: ' . $e->getMessage());
        }
    }
    
    private function unescape($str)
    {
        return stripslashes($str);
        $search = array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"');
        $replace = array("\\", "\0", "\n", "\r", "\x1a", "'", '"');
        return str_replace($search, $replace, $str);
    }
    
    public function query($sql)
    {
        $sth = $this->db->prepare($sql);
        $sth->execute();
    }
    
    public function fetchAll($sql)
    {
        $args = func_get_args();
        array_shift($args);
        
        $sth = $this->db->prepare($sql);
        $sth->execute($args);
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($results as &$result)
        {
            foreach($result as $field => &$value) {
                $value = $this->unescape($value);
            }
        }
        
        return $results;
    }
    
    public final function fetchRow($sql)
    {
        $args = func_get_args();
        array_shift($args);
        
        $sth = $this->db->prepare($sql);
        $sth->execute($args);
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        if($row)
        {
            foreach($row as $field => &$value) {
                $value = $this->unescape($value);
            }
        }
        
        return $row;
    }
    
    public final function fetchCol($sql)
    {
        $args = func_get_args();
        array_shift($args);
        
        $sth = $this->db->prepare($sql);
        $sth->execute($args);
        
        $col = $sth->fetchAll(PDO::FETCH_COLUMN);
        
        foreach($col as &$value) {
            $value = $this->unescape($value);
        }
        
        return $col;
    }
    
    public final function fetchOne($sql, $args = null)
    {
        $args = func_get_args();
        array_shift($args);
        
        $sth = $this->db->prepare($sql);
        $sth->execute($args);
        $value =  $sth->fetchColumn();
        
        return $this->unescape($value);
    }
    
    public final function countRows($sql, $args = null)
    {
        $args = func_get_args();
        array_shift($args);
        
        $sth = $this->db->prepare($sql);
        $sth->execute($args);
        return $sth->rowCount();
    }
    
    public final function lastInsertId()
    {
        return $this->db->lastInsertId();
    }
    
    public final function insert($table, array $data)
    {
        $fields = '`' . implode('`,`', array_keys($data)) . '`';
        $values = array_values($data);
        
        $pattern = array();
        foreach($values as $value) {
            $pattern[] = '?';
        }
        $pattern = implode(',', $pattern);
        
	$sql = "INSERT INTO `$table` ($fields) VALUES ($pattern)";
        
        $insert = $this->db->prepare($sql);
        $insert->execute($values);
        
        return $this->lastInsertId();
    }

    public final function update($table, array $data, $where = null)
    {
        $fields = array_keys($data);
        $values = array_values($data);
        
        $set = array();
        foreach($fields as $field) {
            $set[] = "`$field`=?";
        }
        $set = implode(',', $set);
        
        $args = func_get_args();
        $whereValues = (count($args) > 3) ? array_slice($args, 3, count($args)) : array();
        
        $sql = "UPDATE `$table` SET $set";
        if($where) {
            $sql .= " WHERE $where";
        }
        $q = $this->db->prepare($sql);
        
        return $q->execute(array_merge($values, $whereValues));
    }
    
    public final function delete($table, $where)
    {
        $args = func_get_args();
        $whereValues = (count($args) > 2) ? array_slice($args, 2, count($args)) : array();
        
        $sql = "DELETE FROM `$table` WHERE $where";
        
        $q = $this->db->prepare($sql);
        return $q->execute($whereValues);
    }
}