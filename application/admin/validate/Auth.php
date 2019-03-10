<?php 
namespace app\admin\validate;
use think\Validate;
class Auth extends Validate{
	
	//1.规则
	protected $rule = [
		'auth_name' => 'require|unique:auth',
		'pid' => 'require',
		'auth_c' => 'require',
		'auth_a' => 'require'

	];
	//2.错误信息
	protected $message = [
		'auth_name.require' => '权限名必填',
		'auth_name.unique' => '权限名占用',
		'pid.require' => '请选择父权限',
		'auth_c.require' => '非顶级权限控制器名必填',
		'auth_a.require' => '非顶级权限方法名必填',
	];
	//3.场景
	protected $scene = [
		'add' => ['auth_name','pid','auth_c','auth_a'],
		'upd' => ['auth_name','pid','auth_c','auth_a'],
		//此场景只验证auth_name元素
		'checkAuthName'=>['auth_name']
	];
}