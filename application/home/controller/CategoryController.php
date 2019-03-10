<?php 
namespace app\home\controller; //定义当前类所在的命名空间
use think\Controller; 	//引入Controller核心控制器
use app\home\model\Category;
use app\home\model\Goods;
class CategoryController extends Controller {
    
	public function index(){
		// $arr = ['a','b','c'];
		// dump(array_reverse($arr));die;
		/********1.面包屑导航******************************/
		$cat_id = input('cat_id');
		//递归找当前分类的祖先分类
		$catModel = new  Category();
		$cats = $catModel->select()->toArray();
		$familyCats = $catModel->getFamilyCat($cats,$cat_id);
		/*********2.找当前分类的子孙分类id***************/
		$sonsCatId = $catModel->getSonCat($cats,$cat_id);
		//把当前分类id也加入进来
		$sonsCatId[] = intVal($cat_id); // [7,8,3] '7,3,8'
		//取出指定分类的商品
		$where = [
			'is_sale' => 1,
			'is_delete' => 0,
			'cat_id' => ['in',$sonsCatId]  // 支持数组也支持字符串  [7,8,3] 或 '7,3,8'  
		];
		$goods = Goods::where($where)->select()->toArray();

		/********3.完成分类列表页的分类菜单功能************/
		//技巧1：
		$menuCats = [];
		foreach($cats as $v){
			$menuCats[ $v['cat_id'] ] = $v;
		}
		//技巧2：
		$children = [];
		foreach($cats as $v){
			$children[ $v['pid'] ][] = $v['cat_id'];
		}
		return $this->fetch('',[
			'familyCats' => $familyCats,   
			'goods' => $goods,  
			'menuCats' => $menuCats,   
			'children' => $children
		]);
	}

}