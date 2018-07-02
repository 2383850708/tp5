<?php 
namespace app\admin\validate;

use think\Validate;

class EmailSet extends Validate
{
    protected $rule = [
        'smtp_server'  =>  'require',
        'smtp_port' =>  'require',
        'send_email' =>  'require',
        'send_nickname' =>  'require',
        'send_password' =>  'require',
    ];

    protected $message = [
	    'smtp_server.require' => 'SMTP服务器不能为空',
	    'smtp_port.require' => 'SMTP端口号不能为空',
	    'send_email.require' => '发件人邮箱不能为空',
        'send_nickname.require' => '发件人昵称不能为空',
        'send_password.require' => '箱登入密码不能为空'
	];
    

}



 ?>