<?php

class User extends UserItem {
    public static $instance;
    public $required_fields = array('id','nickname','alias','user_type','pro','avatar_img');
    public $is_logged = false;
    public $watch_me = false;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new User();
        }

        return self::$instance;
    }

    function __construct() {
        if (isset($_SESSION['id'])) {
            parent::__construct($_SESSION['id']);
            $this->restoreUserInfo();
        } else {
            parent::__construct();
        }
    }

    public function restoreUserInfo() {
        foreach($this->required_fields as $el) {
            if (isset($_SESSION[$el])) $this->$el = $_SESSION[$el];
        }
        $this->is_logged = true;
    }

    public function refreshUserSession() {
        foreach($this->required_fields as $el) {
            if ($this->$el) {
                $_SESSION[$el] = $this->$el;
            }
        }
    }

    public static function getPasswordHash($password){
        return md5(md5($password) . 'TopCreatorSalt');
    }

    public function Save() {
        parent::Save();
        self::refreshUserSession();
    }

    public function logIn($email, $password, $save_me = false) {
        $user = self::getItem(
            $this->required_fields,
            array(
                'email' => $email,
                'password' => self::getPasswordHash($password)
            )
        )->fetch();

        if (empty($user['id'])){
            return false;
        }

        if (empty($user['alias'])) {
            $user['alias'] = 'profile' . $user['id'];
        }

        $this->fillData($user);
        $this->is_logged = true;

        $this->online = 1;
        //$this->setID($this->id);
        $this->Save();

        $this->refreshUserSession();

        // set cookies if needed (save_me)
        if ($save_me) {
            $this->saveUserAuth();
        }
    }

    private function saveUserAuth() {
        $user = self::getInstance();

        $ip = $_SERVER['REMOTE_ADDR'];
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $hash = md5(($user->id + 17) . $ip . $agent . 'TopCreator');

        $query = new DBQuery();
        $hash_saved = $query->table('user_control')
            ->where(array('user_id' => $user->id, 'hash' => $hash))
            ->count();

        if (!$hash_saved) {
            $query = new DBQuery();
            $new = $query->table('user_control')
                ->setFields(array('user_id' => $user->id, 'hash' => $hash, 'date' => time()))
                ->Insert();
        }

        $data = array('hash' => $hash, 'user' => ($user->id));
        if($hash_saved || $new) {
            // set cookie for 7 days
            setcookie('tc_auth', serialize($data), time() + 3600*24*7, '/');
        }
    }

    public function restoreAuthFromCookie() {
        $data = unserialize($_COOKIE['tc_auth']);
        $user_id = $data['user'];

        $ip = $_SERVER['REMOTE_ADDR'];
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $hash = md5(($user_id +17) . $ip . $agent . 'TopCreator');

        //print_r('trying restore!');

        if ($hash == $data['hash']) {
            $query = new DBQuery();
            $log_info = $query->table('user_control')
                ->getFields(array('user_id', 'hash', 'date'))
                ->where(array('user_id' => $user_id, 'hash' => $hash))
                ->fetch();

            if($log_info) {
                $user = self::getItem(
                    $this->required_fields,
                    array('id' => $user_id)
                )->fetch();

                if ($user) {
                    if (empty($user['alias'])) {
                        $user['alias'] = 'profile' . $user['id'];
                    }

                    $this->fillData($user);
                    $this->is_logged = true;
                    $this->refreshUserSession();
                }
            }
        }
    }

    public function logOut() {
        if (isset($_SESSION['id'])) {
            session_destroy();
        }

        setcookie('tc_auth', '', 0 ,'/');

        foreach($this->required_fields as $el) {
            $this->$el = NULL;
        }

        return true;
    }

    public static function generateSecurityCode($num = 16){
        $arr = array(
            'a','b','c','d','e','f',
            'g','h','i','j','k','l',
            'm','n','o','p','r','s',
            't','u','v','x','y','z',
            'A','B','C','D','E','F',
            'G','H','I','J','K','L',
            'M','N','O','P','R','S',
            'T','U','V','X','Y','Z',
            '1','2','3','4','5','6',
            '7','8','9','0');

        $pass = '';
        for($i = 0; $i < $num; $i++){
            $index = mt_rand(0, count($arr) - 1);
            $pass .= $arr[$index];
        }

        return $pass;
    }

    public static function checkPassStr($pass) {
        if (strlen($pass) < 6)
            return "Пароль слишком короткий. Минимальная длина пароля - 6 символов.";


        return 'ok';
    }

    /*public static function checkAvatar($path, $size = 42) {
        if ($path) {
            ($size == 42) ? $to_find = 'main' : $to_find = 'tmb'.$size;
            $src = '/' . FileManager::getResizedFileName($path, $to_find);
            if (!file_exists($_SERVER['DOCUMENT_ROOT'].$src)) {
                $src = '/img/avatars/default_'.$size.'.png';
            }
        } else {
            $src = '/img/avatars/default_'.$size.'.png';
        }

        return $src;
    }*/
}