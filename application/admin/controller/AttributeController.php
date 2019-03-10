<?php 
namespace app\admin\controller; //定义当前类所在的命名空间
use app\admin\model\Type;
use app\admin\model\Attribute;
class AttributeController extends CommonController {

	public function del(){
		//接受参数，判断删除是否成功
		$attr_id = input('attr_id');
		if(Attribute::destroy($attr_id)){
			$this->success("删除成功",url("/admin/attribute/index"));
		}else{
			$this->error("删除失败");
		}
	}


	public function upd(){
		//1.判断是否是post
		if(request()->isPost()){
			//2.接受参数
			$postData = input('post.');
			//3.验证器验证
			//如果属性录入方式列表选择，需要验证upd场景
			if($postData['attr_input_type'] == 1){
				$result = $this->validate($postData,"Attribute.upd",[],true);
			}else{
				//手工输入
				$result = $this->validate($postData,"Attribute.checkNameAndType",[],true);
			}
			
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//4.写入数据库
			$attributeModel = new Attribute();
			if($attributeModel->update($postData)){
				$this->success("编辑成功",url("/admin/attribute/index"));
			}else{
				$this->error("编辑失败");
			}
		}

		$attr_id = input('attr_id');
		$attrData = Attribute::find($attr_id);
		$types = Type::select();
		return $this->fetch('',[
			'attrData' => $attrData,
			'types' => $types
		]);
	}

	public function index(){
		//取出所有的权限数据，分配到模板
		$attributes = Attribute::alias('t1')
				->field('t1.*,t2.type_name')
				->join('sh_type t2','t1.type_id = t2.type_id','left')
				->select();
		return $this->fetch('',[
			'attributes' => $attributes
		]);
	}
    
    public function add(){
    	//1.判断是否是post
		if(request()->isPost()){
			//2.接受参数
			$postData = input('post.');
			//3.验证器验证
			//如果是列表选择（attr_input_type == 1）
			if($postData['attr_input_type'] == 1){
				//列表选择
				$result = $this->validate($postData,"Attribute.add",[],true);
			}else{
				//手工输入
				$result = $this->validate($postData,"Attribute.checkNameAndType",[],true);
			}
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//4.写入数据库
			$attributeModel = new Attribute();
			if($attributeModel->save($postData)){
				$this->success("入库成功",url("/admin/attribute/index"));
			}else{
				$this->error("入库失败");
			}
		}

    	//要获取所有的商品类型，并分配到模板中(get)
    	$types = Type::select();
    	return $this->fetch('',[
    		'types' => $types
    	]);
    }

}