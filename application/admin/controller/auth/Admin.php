<?php 
namespace app\admin\controller\auth;
use app\admin\common\controller\Backend;
use blog\Random;
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



}



 ?>