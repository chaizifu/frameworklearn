<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * 异常处理类
 */
Class Excep extends Exception{
    public function __construct($message = "", $code = 0) {
        parent::__construct($message, $code);
    }
    
    //显示异常
    public function show(){
        $trace = $this->getTrace();
        $error['message'] = "Message:".$this->message;
        $error['message'].= "<br/>File:".$this->file."[".$this->line."]";
        $error['message'].= "<br/>".$trace[0]['class'];
        $error['message'].= $trace[0]['type'];
        $error['message'].= $trace[0]['function']."()";
        array_shift($trace);
        $info = '';
        foreach ($trace as $v) {
            $class = isset($v['class']) ? $v['class'] : '';
            $type = isset($v['type']) ? $v['type'] : '';
            $file = isset($v['file']) ? $v['file'] : '';
            $info .= $file."\t".$class.$type.$v['function']."<br/>";
        }
        $error['info'] = $info;
        
        Log::write($error['message']);
        
        return $error;
    }
    
}

