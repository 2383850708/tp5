<?php 
namespace app\admin\controller\miscellaneous;
use app\admin\common\controller\Backend;
use think\Db;

class Slide extends Backend
{
	public function index()
	{
		return $this->fetch();
	}

	public function ajax_load_data()
	{
		
	}

	public function add()
	{
		if($this->request->isAjax())
		{

		}
		else
		{
			return $this->fetch();
		}
	}

	public function upload()
	{
		
	}
}


 ?>