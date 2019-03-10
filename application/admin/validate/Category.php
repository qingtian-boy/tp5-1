<?php 
namespace app\admin\validate;
use think\Validate;
class Category extends Validate{
	//1.规则
	protected $rule = [
		'cat_name' => 'require|unique:category',
		'pid' => 'require',

	];
	//2.错误信息
	protected $message = [
		'cat_name.require' => '分类名称必填',
		'cat_name.unique' => '分类名称占用',
		'pid.require' => '请选择所属分类',
		
	];
	//3.场景
	protected $scene = [
		'add' => ['cat_name','pid']
	];
}