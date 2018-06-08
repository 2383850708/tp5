<?php 

namespace app\admin\validate;

use think\Validate;

class AuthRule extends Validate
{

	/**
     * 正则
     */
    //protected $regex = ['format' => '[a-z0-9_\/]+'];

	/**
     * 验证规则
     */
    protected $rule = [
        'name'  => 'require|unique:AuthRule',
        'title' => 'require',
        'type' => 'require|between:1,2',
        'status' => 'between:1,2'
    ];

    protected $message = [
	    'name.require' => '规则不能为空',
	    //'name.format'     => 'URL规则只能是小写字母、数字、下划线和/组成',
	    'name.unique'     => '验证规则已存在',
	    'title.unique'     => '标题不能为空',
	    'type.unique'     => '选择菜单',
	    'type.between'     => '菜单参数不正确',
	    'status.between'  => '状态参数不正确',
	];

	/*protected $scene = [
		
    ];*/
}






 ?>