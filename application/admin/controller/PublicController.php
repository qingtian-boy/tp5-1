<?php 
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User;
class PublicController extends Controller{


	public function logout(){
		//1.清除session
		session('user_id',null);
		session('username',null);
		//2.重定向到登录页
		$this->redirect('/admin/public/login');
	}

	public function login(){
		//1.判断post请求
		if(request()->isPost()){
			//2.接受post参数
			$postData = input('post.');
			//3.验证器验证
			$result = $this->validate($postData,"User.login",[],true);
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//4.调用模型的checkUser方法来检测用户名和密码是否匹配
			$userModel = new User();
			$status = $userModel->checkUser($postData['username'],$postData['password']);
			if($status){
				
				//重定向到首页
				$this->redirect('/admin/index/index');
			}else{
				$this->error('用户名或密码失败');
			}
		}
		
		return $this->fetch();
	}

}