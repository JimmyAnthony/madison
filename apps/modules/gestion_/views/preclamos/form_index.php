<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('preclamos-tab')){
    var preclamos = {
        id: 'preclamos',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/preclamos/',
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'agencia', type: 'string'},
                    {name: 'linea', type: 'string'},
                    {name: 'fecha', type: 'string'},
                    {name: 'tipo_reclamo', type: 'string'},
                    {name: 'tot_reclamo', type: 'int'},
                    {name: 'tot_sin_imp', type: 'int'},
                    {name: 'tot_sin_ld', type: 'int'},
                    {name: 'pje_sin_ld', type: 'float'},
                    {name: 'tot_digital', type: 'int'},
                    {name: 'pje_digital', type: 'float'},
                    {name: 'tot_sin_digital', type: 'int'},
                    {name: 'pje_sin_digital', type: 'float'},
                    {name: 'tot_pendiente', type: 'int'},
                    {name: 'pje_pendiente', type: 'float'},
                    {name: 'tot_auditado', type: 'int'},
                    {name: 'pje_auditado', type: 'float'},
                    {name: 'tot_procede', type: 'int'},
                    {name: 'pje_procede', type: 'float'},
                    {name: 'tot_no_procede', type: 'int'},
                    {name: 'pje_no_procede', type: 'float'},
                    {name: 'tot_ld', type: 'int'},
                    {name: 'prov_codigo', type: 'int'},
                    {name: 'id_linea', type: 'int'},
                    {name: 'tipo_rec_id', type: 'int'}
                ],
                proxy:{
                    type: 'ajax',
                    url: preclamos.url+'get_scm_reclamo_panel/',
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
                id: preclamos.id+'-form',
                border:false,
                // layout: 'fit',
                defaults:{
                    border: false
                },
                tbar:[
                    'Agencia:',
                    {
                        xtype: 'combo',
                        id: preclamos.id + '-agencia',
                        store: Ext.create('Ext.data.Store',{
                            fields: [
                                {name: 'prov_codigo', type: 'int'},
                                {name: 'prov_nombre', type: 'string'},
                                {name: 'prov_sigla', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: preclamos.url + 'get_usr_sis_provincias/',
                                reader:{
                                    type: 'json',
                                    rootProperty: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'prov_codigo',
                        displayField: 'prov_nombre',
                        emptyText: '[ Todos ]',
                        width: 100,
                        listConfig:{
                            minWidth: 150
                        },
                        listeners:{
                            afterrender: function(obj, e){
                                obj.getStore().load({
                                    params:{

                                    },
                                    callback: function(){
                                        obj.setValue(0);
                                    }
                                });
                            }
                        }
                    },
                    'Tipo:',
                    {
                        xtype: 'combo',
                        id: preclamos.id+'-tipo_reclamo',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'descripcion', type: 'string'},
                                {name: 'id_elemento', type: 'int'},
                                {name: 'des_corto', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: preclamos.url + 'get_scm_tabla_detalle/',
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
                        emptyText: '[ Todos ]',
                        listeners:{
                            afterrender: function(obj, e){
                                obj.getStore().load({
                                    params:{
                                        vp_tab_id: 'TAU',
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
                        id: preclamos.id+'-linea',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'id', type: 'int'},
                                {name: 'nombre', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: preclamos.url + 'get_usr_sis_linea_negocio/',
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
                                        obj.setValue(0);
                                    }
                                });
                            }
                        }
                    },
                    'Shipper',
                    {
                        xtype:'combo',
                        id:preclamos.id+'-shipper',
                        store:Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'shi_codigo', type: 'int'},
                                {name: 'shi_nombre', type: 'string'},
                                {name: 'shi_id', type: 'string'}
                            ],
                            proxy:{
                                type:'ajax',
                                url:preclamos.url+'get_usr_sis_shipper/',
                                reader:{
                                    type:'json',
                                    rootProperty:'data'
                                }
                            }
                        }),
                        queryMode:'local',
                        valueField:'shi_codigo',
                        displayField:'shi_nombre',
                        listConfig:{
                            minWidth:350
                        },
                        width:150,
                        forceSelection:true,
                        allowBlank:false,
                        selectOnFocus:true,
                        emptyText:'[ Seleccione ]',
                        listeners:{
                            afterrender:function(obj,record,options){
                                obj.getStore().load({
                                    params:{
                                        vp_linea:0
                                    },
                                    callback:function(){
                                        obj.setValue(0);
                                    }
                                });
                            }
                        }

                    },
                    'Desde:',
                    {
                        xtype: 'datefield',
                        id: preclamos.id + '-desde',
                        width: 90,
                        value: new Date()
                    },
                    'Hasta:',
                    {
                        xtype: 'datefield',
                        id: preclamos.id + '-hasta',
                        width: 90,
                        value: new Date()
                    },
                    '-',
                    {
                        text: 'Buscar',
                        id: preclamos.id+'-btn_buscar',
                        icon: '/images/icon/search.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 24, 
                                    id_btn: obj.getId(), 
                                    id_menu: preclamos.id_menu,
                                    fn: ['']
                                });
                            },
                            click: function(obj, e){
                                preclamos.get_consulta();
                            }
                        }
                    }
                ],
                layout: 'fit',
                // layout: 'vbox',
                items:[
                    {
                        xtype: 'grid',
                        id: preclamos.id + '-grid',
                        store: store,
                        columnLines: true,
                        // flex: 1,
                        // autoScroll: true,
                        features: [
                            {
                                ftype: 'summary',
                                dock: 'bottom'
                            }
                        ],
                        plugins: [
                            {
                                ptype: 'rowexpander',
                                pluginId: preclamos.id + '-cellplugin',
                                rowBodyTpl : new Ext.XTemplate(
                                    '<div id="'+preclamos.id+'-{prov_codigo}"></div>'
                                )
                            }
                        ],
                        columns:{
                            items:[
                                {
                                    text: 'X',
                                    flex: 1,
                                    dataIndex: '',
                                    menuDisabled: false
                                },
                                {
                                    text: 'Agencia',
                                    dataIndex: 'agencia',
                                    flex: 1,
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return 'Totales:';
                                    }
                                },
                                {
                                    text: 'Total Reclamo',
                                    dataIndex: 'tot_reclamo',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        metaData.style = "padding: 0px; margin: 0px";
                                        return global.permisos({
                                            type: 'link',
                                            id_menu: preclamos.id_menu,
                                            icons:[
                                                {id_serv: 24, value: value, qtip: 'Click para ver detalle.', js: ''}
                                            ]
                                        });
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: 'Sin Manifestar',
                                    dataIndex: 'tot_sin_ld',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        metaData.style = "padding: 0px; margin: 0px";
                                        return global.permisos({
                                            type: 'link',
                                            id_menu: preclamos.id_menu,
                                            icons:[
                                                {id_serv: 24, value: value, qtip: 'Click para ver detalle.', js: ''}
                                            ]
                                        });
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: '% Sin Manifestar',
                                    dataIndex: 'pje_sin_ld',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        return Ext.util.Format.number(value, '0.0');
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(preclamos.getDataSummary(summaryData, dataIndex), '0.0');
                                    }
                                },
                                {
                                    text: 'Total Digitalizado',
                                    dataIndex: 'tot_digital',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: '% Digitalizado',
                                    dataIndex: 'pje_digital',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        return Ext.util.Format.number(value, '0.0');
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(preclamos.getDataSummary(summaryData, dataIndex), '0.0');
                                    }
                                },
                                {
                                    text: 'Sin Digitalizar',
                                    dataIndex: 'tot_sin_digital',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        metaData.style = "padding: 0px; margin: 0px";
                                        return global.permisos({
                                            type: 'link',
                                            id_menu: preclamos.id_menu,
                                            icons:[
                                                {id_serv: 24, value: value, qtip: 'Click para ver detalle.', js: ''}
                                            ]
                                        });
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: '% Sin Digitalizar',
                                    dataIndex: 'pje_sin_digital',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        return Ext.util.Format.number(value, '0.0');
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(preclamos.getDataSummary(summaryData, dataIndex), '0.0');
                                    }
                                },
                                {
                                    text: 'Total Sin Auditar',
                                    dataIndex: 'tot_pendiente',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        metaData.style = "padding: 0px; margin: 0px";
                                        return global.permisos({
                                            type: 'link',
                                            id_menu: preclamos.id_menu,
                                            icons:[
                                                {id_serv: 24, value: value, qtip: 'Click para ver detalle.', js: ''}
                                            ]
                                        });
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: '% Sin Auditar',
                                    dataIndex: 'pje_pendiente',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        return Ext.util.Format.number(value, '0.0');
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(preclamos.getDataSummary(summaryData, dataIndex), '0.0');
                                    }
                                },
                                {
                                    text: 'Total Auditando',
                                    dataIndex: 'tot_ld',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        metaData.style = "padding: 0px; margin: 0px";

                                        var fecini = Ext.getCmp(preclamos.id + '-desde').getRawValue();
                                        var fecfin = Ext.getCmp(preclamos.id + '-hasta').getRawValue();

                                        var js = 'preclamos.get_form_auditando('+record.get('prov_codigo')+',\''+fecini+'\',\''+fecfin+'\','+record.get('tipo_rec_id')+','+record.get('id_linea')+');'

                                        return global.permisos({
                                            type: 'link',
                                            id_menu: preclamos.id_menu,
                                            icons:[
                                                {id_serv: 24, value: value, qtip: 'Click para ver detalle.', js: js}
                                            ]
                                        });
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: 'Total Auditados',
                                    dataIndex: 'tot_auditado',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        metaData.style = "padding: 0px; margin: 0px";
                                        return global.permisos({
                                            type: 'link',
                                            id_menu: preclamos.id_menu,
                                            icons:[
                                                {id_serv: 24, value: value, qtip: 'Click para ver detalle.', js: ''}
                                            ]
                                        });
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: '% Auditados',
                                    dataIndex: 'pje_auditado',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        return Ext.util.Format.number(value, '0.0');
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(preclamos.getDataSummary(summaryData, dataIndex), '0.0');
                                    }
                                },
                                {
                                    text: 'Procedentes',
                                    dataIndex: 'tot_procede',
                                    width: 80,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        metaData.style = "padding: 0px; margin: 0px";
                                        return global.permisos({
                                            type: 'link',
                                            id_menu: preclamos.id_menu,
                                            icons:[
                                                {id_serv: 24, value: value, qtip: 'Click para ver detalle.', js: ''}
                                            ]
                                        });
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: '% Procedentes',
                                    dataIndex: 'pje_procede',
                                    width: 80,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        return Ext.util.Format.number(value, '0.0');
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(preclamos.getDataSummary(summaryData, dataIndex), '0.0');
                                    }
                                },
                                {
                                    text: 'No Procedente',
                                    dataIndex: 'tot_no_procede',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        metaData.style = "padding: 0px; margin: 0px";
                                        return global.permisos({
                                            type: 'link',
                                            id_menu: preclamos.id_menu,
                                            icons:[
                                                {id_serv: 24, value: value, qtip: 'Click para ver detalle.', js: ''}
                                            ]
                                        });
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: '% No Procedente',
                                    dataIndex: 'pje_no_procede',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        return Ext.util.Format.number(value, '0.0');
                                    },
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(preclamos.getDataSummary(summaryData, dataIndex), '0.0');
                                    }
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
                            /*resize: function(obj, width, height, oldWidth, oldHeight, eOpts){
                                preclamos.gridResize(obj);
                            },*/
                            afterrender: function(obj){
                                preclamos.gridResize(obj);
                                obj.getView().addListener('expandbody', function(rowNode, record, expandRow, eOpts){

                                    var vp_prov = record.get('prov_codigo');
                                    var vp_tipo = record.get('tipo_rec_id');
                                    var vp_linea = record.get('id_linea');
                                    var vp_fecini = Ext.getCmp(preclamos.id + '-desde').getRawValue();
                                    var vp_fecfin = Ext.getCmp(preclamos.id + '-hasta').getRawValue();
                                    var vp_nivel = 'D';
                                    var vp_shipper = Ext.getCmp(preclamos.id+'-shipper').getValue();

                                    Ext.Ajax.request({
                                        url: preclamos.url + 'get_scm_reclamo_panel/',
                                        params:{
                                            vp_prov: vp_prov,
                                            vp_tipo: vp_tipo,
                                            vp_linea: vp_linea,
                                            vp_fecini: vp_fecini,
                                            vp_fecfin: vp_fecfin,
                                            vp_nivel: vp_nivel,
                                            vp_shipper:vp_shipper
                                        },
                                        success: function(response, options){
                                            var res = Ext.JSON.decode(response.responseText);
                                            // console.log(res);
                                            global.subtable({
                                                id: preclamos.id + '-subtable' + '-' + record.get('prov_codigo'),
                                                columns:[
                                                    {text: 'Fecha', width: '70px', dataIndex: 'fecha'},
                                                    {text: 'Tipo Reclamo', width: '150px', dataIndex: 'tipo_reclamo'},
                                                    {text: 'Total Reclamo', width: '70px', dataIndex: 'tot_reclamo', align: 'center'},
                                                    {text: 'Sin Imprimir', width: '70px', dataIndex: 'tot_sin_imp', align: 'center',
                                                        renderer: function(value, record){
                                                            var js = 'preclamos.get_form_control_impresiones('+record.prov_codigo+',\''+record.fecha+'\','+record.tipo_rec_id+', '+record.id_linea+');'
                                                            return '<a href="#" class="link" data-qtip="Click para imprimir." onclick="' + js + '">'+value+'</a>';
                                                        }
                                                    },
                                                    {text: 'Sin Manifestar', width: '70px', dataIndex: 'tot_sin_ld', align: 'center'},
                                                    {text: '% Sin Manifestar', width: '70px', dataIndex: 'pje_sin_ld', align: 'center'},
                                                    {text: 'Total Digitalizado', width: '70px', dataIndex: 'tot_digital', align: 'center'},
                                                    {text: '% Digitalizado', width: '70px', dataIndex: 'pje_digital', align: 'center'},
                                                    {text: 'Sin Digitalizar', width: '70px', dataIndex: 'tot_sin_digital', align: 'center'},
                                                    {text: '% Sin Digitalizar', width: '70px', dataIndex: 'pje_sin_digital', align: 'center'},
                                                    {text: 'Total Sin Auditar', width: '70px', dataIndex: 'tot_pendiente', align: 'center'},
                                                    {text: '% Sin Auditar', width: '70px', dataIndex: 'pje_pendiente', align: 'center'},
                                                    {text: 'Total Auditando', width: '70px', dataIndex: 'tot_ld', align: 'center'},
                                                    {text: 'Total Auditados', width: '70px', dataIndex: 'tot_auditado', align: 'center'},
                                                    {text: '% Auditados', width: '70px', dataIndex: 'pje_auditado', align: 'center'},
                                                    {text: 'Procedentes', width: '70px', dataIndex: 'tot_procede', align: 'center'},
                                                    {text: '% Procedentes', width: '70px', dataIndex: 'pje_procede', align: 'center'},
                                                    {text: 'No Procendente', width: '70px', dataIndex: 'tot_no_procede', align: 'center'},
                                                    {text: '% No Procedente', width: '70px', dataIndex: 'pje_no_procede', align: 'center'}
                                                ],
                                                data: res.data,
                                                renderTo: preclamos.id + '-' + record.get('prov_codigo')
                                            });
                                        }
                                    });
                                });
                            }
                        }
                    }
                ]
            });

            tab.add({
                id: preclamos.id+'-tab',
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
                        global.state_item_menu(preclamos.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        /*Ext.getCmp(preclamos.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + preclamos.id_menu).text,
                            icon: Ext.getCmp('menu-' + preclamos.id_menu).icon
                        });*/
                        global.state_item_menu_config(obj,preclamos.id_menu);
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(preclamos.id_menu, false);
                    }
                }
            }).show();
        },
        gridResize: function(obj){
            obj.columns[0].show();
            obj.columns[0].hide();
        },
        get_consulta: function(){
            var grid = Ext.getCmp(preclamos.id + '-grid');
            var store = grid.getStore();

            var vp_prov = Ext.getCmp(preclamos.id + '-agencia').getValue();
            var vp_tipo = Ext.getCmp(preclamos.id+'-tipo_reclamo').getValue();
            var vp_linea = Ext.getCmp(preclamos.id+'-linea').getValue();
            var vp_fecini = Ext.getCmp(preclamos.id + '-desde').getRawValue();
            var vp_fecfin = Ext.getCmp(preclamos.id + '-hasta').getRawValue();
            var vp_nivel = 'R';
            var vp_shipper = Ext.getCmp(preclamos.id+'-shipper').getValue();

          //  console.log(vp_shipper);

            store.load({
                params:{
                    vp_prov: vp_prov,
                    vp_tipo: vp_tipo,
                    vp_linea: vp_linea,
                    vp_fecini: vp_fecini,
                    vp_fecfin: vp_fecfin,
                    vp_nivel: vp_nivel,
                    vp_shipper:vp_shipper
                },
                callback: function(){
                    preclamos.gridResize(grid);
                }
            });
        },
        getDataSummary: function(data, i){
            var a = [];
            Ext.Object.each(data, function(index, value){
                a.push(value);
            });
            var res = 0;
            switch(i){
                case 'pje_sin_ld': res = ( ( parseInt(a[2]) / parseInt(a[1]) ) * 100 ); break;
                case 'pje_digital': res = ( ( parseInt(a[4]) / parseInt(a[1]) ) * 100 ); break;
                case 'pje_sin_digital': res = ( ( parseInt(a[6]) / parseInt(a[1]) ) * 100 ); break;
                case 'pje_pendiente': res = ( ( parseInt(a[8]) / parseInt(a[1]) ) * 100 ); break;
                case 'pje_auditado': res = ( ( parseInt(a[11]) / parseInt(a[1]) ) * 100 ); break;
                case 'pje_procede': res = ( ( parseInt(a[13]) / parseInt(a[11]) ) * 100 ); break;
                case 'pje_no_procede': res = ( ( parseInt(a[15]) / parseInt(a[11]) ) * 100 ); break;
            }
            res = isNaN(res) ? 0 : res;
            return Ext.util.Format.number(res);
        },
        get_form_control_impresiones: function(vp_prov, vp_fecini, tipo_rec_id, id_linea){
            win.show({vurl: preclamos.url + 'form_control_impresiones/?vp_prov='+vp_prov+'&vp_fecini='+vp_fecini+'&tipo_rec_id='+tipo_rec_id+'&id_linea='+id_linea, id_menu: preclamos.id_menu, class: ''});
        },
        get_form_auditando: function(vp_prov, vp_fecini, vp_fecfin, tipo_rec_id, vp_linea){
            win.show({vurl: preclamos.url + 'form_auditando/?vp_prov='+vp_prov+'&vp_fecini='+vp_fecini+'&vp_fecfin='+vp_fecfin+'&vp_tipo='+tipo_rec_id+'&vp_linea='+vp_linea, id_menu: preclamos.id_menu, class: ''});
        }
    }
    Ext.onReady(preclamos.init, preclamos);
}else{
    tab.setActiveTab(preclamos.id+'-tab');
}
</script>