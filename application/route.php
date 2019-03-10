<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];

use think\Route;
//默认网站根目录路由
Route::get('/','home/index/index');

//前台home分组
Route::group('home',function(){
	//qq登录成功的回调地址（方法）
	Route::get('/member/qqcallback','home/member/qqcallback');
	//qq登录的路由
	Route::get('/member/qqlogin','home/member/qqlogin');
	//个人订单付款的路由
	Route::get('/order/selfPayMoney','home/order/selfPayMoney');
	//查看个人所有的订单
	Route::get('/order/selfOrder','home/order/selfOrder');

	//支付宝支付成功get方式同步通知的路由：
	Route::get('/order/returnUrl','home/order/returnUrl');
	//支付宝支付成功post方式同步通知的路由：
	Route::post('/order/notifyUrl','home/order/notifyUrl');

	Route::post('/order/writeOrderInfo','home/order/writeOrderInfo');
	Route::get('/order/orderInfo','home/order/orderInfo');
	Route::get('/cart/updCart','home/cart/updCart');
	Route::get('/cart/delCart','home/cart/delCart');
	Route::get('/cart/index','home/cart/index');
	Route::post('/goods/addGoodsToCart','home/goods/addGoodsToCart');
	Route::any('/public/reset/[:member_id]/[:hash]/[:time]','home/public/reset');

	Route::post('/public/sendEmail','home/public/sendEmail');
	
	Route::get('/public/forgetpassword','home/public/forgetpassword');
	Route::post('/public/sendsms','home/public/sendsms');
	Route::get('/goods/detail','home/goods/detail');
	Route::get('/public/logout','home/public/logout');
	Route::any('/public/register','home/public/register');
	Route::any('/public/login','home/public/login');
	Route::get('/index/index','home/index/index');
	Route::get('/category/index','home/category/index');
});


//后台路由amdin分组
Route::group('admin',function(){
	//商品回收站列表路由
	Route::get('/goods/recycle','admin/goods/recycle');
	//把商品加入到回收站
	Route::get('/goods/joinRecycle','admin/goods/joinRecycle');

	//后台订单管理的相关路由
	Route::get('/order/index','admin/order/index');
	Route::get('/order/getWuliu','admin/order/getWuliu');
	Route::any('/order/upd','admin/order/upd');

	//后台添加商品的路由
	Route::any('/goods/add','admin/goods/add');
	Route::get('/goods/getTypeAttr','admin/goods/getTypeAttr');
	Route::get('/goods/index','admin/goods/index');


	//后台商品分类管理的相关路由
	Route::any('/category/add','admin/category/add');
	Route::get('/category/index','admin/category/index');
	Route::any('/category/upd','admin/category/upd');
	Route::get('/category/del','admin/category/del');


	//后台属性管理的相关路由
	Route::any('/attribute/add','admin/attribute/add');
	Route::get('/attribute/index','admin/attribute/index');
	Route::any('/attribute/upd','admin/attribute/upd');
	Route::get('/attribute/del','admin/attribute/del');


	//后台商品类型管理的相关路由
	Route::any('/type/add','admin/type/add');
	Route::get('/type/index','admin/type/index');
	Route::any('/type/upd','admin/type/upd');
	Route::get('/type/del','admin/type/del');


	//后台角色管理的路由
	Route::any('/role/add','admin/role/add'); //添加
	Route::get('/role/index','admin/role/index'); //列表
	Route::any('/role/upd','admin/role/upd'); //编辑
	
	//后台用户权限管理的路由
	Route::any('/auth/add','admin/auth/add'); //添加
	Route::get('/auth/index','admin/auth/index'); //列表
	Route::any('/auth/upd','admin/auth/upd'); //编辑
	Route::get('/auth/ajaxDel','admin/auth/ajaxDel'); //ajax删除
	

	//登录的路由
	Route::any('/public/login','admin/public/login');
	Route::get('/public/logout','admin/public/logout');


	//后台用户管理的路由
	Route::any('/user/add','admin/user/add'); //添加
	Route::get('/user/index','admin/user/index'); //列表
	Route::get('/user/del','admin/user/del'); //删除
	Route::any('/user/upd','admin/user/upd'); //更新


	//后台首页的路由
	Route::get('/index/index','admin/index/index');
	Route::get('/index/top','admin/index/top');
	Route::get('/index/left','admin/index/left');
	Route::get('/index/main','admin/index/main');
});


