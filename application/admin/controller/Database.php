<?php 
/**
 * 数据库备份
 */
namespace app\admin\controller;
use app\admin\common\controller\Backend;
use blog\Databack;
use think\Db;
use think\Session;
class Database extends Backend
{

	/**
	 * 数据库备份
	 * @Author   wyk
	 * @DateTime 2018-07-06
	 */
	public function backups()
	{
		if($this->request->isAjax())
		{
			$list = Db::query('SHOW TABLE STATUS like "fa_%"');
			if($list)
			{
				$list  = array_map('array_change_key_case', $list);
			}

			$data = array();
			$data['code'] = 0;
			$data['status'] = 1;
			$data['count'] = 0;
			$data['data'] = $list;
			$data['msg'] = '';

			return json($data);
		}
		else
		{
			return $this->fetch();
		}
		
	}	

	/**
     * 备份数据库
     * @param  String  $tables 表名
     * @param  Integer $id     表ID
     * @param  Integer $start  起始行数
     */
    public function export($tables = null, $id = null, $start = null){

        if(request()->isPost() && !empty($tables) && is_array($tables)){ //初始化
            //读取备份配置
         
            $config = array(
                'path'     => realpath(config('data_backup_puth')).DIRECTORY_SEPARATOR,
                'part'     => '20971520',
                'compress' => '1',
                'level'    => '9',
            );
         
            //检查是否有正在执行的任务
			
            $lock = "{$config['path']}backup.lock";
		
            if(is_file($lock))
            {
                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
                file_put_contents($lock, time());
            }


            //检查备份目录是否可写
            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');
            session('backup_config', $config);
           
            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', time()),
                'part' => 1,
            );
            session('backup_file', $file);

            //缓存要备份的表
            session('backup_tables', $tables);

            //创建备份文件
            $Databack = new Databack($file, $config);
            if(false !== $Databack->create()){
                $tab = array('id' => 0, 'start' => 0);
                return $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            } else {
                return $this->error('初始化失败，备份文件创建失败！');
            }
        } elseif (request()->isGet() && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = session('backup_tables');

            //备份指定表
            $Databack = new Databack(session('backup_file'), session('backup_config'));
            $start  = $Databack->backup($tables[$id], $start);
            if(false === $start){ //出错
                $this->error('备份出错！');
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
                    return $this->success('备份完成！', '', array('tab' => $tab));
                } else { //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
                    return $this->success('备份完成！','',array('tid'=>$id));
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                return $this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
            }

        } else { //出错
            $this->error('参数错误！');
        }
    }

    /**
	 * 数据库还原
	 * @Author   wyk
	 * @DateTime 2018-07-06
	 */
	public function reduction()
	{
		if($this->request->isAjax())
		{
			
			//列出备份文件列表
            $path = realpath(config('data_backup_puth'));

            $flag = \FilesystemIterator::KEY_AS_FILENAME;

            $glob = new \FilesystemIterator($path,  $flag);

            $list = array();
            foreach ($glob as $name => $file) {
                if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                    $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                    $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                    $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                    $part = $name[6];

                    if(isset($list["{$date} {$time}"])){
                        $info = $list["{$date} {$time}"];
                        $info['part'] = max($info['part'], $part); 
                        $info['size'] = $this->formatBytes($info['size'] + $file->getSize());
                    } else {
                        $info['part'] = $part;
                        $info['size'] = $this->formatBytes($file->getSize());
                    }
                    $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                    $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                    $info['time']     = "{$name[0]}{$name[1]}{$name[2]}-{$name[3]}{$name[4]}{$name[5]}";
					$info['date_time']     = "{$date} {$time}";//改
                    //$list["{$date} {$time}"] = $info;
                    $list[] = $info;
                }
            }
            
            krsort($list);
           
            $data = array();
			$data['code'] = 0;
			$data['status'] = 1;
			$data['count'] = 0;
			$data['data'] = $list;
			$data['msg'] = '';

			return json($data);
		}
		else
		{
			return $this->fetch();
		}
	}

	function formatBytes($bytes, $precision = 0) {
    $units = array('b', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

}
?>