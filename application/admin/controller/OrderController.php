<?php 
namespace app\admin\controller; //定义当前类所在的命名空间
//use think\Controller; 	//引入Controller核心控制器
use app\admin\model\Order;
class OrderController extends CommonController {



	//ajax获取订单的物流信息
	public function getWuliu(){
		if(request()->isAjax()){
			$company = input('company');
			$number = input('number');
			//现在php需要做代理请求第三物流的接口地址
			$url = "http://www.kuaidi100.com/applyurl?key=9d37bc6b0a41e6fe&com={$company}&nu={$number}&show=0";

			//string file_get_contents() 函数可以模拟get请求
			echo file_get_contents($url);
		}
	}


	public function upd(){
		if(request()->isPost()){
			$postData = input('post.');
			$result = $this->validate($postData,"Order.upd",[],true);
			if($result!==true){
				$this->error( implode(',',$result) );
			}
			//更新订单的物流信息和把发货状态改为已发货send_status = 1
			$postData['send_status'] = 1;
			$orderModel = new Order();
			if($orderModel->update($postData)){
				$this->success("设置成功",url("/admin/order/index"));
			}else{
				$this->error("设置失败");
			}
		}


		$order_id = input('order_id');
		$orderData = Order::where('order_id',$order_id)->find();
		return $this->fetch('',[
			'orderData' => $orderData
		]);
	}
    
    public function index(){
    	//1.取出所有的订单数据，分配到模板中
    	$order = Order::select();
    	return $this->fetch('',[
    		'order' => $order
    	]);
    	
    }

}