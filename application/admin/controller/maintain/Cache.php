<?php 
/**
 * 清除缓存
 */
namespace app\admin\controller\maintain;
use app\admin\common\controller\Backend;

class Cache extends Backend
{
	public function index()
	{
		return $this->fetch();
	}

	public function _empty($name)
    {
        echo $name.'方法不存在';
    }

	public function clear()
	{
		$data = $_REQUEST['data'];
		
		if(!empty($data))
		{
			foreach ($data as $key => $value) 
			{
				$this->cleardir(RETIMEPATH.$value.'/');
			}
		}
		$this->success("清除成功");
	}

	    function cleardir($dir,$forceclear=false) {
        if(!is_dir($dir)){
            return;
        }
        $directory=dir($dir);
        while($entry=$directory->read()){
            $filename=$dir.'/'.$entry;
            if(is_file($filename)){
                @unlink($filename);
            }elseif(is_dir($filename)&$forceclear&$entry!='.'&$entry!='..'){
                chmod($filename,0777);
                file::cleardir($filename,$forceclear);
                rmdir($filename);
            }
        }
        $directory->close();
    }
}




 ?>