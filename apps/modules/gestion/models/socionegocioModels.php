<?php
//require_once PATH . 'libs/adodb/Adodb_Zend.php';

class socionegocioModels extends Adodb{
	private $dsn;
    private $db;

    public function __construct(){
        $this->dsn = Common::read_ini(PATH.'config/config.ini', 'server_scm30');//
    }

    public function scm_socionegocio_ruc($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_ruc');
        parent::SetParameterSP($p['vp_sne_ruc'] , 'varchar');
        parent::SetParameterSP($p['vp_sne_nombre'] , 'varchar');
        parent::SetParameterSP($p['vp_tipo'] , 'varchar');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }


    public function scm_socionegocio_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_shipper');
        parent::SetParameterSP($p['vp_id_sne'] , 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_socionegocio_estado_ruc($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_estado_ruc');
        parent::SetParameterSP($p['vp_id_sne'] , 'int');
        parent::SetParameterSP($p['vp_sne_estado'] , 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_tabla_detalle($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_tabla_detalle');
        parent::SetParameterSP($p['vp_tab_id'] , 'varchar');
        parent::SetParameterSP($p['vp_shi_codigo'] , 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_socionegocio_add_ruc($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_add_ruc');
        parent::SetParameterSP($p['vp_tip_insert'] , 'varchar');
        parent::SetParameterSP($p['vp_id_sne'] , 'int');    
            
        parent::SetParameterSP($p['vp_sne_tipo'] , 'varchar');
        parent::SetParameterSP($p['vp_sne_ruc'] , 'varchar');
        parent::SetParameterSP($p['vp_sne_nombre'] , 'varchar');
        parent::SetParameterSP($p['vp_tsec_id'] , 'varchar');
        parent::SetParameterSP($p['vp_sne_abc'] , 'varchar');
        parent::SetParameterSP($p['vp_ciu_id'] , 'varchar');  
        parent::SetParameterSP($p['vp_dir_factura'] , 'varchar');   
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

      public function usr_sis_personal($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'usr_sis_personal');
        parent::SetParameterSP($p['vp_prov_codigo'] , 'int');
        parent::SetParameterSP($p['vp_cargo'] , 'int');
        parent::SetParameterSP($p['vp_area'] , 'int');
        //echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

      public function scm_socionegocio_add_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_add_shipper');
        parent::SetParameterSP($p['vp_tip'] , 'varchar');
        parent::SetParameterSP($p['vp_id_sne'] , 'int');
        parent::SetParameterSP($p['vp_shi_codigo'] , 'int');
        parent::SetParameterSP($p['vp_shi_id'] , 'varchar');
        parent::SetParameterSP($p['vp_shi_nombre'] , 'varchar');
        
        parent::SetParameterSP($p['vp_fec_ing'] , 'varchar');
        parent::SetParameterSP($p['vp_tip_emp'] , 'varchar');//vp_tip_emp
        parent::SetParameterSP($p['vp_per_id_ven'] , 'int');
        parent::SetParameterSP($p['vp_per_id_sac'] , 'int');

       // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

      public function scm_socionegocio_add_contacto($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_add_contacto');
        parent::SetParameterSP($p['id_contacto'] , 'int');
        parent::SetParameterSP($p['shi_codigo'] , 'int');
        parent::SetParameterSP($p['con_codigo'] , 'varchar');
        parent::SetParameterSP($p['id_agencia'] , 'int');
        parent::SetParameterSP($p['tcon_id'] , 'int');
        parent::SetParameterSP($p['con_nombre'] , 'varchar');
        parent::SetParameterSP($p['con_cargo'] , 'varchar');
        parent::SetParameterSP($p['con_email'] , 'varchar');
        parent::SetParameterSP($p['dir_npiso'] , 'varchar');
        parent::SetParameterSP($p['dir_office'] , 'varchar');
        parent::SetParameterSP($p['telf'] , 'varchar');
        parent::SetParameterSP($p['anexo'] , 'varchar');
        parent::SetParameterSP(USR_ID , 'varchar');
        
       // echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }    
    

    public function scm_gestion_personal_ubigeo($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_gestion_personal_ubigeo');
        parent::SetParameterSP(trim($p['va_departamento']), 'int');
        parent::SetParameterSP(trim($p['va_provincia']), 'char');
        parent::SetParameterSP(trim($p['va_distrito']), 'char');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_socionegocio_add_agencia($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_add_agencia');
        parent::SetParameterSP(trim($p['vp_shi_codigo']), 'int');
        parent::SetParameterSP(trim($p['vp_id_agencia']), 'int');
        parent::SetParameterSP(trim($p['vp_nom_age']), 'char');
        parent::SetParameterSP(trim($p['vp_cod_age']), 'char');
        parent::SetParameterSP(trim($p['vp_tage_id']), 'int');

        parent::SetParameterSP(trim($p['vp_dir_id']), 'int');
        parent::SetParameterSP(trim($p['vp_id_geo']), 'int');
        parent::SetParameterSP(trim($p['vp_ciu_id']), 'int');
        parent::SetParameterSP(trim($p['vp_via_id']), 'int');
        parent::SetParameterSP(trim($p['vp_dir_calle']), 'char');
        parent::SetParameterSP(trim($p['vp_dir_numvia']), 'char');
        parent::SetParameterSP(trim($p['vp_dir_referent']), 'char');
        parent::SetParameterSP(trim($p['vp_urb_id']), 'int');
        parent::SetParameterSP(trim($p['vp_urb_nombre']), 'char');
        parent::SetParameterSP(trim($p['vp_mz_id']), 'int');
        parent::SetParameterSP(trim($p['vp_mz_nom']), 'char');
        parent::SetParameterSP(trim($p['vp_num_lote']), 'char');
        parent::SetParameterSP(trim($p['vp_num_int']), 'char');
        parent::SetParameterSP(trim($p['vp_lat']), 'int');
        parent::SetParameterSP(trim($p['vp_lon']), 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_socionegocio_select_agencia_shi($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_select_agencia_shi');
        parent::SetParameterSP(trim($p['vp_shi_codigo']), 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_socionegocio_select_contacto_shi($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_select_contacto_shi');
        parent::SetParameterSP(trim($p['vp_shi_codigo']), 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_ss_agencia_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_ss_agencia_shipper');
        parent::SetParameterSP(trim($p['vp_shi_codigo']), 'int');
        parent::SetParameterSP(USR_ID, 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_socionegocio_estado_contacto($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_estado_contacto');
        parent::SetParameterSP(trim($p['vp_id_contacto']), 'int');
        parent::SetParameterSP(trim($p['vp_stado']), 'char');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_socionegocio_estado_activity($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_estado_activity');
        parent::SetParameterSP(trim($p['vp_id_agencia']), 'int');
        parent::SetParameterSP(trim($p['vp_stado']), 'char');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_socionegocio_estado_socio_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_estado_socio_shipper');
        parent::SetParameterSP(trim($p['vp_id_sne']), 'int');
        parent::SetParameterSP(trim($p['vp_shi_codigo']), 'int');
        parent::SetParameterSP(trim($p['vp_stado']), 'char');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }

    public function scm_socionegocio_select_shipper($p){
        parent::ReiniciarSQL();
        parent::ConnectionOpen($this->dsn, 'scm_socionegocio_select_shipper');
        parent::SetParameterSP(trim($p['vp_shi_codigo']), 'int');
        //   echo '=>' . parent::getSql();die();
        $array = parent::ExecuteSPArray();
        return $array; 
    }    

        

}