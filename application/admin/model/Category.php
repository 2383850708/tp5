<?php 
namespace app\admin\model;

use think\Model;
use think\Db;

class Category extends Model
{
	//开启自动写入时间戳字段
	protected $autoWriteTimestamp = true;
	//定义时间戳字段名
	protected $createTime = 'createtime';
    protected $updateTime = false;

}


 ?>