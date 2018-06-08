<?php 
namespace app\admin\controller\auth;
use app\admin\common\controller\Backend;
use blog\Random;
use think\Db;
//use app\admin\model\Admin as AdminMOdel;


class Admin extends Backend
{
	public function _initialize()
	{
		parent::_initialize();
		$this->model = model('Admin');
	}

	public function index()
	{
		return $this->fetch();
	}

	public function ajax_load_data()
	{
		
		$condition = $this->checkForm();

		$len = input('param.limit');
		$page = input('param.page');
		$offset = ($page-1)*$len;

		$count = $this->model->where($condition)->count();
		
		$result = Db::name('admin')->where($condition)->limit($offset,$len)->select();
		
		if($result)
		{
			$result = $this->model->handleData($result);
		}

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
			$data=$this->request->get("row/a");
			$group = input('param.group');
			if(empty($group))
			{
				return parent::returnJson('请选择分组',0);
			}

			$salt = Random::alnum();//密码盐
			$data['salt'] = $salt;
			$data['avatar'] = '/assets/img/avatar.png';

			$result = $this->model->validate('Admin.add')->save($data);
			if ($result === false)
            {
            	$msg = $this->model->getError();
                return parent::returnJson($msg,0);
            }
            else
            {
            	$id = $this->model->getLastInsID();
            	$group = explode(',',$group);
            	foreach ($group as $key => $value) 
            	{
            		$data = array();
            		$data['uid'] = $id;
            		$data['group_id'] = $value;
            		Db::name('auth_group_access')->insert($data);
            	}

                return parent::returnJson('添加成功',1);
            }
		}
		else
		{
			$result = collection(model('authGroup')->field('id,title')->where('status',1)->select())->toArray();
			$this->assign('result',$result);
			return $this->fetch();
		}
		
	}

	/**
	 * 管理员修改
	 * @param    id 用户id
	 * @Author   wyk
	 * @DateTime 2018-05-30
	 * @return   json
	 */
	public function edit()
	{
		if($this->request->isAjax())
		{
			
			$data=$this->request->get("row/a");
			$id =  input('param.row.id');

			$group = input('param.group');
			
			if(empty($group))
			{
				return parent::returnJson('请选择分组',0);
			}
			$result = $this->model->validate('Admin.edit')->save($data,['id'=>$id]);
		
			if ($result === false)
            {
            	$msg = $this->model->getError();
                return parent::returnJson($msg,0);
            }
            else
            {
            	$group = explode(',',$group);
            	Db::name('auth_group_access')->where('uid',$id)->delete();
            
            	foreach ($group as $key => $value) 
            	{
            		$data = array();
            		$data['uid'] = $id;
            		$data['group_id'] = $value;
            		Db::name('auth_group_access')->insert($data);
            	}
                return parent::returnJson('添加成功',1);
            }
            return json($res);
		}
		else
		{
			$id = input('get.id');
			
			$info =  $this->model->get($id)->toArray();
			$result = collection(model('authGroup')->field('id,title')->where('status',1)->select())->toArray();
			$groups = Db::name('auth_group_access')->where('uid',$id)->select();
			$data = array();
			foreach ($groups as $key => $value) 
			{
				$data[] = $value['group_id'];
			}

			$this->assign('check',json_encode($data));
			$this->assign('result',$result);
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

	public function checkForm()
	{
		$data = array();

		if(input('param.id'))
		{	
			$data['id'] = input('get.id');
		}

		if(input('param.username'))
		{
			$data['username'] = array('like','%'.input('param.username').'%');
		}
		return $data;
	}

}



 ?>