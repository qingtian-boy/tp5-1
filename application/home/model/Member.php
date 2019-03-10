<?php 
namespace app\home\model;
use think\Model;

class Member extends Model{
	protected $pk = 'member_id';
	protected $autoWriteTimestamp = true;


	public static function init(){
		//入库的前钩子
		Member::event('before_insert',function($member){
			//给密码字段password，进行拼接盐加密
			//由于qq登录没有密码，所以需要使用isset进行判断，否则会报错
			if(isset($member['password'])){
				$member['password'] = md5( $member['password'].config('password_salt') );
			}
			
		});

		//编辑的前钩子，要删除表不存在的字段repassword
		Member::event('before_update',function($member){
			//unset($member['repassword']);
			// dump($member);die;
			//密码需要加密处理
			//$member['password'] = md5($member['password'].config('password_salt'));
		});
	}


	public function checkUser($username,$password){
		//组装查询的条件
		$where = [
			'username' => $username,
			'password' => md5( $password.config('password_salt') )
		];
		$memberInfo = $this->where($where)->find();
		if($memberInfo){
			//匹配成功，返回true,把用户名id和用户名写入session中
			session('member_id',$memberInfo['member_id']);
			session('member_username',$memberInfo['username']);
			return true;
		}else{
			return false;
		}
	}
}