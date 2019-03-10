<?php 
namespace app\admin\controller;
use app\admin\model\Auth;
class AuthController extends CommonController{


	public function ajaxDel(){
		//1.判断是否是ajax请求
		if(request()->isAjax()){
			//2.接受参数
			$auth_id = input('auth_id');
			//3.判断当前删除的权限下面是否有子孙权限
			$sons = Auth::where('pid','=',$auth_id)->find();
			
			if($sons){
				//有子孙：则不能删除，响应json数据
				$response = ['code'=>'-1','message'=>'含有子权限，无法删除'];
				echo json_encode($response);die;
			}
				
			//没有子孙，则可以删除，响应json数据
			if(Auth::destroy($auth_id)){
				$response = ['code'=>'200','message'=>'删除成功'];
				echo json_encode($response);die;
			}else{
				$response = ['code'=>'-2','message'=>'删除失败'];
				echo json_encode($response);die;
			}

		}
	}


	public function upd(){
		//1.判断post请求
		if(request()->isPost()){
			//2.接受post参数
			$postData = input('post.');
			//3.验证
			if($postData['pid'] == 0){
				//顶级
				$result = $this->validate($postData,'Auth.checkAuthName',[],true);
			}else{
				//非顶级
				$result = $this->validate($postData,'Auth.upd',[],true);
			}
			if($result!==true){
				$this->error( implode(',',$result) );
			}
			//4.写入数据库
			$authModel = new Auth();
			if($authModel->update($postData)){
				$this->success("编辑成功",url("/admin/auth/index"));
			}else{
				$this->error("编辑失败");
			}
		}
		
		$auth_id = input('auth_id');
		$authModel = new Auth();
		$authData = $authModel->find($auth_id);
		//取出无限极分类的所有的权限并分配
		$auths = $authModel->select();
		$authsTree = $authModel->getSonsTree($auths);
		return $this->fetch('',[
			'authData' => $authData,   
			'auths' => $authsTree
		]);
	}


	public function index(){
		//取出所有的权限并分配
		$authModel = new Auth();
		$auths = $authModel
				->field('t1.*,t2.auth_name p_name')
				->alias('t1')
				->join("sh_auth t2",'t1.pid = t2.auth_id','left')
				->select();
		//无限极递归处理
		$authsTree = $authModel->getSonsTree($auths);
		return $this->fetch('',[
			'auths' => $authsTree
		]);
	}

	public function add(){
		//1.判断post请求
		if(request()->isPost()){
			//2.接受post参数
			$postData = input('post.');
			//3.验证器验证
			
			if($postData['pid'] == 0){
				//如果是顶级权限pid=0,只验证auth_name，即checkAuthName场景
				$result = $this->validate($postData,"Auth.checkAuthName",[],true);
			}else{
				//非顶级pid!=0,要验证add场景
				$result = $this->validate($postData,"Auth.add",[],true);
			}
			
			if($result!==true){
				$this->error( implode(',',$result) );
			}
			//4.写入数据库
			$authModel = new Auth();
			if($authModel->save($postData)){
				$this->success("入库成功",url("/admin/auth/index"));
			}else{
				$this->error("入库失败");
			}
		}
		
		//要获取所有的权限（无限极递归处理）
		$authModel = new Auth();
		$auths = $authModel->select()->toArray();
		//无限极处理一下
		$authsTree = $authModel->getSonsTree($auths);
		return $this->fetch('',[
			'auths' => $authsTree
		]);
	}
}