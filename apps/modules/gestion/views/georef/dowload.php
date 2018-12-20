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

$store = $this->objDatos->scm_georeferenciar_dowload($p);
$nom = $p['vp_ord_numero'];
//var_export($store); die();
$objReader = new PHPExcel_Reader_Excel5();
$objPHPExcel = new PHPExcel();
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

$arrayNCol = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
//cli_nombre,direccion,dir_referen,ciu_id,ciu_ubigeo,nombre_ubi,dir_px,dir_py
$arrayHeader= array('ID','N°. GE','Cliente','Direccion','Referencia','Ubigeo','Ciudad Normalizada','Localidad Original','Latitud (Coord. X)','Longitud (Coord. Y)','Error');

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
    $objPHPExcel->getActiveSheet()->getCell($arrayNCol[0].$i)->setValueExplicit(trim($fila['dir_id']), PHPExcel_Cell_DataType::TYPE_STRING);    
    $objPHPExcel->getActiveSheet()->getCell($arrayNCol[1].$i)->setValueExplicit(trim($fila['gui_numero']), PHPExcel_Cell_DataType::TYPE_STRING);    
    $objPHPExcel->getActiveSheet()->getCell($arrayNCol[2].$i)->setValueExplicit(trim($fila['cli_nombre']), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->getCell($arrayNCol[3].$i)->setValueExplicit(trim($fila['direccion']), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->getCell($arrayNCol[4].$i)->setValueExplicit(trim($fila['dir_referen']), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->getCell($arrayNCol[5].$i)->setValueExplicit(trim($fila['ciu_ubigeo']), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->getCell($arrayNCol[6].$i)->setValueExplicit(trim($fila['nombre_ubi']), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->getCell($arrayNCol[7].$i)->setValueExplicit(trim($fila['dir_ciudad']), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->getCell($arrayNCol[8].$i)->setValueExplicit(trim($fila['dir_px']), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->getCell($arrayNCol[9].$i)->setValueExplicit(trim($fila['dir_py']), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->getCell($arrayNCol[10].$i)->setValueExplicit(trim($fila['err']), PHPExcel_Cell_DataType::TYPE_STRING);
    


    
     ++$i;

}
header('Content-Type: application/vnd.ms-excel'); 
header('Content-Disposition: attachment;filename="'.$p['vp_ord_numero'].'.xls"');
header('Pragma: public');
header('Cache-control: public');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>

