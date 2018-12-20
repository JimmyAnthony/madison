<?php
set_time_limit(0);
ini_set("memory_limit", "-1");



require_once PATH . "libs/PHPExcel/PHPExcel.php";
require_once PATH . "libs/PHPExcel/PHPExcel/Reader/Excel2007.php";


$styleArray = array(
    'font' => array(
        'bold' => true,
        'name' => 'Calibri',
        'size' => 9,
        'color' => array(
            'argb' => 'FFFFFFFF',
        ),
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
    ), 'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'argb' => 'FFFF0000',
        ),
        'endcolor' => array(
            'argb' => 'FFFFFFFF',
        ),
    ),
);

$store = $this->objDatos->scm_gestion_busq_personal($p);
//var_export($store); die();
$objReader = new PHPExcel_Reader_Excel5();
$objPHPExcel = new PHPExcel();
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

$arrayNCol = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

$arrayHeader= array('CODIGO RRHH','DNI','APELLIDOS','NOMBRES','TRABAJA EN AGENCIA','CARGO','AREA','TELEFONO','ESTADO');

$i=0; //fila donde comienza a escribir
//$stable=6;

//$objPHPExcel->getActiveSheet()->getCell($arrayNCol[0].$i)->setValueExplicit('REPORTE ANS VISITAS', PHPExcel_Cell_DataType::TYPE_STRING);
++$i;

foreach($arrayHeader as $index => $value){
    $objPHPExcel->getActiveSheet()->getColumnDimension($arrayNCol[$index])->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[$index].''.$i, $value);
    $objPHPExcel->getActiveSheet()->getStyle($arrayNCol[$index].''.$i)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle($arrayNCol[$index].''.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(18);
}
++$i;
$j = 0;

foreach($store as $fila){
    //$objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[0].$i, trim($fila['AGENCIA']));
    $objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[0].$i, utf8_encode($fila['per_codigo']));
    $objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[1].$i, trim($fila['doc_numero']));
    $objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[2].$i, trim($fila['per_apellido']));
    $objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[3].$i, trim($fila['per_nombre']));
    $objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[4].$i, utf8_encode(trim($fila['prov_nombre'])));
    $objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[5].$i, trim($fila['car_nombre']));
    $objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[6].$i, trim($fila['area_nombre']));
    $objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[7].$i, trim($fila['per_telefono']));   
    $objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[8].$i, utf8_encode(trim($fila['per_estado'])));
    //$objPHPExcel->getActiveSheet()->setCellValue($arrayNCol[9].$i, utf8_encode(trim($fila['direccion'])));
    
     ++$i;

}
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte.xls"');
header('Pragma: public');
header('Cache-control: public');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>

