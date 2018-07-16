<?php 
namespace app\admin\controller;
use think\Controller;
class Err extends Controller
{
	public function auth()
	{
		return $this->fetch();
	}

	public function error()
	{
		return $this->fetch();
	}
}



 ?>