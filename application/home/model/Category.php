<?php 
namespace app\home\model;
use think\Model;
class Category extends Model{
	protected $pk = 'cat_id';

	//取出导航栏的分类数据
	public function getNavCat(){
		//取出顶级（pid=0）并is_show=1的分类
		$where = [
			'is_show' => 1,
			'pid' =>0
		];
		return $this->where($where)->select();
	}


	//递归找祖先分类
	public function getFamilyCat($data,$cat_id){
		static $result = [];
		foreach($data as $v){
			//第一次循环肯定是找到当前分类
			if($v['cat_id'] == $cat_id){
				$result[] = $v;
				//递归调用自己，传递当前的pid找祖先
				$this->getFamilyCat($data,$v['pid']);
			}
		}
		//翻转数组 
		return array_reverse($result);
	}


	//递归找子孙分类
	public function getSonCat($data,$cat_id){
		static $result = [];
		foreach($data as $v){
			if($v['pid'] == $cat_id){
				$result[] = $v['cat_id'];
				//递归调用找子孙传递cat_id
				$this->getSonCat($data,$v['cat_id']);
			}
		}

		//返回结果
		return $result;
	}
}