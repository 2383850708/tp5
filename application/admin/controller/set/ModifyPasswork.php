<?php 
/**
 * 修改密码
 */
namespace app\admin\controller\set;
use app\admin\common\controller\Backend;

use think\Db;

class ModifyPasswork extends Backend
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

	public function edit()
	{
		$data = input('param.');
		$user = parent::getUserInfo();
		$info = $this->model->get($user['id']);
	
		if(md5($data['oldPassword'])!=$info['password'])
		{
			return parent::returnJson('原始密码不正确',0);
		}

		if($data['password']=='')
		{
			return parent::returnJson('密码不能为空',0);
		}

		if($data['password']!=$data['repassword'])
		{
			return parent::returnJson('两次密码不相同',0);
		}

		$arr = array();
		$arr['password'] = $data['password'];
		$arr['id'] = $user['id'];

		$res = $this->model->save($arr,['id'=>$user['id']]);
		if($res)
		{
			return parent::returnJson('修改成功',1);
		}
		else
		{
			return parent::returnJson('修改失败',0);
		}
	}
}