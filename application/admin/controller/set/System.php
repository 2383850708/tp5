<?php 
/**
 * 系统设置
 */
namespace app\admin\controller\set;
use app\admin\common\controller\Backend;

use think\Db;

class System extends Backend
{
	public function _initialize()
	{
		parent::_initialize();
		$this->model = model('System');
	}

	public function _empty($name)
    {
        echo $name.'方法不存在';
    }

	public function index()
	{
		$info = $this->model->get(1);
	
		$this->assign('info',$info);
		return $this->fetch();
	}

	/**
	 * 系统设置
	 * @Author   wyk
	 * @DateTime 2018-07-02
	 */
	public function add()
	{
		$data = input('param.');
		$id = input('param.id');
		$info = $this->upload();

		if($info['status']==1)
		{
			$data['logo'] = $info['path'];
		}
		else if($info['status']==0)
		{
			return parent::returnJson($info['path'],0);
		}

		if($id!='')
		{
			$result = $this->model->validate('System')->save($data,['id'=>$id]);
		}
		else
		{
			$result = $this->model->validate('System')->save($data);
		}
		
		if ($result === false)
        {
        	$msg = $this->model->getError();
            return parent::returnJson($msg,0);
        }
        else
        {
        	if($id!='')
        	{
        		return parent::returnJson('修改成功',1);
        	}
        	return parent::returnJson('添加成功',1);
        }

	}

	/**
	 * 文件上传
	 * @Author   wyk
	 * @DateTime 2018-07-02
	 */
	public function upload()
	{
		$file = $this->request->file('file');

			$data = array();
			$data['status'] = 3;
			if($file)
			{
		        $info = $file->move('./logo/','logo');
		        
		        if($info)
		        {
		        	$data['status'] = 1;
		        	$data['path'] = $info->getSaveName();
		        	// $image = \think\Image::open($info->getPathName());
					// 给原图左上角添加水印并保存water_image.png
					//$image->text('www.baidu.com','fontlibrary/Lucia.ttf',20,'#ffffff')->save($info->getPathName());
		        }
		        else
		        {
		            $data['status'] = 0;
		        	$data['path'] = $file->getError();
		        }
		    }
		    return $data;
	}

}



 ?>