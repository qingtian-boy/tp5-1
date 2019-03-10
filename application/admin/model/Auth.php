<?php 
namespace app\admin\model;
use think\Model;
class Auth extends Model{
	protected $pk = 'auth_id';
	protected $autoWriteTimestamp = true;


	public static function init(){
		//定义编辑的前钩子
		Auth::event('before_update',function($auth){
			//如果是顶级权限pid=0,需要把控制器名和方法名设置为空字符
			if($auth['pid'] == 0){
				$auth['auth_c'] = '';
				$auth['auth_a'] = '';
			}
		});
	}



	//定义一个无限极分类的方法（找子孙）
	public function getSonsTree($data,$pid=0,$level=1){
		static $result = []; 
		foreach($data as $v){
			if($v['pid'] == $pid){
				$v['level'] = $level;
				$result[] = $v;// 存进$result数组中去
				$this->getSonsTree($data,$v['auth_id'],$level+1);
			}
		}
		//返回递归好的结果
		return $result;
	}
}