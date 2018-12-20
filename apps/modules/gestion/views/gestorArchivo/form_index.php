<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('gestorArchivo-tab')){
    var gestorArchivo = {
        id: 'gestorArchivo',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/gestorArchivo/',
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'linea', type: 'string'}
                ],
                proxy:{
                    type: 'ajax',
                    url: gestorArchivo.url+'/',
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
                id: gestorArchivo.id+'-form',
                border:false,
                layout: 'border',
                defaults:{
                    border: false
                },
                tbar:[
                    'Shipper:',
                    {
                        xtype: 'combo',
                        id: gestorArchivo.id + '-shipper',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'shi_codigo', type: 'int'},
                                {name: 'shi_nombre', type: 'string'},
                                {name: 'shi_id', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: gestorArchivo.url + 'get_usr_sis_shipper/',
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
                        width: 150,
                        forceSelection: true,
                        emptyText: '[ Seleccione ]',
                        listeners:{
                            afterrender: function(obj, e){
                                obj.getStore().load({
                                    params:{
                                        vp_linea: 0,
                                        all: false
                                    },
                                    callback: function(){

                                    }
                                });
                            }
                        }
                    },
                    'Tipo Archivo:',
                    {
                        xtype: 'combo',
                        id: gestorArchivo.id + '-tipo-archivo',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'descripcion', type: 'string'},
                                {name: 'id_elemento', type: 'int'},
                                {name: 'des_corto', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: gestorArchivo.url + 'get_scm_tabla_detalle/',
                                reader:{
                                    type: 'json',
                                    root: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'id_elemento',
                        displayField: 'descripcion',
                        listConfig:{
                            minWidth: 150
                        },
                        width: 100,
                        forceSelection: true,
                        emptyText: '[ Seleccione ]',
                        listeners:{
                            afterrender: function(obj, e){
                                obj.getStore().load({
                                    params:{
                                        vp_tab_id: 'TAD',
                                        vp_shipper: 0
                                    },
                                    callback: function(){
                                        obj.setValue(0);
                                    }
                                });
                            }
                        }
                    },
                    'Línea:',
                    {
                        xtype: 'combo',
                        id: gestorArchivo.id + '-linea',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'id', type: 'int'},
                                {name: 'nombre', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: gestorArchivo.url + 'get_usr_sis_linea_negocio/',
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
                            minWidth: 150
                        },
                        width: 100,
                        forceSelection: true,
                        emptyText: '[ Seleccione ]',
                        listeners:{
                            afterrender: function(obj, e){
                                obj.getStore().load({
                                    params:{
                                        vp_linea: 0
                                    },
                                    callback: function(){
                                        obj.setValue(0);
                                    }
                                });
                            },
                            select: function(combo, records, opts){
                                var cmb_producto = Ext.getCmp(gestorArchivo.id + '-producto');
                                cmb_producto.getStore().load({
                                    params:{
                                        vp_shipper: Ext.getCmp(gestorArchivo.id + '-shipper').getValue(),
                                        vp_linea: records.get('id')
                                    },
                                    callback: function(){
                                        cmb_producto.clearValue();
                                    }
                                });
                            }
                        }
                    },
                    'Producto:',
                    {
                        xtype: 'combo',
                        id: gestorArchivo.id + '-producto',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'id_pro', type: 'int'},
                                {name: 'pro_nombre', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: gestorArchivo.url + 'get_usr_sis_productos/',
                                reader:{
                                    type: 'json',
                                    root: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'id_pro',
                        displayField: 'pro_nombre',
                        listConfig:{
                            minWidth: 150
                        },
                        width: 250,
                        forceSelection: true,
                        emptyText: '[ Todos ]',
                        listeners:{
                            afterrender: function(obj, e){
                                /*obj.getStore().load({
                                    params:{
                                        vp_linea: 0
                                    },
                                    callback: function(){

                                    }
                                });*/
                            }
                        }
                    },
                    'Desde:',
                    {
                        xtype: 'datefield',
                        id: gestorArchivo.id + '-desde',
                        width: 90,
                        value: new Date()
                    },
                    'Hasta:',
                    {
                        xtype: 'datefield',
                        id: gestorArchivo.id + '-hasta',
                        width: 90,
                        value: new Date()
                    },
                    '-',
                    {
                        text: 'Buscar',
                        id: gestorArchivo.id+'-btn_buscar',
                        icon: '/images/icon/search.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 37, 
                                    id_btn: obj.getId(), 
                                    id_menu: gestorArchivo.id_menu,
                                    fn: ['']
                                });
                            },
                            click: function(obj, e){
                                gestorArchivo.load_carousel();
                            }
                        }
                    },
                    {
                        text: 'Cargar',
                        icon: '/images/icon/upload.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 39, 
                                    id_btn: obj.getId(), 
                                    id_menu: gestorArchivo.id_menu,
                                    fn: ['']
                                });
                            },
                            click: function(obj, e){
                                win.show({vurl: gestorArchivo.url + 'form_carga_ftp/', id_menu: gestorArchivo.id_menu, class: ''});
                            }
                        }
                    }
                ],
                items:[
                    {
                        region: 'north',
                        height: 150,
                        border: false,
                        layout: 'fit',
                        items:[
                            {
                                xtype: 'ueCarousel',
                                id: gestorArchivo.id + '-carousel'
                            }
                        ]
                    },
                    {
                        region: 'center',
                        layout: 'border',
                        defaults:{
                            border: false
                        },
                        items:[
                            {
                                region: 'west',
                                width: 390,
                                defaults:{
                                    style:{
                                        margin: '10px 20px 0 20px'
                                    }
                                },
                                items:[
                                    {
                                        xtype: 'uePanelHtml',
                                        id: gestorArchivo.id + '-panelHtml-01',
                                        title: 'Propiedades del Archivo',
                                        width: 350,
                                        // height: 200,
                                        listeners:{
                                            afterrender: function(obj, e){
                                                obj.setHtml(
                                                    new Ext.XTemplate(
                                                        '<p>',
                                                            '<label style="width: 100px;">Archivo para:</label>',
                                                            '<span></span>',
                                                        '</p>',
                                                        '<p>',
                                                            '<label style="width: 100px;">Subido el:</label>',
                                                            '<span></span>',
                                                        '</p>',
                                                        '<p>',
                                                            '<label style="width: 100px;">Desde:</label>',
                                                            '<span></span>',
                                                        '</p>',
                                                        '<p>',
                                                            '<label style="width: 100px;">Shipper:</label>',
                                                            '<span></span>',
                                                        '</p>',
                                                        '<p>',
                                                            '<label style="width: 100px;">Producto:</label>',
                                                            '<span></span>',
                                                        '</p>',
                                                        '<p>',
                                                            '<label style="width: 100px;">Ciclo:</label>',
                                                            '<span></span>',
                                                            '<label>Nº O/S:</label>',
                                                            '<span></span>',
                                                        '</p>'
                                                    ),{}
                                                );
                                            }
                                        }
                                    }
                                ]
                            },
                            {
                                region: 'center',
                                defaults:{
                                    style:{
                                        margin: '10px 20px 0 10px'
                                    }
                                },
                                items:[
                                    {
                                        xtype: 'uePanelHtml',
                                        id: gestorArchivo.id + '-panelHtml-02',
                                        title: 'Propiedades del Archivo',
                                        width: 720,
                                        // height: 200,
                                        listeners:{
                                            afterrender: function(obj, e){
                                                obj.setHtml(
                                                    new Ext.XTemplate(
                                                        '<div style="width: 300px;">',
                                                            '<p>',
                                                                '<label style="width: 150px;">Total Registros:</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                            '<p>',
                                                                '<label style="width: 150px;">Total Piezas:</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                            '<p>',
                                                                '<label style="width: 150px;">Total Físico(Sobres):</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                            '<p>',
                                                                '<label style="width: 150px;">Total Piezas Física:</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                            '<p>',
                                                                '<label style="width: 150px;">Discrepancia Sobres:</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                            '<p>',
                                                                '<label style="width: 150px;">Discrepancia Piezas:</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                        '</div>',
                                                        '<div class="separation" style="height: 150px;"></div>',
                                                        '<div style="width: 300px;">',
                                                            '<p>',
                                                                '<label style="width: 150px;">Total Registros:</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                            '<p>',
                                                                '<label style="width: 150px;">Total Piezas:</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                            '<p>',
                                                                '<label style="width: 150px;">Total Físico(Sobres):</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                            '<p>',
                                                                '<label style="width: 150px;">Total Piezas Física:</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                            '<p>',
                                                                '<label style="width: 150px;">Discrepancia Sobres:</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                            '<p>',
                                                                '<label style="width: 150px;">Discrepancia Piezas:</label>',
                                                                '<span></span>',
                                                            '</p>',
                                                        '</div>'
                                                    ),{}
                                                );
                                            }
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ]
            });

            tab.add({
                id: gestorArchivo.id+'-tab',
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
                        global.state_item_menu(gestorArchivo.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        /*Ext.getCmp(gestorArchivo.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + gestorArchivo.id_menu).text,
                            icon: Ext.getCmp('menu-' + gestorArchivo.id_menu).icon
                        });*/
                        global.state_item_menu_config(obj,gestorArchivo.id_menu);
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(gestorArchivo.id_menu, false);
                    }
                }
            }).show();
        },
        load_carousel: function(){
            var carousel = Ext.getCmp(gestorArchivo.id + '-carousel');
            // console.log(carousel);
            var vp_shipper = Ext.getCmp(gestorArchivo.id + '-shipper').getValue();
            var vp_tipfile = Ext.getCmp(gestorArchivo.id + '-tipo-archivo').getValue();
            vp_tipfile = vp_tipfile == null || vp_tipfile == undefined ? 0 : vp_tipfile;
            var vp_linea = Ext.getCmp(gestorArchivo.id + '-linea').getValue();
            vp_linea = vp_linea == null || vp_linea == undefined ? 0 : vp_linea;
            var vp_producto = Ext.getCmp(gestorArchivo.id + '-producto').getValue();
            vp_producto = vp_producto == null || vp_producto == undefined ? 0 : vp_producto;
            var vp_desde = Ext.getCmp(gestorArchivo.id + '-desde').getRawValue();
            var vp_hasta = Ext.getCmp(gestorArchivo.id + '-hasta').getRawValue();

            Ext.getCmp(gestorArchivo.id+'-tab').el.mask('Cargando…', 'x-mask-loading');
            Ext.Ajax.request({
                url: gestorArchivo.url + 'get_scm_gestor_ftp_panel/',
                params:{
                    vp_shipper: vp_shipper,
                    vp_tipfile: vp_tipfile,
                    vp_linea: vp_linea,
                    vp_producto: vp_producto,
                    vp_desde: vp_desde,
                    vp_hasta: vp_hasta
                },
                success: function(response, options){
                    Ext.getCmp(gestorArchivo.id+'-tab').el.unmask();
                    var res = Ext.JSON.decode(response.responseText);
                    console.log(res);
                    var dataCarousel = [];
                    Ext.Object.each(res.data, function(index, value){
                        dataCarousel.push({
                            id: value.id_solicitud,
                            tipo: '', 
                            nombre_file: value.file,
                            remove:value.remove,
                            id_solicitud:value.id_solicitud
                        });
                    });
                    carousel.setDataSlide({
                        items: dataCarousel
                    });
                    carousel.reconfigure();
                }
            });
        },
        remove_sol:function(sol_id){
            
        }
    }
    Ext.onReady(gestorArchivo.init, gestorArchivo);
}else{
    tab.setActiveTab(gestorArchivo.id+'-tab');
}
</script>