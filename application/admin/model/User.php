<?php 
namespace app\admin\model;
use think\Model;
use app\admin\model\Role;
use app\admin\model\Auth;
class User extends Model{
	protected $pk = "user_id";
	protected $autoWriteTimestamp = true;


	//定义一个初始化方法init(定义钩子的代码)
	protected static function init(){
		//定义前钩子
		User::event('before_insert',function($user){
			//$user是表单提交过来的数据，已转化为一个当前模型的数据对象（是一个即将入库的记录）
			//入库之前，可以把密码在这里进行加密处理
			//dump($user->password); // 加密前明文
			$user['password'] = md5($user['password'].config('password_salt'));
			//dump($user->password); // 加密后密文
			//echo '前钩子';die;
		});
		//....定义当前模型更多的钩子;
		

		//编辑的前钩子
		User::event('before_update',function($user){
			//更新删除repassword 
			unset($user['repassword']);
			//如果密码为空，说明不想修改密码，要保留原密码
			if($user['password'] == ''){
				unset($user['password']);
			}else{
				//说明需要修改密码，需要md5加密拼接盐
				$user['password'] = md5($user['password'].config('password_salt'));
			}
			//dump($user);die;
		});
	}


	//定义一个判断用户名和密码是否匹配的方法
	public function checkUser($username,$password){
		//定义查询的条件
		$where = [
			'username' => $username,
			'password' => md5( $password.config('password_salt') )
		];
		$userInfo = $this->where($where)->find();
		if($userInfo){
			//把用户的信息写入到session
			session('user_id',$userInfo['user_id']);
			session('username',$userInfo['username']);
			//根据用户的角色id把应有的权限保存在session中，供left。html模板中使用
			//方法名只要是_开头，一般都是私有的private
			$this->_initAuth($userInfo['role_id']);
			return true;
		}else{
			return false;
		}
	}


	//初始化角色应用的权限到session中去
	private function _initAuth($role_id){
		//根据角色id去角色表中查询auth_ids_list字段值
		$auth_ids_list = Role::where('role_id',$role_id)->value('auth_ids_list');

		if($auth_ids_list == '*'){
			//说明是超级管理员
			$allAuth = Auth::select()->toArray();
		}else{
			//说明是非超级管理员,只能取出自己在auth_ids_list中应有的权限
			$allAuth = Auth::where('auth_id','in',$auth_ids_list)->select()->toArray();
		}

		//因为满足两个技巧的条件
		//技巧1：以每个元素的主键值作为其下标
		$auths = [];
		foreach($allAuth as $v){
			$auths[ $v['auth_id'] ] = $v;
		}
		//技巧2：以指向父字段的值pid进行分组
		$children = [];
		foreach($allAuth as $vv){
			$children[ $vv['pid'] ][] = $vv['auth_id'];
		}
		//把$auhts和$children存储到session中去，
		session('auths',$auths);
		session('children',$children);

		//把角色该有的权限写入到session,用于后续判断
		if($auth_ids_list == '*'){
			//超级管理员
			$visitor = "*";
		}else{
			//非超级，要获取到控制器名和方法名拼接起来，存入数组，在把数组存入到session
			$visitor = [];
			foreach($allAuth as $v){
				$visitor[] = strtolower($v['auth_c'].'/'.$v['auth_a']);
			}
		}
		//把可访问的权限存储到session中
		session('visitor',$visitor);
	}
	
}