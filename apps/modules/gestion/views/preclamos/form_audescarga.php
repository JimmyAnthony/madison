<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('auDescarga-tab')){
    var auDescarga = {
        id: 'auDescarga',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/preclamos/',
        vp_prov: parseInt('<?php echo $p["vp_prov"];?>'),
        vp_man_id: parseInt('<?php echo $p["vp_man_id"];?>'),
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
                    {name: 'estado', type: 'int'},
                    {name: 'item', type: 'int'},
                    {name: 'guia', type: 'int'}
                ],
                proxy:{
                    type: 'ajax',
                    url: auDescarga.url+'get_scm_reclamo_audi_detalle/',
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
                id: auDescarga.id + '-form',
                border: false,
                defaults:{
                    border: false
                },
                tbar:[
                    {
                        text: 'Regresar',
                        icon: '/images/icon/get_back.png',
                        listeners:{
                            click: function(obj, e){
                                Ext.getCmp(auDescarga.id+'-tab').close();
                            }
                        }
                    },
                    '-'
                ],
                layout: 'border',
                items:[
                    {
                        region: 'north',
                        height: 55,
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
                                        id: auDescarga.id + '-id',
                                        fieldLabel: 'Nº',
                                        width: 140,
                                        readOnly: true,
                                        labelWidth: 45
                                    },
                                    {
                                        xtype: 'textfield',
                                        id: auDescarga.id + '-fecha',
                                        fieldLabel: 'Fecha',
                                        width: 130,
                                        labelWidth: 45,
                                        readOnly: true,
                                        labelAlign: 'right'
                                    },
                                    {
                                        xtype: 'textfield',
                                        id: auDescarga.id + '-auditor',
                                        fieldLabel: 'Auditor',
                                        flex: 1,
                                        labelWidth: 55,
                                        readOnly: true,
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
                                        id: auDescarga.id + '-barra',
                                        fieldLabel: 'Barra',
                                        width: 300,
                                        labelWidth: 45,
                                        allowBlank: false,
                                        enableKeyEvents: true,
                                        listeners:{
                                            keypress: function(obj, e, opts){
                                                if (e.getKey() == 13){
                                                    auDescarga.get_barra();
                                                }
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'textfield',
                                        id: auDescarga.id + '-tot_auditoria',
                                        fieldLabel: 'Total en auditoria',
                                        width: 180,
                                        labelWidth: 120,
                                        readOnly: true,
                                        labelAlign: 'right'
                                    },
                                    {
                                        xtype: 'textfield',
                                        id: auDescarga.id + '-pendiente',
                                        fieldLabel: 'Pendiente',
                                        width: 130,
                                        labelWidth: 70,
                                        readOnly: true,
                                        labelAlign: 'right'
                                    },
                                    {
                                        xtype: 'checkboxgroup',
                                        id: auDescarga.id + '-chk_group',
                                        vertical: true,
                                        columns: 1,
                                        hideLabel: true,
                                        items:[
                                            {boxLabel: 'Auditoria deficiente', name: auDescarga.id + '-chk', id: auDescarga.id + '-chk01', inputValue: '1', width: 150}
                                        ],
                                        listeners:{
                                            change: function(obj, newValue, oldValue){
                                                if (newValue['auDescarga-chk'] != undefined)
                                                    Ext.getCmp(auDescarga.id + '-au_deficiente').show();
                                                else
                                                    Ext.getCmp(auDescarga.id + '-au_deficiente').hide();
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'combo',
                                        id: auDescarga.id + '-au_deficiente',
                                        hidden: true,
                                        store: Ext.create('Ext.data.Store',{
                                            fields:[
                                                {name: 'chk_id', type: 'string'},
                                                {name: 'chk', type: 'string'},
                                                {name: 'chk_descri', type: 'string'}
                                            ],
                                            proxy:{
                                                type: 'ajax',
                                                url: auDescarga.url + 'get_scm_lista_chk_motivos/',
                                                reader:{
                                                    type: 'json',
                                                    root: 'data'
                                                }
                                            }
                                        }),
                                        queryMode: 'local',
                                        triggerAction: 'all',
                                        valueField: 'chk_id',
                                        displayField: 'chk_descri',
                                        listConfig:{
                                            minWidth: 200
                                        },
                                        emptyText: '[ Seleccione ]',
                                        listeners:{
                                            afterrender: function(obj){
                                                obj.getStore().load({
                                                    params:{
                                                        vp_chk_id: 65,
                                                        vp_proceso: '0'
                                                    }
                                                });
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
                                id: auDescarga.id+'-grid',
                                store: store,
                                columnLines: true,
                                // layout: 'hbox',
                                columns:{
                                    items:[
                                        {
                                            text: 'Nº Reclamo',
                                            dataIndex: 'reclamo',
                                            width: 80
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
                                            width: 100
                                        },
                                        {
                                            text: 'Estado',
                                            dataIndex: 'estado',
                                            width: 70,
                                            align: 'center',
                                            renderer:function(val, metaData, record, rowIndex, colIndex, store, view){
                                                var html = '<div class="gk-div-circle-status">';
                                                if (parseInt(val) == 0)
                                                    html+= '<span class="circle-red"></span>';
                                                else if(parseInt(val) == 1)
                                                    html+= '<span class="circle-green"></span>';
                                                html+= '</div>';
                                                return html;
                                            }
                                        }
                                    ],
                                    defaults:{
                                        menuDisabled: true,
                                        sortable: false
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
                    }
                ],
                listeners:{
                    afterrender: function(obj){
                        Ext.Ajax.request({
                            url: auDescarga.url + 'get_scm_reclamo_audi_cabecera/',
                            params:{
                                vp_prov: auDescarga.vp_prov,
                                vp_man_id: auDescarga.vp_man_id
                            },
                            success: function(response, options){
                                var res = Ext.JSON.decode(response.responseText);
                                // console.log(res);
                                Ext.getCmp(auDescarga.id + '-id').setValue(res.man_id);
                                Ext.getCmp(auDescarga.id + '-fecha').setValue(res.fec_man);
                                Ext.getCmp(auDescarga.id + '-auditor').setValue(res.auditor);
                                Ext.getCmp(auDescarga.id + '-tot_auditoria').setValue(res.tot_ld);
                                Ext.getCmp(auDescarga.id + '-pendiente').setValue(res.tot_pendiente);
                                Ext.getCmp(auDescarga.id + '-barra').focus(true, 500);
                                auDescarga.load_grid();
                            }
                        });
                    }
                }
            });

            tab.add({
                id: auDescarga.id+'-tab',
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
                        Ext.getCmp(aureclamo.id+'-tab').disable();
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                    },
                    beforeclose: function(obj, opts){
                        Ext.getCmp(aureclamo.id+'-tab').enable();
                        Ext.getCmp(inicio.id+'-tabContent').setActiveTab(Ext.getCmp(aureclamo.id+'-tab'))
                    }
                }
            }).show();
        },
        load_grid: function(){
            var grid = Ext.getCmp(auDescarga.id+'-grid');
            var store = grid.getStore();

            store.load({
                params:{
                    vp_prov: auDescarga.vp_prov,
                    vp_man_id: auDescarga.vp_man_id
                },
                callback: function(){

                }
            });
        },
        get_barra: function(){
            var form = Ext.getCmp(auDescarga.id + '-form').getForm();
            if (form.isValid()){
                var vp_barra = Ext.getCmp(auDescarga.id + '-barra').getValue();

                var vp_chk_id = 0;
                if (Ext.getCmp(auDescarga.id + '-chk01').getValue()){
                    vp_chk_id = Ext.getCmp(auDescarga.id + '-au_deficiente').getValue();
                    vp_chk_id = vp_chk_id == null ? 0 : vp_chk_id;
                    if (vp_chk_id == 0){
                        global.Msg({
                            msg: 'Debe de seleccionar el motivo de la auditoria deficiente!',
                            icon: 2,
                            buttons: 1,
                            fn: function(btn){
                                Ext.getCmp(auDescarga.id + '-au_deficiente').focus(true, 500);
                            }
                        });
                        return false;
                    }
                }

                Ext.getCmp(auDescarga.id+'-tab').el.mask('Cargando…', 'x-mask-loading');
                Ext.Ajax.request({
                    url: auDescarga.url + 'set_scm_reclamo_audi_verifica/',
                    params:{
                        vp_prov: auDescarga.vp_prov,
                        vp_man_id: auDescarga.vp_man_id,
                        vp_barra: vp_barra,
                        vp_chk_id: vp_chk_id
                    },
                    success: function(response, options){
                        Ext.getCmp(auDescarga.id+'-tab').el.unmask();
                        var res = Ext.JSON.decode(response.responseText);
                        // console.log(res);
                        if (parseInt(res.error_sql) >= 0 ){
                            Ext.getCmp(auDescarga.id + '-barra').focus(true, 500);
                            auDescarga.load_grid();
                        }else{
                            global.Msg({
                                msg: res.error_info,
                                icon: 0,
                                buttons: 1,
                                fn: function(btn){
                                    Ext.getCmp(auDescarga.id + '-barra').focus(true, 500);
                                }
                            });
                        }
                    }
                });
            }
        }
    }
    Ext.onReady(auDescarga.init, auDescarga);
}else{
    tab.setActiveTab(auDescarga.id+'-tab');
}
</script>