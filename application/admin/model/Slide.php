<?php 
namespace app\admin\model;

use think\Model;

use think\Session;
class Slide extends Model
{
	//开启自动写入时间戳字段
	protected $autoWriteTimestamp = true;
	//定义时间戳字段名
	protected $createTime = 'createtime';
	protected $updateTime = 'updatetime';
	protected $insert = ['uid'];
	protected $update = ['updateuid'];

	protected function setUidAttr($value)
	{
		return Session::get('userInfo.id');
	}

	protected function setUpdateuidAttr($value)
	{
		return Session::get('userInfo.id');
	}

	public function profile()
    {
        return $this->belongsTo('admin','uid','id',['slide'=>'a','admin'=>'b'])->field('id');
    }

}

?>