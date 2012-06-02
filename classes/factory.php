<?php

class Factory {

    public static function createObject($action, $query = array()) {
        // creating self (need to check whether we are authorized or not anyway)
        $user = User::getInstance();
        // check if auth data has been saved
        //print_r($_COOKIE);
        if (isset($_COOKIE['tc_auth'])) {
            $_SESSION['cookie'] = 'found';
            $user->restoreAuthFromCookie();
        }

        if (class_exists($action.'Page')) {
            // if class exists then we are watching common pages
            // or page of this user (self)
            $user->watch_me = true;

            // check access to chosen page
            // or loop self pages on profile if user type is not chosen (7)
            if (!self::checkPermission($action, $user)
                /*|| (intval($user->user_type) == 7 && $action != 'Auth')*/) {
                print_r('permission denied to '.$action);
                // if access is forbidden
                if ($user->is_logged) {
                    // logged users are redirected to profile page
                    $action = 'Profile';
                    $query  = array();
                } else {
                    // guests are redirected to Index
                    $action = 'Index';
                    $query  = array();
                }
            }

            $name = $action.'Page';
            $obj = new $name($query, $user);

        } elseif ($userItem = self::checkAlias(strtolower($action), $user)) {
            // TODO change to profile page
            // if just alias is given - go to the profile user page
            if (empty($query)) {
                $query = array('profile');
            }
            // first param becomes action
            $action = array_shift($query);
            // creating user page
            $name = ucfirst($action).'Page';
            $obj = new $name($query, $userItem);
        } else {
            // if url is wrong from the start throw to 404 page
            $obj = new NotFoundPage();
        }

        return $obj;
    }

    private static function checkPermission($action, &$user) {
        $forbidden_calls = array(
            // 0 - not authorized
            0 => array(),
            // 1 - authorized as designer
            1 => array('Orders'),
            // 2 - authorized as employer
            2 => array('Projects'),
            7 => array('Projects', 'Messages')
        );

        $type = intval($user->user_type);

        if (in_array($action, $forbidden_calls[$type])) {
            return false;
        }

        return true;
    }

    private static function checkAlias($alias, &$user) {
        // goes only for profile
        if (preg_match("#^profile(\d+)$#", $alias, $id)) {
            $alias = $id[1];
        }

        // if its my alias
        if (is_string($user->alias) && $user->alias == $alias
            || is_numeric($user->id) && $user->id == $alias) {

            $user->watch_me = true;
            return $user;
        }

        // trying to find user by alias
        if (is_numeric($alias)) {
            $where = array('id' => $alias);
        } else {
            $where = array('alias' => $alias);
        }

        $query = new DBQuery();
        $result = $query->table('users')
            ->getFields($user->required_fields)
            ->where($where)
            ->fetch();
        // TODO this query can become cachable

        if (is_array($result)) {
            // creating user object only when the user is found
            $user_item = new UserItem();
            // fill user data got from query
            foreach($user->required_fields as $el) {
                $user_item->$el = $result[$el];
            }

            return $user_item;
        }


        return false;
    }
}