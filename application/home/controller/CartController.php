<?php 
namespace app\Home\controller; //定义当前类所在的命名空间
use think\Controller; 	//引入Controller核心控制器
use app\home\model\Cart;
class CartController extends Controller {


	public function updCart(){
		//1.判断ajax请求
		if(request()->isAjax()){
			//2.接受cart_id和goods_number
			$cart_id = input('cart_id');
			$goods_number = input('goods_number');
			//3.更新数量
			$result = Cart::where('cart_id',$cart_id)->setField('goods_number',$goods_number);
			if($result){
				$response = ['code'=>200,'message' => '更新成功'];
			}else{
				$response = ['code'=>-1,'message' => '更新失败'];
			}
			echo json_encode($response);
		}
	}


	public function delCart(){
		//1.判断是否是ajax
		if(request()->isAjax()){
			//2.接收cart_id参数，进行删除数据，响应json数据给客户端
			$cart_id = input('cart_id');
			if(Cart::destroy($cart_id)){
				$response = ['code'=>200,'message'=>'删除成功'];
			}else{
				$response = ['code'=>-1,'message'=>'删除失败'];
			}
			echo json_encode($response);
		}
	}


    
    public function index(){
    	if(!session('member_id')){
    		$this->error('请先登录',url('/home/public/login'));
    	}
    	//取出所有的购物车商品的数据
    	$cartModel = new Cart();
    	$cartData = $cartModel->getCart();
    	// dump($cartData);die;
    	return $this->fetch('',[
    		'cartData' => $cartData
    	]);
    }

}