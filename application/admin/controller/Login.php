<?php 

namespace app\admin\controller;
use app\admin\common\controller\Backend;
use think\Session;
class Login extends Backend
{
	public function index()
	{

		return $this->fetch();
	}

	public function login()
	{
		echo 11;exit;
	}

	/**
	 * 获取验证码
	 * @Author   wyk
	 * @DateTime 2018-04-12
	 */
	public function geetest_show_verify()
	{      
		$arr = array();
		session_start();
        $geetest_id=config('GEETEST_ID');
        $geetest_key=config('GEETEST_KEY');
		$geetest = new \blog\GeetestLib($geetest_id,$geetest_key);
        $arr['user_id'] = "test";
        $status = $geetest->pre_process($arr);
		Session::set('geetest.gtserver',$status);
		Session::set('geetest.user_id',$arr['user_id']);
        echo $geetest->get_response_str();
    }
}
























 ?>