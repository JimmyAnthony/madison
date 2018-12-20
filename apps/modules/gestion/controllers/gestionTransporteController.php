<?php
class gestionTransporteController extends AppController {
    private $objDatos;
    private $arrayMenu;

    public function __construct(){
        $this->valida();
        $this->objDatos = new gestionTransporteModels();
    }

    public function index($p){
        $this->view('/gestion_transportes/form_index.php', $p);
    }
    public function get_usr_sis_provincias($p){
        $rs = $this->objDatos->usr_sis_provincias($p);
        $array = array();
        foreach ($rs as $fila) {
            $array[] = $fila;
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return json_encode($data);
    }
    public function get_scm_track_panel_unidades($p){
        $rs = $this->objDatos->scm_track_panel_unidades($p);
        $array = array();
        foreach ($rs as $fila) {
            $fila['id']=$fila['id_placa'];
            //$fila['styleactive']=((int)$fila['estado'])?'StyleActive':'StyleInactive';

            $fila['styleactive']=((int)$fila['estado'])?'<div class="StyleActive"><img src="/images/icon/green-star-army-geo-location-point-16.png" /></div>':'';
            $fila['styleactive'].='<a onClick="gestion_transporte.getFromFinishRoute();" class="StyleLock"><img src="/images/icon/padlock-closed.png"/></a>';
            $fila['fecha']=(!empty($p['vp_fecha']))?$p['vp_fecha']:date('d/m/Y');
            $fila['agencia']=(!empty($p['vp_agencia']))?$p['vp_agencia']:PROV_CODIGO;
            $fila['batery']=$this->getBatery($fila['bateria']);
            $fila['progress']=($fila['tot_servicio'] - $fila['pendientes']);
            $fila['DL']=$fila['tot_dl'];
            $fila['MOT']=($fila['progress'] - $fila['tot_dl']);
            $fila['P_DL']=$this->getPorcent($fila['tot_dl'],$fila['tot_servicio']);
            $fila['P_AU']=$this->getPorcent($fila['tot_ausente'],$fila['tot_servicio']);
            $fila['P_RC']=$this->getPorcent($fila['tot_rechazo'],$fila['tot_servicio']);
            $fila['P_RZ']=$this->getPorcent($fila['rezagos'],$fila['tot_servicio']);
            $fila['P_PE']=$this->getPorcent($fila['pendientes'],$fila['tot_servicio']);
            $array[] = $fila;
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return json_encode($data);
    }
    public function getPorcent($c,$d){
        return round((float)$c*100/(float)$d);
    }
    public function getBatery($bateria){
        return floor((float)$bateria*8/100);
    }
    public function get_scm_track_panel_unidades_carga($p){
        $rs = $this->objDatos->scm_track_panel_unidades_carga($p);
        $array = array();
        $fila['nivel']=0;
        $array[] = $fila;
        $j=1;
        foreach ($rs as $fila) {
            //$fila['id']=$p['vp_id_placa'];
            $fila['orden']=$j;
            if(empty($fila['hora_estimada'])){
                $fila['hora_estimada']="00:00";
            }
            if(empty($fila['hora_real'])){
                $fila['hora_real']="00:00";
            }

            if($fila['hora_real']=="00:00"){
                $fila['hora_line']=$fila['hora_estimada'];
            }else{
                $fila['hora_line']=$fila['hora_real'];
            }
            $fila['stylestatus']=$this->getStyleStatus($fila['chk']);

            $fila['nivel']=1;
            $array[] = $fila;
            $j++;
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return json_encode($data);
    }
    public function getStyleStatus($chk){
        switch ($chk) {
            case 'LD':
                return 'grisStyle';
            break;
            case 'DL':
                return 'blueStyle';
            break;
            case 'CV':
                return 'redstyle';
            break;
            default:
                return 'grisStyle';
                break;
        }
    }
    public function get_scm_api_track_datos_cliente($p){
        $rs = $this->objDatos->scm_api_track_datos_cliente($p);
        $array = array();
        foreach ($rs as $fila) {
            $array[] = $fila;
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return json_encode($data);
    }
    public function get_scm_track_panel_gps_unidad($p){
        $rs = $this->objDatos->scm_track_panel_gps_unidad($p);
        $array = array();
        foreach ($rs as $fila) {
            $array[] = $fila;
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return json_encode($data);
    }
    public function get_img_tracks($p){
        $rs = $this->objDatos->get_img_tracks($p);
        $array = array();
        foreach ($rs as $fila) {
            $fila['img_path']=trim($fila['img_path']);
            $fila['img_thumbs']=$this->getMakeThumb(PATH."public_html".trim($fila['img_path']),$fila['img_id'].".jpg",70);
            $array[] = $fila;
        }
        $data = array(
            'success' => true,
            'total' => count($array),
            'data' => $array
        );
        return json_encode($data);
    }
    public function getMakeThumb($src, $dest, $desired_width) {
        if(file_exists(PATH."/public_html/tmp/fotos_iridio/".$dest)) { unlink (PATH."/public_html/tmp/fotos_iridio/".$dest); }
        $source_image = imagecreatefromjpeg($src);
        $width = imagesx($source_image);
        $height = imagesy($source_image);
        //$desired_height = floor($height * ($desired_width / $width));
        $virtual_image = imagecreatetruecolor($desired_width, $desired_width);//$desired_height
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_width, $width, $height);//$desired_height
        imagejpeg($virtual_image, PATH."/public_html/tmp/fotos_iridio/".$dest);
        return "/tmp/fotos_iridio/".$dest;
    }
}
?>