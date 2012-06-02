<?php
/**
 * User class
 */

class UserItem extends AbstractItem{

	protected $dbFields = array('id', 'name');

	protected static $tableName = 'users';
	//protected $where = array();

	function __construct($id = NULL){
		$where = array();
		if($id !== NULL){
			if(is_numeric($id)){
				$where = array('id' => $id);
			}
		}
		parent::__construct($where);
	}

    public function __set($var, $value){
        if($var == 'id'){
            $this->where = array('id' => $value);
            $this->dbData['id'] = $value;
        }
        parent::__set($var, $value);
    }

    public function isMe() {
        $me = User::getInstance();
        if ($me->id == $this->id) {
            return true;
        }

        return false;
    }

    public static function getItem($data, $where) {
    	// should use self::getList(.... limit 1) and then return first item
    	// or maybe should implement AbstractItem::getItem
        $query = new DBQuery();
        return $query->table(self::$tableName)
            ->getFields($data)
            ->where($where)
            ->Query();
    }

    protected function setID($id) {
        $this->id = $id;
        $this->where = array('id' => $this->id);
    }
}