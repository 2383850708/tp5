<?php 
namespace app\home\common\controller;
use think\Controller;
use think\Session;
use think\Db;


/**
 * 前台控制器基类
 */
class Backend extends Controller
{
	public function _initialize()
    {

        /*$system = Db::name('system')->find(1);
        $this->assign('system',$system);

        $category = Db::name('category')->select();
        $tree = new \blog\Tree();
        $tree->load($category);
        $category_list = $tree->DeepTree();
        $this->assign('category_list',$category_list);*/
        //检查用户是否登陆
        /*if(!Session::has('userInfo'))
        {
            $this->redirect(url('login/index'));
        }*/
    }

    /**
     * 获取用户信息
     * @Author   wyk
     * @DateTime 2018-06-01
     * @return   Array      
     */
    protected function getUserInfo()
    {
    	$info = Session::get('userInfo');
    	return $info;
    }

    public function returnJson($msg,$type=0)
    {
        $promptIcon = db('prompt')->find();
        $data = array();

        if($type==0)
        {
            $data['icon'] = $promptIcon['error_icon'];
        }
        else
        {
            $data['icon'] = $promptIcon['success_icon'];
        }
        $data['skin'] = $promptIcon['skin'];
        $data['anim'] = $promptIcon['anim'];
        $data['status'] = $type;
        $data['msg'] = $msg;

        return json($data);
    }

	
}

 ?>