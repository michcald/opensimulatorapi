<?php

class Model_Users
{
    /**
     *
     * @var Lib_Db
     */
    private $db = null;
    
    private $fields = array();
    
    public function __construct()
    {
        $this->db = Lib_Registry::get('db');
        
        $tbl1 = $this->db->fetchCol('SHOW COLUMNS FROM auth');
        $tbl2 = $this->db->fetchCol('SHOW COLUMNS FROM useraccounts');
        $this->fields = array_merge($tbl1, $tbl2);
    }
    
    private function isField($field)
    {
        return array_search($field, $this->fields) !== false;
    }
    
    private function filterFields(array $fields)
    {
        $temp = array();
        
        foreach($fields as $f)
        {
            if($this->isField($f)) {
                $temp[] = $f;
            }
        }
        
        return (count($temp) > 0) ? implode(',', $temp) : '*';
    }
    
    public function getUser($id, $fields = null)
    {
        $user = array();
        
        $id = addslashes($id);
        
        $fields = explode(',', $fields);
        
        // information if he's online
        if(array_search('online', $fields) !== false) {
            $user['online'] = $this->db->fetchRow("SELECT * FROM presence WHERE UserID='$id' LIMIT 1");
        }
        
        // information about his friend
        if(array_search('friends', $fields) !== false) {
            $user['friends'] = $this->db->fetchAll("SELECT * FROM friends WHERE PrincipalID='$id'");
        }
        
        // information about the grid
        if(array_search('grid', $fields) !== false) {
            $user['grid'] = $this->db->fetchRow("SELECT * FROM griduser WHERE UserID='$id' LIMIT 1");
        }
        
        // information about the inventory
        if(array_search('inventory', $fields) !== false) {
            $user['inventory'] = $this->db->fetchAll("SELECT * FROM inventoryitems WHERE avatarID='$id'");
        }
        
        // information about the avatar
        if(array_search('avatar', $fields) !== false) {
            $user['avatar'] = $this->db->fetchAll("SELECT * FROM avatars WHERE PrincipalID='$id'");
        }
        
        // fetching user data
        $fields = $this->filterFields($fields);

        $user = $this->db->fetchRow(
                "SELECT $fields FROM auth,useraccounts WHERE UUID=PrincipalID AND UUID='$id'");
        
        if(!$user) {
            return false;
        }

        $user['UUID'] = $id;
        
        return $user;
    }
    
    public function getUsers($fields = null, $where = null, $order = null, $count = null, $onlyOnline = false)
    {
        // the table 'presence' is populated every time a user access into the world
        if($onlyOnline) {
            $sql = 'SELECT UUID FROM auth,useraccounts,presence WHERE UUID=PrincipalID AND UserID=UUID';
        } else {
            $sql = 'SELECT UUID FROM auth,useraccounts WHERE UUID=PrincipalID';
        }
        
        // where clause
        if($where) {
            $sql .= ' AND (' . $where . ') ';
        }
        
        if($order) {
            $sql .= " ORDER BY " . $order;
        }
        
        // how many results
        if($count && $count > 0) {
            $sql .= " LIMIT $count";
        }
        
        $usersId = $this->db->fetchCol($sql);
        
        $results = array();
        
        foreach($usersId as $id) {
            $results[] = $this->getUser($id, $fields);
        }
        
        return $results;
    }
    
    public function add($firstName, $lastName, $email, $password, $userLevel)
    {
        $uuid = Model_OpenSim::getRandomId();
        
        $passwordSalt = md5($uuid);
        
        $auth = array(
            'UUID' => $uuid,
            'passwordHash' => md5(md5($password) . ':' . $passwordSalt),
            'passwordSalt' => $passwordSalt,
            'webLoginKey' => '00000000-0000-0000-0000-000000000000'
        );
        
        $user = array(
            'PrincipalID' => $uuid,
            'ScopeID' => '00000000-0000-0000-0000-000000000000',
            'FirstName' => $firstName,
            'LastName' => $lastName,
            'Email' => $email,
            'Created' => (int)time(),
            'UserTitle' => 'Local User',
            'UserLevel' => (int)$userLevel
        );
        
        $this->db->insert('auth', $auth);
        $this->db->insert('useraccounts', $user);
        
        $rootFolderId = Model_OpenSim::getRandomId();
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'My Inventory',
            'type' => '9',
            'version' => '1',
            'folderID' => $rootFolderId,
            'agentID' => $uuid,
            'parentFolderID' => '00000000-0000-0000-0000-000000000000'
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Calling Cards',
            'type' => '2',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Body Parts',
            'type' => '13',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Objects',
            'type' => '6',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Animations',
            'type' => '20',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Photo Album',
            'type' => '15',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Landmarks',
            'type' => '3',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Clothing',
            'type' => '5',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Lost And Found',
            'type' => '16',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Sounds',
            'type' => '1',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Textures',
            'type' => '0',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Gestures',
            'type' => '21',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Notecards',
            'type' => '7',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Trash',
            'type' => '14',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        $this->db->insert('inventoryfolders', array(
            'folderName' => 'Scripts',
            'type' => '10',
            'version' => '1',
            'folderID' => Model_OpenSim::getRandomId(),
            'agentID' => $uuid,
            'parentFolderID' => $rootFolderId
        ));
        
        return $uuid;
    }
    
    public function edit($id, $firstName, $lastName, $email, $userLevel)
    {
        $user = $this->db->fetchRow("SELECT UUID FROM auth,useraccounts WHERE UUID=PrincipalID AND UUID='$id'");
        
        if(!$user) {
            return false;
        }
        
        $this->db->update('useraccounts', array(
            'FirstName' => $firstName,
            'LastName' => $lastName,
            'Email' => $email,
            'UserLevel' => $userLevel
        ), "PrincipalID=\"$id\"");
        
        return true;
    }
    
    public function editPassword($id, $password)
    {
        $passwordSalt = $this->db->fetchOne("SELECT passwordSalt FROM auth WHERE UUID=\"$id\"");
        
        if(!$passwordSalt) {
            return false;
        }

        $this->db->update('auth', array(
            'passwordHash' => md5(md5($password) . ':' . $passwordSalt)
        ), "UUID=\"$id\"");
    }
    
    public function delete($id)
    {
        $this->db->delete('auth', "UUID='$id'");
        $this->db->delete('useraccounts', "PrincipalID='$id'");
        $this->db->delete('griduser', "UserID='$id'");
        $this->db->delete('inventoryfolders', "agentID='$id'");
    }
}