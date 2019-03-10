<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 1.消除模板中变量不存在的报错。
// 2.消除数组下标不存在的报错。
error_reporting(0);


//封装发送邮件的方法
function sendEmail($to,$title,$content){
	// 实例化
	include "../extend/sendEmail/class.phpmailer.php";
	$pm = new PHPMailer();
	// 服务器相关信息
	$pm->Host = 'smtp.163.com'; // SMTP服务器
	$pm->IsSMTP(); // 设置使用SMTP服务器发送邮件
	$pm->SMTPAuth = true; // 需要SMTP身份认证
	$pm->Username = 'manbawei'; // 登录SMTP服务器的用户名，邮箱@前面一串字符
	$pm->Password = 'manba123'; //授权码 登录SMTP服务器的密码

	// 发件人信息
	$pm->From = 'manbawei@163.com'; //自己的邮箱
	$pm->FromName = '小可爱'; // 发件人昵称，名字可以随便取

	// 收件人信息
	$pm->AddAddress($to, ''); // 设置收件人邮箱和昵称，昵称名字随便取
	//$pm->AddAddress('888888@qq.com', '8哥'); // 添加另一个收件人

	$pm->CharSet = 'utf-8'; // 内容编码
	$pm->Subject = $title; // 邮件标题
	$pm->MsgHTML($content); // 邮件内容

	// 发送邮件
	return $pm->Send(); // 发送成功返回true

}

//发送短信的方法
function SendSms($to,$datas,$tempId = '1'){
	include_once("../extend/sendSms/CCPRestSmsSDK.php");
	//主帐号,对应开官网发者主账号下的 ACCOUNT SID
	$accountSid= '8aaf07085c1ab70d015c28adee3d02c3';
	//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
	$accountToken= '817585a4a0d14f15a0dcd7ab8c534856';
	//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
	//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
	$appId='8aaf07085c1ab70d015c28adefcb02ca';
	//请求地址
	//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
	//生产环境（用户应用上线使用）：app.cloopen.com
	$serverIP='app.cloopen.com';

	//请求端口，生产环境和沙盒环境一致
	$serverPort='8883';
	//REST版本号，在官网文档REST介绍中获得。
	$softVersion='2013-12-26';
	// 初始化REST SDK
     // global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
     // 发送模板短信
     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
     return $result ;  //返回发送短信的结果   
}
