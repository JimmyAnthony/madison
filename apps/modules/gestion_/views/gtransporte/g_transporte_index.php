<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if (!Ext.getCmp('g_transporte-tab')){
		var g_transporte = {
			id:'g_transporte',
			id_menu:'<?php echo $p["id_menu"];?>',
			url:'/gestion/gtransporte/',
			arrayMaker:[],
			id_unidad:0,
			tplInciPanel:new Ext.XTemplate(
					'<tpl for=".">',
						'<div class="gt-tpl-box">',
							'<div class="gt-tpl-1">Secuencia Ejecución</div>',
							'<div class="gt-tpl-2"> {cnt} </div>',
						'</div>',
					'</tpl>'
			),
			varsmapa:{
				directionsDisplay: new google.maps.DirectionsRenderer(),
				directionsService : new google.maps.DirectionsService(),
				trafficLayer:new google.maps.TrafficLayer(),
				marker:new google.maps.Marker(),
				lat:-11.782413062516948,
				lon:-76.79493715625,
				icon:'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%C2%B0|0BBCDC|000001'
			},
			select:{
				tipo:0,
				gui_srec_id:0,
				ciu_id:0,
				chk:'',
				id_shipper:0,
				id_direccion:0
			},
			map:'',
			save:{
				vp_man_id:0,
				vp_srec_id:0,
				vp_per_id:0,
				vp_und_id:0
			},
			runner: new Ext.util.TaskRunner(),
			init:function(){	

				Ext.tip.QuickTipManager.init();

                g_transporte.task = g_transporte.runner.newTask({
                    run: function(){
                        g_transporte.newServices();
                    },
                    interval: (10000 * 2)
                });

                g_transporte.task.start();

                var store_vista3 = Ext.create('Ext.data.Store',{
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
                		url:g_transporte.url+'scm_scm_home_delivery_panel/',
                		reader:{
                			type:'json',
                			root:'data'
                		}
                	}
                });
				var store_ruta = Ext.create('Ext.data.Store',{
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
						url:g_transporte.url+'scm_scm_home_delivery_lista_ruta/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				var store = Ext.create('Ext.data.Store',{
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
						url:g_transporte.url+'scm_scm_home_delivery_unidades/',
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
				var store2 = Ext.create('Ext.data.Store',{
					model:'grid_motos',
					proxy:{
						type:'ajax',
						url:g_transporte.url+'scm_scm_home_delivery_unidad_gps_distance/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				var porgeoreferenciar = Ext.create('Ext.data.Store',{
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
					],
					proxy:{
						type:'ajax',
						url:g_transporte.url+'scm_scm_home_delivery_servicios/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});

				var store_grid_agencias = Ext.create('Ext.data.Store',{
					model:'grid_agencias',
					proxy:{
						type:'ajax',
						url:g_transporte.url+'/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				var south = Ext.create('Ext.form.Panel',{
					id:g_transporte.id+'-south',
					layout:'column',
					scrollable:true,
					border:false,
					defaults:{
						border:false
					},
					items:[
							{
								xtype:'panel',
								columnWidth:1,
								layout:'fit',
								id:g_transporte.id+'-panel-porgeo',
								items:[
										{
											xtype:'grid',
											id:g_transporte.id+'-por-georeferenciar',
											store:porgeoreferenciar,
											columnLines:true,
											columns:{
												items:[
														{
															text:'Menu',
															dataIndex:'',
															align:'center',
															width:50,
															renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
																metaData.style = "padding: 0px; margin: 0px";
																return global.permisos({
																	type:'link',
																	id_menu: g_transporte.id_menu,
																	icons:[
						                                                {id_serv: 0, img: 'close.png', qtip: 'Click para Anular registro', js: ''},
						                                                {id_serv: 0, img: 'ver.png', qtip: 'Click para Ver Detalle', js: ''}
						                                            ]
																});

															}
														},
														{
															text:'Origen',
															dataIndex:'origen',
															flex:2
														},
														{
															text:'Zona',
															dataIndex:'destino_zona',
															flex:1
														},
														{
															text:'Dirección',
															dataIndex:'destino_dir',
															flex:2
														},
														{
															text:'Nombre',
															dataIndex:'cliente',
															flex:2
														},
														{
															text:'Hora Solicitud',
															dataIndex:'hora_ss',
															width:50
														},
														{
															text:'Estado',
															dataIndex:'estado',
															flex:1
														},
														{
															text:'Hora Chk',
															dataIndex:'hora_chk',
															width:50
														},
														{
															text:'Ultimo',
															dataIndex:'time_last',
															width:50
														},
														{
															text:'Duración',//'Transcurrido',
															dataIndex:'time_delay',
															width:60
														},
														{
															text:'Unidad',
															dataIndex:'placa_unidad',
															width:50
														}
												]
											},
											listeners:{
												beforeselect:function(obj, record, index, eOpts ){
													//g_transporte.get_georeferencias(record);	
													if (record.get('chk')=='SS'){
														g_transporte.get_georeferencias(record);	
													}else{
														g_transporte.origen_destino(record);
													}

												}
											}
										},
								]
							},
							{
								xtype:'panel',
								columnWidth:1,
								border:false,
								layout:'fit',
								id:g_transporte.id+'-secuencia-ruta',
								items:[
										{
											xtype:'grid',
											id:g_transporte.id+'-ruta',
											store:store_ruta,
											columnLines:true,
											multiSelect: true,
											border:false,
											//height:150,
											features: [{
												groupHeaderTpl: 'Cliente:{[ values.rows[0].data["cliente"] ]}',
									            ftype: 'grouping'
									        }],
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
																	id_menu: g_transporte.id_menu,
																	icons:[
						                                                {id_serv: 0, img: 'gears3.png', qtip: 'Click para Procesar Registro', js: ''},
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
									        			g_transporte.findMaker(parseInt(data.records[0].data.guia));
									        		}
									            }
									        },
									        listeners:{
									        	beforeselect:function(obj, record, index, eOpts ){
									        		var guia = parseInt(record.get('guia'));
									        		g_transporte.findMaker(guia);
									        		//console.log(guia);
									        	},
									        	cellclick:function( obj, td, cellIndex, record, tr, rowIndex, e, eOpts ){
									        	},
									        	headerclick:function( ct, column, e, t, eOpts ){
									        		if (column.componentLayout.owner.dataIndex=='id_doc'){
									        			g_transporte.order_ruta();
									        		}
									        	}
									        	
									        	
									        }
										}
								]
							}
					],
					listeners:{
						boxready:function(){

						}
					}
				});
				var west = Ext.create('Ext.form.Panel',{
					id:g_transporte.id+'-west',
					border:false,
					layout:'fit',
					height:'100%',
					defaults:{
						border:false
					},
					items:[
							{
								xtype:'grid',
								id:g_transporte.id+'-recol-ruta',
								columnWidth:1,
								border:false,
								height:'100%',
								store:store,
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
												width:80,
												//flex:1
											},
									]
								},
								listeners:{
									beforeselect:function(obj, record, index, eOpts ){
										g_transporte.id_unidad = record.get('id_unidad');
										Ext.getCmp(g_transporte.id+'center-south').setVisible(true);
										Ext.getCmp(g_transporte.id+'-panel-porgeo').setVisible(false);
										Ext.getCmp(g_transporte.id+'-secuencia-ruta').setVisible(true);

										var agencias = Ext.getCmp(g_transporte.id+'-agencia').getValue();
										Ext.getCmp(g_transporte.id+'-ruta').getStore().load({
											params:{vp_prov_cod:agencias,vp_man_id:record.get('id_man'),vp_estado:'P'},
											callback:function(){
												g_transporte.select_ruta();
											}
										});
									},

								},
								viewConfig: {
		            				getRowClass: function(record, rowIndex, rowParams, store){
		            				}
		            			},
							},
							{
								xtype:'grid',
								id:g_transporte.id+'-grid-vista3',
								columnWidth:1,
								border:true,
								//height:800,
								store:store_vista3,
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
												text:'',
												dataIndex:'',
												align:'center',
												width:60
											},
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
											}
									]
								},
								listeners:{
									afterrender:function(){
									}
								}
							}

							

					]
				});
				
				var panel = Ext.create('Ext.form.Panel',{
					id:g_transporte.id+'form',
					border:false,
					layout:'border',
					defaults:{
						border:false
					},
					tbar:[
							{
								text:'',
								id:g_transporte.id+'-btn-1',
								icon:'/images/icon/novedad_0001.png',
								listeners:{
									click:function(obj,e){
										g_transporte.toggleButton(0);
									}
								}
							},
							{
								text:'',
								id:g_transporte.id+'-btn-2',
								icon:'/images/icon/iniciado.png',
								listeners:{
									click:function(obj,e){
										g_transporte.toggleButton(1);
									}
								}
							},
							{
								text:'',
								id:g_transporte.id+'-btn-3',
								icon:'/images/icon/page_find.png',
								listeners:{
									click:function(obj,e){
										g_transporte.toggleButton(2);
									}
								}
							},
							{xtype:'tbspacer',width:30},
							{
								xtype:'combo',
								id:g_transporte.id+'-agencia',
								fieldLabel:'Agencia',
								labelWidth:50,
								store:Ext.create('Ext.data.Store',{
								fields:[
										{name:'prov_codigo', type:'int'},
										{name:'prov_nombre', type:'string'},
								],
								proxy:{
									type:'ajax',
									url:g_transporte.url+'get_usr_sis_provincias/',
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
									minWidth:350
								},
								width:150,
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
									}
								}
							},'-',
							{
								xtype:'datefield',
								id:g_transporte.id+'-fecha',
								fieldLabel:'Fecha',
								labelWidth:35,
								//labelAlign:'top',
								width:150,
								value:new Date()
							},{xtype:'tbspacer',width:10},
							{
								xtype:'checkbox',
								id:g_transporte.id+'chk_hasta',
								listeners:{
									change:function( obj, newValue, oldValue, eOpts ){
										if(newValue){
											Ext.getCmp(g_transporte.id+'chk-solo-ruta').setValue(true);
											Ext.getCmp(g_transporte.id+'-hasta').setReadOnly(false);
											Ext.getCmp(g_transporte.id+'-hasta').setValue(new Date());
											Ext.getCmp(g_transporte.id+'chk-solo-ruta').setReadOnly(true);
										}else{
											Ext.getCmp(g_transporte.id+'chk-solo-ruta').setValue(false);
											Ext.getCmp(g_transporte.id+'-hasta').setReadOnly(true);
											Ext.getCmp(g_transporte.id+'-hasta').setValue('');
											Ext.getCmp(g_transporte.id+'chk-solo-ruta').setReadOnly(false);
										}
									}
								}
							},
							{
								xtype:'datefield',
								id:g_transporte.id+'-hasta',
								readOnly:true,
								fieldLabel:'Hasta',
								labelWidth:35,
								width:150
							},
							{
								text:'',
								id:g_transporte.id+'-btn3',
								icon: '/images/icon/search.png',
								tooltip:'Buscar',
								listeners:{
									click:function(obj,opts){
										g_transporte.buscar_vista3();
										console.log('btn3');
									}
								}
							},
							{
								xtype:'checkbox',
								labelAlign:'right',
								fieldLabel:'Solo En Ruta',
								id:g_transporte.id+'chk-solo-ruta',
								listeners:{
									change:function( obj, newValue, oldValue, eOpts ){

									}
								}
							},
							{
								text:'',
								id:g_transporte.id+'btn1',
								iconAlign:'bottom',
								icon: '/images/icon/search.png',
								tooltip:'Buscar',
								listeners:{
									click:function(obj,opts){
										g_transporte.buscar();
										//console.log('btn1');
									}
								}
							},
							{
								xtype:'combo',
								fieldLabel:'Estado',
								labelWidth:50,
								id:g_transporte.id+'-stado',
								hidden:true,
								store:Ext.create('Ext.data.Store',{
									fields:[
										{name: 'chk', type: 'string'},
										{name: 'descri', type: 'string'}
									],
									proxy:{
										type:'ajax',
										url:g_transporte.url+'get_estados/',
										reader:{
											type:'json',
											root:'data'
										}
									}
								}),
								queryMode: 'local',
								triggerAction: 'all',
								valueField:'chk',
								displayField:'descri',
								emptyText: '[seleccione]',
								listeners:{
									afterrender:function(obj,e){
										obj.getStore().load({
											params:{}
										})
									}
								}
							},
							{
								text:'',
								id:g_transporte.id+'-btn2',
								//hidden:true,
								iconAlign:'bottom',
								icon: '/images/icon/search.png',
								tooltip:'Buscar',
								listeners:{
									click:function(obj,opts){
										g_transporte.por_georeferenciar();
										//console.log('btn2');

									}
								}
							},
							{xtype:'tbspacer',width:30},
							'->',
							'-',
							{
								text:'',
								id:g_transporte.id+'-btn_alert_',
								hidden:true,
								//iconAlign:'bottom',
								icon: '/images/icon/alert_red_min.ico',
								tooltip:'Pendientes',
								listeners:{
									click:function(obj,opts){
										g_transporte.buscar_pendientes();
									}
								}
							},
							'-',
							{xtype:'tbspacer',width:30}
					],
					items:[
							{
								region:'west',
								layout:'border',
								id:g_transporte.id+'west',
								header:false,
								split: true,
								collapsible: true,
								hideCollapseTool:true,
								titleCollapse:false,
								floatable: false,
								border:false,
								width:550,
								collapseMode : 'mini',
								animCollapse : true,
								cls:'.x-accordion-hd',
								items:[
										{
											region:'center',
											layout:'fit',
											border:false,
											items:[west]
										}
								]
							},
							{
								region:'center',
								layout:'border',
								border:false,
								items:[
										{
											region:'west',
											id:g_transporte.id+'-center-west',
											layout:'fit',
											width:400,
											collapsible:true,
											hideCollapseTool:true,
											titleCollapse:false,
											header:false,
											collapsed:false,
											items:[

													{
														xtype:'panel',
														columnWidth:1,
														cls:'x-accordion-hd',
														
														width: 400,
        												defaults: {
												        },
														layout:{
															type: 'accordion',
												            titleCollapse: false,
												            animate: true,
												            //activeOnTop: true
														},
														items:[
															{
																title:'Destino',
																id:g_transporte.id+'-acoordion-destino',
																layout:'fit',
																//height:'100%',
																items:[
																	{
																		xtype:'searchdirection',
																		id:g_transporte.id+'-destino',
																		setMapping:g_transporte.id+'-map',
																		//apimapa:g_transporte.id+'-map',
																		tipo:'D'
																	}
																]
															},
															{
																title:'Origen',
																id:g_transporte.id+'-acoordion-origen',
																layout:'fit',
																//height:'100%',
																items:[
																		{
																			xtype:'searchdirection',
																			id:g_transporte.id+'-origen',
																			setMapping:g_transporte.id+'-map',
																			//apimapa:g_transporte.id+'-map',
																			tipo:'O'
																		}
																],
																listeners:{
																	expand:function(obj,eOpts){
																		Ext.getCmp(g_transporte.id+'-origen').get_agenciShipper();
																		g_transporte.unidad_actual();
																	}
																}		
															},
															{
																title:'Asignacion de Ruta',
																id:g_transporte.id+'-acoordion-ruta',
																bbar:[
																		{
																			text:'Asignar Unidad',
																			icon:'/images/icon/save.png',
																			listeners:{
																				click:function(){
																					g_transporte.save_unidad();
																				}
																			}
																		},
																		{
																			
																			text:'Cancelar',
																			icon: '/images/icon/close.png',
																			listeners:{
																				click:function(){
																					Ext.getCmp(g_transporte.id+'-center-west').setVisible(false);
																					Ext.getCmp(g_transporte.id+'west').setVisible(true);
																				}
																			}
																		},'-',
																		{
																			xtype:'checkbox',
																			fieldLabel:'Notificar por SMS',
																			labelWidth:100,
																			listeners:{
																				change:function( obj, newValue, oldValue, eOpts ){
																					if (newValue){
																						// g_transporte.varsmapa.trafficLayer.setMap(g_transporte.map);
																					}else{
																						// g_transporte.varsmapa.trafficLayer.setMap(null);
																					}
																					
																				}
																			}

																		}
																],
																layout:'fit',
																border:false,
																items:[
																		{
																			xtype:'panel',
																			layout:'border',
																			//cls:'x-accordion-hd',
																			border:false,
																			items:[
																					{
																						xtype:'panel',
																						region:'north',
																						id:g_transporte.id+'-collapsed-unidad-ruta',
																						height:'50%',
																						title:'Unidades',
																						//width:400,
																						layout:'fit',
																						collapsible:true,
																						hideCollapseTool:true,
																						titleCollapse:false,
																						header:false,
																						collapsed:false,
																						split:true,
																						items:[
																								{
																									xtype:'grid',
																									id:g_transporte.id+'-unidades-asignacion-ruta',
																									store:store2,
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
																													flex:0.3
																												}
																										]
																									},
																									listeners:{
																										beforeselect:function(obj, record, index, eOpts ){
																											g_transporte.confirma_unidad(record);
																										}
																									},
																									viewConfig: {
																			            				getRowClass: function(record, rowIndex, rowParams, store){	
																			            					
																			            				}
																			            			},
																								}
																						],
																					},
																					{
																						region:'center',
																						xtype:'form',
																						id:g_transporte.id+'save-unidad',
																						layout:'fit',
																						border:false,
																						bodyStyle: 'padding:10px 10px 10px 10px;',
																						defaults:{
																							padding:'5 5 5 5'
																						},
																						items:[
																								{
																									xtype:'fieldset',
																									layout:'column',
																									defaults:{
																										padding:'5 5 5 5'
																									},
																									items:[
																											{
																									  			xtype:'textfield',
																									  			id:g_transporte.id+'-unidad',
																									  			fieldLabel:'Unidad:',
																									  			readOnly:true,
																									  			labelWidth:50,
																									  			columnWidth:0.5,
																									  			allowBlank:false,
																									  		},
																									  		{
																												xtype:'datefield',
																												id:g_transporte.id+'-u-fecha',
																												allowBlank:false,
																												fieldLabel:'Fecha',
																												labelWidth:50,
																												columnWidth:0.5,
																												value:new Date(),
																												readOnly:true,
																											},
																											{
																												xtype:'combo',
																												id:g_transporte.id+'-per_id',
																												allowBlank:false,
																												columnWidth:1,
																												fieldLabel:'Chofer/Motorizado',
																												store:Ext.create('Ext.data.Store',{
																													fields:[
																														{name:'completo', type:'string'},
																														{name:'per_id' , type:'int'}
																													],
																													proxy:{
																														type:'ajax',
																														url:g_transporte.url+'scm_usr_sis_personal/',
																														reader:{
																															type:'json',
																															rootProperty:'data'
																														}
																													}
																												}),
																												queryMode:'local',
																												valueField:'per_id',
																												displayField:'completo',
																												listConfig:{
																													minWidth:140
																												},
																												width:140,
																												forceSelection:true,
																												allowBlank:false,
																												empyText:'[ Seleccione]',
																												listeners:{
																													afterrender:function(obj){
																														obj.getStore().load({
																															params:{vp_cargo:22},
																															callback:function(){
																															}
																														});
																													}
																												}
																											},
																											{
																												xtype:'label',
																												id:g_transporte.id+'siguiente-ejecu',
																												//text:'Secuencia Ejecución:',
																												columnWidth:1,
																												items:[
																														{
																															beforerender:function(obj,eOpts){
																																
																															}
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
																listeners:{
																	expand:function( obj, eOpts ){
																		g_transporte.asignacion_ruta_buscar();
																		var objeto = Ext.get(g_transporte.id+'siguiente-ejecu');
																		g_transporte.tplInciPanel.overwrite(objeto,{cnt:0});
																	},
																	afterrender:function( obj, eOpts ){
																		g_transporte.unidad_actual();
																	
																	}
																	
																}
															}

														]
													}
													
											]

										},
										{
											region:'center',
											layout:'fit',
											id:g_transporte.id+'cont_map',
											html:'<div id="'+g_transporte.id+'-map" class="ue-map-canvas"></div>'
										},
										{
											region:'south',
											id:g_transporte.id+'center-south',
											layout:'fit',
											header:false,
											border:false,
											split:true,
											collapsible:true,
											hideCollapseTool:true,
											titleCollapse:false,
											border:false,
											height:150,
											collapseMode : 'mini',
											animCollapse : true,
											//floatable:false,
											items:[
													south
											]
										}
								]

							}
					]
				});

				tab.add({
					id:g_transporte.id+'-tab',
					border:false,
					autoScroll:true,
					closable:true,
					layout:{
						type:'fit'
					},
					//autoDestroy: true,
					items:[
						panel
					],
					listeners:{
						beforerender: function(obj, opts){
	                        global.state_item_menu(g_transporte.id_menu, true);                 
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,g_transporte.id_menu);
	                        g_transporte.setMap();
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(g_transporte.id_menu, false);

	                    },
	                    boxready:function(obj, width, height, eOpts ){
	                    	//Ext.getCmp(g_transporte.id+'-center-west').setVisible(false);
	                    	g_transporte.toggleButton(0);
	                    	Ext.getCmp(g_transporte.id+'-secuencia-ruta').setVisible(false);
	                    	Ext.getCmp(g_transporte.id+'center-south').setVisible(false);
	                    }

					}
				}).show();
			},
			setMap:function(){
				var directionsService = new google.maps.DirectionsService();
		        
		        var rendererOptions = {
					  draggable: true,
					  suppressMarkers: true
				};
		        g_transporte.varsmapa.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
		        g_transporte.varsmapa.directionsService = new google.maps.DirectionsService();
		        var argentina = new google.maps.LatLng(-12.0473179,-77.0824867);
		        var mapOptions = {
		            zoom:12,
		            center: argentina,
		            //mapTypeId: google.maps.MapTypeId.ROADMAP
		        };
		        g_transporte.map = new google.maps.Map(document.getElementById(g_transporte.id+'-map'), mapOptions);
		        g_transporte.varsmapa.directionsDisplay.setMap(g_transporte.map);

		        var homeControlDiv = document.createElement('div');
		        var homeControl = new HomeControl(homeControlDiv, g_transporte.map, argentina);
		        homeControlDiv.index = 1;
		        g_transporte.map.controls[google.maps.ControlPosition.TOP_CENTER].push(homeControlDiv);

		        var hdiv = document.createElement('div');
		        var hcontro = new g_transporte.HHomeControl(hdiv,g_transporte.map);
		        hdiv.index = 1;
		        g_transporte.map.controls[google.maps.ControlPosition.TOP_LEFT].push(hdiv);

		        
			},
			HHomeControl:function(controlDiv, map) {
			  controlDiv.style.padding = '5px';

			  // Set CSS for the control border.
			  var controlUI = document.createElement('div');
			  controlUI.style.backgroundColor = 'gray';
			  controlUI.style.borderStyle = 'gray';
			  controlUI.style.borderWidth = '2px';
			  controlUI.style.borderColor = 'gray';
			  controlUI.style.cursor = 'pointer';
			  controlUI.style.textAlign = 'center';
			  controlUI.title = 'Click Para Mostrar el Trafico';
			  controlDiv.appendChild(controlUI);

			  // Set CSS for the control interior.
			  var controlText = document.createElement('div');
			  controlText.style.fontFamily = 'Arial,sans-serif';
			  controlText.style.fontSize = '12px';
			  controlText.style.paddingLeft = '4px';
			  controlText.style.paddingRight = '4px';
			  controlText.style.color = 'white';
			  controlText.innerHTML = '<input type="checkbox" id="trafic" name="trafic" onClick="g_transporte.trafic();"> <b>Ver Trafico</b>';
			  controlUI.appendChild(controlText);
			},
			trafic:function(){
				 if (document.getElementById("trafic").checked){
				 	 g_transporte.varsmapa.trafficLayer.setMap(g_transporte.map);
				 }else{
				 	g_transporte.varsmapa.trafficLayer.setMap(null);
				 }

			},
			buscar:function(){
				Ext.getCmp(g_transporte.id+'west').setCollapsed(false);
				Ext.getCmp(g_transporte.id+'center-south').setVisible(false);
				Ext.getCmp(g_transporte.id+'-secuencia-ruta').setVisible(false);
				Ext.getCmp(g_transporte.id+'-panel-porgeo').setVisible(true);
				g_transporte.setMap();
				var grid =Ext.getCmp(g_transporte.id+'-recol-ruta');
				var vp_agencia = Ext.getCmp(g_transporte.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(g_transporte.id+'-fecha').getRawValue();
				grid.getStore().load({
					params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha},
					callback:function(){
						g_transporte.polyline();
						g_transporte.unidad_actual();
					}
				});
			},
			buscar_vista3:function(){
				var grid = Ext.getCmp(g_transporte.id+'-grid-vista3');
				var vp_agencia = Ext.getCmp(g_transporte.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(g_transporte.id+'-fecha').getRawValue();
				var vp_fecha_fin = Ext.getCmp(g_transporte.id+'-hasta').getRawValue();
				var vp_estado = Ext.getCmp(g_transporte.id+'chk-solo-ruta').getValue() == true ? '1':'0';
				grid.getStore().load({
					params:{vp_prov_cod:vp_agencia,vp_fecha_inicio:vp_fecha,vp_fecha_fin:vp_fecha_fin,vp_estado:vp_estado},
					callback:function(){
					}
				});
			},
			buscar_pendientes:function(){
				g_transporte.toggleButton(1);
				g_transporte.setMap();
				var grid = Ext.getCmp(g_transporte.id+'-por-georeferenciar');
				var vp_agencia = Ext.getCmp(g_transporte.id+'-agencia').getValue();
				var vp_fecha = '<?php echo date("d/m/Y"); ?>';
				grid.getStore().load({
					params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha,vp_estado:"SS"},
					callback:function(){
						Ext.getCmp(g_transporte.id+'-btn_alert_').hide();
						Ext.getCmp(g_transporte.id+'-center-west').setVisible(false);
					}
				});
			},
			asignacion_ruta_buscar:function(){
				var grid =Ext.getCmp(g_transporte.id+'-unidades-asignacion-ruta');
				var vp_prov_codigo = Ext.getCmp(g_transporte.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(g_transporte.id+'-fecha').getRawValue();
				var value = Ext.getCmp(g_transporte.id+'-origen').getUnidad();
				grid.getStore().load({
					params:{vp_prov_codigo:vp_prov_codigo,vp_id_agencia:value.id_agencia},
					callback:function(){
						g_transporte.asigRutaSelect();
					}
				});
			},
			time_unidad:function(){
				var service = new google.maps.DistanceMatrixService();
				var grid =Ext.getCmp(g_transporte.id+'-unidades-asignacion-ruta');
				var vp_agencia = Ext.getCmp(g_transporte.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(g_transporte.id+'-fecha').getRawValue();
				var arrayData = [];
				var value = Ext.getCmp(g_transporte.id+'-origen').getUnidad();
				var destino = new google.maps.LatLng(value.age_x,value.age_y);
				Ext.Ajax.request({
					url:g_transporte.url+'scm_scm_home_delivery_unidad_gps/',
					params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha},
					success:function(response,options){
						var res = Ext.decode(response.responseText).data;
						Ext.each(res,function(obj,index){
							var origen = new google.maps.LatLng(obj.pos_px,obj.pos_py);
							var request = {
			                       origins: [origen],
			                       destinations: [destino],//es la agencia
			                       travelMode: google.maps.TravelMode.DRIVING,
			                       unitSystem: google.maps.UnitSystem.METRIC,
			                       avoidHighways: false,
			                       avoidTolls: false
			                }
			                global.sleep(150);

			                service.getDistanceMatrix(request, function(response, status){
			                	if (status == google.maps.DistanceMatrixStatus.OK){
			                		 if (response.rows[0].elements[0].status=='OK'){
			                		 	arrayData.push({
											id_unidad:obj.id_unidad,
											placa:obj.placa,
											id_man:obj.id_man,
											posicion:obj.posicion,
											pos_px:obj.pos_px,
											pos_py:obj.pos_py,
											tipo:obj.tipo,
											sentido:obj.sentido,
											id_per:obj.id_per,
											time:response.rows[0].elements[0].duration.text,
											distance:response.rows[0].elements[0].distance.text
										});
			                		 }else{
			                		 	arrayData.push({
											id_unidad:obj.id_unidad,
											placa:obj.placa,
											id_man:obj.id_man,
											posicion:obj.posicion,
											pos_px:obj.pos_px,
											pos_py:obj.pos_py,
											tipo:obj.tipo,
											sentido:obj.sentido,
											id_per:obj.id_per,
											time:'',
											distance:''
										});
			                		 }
			                	}
			                	arrayData.sort(function(a,b) { return parseInt(a.distance) - parseInt(b.distance)});
			                	grid.getStore().loadData(arrayData);
			                	grid.getView().refresh(); 
			                	//console.log(arrayData);
			                	if (grid.getStore().getCount()>0){
									for(var i = 0; i < grid.getStore().getCount(); ++i){
									   var rec = grid.getStore().getAt(i);
									   if (rec.get('id_unidad')== value.id_unidad ){
									   		grid.getSelectionModel().select(i, true);
									   }
									}
								}
			                });


						});
					}
				});
			},
			asigRutaSelect:function(){
				var grid =Ext.getCmp(g_transporte.id+'-unidades-asignacion-ruta');
				var value = Ext.getCmp(g_transporte.id+'-origen').getUnidad();
				if (grid.getStore().getCount()>0){
					for(var i = 0; i < grid.getStore().getCount(); ++i){
					   var rec = grid.getStore().getAt(i);
					   if (rec.get('id_unidad')== value.id_unidad ){
					   		grid.getSelectionModel().select(i, true);
					   }
					}
				}
			},
			unidad_actual:function(){
				var vp_agencia = Ext.getCmp(g_transporte.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(g_transporte.id+'-fecha').getRawValue();
				Ext.Ajax.request({
					url:g_transporte.url+'scm_scm_home_delivery_unidades/',
					params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha},
					success:function(response,options){
						var res = Ext.decode(response.responseText).data;
						Ext.each(res, function(obj,index){
							if (obj.pos_px != ''){
								Ext.getCmp(g_transporte.id+'-origen').pinta_unidad_actual(parseFloat(obj.pos_px),parseFloat(obj.pos_py),'/images/icon/moto32.png',obj.placa);		
							}
						});
					}
				});
			},
			confirma_unidad:function(record){
				g_transporte.waypoints(record);
				var siguiente = parseInt(record.get('posicion'));
				var placa = record.get('placa')
				var per_id = record.get('id_per');
				var vp_man_id = parseInt(record.get('id_man'));
				var vp_und_id = parseInt(record.get('id_unidad'));
				siguiente=siguiente+1;
				g_transporte.save.vp_man_id = vp_man_id;
				g_transporte.save.vp_per_id = per_id;
				g_transporte.save.vp_und_id = vp_und_id;
				var objeto = Ext.get(g_transporte.id+'siguiente-ejecu');
				g_transporte.tplInciPanel.overwrite(objeto,{cnt:siguiente});
				Ext.getCmp(g_transporte.id+'-unidad').setValue(placa);
				if (per_id > 0 ){
					Ext.getCmp(g_transporte.id+'-per_id').setReadOnly(true);
				}else{
					Ext.getCmp(g_transporte.id+'-per_id').setReadOnly(false);
				}
				Ext.getCmp(g_transporte.id+'-per_id').setValue(parseInt(per_id));
				//cosole.log(per_id);
			},
			waypoints:function(record){
				var value = Ext.getCmp(g_transporte.id+'-origen').getUnidad();
				var age_x = record.get('age_x');
				var age_y = record.get('age_y');
				Ext.getCmp(g_transporte.id+'-origen').google_ruta(age_x,age_y,value.d_dir_px,value.d_dir_py);
				

			},
			save_unidad:function(){
				
				
				var form = Ext.getCmp(g_transporte.id+'save-unidad').getForm();
				var agencia = Ext.getCmp(g_transporte.id+'-agencia').getValue();
				var vp_man_id = g_transporte.save.vp_man_id;
				var vp_per_m = Ext.getCmp(g_transporte.id+'-per_id').getValue();
				var vp_und_id = g_transporte.save.vp_und_id;
				var vp_srec_id = g_transporte.save.vp_srec_id
				var val_des = Ext.getCmp(g_transporte.id+'-destino').valida_coordenada();
			
				if (form.isValid() && val_des){
					Ext.Ajax.request({
						url:g_transporte.url+'scm_scm_home_delivery_add_ruta/',
						params:
						{	
							vp_provin:agencia,
							vp_man_id:vp_man_id,
							vp_gui_num:vp_srec_id,
							vp_per_m:vp_per_m,
							vp_und_id:vp_und_id
						},
						success:function(response,options){
							var res = Ext.decode(response.responseText);
							if (parseInt(res.data[0].error_sql)==1){
								global.Msg({
									msg:res.data[0].error_info,
									icon:1,
									buttosn:1,
									fn:function(btn){
										Ext.getCmp(g_transporte.id+'-center-west').setVisible(false);
										g_transporte.por_georeferenciar();
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
				}else{
					global.Msg({
						msg:'Debes Completar los Campos / Origen - Destino',
						icon:0,
						buttosn:1,
						fn:function(btn){
						}
					});
				}
			},
			por_georeferenciar:function(){
				g_transporte.setMap();
				var grid = Ext.getCmp(g_transporte.id+'-por-georeferenciar');
				var vp_agencia = Ext.getCmp(g_transporte.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(g_transporte.id+'-fecha').getRawValue();
				var vp_estado = Ext.getCmp(g_transporte.id+'-stado').getValue();
				grid.getStore().load({
					params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha,vp_estado:vp_estado},
					callback:function(){
						Ext.getCmp(g_transporte.id+'-center-west').setVisible(false);
					}
				});
			},
			get_georeferencias:function(record){
				Ext.getCmp(g_transporte.id+'west').setVisible(false);
				Ext.getCmp(g_transporte.id+'-acoordion-destino').setCollapsed(false);
				g_transporte.save.vp_man_id=0;
				g_transporte.save.vp_srec_id=0;
				g_transporte.save.vp_per_m=0;
				g_transporte.save.vp_per_id_h=0;
				g_transporte.save.vp_und_id=0;
				g_transporte.save.vp_srec_id = record.get('guia');
				Ext.getCmp(g_transporte.id+'-center-west').setVisible(true);
				var cnt_recol=0;
				var id_rec_gui2 = 0;
				var id_rec_gui = 0;

				Ext.Ajax.request({
					url:g_transporte.url+'scm_scm_home_delivery_paradas/',
					params:{vp_guia:record.get('guia')},
					success: function(response, options){
						var res = Ext.JSON.decode(response.responseText).data;

						Ext.each(res,function(obj,index){
							
							if (obj.tipo_parada == 'R'){
								cnt_recol = cnt_recol+1;
								if (cnt_recol == 1){
									id_rec_gui = obj.id_rec_gui;
								}
								if(cnt_recol == 2){
									id_rec_gui2 = obj.id_rec_gui;	
								}
								//console.log(record.get('id_shipper'));
								Ext.getCmp(g_transporte.id+'-origen').setDirections(
									{tipo:'O',valor:2,//parseInt(record.get('place_org')),
									id_shipper:record.get('id_shipper'),
									gui_srec_id:id_rec_gui,
									gui_srec_id2:id_rec_gui2,
									cnt_recol:cnt_recol,
									id_age:obj.id_age,
									vp_guia:record.get('guia'),
									id_direccion:record.get('id_dir')
								});
							}
						});
					}

				});
			},
			order_ruta:function(){

				var grid = Ext.getCmp(g_transporte.id+'-ruta');
				var arrayData =[];
				if (grid.getStore().getCount() > 0){
					for(var i = 0; i < grid.getStore().getCount(); ++i){
						var rec = grid.getStore().getAt(i);
						arrayData.push(rec);
					}	
				}
				console.log(arrayData);
			},
			select_ruta:function(){

				g_transporte.arrayMaker = [];
				g_transporte.setMap();

				var grid = Ext.getCmp(g_transporte.id+'-ruta');
				var dir_px;
				var dir_py;
				var tipo;
				var coordenadas;
				var ruta;
				if (grid.getStore().getCount() > 0){
					for(var i = 0; i < grid.getStore().getCount(); ++i){
						var rec = grid.getStore().getAt(i);
						dir_px = rec.get('dir_px');
						dir_py = rec.get('dir_py');
						tipo = rec.get('tipo');
						ruta = parseInt(rec.get('ruta'));
						id_doc = parseInt(rec.get('id_doc'));
						guia = parseInt(rec.get('guia'));
						logo = rec.get('shipper_logo');
						coordenadas = new google.maps.LatLng(dir_px,dir_py);
						g_transporte.secuencia_ruta_marker(coordenadas,tipo,id_doc,ruta,guia,logo);
					}	
				}
			},
			secuencia_ruta_marker:function(LatLng,tipo,id_doc,ruta,guia,logo){
				var icon = g_transporte.case_conjunto(tipo);
				if (tipo == 'R'){
    				icon ='/images/icon/'+logo;
    			}

		        var contentString = '<div id="content"  style="width:15px;">'+
		              ruta +
		              '</div>';
		        var infowindow = new google.maps.InfoWindow({
		              content: contentString,
		              maxWidth: 35
		        });      
		        var marker = new google.maps.Marker({
		              position: LatLng,
		              map: g_transporte.map,
		              animation: google.maps.Animation.DROP,
		              title: '',
		              icon:icon,
		              draggable:true,
		              ruta:ruta,
		              guia:guia
		        });
		        google.maps.event.addListener(marker, 'click', function() {
		        	 infowindow.open(g_transporte.map,marker);
		        });

		        g_transporte.arrayMaker.push(marker);
			},
			findMaker:function(rec){
		        g_transporte.setMap();
		        for (var i = 0; i < g_transporte.arrayMaker.length; i++) {
		            if(parseInt(g_transporte.arrayMaker[i].guia) == rec){
		                g_transporte.arrayMaker[i].setMap(g_transporte.map);  
		                g_transporte.arrayMaker[i].setAnimation(google.maps.Animation.BOUNCE);
		            }else{
		                g_transporte.arrayMaker[i].setMap(g_transporte.map);  
		            }
		        }
		        g_transporte.waypoints_ruta_unidad();
		    },
		    pinta_unidad_actual:function(x,y,icono){
		        var unidad = new google.maps.LatLng(x,y);
		        var marker = new google.maps.Marker({
		                position: unidad,
		                map: g_transporte.map,
		                title: '',
		                icon:icono
		        });
		    },
		    waypoints_ruta_unidad:function(){
		    	//console.log('entro');
		    	var grid = Ext.getCmp(g_transporte.id+'-ruta');
		    	var o_dir_px;
		    	var o_dir_py;
		    	var d_dir_px;
		    	var d_dir_py;
		    	var location =[];
		    	var coordenada;
		    	Ext.Ajax.request({
		    		url:g_transporte.url+'scm_scm_dispatcher_unidad_gps/',
		    		params:{vp_unidad:g_transporte.id_unidad},
		    		success:function(response,options){
		    			var res = Ext.decode(response.responseText).data[0];
		    			if (res.dir_px==''){
		    				alert('Moto sin coordenada');
		    			}
		    			o_dir_px = parseFloat(res.dir_px);
		    			o_dir_py = parseFloat(res.dir_py);

		    			g_transporte.pinta_unidad_actual(o_dir_px,o_dir_py,'/images/icon/moto32.png');

				    	if (grid.getStore().getCount() > 0){
				    		for(var i = 0; i < grid.getStore().getCount(); ++i){
				    			var rec = grid.getStore().getAt(i);
				    			
				    			if (i < 3){
				    				d_dir_px = parseFloat(rec.get('dir_px'));
									d_dir_py = parseFloat(rec.get('dir_py'));
				    			}

				    			if (i < 3){
				    				coordenada = new google.maps.LatLng(parseFloat(rec.get('dir_px')),parseFloat(rec.get('dir_py')));
				    				location.push({location:coordenada});
				    			}
				    		}
				    		
				    	}

				        var OriginLatlng = new google.maps.LatLng(o_dir_px,o_dir_py);
				        var destino = new google.maps.LatLng(d_dir_px,d_dir_py);
				        var request = {
				            origin:OriginLatlng,
				            destination:destino,
				            waypoints:location,
				            optimizeWaypoints: true,
				            travelMode: google.maps.TravelMode.DRIVING,
				            provideRouteAlternatives: true
				        };
				         g_transporte.varsmapa.directionsService.route(request,function(response,status){
				            if (status == google.maps.DirectionsStatus.OK){
				                 g_transporte.varsmapa.directionsDisplay.setDirections(response);
				                
				            }else{
				                alert(status);
				            }
				        });
		    		}

		    	});
		    },
		    case_conjunto:function(tipo){
		    	if (tipo=='E'){
		    		return '/images/icon/marker1.png';
		    	}else if(tipo=='R'){
		    		return '/images/icon/marker2.png';
		    	}else if(tipo=='M'){
		    		return '/images/icon/moto32.png';
		    	}
		    },
			origen_destino:function(record){
				console.log(record);
				var OriginLatlng;
				var destino;
				var search;
				var paradas=[];
				Ext.Ajax.request({
					url:g_transporte.url+'scm_scm_home_delivery_paradas/',
					params:{vp_guia:record.get('guia')},
					success: function(response, options){
						var res = Ext.JSON.decode(response.responseText).data;
						Ext.each(res,function(obj,index){
							var img = g_transporte.case_conjunto(obj.tipo_parada);
							g_transporte.pinta_unidad_actual(parseFloat(obj.pos_px),parseFloat(obj.pos_py),img);
							if (obj.tipo_parada == 'M'){//cambiar por M
								OriginLatlng = new google.maps.LatLng(parseFloat(obj.pos_px),parseFloat(obj.pos_py));
								search = 'M'
							}
							/*** Todas las rutas despues de la Moto ***/
							if (search == 'M' && obj.tipo_parada != 'M' && obj.tipo_parada !='E'){
								var new_parada = new google.maps.LatLng(parseFloat(obj.pos_px),parseFloat(obj.pos_py));	
								paradas.push({location:new_parada});
								//console.log(paradas);
							}
							/*** El destino es el ultimo dergistro***/
							if (obj.tipo_parada == 'E'){
								destino = new google.maps.LatLng(parseFloat(obj.pos_px),parseFloat(obj.pos_py));	
							}
							
						});
						var request = {
					            origin:OriginLatlng,
					            destination:destino,
					            //waypoints:[{location:'',tape:false}],//{location: agencia},{location: '-13.918217, -74.330162'}  
					            waypoints:paradas,
					            optimizeWaypoints: true,
					            travelMode: google.maps.TravelMode.DRIVING,
					            provideRouteAlternatives: true
					    };
				        g_transporte.varsmapa.directionsService.route(request,function(response,status){
				            if (status == google.maps.DirectionsStatus.OK){
				            	
				                g_transporte.varsmapa.directionsDisplay.setDirections(response);
				                
				            }else{
				                alert('no hay ruta');
				                status;
				            }
				        });
						
					}


				});
			},
			chart:function(){
				FusionCharts.ready(function () {
				var chart = new FusionCharts({
                            type: 'mscombi2d',
                            renderAt: 'chart-cont',
                            width: '300',
                            height: '300',
                            dataFormat: 'json',
                            dataSource: {
                                "chart": {
                                    "caption": 'Resumen General',
                                    "rotateValues": "1",
                                    "formatNumberScale": "0",
                                    "exportEnabled": "1",
                                    "legendBgColor": "#CCCCCC",
                                    "legendBgAlpha": "20",
                                    "legendBorderColor": "#666666",
                                    "legendBorderThickness": "1",
                                    "legendBorderAlpha": "40",
                                    "legendShadow": "1",
                                    "theme": "fint"
                                },
                                "categories": [
                                    {
                                        "category":[
                                        		{"label": "Total Servicio"},
                                        		{"label": "Ejecutados"},
                                        		{"label": "Ausentes"},
                                        		{"label": "Pendientes"},
                                        ]
                                    }
                                ],
                               "dataset": [
                               				{
                               					"data":[
                               						{
											         "label": "Total Servicio",
											         "value": "20",
											      	},
											      	{
											         "label": "Ejecutados",
											         "value": "14",
											      	},
											      	{
											         "label": "Ausentes",
											         "value": "2",
											      	},
											      	{
											         "label": "Pendientes",
											         "value": "6",
											      	}
                               					],
                               					"seriesname":"General"
                               				},                     				
								]
                            }
              		 });
					chart.render();
				});
			},
			polyline:function(){
				var vp_agencia = Ext.getCmp(g_transporte.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(g_transporte.id+'-fecha').getRawValue();
				var lineSymbol = {
				    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
				};
				Ext.Ajax.request({
					url:g_transporte.url+'scm_scm_home_delivery_unidades/',
					params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha},
					  success: function(response, options){
					  	var res = Ext.JSON.decode(response.responseText).data;

					  	Ext.each(res,function(obj,index){
					  		if (parseInt(obj.servicio_px) != 0 ){
						  		var lineCoordinates = [
								    new google.maps.LatLng(obj.pos_px, obj.pos_py),
								    new google.maps.LatLng(obj.servicio_px, obj.servicio_py)
								];
								//console.log(lineCoordinates);
								var img = g_transporte.case_conjunto('R');
				    			g_transporte.pinta_unidad_actual(parseFloat(obj.servicio_px),parseFloat( obj.servicio_py),img);

								var line = new google.maps.Polyline({
								    path: lineCoordinates,
								    geodesic: true,
				                    strokeColor: '#0C04FA',
				                    strokeOpacity: 0.5,
				                    strokeWeight: 3,
				                    //draggable: true,
				                    editable: true,
								    icons: [{
								      icon: lineSymbol,
								      offset: '100%'
								    }],
								    map: g_transporte.map
								});
							}	
					  	});
						  	

					  }
				});
			},
			newServices:function(){
				var id_agencia = Ext.getCmp(g_transporte.id+'-agencia').getValue();
				Ext.Ajax.request({
                    url: g_transporte.url+'scm_home_delivery_pendientes/',
                    params:{vp_agencia:id_agencia},
                    success: function(response, options){
                        var res = Ext.JSON.decode(response.responseText);
                        res = res.data[0];
                        if(parseInt(res.pendientes)!=0){
                        	Ext.getCmp(g_transporte.id+'-btn_alert_').show();
                        	Ext.getCmp(g_transporte.id+'-btn_alert_').setText(res.pendientes+" Nuevo(s) Servicio(s)");
                        }else{
                        	Ext.getCmp(g_transporte.id+'-btn_alert_').hide();
                        	Ext.getCmp(g_transporte.id+'-btn_alert_').setText("");
                        }
                    }
                });
			},
			toggleButton: function(vi){
				var obj = ['-btn-1', '-btn-2', '-btn-3'];
				g_transporte.actionToggle(vi);
				Ext.Object.each(obj, function(index, value){
	                if (index == vi){
		                Ext.getCmp(g_transporte.id + value).toggle(true);
		            }else{
		            	Ext.getCmp(g_transporte.id + value).toggle(false);
		            }   
	            });
			},
			actionToggle:function(vi){
				if (vi == 0){
					Ext.getCmp(g_transporte.id+'-grid-vista3').setVisible(false);
					Ext.getCmp(g_transporte.id+'-btn3').setVisible(false);
					Ext.getCmp(g_transporte.id+'-stado').setVisible(false);
					Ext.getCmp(g_transporte.id+'-btn2').setVisible(false);
					Ext.getCmp(g_transporte.id+'btn1').setVisible(true);
					Ext.getCmp(g_transporte.id+'-center-west').setVisible(false);
					Ext.getCmp(g_transporte.id+'west').setVisible(true);
					Ext.getCmp(g_transporte.id+'center-south').setVisible(false);
					Ext.getCmp(g_transporte.id+'-recol-ruta').setVisible(true);
					Ext.getCmp(g_transporte.id+'west').setWidth(550);

					Ext.getCmp(g_transporte.id+'chk_hasta').setVisible(false);
					Ext.getCmp(g_transporte.id+'-hasta').setVisible(false);
					Ext.getCmp(g_transporte.id+'chk-solo-ruta').setVisible(false);
				}else if(vi == 1){
					Ext.getCmp(g_transporte.id+'-btn3').setVisible(false);
					Ext.getCmp(g_transporte.id+'-stado').setVisible(true);
					Ext.getCmp(g_transporte.id+'-btn2').setVisible(true);
					Ext.getCmp(g_transporte.id+'btn1').setVisible(false);
					Ext.getCmp(g_transporte.id+'-center-west').setVisible(false);
					Ext.getCmp(g_transporte.id+'west').setVisible(false);
					Ext.getCmp(g_transporte.id+'center-south').setVisible(true);
					Ext.getCmp(g_transporte.id+'-secuencia-ruta').setVisible(false);
					Ext.getCmp(g_transporte.id+'-panel-porgeo').setVisible(true);
					Ext.getCmp(g_transporte.id+'chk_hasta').setVisible(false);
					Ext.getCmp(g_transporte.id+'-hasta').setVisible(false);
					Ext.getCmp(g_transporte.id+'chk-solo-ruta').setVisible(false);
				}else if (vi == 2){
					Ext.getCmp(g_transporte.id+'-btn3').setVisible(true);
					Ext.getCmp(g_transporte.id+'west').setVisible(true);
					//Ext.getCmp(g_transporte.id+'west').setCollapsed(false);
					
        	 		Ext.getCmp(g_transporte.id+'center-south').setVisible(false);
        	 		Ext.getCmp(g_transporte.id+'-center-west').setVisible(false);
        	 		Ext.getCmp(g_transporte.id+'-recol-ruta').setVisible(false);
        	 		Ext.getCmp(g_transporte.id+'btn1').setVisible(false);
        	 		Ext.getCmp(g_transporte.id+'-btn2').setVisible(false);
        	 		Ext.getCmp(g_transporte.id+'-stado').setVisible(false);
        	 		Ext.getCmp(g_transporte.id+'-grid-vista3').setVisible(true);
        	 		Ext.getCmp(g_transporte.id+'chk_hasta').setVisible(true);
        	 		Ext.getCmp(g_transporte.id+'-hasta').setVisible(true);
        	 		Ext.getCmp(g_transporte.id+'chk-solo-ruta').setVisible(true);
        	 		var width_num =Ext.getCmp(g_transporte.id+'form').getWidth();
        	 		console.log(width_num);
        	 		Ext.getCmp(g_transporte.id+'west').setWidth(width_num);
				}
			}


		}
		Ext.onReady(g_transporte.init,g_transporte);
	}else{
		tab.setActiveTab(g_transporte.id+'-tab');
	}
</script>