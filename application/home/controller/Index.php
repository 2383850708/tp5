<?php
namespace app\home\controller;
use app\home\common\controller\Backend;
use think\Db;
class Index extends Backend
{

	public function _initialize()
	{
		parent::_initialize();
		
	}

    public function index()
    {
        return $this->fetch();
    }
}
