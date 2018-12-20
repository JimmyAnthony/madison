<script type="text/javascript">
	/**
	 * @author  Jim
	 */
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if (!Ext.getCmp('home_delivery_urb-tab')){
		var home_delivery_urb = {
			id:'home_delivery_urb',
			id_menu:'<?php echo $p["id_menu"];?>',
			url:'/gestion/gtransporte/',
			varsmapa:{
				directionsDisplay: new google.maps.DirectionsRenderer(),
				directionsService : new google.maps.DirectionsService(),
				trafficLayer:new google.maps.TrafficLayer(),
				markers:[]
			},
			recordDelivery:{},
			PositionReload:{
				tipo_marker:'',
				id_marker:0
			},
			id_shipper:0,
			fecha_ss:'',
			prov_codigo:0,
			id_agencia:0,
			contenedor:null,
			map:'',
			transferencia:true,
			destino:{
				record:{},
				id_geo:0,
				lat:-11.782413062516948,
				lng:-76.79493715625
			},
			origen:{
				saveRercord:{},
				id_manifiesto:0,
				pointO:[],
				record:{},
				id_rec_gui:0,
				id_age:0,
				lat:0,
				lng:0
			},
			unidad:{
				record:{},
				placa:'',
				lat:0,
				lng:0
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
				}
			},
			tiempo:{
				task:{},
				runner: new Ext.util.TaskRunner(),
				gui_numero:0
			},
			init:function(){	
				Ext.tip.QuickTipManager.init();
				home_delivery_urb.tiempo.task = home_delivery_urb.tiempo.runner.newTask({
                    run: function(){
                        home_delivery_urb.newServices();
                    },
                    interval: (10000 * 2)
                });

                home_delivery_urb.tiempo.task.start();
				this.store_recoleccion = Ext.create('Ext.data.Store',{
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
						{name:'servicio_px', type:'float'},
						{name:'servicio_py', type:'float'},
						{name:'id_man', type:'int'}
					],
					proxy:{
						type:'ajax',
						url:home_delivery_urb.url+'scm_scm_home_delivery_unidades/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});

				this.porgeoreferenciar = Ext.create('Ext.data.Store',{
					autoLoad:false,
					fields:[
						{name:'origen', type:'string'},
						{name:'destino_zona', type:'string'},
						{name:'destino_dir', type:'string'},
						{name:'cliente', type:'string'},
						{name:'hora_ss', type:'string'},
						{name:'estado', type:'string'},
						{name:'hora_chk', type:'string'},
						{name:'time_delay', type:'string'},
						{name:'ship_logo', type:'string'},
						{name:'guia', type:'string'},
						{name:'chk', type:'string'},
						{name:'id_shipper', type:'int'},
						{name:'placa_unidad', type:'string'},
						{name:'fecha_ss', type:'string'},
						{name:'prov_codigo', type:'int'}
					],
					proxy:{
						type:'ajax',
						url:home_delivery_urb.url+'scm_scm_home_delivery_servicios/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});

				this.store_ruta = Ext.create('Ext.data.Store',{
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
						{name:'guia', type:'int'}
					],
					//groupField: 'guia',
					proxy:{
						type:'ajax',
						url:home_delivery_urb.url+'scm_scm_home_delivery_lista_ruta/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				Ext.define('grid_motos',{
				    extend:'Ext.data.Model',
				    fields:[
						{name:'id_unidad', type:'string'},
						{name:'placa', type:'string'},
						{name:'id_man', type:'string'},
						{name:'posicion', type:'string'},
						{name:'pos_px', type:'float'},
						{name:'pos_py', type:'float'},
						{name:'tipo', type:'string'},
						{name:'sentido', type:'string'},
						{name:'id_per', type:'int'},
						{name:'time', type:'string'},
						{name:'distance', type:'string'},
						{name:'gps_dist_m', type:'int'},
						{name:'gps_dist_t', type:'string'},
						{name:'gps_time_s', type:'int'},
						{name:'gps_time_t', type:'string'},
						{name:'age_x', type:'float'},
						{name:'age_y', type:'float'}
				    ]
				});
				this.unidades = Ext.create('Ext.data.Store',{
					model:'grid_motos',
					proxy:{
						type:'ajax',
						url:home_delivery_urb.url+'scm_scm_home_delivery_unidad_gps_distance/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				this.store_status = Ext.create('Ext.data.Store',{
                	fields:[
	                	{name:'placa', type:'string'}, 
	                	{name:'courrier', type:'string'}, 
	                	{name:'tot_ld', type:'int'}, 
	                	{name:'recojo_ld', type:'int'}, 
	                	{name:'recojo_ro', type:'int'}, 
	                	{name:'recojo_no_ro', type:'int'}, 
	                	{name:'recojo_sp', type:'int'}, 
	                	{name:'entrega_ld', type:'int'}, 
	                	{name:'entrega_dl', type:'int'}, 
	                	{name:'entrega_ca', type:'int'}, 
	                	{name:'entrega_sp', type:'int'}, 
	                	{name:'rendir_la', type:'int'}, 
	                	{name:'rendir_no_la', type:'int'}, 
	                	{name:'rendir_lv', type:'int'}, 
	                	{name:'rendir_no_lv', type:'int'}, 
	                	{name:'progreso', type:'float'}, 
	                	{name:'time_upd', type:'string'}, 
	                	{name:'telefono', type:'string'}, 
	                	{name:'bateria', type:'float'}, 
	                	{name:'tipo', type:'string'}, 
	                	{name:'id_unidad', type:'int'}, 
	                	{name:'id_man', type:'int'}
						
                	],
                	proxy:{
                		type:'ajax',
                		url:home_delivery_urb.url+'scm_scm_home_delivery_panel/',
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
						url:home_delivery_urb.url+'scm_usr_sis_personal/',
						reader:{
							type:'json',
							rootProperty:'data'
						}
					}
				});

				this.store_estado = Ext.create('Ext.data.Store',{
					autoLoad:true,
					fields:[
						{name: 'chk', type: 'string'},
						{name: 'descri', type: 'string'}
					],
					proxy:{
						type:'ajax',
						url:home_delivery_urb.url+'get_estados/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				
				tab.add({
					id:home_delivery_urb.id+'-tab',
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
							id:home_delivery_urb.id+'-agencia',
							fieldLabel:'Agencia',
							labelWidth:50,
							store:Ext.create('Ext.data.Store',{
							fields:[
									{name:'prov_codigo', type:'int'},
									{name:'prov_nombre', type:'string'}
							],
							proxy:{
								type:'ajax',
								url:home_delivery_urb.url+'get_usr_sis_provincias/',
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
									home_delivery_urb.getReloadComponent();
								}
							}
						},'-',
						{
							xtype:'datefield',
							id:home_delivery_urb.id+'-fecha',
							fieldLabel:'Fecha',
							labelWidth:35,
							//labelAlign:'top',
							width:150,
							value:new Date()
						},
						{xtype:'tbspacer',width:5},
						{
							xtype:'checkbox',
							hidden:true,
							labelWidth:35,
							fieldLabel:'Hasta',
							id:home_delivery_urb.id+'chk_hasta',
							listeners:{
								change:function( obj, newValue, oldValue, eOpts ){
									if(newValue){
										Ext.getCmp(home_delivery_urb.id+'chk-solo-ruta').setValue(true);
										Ext.getCmp(home_delivery_urb.id+'-hasta').setReadOnly(false);
										Ext.getCmp(home_delivery_urb.id+'-hasta').setValue(new Date());
										Ext.getCmp(home_delivery_urb.id+'-hasta').show();
										Ext.getCmp(home_delivery_urb.id+'chk-solo-ruta').setReadOnly(true);
									}else{
										Ext.getCmp(home_delivery_urb.id+'chk-solo-ruta').setValue(false);
										Ext.getCmp(home_delivery_urb.id+'-hasta').setReadOnly(true);
										Ext.getCmp(home_delivery_urb.id+'-hasta').hide();
										Ext.getCmp(home_delivery_urb.id+'-hasta').setValue('');
										Ext.getCmp(home_delivery_urb.id+'chk-solo-ruta').setReadOnly(false);
									}
								}
							}
						},
						{
							xtype:'datefield',
							hidden:true,
							id:home_delivery_urb.id+'-hasta',
							readOnly:true,
							width:130
						},
						{
							xtype:'checkbox',
							hidden:true,
							labelAlign:'right',
							fieldLabel:'Solo En Ruta',
							id:home_delivery_urb.id+'chk-solo-ruta',
							listeners:{
								change:function( obj, newValue, oldValue, eOpts ){

								}
							}
						},
						{
							xtype:'combo',
							fieldLabel:'Estado',
							labelWidth:50,
							id:home_delivery_urb.id+'-stado',
							hidden:true,
							store:home_delivery_urb.store_estado,
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
									Ext.getCmp(home_delivery_urb.id+'-region-south').show();
									home_delivery_urb.getReloadComponent();
								}
							}
						},
						{
							text:'',
							id:home_delivery_urb.id+'-btn-find',
							icon: '/images/icon/search.png',
							tooltip:'Buscar',
							listeners:{
								click:function(obj,opts){
									home_delivery_urb.getReloadComponent();
								}
							}
						}
					],
					listeners:{
						beforerender: function(obj, opts){
	                        global.state_item_menu(home_delivery_urb.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,home_delivery_urb.id_menu);
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(home_delivery_urb.id_menu, false);
	                    },
	                    boxready:function(obj, width, height, eOpts ){
	                    	/*home_delivery_urb.showEjecutados();
	                    	home_delivery_urb.showSolicitudes();*/
	                    	home_delivery_urb.setMachineEstructure('A');
	                    	// Set CSS for the control module.
							home_delivery_urb.setIconModule();
	                    }
					}
				}).show();
			},
			setInitMachine:function(){
				//MODULE A
				home_delivery_urb.module.A.C='<div id="'+home_delivery_urb.id+'-map" class="ue-map-canvas"></div>';
				home_delivery_urb.module.A.W=Ext.create('Ext.grid.Panel',{
					id:home_delivery_urb.id+'-recol-ruta',
					border:false,
					height:'100%',
					store:home_delivery_urb.store_recoleccion,
					columnLines:true,
					columns:{
						items:[
								{
									text:'Unidad',
									dataIndex:'placa',
									width:85,
									align:'center',
									renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
										metaData.style = "padding: 2px; margin: 0px;";
										metaData.tdAttr = 'data-qtip=Bateria:' + record.get('bateria') + '%';
										if (record.get('tipo')==5){
											return '<div style="height:20px;display:inline-block;width:80px;"><table width="80px"><tr><td width="80px";><img src="/images/icon/moto.png">&nbsp;&nbsp;&nbsp;&nbsp;'+value+'</td><td  width="5px"; style="background-color: #'+( record.get('pos_px')!='' ?'3CB371':'DC143C')+';"></td></tr></table></div>';
										}else if(record.get('tipo')==4){
											return '<img src="/images/icon/car.png">&nbsp;&nbsp;&nbsp;&nbsp;'+value;	
										}
										
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
								},
								{
									text:'T. Servicio',
									dataIndex:'time_service',
									align:'center',
									width:80
								}
						]
					},
					listeners:{
						beforeselect:function(obj, record, index, eOpts ){
							home_delivery_urb.destino.lat=record.get('servicio_px');
							home_delivery_urb.destino.lng=record.get('servicio_py');
					        home_delivery_urb.unidad.lat=record.get('pos_px');
					        home_delivery_urb.unidad.lng=record.get('pos_py');
					        home_delivery_urb.unidad.placa=record.get('placa');
							home_delivery_urb.getRutaExe(record);
						}
					},
					viewConfig: {
        				getRowClass: function(record, rowIndex, rowParams, store){
        				}
        			}
						
				});
				home_delivery_urb.module.A.S=Ext.create('Ext.grid.Panel',{
					id:home_delivery_urb.id+'-ruta',
					store:home_delivery_urb.store_ruta,
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
											id_menu: home_delivery_urb.id_menu,
											icons:[
                                                {id_serv: 0, img: 'gears3.png', qtip: 'Click para Procesar Registro', js: ''}
                                            ]
										});

									}
								},
								{
									text:'<div><img src="/images/icon/save.png"></div>',
									dataIndex:'ruta',
									width:50,
									sortable: false,
									align:'center'
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
									text:'Direccion',
									dataIndex:'direccion',
									flex:2,
									sortable: false
								},
								{
									text:'Cliente',
									dataIndex:'cliente',
									flex:1,
									sortable: false
								},
								{
									text:'Hora Solicitud',
									dataIndex:'hora_ss',
									flex:1,
									sortable: false
								},
								{
									text:'Estado',
									dataIndex:'estado',
									flex:1,
									sortable: false
								},
								{
									text:'Hora Chk',
									dataIndex:'hora_chk',
									flex:1,
									sortable: false
								},
								{
									text:'Ultimo Chk',
									dataIndex:'time_last',
									flex:1,
									sortable: false
								},
								{
									text:'Total',
									dataIndex:'time_total',
									flex:1,
									sortable: false
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
			        			//home_delivery_urb.findMaker(parseInt(data.records[0].data.guia));
			        		}
			            }
			        },
			        listeners:{
			        	beforeselect:function(obj, record, index, eOpts ){
			        		/*var guia = parseInt(record.get('guia'));
			        		home_delivery_urb.findMaker(guia);*/
			        		//console.log(guia);
			        		home_delivery_urb.origen.record=record;
			        		home_delivery_urb.google_ruta();
			        	},
			        	cellclick:function( obj, td, cellIndex, record, tr, rowIndex, e, eOpts ){
			        	},
			        	headerclick:function( ct, column, e, t, eOpts ){
			        		/*if (column.componentLayout.owner.dataIndex=='id_doc'){
			        			home_delivery_urb.order_ruta();
			        		}*/
			        	}
			        }
				});

				//MODULE B
				home_delivery_urb.module.B.W=Ext.create('Ext.panel.Panel',{
					border:false,
					layout:'fit',
					items:[
						{
							xtype: 'tabpanel',
						    //tabPosition: 'left',
						    //tabRotation: 0,
						    id:home_delivery_urb.id+'tab-content',
						    layout:'fit',
						    tabBar: {
						        border: false
						    },
						    listeners: {
				                'tabchange': function(tabPanel, tab){
				                    tabPanel.doLayout();
				                }
				            },
						    items: [
						    {
						        title: 'Destino',
						        id: home_delivery_urb.id+'-tab-destino',
						        disabled:true,
						        layout:'fit',
						        bbar:[
						        	{
                                        xtype:'button',
                                        margin:'10, 0 0 0',
                                        text:'Confirmar Dirección',
                                        icon:'/images/icon/close_nov.ico',
                                        listeners:{
                                            click:function(obj,e){
                                            	home_delivery_urb.setSaveAddress();
                                            }
                                        }
                                    },
                                    {
                                        xtype:'button',
                                        margin:'10, 0 0 0',
                                        text:'Cancelar',
                                        icon:'/images/icon/cancel.png',
                                        listeners:{
                                            click:function(obj,e){
                                            	home_delivery_urb.setClearPanelModule();
                                            	home_delivery_urb.setMachineEstructure('B');
                                            }
                                        }
                                    }
						        ],
						        items:[
						        	{
                                       xtype: 'findlocation',
                                       id: home_delivery_urb.id+'-destino',
                                       mapping: false,
                                       clearReferent:false,
                                       getMapping:false,
                                       setMapping:home_delivery_urb.id+'-map',
                                       trust:true,
                                       listeners:{
                                            afterrender: function(obj){
                                            }
                                       }
                                    }
						        ]
						    },{
						        title: 'Origen - Asignación',
						        id: home_delivery_urb.id+'-tab-origen',
						        disabled:true,
						        layout:'border',
                                bbar:[
                                	'-',
									{
										text:'Asignar Unidad',
										icon:'/images/icon/save.png',
										listeners:{
											click:function(){
												home_delivery_urb.save_unidad();
											}
										}
									},
									{
                                        xtype:'button',
                                        text:'Re-Calcular',
                                        icon: '/images/icon/reloj.ico',
                                        listeners:{
                                            click:function(){
                                            	home_delivery_urb.getAgenciaGrid();
                                            }
                                        }
                                    },
									'-',
									{
										xtype:'checkbox',
										id: home_delivery_urb.id+'-chk-msn',
										fieldLabel:'Notificar por SMS',
										labelWidth:100,
										listeners:{
											change:function( obj, newValue, oldValue, eOpts ){
												if (newValue){
													
												}else{
													
												}
											}
										}

									},
									{
                                        xtype:'button',
                                        margin:'10, 0 0 0',
                                        //text:'Cancelar',
                                        icon:'/images/icon/cancel.png',
                                        listeners:{
                                            click:function(obj,e){
                                            	if(home_delivery_urb.transferencia){
                                            		var mensaje = "¿Esta seguro de cancelar la asignación?";
                                            	}else{
                                            		var mensaje = "¿Esta seguro de cancelar la transferencia?";
                                            	}
                                            	global.Msg({
									                msg: mensaje,
									                icon: 2,
									                buttons: 3,
									                fn: function(btn){
									                    if (btn == 'yes'){
									                    	home_delivery_urb.setClearPanelModule();
                                            				home_delivery_urb.setMachineEstructure('B');
                                            			}
                                            		}
                                            	});
                                            }
                                        }
                                    }
								],
						        items:[
						        	{
						        		region:'center',
						        		layout:'fit',
						        		border:false,
						        		items:[
						        			{
                                                xtype:'grid',
                                                margin:'0 0 0 0',
                                                store:Ext.create('Ext.data.Store',{
                                                    model:'grid_agencias',
                                                    proxy:{
                                                        type:'ajax',
                                                        url:home_delivery_urb.url+'scm_scm_home_delivery_agencia_shipper/',
                                                        reader:{
                                                            type:'json',
                                                            root:'data'
                                                        }
                                                    },
                                                    fields:[
														{name:'id_agencia', type:'int'},
														{name:'age_codigo', type:'int'},
														{name:'agencia', type:'string'},
														{name:'dir_calle', type:'string'},
														{name:'dir_referen', type:'string'},
														{name:'ciu_iata', type:'string'},
														{name:'distrito', type:'string'},
														{name:'ciu_id', type:'int'},
														{name:'ciu_ubigeo', type:'string'},
														{name:'dir_px', type:'string'},
														{name:'dir_py', type:'string'},
														{name:'shi_logo', type:'string'},
														{name:'und_id', type:'int'},
														{name:'gps_dist_m', type:'string'},
														{name:'gps_dist_t', type:'string'},
														{name:'gps_time_s', type:'string'},
														{name:'gps_time_t', type:'string'},
														{name:'und_px', type:'string'},
														{name:'und_py', type:'string'},
														{name:'tipo', type:'string'},
														{name:'sentido', type:'string'},
														{name:'und_placa', type:'string'},
														{name:'dis_text', type:'string'},
														{name:'dis_value', type:'string'},
														{name:'dur_text', type:'string'},
														{name:'dur_value', type:'string'},
														{name:'time_total', type:'string'},
														{name:'time_t', type:'string'}
													]
                                                }),
                                                id:home_delivery_urb.id+'-grid-agencias-longitud',
                                                columnLines: true,
                                                height:'50%',
                                                columns:{
                                                    items:[
                                                    		{
													            xtype: 'checkcolumn',
													            hidden:true,
													            text: 'Item',
													            width:40,
													            dataIndex: 'active',
													            stopSelection : false,
													            align: 'center',
													            defaultType: 'boolean',
													            listeners:{
													            	"checkchange": function( comp, rowIndex, checked, eOpts ){
																     	var st = home_delivery_urb.get_count_sel();
																     	if(st==0){
																     		var grid = Ext.getCmp(home_delivery_urb.id+'-grid-agencias-longitud');
																			var store = grid.getStore();
																			store.getAt(rowIndex).set('active', true);
																     	}else{
																     		//if(!checked){
																     			home_delivery_urb.google_ruta();
																     		//}
																     	}
																    }
													            }
													        },
                                                            {
                                                                text:'Agencia',
                                                                flex:1,
                                                                dataIndex:'agencia'
                                                            },
                                                            {
                                                                text:'Tiempo',
                                                                flex:1,
                                                                //align:'center',
                                                                dataIndex:'dur_text'
                                                            },
                                                            {
                                                                text:'Und.Cercana',
                                                                flex:1,
                                                                dataIndex:'und_placa'
                                                            },
                                                            {
                                                                text:'T. Aprox',
                                                                flex:1,
                                                                dataIndex:'gps_time_t'
                                                            },
                                                            {
                                                                text:'T. Total',
                                                                flex:1,
                                                                //align:'center',
                                                                dataIndex:'time_t'
                                                            }
                                                    ]
                                                },
                                                listeners:{
                                                    beforeselect:function(obj, record, index, eOpts ){
	                                                    home_delivery_urb.origen.record=record;
                                                        home_delivery_urb.origen.lat=parseFloat(record.get('dir_px'));
                                                        home_delivery_urb.origen.lng=parseFloat(record.get('dir_py'));
                                                        home_delivery_urb.unidad.lat=parseFloat(record.get('und_px'));
                                                        home_delivery_urb.unidad.lng=parseFloat(record.get('und_py'));
                                                        home_delivery_urb.getReloadUnidades();
                                                    }
                                                }
                                            }
						        		]
						        	},
						        	{
						        		region:'south',
						        		height:'50%',
						        		id:home_delivery_urb.id+'-panel-unidades',
						        		layout:'fit',
						        		border:false,
						        		bbar:[
						        			{
												xtype:'combo',
												id:home_delivery_urb.id+'-per_id',
												allowBlank:false,
												width:'100%',
												anchor:'100%',
												fieldLabel:'Chofer/Motorizado',
												store:home_delivery_urb.store_personal,
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
												id:home_delivery_urb.id+'-unidades-asignacion-ruta',
												store:home_delivery_urb.unidades,
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
														home_delivery_urb.unidad.record=record;
														if(parseInt(record.get('id_per'))!=0){
															if(home_delivery_urb.transferencia){
																Ext.getCmp(home_delivery_urb.id+'-per_id').setValue(parseInt(record.get('id_per')));
															}
														}
														home_delivery_urb.unidad.lat=parseFloat(record.get('pos_px'));
                                                        home_delivery_urb.unidad.lng=parseFloat(record.get('pos_py'));
                                                        home_delivery_urb.google_ruta();
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
						    }]
						}
					]
						
				});
				home_delivery_urb.module.B.S=Ext.create('Ext.grid.Panel',{
					id:home_delivery_urb.id+'-por-georeferenciar',
					store:home_delivery_urb.porgeoreferenciar,
					columnLines:true,
					columns:{
						items:[
								{
									text:'Origen',
									dataIndex:'origen',
									flex:1
								},
								{
									text:'Zona',
									dataIndex:'destino_zona',
									flex:1
								},
								{
									text:'Dirección',
									dataIndex:'destino_dir',
									flex:1
								},
								{
									text:'Nombre',
									dataIndex:'cliente',
									flex:1
								},
								{
									text:'Hora Solicitud',
									dataIndex:'hora_ss',
									flex:1
								},
								{
									text:'Estado',
									dataIndex:'estado',
									flex:1
								},
								{
									text:'Hora Chk',
									dataIndex:'hora_chk',
									flex:1
								},
								{
									text:'Ultimo',
									dataIndex:'time_last',
									flex:1
								},
								{
									text:'Duración',
									dataIndex:'time_delay',
									flex:1
								},
								{
									text:'Unidad',
									dataIndex:'placa_unidad',
									flex:1
								}
						]
					},
					listeners:{
						beforeselect:function(obj, record, index, eOpts ){
							home_delivery_urb.get_georeferencias(record);
						}
					}
				});
				home_delivery_urb.module.C.C=Ext.create('Ext.grid.Panel',{
					id:home_delivery_urb.id+'-grid-status',
					columnWidth:1,
					border:true,
					//height:800,
					store:home_delivery_urb.store_status,
					features: [
                        {
                            ftype: 'summary',
                            dock: 'bottom'
                        }
                    ],
					columnLines:true,
					columns:{
						items:[
								{
									text:'Unidad',
									dataIndex:'placa',
									align:'center',
									width:80
								},
								{
									text:'Courrier',
									dataIndex:'courrier',
									flex:3,
									summaryRenderer: function(value, summaryData, dataIndex){
                                        return 'Totales:';
                                    }
								},
								{
									text:'Total </br>Asignados',
									dataIndex:'tot_ld',
									align:'center',
									width:70,
									summaryType: 'sum',
                                    summaryRenderer: function(value, summaryData, dataIndex){
                                        return Ext.util.Format.number(value, '0');
                                    }
								},
								{
									text:'Recolecciones',
									flex:1,
									columns:[
												{
													text:'Asig',
													dataIndex:'recojo_ld',
													align:'center',
													width:35,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												},
												{
													text:'Recol',
													dataIndex:'recojo_ro',
													align:'center',
													width:40,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												},
												{
													text:'No Recol',
													dataIndex:'recojo_no_ro',
													align:'center',
													width:55,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												},
												{
													text:'Pend',
													dataIndex:'recojo_sp',
													align:'center',
													width:35,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												}
									]
								},
								{
									text:'Entregas',
									flex:1,
									columns:[
												{
													text:'Asig',
													dataIndex:'entrega_ld',
													align:'center',
													width:35,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												},
												{
													text:'Ent',
													dataIndex:'entrega_dl',
													align:'center',
													width:35,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												},
												{
													text:'Rezag.',
													dataIndex:'entrega_ca',
													align:'center',
													width:50,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												},
												{
													text:'Pend',
													dataIndex:'entrega_sp',
													align:'center',
													width:40,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												}
									]
								},
								{
									text:'Rendiciones',
									flex:1,
									columns:[
												{
													text:'Rend',
													dataIndex:'rendir_la',
													align:'center',
													width:35,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												},
												{
													text:'P.Rend',
													dataIndex:'rendir_no_la',
													align:'center',
													width:50,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												},
												{
													text:'Dev',
													dataIndex:'rendir_lv',
													align:'center',
													width:35,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												},
												{
													text:'P.Dev',
													dataIndex:'rendir_no_lv',
													align:'center',
													width:45,
													summaryType: 'sum',
				                                    summaryRenderer: function(value, summaryData, dataIndex){
				                                        return Ext.util.Format.number(value, '0');
				                                    }
												}
									]
								},
								{
									text:'Progreso',
									dataIndex:'progreso',
									align:'center',
									width:75,
									renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
										metaData.style = "padding: 2px 2px 2px 2px; margin: 0px;";
										var id = Ext.id();
										var progreso = record.get('progreso');
					                    Ext.defer(function () {
					                        Ext.widget('progressbar', {
					                            renderTo: id,
					                            value: progreso,
					                            width: 70,
					                           // height:17,
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
					                    return Ext.String.format('<div id="{0}" align="center" style="height: 20px;""></div>', id);
									}
								},
								{
									text:'Ultima',
									dataIndex:'time_upd',
									align:'center',
									width:60
								},
								{
									text:'Bateria',
									dataIndex:'bateria',
									align:'center',
									width:50,
									renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
										metaData.style = "padding: 2px 2px 2px 2px; margin: 0px;";
										var id = Ext.id();
										var bateria = parseInt(record.get('bateria'))/100;
					                    Ext.defer(function () {
					                        Ext.widget('progressbar', {
					                            renderTo: id,
					                            value: bateria,
					                            width: 40,
					                           // height:17,
					                            animate:true,
					                            listeners:{
					                            	afterrender:function( obj, eOpts ){
					                            		if (obj.getValue() < 0.2){
					                            			obj.addCls('p.bar-red');	
					                            		}else{
					                            			obj.addCls('p_bar-blue');	
					                            		}
					                            	}
					                            }
					                            
					                        });
					                    }, 50);
					                    return Ext.String.format('<div id="{0}" align="center" style="height: 20px;""></div>', id);
									}
								},
								{
									text:'',
									dataIndex:'',
									align:'center',
									width:70
								}
						]
					},
					listeners:{
						afterrender:function(){
						}
					}
				});
			},
			setClearMachine:function(){
				try{
					home_delivery_urb.module={
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
						}
					};
					home_delivery_urb.setResetValues('T');
					Ext.getCmp(home_delivery_urb.id+'-region-north').destroy();
					Ext.getCmp(home_delivery_urb.id+'-region-west').destroy();
					Ext.getCmp(home_delivery_urb.id+'-sub-region-center').update('');
					Ext.getCmp(home_delivery_urb.id+'-sub-region-center').destroy();
					Ext.getCmp(home_delivery_urb.id+'-sub-region-south').destroy();
					Ext.getCmp(home_delivery_urb.id+'-region-center').destroy();
					Ext.getCmp(home_delivery_urb.id+'-region-east').destroy();
					Ext.getCmp(home_delivery_urb.id+'-region-south').destroy();
					Ext.getCmp(home_delivery_urb.id+'-tab').remove(home_delivery_urb.id+'form');

                }catch(err){
            		console.log(err);
            	}
			},
			setMachineEstructure:function(tipo){
				home_delivery_urb.setClearMachine();
            	home_delivery_urb.setInitMachine();
				var panel = Ext.create('Ext.form.Panel',{
					id:home_delivery_urb.id+'form',
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
								id:home_delivery_urb.id+'-region-north',
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
								id:home_delivery_urb.id+'-region-west',
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
								}
							},
							{
								region:'center',
								id:home_delivery_urb.id+'-region-center',
								layout:'border',
								border:false,
								items:[
									{
										region:'center',
										id:home_delivery_urb.id+'-sub-region-center',
										border:false,
										layout:'fit',
										html:'<div id="'+home_delivery_urb.id+'-map" class="ue-map-canvas"></div>'
									},
									{
										region:'south',
										id:home_delivery_urb.id+'-sub-region-south',
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
								id:home_delivery_urb.id+'-region-east',
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
								id:home_delivery_urb.id+'-region-south',
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
							home_delivery_urb.module.tipo=tipo;
							Ext.getCmp(home_delivery_urb.id+'chk_hasta').hide();
							Ext.getCmp(home_delivery_urb.id+'chk_hasta').setValue(false);
							Ext.getCmp(home_delivery_urb.id+'-hasta').hide();
							Ext.getCmp(home_delivery_urb.id+'-hasta').setValue('');
							Ext.getCmp(home_delivery_urb.id+'chk-solo-ruta').hide();
							Ext.getCmp(home_delivery_urb.id+'chk-solo-ruta').setValue(false);
							Ext.getCmp(home_delivery_urb.id+'-stado').hide();
							home_delivery_urb.setClearPanelModule();
							switch(tipo){
								case 'A':
									Ext.getCmp(home_delivery_urb.id+'-region-center').update(home_delivery_urb.module.A.C);
									Ext.getCmp(home_delivery_urb.id+'-region-west').add(home_delivery_urb.module.A.W);
									Ext.getCmp(home_delivery_urb.id+'-region-west').show();
									Ext.getCmp(home_delivery_urb.id+'-sub-region-south').setHeight(150);
									Ext.getCmp(home_delivery_urb.id+'-sub-region-south').show();
									Ext.getCmp(home_delivery_urb.id+'-sub-region-south').add(home_delivery_urb.module.A.S);
									home_delivery_urb.setMap();
								break;
								case 'B':
									Ext.getCmp(home_delivery_urb.id+'-stado').show();
									Ext.getCmp(home_delivery_urb.id+'-stado').setValue('SS');
									Ext.getCmp(home_delivery_urb.id+'-region-west').add(home_delivery_urb.module.B.W);
									Ext.getCmp(home_delivery_urb.id+'-region-west').hide();
									Ext.getCmp(home_delivery_urb.id+'-region-south').setHeight(150);
									Ext.getCmp(home_delivery_urb.id+'-region-south').show();
									Ext.getCmp(home_delivery_urb.id+'-region-south').add(home_delivery_urb.module.B.S);
									home_delivery_urb.setMap();
								break;
								case 'C':
									Ext.getCmp(home_delivery_urb.id+'-sub-region-center').add(home_delivery_urb.module.C.C);
									Ext.getCmp(home_delivery_urb.id+'chk_hasta').show();
									Ext.getCmp(home_delivery_urb.id+'chk-solo-ruta').show();
								break;
							}
							home_delivery_urb.getReloadComponent();
		       			}
					}
				});
				Ext.getCmp(home_delivery_urb.id+'-tab').add(panel);
			},
			setMap:function(){
				var directionsService = new google.maps.DirectionsService();
		        
		        var rendererOptions = {
					  draggable: true,
					  suppressMarkers: true
				};
		        home_delivery_urb.varsmapa.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
		        home_delivery_urb.varsmapa.directionsService = new google.maps.DirectionsService();

		        var myLatlng = new google.maps.LatLng(-12.0473179,-77.0824867);

		        var mapOptions = {
					zoom: 18,
    				center: myLatlng,
    				mapTypeId: google.maps.MapTypeId.ROADMAP
				};
		        home_delivery_urb.map = new google.maps.Map(document.getElementById(home_delivery_urb.id+'-map'), mapOptions);
		        

		        var homeControlDiv = document.createElement('div');
		        var homeControl = new HomeControl(homeControlDiv, home_delivery_urb.map, myLatlng);
		        homeControlDiv.index = 1;
		        home_delivery_urb.map.controls[google.maps.ControlPosition.TOP_CENTER].push(homeControlDiv);

		        var hdiv = document.createElement('div');
		        var hcontro = new home_delivery_urb.HHomeControl(hdiv,home_delivery_urb.map);
		        hdiv.index = 1;
		        home_delivery_urb.map.controls[google.maps.ControlPosition.TOP_LEFT].push(hdiv);

		        home_delivery_urb.varsmapa.directionsDisplay.setMap(home_delivery_urb.map);
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
			  controlText.innerHTML = '<input type="checkbox" id="trafic" name="trafic" onClick="home_delivery_urb.trafic();"> <b>Ver Tráfico</b>';
			  controlUI.appendChild(controlText);
			},
			trafic:function(){
				 if (document.getElementById("trafic").checked){
				 	 home_delivery_urb.varsmapa.trafficLayer.setMap(home_delivery_urb.map);
				 }else{
				 	home_delivery_urb.varsmapa.trafficLayer.setMap(null);
				 }

			},
			setPanelModule:function(){
				var controlModule = document.createElement('div');
				controlModule.setAttribute("id", "panelDiv1");
				controlModule.className = 'panelDiv';
				controlModule.innerHTML = '<p><span>Origen:</span><span id="sp_origen"></span></p><p><span>Destino:</span><span id="sp_destino"></span></p><p><span>Dirección:</span><span id="sp_direccion"></span></p><p><span>Cliente:</span><span id="sp_cliente"></span></p><p><span>Hora SS:</span><span id="sp_hora_ss"></span></p><p><span>Estado:</span><span id="sp_estado"></span></p>';
				document.getElementById(home_delivery_urb.id+'-tab').appendChild(controlModule);

				var controlModuleTiempo = document.createElement('div');
				controlModuleTiempo.setAttribute("id", "panelDivTiempo");
				controlModuleTiempo.className = 'panelDivTiempo';
				controlModuleTiempo.innerHTML = '<table class="clsRec"><tr><td class="clskm" id="clskm">0 km</td><td class="clsdur" id="clsdur">00:00:00</td></tr></table>';
				document.getElementById(home_delivery_urb.id+'-tab').appendChild(controlModuleTiempo);
			},
			setClearPanelModule:function(){
				try{
					document.getElementById("panelDiv1").remove();
					document.getElementById("panelDivTiempo").remove();
				}catch(err){
            		console.log(err);
            	}
			},
			setIconModule:function(){
				var controlModule = document.createElement('div');
				controlModule.className = 'contetDiv';
				controlModule.innerHTML = '<div id="contetDivA"><span></span></div><div id="contetDivB"><span></span></div><div id="contetDivC"><span></span></div>';
				document.getElementById(home_delivery_urb.id+'-tab').appendChild(controlModule);
				home_delivery_urb.setButtonModule();
			},
			setButtonModule:function(){
				try{
                	Ext.getCmp(home_delivery_urb.id+'btn-a').destroy();
                }catch(err){
            		console.log(err);
            	}
            	try{
                	Ext.getCmp(home_delivery_urb.id+'btn-b').destroy();
                }catch(err){
            		console.log(err);
            	}
            	try{
                	Ext.getCmp(home_delivery_urb.id+'btn-c').destroy();
                }catch(err){
            		console.log(err);
            	}

				Ext.create('Ext.button.Button', {
            		id:home_delivery_urb.id+'btn-a',
            		disabled:true,
			        text: '<div style="font-size:20px;font-weight: bold;color:#fff">E</div>',
			        cls:'contetDivA',
			        renderTo: 'contetDivA',
			        handler : function(btn) {
			            btn.disable();
			            home_delivery_urb.setMachineEstructure('A');
			            try{
                        	//Ext.getCmp(home_delivery_urb.id+'-win-sol').hide();
                        	Ext.getCmp(home_delivery_urb.id+'btn-b').setDisabled(false);
                        	Ext.getCmp(home_delivery_urb.id+'btn-c').setDisabled(false);
                        	btn.setText('<div style="font-size:20px;font-weight: bold;color:#000">E</div>');
                        	Ext.getCmp(home_delivery_urb.id+'btn-b').setText('<div style="font-size:20px;font-weight: bold;color:#fff">S</div>');
                        	Ext.getCmp(home_delivery_urb.id+'btn-c').setText('<div style="font-size:20px;font-weight: bold;color:#fff">R</div>');
                        	Ext.getCmp(home_delivery_urb.id+'btn-b').setStyle('background','#59789C');
                        }catch(err){
                    		console.log(err);
                    	}
                    	try{
                        	//Ext.getCmp(home_delivery_urb.id+'-win-eje').show();
                        }catch(err){
                    		console.log(err);
                    	}
			            
			        },
			        listeners:{
			        	afterrender:function(obj){
			        		Ext.getCmp(home_delivery_urb.id+'btn-a').setText('<div style="font-size:20px;font-weight: bold;color:#000">E</div>');
			        	}
			        }
			    });
			    Ext.create('Ext.button.Button', {
			    	id:home_delivery_urb.id+'btn-b',
			        text: '<div style="font-size:20px;font-weight: bold;color:#fff">S</div>',
			        cls:'contetDivB',
			        renderTo: 'contetDivB',
			        handler : function(btn) {
			            btn.disable();
			            if(home_delivery_urb.module.tipo!='B'){
			            	home_delivery_urb.setMachineEstructure('B');
			            }else{
			            	Ext.getCmp(home_delivery_urb.id+'-region-south').show();
			            	Ext.getCmp(home_delivery_urb.id+'-stado').setValue('SS');
			            	home_delivery_urb.getReloadComponent();
			            }
			            try{
                        	Ext.getCmp(home_delivery_urb.id+'btn-a').setDisabled(false);
                        	Ext.getCmp(home_delivery_urb.id+'btn-c').setDisabled(false);
                        	btn.setText('<div style="font-size:20px;font-weight: bold;color:#000">S</div>');
                        	btn.setStyle('background','white');
                        	btn.setStyle('height','40px');
                        	btn.setStyle('padding-right','5px');

                        	Ext.getCmp(home_delivery_urb.id+'btn-a').setText('<div style="font-size:20px;font-weight: bold;color:#fff">E</div>');
                        	Ext.getCmp(home_delivery_urb.id+'btn-c').setText('<div style="font-size:20px;font-weight: bold;color:#fff">R</div>');
                        }catch(err){
                    		console.log(err);
                    	}
                    	try{
                        }catch(err){
                    		console.log(err);
                    	}
			        }
			    });
			    Ext.create('Ext.button.Button', {
			    	id:home_delivery_urb.id+'btn-c',
			        text: '<div style="font-size:20px;font-weight: bold;color:#fff">R</div>',
			        cls:'contetDivC',
			        renderTo: 'contetDivC',
			        handler : function(btn) {
			            btn.disable();
			            home_delivery_urb.setMachineEstructure('C');
			            try{
                        	Ext.getCmp(home_delivery_urb.id+'btn-a').setDisabled(false);
                        	Ext.getCmp(home_delivery_urb.id+'btn-b').setDisabled(false);
                        	btn.setText('<div style="font-size:20px;font-weight: bold;color:#000">R</div>');
                        	Ext.getCmp(home_delivery_urb.id+'btn-a').setText('<div style="font-size:20px;font-weight: bold;color:#fff">E</div>');
                        	Ext.getCmp(home_delivery_urb.id+'btn-b').setText('<div style="font-size:20px;font-weight: bold;color:#fff">S</div>');
                        	Ext.getCmp(home_delivery_urb.id+'btn-b').setStyle('background','#59789C');
                        }catch(err){
                    		console.log(err);
                    	}
                    	try{
                        }catch(err){
                    		console.log(err);
                    	}
			        }
			    });
			},
			get_georeferencias:function(record){
				home_delivery_urb.setClearPanelModule();
				home_delivery_urb.setPanelModule();
				document.getElementById("sp_origen").innerHTML=record.get('origen');
				document.getElementById("sp_destino").innerHTML=record.get('destino_zona');
				document.getElementById("sp_direccion").innerHTML=record.get('destino_dir');
				document.getElementById("sp_cliente").innerHTML=record.get('cliente');
				document.getElementById("sp_hora_ss").innerHTML=record.get('hora_ss');
				document.getElementById("sp_estado").innerHTML=record.get('estado');
				home_delivery_urb.destino.record=record;
				home_delivery_urb.id_shipper=record.get('id_shipper');
				home_delivery_urb.fecha_ss=record.get('fecha_ss');
				home_delivery_urb.prov_codigo=record.get('prov_codigo');
				Ext.Ajax.request({
					url:home_delivery_urb.url+'scm_scm_home_delivery_paradas/',
					params:{vp_guia:record.get('guia')},
					success: function(response, options){

						var estado = Ext.getCmp(home_delivery_urb.id+'-stado').getValue();
						switch(estado){
							case 'SS':
								Ext.getCmp(home_delivery_urb.id+'-region-west').show();
								Ext.getCmp(home_delivery_urb.id+'-region-south').hide();
								var res = Ext.JSON.decode(response.responseText).data;
								Ext.each(res,function(obj,index){
									if (obj.tipo_parada == 'E'){
										Ext.getCmp(home_delivery_urb.id+'-tab-destino').setDisabled(false);
										Ext.getCmp(home_delivery_urb.id+'tab-content').setActiveTab(0);
										home_delivery_urb.destino.id_geo=obj.id_geo;
										home_delivery_urb.destino.lat=obj.pos_px;
										home_delivery_urb.destino.lng=obj.pos_py;
										Ext.getCmp(home_delivery_urb.id+'-destino').setGeoLocalizar({id_geo:obj.id_geo,dir_id:obj.id_dir,dir_px:obj.pos_px,dir_py:obj.pos_py});
									}else{
										//VERIFICAR SI VIENEN MAS DE UNA R. FOR MANTENT.
										home_delivery_urb.id_agencia=obj.id_age;
										home_delivery_urb.origen.id_rec_gui=obj.id_rec_gui;
										if(parseInt(record.get('id_geo'))!=0){
											home_delivery_urb.destino.id_geo=parseInt(record.get('id_geo'));
											Ext.getCmp(home_delivery_urb.id+'-tab-origen').setDisabled(false);
											Ext.getCmp(home_delivery_urb.id+'tab-content').setActiveTab(1);
											home_delivery_urb.getAgenciaGrid();
										}else{
											Ext.getCmp(home_delivery_urb.id+'tab-content').setActiveTab(0);
											Ext.getCmp(home_delivery_urb.id+'-tab-origen').setDisabled(true);
											Ext.getCmp(home_delivery_urb.id+'-grid-agencias-longitud').getStore().removeAll();
										}
									}
								});
							break;
							default:
								Ext.getCmp(home_delivery_urb.id+'-region-west').hide();
								home_delivery_urb.recordDelivery=Ext.JSON.decode(response.responseText).data;
								home_delivery_urb.google_ruta();
							break;
						}
					}
				});
			},
			getAgenciaGrid:function(vp_id_agencia){
				var mask = new Ext.LoadMask(Ext.getCmp(home_delivery_urb.id+'-tab'),{
		            msg:'Calculando Rutas...'
		        });
		        mask.show();
		        var arrayAgencia = [];
		        grid = Ext.getCmp(home_delivery_urb.id+'-grid-agencias-longitud');
		        grid.getStore().load({
		            params:{
		            	vp_prov_cod:home_delivery_urb.prov_codigo,
		            	va_shipper:home_delivery_urb.id_shipper,
		            	vp_id_agencia:home_delivery_urb.origen.id_age,
		            	pos_px:home_delivery_urb.destino.lat,
		            	pos_py:home_delivery_urb.destino.lng,
		            	und_px:home_delivery_urb.unidad.lat,
		            	und_py:home_delivery_urb.unidad.lng,
		            	und_placa:home_delivery_urb.unidad.placa
		            },
		            callback:function(){
                        mask.hide();
                        if (grid.getStore().getCount()>0){
                        	if(home_delivery_urb.id_agencia!=0){
								for(var i = 0; i < grid.getStore().getCount(); ++i){
								   var rec = grid.getStore().getAt(i);
								   if (rec.get('id_agencia')==home_delivery_urb.id_agencia){
								   		if(!home_delivery_urb.transferencia){
								   			grid.getStore().getAt(i).set('active', true);
								   		}
								   		grid.getSelectionModel().select(i, true);
								   		return;
								   }
								}
							}else{
								if(!home_delivery_urb.transferencia){
									grid.getStore().getAt(0).set('active', true);
								}
								grid.getSelectionModel().select(0, true);
							}
						}
		            }
		        });
			},
			getReloadUnidades:function(){
				if(home_delivery_urb.transferencia){
					var grid =Ext.getCmp(home_delivery_urb.id+'-unidades-asignacion-ruta');
					grid.getStore().removeAll();
					grid.getStore().load({
						params:{vp_prov_codigo:home_delivery_urb.prov_codigo,vp_id_agencia:home_delivery_urb.origen.record.get('id_agencia')},
						callback:function(){
							for(var i = 0; i < grid.getStore().getCount(); ++i){
							   var rec = grid.getStore().getAt(i);
							   if (parseInt(rec.get('id_unidad'))==parseInt(home_delivery_urb.origen.record.get('und_id'))){
							   		grid.getSelectionModel().select(i, true);
									return;
							   }
							}
						}
					});
				}else{
					home_delivery_urb.google_ruta();
				}
			},
			getReloadComponent:function(){
				home_delivery_urb.tiempo.gui_numero=0;
				switch(home_delivery_urb.module.tipo){
					case 'A':
						var vp_prov_codigo= Ext.getCmp(home_delivery_urb.id+'-agencia').getValue();
						var vp_fecha = Ext.getCmp(home_delivery_urb.id+'-fecha').getRawValue();
						Ext.getCmp(home_delivery_urb.id+'-recol-ruta').getStore().load({
			               params:{vp_prov_codigo:vp_prov_codigo,vp_fecha:vp_fecha},
			               callback:function(){
			               }
			            });
					break;
					case 'B':
						var estado = Ext.getCmp(home_delivery_urb.id+'-stado').getValue();
						if(estado!='SS'){
							Ext.getCmp(home_delivery_urb.id+'-region-west').hide();
						}
						var vp_prov_codigo= Ext.getCmp(home_delivery_urb.id+'-agencia').getValue();
						var vp_fecha = Ext.getCmp(home_delivery_urb.id+'-fecha').getRawValue();
						var grid = Ext.getCmp(home_delivery_urb.id+'-por-georeferenciar');
						grid.getStore().load({
			               params:{vp_agencia:vp_prov_codigo,vp_fecha:vp_fecha,vp_estado:estado},
			               callback:function(){
			               		/*if (grid.getStore().getCount()>0){
		                        	grid.getSelectionModel().select(0, true);
								}*/
								Ext.getCmp(home_delivery_urb.id+'-por-georeferenciar').getStore().each(function(record){
									home_delivery_urb.tiempo.gui_numero=parseInt(record.get('guia'));
									console.log(record.get('guia'));
								});
								//reload personal
								Ext.getCmp(home_delivery_urb.id+'-per_id').getStore().load({
					               params:{vp_agencia:vp_prov_codigo},
					               callback:function(){
									}
			            		});
			               }
			            });
		            break;
		            case 'C':
		            	var grid = Ext.getCmp(home_delivery_urb.id+'-grid-status');
						var vp_agencia = Ext.getCmp(home_delivery_urb.id+'-agencia').getValue();
						var vp_fecha = Ext.getCmp(home_delivery_urb.id+'-fecha').getRawValue();
						var vp_fecha_fin = Ext.getCmp(home_delivery_urb.id+'-hasta').getRawValue();
						var vp_estado = Ext.getCmp(home_delivery_urb.id+'chk-solo-ruta').getValue() == true ? '1':'0';
						grid.getStore().load({
							params:{vp_prov_cod:vp_agencia,vp_fecha_inicio:vp_fecha,vp_fecha_fin:vp_fecha_fin,vp_estado:vp_estado},
							callback:function(){
							}
						});
		            break;
	            }
			},
			setPositionReload:function(record){
				switch(record.tipo_marker){
					case 'A':
						grid = Ext.getCmp(home_delivery_urb.id+'-grid-agencias-longitud');
						if (grid.getStore().getCount()>0){
							for(var i = 0; i < grid.getStore().getCount(); ++i){
							   var rec = grid.getStore().getAt(i);
							   if (rec.get('id_agencia')==record.id_marker){
							   		if(!home_delivery_urb.transferencia){
							   			grid.getStore().getAt(i).set('active', true);
							   		}
							   		grid.getSelectionModel().select(i, true);
							   		return;
							   }
							}
						}
					break;
					case 'M':
						if(home_delivery_urb.transferencia){
							var grid =Ext.getCmp(home_delivery_urb.id+'-unidades-asignacion-ruta');
							for(var i = 0; i < grid.getStore().getCount(); ++i){
							   var rec = grid.getStore().getAt(i);
							   if (parseInt(rec.get('id_unidad'))==parseInt(record.id_marker)){
							   		grid.getSelectionModel().select(i, true);
									return;
							   }
							}
						}
					break;
				}
			},
			setMarker:function(record){
				var point = new google.maps.LatLng(parseFloat(record.dir_px),parseFloat(record.dir_py));
				switch(record.tipo_marker){
					case 'P':
						var marker = new google.maps.Marker({
		                        position: point,
		                        map: home_delivery_urb.map,
		                        animation: google.maps.Animation.DROP,
		                        title: '',
		                        icon:record.shi_logo,
		                        tipo:record.tipo_marker,
		                        id:record.id_marker
		                });
					break;
					default:
						var marker = new google.maps.Marker({
		                        position: point,
		                        map: home_delivery_urb.map,
		                        animation: google.maps.Animation.DROP,
		                        title: '',
		                        icon:'/images/icon/'+record.shi_logo,
		                        tipo:record.tipo_marker,
		                        id:record.id_marker
		                });
					break;
				}
                /*var marker = new google.maps.Marker({
                        position: point,
                        map: home_delivery_urb.map,
                        animation: google.maps.Animation.DROP,
                        title: '',
                        icon:'/images/icon/'+record.shi_logo,
                        tipo:record.tipo_marker,
                        id:record.id_marker
                });*/
                var infowindow = new google.maps.InfoWindow({
                      content: '<div id="content"  style="width:80px;">'+record.agencia+'</div>',
                      maxWidth: 80
                });
                google.maps.event.addListener(marker, 'click', function() {
                	home_delivery_urb.setPositionReload({tipo_marker:marker.tipo,id_marker:marker.id});
                    infowindow.open(home_delivery_urb.map,marker);
                });
                home_delivery_urb.varsmapa.markers.push(marker);
			},
			setClearMarker:function(){
				if (home_delivery_urb.varsmapa.markers) {
			    	for (i in home_delivery_urb.varsmapa.markers) {
			      		home_delivery_urb.varsmapa.markers[i].setMap(null);
			    	}
			  	}
			},
			google_ruta:function(){
				home_delivery_urb.setMap();
				home_delivery_urb.setClearMarker();
				home_delivery_urb.origen.pointO=[];
				switch(home_delivery_urb.module.tipo){
					case 'A':
						//FALTA LA REGLA
						/*{name:'ruta', type:'string'}, 
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
						{name:'guia', type:'int'}*/
					    Ext.getCmp(home_delivery_urb.id+'-ruta').getStore().each(function(record){
		            		//agencia
		            		switch(record.get('tipo')){
		            			case 'E':
		            				home_delivery_urb.setMarker({dir_px:parseFloat(home_delivery_urb.unidad.lat),dir_py:parseFloat(home_delivery_urb.unidad.lng),shi_logo:'moto32.png',agencia:home_delivery_urb.unidad.placa,tipo_marker:'M',id_marker:0});
		            				if(parseInt(record.get('dir_px'))!=0){
			            				home_delivery_urb.destino.lat=record.get('dir_px');
						    			home_delivery_urb.destino.lng=record.get('dir_py');
					    			}
		            			break;
		            			case 'R':
		            				//add array for point route
		            				home_delivery_urb.origen.lat=record.get('dir_px');
						    		home_delivery_urb.origen.lng=record.get('dir_py');
						    		var point = new google.maps.LatLng(parseFloat(record.get('dir_px')),parseFloat(record.get('dir_py')));
						    		home_delivery_urb.origen.pointO.push({location:point});
		            				home_delivery_urb.setMarker({dir_px:record.get('dir_px'),dir_py:record.get('dir_py'),shi_logo:record.get('shipper_logo'),agencia:record.get('cliente'),tipo_marker:'A',id_marker:0});
		            			break;
		            		}
						});
					break;
					case 'B':
						var estado = Ext.getCmp(home_delivery_urb.id+'-stado').getValue();
						switch(estado){
							case 'SS':
								if(!home_delivery_urb.transferencia){
									home_delivery_urb.setMarker({dir_px:home_delivery_urb.origen.saveRercord.get('dir_px'),dir_py:home_delivery_urb.origen.saveRercord.get('dir_py'),shi_logo:home_delivery_urb.origen.saveRercord.get('shi_logo'),agencia:home_delivery_urb.origen.saveRercord.get('agencia'),tipo_marker:'S',id_marker:0});
									var point = new google.maps.LatLng(parseFloat(home_delivery_urb.origen.saveRercord.get('dir_px')),parseFloat(home_delivery_urb.origen.saveRercord.get('dir_py')));
									home_delivery_urb.origen.pointO.push({location:point});
								}else{
									var point = new google.maps.LatLng(parseFloat(home_delivery_urb.origen.lat),parseFloat(home_delivery_urb.origen.lng));
									home_delivery_urb.origen.pointO.push({location:point});
								}
								Ext.getCmp(home_delivery_urb.id+'-grid-agencias-longitud').getStore().each(function(record){
									if(home_delivery_urb.transferencia){
				            			home_delivery_urb.setMarker({dir_px:record.get('dir_px'),dir_py:record.get('dir_py'),shi_logo:record.get('shi_logo'),agencia:record.get('agencia'),tipo_marker:'A',id_marker:record.get('id_agencia')});
				            		}else{
				            			if(record.get('active')){
				            				var point = new google.maps.LatLng(parseFloat(record.get('dir_px')),parseFloat(record.get('dir_py')));
											home_delivery_urb.origen.pointO.push({location:point});
				            			}
				            			home_delivery_urb.setMarker({dir_px:record.get('dir_px'),dir_py:record.get('dir_py'),shi_logo:'home_delivery.png',agencia:record.get('agencia'),tipo_marker:'A',id_marker:record.get('id_agencia')});
				            		}
								});
				            	var grid =Ext.getCmp(home_delivery_urb.id+'-unidades-asignacion-ruta');
								if (grid.getStore().getCount()>0){
									if(home_delivery_urb.transferencia){
										grid.getStore().each(function(record){
											home_delivery_urb.setMarker({dir_px:record.get('pos_px'),dir_py:record.get('pos_py'),shi_logo:'moto32.png',agencia:record.get('placa'),tipo_marker:'M',id_marker:record.get('id_unidad')});
										});
									}else{
										Ext.getCmp(home_delivery_urb.id+'-unidades-asignacion-ruta').getStore().each(function(record){
											if(parseInt(home_delivery_urb.unidad.record.get('id_unidad'))==parseInt(record.get('id_unidad'))){
												home_delivery_urb.setMarker({dir_px:record.get('pos_px'),dir_py:record.get('pos_py'),shi_logo:'moto32.png',agencia:record.get('placa'),tipo_marker:'M',id_marker:record.get('id_unidad')});
												return;
											}
										});
									}
								}
							break;
							default:
								Ext.each(home_delivery_urb.recordDelivery,function(obj,index){
									switch(obj.tipo_parada){
										case 'M':
			                                home_delivery_urb.unidad.lat=parseFloat(obj.pos_px);
			                                home_delivery_urb.unidad.lng=parseFloat(obj.pos_py);
			                                home_delivery_urb.setMarker({dir_px:obj.pos_px,dir_py:obj.pos_py,shi_logo:'moto32.png',agencia:obj.descripcion,tipo_marker:'S',id_marker:0});
										break;
										case 'R':
											home_delivery_urb.origen.lat=parseFloat(obj.pos_px);
			                                home_delivery_urb.origen.lng=parseFloat(obj.pos_py);
			                                var point = new google.maps.LatLng(parseFloat(obj.pos_px),parseFloat(obj.pos_py));
											home_delivery_urb.origen.pointO.push({location:point});
											home_delivery_urb.setMarker({dir_px:obj.pos_px,dir_py:obj.pos_py,shi_logo:obj.shi_logo,agencia:obj.descripcion,tipo_marker:'AA',id_marker:0});
										break;
										case 'E':
											home_delivery_urb.destino.id_geo=obj.id_geo;
											home_delivery_urb.destino.lat=parseFloat(obj.pos_px);
											home_delivery_urb.destino.lng=parseFloat(obj.pos_py);
										break;
									}
								});
							break;
						}
					break;
				}
				//point
				var pointU = new google.maps.LatLng(parseFloat(home_delivery_urb.unidad.lat),parseFloat(home_delivery_urb.unidad.lng));
		        var pointD = new google.maps.LatLng(parseFloat(home_delivery_urb.destino.lat),parseFloat(home_delivery_urb.destino.lng));
		        
		        home_delivery_urb.setMarker({dir_px:home_delivery_urb.destino.lat,dir_py:home_delivery_urb.destino.lng,shi_logo:'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%C2%B0|0BBCDC|000001',agencia:'',tipo_marker:'P',id_marker:0});

		        var request = {
		            origin:pointU,
		            destination:pointD,
		            waypoints:home_delivery_urb.origen.pointO,//[{location: pointO}],//{location: agencia},{location: '-13.918217, -74.330162'}  
		            optimizeWaypoints: true,
		            travelMode: google.maps.TravelMode.DRIVING,
		            provideRouteAlternatives: true
		        };
		        home_delivery_urb.varsmapa.directionsDisplay.setMap(home_delivery_urb.map);

		        home_delivery_urb.varsmapa.directionsService.route(request,function(response,status){
		            if (status == google.maps.DirectionsStatus.OK){
		                home_delivery_urb.varsmapa.directionsDisplay.setDirections(response);
		            }else{
		                global.Msg({
							msg:'No Existe Ruta',
							icon:2,
							buttosn:1,
							fn:function(btn){
							}
						});
		                return;
		            }
		        });
		        google.maps.event.addListener(home_delivery_urb.varsmapa.directionsDisplay, 'directions_changed', function() {
				    home_delivery_urb.computeTotalDistance(home_delivery_urb.varsmapa.directionsDisplay.getDirections());
				});
		    },
		    computeTotalDistance:function(result){
			  var total = 0;
			  var time = 0;
			  var myroute = result.routes[0];
			  for (var i = 0; i < myroute.legs.length; i++) {
			    total += myroute.legs[i].distance.value;
			    time+= myroute.legs[i].duration.value;
			  }
			  total = total / 1000.0;
			  document.getElementById('clskm').innerHTML = total + ' km';
			  document.getElementById('clsdur').innerHTML = home_delivery_urb.sethour(time);
			},
			sethour:function(time){
				var hours = Math.floor( time / 3600 );
				var minutes = Math.floor( (time % 3600) / 60 );
				var seconds = time % 60;
				hours = hours < 10 ? '0' + hours : hours;
				minutes = minutes < 10 ? '0' + minutes : minutes;
				seconds = seconds < 10 ? '0' + seconds : seconds;
				return hours + ":" + minutes + ":" + seconds;
			},
		    getRutaExe:function(record){
		    	if(parseInt(record.get('id_man'))!=0){
					Ext.getCmp(home_delivery_urb.id+'-ruta').getStore().load({
						params:{vp_prov_codigo:record.get('prov_codigo'),vp_man_id:record.get('id_man'),vp_estado:'P'},
						callback:function(){
							//home_delivery_urb.google_ruta();
						}
					});
				}else{
					home_delivery_urb.setMap();
					Ext.getCmp(home_delivery_urb.id+'-ruta').getStore().removeAll();
				}
		    },
		    setSaveAddress:function(){

		    	var record=home_delivery_urb.destino.record;
		        var v = Ext.getCmp(home_delivery_urb.id+'-destino').getValues();

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
					        home_delivery_urb.destino.id_geo=v[0].id_puerta;
					        home_delivery_urb.destino.lat=v[0].coordenadas[0].lat;
					        home_delivery_urb.destino.lng=v[0].coordenadas[0].lon;
					        Ext.Ajax.request({
			                    url:home_delivery_urb.url+'scm_scm_home_delivery_upd_destino/',
			                    params:{
			                        vp_gui_num:record.get('guia'),
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
			                                	Ext.getCmp(home_delivery_urb.id+'-tab-origen').setDisabled(false);
			                                	Ext.getCmp(home_delivery_urb.id+'tab-content').setActiveTab(1);
			                                	home_delivery_urb.setResetValues('D');
			                                    home_delivery_urb.getAgenciaGrid();
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
		    save_unidad:function(){
				//var val_des = Ext.getCmp(home_delivery_urb.id+'-destino').valida_coordenada();
				var vp_per_id=Ext.getCmp(home_delivery_urb.id+'-per_id').getValue();
				var vp_id_age = home_delivery_urb.origen.record.get('id_agencia');
				var vp_id_unidad = home_delivery_urb.unidad.record.get('id_unidad');
				var vp_msn=Ext.getCmp(home_delivery_urb.id+'-chk-msn').getValue();
				vp_msn=(vp_msn)?1:0;
				if (vp_id_age==0 || vp_id_age=='' || vp_id_age== null){
					global.Msg({
						msg:'Selecciona una Agencia',
						icon:2,
						buttosn:1,
						fn:function(btn){
						}
					});
					return;
				}
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

				if(!home_delivery_urb.transferencia){
					var grid = Ext.getCmp(home_delivery_urb.id+'-grid-agencias-longitud');
					var store = grid.getStore();
					var cc = store.getCount();
					var json = [];
					var cc_sel = 0;
					for(var i = 0; i < cc; ++i){
						var rec = store.getAt(i);
						if (rec.get('active')){
							++cc_sel;
							json.push({vp_id_agencia: rec.get('id_agencia')});
						}
					}
					if(cc_sel==0){
						global.Msg({
							msg:'Seleccione una sub Agencia de recoleccón',
							icon:2,
							buttosn:1,
							fn:function(btn){
							}
						});
						return;
					}
				}
				
				global.Msg({
	                msg: '¿Seguro de Asignar a Unidad?',
	                icon: 3,
	                buttons: 3,
	                fn: function(btn){
	                    if (btn == 'yes'){
							Ext.Ajax.request({
								url:home_delivery_urb.url+'scm_scm_home_delivery_add_ruta/',
								params:
								{
									vp_gui_num:home_delivery_urb.destino.record.get('guia'),
									vp_srec_id:(home_delivery_urb.transferencia)?home_delivery_urb.origen.id_rec_gui:0,
									vp_id_age:vp_id_age,
									vp_provin:home_delivery_urb.destino.record.get('prov_codigo'),
									vp_man_id:(!home_delivery_urb.transferencia)?home_delivery_urb.origen.id_manifiesto:home_delivery_urb.unidad.record.get('id_man'),
									vp_per_m:vp_per_id,
									vp_und_id:home_delivery_urb.unidad.record.get('id_unidad'),
									vp_msn:vp_msn,
									vp_transferencia:(!home_delivery_urb.transferencia)?1:0,
									vp_json:Ext.JSON.encode(json)
								},
								success:function(response,options){
									var res = Ext.decode(response.responseText);
									if (parseInt(res.data[0].error_sql)==1){
										if(home_delivery_urb.transferencia){
											global.Msg({
								                msg: res.data[0].error_info+',¿Tiene transferencia entre Agencias?',
								                icon: 3,
								                buttons: 3,
								                fn: function(btn){
								                    if (btn == 'yes'){
								                    	Ext.getCmp(home_delivery_urb.id+'-grid-agencias-longitud').columns[0].setVisible(true);
								                    	if(home_delivery_urb.origen.id_age==0){
								                    		home_delivery_urb.origen.id_age = vp_id_age;
								                    		home_delivery_urb.transferencia=false;
								                    		home_delivery_urb.origen.id_manifiesto=res.data[0].id_manifiesto;
								                    		home_delivery_urb.origen.saveRercord=home_delivery_urb.origen.record;
								                    	}
								                    	Ext.getCmp(home_delivery_urb.id+'-panel-unidades').setDisabled(true);
														home_delivery_urb.getAgenciaGrid();
													}else{
														home_delivery_urb.setClearPanelModule();
														home_delivery_urb.setResetValues('T');
														Ext.getCmp(home_delivery_urb.id+'-region-west').hide();
														Ext.getCmp(home_delivery_urb.id+'-region-south').show();
														home_delivery_urb.getReloadComponent();
														Ext.getCmp(home_delivery_urb.id+'tab-content').setActiveTab(0);
													}
								                }
								            });
										}else{
											global.Msg({
												msg:res.data[0].error_info,
												icon:3,
												buttosn:1,
												fn:function(btn){
													home_delivery_urb.setClearPanelModule();
													home_delivery_urb.setResetValues('T');
													Ext.getCmp(home_delivery_urb.id+'-region-west').hide();
													Ext.getCmp(home_delivery_urb.id+'-region-south').show();
													home_delivery_urb.getReloadComponent();
													Ext.getCmp(home_delivery_urb.id+'tab-content').setActiveTab(0);
												}
											});
										}
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
			setResetValues:function(tipo){
				if(tipo=='T'){
					home_delivery_urb.fecha_ss='';
					home_delivery_urb.prov_codigo=0;
					home_delivery_urb.id_shipper=0;
					home_delivery_urb.destino={
						record:{},
						id_geo:0,
						lat:-11.782413062516948,
						lng:-76.79493715625
					};
				}
				home_delivery_urb.id_agencia=0;
				home_delivery_urb.transferencia=true;
				home_delivery_urb.origen={
					saveRercord:{},
					id_manifiesto:0,
					pointO:[],
					record:{},
					id_rec_gui:0,
					id_age:0,
					lat:0,
					lng:0
				};
				home_delivery_urb.unidad={
					record:{},
					placa:'',
					lat:0,
					lng:0
				};
				home_delivery_urb.PositionReload={
					tipo_marker:'',
					id_marker:0
				};
				try{
					Ext.getCmp(home_delivery_urb.id+'-grid-agencias-longitud').columns[0].setVisible(false);
					Ext.getCmp(home_delivery_urb.id+'-panel-unidades').setDisabled(false);
					Ext.getCmp(home_delivery_urb.id+'-unidades-asignacion-ruta').getStore().removeAll();
					Ext.getCmp(home_delivery_urb.id+'-grid-agencias-longitud').getStore().removeAll();
					Ext.getCmp(home_delivery_urb.id+'-chk-msn').setValue(false);
				}catch(err){
            		console.log(err);
            	}
			},
			newServices:function(){
				var id_agencia = Ext.getCmp(home_delivery_urb.id+'-agencia').getValue();
				Ext.Ajax.request({
                    url: home_delivery_urb.url+'scm_home_delivery_pendientes/',
                    params:{vp_agencia:id_agencia},
                    success: function(response, options){
                        var res = Ext.JSON.decode(response.responseText);
                        res = res.data[0];
                        if(parseInt(res.pendientes)!=0){
	                        var pendientes=0;
	                        var estado = Ext.getCmp(home_delivery_urb.id+'-stado').getValue();
							if(home_delivery_urb.module.tipo=='B' && estado=='SS'){
					        	var grid = Ext.getCmp(home_delivery_urb.id+'-por-georeferenciar');
					        	pendientes = parseInt(grid.getStore().getCount());
					        }
	                    	pendientes =(parseInt(res.pendientes)>=pendientes)?(parseInt(res.pendientes)-pendientes):(pendientes - parseInt(res.pendientes));
	                        if(pendientes!=0 && parseInt(res.gui_numero)!=home_delivery_urb.tiempo.gui_numero){
	                        	Ext.getCmp(home_delivery_urb.id+'btn-b').setDisabled(false);
	                        	Ext.getCmp(home_delivery_urb.id+'btn-b').setStyle('background','#E43131');
	                        	Ext.getCmp(home_delivery_urb.id+'btn-b').setStyle('height','62px');
	                        	//Ext.getCmp(home_delivery_urb.id+'btn-b').setStyle('padding-right','10px');
	                        	Ext.getCmp(home_delivery_urb.id+'btn-b').setHeight(62);
	                        	//Ext.getCmp(home_delivery_urb.id+'btn-b').setText('<div style="font-size:20px;font-weight: bold;color:#fff;width:40px;height:60px"><div style="width:32px;height:13px;padding-right:7px;">S</div><div style="font-size:12px;font-weight:bold;color:#fff;height:8px;width:10px;padding-top:1px;padding-left:6px;padding-right:8px;">'+pendientes+'</div><div style="font-size:9px;font-weight:bold;color:#fff;height:5px;width:40px;padding-top:2px;padding-right:12px;">Nuevas</div></div>');
	                        	Ext.getCmp(home_delivery_urb.id+'btn-b').setText('<table style="padding-right:7px;""><tr><td style="font-size:20px;font-weight: bold;color:#fff;">S</td></tr><tr><td style="font-size:12px;font-weight:bold;color:#fff;">'+pendientes+'</td></tr><tr><td style="font-size:9px;font-weight:bold;color:#fff;">Nuevas</td></tr></table>');
	                        	home_delivery_urb.tiempo.gui_numero = parseInt(res.gui_numero);
	                        }
                        }
                    }
                });
			},
			get_count_sel: function(){
				var grid = Ext.getCmp(home_delivery_urb.id+'-grid-agencias-longitud');
				var store = grid.getStore();
				var cc = store.getCount();
				var tot_sel = 0;
				for(var i = 0; i < cc; ++i){
					var rec = store.getAt(i);
					if (rec.get('active')){
						++tot_sel;
					}
				}
				return tot_sel;
			}
		}
		Ext.onReady(home_delivery_urb.init,home_delivery_urb);
	}else{
		tab.setActiveTab(home_delivery_urb.id+'-tab');
	}
</script>