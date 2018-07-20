<?php
namespace app\home\controller;
use app\home\common\controller\Backend;
use think\Db;
use app\admin\model\Article;
class Index extends Backend
{

	public function _initialize()
	{
		parent::_initialize();
		
	}

    public function index()
    {

    	$article = new Article();
    	$list = collection($article->all())->toArray();

    	$getcategoryName = array();
    	$category = Db::name('category')->select();
    	foreach($category as $k=>$v)
    	{
    		$getcategoryName[$v['id']] = $v['title'];
    	}

    	$this->assign('list',$list);
 
    	$this->assign('getcategoryName',$getcategoryName);
        return $this->fetch();
    }
}
