<?php 
namespace app\admin\validate;
use think\Validate;
class Attribute extends Validate{
	//1.规则
	protected $rule = [
		'attr_name' => 'require|unique:attribute',
		'type_id' => 'require',
		'attr_values' => 'require'

	];
	//2.错误信息
	protected $message = [
		'attr_name.require' => '属性名必填',
		'attr_name.unique' => '属性名占用',
		'type_id.require' => '请选择商品类型',
		'attr_values.require' => '列表选择时，属性值必填'
	];
		
	//3.场景
	protected $scene = [
		'add' => ['attr_name','type_id','attr_values'],
		'upd' => ['attr_name','type_id','attr_values'],
		//在checkNameAndType（手工输入时候验证）只验证attr_name和type_id的规则
		'checkNameAndType' => ['attr_name','type_id'],
	];
}
