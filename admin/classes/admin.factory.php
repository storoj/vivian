<?php

class FactoryAdmin {

    public static function createObject($action, $query = array()) {
        if (class_exists($action.'Module')) {
            $name = $action.'Module';
            $obj = new $name($query);
        }

        return $obj;
    }
}
