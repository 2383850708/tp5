<?php 

namespace app\admin\model;

use think\Model;
use think\Db;

class AuthRule extends Model
{
	//定义时间戳字段名
	protected $autoWriteTimestamp = 'int';
	protected $createTime = 'createtime';
	protected $updateTime = 'updatetime';
	protected $insert = ['level'];


	public function setLevelAttr($value,$data)
	{
		if($data['pid']!=0)
		{
			$level = Db::name('auth_rule')->where('id',$data['pid'])->value('level');
			return $level+1;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * 验证子类是否存在
	 * @Author   wyk
	 * @DateTime 2018-06-05
	 */
	public static function isCheckSubclass($id)
	{
		
		$id = AuthRule::where('pid',$id)->value('id');
		
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