<?php 
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'username'  =>  'require|max:25|unique:admin',
        'nickname' =>  'require',
        'password' =>  'require',
        'status' =>  'require',
        'email' =>  'email',
    ];

    protected $message = [
	    'username.require' => '用户名不能为空',
	    'username.max'     => '名称最多不能超过25个字符',
	    'username.unique'     => '用户名已存在',
	    'nickname.require'   => '昵称不能为空',
	    'password.require'  => '密码不能为空',
	    'status.require'        => '状态不能为空',
	    'email.email'        => '邮箱格式不正确',
	];

	protected $scene = [
		'add'  =>  ['username','nickname','password','status','email'],
        'edit'  =>  ['username','nickname','status','email'],
        'edit_set'=>['nickname','email','sex','mobile'],
    ];

}




 ?>