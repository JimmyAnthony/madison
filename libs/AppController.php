<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */


require_once PATH . 'libs/Auth.php';

class AppController extends \Geekode\Geekode{

    function __construct(){

    }

    /**
     * Instancia a la clase Auth para validar la session del usuario.
     */
    function valida(){
        $objAuth = new Auth();
        $objAuth->valida();
    }

    /**
     * Instancia a la clase Auth para finalizar la session usuario.
     */
    function expire(){
        $objAuth = new Auth();
        $objAuth->expire();
    }

    function status(){
        $objAuth = new Auth();
        return $objAuth->status();
    }

    /**
     * Se encarga de renderizar las vistas.
     */
    function view($path = '', $p = array()){
        try{
            if (!file_exists(APPPATH_VIEW . $path)){
                throw new Exception('This views you request was not found.', 404);
            }else{
                require APPPATH_VIEW . $path;
            }
        } catch (Exception $e) {
            require_once PATH . 'public_html/template/error.php';
        }
    }

    function getImg_base64($img){
        $path = PATH . 'public_html/images/front/'. $img;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    function template_mailer($p = array(), $data = array()){
        $html = '';
        $html.= '<table bgcolor="#D9D9D9" cellpadding="0" cellspacing="0" width="100%">';
            $html.= '<tr>';

                $html.= '<td><img src="http://www.urbano.com.pe/images/front/logo_urbano.png" style=""/></td>';
                $html.= '<td style="padding-top: 20px; padding-left: 5px;" align="left"><img src="http://www.urbano.com.pe/images/front/flag-pe.png" style=""/></td>';
                $html.= '<td width="100%">&nbsp;</td>';
            $html.= '</tr>';
            $html.= '<tr>';
                $html.= '<td colspan="3" style="padding: 10px;">';
                    $html.= '<table bgcolor="#FFFFFF" width="100%" cellpadding="5">';
                        $html.= '<tr>';
                            $html.= '<td style="font-size: 14px; font-family: sans-serif;">';
                                $html.= '<p>';
                                    $html.= '<span style="">' . $p["saludo"] .'</span>';
                                $html.= '</p>';
                                $html.= '<p>' . $p["mensaje"] .'</p>';

                                $html.= '<div>';
                                if (count($data['data']) > 0){
                                    $html.= '<table bgcolor="#FFFFFF" width="100%" cellpadding="2" cellspacing="0">';
                                        $html.= '<tr bgcolor="#E30909" style="color: white; font-size: 14px; text-transform: uppercase;">';
                                        foreach($data['header'] as $index => $value){
                                            $html.= '<td style="border: 1px solid #D9D9D9; white-space: nowrap;">'. $value .'</td>';
                                        }
                                        $html.= '</tr>';
                                        foreach($data['data'] as $index => $value){
                                            $background = $index % 2 == 0 ? 'white' : '#DEDEDE';
                                            $html.= '<tr style="background-color: ' . $background . '; color: black; font-size: 13px;">';
                                            foreach($value as $index01 => $value01){
                                                $html.= '<td style="border: 1px solid #D9D9D9; white-space: nowrap; ">'. (empty($value01) ? '&nbsp;' : $value01 ) .'</td>';
                                            }
                                            $html.= '</tr>';
                                        }
                                    $html.= '</table>';
                                }
                                $html.= '</div>';

                                $html.= '<p>Cordialmente,</p>';
                                $html.= '<p style="color:gray;">--</p>';
                                $html.= '<p style="color:gray;">Urbano Per&uacute; S.A.</p>';
                            $html.= '</td>';
                        $html.= '</tr>';
                    $html.= '</table>';
                $html.= '</td>';
            $html.= '</tr>';
            $html.= '<tr bgcolor="#333333">';
                $html.= '<td align="center" style="color: white; font-family: sans-serif; font-size: 10px;" colspan="3">';
                    $html.= '<p>';
                        $html.= '<span style="display: block;">Copyright&copy; Urbano Per&uacute;</span>';
                        $html.= '<span style="display: block;">Todos los derechos reservados</span>';
                    $html.= '</p>';
                $html.= '</td>';
            $html.= '</tr>';
        $html.= '</table>';
        return $html;
    }

    function response($data, $content_type = 'application/json'){
        $this->set_headers('Content-Type', $content_type);
        return $this->getData($data);
    }

    function include_excel(){
        set_time_limit(180);
        ini_set("memory_limit", "-1");
        require_once PATH . 'libs/PHPExcel/PHPExcel.php';
    }

    function include_mailer(){
        require_once PATH . 'libs/phpmailer/class.phpmailer.php';
    }

}
