<?php 
namespace app\admin\controller\article;
use app\admin\common\controller\Backend;
use think\Db;

class Article extends Backend
{

	public function _initialize()
	{
		parent::_initialize();
		$this->model = model('Article');
	}

	public function getCondition()
	{
		$data = array();

		if(input('param.id'))
		{	
			$data['a.id'] = input('get.id');
		}

		if(input('param.categoryid')!=0)
		{
			$data['a.categoryid'] = input('param.categoryid');
		}

		if(input('param.tag')!=-1 && input('param.tag')!='')
		{
			$data['a.tag'] = input('param.tag');
		}

		if(input('param.status')!=-1 && input('param.status')!='')
		{
			$data['a.status'] = input('param.status');
		}

		return $data;
	}

	public function index()
	{
		$Category = new \blog\Category("Category",array('id','pid','title','fullname'));
		$categoryList = $Category->getList(array(),' scort desc ');
		$this->assign('list',$categoryList);
		return $this->fetch();
	}

	public function ajax_load_data()
	{
		$condition = $this->getCondition();

		$len = input('param.limit');
		$page = input('param.page');
		$offset = ($page-1)*$len;

		$count = Db::name('article')
		->alias('a')
		->join('__CATEGORY__ b','a.categoryid=b.id')
		->where($condition)
		->count('a.id');

		$result = Db::name('article')
		->alias('a')
		->join('__CATEGORY__ b','a.categoryid=b.id')
		->field('a.id,a.title,a.author,a.status,b.title lanmu,a.reading,a.tag,a.createtime')
		->where($condition)
		->limit($offset,$len)
		->select();

		if($result)
		{
			$result = $this->model->handleData($result);
		}

		$data = array();
		$data['code'] = 0;
		$data['status'] = 1;
		$data['count'] = $count;
		$data['data'] = $result;
		$data['msg'] = '';

		return json($data);
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

			$result = $this->model->validate('Article')->save($data);
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
			$Category = new \blog\Category("Category",array('id','pid','title','fullname'));
			$categoryList = $Category->getList(array(),' scort desc ');
			$this->assign('list',$categoryList);
			return $this->fetch();
		}
		
	}

	public function edit()
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

			$result = $this->model->validate('Article')->save($data,['id'=>$data['id']]);
		
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
			$Category = new \blog\Category("Category",array('id','pid','title','fullname'));
			$categoryList = $Category->getList(array(),' scort desc ');

			$id = input('param.id');
			$info =  $this->model->get($id)->toArray();

			$this->assign('info',$info);
			$this->assign('list',$categoryList);
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
		$file = $this->request->file('file');

			$data = array();
			$data['status'] = 3;
			if($file)
			{
		        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
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