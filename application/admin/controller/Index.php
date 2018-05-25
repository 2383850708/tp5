<?php 
namespace app\admin\controller;
use app\admin\common\controller\Backend;
class Index extends Backend
{
	public function index()
	{
		return $this->fetch();
	}
}



 ?>