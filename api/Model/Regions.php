<?php

class Model_Regions
{
    /**
     *
     * @var Lib_Db
     */
    private $db = null;
    
    public function __construct()
    {
        $this->db = Lib_Registry::get('db');
    }
    
    public function getRegion($id, $fields)
    {
        $region = $this->db->fetchRow(
                "SELECT * FROM regions,regionsettings WHERE uuid=regionUUID AND uuid='$id' LIMIT 1");
        
        if(!$region) {
            return null;
        }
        
        if(!$fields) {
            return $region;
        }
        
        $results = array('UUID' => $region['uuid']); // always returned
        
        foreach(explode(',', $fields) as $field)
        {
            if(array_key_exists($field, $region)) {
                $results[$field] = $region[$field];
            }
        }
        
        return $results;
    }
    
    public function getRegions($fields = null, $order = null, $count = null)
    {
        $sql = "SELECT uuid FROM regions";
        
        // order
        if($order)
        {
            $order = explode(',', $order);
            
            $orderFields = array();
            
            foreach($order as $o)
            {
                $field = trim(str_replace(array('ASC','DESC'), array('',''), $o));
                
                $orderFields[] = $o;
            }
            
            if(count($orderFields) > 0) {
                $sql .= " ORDER BY " . implode(',', $orderFields);
            }
        }
        
        // how many results
        if($count && $count > 0)
        {
            $sql .= " LIMIT $count";
        }
        
        $idList = $this->db->fetchCol($sql);
        
        $results = array();
        foreach($idList as $id)
        {
            if($r = $this->getRegion($id, $fields)) {
                $results[] = $r;
            }
        }
        
        return $results;
    }
}