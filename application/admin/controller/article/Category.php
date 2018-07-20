<?php 
namespace app\admin\controller\article;
use app\admin\common\controller\Backend;
use think\Db;
use blog\Category as tree;
use blog\Redis;
class Category extends Backend
{
	public function _initialize()
	{
		parent::_initialize();
		$this->model = model('Category');
		

	}

	public function _empty($name)
    {
        echo $name.'方法不存在';
    }

	public function index()
	{

		return $this->fetch();
	}

	public function ajax_load_data()
	{
		$condition = $this->checkForm();
		
		$Category = new tree("Category",array('id','pid','title','fullname'));
		if($Category)
		{
			foreach ($Category as $key => $value) 
			{
				$Category[$key]['createtime'] = date('Y-m-d',$value['createtime']);
			}
		}
		$categoryList = $Category->getList(array(),' scort desc ');

		$data = array();
		$data['code'] = 0;
		$data['status'] = 1;
		$data['count'] = 0;
		$data['data'] = $categoryList;
		$data['msg'] = '';

		return json($data);
	}

	public function add()
	{
		if($this->request->isAjax())
		{
			$data = input('param.');
			$result = $this->model->validate('Category')->save($data);
			if ($result === false)
            {
            	$msg = $this->model->getError();
                return parent::returnJson($msg,0);
            }
            else
            {

            	return parent::returnJson('添加成功',1);
            }
		}
		else
		{
			if(input('?get.id'))
			{
				$this->assign('id',input('param.id'));
			}
			else
			{
				$this->assign('id',0);
			}
				$Category = new tree("Category",array('id','pid','title','fullname'));
				$categoryList = $Category->getList(array(),' scort desc ');
				$this->assign('list',$categoryList);
				return $this->fetch();
			}
		
	}

	public function edit()
	{
		if($this->request->isAjax())
		{
			$data = input('param.');

			$result = $this->model->validate('Category')->save($data,['id'=>$data['id']]);
		
			if ($result === false)
            {
            	$msg = $this->model->getError();
                return parent::returnJson($msg,0);
            }
            else
            {
            	return parent::returnJson('修改成功',1);
            }
		}
		else
		{
			$id = input('get.id');
			$info =  $this->model->get($id)->toArray();
			$Category = new tree("Category",array('id','pid','title','fullname'));
			$categoryList = $Category->getList(array(),' scort desc ');
	
			$this->assign('info',$info);
			$this->assign('list',$categoryList);
			return $this->fetch();
		}
	}

	public function del($id='')
	{
		$id = input('param.id');

		if($id)
		{
			$info = $this->model->isCheckSubclass($id);
			if($info)
			{
				return parent::returnJson('有子类不能删除',0);
			}
			else
			{
				$res = $this->model->destroy($id);
				
				if($res)
				{
					return parent::returnJson('删除成功',1);
				}
				else
				{
					return parent::returnJson('删除失败',0);
				}
			}
		}
		else
		{
			return parent::returnJson('操作异常',0);
		}
	}

	public function getCategoryList()
	{
		$list = collection($this->model->field('id,title,pid')->order('scort', 'desc')->select())->toArray();
		$list = $this->generateTree($list,0,0);
		return $list;
	}

	public function checkForm()
	{
		$data = array();

		if(input('param.id'))
		{	
			$data['id'] = input('get.id');
		}

		return $data;
	}

	function generateTree($arr,$id,$step)
	{
	  static $tree=[];
	  foreach($arr as $key=>$val) 
	  {
	    if($val['pid'] == $id) 
	    {
	      $flg = str_repeat('└―',$step);
	      $val['title'] = $flg.$val['title'];
	      $tree[] = $val;
	      $this->generateTree($arr , $val['id'] ,$step+1);
	    }
	  }
	  return $tree;
	}

}









 ?>