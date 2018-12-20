<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('solicitar_envios-tab')){
    var solicitar_envios = {
        id: 'solicitar_envios',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/solicitar_envios/',
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
                    url: solicitar_envios.url+'get_scm_shipper/',
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
                    url: solicitar_envios.url+'get_scm_tipo_servicios/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        var count = parseInt(obj.getCount());
                        if(count==1){
                            Ext.getCmp(solicitar_envios.id+'-tipo-servicio').setValue(records[0].data.id_orden);
                        }
                    }
                }
            });

            var tipo_doc = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id_elemento', type: 'int'},
                    {name: 'descripcion', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: solicitar_envios.url+'get_scm_detalle_tabla/',
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

            var centro_actividad = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id_agencia', type: 'int'},
                    {name: 'age_codigo', type: 'string'},
                    {name: 'agencia', type: 'string'},
                    {name: 'dir_calle', type: 'string'},
                    {name: 'ciu_iata', type: 'string'},
                    {name: 'distrito', type: 'string'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',
                    url: solicitar_envios.url+'get_scm_agencia_shipper/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        var count = parseInt(obj.getCount());
                        if(count==1){
                            Ext.getCmp(solicitar_envios.id+'-centro-actividad').setValue(records[0].data.id_agencia);
                            Ext.getCmp(solicitar_envios.id+'-direccion').setValue(records[0].data.dir_calle);
                            Ext.getCmp(solicitar_envios.id+'-distrito').setValue(records[0].data.distrito);
                            Ext.getCmp(solicitar_envios.id+'-remitente').setValue('');
                            Ext.getCmp(solicitar_envios.id+'-remitente').getStore().removeAll();
                            var shi_codigo = Ext.getCmp(solicitar_envios.id+'-shipper').getValue();

                            Ext.getCmp(solicitar_envios.id+'-remitente').getStore().load(
                                {params: {vp_id_agencia: records[0].data.id_agencia,vp_shi_codigo:shi_codigo},
                                callback:function(){
                                    
                                }
                            });
                        }
                    }
                }
            });
            var contacto = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id_contacto', type: 'int'},
                    {name: 'contacto', type: 'string'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',
                    url: solicitar_envios.url+'get_scm_contactos/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        var recordsx = new Array();
                        obj.each(function(rec){
                            if(rec.data.id_contacto==parseInt(solicitar_envios.id_contacto)){
                                Ext.getCmp(solicitar_envios.id+'-remitente').setValue(rec.data.id_contacto);
                                return;
                            }
                        });
                        
                    }
                }
            });
            var cliente_frecuente = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'cli_id', type: 'int'},
                    {name: 'cliente', type: 'string'},
                    {name: 'cli_codigo', type: 'string'},
                    {name: 'cli_nrodoc', type: 'string'},
                    {name: 'empresa', type: 'string'},
                    {name: 'dir_calle', type: 'string'},
                    {name: 'dir_num_via', type: 'string'},
                    {name: 'dir_referen', type: 'string'},
                    {name: 'dir_ciudad', type: 'string'},
                    {name: 'pue_numero', type: 'int'},
                    {name: 'pue_lote', type: 'string'},
                    {name: 'pue_cuadra', type: 'string'},
                    {name: 'dir_px', type: 'float'},
                    {name: 'dir_py', type: 'float'},
                    {name: 'via_nombre', type: 'string'},
                    {name: 'tipo_via', type: 'string'},
                    {name: 'man_nombre', type: 'string'},
                    {name: 'urb_nombre', type: 'string'},
                    {name: 'nombre_ubi', type: 'string'},
                    {name: 'tvia_id', type: 'int'},
                    {name: 'id_man', type: 'int'},
                    {name: 'id_urb', type: 'int'},
                    {name: 'id_via', type: 'int'},
                    {name: 'id_geo', type: 'int'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',
                    url: solicitar_envios.url+'get_scm_cliente_frecuente/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        var recordsx = new Array();
                        if(solicitar_envios.cli_id!=0){
                            obj.each(function(rec){
                                if(rec.data.cli_id==parseInt(solicitar_envios.cli_id)){
                                    solicitar_envios.setdatoCliente(rec);
                                    return;
                                }
                            });
                        }
                    }
                }
            });

            var producto_sku = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'sku_id', type: 'int'},
                    {name: 'sku_descri', type: 'string'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',
                    url: solicitar_envios.url+'get_scm_producto_sku/',
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

            this.ciudad = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'ciu_id', type: 'int'},
                    {name: 'ciu_ubigeo', type: 'string'},
                    {name: 'ciu_nomdis', type: 'string'},
                    {name: 'ciu_nompro', type: 'string'},
                    {name: 'ciu_nomdep', type: 'string'},
                    {name: 'zon_tipo', type: 'string'},
                    {name: 'ciu_iata', type: 'string'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',
                    url: solicitar_envios.url+'get_scm_ciudad/',
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

            this.generacion= Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'shipper', type: 'string'},
                    {name: 'servicio', type: 'string'},
                    {name: 'gui_numero', type: 'int'},
                    {name: 'gui_peso', type: 'float'},
                    {name: 'cantidad', type: 'int'},
                    {name: 'cli_nombre', type: 'string'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',
                    url: solicitar_envios.url+'get_scm_ss_lista_ge_generadas/',
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

            Ext.define('documento', {
                extend: 'Ext.data.Model',
                fields: [
                    { name: 'doc_numero', type: 'string' },
                    { name: 'tipo_doc', type: 'int' }
                ]
            });
            var store = Ext.create('Ext.data.Store',{
                autoDestroy: true,
                model: 'documento',
                proxy: {
                    type: 'memory'
                },
                sorters: [{
                    property: 'common',
                    direction: 'ASC'
                }]
            });
            
            var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
                clicksToMoveEditor: 1,
                autoCancel: false
            });
            //console.log(imageTplPointerPanel);
            var panel = Ext.create('Ext.form.Panel',{
                id: solicitar_envios.id+'-form',
                border:false,
                layout: 'border',
                defaults:{
                    border: false
                },
                tbar:[
                    '-',
                    {
                        xtype:'button',
                        text: 'Grabar',
                        icon: '/images/icon/save.png',
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
                                solicitar_envios.save_ss();
                            }
                        }
                    },
                    '-',
                    {
                        xtype:'button',
                        text: 'Nuevo',
                        icon: '/images/icon/add.png',
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
                                solicitar_envios.nuevo();
                            }
                        }
                    },
                    '-',
                    {
                        xtype:'button',
                        id:solicitar_envios.id+'-imprimir_ge',
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
                                solicitar_envios.showGeneracion();
                            }
                        }
                    },
                    '-'
                ],
                items:[
                    {
                        region:'west',
                        //frame:true,
                        width:540,
                        bodyStyle: 'background: transparent',
                        border:false,
                        split:false,
                        layout:'fit',
                        items:[
                            {
                                xtype:'panel',
                                layout:'border',
                                bodyStyle: 'background: transparent',
                                border:true,
                                items:[
                                    {
                                        region:'north',
                                        border:false,
                                        xtype: 'uePanelS',
                                        logo: 'SS',
                                        title: 'Solicitud de Servicio',
                                        legend: 'Nueva Guía de Envío',
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
                                                        columnWidth: 1,border:false,
                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                        items:[
                                                            {
                                                                xtype:'combo',
                                                                fieldLabel: 'Shipper',
                                                                id:solicitar_envios.id+'-shipper',
                                                                store: shipper_,
                                                                queryMode: 'local',
                                                                triggerAction: 'all',
                                                                valueField: 'shi_codigo',
                                                                displayField: 'shi_nombre',
                                                                emptyText: '[Seleccione]',
                                                                //allowBlank: false,
                                                                labelWidth: 80,
                                                                width:'100%',
                                                                anchor:'100%',
                                                                //readOnly: true,
                                                                listeners:{
                                                                    afterrender:function(obj, e){
                                                                        obj.getStore().load({
                                                                            params: {
                                                                                
                                                                            },
                                                                            callback: function(records, operation, success) {
                                                                                var shi_codigo = parseInt('<?php echo SHI_CODIGO; ?>');
                                                                                if(shi_codigo!=0 || shi_codigo!='' || shi_codigo!=null){
                                                                                    obj.setValue(shi_codigo);
                                                                                    solicitar_envios.selected_shi(obj);
                                                                                }
                                                                            },
                                                                            scope: this
                                                                        });
                                                                    },
                                                                    select:function(obj, records, eOpts){
                                                                        solicitar_envios.selected_shi(obj);
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
                                                padding:'2px 5px 1px 5px',
                                                layout:'column',
                                                items: [
                                                    {
                                                        columnWidth: 1,border:false,
                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                        items:[
                                                            {
                                                                xtype:'combo',
                                                                fieldLabel: 'Tipo Servicio',
                                                                id:solicitar_envios.id+'-tipo-servicio',
                                                                store: tipo_servicio,
                                                                queryMode: 'local',
                                                                triggerAction: 'all',
                                                                valueField: 'id_orden',
                                                                displayField: 'descripcion',
                                                                emptyText: '[Seleccione]',
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
                                                    }
                                                ]
                                            },
                                            {
                                                xtype:'panel',
                                                border:false,
                                                bodyStyle: 'background: transparent',
                                                padding:'2px 5px 1px 5px',
                                                layout:'column',
                                                items: [
                                                    {
                                                        columnWidth: 1,border:false,
                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                        items:[
                                                            {
                                                                xtype:'combo',
                                                                fieldLabel: 'Centro de Actividad',
                                                                id:solicitar_envios.id+'-centro-actividad',
                                                                store: centro_actividad,
                                                                queryMode: 'local',
                                                                triggerAction: 'all',
                                                                valueField: 'id_agencia',
                                                                displayField: 'agencia',
                                                                emptyText: '[Seleccione]',
                                                                //allowBlank: false,
                                                                labelWidth: 115,
                                                                width:'100%',
                                                                anchor:'100%',
                                                                //readOnly: true,
                                                                listeners:{
                                                                    afterrender:function(obj, e){
                                                                        // obj.getStore().load();
                                                                    },
                                                                    select:function(obj, records, eOpts){
                                                                        Ext.getCmp(solicitar_envios.id+'-direccion').setValue(records[0].data.dir_calle);
                                                                        Ext.getCmp(solicitar_envios.id+'-distrito').setValue(records[0].data.distrito);

                                                                        Ext.getCmp(solicitar_envios.id+'-remitente').setValue('');
                                                                        Ext.getCmp(solicitar_envios.id+'-remitente').getStore().removeAll();
                                                                        var shi_codigo = Ext.getCmp(solicitar_envios.id+'-shipper').getValue();
                                                                        Ext.getCmp(solicitar_envios.id+'-remitente').getStore().load(
                                                                            {params: {vp_id_agencia: obj.getValue('id_agencia'),vp_shi_codigo:shi_codigo},
                                                                            callback:function(){
                                                                                
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
                                                xtype:'panel',
                                                border:false,
                                                bodyStyle: 'background: transparent',
                                                padding:'2px 5px 1px 5px',
                                                layout:'column',
                                                items: [
                                                    {
                                                        columnWidth: 1,border:false,
                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                        items:[
                                                            {
                                                                xtype: 'textfield',
                                                                id:solicitar_envios.id+'-direccion',
                                                                fieldLabel: 'Dirección',
                                                                labelWidth:80,
                                                                readOnly:true,
                                                                width:'100%',
                                                                anchor:'100%'
                                                            }
                                                        ]
                                                    }
                                                ]
                                            },
                                            {
                                                xtype:'panel',
                                                border:false,
                                                bodyStyle: 'background: transparent',
                                                padding:'2px 5px 1px 5px',
                                                layout:'column',
                                                items: [
                                                    {
                                                        columnWidth: 1,border:false,
                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                        items:[
                                                            {
                                                                xtype: 'textfield',
                                                                id:solicitar_envios.id+'-distrito',
                                                                fieldLabel: 'Distrito',
                                                                labelWidth:80,
                                                                readOnly:true,
                                                                width:'100%',
                                                                anchor:'100%'
                                                            }
                                                        ]
                                                    }
                                                ]
                                            },
                                            {
                                                xtype:'panel',
                                                border:false,
                                                bodyStyle: 'background: transparent',
                                                padding:'2px 5px 1px 5px',
                                                layout:'column',
                                                items: [
                                                    {
                                                        columnWidth: 1,border:false,
                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                        items:[
                                                            {
                                                                xtype:'combo',
                                                                fieldLabel: 'Remitente',
                                                                id:solicitar_envios.id+'-remitente',
                                                                store: contacto,
                                                                queryMode: 'local',
                                                                triggerAction: 'all',
                                                                valueField: 'id_contacto',
                                                                displayField: 'contacto',
                                                                emptyText: '[Seleccione]',
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
                                                    }
                                                ]
                                            }
                                        ]
                                    },
                                    {
                                        region:'center',
                                        border:false,
                                        layout:'border',
                                        items:[
                                            {
                                                region:'north',
                                                //frame:true,
                                                //xtype: 'uePanelS',
                                                height:195,
                                                logo: 'DV',
                                                //title: 'Datos de Envío',
                                                legend: 'Ingrese Datos',
                                                xtype: 'fieldset',
                                                title: '¿Qué contenido envía?',
                                                padding:'2px 2px 0px 2px',
                                                margin:1,
                                                bodyStyle: 'background: transparent',
                                                border:true,
                                                //layout:'fit',
                                                items:[
                                                    {
                                                        xtype:'panel',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'0px 5px 1px 5px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: .45,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'textfield',
                                                                        id:solicitar_envios.id+'-cod-rastreo',
                                                                        disabled:false,
                                                                        fieldLabel: '¿Código Rastreo?',
                                                                        labelWidth:110,
                                                                        labelAlign:'left',
                                                                        width:'100%',
                                                                        anchor:'100%'
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .55,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'textfield',
                                                                        id:solicitar_envios.id+'-nro-documento',
                                                                        disabled:false,
                                                                        fieldLabel: '¿Número Documento?',
                                                                        labelWidth:140,
                                                                        labelAlign:'right',
                                                                        width:'100%',
                                                                        anchor:'100%'
                                                                    }
                                                                ]
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype:'panel',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'2px 5px 1px 5px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: .20,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                defaultType: 'checkbox',
                                                                items:[
                                                                    {
                                                                        fieldLabel: '',
                                                                        id:solicitar_envios.id+'-chk-sobres',
                                                                        boxLabel: 'Sobre',
                                                                        name: 'fav-animal-dog',
                                                                        inputValue: 'dog',
                                                                        listeners:{
                                                                            change:function(){
                                                                                var bol = Ext.getCmp(solicitar_envios.id+'-chk-sobres').getValue();
                                                                                var act =(bol)?false:true;
                                                                                Ext.getCmp(solicitar_envios.id+'-nro-piezas-sobre').setDisabled(act);
                                                                                Ext.getCmp(solicitar_envios.id+'-peso-sobre').setDisabled(act);

                                                                                Ext.getCmp(solicitar_envios.id+'-nro-piezas-sobre').setValue('');
                                                                                Ext.getCmp(solicitar_envios.id+'-peso-sobre').setValue('');
                                                                                Ext.getCmp(solicitar_envios.id+'-nro-piezas-sobre').focus(true, 500);
                                                                            }
                                                                        }
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .40,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'numberfield',
                                                                        id:solicitar_envios.id+'-nro-piezas-sobre',
                                                                        fieldLabel: '¿Nro. Sobres?',
                                                                        labelWidth:85,
                                                                        disabled:true,
                                                                        minValue: 1,
                                                                        width:'100%',
                                                                        anchor:'100%'
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .40,border:false,  
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'numberfield',
                                                                        id:solicitar_envios.id+'-peso-sobre',
                                                                        fieldLabel: 'Peso (Kg)',
                                                                        labelAlign:'right',
                                                                        disabled:true,
                                                                        labelWidth:60,
                                                                        minValue: 0,
                                                                        width:'100%',
                                                                        anchor:'100%'
                                                                    }
                                                                ]
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype:'panel',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'2px 5px 1px 5px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: .20,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                defaultType: 'checkbox',
                                                                items:[
                                                                    {
                                                                        fieldLabel: '',
                                                                        id:solicitar_envios.id+'-chk-valijas',
                                                                        boxLabel: 'Valija',
                                                                        name: 'fav-animal-dog',
                                                                        inputValue: 'dog',
                                                                        listeners:{
                                                                            change:function(){
                                                                                var bol = Ext.getCmp(solicitar_envios.id+'-chk-valijas').getValue();
                                                                                var act =(bol)?false:true;
                                                                                Ext.getCmp(solicitar_envios.id+'-nro-piezas-valija').setDisabled(act);
                                                                                Ext.getCmp(solicitar_envios.id+'-peso-valija').setDisabled(act);

                                                                                Ext.getCmp(solicitar_envios.id+'-nro-piezas-valija').setValue('');
                                                                                Ext.getCmp(solicitar_envios.id+'-peso-valija').setValue('');
                                                                                Ext.getCmp(solicitar_envios.id+'-nro-piezas-valija').focus(true, 500);
                                                                            }
                                                                        }
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .40,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'numberfield',
                                                                        id:solicitar_envios.id+'-nro-piezas-valija',
                                                                        fieldLabel: '¿Cant. Valijas?',
                                                                        disabled:true,
                                                                        labelWidth:85,
                                                                        minValue: 1,
                                                                        width:'100%',
                                                                        anchor:'100%'
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .40,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'numberfield',
                                                                        id:solicitar_envios.id+'-peso-valija',
                                                                        fieldLabel: 'Peso (Kg)',
                                                                        labelAlign:'right',
                                                                        disabled:true,
                                                                        labelWidth:60,
                                                                        minValue: 0,
                                                                        width:'100%',
                                                                        anchor:'100%'
                                                                    }
                                                                ]
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype:'panel',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'2px 5px 1px 5px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: .20,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                defaultType: 'checkbox',
                                                                items:[
                                                                    {
                                                                        fieldLabel: '',
                                                                        id:solicitar_envios.id+'-chk-paquetes',
                                                                        boxLabel: 'Paquete',
                                                                        name: 'fav-animal-dog',
                                                                        inputValue: 'dog',
                                                                        listeners:{
                                                                            change:function(){
                                                                                var bol = Ext.getCmp(solicitar_envios.id+'-chk-paquetes').getValue();
                                                                                var act =(bol)?false:true;
                                                                                Ext.getCmp(solicitar_envios.id+'-nro-piezas-paquete').setDisabled(act);
                                                                                Ext.getCmp(solicitar_envios.id+'-peso-paquete').setDisabled(act);
                                                                                Ext.getCmp(solicitar_envios.id+'-asegura-servicio').setDisabled(act);

                                                                                if(!act){
                                                                                    var boll = Ext.getCmp(solicitar_envios.id+'-asegura-servicio').getValue().rb_auto;
                                                                                    var actl =(boll)?true:false;
                                                                                    if(bol){
                                                                                        Ext.getCmp(solicitar_envios.id+'-valor-sku').setDisabled(actl);
                                                                                        Ext.getCmp(solicitar_envios.id+'-dice-contener').setDisabled(actl);
                                                                                    }
                                                                                }else{
                                                                                    Ext.getCmp(solicitar_envios.id+'-valor-sku').setDisabled(act);
                                                                                    Ext.getCmp(solicitar_envios.id+'-dice-contener').setDisabled(act);
                                                                                }

                                                                                Ext.getCmp(solicitar_envios.id+'-peso-paquete').setValue('');
                                                                                Ext.getCmp(solicitar_envios.id+'-nro-piezas-paquete').setValue('');
                                                                                Ext.getCmp(solicitar_envios.id+'-valor-sku').setValue('');
                                                                                Ext.getCmp(solicitar_envios.id+'-dice-contener').setValue('');
                                                                                Ext.getCmp(solicitar_envios.id+'-nro-piezas-paquete').focus(true, 500);
                                                                            }
                                                                        }
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .40,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'numberfield',
                                                                        id:solicitar_envios.id+'-nro-piezas-paquete',
                                                                        fieldLabel: '¿Cant.Piezas?',
                                                                        disabled:true,
                                                                        labelWidth:85,
                                                                        minValue: 1,
                                                                        width:'100%',
                                                                        anchor:'100%'
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .40,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'numberfield',
                                                                        id:solicitar_envios.id+'-peso-paquete',
                                                                        fieldLabel: 'Peso (Kg)',
                                                                        labelAlign:'right',
                                                                        disabled:true,
                                                                        minValue: 0,
                                                                        labelWidth:60,
                                                                        width:'100%',
                                                                        anchor:'100%'
                                                                    }
                                                                ]
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype:'panel',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'2px 5px 1px 5px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: .55,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'radiogroup',
                                                                        fieldLabel: '¿Va a asegurar el producto?',
                                                                        disabled:true,
                                                                        id:solicitar_envios.id+'-asegura-servicio',
                                                                        labelWidth:160,
                                                                        cls: 'x-check-group-alt',
                                                                        items: [
                                                                            {boxLabel: 'Si', name: 'rb_auto',margin:'0px 10px 0px 5px', inputValue: 0},
                                                                            {boxLabel: 'No', name: 'rb_auto', inputValue: 1, checked: true,id:solicitar_envios.id+'-asegura-chek'}
                                                                        ],
                                                                        listeners:{
                                                                            change:function(){
                                                                                var bol = Ext.getCmp(solicitar_envios.id+'-asegura-servicio').getValue().rb_auto;
                                                                                var act =(bol)?true:false;
                                                                                Ext.getCmp(solicitar_envios.id+'-valor-sku').setDisabled(act);
                                                                                Ext.getCmp(solicitar_envios.id+'-dice-contener').setDisabled(act);
                                                                                Ext.getCmp(solicitar_envios.id+'-dice-contener').setValue('');
                                                                                Ext.getCmp(solicitar_envios.id+'-valor-sku').setValue('');

                                                                                if(!act){ 
                                                                                    Ext.getCmp(solicitar_envios.id+'-valor-sku').focus(true, 500);
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .45,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'numberfield',
                                                                        id:solicitar_envios.id+'-valor-sku',
                                                                        disabled:true,
                                                                        fieldLabel: 'Valor SKU',
                                                                        labelWidth:60,
                                                                        minValue: 1,
                                                                        labelAlign:'right',
                                                                        width:'100%',
                                                                        anchor:'100%'
                                                                    }
                                                                ]
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype:'panel',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'2px 5px 1px 5px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: 1,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype:'combo',
                                                                        id:solicitar_envios.id+'-dice-contener',
                                                                        fieldLabel: '¿Qué contiene el paquete?',
                                                                        labelWidth:160,
                                                                        disabled:true,
                                                                        store: producto_sku,
                                                                        labelAlign:'right',
                                                                        queryMode: 'local',
                                                                        triggerAction: 'all',
                                                                        autoSelect: false,
                                                                        enableKeyEvents: true,
                                                                        //forceSelection:true,
                                                                        caseSensitive: true,
                                                                        valueField: 'sku_id',
                                                                        displayField: 'sku_descri',
                                                                        emptyText: '[Seleccione]',
                                                                        //allowBlank: false,
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        //readOnly: true,
                                                                        listeners:{
                                                                            afterrender: function(obj){
                                                                                //obj.focus(true, 500);
                                                                            },
                                                                            beforeselect: function( obj, record, index, eOpts ){
                                                                                if (index >= 0)
                                                                                    return true;
                                                                                else
                                                                                    return false;
                                                                            },
                                                                            select:function(obj, records, eOpts){
                                                                                
                                                                            }
                                                                        }
                                                                    }
                                                                ]
                                                            }
                                                        ]
                                                    }
                                                ]
                                            },
                                            {
                                                xtype:'panel',
                                                border:false,
                                                title:'Agregar Acuse',
                                                region:'center',
                                                bbar:[
                                                    '->',
                                                    '-',
                                                    {
                                                        xtype:'button',
                                                        text: 'Quitar',
                                                        icon: '/images/icon/cancel.png',
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
                                                                var sm = Ext.getCmp(solicitar_envios.id+'-acuse').getSelectionModel();
                                                                rowEditing.cancelEdit();
                                                                store.remove(sm.getSelection());
                                                                if (store.getCount() > 0) {
                                                                    sm.select(0);
                                                                }
                                                            }
                                                        }
                                                    },
                                                    '-',
                                                    {
                                                        xtype:'button',
                                                        text: 'Agregar',
                                                        icon: '/images/icon/add.png',
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
                                                                rowEditing.cancelEdit();
                                                                var r = Ext.create('documento', {
                                                                    doc_numero: '',
                                                                    tipo_doc: ''
                                                                });

                                                                store.insert(0, r);
                                                                rowEditing.startEdit(0, 0);
                                                            }
                                                        }
                                                    },
                                                    '-'
                                                ],
                                                layout:'fit',
                                                items:[
                                                    {
                                                        xtype: 'grid',
                                                        id: solicitar_envios.id+'-acuse',
                                                        store: store,
                                                        plugins: [
                                                            rowEditing
                                                        ],
                                                        columnLines: true,
                                                        columns:{
                                                            items:[
                                                                {
                                                                    text: 'Nro. Documento',
                                                                    dataIndex: 'doc_numero',
                                                                    flex: 1,
                                                                    align: 'left',
                                                                    editor: {
                                                                        // defaults to textfield if no xtype is supplied
                                                                        allowBlank: false
                                                                    }
                                                                },
                                                                {
                                                                    text: 'Tipo Documento',
                                                                    dataIndex: 'tipo_doc',
                                                                    flex: 1,
                                                                    align: 'left',typeAhead: true,
                                                                    editor: {
                                                                        // defaults to textfield if no xtype is supplied
                                                                        xtype:'combo',
                                                                        store: tipo_doc,
                                                                        typeAhead: true,
                                                                        queryMode: 'local',
                                                                        triggerAction: 'all',
                                                                        valueField: 'id_elemento',
                                                                        displayField: 'descripcion',
                                                                        emptyText: '[Seleccione]',
                                                                        //allowBlank: false,
                                                                        labelWidth: 80,
                                                                        //readOnly: true,
                                                                        listeners:{
                                                                            afterrender:function(obj, e){
                                                                                // obj.getStore().load();
                                                                            },
                                                                            select:function(obj, records, eOpts){
                                                                    
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            ],
                                                            defaults:{
                                                                menuDisabled: true
                                                            }
                                                        },
                                                        selModel: {
                                                            selType: 'cellmodel'
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
                            xtype:'panel',bodyStyle: 'background: transparent',
                            layout:'border',
                            border:false,
                            items:[
                                {
                                    //xtype:'fieldset',
                                    region:'north',
                                    border:true,
                                    height:95,
                                    layout:'border',
                                    margin:5,
                                    items:[
                                        /*{
                                            region:'east',
                                            title:'Impresión',
                                            width:200,
                                            border:true,
                                            html:'<div style="font-size:60px;">0</div>'
                                        },*/
                                        {
                                            region:'center',
                                            title:'Datos para Entrega',
                                            bodyStyle: 'background: transparent',
                                            border:false,
                                            items:[
                                                {
                                                    xtype:'panel',
                                                    border:false,
                                                    bodyStyle: 'background: transparent',
                                                    padding:'2px 5px 1px 5px',
                                                    layout:'column',
                                                    items: [
                                                        {
                                                            columnWidth: .32,border:false,
                                                            padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                            items:[
                                                                {
                                                                    xtype: 'textfield',
                                                                    id:solicitar_envios.id+'-codigo-cliente',
                                                                    fieldLabel: 'Código Cliente',
                                                                    labelWidth:90,
                                                                    labelAlign:'right',
                                                                    width:'100%',
                                                                    anchor:'100%'
                                                                }
                                                            ]
                                                        },
                                                        {
                                                            columnWidth: .68,border:false,
                                                            padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                            items:[
                                                                {
                                                                    xtype:'combo',
                                                                    fieldLabel: 'Destinatario',
                                                                    id:solicitar_envios.id+'-destinatario-re',
                                                                    store: cliente_frecuente,
                                                                    labelAlign:'right',
                                                                    queryMode: 'local',
                                                                    triggerAction: 'all',
                                                                    autoSelect: false,
                                                                    enableKeyEvents: true,
                                                                    //forceSelection:true,
                                                                    caseSensitive: true,
                                                                    valueField: 'cli_id',
                                                                    displayField: 'cliente',
                                                                    emptyText: '[Seleccione]',
                                                                    //allowBlank: false,
                                                                    labelWidth: 85,
                                                                    width:'100%',
                                                                    anchor:'100%',
                                                                    //readOnly: true,
                                                                    listeners:{
                                                                        afterrender: function(obj){
                                                                            obj.focus(true, 500);
                                                                        },
                                                                        beforeselect: function( obj, record, index, eOpts ){
                                                                            if (index >= 0)
                                                                                return true;
                                                                            else
                                                                                return false;
                                                                        },
                                                                        select:function(obj, records, eOpts){
                                                                            var values = Ext.getCmp(solicitar_envios.id + '-bdireccion').getValues();
                                                                            //console.log(values);
                                                                            solicitar_envios.setdatoCliente(records);
                                                                            
                                                                        }
                                                                    }
                                                                }
                                                            ]
                                                        },
                                                        {
                                                            width:135,border:false,
                                                            padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                            items:[
                                                                {
                                                                    xtype:'button',
                                                                    text: 'Búsqueda Avanzada',
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
                                                                            var shipper = Ext.getCmp(solicitar_envios.id+'-shipper').getValue();
                                                                            shipper = (shipper==null)?0:shipper;
                                                                            //console.log(shipper);
                                                                            if(shipper!=0){
                                                                                solicitar_envios.buscar_destinatario();
                                                                            }else{
                                                                                global.Msg({msg:"Seleccione un shipper",icon:2,fn:function(){}});
                                                                                return;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            ]
                                                        },
                                                        {
                                                            width:70,border:false,
                                                            padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                            items:[
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
                                                                            solicitar_envios.limpiar();
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
                                                    padding:'2px 5px 1px 5px',
                                                    layout:'column',
                                                    items: [
                                                        
                                                    ]
                                                },
                                                {
                                                    xtype:'panel',
                                                    border:false,
                                                    bodyStyle: 'background: transparent',
                                                    padding:'2px 5px 1px 5px',
                                                    layout:'column',
                                                    items: [
                                                        {
                                                            columnWidth: 1,border:false,
                                                            padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                            items:[
                                                                {
                                                                    xtype: 'textfield',
                                                                    id:solicitar_envios.id+'-empresa',
                                                                    fieldLabel: 'Empresa',
                                                                    labelWidth:90,
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
                                   region:'center',
                                   bodyStyle: 'background: transparent',
                                   autoScroll:true,
                                   layout:'fit',
                                   border:true,
                                   items:[
                                        {
                                           xtype: 'findlocation',
                                           id: solicitar_envios.id + '-bdireccion',
                                           mapping: false
                                        }
                                   ] 
                                },
                                {
                                    region:'south',
                                    border:false,bodyStyle: 'background: transparent',
                                    height:50,
                                    margin:5,
                                    items:[
                                        {
                                            xtype: 'textarea',
                                            id:solicitar_envios.id+'-referencia-r',
                                            margin:2,
                                            labelWidth:62,
                                            //flex: 1,
                                            fieldLabel: 'Referencia',
                                            width: '100%',
                                            anchor:'100%',
                                            emptyText: 'Escribo un referencia...',
                                            maxLength:200,
                                            grow: true,
                                            maxLengthText:'El maximo de caracteres permitidos para este campo es {0}',
                                            enforceMaxLength:true
                                        }
                                    ]
                                }
                            ]
                          }  
                        ]
                    }
                ]
            });

            tab.add({
                id: solicitar_envios.id+'-tab',
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
                        global.state_item_menu(solicitar_envios.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        /*Ext.getCmp(solicitar_envios.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + solicitar_envios.id_menu).text,
                            icon: Ext.getCmp('menu-' + solicitar_envios.id_menu).icon
                        });*/
                        global.state_item_menu_config(obj,solicitar_envios.id_menu);
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(solicitar_envios.id_menu, false);
                    }
                }
            }).show();
        },
        save_ss:function(){
            
            var records = Ext.getCmp(solicitar_envios.id + '-bdireccion').getValues();
            //console.log(records[0]);
            var ciu_id = records[0].ciu_id;
            var id_geo = records[0].id_puerta;
            var vp_ciu_px = records[0].coordenadas[0].lat;
            var vp_ciu_py = records[0].coordenadas[0].lon;
            
            var shi_codigo = Ext.getCmp(solicitar_envios.id+'-shipper').getValue();
            var nombre_shipper = Ext.getCmp(solicitar_envios.id+'-shipper').getRawValue();
            var tipo_servicio = Ext.getCmp(solicitar_envios.id+'-tipo-servicio').getValue();
            var nombre_servicio = Ext.getCmp(solicitar_envios.id+'-tipo-servicio').getRawValue();
            var id_agencia = Ext.getCmp(solicitar_envios.id+'-centro-actividad').getValue();

            //var distrito = Ext.getCmp(solicitar_envios.id+'-distrito').getValue();
            var remitente = Ext.getCmp(solicitar_envios.id+'-remitente').getValue();

            var vp_sku_id = Ext.getCmp(solicitar_envios.id+'-dice-contener').getValue();
            vp_sku_id = isNaN(vp_sku_id)?'':vp_sku_id;
            var dice_contener = Ext.getCmp(solicitar_envios.id+'-dice-contener').getRawValue();

            /*var nro_piezas = Ext.getCmp(solicitar_envios.id+'-nro-piezas').getValue();
            var peso_total = Ext.getCmp(solicitar_envios.id+'-peso-total').getValue();*/

            var nro_sobre = Ext.getCmp(solicitar_envios.id+'-nro-piezas-sobre').getValue();
            var pes_sobre = Ext.getCmp(solicitar_envios.id+'-peso-sobre').getValue();

            var nro_valija = Ext.getCmp(solicitar_envios.id+'-nro-piezas-valija').getValue();
            var pes_valija = Ext.getCmp(solicitar_envios.id+'-peso-valija').getValue();

            var nro_paquete = Ext.getCmp(solicitar_envios.id+'-nro-piezas-paquete').getValue();
            var pes_paquete = Ext.getCmp(solicitar_envios.id+'-peso-paquete').getValue();



            var asegura_serv = Ext.getCmp(solicitar_envios.id+'-asegura-servicio').getValue().rb_auto;
            var valor_sku = 0;
            if(asegura_serv==0){
                 valor_sku = Ext.getCmp(solicitar_envios.id+'-valor-sku').getValue();
            }

            var cli_id = Ext.getCmp(solicitar_envios.id+'-destinatario-re').getValue();
            cli_id = isNaN(cli_id)?'':cli_id;
            var destinatario = Ext.getCmp(solicitar_envios.id+'-destinatario-re').getRawValue();
            var codigo_cliente = Ext.getCmp(solicitar_envios.id+'-codigo-cliente').getValue();
            var empresa = Ext.getCmp(solicitar_envios.id+'-empresa').getValue();
            var direccion = records[0].direccion;
            var referencia = Ext.getCmp(solicitar_envios.id+'-referencia-r').getValue();

            var cod_rastreo = Ext.getCmp(solicitar_envios.id+'-cod-rastreo').getValue();
            var nro_documento =Ext.getCmp(solicitar_envios.id+'-nro-documento').getValue();

            
            if(shi_codigo== null || shi_codigo==''){
                global.Msg({msg:"Seleccione un shipper",icon:2,fn:function(){}});
                return;
            }
            if(tipo_servicio== null || tipo_servicio==''){
                global.Msg({msg:"Seleccione un tipo de servicio",icon:2,fn:function(){}});
                return;
            }
            if(id_agencia== null || id_agencia==''){
                global.Msg({msg:"Seleccione un centro de actividad",icon:2,fn:function(){}});
                return;
            }
            if(direccion== null || direccion==''){
                global.Msg({msg:"Ingrese una direccion",icon:2,fn:function(){}});
                return;
            }
            /*if(distrito== null || distrito==''){
                global.Msg({msg:"El distrito de la direccion no esta ingresado",icon:2,fn:function(){}});
                return;
            }*/
            if(remitente== null || remitente==''){
                global.Msg({msg:"Seleccione un remitente un remitente",icon:2,fn:function(){}});
                return;
            }

            nro_sobre = (nro_sobre=='' || nro_sobre==0 || nro_sobre==null)?0:nro_sobre;
            pes_sobre = (pes_sobre=='' || pes_sobre==0 || pes_sobre==null)?0:pes_sobre;

            nro_valija = (nro_valija=='' || nro_valija==0 || nro_valija==null)?0:nro_valija;
            pes_valija = (pes_valija=='' || pes_valija==0 || pes_valija==null)?0:pes_valija;

            nro_paquete = (nro_paquete=='' || nro_paquete==0 || nro_paquete==null)?0:nro_paquete;
            pes_paquete = (pes_paquete=='' || pes_paquete==0 || pes_paquete==null)?0:pes_paquete;

            if(nro_sobre!= 0 && pes_sobre==0){
                global.Msg({msg:"Ingrese el peso del sobre",icon:2,fn:function(){}});
                return;
            }

            if(nro_sobre== 0 && pes_sobre!=0){
                global.Msg({msg:"Ingrese el número de piezas del sobre",icon:2,fn:function(){}});
                return;
            }

            if(nro_valija!= 0 && pes_valija==0){
                global.Msg({msg:"Ingrese el peso del valija",icon:2,fn:function(){}});
                return;
            }

            if(nro_valija== 0 && pes_valija!=0){
                global.Msg({msg:"Ingrese el número de piezas del valija",icon:2,fn:function(){}});
                return;
            }

            if(nro_paquete!= 0 && pes_paquete==0){
                global.Msg({msg:"Ingrese el peso del paqueta",icon:2,fn:function(){}});
                return;
            }

            if(nro_paquete== 0 && pes_paquete!=0){
                global.Msg({msg:"Ingrese el número de piezas del paqueta",icon:2,fn:function(){}});
                return;
            }


            var cantidad = nro_sobre + nro_valija + pes_paquete;
            var peso = pes_sobre + pes_valija + pes_paquete;
            cantidad = (cantidad=='')?0:cantidad;
            peso = (peso=='')?0:peso;

            if(cantidad== 0 || peso==0){
                global.Msg({msg:"No ingreso piezas o pesos",icon:2,fn:function(){}});
                return;
            }

            if(asegura_serv==0){
                if(valor_sku== null || valor_sku=='' || valor_sku == 0){
                    global.Msg({msg:"Ingrese el valor sku",icon:2,fn:function(){}});
                    return;
                }
                asegura_serv = 1;
                if(dice_contener== null || dice_contener==''){
                    global.Msg({msg:"Ingrese el contenido de la solicitud",icon:2,fn:function(){}});
                    return;
                }
            }
            if (cli_id == null || cli_id=='' || cli_id == 0){
                if(destinatario== null || destinatario=='' || destinatario == 0){
                    global.Msg({msg:"Ingrese un nombre de destinatario",icon:2,fn:function(){}});
                    return;
                }
                if(codigo_cliente== null || codigo_cliente=='' || codigo_cliente == 0){
                    global.Msg({msg:"Ingrese el codigo de cliente",icon:2,fn:function(){}});
                    return;
                }
            }
            

            var modified = Ext.getCmp(solicitar_envios.id+'-acuse').getStore().getModifiedRecords();
            var recordsToSend = [];
            if(!Ext.isEmpty(modified)){
                Ext.each(modified, function(record) { //step 2
                    recordsToSend.push(Ext.apply({id:record.id},record.data));
                });
                recordsToSend = Ext.encode(recordsToSend);
            }
            global.Msg({
                msg: '¿Seguro de registrar el contenido?',
                icon: 3,
                buttons: 3,
                fn: function(btn){
                    if (btn == 'yes'){
                        Ext.Ajax.request({
                            url:solicitar_envios.url+'set_scm_registra',
                            params:{
                                vp_shi_codigo:shi_codigo,vp_id_orden:tipo_servicio,vp_id_agencia:id_agencia,
                                vp_dir_entrega:direccion,vp_id_direc:0,
                                vp_id_remitente:remitente,vp_descr_sku:dice_contener,
                                vp_cantidad_sku_sobre:nro_sobre,vp_peso_sku_sobre:pes_sobre,
                                vp_cantidad_sku_valija:nro_valija,vp_peso_sku_valija:pes_valija,
                                vp_cantidad_sku_paquete:nro_paquete,vp_peso_sku_paquete:pes_paquete,
                                vp_asegura_serv:asegura_serv,vp_valor_sku:valor_sku,vp_cli_id:cli_id,vp_nom_cliente:destinatario,
                                vp_cod_cliente:codigo_cliente,vp_nom_empresa:empresa,vp_ref_direc:referencia,vp_ciu_id:ciu_id,vp_id_geo:id_geo,
                                vp_cod_rastreo:cod_rastreo,vp_nro_documento:nro_documento,vp_sku_id:vp_sku_id,vp_ciu_px:vp_ciu_px,vp_ciu_py:vp_ciu_py,
                                vp_recordsToSend:recordsToSend
                            },
                            success:function(response,options){
                                var res = Ext.decode(response.responseText);

                                if(parseInt(res.data[0].error_sql)<=0){

                                    global.Msg({
                                        msg:res.data[0].error_info,
                                        icon:0,
                                        fn:function(){
                                                
                                        }
                                    });

                                }else{
                                    global.Msg({
                                        msg:res.data[0].error_info,
                                        icon:1,
                                        fn:function(){
                                            //METHOD
                                            solicitar_envios.siguiente();
                                            var cantidad = nro_sobre+nro_valija+nro_paquete;
                                            var peso =pes_sobre+pes_valija+pes_paquete;
                                            solicitar_envios.generacion.add({shipper: nombre_shipper,servicio:nombre_servicio,gui_numero:res.data[0].gui_numero,gui_peso:peso,cantidad:cantidad,cli_nombre:destinatario});
                                            var count = solicitar_envios.generacion.getCount();
                                            count=(count=='')?0:count;
                                            Ext.getCmp(solicitar_envios.id+'-imprimir_ge').setText("Imprimir("+count+")");
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
            });
        },
        nuevo:function(){
            Ext.getCmp(solicitar_envios.id+'-shipper').setValue('');
            Ext.getCmp(solicitar_envios.id+'-tipo-servicio').setValue('');
            Ext.getCmp(solicitar_envios.id+'-centro-actividad').setValue('');

            //var distrito = Ext.getCmp(solicitar_envios.id+'-distrito').getValue();
            Ext.getCmp(solicitar_envios.id+'-remitente').setValue('');

            Ext.getCmp(solicitar_envios.id+'-direccion').setValue('');
            Ext.getCmp(solicitar_envios.id+'-distrito').setValue('');
            Ext.getCmp(solicitar_envios.id+'-dice-contener').setValue('');
            
            Ext.getCmp(solicitar_envios.id+'-nro-piezas-sobre').setValue('');
            Ext.getCmp(solicitar_envios.id+'-peso-sobre').setValue('');
            Ext.getCmp(solicitar_envios.id+'-nro-piezas-valija').setValue('');
            Ext.getCmp(solicitar_envios.id+'-peso-valija').setValue('');
            Ext.getCmp(solicitar_envios.id+'-nro-piezas-paquete').setValue('');
            Ext.getCmp(solicitar_envios.id+'-peso-paquete').setValue('');
            Ext.getCmp(solicitar_envios.id+'-cod-rastreo').setValue('');
            Ext.getCmp(solicitar_envios.id+'-nro-documento').setValue('');

            //Ext.getCmp(solicitar_envios.id+'-asegura-chek').checked;
            //Ext.getCmp(solicitar_envios.id+'-asegura-servicio').setValue(1);
            Ext.getCmp(solicitar_envios.id+'-valor-sku').setValue('');
            //Ext.getCmp(solicitar_envios.id+'-valor-sku').setDisabled(true);
            Ext.getCmp(solicitar_envios.id+'-destinatario-re').getStore().removeAll();
            Ext.getCmp(solicitar_envios.id+'-acuse').getStore().removeAll();
            solicitar_envios.cli_id=0;
            solicitar_envios.limpiar();
        },
        siguiente:function(){
            Ext.getCmp(solicitar_envios.id+'-dice-contener').setValue('');
            
            Ext.getCmp(solicitar_envios.id+'-nro-piezas-sobre').setValue('');
            Ext.getCmp(solicitar_envios.id+'-peso-sobre').setValue('');
            Ext.getCmp(solicitar_envios.id+'-nro-piezas-valija').setValue('');
            Ext.getCmp(solicitar_envios.id+'-peso-valija').setValue('');
            Ext.getCmp(solicitar_envios.id+'-nro-piezas-paquete').setValue('');
            Ext.getCmp(solicitar_envios.id+'-peso-paquete').setValue('');
            Ext.getCmp(solicitar_envios.id+'-cod-rastreo').setValue('');
            Ext.getCmp(solicitar_envios.id+'-nro-documento').setValue('');

            //Ext.getCmp(solicitar_envios.id+'-asegura-chek').checked;
            //Ext.getCmp(solicitar_envios.id+'-asegura-servicio').setValue(1);
            Ext.getCmp(solicitar_envios.id+'-valor-sku').setValue('');
            //Ext.getCmp(solicitar_envios.id+'-valor-sku').setDisabled(true);
            //Ext.getCmp(solicitar_envios.id+'-destinatario-re').getStore().removeAll();
            Ext.getCmp(solicitar_envios.id+'-acuse').getStore().removeAll();
            solicitar_envios.cli_id=0;
            solicitar_envios.limpiar();
            var shi_codigo = Ext.getCmp(solicitar_envios.id+'-shipper').getValue();
            Ext.getCmp(solicitar_envios.id+'-destinatario-re').setValue('');
            Ext.getCmp(solicitar_envios.id+'-destinatario-re').getStore().removeAll();
            Ext.getCmp(solicitar_envios.id+'-destinatario-re').getStore().load(
                {params: {vp_shi_codigo: shi_codigo},
                callback:function(){
                    
                }
            });
        },
        limpiar:function(){
            Ext.getCmp(solicitar_envios.id+'-destinatario-re').setValue(''); 
            Ext.getCmp(solicitar_envios.id+'-destinatario-re').clearValue();
            Ext.getCmp(solicitar_envios.id+'-codigo-cliente').setValue('');
            Ext.getCmp(solicitar_envios.id+'-empresa').setValue('');
            Ext.getCmp(solicitar_envios.id+'-referencia-r').setValue('');
            Ext.getCmp(solicitar_envios.id + '-bdireccion').reset_maps();
            Ext.getCmp(solicitar_envios.id + '-bdireccion').reset();
        },
        buscar_destinatario:function(){
            var form = Ext.widget({
                xtype: 'panel',
                layout: 'border',
                border:false,
                frame:true,
                collapsible: false,
                bodyPadding: 0,
                margin:8,
                bodyStyle: 'background: transparent',
                fieldDefaults: {
                    labelAlign: 'top',
                    msgTarget: 'side'
                },
                defaults: {
                    anchor: '100%'
                },
                items:[
                    {
                        region:'center',
                        border:false,
                        items:[
                            {
                                xtype: 'combo',
                                id:solicitar_envios.id+'-ciudad-re',
                                fieldLabel: 'Dist/Prov/Dep',
                                width:'100%',
                                anchor:'100%',
                                labelAlign: 'left',
                                store: Ext.create('Ext.data.Store',{
                                    fields: [
                                        {name: 'ciudad', type: 'string'},
                                        {name: 'ciu_id', type: 'int'},
                                        {name: 'ciu_px', type: 'float'},
                                        {name: 'ciu_py', type: 'float'},
                                        {name: 'mapa', type: 'int'}
                                    ],
                                    proxy:{
                                        type: 'ajax',
                                        url: solicitar_envios.url + 'get_gis_busca_distrito/',
                                        reader:{
                                            type: 'json',
                                            rootProperty: 'data'
                                        }
                                    },
                                    listeners:{
                                        load: function(store, records, successful, eOpts){
                                            //console.log(store.getProxy().getReader().rawData.debug.sql);
                                        }
                                    }
                                }),
                                typeAhead: false,
                                hideTrigger: true,
                                labelWidth: 90,
                                valueField: 'ciu_id',
                                displayField: 'ciudad',
                                emptyText: '[ search ]',
                                queryParam: 'vp_nomdis',
                                minChars: 3,
                                enableKeyEvents: true,
                                caseSensitive: true,
                                autoSelect: false,
                                listeners:{
                                    afterrender: function(obj){
                                        obj.focus(true, 500);
                                    },
                                    beforeselect: function( obj, record, index, eOpts ){
                                        if (index >= 0)
                                            return true;
                                        else
                                            return false;
                                    },
                                    select: function(obj, records, opts){
                                        Ext.getCmp(solicitar_envios.id+'-destinatario-bs').setDisabled(false);
                                        Ext.getCmp(solicitar_envios.id+'-codigo-cliente-bs').setDisabled(false);
                                        Ext.getCmp(solicitar_envios.id+'-destinatario-bs').focus(true, 500);
                                    }
                                }
                            },
                            {
                                xtype:'combo',
                                fieldLabel: 'Destinatario',
                                id:solicitar_envios.id+'-destinatario-bs',
                                store: Ext.create('Ext.data.Store',{
                                    fields: [
                                        {name: 'cli_id', type: 'int'},
                                        {name: 'cli_empresa', type: 'string'},
                                        {name: 'cliente', type: 'string'},
                                        {name: 'cli_codigo', type: 'string'}
                                    ],
                                    proxy:{
                                        type: 'ajax',
                                        url: solicitar_envios.url + 'get_scm_busca_destinatario/',
                                        reader:{
                                            type: 'json',
                                            rootProperty: 'data'
                                        }
                                    },
                                    listeners:{
                                        load: function(store, records, successful, eOpts){
                                            //console.log(store.getProxy().getReader().rawData.debug.sql);
                                        },
                                        beforeload: function(store, operation, opts){
                                            var shipper = Ext.getCmp(solicitar_envios.id+'-shipper').getValue();
                                            var ciu_id = Ext.getCmp(solicitar_envios.id+'-ciudad-re').getValue();
                                            var proxy = store.getProxy();
                                            proxy.setExtraParam('vp_shi_codigo',{
                                                vp_shi_codigo: shipper
                                            });
                                            proxy.setExtraParam('vp_ciu_id',{
                                                vp_ciu_id: ciu_id
                                            });
                                        }
                                    }
                                }),
                                typeAhead: false,
                                hideTrigger: true,
                                disabled:true,
                                valueField: 'cli_id',
                                displayField: 'cliente',
                                emptyText: '[ Ingrese almenos 3 caracteres para buscar ]',
                                queryParam: 'vp_nombre',
                                minChars: 3,
                                labelAlign:'right',
                                labelWidth: 90,
                                width:'100%',
                                anchor:'100%',
                                enableKeyEvents: true,
                                caseSensitive: true,
                                autoSelect: false,
                                listeners:{
                                    afterrender: function(obj){
                                        obj.focus(true, 500);
                                    },
                                    beforeselect: function( obj, record, index, eOpts ){
                                        if (index >= 0)
                                            return true;
                                        else
                                            return false;
                                    },
                                    select: function(obj, records, opts){
                                        //records[0].get('mapa');
                                        Ext.getCmp(solicitar_envios.id+'-codigo-cliente-bs').setValue(records[0].get('cli_codigo'));
                                        Ext.getCmp(solicitar_envios.id+'-empresa-bs').setValue(records[0].get('cli_empresa'));
                                    }
                                }
                            },
                            {
                                xtype: 'textfield',
                                id:solicitar_envios.id+'-codigo-cliente-bs',
                                fieldLabel: 'Codigo Cliente',
                                disabled:true,
                                labelWidth:90,
                                labelAlign:'right',
                                width:'100%',
                                anchor:'100%'
                            },
                            {
                                xtype: 'textfield',
                                id:solicitar_envios.id+'-empresa-bs',
                                fieldLabel: 'Empresa',
                                labelWidth:90,
                                labelAlign:'right',
                                width:'100%',
                                anchor:'100%'
                            }/*,
                            {
                                xtype: 'textfield',
                                id:solicitar_envios.id+'-direccion-bs',
                                fieldLabel: 'Dirección',
                                labelWidth:90,
                                labelAlign:'right',
                                width:'100%',
                                anchor:'100%'
                            },
                            {
                                xtype: 'textfield',
                                id:solicitar_envios.id+'-Referencia-bs',
                                fieldLabel: 'Referencia',
                                labelWidth:90,
                                labelAlign:'right',
                                width:'100%',
                                anchor:'100%'
                            }*/
                        ]
                    }
                ]
            });

            Ext.create('Ext.window.Window',{
                id:solicitar_envios.id+'-win-dst',
                plain: true,
                title:'BUSQUEDA DE DESTINATARIO',
                icon: '/images/icon/binocular.png',
                height: 200,
                width: 500,
                resizable:false,
                layout:{
                    type:'fit'
                },
                modal: true,
                border:false,
                closable:true,
                items:[form],
                bbar:[       
                    '->',
                    '-',
                    {
                        xtype:'button',
                        text: 'Seleccionar',
                        icon: '/images/icon/ok.png',
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
                                var params = {};
                                params.shipper = Ext.getCmp(solicitar_envios.id+'-shipper').getValue();
                                params.ciu_id  = Ext.getCmp(solicitar_envios.id+'-ciudad-re').getValue();
                                params.cli_id  = Ext.getCmp(solicitar_envios.id+'-destinatario-bs').getValue();
                                solicitar_envios.registra_cliente(params);
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
                                /*global.permisos({
                                    id: 15,
                                    id_btn: obj.getId(), 
                                    id_menu: gestion_devolucion.id_menu,
                                    fn: ['panel_asignar_gestion.limpiar']
                                });*/
                            },
                            click: function(obj, e){
                                Ext.getCmp(solicitar_envios.id+'-win-dst').close();
                            }
                        }
                    },
                    '-'
                ],
                listeners:{
                    'afterrender':function(obj, e){ 
                        //panel_asignar_gestion.getDatos();
                    },
                    'close':function(){
                        //if(panel_asignar_gestion.guarda!=0)gestion_devolucion.buscar();
                    }
                }
            }).show().center();
        },
        registra_cliente:function(params){
            params.shipper = (params.shipper==null)?0:params.shipper;
            if(params.shipper==0){
                global.Msg({msg:"Seleccione un shipper",icon:2,fn:function(){}});
                return;
            }
            if(params.ciu_id==''){
                global.Msg({msg:"Seleccione una ciudad",icon:2,fn:function(){}});
                return;
            }
            params.cli_id = isNaN(params.cli_id)?'':params.cli_id;
            if(params.cli_id==''){
               global.Msg({msg:"Seleccione una destinatario",icon:2,fn:function(){}});
                return;
            }
            Ext.Ajax.request({
                url:solicitar_envios.url+'set_scm_ss_registra_cliente',
                params:{
                    vp_shi_codigo:params.shipper,vp_ciu_id:params.ciu_id,vp_cli_id:params.cli_id
                },
                success:function(response,options){
                    var res = Ext.decode(response.responseText);
                    solicitar_envios.cli_id=params.cli_id;
                    if(parseInt(res.data[0].error_sql)<=0){

                        global.Msg({
                            msg:res.data[0].error_info,
                            icon:0,
                            fn:function(){
                                
                            }
                        });

                    }else{
                        Ext.getCmp(solicitar_envios.id+'-destinatario-re').getStore().load(
                            {params: {vp_shi_codigo: params.shipper},
                            callback:function(){
                                Ext.getCmp(solicitar_envios.id+'-win-dst').close();
                            }
                        });
                        

                    }
                }
            });
        },
        setdatoCliente:function(rec){
            Ext.getCmp(solicitar_envios.id+'-destinatario-re').setValue(rec.data.cli_id);
            Ext.getCmp(solicitar_envios.id+'-codigo-cliente').setValue(rec.data.cli_codigo);
            Ext.getCmp(solicitar_envios.id+'-empresa').setValue(rec.data.empresa);
            Ext.getCmp(solicitar_envios.id + '-bdireccion').setGeoLocalizar(rec.data);
            Ext.getCmp(solicitar_envios.id+'-referencia-r').setValue(rec.data.dir_referen);
        },
        showGeneracion:function(){

            Ext.create('Ext.window.Window',{
                id:solicitar_envios.id+'-win-gen',
                plain: true,
                title:'Imprimir Guías Electrónicas',
                icon: '/images/icon/print.png',
                height: 300,
                width: 800,
                resizable:false,
                layout:{
                    type:'fit'
                },
                modal: true,
                border:false,
                closable:true,
                items:[
                    {
                        xtype: 'grid',
                        id: solicitar_envios.id+'-gen-grid',
                        store: solicitar_envios.generacion,
                        columnLines: true,
                        selModel: Ext.create("Ext.selection.CheckboxModel", {
                            checkOnly : true,
                            mode: 'SIMPLE'
                        }),
                        columns:{
                            items:[
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
                                    text: 'Guía Envío',
                                    dataIndex: 'gui_numero',
                                    width:100,
                                    align: 'left'
                                },
                                {
                                    text: 'Peso',
                                    dataIndex: 'gui_peso',
                                    width:70,
                                    align: 'left'
                                },
                                {
                                    text: 'Pieza',
                                    dataIndex: 'cantidad',
                                    width:70,
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
                            afterrender: function(obj){
                                
                            }
                        }
                    }
                ],
                bbar:[       
                    '->',
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
                                solicitar_envios.printGE();
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
                                /*global.permisos({
                                    id: 15,
                                    id_btn: obj.getId(), 
                                    id_menu: gestion_devolucion.id_menu,
                                    fn: ['panel_asignar_gestion.limpiar']
                                });*/
                            },
                            click: function(obj, e){
                                Ext.getCmp(solicitar_envios.id+'-win-gen').close();
                            }
                        }
                    },
                    '-'
                ],
                listeners:{
                    'afterrender':function(obj, e){ 
                        //panel_asignar_gestion.getDatos();
                    },
                    'close':function(){
                        //if(panel_asignar_gestion.guarda!=0)gestion_devolucion.buscar();
                    }
                }
            }).show().center();
        },
        printGE:function(){
            var records_ = new Array();
            var records = Ext.getCmp(solicitar_envios.id+'-gen-grid').getSelection();
            if(records.length <=0){
                global.Msg({
                    msg:"No tiene registros que imprimir",
                    icon:1,
                    fn:function(){
                        
                    }
                });
                return;
            }
            for (i=0; i<=records.length-1; i++) {
                records_.push(records[i].data.gui_numero);
            }
            var recordsx = Ext.encode(records_);
            console.log(recordsx);
            window.open( solicitar_envios.url + 'generate_pdf/?recordsx='+recordsx, '_blank');
        },
        selected_shi:function(obj){

            Ext.getCmp(solicitar_envios.id+'-tipo-servicio').setValue('');
            Ext.getCmp(solicitar_envios.id+'-tipo-servicio').getStore().removeAll();

            Ext.getCmp(solicitar_envios.id+'-centro-actividad').setValue('');
            Ext.getCmp(solicitar_envios.id+'-centro-actividad').getStore().removeAll();

            Ext.getCmp(solicitar_envios.id+'-direccion').setValue('');
            Ext.getCmp(solicitar_envios.id+'-distrito').setValue('');

            Ext.getCmp(solicitar_envios.id+'-remitente').setValue('');
            Ext.getCmp(solicitar_envios.id+'-remitente').getStore().removeAll();

            Ext.getCmp(solicitar_envios.id+'-destinatario-re').setValue('');
            Ext.getCmp(solicitar_envios.id+'-destinatario-re').getStore().removeAll();

            Ext.getCmp(solicitar_envios.id+'-codigo-cliente').setValue('');
            Ext.getCmp(solicitar_envios.id+'-empresa').setValue('');
            
            Ext.getCmp(solicitar_envios.id+'-tipo-servicio').getStore().load(
                {params: {vp_id_linea:3,vp_shi_codigo: obj.getValue('shi_codigo')},
                callback:function(){
                    Ext.getCmp(solicitar_envios.id+'-centro-actividad').getStore().load(
                        {params: {vp_id_linea:3,vp_shi_codigo: obj.getValue('shi_codigo')},
                        callback:function(){ 
                            Ext.getCmp(solicitar_envios.id+'-destinatario-re').getStore().load(
                                {params: {vp_shi_codigo: obj.getValue('shi_codigo')},
                                callback:function(){
                                    Ext.getCmp(solicitar_envios.id+'-dice-contener').setValue('');
                                    Ext.getCmp(solicitar_envios.id+'-dice-contener').getStore().removeAll();
                                    Ext.getCmp(solicitar_envios.id+'-dice-contener').getStore().load(
                                        {params: {vp_shi_codigo:obj.getValue('shi_codigo')},
                                        callback:function(){
                                            
                                        }                                                           
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }
    }
    Ext.onReady(solicitar_envios.init, solicitar_envios);
}else{
    tab.setActiveTab(solicitar_envios.id+'-tab');
}
</script>