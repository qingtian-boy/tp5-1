<?php 
namespace app\admin\controller;
use think\Controller;
class CommonController extends Controller{

	//重新父类的初始化方法_initialize
	//只要是继承了CommonController，调用子控制器的任一方法都会触发此方法_initialize
	public function _initialize(){
		//判断是否有session信息
		if(!session('user_id')){
			//提示用户先登录后在操作
			$this->error('请先登录',url('/admin/public/login'));
		}else{
			//有session说明可以访问后台程序，但是可能权限翻墙，在url中输入不存在的权限进行访问
			//获取到当前访问的控制器名和方法名
			$now_c = request()->controller();
			$now_a = request()->action();
			$now_ca = strtolower($now_c.'/'.$now_a);
			//要获取到当前角色所有的访问权限
			$visitor = session('visitor'); // '*'  或者 ['auth/index',....]、
			//1.等于*说明是超级管理员，不做权限控制，直接放行
			//2.如果是后台首页的index控制器也要放行
			if($visitor == '*' || strtolower( $now_c ) == 'index'){
				//echo '超级';
				return; //不在往下走，退出当前函数
			}

			//说明是非超级管理员，需要判断是否有访问权限
			// echo '非超级';
			if(!in_array($now_ca,$visitor)){
				$this->error('想翻墙！，没门');
			}
		}
	}


}