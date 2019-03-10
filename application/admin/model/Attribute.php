<?php 
namespace app\admin\model;
use think\Model;

class Attribute extends Model{
	protected $pk = 'attr_id';
	protected $autoWriteTimestamp = true;


	public static function init(){
		//属性编辑数据的前钩子
		Attribute::event('before_update',function($attr){
			//把属性值的录入方式改为手工输入，应该把属性值进行清空
			if($attr['attr_input_type'] == 0){
				$attr['attr_values'] = '';
			}
		});
	}
}