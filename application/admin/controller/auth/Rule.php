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

	/**
	 * 公共删除方法
	 * @Author   wyk
	 * @DateTime 2018-06-05
	 * @return   json
	 */
	public function del($id='')
	{
		$data = array();
		$data['skin'] = SKIN;
        $data['anim'] = ANIM;
		$id = input('param.id');

		if($id)
		{
			$info = $this->model->isCheckSubclass($id);
			if($info)
			{
				$data['status'] = ERROR;
				$data['msg'] = '有子类不能删除';
			}
			else
			{
				$res = $this->model->destroy($id);
				
				if($res)
				{
					$data['status'] = SUCCESS;
					$data['msg'] = '删除成功';
				}
				else
				{
					$data['status'] = ERROR;
					$data['msg'] = '删除失败';
				}
			}
		}
		else
		{
			$data['status'] = 0;
			$data['msg'] = '操作异常';
		}
		return json($data);
	}

 	function getSubTree($data , $id = 0 , $lev = 0) {
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


	public function add()
	{
		if($this->request->isAjax())
		{

			$res = array();
			$res['skin'] = SKIN;
        	$res['anim'] = ANIM;
			$data=$this->request->get("row/a");
			 
			$result = $this->model->validate('AuthRule')->save($data);

			if(false === $result)
			{
			    $res['status'] = ERROR;
			    $res['msg'] = $this->model->getError();
			}
			else
			{
				$res['status'] = SUCCESS;
			    $res['msg'] = '添加成功';
			}
			return json($res);
		}
		else
		{
			$result = collection($this->model->field('id,pid,name,title')->where('status',1)->order('weigh', 'desc')->select())->toArray();
			$tree = new Tree();
			$tree->load($result);
			$treelist=$tree->DeepTree();//所有分类树结构
			$this->assign('treelist',$treelist);
			return $this->fetch();
		}
		
	}

}











 ?>