<?php 
namespace app\home\controller; //定义当前类所在的命名空间
use think\Controller; 	//引入Controller核心控制器
use app\home\model\Cart;
use think\Db;
use app\home\model\Order;
class OrderController extends Controller {



	public function selfPayMoney(){
		//注意:一个订单只能支付一次，支付宝会进行检测的。
		//$this->_payMoney('53534653465','京西商城','666');die;
		//1.接收订单号id
		$order_id = input('order_id');
		//2.查询订单的金额
		$totalPrice = Order::where("order_id",$order_id)->value('total_price');
		//3.需要去支付宝付款
		$this->_payMoney($order_id,'京西商城',$totalPrice);
	}


	public function selfOrder(){
		//1.判断是否登录
		$member_id = session('member_id');
		if(!$member_id){
			$this->error('请先登录',url('/home/public/login'));
		}
		//2.查询出个人的所有的订单，并且分配到模板中展示
		$order = Order::where("member_id",$member_id)->select();
		return $this->fetch('',[
			'order' => $order
		]);
	}



	//支付宝post异步通知，(只能是线上环境通过，并且网站需要备案通过)
	//并且还会带一些支付成功的参数来
	public function notifyUrl(){
		// dump( input('get.') );
		// $get = input('get.');
		require_once("../extend/alipay/config.php");
		require_once '../extend/alipay/pagepay/service/AlipayTradeService.php';
		$arr=input('post.');
		//实例化系统内置类和第三方引入过来的类，前面需要加个反斜杠
		$alipaySevice = new \AlipayTradeService($config); 
		$result = $alipaySevice->check($arr);

		if($result) {//验证成功
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
			//商户订单号（自己网站订单号）
			$out_trade_no = htmlspecialchars($arr['out_trade_no']);
			//支付宝交易号
			$trade_no = htmlspecialchars($arr['trade_no']);
			//需要修改支付的状态为已支付 pay_status 改为 1
			$data = [
				'pay_status' => 1 , //已付款
				'ali_order_id' => $trade_no // 记录支付宝的交易流水号
			];
			$orderModel = new Order();
			$orderModel->where('order_id',$out_trade_no)->update($data);
			echo 'success'; //异步一定要输出success字符，支付宝才认为你是支付成功。
		}
	}

	//支付宝get同步通知，
	//并且还会带一些支付成功的参数来
	public function returnUrl(){
		// dump( input('get.') );
		// $get = input('get.');
		require_once("../extend/alipay/config.php");
		require_once '../extend/alipay/pagepay/service/AlipayTradeService.php';
		$arr=input('get.');
		//实例化系统内置类和第三方引入过来的类，前面需要加个反斜杠
		$alipaySevice = new \AlipayTradeService($config); 
		$result = $alipaySevice->check($arr);

		if($result) {//验证成功
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
			//商户订单号（自己网站订单号）
			$out_trade_no = htmlspecialchars($arr['out_trade_no']);
			//支付宝交易号
			$trade_no = htmlspecialchars($arr['trade_no']);
			//需要修改支付的状态为已支付 pay_status 改为 1
			$data = [
				'pay_status' => 1 , //已付款
				'ali_order_id' => $trade_no // 记录支付宝的交易流水号
			];
			$orderModel = new Order();
			$orderModel->where('order_id',$out_trade_no)->update($data);
			//展示支付成功的模板
			return $this->fetch('success');
		}
	}



	public function writeOrderInfo(){
		$member_id =session('member_id');
		if(!$member_id){
			$this->error('请先登录',url('/home/public/login'));
		}
		//1.接收收货人的基本信息，需要把订单商品入库到订单表和订单商品表
		$postData = input('post.');
		//2.准备生产一个订单号order_id
		$order_id = date('ymd').time();
		//3.得到购物车所有的商品，计算出总价
		$cartModel = new Cart();
		$cartData = $cartModel->getCart();
		$totalPrice = 0; // 总价
		foreach($cartData as $v){
			$goods_attr_price = ($v['goodsInfo']['goods_price']+$v['attr']['attrTotalPrice'])*$v['goods_number'];
			$totalPrice+=$goods_attr_price;
		}
		//开启事务，保证数据的一致性
		Db::startTrans();
		try{
			//4.开始订单表入库,准备订单表所需数据
			$orderData = $postData;
			$orderData['order_id'] = $order_id;
			$orderData['total_price'] = $totalPrice;
			$orderData['member_id'] = $member_id;
			$orderResult = Order::create($orderData);
			//判断订单表是否成功,如果失败，需要手动抛出异常，触发回滚
			if(!$orderResult){
				throw new \Exception('订单表入库失败');
			}
			//如果上面抛出异常，则不会执行其下面的代码
			//5.只有订单表入库成功之后才可以入库订单商品表
			//需要循环购物车数据，入库到订单商品表-同时要扣去商品对应的库存
			foreach($cartData as $v){
				$goods_attr_price = ($v['goodsInfo']['goods_price']+$v['attr']['attrTotalPrice'])*$v['goods_number'];
				//准备订单商品表数据
				$orderGoodsData = [
					'order_id' => $order_id,
					'goods_id' => $v['goods_id'],
					'goods_attr_ids' => $v['goods_attr_ids'],
					'goods_number' => $v['goods_number'],
					'goods_price' => $goods_attr_price
				];
				$orderDataResult = Db::name('order_goods')->insert($orderGoodsData);
				//还要扣去对应商品的购买的库存
				//什么时候扣？只有商品表中的数量大于或等于我们所购买的数量才进行扣库存
				$where = [
					'goods_id' => $v['goods_id'],
					'goods_number' => ['>=',$v['goods_number']]
				];
				$goodsResult = Db::name('goods')->where($where)->setDec('goods_number',$v['goods_number']);
				//只有订单商品表和扣库存同时成功才可以进行下一次foreach,其中一个操作失败了，就会抛出异常，从而触发回滚
				if(!$orderDataResult || !$goodsResult){
					throw new \Exception('订单表入库失败或库存不足');
				}
			}
			//6.上面都成功后才可以提交事务，并且清空购物车
			Db::commit();
			Db("cart")->where('member_id',$member_id)->delete();
			
		}catch(\Exception $e){
			//$e是我们异常对象，可以通过异常对象获取到抛出来的错误信息
			//在tp5框架中实例化php系统类，需要在前面加一个反斜杠
			//只有try中代码有问题（抛出异常）,try下方的代码就不会执行，则执行catch中的代码
			Db::rollback(); //回滚操作
			$this->error( $e->getMessage() );  //获取上面的抛出的错误信息
		}

		//7.上面都成功之后，就要唤起支付宝进行支付操作
		// echo '正在支付中......';
		// 调用支付宝支付方法
		$this->_payMoney($order_id,'支付测试..',$totalPrice);
		
	}


	//定义一个方法，唤起支付宝进行支付
	public function _payMoney($order_id,$title,$totalPrice,$body='支付-京西商城'){
		include "../extend/alipay/pagepay/pagepay.php";
	}
    
	public function orderInfo(){
		//取出购物车数据分配到模板中
		$cartModel = new Cart();
		$cartData = $cartModel->getCart();
		return $this->fetch('',[
			'cartData' => $cartData
		]);

	}





}