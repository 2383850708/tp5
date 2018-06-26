<?php 
namespace app\admin\controller\article;
use app\admin\common\controller\Backend;

class Article extends Backend
{
	public function index()
	{
		return $this->fetch();
	}

	public function add()
	{
		if($this->request->isAjax())
		{
			print_r($_REQUEST);exit;
		}
		else
		{
			$Category = new \blog\Category("Category",array('id','pid','title','fullname'));
			$categoryList = $Category->getList(array(),' scort desc ');
			$this->assign('list',$categoryList);
			return $this->fetch();
		}
		
	}

	
}









 ?>