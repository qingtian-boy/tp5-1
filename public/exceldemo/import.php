<?php 

 //excel导入的方法
function import()
{
    require "./PHPExcel/IOFactory.php"; //负责读取excel表格中的数据
    $excelio=PHPExcel_IOFactory::load($_FILES['excel']['tmp_name']);
    $sheetcount=$excelio->getSheetCount();
    
    $datas=$excelio->getSheet(0)->toArray(); // 获取第一个sheet数据
    // echo "<pre />";
    // var_dump($datas);
    unset($datas[0]);  // 删除表头名字，
    var_dump($datas); // 把数据入库就so easy...


    //链接数据库
    $dsn = 'mysql:dbname=test;host=127.0.0.1;port=3306';
    $user = 'root';
    $password = '123456';
    $pdo = new PDO($dsn,$user,$password);
    //循环入库
    foreach($datas as $v){
    	$username = $v[0];
    	$age = $v[1];
    	$phone = $v[2];
    	$sql = "insert into users (username,age,phone) values('$username',$age,'$phone')";
    	$pdo->exec($sql);
    }

    echo "导入成功";
   

}

import();