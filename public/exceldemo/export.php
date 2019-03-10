<?php 

//excel导出的方法
function export()
{
    require "./PHPExcel.php"; // 引入核心文件
    require "./PHPExcel/Writer/Excel5.php"; //此类主要往excel表中写数据的文件
    $objPHPExcel = new PHPExcel(); // 实例一个excel核心类
    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel); // 将excel类对象作为参数传入进去

    $sheets=$objPHPExcel->getActiveSheet()->setTitle('sheet_name');//设置表格内部sheet名称

    //设置sheet列头信息
    $objPHPExcel->setActiveSheetIndex()->setCellValue('A1', '用户名')->setCellValue('B1', '年龄')->setCellValue('C1', '手机号');
    //导出用户表的十条数据
    $dsn = 'mysql:dbname=test;host=127.0.0.1;port=3306';
    $user = 'root';
    $password = '123456';
    $pdo = new PDO($dsn,$user,$password);
    $sql = "select * from users ";
    $res = $pdo->query($sql);
    $datas= $res->fetchAll(PDO::FETCH_ASSOC); // 二维

    $i=2;
    foreach($datas as $v){
        //设置单元格的值
        $sheets=$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$v['username']);
        $sheets=$objPHPExcel->getActiveSheet()->setCellValue('B'.$i,$v['age']);
        $sheets=$objPHPExcel->getActiveSheet()->setCellValue('C'.$i,$v['phone']);
        $i++;
    }

    //整体设置字体和字体大小
    $objPHPExcel->getDefaultStyle()->getFont()->setName( 'Arial');//整体设置字体
    $objPHPExcel->getDefaultStyle()->getFont()->setSize(20);//整体设置字体大小


    // $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true); //单元格宽度自适应
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20); //设置列宽度
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20); //设置列宽度
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); //设置列宽度
    $objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true); //设置单元格字体加粗

    // 输出Excel表格到浏览器下载
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="php32用户数据.xls"'); //excel表格名称
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter->save('php://output');

}


export();