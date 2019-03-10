<?php
// 实例化
include "class.phpmailer.php";
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
$pm->AddAddress('1259481020@qq.com', ''); // 设置收件人邮箱和昵称，昵称名字随便取
//$pm->AddAddress('888888@qq.com', '8哥'); // 添加另一个收件人


$pm->CharSet = 'utf-8'; // 内容编码
$pm->Subject = '邮件标题'; // 邮件标题
$pm->MsgHTML('<a href="http://www.itcast.cn" target="_blank">商城找回密码</a>！'); // 邮件内容

//var_dump($pm->Send()); //发送成功返回true
// 发送邮件
if($pm->Send()){
   echo 'ok';
}else {
   echo $pm->ErrorInfo;
}