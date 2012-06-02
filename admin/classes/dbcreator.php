<?php

class DBCreator {
    private static $site_table_prefix = DB_PREFIX;
    private static $admin_table_prefix = 'vivian_';
    private $db_engine =  'MyISAM';

    function __construct() {}

    public function createTable($table) {
        $templateName = $table . '.sql';
        $sql = $this->parseTemplate($templateName);

        $query = new DBQuery();
        $query->Query($sql);
    }

    private function parseTemplate($templateName) {
        $sql_setup = PATH_TEMPLATES_SQL . $templateName;
        if (file_exists($sql_setup)) {
            $sql = file_get_contents($sql_setup);
            $sql = str_replace('{db_prefix}', self::$site_table_prefix . self::$admin_table_prefix, $sql);
            $sql = str_replace('{db_engine}', $this->db_engine, $sql);

            return $sql;
        }

        return false;
    }

    public static function getAdminTableName($table_name) {
        return self::$site_table_prefix
            . self::$admin_table_prefix
            . $table_name;
    }
}
