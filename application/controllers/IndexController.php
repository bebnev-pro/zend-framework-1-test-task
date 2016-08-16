<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        
        // read credentials of db connection from mysql.ini file
        $mysql_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/mysql.ini', 'mysql');
        
        // instance of connection
        $this->db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'     => $mysql_config->mysql->host,
            'username' => $mysql_config->mysql->username,
            'password' => $mysql_config->mysql->password,
            'dbname'   => $mysql_config->mysql->dbname
        ));
        
        // catch connection errors and write out comments to user
        try {
            $this->db->getConnection();            
        } catch (Zend_Db_Adapter_Exception $e) {
            
            $errorCode = $e->getCode();
            
            if ($errorCode === 1045) {
                echo '<h2>Please, specify your MySQL properly credentials into application/configs/mysql.ini file</h2>';
            }
            
            if ($errorCode === 1049) {
                echo '<h2>Please, create database at your mysql server. SQL query is: </h2>CREATE DATABASE '.$mysql_config->mysql->dbname.' CHARACTER SET utf8 COLLATE utf8_general_ci';
            }
            
        } catch (Zend_Exception $e) {
            echo 'Zend_Exception<br/>';
            echo $e;
        }
        
        // creating table users - if not exists
        $sql = "CREATE TABLE IF NOT EXISTS `users` (
                    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `name` varchar(200) NOT NULL,
                    `registration_date` DATE NOT NULL,
                    `role` varchar(200) NOT NULL DEFAULT 'seeker',
                    `active` tinyint(1) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        
        $this->db->getConnection()->exec($sql);
        
        // model instance
        $this->user = new Application_Model_User();
        
    }

    public function indexAction()
    {
        // all users
        $this->view->users = $this->user->getUsers('all');
        
        // count of active users
        $this->view->activeUsers = count($this->user->getUsers('active'));
        
        // new users today
        $this->view->todayUsers = count($this->user->getUsers('today'));
        
        // new users of current week
        $this->view->weekUsers = count($this->user->getUsers('week'));
        
        // new users of current month
        $this->view->monthUsers = count($this->user->getUsers('month'));
    }

}

