<script type="text/javascript">


var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('re_impresion-tab')){
    var re_impresion = {
        id: 're_impresion',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/re_impresion/',
        id_contacto:'<?php echo REMITENTE_ID;?>',
        cli_id:0,
        firstGE:0,//507
        countGE:0,
        init:function(){

            var shipper_= Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'shi_codigo', type: 'int'},
                    {name: 'shi_nombre', type: 'string'},
                    {name: 'shi_id', type: 'string'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',
                    url: re_impresion.url+'get_scm_shipper/',
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
           var tipo_servicio = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id_orden', type: 'int'},
                    {name: 'pro_id', type: 'int'},
                    {name: 'descripcion', type: 'string'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',
                    url: re_impresion.url+'get_scm_tipo_servicios/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        var count = parseInt(obj.getCount());
                        if(count==1){
                            Ext.getCmp(re_impresion.id+'-tipo-servicio').setValue(records[0].data.id_orden);
                        }
                    }
                }
            });

           var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'gui_numero', type: 'int'},
                    {name: 'shipper', type: 'string'},
                    {name: 'servicio', type: 'string'},
                    {name: 'gui_numero', type: 'string'},                    
                    {name: 'cod_rastreo', type: 'string'},
                    {name: 'doc_numero', type: 'string'},
                    {name: 'cli_nombre', type: 'string'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',
                    url: re_impresion.url+'get_scm_lista_ge/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        var count = parseInt(obj.getCount());
                        if(count==1){
                            Ext.getCmp(re_impresion.id+'-tipo-servicio').setValue(records[0].data.id_orden);
                        }
                    }
                }
            });

            var provincias_ = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'prov_codigo', type: 'int'},
                    {name: 'prov_nombre', type: 'string'},
                    {name: 'prov_sigla', type: 'string'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',

                    url: re_impresion.url+'get_scm_provincias/',
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
            //console.log(imageTplPointerPanel);
            var panel = Ext.create('Ext.form.Panel',{
                id: re_impresion.id+'-form',
                border:false,
                layout: 'border',
                defaults:{
                    border: false
                },
                tbar:[
                    '-',
                    {
                        xtype:'button',
                        text: 'Buscar',
                        icon: '/images/icon/binocular.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                /*global.permisos({
                                    id: 15,
                                    id_btn: obj.getId(), 
                                    id_menu: gestion_devolucion.id_menu,
                                    fn: ['panel_asignar_gestion.limpiar']
                                });*/
                            },
                            click: function(obj, e){
                                re_impresion.buscar_ge();
                            }
                        }
                    },
                    '-',
                    {
                        xtype:'button',
                        text: 'Imprimir',
                        icon: '/images/icon/pdf.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                /*global.permisos({
                                    id: 15,
                                    id_btn: obj.getId(), 
                                    id_menu: gestion_devolucion.id_menu,
                                    fn: ['panel_asignar_gestion.limpiar']
                                });*/
                            },
                            click: function(obj, e){
                                re_impresion.printGE();
                            }
                        }
                    },
                    '-',
                    {
                        xtype:'button',
                        text: 'Limpiar',
                        icon: '/images/icon/new_file.ico',
                        listeners:{
                            beforerender: function(obj, opts){
                                /*global.permisos({
                                    id: 15,
                                    id_btn: obj.getId(), 
                                    id_menu: gestion_devolucion.id_menu,
                                    fn: ['panel_asignar_gestion.limpiar']
                                });*/
                            },
                            click: function(obj, e){
                                re_impresion.limpiar();
                            }
                        }
                    },
                    '-'
                ],
                items:[
                    {
                        region:'north',
                        //frame:true,
                        height:92,
                        bodyStyle: 'background: transparent',
                        border:true,
                        split:false,
                        layout:'fit',
                        items:[
                            {
                                region:'north',
                                border:false,
                                xtype: 'uePanelS',
                                logo: 'RE',
                                title: 'Re-Impresión de Guías',
                                legend: 'Búsqueda de GE registradas',
                                height:255,
                                items:[
                                    {
                                        xtype:'panel',
                                        border:false,
                                        bodyStyle: 'background: transparent',
                                        padding:'2px 5px 1px 5px',
                                        layout:'column',
                                        items: [
                                            {
                                                columnWidth: 0.20,border:false,
                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                items:[
                                                    {
                                                        xtype:'combo',
                                                        fieldLabel: 'Agencia',
                                                        id:re_impresion.id+'-agencia',
                                                        store: provincias_,
                                                        queryMode: 'local',
                                                        triggerAction: 'all',
                                                        valueField: 'prov_codigo',
                                                        displayField: 'prov_nombre',
                                                        emptyText: '[Seleccione]',
                                                        labelAlign:'right',
                                                        //allowBlank: false,
                                                        labelWidth: 60,
                                                        width:'100%',
                                                        anchor:'100%',
                                                        //readOnly: true,
                                                        listeners:{
                                                            afterrender:function(obj, e){
                                                                
                                                                obj.getStore().load({
                                                                    params: {
                                                                        
                                                                    },
                                                                    callback: function(records, operation, success) {
                                                                        var count = parseInt(obj.getStore().getCount());
                                                                        if(count>0){
                                                                            obj.setValue(parseInt('<?php echo PROV_CODIGO; ?>'));
                                                                        }
                                                                    },
                                                                    scope: this
                                                                });
                                                            },
                                                            select:function(obj, records, eOpts){
                                                                
                                                            }
                                                        }
                                                    }
                                                ]
                                            },
                                            {
                                                columnWidth: 0.25,border:false,
                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                items:[
                                                    {
                                                        xtype:'combo',
                                                        fieldLabel: 'Shipper',
                                                        id:re_impresion.id+'-shipper',
                                                        store: shipper_,
                                                        queryMode: 'local',
                                                        triggerAction: 'all',
                                                        valueField: 'shi_codigo',
                                                        displayField: 'shi_nombre',
                                                        emptyText: '[Seleccione]',
                                                        labelAlign:'right',
                                                        //allowBlank: false,
                                                        labelWidth: 60,
                                                        width:'100%',
                                                        anchor:'100%',
                                                        //readOnly: true,
                                                        listeners:{
                                                            afterrender:function(obj, e){
                                                                obj.getStore().load({
                                                                    params: {
                                                                        
                                                                    },
                                                                    callback: function(records, operation, success) {
                                                                        var count = parseInt(obj.getStore().getCount());
                                                                        if(count>0){
                                                                            var shi_codigo = parseInt('<?php echo SHI_CODIGO; ?>');
                                                                            if(shi_codigo!=0 || shi_codigo!='' || shi_codigo!=null){
                                                                                obj.setValue(shi_codigo);
                                                                                re_impresion.select_shipper(obj);
                                                                            }
                                                                        }
                                                                    },
                                                                    scope: this
                                                                });
                                                            },
                                                            select:function(obj, records, eOpts){
                                                                re_impresion.select_shipper(obj);
                                                            }
                                                        }
                                                    }
                                                ]
                                            },
                                            {
                                                columnWidth: 0.25,border:false,
                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                items:[
                                                    {
                                                        xtype:'combo',
                                                        fieldLabel: 'Tipo Servicio',
                                                        id:re_impresion.id+'-tipo-servicio',
                                                        store: tipo_servicio,
                                                        queryMode: 'local',
                                                        triggerAction: 'all',
                                                        valueField: 'id_orden',
                                                        displayField: 'descripcion',
                                                        emptyText: '[Seleccione]',
                                                        labelAlign:'right',
                                                        //allowBlank: false,
                                                        labelWidth: 80,
                                                        width:'100%',
                                                        anchor:'100%',
                                                        //readOnly: true,
                                                        listeners:{
                                                            afterrender:function(obj, e){
                                                                // obj.getStore().load();
                                                            },
                                                            select:function(obj, records, eOpts){
                                                    
                                                            }
                                                        }
                                                    }
                                                ]
                                            },
                                            {
                                                columnWidth: 0.15,border:false,
                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                items:[
                                                    {
                                                        xtype:'datefield',
                                                        id:re_impresion.id+'-date-re',
                                                        fieldLabel:'Fecha',
                                                        labelWidth:60,
                                                        labelAlign:'right',
                                                        value:new Date(),
                                                        width: '100%',
                                                        anchor:'100%'
                                                    }
                                                ]
                                            },
                                            {
                                                columnWidth: 0.15,border:false,
                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                items:[
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Guía Envío',
                                                        id:re_impresion.id+'-guie-envio',
                                                        labelWidth:80,
                                                        //readOnly:true,
                                                        labelAlign:'right',
                                                        width:'100%',
                                                        anchor:'100%'
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
                        xtype:'panel',
                        region:'center',bodyStyle: 'background: transparent',
                        layout:'fit',
                        items:[
                           {
                                xtype: 'grid',
                                id: re_impresion.id+'-acuse',
                                store: store,
                                columnLines: true,
                                selModel: Ext.create("Ext.selection.CheckboxModel", {
                                    checkOnly : true,
                                    mode: 'SIMPLE'
                                }),
                                columns:{
                                    items:[
                                        {
                                            text: 'G.Envío',
                                            dataIndex: 'gui_numero',
                                            flex: 1,
                                            align: 'left'
                                        },
                                        {
                                            text: 'Shipper',
                                            dataIndex: 'shipper',
                                            flex: 1,
                                            align: 'left'
                                        },
                                        {
                                            text: 'Servicio',
                                            dataIndex: 'servicio',
                                            flex: 1,
                                            align: 'left'
                                        },
                                        {
                                            text: 'Nro. Documento',
                                            dataIndex: 'doc_numero',
                                            flex: 1,
                                            align: 'left'
                                        },
                                        {
                                            text: 'Documento Ref.',
                                            dataIndex: 'cod_rastreo',
                                            flex: 1,
                                            align: 'left'
                                        },
                                        {
                                            text: 'Consignatario',
                                            dataIndex: 'cli_nombre',
                                            flex: 1,
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
                                    afterrender:function( obj, eOpts ){
                                        /*var sm=obj.getSelectionModel();
                                        console.log(sm);
                                        sm.selectAll(true);*/
                                    }
                                }
                            }
                        ]
                    }
                ]
            });

            tab.add({
                id: re_impresion.id+'-tab',
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
                        global.state_item_menu(re_impresion.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        /*Ext.getCmp(re_impresion.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + re_impresion.id_menu).text,
                            icon: Ext.getCmp('menu-' + re_impresion.id_menu).icon
                        });*/
                        global.state_item_menu_config(obj,re_impresion.id_menu);
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(re_impresion.id_menu, false);
                    }
                }
            }).show();
        },
        printGE:function(){
            var records_ = new Array();
            var records = Ext.getCmp(re_impresion.id+'-acuse').getSelection();
            for (i=0; i<=records.length-1; i++) {
                records_.push(records[i].data.gui_numero);
            }
            var recordsx = Ext.encode(records_);
            console.log(recordsx);
            window.open( re_impresion.url + 'generate_pdf/?recordsx='+recordsx, '_blank');
        },
        select_shipper:function(obj){
            Ext.getCmp(re_impresion.id+'-tipo-servicio').setValue('');
            Ext.getCmp(re_impresion.id+'-tipo-servicio').getStore().removeAll();
            
            Ext.getCmp(re_impresion.id+'-tipo-servicio').getStore().load(
                {params: {vp_id_linea:3,vp_shi_codigo: obj.getValue('shi_codigo')},
                callback:function(){
                    
                }
            });
        },
        limpiar:function(){
            Ext.getCmp(re_impresion.id+'-guie-envio').setValue('');
            Ext.getCmp(re_impresion.id+'-tipo-servicio').setValue('');
        },
        buscar_ge:function(){
            var shi_codigo = Ext.getCmp(re_impresion.id+'-shipper').getValue();
            var tipo_servicio = Ext.getCmp(re_impresion.id+'-tipo-servicio').getValue();
            var agencia = Ext.getCmp(re_impresion.id+'-agencia').getValue();
            var fecha = Ext.getCmp(re_impresion.id+'-date-re').getRawValue();
            var guia = Ext.getCmp(re_impresion.id+'-guie-envio').getValue();

            Ext.getCmp(re_impresion.id+'-acuse').getStore().removeAll();
            
            Ext.getCmp(re_impresion.id+'-acuse').getStore().load(
                {params: {vp_shi_codigo:shi_codigo,vp_tipo_servicio: tipo_servicio,vp_codsuc: agencia,vp_fecha:fecha,vp_gui_numero:guia},
                callback:function(){
                    
                }
            });
        }
    }
    Ext.onReady(re_impresion.init, re_impresion);
}else{
    tab.setActiveTab(re_impresion.id+'-tab');
}
</script>
