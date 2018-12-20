<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

class Adodb {

    private $arrayDsn=array();
    private $conex;
    private $parameter=array();
    private $nomProcedure='';
    private $scheme = '';
    private $data='';
    private $arrayData=array();
    private $query;

    public function  __construct(){

    }

    public function ConnectionOpen($_dsn=array(),$_sp='', $_scheme = 'dbo'){
        $this->arrayDsn = $_dsn;
        $this->nomProcedure = $_sp;
        $this->scheme = $_scheme;
        
        switch (strtoupper($this->arrayDsn['dbtype'])){
            case 'MYSQL':
                if (mysqli_connect_errno()){
                    printf("Connect failed: %s\n", mysqli_connect_error());
                    exit();
                }
                $this->conex = new mysqli($this->arrayDsn['dbhost'], $this->arrayDsn['dbuser'], $this->arrayDsn['dbpass']);
                $this->conex->select_db($this->arrayDsn['dbname']);
            break;
            case 'MSSQL':
                $this->conex = mssql_connect($this->arrayDsn['dbhost'], $this->arrayDsn['dbuser'], $this->arrayDsn['dbpass']);
                if (!$this->conex){
                    exit();
                }else{
                    $db = mssql_select_db($this->arrayDsn['dbname'], $this->conex);
                }
            break;
            case 'INFORMIX':
                try{
                    $this->arrayDsn = $_dsn;
                    $this->arrayDsn['dbname'] = $_db==''?$_dsn['dbname']:$_db;
                    $this->nomProcedure = $_sp;
                    $dsn = $_dsn['dbtype'].':host='.$_dsn['dbhost'].';service='.$_dsn['dbservices'].';database='.$_dsn['dbname'].';server='.$_dsn['dbserver'].';protocol='.$_dsn['protocolo'].';EnableScrollableCursors='.$_dsn['scrolle'];
                    $this->conex = new PDO($dsn,$_dsn['dbuser'],$_dsn['dbpass']);
                }catch(PDOException $e){
                    throw new Exception("Error:Conexion Fallida");
                }
            break;
        }
    }

    public function SetParameterSP($pValue,$_tparam){
        $_tparam=trim($_tparam)==''?'VARCHAR':trim($_tparam);
        switch(strtoupper($_tparam)){
            case "NUMERIC": case "INT": case "INTEGER": case "DECIMAL":
                if ($pValue=="")                        $pValue="NULL";
                else if(strtoupper($pValue)=="NULL")    $pValue = "NULL";   
                else                                    $pValue = "$pValue";                
            break;
            case "VARCHAR": case "TEXT":    default:        
                if ($pValue=="")                        $pValue="''";
                else if(strtoupper($pValue)=="NULL")    $pValue = "NULL";   
                else                                    $pValue = "'$pValue'";  
            break;   
            case "DATE":
                if ($pValue=="")                        $pValue="''";
                else if(strtoupper($pValue)=="NULL")    $pValue = "''";
                else                                    $pValue = "'".Common::getFormatDMY($pValue)."'";
        }
        $this->parameter[]=$pValue;
    }

    public function Prepare_Procedure(){
        switch (strtoupper($this->arrayDsn['dbtype'])){
            case 'MYSQL':
                $query = " Call ".$this->nomProcedure."(";
                if(count($this->parameter)>0) 
                    foreach ($this->parameter as $value) $query.=$value.",";
                $len = strlen($query);
                if(count($this->parameter)>0) 
                    $len-=1;
                $this->query = substr($query, 0, $len).")";
            break;
            case 'MSSQL':
                $query = " Execute ".$this->scheme.'.'.$this->nomProcedure." ";
                if(count($this->parameter)>0) 
                    foreach ($this->parameter as $value) $query.=$value.",";
                $len = strlen($query);
                if(count($this->parameter)>0) 
                    $len-=1;
                $this->query = substr($query, 0, $len)." ";
            break;
            case 'INFORMIX':
                $query = " Execute Procedure ".$this->arrayDsn['dbname'].":".$this->nomProcedure."(";
                if(count($this->parameter)>0) foreach ($this->parameter as $value) $query.=$value.",";
                $len=strlen($query);
                if(count($this->parameter)>0) $len-=1;
                $this->query=substr($query, 0, $len).")";
            break;
        }
        return $this->query;
    }

    public function getSql(){
        return $this->Prepare_Procedure();
    }

    public function ExecuteSP(){
        $this->conex;
        $query = $this->Prepare_Procedure();
        switch (strtoupper($this->arrayDsn['dbtype'])){
            case 'MYSQL':
                $this->data = $this->conex->query($query);
            break;
            case 'MSSQL':
                $this->data = mssql_query($query);
            break;
            case 'INFORMIX':
                $this->data = $this->conex->query($query);
            break;
        }
    }

    public function ExecuteSPArray($noInclude = array()){
        $this->ExecuteSP();
        switch (strtoupper($this->arrayDsn['dbtype'])){
            case 'MYSQL':
                if ( !empty($this->data->num_rows) || intval($this->data->num_rows) > 0 ){
                    while($data = $this->data->fetch_array(MYSQLI_BOTH)){
                        $this->arrayData[]=$data;
                    }
                }
            break;
            case 'MSSQL':
                $num_rows = mssql_num_rows($this->data);
                if (!empty($num_rows) || intval($num_rows) > 0){
                    mssql_data_seek($this->data, 0);
                    while ($row = mssql_fetch_array($this->data, MSSQL_BOTH)) {
                        $this->arrayData[] = $row;
                    }
                    $this->arrayData = Common::iaLower($this->arrayData);
                }
            break;
            case 'INFORMIX':
                // var_dump($this->data->fetchAll(PDO::FETCH_ASSOC));
                // echo '<pre>' , print_r($this->data->fetch(PDO::FETCH_ASSOC)), '</pre>';
                // die();
                $this->arrayData = Common::UTF8(Common::iaLower($this->data->fetchAll(PDO::FETCH_ASSOC)), $noInclude);
            break;
        }
        return count($this->arrayData)>0?$this->arrayData:array();
    }

    public function ReiniciarSQL(){
        unset ($this->query);
        unset ($this->parameter);
        unset ($this->arrayData);
    }

    public function Close(){
        //$this->data->close();
        //$this->conex->close();
    }

    public function setQuery($_query=''){
        $this->query = $_query;
    }
    
}
