<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * 控制器基类
 */
Class Control{
    
	public function __construct(){
		$this->load = O('View');
	}
	
}
