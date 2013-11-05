<?php

class Model_Prims
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
    
    public function getPrim($id, $fields)
    {
        $prim = $this->db->fetchRow(
                "SELECT * FROM prims,primshapes WHERE prims.UUID=primshapes.UUID AND prims.UUID='$id' LIMIT 1");
        
        if(!$fields) {
            return $prim;
        }
        
        $results = array(); // always returned
        $results['UUID'] = $prim['UUID'];
        
        foreach(explode(',', $fields) as $field)
        {
            if(array_key_exists($field, $prim)) {
                $results[$field] = $prim[$field];
            }
        }
        
        return $results;
    }
    
    public function getPrims($fields = null, $order = null, $count = null)
    {
        $sql = "SELECT UUID FROM prims";
        
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
        foreach($idList as $id) {
            $results[] = $this->getPrim($id, $fields);
        }
        
        return $results;
    }
    
    public function add(array $data)
    {
        if(!isset($data['CreatorID']) || !isset($data['RegionUUID'])) {
            return false;
        }
        
        $data['CreationDate'] = time();
        
        if(count($this->db->fetchOne("SELECT UUID FROM auth WHERE UUID='{$data['CreatorID']}'")) != 1) {
            return false;
        }
        
        $data['OwnerID'] = (isset($data['OwnerID'])) ? $data['OwnerID'] : $data['CreatorID'];
        $data['LastOwnerID'] = (isset($data['LastOwnerID'])) ? $data['LastOwnerID'] : $data['CreatorID'];
        
        if(count($this->db->fetchOne("SELECT uuid FROM regions WHERE uuid='{$data['RegionUUID']}'")) != 1) {
            return false;
        }
        
        $data['UUID'] = $data['SceneGroupID'] = Model_OpenSim::getRandomId();
        
        $primsData = array();
        $primsFields = $this->db->fetchAll('SHOW COLUMNS FROM prims');
        
        foreach($primsFields as $p)
        {
            $f = $p['Field'];
            if(array_key_exists($f, $data)) {
                $primsData[$f] = $data[$f];
            }
        }
        
        $shapeData = array();
        $shapeFields = $this->db->fetchAll('SHOW COLUMNS FROM primshapes');
        foreach($shapeFields as $p)
        {
            $f = $p['Field'];
            if(array_key_exists($f, $data)) {
                $shapeData[$f] = $data[$f];
            }
        }
        
        $this->db->insert('prims', $primsData);
        
        $this->db->insert('primshapes', $shapeData);
        
        return $data['UUID'];
    }
    
    public function delete($uuid)
    {
        $this->db->delete('prims', "UUID=\"$uuid\"");
        $this->db->delete('primshapes', "UUID=\"$uuid\"");
    }
}