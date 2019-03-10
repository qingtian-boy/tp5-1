<?php 
namespace app\home\controller; //定义当前类所在的命名空间
use think\Controller; 	//引入Controller核心控制器
use app\home\model\Member;
class MemberController extends Controller {
    
    // 实现qq登录 ，这里唤起qq登录的扫码登录框
    public function qqLogin(){
     	require_once("../extend/qqlogin/API/qqConnectAPI.php");
		$qc = new \QC();
		$qc->qq_login();
    }


    //qq登录的回调方法
    public function qqCallback(){
    	require_once("../extend/qqlogin/API/qqConnectAPI.php");
		$qc = new \QC();
    	//这里获取两个参数：token、openid
    	$token = $qc->qq_callback();
    	$openid = $qc->get_openid();
    	//重新实例化QC类，
    	$qc = new \QC($token,$openid);
    	// 判断是否与member表的openid绑定过
    	$memberInfo = Member::where('openid',$openid)->find();
    	//一个网站一个qq其对应的openid一定是唯一
    	if($memberInfo){
    		//说明之前有使用qq登录过，取出用户的信息存储到session，帮助用户登录
    		session('member_username',$memberInfo['nickname']);
    		session('member_id',$memberInfo['member_id']);
    		$this->redirect('/');
    		
    	}else{
    		//第一次使用qq登录我们的网站
    		//调用接口，获取用户信息
    		$qqUserInfo = $qc->get_user_info();
    		$data = [
    			'nickname' =>$qqUserInfo['nickname'],
    			'openid' =>$openid
    		];
    		//入库操作，存储qq用户信息
    		$result = Member::create($data);
    		if($result){
    			//绑定成功，设置用户信息到session中，帮助用户登录
    			session('member_username',$result['nickname']);
    			session('member_id',$result['member_id']);
    			$this->redirect('/');
    		}else{
    			$this->error('网路异常，请重试');
    		}
    	}
    }

}