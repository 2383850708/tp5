<?php 
namespace app\admin\controller\auth;
use app\admin\common\controller\Backend;
use think\Db;

class Rule extends Backend
{
	public function _initialize()
	{
		parent::_initialize();
		//$this->model = model('AuthRule');
	}

	public function index()
	{

		return $this->fetch();
	}

	public function add()
	{
		return $this->fetch();
	}

}











 ?>