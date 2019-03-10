<?php 
namespace app\home\model;
use think\Model;
use app\home\model\Goods;
use think\Db;

class Goods extends Model{
	protected $pk = 'goods_id';

	//取出首页推荐位的商品
	public function getTypeGoods($type,$limit){
		//is_hot = 1
		//定义查询商品的初始化条件
		$where=[
			'is_delete' => 0,  //不在回收站中
			'is_sale'   => 1   //上架中
		];
		//要根据传递的商品类型组装查询条件
		if($type == 'is_crazy'){
			//数据表没有is_crazy字段,根据价格升序，通过limit取出$limit条
			return $this->where($where)->order('goods_price asc')->limit($limit)->select();
		}else{
			//其他三个类型，表中有这三个字段（is_hot、is_new、is_best）,需要加入条件where中
			$where[$type] = 1;
			return $this->where($where)->limit($limit)->select();
		}
		
	}

	//实现商品的浏览历史
	public function addGoodsToHistory($goods_id){
		//加入之前，之前的cookie可能已经有商品，所以要先取出来
		$history = cookie('history')?:[];
		if($history){
			//说明浏览历史已经有数据
			//1.在数组的头部加入我们访问的商品
			array_unshift($history, $goods_id);
			//2.因为加入的商品可能是重复，需要把商品进行去重
			$history = array_unique($history);
			//3.如果加入的商品超过我们指定的数量（5），需要把最先访问商品id给删除 
			if(count($history) > 5){
				array_pop($history);
			}
		}else{
			//说明浏览历史没有数据,需要把商品id加入进去
			$history[] = $goods_id;
		}
		//需要重新把数组在写入到cookie，便于下次获取
		cookie('history',$history,60*60*24*7);  //存储一个星期 单位秒 ，存储一个星期
		//dump($history);die; //要先写入到cookie再去打印
		//取出浏览历史中的商品 [1,5,6]
		// $where = [
		// 	'is_sale' => 1,
		// 	'is_delete' => 0,
		// 	'goods_id' => ['in',$history]
		// ];
		$history = implode(',',$history);
		//由于tp中不可以使用field此方法，所以我们可以改为原生去执行
		//$historyData = Goods::where($where)->order("field('goods_id',$history)")->select()->toArray();
		$sql = "select * from sh_goods where goods_id in (".$history.") and is_sale = 1 and is_delete = 0 order by field(goods_id,".$history.")";
		$historyData = Db::query($sql);
		//返回浏览历史的商品数据  
		return $historyData;
	}
}