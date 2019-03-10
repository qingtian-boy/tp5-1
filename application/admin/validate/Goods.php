<?php 
namespace app\admin\validate;
use think\Validate;
class Goods extends Validate{
	//1.规则
	protected $rule = [
		'goods_name' => 'require|unique:goods',
		'cat_id' => 'require',
		'goods_price' => 'require|gt:0',
		// 'goods_number' => 'require|egt:0'
		// 使用内置的正则验证,大于或等于0正则： ^\d+$
		'goods_number' => 'require|regex:^\d+$'

	];
	//2.错误信息
	protected $message = [
		'goods_name.require' => '商品名称必填',
		'goods_name.unique' => '商品名称占用',
		'cat_id.require' => '请选择商品分类',
		'goods_price.require' => '价格必填',
		'goods_price.gt' => '商品价格需大于0',
		'goods_number.require' => '库存必填',
		'goods_number.egt' => '商品库存需大于等于0',
		'goods_number.regex' => '商品库存需大于等于0',
	];
	//3.场景
	protected $scene = [
		'add' => ['goods_name','cat_id','goods_price','goods_number'],
		
	];
}