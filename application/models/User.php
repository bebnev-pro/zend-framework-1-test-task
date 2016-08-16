<?php

class Application_Model_User
{
    
    public 	$id,
                $name,
                $registration_date,
                $role,
                $active;
    
    public function __construct() {
        // read credentials of db connection from mysql.ini file
        $mysql_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/mysql.ini', 'mysql');
        
        // instance of connection
        $this->db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'     => $mysql_config->mysql->host,
            'username' => $mysql_config->mysql->username,
            'password' => $mysql_config->mysql->password,
            'dbname'   => $mysql_config->mysql->dbname
        ));
    }
    
    public function getUsers($param) {
        
        switch ($param) {
            case 'all':
                $sql = 'SELECT * FROM users';
                break;
            case 'active':
                $sql = "SELECT * FROM users WHERE active=1";
                break;
            case 'today':
                $sql = "SELECT * FROM users WHERE DATE(registration_date) = DATE(NOW())";
                break;
            case 'week':
                $sql = "SELECT * FROM users WHERE YEARWEEK(`registration_date`, 1) = YEARWEEK(CURDATE(), 1);";
                break;
            case 'month':
                $sql = "SELECT * FROM users WHERE YEAR(registration_date) = YEAR(NOW()) AND MONTH(registration_date) = MONTH(NOW());";
                break;
        }
        
        $users = $this->db->query($sql)->fetchAll();
        return $users;
    }
    
    public function getUser($id) {
        if (!empty($id)) {
            $sql = 'SELECT * FROM users WHERE id=' . $id;
            $user = $this->db->fetchAll($sql);
            return $user;
        }
    }
    
    public function deleteUser($id) {
        if (!empty(id)) {
            $this->db->delete('users', 'id = ' . $id);    
        } else {
            throw new Exception('Invalid id');
        }

    }
    
    
    // validate of active checkbox
    public function setActive($active) {
        if ($active == 'on') {
            $this->active = 1;
        } else {
            $this->active = 0;
        }
    }
    
    // validate of registration date
    public function setRegistrationDate($date) {
        $this->registration_date = date('Y-m-d', strtotime($date));
    }
    
    public function save($id) {
        
        $data = array(
            'name'   => $this->name,
            'registration_date' => $this->registration_date,
            'role' => $this->role,
            'active' => $this->active
        );
        
        if (empty($id)) {
            // insert to db
            $this->db->insert('users', $data);
        } else {
            // update existing row
            $this->db->update('users', $data, 'id='.$id);
        }
    }

}

