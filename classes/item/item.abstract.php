<?php
abstract class AbstractItem extends TemplatifyHelper {

	protected $dbFields = array();				// list of existing fields in DB table
	protected $dbData = array();				// data from DB for existing item instance
	protected $dbUpdatedData = array();		// data to save in DB
	protected static $tableName = 'default';	// table name
	public    $isNew = true;					    // if the object is new or not
	protected $where = array();				// standart condition to save object

	public function __construct($where = NULL){
		foreach($this->dbFields as $field){
			$this->dbData[$field] = NULL;
		}
        if (is_array($where)) {
            $this->where = $where;
        } elseif (is_numeric($where)) {
            $this->where = array('id' => $where);
        } elseif($where !== NULL){
			$this->where = $where;
		}
	}

	public function Save(){
		//print_r($this->dbUpdatedData);

        if (!empty($this->dbUpdatedData)) {
            $dbQuery = new DBQuery();
            return $dbQuery
                ->table(self::getTableName())
                ->setFields($this->dbUpdatedData)
                ->where($this->where)
                ->Update();
        }
	}

    public function Insert() {
        $dbQuery = new DBQuery();
        $dbQuery
            ->table(self::getTableName())
            ->setFields($this->dbUpdatedData);
        if ($this->id = $dbQuery->Insert()){
            return $this->id;
        } else  {
            return false;
        }
    }

	public function Delete(){}

	public function Exists(){
		$dbQuery = new DBQuery();
		$dbQuery
			->table(self::getTableName())
			->getFields(array('id'))
			->where($this->where)
			->fetch();
	}

	public function getData($arFields = '*', $force = false){
		if(!$force){
			// TODO rm loaded fields from arFields here
            if ($arFields == '*') {
                $arFields = $this->dbFields;
            } elseif (is_array($arFields)) {
                foreach($arFields as $key => $el) {
                    if ($this->$el !== NULL) {
                        unset($arFields[$key]);
                    }
                }
            }
		}


        //if (!empty($arFields)) {
            $dbQuery = new DBQuery();
            $data = $dbQuery
                        ->table(self::getTableName())
                        ->getFields($arFields)
                        ->where($this->where)
                        ->fetch();

            if($data !== false){
                $this->isNew = false;
                $this->fillData($data);
                return $data;
            }
        //}

		return false;
	}

	// filling only allowed fields
	public function fillData($arData){
		foreach($this->dbFields as $field){
			if(isset($arData[$field])){
				$this->dbData[$field] = $arData[$field];
			}
		}
	}

	public static function Query(){
		$query = new DBQuery();
		$query
			->table(self::getTableName())
			->resultType(get_called_class());
		return $query;
	}

	public function __get($var){
		if(in_array($var, $this->dbFields)){
			return $this->dbData[$var];
		}

		if(property_exists($this, $var)){
			return $this->$var;
		}

		return NULL;
	}

	public function __set($var, $value){
		if(in_array($var, $this->dbFields)){
			if(is_null($this->dbData[$var]) || $this->dbData[$var] != $value){
				$this->dbUpdatedData[$var] = $value;
			}// else {
				#unset($this->dbUpdatedData[$var]);
			#}

			return $this->dbData[$var] = $value;
		}

		return $this->$var = $value;
	}

	public static function getTableName(){
		$class = get_called_class();
		return $class::$tableName;
	}

    public function wasChanged($field) {
        if (array_key_exists($field, $this->dbUpdatedData)){
            return $this->dbUpdatedData[$field];
        }

        return false;
    }


	// Debug functions
	public function showFields(){
		print_r($this->dbFields);
	}

	public function showFieldValues(){
		var_dump($this->dbData);
	}
}
?>