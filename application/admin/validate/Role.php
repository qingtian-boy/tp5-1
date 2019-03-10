<?php 
namespace app\admin\validate;
use think\Validate;
class Role extends Validate{
	//1.规则
	protected $rule = [
		'role_name' => 'require|unique:role',
		
	];
	//2.错误信息
	protected $message = [
		'role_name.require' => '角色名称必填',
		'role_name.unique' => '角色名称占用',

	];
	//3.场景
	protected $scene = [
		'add' => ['role_name'],
		'upd' => ['role_name'],
	];
}