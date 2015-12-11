<?php
/* 
 * chaiwei
 * 2015-11-25 23:08:49
 */

//计算运行时间
function run_time($start, $end = '', $decimial = 3){
    static $times = array();
    if($end){//有传递进来
        $times[$end] = microtime();
        return number_format($times[$end] - $times[$start], $decimial);
    }
    $times[$start] = microtime();
}

run_time('start');
//项目初始化
if(!defined('APP_PATH')){
	define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/Application');
}
//框架主目录
define('PHP_PATH', dirname(__FILE__));

//临时目录
define('TEMP_PATH', APP_PATH.'/Temp');

//加载编译文件
$runtime_file = TEMP_PATH.'/Runtime.php';

if(is_file($runtime_file)){
    require $runtime_file;
}
else{
    include PHP_PATH.'/Common/Runtime.php';
    runtime();
}

run_time('start', 'end');