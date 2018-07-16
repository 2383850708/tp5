<?php 

namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Session;
class Login extends Controller
{
	public function index()
	{

		return $this->fetch();
	}

	public function _empty($name)
    {
        echo $name.'方法不存在';
    }

	public function login()
	{

	  	if($this->request->isPost())
		{
			$geetest_id=config('GEETEST_ID');
	        $geetest_key=config('GEETEST_KEY');
			$GtSdk = new \blog\GeetestLib($geetest_id,$geetest_key);
			$request = Request::instance();

			$data = array(
		        "user_id" => Session::get('geetest.user_id'), # 网站用户id
		        "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
		        "ip_address" => $request->ip(),//$request->ip(); # 请在此处传输用户请求验证时所携带的IP
	    	);

			if (Session::get('geetest.gtserver')== 1) 
			{   
	    		$result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
			    if ($result) 
			    {
			        $this->checkForm();
			    } 
			    else
			    {
			        $this->error('验证码失败');
			    }
			}
			else
			{   //服务器宕机,走failback模式
			    if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) 
			    {
			        $this->checkForm();
			    }
			    else
			    {
			        $this->error('验证码失败');
			    }
			}
		}
	}

	private function checkForm()
	{
		$model = model('Admin');
		
		$info = $model->get(function($query){
			$query->where('username',input('param.username'));
		});

	
		if($info)
		{
			$info = $info->toArray();
			
			if($info['password']==md5(input('param.password')))
			{
				if(!session_id())
				{
					session_start();
				}
				$sessionID = session_id();

				Session::set('userInfo.name',$info['username']);
				Session::set('userInfo.token',$sessionID);
				Session::set('userInfo.id',$info['id']);
				Session::set('userInfo.loginTime',date('Y-m-d H:i:s',$info['logintime']));
				Session::set('userInfo.login_ip_address',$info['login_ip_address']);

				Session::set('userInfo.login_ip',request()->ip());

				$model->allowField(['logintime','token','login_ip','login_ip_address'])->save(['token'=>$sessionID], ['id' => $info['id']]);
				
				$this->success('登陆成功', url('index/index'));
			}
			else
			{
				$this->error('密码错误');
			}
		}
		else
		{
			$this->error('用户名不存在');
		}
	}

	/**
	 * 获取验证码
	 * @Author   wyk
	 * @DateTime 2018-04-12
	 */
	public function geetest_show_verify()
	{      
		$arr = array();
		
		if(!session_id())
		{
			session_start();
		}
		
        $geetest_id=config('GEETEST_ID');
        $geetest_key=config('GEETEST_KEY');
		$geetest = new \blog\GeetestLib($geetest_id,$geetest_key);
        $arr['user_id'] = "test";
        $status = $geetest->pre_process($arr);
		Session::set('geetest.gtserver',$status);
		Session::set('geetest.user_id',$arr['user_id']);
        echo $geetest->get_response_str();
    }

    /**
     * 注销登录
     * @Author   wyk
     * @DateTime 2018-06-01
     */
    public function loginOut()
    {
        session(null);
        $this->success('退出成功',url('login/index'));
    }
}
























 ?>