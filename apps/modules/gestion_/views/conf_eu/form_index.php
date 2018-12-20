<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('conf_eu-tab')){
		var conf_eu = {
			id:'conf_eu',
			id_menu: '<?php echo $p["id_menu"];?>',
			url: '/gestion/conf_eu/',
			init:function(){
				var panel = Ext.create('Ext.form.Panel',{
					border:false,
					layout:'fit',
					defaults:{
						border:false
					},
					tbar:[//
							{
			                    xtype: 'segmentedbutton',
			                    vertical: false,
			                    items: [
					                    {
					                        text: '',
					                        icon: '/images/icon/time2_01.png',
					                        listeners:{
					                        	click:function(obj){
					                        		//conf_eu.show_horario();
					                        		//Ext.getCmp(conf_eu.id+'-turnos').setVisible(true);
					                        		conf_eu.pressed(1);
					                        	}
					                        }
					                    },
					                    {
					                        text: '',
					                        icon: '/images/icon/car.png',
					                        listeners:{
					                        	click:function(obj){
					                        		//conf_eu.show_unidades();
					                        		//Ext.getCmp(conf_eu.id+'-unidades').setVisible(true);
					                        		conf_eu.pressed(2);
					                        	}
					                        }
					                    },
					                    {
					                        text: '',
					                        icon:'/images/icon/Phone.png',
					                        listeners:{
					                        	click:function(obj){
					                        		//conf_eu.show_celulares();
					                        		//Ext.getCmp(conf_eu.id+'-celulares').setVisible(true);
					                        		conf_eu.pressed(3);
					                        	}
					                        }
					                    },
					                    {
					                        text: '',
					                        icon:'/images/icon/exec.gif',
					                        pressed:true,
					                        listeners:{
					                        	click:function(obj){
					                        		//Ext.getCmp(conf_eu.id+'-configurar').setVisible(true);
					                        		conf_eu.pressed(4);
					                        	}
					                        }
					                        //pressed: true
					                    }
					            ]        
			                },
			                {xtype:'tbspacer',width:80 },
			                {
			                	xtype:'segmentedbutton',
			                	id:conf_eu.id+'-btns-horario',
			                	vertical:false,
			                	allowToggle:false,
			                	hidden:true,
			                	items:[
			                			{
					                        text: 'Agregar Horario',
					                        icon: '/images/icon/add.png',
					                        listeners:{
					                        	click:function(obj){
					                        		conf_eu.show_horario();
					                        	}
					                        }
					                    }
			                	]
			                },
			                {
			                	xtype:'fieldcontainer',
			                	layout: 'hbox',
			                	id:conf_eu.id+'-btns-unidades',
			                	hidden:true,
			                	items:[
			                			
			                			{
			                				xtype:'combo',
			                				id:conf_eu.id+'-und-provincia',
			                				fieldLabel:'Provincia',
			                				labelWidth:60,
			                			    store: Ext.create('Ext.data.Store',{
				                            fields:[
					                                {name: 'prov_codigo', type: 'int'},
					                                {name: 'prov_nombre', type: 'string'}
					                            ],
					                            proxy:{
					                                type: 'ajax',
					                                url: conf_eu.url + 'get_usr_sis_provincias/',
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
					                        width: 200,
					                        forceSelection: true,
					                       //allowBlank: false,
					                        selectOnFocus:true,
					                        emptyText: '[ Seleccione ]',
					                        listeners:{
					                            afterrender: function(obj,record,options){
					                                obj.getStore().load({
					                                    params:{vp_id_linea:0},
					                                    callback: function(){
					                                     obj.setValue('<?php echo PROV_CODIGO;?>');  
					                                    }
					                                });
					                            }
					                        }
			                			},{xtype:'tbspacer',width:10 },
			                			{
			                				xtype:'textfield',
			                				id:conf_eu.id+'-und-placa',
			                				fieldLabel:'Placa',
			                				labelWidth:30
			                			},{xtype:'tbspacer',width:10 },
			                			{
			                				xtype:'combo',
			                				id:conf_eu.id+'-und-tipo',
			                				fieldLabel:'Tipo Unidad',
			                				labelWidth:70,
			                				store: Ext.create('Ext.data.Store',{
					                            fields:[
					                                {name: 'descripcion', type: 'string'},
					                                {name: 'id_elemento', type: 'int'},
					                                {name: 'des_corto', type: 'string'}
					                            ],
					                            proxy:{
					                                type: 'ajax',
					                                url: conf_eu.url + 'get_scm_tabla_detalle/',
					                                reader:{
					                                    type: 'json',
					                                    root: 'data'
					                                }
					                            }
					                        }),
					                        queryMode: 'local',
					                        triggerAction: 'all',
					                        valueField: 'id_elemento',
					                        displayField: 'descripcion',
					                        listConfig:{
					                            minWidth: 200
					                        },
					                        width: 200,
					                        forceSelection: true,
					                        emptyText: '[ Seleccione ]',
					                        listeners:{
					                            afterrender: function(obj, e){
					                                obj.getStore().load({
					                                    params:{
					                                        vp_tab_id: 'TUN',
					                                        vp_shipper: 0
					                                    },
					                                    callback: function(){
					                                        obj.setValue(0);
					                                    }
					                                });
					                            }
					                        }
			                			},{xtype:'tbspacer',width:10 },
			                			{
						                	xtype:'segmentedbutton',
						                	vertical:false,
						                	allowToggle:false,
						                	items:[
						                			{
								                        text: '',
								                        icon: '/images/icon/search.png',
								                        listeners:{
								                        	click:function(obj){
								                        		conf_eu.busca_unidad();
								                        	}
								                        }
								                    },
								                    {
								                        text: '',
								                        icon: '/images/icon/add.png',
								                        listeners:{
								                        	click:function(obj){
								                        		conf_eu.show_unidades();
								                        	}
								                        }
								                    }
						                	]
						                },
			                			
			                	]

			                },
			                {
			                	xtype:'fieldcontainer',
			                	layout:'hbox',
			                	id:conf_eu.id+'-btns-celulares',
			                	hidden:true,
			                	items:[
			                			{
			                				xtype:'textfield',
			                				id:conf_eu.id+'-cel-imei',
			                				fieldLabel:'IMEI',
			                				labelWidth:30
			                			},{xtype:'tbspacer',width:10 },
			                			{
			                				xtype:'textfield',
			                				id:conf_eu.id+'-cel-celulares',
			                				fieldLabel:'Celular',
			                				labelWidth:60
			                			},{xtype:'tbspacer',width:10 },
			                			{
			                				xtype:'segmentedbutton',
			                				vertical:false,
						                	allowToggle:false,
						                	items:[
						                			{
								                        text: '',
								                        icon: '/images/icon/search.png',
								                        listeners:{
								                        	click:function(obj){
								                        		conf_eu.buscar_celulares();
								                        	}
								                        }
								                    },
								                    {
								                        text: '',
								                        icon: '/images/icon/add.png',
								                        listeners:{
								                        	click:function(obj){
								                        		conf_eu.show_celulares();
								                        	}
								                        }
								                    }
						                	]
			                			}
			                	]
			                },
			                {
			                	xtype:'fieldcontainer',
			                	layout:'hbox',
			                	hidden:true,
			                	id:conf_eu.id+'-btn-configurar',
			                	items:[
			                			{
					                        xtype: 'combo',
					                        id: conf_eu.id + '-conf-provincia',
					                        fieldLabel:'Provincia',
					                        labelWidth:60,
					                        store: Ext.create('Ext.data.Store',{
					                            fields:[
					                                {name: 'prov_codigo', type: 'int'},
					                                {name: 'prov_nombre', type: 'string'}
					                            ],
					                            proxy:{
					                                type: 'ajax',
					                                url: conf_eu.url + 'get_usr_sis_provincias/',
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
					                        width: 200,
					                        forceSelection: true,
					                       //allowBlank: false,
					                        selectOnFocus:true,
					                        emptyText: '[ Seleccione ]',
					                        listeners:{
					                            afterrender: function(obj,record,options){
					                                obj.getStore().load({
					                                    params:{vp_id_linea:0},
					                                    callback: function(){
					                                     obj.setValue(0);  

					                                    }
					                                });
					                            }
					                        }
					                    },{xtype:'tbspacer',width:10 },
			                			{
			                				xtype:'textfield',
			                				id:conf_eu.id+'-rrhh',
			                				fieldLabel:'RRHH',
			                				labelWidth:40,
			                				width:100
			                			},{xtype:'tbspacer',width:10 },
			                			{
			                				xtype:'textfield',
			                				id:conf_eu.id+'-doc_numero',
			                				fieldLabel:'N° Documento',
			                				labelWidth:90,
			                				width:170
			                			},{xtype:'tbspacer',width:10 },
			                			{
			                				xtype:'textfield',
			                				id:conf_eu.id+'-apellidos',
			                				fieldLabel:'Apellidos',
			                				labelWidth:60
			                			},{xtype:'tbspacer',width:10 },
			                			{
			                				xtype:'textfield',
			                				id:conf_eu.id+'-nombres',
			                				fieldLabel:'Nombres',
			                				labelWidth:60
			                			},{xtype:'tbspacer',width:10 },
			                			{
			                				xtype:'segmentedbutton',
			                				vertical:false,
						                	allowToggle:false,
						                	items:[
						                			{
								                        text: '',
								                        icon: '/images/icon/search.png',
								                        listeners:{
								                        	click:function(obj){
								                        		conf_eu.buscar_config();
								                        	}
								                        }
								                    },
								                   /* {
								                        text: '',
								                        icon: '/images/icon/add.png',
								                        listeners:{
								                        	click:function(obj){
								                        		conf_eu.show_configurar();
								                        	}
								                        }
								                    }*/
						                	]
			                			}
			                	]
			                }    
					],
					items:[
							{
								xtype:'panel',
								border:false,
								id:conf_eu.id+'-configurar',
								hidden:true,
								defaults:{
									border:false
								},
								layout:'fit',
								items:[
										{
											xtype:'grid',
											id:conf_eu.id+'-configurar-grid',
											columnWidth:1,
											height:'100%',
											store:Ext.create('Ext.data.Store',{
												fields:[
													{name:'per_id', type:'int'},
													{name:'per_codigo', type:'string'},
													{name:'prov_codigo', type:'int'},
													{name:'per_apellido', type:'string'},
													{name:'per_nombre', type:'string'},
													{name:'doc_numero', type:'string'},
													{name:'per_telefono', type:'string'},
													{name:'per_estado', type:'string'},
													{name:'per_domicilio', type:'string'},
													{name:'per_email', type:'string'},
													{name:'prov_nombre', type:'string'},
													{name:'hora_rango', type:'string'},
													{name:'und_placa', type:'string'},
													{name:'zon_codigo', type:'string'},
													{name:'id_turno', type:'int'},
													{name:'cel_id', type:'int'},
													{name:'und_id', type:'int'},
													{name:'zon_id', type:'int'},
												],
												//autoLoad:true,
												proxy:{
													type:'ajax',
													url:conf_eu.url+'scm_scm_hue_select_equipo_trabajo/',
													reader:{
														type:'json',
														root:'data'
													}
												}
											}),
											columnLines:true,
											columns:{
												items:[
														{
															text:'Menu',
															align:'center',
															width:60,
															renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																metaData.style = "padding: 0px; margin: 0px";
																var prov_codigo = "'"+record.get('prov_codigo')+"'";
																var per_id = "'"+record.get('per_id')+"'";
																var id_turno = "'"+record.get('id_turno')+"'";
																var cel_id = "'"+record.get('cel_id')+"'";
																var und_id = "'"+record.get('und_id')+"'";
																var zon_id = "'"+record.get('zon_id')+"'";
																return global.permisos({
																	type: 'link',
							                                        extraCss: 'ftp-procesar-link',
							                                        id_menu:conf_eu.id_menu,
							                                        icons:[
							                                        	{id_serv: 92, img: 'ico_editar.gif', qtip: 'Click para Editar Registro.',js: 'conf_eu.show_configurar('+prov_codigo+','+per_id+','+id_turno+','+cel_id+','+und_id+','+zon_id+')'},
							                                        ]
																});
															}
														},
														{
															text:'Nombres',
															dataIndex:'per_nombre',
															flex:1
														},
														{
															text:'Apellidos',
															dataIndex:'per_apellido',
															flex:1
														},
														{
															text:'Doc Numero',
															dataIndex:'doc_numero',
															flex:1
														},
														{
															text:'Provincia',
															dataIndex:'prov_nombre',
															flex:1
														},
														{
															text:'Turno',
															dataIndex:'hora_rango',
															flex:1
														},
														{
															text:'Celular',
															dataIndex:'per_telefono',
															flex:1
														},
														{
															text:'Unidad',
															dataIndex:'und_placa',
															flex:1
														},
														{
															text:'Zona',
															dataIndex:'zon_codigo',
															flex:1
														}
													
												]
											}
										}
								]
							},
							{
								xtype:'panel',
								border:false,
								id:conf_eu.id+'-celulares',
								layout:'fit',
								hidden:true,
								defaults:{
									border:false
								},
								items:[
										{
											xtype:'grid',
											id:conf_eu.id+'-grid-celulares',
											//height:250,
											store:Ext.create('Ext.data.Store',{
												fields:[
													{name:'cel_id', type:'int'},
													{name:'cel_imei', type:'string'},
													{name:'cel_numero', type:'string'},
													{name:'cel_num_rp', type:'string'},
													{name:'tprop_id', type:'int'},
													{name:'prop_descri', type:'string'},
													{name:'cel_estado', type:'string'}  
												],
												//autoLoad:true,
												proxy:{
													type:'ajax',
													url:conf_eu.url+'scm_scm_hue_select_celulares/',
													reader:{
														type:'json',
														root:'data'
													}
												}
											}),
											columnLines:true,
											columns:{
												items:[
														{
															text:'Menu',
															width:60,
															align:'center',
															renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																//show_celularesE
																metaData.style = "padding: 0px; margin: 0px";
																var cel_id = "'"+record.get('cel_id')+"'";
																var cel_imei = "'"+record.get('cel_imei')+"'";
																var cel_numero = "'"+record.get('cel_numero')+"'";
																var cel_num_rp = "'"+record.get('cel_num_rp')+"'";
																var tprop_id = "'"+record.get('tprop_id')+"'";
																var cel_estado = "'"+record.get('cel_estado')+"'";

																return global.permisos({
																	type: 'link',
							                                        extraCss: 'ftp-procesar-link',
							                                        id_menu:conf_eu.id_menu,
							                                        icons:[
							                                        	{id_serv: 92, img: 'ico_editar.gif', qtip: 'Click para Editar Registro.',js: 'conf_eu.show_celularesE('+cel_id+','+cel_imei+','+cel_numero+','+cel_num_rp+','+tprop_id+','+cel_estado+')'},
							                                        ]
																});
															}
														},
														{
															text:'Cel IMEI',
															dataIndex:'cel_imei',
															flex:1
														},
														{
															text:'Cel Numero',
															dataIndex:'cel_numero',
															flex:1
														},
														{
															text:'Cel Numero RPM',
															dataIndex:'cel_num_rp',
															flex:1
														},
														{
															text:'Propietario',
															dataIndex:'prop_descri',
															flex:1,
															
														},
														{
															text:'Estado',
															dataIndex:'cel_estado',
															flex:1,
															renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																if (value == 1 ){
																	return 'Activado';
																}else{
																	return 'Desactivado';
																}
															}
														}
												]
											}
										}
								]
							},
							{
								xtype:'panel',
								id:conf_eu.id+'-unidades',
								layout:'fit',
								border:false,
								hidden:true,
								defaults:{
									border:false
								},
								items:[
										{
											xtype:'grid',
											id:conf_eu.id+'-und-grid',
											//height:250,
											store:Ext.create('Ext.data.Store',{
												fields:[
													{name:'und_id', type:'int'},
													{name:'prov_codigo', type:'int'},
													{name:'prov_nombre' , type:'string'},
													{name:'und_placa' , type:'string'},
													{name:'tund_id' , type:'int'},
													{name:'tundescri' , type:'string'},
													{name:'und_descri' , type:'string'},
													{name:'tprop_id' , type:'int'},
													{name:'tporp_descri' , type:'string'},
													{name:'und_anio' , type:'string'},
													{name:'und_marca' , type:'string'},
													{name:'und_capacidad' , type:'int'},
													{name:'und_kmact' , type:'string'},
													{name:'und_estado' , type:'string'}
												],
												//autoLoad:true,
												proxy:{
													type:'ajax',
													url:conf_eu.url+'scm_scm_hue_select_unidades/',
													reader:{
														type:'json',
														root:'data'
													}
												}
											}),
											columnLines:true,
											columns:{
												items:[
														{
															text:'menu',
															width:60,
															align:'center',
															renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																metaData.style = "padding: 0px; margin: 0px";
																var prov_codigo = "'"+record.get('prov_codigo')+"'";
																var tporp_descri = "'"+record.get('tporp_descri')+"'";
																var tprop_id = "'"+record.get('tprop_id')+"'";
																var tund_id = "'"+record.get('tund_id')+"'";
																var und_anio = "'"+record.get('und_anio')+"'";
																var und_capacidad = "'"+record.get('und_capacidad')+"'";
																var und_descri = "'"+record.get('und_descri')+"'";
																var und_estado = "'"+record.get('und_estado')+"'";
																var und_id = "'"+record.get('und_id')+"'";
																var und_kmact = "'"+record.get('und_kmact')+"'";
																var und_marca = "'"+record.get('und_marca')+"'";
																var und_placa = "'"+record.get('und_placa')+"'";

																return global.permisos({
																	type: 'link',
							                                        extraCss: 'ftp-procesar-link',
							                                        id_menu:conf_eu.id_menu,
							                                        icons:[
							                                        	{id_serv: 92, img: 'ico_editar.gif', qtip: 'Click para Editar Registro.',js: 'conf_eu.show_unidadesE('+prov_codigo+','+tporp_descri+','+tprop_id+','+tund_id+','+und_anio+','+und_capacidad+','+und_descri+','+und_estado+','+und_id+','+und_kmact+','+und_marca+','+und_placa+')'},
							                                        ]
																});
																
			
															}

														},
														{
															text:'Provincia',
															dataIndex:'prov_nombre',
															flex:1
														},
														{
															text:'Placa',
															dataIndex:'und_placa',
															flex:1
														},
														{
															text:'Tipo de Unidad',
															dataIndex:'tundescri',
															flex:1
														},
														{
															text:'Und Descripción',
															dataIndex:'und_descri',
															flex:1
														},
														{
															text:'Tipo Propietario',
															dataIndex:'tporp_descri',
															flex:1
														},
														{
															text:'Año',
															dataIndex:'und_anio',
															flex:1
														},
														{
															text:'Marca',
															dataIndex:'und_marca',
															flex:1
														},
														{
															text:'Capacidad',
															dataIndex:'und_capacidad',
															flex:1
														},
														{
															text:'Und Km Actual',
															dataIndex:'und_kmact',
															flex:1
														},
														{
															text:'Estado',
															dataIndex:'und_estado',
															flex:1,
															renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																if (value == 1 ){
																	return 'Activado';
																}else{
																	return 'Desactivado';
																}
															}
														}
													
												]
											}
										}
								]
							},
							{
								xtype:'panel',
								id:conf_eu.id+'-turnos',
								layout:'fit',
								hidden:true,
								border:false,
								defaults:{
									border:false
								},
								items:[
										{
											xtype:'grid',
											id:conf_eu.id+'-grid-horario',
											store:Ext.create('Ext.data.Store',{
												fields:[
													{name:'id_turno' , type:'int'},
													{name:'hora_ini' , type:'string'},
													{name:'hora_fin' , type:'string'},
													{name:'hora_ini_ref' , type:'string'},
													{name:'hora_fin_ref' , type:'string'},
													{name:'tur_estado' , type:'string'}
												],
												autoLoad:true,
												proxy:{
													type:'ajax',
													url:conf_eu.url+'scm_scm_hue_select_turnos_laboral/',
													reader:{
														type:'json',
														root:'data'
													}
												}
											}),
											columnLines:true,
											columns:{
												items:[
														{
															text:'Menu',
															align:'center',
															width:60,
															renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																//console.log(record);
																metaData.style = "padding: 0px; margin: 0px";
																var hora_ini ="'"+record.get('hora_ini')+"'";
																var hora_fin = "'"+record.get('hora_fin')+"'";
																var ini_ref ="'"+record.get('hora_ini_ref')+"'";
																var fin_ref= "'"+record.get('hora_fin_ref')+"'";
																var id_turno = "'"+record.get('id_turno')+"'";
																var estado = "'"+record.get('tur_estado')+"'";

																return global.permisos({
																	type: 'link',
							                                        extraCss: 'ftp-procesar-link',
							                                        id_menu:conf_eu.id_menu,
							                                        icons:[
							                                        	{id_serv: 92, img: 'ico_editar.gif', qtip: 'Click para Editar Registro.',js: 'conf_eu.show_horarioE('+hora_ini+','+hora_fin+','+ini_ref+','+fin_ref+','+id_turno+','+estado+')'},
							                                        ]
																});
															}
														},
														{
															text:'Hora Inicio',
															dataIndex:'hora_ini',
															align:'center',
															flex:1
														},
														{
															text:'Hora Fin',
															dataIndex:'hora_fin',
															align:'center',
															flex:1
														},
														{
															text:'Hora Inicio Refrigerio',
															dataIndex:'hora_ini_ref',
															align:'center',
															flex:1
														},
														{
															text:'Hora Fin Refrigerio',
															dataIndex:'hora_fin_ref',
															align:'center',
															flex:1
														},
														{
															text:'Estado',
															dataIndex:'tur_estado',
															align:'center',
															flex:1,
															renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																if (value == 1 ){
																	return 'Activado';
																}else{
																	return 'Desactivado';
																}
															}
														}
												]
											}
										}
								]
							}
							
							
					]

				});

				tab.add({
					id:conf_eu.id+'-tab',
					border:false,
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
	                        global.state_item_menu(conf_eu.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,conf_eu.id_menu);
	                        conf_eu.pressed(4);

	                    },
	                    beforeclose: function(obj, opts){
	                        global.state_item_menu(conf_eu.id_menu, false);
	                    }
	                }
				}).show();			
			},
			show_horario:function(){
				 win.show({vurl: conf_eu.url + 'form_show_horario/', id_menu: conf_eu.id_menu, class: ''});
			},
			show_horarioE:function(hora_ini,hora_fin,ini_ref,fin_ref,id_turno,estado){
				 win.show({vurl: conf_eu.url + 'form_show_horario/?hora_ini='+hora_ini+'&hora_fin='+hora_fin+'&ini_ref='+ini_ref+'&fin_ref='+fin_ref+'&id_turno='+id_turno+'&estado='+estado+'&editar=E', id_menu: conf_eu.id_menu, class: ''});
			},
			show_unidades:function(){
				 win.show({vurl: conf_eu.url + 'form_show_unidades/', id_menu: conf_eu.id_menu, class: ''});
			},
			show_unidadesE:function(prov_codigo,tporp_descri,tprop_id,tund_id,und_anio,und_capacidad,und_descri,und_estado,und_id,und_kmact,und_marca,und_placa){
				 win.show({vurl: conf_eu.url + 'form_show_unidades/?prov_codigo='+prov_codigo+'&tporp_descri='+tporp_descri+'&tprop_id='+tprop_id+'&tund_id='+tund_id+'&und_anio='+und_anio+'&und_capacidad='+und_capacidad+'&und_descri='+und_descri+'&und_estado='+und_estado+'&und_id='+und_id+'&und_kmact='+und_kmact+'&und_marca='+und_marca+'&und_placa='+und_placa+'&editar=E', id_menu: conf_eu.id_menu, class: ''});
			},
			show_celulares:function(){
				win.show({vurl: conf_eu.url + 'form_show_celulares/', id_menu: conf_eu.id_menu, class: ''});
			},
			show_celularesE:function(cel_id,cel_imei,cel_numero,cel_num_rp,tprop_id,cel_estado){
				win.show({vurl: conf_eu.url + 'form_show_celulares/?cel_id='+cel_id+'&cel_imei='+cel_imei+'&cel_numero='+cel_numero+'&cel_num_rp='+cel_num_rp+'&tprop_id='+tprop_id+'&cel_estado='+cel_estado+'&editar=E', id_menu: conf_eu.id_menu, class: ''});
			},
			show_configurar:function(prov_codigo,per_id,id_turno,cel_id,und_id,zon_id){
				win.show({vurl: conf_eu.url + 'form_show_configurar/?prov_codigo='+prov_codigo+'&per_id='+per_id+'&id_turno='+id_turno+'&cel_id='+cel_id+'&und_id='+und_id+'&zon_id='+zon_id+'&editar=E', id_menu: conf_eu.id_menu, class: ''});
			},
			pressed:function(value){
				/***************Menu*******************/
				Ext.getCmp(conf_eu.id+'-turnos').setVisible(false);
				Ext.getCmp(conf_eu.id+'-unidades').setVisible(false);
				Ext.getCmp(conf_eu.id+'-celulares').setVisible(false);
				Ext.getCmp(conf_eu.id+'-configurar').setVisible(false);
				/****************Munu del menu*************************/
				Ext.getCmp(conf_eu.id+'-btns-horario').setVisible(false);
				Ext.getCmp(conf_eu.id+'-btns-unidades').setVisible(false);
				Ext.getCmp(conf_eu.id+'-btns-celulares').setVisible(false);
				Ext.getCmp(conf_eu.id+'-btn-configurar').setVisible(false);
				if (value == 1 ){
					Ext.getCmp(conf_eu.id+'-turnos').setVisible(true);	
					Ext.getCmp(conf_eu.id+'-btns-horario').setVisible(true);	
				}else if(value == 2){
					Ext.getCmp(conf_eu.id+'-unidades').setVisible(true);
					Ext.getCmp(conf_eu.id+'-btns-unidades').setVisible(true);
				}else if(value == 3){
					Ext.getCmp(conf_eu.id+'-celulares').setVisible(true);	
					Ext.getCmp(conf_eu.id+'-btns-celulares').setVisible(true);
				}else if(value == 4){
					Ext.getCmp(conf_eu.id+'-configurar').setVisible(true);
					Ext.getCmp(conf_eu.id+'-btn-configurar').setVisible(true);
				}
			},
			busca_unidad:function(){
				var provincia = Ext.getCmp(conf_eu.id+'-und-provincia').getValue();
				var placa = Ext.getCmp(conf_eu.id+'-und-placa').getValue();
				var tipo = Ext.getCmp(conf_eu.id+'-und-tipo').getValue();
				Ext.getCmp(conf_eu.id+'-und-grid').getStore().load({
					params:{
						    vp_und_id:0,
					        vp_prov_codigo:provincia,
					        vp_placa:placa,
					        vp_tipo:tipo
					}
				});
			},
			buscar_celulares:function(){
				var grid = Ext.getCmp(conf_eu.id+'-grid-celulares');
				var imei = Ext.getCmp(conf_eu.id+'-cel-imei').getValue();
			    var celulares = Ext.getCmp(conf_eu.id+'-cel-celulares').getValue();
				grid.getStore().load({
					params:{vp_imei:imei,vp_cel_numero:celulares}
				});
			},
			buscar_config:function(){
				var prov_codigo = Ext.getCmp(conf_eu.id + '-conf-provincia').getValue();
				var rrhh = Ext.getCmp(conf_eu.id+'-rrhh').getValue();
				var doc_numero = Ext.getCmp(conf_eu.id+'-doc_numero').getValue();
				var apellido = Ext.getCmp(conf_eu.id+'-apellidos').getValue();
				var nombres = Ext.getCmp(conf_eu.id+'-nombres').getValue();
				var grid = Ext.getCmp(conf_eu.id+'-configurar-grid');

				grid.getStore().load({
					params:{vp_prov_codigo:prov_codigo,vp_doc_numero:doc_numero,vp_per_codigo:rrhh,vp_per_apellido:apellido,vp_per_nombre:nombres},
					callback:function(){

					}

				});
			}

		}
		Ext.onReady(conf_eu.init, conf_eu);
	}else{
		tab.setActiveTab(conf_eu.id+'-tab');
	}

</script>