<?php 
namespace app\admin\validate;
use think\Validate;
class User extends Validate{

	//三个属性
	//1.规则
	protected $rule = [
		'username' => 'require|unique:user',
		'password' => 'require|min:5',
		'role_id' => 'require',
		//confirm:password 要求repassword的字段值和password一直
		'repassword' => 'require|confirm:password',
		'captcha' => 'require|captcha'

	];
	//2.错误信息
	protected $message = [
		'username.require' => '用户名必填',
		'role_id.require' => '请选择角色',
		'username.unique' => '用户名占用',
		'password.require' => '密码必填',
		'password.min' => '密码长度最少五位',
		'repassword.require' => '确认密码必填',
		'repassword.confirm' => '两次密码不一致',
		'captcha.require' => '验证码必填',
		'captcha.captcha' => '验证码输入错误',
	];
	//3.场景
	protected $scene = [
		'add' => ['role_id','username','password','repassword'],
		'upd' => ['password','repassword','role_id'],
		//在login场景，验证username的require，和password的require，和captcha元素的所有的规则
		'login' => ['username'=>"require",'password'=>"require",'captcha']
	];

}