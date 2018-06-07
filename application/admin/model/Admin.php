<?php 
namespace app\admin\model;

use think\Model;
use think\Db;

class Admin extends Model
{
	//开启自动写入时间戳字段
	protected $autoWriteTimestamp = true;
	//定义时间戳字段名
	protected $createTime = 'createtime';
	protected $updateTime = 'updatetime';

	protected $insert = ['ip','ip_address'];
	protected $update = ['login_ip','login_ip_address'];

	
	protected function setPasswordAttr($value)
    {
        return MD5($value);
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

	protected function setIpAttr()
    {
        return request()->ip();
    }
    
    protected function setIpAddressAttr()
    {
        return $this->taobaoIP();
    }

    protected function setLoginIpAddressAttr()
    {
        return $this->taobaoIP();
    }

    protected function setLoginIpAttr()
    {
        return request()->ip();
    }

 

    protected function taobaoIP()
	{
    	$clientIP = request()->ip();
        $taobaoIP = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$clientIP;
        $IPinfo = json_decode(file_get_contents($taobaoIP));
        $province = $IPinfo->data->region;
        $city = $IPinfo->data->city;
        $data = $province.$city;
        return $data;
    }
}






 ?>