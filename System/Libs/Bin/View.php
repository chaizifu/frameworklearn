<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * 视图处理类
 */
Final Class View extends ArrayObject{
	
	public $_request = array();


    public function __construct() {
        parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
    }
    
    public function render($file){
		//拆分一下$file，如果是xx/xx格式的话说明是跨模块调用视图文件
		//如果是xx格式的话为调用本模块视图文件
		$pathArr = explode('/', $file);
		if(count($pathArr) > 1){
			$f = MODULE_PATH.'/'.$pathArr[0].'/Views/'.$pathArr[1].'.php';
		}
		else{
			$f = MODULE_PATH.'/'.MODULE.'/Views/'.$file.'.php';
		}
		
        if(!empty($this->_request)){
            foreach($this->_request as $k => $v){
                $$k = $v;
             }
        }
        ob_start();
        include($f);
        echo ob_get_clean();
    }
    
    public function assign($k, $v){
        $this->_request[$k] = $v;
		return $this;
    }
	
}