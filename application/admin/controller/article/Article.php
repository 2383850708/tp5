<?php 
namespace app\admin\controller\article;
use app\admin\common\controller\Backend;

class Article extends Backend
{
	public function index()
	{
		return $this->fetch();
	}

	public function add()
	{
		if($this->request->isAjax())
		{
			$data = input('param.');
			$info = $this->upload();
			if($info['status']==1)
			{
				$data['pic'] = $info['path'];
			}
			else if($info['status']==0)
			{
				return parent::returnJson($info['path'],0);
			}
			
			
		}
		else
		{
			$Category = new \blog\Category("Category",array('id','pid','title','fullname'));
			$categoryList = $Category->getList(array(),' scort desc ');
			$this->assign('list',$categoryList);
			return $this->fetch();
		}
		
	}

	public function upload()
	{
		$file = $this->request->file('file');
			$data = array();
			$data['status'] = 3;
			if($file)
			{
		        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		        if($info)
		        {
		        	$data['status'] = 1;
		        	$data['path'] = $info->getSaveName();;
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