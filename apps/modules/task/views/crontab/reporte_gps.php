<?php

$array = array();
$array[0] = array();
$array[1] = array();
$array[2] = array();
$array[3] = array();
foreach($p['data1'] as $index => $value){
    if ( (intval($value['cargo']) + intval($value['perso'])) > 0 ){
        $value['title'] = 'Certificado (Lima)';
        $array[0][] = $value;
    }
    if ( intval($value['simple']) > 0 ){
        $value['title'] = 'Servicio Simple (Lima)';
        $array[1][] = $value;
    }
}

foreach($p['data2'] as $index => $value){
    if ( (intval($value['cargo']) + intval($value['perso'])) > 0 ){
        $value['title'] = 'Certificado (ATE)';
        $array[2][] = $value;
    }
    if ( intval($value['simple']) > 0 ){
        $value['title'] = 'Servicio Simple (ATE)';
        $array[3][] = $value;
    }
}

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

$arrayHeader= array('Personal','Fecha','Grupo','Zona','Cantidad','Tiempo en planta','Tpo llegada ZR','Tiempo en campo','Tiempo Ruta Total','Distancia Zona(KM)','Distancia recorrida','% cobertura','Velocidad recorrido','Cantidad  Detenido','Total Detenido','Cantidad Apagado GPS','Total Apagado GPS');

/* ------------------------------------------------------------------------ */
function por_cobertura($value){
    $distancia_rec = intval($value['distanc']);
    $distancia_zon = intval( floatval($value['km_zona']) * 1000 );
    if(!empty($distancia_zon) || $distancia_zon!=0){
    return round(floatval($distancia_rec / $distancia_zon) * 100, 2);
    }else{
        return 0;
    }
}
/* ------------------------------------------------------------------------ */

$objPHPExcel = new PHPExcel();

$indice = 0;
foreach($array as $index01 => $value01){

    if (count($value01) > 0){

        $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, $value01[0]['title']);
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

        foreach($value01 as $index => $value){
            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[0].$i)->setValueExplicit(trim($value['codigo']) . ' ' . utf8_encode(trim($value['nombres'])), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[1].$i)->setValueExplicit(Common::getFormatDMY(trim($value['fecha']), '-'), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[2].$i)->setValueExplicit(trim($value['grupo']), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[3].$i)->setValueExplicit(trim($value['cdra_01']) . ' ' . trim($value['cdra_02']). ' ' . trim($value['cdra_03']). ' ' . trim($value['cdra_04']). ' ' . trim($value['cdra_05']). ' ' . trim($value['cdra_06']). ' ' . trim($value['cdra_07']), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[4].$i)->setValueExplicit(intval($value['cnt_01']) + intval($value['cnt_02']) + intval($value['cnt_03']) + intval($value['cnt_04']) + intval($value['cnt_05']) + intval($value['cnt_06']) + intval($value['cnt_07']), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[5].$i)->setValueExplicit(Common::convert_minute_to_hour(intval($value['tpoplanta'])), PHPExcel_Cell_DataType::TYPE_STRING);

            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[6].$i)->setValueExplicit(Common::convert_minute_to_hour(intval($value['tpo_ruta'])), PHPExcel_Cell_DataType::TYPE_STRING);

            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[7].$i)->setValueExplicit(Common::convert_minute_to_hour(intval($value['tpozona'])), PHPExcel_Cell_DataType::TYPE_STRING);
            if (intval($value['tpozona']) < 60)
                $color = $style_rojo;
            else if( (intval($value['tpozona']) >= 60) && (intval($value['tpozona']) <= (60 * 4)) )
                $color = $style_amarillo;
            else
                $color = $style_verde;
            $objPHPExcel->getSheet($indice)->getStyle($arrayNCol[7].$i)->applyFromArray($color);

            

            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[8].$i)->setValueExplicit(Common::convert_minute_to_hour(intval($value['tpo_total'])), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[9].$i)->setValueExplicit(floatval($value['km_zona']) / 1000, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            
            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[10].$i)->setValueExplicit(floatval($value['distanc']), PHPExcel_Cell_DataType::TYPE_NUMERIC);

            $por_cobertura = por_cobertura($value);
            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[11].$i)->setValueExplicit($por_cobertura, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            if ($por_cobertura < 30)
                $color = $style_rojo;
            else if( ($por_cobertura >= 30) && ($por_cobertura <= 80) )
                $color = $style_amarillo;
            else
                $color = $style_verde;
            $objPHPExcel->getSheet($indice)->getStyle($arrayNCol[11].$i)->applyFromArray($color);

            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[12].$i)->setValueExplicit(floatval($value['velocidad']), PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $velocidad = intval(floatval($value['velocidad']) * 1000);
            if ( ($velocidad < 5) || ($velocidad > 12) )
                $color = $style_rojo;
            else if ( ($velocidad >= 5) && ($velocidad <= 8) )
                $color = $style_amarillo;
            else
                $color = $style_verde;
            $objPHPExcel->getSheet($indice)->getStyle($arrayNCol[12].$i)->applyFromArray($color);

            
            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[13].$i)->setValueExplicit(intval($value['cnt_parad']), PHPExcel_Cell_DataType::TYPE_STRING);

            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[14].$i)->setValueExplicit(Common::convert_minute_to_hour(intval($value['tot_parad'])), PHPExcel_Cell_DataType::TYPE_STRING);

            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[15].$i)->setValueExplicit(intval($value['cnt_gpsoff']), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getSheet($indice)->getCell($arrayNCol[16].$i)->setValueExplicit(Common::convert_minute_to_hour(intval($value['tot_gpsoff'])), PHPExcel_Cell_DataType::TYPE_STRING);
            ++$i;
        }
        ++$indice;

    }

}


$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(PATH . 'public_html/tmp/gps/' . $p['name_file']);