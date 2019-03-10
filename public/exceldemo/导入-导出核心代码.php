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
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A1', '名字')->setCellValue('B1', '邮箱')->setCellValue('C1', '性别')->setCellValue('D1', '入职时间')->setCellValue('E1', '电话号码');
        //导出用户表的十条数据
        $users=D("User")->limit(10)->Select();
        $i=2;
        foreach($users as $v){
            //设置单元格的值
            $sheets=$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$v['username']);
            $sheets=$objPHPExcel->getActiveSheet()->setCellValue('B'.$i,$v['email']);
            $sheets=$objPHPExcel->getActiveSheet()->setCellValue('C'.$i,$v['sex']?"男":"女");
            $sheets=$objPHPExcel->getActiveSheet()->setCellValue('D'.$i,date("Y-m-d",$v['add_time']));
            $sheets=$objPHPExcel->getActiveSheet()->setCellValue('E'.$i,$v['tel']);
            $i++;
        }

        //整体设置字体和字体大小
        $objPHPExcel->getDefaultStyle()->getFont()->setName( 'Arial');//整体设置字体
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);//整体设置字体大小


        // $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true); //单元格宽度自适应
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20); //设置列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20); //设置列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); //设置列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20); //设置列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); //设置列宽度
        $objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true); //设置单元格字体加粗

        // 输出Excel表格到浏览器下载
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="excel_name.xls"'); //excel表格名称
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter->save('php://output');

    }

    //excel导入的方法
    public function import()
    {
        require "./PHPExcel/IOFactory.php";
        $excelio=PHPExcel_IOFactory::load($_FILES['excel']['tmp_name']);
        $sheetcount=$excelio->getSheetCount();
        
        $datas=$excelio->getSheet(0)->toArray(); // 获取第一个sheet数据
        unset($datas[0]);  // 删除表头名字，
        var_dump($datas); // 把数据入库就so easy...

    }