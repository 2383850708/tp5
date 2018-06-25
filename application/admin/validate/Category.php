<?php 
namespace app\admin\validate;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'title'  =>  'require|unique:category',
    ];

    protected $message = [
	    'title.require' => '分类名称不能为空',
	    'title.unique'     => '分类名称已存在',
	];

}




 ?>