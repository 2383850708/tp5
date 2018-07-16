<?php 
namespace app\admin\controller\miscellaneous;
use app\admin\common\controller\Backend;
use think\Db;

class Slide extends Backend
{
	public function _initialize()
	{
		parent::_initialize();
		$this->model = model('Slide');
	}

	public function index()
	{
		return $this->fetch();
	}

	public function getCondition()
	{
		$data = array();

		if(input('param.id'))
		{	
			$data['a.id'] = input('get.id');
		}

		if(input('param.name')!=0)
		{
			$data['a.name'] = array('like','%'.input('param.name').'%');
		}

		if(input('param.status')!=-1 && input('param.status')!='')
		{
			$data['a.status'] = input('param.status');
		}

		return $data;
	}

	public function ajax_load_data()
	{
		/*$list = collection($this->model->relation('profile')->field('id,name,uid')->select())->toArray();*/
		
		$condition = $this->getCondition();
		$result = Db::name('slide')
		->alias('a')
		->join('fa_admin b','b.id=a.uid')
		->where($condition)
		->field("a.*,b.username,FROM_UNIXTIME(a.createtime,'%Y-%m-%d %H:%i:%s') as datetime")
		->order('a.id desc,a.scort')
		->select();
		$data = array();
		$data['code'] = 0;
		$data['status'] = 1;
		$data['count'] = 0;
		$data['data'] = $result;
		$data['msg'] = '';

		return json($data);
	}

	public function add()
	{
		if($this->request->isAjax())
		{
			$data = input('param.');
			$result = $this->model->validate('Slide')->save($data);
			if ($result === false)
            {
            	$msg = $this->model->getError();
                return parent::returnJson($msg,0);
            }
            else
            {
            	return parent::returnJson('添加成功',1);
            }
		}
		else
		{
			return $this->fetch();
		}
	}

	public function edit()
	{
		if($this->request->isAjax())
		{
			$data = input('param.');
			$result = $this->model->validate('Slide')->save($data,['id',$data['id']]);
			if ($result === false)
            {
            	$msg = $this->model->getError();
                return parent::returnJson($msg,0);
            }
            else
            {
            	return parent::returnJson('修改成功',1);
            }
		}
		else
		{
			$id = input('param.id');
			$info = $this->model->get($id)->toArray();
			$this->assign('info',$info);
			return $this->fetch();
		}
	}

	public function del()
	{
		$ids = json_decode(input('param.ids'),true);
		$res = $this->model->destroy($ids);
		if($res)
		{
			return parent::returnJson('删除成功',1);
		}
		else
		{
			return parent::returnJson('删除失败',0);
		}
	}

	public function upload()
	{
		Config('default_return_type','json');
		$file = $this->request->file('file');
		$pic =  input('param.pic');
		$pic_min = input('param.pic_min');
		
		$config = Db::name('system')->where('id',1)->find();
		if($config)
		{

		}
		$info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info)
        {
        	
        	$image = \think\Image::open($info->getPathName());
			// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
			$path = substr($info->getPathName(),0,stripos($info->getPathName(),'.')).'_min.'.$info->getExtension();
			
			$res = $image->thumb(150, 39)->save($path);
			if(!empty($pic))
			{
				$pic_path = ROOT_PATH . 'public' . DS . 'uploads'.DS.$pic;
				if(file_exists($pic_path))
				{
					unlink($pic_path);
				}
				
			}

			if(!empty($pic_min))
			{
				$pic_min_path = ROOT_PATH . 'public' . DS . 'uploads'.DS.$pic_min;
				if(file_exists($pic_min_path))
				{
					unlink($pic_min_path);
				}
			}

        	$data['status'] = 1;
        	$data['pic'] = $info->getSaveName();
        	$data['pic_min'] = substr($info->getSaveName(),0,stripos($info->getSaveName(),'.')).'_min.'.$info->getExtension();
        	$data['msg'] = '上传成功';
      
        }
        else
        {
            $data['status'] = 0;
        	$data['msg'] = $file->getError();
        }
      
        return $data;
	}
}


 ?>