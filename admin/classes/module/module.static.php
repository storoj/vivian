<?php

class StaticModule extends AbstractModule{
    public $list;
    public $tableName = 'vivian_static';

    public function action_index() {
        $query = new DBQuery();
        $this->list = $query
            ->table($this->tableName)
            ->getFields(array('id','url','title','text'))
            ->limit(20)
            ->fetchAll();
    }
}
