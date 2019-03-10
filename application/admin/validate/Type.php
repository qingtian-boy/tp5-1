<?php 
namespace app\admin\validate;
use think\Validate;
class Type extends Validate{
	//1.规则
	protected $rule = [
		'type_name' => 'require|unique:type',

	];
	//2.错误信息
	protected $message = [
		'type_name.require' => '商品类型名称必填',
		'type_name.unique' => '商品类型名称占用',
	];
	//3.场景
	protected $scene = [
		'add' => ['type_name'],
		'upd' => ['type_name'],
	];
}