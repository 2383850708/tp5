<?php 
namespace app\admin\validate;

use think\Validate;

class System extends Validate
{
    protected $rule = [
        'title'  =>  'require',
        'domain_name' =>  'require',
        'cache_time' =>  'require',
        'upload_size' =>  'require',
        'upload_type' =>  'require',
    ];

    protected $message = [
	    'title.require' => '网站名称不能为空',
	    'domain_name.require' => '网站域名不能为空',
	    'cache_time.require' => '缓存时间不能为空',
        'upload_size.require' => '文件上传大小不能为空',
        'upload_type.require' => '文件上传类型不能为空'
	];
    

}



 ?>