<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('gestion_devolucion-tab')){
    var gestion_devolucion = {
        id: 'gestion_devolucion',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/postal/GestionDevolucion/',
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'des_detalle', type: 'string'},
                    {name: 'ciclo', type: 'string'},
                    {name: 'cierre', type: 'string'},
                    {name: 'total_as', type: 'int'},
                    {name: 'total_dv', type: 'int'},
                    {name: 'pje_dv', type: 'string'},
                    {name: 'total_gt', type: 'int'},
                    {name: 'pje_gt', type: 'string'},
                    {name: 'gt_actualizada', type: 'int'},
                    {name: 'pje_gt_actualizada', type: 'string'},
                    {name: 'gt_dir_ok', type: 'int'},
                    {name: 'pje_dir_ok', type: 'string'},
                    {name: 'gt_error', type: 'int'},
                    {name: 'pje_gt_error', type: 'string'},
                    {name: 'pendiente_gt', type: 'int'},
                    {name: 'pje_pen_gt', type: 'string'},
                    {name: 'orden', type: 'int'},
                    {name: 'agencia', type: 'string'},
                    {name: 'ubigeo', type: 'string'}
                ],
                proxy:{
                    type: 'ajax',
                    url: gestion_devolucion.url+'get_pcn_gestion_devolucion/',
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
                id: gestion_devolucion.id+'-form',
                border:false,
                layout: 'border',
                defaults:{
                    border: false
                },
                tbar:[
                    '-',
                    {
                        xtype: 'combo',
                        id: gestion_devolucion.id + '-shipper',
                        fieldLabel: 'Shipper',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'shi_codigo', type: 'int'},
                                {name: 'shi_nombre', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: gestion_devolucion.url + 'get_pcn_shipper',
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
                            minWidth: 220
                        },
                        width: 280,
                        labelWidth: 50,
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
                        xtype: 'combo',
                        id: gestion_devolucion.id + '-provincia',
                        fieldLabel: 'Provincia',
                        store: Ext.create('Ext.data.Store',{
                            fields:[
                                {name: 'prov_codigo', type: 'string'},
                                {name: 'prov_nombre', type: 'string'},
                                {name: 'ciu_id', type: 'int'},
                                {name: 'iata', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: gestion_devolucion.url + 'get_pcn_provincias',
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
                        width: 260,
                        labelWidth: 50,
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
                        xtype: 'datefield',
                        id: gestion_devolucion.id + '-ciclo',
                        fieldLabel: 'Corte/Ciclo',
                        width: 160,
                        labelWidth: 60,
                        value: new Date()
                    },
                    '-',
                    {
                        text: 'Consultar',
                        id: gestion_devolucion.id + '-btn_buscar',
                        icon: '/images/icon/search.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id: 11, 
                                    id_btn: obj.getId(), 
                                    id_menu: gestion_devolucion.id_menu,
                                    fn: ['gestion_devolucion.consultar']
                                });
                            },
                            click: function(obj, e){
                                gestion_devolucion.consultar();
                            }
                        }
                    }
                ],
                items:[
                    {
                        region:'center',
                        frame:true,
                        border:false,
                        layout:'fit',
                        items:[
                            {
                                xtype: 'grid',
                                id: gestion_devolucion.id + '-grid',
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
                                            text: 'Servicio',
                                            dataIndex: 'des_detalle',
                                            width: 220
                                        },
                                        {
                                            text: 'Ciclo',
                                            dataIndex: 'ciclo',
                                            flex: 1
                                        },
                                        {
                                            text: 'F.Cierre',
                                            dataIndex: 'cierre',
                                            width: 70
                                        },
                                        {
                                            text: 'T.Recibido',
                                            dataIndex: 'total_as',
                                            width: 60,
                                            align: 'right'
                                        },
                                        {
                                            text: 'Total DV',
                                            dataIndex: 'total_dv',
                                            width: 60,
                                            align: 'right'
                                        },
                                        {
                                            text: '%DV',
                                            dataIndex: 'pje_dv',
                                            width: 50,
                                            align: 'right'
                                        },
                                        {
                                            text: 'T.Gestionado',
                                            dataIndex: 'total_gt',
                                            width: 80,
                                            align: 'right'
                                        },
                                        {
                                            text: '%Gestionado',
                                            dataIndex: 'pje_gt',
                                            width: 80,
                                        },
                                        {
                                            text: 'T.Actualizado',
                                            dataIndex: 'gt_actualizada',
                                            flex: 1,
                                            align: 'left'
                                        },
                                        {
                                            text: '%Actualizado',
                                            dataIndex: 'pje_gt_actualizada',
                                            flex: 1,
                                            align: 'left'
                                        },
                                        {
                                            text: 'T.Validos',
                                            dataIndex: 'gt_dir_ok',
                                            width: 60,
                                            align: 'left'
                                        },
                                        {
                                            text: '%Validos',
                                            dataIndex: 'pje_dir_ok',
                                            width: 60,
                                            align: 'left'
                                        },
                                        {
                                            text: 'T. No Contestados',
                                            dataIndex: 'gt_error',
                                            flex: 1,
                                            align: 'left'
                                        },
                                        {
                                            text: '%No Contactados',
                                            dataIndex: 'pje_gt_error',
                                            flex: 1,
                                            align: 'left'
                                        },
                                        {
                                            text: 'Pend.Gest.',
                                            dataIndex: 'pendiente_gt',
                                            width: 65,
                                            align: 'center',
                                            renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                                return global.permisos({
                                                   type: 'link',
                                                   id_menu: gestion_devolucion.id_menu,
                                                   icons:[
                                                       {id: 14, value: value, qtip: 'Click para asignar gestion.', js:'gestion_devolucion.get_form_asignar'}
                                                   ]
                                               });
                                            }
                                        },
                                        {
                                            text: '%Pend.Gest.',
                                            dataIndex: 'pje_pen_gt',
                                            width: 70,
                                            align: 'left'
                                        },
                                        {
                                            text: 'No Gestionable.',
                                            dataIndex: '',
                                            flex: 1,
                                            align: 'left'
                                        }
                                    ],
                                    defaults:{
                                        menuDisabled: true
                                    }
                                },
                                plugins: [
                                    
                                ],
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
                    }
                ]
            });

            tab.add({
                id: gestion_devolucion.id+'-tab',
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
                        global.state_item_menu(gestion_devolucion.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        Ext.getCmp(gestion_devolucion.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + gestion_devolucion.id_menu).text,
                            icon: Ext.getCmp('menu-' + gestion_devolucion.id_menu).icon
                        });
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(gestion_devolucion.id_menu, false);
                    }
                }
            }).show();
        },
        consultar: function(){
            var va_shi_codigo = Ext.getCmp(gestion_devolucion.id + '-shipper').getValue();
            va_shi_codigo = va_shi_codigo == null ? 0 : va_shi_codigo;
            var va_producto = Ext.getCmp(gestion_devolucion.id + '-provincia').getValue();
            va_producto = va_producto == null ? 0 : va_producto;
            var va_ciclo = Ext.getCmp(gestion_devolucion.id + '-ciclo').getRawValue();
            
            var grid = Ext.getCmp(gestion_devolucion.id+'-grid');
            var store = grid.getStore();
            store.load({
                params:{
                    vp_orden:0,
                    vp_shipper: va_shi_codigo,
                    vp_producto: va_producto,
                    vp_ciclo: va_ciclo,
                    vp_reporte: 1
                },
                callback: function(){

                }
            });
        },
        get_form_asignar: function(){
            win.show({vurl: gestion_devolucion.url + 'get_form_asignar/?obj=gestion_devolucion', id_menu: gestion_devolucion.id_menu, class: 'gestion_devolucion'});
        }
    }
    Ext.onReady(gestion_devolucion.init, gestion_devolucion);
}else{
    tab.setActiveTab(gestion_devolucion.id+'-tab');
}
</script>
