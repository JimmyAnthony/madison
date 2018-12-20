<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('detalleGestion-tab')){
    var detalleGestion = {
        id: 'detalleGestion',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/callcenter/',
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'estado', type: 'string'},
                    {name: 'codigo', type: 'string'},
                    {name: 'telefono', type: 'string'},
                    {name: 'cliente', type: 'string'},
                    {name: 'guia', type: 'int'}
                ],
                proxy:{
                    type: 'ajax',
                    url: detalleGestion.url+'get_scm_call_buzon_detalle/',
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
                id: detalleGestion.id+'-form',
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
                                Ext.getCmp(detalleGestion.id+'-tab').close();
                            }
                        }
                    },
                    '-',
                    {
                        text: 'Grabar',
                        icon: '/images/icon/save.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 10, 
                                    id_btn: obj.getId(), 
                                    id_menu: detalleGestion.id_menu,
                                    fn: ['']
                                });
                            },
                            click: function(obj, e){

                            }
                        }
                    },
                    '-',
                    'Total Gestionados:',
                    {
                        xtype: 'textfield',
                        width: 70
                    },
                    'Gestiones Efectivas:',
                    {
                        xtype: 'textfield',
                        width: 70
                    },
                    'No contactados:',
                    {
                        xtype: 'textfield',
                        width: 70
                    },
                    'Por Gestionar:',
                    {
                        xtype: 'textfield',
                        width: 70
                    }
                ],
                items:[
                    {
                        region: 'west',
                        xtype: 'grid',
                        id: detalleGestion.id + '-grid',
                        width: 300,
                        border: true,
                        store: store,
                        columnLines: true,
                        columns:{
                            items:[
                                {
                                    text: '&nbsp;',
                                    dataIndex: '',
                                    width: 30
                                },
                                {
                                    text: 'Cliente',
                                    dataIndex: 'cliente',
                                    flex: 1
                                },
                                {
                                    text: 'Teléfono',
                                    dataIndex: 'telefono',
                                    width: 70
                                }
                            ],
                            defaults:{
                                menuDisabled: true
                            }
                        },
                        viewConfig: {
                            stripeRows: true,
                            enableTextSelection: false,
                            markDirty: false
                        },
                        trackMouseOver: false,
                        listeners:{
                            afterrender: function(obj){
                                var grid = Ext.getCmp(callcenter.id + '-grid');
                                var sm = grid.getSelectionModel();
                                var rec = sm.getSelection()[0];

                                obj.getStore().load({
                                    params:{
                                        vp_ges_id: rec.get('id_gestion')
                                    },
                                    callback: function(){

                                    }
                                });
                            }
                        }
                    },
                    {
                        region: 'center',
                        layout: 'border',
                        defaults:{
                            border: false
                        },
                        items:[
                            {
                                region: 'north',
                                height: 95,
                                defaults:{
                                    style:{
                                        margin: '2px',
                                        borderRadius: '0'
                                    }
                                },
                                items:[
                                    {
                                        xtype: 'fieldset',
                                        title: 'Datos del servicio',
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
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Shipper',
                                                        flex: 1,
                                                        labelWidth: 50
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Servicio',
                                                        flex: 1,
                                                        labelWidth: 70,
                                                        labelAlign: 'right'
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'panel',
                                                layout: 'hbox',
                                                items:[
                                                    {
                                                        xtype: 'datefield',
                                                        fieldLabel: 'Ciclo',
                                                        width: 150,
                                                        labelWidth: 50
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Cliente',
                                                        flex: 1,
                                                        labelWidth: 70,
                                                        labelAlign: 'right'
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'panel',
                                                layout: 'hbox',
                                                items:[
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Estado',
                                                        flex: 1,
                                                        labelWidth: 50
                                                    },
                                                    {
                                                        xtype: 'datefield',
                                                        fieldLabel: 'Fecha',
                                                        width: 150,
                                                        labelWidth: 50,
                                                        labelAlign: 'right'
                                                    },
                                                    {
                                                        xtype: 'panel',
                                                        style:{
                                                            marginLeft: '5px'
                                                        },
                                                        html: '<div id="' +detalleGestion.id + '-icon-opciones"></div>',
                                                        width: 40,
                                                        height: 22,
                                                        border: false,
                                                        listeners:{
                                                            afterrender: function(obj, e){
                                                                var div = Ext.get(detalleGestion.id + '-icon-opciones');
                                                                div.update('');
                                                                div.update(global.permisos({
                                                                    type: 'icon',
                                                                    id_menu: detalleGestion.id_menu,
                                                                    icons:[
                                                                        {id: 30, img: 'show_image.png', qtip: 'Click para ver detalle.', js: ''},
                                                                        {id: 31, img: 'history.png', qtip: 'Click para ver detalle.', js: ''}
                                                                    ]
                                                                }));
                                                            }
                                                        }
                                                    }
                                                ]
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                region: 'center',
                                layout: 'fit',
                                defaults:{
                                    border: false
                                },
                                border: false,
                                items:[
                                    {
                                        xtype: 'findlocation',
                                        id: detalleGestion.id + '-findLocation'
                                    }
                                ]
                            }
                            /*,
                            {
                                region: 'center',
                                layout: 'border',
                                defaults:{
                                    border: false
                                },
                                items:[
                                    {
                                        region: 'west',
                                        width: 300,
                                        border: true,
                                        defaults:{
                                            border: false,
                                            style:{
                                                margin: '2px'
                                            }
                                        },
                                        items:[
                                            {
                                                xtype: 'panel',
                                                layout: 'hbox',
                                                items:[
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Distrito',
                                                        flex: 1,
                                                        labelAlign: 'top'
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'panel',
                                                layout: 'hbox',
                                                items:[
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Dirección (Nombre Vía)',
                                                        flex: 1,
                                                        labelAlign: 'top'
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'panel',
                                                layout: 'hbox',
                                                items:[
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Urbanización / Conjunto Vivienda / AAHH',
                                                        flex: 1,
                                                        labelAlign: 'top'
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'panel',
                                                layout: 'hbox',
                                                defaults:{
                                                    style:{
                                                        margin: '1px'
                                                    }
                                                },
                                                items:[
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Nº Vía',
                                                        flex: 1,
                                                        labelAlign: 'top'
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Manzana',
                                                        flex: 1,
                                                        labelAlign: 'top'
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Lote',
                                                        flex: 1,
                                                        labelAlign: 'top'
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'panel',
                                                layout: 'hbox',
                                                items:[
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Referencia de la dirección',
                                                        flex: 1,
                                                        labelAlign: 'top'
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'panel',
                                                layout: 'hbox',
                                                defaults:{
                                                    style:{
                                                        margin: '1px'
                                                    }
                                                },
                                                items:[
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Cargo',
                                                        flex: 1,
                                                        labelAlign: 'top'
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Área',
                                                        flex: 1,
                                                        labelAlign: 'top'
                                                    }
                                                ]
                                            }
                                        ]
                                    },
                                    {
                                        region: 'center',
                                        layout: 'fit'
                                    }
                                ]
                            }*/
                        ]
                    }
                ]
            });

            tab.add({
                id: detalleGestion.id+'-tab',
                title: 'Detalle Gestion',
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
                        Ext.getCmp(callcenter.id+'-tab').disable();
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                    },
                    beforeclose: function(obj, opts){
                        Ext.getCmp(callcenter.id+'-tab').enable();
                        Ext.getCmp(inicio.id+'-tabContent').setActiveTab(Ext.getCmp(callcenter.id+'-tab'))
                    }
                }
            }).show();
        }
    }
    Ext.onReady(detalleGestion.init, detalleGestion);
}else{
    tab.setActiveTab(detalleGestion.id+'-tab');
}
</script>