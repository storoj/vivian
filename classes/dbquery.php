<?php

class DBQuery {

    private $tableName	= '';
    private $tableAlias = '';
    private $fields		= '';
    private $where		= '';
    private $order		= '';
    private $limit		= '';
    private $joins		= '';
    private $dbResult	= NULL;
    //private $sql		= '';
    public $sql = '';
    private $resultType = NULL;

    private $SQL_SELECT_TPL = array(
        'TPL_STRING'	=> 'SELECT {fields} FROM {tableName} {tableAlias} {joins} {where} {order} {limit}',
        'REQUIRED'		=> array('fields', 'tableName')
    );
    private $SQL_UPDATE_TPL = array(
        'TPL_STRING'	=> 'UPDATE {tableName} SET {fields} {where}',
        'REQUIRED'		=> array('tableName', 'fields')
    );
    private $SQL_DELETE_TPL = array(
        'TPL_STRING'	=> 'DELETE FROM {tableName} {where}',
        'REQUIRED'		=> array('tableName')
    );
    private $SQL_INSERT_TPL = array(
        'TPL_STRING'	=> 'INSERT INTO {tableName} SET {fields}',
        'REQUIRED'		=> array('tableName', 'fields')
    );

    private function wrapper($field){
        $field = str_replace('`', '', $field);

        $fields = explode('.', $field);
        foreach($fields as &$field){
            $field = '`'.$field.'`';
        }

        return implode('.', $fields);
    }

    private function filterValue($value){
        if($value === NULL){
            return 'NULL';
        }

        if(is_array($value)){
            $value = serialize($value);
        }

        if(!is_numeric($value)){
            return '"'.mysql_real_escape_string($value).'"';
        }

        return $value;
    }

    public function resultType($type){
        if(method_exists($type, 'fillData')){
            $this->resultType = $type;
        }
    }

    public function table($tableName){
        $this->tableName = $this->wrapper(DB::getInstance()->getFullTableName($tableName));
        $this->tableAlias = "AS " . $this->wrapper($tableName);
        return $this;
    }

    public function getFields($arFields){
        if(is_array($arFields)){
            foreach($arFields as $key => &$field){
                if ($field instanceof DBQuery) {
                    $field = "(".$field->getSQL($this->SQL_SELECT_TPL).")";
                } else {
                    $field = $this->wrapper($field);
                }
                if (!is_numeric($key)) {
                    $field .= 'AS '.$this->wrapper($key);
                }
            }
            $arFields = implode(', ', $arFields);
        }

        // raw string
        $this->fields = $arFields;
        return $this;
    }

    public function setFields($arFields){
        if (is_array($arFields)) {
            $result = array();
            foreach($arFields as $fieldName => $value){
                $fieldName = $this->wrapper($fieldName);
                $value = $this->filterValue($value);

                $result[] = $fieldName.'='.$value;
            }

            $this->fields = implode(', ', $result);
        } else {
            $this->fields = $arFields;
        }
        return $this;
    }

    public function where($where){
        $conditions = array('>' => '>', '<' => '<', '!' => '<>', '_' => 'IN');
        if(!empty($where)){
            if (is_array($where)) {
                $this->where = "WHERE ";
                $list = array();
                foreach($where as $field => $val) {
                    $sign = substr($field, 0, 1);
                    if (array_key_exists($sign, $conditions)) {
                        $field = substr($field, 1);
                        $sign = $conditions[$sign];
                    } else {
                        $sign = '=';
                    }

                    if ($sign == 'IN') {
                        $list[] = '(' . $this->wrapper($field) . ' '.$sign.' (' . $val . '))';
                    } else {
                        $list[] = '(' . $this->wrapper($field) . ' '.$sign.' "' . $val . '")';
                    }
                }
                $this->where .= implode(" AND ", $list);
            } else {
                $this->where = 'WHERE '.$where;
            }
        }

        return $this;
    }

    public function order($fieldName, $direction = 'ASC', $table = ''){
        if ($fieldName != 'RAND()') {
            if(!empty($table)){
                $fieldName = $table.'.'.$fieldName;
            }
            $orderStr = $this->wrapper($fieldName).' '.$direction;
        } else {
            $orderStr = $fieldName;
        }

        if(empty($this->order)){
            $this->order = 'ORDER BY '.$orderStr;
        } else {
            $this->order .= ', '.$orderStr;
        }

        return $this;
    }

    public function join($table, $on){
        if (is_string($table) && is_array($on)) {
            $this->joins .= "LEFT JOIN ".$this->wrapper(DB::getInstance()->getFullTableName($table))
                ." AS ".$this->wrapper($table);

            $this->joins .= " ON " . $this->wrapper($on[0]) . " = " . $this->wrapper($on[1]);
        }

        return $this;
    }

    public function limit($limit, $offset = 0){
        $this->limit = 'LIMIT '.$offset.','.$limit;
        return $this;
    }

    public function getSQL($sqlTemplate){
        foreach($sqlTemplate['REQUIRED'] as $placeholder){
            if(empty($this->$placeholder)){
                echo 'no '.$placeholder;
                return false;
            }
        }
        $sql = $sqlTemplate['TPL_STRING'];
        $toReplace = array('tableName', 'tableAlias', 'where', 'order', 'limit', 'joins', 'fields');

        foreach($toReplace as $replacer){
            $sql = str_replace('{'.$replacer.'}', $this->$replacer, $sql);
        }

        #var_dump($sql);
        return $sql;
    }

    public function Query($sql = NULL){
        //global $debugger;

        if (!is_null($sql)) {
            $this->sql = $sql;
        } elseif (empty($this->sql)){
            return $this;
        }
        //$debugger->addRequest($this->sql, 0);
        //print_r($this->sql);

        $this->dbResult = DB::getInstance()->query($this->sql);
        //return $this;
        return $this->dbResult;
    }

    public function Insert(){
        $this->sql = $this->getSQL($this->SQL_INSERT_TPL);
        $this->Query();

        if(!$this->dbResult){
            return false;
        }
        return mysql_insert_id();
    }

    public function Update(){
        $this->sql = $this->getSQL($this->SQL_UPDATE_TPL);
        $this->Query();

        return $this->dbResult != false;
    }

    public function Delete(){
        $this->sql = $this->getSQL($this->SQL_DELETE_TPL);
        $this->Query();

        return $this->dbResult != false;
    }

    public function fillData($row){
        if($this->resultType !== NULL){
            $className = $this->resultType;

            $obj = new $className();
            $obj->fillData($row);

            return $obj;
        }
        return $row;
    }

    public function fetch(){
        $this->sql = $this->getSQL($this->SQL_SELECT_TPL);
        
        $sqlHash = md5($this->sql);
        $cached = DB::getInstance()->getCached($sqlHash);
        
        if($cached !== false){
        	return $cached;
        }
        $this->Query();

        if(empty($this->dbResult)){
            return false;
        }
        $result = mysql_fetch_assoc($this->dbResult);
        $result = $this->fillData($result);
        
        DB::getInstance()->setCached($sqlHash, $result);
        return $result;
    }

    public function fetchAll() {
        $this->sql = $this->getSQL($this->SQL_SELECT_TPL);
        
        //$sqlHash = md5($this->sql);
        //$cached = DB::getInstance()->getCached($sqlHash);

        /*if($cached !== false){
        	return $cached;
        }*/
        
        $this->Query();

        if(empty($this->dbResult)){
            return false;
        }

        for($result = array(); $row = mysql_fetch_assoc($this->dbResult); $result[] = $this->fillData($row));
        //DB::getInstance()->setCached($sqlHash, $result, 3);
        return $result;
    }

    public function count($what = '*'){
        $oldFields = $this->fields;
        $oldResultType = $this->resultType;
        $oldLimit = $this->limit;

        $this->resultType = NULL;
        $this->limit = NULL;
        $this->getFields('COUNT('.$what.') as `count`');
        $data = $this->fetch();

        $this->fields = $oldFields;
        $this->resultType = $oldResultType;
        $this->limit = $oldLimit;

        if($data !== false){
            $data = $data['count'];
        }
        return $data;
    }

    public function increment($field, $num = 1) {
        $this->fields = $this->wrapper($field) . " = " . $this->wrapper($field) . " + " . $num;
        return $this;
    }

}

?>
