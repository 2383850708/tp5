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
		$count = $this->model->where($condition)->count();
		$result = collection($this->model->where($condition)->field('id,title,status')->select())->toArray();
		
		$data = array();
		$data['code'] = 0;
		$data['count'] = $count;
		$data['data'] = $result;
		$data['msg'] = '';
		return json($data);
	}

	public function add()
	{
		if($this->request->isAjax())
		{
			$data = $this->request->get("row/a");
			$group =  $this->request->get('group/a');

			if(empty($group))
			{
				return parent::returnJson('权限不能为空',0);
			}
			$data['rules'] = implode(',', $group);

			$result = $this->model->validate('AuthGroup')->save($data);
			
			if(false === $result)
			{
			    return parent::returnJson($this->model->getError(),0);
			}
			else
			{
				return parent::returnJson('分配成功',1);
			}
		}
		else
		{
			return $this->fetch();
		}
	}

	public function edit()
	{
		if($this->request->isAjax())
		{
			$data = $this->request->get("row/a");
			$group =  $this->request->get('group/a');

			if(empty($group))
			{
				return parent::returnJson('权限不能为空',0);
			}
			$data['rules'] = implode(',', $group);

			$result = $this->model->validate('AuthGroup')->save($data,['id'=>$data['id']]);
			if(false === $result)
			{
			    return parent::returnJson($this->model->getError(),0);
			}
			else
			{
				return parent::returnJson('分配成功',1);
			}
		}
		else
		{
			$id = input('get.id');
			$info =  $this->model->get($id)->toArray();
			$this->assign('info',$info);

			return $this->fetch();
		}
	}

	public function del($ids='')
	{
		if($ids)
		{
			$ids = json_decode(input('param.ids'));
			$res = $this->model->destroy($ids);
			if($res)
			{
				return parent::returnJson('删除成功',1);
			}
			else
			{
				return parent::returnJson('删除失败',0);
			}
		}
		else
		{
			return parent::returnJson('操作异常',0);
		}
	}

	public function jurisdiction()
	{
		$id = input('param.id');

		$result = collection(model('AuthRule')->field(array('id'=>'value','pid','title'))->where(['status'=>1])->order('weigh', 'desc')->select())->toArray();

		if(!empty($id))
		{
			$rules =  $this->model->where('id',$id)->value('rules');
			$rules = explode(',', $rules);

			foreach($result as $k=>$v)
			{
				if(in_array($v['value'], $rules))
				{
					$result[$k]['checked'] = true;
				}
			}
		}

		$tree = new Tree('value','pid','data','arr');
		$tree->load($result);
		$treelist=$tree->DeepTree();//所有分类树结构
		
		return json($treelist);
	}


	public function checkForm()
	{
		$data = array();
		//$data['status'] = 1;
		if(input('param.id'))
		{	
			$data['id'] = input('get.id');
		}

		if(input('param.title'))
		{
			$data['title'] = array('like','%'.input('param.title').'%');
		}
		return $data;
	}
}





 ?>