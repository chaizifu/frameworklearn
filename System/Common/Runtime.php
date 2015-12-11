<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * 生成编译文件
 */
function runtime(){
    $files = require PHP_PATH.'/Common/Files.php';
    foreach ($files as $v) {
        if(is_file($v)){
            require $v;
        }
    }
    mkdirs();
    //框架常规配置项
    C(require PHP_PATH.'/Libs/Etc/init.config.php');
    $data = '';
    foreach ($files as $v) {
        $data .= del_space($v);
    }
    $data = "<?php ".$data." C(require PHP_PATH.'/Libs/Etc/init.config.php');"." ?>";
    file_put_contents(TEMP_PATH.'/Runtime.php', $data);
    
    index_control();
	test_model();
	index_view();
}

//创建友好测试控制器
function index_control(){
    $index_dir = MODULE_PATH.'/Index/Controllers';
    $index_file = $index_dir.'/Index'.C("CONTROL_FIX").C("CLASS_FIX").'.php';
    if(!is_dir($index_dir)){
        mkdir($index_dir, 0777, true);
    }
    if(!is_file($index_file)){
        $data = <<<'str'
<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * Index控制器
 */
Class IndexControl extends Control{
    public function index(){
		$testModel = M('Test');
		$var = $testModel->show();
		$this->load->assign('var', $var)->render('Index');
    }
}
str;
        file_put_contents($index_file, $data);
    }
}

//创建友好测试数据模型
function test_model(){
	$model_dir = MODEL_PATH;
    $model_file = $model_dir.'/TestModel.php';
    if(!is_dir($model_dir)){
        mkdir($model_dir, 0777, true);
    }
	if(!is_file($model_file)){
		$data = <<<'str'
<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * Test模型
 */
Class TestModel{
	
	static $name = 'Chai';
	
    public function show(){
        return 'Hello Phper!';
    }
}
str;
		file_put_contents($model_file, $data);
	}
}

//创建友好测试视图文件
function index_view(){
	$index_dir = MODULE_PATH.'/Index/Views';
    $index_file = $index_dir.'/Index.php';
    if(!is_dir($index_dir)){
        mkdir($index_dir, 0777, true);
    }
    if(!is_file($index_file)){
        $data = <<<'str'
<?php defined('APPNAME') OR exit('No direct script access allowed');?>
<div style='border:2px solid #666;width:280px;height:40px;padding:30px 50px 10px;'>
	<h1 style='font-size:18px;color:#F00;'><?php echo $var;?></h1>
</div>
str;
		file_put_contents($index_file, $data);
	}
}

//创建环境目录
function mkdirs(){
    //判断目录是否存在
    if(!is_dir(TEMP_PATH)){
        @mkdir(TEMP_PATH, 0777, true);
    }
    //检测目录是否有写权限
    if(!is_writeable(TEMP_PATH)){
        error('目录没有写权限,程序无法运行!');
    }
    if(!is_dir(CACHE_PATH)) mkdir(CACHE_PATH, 0777, true);
    if(!is_dir(LOG_PATH)) mkdir(LOG_PATH, 0777, true);
    if(!is_dir(TEMPLETE_PATH)) mkdir(TEMPLETE_PATH, 0777, true);
    if(!is_dir(TPL_PATH)) mkdir(TPL_PATH, 0777, true);
    if(!is_dir(MODULE_PATH)) mkdir(MODULE_PATH, 0777, true);
	if(!is_dir(MODEL_PATH)) mkdir(MODEL_PATH, 0777, true);
    if(!is_dir(CONFIG_PATH)) mkdir(CONFIG_PATH, 0777, true);
    if(!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0777, true);
}