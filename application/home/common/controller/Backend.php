<?php 
namespace app\home\common\controller;
use think\Controller;
use think\Session;
use think\Db;
//use think\Cache;
use blog\Redis;

/**
 * 前台控制器基类
 */
class Backend extends Controller
{
	public function _initialize()
    {

        $res = Redis::getInstance(config('cache.redis_zu'));

        print_r($res->hgetall('ceshi')) ;exit;
        /*$config = Cache::remember('config',function(){
            return Db::name('system')->find(1);
        });

        $category_list = Cache::remember('category_list',function(){
            $category = Db::name('category')->select();
            $tree = new \blog\Tree();
            $tree->load($category);
            return $tree->DeepTree();
        });

        $slide = Cache::remember('slide',function(){
            return Db::name('slide')->field('name,url,pic')->where('status',1)->select();
        });


       $this->assign('category_list',$category_list);
        
       $this->assign('config',$config);

       $this->assign('slide',$slide);*/
        //Cache::store('redis')->set('name','value');

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