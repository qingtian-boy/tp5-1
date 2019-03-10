<?php 
namespace app\home\controller; //定义当前类所在的命名空间
use think\Controller; 	//引入Controller核心控制器
use app\home\model\Category;
use app\home\model\Goods;
class IndexController extends Controller {
    
	public function index(){
		// dump(sendEmail('1259481020@qq.com','晚上聚餐','广州塔最顶层'));die;
		// echo  mt_rand(1000,9999);die;
		//测试短信封装的是否成功
		// dump( sendSms('15815740424',[6666,5]) );die;
		/*****************1.首页导航栏分类（is_show=1)）**********/
		$catModel = new Category();
		$navCats = $catModel->getNavCat();

		/*****************2.取出三级分类筛选的数据***************/
		$oldCats = Category::select()->toArray();
		//技巧1：
		$cats = [];
		foreach($oldCats as $v){
			$cats[ $v['cat_id'] ] = $v;
		}
		//技巧2：
		$children = [];
		foreach($oldCats as $v){
			$children[ $v['pid'] ][] = $v['cat_id'];
		}
		/************3.取出首页的推荐位商品*****************/
		$goodsModel = new Goods();
		$hotGoods = $goodsModel->getTypeGoods('is_hot',5); //热卖
		$bestGoods = $goodsModel->getTypeGoods('is_best',5); //推荐
		$newGoods = $goodsModel->getTypeGoods('is_new',5); //新品
		$crazyGoods = $goodsModel->getTypeGoods('is_crazy',5); //疯狂抢购
		return $this->fetch('',[
			'navCats' =>$navCats,     
			'cats' => $cats,      
			'children' => $children,    
			'hotGoods' => $hotGoods,   
			'bestGoods' => $bestGoods,    
			'newGoods' =>$newGoods,   
			'crazyGoods' => $crazyGoods
		]);
	}

}