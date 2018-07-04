<?php 
namespace app\admin\controller;
use app\admin\common\controller\Backend;
use think\Db;
class Index extends Backend
{
	public function index()
	{
		return $this->fetch();
	}

	public function menu()
	{
		$data = $this->tree();
		\think\Config::set('default_return_type','json');
		if($data)
		{
			return ['data'=>$data,'status'=>1,'message'=>'操作完成'];
		}
		else
		{
			return ['data'=>null,'status'=>0,'message'=>'没有权限'];
		}
		
	}

	public function rules()
	{
		$list = Db::name('auth_rule')->field('id,pid,title,icon')->where(['status'=>1,'type'=>'menu'])->order('weigh desc')->select();
		return $list;
	}

	public function groups()
	{
		$userInfo = parent::getUserInfo();

		$info = Db::name('auth_group')
		->field('rules')
		->where('id','IN',function($query) use ($userInfo){
		    $query->name('auth_group_access')->where('uid='.$userInfo['id'])->field('group_id');
		})
		->select();
		if($info)
		{
			$arr = '';
			foreach ($info as $key => $value) 
			{
				$arr.=$value['rules'].',';
			}
			$arr = array_unique(explode(',',rtrim($arr,','))); 
			return $arr;
		}
		return null;
	}

	public function check()
	{
		$rules = $this->rules();
		$my_rules = $this->groups();
		foreach ($rules as $key => $value) 
		{
			if(!in_array($value['id'],$my_rules))
			{
				unset($rules[$key]);
			}
		}
		return $rules;
	}

	public function tree()
	{
		$reles = $this->check();

		$tree = new \blog\Tree();
		$tree->load($reles);
		$list = $tree->DeepTree();
		return $list;
	}


}



 ?>