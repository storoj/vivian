<?php
/**
 * Debugger
 */

class Debugger {
    private $debug_data;
    private $start;
    private $end;
    public $state = '';
    public $enabled = true;

    function __construct() {
        $this->sql_dump = array();
    }

    public function getMicroTime() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    public function startDebug() {
        $this->start = $this->getMicroTime();
    }

    public function endDebug() {
        $this->end = $this->getMicroTime();
    }

    public function addRequest($sql, $time = 0, $result = NULL) {
        $this->debug_data[] = array('sql' => $sql, 'time' => ($time * 1000), 'result' => $result);
        // add time count
    }

    public function addClass($class) {
        $this->debug_data[] = array('class' => $class);
    }

    public function addError($error) {
        $this->debug_data[] = array('error' => $error);
    }

    public function showDebugInfo() {
	if(!isset($_COOKIE['gebug_panel_state'])) $_COOKIE['gebug_panel_state'] = '';
        $panel = file_get_contents(PATH_ROOT.'debugger/debugger.html');
        $panel = str_replace("{state}", $_COOKIE['gebug_panel_state'], $panel);
        $content = '<p class="debug_exec_time">Full execution time: <b class="debug_table">'.$this->getExecTime().'</b> msec</p>';
        foreach($this->debug_data as $el) {
            if (array_key_exists('sql', $el)) {
                // light up queries
                $request = preg_replace('/("[\w]*?")/', '<span class="debug_value">$1</span>', $el['sql']);
                $request = preg_replace('/(`[\w]*?`)/', '<span class="debug_table">$1</span>', $request);
                $content .= '<p><span class="debug_def">[Q]</span> :: '
                    .$request.'&nbsp;&nbsp;$$$&nbsp;&nbsp;exec time: '
                    .number_format($el['time'], 5).' msec &nbsp;&nbsp;$$$&nbsp;&nbsp;'.$el['result'].' rows</p>';
            } elseif (array_key_exists('class', $el)) {
                $content .= '<p><span class="debug_def">[C]</span> :: Loading class &gt;&gt; <span class="debug_class">'.$el['class'].'</span></p>';
            } elseif (array_key_exists('error', $el)) {
                $content .= '<p><span class="debug_def">[E]</span> :: Error occured &gt;&gt; <span class="debug_error">'.$el['error'].'</span></p>';
            }
        }

        return str_replace('%content%', $content, $panel);
    }

    public function getRawData() {
        $data = array();
        foreach($this->debug_data as $el){
            if (array_key_exists('sql', $el)) {
                // light up queries
                $request = preg_replace('/("[\w]*?")/', '<span class="debug_value">$1</span>', $el['sql']);
                $request = preg_replace('/(`[\w]*?`)/', '<span class="debug_table">$1</span>', $request);
                $data[] = '<p><span class="debug_def">[Q]</span> :: '
                    .$request.'&nbsp;&nbsp;$$$&nbsp;&nbsp;exec time: '
                    .number_format($el['time'], 5).' msec&nbsp;&nbsp;$$$&nbsp;&nbsp;'.$el['result'].' rows</p>';
            } elseif (array_key_exists('class', $el)) {
                $data[] = '<p><span class="debug_def">[C]</span> :: Loading class &gt;&gt; <span class="debug_class">'.$el['class'].'</span></p>';
            } elseif (array_key_exists('error', $el)) {
                $data[] = '<p><span class="debug_def">[E]</span> :: Error occured &gt;&gt; <span class="debug_error">'.$el['error'].'</span></p>';
            }

        }

        return $data;
    }

    public function getExecTime() {
        return number_format(($this->end - $this->start) * 1000, 5);
    }
}
