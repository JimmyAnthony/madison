<script type="text/javascript">


var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('panel_novedades-tab')){
    var panel_novedades = {
        id: 'panel_novedades',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/novedades/',
        id_nov:0,
        fil:0,
        idx:-1,
        type:'',
        view:{},
        record:{},
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'nov_id', type: 'int'},
                    {name: 'line', type: 'string'},
                    {name: 'class_line', type: 'string'},
                    {name: 'msn', type: 'string'},
                    {name: 'fecha', type: 'string'},
                    {name: 'clase', type: 'string'},
                    {name: 'documento', type: 'string'},
                    {name: 'doc_numero', type: 'string'},
                    {name: 'estado', type: 'string'},
                    {name: 'file_nombre', type: 'string'},
                    {name: 'agencia', type: 'int'},
                    {name: 'agencia_origen', string: 'string'},
                    {name: 'doc_id', type: 'int'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',

                    url: panel_novedades.url+'get_scm_lista_novedades/',
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

            var linea_ = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id', type: 'int'},
                    {name: 'nombre', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',

                    url: panel_novedades.url+'get_scm_linea/',
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
            var check_ = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id_elemento', type: 'int'},
                    {name: 'descripcion', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',

                    url: panel_novedades.url+'get_scm_check_novedad/?vp_tipo=NOV',
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
            var motivo_ = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'tnov_id', type: 'int'},
                    {name: 'tnov_descri', type: 'string'},
                    {name: 'mnov_publico', type: 'int'},
                    {name: 'mnov_priori', type: 'int'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',

                    url: panel_novedades.url+'get_scm_motivo/',
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
            var tipo_doc_ = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id_elemento', type: 'int'},
                    {name: 'descripcion', type: 'string'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',

                    url: panel_novedades.url+'get_scm_check_novedad/?vp_tipo=NTD',
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
            var provincias_ = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'prov_codigo', type: 'int'},
                    {name: 'prov_nombre', type: 'string'},
                    {name: 'prov_sigla', type: 'string'}
                ],
                autoLoad:false,
                proxy:{
                    type: 'ajax',

                    url: panel_novedades.url+'get_scm_provincias/',
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
            var shipper_= Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'shi_codigo', type: 'int'},
                    {name: 'shi_nombre', type: 'string'},
                    {name: 'shi_id', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: panel_novedades.url+'get_scm_shipper/',
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

            var imageTplPointerPanel = new Ext.XTemplate(
                '<tpl for=".">',
                    '<div class="databox_panel_fill" >',
                        '<div class="databox_nodedad_panel" >',
                            '<div class="">NV',
                            
                            '</div>',
                        '</div>',
                        '<div class="databox_mensage" >',
                            '<div class="databox_bar">',
                                '<div class="databox_title">',
                                    '<span>PANEL DE NOVEDADES</span>',
                                '</div>',
                                '<div class="databox_date"><span class=""></span></div>',
                            '</div>',
                            '<div class="databox_message"></div>',
                        '</div>',
                        '<div class="databox_btools">',
                            '<hr></hr>',
                            '<span><p>PROVINCIA:</p></span>',
                            '<hr></hr>',
                            '<span><p>DOC:</p></span><span><p>TIPO:</p></span>',
                            '<hr></hr>',
                            '<span><p>PROCESO:</p></span><span></span>',
                            '<hr></hr>',
                            '<span><p>MOTIVO:</p></span><span></span>',
                            '<hr></hr>',
                            '<span><p>TIPO NOVEDAD:</p></span><span></span>',
                            '<hr></hr>',
                            '<span><p>ESTADO:</p></span><span></span>',
                            '<hr></hr>',
                        '</div>',
                    '</div>',
                '</tpl>'
            );
            
            //console.log(imageTplPointerPanel);
            var panel = Ext.create('Ext.form.Panel',{
                id: panel_novedades.id+'-form',
                border:false,
                layout: 'border',
                defaults:{
                    border: false
                },
                items:[
                    {
                        region:'west',
                        //frame:true,
                        width:370,
                        border:false,
                        split:true,
                        layout:'fit',
                        items:[
                            {
                                xtype:'panel',
                                layout:'border',
                                border:false,
                                items:[
                                    {
                                        region:'north',
                                        border:false,
                                        id: panel_novedades.id+'-panel-filtro',
                                        bodyStyle: 'background: #fff',
                                        frame:true,
                                        split:false,
                                        layout:'fit',
                                        height:0,
                                        items:[
                                            {
                                                xtype:'fieldset',
                                                region:'north',
                                                title:'Parametros de Busqueda',
                                                bodyStyle: 'background: #fff',
                                                border:true,
                                                bodyStyle: 'background: transparent',
                                                margin:0,
                                                frame:true,
                                                margin:5,
                                                items:[
                                                    {
                                                        xtype:'panel',
                                                        //xtype:'compositefield',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'0px 0px 4px 0px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: .50,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype:'datefield',
                                                                        fieldLabel: 'Desde',
                                                                        id:panel_novedades.id+'-nov-desde',
                                                                        labelWidth:60,
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        value:'<?php echo date("d/m/Y");?>'
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .50,border:false,
                                                                padding:'0px 2px 0px 12px', 
                                                                bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype:'datefield',
                                                                        fieldLabel: 'Hasta',
                                                                        id:panel_novedades.id+'-nov-hasta',
                                                                        labelWidth:40,
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        value:'<?php echo date("d/m/Y");?>'
                                                                    }
                                                                ]
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype:'panel',
                                                        //xtype:'compositefield',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'0px 0px 4px 0px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: 1,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype:'combo',
                                                                        fieldLabel: 'Linea.',
                                                                        id:panel_novedades.id+'-nov-linea',
                                                                        store: linea_,
                                                                        queryMode: 'local',
                                                                        triggerAction: 'all',
                                                                        valueField: 'id',
                                                                        displayField: 'nombre',
                                                                        emptyText: '[Seleccione]',
                                                                        //allowBlank: false,
                                                                        labelWidth: 60,
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        //readOnly: true,
                                                                        listeners:{
                                                                            afterrender:function(obj, e){
                                                                                // obj.getStore().load();
                                                                            },
                                                                            select:function(obj, records, eOpts){
                                                                                var linea=Ext.getCmp(panel_novedades.id+'-nov-linea').getValue();
                                                                                
                                                                                Ext.getCmp(panel_novedades.id+'-nov-shipper').setValue('');
                                                                                Ext.getCmp(panel_novedades.id+'-nov-shipper').getStore().removeAll();
                                                                                Ext.getCmp(panel_novedades.id+'-nov-shipper').getStore().load(
                                                                                    {params: {vp_linea: obj.getValue('id')},
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
                                                        //xtype:'compositefield',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'0px 0px 4px 0px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: 1,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype:'combo',
                                                                        fieldLabel: 'Shipper',
                                                                        id:panel_novedades.id+'-nov-shipper',
                                                                        store: shipper_,
                                                                        queryMode: 'local',
                                                                        triggerAction: 'all',
                                                                        valueField: 'shi_codigo',
                                                                        displayField: 'shi_nombre',
                                                                        emptyText: '[Seleccione]',
                                                                        //allowBlank: false,
                                                                        labelWidth: 60,
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        listConfig:{
                                                                            minWidth: 200
                                                                        },
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
                                                        //xtype:'compositefield',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'0px 0px 4px 0px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: 1,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype:'combo',
                                                                        fieldLabel: 'Proceso.',
                                                                        id:panel_novedades.id+'-nov-referente',
                                                                        store: check_,
                                                                        queryMode: 'local',
                                                                        triggerAction: 'all',
                                                                        valueField: 'id_elemento',
                                                                        displayField: 'descripcion',
                                                                        emptyText: '[Seleccione]',
                                                                        //allowBlank: false,
                                                                        labelWidth: 60,
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        listConfig:{
                                                                            minWidth: 200
                                                                        },
                                                                        //readOnly: true,
                                                                        listeners:{
                                                                            afterrender:function(obj, e){
                                                                                // obj.getStore().load();
                                                                            },
                                                                            select:function(obj, records, eOpts){
                                                                                Ext.getCmp(panel_novedades.id+'-nov-motivo').setValue('');
                                                                                Ext.getCmp(panel_novedades.id+'-nov-motivo').getStore().removeAll();
                                                                                Ext.getCmp(panel_novedades.id+'-nov-motivo').getStore().load(
                                                                                    {params: {vp_pnov_id: obj.getValue('id_elemento')},
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
                                                        //xtype:'compositefield',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'0px 0px 4px 0px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: 1,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype:'combo',
                                                                        fieldLabel: 'Motivo.',
                                                                        id:panel_novedades.id+'-nov-motivo',
                                                                        store: motivo_,
                                                                        queryMode: 'local',
                                                                        triggerAction: 'all',
                                                                        valueField: 'tnov_id',
                                                                        displayField: 'tnov_descri',
                                                                        emptyText: '[Seleccione]',
                                                                        //allowBlank: false,
                                                                        labelWidth: 60,
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        listConfig:{
                                                                            minWidth: 200
                                                                        },
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
                                                        //xtype:'compositefield',
                                                        border:false,
                                                        hidden:true,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'0px 0px 4px 0px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: .50,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype:'combo',
                                                                        fieldLabel: 'Provincia',
                                                                        id:panel_novedades.id+'-provincia',
                                                                        store: provincias_,
                                                                        queryMode: 'local',
                                                                        triggerAction: 'all',
                                                                        valueField: 'prov_codigo',
                                                                        displayField: 'prov_nombre',
                                                                        emptyText: '[Seleccione]',
                                                                        //allowBlank: false,
                                                                        listConfig:{
                                                                            minWidth: 200
                                                                        },
                                                                        labelWidth: 60,
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        //disabled: true,
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
                                                                columnWidth: .50,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype:'combo',
                                                                        fieldLabel: 'T.Doc.',
                                                                        id:panel_novedades.id+'-nov-tipo_doc',
                                                                        store: tipo_doc_,
                                                                        queryMode: 'local',
                                                                        triggerAction: 'all',
                                                                        valueField: 'id_elemento',
                                                                        displayField: 'descripcion',
                                                                        emptyText: '[Seleccione]',
                                                                        //allowBlank: false,
                                                                        labelWidth: 38,
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        //readOnly: true,
                                                                        listConfig:{
                                                                            minWidth: 200
                                                                        },
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
                                                        //xtype:'compositefield',
                                                        hidden:true,
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'0px 0px 4px 0px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: 1,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'textfield',
                                                                        id:panel_novedades.id+'-nov-nro-doc',
                                                                        fieldLabel: 'Nro. Doc.',
                                                                        labelWidth:60,
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        listeners: {
                                                                            specialkey: function(f,e){
                                                                                if(e.getKey() == e.ENTER){
                                                                                    panel_novedades.buscar_novedad();
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                ]
                                                            }
                                                        ]
                                                    }
                                                ]
                                            }
                                        ],
                                        bbar:[
                                            '->',
                                            '-',
                                            {
                                                xtype: 'button',
                                                text:'Limpiar',
                                                icon: '/images/icon/new_file.ico',
                                                border:false,
                                                listeners:{
                                                    click: function(obj, e){
                                                        panel_novedades.limpiar_filtro(); 
                                                    }
                                                }
                                            },
                                            '-',
                                            {
                                                xtype: 'button',
                                                text:'Buscar',
                                                icon: '/images/icon/binocular.png',
                                                border:false,
                                                listeners:{
                                                    click: function(obj, e){
                                                        panel_novedades.reload_novedad(); 
                                                    }
                                                }
                                            },
                                            '-'
                                        ]
                                    },
                                    {
                                        region:'center',
                                        //frame:true,
                                        border:false,
                                        id:panel_novedades.id+'-panel-novedades',
                                        layout:'fit',
                                        tbar:[
                                            {
                                                xtype:'panel',
                                                border:false,
                                                height:34,
                                                width:'100%',
                                                anchor:'100%',
                                                baseCls:'databox_menu_novedad_titulo',
                                                html:'<div class="databox_ttl">NOVEDADES<div class="databox_add_novedad"><a href="#" onclick="panel_novedades.add_novedad();"><em></em></a></div><div class="databox_menu_novedad"><a href="#" onclick="panel_novedades.filtro();"><em></em></a></div></div>'
                                            }
                                        ],
                                        items:[
                                            {
                                                xtype:'GridNovedades',
                                                id:panel_novedades.id,
                                                url:panel_novedades.url,
                                                front:0,
                                                autoLoad:true,
                                                /*msn:1,
                                                hist:1,*/
                                                records:panel_novedades.load_records
                                            }
                                        ]
                                    }
                                ]
                            }
                        ],
                        bbar:[
                            '-',
                            {
                                xtype: 'checkboxfield',
                                fieldLabel: '',
                                //labelWidth: 90,
                                boxLabel: 'Solo Novedades Nuevas',
                                id:panel_novedades.id+'_chk_nv_new',
                                listeners:{
                                    change:function(obj){
                                        panel_novedades.reload_novedad();
                                    }
                                }
                            },
                            '-',
                            {
                                xtype: 'checkboxfield',
                                //fieldLabel: 'Mis Novedades',
                                //labelWidth: 90,
                                boxLabel: 'Mis Novedades',
                                id:panel_novedades.id+'_chk_mis_novedades',
                                listeners:{
                                    change:function(obj){
                                        panel_novedades.reload_novedad();
                                    }
                                }
                            },
                            '-'
                        ]
                    },
                    {
                        region:'center',
                        //frame:true,
                        border:true,
                        layout:'fit',
                        /*tbar:[
                            '->',
                            '-',
                            {
                                xtype: 'button',
                                text:'Buscar',
                                icon: '/images/icon/binocular.png',
                                border:false,
                                listeners:{
                                    click: function(obj, e){
                                        panel_novedades.reload_novedad(); 
                                    }
                                }
                            },
                            '-'
                        ],*/
                        items:[
                            {
                                xtype:'GridNovedadesComentarios',
                                id:panel_novedades.id,
                                url:panel_novedades.url,
                                closed:1
                            }
                        ]
                    },
                    {
                        region:'east',
                        //frame:true,
                        width:355,
                        border:false,
                        split:true,
                        layout:'fit',
                        items:[
                            {
                                xtype:'panel',
                                layout:'border',
                                border:false,
                                items:[
                                    {
                                        region:'north',
                                        border:false,
                                        id:panel_novedades.id+'-panel-detalle',
                                        frame:true,
                                        split:true,
                                        layout:'fit',
                                        height:245,
                                        html:imageTplPointerPanel
                                    },
                                    {
                                        region:'center',
                                        frame:true,
                                        border:false,
                                        layout:'fit',
                                        tbar:[
                                            {
                                                xtype:'panel',
                                                border:false,
                                                height:30,
                                                width:'100%',
                                                anchor:'100%',
                                                baseCls:'databox_nodedad_titulo_hst',
                                                html:'NOVEDADES RELACIONADAS'
                                            }
                                        ],
                                        items:[
                                            {
                                                xtype:'GridNovedadesHistorico',
                                                id:panel_novedades.id,
                                                url:panel_novedades.url,
                                                records:panel_novedades.load_records
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
                id: panel_novedades.id+'-tab',
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
                        global.state_item_menu(panel_novedades.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        /*Ext.getCmp(panel_novedades.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + panel_novedades.id_menu).text,
                            icon: Ext.getCmp('menu-' + panel_novedades.id_menu).icon
                        });*/
                        global.state_item_menu_config(obj,panel_novedades.id_menu);
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(panel_novedades.id+'-tab',panel_novedades.id_menu, false);
                    }
                }
            }).show();
        },
        filtro:function(){
            if(panel_novedades.fil==0){
                panel_novedades.fil = 1;
                Ext.getCmp(panel_novedades.id+'-panel-filtro').setSize(0,215);
            }else{
                panel_novedades.fil=0;
                Ext.getCmp(panel_novedades.id+'-panel-filtro').setSize(0,0);
            }
            Ext.getCmp(panel_novedades.id+'-panel-novedades').doLayout();
            Ext.getCmp(panel_novedades.id+'-panel-filtro').doLayout();
        },
        add_novedad:function(){
            var obj = new Ext.global.plugin.RegistroNovedades();
            obj.show_novedad();
        },
        reload_novedad:function(){

            var vp_id_nov = 0;
            var vp_desde = Ext.getCmp(panel_novedades.id+'-nov-desde').getRawValue();
            var vp_hasta = Ext.getCmp(panel_novedades.id+'-nov-hasta').getRawValue();
            var vp_linea = Ext.getCmp(panel_novedades.id+'-nov-linea').getValue();
            var vp_prov_codigo = Ext.getCmp(panel_novedades.id+'-provincia').getValue();
            var vp_pnov_id = Ext.getCmp(panel_novedades.id+'-nov-referente').getValue();
            var vp_tnov_id = Ext.getCmp(panel_novedades.id+'-nov-motivo').getValue();
            var vp_tdoc_id = Ext.getCmp(panel_novedades.id+'-nov-tipo_doc').getValue();
            var vp_doc_numero = Ext.getCmp(panel_novedades.id+'-nov-nro-doc').getValue();
            var vp_shi_codigo = Ext.getCmp(panel_novedades.id+'-nov-shipper').getValue();
            var vp_new = Ext.getCmp(panel_novedades.id+'_chk_nv_new').getValue();
            var vp_you = Ext.getCmp(panel_novedades.id+'_chk_mis_novedades').getValue();
            var vp_activas = (vp_new)?1:0;
            var vp_you = (vp_you)?1:0;

            Ext.getCmp(panel_novedades.id+'-nov-lista').getStore().removeAll();
            Ext.getCmp(panel_novedades.id+'-nov-lista').getStore().load(
                {
                params: {
                    vp_id_nov:vp_id_nov,vp_desde:vp_desde,vp_hasta:vp_hasta,
                    vp_pnov_id:vp_pnov_id,vp_tnov_id:vp_tnov_id,vp_tdoc_id:vp_tdoc_id,
                    vp_doc_numero:vp_doc_numero,vp_activas:vp_activas,vp_prov_codigo:vp_prov_codigo,
                    vp_id_linea:vp_linea,vp_shi_codigo:vp_shi_codigo,vp_you:vp_you,front:0
                },
                callback:function(){
                    /*if(params.msn)me.remove_novedad();
                    if(params.hist)me.remove_historico();*/
                }
            });
        },
        download_novedad:function(id_file){
            if(parseInt(id_file)==0)return;
            document.location.href=panel_novedades.url+'get_forzar_descarga/?vp_id_file='+id_file;
        },
        elimina_novedad:function(msn_id,id_nov,flag){
            if(parseInt(flag)==0)return;
            if(msn_id== null || msn_id==''){
                global.Msg({msg:"Mensaje no existe.",icon:2,fn:function(){}});
                return false;
            }
            global.Msg({
                msg: '¿Seguro de eliminar comentario?',
                icon: 3,
                buttons: 3,
                fn: function(btn){
                    if (btn == 'yes'){
                        Ext.Ajax.request({
                            url:panel_novedades.url+'set_scm_elimina_comentario',
                            params:{vp_msn_id:msn_id},
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
                                            panel_novedades.reload_comentarios(id_nov);
                                        }
                                    });
                                    

                                }
                            }
                        });
                    }
                }
            });
        },
        reload_comentarios:function(id_nov){
            var obj = panel_novedades.view;
            Ext.getCmp(panel_novedades.id+'-nov-list_comentarios').getStore().removeAll();
            Ext.getCmp(panel_novedades.id+'-nov-list_comentarios').getStore().load(
                {params: {vp_id_nov: id_nov},
                callback:function(){
                    Ext.getCmp(panel_novedades.id+'-nov-list_comentarios').refresh();
                    obj.all.elements[panel_novedades.idx].childNodes[2].className = 'databox_status_off';
                    setTimeout( "panel_novedades.remove_visto()", 2000 );
                    if(panel_novedades.type!='H')panel_novedades.reload_historico();
                }
            });
        },
        reload_historico:function(){
            me_ = this;
            Ext.getCmp(panel_novedades.id+'-nov-list_historico').refresh();
            Ext.getCmp(panel_novedades.id+'-nov-list_historico').getStore().removeAll();
            if(panel_novedades.record.data.doc_numero!='' ){
                var obj = Ext.getCmp(panel_novedades.id+'-nov-list_historico');
                Ext.getCmp(panel_novedades.id+'-nov-list_historico').getStore().load(
                    {params: {vp_id_nov: panel_novedades.record.data.id_nov,vp_doc_numero:panel_novedades.record.data.doc_numero},
                    callback:function(){
                        Ext.getCmp(panel_novedades.id+'-nov-list_historico').refresh();
                    }
                });
            }
        },
        remove_visto:function(){
            var obj_ = Ext.getCmp(panel_novedades.id+'-nov-list_comentarios');
            Ext.Object.each(obj_.all.elements, function(index, v){
                obj_.all.elements[index].childNodes[2].className = 'databox_status_msn_off';
            });
        },
        datos_novedad:function(){
            var items = Ext.getCmp(panel_novedades.id+'-nov-lista').getSelection();
            /*Ext.get('box_titulo').setHtml(items[0].data.titulo);
            Ext.get('box_user').setHtml(items[0].data.titulo);
            Ext.get('box_fecha').setHtml(items[0].data.fecha);
            Ext.get('box_msn').setHtml(items[0].data.msn);*/
        },
        limpiar_filtro:function(){
            Ext.getCmp(panel_novedades.id+'-nov-linea').setValue('');
            Ext.getCmp(panel_novedades.id+'-provincia').setValue('');
            Ext.getCmp(panel_novedades.id+'-nov-referente').setValue('');
            Ext.getCmp(panel_novedades.id+'-nov-motivo').setValue('');
            Ext.getCmp(panel_novedades.id+'-nov-shipper').setValue('');
            Ext.getCmp(panel_novedades.id+'-nov-tipo_doc').setValue('');
            Ext.getCmp(panel_novedades.id+'-nov-nro-doc').setValue('');
        },
        load_msn:function(record){
            Ext.getCmp(panel_novedades.id+'-nov-list_comentarios').getStore().removeAll();
            Ext.getCmp(panel_novedades.id+'-nov-list_comentarios').getStore().load(
                {params: {vp_id_nov: id_nov},
                callback:function(){
                    
                }
            });
        },
        load_records:function(view, record, item, idx, event, opts, type){
            panel_novedades.record=record;
            panel_novedades.idx = idx;
            panel_novedades.type=type;
            panel_novedades.view=view;
            panel_novedades.reload_comentarios(record.data.id_nov);

            if(parseInt(record.data.publico)==0){
                Ext.getCmp(panel_novedades.id+'_chk_public_cmt').setValue(false);
                Ext.getCmp(panel_novedades.id+'_chk_public_cmt').setDisabled(true);
            }

            if(parseInt(record.data.nov_estado)==2){
                Ext.getCmp(panel_novedades.id+'-nov-cerrar-cmt').setDisabled(true);
                Ext.getCmp(panel_novedades.id+'-nov-grabar-cmt').setDisabled(true);
            }else{
                Ext.getCmp(panel_novedades.id+'-nov-cerrar-cmt').setDisabled(false);
                Ext.getCmp(panel_novedades.id+'-nov-grabar-cmt').setDisabled(false);
            }

            var imageTplPointerPanel = new Ext.XTemplate(
                '<tpl for=".">',
                    '<div class="databox_panel_fill" >',
                        '<div class="databox_nodedad_panel" >',
                            '<div class="">NV',
                            
                            '</div>',
                        '</div>',
                        '<div class="databox_mensage" >',
                            '<div class="databox_bar">',
                                '<div class="databox_title">',
                                    '<span>'+record.data.titulo+'</span>',
                                '</div>',
                                '<div class="databox_date"><span class="dbx_user">'+record.data.usr_codigo+'</span><span class="dbx_fecha">'+record.data.fecha+'</span></div>',
                            '</div>',
                            '<div class="databox_message">'+record.data.msn+'</div>',
                        '</div>',
                        '<div class="databox_btools">',
                            '<hr></hr>',
                            '<span><p>PROVINCIA:</p>'+record.data.provincia+'</span>',
                            '<hr></hr>',
                            '<span><p>DOC:</p>'+record.data.doc_numero+'</span><span><p>TIPO:</p>'+record.data.tipo_doc+'</span>',
                            '<hr></hr>',
                            '<span><p>PROCESO:</p></span><span>'+record.data.checkd+'</span>',
                            '<hr></hr>',
                            '<span><p>MOTIVO:</p></span><span>'+record.data.motivo+'</span>',
                            '<hr></hr>',
                            '<span><p>TIPO NOVEDAD:</p></span><span>'+record.data.tipo_novedad+'</span>',
                            '<hr></hr>',
                            '<span><p>ESTADO:</p></span><span>'+record.data.estado_decripcion+'</span>',
                            '<hr></hr>',
                        '</div>',
                    '</div>',
                '</tpl>'
            );
            Ext.getCmp(panel_novedades.id+'-panel-detalle').setHtml(imageTplPointerPanel);
        }
    }
    Ext.onReady(panel_novedades.init, panel_novedades);
}else{
    tab.setActiveTab(panel_novedades.id+'-tab');
}
</script>
