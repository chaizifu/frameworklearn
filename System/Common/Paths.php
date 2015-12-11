<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * 配置框架的目录结构
 */
define('CACHE_DIR', 'Cache');//缓存目录
define('LOG_DIR', 'Log');//日志目录
define('CONFIG_DIR', 'Config');//配置目录
define('TEMPLETE_DIR', 'Templete');//视图目录
define('TPL_DIR', 'Tpl');//模板编译目录
define('MODULE_DIR', 'Modules');//模块目录
define('MODEL_DIR', 'Models');//数据模型目录
define('UPLOAD_DIR', 'Upload');//上传目录

define('CACHE_PATH', TEMP_PATH.'/'.CACHE_DIR);
define('LOG_PATH', TEMP_PATH.'/'.LOG_DIR);
define('TEMPLETE_PATH', APP_PATH.'/'.TEMPLETE_DIR);
if(!defined('MODULE_PATH')) define('MODULE_PATH', APP_PATH.'/'.MODULE_DIR);
define('MODEL_PATH', APP_PATH.'/'.MODEL_DIR);
define('TPL_PATH', TEMP_PATH.'/'.TPL_DIR);
define('CONFIG_PATH', APP_PATH.'/'.CONFIG_DIR);
define('UPLOAD_PATH', APP_PATH.'/'.UPLOAD_DIR);
