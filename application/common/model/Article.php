<?php  
namespace app\admin\model;
use think\Model;

class Article extends Model
{
	protected $autoWriteTimestamp = true;
	//定义时间戳字段名
	protected $createTime = 'createtime';
	protected $updateTime = 'updatetime';

	public function handleData($result)
	{
		foreach ($result as $key => $value) 
		{
			$result[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
			if($value['tag']==1)
			{
				$result[$key]['tag'] = '原创';
			}
			else
			{
				$result[$key]['tag'] = '转载';
			}
		}
		return $result;
	}
}



?>