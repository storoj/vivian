<?php

abstract class TemplatifyHelper {
	protected $templateName = NULL;
	protected $result		= NULL;
    public $use_ajax = false;

	function templatify($templateName = NULL, $arParams = array()){
        global $debugger;
		ob_start();

		$className = get_called_class();
		if($templateName === NULL){
			$templateName = empty($this->templateName) ? $className : $this->templateName;
		}

		$templateFileName = $className.'/'.$templateName.'.php';

        #$debugger->addClass('trying: '.$templateFileName);


		if(!file_exists(PATH_TEMPLATES . $templateFileName)){
			$templateFileName = $templateName.'.php';

            //print_r('Loading: '.$templateFileName);

			if(!file_exists(PATH_TEMPLATES . $templateFileName)){
				ob_end_clean();
				return $this->result;
			    //throw new Exception('template '.$templateName.' not found!');
				#return false;
			}
		}

    	include(PATH_TEMPLATES . $templateFileName);
    	$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}
