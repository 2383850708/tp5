<?php
namespace blog;
class Tree{
    private $OriginalList;
    public $pk;//主键字段名
    public $parentKey;//上级id字段名
    public $childrenKey;//用来存储子分类的数组key名children
    public $type;//是否在每个数组中加一个daa 属性的空数组  arr:加  notarr:不加
    
    function __construct($pk="id",$parentKey="pid",$childrenKey="children",$type='notarr'){
        if(!empty($pk) && !empty($parentKey) && !empty($childrenKey)){
            $this->pk=$pk;
            $this->parentKey=$parentKey;
            $this->childrenKey=$childrenKey;
            $this->type = $type;
        }else{
            return false;
        }
    
    }
    //载入初始数组
    function load($data){
        if(is_array($data)){
            $this->OriginalList=$data;
        }
    }
    
    /**
     * 生成嵌套格式的树形数组
     * array(..."children"=>array(..."children"=>array(...)))
     */
    function DeepTree($root=0){
        if(!$this->OriginalList){
            return FALSE;
        }

        $OriginalList=$this->OriginalList;
        $children = array();//存储父id与数组单元的引用关系
        $tree=array();//最终数组
        $refer=array();//存储主键与数组单元的引用关系
        //遍历
        $children = $this->getCilent($OriginalList);
        foreach($OriginalList as $k=>$v){

            if(!isset($v[$this->pk]) || !isset($v[$this->parentKey]) || isset($v[$this->childrenKey])){
                unset($OriginalList[$k]);
                continue;
            }
            $refer[$v[$this->pk]]=&$OriginalList[$k];//为每个数组成员建立引用关系
        }
        //print_r($refer);exit;
        //遍历2
       //print_r($OriginalList);exit;
        foreach($OriginalList as $k=>$v){
                 
            if($v[$this->parentKey]==$root)
            {//根分类直接添加引用到tree中

                if(!isset($children[$v[$this->pk]]) && $this->type=='arr')
                {
                    $OriginalList[$k]['data'] = array();
                }
                $tree[]=&$OriginalList[$k];
            }
            else
            {  
               
                if(isset($refer[$v[$this->parentKey]])){

                    $parent=&$refer[$v[$this->parentKey]];//获取父分类的引用
                    if(!isset($children[$v[$this->pk]]) && $this->type=='arr')
                    {
                        $OriginalList[$k]['data'] = array();
                    }
                    $parent[$this->childrenKey][]=&$OriginalList[$k];//在父分类的children中再添加一个引用成员
                    unset($OriginalList[$k]);
                }
                else
                {
                    $OriginalList[$k][$this->childrenKey] = [];
                }
               
            }
            
        }
        return $tree;
    }

    public function getCilent($OriginalList)
    {
        $refer=array();
        //遍历
        
        foreach($OriginalList as $k=>$v){

            if(!isset($v[$this->pk]) || !isset($v[$this->parentKey]) || isset($v[$this->childrenKey])){
                unset($OriginalList[$k]);
                continue;
            }
            $refer[$v[$this->parentKey]]=$OriginalList[$k];//为每个数组成员建立引用关系
        }
        return $refer;
        
    }
}
?> 