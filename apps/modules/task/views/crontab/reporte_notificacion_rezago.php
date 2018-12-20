<?php

$styleArray = array(
    'font' => array(
        'name' => 'Calibri',
        'size' => 11,
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

$style_rojo = array(
    'font' => array(
        'name' => 'Calibri',
        'size' => 11,
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

$style_amarillo = array(
    'font' => array(
        'name' => 'Calibri',
        'size' => 11,
        'color' => array(
            'argb' => '00000000',
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
            'argb' => 'FFFFFF00',
        ),
        'endcolor' => array(
            'argb' => 'FFFFFFFF',
        ),
    ),
);

$style_verde = array(
    'font' => array(
        'name' => 'Calibri',
        'size' => 11,
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
            'argb' => 'FF32CD32',
        ),
        'endcolor' => array(
            'argb' => 'FFFFFFFF',
        ),
    ),
);

$arrayNCol = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

$arrayHeader= array('GE','Código Seguimiento','Cliente','Teléfono','Dirección','Referencia','Localidad','Tipo Zona','Contenido','Piezas','Peso','Fecha Visita','Motivo Rezago','Detalle Motivo');

$objPHPExcel = new PHPExcel();

$indice = 0;
$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Envío Email');
$objPHPExcel->addSheet($myWorkSheet, $indice);

$i=1;
foreach($arrayHeader as $index => $value){
    $objPHPExcel->getSheet($indice)->getColumnDimension($arrayNCol[$index])->setAutoSize(true);
    $objPHPExcel->getSheet($indice)->setCellValue($arrayNCol[$index].''.$i, $value);
    $objPHPExcel->getSheet($indice)->getStyle($arrayNCol[$index].''.$i)->applyFromArray($styleArray);
    $objPHPExcel->getSheet($indice)->getStyle($arrayNCol[$index].''.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getSheet($indice)->getRowDimension($i)->setRowHeight(16);
}
++$i;

foreach($p['data1'] as $index => $value){
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[0].$i)->setValueExplicit(trim($value['guia']), PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[1].$i)->setValueExplicit(trim($value['cod_track']), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[2].$i)->setValueExplicit(utf8_encode(trim($value['cliente'])), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[3].$i)->setValueExplicit(trim($value['telefono']), PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[4].$i)->setValueExplicit(utf8_encode(trim($value['direccion'])), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[5].$i)->setValueExplicit(utf8_encode(trim($value['referencia'])), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[6].$i)->setValueExplicit(utf8_encode(trim($value['localidad'])), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[7].$i)->setValueExplicit(utf8_encode(trim($value['zona'])), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[8].$i)->setValueExplicit(utf8_encode(trim($value['contenido'])), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[9].$i)->setValueExplicit(trim($value['piezas']), PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[10].$i)->setValueExplicit(trim($value['peso']), PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[11].$i)->setValueExplicit(trim($value['fecha']), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[12].$i)->setValueExplicit(trim($value['estado']), PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getSheet($indice)->getCell($arrayNCol[13].$i)->setValueExplicit(trim($value['detalle']), PHPExcel_Cell_DataType::TYPE_STRING);

    ++$i;
}

$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(PATH . 'public_html/tmp/notificacion_rezagos/' . $p['name_file']);