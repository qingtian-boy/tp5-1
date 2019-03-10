<?php 
namespace app\home\model;
use think\Model;
use think\Db;

class Cart extends Model{
	protected $pk = 'cart_id';

	//封装一个把商品加入到购物车的方法
	public function  addCart($goods_id,$goods_attr_ids,$goods_number){
		//1.加入商品之前，先判断是否有相同的商品，如果有，数量直接加$goods_number  
		$where = [
			'goods_id' => $goods_id,
			'goods_attr_ids' => $goods_attr_ids,
			'member_id' => session('member_id')
		];
		$result = $this->where($where)->find();
		if($result){
			//递增一个字段值  setInc('id')，不写第二个参数默认是1  递减 setDec('id',5)
			return $this->where($where)->setInc('goods_number',$goods_number);
		}

		//2.如果没有相同商品，则把商品进行入库操作
		$data = [
			'goods_id' => $goods_id,
			'goods_attr_ids' => $goods_attr_ids,
			'member_id' => session('member_id'),   
			'goods_number' =>$goods_number
		];

		return $this->save($data);
	}


	//获取购物车商品的完整数据
	public function getCart(){
		//取出当前会员用户所有的购物车商品
		$cartData = $this->where("member_id",session('member_id'))->select()->toArray();
		$newCartData = [];
		//循环$cartData数组
		foreach($cartData as $v){
			//给$v加入一个goodsInfo下标，为了记录当前商品的信息
			$v['goodsInfo'] = Db::name('goods')->find($v['goods_id']);
			//$v加入一个attr的下标，为了记录当前商品所有的属性信息
			$v['attr'] = Db::name('goods_attr')
							->alias('t1')
							->field("sum(attr_price) AS attrTotalPrice,GROUP_CONCAT(attr_name,':',attr_value,'¥',attr_price SEPARATOR '<br />') AS attrInfo")
							->join('sh_attribute t2','t1.attr_id=t2.attr_id','left')
							->where('goods_attr_id','in',$v['goods_attr_ids'])
							->find();
			$newCartData[] = $v;
		}
		return $newCartData;
	}
}