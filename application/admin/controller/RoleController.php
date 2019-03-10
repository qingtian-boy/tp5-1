<?php 
namespace app\admin\controller;
use app\admin\model\Auth;
use app\admin\model\Role;
use think\Db;
class RoleController extends CommonController{


	public function upd(){
		//1.判断是否是post
		if(request()->isPost()){
			//2.接受参数
			$postData = input('post.');
			//3.验证器验证
			$result = $this->validate($postData,"Role.upd",[],true);
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//4.写入数据库
			$roleModel = new Role();
			if($roleModel->update($postData)){
				$this->success("编辑成功",url("/admin/role/index"));
			}else{
				$this->error("编辑失败");
			}
		}


		$role_id = input('role_id');
		$roleData = Role::find($role_id);
		//取出所有的权限数据
		$oldAuths = Auth::select()->toArray();

		//两个技巧构造数据
		//技巧1：循环$oldAuths数组，以每个元素的主键值作为其下标
		$auths = [];
		foreach($oldAuths as $v){
			$auths[ $v['auth_id'] ] = $v;
		}
		//技巧2：循环$oldAuths数组，通过pid进行分组
		$children = [];
		foreach($oldAuths as $v){
			$children[ $v['pid'] ][] = $v['auth_id'];
		}
		return $this->fetch('',[
			'roleData' => $roleData,   
			'auths' => $auths,    
			'children'=>$children
		]);
	}

	public function index(){
		//取出所有的角色数据
		//$roles = Role::select();
		$sql = "select t1.*, GROUP_CONCAT(t2.auth_name SEPARATOR '|') as allAuth from sh_role t1 left join sh_auth t2 on FIND_IN_SET(t2.auth_id,t1.auth_ids_list) group by t1.role_id";
		$roles = Db::query($sql);
		return $this->fetch('',[
			'roles' => $roles
		]);
	}

	public function add(){
		//1.判断post请求
		if(request()->isPost()){
			//2.接受post参数
			$postData = input('post.');
			//3.验证器验证
			$result = $this->validate($postData,"Role.add",[],true);
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//4.写入数据库
			$roleModel = new Role();
			if($roleModel->save($postData)){
				$this->success("入库成功",url("/admin/role/index"));
			}else{
				$this->error("入库失败");
			}
		}

		//取出所有的权限数据
		$oldAuth = Auth::select()->toArray();
		//dump(['aa','bb']);die;
		//dump($oldAuth);die;
		//奇淫技巧
		//技巧1：循环$oldAuth数组，以每个元素的主键值作为其对应的下标
		$auths = [];
		foreach($oldAuth as $v){
			//如果数组中括号中[]没有写值，下标默认从0开始递增
			$auths[ $v['auth_id'] ] = $v;
		}
		
		//技巧二：循环$oldAuth数组，把每个元素通过pid进行分组，即把相同pid的元素划分为同一组
		$children = [];
		foreach($oldAuth as $v){
			$children[ $v['pid'] ][] = $v['auth_id'];
		}
		//dump($auths);die;
		return $this->fetch('',[
			'auths' => $auths,    
			'children' => $children
		]);
	}




	
}