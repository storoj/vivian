<?php

class DB {
	private static $instance;
	private $tablePrefix	= '';
	private $host			= 'localhost';
	private $user			= '';
	private $password		= '';
	private $dbName			= '';
	private $cache 			= false;

	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new DB;
		}

		return self::$instance;
	}
	
	public function tryCache(){
		if($this->cache !== false){
			return true;
		}
		if(!class_exists('Memcached')){
			return false;
		}
		$this->cache = new Memcached;
		return $this->cache->addServer('localhost', 11211);
	}
	
	public function getCached($key){
		if($this->tryCache()){
			return $this->cache->get($key);
		}
		return false;
	}
	
	public function setCached($key, $value, $timeout = 3){
		if($this->tryCache()){
			return $this->cache->set($key, $value, $timeout);
		}
		return false;
	}

	public function connect($host, $user, $password, $db, $prefix) {
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->dbName = $db;
		$this->tablePrefix = $prefix;

		if (mysql_connect($this->host, $this->user, $this->password) == false) {
			return false;
		}

		if (!mysql_select_db($this->dbName)) {
			return false;
		}

        mysql_query("set names utf8");

		$this->tablePrefix = $prefix;

		return true;
	}

	public function getFullTableName($tableName){
		return $this->tablePrefix . $tableName;
	}

	public function query($sql){
		if (USE_DEBUG) {
			global $debugger;
			$start = $debugger->getMicroTime();
		}

		$res = mysql_query($sql);

		if (USE_DEBUG) {
			$end = $debugger->getMicroTime();

            $result = @mysql_num_rows($res);
            if (!$result) {
                $result = @mysql_error();
            }
			$debugger->addRequest($sql, ($end - $start), $result);
		}

		return $res;
	}

	public function insertID(){
		return mysql_insert_id();
	}

}
