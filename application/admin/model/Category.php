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
    
    /**
	 * 验证子类是否存在
	 * @Author   wyk
	 * @DateTime 2018-06-05
	 */
	public static function isCheckSubclass($id)
	{
		
		$id = Category::where('pid',$id)->value('id');
		
		if($id)
		{
			return true;
		}
		else
		{
			return false;
		}

	}
}


 ?>