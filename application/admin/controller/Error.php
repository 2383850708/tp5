<?php
namespace app\admin\controller;

use think\Request;

class Error
{
    public function index(Request $request)
    {
        //根据当前控制器名来判断要执行那个城市的操作
        $cityName = $request->controller();
        return $this->city($cityName);
    }

    protected function city($name)
    {
        //和$name这个城市相关的处理
         return '你访问的' . $name.'控制器，不存在！';
    }
   
}