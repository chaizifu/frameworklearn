<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * 日志处理类
 */
Class Log{
    static $log = array();
    
    //记录日志内容
    static public function set($message, $type = "NOTICE"){
        if(in_array($type, C("LOG_TYPE"))){
            $date = date('Y-m-d H:i:s');
            self::$log[] = $message."(".$date.")"."\r\n";
        }
    }
    
    //存储日志内容到日志文件
    static public function save($message_type = 3, $destination = null, $extra_headers = null){
        if(!C("LOG_START")) return;
        if(is_null($destination)){
            $destination = LOG_PATH.'/'.date('y_m_d').'.log';
        }
        if($message_type == 3){
            if(is_file($destination) && filesize($destination) > C("LOG_SIZE")){
                rename($destination, dirname($destination).'/'.  time().'.log');
            }
        }
        error_log(implode(',', self::$log), $message_type, $destination);
    }
    
    //直接写入日志内容到日志文件
    static public function write($message, $message_type = 3, $destination = null, $extra_headers = null){
        if(!C("LOG_START")) return;
        if(is_null($destination)){
            $destination = LOG_PATH.'/'.date('y_m_d').'.log';
        }
        if($message_type == 3){
            if(is_file($destination) && filesize($destination) > C("LOG_SIZE")){
                rename($destination, dirname($destination).'/'.  time().'.log');
            }
        }
        $date = date('Y-m-d H:i:s');
        $message = $message."(".$date.")"."\r\n";
        
        error_log($message, $message_type, $destination);
    }
}
