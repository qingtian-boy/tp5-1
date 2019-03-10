<?php 
namespace app\admin\model;
use think\Model;
class Role extends Model{
	protected $pk = "role_id";
	protected $autoWriteTimestamp = true;

	public static function init(){
		//入库的前钩子
		Role::event('before_insert',function($role){
			//要把表单提交过来的权限数组变成一个字符串进行入库
			$role['auth_ids_list'] = implode(',',$role['auth_ids_list']);
		});

		//编辑的前钩子
		Role::event('before_update',function($role){
			//要把表单提交过来的权限数组变成一个字符串进行入库
			$role['auth_ids_list'] = implode(',',$role['auth_ids_list']);
		});
	}
}