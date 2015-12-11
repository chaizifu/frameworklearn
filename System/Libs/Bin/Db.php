<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * mysqli数据库操作类
 * chaiwei
 * 2015-11-22 18:57:35
 */
Class Db{
    //数据库链接
    protected $mysqli;
    //表名
    protected $table;
    //选项
    protected $opt;
    
    //主机
    public $host;
    //数据库账号
    public $user;
    //数据库密码
    public $passwd;
    //链接的数据库
    public $dbname;
    
    /* 
    * 构造方法
    */
    public function __construct($tab_name) {
        $this->config($tab_name);
		$this->host = C("MYSQL_HOST");
        $this->user = C("MYSQL_USER");
        $this->passwd = C("MYSQL_PWD");
		$this->dbname = C("MYSQL_DB");
    }
    
    /* 
    * 配置方法
    */
    protected function config($tab_name){
        $this->db = new mysqli($this->host, $this->user, $this->passwd, $this->dbname);
        if(mysqli_connect_errno()){
            echo "数据库连接错误:".mysqli_connect_errno();
            exit();
        }
        $this->db->query("set names 'utf-8'");
        $this->table = $tab_name;
        
        //初始化
        $this->opt['fields'] = '*';
        $this->opt['where'] = $this->opt['order'] = $this->opt['limit'] = $this->opt['group'] = '';
        
        $this->tbFields();
    }
    
    /* 
    * 获得表字段
    */
    public function tbFields(){
        $result = $this->db->query("DESC {$this->table}");
        $fieldArr = array();
        while(($row = $result->fetch_assoc()) != false){
            $fieldArr[] = $row['Field'];
        }
        $this->opt['fields'] = $fieldArr;
        $this->field($fieldArr);
    }
    
    /* 
    * 获得查询字段
    */
    protected function field($field){
        $fieldArr = is_string($field) ? explode(',', $field) : $field;
        if(is_array($fieldArr)){
            $field = '';
            foreach ($fieldArr as $v) {
                $field .= "`".$v."`,";
            }            
        }
        $this->opt['field'] = rtrim($field, ',');
        return $this->opt['field'];
    }
    
    /* 
    * 设置查询字段
    */
    public function setfield($field){
        $fieldArr = is_string($field) ? explode(',', $field) : $field;
        if(is_array($fieldArr)){
            $field = '';
            foreach ($fieldArr as $v) {
                $field .= "`".$v."`,";
            }            
        }
        $this->opt['field'] = rtrim($field, ',');
        return $this;
    }
    
    /* 
    * SQL条件方法
    */
    public function where($where){
        $this->opt['where'] = is_string($where) ? 'where '.$where : '';
        return $this;
    }
    
    /* 
    * LIMIT
    */
    public function limit($limit){
        $this->opt['limit'] = is_string($limit) ? 'limit '.$limit : '';
        return $this;
    }
    
    /* 
    * ORDER
    */
    public function order($order){
        $this->opt['order'] = is_string($order) ? 'order by '.$order : '';
        return $this;
    }
    
    /* 
    * GROUP
    */
    public function group($group){
        $this->opt['group'] = is_string($group) ? 'group by '.$group : '';
        return $this;
    }
    
    /* 
    * SELECT
    */
    public function select(){
        $sql = "select {$this->opt['field']} from {$this->table}"
            . " {$this->opt['where']} {$this->opt['group']}"
            . " {$this->opt['limit']} {$this->opt['order']}";
            
        return $this->sql($sql);
    }
    
    /* 
    * 返回错误
    */
    public function dbError(){
        return $this->db->error;
    }
    
    /* 
    * 发送SQL返回结果集
    */
    public function sql($sql){
        $result = $this->db->query($sql) or die($this->dbError());
        $resultArr = array();
        while (($row = $result->fetch_assoc()) != false) {
            $resultArr[] = $row;
        }
        return $resultArr;
    }
    
    /* 
    * DELETE语句
    */
    public function del($id = ''){
        if(empty($this->opt['where']) && $id == ''){
            die('查询条件不能为空!');
        }
        if($id != ''){
            if(is_array($id)){
                $id = implode(',', $id);
            }
            $this->opt['where'] = "where id in (".$id.")";
        }
        $sql = "delete from {$this->table} {$this->opt['where']} {$this->opt['limit']}";
        return $this->query($sql);
    }
    
    /* 
    * 没有结果集SQL
    */
    public function query($sql){
        $this->db->query($sql) or die($this->dbError());
        return $this->db->affected_rows;
    }
    
    /* 
    * 查找单条记录
    */
    public function find($id){
        $sql = "select {$this->opt['field']} from {$this->table} where `id` = {$id}";
        return $this->sql($sql);
    }
    
    /* 
    * 添加数据
    */
    public function insert($args){
        is_array($args) or die('参数非数组');
        
        $fields = $this->field(array_keys($args));
        $values = $this->values(array_values($args));
        
        $sql = "insert into {$this->table} ({$fields}) values ({$values})";
        if($this->query($sql) > 0){
            return $this->db->insert_id;
        }
        return false;
    }
    
    /* 
    * 更新数据
    */
    public function update($args){
        is_array($args) or die('参数非数组!');
        if(empty($this->opt['where'])){
            die('条件不能为空!');
        }
        
        $set = '';
        $gpc = get_magic_quotes_gpc();
        while (list($k, $v) = each($args)){
            $v = !$gpc ? addslashes($v) : $v;
            
            $set .= "`{$k}` = '{$v}',";
        }
        $set = rtrim($set, ',');
        
        $sql = "update {$this->table} set {$set} {$this->opt['where']}";
        
        return $this->query($sql);
    }
    
    /* 
    * 数据数组转为字符串格式,同时进行转义
    */
    protected function values($value){
        $str = '';
        
        if(!get_magic_quotes_gpc()){
            foreach ($value as $v) {
                $str .= "'".  addslashes($v)."',";
            }
        }
        else{
            foreach ($value as $v) {
                $str .= "'$v',";
            }
        }
        
        return rtrim($str, ',');
    }
}
