<?php 
namespace app\admin\controller\auth;
use app\admin\common\controller\Backend;
use blog\Random;
use think\Db;
use app\admin\model\Admin as AdminMOdel;


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

	public function add()
	{
		if($this->request->isAjax())
		{
			$res = array();
			$data=$this->request->get("row/a");

			$salt = Random::alnum();//密码盐
			$data['salt'] = $salt;
			$data['avatar'] = '/assets/img/avatar.png';

			$result = $this->model->validate('Admin.add')->save($data);
			if ($result === false)
            {
            	$msg = $this->model->getError();
                $res['msg'] = $msg;
                $res['status'] = 0;
            }
            else
            {
            	$res['msg'] = '添加成功';
                $res['status'] = 1;
            }
            return json($res);
		}
	
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
			$res = array();
			
			$data=$this->request->get("row/a");
			$id =  input('param.row.id');
			$result = $this->model->validate('Admin.edit')->save($data,['id'=>$id]);
			
			if ($result === false)
            {
            	$msg = $this->model->getError();
                $res['msg'] = $msg;
                $res['status'] = 0;
            }
            else
            {
            	$res['msg'] = '修改成功';
                $res['status'] = 1;
            }
            return json($res);
		}
		else
		{
			$id = input('get.id');
			
			$info =  $this->model->get($id)->toArray();
			$this->assign('info',$info);
			$this->assign('id',$id);
			return $this->fetch();
		}
		
	}

	public function del($ids='')
	{
		$data = array();

		if($ids)
		{
			$ids = json_decode(input('param.ids'));
			$res = $this->model->destroy($ids);
			if($res)
			{
				$data['status'] = 1;
				$data['msg'] = '删除成功';
			}
			else
			{
				$data['status'] = 0;
				$data['msg'] = '删除失败';
			}
		}
		else
		{
			$data['status'] = 0;
			$data['msg'] = '操作异常';
		}
		return json($data);
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