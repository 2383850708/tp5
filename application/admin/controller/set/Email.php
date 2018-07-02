<?php 
/**
 * 系统设置
 */
namespace app\admin\controller\set;
use app\admin\common\controller\Backend;

use think\Db;

class Email extends Backend
{
	public function _initialize()
	{
		parent::_initialize();
		$this->model = model('EmailSet');
	}

	public function index()
	{
		$info = $this->model->get(1);
		$this->assign('info',$info);
		return $this->fetch();
	}

	public function add()
	{
		$data = input('param.');
		$id = input('param.id');

		if($id!='')
		{
			$result = $this->model->validate('EmailSet')->save($data,['id'=>$id]);
		}
		else
		{
			$result = $this->model->validate('EmailSet')->save($data);
		}
		
		if ($result === false)
        {
        	$msg = $this->model->getError();
            return parent::returnJson($msg,0);
        }
        else
        {
        	if($id!='')
        	{
        		return parent::returnJson('修改成功',1);
        	}
        	return parent::returnJson('添加成功',1);
        }
	}
}

?>