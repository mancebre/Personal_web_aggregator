<?php
namespace models;

use models\MysqliDb;
/**
 * Base Model
 * 
 * 
 * @author Djordje Mancovic <dj.mancovic@gmail.com>
 */
class Model {

    protected $db;

    function __construct() {
        $this->db = new MysqliDb ('localhost', 'root', '', 'personal_web_aggregator');
    }
    
    public function storeTwitterName ($name, $id, $category) {
        $data = array (
            "name"      => $name,
            "category"  => $category,
            "user_id"   => $id,
            "date"      => date("Y-m-d")
        );
        
        $twitterNameId = $this->db->insert('twitter_names', $data);
        
        return $twitterNameId ? true : false;
    }
    
    public function getFeedsOfUser ($userId) {
        $this->db->where('user_id', $userId);
        $feeds = $this->db->get('twitter_names');
        
        return $feeds;
    }

    public function authenticateUser($username, $password) {
        $this->db->where('username', $username);
        $userData =  $this->db->get ("users");
        if (!password_verify($password, $userData[0]['password'])) {
            return false;
        }
        unset($userData[0]['password']);
        
        return $userData[0];
    }

    public function saveUser ($data) {
        $user = array (
            "username"  => $data['username'],
            "password"  => $data['password'],
            "email"     => $data['email'],
            "date"      => date("Y-m-d")
        );
        
        if (strlen(trim($data['password'])) > 0) {
            $user['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        
        $id = $this->db->insert('users', $user);
        
        return $id ? true : false;
    }

    public function checkUsername ($username = '') {
        $this->db->where('username', $username);
        $user = $this->db->get('users');
        
        return $user ? true : false;
    }

    public function checkEmail ($email = '') {
        $this->db->where('email', $email);
        $user = $this->db->get('users');
        
        return $user ? true : false;
    }
    
    public function getUser ($id = 0) {
        $cols = Array ("id", "username", "email", "date");

        $this->db->where('id', $id);
        $user =  $this->db->get ("users", null, $cols);
        
        return $user ? $user : false;
    }
}