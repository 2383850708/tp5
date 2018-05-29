<?php 

namespace blog;

class Random
{
	/**
	 * 随机生成类
	 *
	 * @param  int $len 长度
	 * @return string
	 */
	public static function alnum($len = 6)
	{
		return self::build('alnum',$len);
	}

	/**
	 * 能用的随机数生成
	 * @param string $type 类型 alpha/alnum/numeric/nozero/unique/md5/encrypt/sha1
	 * @param   $[int] [$len] 长度
	 */
	public static function build($type = 'alnum',$len = 8)
	{
		switch ($type)
        {
            case 'alpha':
            case 'alnum':
            case 'numeric':
            case 'nozero':
                switch ($type)
                {
                    case 'alpha':
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum':
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $pool = '0123456789';
                        break;
                    case 'nozero':
                        $pool = '123456789';
                        break;
                }
                return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
            case 'unique':
            case 'md5':
                return md5(uniqid(mt_rand()));
            case 'encrypt':
            case 'sha1':
                return sha1(uniqid(mt_rand(), TRUE));
        }
	}
}








 ?>