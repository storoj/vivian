<?php
/*
 * Registering admin modules and saving settings
 */
class AbstractModule extends AbstractPage {
    public $tableName = '';

    function __construct($arParams = array()) {
        if (!$this->checkTable()) {
            $creator = new DBCreator();
            $creator->createTable($this->tableName);
        }

        parent::__construct($arParams);
    }

    protected function checkTable() {
        if ($this->tableName) {
            $query = new DBQuery();
            $sql = "SHOW TABLES LIKE '".DBCreator::getAdminTableName($this->tableName)."'";
            $res = mysql_fetch_row($query->Query($sql));

            return $res[0] == DBCreator::getAdminTableName($this->tableName);
        }

        return false;
    }

}
