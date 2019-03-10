<?php 
namespace app\admin\controller;
// use think\Controller;
use app\admin\model\User;
use app\admin\model\Role;
class UserController extends CommonController{


	public function upd(){
		//1.判断post请求
		if(request()->isPost()){
			//2.接受参数
			$postData = input('post.');
			//3.验证
			//只有密码和确认密码有一个不为空，就需要upd场景进行验证
			if($postData['password'] != '' || $postData['repassword'] != '' || $postData['role_id'] == ''){
				$result = $this->validate($postData,"User.upd",[],true);
				if($result!==true){
					$this->error( implode(',',$result) );
				}
			}
			
			//‘’  ‘’   111111  111111
			//4.写入数据库
			$userModel = new User();
			if($userModel->update($postData)){
				$this->success('编辑成功',url('/admin/user/index'));
			}else{
				$this->error('编辑失败');
			}
		}

		$user_id = input('user_id');
		$userData = User::find($user_id);
		//取出所有的角色数据并分配
		$roles = Role::select();
		return $this->fetch('',[
			'userData' => $userData,
			'roles' => $roles,
		]);
	}


	public function del(){
		//1.接受参数
		$user_id = input('user_id');
		//2.判断删除是否成功进行跳转
		if(User::destroy($user_id )){
			$this->success('删除成功',url('/admin/user/index'));
		}else{
			$this->error('删除失败');
		}
	}

	public function index(){
		//取出用户表的所有的数据并分配
		// $users = User::select();
		$users = User::alias('t1')
			->field('t1.*,t2.role_name')
			->join("sh_role t2",'t1.role_id = t2.role_id','left')
			->paginate(2);
		return $this->fetch('',[
			'users' => $users
		]);
	}

	public function add(){
		//1.判断post请求
		if(request()->isPost()){
			//2.接受post参数
			$postData = input('post.');
			//3.验证
			$result = $this->validate($postData,"User.add",[],true);
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//4.写入（增删改）数据库
			$userModel = new User();
			//密码需要加密(后面抽离到模型中去，让控制器只做逻辑)
			//$postData['password'] = md5($postData['password'].config('password_salt'));
			if($userModel->allowField(true)->save($postData)){
				$this->success('入库成功',url('/admin/user/index'));
			}else{
				$this->error('入库失败');
			}
		}
		
		//取出所有的角色分配到模板中
		$roles = Role::select();
		return $this->fetch('',[
			'roles' => $roles
		]);
	}
}