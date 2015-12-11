<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * ��ͼ������
 */
Final Class View extends ArrayObject{
	
	public $_request = array();


    public function __construct() {
        parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
    }
    
    public function render($file){
		//���һ��$file�������xx/xx��ʽ�Ļ�˵���ǿ�ģ�������ͼ�ļ�
		//�����xx��ʽ�Ļ�Ϊ���ñ�ģ����ͼ�ļ�
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