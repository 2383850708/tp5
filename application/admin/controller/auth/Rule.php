<?php 
namespace app\admin\controller\auth;
use app\admin\common\controller\Backend;
use blog\Tree;

class Rule extends Backend
{
	public function _initialize()
	{
		parent::_initialize();
		$this->model = model('AuthRule');
	}

	public function index()
	{
		return $this->fetch();
	}

	public function ajax_load_data()
	{
		//field('id,pid,name,title,level,icon,type,')->
		$count = $this->model->where('status',1)->count();
		$result = collection($this->model->order('weigh', 'desc')->select())->toArray();
		$result = $this->getSubTree($result,0,0);

		$data = array();
		$data['code'] = 0;
		$data['count'] = $count;
		$data['data'] = $result;
		$data['msg'] = '';
		return json($data);
	}


	public function add()
	{
		if($this->request->isAjax())
		{

			$data=$this->request->get("row/a");
			 
			$result = $this->model->validate('AuthRule')->save($data);

			if(false === $result)
			{
			    return parent::returnJson($this->model->getError(),0);
			}
			else
			{
			    return parent::returnJson('添加成功',1);
			}
			return json($res);
		}
		else
		{
			if(input('?get.id'))
			{
				$this->assign('id',input('param.id'));
			}
			else
			{
				$this->assign('id',0);
			}
			
			$result = collection($this->model->field('id,pid,name,title,level')->where(['status'=>1,'type'=>'menu'])->order('weigh', 'desc')->select())->toArray();

			$treelist = $this->getSubTree($result,0,0);
			
			$this->assign('treelist',$treelist);
			return $this->fetch();
		}
		
	}

	public function edit()
	{
		if($this->request->isAjax())
		{
			$data=$this->request->get("row/a");
			
			$result = $this->model->validate('AuthRule')->save($data,['id',$data['id']]);

			if(false === $result)
			{
			    return parent::returnJson($this->model->getError(),0);
			}
			else
			{
			    return parent::returnJson('修改成功',1);
			}
			return json($res);
		}
		else
		{
			$result = collection($this->model->field('id,pid,name,title,level')->where('status',1)->order('weigh', 'desc')->select())->toArray();
			$treelist = $this->getSubTree($result,0,0);

			$id = input('param.');
			$info = $this->model->get($id)->toArray();
			$this->assign('info',$info);
			$this->assign('treelist',$treelist);
			return $this->fetch();
		}
	}

	/**
	 * 公共删除方法
	 * @Author   wyk
	 * @DateTime 2018-06-05
	 * @return   json
	 */
	public function del($id='')
	{
		$id = input('param.id');

		if($id)
		{
			$info = $this->model->isCheckSubclass($id);
			if($info)
			{
				return parent::returnJson('有子类不能删除',0);
			}
			else
			{
				$res = $this->model->destroy($id);
				
				if($res)
				{
					return parent::returnJson('删除成功',1);
				}
				else
				{
					return parent::returnJson('删除失败',0);
				}
			}
		}
		else
		{
			return parent::returnJson('操作异常',0);
		}
	}
	
	public function setMenu()
	{

		$type = input('param.type');
		$data = array();
		if($type=='menu')
		{
			$data['type'] = 'file';
		}
		else
		{
			$data['type'] = 'menu';
		}
		$res =$this->model->save($data,['id' => input('param.id')]);
		if($res)
		{
			return parent::returnJson('修改成功',1);
		}
		else
		{
			return parent::returnJson('修改失败',0);
		}
	}


	function getSubTree($data , $id = 0 , $lev = 0) 
	{
	    static $son = array();

	    foreach($data as $key => $value) 
	    {
	        if($value['pid'] == $id) 
	        {
	        	$data[$key]['level'] = $lev;
	        	if($value['pid']==0)
	        	{
					$value['title'] = str_repeat('&nbsp;',$lev*7).$value['title'];
	        	}
	        	else
	        	{
					if($value['level']==3)
					{
						$value['title'] = str_repeat('&nbsp;',$lev*7).'└ '.$value['title'];
					}
					else
					{
						$value['title'] = str_repeat('&nbsp;',$lev*7).'|— '.$value['title'];
					}
	        	}
	        	
	            $son[] = $value;
	           
	            $this->getSubTree($data , $value['id'] , $lev+1);
	        }
	    }
    	return $son;
 	}

}











 ?>