<?php

class PaginatorHelper extends TemplatifyHelper {

    public $current_page;
    public $total_page_num;

    function __construct($total, $current = 1) {

    	if(!is_numeric($current) || $current < 0){
    		$current = 1;
    	}
        $this->current_page     = $current;
        $this->total_page_num   = $total;

        if ($this->current_page < 1) $this->current_page = 1;
        if ($this->current_page > $this->total_page_num) $this->current_page = $this->total_page_num;
    }

}
