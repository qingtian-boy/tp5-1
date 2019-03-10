<?php 
namespace app\admin\model;
use think\Model;
use think\Db;

class Goods extends Model{
	protected $pk = 'goods_id';
	protected $autoWriteTimestamp = true;

	public  static function  init(){
		//入库的前钩子：save(before_insert-->insert()---->after_insert())
		Goods::event('before_insert',function($goods){
			//添加商品需要自动生成一个唯一的货号
			$goods['goods_sn'] = 'sn_'.time().uniqid();
			// dump($goods);die;
		});

		//入库的后钩子
		//因为只有商品表入库成功之后，才可以获取到主键goods_id的自增的值
		Goods::event('after_insert',function($goods){
			//$goods是商品表入库成功之后的一条记录对象
			$goods_id = $goods['goods_id'];
			$postData = input('post.');
			$attrValue = $postData['attrValue']; //属性值
			$attrPrice = $postData['attrPrice'];//属性价格
			//循环入库
			foreach($attrValue as $attr_id=>$attr_values){
				//如果属性值是单选属性，则$attr_values是数组
				if(is_array($attr_values)){
					//单选属性,需要循环入库
					foreach($attr_values as $k => $single_attr_value){
						$data = [
							'goods_id' => $goods_id,
							'attr_id' =>$attr_id,
							'attr_value' => $single_attr_value,
							'attr_price' => $attrPrice[$attr_id][$k],
							'create_time' => time(),
							'update_time' => time()
						];
						Db::name('goods_attr')->insert($data);
					}

				}else{
					//唯一属性
					$data = [
							'goods_id' => $goods_id,
							'attr_id' =>$attr_id,
							'attr_value' => $attr_values,
							'create_time' => time(),
							'update_time' => time()
					];
					Db::name('goods_attr')->insert($data);
				}
			}
		});
	}
}