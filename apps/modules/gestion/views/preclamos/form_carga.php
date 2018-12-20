<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('cargaReclamo-tab')){
    var cargaReclamo = {
        id: 'cargaReclamo',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/preclamos/',
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id', type: 'int'},
                    {name: 'shipper', type: 'string'},
                    {name: 'producto', type: 'string'},
                    {name: 'ciclo', type: 'string'},
                    {name: 'cli_codigo', type: 'string'},
                    {name: 'mot_reclamo', type: 'string'},
                    {name: 'det_reclamo', type: 'string'},
                    {name: 'posicion', type: 'int'},
                    {name: 'guia', type: 'int'},
                    {name: 'error_info', type: 'string'}
                ],
                proxy:{
                    type: 'ajax',
                    url: cargaReclamo.url+'get_reclamos_erroneos/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        
                    }
                }
            });

            var panel = Ext.create('Ext.form.Panel',{
                id: cargaReclamo.id+'-form',
                border:false,
                layout: 'border',
                defaults:{
                    border: false
                },
                tbar:[
                    {
                        text: 'Regresar',
                        icon: '/images/icon/get_back.png',
                        listeners:{
                            click: function(obj, e){
                                Ext.getCmp(cargaReclamo.id+'-tab').close();
                            }
                        }
                    },
                    '-',
                    {
                        text: 'Cargar Archivo',
                        id: cargaReclamo.id+'-cargar',
                        icon: '/images/icon/upload-file.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 26, 
                                    id_btn: obj.getId(), 
                                    id_menu: cargaReclamo.id_menu,
                                    fn: ['']
                                });
                            },
                            click: function(obj, e){
                                var form = Ext.getCmp(cargaReclamo.id+'-form').getForm();

                                if (form.isValid()){
                                    Ext.getCmp(cargaReclamo.id+'-tab').el.mask('Cargando…', 'x-mask-loading');

                                    var vp_linea = Ext.getCmp(cargaReclamo.id+'-linea').getValue();

                                    form.submit({
                                        url: cargaReclamo.url + 'upload_file/',
                                        params:{
                                            vp_linea: vp_linea
                                        },
                                        success: function( fp, o ){
                                            var res = o.result.output;
                                            Ext.getCmp(cargaReclamo.id+'-tab').el.unmask();
                                            // console.log(o.result);
                                            cargaReclamo.aleatorio = res[2];
                                            if (parseInt(res[0]) == 0){
                                                global.Msg({
                                                    msg: 'Se cargó archivo exitósamente, ya puede procesar.',
                                                    icon: 1,
                                                    buttons: 1,
                                                    fn: function(btn){
                                                        Ext.getCmp(cargaReclamo.id+'-btn_procesar').enable();
                                                        Ext.getCmp(cargaReclamo.id+'-linea').disable();
                                                    }
                                                });
                                            } else{
                                                Ext.getCmp(cargaReclamo.id+'-btn_procesar').disable();
                                                global.Msg({
                                                    msg: 'Se encontraron registros erroneos!',
                                                    icon: 0,
                                                    buttons: 1,
                                                    fn: function(btn){
                                                        cargaReclamo.load_grid();
                                                    }
                                                });
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    },
                    {
                        text: 'Procesar',
                        id: cargaReclamo.id+'-btn_procesar',
                        icon: '/images/icon/gear.png',
                        disabled: true,
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 27, 
                                    id_btn: obj.getId(), 
                                    id_menu: cargaReclamo.id_menu,
                                    fn: ['']
                                });
                            },
                            click: function(obj, e){
                                cargaReclamo.save();
                            }
                        }
                    }
                ],
                items:[
                    {
                        region: 'north',
                        height: 55,
                        defaults:{
                            style:{
                                margin: '2px 2px 2px 2px'
                            },
                            border: false
                        },
                        items:[
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                defaults:{
                                    labelWidth: 60
                                },
                                items:[
                                    {
                                        xtype: 'combo',
                                        id: cargaReclamo.id+'-linea',
                                        fieldLabel: 'Línea',
                                        allowBlank: false,
                                        store: Ext.create('Ext.data.Store',{
                                            fields:[
                                                {name: 'id', type: 'int'},
                                                {name: 'nombre', type: 'string'}
                                            ],
                                            proxy:{
                                                type: 'ajax',
                                                url: cargaReclamo.url + 'get_usr_sis_linea_negocio/',
                                                reader:{
                                                    type: 'json',
                                                    rootProperty: 'data'
                                                }
                                            }
                                        }),
                                        queryMode: 'local',
                                        triggerAction: 'all',
                                        valueField: 'id',
                                        displayField: 'nombre',
                                        emptyText: '[ Todos ]',
                                        listeners:{
                                            afterrender: function(obj, e){
                                                obj.getStore().load({
                                                    params:{},
                                                    callback: function(){

                                                    }
                                                });
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'combo',
                                        id: cargaReclamo.id+'-tipo_reclamo',
                                        fieldLabel: 'Tipo reclamo',
                                        allowBlank: false,
                                        labelWidth: 80,
                                        labelAlign: 'right',
                                        store: Ext.create('Ext.data.Store',{
                                            fields:[
                                                {name: 'descripcion', type: 'string'},
                                                {name: 'id_elemento', type: 'int'},
                                                {name: 'des_corto', type: 'string'}
                                            ],
                                            proxy:{
                                                type: 'ajax',
                                                url: cargaReclamo.url + 'get_scm_tabla_detalle/',
                                                reader:{
                                                    type: 'json',
                                                    rootProperty: 'data'
                                                }
                                            }
                                        }),
                                        queryMode: 'local',
                                        triggerAction: 'all',
                                        valueField: 'id_elemento',
                                        displayField: 'descripcion',
                                        emptyText: '[ Seleccione ]',
                                        listeners:{
                                            afterrender: function(obj, e){
                                                obj.getStore().load({
                                                    params:{
                                                        vp_tab_id: 'TAU',
                                                        vp_shipper: 0
                                                    },
                                                    callback: function(){

                                                    }
                                                });
                                            }
                                        }
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                defaults:{
                                    labelWidth: 60
                                },
                                items:[
                                    {
                                        xtype: 'filefield',
                                        id: cargaReclamo.id+'-file',
                                        name: cargaReclamo.id+'-file',
                                        fieldLabel: 'Archivo',
                                        allowBlank: false,
                                        emptyText: 'Seleccione archivo',
                                        buttonText: '',
                                        buttonConfig:{
                                            icon: '/images/icon/directory.png'
                                        },
                                        width: 500,
                                        listeners:{
                                             change:function(obj,e){                                    
                                                var elem = obj.value.split('.');
                                                var ext = elem[elem.length-1];                                  
                                                if(ext == 'xls' || ext == 'xlsx'){      

                                                }else{                                          
                                                    global.Msg({
                                                        msg:'La extencion: '+ext+' del archivo no es valido',
                                                        icon: 0,
                                                        buttons: 1
                                                    });
                                                }
                                            }
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        region: 'center',
                        layout: 'fit',
                        items:[
                            {
                                xtype: 'grid',
                                id: cargaReclamo.id+'-grid',
                                store: store,
                                columnLines: true,
                                columns:{
                                    items:[
                                        {
                                            text: 'Shipper',
                                            dataIndex: 'shipper',
                                            width: 70
                                        },
                                        {
                                            text: 'Producto',
                                            dataIndex: 'producto',
                                            width: 70
                                        },
                                        {
                                            text: 'Ciclo',
                                            dataIndex: 'ciclo',
                                            width: 70
                                        },
                                        {
                                            text: 'Cod. Cliente',
                                            dataIndex: 'cli_codigo',
                                            width: 150
                                        },
                                        {
                                            text: 'Cod. Motivo',
                                            dataIndex: 'mot_reclamo',
                                            width: 80
                                        },
                                        {
                                            text: 'Motivo',
                                            dataIndex: 'det_reclamo',
                                            width: 200
                                        },
                                        {
                                            text: 'Posición',
                                            dataIndex: 'posicion',
                                            width: 70,
                                            align: 'right'
                                        },
                                        {
                                            text: 'Mensaje Error',
                                            dataIndex: 'error_info',
                                            flex: 1
                                        }
                                    ],
                                    defaults:{
                                        menuDisabled: true,
                                        sortable: false
                                    }
                                },
                                listeners:{
                                    afterrender: function(obj, e){

                                    }
                                }
                            }
                        ]
                    }
                ]
            });

            tab.add({
                id: cargaReclamo.id+'-tab',
                title: 'Carga de reclamos',
                border: false,
                autoScroll: true,
                closable: false,
                layout:{
                    type: 'fit'
                },
                items:[
                    panel
                ],
                listeners:{
                    beforerender: function(obj, opts){
                        Ext.getCmp(preclamos.id+'-tab').disable();
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                    },
                    beforeclose: function(obj, opts){
                        Ext.getCmp(preclamos.id+'-tab').enable();
                        Ext.getCmp(inicio.id+'-tabContent').setActiveTab(Ext.getCmp(preclamos.id+'-tab'))
                    }
                }
            }).show();
        },
        load_grid: function(){
            var grid = Ext.getCmp(cargaReclamo.id+'-grid');
            var store = grid.getStore();
            store.load({
                params:{
                    aleatorio: cargaReclamo.aleatorio
                },
                callback: function(){

                }
            });
        },
        save: function(){
            var vp_linea = Ext.getCmp(cargaReclamo.id+'-linea').getValue();
            var vp_tipo = Ext.getCmp(cargaReclamo.id+'-tipo_reclamo').getValue();
            if (vp_tipo != null && vp_tipo != 0 ){
                global.Msg({
                    msg: '¿Está seguro de procesar?',
                    icon: 3,
                    buttons: 3,
                    fn: function(btn){
                        Ext.getCmp(cargaReclamo.id+'-tab').el.mask('Cargando…', 'x-mask-loading');
                        Ext.Ajax.request({
                            url: cargaReclamo.url + 'set_reclamo_masivo/',
                            params:{
                                vp_tipo: vp_tipo,
                                vp_linea: vp_linea,
                                aleatorio: cargaReclamo.aleatorio
                            },
                            success: function(response, options){
                                var res = Ext.JSON.decode(response.responseText);
                                Ext.getCmp(cargaReclamo.id+'-tab').el.unmask();
                                if (parseInt(res.error) == 0){
                                    global.Msg({
                                        msg: 'Se procesó correctamente!',
                                        icon: 1,
                                        buttons: 1,
                                        fn: function(btn){
                                            cargaReclamo.clear();
                                        }
                                    });
                                }else{
                                    global.Msg({
                                        msg: 'Error al procesar!',
                                        icon: 0,
                                        buttons: 1,
                                        fn: function(btn){

                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }else{
                global.Msg({
                    msg: 'Debe de selecionar el tipo de reclamo!',
                    icon: 2,
                    buttons: 1,
                    fn: function(btn){

                    }
                });
            }
        },
        clear: function(){
            var grid = Ext.getCmp(cargaReclamo.id+'-grid');
            var store = grid.getStore();
            store.removeAll();
            Ext.getCmp(cargaReclamo.id+'-form').getForm().reset();
            Ext.getCmp(cargaReclamo.id+'-btn_procesar').disable();
        }
    }
    Ext.onReady(cargaReclamo.init, cargaReclamo);
}else{
    tab.setActiveTab(cargaReclamo.id+'-tab');
}
</script>