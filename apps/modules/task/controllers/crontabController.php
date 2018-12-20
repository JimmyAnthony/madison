<?php

/**
 * Geekode php (http://geekode.net/)
 * @link    https://github.com/remicioluis/geekcode_php
 * @author  Luis Remicio @remicioluis (https://twitter.com/remicioluis)
 * @version 2.0
 */

class crontabController extends AppController {

    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        $this->objDatos = new crontabModels();
    }

    public function test($p){
        $this->include_mailer();

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "200.1.178.19";

        $mail->Port = 25;
        $mail->SMTPAuth = true;
        $mail->Username   = "urbano@urbano.com.pe";
        $mail->Password   = "urbano$2014";

        $mail->IsHTML(true);
        $asunto = 'Reporte de GPS';
        $mail->Subject = $asunto;
        $mail->SetFrom('urbano@urbano.com.pe', $asunto);
        $mail->MsgHTML("test");

        $mail->AddAddress('remicioluis@gmail.com', 'Luis Remicio');
        $mail->AddBCC('jbazan@urbano.com.pe');
        try {
            if (!$mail->Send()) {
                $errormsn = $mail->ErrorInfo;
            }else{
                echo 'Envio correctamente!';
            }
        } catch(Exception $e){
            echo $e;
        }
    }

    public function reporte_gps($p){
        $this->include_mailer();

        $contacts = Common::read_ini_contacts(PATH . 'config/contacts.json', 'send_reporte_gps');

        if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",date('d/m/Y'))) 
            list($dia,$mes,$anio) = split("/", date('d/m/Y'));
        $hora = 0;
        $minute = 0;
        $second = 0;
        $fecha01 = mktime($hora, $minute, $second, $mes,$dia - 2,$anio) + 0 * 24 * 60 * 60;
        $fecha01 = date('d/m/Y', $fecha01);

        $p['name_file'] = 'reporte_gps.xlsx';
        $p['vp_fecha_ini'] = $fecha01;
        $p['vp_fecha_fin'] = $fecha01;
        $this->get_data_reporte_gps($p);
        // die();

        $directorio = PATH . 'public_html/tmp/gps/' . $p['name_file'];

        $data_html = array();
        $data_html['saludo'] = 'Buenos d&iacute;as,';
        $data_html['mensaje'] = 'Ajunto reporte de GPS.';
        $html = $this->template_mailer($data_html);

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "200.1.178.19";
        $mail->SMTPAuth   = false;                                         

        $mail->IsHTML(true);
        $asunto = 'Reporte de GPS';
        $mail->Subject = $asunto;
        $mail->SetFrom('urbano@urbano.com.pe', $asunto);
        $mail->MsgHTML($html);

        foreach($contacts as $index => $value){
            $mail->AddAddress(trim($value['address_email']), trim($value['address_name']));
        }

        $mail->AddBCC('jbazan@urbano.com.pe');

        $mail->AddAttachment($directorio,$nombre_archivo, 'base64');
        try {
            if (!$mail->Send()) {
                $errormsn = $mail->ErrorInfo;
            }else{
                echo 'Envio correctamente!';
                unlink($directorio);
            }
        } catch(Exception $e){
            echo $e;
        }
    }

    public function get_data_reporte_gps($p){
        $p['vp_prov'] = 1;
        $rs1 = $this->objDatos->uSP_Alerta_Diaria_2($p);
        $p['data1'] = $rs1;
        $p['vp_prov'] = 5;
        $rs2 = $this->objDatos->uSP_Alerta_Diaria_2($p);
        $p['data2'] = $rs2;

        $this->include_excel();
        $this->view('crontab/reporte_gps.php', $p);
    }

    public function reporte_notificacion_rezago($p){
        $this->include_mailer();
        $this->include_excel();
        
        $rs = $this->objDatos->pcl_email_programacion($p);

        // var_export($rs);

        if (count($rs) > 0){
            if (intval($rs[0]['error_sql']) > 0){
                foreach($rs as $index => $value){
                    $p['vp_id_email'] = $value['id_mail'];
                    $p['vp_id_user'] = 1;
                    $contacts = $this->objDatos->pcl_email_miembros($p);
                    
                    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",date('d/m/Y'))) 
                        list($dia,$mes,$anio) = split("/", date('d/m/Y'));
                    $hora = 0;
                    $minute = 0;
                    $second = 0;
                    $fecha01 = mktime($hora, $minute, $second, $mes,$dia - 1,$anio) + 0 * 24 * 60 * 60;
                    
                    // $p['vp_user'] = 1;
                    $p['name_file'] = 'reporte_notificacion_rezago'.$p['vp_id_email'].'.xlsx';
                    // $p['vp_fecha'] = date('d/m/Y', $fecha01);

                    $rs01 = $this->objDatos->pcl_email_envios($p);
                    if (count($rs01) > 0){
                        $p['data1'] = $rs01;

                        $this->get_data_notificacion_rezago($p);

                        $directorio = PATH . 'public_html/tmp/notificacion_rezagos/' . $p['name_file'];

                        $data_html = array();
                        $data_html['saludo'] = 'Buenos d&iacute;as,';
                        $data_html['mensaje'] = 'Ajunto reporte de notificaci&oacute;n de rezagos.';
                        $html = $this->template_mailer($data_html, array('header' => array('GE','C&oacute;digo Seguimiento','Cliente','Tel&eacute;fono','Direcci&oacute;n','Referencia','Localidad','Tipo Zona','Contenido','Piezas','Peso','Fecha Visita','Motivo Rezago','Detalle Motivo'), 'data' => $p['data1']));
                        // die();
                        $mail = new PHPMailer();
                        $mail->IsSMTP();
                        $mail->Host = "200.1.178.19";

                        // $mail->SMTPAuth   = false;                                         
                        $mail->Port = 25;
                        $mail->SMTPAuth = true;
                        $mail->Username   = "urbano@urbano.com.pe";
                        $mail->Password   = "urbano$2014";
                        
                        $mail->IsHTML(true);
                        $asunto = 'Reporte Notificación de Rezagos';
                        $mail->Subject = $asunto;
                        $mail->SetFrom('urbano@urbano.com.pe', $asunto);
                        $mail->MsgHTML($html);

                        foreach($contacts as $index => $value){                            
                            $mail->AddAddress(trim($value['e_mail']), trim($value['nombre']));
                        }

                        $mail->AddBCC('jbazan@urbano.com.pe');

                        $mail->AddAttachment($directorio,$nombre_archivo, 'base64');
                        try {
                            if (!$mail->Send()) {
                                $errormsn = $mail->ErrorInfo;
                            }else{
                                echo 'Envio correctamente!';
                                unlink($directorio);
                            }
                        } catch(Exception $e){
                            echo $e;
                        }

                    }
                }
            }
        }
    }

    public function get_data_notificacion_rezago($p){
        $this->view('crontab/reporte_notificacion_rezago.php', $p);
        
        
    }

    public function send_sms($p){
        set_time_limit(180000);
        ini_set("memory_limit", "-1");

        require PATH . 'libs/Sms.php';
        
        $p['vp_prov'] = intval($p['vp_prov']);
        $p['vp_shipper'] = intval($p['vp_shipper']);
        $p['vp_fecha'] = date('d/m/Y');
        $rs = $this->objDatos->ll_envio_sms_er($p);

        $mensajes = array();
        $vi = 0;
        foreach($rs as $index => $value){
            $msn = $value['msn'];
            $celular = $value['celular'];
            $gui_numero = $value['guia'];
            $mensajes[] = new Sms($msn, $celular, $gui_numero);
            $mensajes[$vi]->send();

            ++$vi;
        }

        $all_done = false;
        while(!$all_done){
            $all_done = true;
            foreach($mensajes as $index => $value){
                $estado = $mensajes[$index]->get_status();
                if ($estado == 0 || $estado == 1){
                    $all_done = false;
                    break;
                }
            }
        }
    }

    public function reporte_ge_sin_img($p){
        $this->include_mailer();

        $contacts = Common::read_ini_contacts(PATH . 'config/contacts.json', 'send_reporte_ge_sin_img');

        if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",date('d/m/Y'))) 
            list($dia,$mes,$anio) = split("/", date('d/m/Y'));
        $hora = 0;
        $minute = 0;
        $second = 0;
        $fecha01 = mktime($hora, $minute, $second, $mes,$dia - 2,$anio) + 0 * 24 * 60 * 60;
        $fecha01 = date('d/m/Y', $fecha01);

        $p['name_file'] = 'rpts_ge_sin_img.xlsx';
        $this->get_data_reporte_ge_sin_img($p);
        // die();

        $directorio = PATH . 'public_html/tmp/' . $p['name_file'];

        $data_html = array();
        $data_html['saludo'] = 'Buenos d&iacute;as,';
        $data_html['mensaje'] = 'Ajunto reporte de GE sin Imagen';
        $html = $this->template_mailer($data_html);

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "200.1.178.19";
        $mail->SMTPAuth   = false;                                         

        $mail->IsHTML(true);
        $asunto = 'Reporte de GE sin Imagen';
        $mail->Subject = $asunto;
        $mail->SetFrom('urbano@urbano.com.pe', $asunto);
        $mail->MsgHTML($html);

        foreach($contacts as $index => $value){
            $mail->AddAddress(trim($value['address_email']), trim($value['address_name']));
        }

        $mail->AddBCC('jbazan@urbano.com.pe');

        $mail->AddAttachment($directorio,$nombre_archivo, 'base64');
        try {
            if (!$mail->Send()) {
                $errormsn = $mail->ErrorInfo;
            }else{
                echo 'Envio correctamente!';
                unlink($directorio);
            }
        } catch(Exception $e){
            echo $e;
        }
    }

    public function get_data_reporte_ge_sin_img($p){
        $rs = $this->objDatos->get_pcll_rpt_sin_img($p);
        $p['data'] = $rs;
        $this->include_excel();
        $this->view('crontab/reporte_sin_img.php', $p);
    }
}