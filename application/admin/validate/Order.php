<?php 
namespace app\admin\validate;
use think\Validate;
class Order extends Validate{
	//规则
	protected $rule = [
		'company' => 'require',
		'number' =>'require|unique:order'
	];

	//提示信息
	protected $message = [
		'company.require' => '请选择物流公司',
		'number.require' => '物流号必填',
		'number.unique' => '物流号已存在',
	];

	//场景
	protected $scene = [
		'upd' => ['company','number']
	];
}