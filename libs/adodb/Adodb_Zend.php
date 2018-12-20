<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

class Adodb_Zend {

    private $_dbtype;
    private $_dbhost;
    private $_dbservices;
    private $_dbname;
    private $_dbserver;
    private $_protocolo;
    private $_scrolle;
    private $_dbuser;
    private $_dbpass;
    private $_path;
    private $db;

    public function __construct($options = array(), $confApply = true) {
        $op = new Zend_Registry($options);

        if ($confApply) {
            $configIni = new Zend_Config_Ini(PATH . 'config/config.ini', 'server_' . $op->nConfigServ);
            $this->settingsVars($configIni);
            $configDb = new Zend_Config(
                            array(
                                'database' => array(
                                    'adapter' => $this->_dbtype,
                                    'params' => array(
                                        'host' => $this->_dbhost,
                                        'dbname' => $this->_dbname,
                                        'username' => $this->_dbuser,
                                        'password' => $this->_dbpass
                                    )
                                )
                            )
            );

            try {
                $this->db = Zend_Db::factory($configDb->database);
                $this->db->getConnection();
            } catch (Zend_Db_Adapter_Exception $e) {
                
            } catch (Zend_Exception $e) {
                
            }
        } else {
            $configIni = new Zend_Config_Ini(PATH . 'config/config.ini', $op->nConfigServ);
            $this->settingsVars($configIni);
            $this->db = new SQLite3(PATH . $this->_path . $this->_dbname);
        }
    }

    public function settingsVars($configIni) {
        $this->_dbtype = $configIni->dbtype;
        $this->_dbhost = $configIni->dbhost;
        $this->_dbservices = $configIni->dbservices;
        $this->_dbname = $configIni->dbname;
        $this->_dbserver = $configIni->dbserver;
        $this->_protocolo = $configIni->protocolo;
        $this->_scrolle = $configIni->scrolle;
        $this->_dbuser = $configIni->dbuser;
        $this->_dbpass = $configIni->dbpass;
        $this->_path = $configIni->path;
    }

    public function QuerySql($_sql) {
        switch ($this->_dbtype) {
            case 'Mysql':
                $result = $this->db->query($_sql);
                break;
        }
        return $result;
    }

    public function ExecuteSql($_sql, $return) {
        $results = array();
        switch ($this->_dbtype) {
            case 'Mysql':
                $query = $this->QuerySql($_sql);
                $results = $query->fetchAll();
                break;
            case 'SQLite':
                if ($return) {
                    $query = $this->db->query($_sql);
                    while ($row = $query->fetchArray()) {
                        $results[] = $row;
                    }
                }else
                    $query = $this->db->exec($_sql);

                break;
        }

        return $return ? $results : array();
    }

    public function close() {
        switch ($this->_dbtype) {
            case 'Mysql': $this->db->closeConnection();
                break;
            case 'SQLite': $this->db->close();
                break;
        }
    }
    
    public function get_lastInsert(){
        switch($this->_dbtype){
            case 'Mysql':
                break;
            case 'SQLite':
                $lastInsert = $this->db->lastInsertRowID();
                break;
        }
        return $lastInsert;
    }

    public function __destruct() {
        $this->close();
    }

}

?>
