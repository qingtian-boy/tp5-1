<?php 
namespace app\home\controller; //定义当前类所在的命名空间
use think\Controller; 	//引入Controller核心控制器
use app\home\model\Goods;
use app\home\model\Category;
use think\Db;
use app\home\model\Cart;
class GoodsController extends Controller {


    public function addGoodsToCart(){
        //1.判断是否是ajax
        if(request()->isAjax()){
            //2.判断是否登录,未登录需要提示用户进行登录
            if(!session('member_id')){
                $response = ['code'=>-1,'message'=>'请先登录'];
                echo json_encode($response);exit;
            }
            //3.登录了，需要把商品加入到购物车
            $cartModel = new Cart();
            $goods_id = input('goods_id');
            $goods_attr_ids = input('goods_attr_ids');
            $goods_number = input('goods_number');
            //4.调用模型方法进行加入购物车
            if($cartModel->addCart( $goods_id,$goods_attr_ids,$goods_number )){
                $response = ['code'=>200,'message'=>'加入购物车成功'];
                echo json_encode($response);exit;
            }else{
                $response = ['code'=>-2,'message'=>'加入购物车失败，稍后再试'];
                echo json_encode($response);exit;
            }
        }
        
    }
    
    public function detail(){
    	/*************1.取出商品的信息******************/
    	$goods_id = input('goods_id');
    	$goodsData = Goods::find($goods_id)->toArray();
    	/*************2.商品详情页的面包屑导航**********/
    	$catModel = new Category();
    	$cats = $catModel->select();
    	$familyCats = $catModel->getFamilyCat($cats,$goodsData['cat_id']);
    	/*************3.完成商品详情的图片画廊展示********/
    	//由于图片在表中存储是json格式，在这里我们使用json_decode提前把他转换成数组格式
    	$goodsData['goods_img'] = json_decode($goodsData['goods_img']);
    	$goodsData['goods_middle'] = json_decode($goodsData['goods_middle']);
    	$goodsData['goods_thumb'] = json_decode($goodsData['goods_thumb']);
    	/**************4.取出唯一属性(attr_type = 0)**********************************/
    	$only_attr = Db::name('goods_attr')
    		->alias('t1')
    		->field('t1.*,t2.attr_name')
    		->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
    		->where('t1.goods_id',$goods_id)
    		->where('t2.attr_type = 0')
    		->select();

    	/**************5.取出单选属性(attr_type = 1)**********************************/
    	$single_attr = Db::name('goods_attr')
    		->alias('t1')
    		->field('t1.*,t2.attr_name')
    		->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
    		->where('t1.goods_id',$goods_id)
    		->where('t2.attr_type = 1')
    		->select();
    	//由于单选属性有多个，所以这多个的属性值attr_id是一样的，所以我们可以通过attr_id进行分组，
    	//把具有相同attr_id分为同一组，这样便于在模板中进行遍历循环
    	$singleAttr = [];
    	foreach($single_attr as $v){
    		$singleAttr[ $v['attr_id'] ][] = $v;
    	}

        /***********6.完成商品的浏览历史，存放在cookie*********************/
        $goodsModel = new Goods();
        $historyData = $goodsModel->addGoodsToHistory($goods_id);
        // dump($singleAttr);die;

    	return $this->fetch('',[
    		'goodsData' => $goodsData,     
    		'familyCats' => $familyCats,  
    		'only_attr' => $only_attr,   
    		'singleAttr' => $singleAttr,  
            'historyData' => $historyData
    	]);
    }

}