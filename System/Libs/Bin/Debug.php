<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * DEBUG类
 */
Class Debug{
    //运行时间
    static $runtime;
    //内存占用
    static $memory;
    //内存峰值
    static $memory_peak;
    
    //调试开始
    static function start($start){
        self::$runtime[$start] = microtime(TRUE);
        self::$memory[$start] = memory_get_usage();
        self::$memory_peak[$start] = memory_get_peak_usage();
    }
    
    //运行时间
    static function runtime($start, $end = '', $decimal = 4){
        if(!isset(self::$runtime[$start])){
            error("必须设置项目起点时间!");
        }
        
        if(empty(self::$runtime[$end])){
            self::$runtime[$end] = microtime(TRUE);
        }
        
        return number_format((self::$runtime[$end] - self::$runtime[$start]), $decimal);
    }
    
    //内存峰值占用
    static function memory_peak($start, $end = ''){
        if(!isset(self::$memory_peak[$start])){
            return false;
        }
        
        if(!empty($end)){
            self::$memory_peak[$end] = memory_get_peak_usage();
        }
        
        return max(self::$memory_peak[$start], self::$memory_peak[$end]);
    }
    
    //项目运行结果
    static function show($start, $end){
        $message = "项目运行时间: ".self::runtime($start, $end)."&nbsp;&nbsp;内存峰值: ".  number_format(self::memory_peak($start, $end) / 1024)."KB";
        $load_file_list = loadfile();
        $info = '';
        $i = 1;
        foreach ($load_file_list as $k => $v) {
            $info .= "[".$i++."]".$k."<br/>";
        }
        
        $e['info'] = $info.'<p>'.$message.'</p>';
        include C("DEBUG_TPL");
    }
    
}

