<?php 
namespace app\home\controller; //定义当前类所在的命名空间
use think\Controller; 	//引入Controller核心控制器
use app\home\model\Member;
class PublicController extends Controller {

	public function reset($member_id,$hash,$time){
		//先验证链接地址的有效性，防止被篡改了
		if($hash !== md5($member_id.$time.config('email_salt'))){
			//说明篡改了。
			$this->error('链接地址无效',url('/home/public/forgetPassword'));
		}
		//判断是否在有效期内 3分钟之内
		if(time() > $time+1800){
			//说明过期了。
			$this->error('链接地址过期了',url('/home/public/forgetPassword'));
		}
	
		//1.判断post请求
		if(request()->isPost()){
			//2.接收post参数
			$postData = input('post.');
			//3.验证器验证
			$result = $this->validate($postData,'Member.reset',[],true);
			if($result!==true){
				$this->error( implode(',',$result) );
			}
			//4.修改密码
			$memberModel = new Member();
			unset($postData['repassword']);
			$postData['password'] = md5($postData['password'].config('password_salt'));
			if($memberModel->where('email',$postData['email'])->update($postData)){
				//修改成功
				$this->success("密码重置成功",url("/home/public/login"));
			}else{
				//修改失败
				$this->error("修改失败");
			}
		}
		
		$email = Member::where("member_id",$member_id)->value('email');
		return $this->fetch('',[
			'email' => $email
		]);
	}


	public function sendEmail(){
		//1.判断是否是ajax请求
		if(request()->isAjax()){
			//2.接收邮件参数
			$email = input('email');
			//3.只有已存在的邮件，才发送
			$memberInfo = Member::where('email',$email)->find();
			if(!$memberInfo){
				//说明没有注册过，不发邮件
				$response = ['code'=>-1,'message'=>'此邮件未被注册'];
				echo json_encode($response);die;
			}
			$nowTime = time();
			//把用户的id和时间和邮箱的盐一起md5加密
			$hash = md5($memberInfo['member_id'].$nowTime.config('email_salt'));
			//4.构造邮件的a链接地址，发送给用户的邮箱，最后返回json格式数据
			$href=request()->domain().'/home/public/reset/'.$memberInfo['member_id'].'/'.$hash.'/'.$nowTime;
			$content = "<a href='".$href."'>戳我找我密码</a>";
			$result = sendEmail($email,'京西商城-找回密码',$content);
			if($result == true){
				//发送成功
				$response = ['code'=>200,'message'=>'邮箱发送成功，请及时修改'];
				echo json_encode($response);die;
			}else{
				//发送失败
				$response = ['code'=>-2,'message'=>'网络繁忙，请稍后再试'];
				echo json_encode($response);die;
			}
		}
	}


	public function forgetPassword(){
		return $this->fetch();
	}



	public function sendSMS(){
		//1.判断是否是ajax
		if(request()->isAjax()){
			//2.接受手机号
			$phone = input('phone');
			//3. 判断此手机号是否被注册过
			$memberInfo = Member::where('phone',$phone)->find();
			if($memberInfo){
				//说明被注册过，提示信息给用户
				$response = ['code' => -1,'message'=>'此号码已被注册'];
				echo json_encode($response); die;
			}
			$code = mt_rand(1000,9999); //随机取1000-9999中的4位  
			//4.说明没有注册过，需要给手机号发送短信
			cookie('sms',md5($code.config('sms_salt')),300); //有效期5分钟
			$result = sendSms($phone,[$code,5]);
			if($result->statusCode == '000000'){
				//发送成功
				$response = ['code' => 200,'message'=>'发送成功，请查收!'];
				echo json_encode($response); die;
			}else{
				//发送失败
				$response = ['code' => -2,'message'=>'发送失败，原因：'.$result->statusMsg];
				echo json_encode($response); die;
			}
		}
	}


	public function logout(){
		//1.清除session
		session('member_id',null);
		session('member_username',null);
		//2.重定向到登录页
		$this->redirect('/home/public/login');
	}


	public function login(){
		//1.判断post请求
		if(request()->isPost()){
			//2.接收post参数
			$postData = input('post.');
			//3.验证
			$result = $this->validate($postData,'Member.login',[],true);
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//4.调用Member模型中的方法checkUser来检测用户名和密码是否匹配
			$memberModel = new Member();
			$status = $memberModel->checkUser($postData['username'],$postData['password']);
			if($status){
				//可能是用户未登录打回来，打回来的url中一定会有一个goods_id的参数，
				//只要判断有这个参数，就需要打回之前的购买页面
				if(input('goods_id')){
					$this->redirect('/home/goods/detail?goods_id='.input('goods_id'));
				}
				//登录成功
				$this->redirect('/');
			}else{
				//匹配失败
				$this->error("用户名或密码错误");
			}
			
		}
		return $this->fetch('');
	}
    
	public function register(){
		//1.判断post请求
		if(request()->isPost()){
			//2.接受post参数
			$postData = input('post.');
			//3.验证
			$result = $this->validate($postData,'Member.register',[],true);
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//验证手机号验证码是否正确
			if(md5($postData['phoneCode'].config('sms_salt'))!== cookie('sms')){
				$this->error('手机验证码输入错误');
			}

			//验证成功之后，把cookie给删除
			cookie('sms',null);
			//4.写入数据库sh_member
			$memberModel = new Member();
			if($memberModel->allowField(true)->save($postData)){
				$this->success("注册成功",url("/home/public/login"));
			}else{
				$this->error("注册失败");
			}
		}
		return $this->fetch();
	}

}