<script type="text/javascript">
	/**
	 * @author  Jim
	 */
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if (!Ext.getCmp('gestor_operativo-tab')){
		var gestor_operativo = {
			id:'gestor_operativo',
			id_menu:'<?php echo $p["id_menu"];?>',
			url:'/gestion/gestorOperativo/',
			mapa:{
				directionsDisplay: new google.maps.DirectionsRenderer(),
				directionsService : new google.maps.DirectionsService(),
				trafficLayer:new google.maps.TrafficLayer(),
				markersService:[],
				markers:[],
				lat:-11.782413062516948,
				lng:-76.79493715625,
				currentinfowindow:null
			},
			destino:{
				record:{}
			},
			origen:{
				record:{}
			},
			unidad:{
				record:{}
			},
			service:{
				record:{}
			},
			module:{
				tipo:'A',
				A:{
					N:{},
					C:{},
					W:{},
					E:{},
					S:{}
				},
				B:{
					N:{},
					C:{},
					W:{},
					E:{},
					S:{}
				},
				C:{
					N:{},
					C:{},
					W:{},
					E:{},
					S:{}
				},
				D:{
					N:{},
					C:{},
					W:{},
					E:{},
					S:{}
				}
			},
			init:function(){	
				Ext.tip.QuickTipManager.init();

				this.store_service_geo = Ext.create('Ext.data.Store',{
					autoLoad:false,
					fields:[
						{name:'id_servicio', type:'int'},
						{name:'pos_px', type:'float'},
						{name:'pos_py', type:'float'},
						{name:'chk', type:'string'}
					],
					proxy:{
						type:'ajax',
						url:gestor_operativo.url+'scm_dispatcher_lista_servicios/',
						reader:{
							type:'json',
							root:'data'
						}
					},
					listeners:{
						load:function(){
							
						}
					}
				});
				
				this.store_pickup = Ext.create('Ext.data.Store',{
					autoLoad:true,
					fields:[
						{name:'placa', type:'string'},
						{name:'courrier', type:'string'},
						{name:'tot_ld', type:'int'},
						{name:'progreso', type:'float'},
						{name:'tot_pendiente', type:'int'},
						{name:'servicio', type:'string'},
						{name:'time_service', type:'string'},
						{name:'pos_px', type:'float'},
						{name:'pos_py', type:'float'},
						{name:'last_time', type:'string'},
						{name:'tipo', type:'string'},
						{name:'id_unidad', type:'int'},
						{name:'sentido', type:'string'},
						{name:'bateria', type:'int'},
						{name:'pos_px', type:'float'},
						{name:'pos_py', type:'float'},
						{name:'id_man', type:'int'},
						{name:'prov_codigo', type:'int'}
					],
					proxy:{
						type:'ajax',
						url:gestor_operativo.url+'scm_dispatcher_unidades/',
						reader:{
							type:'json',
							root:'data'
						}
					},
					listeners:{
						load:function(){
						}
					}
				});

				this.store_service = Ext.create('Ext.data.Store',{
					autoLoad:false,
					fields:[
						{name:'origen', type:'string'},
						{name:'zona', type:'string'},
						{name:'direccion', type:'string'},
						{name:'geo_px', type:'float'},
						{name:'geo_py', type:'float'},
						{name:'horario', type:'string'},
						{name:'peso', type:'string'},
						{name:'estado', type:'string'},
						{name:'shipper', type:'string'},
						{name:'gui_srec_id', type:'int'},
						{name:'chk', type:'string'},
						{name:'tipo', type:'string'},
						{name:'tipo_servicio', type:'string'},
						{name:'id_shipper', type:'int'},
						{name:'ciu_id', type:'int'},
						{name:'id_geo', type:'int'},
						{name:'id_dir', type:'int'},
						{name:'frecuente', type:'string'},
						{name:'gui_numero', type:'int'},
					],
					proxy:{
						type:'ajax',
						url:gestor_operativo.url+'scm_dispatcher_servicios/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});

				this.store_route = Ext.create('Ext.data.Store',{
					fields:[
						{name:'ruta', type:'string'}, 
						{name:'tipo_servicio', type:'string'},
						{name:'distrito', type:'string'},
						{name:'direccion', type:'string'}, 
						{name:'hora_ss', type:'string'},   
						{name:'estado', type:'string'}, 
						{name:'hora_chk', type:'string'}, 
						{name:'time_last', type:'string'}, 
						{name:'time_total', type:'string'}, 
						{name:'shipper_logo', type:'string'}, 
						{name:'id_doc', type:'int'}, 
						{name:'dir_px', type:'float'}, 
						{name:'dir_py', type:'float'}, 
						{name:'chk', type:'string'}, 
						{name:'guia', type:'int'},
						{name:'prov_codigo', type:'int'}
					],
					//groupField: 'guia',
					proxy:{
						type:'ajax',
						url:gestor_operativo.url+'scm_dispatcher_lista_ruta/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});

				this.store_status = Ext.create('Ext.data.Store',{
					autoLoad:true,
					fields:[
						{name: 'chk', type: 'string'},
						{name: 'descri', type: 'string'}
					],
					proxy:{
						type:'ajax',
						url:gestor_operativo.url+'get_estados/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				this.store_personal = Ext.create('Ext.data.Store',{
					autoLoad:false,
					fields:[
						{name:'nombre', type:'string'},
						{name:'per_id' , type:'int'}
					],
					proxy:{
						type:'ajax',
						url:gestor_operativo.url+'get_scm_usr_sis_personal/',
						reader:{
							type:'json',
							rootProperty:'data'
						}
					}
				});
				
				tab.add({
					id:gestor_operativo.id+'-tab',
					border:false,
					autoScroll:true,
					closable:true,
					layout:{
						type:'fit'
					},
					tbar:[
						'-',
						{
							xtype:'combo',
							id:gestor_operativo.id+'-agencia',
							fieldLabel:'Agencia',
							labelWidth:50,
							store:Ext.create('Ext.data.Store',{
							fields:[
									{name:'prov_codigo', type:'int'},
									{name:'prov_nombre', type:'string'}
							],
							proxy:{
								type:'ajax',
								url:gestor_operativo.url+'get_usr_sis_provincias/',
								reader:{
									type:'json',
									rootProperty:'data'
								}
							}
							}),
							queryMode:'local',
							valueField:'prov_codigo',
							displayField:'prov_nombre',
							listConfig:{
								minWidth:250
							},
							width:180,
							forceSelection:true,
							allowBlank:false,
							selecOnFocus:true,
							emptyText:'[ Seleccione ]',
							listeners:{
								afterrender:function(obj,record,options){
									obj.getStore().load({
										params:{linea:3},
										callback:function(){
											obj.setValue(parseInt('<?php echo PROV_CODIGO;?>'));
										}
									});
								},
								select: function(obj, records, opts){
									gestor_operativo.getReloadComponent();
								}
							}
						},'-',
						{
							xtype:'datefield',
							id:gestor_operativo.id+'-fecha',
							fieldLabel:'Fecha',
							labelWidth:35,
							//labelAlign:'top',
							width:150,
							value:new Date()
						},
						{xtype:'tbspacer',width:5},
						{
							xtype:'combo',
							fieldLabel:'Estado',
							labelWidth:50,
							id:gestor_operativo.id+'-stado',
							//hidden:true,
							store:gestor_operativo.store_status,
							queryMode: 'local',
							triggerAction: 'all',
							valueField:'chk',
							displayField:'descri',
							emptyText: '[seleccione]',
							listeners:{
								afterrender:function(obj,e){
									obj.setValue('SS');
								},
								select: function(obj, records, opts){
									gestor_operativo.getReloadService();
								}
							}
						},
						{
							text:'',
							id:gestor_operativo.id+'-btn-find',
							icon: '/images/icon/search.png',
							tooltip:'Buscar',
							listeners:{
								click:function(obj,opts){
									gestor_operativo.getReloadComponent();
								}
							}
						}
					],
					listeners:{
						beforerender: function(obj, opts){
	                        global.state_item_menu(gestor_operativo.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,gestor_operativo.id_menu);
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(gestor_operativo.id_menu, false);
	                    },
	                    boxready:function(obj, width, height, eOpts ){
	                    	/*gestor_operativo.showEjecutados();
	                    	gestor_operativo.showSolicitudes();*/
	                    	gestor_operativo.setMachineEstructure('A');
	                    }
					}
				}).show();
			},
			setInitMachine:function(){
				//MODULE A
				gestor_operativo.module.A.C='<div id="'+gestor_operativo.id+'-map" class="ue-map-canvas"></div>';
				gestor_operativo.module.A.W=Ext.create('Ext.grid.Panel',{
					id:gestor_operativo.id+'-recol-ruta',
					border:false,
					height:'100%',
					store:gestor_operativo.store_pickup,
					columnLines:true,
					columns:{
						items:[
								{
									text:'Unidad',
									dataIndex:'placa',
									width:85,
									align:'center',
									renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
										//metaData.style = "padding: 2px; margin: 0px;";
										//metaData.tdAttr = 'data-qtip=Bateria:' + record.get('bateria') + '%';
										return '<div style="height:20px;display:inline-block;width:80px;"><table width="80px"><tr><td width="80px";><img src="/images/icon/moto.png">&nbsp;&nbsp;&nbsp;&nbsp;'+value+'</td><td  width="5px"; style="background-color: #'+( record.get('pos_px')!='' ?'3CB371':'DC143C')+';"></td></tr></table></div>';
									}
								},
								{
									text:'Asig',
									dataIndex:'tot_ld',
									width:30,
									align:'center',
								},
								{
									text:'Pend',
									dataIndex:'tot_pendiente',
									width:36,
									align:'center',
								},
								{
									text:'Progreso',
									dataIndex:'estado',
									align:'center',
									width:90,
									renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
										metaData.style = "padding: 2px; margin: 0px;";
										var id = Ext.id();
										var progreso = record.get('progreso');
					                    Ext.defer(function () {
					                        Ext.widget('progressbar', {
					                            renderTo: id,
					                            value: progreso,
					                            width: 70,
					                            //height:16,
					                            animate:true,
					                            listeners:{
					                            	afterrender:function( obj, eOpts ){
					                            		if (obj.getValue() < 0.5){
					                            			obj.addCls('p_bar-orange');	
					                            		}else{
					                            			obj.addCls('p_bar-green');	
					                            		}
					                            	}
					                            }
					                            
					                        });

					                    }, 50);
					                    return Ext.String.format('<div id="{0}" align="center"></div>', id);
									}
								},
								{
									text:'En Ejecucion',
									dataIndex:'servicio',
									align:'center',
									flex:1
								}/*,
								{
									text:'T. Servicio',
									dataIndex:'time_service',
									align:'center',
									width:80
								}*/
						]
					},
					listeners:{
						beforeselect:function(obj, record, index, eOpts ){
					        gestor_operativo.unidad.record=record;
							gestor_operativo.getReloadRouteExecute();
						}
					},
					viewConfig: {
        				getRowClass: function(record, rowIndex, rowParams, store){
        				}
        			}
				});
				gestor_operativo.module.A.S=Ext.create('Ext.grid.Panel',{
					id:gestor_operativo.id+'-grid-route',
					store:gestor_operativo.store_route,
					columnLines:true,
					multiSelect: true,
					border:false,
					//height:150,
					features: [
						{
							groupHeaderTpl: 'Cliente:{[ values.rows[0].data["cliente"] ]}',
				            ftype: 'grouping'
				        }
			        ],
					columns:{
						items:[	
							{
								text:'Menu',
								dataIndex:'',
								sortable:'false',
								width:50,
								align:'center',
								renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
									metaData.style = "padding: 0px; margin: 0px";
									return global.permisos({
										type:'link',
										id_menu: gestor_operativo.id_menu,
										icons:[
                                            {id_serv: 0, img: 'gears3.png', qtip: 'Click para Procesar Registro', js: ''}
                                        ]
									});

								}
							},
							{
								text:'Tipo de Servicio',
								dataIndex:'tipo_servicio',
								flex:1,
								sortable: false
							},
							{
								text:'Distrito',
								dataIndex:'distrito',
								flex:1,
								sortable: false
							},
							{
								text:'Dirección',
								dataIndex:'direccion',
								flex:1,
								sortable: false
							},
							{
								text:'Cliente',
								dataIndex:'cliente',
								flex:1,
								sortable: false
							},
							{
								text:'Hora SS',
								dataIndex:'hora_ss',
								width:60,
								sortable: false
							},
							{
								text:'Estado',
								dataIndex:'estado',
								flex:1,
								sortable: false
							},
							{
								text:'Hora CHK',
								dataIndex:'hora_chk',
								width:60,
								sortable: false
							},
							{
								text:'Menú',
								dataIndex:'',
								width:50,
								sortable: false,
								renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
			                        metaData.style = "padding: 0px; margin: 0px";
			                        return '<a href="#" onclick="gestor_operativo.getFormDownload('+record.get('id_doc')+',\''+record.get('tipo')+'\');"><img src="/images/icon/basket_put.png" style="padding:2px 5px 2px 5px;" /></a>';
			                    }
							}
						]
					},
					viewConfig: {
			            plugins: {
			                ptype: 'gridviewdragdrop',
			                dragText: 'Arrastre y Suelte Para Organizar la Ruta',
			            },
			            listeners:{
			            	drop:function(node, data, overModel, dropPosition, eOpts){
			        			//gestor_operativo.findMaker(parseInt(data.records[0].data.guia));
			        		}
			            }
			        },
			        listeners:{
			        	beforeselect:function(obj, record, index, eOpts ){
			        		gestor_operativo.origen.record=record;
			        	},
			        	cellclick:function( obj, td, cellIndex, record, tr, rowIndex, e, eOpts ){
			        	},
			        	headerclick:function( ct, column, e, t, eOpts ){
			        	}
			        }
				});

				//MODULE B
				gestor_operativo.module.B.W=Ext.create('Ext.panel.Panel',{
					border:false,
					layout:'fit',
					items:[
						{
			        		//region:'south',
			        		height:'50%',
			        		id:gestor_operativo.id+'-panel-unidades',
			        		layout:'fit',
			        		border:false,
			        		/*tbar:[
			        			{
                                    xtype:'combo',
                                    fieldLabel: 'Contacto',
                                    id:gestor_operativo.id+'-contacto_-a',
                                    store: Ext.create('Ext.data.Store',{
										autoLoad:false,
										fields:[
												{name:'contacto',type:'string'},
												{name:'id_contacto',type:'int'}
										     
										],
										proxy:{
											type:'ajax',
											url:gestor_operativo.url+'get_scm_contactos/',
											reader:{
												type:'json',
												root:'data'
											}
										}
									}),
                                    queryMode: 'local',
                                    triggerAction: 'all',
                                    valueField: 'id_contacto',
                                    displayField: 'contacto',
                                    emptyText: '[Seleccione]',
                                    //allowBlank: false,
                                    labelWidth: 115,
                                    width:'100%',
                                    anchor:'100%',
                                    //readOnly: true,
                                    listConfig:{
										minWidth:200
									},
                                    listeners:{
                                        afterrender:function(obj, e){
                                        },
                                        select:function(obj, records, eOpts){
                                            
                                        }
                                    }
                                }
			        		],*/
			        		bbar:[
			        			{
									xtype:'combo',
									id:gestor_operativo.id+'-per_id',
									allowBlank:false,
									width:'100%',
									anchor:'100%',
									fieldLabel:'Chofer/Motorizado',
									store:gestor_operativo.store_personal,
									queryMode:'local',
									valueField:'per_id',
									displayField:'nombre',
									listConfig:{
										minWidth:140
									},
									forceSelection:true,
									allowBlank:false,
									empyText:'[ Seleccione]',
									listeners:{
										afterrender:function(obj){
										}
									}
								}
			        		],
			        		items:[
			        			{
									xtype:'grid',
									id:gestor_operativo.id+'-unidades-asignacion-ruta',
									store:Ext.create('Ext.data.Store',{
										autoLoad:false,
										fields:[
												{name:'id_unidad',type:'int'},
												{name:'placa',type:'string'},
												{name:'id_Man',type:'int'},
												{name:'posicion',type:'string'},
												{name:'pos_px',type:'float'},
												{name:'pos_py',type:'float'},
												{name:'tipo',type:'string'},
												{name:'sentido',type:'string'},
												{name:'id_Per',type:'int'},
												{name:'gps_dist_m',type:'string'},
												{name:'gps_dist_t',type:'string'},
												{name:'gps_time_s',type:'string'},
												{name:'gps_time_t',type:'string'},
												{name:'age_x',type:'float'},
												{name:'age_y',type:'float'}
										     
										],
										proxy:{
											type:'ajax',
											url:gestor_operativo.url+'get_scm_delivery_unidad_gps_distance/',//get_scm_delivery_unidad_gps_distance
											reader:{
												type:'json',
												root:'data'
											}
										}
									}),
									//height:300,
									columnLines:true,
									columns:{
										items:[
												{
													text:'Placa',
													dataIndex:'placa',
													width:90,
													align:'center',
													renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
														metaData.style = "padding: 2px; margin: 0px;";
														//metaData.tdAttr = 'data-qtip=Bateria:"' + record.get('placa') + '"';
														if (record.get('tipo')==5){
															return '<div style="height:16px;display:inline-block;width:85px;"><table width="85px"><tr><td width="90px";><img src="/images/icon/moto.png">&nbsp;&nbsp;&nbsp;&nbsp;'+value+'</td><td  width="5px"; style="background-color: #'+( record.get('pos_px')!='' ?'3CB371':'DC143C')+';"></td></tr></table></div>';
														}else if(record.get('tipo')==4){
															return '<img src="/images/icon/car.png">&nbsp;&nbsp;&nbsp;&nbsp;'+value;	
														}
													}
												},
												{
													text:'Distancia',
													dataIndex:'gps_dist_t',
													flex:1
												},
												{
													text:'Tiempo',
													dataIndex:'gps_time_t',
													flex:1
												},
												{
													text:'Sentido',
													dataIndex:'sentido',
													flex:1
												}
										]
									},
									listeners:{
										beforeselect:function(obj, record, index, eOpts ){
											gestor_operativo.unidad.record=record;
											Ext.getCmp(gestor_operativo.id+'-per_id').setValue(record.get('id_per'));
										}
									},
									viewConfig: {
			            				getRowClass: function(record, rowIndex, rowParams, store){	
			            					
			            				}
			            			}
								}
			        		]
			        	}
					]
				});
				gestor_operativo.module.B.S=Ext.create('Ext.grid.Panel',{
					id:gestor_operativo.id+'-panel-services',
					store:gestor_operativo.store_service,
					columnLines:true,
					columns:{
						items:[
								{
									text:'Shipper',
									dataIndex:'shipper',
									flex:1
								},
								{
									text:'Zona',
									dataIndex:'zona',
									flex:1
								},
								{
									text:'Tipo Servicio',
									dataIndex:'tipo_servicio',
									flex:1
								},
								{
									text:'Dirección',
									dataIndex:'direccion',
									flex:1
								},
								{
									text:'Horario',
									dataIndex:'horario',
									flex:1
								},
								{
									text:'carga',
									dataIndex:'peso',
									flex:1
								},
								{
									text:'Hora Solicitud',
									dataIndex:'hora_ss',
									flex:1
								},
								{
									text:'Tiempo',
									dataIndex:'time',
									flex:1
								},
								{
									text:'Estado',
									dataIndex:'estado',
									flex:1
								},
								{
									text:'Opciones',
									dataIndex:'',
									flex:1,
									renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
										//Ext.getCmp(gestor_operativo.id+'-panel-services').getSelectionModel().select(rowIndex, true); 
				                        metaData.style = "padding: 0px; margin: 0px";
				                        //if(parseInt(record.get('id_geo'))!=0 && parseInt(record.get('gui_numero'))==0){
				                        	return global.permisos({
	                                            type: 'link',
	                                            id_menu: gestor_operativo.id_menu,
	                                            icons:[
	                                                {id_serv: 110, img: 'reloj.ico', qtip: 'Click para Reprogramar.', js: 'gestor_operativo.getFormReprogramming('+record.get('gui_srec_id')+','+rowIndex+')'},
	                                                {id_serv: 111, img: 'siguiente.png', qtip: 'Click para Asignar Unidad.', js: 'gestor_operativo.getFormAllocation({id_servicio:'+record.get('gui_srec_id')+',id_shipper:'+record.get('id_shipper')+',prov_codigo:'+record.get('prov_codigo')+'},'+rowIndex+')'}
	                                            ]
	                                        });
				                        /*}else{
				                        	return global.permisos({
	                                            type: 'link',
	                                            id_menu: gestor_operativo.id_menu,
	                                            icons:[
	                                                {id_serv: 112, img: 'marker1.png', qtip: 'Click para editar dirección.', js: 'gestor_operativo.getFormChangeAddress('+record.get('gui_numero')+','+rowIndex+')'},
	                                            ]
	                                        });
				                        }*/
				                    }
								}
						]
					},
					listeners:{
						beforeselect:function(obj, record, index, eOpts ){
							gestor_operativo.service.record=record;
							try{
                        		if(gestor_operativo.mapa.currentinfowindow)gestor_operativo.mapa.currentinfowindow.close();
                        	}catch(e){}
                        	try{
								google.maps.event.trigger(gestor_operativo.mapa.markersService[record.get('gui_srec_id')],'click');//set id service
							}catch(e){}
						}
					}
				});
				gestor_operativo.module.C.C=Ext.create('Ext.panel.Panel',{
					border:false,
					layout:'border',
					title: 'Programación',
					items:[
						{
			        		region:'north',
			        		height:60,
			        		border:false,
			        		items:[
			        			{
                                    xtype:'panel',
                                    id:gestor_operativo.id+'-fecha-programacion-a',
                                    border:false,
                                    bodyStyle: 'background: transparent',
                                    padding:'5px 5px 5px 5px',
                                    layout:'column',
                                    items: [
                                    	{
                                            columnWidth: 1,border:false,
                                            layout:'fit',
                                            padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                            items:[
                                            	{
													xtype:'fieldset',
													title:'Fecha a Recolectar',
													items:[
															{
																xtype:'datefield',
																editable:false,
																allowBlank:false,
																id:gestor_operativo.id+'-d-fecha-inicio',
																margin:'0 0 5 0',
																columnWidth:1,
																fieldLabel:'Fecha Programada',
																labelWidth:120,
																minValue:new Date(),
																value:new Date(),
																listeners: {
																	'select' : {
														                fn:function(){
														                    gestor_operativo.getReloadHourList();
														                }
														            },
														            blur: function(o) {
															            gestor_operativo.getReloadHourList();
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
                                    id:gestor_operativo.id+'-fecha-programacion-b',
                                    border:false,
                                    hidden:true,
                                    bodyStyle: 'background: transparent',
                                    padding:'5px 5px 5px 5px',
                                    layout:'fit',
                                    items: [
                                    	{
											xtype:'fieldset',
											title:'Programación Frecuente / Vigencia de la Programación',
											layout:'column',
											items:[
                                            	{
                                                    columnWidth: 0.5,border:false,
                                                    layout:'fit',
                                                    padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                    items:[
														{
															xtype:'datefield',
															editable:false,
															id:gestor_operativo.id+'-f-fecha-inicio',
															allowBlank:false,
															margin:'0 0 5 0',
															fieldLabel:'Del',
															columnWidth:0.5,
															labelWidth:20,
															value:new Date(),
															listeners: {
																'select' : {
													                fn:function(){
													                    
													                }
													            },
													            blur: function(o) {
														            gestor_operativo.getReloadHourList();
														        }
												            }
														}
													]
												},
												{
                                                    columnWidth: 0.5,border:false,
                                                    layout:'fit',
                                                    padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                    items:[
														{
															xtype:'datefield',
															editable:false,
															id:gestor_operativo.id+'-f-fecha-fin',
															allowBlank:false,
															margin:'0 0 5 0',
															columnWidth:0.5,
															fieldLabel:'Al',
															labelWidth:20,
															value:new Date(),
															listeners: {
																'select' : {
													                fn:function(){
													                    
													                }
													            },
													            blur: function(o) {
														            gestor_operativo.getReloadHourList();
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
			        		region:'center',
			        		border:false,
			        		layout:'fit',
			        		items:[
			        			{
									xtype:'grid',
									id:gestor_operativo.id+'-grid_diario',
									store:Ext.create('Ext.data.Store',{
										sorters: [
													{
								                        property: 'cnt',
								                        direction: 'DESC'
								                    }
										],
										fields:[
												{name:'time1',type:'string'},
												{name:'cnt',type:'int'},
												{name:'hor_minima',type:'string'},
												{name:'hor_maxima',type:'string'},
												{name:'hor_min',type:'string'},
												{name:'hor_max',type:'string'}
										],
										proxy:{
											type:'ajax',
											url:gestor_operativo.url+'get_scm_dispatcher_horarios/',
											reader:{
												type:'json',
												root:'data'
											}
										}
									}),
									columnsLines:true,
									columns:{
										items:[
												{
													text:'Por Hora',
													dataIndex:'time1',
													flex:1
												},
												{
													text:'Cantidad',
													align:'center',
													dataIndex:'cnt',
													flex:0.5
												},
												{
													text:'Hora Inicio',
													dataIndex:'hor_minima',
													flex:1
												},
												{
													text:'Hora Fin',
													dataIndex:'hor_maxima',
													flex:1
												}
										]
									},
									listeners:{
										afterrender:function(obj){
											obj.getStore().load({params:{},
												callback:function(){
													var count = obj.getStore().getCount();
													if(count==1){
														var record = obj.getStore().getAt(0);
														Ext.getCmp(gestor_operativo.id+'-d-hora-max').setValue(record.get('hor_max'));
														Ext.getCmp(gestor_operativo.id+'-d-hora-min').setValue(record.get('hor_min'));
													}
												}
											});
										},
										beforeselect:function(obj, record, index, eOpts ){
											Ext.getCmp(gestor_operativo.id+'-d-hora-max').setValue(record.get('hor_max'));
											Ext.getCmp(gestor_operativo.id+'-d-hora-min').setValue(record.get('hor_min'));
										}
									}
								}
			        		]
			        	},
			        	{
			        		region:'south',
			        		height:50,
			        		border:false,
			        		items:[
			        			{
									xtype:'fieldset',
									layout:'column',
									title:'Hora Programada',
									items:[
											{
												xtype:'timefield',
												id:gestor_operativo.id+'-d-hora-min',
												//editabled:true,
												//allowBlank:false,
												margin:'0 0 5 0',
												columnWidth:0.5,
												fieldLabel:'Hora Recolectar',
												plugins:  [new ueInputTextMask('99:99')],
												inputAttrTpl:" data-qtip='Ingrese Hora y Minuto (15:15) Formato de 24 Horas'",
												labelWidth:100,
												format: 'H:i',
												altFormats:'H:i',
												increment: 30,
												listeners:{
													keypress:function(obj){
														//obj.setValue(null);
													},
                                                    keyup:function(obj){
                                                        //obj.setValue(null);
                                                    }
												}
											},
											{
												xtype:'timefield',
												id:gestor_operativo.id+'-d-hora-max',
												//allowBlank:false,
												//editable:false,
												//readOnly:true,
												margin:'0 0 5 0',
												columnWidth:0.5,
												fieldLabel:'Hora Maxima',
												plugins:  [new ueInputTextMask('99:99')],
												inputAttrTpl:" data-qtip='Ingrese Hora y Minuto (15:15) Formato de 24 Horas'",
												labelWidth:80,
												format: 'H:i',
												altFormats:'H:i',
												increment: 30,
												listeners:{
													/*keypress:function(obj){
														obj.setValue(null);
													},
                                                    keyup:function(obj){
                                                        obj.setValue(null);
                                                    }*/
												}
											}

									]
								},
								{
									xtype:'fieldset',
									hidden:true,
									id:gestor_operativo.id+'-dias-programacion',
									disabled:true,
									columnWidth:1,
									title:'Dia de la Semana a Recolectar',
									layout:'column',
									items:[
											{
												xtype: 'checkboxgroup',
												id:gestor_operativo.id+'-f-chk-dia-semana',
												columnWidth:1,
												columns: 7,
												vertical: true,
												items:[
														{boxLabel: 'LU',name:'_semana',id:gestor_operativo.id+'_semana_LU',inputValue: 1},
														{boxLabel: 'MA',name:'_semana',id:gestor_operativo.id+'_semana_MA',inputValue: 2},
														{boxLabel: 'MI',name:'_semana',id:gestor_operativo.id+'_semana_MI',inputValue: 3},
														{boxLabel: 'JU',name:'_semana',id:gestor_operativo.id+'_semana_JU',inputValue: 4},
														{boxLabel: 'VI',name:'_semana',id:gestor_operativo.id+'_semana_VI',inputValue: 5},
														{boxLabel: 'SA',name:'_semana',id:gestor_operativo.id+'_semana_SA',inputValue: 6},
														{boxLabel: 'DO',name:'_semana',id:gestor_operativo.id+'_semana_DO',inputValue: 7}
												]
											}
									]
								}
								/*{
									xtype:'label',
									columnWidth:1,
									text:'* Rango Horario de 60 Min. Máximo'
								}*/

			        		]
			        	}
					]
				});
				//MODULE D
				gestor_operativo.module.D.W=Ext.create('Ext.panel.Panel',{
					border:false,
					layout:'fit',
					items:[
						{
					        //title: 'Destino',
					        id: gestor_operativo.id+'-tab-destino',
					        //disabled:true,
					        layout:'fit',
					        items:[
					        	{
                                   xtype: 'findlocation',
                                   id: gestor_operativo.id+'-destino',
                                   mapping: false,
                                   clearReferent:false,
                                   getMapping:false,
                                   setMapping:gestor_operativo.id+'-map',
                                   trust:true,
                                   listeners:{
                                        afterrender: function(obj){
                                        	try{
                                        		obj.setGeoLocalizar({dir_px:gestor_operativo.service.record.get('geo_px'),dir_py:gestor_operativo.service.record.get('geo_py'),id_geo:gestor_operativo.service.record.get('id_geo'),dir_id:gestor_operativo.service.record.get('id_dir')});
                                        	}catch(e){}
                                        }
                                   }
                                }
					        ]
					    }
					]
				});
			},
			setClearMachine:function(){
				try{
					gestor_operativo.module={
						A:{
							N:{},
							C:{},
							W:{},
							E:{},
							S:{}
						},
						B:{
							N:{},
							C:{},
							W:{},
							E:{},
							S:{}
						},
						C:{
							N:{},
							C:{},
							W:{},
							E:{},
							S:{}
						},
						D:{
							N:{},
							C:{},
							W:{},
							E:{},
							S:{}
						}
					};
					gestor_operativo.setResetValues();
					Ext.getCmp(gestor_operativo.id+'-region-north').destroy();
					Ext.getCmp(gestor_operativo.id+'-region-west').destroy();
					Ext.getCmp(gestor_operativo.id+'-sub-region-center').update('');
					Ext.getCmp(gestor_operativo.id+'-sub-region-center').destroy();
					Ext.getCmp(gestor_operativo.id+'-sub-region-south').destroy();
					Ext.getCmp(gestor_operativo.id+'-region-center').destroy();
					Ext.getCmp(gestor_operativo.id+'-region-east').destroy();
					Ext.getCmp(gestor_operativo.id+'-region-south').destroy();
					Ext.getCmp(gestor_operativo.id+'-tab').remove(gestor_operativo.id+'form');

                }catch(err){
            		console.log(err);
            	}
			},
			setMachineEstructure:function(tipo){
				gestor_operativo.setClearMachine();
            	gestor_operativo.setInitMachine();
				var panel = Ext.create('Ext.form.Panel',{
					id:gestor_operativo.id+'form',
					border:false,
					layout:'border',
					defaults:{
						border:false
					},
					tbar:[
							
					],
					items:[
							{
								region:'north',
								id:gestor_operativo.id+'-region-north',
								hidden:true,
								layout:'fit',
								border:false,
								listeners:{
									boxready: function(self) {
					       			}
								}
							},
							{
								region:'west',
								id:gestor_operativo.id+'-region-west',
								width:400,
								hidden:true,
								layout:'fit',
								header:false,
								split: true,
								collapsible: true,
								hideCollapseTool:true,
								titleCollapse:false,
								floatable: false,
								collapseMode : 'mini',
								animCollapse : true,
								border:false,
								listeners:{
									boxready: function(self) {
					       			}
								},
								bbar:[
									'-',
									{
										text:'Grabar',
										id:gestor_operativo.id+'-btn-grabar',
										icon: '/images/icon/save.png',
										tooltip:'Grabar',
										listeners:{
											click:function(obj,opts){
												gestor_operativo.setSaveProcess();
											}
										}
									},
									'-',
									{
										text:'Cancelar',
										id:gestor_operativo.id+'-btn-cancelar',
										icon: '/images/icon/cancel.png',
										tooltip:'Cancelar',
										listeners:{
											click:function(obj,opts){
												gestor_operativo.setCancelProcess();
											}
										}
									},
									'-'
								]
							},
							{
								region:'center',
								id:gestor_operativo.id+'-region-center',
								layout:'border',
								border:false,
								items:[
									{
										region:'center',
										id:gestor_operativo.id+'-sub-region-center',
										border:false,
										layout:'fit',
										html:'<div id="'+gestor_operativo.id+'-map" class="ue-map-canvas"></div>'
									},
									{
										region:'south',
										id:gestor_operativo.id+'-sub-region-south',
										hidden:true,
										layout:'fit',
										header:false,
										split: true,
										collapsible: true,
										hideCollapseTool:true,
										titleCollapse:false,
										floatable: false,
										collapseMode : 'mini',
										animCollapse : true,
										border:false,
										listeners:{
											boxready: function(self) {
							       			}
										}
									}
								],
								listeners:{
									boxready: function(self) {
					       			}
								}
							},
							{
								region:'east',
								id:gestor_operativo.id+'-region-east',
								hidden:true,
								layout:'fit',
								border:false,
								listeners:{
									boxready: function(self) {
					       			}
								}
							},
							{
								region:'south',
								id:gestor_operativo.id+'-region-south',
								hidden:true,
								layout:'fit',
								header:false,
								split: true,
								collapsible: true,
								hideCollapseTool:true,
								titleCollapse:false,
								floatable: false,
								collapseMode : 'mini',
								animCollapse : true,
								border:false,
								listeners:{
									boxready: function(self) {
					       			}
								}
							}
					],
					listeners:{
						boxready: function(self){
							gestor_operativo.module.tipo=tipo;
							Ext.getCmp(gestor_operativo.id+'-btn-cancelar').show();
							Ext.getCmp(gestor_operativo.id+'-btn-grabar').show();
							switch(tipo){
								case 'A':
									Ext.getCmp(gestor_operativo.id+'-btn-cancelar').hide();
									Ext.getCmp(gestor_operativo.id+'-btn-grabar').hide();
									Ext.getCmp(gestor_operativo.id+'-stado').setValue('SS');
									Ext.getCmp(gestor_operativo.id+'-region-center').update(gestor_operativo.module.A.C);
									Ext.getCmp(gestor_operativo.id+'-region-west').add(gestor_operativo.module.A.W);
									Ext.getCmp(gestor_operativo.id+'-region-west').show();
									Ext.getCmp(gestor_operativo.id+'-sub-region-south').setHeight(150);
									//Ext.getCmp(gestor_operativo.id+'-sub-region-south').show();
									Ext.getCmp(gestor_operativo.id+'-sub-region-south').add(gestor_operativo.module.A.S);
									Ext.getCmp(gestor_operativo.id+'-region-south').setHeight(150);
									Ext.getCmp(gestor_operativo.id+'-region-south').show();
									Ext.getCmp(gestor_operativo.id+'-region-south').add(gestor_operativo.module.B.S);
									gestor_operativo.setMap();
								break;
								case 'B':
									Ext.getCmp(gestor_operativo.id+'-region-west').add(gestor_operativo.module.B.W);
									Ext.getCmp(gestor_operativo.id+'-region-west').show();
									Ext.getCmp(gestor_operativo.id+'-region-south').hide();
									gestor_operativo.setMap();
								break;
								case 'C':
									Ext.getCmp(gestor_operativo.id+'-region-west').add(gestor_operativo.module.C.C);
									Ext.getCmp(gestor_operativo.id+'-region-west').show();
									Ext.getCmp(gestor_operativo.id+'-region-south').hide();
									gestor_operativo.setReloadMarker();
									try{
		                        		if(gestor_operativo.mapa.currentinfowindow)gestor_operativo.mapa.currentinfowindow.close();
		                        	}catch(e){}
		                        	try{
										google.maps.event.trigger(gestor_operativo.mapa.markersService[gestor_operativo.service.record.get('gui_srec_id')],'click');//set id service
									}catch(e){}
									//gestor_operativo.setChangeTypePickUp();
								break;
								case 'D':
									Ext.getCmp(gestor_operativo.id+'-region-west').add(gestor_operativo.module.D.W);
									Ext.getCmp(gestor_operativo.id+'-region-west').show();
									//Ext.getCmp(gestor_operativo.id+'-region-south').hide();
									gestor_operativo.setMap(); 
								break;
							}
							gestor_operativo.getReloadComponent();
		       			}
					}
				});
				Ext.getCmp(gestor_operativo.id+'-tab').add(panel);
			},
			setMap:function(){
				var directionsService = new google.maps.DirectionsService();
		        
		        var rendererOptions = {
					  draggable: true,
					  suppressMarkers: true
				};
		        gestor_operativo.mapa.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
		        gestor_operativo.mapa.directionsService = new google.maps.DirectionsService();

		        var myLatlng = new google.maps.LatLng(-12.0473179,-77.0824867);

		        var mapOptions = {
					zoom: 11,
    				center: myLatlng,
    				mapTypeId: google.maps.MapTypeId.ROADMAP
				};
		        gestor_operativo.map = new google.maps.Map(document.getElementById(gestor_operativo.id+'-map'), mapOptions);
		        

		        var homeControlDiv = document.createElement('div');
		        var homeControl = new HomeControl(homeControlDiv, gestor_operativo.map, myLatlng);
		        homeControlDiv.index = 1;
		        gestor_operativo.map.controls[google.maps.ControlPosition.TOP_CENTER].push(homeControlDiv);

		        var hdiv = document.createElement('div');
		        var hcontro = new gestor_operativo.HHomeControl(hdiv,gestor_operativo.map);
		        hdiv.index = 1;
		        gestor_operativo.map.controls[google.maps.ControlPosition.TOP_LEFT].push(hdiv);

		        gestor_operativo.mapa.directionsDisplay.setMap(gestor_operativo.map);
			},
			HHomeControl:function(controlDiv, map) {
			  controlDiv.style.padding = '5px';

			  // Set CSS for the control border.
			  var controlUI = document.createElement('div');
			  controlUI.style.backgroundColor = 'gray';
			  controlUI.style.borderStyle = 'gray';
			  controlUI.style.borderWidth = '2px';
			  controlUI.style.borderColor = 'gray';

			  controlUI.style.width = '100%';
			  controlUI.style.height = '100%';

			  controlUI.style.cursor = 'pointer';
			  controlUI.style.textAlign = 'center';
			  controlUI.title = 'Click Para Mostrar el Tráfico';
			  controlDiv.appendChild(controlUI);

			  // Set CSS for the control interior.
			  var controlText = document.createElement('div');
			  controlText.style.fontFamily = 'Arial,sans-serif';
			  controlText.style.fontSize = '12px';
			  controlText.style.paddingLeft = '4px';
			  controlText.style.paddingRight = '4px';
			  controlText.style.color = 'white';
			  controlText.innerHTML = '<input type="checkbox" id="trafic" name="trafic" onClick="gestor_operativo.trafic();"> <b>Ver Tráfico</b>';
			  controlUI.appendChild(controlText);
			},
			trafic:function(){
				 if (document.getElementById("trafic").checked){
				 	 gestor_operativo.mapa.trafficLayer.setMap(gestor_operativo.map);
				 }else{
				 	gestor_operativo.mapa.trafficLayer.setMap(null);
				 }
			},
			getReloadComponent:function(){
				switch(gestor_operativo.module.tipo){
					case 'A':
						var vp_prov_codigo= Ext.getCmp(gestor_operativo.id+'-agencia').getValue();
						var vp_fecha = Ext.getCmp(gestor_operativo.id+'-fecha').getRawValue();
						Ext.getCmp(gestor_operativo.id+'-recol-ruta').getStore().load({
			               params:{vp_prov_codigo:vp_prov_codigo,vp_fecha:vp_fecha},
			               callback:function(){
			               		gestor_operativo.store_service_geo.reload({
									params:{vp_prov_codigo:vp_prov_codigo,vp_fecha:vp_fecha},
									callback:function(){
										gestor_operativo.getReloadService();
										gestor_operativo.setReloadMarker();
									}
								});
			               }
			            });
					break;
					case 'B':
						
		            break;
		            case 'C':
		            	var grid = Ext.getCmp(gestor_operativo.id+'-grid-status');
						var vp_agencia = Ext.getCmp(gestor_operativo.id+'-agencia').getValue();
						var vp_fecha = Ext.getCmp(gestor_operativo.id+'-fecha').getRawValue();
						grid.getStore().load({
							params:{vp_prov_cod:vp_agencia,vp_fecha_inicio:vp_fecha},
							callback:function(){
							}
						});
		            break;
	            }
			},
			getReloadService:function(){
				var estado = Ext.getCmp(gestor_operativo.id+'-stado').getValue();
				Ext.getCmp(gestor_operativo.id+'-sub-region-south').hide();
				/*if(estado!='SS'){
					Ext.getCmp(gestor_operativo.id+'-region-west').hide();
				}else{
					Ext.getCmp(gestor_operativo.id+'-region-west').show();
				}*/
				var vp_prov_codigo= Ext.getCmp(gestor_operativo.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(gestor_operativo.id+'-fecha').getRawValue();
				var grid = Ext.getCmp(gestor_operativo.id+'-panel-services');
				grid.getStore().load({
	               params:{vp_agencia:vp_prov_codigo,vp_fecha:vp_fecha,vp_estado:estado},
	               callback:function(){
	               		
	               }
	            });
			},
			setReloadMarker:function(){
				gestor_operativo.setMap();
				gestor_operativo.setClearMarker();
				gestor_operativo.store_service_geo.each(function(record){
					if(record.get('tipo')=='R'){
						gestor_operativo.setMarker({dir_px:record.get('pos_px'),dir_py:record.get('pos_py'),shi_logo:'home_delivery.png',agencia:record.get('chk'),tipo_marker:'S',id_marker:record.get('id_servicio')});
					}else{
						gestor_operativo.setMarker({dir_px:record.get('pos_px'),dir_py:record.get('pos_py'),shi_logo:'Logistic_7_20.png',agencia:record.get('chk'),tipo_marker:'S',id_marker:record.get('id_servicio')});
					}
				});
				Ext.getCmp(gestor_operativo.id+'-recol-ruta').getStore().each(function(record){ 
					gestor_operativo.setMarker({dir_px:record.get('pos_px'),dir_py:record.get('pos_py'),shi_logo:'moto32.png',agencia:record.get('placa'),tipo_marker:'M',id_marker:0});
				});
			},
			setMarker:function(record){
				var point = new google.maps.LatLng(parseFloat(record.dir_px),parseFloat(record.dir_py));
				switch(record.tipo_marker){
					case 'S':
						var marker = new google.maps.Marker({
		                        position: point,
		                        map: gestor_operativo.map,
		                        animation: google.maps.Animation.DROP,
		                        title: '',
		                        icon:'/images/icon/Logistic_7_20.png',
		                        tipo:record.tipo_marker,
		                        id:record.id_marker
		                });
					break;
					default:
						var marker = new google.maps.Marker({
		                        position: point,
		                        map: gestor_operativo.map,
		                        animation: google.maps.Animation.DROP,
		                        title: '',
		                        icon:'/images/icon/'+record.shi_logo,
		                        tipo:record.tipo_marker,
		                        id:record.id_marker
		                });
					break;
				}
                var infowindow = new google.maps.InfoWindow({
                      content: '<div id="content_info_trans_'+record.id_marker+'"  style="width:100%;padding:0px;margin:0px;">'+record.agencia+'</div>',
                      maxWidth: 510,
                      padding:0,
                      margin:0
                });
                google.maps.event.addListener(marker, 'click', function() {
                	if(marker.id!=0){
	                	gestor_operativo.getInfoPanetMarker({
	                		id:marker.id
	                	});
                	}
                    if(gestor_operativo.mapa.currentinfowindow)gestor_operativo.mapa.currentinfowindow.close();
                	gestor_operativo.mapa.currentinfowindow=infowindow;
                    gestor_operativo.mapa.currentinfowindow.open(gestor_operativo.map,marker);
                });
                switch(record.tipo_marker){
					case 'S':
                		gestor_operativo.mapa.markersService[record.id_marker]=marker;
                	break;
                	default:
                		gestor_operativo.mapa.markers.push(marker);
                	break;
                }
			},
			setClearMarker:function(){
				if (gestor_operativo.mapa.markers) {
			    	for (i in gestor_operativo.mapa.markers) {
			    		try{
			      			gestor_operativo.mapa.markers[i].setMap(null);
			      		}catch(e){}
			    	}
			  	}
			  	if (gestor_operativo.mapa.markersService.length>0) {
			    	gestor_operativo.store_service_geo.each(function(record){
			    		try{
			      			gestor_operativo.mapa.markersService[record.get('id_servicio')].setMap(null);
			      		}catch(e){}
			    	});
			  	}
			},
			getInfoPanetMarker:function(record){
				Ext.Ajax.request({
					url:gestor_operativo.url+'scm_dispatcher_datos_servicios/',
					params:{
						vp_srec_id:record.id
					},
					success:function(response,options){
						var res = Ext.decode(response.responseText);
						//console.log(res);
						if (parseInt(res.data[0].error_sql)==0){
							gestor_operativo.getPanelMarker({
								id:record.id,
								shipper:res.data[0].shipper,
								servicio:res.data[0].servicio,
								carga:res.data[0].carga,
								horario:res.data[0].horario,
								direccion:res.data[0].direccion,
								referencia:res.data[0].referencia,
								distrito:res.data[0].distrito,
								tipo:res.data[0].tipo,
								estado:res.data[0].estado,
								observaciones:res.data[0].observaciones,
								propietario:res.data[0].propietario,
								ejecutor:res.data[0].ejecutor
							});
						}else{
							
						}
					}
				});
			},
			getPanelMarker:function(record){
				//console.log(record);
	            var imageTplPointerPanel = new Ext.XTemplate(
	                '<tpl for=".">',
	                    '<div class="" >',
	                        '<div class="databox_mensage" >',
	                            '<div class="databox_bar">',
	                                '<div class="databox_title">',
	                                    '<span>'+record.shipper+'</span>',
	                                '</div>',
	                                //'<div class="databox_date"><span class="dbx_user">'+record.data.usr_codigo+'</span><span class="dbx_fecha">'+record.data.fecha+'</span></div>',
	                            '</div>',
	                            '<div class="databox_message">'+record.servicio+'</div>',
	                        '</div>',
	                        '<div class="databox_btools">',
	                            '<hr></hr>',
	                            '<span><p>CARGA:</p>'+record.carga+'</span>',
	                            '<hr></hr>',
	                            '<span><p>HORARIOS:</p>'+record.horario+'</span>',
	                            '<hr></hr>',
	                            '<span><p>DIRECCIÓN:</p></span><span>'+record.direccion+'</span>',
	                            '<hr></hr>',
	                            '<span><p>REFERENCIA:</p></span><span>'+record.referencia+'</span>',
	                            '<hr></hr>',
	                            '<span><p>DISTRITO:</p></span><span>'+record.distrito+'</span>',
	                            '<hr></hr>',
	                            '<span><p>ESTADO:</p></span><span>'+record.tipo+'</span>',
	                            '<hr></hr>',
	                            '<span><p>OBSERVACIONES:</p></span><span>'+record.observaciones+'</span>',
	                            '<hr></hr>',
	                            '<span><p>PROPIETARIO:</p></span><span>'+record.propietario+'</span>',
	                            '<hr></hr>',
	                            '<span><p>EJECUTOR:</p></span><span>'+record.ejecutor+'</span>',
	                            '<hr></hr>',
	                        '</div>',
	                    '</div>',
	                '</tpl>'
	            );
				//console.log(imageTplPointerPanel);
				try{
	            	Ext.get('content_info_trans_'+record.id).setHtml(imageTplPointerPanel.html);
	            }catch(e){}
			},
		    getReloadRouteExecute:function(){
		    	Ext.getCmp(gestor_operativo.id+'-sub-region-south').hide();
		    	var record = gestor_operativo.unidad.record;
		    	gestor_operativo.setMap();
				gestor_operativo.setClearMarker();
		    	//console.log(record);
		    	//Ext.getCmp(gestor_operativo.id+'-per_id').setValue(record.get('id_per'));
				Ext.getCmp(gestor_operativo.id+'-grid-route').getStore().load({
					params:{vp_prov_codigo:record.get('prov_codigo'),vp_man_id:record.get('id_man'),vp_estado:'P'},
					callback:function(){
						var count = Ext.getCmp(gestor_operativo.id+'-grid-route').getStore().getCount();
						if(count!=0){
							Ext.getCmp(gestor_operativo.id+'-sub-region-south').show();
							Ext.getCmp(gestor_operativo.id+'-grid-route').getStore().each(function(record){
								if(record.get('tipo')=='E'){
									gestor_operativo.setMarker({dir_px:record.get('dir_px'),dir_py:record.get('dir_py'),shi_logo:'home_delivery.png',agencia:record.get('cliente'),tipo_marker:'',id_marker:0});
								}else{
									gestor_operativo.setMarker({dir_px:record.get('dir_px'),dir_py:record.get('dir_py'),shi_logo:'map-marker-24.png',agencia:record.get('cliente'),tipo_marker:'',id_marker:0});
								}
							});
						}else{
							gestor_operativo.setReloadMarker();
						}
					}
				});
		    },
			setResetValues:function(){
				gestor_operativo.destino={
					record:{}
				};
				gestor_operativo.origen={
					record:{}
				};
				gestor_operativo.unidad={
					record:{}
				};
			},
			getFormReprogramming:function(id_servicio,rowIndex){
				Ext.getCmp(gestor_operativo.id+'-panel-services').getSelectionModel().select(rowIndex, true);
				gestor_operativo.setMachineEstructure('C');
			},
			getFormChangeAddress:function(gui_numero,rowIndex){
				Ext.getCmp(gestor_operativo.id+'-panel-services').getSelectionModel().select(rowIndex, true);
				gestor_operativo.setMachineEstructure('D');
			},
			getFormAllocation:function(record,rowIndex){
				Ext.getCmp(gestor_operativo.id+'-panel-services').getSelectionModel().select(rowIndex, true);
				gestor_operativo.setMachineEstructure('B');
				/*Ext.getCmp(gestor_operativo.id+'-contacto_-a').getStore().load({
					params:{vp_shi_codigo:record.id_shipper,vp_id_agencia:0},//record.prov_codigo
					callback:function(){
						
					}
				});*/
				Ext.getCmp(gestor_operativo.id+'-unidades-asignacion-ruta').getStore().load({
					params:{vp_prov_codigo:record.prov_codigo,vp_id_agencia:0},
					callback:function(){
						gestor_operativo.setMap();
						gestor_operativo.setClearMarker();
						Ext.getCmp(gestor_operativo.id+'-unidades-asignacion-ruta').getStore().each(function(record){
							gestor_operativo.setMarker({dir_px:record.get('pos_px'),dir_py:record.get('pos_py'),shi_logo:'moto32.png',agencia:record.get('placa'),tipo_marker:'M',id_marker:0});
						});
						Ext.getCmp(gestor_operativo.id+'-per_id').getStore().load({
							params:{vp_agencia:record.prov_codigo},
							callback:function(){
							}
						});
					}
				});
			},
			getFormDownload:function(id_servicio,tipo){
				Ext.create('Ext.window.Window',{
					id:gestor_operativo.id+'-win',
					title:'Descarga',
					height:160,
					width:320,
					resizable:false,
					//closable:false,
					plain:true,
					minimizable: false,
					maximizable: false,
					constrain: true,
					constrainHeader:false,
					header:true,
					border:false,
					//layout:'fit',
					bodyStyle: 'background: #fff',
					modal:true,
					items:[
						{
                            xtype:'panel',
                            border:false,
                            bodyStyle: 'background: transparent',
                            padding:'3px 5px 3px 5px',
                            layout:'column',
                            items: [
                                {
                                    columnWidth: .8,border:false,
                                    layout:'fit',
                                    padding:'0px 0px 0px 0px',  bodyStyle: 'background: transparent',
                                    items:[
                                        {
											xtype:'radiogroup',
											allowBlank:false,
											//columnWidth:0.4,
											id:gestor_operativo.id+'-rbtn-group-estado',
											fieldLabel:(tipo=='R')?'¿Recolectado?':'¿Entregado?',
											columns:2,
											vertical:false,
											labelWidth:80,
											items:[
													{boxLabel:'SI',id:gestor_operativo.id+'_rbtn_ejecutar_a',name:'_rbtn_ejecutar',inputValue:1, width:20,checked:true},
													{boxLabel:'NO',id:gestor_operativo.id+'_rbtn_ejecutar_b',name:'_rbtn_ejecutar',inputValue:2, width:20}
											],
											listeners:{
												change: function (field, newValue, oldValue) {
													Ext.getCmp(gestor_operativo.id+'-motivo-a').setDisabled(false);
													Ext.getCmp(gestor_operativo.id+'-motivo-a').setValue(null);
													Ext.getCmp(gestor_operativo.id+'-motivo-a').getStore().removeAll();
													if(parseInt(newValue._rbtn_ejecutar)==2){
														Ext.getCmp(gestor_operativo.id+'-motivo-a').getStore().load({
															params:{vp_opcion:(tipo=='R')?2:1},
															callback:function(){}
														});
													}else{
														Ext.getCmp(gestor_operativo.id+'-motivo-a').setDisabled(true);
													}
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
                            padding:'10px 5px 3px 5px',
                            layout:'column',
                            items: [
                                {
                                    columnWidth: 0.6,border:false,
                                    //layout:'fit',
                                    padding:'0px 2px 0px 10px',  bodyStyle: 'background: transparent',
                                    items:[
                                        {
											xtype:'datefield',
											id:gestor_operativo.id+'-fecha-descarga',
											allowBlank:false,
											margin:'0 0 5 0',
											columnWidth:0.5,
											fieldLabel:'Fecha',
											width:'100%',
											labelWidth:42,
											value:new Date()
										}
                                    ]
                                },
                                {
                                    columnWidth: 0.4,border:false,
                                    //layout:'fit',
                                    padding:'0px 2px 0px 10px',  bodyStyle: 'background: transparent',
                                    items:[
                                        {
											xtype:'textfield',
											id:gestor_operativo.id+'-d-hora-descarga',
											//editabled:false,
											//allowBlank:false,
											margin:'0 0 5 0',
											//columnWidth:0.5,
											fieldLabel:'Hora',
											width:'100%',
											plugins:  [new ueInputTextMask('23:59')],
											value:'<?php echo  date ("h:i");?>',
											inputAttrTpl:" data-qtip='Ingrese Hora y Minuto (15:15) Formato de 24 Horas'",
											labelWidth:42,
											listeners:{
												afterrender:function(obj){
													obj.setValue('<?php echo  date ("G:i");?>');
												}
											}
										}
                                    ]
                                },
                                {
                                    columnWidth: 1,border:false,
                                    layout:'fit',
                                    padding:'0px 2px 0px 10px',  bodyStyle: 'background: transparent',
                                    items:[
                                        {
											xtype:'combo',
											id:gestor_operativo.id+'-motivo-a',
											disabled:true,
											fieldLabel:'Motivo',
											labelWidth:42,
											width:'100%',
											store:Ext.create('Ext.data.Store',{
											fields:[
													{name:'mot_id', type:'int'},
													{name:'chk_descri', type:'string'}
											],
											proxy:{
												type:'ajax',
												url:gestor_operativo.url+'get_CuadroMotivos/',
												reader:{
													type:'json',
													rootProperty:'data'
												}
											}
											}),
											queryMode:'local',
											valueField:'mot_id',
											displayField:'chk_descri',
											listConfig:{
												minWidth:250
											},
											//width:180,
											forceSelection:true,
											//allowBlank:false,
											selecOnFocus:true,
											emptyText:'[ Seleccione ]',
											listeners:{
												afterrender:function(obj,record,options){
													/*obj.setValue(null);
													obj.getStore().removeAll();
													obj.getStore().load({
														params:{vp_opcion:(tipo=='R')?1:2},
														callback:function(){
															
														}
													});*/
												},
												select: function(obj, records, opts){
													//gestor_operativo.getReloadComponent();
												}
											}
										}
                                    ]
                                }
                            ]
                        }
					],
					bbar:[
						{
							text:'Grabar',
							id:gestor_operativo.id+'-btn-grabar-descarga',
							icon: '/images/icon/save.png',
							tooltip:'Grabar',
							listeners:{
								click:function(obj,opts){
									gestor_operativo.setSaveProcess();
								}
							}
						},
						'->',
						'-',
						{
							text:'Salir',
							id:gestor_operativo.id+'-btn-salir',
							icon: '/images/icon/close.png',
							tooltip:'Salir',
							listeners:{
								click:function(obj,opts){
									Ext.getCmp(gestor_operativo.id+'-win').close();
								}
							}
						},
						'-'
					]
				}).show();
			},
			getReloadHourList:function(){
            	switch(gestor_operativo.pickup.tipo){
					case 0:
						var fecha_inicio = Ext.getCmp(gestor_operativo.id+'-d-fecha-inicio').getRawValue();
						var fecha_fin=fecha_inicio;
					break;
					case 2:
						var fecha_inicio = Ext.getCmp(gestor_operativo.id+'-f-fecha-inicio').getRawValue();
						var fecha_fin = Ext.getCmp(gestor_operativo.id+'-f-fecha-fin').getRawValue();
					break;
				}
            	Ext.getCmp(gestor_operativo.id+'-grid_diario').getStore().removeAll();
    			Ext.getCmp(gestor_operativo.id+'-grid_diario').getStore().load({
	                params: {
	                    vp_srec_id:gestor_operativo.pickup.srec_id,vp_fecha_inicio:fecha_inicio,vp_fecha_fin:fecha_fin
	                },
	                callback:function(){
	                }
	            });
            },
            setCancelProcess:function(){
            	gestor_operativo.setMachineEstructure('A');
            },
            setChangeTypePickUp:function(){
            	gestor_operativo.setClearCheckBoxDay();
            	switch(gestor_operativo.service.record.get('frecuente')){
            		case 'NNNNNNN':
            			Ext.getCmp(gestor_operativo.id+'-dias-programacion').setDisabled(true);
            			Ext.getCmp(gestor_operativo.id+'-fecha-programacion-b').hide();
            			Ext.getCmp(gestor_operativo.id+'-fecha-programacion-a').show();
            		break;
            		default:
            			Ext.getCmp(gestor_operativo.id+'-dias-programacion').setDisabled(false);
            			Ext.getCmp(gestor_operativo.id+'-fecha-programacion-b').show();
            			Ext.getCmp(gestor_operativo.id+'-fecha-programacion-a').hide();
            			var dias = ['LU','MA','MI','JU','VI','SA','DO'];
						for(var i=0;i<dias.length;i++){
							var frecuente = gestor_operativo.service.record.get('frecuente').substr(i,1);
							if(frecuente=='S'){
								Ext.getCmp(gestor_operativo.id+'_semana_'+dias[i]).setValue(true);
							}
						}
            		break;
            	}
            },
            setClearCheckBoxDay:function(){
            	Ext.getCmp(gestor_operativo.id+'_semana_LU').setValue(false);
            	Ext.getCmp(gestor_operativo.id+'_semana_MA').setValue(false);
            	Ext.getCmp(gestor_operativo.id+'_semana_MI').setValue(false);
            	Ext.getCmp(gestor_operativo.id+'_semana_JU').setValue(false);
            	Ext.getCmp(gestor_operativo.id+'_semana_VI').setValue(false);
            	Ext.getCmp(gestor_operativo.id+'_semana_SA').setValue(false);
            	Ext.getCmp(gestor_operativo.id+'_semana_DO').setValue(false);
            },
            setSaveProcess:function(){
            	switch(gestor_operativo.module.tipo){
            		case 'A':
            			var tipo = Ext.getCmp(gestor_operativo.id+'-rbtn-group-estado').getValue()._rbtn_ejecutar;
            			var fecha = Ext.getCmp(gestor_operativo.id+'-fecha-descarga').getRawValue();
            			var hora = Ext.getCmp(gestor_operativo.id+'-d-hora-descarga').getValue();
            			var vp_mot_id=0;
            			if (fecha==''){
							global.Msg({
								msg:'Ingrese la fecha de descarga',
								icon:2,
								buttosn:1,
								fn:function(btn){
								}
							});
							return;
						}
						if (hora==''){
							global.Msg({
								msg:'Ingrese la hora y minuto',
								icon:2,
								buttosn:1,
								fn:function(btn){
								}
							});
							return;
						}
						if(tipo==2){
							vp_mot_id =Ext.getCmp(gestor_operativo.id+'-motivo-a').getValue(); 
							if (vp_mot_id==0 || vp_mot_id=='' || vp_mot_id== null){
								global.Msg({
									msg:'Selecciona un motivo de descarga',
									icon:2,
									buttosn:1,
									fn:function(btn){
									}
								});
								return;
							}
						}

						global.Msg({
			                msg: '¿Realmente deseas grabar el proceso?',
			                icon: 3,
			                buttons: 3,
			                fn: function(btn){
			                    if (btn == 'yes'){
					            	Ext.Ajax.request({
										url:gestor_operativo.url+'set_scm_mobile_upd_descarga/',
										params:{
											vp_srec_id:gestor_operativo.service.record.get('gui_srec_id'),
											vp_fecha:fecha,
											vp_hora:hora,
											vp_mot_id:vp_mot_id
										},
										success:function(response,options){
											var res = Ext.decode(response.responseText);
											//console.log(res);
											if (parseInt(res.data[0].error_sql)==1){
												global.Msg({
													msg:res.data[0].error_info,
													icon:1,
													buttons:1,
													fn:function(btn){
														gestor_operativo.setMachineEstructure('A');
													}
												});
											}else{
												global.Msg({
													msg:res.data[0].error_info,
													icon:0,
													buttons:1,
													fn:function(btn){}
												});
											}
										}
									});
					            }
					        }
					    });
            		break;
            		case 'B':
            			gestor_operativo.setSaveUnidad();
            		break;
            		case 'C':
            			var string_day='';
						var hora_max = Ext.getCmp(gestor_operativo.id+'-d-hora-max').getRawValue();
						var hora_min = Ext.getCmp(gestor_operativo.id+'-d-hora-min').getRawValue();
						var fecha_inicio = Ext.getCmp(gestor_operativo.id+'-d-fecha-inicio').getRawValue();
						var fecha_fin=fecha_inicio;

						/*switch(gestor_operativo.service.record.get('frecuente')){ 
							case 'NNNNNNN':
								var fecha_inicio = Ext.getCmp(gestor_operativo.id+'-d-fecha-inicio').getRawValue();
								var fecha_fin=fecha_inicio;
							break;
							default:
								var fecha_inicio = Ext.getCmp(gestor_operativo.id+'-f-fecha-inicio').getRawValue();
								var fecha_fin = Ext.getCmp(gestor_operativo.id+'-f-fecha-fin').getRawValue();
								var dias = ['LU','MA','MI','JU','VI','SA','DO'];
								var count = 0;
								for(var i=0;i<dias.length;i++){
									var bool=Ext.getCmp(gestor_operativo.id+'_semana_'+dias[i]).getValue();
									if(bool){
										string_day+='S';
										count++;
									}else{
										string_day+='N';
									}
								}
								if (count==0){
									global.Msg({
										msg:'Seleccione Almenos un día en la Programación',
										icon:2,
										buttosn:1,
										fn:function(btn){
										}
									});
									return;
								}
							break;
						}*/
						//var origen = Ext.getCmp(gestor_operativo.id+'-grid_diario').getValue();
						if (fecha_inicio==''){
							global.Msg({
								msg:'Seleccione Almenos un día en la Programación',
								icon:2,
								buttosn:1,
								fn:function(btn){
								}
							});
							return;
						}
						if (hora_max==''){
							global.Msg({
								msg:'Seleccione el horario en el listado',
								icon:2,
								buttosn:1,
								fn:function(btn){
								}
							});
							return;
						}
						if (hora_min==''){
							global.Msg({
								msg:'Seleccione el horario en el listado',
								icon:2,
								buttosn:1,
								fn:function(btn){
								}
							});
							return;
						}
						global.Msg({
			                msg: '¿Realmente deseas grabar el proceso?',
			                icon: 3,
			                buttons: 3,
			                fn: function(btn){
			                    if (btn == 'yes'){
					            	Ext.Ajax.request({
										url:gestor_operativo.url+'scm_dispatcher_upd_programacion/',
										params:{
											vp_srec_id:gestor_operativo.service.record.get('gui_srec_id'),
											vp_fecini:fecha_inicio,
											vp_horini:hora_min,
											vp_fecfin:fecha_fin,
											vp_horfin:hora_max,
											vp_semana:string_day
										},
										success:function(response,options){
											var res = Ext.decode(response.responseText);
											//console.log(res);
											if (parseInt(res.data[0].error_sql)==1){
												global.Msg({
													msg:res.data[0].error_info,
													icon:1,
													buttons:1,
													fn:function(btn){
														//FIN DEL PROCESO
														//Ext.getCmp(gestor_operativo.id+'tab-content').setActiveTab(0);
														//gestor_operativo.setClear();
														gestor_operativo.setMachineEstructure('A');
													}
												});
											}else{
												global.Msg({
													msg:res.data[0].error_info,
													icon:0,
													buttons:1,
													fn:function(btn){
													}
												});
											}
										}
									});
					            }
					        }
					    });
            		break;
            		case 'D':
            		   gestor_operativo.setSaveAddress();
            		break;
            	}
            },
            setSaveAddress:function(){
		    	var record=gestor_operativo.service.record;
		        var v = Ext.getCmp(gestor_operativo.id+'-destino').getValues();

		        if (parseFloat(v[0].coordenadas[0].lat)==-11.782413062516948 || parseFloat(v[0].coordenadas[0].lon)==-76.79493715625 || parseFloat(v[0].coordenadas[0].lat)==0 || parseFloat(v[0].coordenadas[0].lon)==0 ){
		            global.Msg({
		                msg:'Debes Realizar la búsqueda...',
		                icon:2,
		                buttosn:1,
		                fn:function(btn){
		                }
		            });
		            return;
		        }
		        global.Msg({
	                msg: '¿Seguro de confirmar la dirección?',
	                icon: 3,
	                buttons: 3,
	                fn: function(btn){
	                    if (btn == 'yes'){
					        Ext.Ajax.request({
			                    url:gestor_operativo.url+'scm_scm_home_delivery_upd_destino/',
			                    params:{
			                        vp_gui_num:record.get('gui_numero'),
			                        vp_dir_id:record.get('id_dir'),
			                        vp_ciu_id:v[0].ciu_id,
			                        vp_id_geo:v[0].id_puerta,
			                        vp_via_id:v[0].id_via,
			                        vp_dir_calle:v[0].dir_calle,
			                        vp_dir_numvia:v[0].nro_via,
			                        vp_dir_refere:v[0].referencia,
			                        vp_urb_id:v[0].id_urb,
			                        vp_urb_nom:v[0].nombre_urb,
			                        vp_mz_id:v[0].id_mza,
			                        vp_mz_nom:v[0].nombre_mza,
			                        vp_num_lote:v[0].nro_lote,
			                        vp_num_int:v[0].nro_interno,
			                        vp_dir_px:v[0].coordenadas[0].lat,
			                        vp_dir_py:v[0].coordenadas[0].lon
			                    },
			                    success:function(response,options){
			                        var res = Ext.decode(response.responseText);
			                        if (parseInt(res.data[0].error_sql)==1){
			                            global.Msg({
			                                msg:res.data[0].error_info,
			                                icon:1,
			                                buttosn:1,
			                                fn:function(btn){
			                                	gestor_operativo.setMachineEstructure('A');
			                                }
			                            });
			                        }else{
			                            global.Msg({
			                                msg:res.data[0].error_info,
			                                icon:0,
			                                buttosn:1,
			                                fn:function(btn){
			                                }
			                            });
			                        }
			                    }
			                });
						}
	                }
	            });
		    },
            setSaveUnidad:function(){
            	//var id_contacto = Ext.getCmp(gestor_operativo.id+'-contacto_-a').getValue();
				var vp_per_id=Ext.getCmp(gestor_operativo.id+'-per_id').getValue();
				var vp_id_age = 0;//gestor_operativo.origen.record.get('id_agencia');

				try{
					var vp_id_unidad = gestor_operativo.unidad.record.get('id_unidad');
				}catch(e){
					var vp_id_unidad = 0;
				}
				/*if (id_contacto==0 || id_contacto=='' || id_contacto== null){
					global.Msg({
						msg:'Selecciona un Contacto',
						icon:2,
						buttosn:1,
						fn:function(btn){
						}
					});
					return;
				}*/

				if (vp_id_unidad==0 || vp_id_unidad=='' || vp_id_unidad == null){
					global.Msg({
						msg:'Selecciona una unidad',
						icon:2,
						buttosn:1,
						fn:function(btn){
						}
					});
					return;
				}
			
				if (vp_per_id==0 || vp_per_id=='' || vp_per_id== null){
					global.Msg({
						msg:'Selecciona un chofer',
						icon:2,
						buttosn:1,
						fn:function(btn){
						}
					});
					return;
				}
				
				global.Msg({
	                msg: '¿Seguro de Asignar a Unidad?',
	                icon: 3,
	                buttons: 3,
	                fn: function(btn){
	                    if (btn == 'yes'){
							Ext.Ajax.request({
								url:gestor_operativo.url+'scm_dispatcher_add_ruta/',
								params:
								{
									vp_agencia:gestor_operativo.service.record.get('prov_codigo'),//gestor_operativo.origen.record
									vp_srec_id:gestor_operativo.service.record.get('gui_srec_id'),//gestor_operativo.origen.record
									vp_man_id:gestor_operativo.unidad.record.get('id_man'),
									vp_per_m:vp_per_id,
									//vp_per_id_h:id_contacto,
									vp_und_id:gestor_operativo.unidad.record.get('id_unidad')
									//vp_msn:vp_msn,
								},
								success:function(response,options){
									var res = Ext.decode(response.responseText);
									if (parseInt(res.data[0].error_sql)==1){
										global.Msg({
							                msg: res.data[0].error_info,
							                icon: 3,
							                buttons: 1,
							                fn: function(btn){
							                    //if (btn == 'yes'){
							                    	gestor_operativo.setMachineEstructure('A');
												//}
							                }
							            });
									}else{
										global.Msg({
											msg:res.data[0].error_info,
											icon:0,
											buttosn:1,
											fn:function(btn){

											}
										});
									}
								}
							});
						}
	                }
	            });
			}
		}
		Ext.onReady(gestor_operativo.init,gestor_operativo);
	}else{
		tab.setActiveTab(gestor_operativo.id+'-tab');
	}
</script>