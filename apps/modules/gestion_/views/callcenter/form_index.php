<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('callcenter-tab')){
    var callcenter = {
        id: 'callcenter',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/callcenter/',
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'linea', type: 'string'},
                    {name: 'gestionista', type: 'string'},
                    {name: 'shipper', type: 'string'},
                    {name: 'fecha', type: 'string'},
                    {name: 'inicio', type: 'string'},
                    {name: 'cierre', type: 'string'},
                    {name: 'tiempo', type: 'string'},
                    {name: 'total', type: 'int'},
                    {name: 'gestionados', type: 'int'},
                    {name: 'efectivas', type: 'int'},
                    {name: 'pje_efectiva', type: 'float'},
                    {name: 'no_contactado', type: 'int'},
                    {name: 'pje_no_contac', type: 'float'},
                    {name: 'pendiente', type: 'int'},
                    {name: 'pje_pendiente', type: 'float'},
                    {name: 'entrantes', type: 'int'},
                    {name: 'ent_efectivas', type: 'int'},
                    {name: 'pje_ent_efec', type: 'float'},
                    {name: 'estado', type: 'string'},
                    {name: 'id_gestion', type: 'int'}
                ],
                proxy:{
                    type: 'ajax',
                    url: callcenter.url+'get_scm_call_buzon_gestiones/',
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
                id: callcenter.id+'-form',
                border:false,
                layout: 'fit',
                defaults:{
                    border: false
                },
                tbar:[
                    'Desde:',
                    {
                        xtype: 'datefield',
                        id: callcenter.id + '-desde',
                        width: 90,
                        value: new Date()
                    },
                    'Hasta:',
                    {
                        xtype: 'datefield',
                        id: callcenter.id + '-hasta',
                        width: 90,
                        value: new Date()
                    },
                    'Gestionista:',
                    {
                        xtype: 'combo',
                        id: callcenter.id + '-gestionista',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'ges_id', type: 'int'},
                                {name: 'gestionista', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: callcenter.url + 'get_scm_call_gestionistas/',
                                reader:{
                                    type: 'json',
                                    rootProperty: 'data'
                                }
                            }
                        }),
                        queryMode: 'local',
                        valueField: 'ges_id',
                        displayField: 'gestionista',
                        listConfig:{
                            minWidth: 300
                        },
                        width: 150,
                        forceSelection: true,
                        emptyText: '[ Seleccione ]',
                        listeners:{
                            afterrender: function(obj){
                                obj.getStore().load({
                                    params:{},
                                    callback: function(){

                                    }
                                });
                            }
                        }
                    },
                    '-',
                    {
                        text: 'Buscar',
                        id: callcenter.id + '-btn-buscar',
                        icon: '/images/icon/search.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 8, 
                                    id_btn: obj.getId(), 
                                    id_menu: callcenter.id_menu,
                                    fn: ['callcenter.consultar']
                                });
                            },
                            click: function(obj, e){
                                callcenter.consultar();
                            }
                        }
                    }
                ],
                items:[
                    {
                        xtype: 'grid',
                        id: callcenter.id + '-grid',
                        store: store,
                        columnLines: true,
                        features: [
                            {
                                ftype: 'summary',
                                dock: 'bottom'
                            }
                        ],
                        columns:{
                            items:[
                                {
                                    text: '&nbsp;',
                                    dataIndex: '',
                                    width: 30,
                                    align: 'center',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        metaData.style = "padding: 0px; margin: 0px";
                                        return global.permisos({
                                            type: 'link',
                                            id_menu: callcenter.id_menu,
                                            icons:[
                                                {id_serv: 9, img: 'detail.png', qtip: 'Click para ver detalle.', js: 'callcenter.getFormDetalleGestion()'}
                                            ]
                                        });
                                    }
                                },
                                {
                                    text: 'Línea',
                                    dataIndex: 'linea',
                                    width: 150,
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return 'Totales:';
                                    }
                                },
                                {
                                    text: 'Gestionista',
                                    dataIndex: 'gestionista',
                                    width: 250
                                },
                                {
                                    text: 'Shipper',
                                    dataIndex: 'shipper',
                                    width: 250
                                },
                                {
                                    text: 'Fecha',
                                    dataIndex: 'fecha',
                                    width: 70
                                },
                                {
                                    text: 'Hora Inicio',
                                    dataIndex: 'inicio',
                                    width: 50,
                                    cls: 'column_header_double',
                                    align: 'center'
                                },
                                {
                                    text: 'Hora Término',
                                    dataIndex: 'cierre',
                                    width: 60,
                                    cls: 'column_header_double',
                                    align: 'center'
                                },
                                {
                                    text: 'Tiempo Usado',
                                    dataIndex: 'tiempo',
                                    width: 60,
                                    cls: 'column_header_double',
                                    align: 'center'
                                },
                                {
                                    text: 'Total Asignado',
                                    dataIndex: 'total',
                                    width: 60,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: 'Total Efectivas',
                                    dataIndex: 'efectivas',
                                    width: 60,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: '% Efectivas',
                                    dataIndex: 'pje_efectiva',
                                    width: 60,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(callcenter.getDataSummary(summaryData, dataIndex), '0.0');
                                    }
                                },
                                {
                                    text: 'No Conectados',
                                    dataIndex: 'no_contactado',
                                    width: 80,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: '% No Conectados',
                                    dataIndex: 'pje_no_contac',
                                    width: 80,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(callcenter.getDataSummary(summaryData, dataIndex), '0.0');
                                    }
                                },
                                {
                                    text: 'Por Gestionar',
                                    dataIndex: 'pendiente',
                                    width: 70,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: '% Por Gestionar',
                                    dataIndex: 'pje_pendiente',
                                    width: 75,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(callcenter.getDataSummary(summaryData, dataIndex), '0.0');
                                    }
                                },
                                {
                                    text: 'Llamadas Entrantes',
                                    dataIndex: 'entrantes',
                                    width: 65,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: 'Efectivas Entrantes',
                                    dataIndex: 'ent_efectivas',
                                    width: 80,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0,000');
                                    }
                                },
                                {
                                    text: '% Efectivas Entrantes',
                                    dataIndex: 'pje_ent_efec',
                                    width: 80,
                                    cls: 'column_header_double',
                                    align: 'right',
                                    summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(callcenter.getDataSummary(summaryData, dataIndex), '0.0');
                                    }
                                },
                                {
                                    text: 'Estado',
                                    dataIndex: 'estado',
                                    width: 100,
                                    align: 'left'
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
                                
                            }
                        }
                    }
                ]
            });

            tab.add({
                id: callcenter.id+'-tab',
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
                        global.state_item_menu(callcenter.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        /*Ext.getCmp(callcenter.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + callcenter.id_menu).text,
                            icon: Ext.getCmp('menu-' + callcenter.id_menu).icon
                        });*/
                        global.state_item_menu_config(obj,callcenter.id_menu);
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(callcenter.id_menu, false);
                    }
                }
            }).show();
        },
        consultar: function(){
            var grid = Ext.getCmp(callcenter.id + '-grid');
            var store = grid.getStore();

            var vp_fecini = Ext.getCmp(callcenter.id + '-desde').getRawValue();
            var vp_fecfin = Ext.getCmp(callcenter.id + '-hasta').getRawValue();
            var vp_gestionista = Ext.getCmp(callcenter.id + '-gestionista').getValue();
            vp_gestionista = vp_gestionista == null ? 0 : vp_gestionista;

            store.load({
                params:{
                    vp_fecini: vp_fecini,
                    vp_fecfin: vp_fecfin,
                    vp_gestionista: vp_gestionista
                },
                callback: function(){

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
                case 'pje_efectiva': res = ( ( parseInt(a[9]) / parseInt(a[8]) ) * 100 ); break;
                case 'pje_no_contac': res = ( ( parseInt(a[11]) / parseInt(a[8]) ) * 100 ); break;
                case 'pje_pendiente': res = ( ( parseInt(a[13]) / parseInt(a[8]) ) * 100 ); break;
                case 'pje_ent_efec': res = ( ( parseInt(a[16]) / parseInt(a[15]) ) * 100 ); break;
            }
            res = isNaN(res) ? 0 : res;
            return Ext.util.Format.number(res);
        },
        getFormDetalleGestion: function(){
            win.show({vurl: callcenter.url + 'form_detalle_gestion/', id_menu: callcenter.id_menu, class: 'detalleGestion'});
        }
    }
    Ext.onReady(callcenter.init, callcenter);
}else{
    tab.setActiveTab(callcenter.id+'-tab');
}
</script>