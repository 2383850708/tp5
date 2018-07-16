<?php 
namespace app\admin\controller;
use app\admin\common\controller\Backend;
class Dashboard extends Backend
{
	public function index()
	{
		return $this->fetch();
	}

	public function _empty($name)
    {
        echo $name.'方法不存在';
    }

	
}



 ?>