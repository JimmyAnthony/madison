<script type="text/javascript">
    var panel_asignar_gestion = {
        myMask: [],
        id: 'panel_asignar_gestion',
        url: '/postal/GestionDevolucion/',
        orden:0,
        agencia:0,
        indexRow:'0',
        guarda:0,
        init:function(){
            Ext.Ajax.timeout = 580000;

            var form = Ext.widget({
                    xtype: 'panel',
                    layout: 'border',
                    border:false,
                    frame:true,
                    id: 'innerTabsForm',
                    collapsible: false,
                    bodyPadding: 5,
                    fieldDefaults: {
                    labelAlign: 'top',
                    msgTarget: 'side'
                    },
                    defaults: {
                    anchor: '100%'
                    },
                    items: [
                        {
                            region:'north',
                            border:false,
                            //frame:true,
                            height:130,
                            layout:'fit',
                            items:[
                                {
                                    xtype: 'fieldset',
                                    title: 'Datos del Servicio',
                                    style:{
                                        margin: '2px',
                                        padding: '2px'
                                    },
                                    collapsible: false,
                                    collapsed: false,
                                    border: true,
                                    frame: true,
                                    defaults:{
                                        border: false,
                                        bodyStyle: 'background: transparent',
                                    },
                                    items:[
                                        {
                                            xtype:'panel',
                                            border:false,
                                            bodyStyle: 'background: transparent',
                                            padding:'0px 0px 4px 0px',
                                            layout:'column',
                                            items: [
                                                {
                                                    columnWidth: 1,border:false,
                                                    padding:'0px 2px 0px 0px',bodyStyle: 'background: transparent',
                                                    items:[
                                                        {
                                                            xtype: 'textfield',
                                                            id:panel_asignar_gestion.id+'-detalle',
                                                            fieldLabel: 'Detalle',
                                                            labelWidth:110,
                                                            width: '100%',
                                                            anchor: '100%',
                                                            readOnly: true
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            xtype:'panel',
                                            border:false,
                                            bodyStyle: 'background: transparent',
                                            padding:'0px 0px 4px 0px',
                                            layout:'column',
                                            items: [
                                                {
                                                    columnWidth: .50,border:false,
                                                    padding:'0px 2px 0px 0px',bodyStyle: 'background: transparent',
                                                    items:[
                                                        {
                                                            xtype: 'textfield',
                                                            id:panel_asignar_gestion.id+'-tot-dv',
                                                            fieldLabel: 'Total DV',
                                                            labelWidth:110,
                                                            width: '100%',
                                                            anchor: '100%',
                                                            readOnly: true
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            xtype:'panel',
                                            border:false,
                                            bodyStyle: 'background: transparent',
                                            padding:'0px 0px 4px 0px',
                                            layout:'column',
                                            items: [
                                                {
                                                    columnWidth: .50,border:false,
                                                    padding:'0px 2px 0px 0px',bodyStyle: 'background: transparent',
                                                    items:[
                                                        {
                                                            xtype: 'textfield',
                                                            id:panel_asignar_gestion.id+'-tot-gestionar',
                                                            fieldLabel: 'Total por Gestionar',
                                                            labelAlign: 'left',
                                                            labelWidth:110,
                                                            width: '100%',
                                                            anchor: '100%',
                                                            readOnly: true
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            region: 'center',
                            border:false,
                            //frame:true,
                            layout:'fit',
                            items:[
                                {
                                    xtype: 'fieldset',
                                    title: 'Asignar',
                                    style:{
                                        margin: '2px',
                                        padding: '2px'
                                    },
                                    collapsible: false,
                                    collapsed: false,
                                    border: true,
                                    frame: true,
                                    defaults:{
                                        border: false,
                                        bodyStyle: 'background: transparent',
                                    },
                                    items:[
                                        {
                                            xtype:'panel',
                                            border:false,
                                            bodyStyle: 'background: transparent',
                                            padding:'0px 0px 4px 0px',
                                            layout:'column',
                                            items: [
                                                {
                                                    columnWidth: 1,border:false,
                                                    padding:'0px 2px 0px 0px',bodyStyle: 'background: transparent',
                                                    items:[
                                                        {
                                                            xtype: 'combo',
                                                            id:panel_asignar_gestion.id+'-gestionista',
                                                            fieldLabel: 'Gestionista',
                                                            //hideLabel: true,
                                                            store: Ext.create('Ext.data.Store',{
                                                                fields:[
                                                                    {name: 'ges_id', type: 'int'},
                                                                    {name: 'gestionista', type: 'string'}
                                                                ],
                                                                proxy:{
                                                                    type: 'ajax',
                                                                    url: panel_asignar_gestion.url + 'get_scm_call_gestionistas',
                                                                    reader:{
                                                                        type: 'json',
                                                                        rootProperty: 'data'
                                                                    }
                                                                }
                                                            }),
                                                            queryMode: 'local',
                                                            triggerAction: 'all',
                                                            valueField: 'ges_id',
                                                            displayField: 'gestionista',
                                                            width: '100%',
                                                            anchor: '100%',
                                                            labelWidth: 100,
                                                            /*listConfig:{
                                                                minWidth: 200
                                                            },*/
                                                            emptyText: '[ Seleccione Origen ]',
                                                            listeners: {
                                                                afterrender: function(obj, e){
                                                                    obj.getStore().load({
                                                                        params:{

                                                                        },
                                                                        callback: function(){

                                                                        }
                                                                    });
                                                                },
                                                                select: function(obj, records, opts){
                                                                    panel_asignar_gestion.total_asignado(obj.getValue('ges_id'));
                                                                }
                                                            }
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            xtype:'panel',
                                            border:false,
                                            bodyStyle: 'background: transparent',
                                            padding:'0px 0px 4px 0px',
                                            layout:'column',
                                            items: [
                                                {
                                                    columnWidth: .50,border:false,
                                                    padding:'0px 2px 0px 0px',bodyStyle: 'background: transparent',
                                                    items:[
                                                        {
                                                            xtype: 'textfield',
                                                            id:panel_asignar_gestion.id+'-tot-asignado',
                                                            fieldLabel: 'Total Asignado',
                                                            labelWidth:100,
                                                            width: '100%',
                                                            anchor: '100%',
                                                            readOnly: true
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            xtype:'panel',
                                            border:false,
                                            bodyStyle: 'background: transparent',
                                            padding:'0px 0px 4px 0px',
                                            layout:'column',
                                            items: [
                                                {
                                                    columnWidth: .50,border:false,
                                                    padding:'0px 2px 0px 0px',bodyStyle: 'background: transparent',
                                                    items:[
                                                        {
                                                            xtype: 'textfield',
                                                            id:panel_asignar_gestion.id+'-espacio',
                                                            fieldLabel: 'Espacio',
                                                            labelAlign: 'left',
                                                            labelWidth:100,
                                                            width: '100%',
                                                            anchor: '100%',
                                                            readOnly: true
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            xtype:'panel',
                                            border:false,
                                            bodyStyle: 'background: transparent',
                                            padding:'0px 0px 4px 0px',
                                            layout:'column',
                                            items: [
                                                {
                                                    columnWidth: .50,border:false,
                                                    padding:'0px 2px 0px 0px',bodyStyle: 'background: transparent',
                                                    items:[
                                                        {
                                                            xtype: 'numberfield',
                                                            id:panel_asignar_gestion.id+'-tot_asignar',
                                                            fieldLabel: 'Total Asignar',
                                                            labelAlign: 'left',
                                                            labelWidth:100,
                                                            width: '100%',
                                                            anchor: '100%'
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        }
                    ],
                    bbar:[
                        '-',
                        {
                            xtype:'button',
                            text: 'Nuevo',
                            icon: '/images/icon/actualizar.png',
                            listeners:{
                                beforerender: function(obj, opts){
                                    global.permisos({
                                        id: 15,
                                        id_btn: obj.getId(), 
                                        id_menu: gestion_devolucion.id_menu,
                                        fn: ['panel_asignar_gestion.limpiar']
                                    });
                                },
                                click: function(obj, e){
                                    panel_asignar_gestion.limpiar();
                                }
                            }
                        },
                        '-',
                        '->',
                        '-',
                        {
                            xtype:'button',
                            text: 'Grabar',
                            icon: '/images/icon/save.png',
                            listeners:{
                                beforerender: function(obj, opts){
                                    global.permisos({
                                        id: 13, 
                                        id_btn: obj.getId(), 
                                        id_menu: gestion_devolucion.id_menu,
                                        fn: ['panel_asignar_gestion.guardar']
                                    });
                                },
                                click: function(obj, e){
                                    panel_asignar_gestion.guardar();
                                }
                            }
                        },
                        '-',
                        {
                            xtype:'button',
                            text: 'Salir',
                            icon: '/images/icon/get_back.png',
                            listeners:{
                                beforerender: function(obj, opts){
                                    
                                },
                                click: function(obj, e){
                                    panel_asignar_gestion.salir();
                                }
                            }
                        },
                        '-'
                    ]
                });
            

                Ext.create('Ext.window.Window',{
                    id:panel_asignar_gestion.id+'-win',
                    title:'ASIGNAR A CALL CENTER',
                    icon: '/images/icon/telephone_bg.png',
                    height: 380,
                    width: 515,
                    resizable:false,
                    layout:{
                        type:'fit'
                    },
                    modal: true,
                    closable:false,
                    items:[form],
                    listeners:{
                        'afterrender':function(obj, e){ 
                            panel_asignar_gestion.getDatos();
                        },
                        'close':function(){
                            if(panel_asignar_gestion.guarda!=0)gestion_devolucion.buscar();
                        }
                    }
                }).show().center();
        },
        getDatos:function(){
            var selectionModel = Ext.getCmp(<?php echo $_REQUEST["obj"];?>.id+'-grid').getSelectionModel();
            var selectedRecords = selectionModel.getSelection();
            gestion_devolucion.orden=selectedRecords[0].get('orden');
            gestion_devolucion.agencia=selectedRecords[0].get('agencia');
            var descr = selectedRecords[0].get('des_detalle');
            var tot_dv = selectedRecords[0].get('total_dv');
            var tot_por_gt = selectedRecords[0].get('pendiente_gt');
            Ext.getCmp(panel_asignar_gestion.id+'-detalle').setValue(descr);
            Ext.getCmp(panel_asignar_gestion.id+'-tot-dv').setValue(tot_dv);
            Ext.getCmp(panel_asignar_gestion.id+'-tot-gestionar').setValue(tot_por_gt);
        },
        guardar:function(){
            var vp_ges_id=Ext.getCmp(panel_asignar_gestion.id+'-gestionista').getValue();
            var vp_tot_asignar=Ext.getCmp(panel_asignar_gestion.id+'-tot_asignar').getValue();
            var vp_orden = gestion_devolucion.orden;
            var vp_agencia = gestion_devolucion.agencia;
            var por_gestionar = Ext.getCmp(panel_asignar_gestion.id+'-tot-gestionar').getValue();
            var espacio = Ext.getCmp(panel_asignar_gestion.id+'-espacio').getValue();
            if(vp_ges_id== 0 || vp_ges_id==''){
                Ext.Msg.alert("Urbano","Seleccione un gestionista por favor.");
                return false; 
            }

            if(vp_tot_asignar== 0 || vp_tot_asignar==''){
                Ext.Msg.alert("Urbano","Cantidad de asignación es incorrecta.");
                return false; 
            }

            if(vp_tot_asignar>por_gestionar){
                Ext.Msg.alert("Urbano","Cantidad de asignación no debe superar la cantidad por gestionar.");
                return false; 
            }

            if(vp_tot_asignar>espacio){
                Ext.Msg.alert("Urbano","Cantidad de asignación no debe superar el espacio por gestionar.");
                return false;
            }

            if(vp_orden== 0 || vp_orden==''){
                Ext.Msg.alert("Urbano","El registro seleccionado no cuenta con número de orden.");
                return false; 
            }

            global.Msg({
                msg: 'Seguro de Asignar?',
                icon: 3,
                buttons: 3,
                fn: function(btn){
                    if (btn == 'yes'){
                        Ext.Ajax.request({                                      
                            url:panel_asignar_gestion.url+'set_pcn_call_asignar_gestionista',
                            params:{vp_orden:vp_orden,vp_agencia:vp_agencia,vp_ges_id:vp_ges_id,vp_tot_asignar:vp_tot_asignar},
                            success:function(response,options){

                                var res = Ext.decode(response.responseText);
                                var res = res.data[0];
                                if(parseInt(res.error_sql)<0){
                                                                                           
                                    global.Msg({
                                        msg:res.error_info,
                                        icon:0,
                                        fn:function(){
                                                
                                        }
                                    });

                                }else{
                                    global.Msg({
                                        msg:res.error_info,
                                        icon:1,
                                        fn:function(){
                                            panel_asignar_gestion.limpiar();
                                            panel_asignar_gestion.guarda=1;
                                        }
                                    });
                                    

                                }
                                                
                            }
                        });
                    }
                }
            });
        },
        total_asignado:function(ges_id){
            Ext.getCmp(panel_asignar_gestion.id+'-tot-asignado').setValue(0);
            Ext.getCmp(panel_asignar_gestion.id+'-espacio').setValue(0);
            Ext.Ajax.request({
                url:panel_asignar_gestion.url+'get_scm_call_tot_asig_gestionista',
                params:{vp_ges_id:ges_id},
                success:function(response,options){
                    var res = Ext.decode(response.responseText);
                    //console.log(res);
                    Ext.getCmp(panel_asignar_gestion.id+'-tot-asignado').setValue(res.data[0].tot_asignado);
                    Ext.getCmp(panel_asignar_gestion.id+'-espacio').setValue(res.data[0].tot_espacio);
                }
            });
        },
        limpiar:function(){
            Ext.getCmp(panel_asignar_gestion.id+'-gestionista').setValue('');
            Ext.getCmp(panel_asignar_gestion.id+'-tot-asignado').setValue(0);
            Ext.getCmp(panel_asignar_gestion.id+'-espacio').setValue(0);
            Ext.getCmp(panel_asignar_gestion.id+'-tot_asignar').setValue(0);
        },
        salir:function(){
            Ext.getCmp(panel_asignar_gestion.id+'-win').close();
            if(panel_asignar_gestion.guarda!=0)gestion_devolucion.buscar();
        }
    }
    Ext.onReady(panel_asignar_gestion.init, panel_asignar_gestion);
</script>