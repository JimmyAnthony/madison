<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('personal-tab')){
    var personal = {
        id: 'personal',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/personal/',
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'per_id', type:'int'},
                    {name: 'per_codigo', type:'string'},
                    {name: 'per_apellido', type:'string'},
                    {name: 'per_nombre', type:'string'},
                    {name: 'doc_numero', type:'string'},
                    {name: 'per_telefono', type:'string'},
                    {name: 'car_nombre', type:'string'},
                    {name: 'area_nombre', type:'string'},
                    {name: 'per_estado', type:'string'},
                    {name: 'direccion', type:'string'},
                    {name: 'ciu_id', type:'int'},
                    {name: 'ciu_ubigeo', type:'string'},
                    {name: 'l_per_telefono', type:'string'},
                    {name: 'l_per_rpm', type:'string'},
                    {name: 'cel_id', type:'string'},
                    {name: 'l_per_email', type:'string'},
                    {name: 'l_fec_ingreso', type:'string'},
                    {name: 'l_per_estado', type:'string'},
                    {name: 'prov_codigo', type:'int'},
                    {name: 'id_area', type:'int'},
                    {name: 'id_cargo', type:'int'},
                    {name: 'fecha_cese', type:'string'},

                    
                    
                ],
                proxy:{
                    type: 'ajax',
                    url: personal.url+'get_gestion_busq_personal/',
                    reader:{
                        type: 'json',
                        root: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        
                    }
                }
            });

            var panel = Ext.create('Ext.form.Panel',{
                id: personal.id+'-form',
                border:false,
                layout: 'fit',
                defaults:{
                    border: false
                },
                tbar:[
                    'Agencia:',
                    {
                        xtype: 'combo',
                        id: personal.id + '-agencia',
                        //editable:false,
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'prov_codigo', type: 'int'},
                                {name: 'prov_nombre', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: personal.url + 'get_usr_sis_provincias/',
                                reader:{
                                    type: 'json',
                                    rootProperty: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        valueField: 'prov_codigo',
                        displayField: 'prov_nombre',
                        listConfig:{
                            minWidth: 200
                        },
                        width: 150,
                        forceSelection: true,
                       //allowBlank: false,
                        selectOnFocus:true,
                        emptyText: '[ Seleccione ]',
                        listeners:{
                            afterrender: function(obj,record,options){
                                obj.getStore().load({
                                    params:{vp_id_linea:0},
                                    callback: function(){
                                     obj.setValue('<?php echo PROV_CODIGO?>');  

                                    }
                                });
                            }
                        }
                    },'-',
                    'Area',
                    {
                        xtype:'combo',
                        id:personal.id+'-area',
                        store:Ext.create('Ext.data.Store',{
                            fields:[
                                    {name:'id_area',type:'int'},
                                    {name:'area_nombre',type:'string'}
                            ],
                            proxy:{
                                type:'ajax',
                                url:personal.url+'scm_usr_sis_area/',
                                reader:{
                                    type:'json',
                                    rootProperty:'data'
                                }
                            }
                        }),
                        queryMode:'local',
                        valueField:'id_area',
                        displayField:'area_nombre',
                        listConfig:{
                            minWidth:300
                        },
                        width:100,
                        forceSelection:true,
                        emptyText: '[ Seleccione ]',
                        listeners:{
                            afterrender:function(obj,record,opts){
                                obj.getStore().load({
                                    params:{vp_id_area:0},
                                    callback:function(){
                                        obj.setValue(0);
                                    }
                                });
                            }
                        }
                    },
                    'Codigo RRHH:',
                    {
                        xtype:'textfield',
                        id:personal.id+'-codigorrhh',
                        //:[0-5][0-9]
                        //plugins: [new ueInputTextMask('X[0|1]X9:99 X[a|p]Xm', false)],
                        //plugins:  [new ueInputTextMask('99:99')],
                        //maskRe:'([01]?[0-9]|2[0-3]):[0-5][0-9]$',
                        width:64,
                        enableKeyEvents: true,
                            listeners:{
                                keypress:function(id,e){
                                    var code = e.getCharCode();
                                    if(code==13){
                                        personal.consultar();
                                    }
                                }
                            }
                    },'-',
                    'N° Documento:',
                    {
                        xtype:'textfield',
                        id:personal.id+'-dni',
                        maskRe : /[0-9]$/,
                        width:80,
                        enableKeyEvents: true,
                        listeners:{
                            keypress:function(id,e){
                                var code = e.getCharCode();
                                if(code==13){
                                    personal.consultar();
                                }
                            }
                        }
                    },'-',
                    'Apellidos:',
                    {
                        xtype:'textfield',
                        id:personal.id+'-apellidos',
                        width:130,
                        enableKeyEvents: true,
                        listeners:{
                            keypress:function(id,e){
                                var code = e.getCharCode();
                                if(code==13){
                                    personal.consultar();
                                }
                            }
                        }
                    },'-',
                     'Nombres:',
                    {
                        xtype:'textfield',
                        id:personal.id+'-nombres',
                        width:130,
                        enableKeyEvents: true,
                        listeners:{
                            keypress:function(id,e){
                                var code = e.getCharCode();
                                if(code==13){
                                    personal.consultar();
                                }
                            }
                        }                        
                    },'-',
                    {
                        text: 'Buscar',
                        id: personal.id + '-btn-buscar',
                        icon: '/images/icon/search.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 2, 
                                    id_btn: obj.getId(), 
                                    id_menu: personal.id_menu,
                                    fn: ['personal.consultar']
                                });
                            },
                            click: function(obj, e){
                                personal.consultar();
                            }
                        }
                    },'-',
                    {
                        text: 'Nuevo',
                        id: personal.id + '-btn-nuevo',
                        icon: '/images/icon/new_file.ico',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 1, 
                                    id_btn: obj.getId(), 
                                    id_menu: personal.id_menu,
                                    fn: ['personal.show_nuevo']
                                });
                            },
                            click: function(obj, e){
                                try {
                                    Ext.getCmp(newpersonal.id + '-win').show();
                                }
                                catch(err) {
                                    
                                }
                                
                            }
                        }
                    },'-',
                    {
                        text: 'Excel',
                        id: personal.id + '-btn-excel',
                        icon: '/images/icon/excel.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 5, 
                                    id_btn: obj.getId(), 
                                    id_menu: personal.id_menu,
                                    fn: ['personal.exportar_xls']
                                });
                            },
                            click: function(obj, e){
                                personal.exportar_xls();
                            }
                        }
                    }



                ],
                items:[
                    {
                        xtype: 'grid',
                        id: personal.id + '-grid',
                        store: store,
                        columnLines: true,
                        features: [
                         /*   {
                                ftype: 'summary',
                                dock: 'bottom',
                              }
                         */   
                        ],
                        columns:{
                            items:[
                                {
                                    text: '&nbsp;',
                                    dataIndex: '',
                                    width: 60,
                                    align: 'center',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        //console.log(record);
                                        metaData.style = "padding: 0px; margin: 0px";
                                        return global.permisos({
                                            type: 'link',
                                            id_menu: personal.id_menu,
                                            icons:[
                                                {id_serv: 6, img: 'editar.png', qtip: 'Click para Editar Personal.', js: 'personal.show_editar()'},
                                                {id_serv: 6, img: 'user_1.png', qtip: 'Click para Editar Personal.', js: 'personal.show_sys_permiss('+record.get('per_id')+')'}
                                            ]
                                        });
                                    }
                                },
                                {
                                    text: 'Codigo RRHH',
                                    dataIndex: 'per_codigo',
                                    width: 100,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'N° Documento',
                                    dataIndex: 'doc_numero',
                                    width: 100,
                                    align: 'left'
                                },
                                {
                                    text: 'Apellidos',
                                    dataIndex: 'per_apellido',
                                    //width: 200,
                                    flex:1,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'Nombres',
                                    dataIndex: 'per_nombre',
                                    //width: 200,
                                    flex:1,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {                
                                    text: 'Trabaja en Agencia',
                                    dataIndex: 'prov_nombre',
                                    //width: 100,
                                    flex:1,
                                    cls: 'column_header_double',
                                    align: 'left'
                                },
                                {
                                    text: 'Cargo',
                                    dataIndex: 'car_nombre',
                                    //width: 150,
                                    flex:1,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'Area',
                                    dataIndex: 'area_nombre',
                                    width: 150,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'Telefono',
                                    dataIndex: 'per_telefono',
                                    width: 100,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'Estado',
                                    dataIndex: 'per_estado',
                                    width: 100,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },                           
                            ],
                            defaults:{
                               // menuDisabled: true
                               sortable: true
                            }
                        },
                        viewConfig: {
                            stripeRows: true,
                            enableTextSelection: false,
                            markDirty: false
                        },
                        trackMouseOver: true,
                       
                    }
                ]
            });
            tab.add({
                id: personal.id+'-tab',
                border: false,
                autoScroll: true,
                closable: true,
                layout:{
                    type: 'fit'
                },
                items:[
                    panel
                ],
                listeners:{
                    beforerender: function(obj, opts){
                        global.state_item_menu(personal.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        /*Ext.getCmp(personal.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + personal.id_menu).text,
                            icon: Ext.getCmp('menu-' + personal.id_menu).icon
                        });*/
                        global.state_item_menu_config(obj,personal.id_menu);
                        try {
                            personal.show_nuevo();
                        }
                        catch(err) {
                            
                        }
                        

                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(personal.id_menu, false);
                      //  Ext.getCmp(newpersonal.id + '-win').remove();
                      //  Ext.getCmp(newpersonal.id + '-win').close();
                        try{
                            Ext.getCmp(newpersonal.id + '-win').destroy();
                        }catch(err){

                        }
                    }
                }
            }).show();
        },
        consultar: function(){     
            //var form = Ext.getCmp(personal.id+'-form').getForm();
   
            //if (form.isValid()){
                var grid = Ext.getCmp(personal.id + '-grid');
                var store = grid.getStore();
                var   vp_agencia    = Ext.getCmp(personal.id + '-agencia').getValue();
                var   vp_codigorrhh = Ext.getCmp(personal.id + '-codigorrhh').getValue();
                var   vp_dni        = Ext.getCmp(personal.id + '-dni').getValue();
                var   vp_apellidos  = Ext.getCmp(personal.id + '-apellidos').getValue();
                var   vp_nombres    = Ext.getCmp(personal.id + '-nombres').getValue();
                var   vp_id_area    = Ext.getCmp(personal.id + '-area').getValue();  

                 //console.log(vp_id_area);
                /*if ((vp_agencia ==null && vp_nombres != '' && vp_dni == '')||(vp_agencia ==null &&  vp_apellidos != '' && vp_dni == '')||(vp_agencia ==null && vp_codigorrhh != '')){
                    global.Msg({
                        msg:'Debe Seleccionar su Agencia',
                        icon:0,
                        fn:function(){            
                        }
                    });
                }else*/
                if ( vp_dni == '' && vp_apellidos.trim()=='' && vp_nombres.trim()== '' && vp_codigorrhh.trim()=='' && vp_agencia =='' && vp_id_area ==''){
                    global.Msg({
                        msg:'No se aceptan compos vacios',
                        icon:0,
                        fn:function(){            
                        }
                    });
                }else{
                    store.load({
                        params:{
                            vp_agencia: vp_agencia,
                            vp_codigorrhh: vp_codigorrhh,
                            vp_dni: vp_dni,
                            vp_apellidos:vp_apellidos,
                            vp_nombres:vp_nombres,
                            vp_id_area:vp_id_area
                        },
                        callback: function(records, operation, success){
                           //console.log(records[0].data['vl_err_sql'] );
                          // console.log(records[0].data);
                           if (global.isEmptyJSON(records[0].data)){
                                global.Msg({
                                        msg:'No Se Encontró Registros',
                                        icon:0,
                                        fn:function(){   
                                        }
                                    });          
                           }else{

                            //console.log(records[0].data['vl_err_sql']);
                                if (records[0].data['vl_err_sql']=='-1'){
                                    store.remove("0");
                                    global.Msg({
                                        msg:records[0].data['vl_err_info'],
                                        icon:0,
                                        fn:function(){   
                                        }
                                    });          
                                }
                            }

                        }
                    });
                }    

            /*}else{
                    global.Msg({
                        msg:'Debe Completar los campos requeridos',
                        icon:0,
                        fn:function(){            
                        }
                    });
            }*/   
        },
       
        show_nuevo: function(){
            win.show({vurl: personal.url + 'form_show_nuevo/', id_menu: personal.id_menu, class: ''});
        },
        show_editar: function(){
            win.show({vurl: personal.url + 'form_show_editar/', id_menu: personal.id_menu, class: ''});
        },
        show_sys_permiss: function(per_id){
            win.show({vurl: personal.url + 'form_show_sys_permiss/?vp_per_id='+per_id, id_menu: personal.id_menu, class: ''});
        },
        exportar_xls:function(){
            var form = Ext.getCmp(personal.id+'-form').getForm();
   
            if (form.isValid()){
                var grid = Ext.getCmp(personal.id + '-grid');
                //var store = grid.getStore();
                var   vp_agencia    = Ext.getCmp(personal.id + '-agencia').getValue();
                var   vp_codigorrhh = Ext.getCmp(personal.id + '-codigorrhh').getValue();
                var   vp_dni        = Ext.getCmp(personal.id + '-dni').getValue();
                var   vp_apellidos  = Ext.getCmp(personal.id + '-apellidos').getValue();
                var   vp_nombres    = Ext.getCmp(personal.id + '-nombres').getValue();

        window.open(personal.url+'get_excel/?&vp_agencia='+vp_agencia+'&vp_codigorrhh='+vp_codigorrhh+'&vp_dni='+vp_dni+'&vp_apellidos='+vp_apellidos+'&vp_nombres='+vp_nombres);        

             
            }else{
                    global.Msg({
                        msg:'Debe Completar los campos requeridos',//res.data[0].error_info,
                        icon:0,
                        fn:function(){            
                        }
                    });
            }  

        }
    }
    Ext.onReady(personal.init, personal);
}else{
    tab.setActiveTab(personal.id+'-tab');
}
</script>