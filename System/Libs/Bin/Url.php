<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * 路由处理类
 */
Final Class Url{
    //保存pathinfo信息
    static $pathinfo;
    //解析URL
    static function parseUrl(){
        if(self::pathinfo() != false){
            $info = explode(C('PATHINFO_DLI'), self::$pathinfo);
            $get['m'] = $info[0];
            array_shift($info);
            $get['c'] = $info[0];
            array_shift($info);
            $get['a'] = $info[0];
            array_shift($info);
            
            $count = count($info);
            for($i = 0;$i < $count;$i += 2){
                $get[$info[$i]] = isset($info[$i + 1]) ? $info[$i + 1] : '';
            }
            $_GET = $get;
        }
        define('MODULE', isset($_GET['m']) ? $_GET['m'] : C('DEFAULT_MODULE'));
        define('CONTROL', isset($_GET['c']) ? $_GET['c'] : C('DEFAULT_CONTROL'));
        define('ACTION', isset($_GET['a']) ? $_GET['a'] : C('DEFAULT_ACTION'));
    }
    
    //解析PATHINFO
    static function pathinfo(){
        //获得pathinfo变量
        if(!empty($_GET[C('PATHINFO_VAR')])){
            $pathinfo = $_GET[C('PATHINFO_VAR')];
        }
        elseif(!empty($_SERVER['PATH_INFO'])){
            $pathinfo = $_SERVER['PATH_INFO'];
        }
        else{
            return false;
        }
        $pathinfo_html = '.'.trim(C('PATHINFO_HTML'), '.');
        self::$pathinfo = trim(str_ireplace($pathinfo_html, '', $pathinfo), '/');
        return true;
    }
    
    
}
