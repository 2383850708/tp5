<?php 

namespace app\admin\controller;
use app\admin\common\controller\Backend;
use think\Db;


class Common extends Backend
{
	/**
	 * 公共删除方法
	 * @Author   wyk
	 * @DateTime 2018-06-05
	 * @return   json
	 */
	public function del($ids='')
	{
		print_r(json_decode(input('param.')));exit;
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