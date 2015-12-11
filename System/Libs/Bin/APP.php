<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * 项目处理类
 */
Class APP{
    
    static $module;//模块
    static $control;//控制器
    static $action;//动作

    static function run(){
        //配置自动加载类文件
        spl_autoload_register(array(__CLASS__, 'autoload'));
        //注册错误处理函数
        set_error_handler(array(__CLASS__, "error"));
        //注册异常处理函数
        set_exception_handler(array(__CLASS__, "exception"));
        //设置时区
        if(function_exists("date_default_timezone_set")){
            date_default_timezone_set(C("DATA_TIMEZONE_SET"));
        }
        //设置时区
        define("MAGIC_QUOTES_GPC", get_magic_quotes_gpc() ? true : false);
        //载入配置文件
        self::config();
        //调试开始
        if(C("DEBUG")){
            Debug::start('app_start');
        }
        self::init();
        if(C("DEBUG")){
            Debug::show('app_start', 'app_end');
        }
        Log::save();
    }
    
    //初始化配置
    static function init(){
        //调用路由器
        Url::parseUrl();
        $control = A(CONTROL);
        $action = ACTION;
        
        if(!method_exists($control, $action)){
            error("动作{$action}不存在!");
        }
        
        call_user_func(array(&$control, $action));
    }

    //初始化配置文件
    static function config(){
        $conf_file = CONFIG_PATH.'/Config.php';
        if(is_file($conf_file)){
            C(require $conf_file);
        }
    }

    //自动加载类文件
    static function autoload($classname){
        if((strpos($classname, C("CONTROL_FIX")) !== false)
                && ($classname != C("CONTROL_FIX"))){
            error("错误:控制器必须由A()函数创建,或者类没有创建!");
        }
        
        $classfile = PHP_PATH.'/Libs/Bin/'.$classname.C("CLASS_FIX").'.php';
        loadfile($classfile);
    }
    
    //错误处理函数
    static function error($errno, $errstr, $errfile, $errline){
		
        switch ($errno) {
            case E_ERROR:
            case E_USER_ERROR:
                $errmsg = "ERROR:[{$errno}]<strong>{$errstr}</strong>File:{$errfile}[{$errline}]";
                
                Log::write("[ERROR]:[{$errno}]<strong>{$errstr}</strong>File:{$errfile}[{$errline}]");
                
                error($errmsg);
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
            case E_USER_WARNING:
            default:
                $errmsg = "ERROR:[{$errno}]<strong>{$errstr}</strong>File:{$errfile}[{$errline}]";
                
                Log::set("[NOTICE]:[{$errno}]<strong>{$errstr}</strong>File:{$errfile}[{$errline}]", "NOTICE");
                
                notice(func_get_args());
                break;
        }
    }
    
    //异常处理函数
    static function exception($e){
        error($e->show());
    }
    
}

