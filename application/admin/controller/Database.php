<?php 
/**
 * 数据库备份
 */
namespace app\admin\controller;
use app\admin\common\controller\Backend;

use think\Db;

class Database extends Backend
{

	/**
	 * 数据库备份
	 * @Author   wyk
	 * @DateTime 2018-07-06
	 */
	public function backups()
	{
		if($this->request->isAjax())
		{
			$list = Db::query('SHOW TABLE STATUS like "fa_%"');
			if($list)
			{
				$list  = array_map('array_change_key_case', $list);
			}

			$data = array();
			$data['code'] = 0;
			$data['status'] = 1;
			$data['count'] = 0;
			$data['data'] = $list;
			$data['msg'] = '';

			return json($data);
		}
		else
		{
			return $this->fetch();
		}
		
	}	

	/**
	 * 数据库还原
	 * @Author   wyk
	 * @DateTime 2018-07-06
	 */
	public function reduction()
	{

	}

	public function export()
	{
		print_r(input('param.'));exit;
	}
}
?>