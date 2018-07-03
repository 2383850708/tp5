<?php 
/**
 * 基本资料设置
 */
namespace app\admin\controller\set;
use app\admin\common\controller\Backend;

use think\Db;

class BasicInformation extends Backend
{
	public function _initialize()
	{
		parent::_initialize();
		$this->model = model('Admin');
	}

	public function index()
	{
		$info = parent::getUserInfo();
		$result = $this->model->get($info['id'])->toArray();
		$groups = collection(model('authGroup')->field('id,title')->where('status',1)->select())->toArray();
		$rules = Db::name('auth_group_access')->where('uid',$info['id'])->select();

		$data = array();
		foreach ($rules as $key => $value) 
		{
			$data[] = $value['group_id'];
		}

		$this->assign('check',json_encode($data));
		$this->assign('result',$result);
		$this->assign('groups',$groups);
		return $this->fetch();
	}

	public function edit()
	{
		$data=$this->request->post("row/a");
		$id =  input('param.row.id');
		
		$group = input('param.group');

		$result = $this->model->validate('Admin.edit_set')->save($data,['id'=>$id]);
	
		if ($result === false)
        {
        	$msg = $this->model->getError();
            return parent::returnJson($msg,0);
        }
        else
        {
        	if(!empty($group))
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
        	}
        	
            return parent::returnJson('修改成功',1);
        }
        return json($res);
	}

}


 ?>