<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * 核心配置文件
 */
return array(
    //系统配置
    "SHOW_TIME" => 1,//显示运行时间
    "DEBUG" => 1,//开启调试模式
    "NOTICE_SHOW" => 1,//是否开启提示性错误
    "DEBUG_TPL" => PHP_PATH.'/tpl/debug.tpl.php',//错误异常模板
    "ERROR_MESSAGE" => '页面出错啦!',//错误显示内容
    "DATA_TIMEZONE_SET" => 'PRC',//默认时区
    "FONT" => PHP_PATH.'/data/font/Tuffy.ttf',//字体
	//Mysql处理
	"MYSQL_HOST" => 'localhost',
	"MYSQL_USER" => 'root',
	"MYSQL_PWD" => 'root',
	"MYSQL_DB" => 'chaiwei',
    //图像水印处理
    "WATER_ON" => 1,//水印是否开启
    "WATER_TYPE" => 1,//1.图片水印 0.文字水印 
    "WATER_IMG" => PHP_PATH.'/data/water/water.png',//水印图片
    "WATER_POS" => 5,//水印位置
    "WATER_PCT" => 60,//水印透明度
    "WATER_QUALITY" => 80,//水印压缩比
    "WATER_TEXT" => 'ChaiZiFu',//水印文字
    "WATER_TEXT_COLOR" => '#000000',//水印文字颜色
    "WATER_TEXT_SIZE" => 13,//水印文字大小
    //缩略图处理
    "THUMB_ON" => 1,//是否开启缩略图
    "THUMB_PREFIX" => 'thumb_',//缩略图前缀
    "THUMB_ENDFIX" => '_thumb',//缩略图后缀
    "THUMB_TYPE" => 1,//生成缩略图方式
    //1.固定宽度,高度自增 2.固定高度,宽度自增 3.固定宽度,高度裁切 4.固定高度,宽度裁切
    "THUMB_WIDTH" => 200,//缩略图宽度
    "THUMB_HEIGHT" => 200,//缩略图高度
    "THUMB_PATH" => UPLOAD_PATH.'/img/'.date('Ymd'),//缩略图保存目录
    //验证码
    "CODE_STR" => '1234567890QWERTYUIOPLKJHGFDSAZXCVBNM',//验证码字符串
    "CODE_WIDTH" => 80,//验证码宽度
    "CODE_HEIGHT" => 25,//验证码高度
    "CODE_BG_COLOR" => '#DCDCDC',//验证码背景颜色
    "CODE_LEN" => 6,//验证码长度
    "CODE_FONT_SIZE" => 15,//验证码字体大小
    "CODE_FONT_COLOR" => '#000000',//验证码字体颜色
    "CODE_VAR" => 'code',//SESSION变量
    //PATHINFO
    "PATHINFO_DLI" => '/',//PATHINFO分隔符
    "PATHINFO_VAR" => 'q',//兼容模式GET变量
    "PATHINFO_HTML" => '.html',//伪静态扩展名
    //日志处理
    "LOG_START" => 1,//日志是否开启
    "LOG_TYPE" => array("SQL", "NOTICE", "ERROR"),//日志处理类型
    "LOG_SIZE" => 2000000,//日志文件大小
    //项目配置项
    "DEFAULT_MODULE" => "index",
    "DEFAULT_CONTROL" => "index",
    "DEFAULT_ACTION" => "index",
    
    "CONTROL_FIX" => "Control",
    "CLASS_FIX" => "",
	"MODEL_FIX" => "Model",
    //全局变量
    "VAR_MODULE" => 'm',
    "VAR_CONTROL" => 'c',
    "VAR_ACTION" => 'a'
);