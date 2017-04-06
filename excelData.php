<?php
/**
 * Created by PhpStorm.
 * User: era-s001
 * Date: 17/4/6
 * Time: 上午9:48
 */
date_default_timezone_set("Asia/Shanghai");
require_once './PHPExcel.php';
require_once './PHPExcel/IOFactory.php';
//$file_path = '.'.$batch_file;  //必须要能找到该文件的位置
$file_path = './shanHai.xlsx';  //必须要能找到该文件的位置

if (!file_exists($file_path)) {
    die('no file!');
}

//文件的扩展名
$ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

if ($ext == 'xlsx') {
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objPHPExcel = $objReader->load($file_path, 'utf-8');
} elseif ($ext == 'xls') {
    $objReader = PHPExcel_IOFactory::createReader('Excel5');
    $objPHPExcel = $objReader->load($file_path, 'utf-8');
}
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumn = $sheet->getHighestColumn(); // 取得总列数

for ($j = 1; $j <= $highestRow; $j++) {
    $str = '';
    for ($k = 'B'; $k <= $highestColumn; $k++) {
        $str .= $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue() . '\\';//读取单元格
    }
    $strs = explode("\\", $str);
    unset($strs[3]);
    $data[] = $strs;
}

$filename = "dataShanHai";
$fp = fopen($filename, "a");
if (flock($fp, LOCK_EX)) {
    fwrite($fp, json_encode($data));
    flock($fp, LOCK_UN);
} else {
    echo "不能锁定文件";
}
fclose($fp);





