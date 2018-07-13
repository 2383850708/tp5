<?php 
namespace app\admin\validate;

use think\Validate;

class Slide extends Validate
{
    protected $rule = [
        'name'  =>  'require|max:25|unique:slide',
        'pic' =>  'require',
        'status' => 'in:1,2'
    ];

    protected $message = [
	    'name.require' => '广告名不能为空',
	    'name.max'     => '广告名最多不能超过25个字符',
	    'name.unique'     => '广告名已存在',
	    'pic.require'   => '广告图片不能为空',
	    'status.require'  => '状态参数不正确',
	];

}




 ?>