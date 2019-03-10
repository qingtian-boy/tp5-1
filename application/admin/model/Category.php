<?php 
namespace app\admin\model;
use think\Model;

class Category extends Model{
	protected $pk = 'cat_id';
	protected $autoWriteTimestamp = true;


	//封装一个无限极分类的方法（找子孙）
	public function getSonsTree($data,$pid=0,$level=1){
		static $result = []; //静态数组递归调用只会初始化一次
		foreach($data as $v){
			if($v['pid'] == $pid){
				$v['level'] = $level;
				// 使用到了技巧1，把主键作为其对应的下标
				$result[ $v['cat_id'] ] = $v;
				//递归调用自己
				$this->getSonsTree($data,$v['cat_id'],$level+1);
			}
		}

		return $result;
	}
}