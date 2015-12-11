<?php
defined('APPNAME') OR exit('No direct script access allowed');
/** 
 * 分页操作类
 * chaiwei
 * 2015-11-24 20:48:49
 */
Class Page{
    private $total_rows;//总记录数
    private $total_page;//总页数
    private $onepage_rows;//每页显示行数
    private $self_page;//当前页
    private $url;//url地址
    private $page_rows;//页码数量
    private $start_id;//当页起始ID
    private $end_id;//当页结束ID
    private $desc = array();
    
    //初始化配置
    public function __construct($total, $rows = 10, $page_rows = 8, $desc = array()) {
        $this->total_rows = $total;//总记录数
        $this->onepage_rows = $rows;//每页显示行数
        $this->page_rows = $page_rows;//页码数量
        $this->total_page = ceil($this->total_rows / $this->onepage_rows);//总页数
        $this->self_page = min($this->total_page, max((int)@$_GET['page'], 1));//当前页数
        $this->start_id = ($this->self_page - 1) * $this->onepage_rows + 1;//起始ID
        $this->end_id = min($this->total_rows, $this->self_page * $this->onepage_rows);//结束ID
        $this->url = $this->requestUrl();//配置URL地址
        $this->desc = $this->desc($desc);//文字描述
    }
    
    //配置URL地址
    private function requestUrl(){
        $url = isset($_SERVER['REQUEST_URI']) ?
                $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
        $requestArr = parse_url($url);
        
        if(isset($requestArr['query'])){
            parse_str($requestArr['query'], $arr);
            unset($arr['page']);
            
            $url = $requestArr['path'].'?'.http_build_query($arr).'&page=';
        }
        else{
            $url = strstr($url, '?') ? $url.'page=' : $url.'?page=';
        }
                
        return $url; 
    }
    
    //配置文字描述方法
    private function desc($desc){
        //默认文字描述
        $d = array(
            'pre' => '上一页',
            'next' => '下一页',
            'first' => '首页',
            'end' => '末页',
            'unit' => '条'
        );
        
        if(empty($desc) || !is_array($desc)){
            return $d;
        }
        
        function filter($v){
            return !empty($v);
        }
        
        return array_merge($d, array_filter($desc, 'filter'));
    }
    
    //limit
    public function limit(){
        return "LIMIT ".max(0, ($this->self_page - 1) * $this->onepage_rows).', '.$this->onepage_rows;
    }
    
    //上一页
    public function pre(){
        return $this->self_page > 1 ?
                "<a href='".$this->url.($this->self_page - 1)."'>".$this->desc['pre']."</a>" : '';
    }
    
    //下一页
    public function next(){
        return $this->self_page < $this->total_page ?
                "<a href='".$this->url.($this->self_page + 1)."'>".$this->desc['next']."</a>" : '';
    }
    
    //前几页
    public function pres(){
        $num = $this->self_page - $this->page_rows;
        return $this->self_page > $this->page_rows ? "<a href='{$this->url}{$num}'>前{$this->page_rows}页</a>" : '';
    }
    
    //后几页
    public function nexts(){
        $num = $this->self_page + $this->page_rows;
        return $this->self_page < $this->total_page - $this->page_rows ?
                "<a href='{$this->url}{$num}'>后{$this->page_rows}页</a>" : '';
    }
    
    //首页
    public function first(){
        return $this->self_page > 1 ?
                "<a href='".$this->url."1'>".$this->desc['first']."</a>" : '';
    }
    
    //尾页
    public function end(){
        return $this->self_page < $this->total_page ?
                "<a href='".$this->url.$this->total_page."'>".$this->desc['end']."</a>" : '';
    }
    
    //当前页的记录
    public function nowpage(){
        return "第".$this->start_id."{$this->desc['unit']} - {$this->end_id} {$this->desc['unit']}";
    }
    
    //返回当前页码
    public function selfnum(){
        return $this->self_page;
    }
    
    //统计数据信息
    public function count(){
        return "<span>总共有{$this->total_page}页,共有{$this->total_rows}条</span>";
    }
    
    //获得页码数组
    private function pagelist(){
        $pagelist = array();
        $start = max(1, min($this->self_page - ceil($this->page_rows / 2), $this->total_page - $this->page_rows));
        $end = $start + $this->page_rows;
        for($i = $start;$i <= $end;$i++){
            if($i == $this->self_page){
                $pagelist[$i]['url'] = '';
                $pagelist[$i]['str'] = $i;
                continue;
            }
            $pagelist[$i]['url'] = $this->url.$i;
            $pagelist[$i]['str'] = $i;
        }
        
        return $pagelist;
    }
    
    //字符串表示的页码列表
    public function strlist(){
        $arr = $this->pagelist();
        $pagelist = '';
        foreach ($arr as $v) {
            $pagelist .= empty($v['url']) ?
                    "<strong>".$v['str']."</strong>" : "<a href='{$v['url']}'>{$v['str']}</a>";
        }
        return $pagelist;
    }
    
    //展示
    public function show($id = 0){
        switch ($id) {
            case 1:
                return $this->pre().$this->strlist().$this->next();
                break;

            default:
                return $this->first().$this->pres().$this->pre().$this->strlist().$this->next().$this->nexts().$this->end();
                break;
        }
    }
    
}
