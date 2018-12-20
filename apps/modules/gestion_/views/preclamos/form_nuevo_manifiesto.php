<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('gManifiesto-tab')){
    var gManifiesto = {
        id: 'gManifiesto',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/preclamos/',
        accion: '<?php echo $p["accion"];?>',
        man_id: parseInt('<?php echo $p["vp_man_id"];?>'),
        init:function(){

            var panel = Ext.create('Ext.form.Panel',{
                id: gManifiesto.id + '-form',
                border: false,
                defaults:{
                    style: {
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
                                id: gManifiesto.id + '-fecha',
                                fieldLabel: 'Fecha Auditoria',
                                labelWidth: 90,
                                readOnly: true,
                                width: 170
                            },
                            {
                                xtype: 'combo',
                                id: gManifiesto.id + '-auditor',
                                fieldLabel: 'Auditor',
                                labelWidth: 70,
                                labelAlign: 'right',
                                flex: 1,
                                allowBlank: false,
                                store: Ext.create('Ext.data.Store',{
                                    fields:[
                                        {name: 'id_per', type: 'int'},
                                        {name: 'codigo', type: 'int'},
                                        {name: 'nombres', type: 'string'}
                                    ],
                                    proxy:{
                                        type: 'ajax',
                                        url: gManifiesto.url + 'get_scm_lista_personal/',
                                        reader:{
                                            type: 'json',
                                            root: 'data'
                                        }
                                    }
                                }),
                                queryMode: 'local',
                                triggerAction: 'all',
                                valueField: 'id_per',
                                displayField: 'nombres',
                                listConfig:{
                                    minWidth: 350
                                },
                                emptyText: '[ Seleccione ]',
                                listeners:{
                                    afterrender: function(obj){
                                        obj.getStore().load({
                                            params:{
                                                vp_linea: '0',
                                                vp_cargo: '0'
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
                        items:[
                            {
                                xtype: 'textfield',
                                id: gManifiesto.id + '-man_id',
                                fieldLabel: 'Id Manifiesto',
                                labelWidth: 90,
                                readOnly: true,
                                width: 170
                            }
                        ]
                    },
                    {
                        xtype: 'panel',
                        layout: 'hbox',
                        items:[
                            {
                                xtype: 'textfield',
                                id: gManifiesto.id + '-barra',
                                fieldLabel: 'Barra reclamo',
                                labelWidth: 90,
                                flex: 1,
                                enableKeyEvents: true,
                                allowBlank: false,
                                listeners:{
                                    keypress: function(obj, e, opts){
                                        if (e.getKey() == 13){
                                            gManifiesto.get_barra();
                                        }
                                    }
                                }
                            },
                            {
                                xtype: 'textfield',
                                id: gManifiesto.id + '-tot_scaneado',
                                fieldLabel: 'Total Scaneado',
                                labelWidth: 110,
                                labelAlign: 'right',
                                value: 0,
                                readOnly: true,
                                width: 170
                            }
                        ]
                    },
                    {
                        xtype: 'fieldset',
                        title: 'Datos del reclamo',
                        style:{
                            padding: '2px',
                            margin: '2px'
                        },
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
                                        id: gManifiesto.id + '-shipper',
                                        fieldLabel: 'Shipper',
                                        labelWidth: 60,
                                        readOnly: true,
                                        flex: 1
                                    },
                                    {
                                        xtype: 'textfield',
                                        id: gManifiesto.id + '-ciclo',
                                        fieldLabel: 'Ciclo',
                                        labelAlign: 'right',
                                        labelWidth: 50,
                                        readOnly: true,
                                        width: 140
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                items:[
                                    {
                                        xtype: 'textfield',
                                        id: gManifiesto.id + '-servicio',
                                        fieldLabel: 'Servicio',
                                        labelWidth: 60,
                                        readOnly: true,
                                        flex: 1
                                    },
                                    {
                                        xtype: 'textfield',
                                        id: gManifiesto.id + '-codigo',
                                        fieldLabel: 'Código',
                                        labelAlign: 'right',
                                        labelWidth: 50,
                                        readOnly: true,
                                        width: 140
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                items:[
                                    {
                                        xtype: 'textfield',
                                        id: gManifiesto.id + '-nombre',
                                        fieldLabel: 'Nombre',
                                        labelWidth: 60,
                                        readOnly: true,
                                        flex: 1
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                items:[
                                    {
                                        xtype: 'textfield',
                                        id: gManifiesto.id + '-direccion',
                                        fieldLabel: 'Dirección',
                                        labelWidth: 60,
                                        readOnly: true,
                                        flex: 1
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                items:[
                                    {
                                        xtype: 'textfield',
                                        id: gManifiesto.id + '-distrito',
                                        fieldLabel: 'Distrito',
                                        labelWidth: 60,
                                        readOnly: true,
                                        flex: 1
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                items:[
                                    {
                                        xtype: 'textfield',
                                        id: gManifiesto.id + '-estado',
                                        fieldLabel: 'Estado',
                                        labelWidth: 60,
                                        readOnly: true,
                                        flex: 1
                                    }
                                ]
                            }
                        ]
                    }
                ],
                listeners:{
                    afterrender: function(obj){
                        if (gManifiesto.accion == 'nuevo'){
                            Ext.Ajax.request({
                                url: gManifiesto.url + 'get_scm_reclamo_audi_check/',
                                params:{},
                                success: function(response, options){
                                    var res = Ext.JSON.decode(response.responseText);
                                    res = res.data[0];
                                    console.log(res);
                                    if (parseInt(res.man_id) == 0){
                                        Ext.getCmp(gManifiesto.id + '-fecha').setValue('<?php echo date("d/m/Y");?>');
                                        Ext.getCmp(gManifiesto.id + '-man_id').setValue(0);
                                        Ext.getCmp(gManifiesto.id + '-tot_scaneado').setValue(0);
                                        Ext.getCmp(gManifiesto.id + '-barra').setValue('');
                                        Ext.getCmp(gManifiesto.id + '-auditor').focus(true, 500);
                                    }else{
                                        Ext.getCmp(gManifiesto.id + '-fecha').setValue(res.fec_man);
                                        Ext.getCmp(gManifiesto.id + '-auditor').setValue(parseInt(res.per_id));
                                        Ext.getCmp(gManifiesto.id + '-tot_scaneado').setValue(res.tot_ld);
                                        Ext.getCmp(gManifiesto.id + '-man_id').setValue(res.man_id);
                                        Ext.getCmp(gManifiesto.id + '-auditor').setReadOnly(true);
                                        Ext.getCmp(gManifiesto.id + '-barra').focus(true, 500);
                                    }
                                }
                            });
                        }else if(gManifiesto.accion == 'editar'){
                            var vp_man_id = gManifiesto.man_id;
                            Ext.getCmp(gManifiesto.id + '-win').el.mask('Cargando…', 'x-mask-loading');
                            Ext.Ajax.request({
                                url: gManifiesto.url + 'get_scm_reclamo_audi_editar/',
                                params:{
                                    vp_man_id: vp_man_id
                                },
                                success: function(response, options){
                                    Ext.getCmp(gManifiesto.id + '-win').el.unmask();
                                    var res = Ext.JSON.decode(response.responseText);
                                    res = res.data[0];
                                    // console.log(res.data[0]);
                                    Ext.getCmp(gManifiesto.id + '-fecha').setValue(res.fec_man);
                                    Ext.getCmp(gManifiesto.id + '-auditor').setValue(parseInt(res.per_id));
                                    Ext.getCmp(gManifiesto.id + '-tot_scaneado').setValue(res.tot_ld);
                                    Ext.getCmp(gManifiesto.id + '-man_id').setValue(res.man_id);
                                    Ext.getCmp(gManifiesto.id + '-auditor').setReadOnly(true);
                                    Ext.getCmp(gManifiesto.id + '-barra').focus(true, 500);
                                }
                            });
                        }
                    }
                }
            });

            Ext.create('Ext.window.Window',{
                id: gManifiesto.id + '-win',
                title: gManifiesto.accion == 'nuevo' ? 'Generar nuevo manifiesto' : 'Editar manifiesto',
                height: 310,
                width: 600,
                layout: 'fit',
                modal: true,
                resizable: false,
                items: panel,
                buttonAlign: 'center',
                buttons:[
                    {
                        text: 'Grabar',
                        icon: '/images/icon/save.png',
                        listeners:{
                            click: function(obj, e){
                                gManifiesto.save();
                            }
                        }
                    },
                    {
                        text: 'Cancelar',
                        icon: '/images/icon/cancel.png',
                        listeners:{
                            click: function(obj, e){
                                gManifiesto.anular();
                            }
                        }
                    }
                ]
            }).show().center();
        },
        get_barra: function(){
            var form = Ext.getCmp(gManifiesto.id + '-form').getForm();
            if (form.isValid()){
                var vp_per_id = Ext.getCmp(gManifiesto.id + '-auditor').getValue();
                var vp_fec_man = Ext.getCmp(gManifiesto.id + '-fecha').getValue();
                var vp_man_id = Ext.getCmp(gManifiesto.id + '-man_id').getValue();
                var vp_barra = Ext.getCmp(gManifiesto.id + '-barra').getValue();

                Ext.Ajax.request({
                    url: gManifiesto.url + 'get_barra/',
                    params:{
                        vp_per_id: vp_per_id,
                        vp_fec_man: vp_fec_man,
                        vp_man_id: vp_man_id,
                        vp_barra: vp_barra
                    },
                    success: function(response, options){
                        var res = Ext.JSON.decode(response.responseText);
                        // console.log(res);
                        if (parseInt(res.error_sql) >= 0 ){
                            Ext.getCmp(gManifiesto.id + '-man_id').setValue(res.man_id);

                            Ext.getCmp(gManifiesto.id + '-shipper').setValue(res.shipper);
                            Ext.getCmp(gManifiesto.id + '-ciclo').setValue(res.ciclo);
                            Ext.getCmp(gManifiesto.id + '-servicio').setValue(res.servicio);
                            Ext.getCmp(gManifiesto.id + '-codigo').setValue(res.codigo);
                            Ext.getCmp(gManifiesto.id + '-nombre').setValue(res.cliente);
                            Ext.getCmp(gManifiesto.id + '-direccion').setValue(res.direccion);
                            Ext.getCmp(gManifiesto.id + '-distrito').setValue(res.distrito);
                            Ext.getCmp(gManifiesto.id + '-estado').setValue(res.estado);
                            Ext.getCmp(gManifiesto.id + '-tot_scaneado').setValue(res.tot_escaneo);
                            Ext.getCmp(gManifiesto.id + '-barra').setValue('');
                            Ext.getCmp(gManifiesto.id + '-barra').focus(true, 500);
                        }else{
                            global.Msg({
                                msg: res.error_info,
                                icon: 0,
                                buttons: 1,
                                fn: function(btn){
                                    Ext.getCmp(gManifiesto.id + '-barra').setValue('');
                                    Ext.getCmp(gManifiesto.id + '-barra').focus(true, 500);
                                }
                            });
                        }
                    }
                });
            }else{
                global.Msg({
                    msg: 'Debe de completar los campos requeridos!',
                    icon: 2,
                    buttons: 1,
                    fn: function(btn){

                    }
                });
            }
        },
        save: function(){

            var vp_man_id = Ext.getCmp(gManifiesto.id + '-man_id').getValue();

            if (parseInt(vp_man_id) != 0){
                global.Msg({
                    msg: '¿Seguro de grabar?',
                    icon: 3,
                    buttons: 3,
                    fn: function(btn){
                        if (btn == 'yes'){
                            Ext.Ajax.request({
                                url: gManifiesto.url + 'set_scm_reclamo_audi_graba/',
                                params:{
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
                                                Ext.getCmp(gManifiesto.id + '-win').close();
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
            }else{
                global.Msg({
                    msg: 'No se ha scaneado ninguna barra!',
                    icon: 2,
                    buttons: 1,
                    fn: function(btn){
                        Ext.getCmp(gManifiesto.id + '-barra').setValue('');
                        Ext.getCmp(gManifiesto.id + '-barra').focus(true, 500);
                    }
                });
            }
        },
        anular: function(){
            var vp_man_id = Ext.getCmp(gManifiesto.id + '-man_id').getValue();

            if (parseInt(vp_man_id) != 0){
                global.Msg({
                    msg: '¿Seguro de anular?',
                    icon: 3,
                    buttons: 3,
                    fn: function(btn){
                        if (btn == 'yes'){
                            Ext.Ajax.request({
                                url: gManifiesto.url + 'set_scm_reclamo_audi_anular/',
                                params:{
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
                                                Ext.getCmp(gManifiesto.id + '-win').close();
                                                aureclamos.consultar();
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
            }else{
                global.Msg({
                    msg: 'No tienen ningún manifiesto por anular!',
                    icon: 2,
                    buttons: 1,
                    fn: function(btn){
                        Ext.getCmp(gManifiesto.id + '-barra').setValue('');
                        Ext.getCmp(gManifiesto.id + '-barra').focus(true, 500);
                    }
                });
            }
        }
    }
    Ext.onReady(gManifiesto.init, gManifiesto);
}else{
    tab.setActiveTab(gManifiesto.id+'-tab');
}
</script>