<?php 
namespace app\admin\controller\auth;
use app\admin\common\controller\Backend;
use blog\Tree;
class Group extends Backend
{
	public function _initialize()
	{
		parent::_initialize();
		$this->model = model('AuthGroup');
	}

	public function index()
	{
		return $this->fetch();
		//parent::returnJson();
	}

	public function ajax_load_data()
	{
		/*$count = $this->model->where('status',1)->count();
		$result = collection($this->model->order('weigh', 'desc')->select())->toArray();
		$result = $this->getSubTree($result,0,0);

		$data = array();
		$data['code'] = 0;
		$data['count'] = $count;
		$data['data'] = $result;
		$data['msg'] = '';
		return json($data);*/
	}

	public function jurisdiction()
	{
		$result = collection(model('AuthRule')->field(array('id'=>'value','pid','title'))->where(['status'=>1])->order('weigh', 'desc')->select())->toArray();
		$tree = new Tree('value','pid','data','arr');
		$tree->load($result);
		$treelist=$tree->DeepTree();//所有分类树结构

		return json($treelist);
	}

	public function add()
	{
		if($this->request->isAjax())
		{
			$row = $this->request->get("row/a");
			$group =  $this->request->get('group/a');
			if(empty($group))
			{
				return parent::returnJson('权限不能为空',0);
			}
		}
		else
		{
			return $this->fetch();
		}
	}
}





 ?>