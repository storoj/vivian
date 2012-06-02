<?php

abstract class AbstractPage extends TemplatifyHelper {
	protected $_parent  = NULL;
    protected $param    = array();
    protected $data     = array();
    protected $status   = 'ok';
    protected $message  = '';
    public $page_title  = '';
    public $show_menu   = false;
    public $add_to_wrap = array();

	public function __construct($arParams = array(), $oParent = NULL){
		$this->_parent = $oParent;
		$className = get_called_class();

        if (isset($_POST['param'])) $this->param = $_POST['param'];
        if (isset($_POST['data'])) $this->data = $_POST['data'];

		$paramCount = count($arParams);
		for($i=$paramCount; $i>0; $i--){
			$methodName = implode('_', array_slice($arParams, 0, $i));
			$methodParams = array_slice($arParams, $i);
            //print_r('trying action: '.$methodName." - ".$methodParams);
			if($this->tryAction($methodName, $methodParams)){
				return;
			}
		}

		$this->tryAction('index', $arParams);
	}

    public function validate($arFields){
        foreach($arFields as $field){
            if(method_exists($this, 'validate_'.$field)){
                $var = isset($this->data[$field]) ? $this->data[$field] : NULL;
                $this->data[$field] = call_user_func_array(array($this, 'validate_'.$field), array($var));
            }
        }
    }

    private function tryAction($action, $params){
        $methodName = 'action_'.$action;
        if(method_exists($this, $methodName)){
            $this->templateName = $action;
            $this->result = call_user_func_array(array($this, $methodName), array($params));
            return true;
        }

        return false;
    }

    public function wrapAjax($arParams = array()){
        global $debugger;
        $debugger->enabled = false;

        $result = array(
                'content'	=> $this->templatify(),
                'param'	=> $this->param,
                'status' => $this->status
            );
        if (!empty($this->message))
            $result['msg'] = $this->message;

        if (USE_DEBUG) {
            $result['exec_time'] = '[{exec_time}]';
            $result['debug_info'] = $debugger->getRawData();
        }

        $result = array_merge($result, $arParams, $this->add_to_wrap);
        $this->use_ajax = true;
        $this->templateName = 'ajax';
        return $result;
    }

    public function setStatus($status, $msg = '') {
        $this->status = $status;
        $this->message = $msg;
    }
}
