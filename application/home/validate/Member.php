<?php 
namespace app\home\validate; 
use think\Validate; 
class Member  extends Validate{
 
  //规则
  protected $rule = [
  	'username' => 'require|unique:member',
  	'email' => 'require|email|unique:member',
  	'password' => 'require',
  	'repassword' => 'require|confirm:password',
  	'phone' => 'require|regex:^1[3-9]\d{9}$|unique:member',
    'captcha' => 'require|captcha',  //注册
  	'login_captcha' => 'require|captcha:2'  //登录
  ];

  //提示信息
  protected $message = [
  	'username.require' => '用户名必填',
  	'username.unique' => '用户名占用',
  	'email.require' => '邮箱必填',
  	'email.email' => '邮箱格式有误',
  	'email.unique' => '邮箱占用',
  	'password.require' => '密码必填',
  	'repassword.require' => '重复密码必填',
  	'repassword.confirm' => '两次密码不一致',
  	'phone.require' => '手机号必填',
  	'phone.regex' => '手机号格式有误',
  	'phone.unique' => '手机号占用',
  	'captcha.require' => '验证码必填',
  	'captcha.captcha' => '验证码输入有误',
    'login_captcha.require' => '验证码必填',
    'login_captcha.captcha' => '验证码输入有误',
  ];

  //场景
  protected $scene = [
    'register' => ['username','email','password','repassword','phone','captcha'],
  	'login' => ['username'=>'require','password','login_captcha'],
    'reset' => ['password','repassword']
  ];

}