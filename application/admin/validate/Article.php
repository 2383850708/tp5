<?php 
namespace app\admin\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title'  =>  'require',
        'author' =>  'require',
        'categoryid' =>  'require',
    ];

    protected $message = [
	    'title.require' => '标题不能为空',
	    'author.require' => '作者不能为空',
	    'categoryid.require' => '栏目不能为空',
	];

}



 ?>