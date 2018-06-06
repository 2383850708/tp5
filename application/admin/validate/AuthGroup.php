<?php 
namespace app\admin\validate;

use think\Validate;

class AuthGroup extends Validate
{
    protected $rule = [
        'title'  =>  'require|max:25|unique:auth_group',
        'status' =>  'between:1,2',
        'rules' =>  'require',
    ];

    protected $message = [
	    'title.require' => '角色名不能为空',
	    'title.max'     => '名称最多不能超过25个字符',
	    'title.unique'     => '角色名已存在',
	    'nickname.require'   => '昵称不能为空',
	    'rules.require'  => '权限不能为空',
	    'status.between'        => '状态参数不正确',
	];

}




 ?>