<?php 
namespace app\admin\model;
use think\Model;
class Common extends Model
{
	public function del($ids='')
	{
		echo 11;exit;
		$data = array();

		if($ids)
		{
			$ids = json_decode(input('param.ids'));
			$res = $this->model->destroy($ids);
			if($res)
			{
				$data['status'] = 1;
				$data['msg'] = '删除成功';
			}
			else
			{
				$data['status'] = 0;
				$data['msg'] = '删除失败';
			}
		}
		else
		{
			$data['status'] = 0;
			$data['msg'] = '操作异常';
		}
		return json($data);
	}
}



 ?>