<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('gCargaFtp-tab')){
    var gCargaFtp = {
        id: 'gCargaFtp',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/gestorArchivo/',
        tipo: 1,
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id', type: 'int'}
                ],
                proxy:{
                    type: 'ajax',
                    url: gCargaFtp.url+'get_reclamos_erroneos/',
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
                id: gCargaFtp.id+'-form',
                border:false,
                defaults:{
                    border: false,
                    style: {
                        margin: '10px'
                    }
                },
                items:[
                    {
                        xtype: 'panel',
                        layout: 'hbox',
                        items:[
                            {
                                xtype: 'radiogroup',
                                id: gCargaFtp.id+'-rbtn-group',
                                fieldLabel: 'Tipo',
                                columns: 4,
                                vertical: true,
                                labelWidth: 35,
                                items:[
                                    {boxLabel: 'Datos para PU', name: gCargaFtp.id+'-rbtn', inputValue: '1', width: 110, checked: true},
                                    {boxLabel: 'Para coordinaciones', name: gCargaFtp.id+'-rbtn', inputValue: '2', width: 140},
                                    {boxLabel: 'Recolección', name: gCargaFtp.id+'-rbtn', inputValue: '3', width: 95},
                                    {boxLabel: 'Reclamos/Auditoria', name: gCargaFtp.id+'-rbtn', inputValue: '4', width: 145}
                                ],
                                listeners:{
                                    change: function(obj, newValue, oldValue, eOpts){
                                        var op = parseInt(newValue[gCargaFtp.id+'-rbtn']);
                                        gCargaFtp.tipo = op;
                                        if (op == 1){
                                            Ext.getCmp(gCargaFtp.id+'-fpanel').enable();
                                        }else{
                                            Ext.getCmp(gCargaFtp.id+'-fpanel').disable();
                                        }
                                    }
                                }
                            }
                        ]
                    },
                    {
                        xtype: 'panel',
                        layout: 'hbox',
                        items:[
                                {
                                    xtype: 'combo',
                                    id: gCargaFtp.id + '-linea',
                                    fieldLabel: 'Línea',
                                    labelWidth: 70,
                                    allowBlank: false,
                                    store: Ext.create('Ext.data.Store',{
                                        fields:[
                                            {name: 'id', type: 'int'},
                                            {name: 'nombre', type: 'string'}
                                        ],
                                        proxy:{
                                            type: 'ajax',
                                            url: gCargaFtp.url + 'get_usr_sis_linea_negocio/',
                                            reader:{
                                                type: 'json',
                                                root: 'data'
                                            }
                                        }
                                    }),
                                    queryMode: 'local',
                                    triggerAction: 'all',
                                    valueField: 'id',
                                    displayField: 'nombre',
                                    listConfig:{
                                        minWidth: 350
                                    },
                                    // width: 250,
                                    flex: 1,
                                    forceSelection: true,
                                    emptyText: '[ Seleccione ]',
                                    listeners:{
                                        afterrender: function(obj, e){
                                            obj.getStore().load({
                                                params:{
                                                    //vp_linea: 0
                                                },
                                                callback: function(){
                                                    Ext.getCmp(gCargaFtp.id + '-shipper').getStore().load({
                                                        params:{vp_linea:obj.getValue()}
                                                    });
                                                }
                                            });
                                        },
                                        select: function(combo, records, opts){
                                            /*var cmb_producto = Ext.getCmp(gCargaFtp.id + '-producto');
                                            cmb_producto.getStore().load({
                                                params:{
                                                    vp_shipper: Ext.getCmp(gCargaFtp.id + '-shipper').getValue(),
                                                    vp_linea: records.get('id')
                                                },
                                                callback: function(){
                                                    cmb_producto.clearValue();
                                                }
                                            });*/
                                        }
                                    }
                                }
                        ]
                    },
                    {
                        xtype: 'panel',
                        layout: 'hbox',
                        items:[
                                {
                                    xtype: 'combo',
                                    id: gCargaFtp.id + '-shipper',
                                    fieldLabel: 'Shipper',
                                    labelWidth: 70,
                                    allowBlank: false,
                                    store: Ext.create('Ext.data.Store',{
                                        fields:[
                                            {name: 'shi_codigo', type: 'int'},
                                            {name: 'shi_nombre', type: 'string'},
                                            {name: 'shi_id', type: 'string'}
                                        ],
                                        proxy:{
                                            type: 'ajax',
                                            url: gCargaFtp.url + 'get_usr_sis_shipper/',
                                            reader:{
                                                type: 'json',
                                                root: 'data'
                                            }
                                        }
                                    }),
                                    queryMode: 'local',
                                    triggerAction: 'all',
                                    valueField: 'shi_codigo',
                                    displayField: 'shi_nombre',
                                    listConfig:{
                                        minWidth: 350
                                    },
                                    // width: 250,
                                    flex: 1,
                                    forceSelection: true,
                                    emptyText: '[ Seleccione ]',
                                    listeners:{
                                        select: function(combo,records,opts){
                                            vp_linea = Ext.getCmp(gCargaFtp.id + '-linea').getValue();
                                            vp_shipper = records.get('shi_codigo');
                                            //console.log(vp_shipper);
                                            Ext.getCmp(gCargaFtp.id+'-producto').getStore().load({
                                                params:{vp_shipper:vp_shipper,vp_linea:vp_linea}
                                            });
                                        }
                                    }
                                }
                        ]
                    },
                    {
                        xtype: 'fieldset',
                        id: gCargaFtp.id+'-fpanel',
                        border: true,
                        flex: 1,
                        defaults:{
                            style:{
                                margin: '2px'
                            },
                            border: false
                        },
                        items:[
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                items:[
                                    {
                                        xtype: 'combo',
                                        id: gCargaFtp.id+'-producto',
                                        fieldLabel: 'Producto',
                                        labelWidth: 115,
                                        store: Ext.create('Ext.data.Store',{
                                            fields:[
                                                {name: 'id_orden', type: 'int'},
                                                {name: 'pro_nombre', type: 'string'}
                                            ],
                                            proxy:{
                                                type: 'ajax',
                                                url: gCargaFtp.url + 'get_usr_sis_productos/',
                                                reader:{
                                                    type: 'json',
                                                    root: 'data'
                                                }
                                            }
                                        }),
                                        queryMode: 'local',
                                        triggerAction: 'all',
                                        valueField: 'id_orden',
                                        displayField: 'pro_nombre',
                                        listConfig:{
                                            minWidth: 350
                                        },
                                        flex: 1,
                                        forceSelection: true,
                                        emptyText: '[ Seleccione ]',
                                        listeners:{
                                            afterrender: function(obj, e){

                                            }
                                        }
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                defaults:{
                                    border: false
                                },
                                items:[
                                    {
                                        xtype: 'datefield',
                                        id: gCargaFtp.id+'-ciclo',
                                        fieldLabel: 'Ciclo',
                                        width: 220,
                                        labelWidth: 115,
                                        value: new Date()
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                items:[
                                    {
                                        xtype: 'textarea',
                                        id: gCargaFtp.id+'-des_servicio',
                                        fieldLabel: 'Descripción servicio',
                                        flex: true,
                                        labelWidth: 115
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                defaults:{
                                    border: false
                                },
                                items:[
                                    {
                                        xtype: 'textfield',
                                        id: gCargaFtp.id+'-guia_rec',
                                        fieldLabel: 'Guía recepción',
                                        width: 220,
                                        labelWidth: 115
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                layout: {
                                    type: 'table',
                                    columns: 3
                                },
                                defaults:{
                                    border: false
                                },
                                items:[
                                    {
                                        xtype: 'panel',
                                        width: 90
                                    },
                                    {
                                        xtype: 'panel',
                                        width: 100,
                                        items:[
                                            {
                                                xtype: 'label',
                                                text: 'Matriz'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'panel',
                                        width: 100,
                                        items:[
                                            {
                                                xtype: 'label',
                                                text: 'Provincia'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'panel',
                                        width: 120,
                                        items:[
                                            {
                                                xtype: 'label',
                                                text: 'Fecha Inicio:'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'panel',
                                        width: 100,
                                        items:[
                                            {
                                                xtype: 'datefield',
                                                id: gCargaFtp.id+'-finicioM',
                                                hideLabel: true,
                                                width: 90,
                                                value: new Date(new Date().getTime() + (1 * 24 * 3600 * 1000)),
                                                minValue: new Date(new Date().getTime() + (1 * 24 * 3600 * 1000)),

                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'panel',
                                        width: 100,
                                        items:[
                                            {
                                                xtype: 'datefield',
                                                id: gCargaFtp.id+'-finicioP',
                                                hideLabel: true,
                                                width: 90,
                                                //value: new Date()
                                                value: new Date(new Date().getTime() + (1 * 24 * 3600 * 1000)),
                                                minValue: new Date(new Date().getTime() + (1 * 24 * 3600 * 1000)),
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'panel',
                                        width: 90,
                                        items:[
                                            {
                                                xtype: 'label',
                                                text: 'Fecha Cierre:'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'panel',
                                        width: 100,
                                        items:[
                                            {
                                                xtype: 'datefield',
                                                id: gCargaFtp.id+'-fcierreM',
                                                hideLabel: true,
                                                width: 90,
                                                value: new Date()
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'panel',
                                        width: 100,
                                        items:[
                                            {
                                                xtype: 'datefield',
                                                id: gCargaFtp.id+'-fcierreP',
                                                hideLabel: true,
                                                width: 90,
                                                value: new Date()
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'panel',
                        layout: 'hbox',
                        items:[
                            {
                                xtype: 'textarea',
                                id: gCargaFtp.id+'-instrucciones',
                                fieldLabel: 'Instrucciones',
                                flex: true,
                                allowBlank: false,
                                labelWidth: 80
                            }
                        ]
                    },
                    {
                        xtype: 'panel',
                        layout: 'hbox',
                        items:[
                            {
                                xtype: 'filefield',
                                id: gCargaFtp.id+'-file',
                                name: gCargaFtp.id+'-file',
                                fieldLabel: 'Archivo',
                                allowBlank: false,
                                emptyText: 'Seleccione archivo',
                                buttonText: '',
                                buttonConfig:{
                                    icon: '/images/icon/directory.png'
                                },
                                flex: 1,
                                labelWidth: 80,
                                listeners:{
                                     change:function(obj,e){                                    
                                        /*var elem = obj.value.split('.');
                                        var ext = elem[elem.length-1];                                  
                                        if(ext == 'xls' || ext == 'xlsx'){      

                                        }else{                                          
                                            global.Msg({
                                                msg:'La extencion: '+ext+' del archivo no es valido',
                                                icon: 0,
                                                buttons: 1
                                            });
                                        }*/
                                    }
                                }
                            }
                        ]
                    }
                ]
            });

            var panelX = Ext.create('Ext.form.Panel',{
                layout: 'fit',
                border: false,
                bodyStyle: 'background: transparent',
                items:[
                    {
                        xtype: 'uePanel',
                        title: 'Cargar Archivo al FTP',
                        logo: 'upload',
                        legend: 'Seleccione y/o ingrese los datos requeridos.',
                        bg: '#991919',
                        items: [
                            panel
                        ]
                    }
                ]
            });

            Ext.create('Ext.window.Window',{
                id: gCargaFtp.id + '-win',
                height: 515,
                width: 600,
                layout: 'fit',
                modal: true,
                resizable: false,
                items: panelX,
                frame: true,
                closable: false,
                header: false,
                dockedItems:[
                    {
                        xtype: 'toolbar',
                        dock: 'bottom',
                        ui: 'footer',
                        alignTarget: 'center',
                        layout:{
                            pack: 'center'
                        },
                        items: [
                            {
                                text: 'Grabar',
                                id: gCargaFtp.id + '-btn-grabar',
                                icon: '/images/icon/save.png',
                                listeners:{
                                    click: function(obj, e){
                                        var form = Ext.getCmp(gCargaFtp.id+'-form').getForm();
                                        if (form.isValid()){

                                            var vp_tip_file = gCargaFtp.tipo;
                                            var vp_shipper = Ext.getCmp(gCargaFtp.id + '-shipper').getValue();
                                            vp_shipper = ( vp_shipper == null || vp_shipper == '' ) ? 0 : vp_shipper;
                                            var vp_id_line = Ext.getCmp(gCargaFtp.id + '-linea').getValue();
                                            vp_id_line = ( vp_id_line == null || vp_id_line == '' ) ? 0 : vp_id_line;
                                            var vp_id_orden = Ext.getCmp(gCargaFtp.id+'-producto').getValue();
                                            vp_id_orden = ( vp_id_orden == null || vp_id_orden == '' ) ? 0 : vp_id_orden;
                                            var vp_ciclo = Ext.getCmp(gCargaFtp.id+'-ciclo').getRawValue();
                                            var vp_guirec = Ext.getCmp(gCargaFtp.id+'-guia_rec').getValue();
                                            var vp_ord_descri = Ext.getCmp(gCargaFtp.id+'-des_servicio').getValue();
                                            var vp_apunts = Ext.getCmp(gCargaFtp.id+'-instrucciones').getValue();
                                            var vp_fec_ini_mat = Ext.getCmp(gCargaFtp.id+'-finicioM').getRawValue();
                                            var vp_fec_fin_mat = Ext.getCmp(gCargaFtp.id+'-fcierreM').getRawValue();
                                            var vp_fec_ini_prov = Ext.getCmp(gCargaFtp.id+'-finicioP').getRawValue();
                                            var vp_fec_fin_prov = Ext.getCmp(gCargaFtp.id+'-fcierreP').getRawValue();

                                            form.submit({
                                                url: gCargaFtp.url + 'set_file_ftp/',
                                                params:{
                                                    vp_tip_file: vp_tip_file,
                                                    vp_shipper: vp_shipper,
                                                    vp_id_line: vp_id_line,
                                                    vp_id_orden: vp_id_orden,
                                                    vp_ciclo: vp_ciclo,
                                                    vp_guirec: vp_guirec,
                                                    vp_ord_descri: vp_ord_descri,
                                                    vp_apunts: vp_apunts,
                                                    vp_fec_ini_mat: vp_fec_ini_mat,
                                                    vp_fec_fin_mat: vp_fec_fin_mat,
                                                    vp_fec_ini_prov: vp_fec_ini_prov,
                                                    vp_fec_fin_prov: vp_fec_fin_prov
                                                },
                                                success: function(fp, o){
                                                    var res = o.result;
                                                    console.log(res.debug);
                                                    if (res.error == 1){
                                                        global.Msg({
                                                            msg: 'Se cargó archivo exitósamente.',
                                                            icon: 1,
                                                            buttons: 1,
                                                            fn: function(btn){
                                                                Ext.getCmp(gCargaFtp.id + '-win').close();
                                                                // Ext.getCmp(cargaReclamo.id+'-btn_procesar').enable();
                                                                // Ext.getCmp(cargaReclamo.id+'-linea').disable();
                                                            }
                                                        });
                                                    }else{
                                                        global.Msg({
                                                            msg: res.error_info,
                                                            icon: 0,
                                                            buttons: 1,
                                                            fn: function(btn){
                                                                
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
                                text: 'Cancelar',
                                id: gCargaFtp.id + '-btn-cancelar',
                                icon: '/images/icon/cancel.png',
                                listeners:{
                                    click: function(obj, e){
                                        Ext.getCmp(gCargaFtp.id + '-win').close();
                                    }
                                }
                            }
                        ]
                    }
                ]
            }).show().center();
        },
        state_option: function(){

        }
    }
    Ext.onReady(gCargaFtp.init, gCargaFtp);
}else{
    tab.setActiveTab(gCargaFtp.id+'-tab');
}
</script>