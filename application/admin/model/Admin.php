<?php 
namespace app\admin\model;

use think\Model;
use think\Db;

class Admin extends Model
{
	//开启自动写入时间戳字段
	protected $autoWriteTimestamp = 'int';
	//定义时间戳字段名
	protected $createTime = 'createtime';
	protected $updateTime = 'updatetime';

	public function getAll($condition)
	{
		$result = Db::name('admin')->where($condition)->select();
		return $result;
	}

	public function handleData($result)
	{
		foreach ($result as $key => $value) 
		{
			if($value['status']==1)
			{
				$result[$key]['status'] = '正常';
			}
			else
			{
				$result[$key]['status'] = '隐藏';
			}
		}
		return $result;
	}
}






 ?>