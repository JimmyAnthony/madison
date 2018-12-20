<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('shipper-tab')){
		var shipper = {
			id:'shipper',
			id_menu:'<?php echo $p["id_menu"];?>',
			url:'/gestion/shipper/',
			opcion:'I',
			init:function(){
				var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'shi_codigo', type: 'string'},
                    {name: 'shi_nombre', type: 'string'},
                    {name: 'shi_logo', type: 'string'},
                    {name: 'campanas', type: 'string'},

                    {name: 'fec_ingreso', type: 'string'},
                    {name: 'shi_estado', type: 'string'},
                    {name: 'id_user', type: 'string'},
                    {name: 'fecha_actual', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: shipper.url+'get_list_shipper/',
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
			var store_campanas = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'cod_cam', type: 'string'},
                    {name: 'nombre', type: 'string'},
                    {name: 'descripcion', type: 'string'},
                    {name: 'imagen', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: shipper.url+'get_list_campana_shipper/',
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

            this.store_permisos_mac = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'mac_ip', type: 'string'},
                    {name: 'mac_vence', type: 'string'},
                    {name: 'mac_estado', type: 'string'},
                    {name: 'id_mac', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: shipper.url+'get_list_permisos_mac/',
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

			var myData = [
			    [1,'Activo'],
			    [0,'Inactivo']
			];
			var store_estado = Ext.create('Ext.data.ArrayStore', {
		        storeId: 'estado',
		        autoLoad: true,
		        data: myData,
		        fields: ['code', 'name']
		    });

				var panel = Ext.create('Ext.form.Panel',{
					id:shipper.id+'-form',
					bodyStyle: 'background: transparent',
					border:false,
					layout:'border',
					defaults:{
						border:false
					},
					tbar:[],
					items:[
						{
							region:'east',
							border:true,
							width:'30%',
							padding:'5px 5px 5px 5px',
							layout:'border',
							items:[
								{
									region:'north',
									border:false,
									items:[
										{
	                                        xtype: 'fieldset',
	                                        margin: '5 5 5 10',
	                                        title:'<b>Mantenimiento de Cliente</b>',
	                                        border:false,
	                                        bodyStyle: 'background: transparent',
	                                        padding:'2px 5px 1px 5px',
	                                        layout:'column',
	                                        items: [
	                                            {
	                                                columnWidth: 1,border:false,
	                                                padding:'10px 2px 0px 0px',  bodyStyle: 'background: transparent',
	                                                items:[
	                                                    {
	                                                        xtype: 'textfield',
	                                                        fieldLabel: 'Nombre',
	                                                        id:shipper.id+'-txt-nombre',
	                                                        labelWidth:60,
	                                                        //readOnly:true,
	                                                        labelAlign:'right',
	                                                        width:'100%',
	                                                        anchor:'100%'
	                                                    }
	                                                ]
	                                            },
	                                            {
	                                                columnWidth: 0.30,border:false,
	                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
	                                                items:[
	                                                    {
	                                                        xtype:'datefield',
	                                                        id:shipper.id+'-date-re',
	                                                        fieldLabel:'Fecha',
	                                                        labelWidth:60,
	                                                        labelAlign:'right',
	                                                        value:new Date('Y-m-d'),
	                                                        format: 'Y-m-d',
	                                                        width: '100%',
	                                                        anchor:'100%'
	                                                    }
	                                                ]
	                                            },
	                                            {
	                                                columnWidth: 1,border:false,
	                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
	                                                items:[
	                                                	{
	                                                		xtype:'form',
	                                                		id:shipper.id+'-form-info',
	                                                		border:false,
	                                                		items:[
	                                                			{
														            xtype: 'filefield',
														            emptyText: 'Select an image',
														            fieldLabel: 'Imagen',
														            labelAlign:'right',
														            labelWidth:60,
														            name: 'uploadedfile',
														            id:shipper.id+'-imagen_shipper',
														            buttonText: '',
														            buttonConfig: {
														                icon: '/images/icon/upload-file.png',
														            }
														        }
	                                                		]
	                                                	}
	                                                ]
	                                            },
	                                            {
	                                                columnWidth: 1,border:false,
	                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
	                                                items:[
	                                                    {
	                                                    	xtype:'panel',
	                                                    	padding:'10px 60px 10px 60px',
	                                                    	border:true,
	                                                    	height:300,
	                                                    	html:'<div id="GaleryFull" class="links"></div>'
	                                                    }
	                                                ]
	                                            },
	                                            {
                                                columnWidth: 0.50,border:false,
                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                items:[
                                                    {
                                                        xtype:'combo',
                                                        fieldLabel: 'Estado',
                                                        id:shipper.id+'-cmb-estado',
                                                        store: store_estado,
                                                        queryMode: 'local',
                                                        triggerAction: 'all',
                                                        valueField: 'code',
                                                        displayField: 'name',
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
	                                        ]
	                                    }
									],
									bbar:[
										{
					                        xtype:'button',
					                        text: 'Guardar',
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
					                                //shipper.buscar_ge();
					                                shipper.setShipper();
					                            }
					                        }
					                    },
					                    {
					                        xtype:'button',
					                        text: 'Nuevo',
					                        icon: '/images/icon/file.png',
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
					                                //shipper.buscar_ge();
					                                shipper.opcion='I';
					                                shipper.setNuevo();
					                            }
					                        }
					                    }
									]
								},
								{
									region:'center',
									border:false,
									layout:'fit',
									items:[
										{
					                        xtype: 'grid',
					                        id: shipper.id + '-grid-campanas',
					                        store: store_campanas,
					                        columnLines: true,
					                        columns:{
					                            items:[
					                                {
					                                    text: 'Campaña',
					                                    dataIndex: 'nombre',
					                                    flex: 1
					                                },
					                                {
					                                    text: 'Descripcion',
					                                    dataIndex: 'descripcion',
					                                    width: 200
					                                }/*,
					                                {
					                                    text: 'Logo',
					                                    dataIndex: 'shi_logo',
					                                    width: 150
					                                },
					                                {
					                                    text: 'Estado',
					                                    dataIndex: 'shi_estado',
					                                    width: 100,
					                                    align: 'center',
					                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
					                                        return value==1?'Activo':'Inactivo';
					                                    }
					                                },
					                                {
					                                    text: '&nbsp;',
					                                    dataIndex: '',
					                                    width: 30,
					                                    align: 'center',
					                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
					                                        metaData.style = "padding: 0px; margin: 0px";
					                                        return global.permisos({
					                                            type: 'link',
					                                            id_menu: shipper.id_menu,
					                                            icons:[
					                                                {id_serv: 9, img: 'detail.png', qtip: 'Click para ver detalle.', js: 'shipper.getFormDetalleGestion()'}
					                                            ]
					                                        });
					                                    }
					                                }*/
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
								}
							]
						},
						{
							region:'center',
							border:false,
							//layout:'fit',
							items:[
								{
	                                //region:'north',
	                                border:false,
	                                xtype: 'uePanelS',
	                                logo: 'CL',
	                                title: 'Listado de Clientes',
	                                legend: 'Búsqueda de clientes registrados',
	                                height:100,
	                                items:[
	                                    {
	                                        xtype:'panel',
	                                        border:false,
	                                        bodyStyle: 'background: transparent',
	                                        padding:'2px 5px 1px 5px',
	                                        layout:'column',
	                                        items: [
	                                            {
	                                                width:600,border:false,
	                                                padding:'0px 2px 0px 0px',  
	                                                bodyStyle: 'background: transparent',
	                                                items:[
	                                                    {
	                                                        xtype: 'textfield',
	                                                        fieldLabel: 'Cliente',
	                                                        id:shipper.id+'-txt-cliente',
	                                                        labelWidth:80,
	                                                        //readOnly:true,
	                                                        labelAlign:'right',
	                                                        width:'100%',
	                                                        anchor:'100%'
	                                                    }
	                                                ]
	                                            },
	                                            {
	                                                width: 80,border:false,
	                                                padding:'0px 2px 0px 0px',  
	                                                bodyStyle: 'background: transparent',
	                                                items:[
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
									                                //shipper.buscar_ge();
									                            }
									                        }
									                    }
	                                                ]
	                                            },
	                                            {
	                                                width: 80,border:false,
	                                                padding:'0px 2px 0px 0px',  
	                                                bodyStyle: 'background: transparent',
	                                                items:[
	                                                    {
									                        xtype:'button',
									                        text: 'Usuarios',
									                        icon: '/images/icon/contactos.png',
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
									                                //shipper.buscar_ge();
									                                shipper.getUser();
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
									//region:'center',
									width:'100%',
									layout:'fit',
									items:[
										{
					                        xtype: 'grid',
					                        id: shipper.id + '-grid',
					                        store: store,
					                        layout:'fit',
					                        columnLines: true,
					                        columns:{
					                            items:[
					                                {
					                                    text: 'Shipper',
					                                    dataIndex: 'shi_nombre',
					                                    flex: 1
					                                },
					                                {
					                                    text: 'Logo',
					                                    dataIndex: 'shi_logo',
					                                    width: 150
					                                },
					                                {
					                                    text: 'Fecha Ingreso',
					                                    dataIndex: 'fec_ingreso',
					                                    width: 100
					                                },
					                                {
					                                    text: 'N° de Campañas',
					                                    dataIndex: 'campanas',
					                                    width: 100
					                                },
					                                {
					                                    text: 'Fecha de Actualización',
					                                    dataIndex: 'fecha_actual',
					                                    width: 120,
					                                    cls: 'column_header_double',
					                                    align: 'center'
					                                },
					                                {
					                                    text: 'Estado',
					                                    dataIndex: 'shi_estado',
					                                    width: 100,
					                                    align: 'center',
					                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
					                                        return value==1?'Activo':'Inactivo';
					                                    }
					                                },
					                                {
					                                    text: '&nbsp;',
					                                    dataIndex: '',
					                                    width: 30,
					                                    align: 'center',
					                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
					                                        metaData.style = "padding: 0px; margin: 0px";
					                                        return global.permisos({
					                                            type: 'link',
					                                            id_menu: shipper.id_menu,
					                                            icons:[
					                                                {id_serv: 9, img: 'detail.png', qtip: 'Click para ver detalle.', js: 'shipper.getFormDetalleGestion()'}
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
					                            enableTextSelection: false,
					                            markDirty: false
					                        },
					                        trackMouseOver: false,
					                        listeners:{
					                            afterrender: function(obj){
					                                shipper.getImagen('default.png');
					                            },
												beforeselect:function(obj, record, index, eOpts ){
													shipper.opcion='U';
													shipper.shi_codigo=record.get('shi_codigo');
													shipper.getImagen(record.get('shi_logo'));
													Ext.getCmp(shipper.id+'-txt-nombre').setValue(record.get('shi_nombre'));
													Ext.getCmp(shipper.id+'-date-re').setValue(record.get('fec_ingreso'));
													Ext.getCmp(shipper.id+'-cmb-estado').setValue(record.get('shi_estado'));
													shipper.getReloadGridCampanas(record.get('shi_codigo'));
												}
					                        }
					                    }
									]
								}
							]
						}
					]
				});
				tab.add({
					id:shipper.id+'-tab',
					border:false,
					autoScroll:true,
					closable:true,
					layout:{
						type:'fit'
					},
					items:[
						panel
					],
					listeners:{
						beforerender: function(obj, opts){
	                        global.state_item_menu(shipper.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,shipper.id_menu);
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(shipper.id_menu, false);
	                    }
					}

				}).show();
			},
			getImagen:function(param){
				win.getGalery({container:'GaleryFull',width:390,height:250,params:{forma:'F',img_path:'/shipper/'+param}});
			},
			setShipper:function(op){

				global.Msg({
                    msg: '¿Está seguro de guardar?',
                    icon: 3,
                    buttons: 3,
                    fn: function(btn){
                        Ext.getCmp(shipper.id+'-form').el.mask('Cargando…', 'x-mask-loading');

						Ext.getCmp(shipper.id+'-form-info').submit({
		                    url: shipper.url + 'setRegisterShipper/',
		                    params:{
		                        vp_op: shipper.opcion,
		                        vp_shi_codigo:shipper.shi_codigo,
		                        vp_shi_nombre:Ext.getCmp(shipper.id+'-txt-nombre').getValue(),
		                        vp_fec_ingreso:Ext.getCmp(shipper.id+'-date-re').getRawValue(),
		                        vp_estado:Ext.getCmp(shipper.id+'-cmb-estado').getValue()
		                    },
		                    success: function( fp, o ){
		                    	//console.log(o);
		                        var res = o.result;
		                        Ext.getCmp(shipper.id+'-form').el.unmask();
		                        //console.log(res);
		                        if (parseInt(res.error) == 0){
		                            global.Msg({
		                                msg: res.data,
		                                icon: 1,
		                                buttons: 1,
		                                fn: function(btn){
		                                    shipper.getReloadGridShipper('');
		                                    shipper.setNuevo();
		                                }
		                            });
		                        } else{
		                            global.Msg({
		                                msg: 'Ocurrio un error intentalo nuevamente.',
		                                icon: 0,
		                                buttons: 1,
		                                fn: function(btn){
		                                    //shipper.getReloadGridShipper('');
		                                    shipper.setNuevo();
		                                }
		                            });
		                        }
		                    }
		                });
		            }
                });
			},
			getReloadGridShipper:function(name){
				Ext.getCmp(shipper.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				Ext.getCmp(shipper.id + '-grid').getStore().load(
	                {params: {vp_nombre:name},
	                callback:function(){
	                	Ext.getCmp(shipper.id+'-form').el.unmask();
	                }
	            });
			},
			getReloadGridCampanas:function(shi_codigo){
				Ext.getCmp(shipper.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				Ext.getCmp(shipper.id + '-grid-campanas').getStore().load(
	                {params: {shi_codigo:shi_codigo},
	                callback:function(){
	                	Ext.getCmp(shipper.id+'-form').el.unmask();
	                }
	            });
			},
			setNuevo:function(){
				shipper.shi_codigo=0;
				shipper.getImagen('default.png');
				Ext.getCmp(shipper.id+'-txt-nombre').setValue('');
				Ext.getCmp(shipper.id+'-date-re').setValue('');
				Ext.getCmp(shipper.id+'-cmb-estado').setValue('');
				Ext.getCmp(shipper.id+'-txt-nombre').focus();
			},
			getUser:function(){
				var myData = [
				    ['P','Pendiente'],
				    ['0','Inactivo']
				];
				var store_estado = Ext.create('Ext.data.ArrayStore', {
			        storeId: 'estado',
			        autoLoad: true,
			        data: myData,
			        fields: ['code', 'name']
			    });

				Ext.create('Ext.window.Window',{
	                id:shipper.id+'-win-form',
	                plain: true,
	                title:'Formulario',
	                icon: '/images/icon/contactos.png',
	                height: 400,
	                width: 450,
	                resizable:false,
	                modal: true,
	                border:false,
	                closable:true,
	                padding:20,
	                items:[
	                	{
	                        xtype: 'grid',
	                        id: shipper.id + '-grid-campanas_user',
	                        store: shipper.store_permisos_mac,
	                        columnLines: true,
	                        columns:{
	                            items:[
	                                {
	                                    text: 'IMEI',
	                                    dataIndex: 'mac_ip',
	                                    flex: 1
	                                },
	                                {
	                                    text: 'Fecha Vigencia',
	                                    dataIndex: 'mac_vence',
	                                    width: 80
	                                },
	                                {
	                                    text: 'Estado',
	                                    dataIndex: 'mac_estado',
	                                    width: 80
	                                },
	                                {
	                                    text: '&nbsp;',
	                                    dataIndex: '',
	                                    width: 30,
	                                    align: 'center',
	                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
	                                        metaData.style = "padding: 0px; margin: 0px";
	                                        return global.permisos({
	                                            type: 'link',
	                                            id_menu: shipper.id_menu,
	                                            icons:[
	                                                {id_serv: 1, img: 'detail.png', qtip: 'Click cambiar estado.', js: 'shipper.setChangeEstado('+record.get('id_mac')+')'}
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
	                        text: 'Actualizar',
	                        icon: '/images/icon/search.png',
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
	                            	shipper.getReloadUser();
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
	                                Ext.getCmp(shipper.id+'-win-form').close();
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
			getReloadUser:function(){
				Ext.getCmp(shipper.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				Ext.getCmp(shipper.id + '-grid-campanas_user').getStore().load(
	                {params: {},
	                callback:function(){
	                	Ext.getCmp(shipper.id+'-form').el.unmask();
	                }
	            });
			},
			setChangeEstado:function(id){
				global.Msg({
                    msg: '¿Está seguro de cambiar autorizar el ingreso?',
                    icon: 3,
                    buttons: 3,
                    fn: function(btn){
                        Ext.getCmp(shipper.id+'-form').el.mask('Cargando…', 'x-mask-loading');

						Ext.getCmp(shipper.id+'-form-info').submit({
		                    url: shipper.url + 'setChangeEstado/',
		                    params:{
		                        id_mac: id
		                    },
		                    success: function( fp, o ){
		                    	//console.log(o);
		                        var res = o.result;
		                        Ext.getCmp(shipper.id+'-form').el.unmask();
		                        //console.log(res);
		                        if (parseInt(res.error) == 0){
		                            global.Msg({
		                                msg: res.data,
		                                icon: 1,
		                                buttons: 1,
		                                fn: function(btn){
		                                    shipper.getReloadUser();
		                                }
		                            });
		                        } else{
		                            global.Msg({
		                                msg: 'Ocurrio un error intentalo nuevamente.',
		                                icon: 0,
		                                buttons: 1,
		                                fn: function(btn){
		                                    
		                                }
		                            });
		                        }
		                    }
		                });
		            }
                });
			}
		}
		Ext.onReady(shipper.init,shipper);
	}else{
		tab.setActiveTab(shipper.id+'-tab');
	}
</script>