<?php
namespace app\home\controller;
use app\home\common\controller\Backend;
use think\Db;
class Common extends Backend
{
    public function index()
    {
       
    }

    public function header()
    {
    	 $system = Db::name('system')->find(1);
         $this->assign('system',$system);
    }
}
