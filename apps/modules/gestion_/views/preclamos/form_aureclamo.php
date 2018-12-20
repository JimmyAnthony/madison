<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('aureclamo-tab')){
    var aureclamo = {
        id: 'aureclamo',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/preclamos/',
        prov_codigo: parseInt('<?php echo PROV_CODIGO;?>'),
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'agencia', type: 'string'},
                    {name: 'man_id', type: 'int'},
                    {name: 'fecha', type: 'string'},
                    {name: 'auditor', type: 'string'},
                    {name: 'tot_ld', type: 'int'},
                    {name: 'tot_verificado', type: 'int'},
                    {name: 'tot_no_verif', type: 'int'},
                    {name: 'tot_descargado', type: 'int'},
                    {name: 'tot_saldo', type: 'int'},
                    {name: 'pje_saldo', type: 'float'},
                    {name: 'tot_procede', type: 'int'},
                    {name: 'pje_procede', type: 'float'},
                    {name: 'tot_no_proce', type: 'int'},
                    {name: 'pje_no_proce', type: 'float'},
                    {name: 'estado', type: 'string'},
                    {name: 'prov_codigo', type: 'int'},
                    {name: 'cod_estado', type: 'string'},
                ],
                proxy:{
                    type: 'ajax',
                    url: aureclamo.url+'get_scm_reclamo_qry_auditorias/',
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
                id: aureclamo.id+'-form',
                border:false,
                layout: 'fit',
                defaults:{
                    border: false
                },
                tbar:[
                    'Agencia:',
                    {
                        xtype: 'combo',
                        id: aureclamo.id + '-agencia',
                        store: Ext.create('Ext.data.Store',{
                            fields: [
                                {name: 'prov_codigo', type: 'int'},
                                {name: 'prov_nombre', type: 'string'},
                                {name: 'prov_sigla', type: 'string'}
                            ],
                            proxy:{
                                type: 'ajax',
                                url: aureclamo.url + 'get_usr_sis_provincias/',
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
                        width: 150,
                        listConfig:{
                            minWidth: 150
                        },
                        listeners:{
                            afterrender: function(obj, e){
                                obj.getStore().load({
                                    params:{
                                        all: 'all'
                                    },
                                    callback: function(){
                                        obj.setValue(aureclamo.prov_codigo)
                                    }
                                });
                            }
                        }
                    },
                    'Desde:',
                    {
                        xtype: 'datefield',
                        id: aureclamo.id + '-desde',
                        width: 90,
                        value: new Date()
                    },
                    'Hasta:',
                    {
                        xtype: 'datefield',
                        id: aureclamo.id + '-hasta',
                        width: 90,
                        value: new Date()
                    },
                    '-',
                    {
                        text: 'Buscar',
                        icon: '/images/icon/search.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 31, 
                                    id_btn: obj.getId(), 
                                    id_menu: aureclamo.id_menu,
                                    fn: ['']
                                });
                            },
                            click: function(obj, e){
                                aureclamo.consultar();
                            }
                        }
                    },
                    '-',
                    {
                        text: 'Exportar',
                        icon: '/images/icon/excel.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 32, 
                                    id_btn: obj.getId(), 
                                    id_menu: aureclamo.id_menu,
                                    fn: ['']
                                });
                            },
                            click: function(obj, e){
                                // var vp_prov = Ext.getCmp(aureclamo.id + '-agencia').getValue();
                                // var vp_fecini = Ext.getCmp(aureclamo.id + '-desde').getRawValue();
                                // var vp_fecfin = Ext.getCmp(aureclamo.id + '-hasta').getRawValue();

                                // window.open(aureclamo.url + 'rpt_excel_aureclamo/?vp_prov='+vp_prov+'&vp_fecini='+vp_fecini+'&vp_fecfin='+vp_fecfin, '_blank');
                            }
                        }
                    },
                    {
                        text: 'Nuevo',
                        icon: '/images/icon/add.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 28, 
                                    id_btn: obj.getId(), 
                                    id_menu: aureclamo.id_menu,
                                    fn: ['']
                                });
                            },
                            click: function(obj, e){
                                aureclamo.form_nuevo();
                            }
                        }
                    },
                    {
                        text: 'Descarga',
                        icon: '/images/icon/download.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id_serv: 34, 
                                    id_btn: obj.getId(), 
                                    id_menu: aureclamo.id_menu,
                                    fn: ['']
                                });
                            },
                            click: function(obj, e){
                                win.show({vurl: aureclamo.url + 'form_descarga_man/?vp_reclamo=0&window=0', id_menu: aureclamo.id_menu, class: ''});
                            }
                        }
                    }
                ],
                items:[
                    {
                        xtype: 'grid',
                        id: aureclamo.id + '-grid',
                        store: store,
                        columnLines: true,
                        features: [
                            {
                                ftype: 'summary',
                                dock: 'bottom'
                            }
                        ],
                        flex: 1,
                        // layout: 'hbox',
                        columns:{
                            items:[
                                {
                                    text: 'Agencia',
                                    dataIndex: 'agencia',
                                    flex: 1,
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return 'Totales:';
                                    }
                                },
                                {
                                    text: 'Id Manifiesto',
                                    dataIndex: 'man_id',
                                    width: 80
                                },
                                {
                                    text: 'Fecha',
                                    dataIndex: 'fecha',
                                    width: 70
                                },
                                {
                                    text: 'Auditor',
                                    dataIndex: 'auditor',
                                    width: 250
                                },
                                {
                                    text: 'Total LD',
                                    dataIndex: 'tot_ld',
                                    width: 70
                                },
                                {
                                    text: 'Total Verificado',
                                    dataIndex: 'tot_verificado',
                                    width: 70,
                                    cls: 'column_header_double'
                                },
                                {
                                    text: 'Total Procedente',
                                    dataIndex: 'tot_no_verif',
                                    width: 70,
                                    cls: 'column_header_double'
                                },
                                {
                                    text: 'Total No Procesado',
                                    dataIndex: 'tot_descargado',
                                    width: 70,
                                    cls: 'column_header_double'
                                },
                                {
                                    text: 'Saldo',
                                    dataIndex: 'tot_saldo',
                                    width: 70
                                },
                                {
                                    text: '% Saldo',
                                    dataIndex: 'pje_saldo',
                                    width: 70
                                },
                                {
                                    text: 'Estado',
                                    dataIndex: 'estado',
                                    width: 200
                                },
                                {
                                    text: 'Opciones',
                                    dataIndex: '',
                                    width: 70,
                                    align: 'center',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                                        metaData.style = "padding: 0px; margin: 0px";
                                        return global.permisos({
                                            type: 'link',
                                            id_menu: aureclamo.id_menu,
                                            icons:[
                                                {id_serv: 28, img: 'edit.png', qtip: 'Click para editar manifiesto.', js: 'aureclamo.editar('+record.get('prov_codigo')+','+record.get('man_id')+');'},
                                                {id_serv: 28, img: 'remove.png', qtip: 'Click para anular manifiesto.', js: 'aureclamo.anular('+record.get('prov_codigo')+','+record.get('man_id')+');'},
                                                {id_serv: 28, img: 'download.png', qtip: 'Click para descargar manifiesto.', js: 'aureclamo.descargar('+record.get('prov_codigo')+','+record.get('man_id')+');'}
                                            ]
                                        });
                                    }
                                }
                            ],
                            defaults:{
                                menuDisabled: true
                            }
                        },
                        viewConfig: {
                            stripeRows: true,
                            enableTextSelection: true,
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
                id: aureclamo.id+'-tab',
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
                        global.state_item_menu(aureclamo.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        /*Ext.getCmp(aureclamo.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + aureclamo.id_menu).text,
                            icon: Ext.getCmp('menu-' + aureclamo.id_menu).icon
                        });*/
                        global.state_item_menu_config(obj,aureclamo.id_menu);
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(aureclamo.id_menu, false);
                    }
                }
            }).show();
        },
        form_nuevo: function(){
            win.show({vurl: aureclamo.url + 'form_nuevo_manifiesto/?accion=nuevo', id_menu: aureclamo.id_menu, class: ''});
        },
        descargar: function(vp_prov, vp_man_id){
            win.show({vurl: aureclamo.url + 'form_audescarga/?vp_prov='+vp_prov+'&vp_man_id='+vp_man_id, id_menu: aureclamo.id_menu, class: ''});
        },
        consultar: function(){
            var grid = Ext.getCmp(aureclamo.id+'-grid');
            var store = grid.getStore();

            var vp_prov = Ext.getCmp(aureclamo.id + '-agencia').getValue();
            var vp_fecini = Ext.getCmp(aureclamo.id + '-desde').getRawValue();
            var vp_fecfin = Ext.getCmp(aureclamo.id + '-hasta').getRawValue();

            store.load({
                params:{
                    vp_prov: vp_prov,
                    vp_fecini: vp_fecini,
                    vp_fecfin: vp_fecfin
                },
                callback: function(){
                    
                }
            });
        },
        anular: function(vp_prov, vp_man_id){
            global.Msg({
                msg: '¿Seguro de anular?',
                icon: 3,
                buttons: 3,
                fn: function(btn){
                    if (btn == 'yes'){
                        Ext.Ajax.request({
                            url: aureclamo.url + 'set_scm_reclamo_audi_anular/',
                            params:{
                                vp_prov : vp_prov,
                                vp_man_id: vp_man_id
                            },
                            success: function(response, options){
                                var res = Ext.JSON.decode(response.responseText);
                                // console.log(res);
                                if (parseInt(res.error_sql) >= 0 ){
                                    global.Msg({
                                        msg: res.error_info,
                                        icon: 1,
                                        buttons: 1,
                                        fn: function(btn){
                                            aureclamo.consultar();
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
            });
        },
        editar: function(vp_prov, vp_man_id){
            win.show({vurl: aureclamo.url + 'form_nuevo_manifiesto/?accion=editar&vp_prov='+vp_prov+'&vp_man_id='+vp_man_id, id_menu: aureclamo.id_menu, class: ''});
        }
    }
    Ext.onReady(aureclamo.init, aureclamo);
}else{
    tab.setActiveTab(aureclamo.id+'-tab');
}
</script>