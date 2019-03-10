<?php 
namespace app\admin\controller; //定义当前类所在的命名空间
use app\admin\model\Category;
class CategoryController extends CommonController {


	public function index(){
		//取出所有的分类数据，分配到模板
		$catModel = new Category();
		$cats = $catModel->select()->toArray();
		$catsTree = $catModel->getSonsTree($cats);
		// dump($catsTree);die;
		return $this->fetch('',[
			'cats' => $catsTree
		]);
	}
    
    public function add(){
    	//1.判断是否是post
		if(request()->isPost()){
			//2.接受参数
			$postData = input('post.');
			//3.验证器验证
			$result = $this->validate($postData,"Category.add",[],true);
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//4.写入数据库
			$categoryModel = new Category();
			if($categoryModel->save($postData)){
				$this->success("编辑成功",url("/admin/category/index"));
			}else{
				$this->error("编辑失败");
			}
		}
    	//取出所有的分类（无限极处理）
    	$catModel = new Category();
    	$cats = $catModel->select();
    	$catsTree = $catModel->getSonsTree($cats);
    	//无限极处理
    	return $this->fetch('',[
    		'cats' => $catsTree
    	]);
    }

}