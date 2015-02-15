<?php
namespace controllers;

use models\Model;
use models\TwitterAPI;

/**
 * Base Controller
 * 
 * 
 * @author Djordje Mancovic <dj.mancovic@gmail.com>
 */
class Controller {
    
    protected $model;
    protected $username;
    protected $password;
    protected $email;
    protected $error;
    protected $twitterName;
    protected $userData;
    protected $message;

    public $baseUrl;
    public $twitterAPI;
    public $logedIn;


    public function __construct () {
        $this->model = new Model();
        $this->baseUrl = "http://" . $_SERVER['SERVER_NAME'];
        $this->isUserLogged();
        $this->setUserData();
    }
    
    public function isUserLogged () {
        if (isset($_SESSION['user'])) {
            $this->logedIn = true;
        } else {
            $this->logedIn = false;
        }
    }
    
    protected function setUserData () {
        if (isset($_SESSION['user'])) {
            $this->userData = $_SESSION['user'];
        }
    }

    public function handleRequest() {
        if (!empty($_POST)) {
            $action = filter_input(INPUT_POST, 'action');
            $this->execute($action);
        } elseif (isset($_GET['p']) && $_GET['p'] === 'logout') { /* I am aware that the use of get methods to logout contain risk of xss attack */
            $this->logout();
        } else {
            $this->homePage();
        }
    }

    public function execute ($action = false) {
     	if ($action) {
            switch ($action) {
                case 'registration':
                    $this->username = isset($_POST['username']) ? trim(filter_input(INPUT_POST, 'username'))    : false;
                    $this->password = isset($_POST['password']) ? trim(filter_input(INPUT_POST, 'password'))    : false;
                    $this->email    = isset($_POST['email'])    ? trim(filter_input(INPUT_POST, 'email'))       : false;
                    
                    $this->createUser();
                    break;
                    
                case 'login':
                    $this->username = isset($_POST['username']) ? trim(filter_input(INPUT_POST, 'username')) : false;
                    $this->password = isset($_POST['password']) ? trim(filter_input(INPUT_POST, 'password')) : false;
                    
                    $this->login();
                    break;
                
                case 'first_feed';
                case 'second_feed';
                case 'third_feed';
                    $this->twitterName = isset($_POST['name']) ? trim(filter_input(INPUT_POST, 'name')) : false;
                    
                    $this->SaveTwitterName($action);
                    break;

                default : /* If the request is not recognized redirect to the home page. */
                    header("Location: " . $_SERVER['SERVER_NAME']);
                    die();
                    break;
            }

            $this->homePage();
     	} else {
            $this->homePage();
     	}
    }
     
    public function logout () {
        session_destroy();
        
        header("Location: " . $this->baseUrl);
        die();
    }
    
    public function homePage () {
        if (!$this->logedIn) {
            list($firstFeed, $secondFeed, $thirdFeed) = $this->genereteDefaultFeeds();
        } else {
            list($firstFeed, $secondFeed, $thirdFeed) = $this->generateUserFeeds();
        }
        
        include("views/home.php");
    }
    
    /* Generate default feed for unregistered users */
    protected function genereteDefaultFeeds () {
        $defaultTwitterAccounts = array('php_net', 'DragonBe', 'HelloSelf');
        $data = array();

        /* Fetch Twitter data for default accounts */
        foreach ($defaultTwitterAccounts as $account) {
            $this->twitterAPI = new TwitterAPI($account, 5);
            $twitterUser[] = $this->twitterAPI->getTwitterData();
        }

        /* Map only data that we need to multidimensional array */
        $count = 0;
        foreach ($twitterUser as $user) {
            $data[$count]['name'] = $defaultTwitterAccounts[$count];

            $postId = 0;
            foreach ($user as $tweet) {
                $data[$count]['tweets'][$postId]['created'] = $tweet->created_at;
                $data[$count]['tweets'][$postId]['text'] = $tweet->text;

                $postId++;
            }
            $count++;
        }

        $firstFeed = $data[0];
        $secondFeed = $data[1];
        $thirdFeed = $data[2];
        
        return array($firstFeed, $secondFeed, $thirdFeed);
    }
    
    /* Generate user feed for registered users */
    protected function generateUserFeeds () {
        $twitterAccounts = $this->model->getFeedsOfUser($this->userData['id']);
        
        $firstFeed  = false;
        $secondFeed = false;
        $thirdFeed  = false;

        /* Map Twitter data for each account in separate array */
        foreach ($twitterAccounts as $account) {
            switch ($account['category']) {
                case 'first_feed':
                    $this->twitterAPI = new TwitterAPI($account['name'], 5);
                    $twitterData = $this->twitterAPI->getTwitterData();
                    $firstFeed['name'] = $account['name'];
                    $firstFeed['tweets'] = array();

                    if ($twitterData) {
                        $count = 0;
                        foreach ($twitterData as $post) {
                            $firstFeed['tweets'][$count]['created'] = $post->created_at;
                            $firstFeed['tweets'][$count]['text'] = $post->text;

                            $count++;
                        }
                    }

                    break;

                case 'second_feed':
                    $this->twitterAPI = new TwitterAPI($account['name'], 5);
                    $twitterData = $this->twitterAPI->getTwitterData();
                    $secondFeed['name'] = $account['name'];
                    $secondFeed['tweets'] = array();

                    if ($twitterData) {
                        $count = 0;
                        foreach ($twitterData as $post) {
                            $secondFeed['tweets'][$count]['created'] = $post->created_at;
                            $secondFeed['tweets'][$count]['text'] = $post->text;

                            $count++;
                        }
                    }
                    break;

                case 'third_feed':
                    $this->twitterAPI = new TwitterAPI($account['name'], 5);
                    $twitterData = $this->twitterAPI->getTwitterData();
                    $thirdFeed['name'] = $account['name'];
                    $thirdFeed['tweets'] = array();

                    if ($twitterData) {
                        $count = 0;
                        foreach ($twitterData as $post) {
                            $thirdFeed['tweets'][$count]['created'] = $post->created_at;
                            $thirdFeed['tweets'][$count]['text'] = $post->text;

                            $count++;
                        }
                    }
                    break;
            }
        }

        return array($firstFeed, $secondFeed, $thirdFeed);
    }

    /* Store Twitter account name in database */
    protected function SaveTwitterName ($category = false) {
        if (isset($this->twitterName) && $this->twitterName != '' && $category) {
            $id = $this->model->storeTwitterName($this->twitterName, $this->userData['id'], $category);
            if ($id) {
                $this->message = "Twitter Name Saved";
            } else {
                $this->error = "Twitter Name NOT Saved";
            }
            return $id ? true : false;
        } else {
            $this->error = "Invalid Twitter name";
            return false;
        }
    }

    protected function login () {
        if ($this->validateCredentials()) {
            $user = $this->model->authenticateUser($this->username, $this->password);
            if ($user) {
                $_SESSION['user'] = $user;
                header("Location: " . $this->baseUrl);
                die();
            } else {
                $this->error = "Invalid User Credentials";
                return $this->error;
            }
        } else {
            return $this->error;
        }
     }

     protected function createUser () {
        if ($this->validateUsername() && $this->validatePassword() && $this->validateEmail()) {
            $saveUser = $this->model->saveUser(
                array(
                    'username' => $this->username,
                    'password' => $this->password, 
                    'email'    => $this->email
                )
            );
            
            if ($saveUser) {
                $this->message = "You have successfully registered";
            } else {
                $this->error = "You have successfully registered";
            }

            return $saveUser ? true : false;
        } else {
            return $this->error;
        }
    }
    
    /* Validate login credentials */
    private function validateCredentials () {
        if (!$this->username || $this->username == '' || !$this->password || $this->password == '') {
            $this->error = "Invalid User Credentials";
            return false;
        } else {
            return true;
        }
    }

    /* Validate username for registration. */
    protected function validateUsername () {
        if (!$this->username || $this->username == '') {
            $this->error = "Username missing";
            return false;
        } elseif (strlen($this->username) <= 3) {
            $this->error = "Username should have more than 3 characters";
            return false;
        } elseif ($this->model->checkUsername($this->username)) {
            $this->error = "Username already taken";
            return false;
        } else {
            return true;
        }
    }

    /* Validate password for registration. */
    protected function validatePassword () {
        if (!$this->password || $this->password == '') {
            $this->error = "Password missing";
            return false;
        } elseif (strlen($this->password) <= 5) {
            $this->error = "Password should have more than 5 characters";
            return false;
        } else {
            return true;
        }
    }

    /* Validate email for registration. */
    protected function validateEmail () {
        if (!$this->email || $this->email == '') {
            $this->error = "Email address missing";
            return false;
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->error = "Invalid email address";
            return false;
        } elseif ($this->model->checkEmail($this->email)) {
            $this->error = "Email address already taken";
            return false;
        } else {
            return true;
        }
    }
}