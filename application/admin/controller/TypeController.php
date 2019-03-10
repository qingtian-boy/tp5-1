<?php 
namespace app\admin\controller; //定义当前类所在的命名空间
//use think\Controller; 	//引入Controller核心控制器
use app\admin\model\Type;
class TypeController extends CommonController {

	public function del(){
		//接受参数，判断删除是否成功，进行跳转就可以
		$type_id = input('type_id');
		if(Type::destroy($type_id)){
			$this->success("删除成功",url("/admin/type/index"));
		}else{
			$this->error("删除失败");
		}
	}


	public function upd(){
		//post添加或编辑入库操作
		//1.判断是否是post
		if(request()->isPost()){
			//2.接受参数
			$postData = input('post.');
			//3.验证器验证
			$result = $this->validate($postData,"Type.upd",[],true);
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//4.写入数据库
			$typeModel = new Type();
			if($typeModel->update($postData)){
				$this->success("编辑成功",url("/admin/type/index"));
			}else{
				$this->error("编辑失败");
			}
		}

		//get方式
		$type_id = input('type_id');
		$typeData = Type::find($type_id);
		return $this->fetch('',[
			'typeData' => $typeData
		]);
	}

	public function index(){
		//获取所有的分类的数据，分配到模板中
		$types = Type::select();
		return $this->fetch('',[
			'types' => $types
		]);
	}
    
    public function add(){
    	//1.判断是否是post请求
    	if(request()->isPost()){
    		//2.接收post参数
    		$postData = input('post.');
    		//3.验证器验证
    		$result = $this->validate($postData,'Type.add',[],true);
    		if($result !== true){
    			$this->error(implode(',',$result));
    		}
    		//4.实例化模型写入数据库
    		$typeModel = new Type();
    		if($typeModel->allowField(true)->save($postData)){
    			$this->success("添加成功",url("/admin/type/index"));
    		}else{
    			$this->error("添加失败");
    		}
    	}
    	return $this->fetch('');
    }

}