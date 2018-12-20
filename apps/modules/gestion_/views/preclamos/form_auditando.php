<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('auditando-tab')){
    var auditando = {
        id: 'auditando',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/preclamos/',
        vp_prov: '<?php echo $p["vp_prov"];?>',
        vp_tipo: '<?php echo $p["vp_tipo"];?>',
        vp_fecini: '<?php echo $p["vp_fecini"];?>',
        vp_fecfin: '<?php echo $p["vp_fecfin"];?>',
        vp_linea: '<?php echo $p["vp_linea"];?>',
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'reclamo', type: 'int'},
                    {name: 'fecha', type: 'string'},
                    {name: 'shipper', type: 'string'},
                    {name: 'servicio', type: 'string'},
                    {name: 'ciclo', type: 'string'},
                    {name: 'cliente', type: 'string'},
                    {name: 'direccion', type: 'string'},
                    {name: 'localidad', type: 'string'},
                    {name: 'guia', type: 'int'}
                ],
                proxy:{
                    type: 'ajax',
                    url: auditando.url+'get_scm_reclamo_en_ld/',
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
                id: auditando.id+'-form',
                border:false,
                defaults:{
                    border: false
                },
                tbar:[
                    {
                        text: 'Retornar',
                        icon: '/images/icon/get_back.png',
                        listeners:{
                            click: function(obj, e){
                                Ext.getCmp(auditando.id+'-tab').close();
                            }
                        }
                    },
                    '-'
                ],
                layout: 'fit',
                items:[
                    {
                        xtype: 'grid',
                        store: store,
                        columnLines: true,
                        flex: 1,
                        autoScroll: true,
                        columns:{
                            items:[
                                {
                                    text: 'Reclamo',
                                    dataIndex: 'reclamo',
                                    width: 70,
                                    align: 'right',
                                    renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
                                        var js = 'auditando.get_form_descarga_man('+record.get('reclamo')+')';

                                        return global.permisos({
                                            type: 'link',
                                            id_menu: auditando.id_menu,
                                            icons:[
                                                {id_serv: 24, value: value, qtip: 'Click para ver detalle.', js: js}
                                            ]
                                        });
                                    }
                                },
                                {
                                    text: 'Fecha',
                                    dataIndex: 'fecha',
                                    width: 70
                                },
                                {
                                    text: 'Shipper',
                                    dataIndex: 'shipper',
                                    flex: 1
                                },
                                {
                                    text: 'Servicio',
                                    dataIndex: 'servicio',
                                    flex: 1
                                },
                                {
                                    text: 'Ciclo',
                                    dataIndex: 'ciclo',
                                    width: 70
                                },
                                {
                                    text: 'Cliente',
                                    dataIndex: 'cliente',
                                    flex: 1
                                },
                                {
                                    text: 'Dirección',
                                    dataIndex: 'direccion',
                                    flex: 1
                                },
                                {
                                    text: 'Localidad',
                                    dataIndex: 'localidad',
                                    flex: 1
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
                            afterrender: function(obj, e){
                                obj.getStore().load({
                                    params:{
                                        vp_prov: auditando.vp_prov,
                                        vp_tipo: auditando.vp_tipo,
                                        vp_fecini: auditando.vp_fecini,
                                        vp_fecfin: auditando.vp_fecfin,
                                        vp_linea: auditando.vp_linea
                                    },
                                    callback: function(){

                                    }
                                });
                            }
                        }
                    }
                ]
            });

            tab.add({
                id: auditando.id+'-tab',
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
        get_form_descarga_man: function(reclamo){
            win.show({vurl: preclamos.url + 'form_descarga_man/?vp_reclamo=' + reclamo+'&window=1', id_menu: preclamos.id_menu, class: ''});
        }
    }
    Ext.onReady(auditando.init, auditando);
}else{
    tab.setActiveTab(auditando.id+'-tab');
}
</script>