<?php 
namespace app\admin\controller\auth;
use app\admin\common\controller\Backend;
use think\Db;
use blog\Tree;

class Rule extends Backend
{
	public function _initialize()
	{
		parent::_initialize();
		$this->model = model('AuthRule');
	}

	public function index()
	{

		return $this->fetch();
	}

	public function add()
	{
		if($this->request->isAjax())
		{

			$res = array();
			$data=$this->request->get("row/a");
			 
			$result = $this->model->validate('AuthRule')->save($data);

			if(false === $result)
			{
			    $res['status'] = 0;
			    $res['msg'] = $this->model->getError();
			}
			else
			{
				$res['status'] = 1;
			    $res['msg'] = '添加成功';
			}
			return json($res);
		}
		else
		{
			$result = collection($this->model->field('id,pid,name,title')->where('status',1)->order('weigh', 'desc')->select())->toArray();
			$tree = new Tree();
			$tree->load($result);
			$treelist=$tree->DeepTree();//所有分类树结构
			$this->assign('treelist',$treelist);
			return $this->fetch();
		}
		
	}

}











 ?>