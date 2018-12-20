<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('procesarftp-tab')){
    var procesarftp = {
        id: 'procesarftp',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/procesarftp/',
        sol_id:null,
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'arch_ext', type:'int'},
                    {name: 'sol_id', type:'int'},
                    {name: 'tarch_id', type:'int'},
                    {name: 'shi_codigo', type:'int'},
                    {name: 'sol_fecha', type:'string'},
                    {name: 'sol_archivo', type:'string'},            
                    {name: 'sol_estado', type:'string'},
                    {name: 'cic_inicio', type:'string'},            
                    {name: 'pro_descri', type:'string'},    
                    {name: 'shi_nombre', type:'string'},    
                    {name: 'estado_descri', type:'string'},  
                    {name: 'tot_data', type:'int'},
                    {name: 'tot_error', type:'int'},
                    {name: 'usr_codigo', type:'string'},
                    {name: 'sol_hora', type:'string'},
                    {name: 'pu_total', type:'int'},
                    {name: 'pu_pieza', type:'int'},
                ],
                proxy:{
                    type: 'ajax',
                    url: procesarftp.url+'scm_gestor_consulta_ftp/',
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
                id: procesarftp.id+'-form',
                border:false,
                layout: 'fit',
                defaults:{
                    border: false
                },
                tbar:[
                    'Shipper:',
                    {
                        xtype: 'combo',
                        id: procesarftp.id + '-shipper',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'shi_codigo', type: 'int'},
                                {name: 'shi_nombre', type: 'string'},
                                {name: 'shi_id', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: procesarftp.url + 'get_usr_sis_shipper/',
                                reader:{
                                    type: 'json',
                                    rootProperty: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        valueField: 'shi_codigo',
                        displayField: 'shi_nombre',
                        listConfig:{
                            minWidth: 350
                        },
                        width: 150,
                        forceSelection: true,
                        allowBlank: false,
                        selectOnFocus:true,
                        emptyText: '[ Seleccione ]',
                        listeners:{
                            afterrender: function(obj,record,options){
                                obj.getStore().load({
                                    params:{
                                        vp_linea: 0    
                                    },
                                    callback: function(){
                                        obj.setValue(0);  
                                     
                                    }
                                });
                            }
                        }
                    },'Tipo:',
                    {
                        xtype: 'combo',
                        id: procesarftp.id + '-tipo-archivo',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'descripcion', type: 'string'},
                                {name: 'id_elemento', type: 'int'},
                                {name: 'des_corto', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: procesarftp.url + 'get_scm_tabla_detalle/',
                                reader:{
                                    type: 'json',
                                    rootProperty: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        valueField: 'id_elemento',
                        displayField: 'descripcion',
                        listConfig:{
                            minWidth: 150
                        },
                        width: 80,
                        forceSelection: true,
                        allowBlank: false,
                        selectOnFocus:true,
                        emptyText: '[ Seleccione ]',
                        listeners:{
                            afterrender: function(obj,record,options){
                                obj.getStore().load({
                                    params:{
                                        vp_tab_id: 'TAD',
                                        vp_shipper: 0
                                    },
                                    callback: function(){
                                     obj.setValue(100);  
                                    }
                                });
                            }
                        }
                    },'Linea:',
                    {
                        xtype: 'combo',
                        id: procesarftp.id + '-linea',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'id', type: 'int'},
                                {name: 'nombre', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: procesarftp.url + 'get_usr_sis_linea_negocio/',
                                reader:{
                                    type: 'json',
                                    rootProperty: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        valueField: 'id',
                        displayField: 'nombre',
                        listConfig:{
                            minWidth: 150
                        },
                        width: 80,
                        forceSelection: true,
                        allowBlank: false,
                        selectOnFocus:true,
                        emptyText: '[ Seleccione ]',
                        listeners:{
                            afterrender: function(obj,record,options){
                                obj.getStore().load({
                                    params:{
                                       vp_linea:0     
                                    },
                                    callback: function(){
                                        obj.setValue(0);  
                                    }
                                });
                            },
                            select: function(combo, records, opts){
                                var cmb_producto = Ext.getCmp(procesarftp.id + '-producto');
                                cmb_producto.getStore().load({
                                    params:{
                                        vp_shipper: Ext.getCmp(procesarftp.id + '-shipper').getValue(),
                                        vp_linea: records.get('id')
                                    },
                                    callback: function(){
                                        cmb_producto.clearValue();
                                    }
                                });
                            }
                        }
                    },'Producto:',
                    {
                        xtype: 'combo',
                        id: procesarftp.id + '-producto',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'id_orden', type: 'int'},
                                {name: 'pro_nombre', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: procesarftp.url + 'get_usr_sis_productos/',
                                reader:{
                                    type: 'json',
                                    rootProperty: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        valueField: 'id_orden',
                        displayField: 'pro_nombre',
                        listConfig:{
                            minWidth: 250
                        },
                        width: 100,
                        forceSelection: true,
                        //allowBlank: false,
                        selectOnFocus:true,
                        emptyText: '[ Seleccione ]',
                        listeners:{
                            afterrender: function(obj,record,options){
                                obj.getStore().load({
                                    params:{},
                                    callback: function(){

                                     obj.setValue();  

                                    }
                                });
                            }
                        }
                    },'Desde:',
                    {
                        xtype:'datefield',
                        //fieldLabel: 'Desde',
                        id:procesarftp.id+'-desde',
                        //labelWidth:60,
                        width: 90,
                       // width:'100%',
                        anchor:'100%',
                        value: new Date()
                    },'Hasta:',
                    {
                        xtype:'datefield',
                        //fieldLabel: 'Hasta',
                        id:procesarftp.id+'-hasta',
                        //labelWidth:60,
                        width: 90,
                       // width:'100%',
                        anchor:'100%',
                        value: new Date()
                    }
                    ,'Estado:',
                    {
                        xtype: 'combo',
                        id: procesarftp.id + '-estado',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'descripcion', type: 'string'},
                                {name: 'id_elemento', type: 'int'},
                                {name: 'des_corto', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: procesarftp.url + 'get_scm_tabla_detalle/',
                                reader:{
                                    type: 'json',
                                    rootProperty: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        valueField: 'id_elemento',
                        displayField: 'descripcion',
                        listConfig:{
                            minWidth: 200
                        },
                        width: 100,
                        forceSelection: true,
                        allowBlank: false,
                        selectOnFocus:true,
                        emptyText: '[ Seleccione ]',
                        listeners:{
                            afterrender: function(obj,record,options){
                                obj.getStore().load({
                                    params:{
                                        vp_tab_id: 'EOT',
                                        vp_shipper: 0
                                    },
                                    callback: function(){
                                     obj.setValue(100);  
                                    }
                                });
                            }
                        }
                    },'-',
                    {
                        text: 'Buscar',
                        id: procesarftp.id + '-btn-buscar',
                        icon: '/images/icon/search.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 42, 
                                    id_btn: obj.getId(), 
                                    id_menu: procesarftp.id_menu,
                                    fn: ['procesarftp.consultar']
                                });
                            },
                            click: function(obj, e){
                                procesarftp.consultar();
                            }
                        }
                    },'-',
                    {
                        text: 'Excel',
                        id: procesarftp.id + '-btn-excel',
                        icon: '/images/icon/excel.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 44, 
                                    id_btn: obj.getId(), 
                                    id_menu: procesarftp.id_menu,
                                    fn: ['procesarftp.exportar_xls']
                                });
                            },
                            click: function(obj, e){
                                procesarftp.exportar_xls();
                            }
                        }
                    },
                    {
                        text:'Upload',
                        id:procesarftp.id +'-btn-upload',
                        icon:'/images/icon/upload-file.png',
                        listeners:{
                            beforerender:function(obj,opts){
                                global.permisos({
                                    id_serv: 49, 
                                    id_btn: obj.getId(), 
                                    id_menu: procesarftp.id_menu,
                                    fn: ['procesarftp.upload']
                                });

                            },
                            click:function(obj,e){
                                procesarftp.upload();    
                            }
                        }
                    }
                ],
                items:[
                    {
                        xtype: 'grid',
                        id: procesarftp.id + '-grid',
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
                                    text: 'Tipo',
                                    dataIndex: 'arch_ext',
                                    width: 35,
                                    align: 'center',
                                    cls: 'column_header_double',
                                    renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
                                        var html ='';
                                        if (parseInt(value)==1){
                                            html='<img src="/images/icon/txt.png" data-qtip="Archivo txt.">'
                                        }else if (parseInt(value)==2){
                                            html='<img src="/images/icon/excel.png" data-qtip="Archivo Excel">'    
                                        }else if (parseInt(value)==3){
                                            html='<img src="/images/icon/alert_red.ico" width="20px" height="20px" data-qtip="Archivo Sin Contrato">'    
                                        }
                                        //html+='</div>';
                                        return html;
                                    }
                                },
                                {
                                    text: 'N° Sol.',
                                    dataIndex: 'sol_id',
                                    width: 50,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'Shipper',
                                    dataIndex: 'shi_nombre',
                                    //width: 180,
                                    flex:1,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'Servicio',
                                    dataIndex: 'pro_descri',
                                    width: 100,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'Ciclo',
                                    dataIndex: 'cic_inicio',
                                    width: 70,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {                
                                    text: 'Fecha de Solicitud',
                                    dataIndex: 'sol_fecha',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'left'
                                },
                                {                
                                    text: 'Hora',
                                    dataIndex: 'sol_hora',
                                    width: 45,
                                    cls: 'column_header_double',
                                    align: 'left'
                                },
                                {
                                    text: 'Nombre Archivo',
                                    dataIndex: 'sol_archivo',
                                    //width: 110,
                                    flex:1,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'Tipo Archivo',
                                    dataIndex: 'tip_arch',
                                    width: 100,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'T. Data',
                                    dataIndex: 'tot_data',
                                    width: 50,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'T. Error',
                                    dataIndex: 'tot_error',
                                    width: 50,
                                    align: 'left',
                                    cls: 'column_header_double',
                                    renderer :function(value, metaData, record, rowIndex, colIndex, store, view){
                                        //console.log(value);
                                        if (parseInt(value) > 0)
                                            return '<div align="right"><a href="#" onclick="procesarftp.total_error('+record.get('tarch_id')+','+record.get('sol_id')+');">'+value+ '</a></div>';
                                    }
                                },
                                {
                                    text: 'T. PU',
                                    dataIndex: 'pu_total',
                                    width: 50,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'T. Pieza',
                                    dataIndex: 'pu_pieza',
                                    width: 50,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'Estado',
                                    dataIndex: 'estado_descri',
                                    width: 100,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: 'Usuario',
                                    dataIndex: 'usr_codigo',
                                    width: 90,
                                    align: 'left',
                                    cls: 'column_header_double',
                                },
                                {
                                    text: '&nbsp;',
                                    dataIndex: '',
                                    width: 140,
                                    align: 'center',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        metaData.style = "padding: 0px; margin: 0px;";
                                        //console.log(record.get('sol_estado'));
                                         //console.log(record.get('sol_id'));
                                        var solicitud = record.get('sol_id');
                                        var tarch_id = record.get('tarch_id');
                                        

                                        if (record.get('sol_estado')=='0'){
                                                return global.permisos({
                                                    type: 'link',
                                                    id_menu: procesarftp.id_menu,
                                                    extraCss: 'ftp-procesar-link',
                                                    icons:[
                                                        {id_serv: 0, img: 'procesar_troceo.ico', qtip: 'Actualizar Datos desde Archivo', js: ''},
                                                        {id_serv: 0, img: 'exec.gif', qtip: 'Hacer PU', js: ''},
                                                        {id_serv: 0, img: 'georeferencia.png', qtip: 'Geo-Referencia Una Dirección.', js: ''},
                                                        {id_serv: 0, img: 'download.png', qtip: 'Descargar Archivo.', js: ''},
                                                        {id_serv: 0, img: 'print.png', qtip: 'Imprimir Archivo.', js: ''}
                                                    ]
                                                });   
                                        }else if (record.get('sol_estado')=='1'){
                                                    //3 es cuando no esta configurado
                                                if (record.get('arch_ext')==3){
                                                    return global.permisos({
                                                        type: 'link',
                                                        id_menu: procesarftp.id_menu,
                                                        extraCss: 'ftp-procesar-link',
                                                        icons:[
                                                            {id_serv: 0, img: 'procesar_troceo.ico', qtip: 'Actualizar Datos desde Archivo', js: ''},
                                                            {id_serv: 0, img: 'exec.gif', qtip: 'Hacer PU', js: ''},
                                                            {id_serv: 0, img: 'georeferencia.png', qtip: 'Geo-Referencia Una Dirección.', js: ''},
                                                            {id_serv: 0, img: 'download.png', qtip: 'Descargar Archivo.', js: ''},
                                                            {id_serv: 0, img: 'print.png', qtip: 'Imprimir Archivo.', js: ''}
                                                        ]
                                                    }); 
                                                }else{
                                                    return global.permisos({
                                                        type: 'link',
                                                        id_menu: procesarftp.id_menu,
                                                        extraCss: 'ftp-procesar-link',
                                                        icons:[
                                                            {id_serv: 46, img: 'procesar_troceo.ico', qtip: 'Actualizar Datos desde Archivo', js: 'procesarftp.procesar('+solicitud+')'},
                                                            {id_serv: 0, img: 'exec.gif', qtip: 'Hacer PU', js: ''},
                                                            {id_serv: 0, img: 'georeferencia.png', qtip: 'Geo-Referencia Una Dirección.', js: ''},
                                                            {id_serv: 0, img: 'download.png', qtip: 'Descargar Archivo.', js: ''},
                                                            {id_serv: 0, img: 'print.png', qtip: 'Imprimir Archivo.', js: ''}
                                                        ]
                                                    }); 

                                                }
                                        }else if (record.get('sol_estado')=='2' ) {
                                             return global.permisos({
                                                type: 'link',
                                                id_menu: procesarftp.id_menu,
                                                extraCss: 'ftp-procesar-link',
                                                icons:[ 
                                                    {id_serv: 0, img: 'procesar_troceo.ico', qtip: 'Actualizar Datos desde Archivo', js: ''},
                                                    {id_serv: 50, img: 'exec.gif', qtip: 'Hacer PU', js: 'procesarftp.pu_datos('+solicitud+','+tarch_id+')'},
                                                    {id_serv: 0, img: 'georeferencia.png', qtip: 'Geo-Referencia Una Dirección.', js: ''},//48
                                                    {id_serv: 0, img: 'download.png', qtip: 'Descargar Archivo.', js: ''},//47
                                                    {id_serv: 0, img: 'print.png', qtip: 'Imprimir Archivo.', js: ''}//43
                                                ] 
                                            }); 

                                        }else if (record.get('sol_estado')=='3' ) {
                                             return global.permisos({
                                                type: 'link',
                                                id_menu: procesarftp.id_menu,
                                                extraCss: 'ftp-procesar-link',
                                                icons:[ 
                                                    {id_serv: 0, img: 'procesar_troceo.ico', qtip: 'Actualizar Datos desde Archivo', js: ''},
                                                    {id_serv: 0, img: 'exec.gif', qtip: 'Hacer PU', js: ''},
                                                    {id_serv: 48, img: 'georeferencia.png', qtip: 'Geo-Referencia Una Dirección.', js: 'procesarftp.geo_ref()'},
                                                    {id_serv: 47, img: 'download.png', qtip: 'Descargar Archivo.', js: 'procesarftp.download('+solicitud+','+tarch_id+')'},
                                                    {id_serv: 43, img: 'print.png', qtip: 'Imprimir Archivo.', js: 'procesarftp.imprimir('+solicitud+')'}
                                                ] 
                                            }); 

                                        }else{
                                            return global.permisos({
                                                type: 'link',
                                                id_menu: procesarftp.id_menu,
                                                extraCss: 'ftp-procesar-link',
                                                icons:[
                                                    {id_serv: 0, img: 'procesar_troceo.ico', qtip: 'Actualizar Datos desde Archivo', js: ''},
                                                    {id_serv: 0, img: 'exec.gif', qtip: 'Hacer PU', js: ''},
                                                    {id_serv: 48, img: 'georeferencia.png', qtip: 'Geo-Referencia Una Dirección.', js: 'procesarftp.geo_ref()'},
                                                    {id_serv: 47, img: 'download.png', qtip: 'Descargar Archivo.', js: 'procesarftp.download()'},
                                                    {id_serv: 43, img: 'print.png', qtip: 'Imprimir Archivo.', js: 'procesarftp.imprimir('+solicitud+')'}
                                                ]
                                            }); 

                                        }
                                        
                                    }// fin del render
                                },
                            ],
                            defaults:{
                                //menuDisabled: false
                                sortable: true
                            }
                        },
                        viewConfig: {
                            stripeRows: true,
                            enableTextSelection: false,
                            markDirty: false
                        },
                        trackMouseOver: true,

                        bbar: Ext.create('Ext.PagingToolbar',{
                            store:store,
                            displayInfo:true,
                            displayMsg: '{0} - {1} de {2} Registros',
                            emptyMsg: "No existe registros",
                            listeners:{ 
                                beforechange: function(obj, page, opts){
                                    var shipper     =   Ext.getCmp(procesarftp.id + '-shipper').getValue();
                                    var tipo        =   Ext.getCmp(procesarftp.id + '-tipo-archivo').getValue();
                                    var linea       =   Ext.getCmp(procesarftp.id + '-linea').getValue();
                                    var producto    =   Ext.getCmp(procesarftp.id + '-producto').getValue();
                                    var estado      =   Ext.getCmp(procesarftp.id + '-estado').getValue();
                                    var fec_ini     =   Ext.getCmp(procesarftp.id + '-desde').getRawValue();
                                    var fec_fin     =   Ext.getCmp(procesarftp.id + '-hasta').getRawValue();

                                    obj.store.proxy.extraParams = {
                                            shipper:shipper,
                                            tipo:tipo,
                                            linea:linea,
                                            producto:producto,
                                            estado:estado,
                                            fec_ini:fec_ini,
                                            fec_fin:fec_fin
                                    }

                                }
                            }
                        })
                    }
                ]
            });
            tab.add({
                id: procesarftp.id+'-tab',
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
                        global.state_item_menu(procesarftp.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        /*Ext.getCmp(procesarftp.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + procesarftp.id_menu).text,
                            icon: Ext.getCmp('menu-' + procesarftp.id_menu).icon
                        });*/
                        global.state_item_menu_config(obj,procesarftp.id_menu);
                    },
                    beforeclose: function(obj, opts){
                       
                        global.state_item_menu(procesarftp.id_menu, false);
                        
                    }
                }
            }).show();
        },
        procesar: function(sol_id){
         global.Msg({
            msg:'Seguro de Procesar Archivo',
            ico:3,
            buttons:3,
            fn:function(btn){
                if (btn=='yes'){
                    var mask = new Ext.LoadMask(Ext.getCmp(procesarftp.id+'-form'), {
                        msg:'Procesando Archivo...'
                    });
                    mask.show();
                    Ext.Ajax.request({
                        url:procesarftp.url + 'procesarftp_procesar_datos/',
                        params:{id_solicitud:sol_id},
                        success:function(response,options){
                            mask.hide();
                            var res = Ext.decode(response.responseText);
                           // console.log(res.data[0].error_sql);
                            if ( parseInt(res.data[0].error_sql) == 1 ){
                                global.Msg({
                                    msg:res.data[0].error_info,
                                    icon:1,
                                    buttons:1,
                                    fn:function(btn){
                                        procesarftp.consultar();
                                    }
                                });
                            }else{
                                mask.hide();
                                 global.Msg({
                                    msg:res.data[0].error_info,
                                    icon:0,
                                    buttons:1,
                                });
                            }
                        }
                    });
                }
            }
         });   
        },
        pu_datos: function(sol_id,tarch_id){
            procesarftp.sol_id = sol_id;

           // console.log(procesarftp.sol_id);
            if (parseInt(tarch_id) == 4){
                //console.log(tarch_id);
                procesarftp.popup_tarch_id_4();
            }else {
                 global.Msg({
                    msg:'Seguro de Realizar PU al Archivo',
                    ico:3,
                    buttons:3,
                    fn:function(btn){
                        if (btn=='yes'){
                            var mask = new Ext.LoadMask(Ext.getCmp(procesarftp.id+'-form'), {
                                msg:'Procesando Archivo...'
                            });
                            mask.show();
                            Ext.Ajax.request({
                                url:procesarftp.url + 'gestor_ftp_pu/',
                                params:{id_solicitud:sol_id,tarch_id:tarch_id},
                                success:function(response,options){
                                    mask.hide();
                                    var res = Ext.decode(response.responseText);
                                   // console.log(res.data[0].error_sql);
                                    if ( parseInt(res.data[0].error_sql) == 1 ){
                                        global.Msg({
                                            msg:res.data[0].error_info,
                                            icon:1,
                                            buttons:1,
                                            fn:function(btn){
                                                 procesarftp.consultar();
                                            }
                                        });
                                    }else{
                                        mask.hide();
                                         global.Msg({
                                            msg:res.data[0].error_info,
                                            icon:0,
                                            buttons:1,
                                        });
                                    }
                                }
                            });
                        }
                    }
                 });  
            }
        },
        geo_ref:function(sol_id){
            global.Msg({
                msg:'Seguro de Geo-Referenciar Archivo',
                ico:3,
                buttons:3,
                fn:function(btn){
                    if (btn=='yes'){
                        Ext.Ajax.request({
                            url:procesarftp.url + '/',
                            params:{id_solicitud:sol_id},
                            success:function(response,options){
                                var res = Ext.decode(response.responseText);
                                if (parseInt(res.error)==0){
                                    global.Msg({
                                        msg:res.error_info,
                                        ico:1,
                                        buttons:1,
                                    });
                                }
                            }
                        });
                    }
                }
            });
        },
        total_error:function(tarch_id,sol_id){
            win.show({vurl: procesarftp.url + 'form_show_total_error/', id_menu: procesarftp.id_menu, class: ''});
        },
        download:function(sol_id,tarch_id){
            if (parseInt(tarch_id)==4 | parseInt(tarch_id)== 5){
                window.open(procesarftp.url+'get_excel_reclamos/?&id_solicitud='+sol_id);            
            }
            
        },
        upload:function(){
             win.show({vurl: procesarftp.url + 'form_upload/', id_menu: procesarftp.id_menu, class: '' });
        },
        imprimir:function(solicitud){
            win.show({vurl: procesarftp.url + 'form_impresion/?solicitud='+solicitud, id_menu: procesarftp.id_menu, class: '' });
        },
        popup_tarch_id_4:function(){
             win.show({vurl: procesarftp.url + 'popup_tarch_id_4/', id_menu: procesarftp.id_menu, class: '' });
        },
        consultar:function(){
            var form = Ext.getCmp(procesarftp.id + '-form');

            if (form.isValid()){
                var grid = Ext.getCmp(procesarftp.id + '-grid');
                var store = grid.getStore();
                var shipper     =   Ext.getCmp(procesarftp.id + '-shipper').getValue();
                var tipo        =   Ext.getCmp(procesarftp.id + '-tipo-archivo').getValue();
                var linea       =   Ext.getCmp(procesarftp.id + '-linea').getValue();
                var producto    =   Ext.getCmp(procesarftp.id + '-producto').getValue();
                var estado      =   Ext.getCmp(procesarftp.id + '-estado').getValue();
                var fec_ini     =   Ext.getCmp(procesarftp.id + '-desde').getRawValue();
                var fec_fin     =   Ext.getCmp(procesarftp.id + '-hasta').getRawValue();

                store.load({
                    params:{
                        shipper:shipper,
                        tipo:tipo,
                        linea:linea,
                        producto:producto,
                        estado:estado,
                        fec_ini:fec_ini,
                        fec_fin:fec_fin

                    }
                });

            }
            
        }
    }
    Ext.onReady(procesarftp.init, procesarftp);
}else{
    tab.setActiveTab(procesarftp.id+'-tab');
}
</script>