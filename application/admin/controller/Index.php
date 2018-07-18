<?php 
namespace app\admin\controller;
use app\admin\common\controller\Backend;
use think\Db;
use League\Pipeline\Pipeline;

class Index extends Backend
{
	public function index()
	{
		return $this->fetch();
	}

	public function _empty($name)
    {
        echo $name.'方法不存在';
    }

    public function ceshi()
    {
    	$pipe1 = function ($payload)
    	{ 
    	return $payload + 1; 
    	}; 
    	$pipe2 = function ($payload) 
    	{ 
    	return $payload * 3; 
    	}; 

    }
	
	/**
     * 获取侧边栏菜单
     */
    public  function getMenu()
    {
    	Config('default_return_type','json');
    	$userInfo = parent::getUserInfo();
        $menu           = [];
        $admin_id       = $userInfo['id'];
        $auth           = new \think\auth\Auth();
   		
        $auth_rule_list = Db::name('auth_rule')->field('id,pid,title,icon,name')->where(['status'=>1,'type'=>'menu'])->order(['weigh' => 'DESC', 'id' => 'ASC'])->select();

        foreach ($auth_rule_list as $value) {

            if ($auth->check($value['name'], $admin_id) || $admin_id == 1) {
                $menu[] = $value;
            }
        }
       
        $menu = !empty($menu) ? $this->new_tree($menu) : [];
        if(!empty($menu))
        {
			return ['data'=>$menu,'code'=>1,'message'=>'操作完成'];
        }
        return ['data'=>null,'code'=>0,'message'=>'没有权限'];
      	
    }

    public function new_tree($reles)
	{
		$tree = new \blog\Tree();
		$tree->load($reles);
		$list = $tree->DeepTree();
		return $list;
	}

	/*public function menu()
	{
		$data = $this->tree();
		
		\think\Config::set('default_return_type','json');
		if($data)
		{
			return ['data'=>$data,'code'=>1,'message'=>'操作完成'];
		}
		else
		{
			return ['data'=>null,'code'=>0,'message'=>'没有权限'];
		}
		
	}

	public function rules()
	{
		$list = Db::name('auth_rule')->field('id,pid,title,icon,name')->where(['status'=>1,'type'=>'menu'])->order('weigh desc')->select();
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
	}*/

	


}



 ?>