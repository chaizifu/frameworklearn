<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * 公共函数文件
 */
 
//错误处理
function error($error){
    if(C("DEBUG")){
        if(!is_array($error)){
            $backtrace = debug_backtrace();
            $e['message'] = $error;
            $info = '';
            foreach ($backtrace as $v) {
                $file = isset($v['file']) ? $v['file'] : '';
                $line = isset($v['line']) ? "[".$v['line']."]" : '';
                $class = isset($v['class']) ? $v['class'] : '';
                $type = isset($v['type']) ? $v['type'] : '';
                $function = isset($v['function']) ? $v['function']."()" : '';
                $info .= $file.$line.$class.$type.$function.'<br/>';
            }
            $e['info'] = $info;
        }
        else{
            $e = $error;
        }
    }
    else{
        $e = C("ERROR_MESSAGE");
    }
	
    include C("DEBUG_TPL");
    exit();
}

//警告处理
function notice($e){
    if(C("DEBUG") && C("NOTICE_SHOW")){
        $time = number_format((microtime(TRUE) - debug::$runtime['app_start']), 4);

        $memory = memory_get_usage();
        $message = $e[1];
        $file = $e[2];
        $line = $e[3];

        $msg = "
            <h1 style='font-size:13px;background-color:#333;height:20px;line-height:1.8em;padding:5px;margin-top:20px;color:#FFF;width:890px;'>NOTICE : $message</h1>
            <div>
                <table style='border:1px solid #dcdcdc;width:900px;'>
                    <tr><td>Time</td><td>Memory</td><td>File</td><td>Line</td></tr>
                    <tr><td>$time</td><td>$memory</td><td>$file</td><td>$line</td></tr>
                </table>
            </div>";

        echo $msg;
    }
}

//载入文件
function loadfile($file = ''){
    static $fileArr = array();
    
    if(empty($file)){
        return $fileArr;
    }
    $filePath = realpath($file);
    if(isset($fileArr[$filePath])){
        return $fileArr[$filePath];
    }
    if(!is_file($filePath)){
        error("文件{$file}不存在");
    }
    require $filePath;
    $fileArr[$filePath] = true;
    return $fileArr[$filePath];
}

//生成唯一序列号
function _md5($var){
    return md5(serialize($var));
}

//实例化控制器
function A($control){
    if(strstr($control, '.')){
        $arr = explode('.', $control);
        $module = $arr[0];
        $control = $arr[1];
    }
    else{
        $module = MODULE;
    }
    
    static $_control = array();
    $control = $control.C("CONTROL_FIX");
    if(isset($_control[$control])){
        return $_control[$control];
    }
    $control_path = MODULE_PATH.'/'.$module.'/Controllers/'.$control.C('CLASS_FIX').'.php';
    loadfile($control_path);
    if(class_exists($control)){
        $_control[$control] = new $control();
        return $_control[$control];
    }
    else{
        return false;
    }
}

//实例化数据模型
function M($model){
	static $_model = array();
	
	$model = $model.C('MODEL_FIX');
    if(isset($_model[$model])){
        return $_model[$model];
    }
	$model_path = MODEL_PATH.'/'.$model.'.php';
    loadfile($model_path);
    if(class_exists($model)){
        $_model[$model] = new $model();
        return $_model[$model];
    }
    else{
        return false;
    }
}

//实例化对象或执行方法
function O($class, $method = null, $args = array()){
    static $result = array();
    $name = empty($args) ? $class.$method : $class.$method._md5($args);
    if(!isset($result[$name])){
        $obj = new $class();
        if(!is_null($method) && method_exists($obj, $method)){
            if(!empty($args)){
                $result[$name] = call_user_func_array(array(&$obj, $method), array($args));
            }
            else{
                $result[$name] = $obj->$method();
            }
        }
        else{
            $result[$name] = $obj;
        }
    }
    
    return $result[$name];
}

//配置文件处理
function C($name = null, $value = null){
    static $config = array();
    if(is_null($name)){//没传递进来name返回全部
        return $config;
    }
    if(is_string($name)){
        $name = strtolower($name);
        if(!strstr($name, '.')){
            if(is_null($value)){
                return isset($config[$name]) ? $config[$name] : null;
            }
            else{
                $config[$name] = $value;
                return;
            }
        }
        $name = explode('.', $name);
        if(is_null($value)){
            return isset($config[$name[0][1]]) ? $config[$name[0][1]] : null;
        }
        else{
            $config[$name[0][1]] = $value;
            return;
        }
    }
    if(is_array($name)){
        $config = array_merge($config, array_change_key_case($name));
        return true;
    }
}

//格式化内容 去空白
function del_space($file_name){
    $data = file_get_contents($file_name);
    $data = substr($data, 0, 5) == '<?php' ? substr($data, 5) : $data;
    $data = substr($data, -2) == '?>' ? substr($data, 0, -2) : $data;
    $preg_arr = array('/\/\*.*?\*\/\s*/is', '/\/\/.*?[\r\n]/is', '/(?!\w)\s?(?!\w)/is');
    return preg_replace($preg_arr, '', $data);
}

