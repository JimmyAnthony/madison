<?php

/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */
class Menu {

    private $arrayMenu;
    private $arrayServicios;

    public function  __construct($_arrayMenu = array(), $_arrayServicios = array()) {

        // echo '=>' . var_dump(DEVELOPMENT) . '</br>';

        $this->arrayServicios = $_arrayServicios;
        if (intval($_arrayMenu[0]['sql_error']) < 0 || count($_arrayMenu) < 0)
            $this->arrayMenu = array(array ('id_menu' => '1','nombre' => 'Permisos de usuario no registrado','url' => '','nivel' => '0','padre' => '0', 'icono' => 'warning.png'));
        else
            $this->arrayMenu = $_arrayMenu;
    }

    public function get_permisos($id){
        $array = array();
        foreach($this->arrayServicios as $index => $value){
            if (intval($value['menu_id']) == intval($id))
                $array[]['id'] = intval($value['servicios']);
        }
        return json_encode($array);
    }

    public function getMenu(){
        $menu = "";
        $menux="";
        $array = $this->getMenuRecursivo();
        foreach ($array as $fila){
                ++$i;
                $menux.="{";
                $menux.="text:'".trim($fila['nombre'])."'";
                $menux.=",id:'laro".$i."'";
                $menux.=",icon: '/images/menu/".trim($fila['icono'])."'";
                if(trim($fila['url']) != '' ){
                    $menux.=",listeners: {";
                                $menux.="click:function(obj, item, e, eOpts){";
                                    $menux.="win.show({vurl:\"".$fila['url']."\"});";
                                $menux.="}";
                            $menux.="}";
                }
                if(count($fila['nhijos'])>0){
                    $menux.=",menu: {";
                                $menux.="items: [";
                    $menux.=$this->getRecursividad($fila['nhijos']);
                    $menux.="]}";
                }
                $menux.="},";
        }
        $len = strlen($menux);
        $len-=1;
        $menu.=substr($menux, 0, $len);
        $menu.="";
        return $menu;
    }

    public function getRecursividad($_array){
        $menu = "";
        foreach ($_array as $fila){
            $menu.="{";
            $menu.="id: 'menu-".trim($fila['id_menu'])."'";
            $menu.=",text: '".trim($fila['nombre'])."'";
            $menu.=",permisos: '".$this->get_permisos($fila['id_menu'])."'";
            $menu.=",icon: '/images/menu/".trim($fila['icono'])."'";
            if(trim($fila['url']) != '' ){
                $menu.=",listeners: {";
                            $menu.="click:function(obj, item, e, eOpts){";
                                $menu.="var menu_class = \"".$fila['menu_class']."\";";
                                $menu.="win.show({vurl:\"".$fila['url']."\", id_menu: obj.getItemId().split('-')[1], class: menu_class});";
                            $menu.="}";
                        $menu.="}";
            }
            if(count($fila['nhijos'])>0){
                $menu.=$this->getRecursividad00($fila['nhijos']);
            }
            $menu.="},";
        }
        $len = strlen($menu);
        $len-=1;
        $menu=substr($menu, 0, $len);
        return $menu;
    }

    public function getRecursividad00($_array){
        $menu = ",menu: { items: [";
        foreach ($_array as $fila){
            $menu.="{";
            $menu.="id: 'menu-".trim($fila['id_menu'])."'";
            $menu.=",text: '".trim($fila['nombre'])."'";
            $menu.=",permisos: '".$this->get_permisos($fila['id_menu'])."'";
            $menu.=",icon: '/images/menu/".trim($fila['icono'])."'";
            if(trim($fila['url']) != '' ){
                $menu.=",listeners: {";
                            $menu.="click:function(obj, item, e, eOpts){";
                                $menu.="var menu_class = \"".$fila['menu_class']."\";";
                                $menu.="win.show({vurl:\"".$fila['url']."\", id_menu: obj.getItemId().split('-')[1], class: menu_class});";
                            $menu.="}";
                        $menu.="}";
            }
            if(count($fila['nhijos'])>0){
                $menu.=$this->getRecursividad00($fila['nhijos']);
            }
            $menu.="},";
        }
        $len = strlen($menu);
        $len-=1;
        $menu=substr($menu, 0, $len);
        $menu.="]}";
        return $menu;
    }

    public function getMenuRecursivo(){
        $array = array();
        foreach ($this->arrayMenu as $index => $value){
            if(intval($value['nivel'])==0){
                $value['icono'] = (trim($value['icono']) == '' || trim($value['icono']) == './') ? 'form.png' : $value['icono'];
                $value['nhijos'] = $this->getMenuInterno($value['id_menu']);
                $array[]=$value;
            }
        }
        return $array;
    }

    public function getMenuInterno($_parent){
        $array = array();
        foreach ($this->arrayMenu as $index => $value){
            if(intval($_parent)==intval($value['padre'])){
                $value['icono'] = (trim($value['icono']) == '' || trim($value['icono']) == './') ? 'form.png' : $value['icono'];
                $array[]=$value;
            }
        }
        return $array;
    }

}
