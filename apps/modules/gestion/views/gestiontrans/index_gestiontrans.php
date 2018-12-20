<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if (!Ext.getCmp('gestiontrans-tab')){
		var gestiontrans = {
			id:'gestiontrans',
			id_menu:'<?php echo $p["id_menu"];?>',
			//url:'/gestion/gtransporte/',
			url:'/gestion/gestiontrans/',
			arrayMaker:[],
			id_unidad:0,
			tplInciPanel:new Ext.XTemplate(
					'<tpl for=".">',
						'<div class="gt-tpl-box">',
							'<div class="gt-tpl-1">Secuencia Ejecución</div>',
							'<div class="gt-tpl-2"> {cnt} </div>',
							//'<div class="gt-tpl-3">Programaciones en el Mismo Horario, Puedes cambiarlo o esperar la Confimación del Dispacher</div>',
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
				//dejarlo en cero cuando grabo todo la recoleccion
				vp_srec_id:1
				//gui_srec_id:0,
				/*ciu_id:0,
				tipo:0,
				chk:'',
				id_shipper:0,
				id_direccion:0*/
			},
			//clock: Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A'),ui:''}),
			map:'',
			//timer:Ext.Date.format(new Date(), 'g:i:s A'),
			save:{
				vp_man_id:0,
				vp_srec_id:0,
				vp_per_m:0,
				vp_per_id_h:0,
				vp_und_id:0
			},
			init:function(){	
				var store_ruta = Ext.create('Ext.data.Store',{
					fields:[
						{name:'ruta', type:'string'},
						{name:'tipo_servicio', type:'string'},
						{name:'distrito', type:'string'},
						{name:'direccion', type:'string'},
						{name:'cliente', type:'string'},
						{name:'horario', type:'string'},
						{name:'peso', type:'string'},
						{name:'estado', type:'string'},
						{name:'shipper', type:'string'},
						{name:'tipo', type:'string'},
						{name:'id_doc', type:'string'},
						{name:'dir_px', type:'float'},
						{name:'dir_py', type:'float'},
						{name:'chk', type:'string'},
						{name:'tipo_dir', type:'string'}   
					],
					proxy:{
						type:'ajax',
						url:gestiontrans.url+'scm_scm_dispatcher_lista_ruta/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				var store = Ext.create('Ext.data.Store',{
					fields:[
						{name:'placa', type:'string'},
						{name:'estado', type:'string'},
						{name:'carga', type:'string'},
						{name:'capacidad', type:'string'},
						{name:'tipo', type:'string'},
						{name:'id_unidad', type:'string'},
						{name:'man_id', type:'string'},
						{name:'per_manager', type:'int'},
						{name:'per_ayudante', type:'int'},
						{name:'tot_ld', type:'int'},
						{name:'pos_px', type:'float'},
						{name:'pos_py', type:'float'}
						
					],
					proxy:{
						type:'ajax',
						url:gestiontrans.url+'scm_scm_dispatcher_unidades/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				var store2 = Ext.create('Ext.data.Store',{
					fields:[
						{name:'placa', type:'string'},
						{name:'estado', type:'string'},
						{name:'carga', type:'string'},
						{name:'capacidad', type:'string'},
						{name:'tipo', type:'string'},
						{name:'id_unidad', type:'string'},
						{name:'man_id', type:'string'},
						{name:'per_manager', type:'int'},
						{name:'per_ayudante', type:'int'},
						{name:'tot_ld', type:'int'},
						{name:'pos_px', type:'float'},
						{name:'pos_py', type:'float'},
						{name:'time', type:'string'},
						{name:'distance', type:'string'}

					],
					proxy:{
						type:'ajax',
						url:gestiontrans.url+'scm_scm_dispatcher_unidades/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				var porgeoreferenciar = Ext.create('Ext.data.Store',{
					fields:[
						{name:'origen', type:'string'},
						{name:'origen_zona', type:'string'},
						{name:'origen_dir', type:'string'},
						{name:'origen_px', type:'float'},
						{name:'origen_py', type:'float'},
						{name:'destino', type:'string'},
						{name:'destino_zona', type:'string'},
						{name:'destino_dir', type:'string'},
						{name:'destino_px', type:'float'},
						{name:'destino_py', type:'float'},
						{name:'horario', type:'string'},
						{name:'peso', type:'string'},
						{name:'estado', type:'string'},
						{name:'shipper', type:'string'},
						{name:'gui_srec_id', type:'string'},
						{name:'chk', type:'string'},
						{name:'id_shipper', type:'string'},
						{name:'ciu_id_ori', type:'string'},
						{name:'id_geo_ori', type:'string'},
						{name:'id_dir_ori', type:'string'},
						{name:'ciu_id_des', type:'string'},
						{name:'id_geo_des', type:'string'},
						{name:'id_dir_des', type:'string'},
						{name:'place_org', type:'int'},
						{name:'place_des', type:'int'},
						

						                                                                              
					],
					proxy:{
						type:'ajax',
						url:gestiontrans.url+'scm_scm_dispatcher_servicios/',
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
						url:gestiontrans.url+'/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				var south = Ext.create('Ext.form.Panel',{
					id:gestiontrans.id+'-south',
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
								id:gestiontrans.id+'-panel-porgeo',
								items:[
										{
											xtype:'grid',
											id:gestiontrans.id+'-por-georeferenciar',
											//hidden:true,
											store:porgeoreferenciar,
											//columnWidth:1,
											columnLines:true,
											//height:150,
											columns:{
												items:[
														{
															text:'Origen',
															dataIndex:'origen',
															flex:2
														},
														{
															text:'Zona',
															dataIndex:'origen_zona',
															flex:1
														},
														{
															text:'Origen Direccion',
															dataIndex:'origen_dir',
															flex:3
														},
														{
															text:'Destino',
															dataIndex:'destino',
															flex:2
														},
														{
															text:'Zona',
															dataIndex:'destino_zona',
															flex:1
														},
														{
															text:'Destino Direccion',
															dataIndex:'destino_dir',
															flex:3
														},
														{
															text:'Horario',
															dataIndex:'horario',
															flex:1
														},
														{
															text:'Peso',
															dataIndex:'peso',
															flex:1
														},
														{
															text:'Estado',
															dataIndex:'estado',
															flex:1
														},
														{
															text:'Shipper',
															dataIndex:'shipper',
															flex:1
														}

												]
											},
											listeners:{
												beforeselect:function(obj, record, index, eOpts ){
													//gestiontrans.get_georeferencias(record);	
													if (record.get('chk')=='SS'){
														gestiontrans.get_georeferencias(record);	
													}else{
														Ext.getCmp(gestiontrans.id+'-center-west').setVisible(false);
														Ext.getCmp(gestiontrans.id+'west').setVisible(true);
														//gestiontrans.origen_destino(record);
													}

												}
											}
										},
								]
							},
							{
								xtype:'panel',
								columnWidth:1,
								layout:'fit',
								bbar:[
										/*{
											text:'Graba Secuencia',
											id:gestiontrans.id+'-grabar-secuencia',
											icon: '/images/icon/save.png',
											listeners:{
												click:function(obj){
													gestiontrans.order_ruta();
												}
											}
										}*/
								],
								id:gestiontrans.id+'-secuencia-ruta',
								items:[
										{
											xtype:'grid',
											id:gestiontrans.id+'-ruta',
											store:store_ruta,
											//columnWidth:1,
											columnLines:true,
											//height:150,
											columns:{
												items:[	
														//{xtype: 'rownumberer'},
														{
															text:'<img src="/images/icon/save.png">',
															dataIndex:'ruta',
															width:50,
															sortable: false,
															align:'center'
															//renderer:function(value, metaData, record, rowIndex, colIndex){
																/*console.log(rowIndex);
																metaData.style = "padding: 0px; margin: 0px";
																if (rowIndex==0){
																	return  value +'<img src="/images/icon/save.png" alt="" >';	
																}else{
																	return  value;
																}*/
																
															//}

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
															text:'Horario',
															dataIndex:'horario',
															flex:1,
															sortable: false
														},
														{
															text:'Peso',
															dataIndex:'peso',
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
															text:'Shipper',
															dataIndex:'shipper',
															flex:1,
															sortable: false
														},
														
												]
											},
											viewConfig: {
									            plugins: {
									                ptype: 'gridviewdragdrop',
									                dragText: 'Arrastre y Suelte Para Organizar la Ruta'
									            },
									            listeners:{
									            	drop:function(node, data, overModel, dropPosition, eOpts){
									            		//console.log(data.records[0].data.id_doc);
									        			//gestiontrans.waypoints_ruta_unidad();
									        			gestiontrans.findMaker(parseInt(data.records[0].data.ruta));	
									        		}	
									            }
									        },
									        listeners:{
									        	beforeselect:function(obj, record, index, eOpts ){
									        		gestiontrans.findMaker(parseInt(record.get('ruta')));	
									        	},
									        	headerclick:function( ct, column, e, t, eOpts ){
									        		//console.log(column.getHeader( ));
									        		//console.log(column.componentLayout.owner.dataIndex);
									        		if (column.componentLayout.owner.dataIndex=='id_doc'){
									        			gestiontrans.order_ruta();
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
					id:gestiontrans.id+'-west',
					border:false,
					layout:'column',
					defaults:{
						border:false
					},

					items:[
							{
								xtype:'grid',
								columnWidth:1,
								border:true,
								height:800,
								id:gestiontrans.id+'-recol-ruta',
								store:store,
								columnLines:true,
								columns:{
									items:[

											{
												text:'Placa',
												dataIndex:'placa',
												width:85,
												align:'center',
												renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
													metaData.style = "padding: 2px; margin: 0px;";
													metaData.tdAttr = 'data-qtip=Bateria:"' + record.get('placa') + '"';
													if (record.get('tipo')==5){
														return '<div style="height:16px;display:inline-block;width:80px;"><table width="80px"><tr><td width="80px";><img src="/images/icon/moto.png">&nbsp;&nbsp;&nbsp;&nbsp;'+value+'</td><td  width="5px"; style="background-color: #'+( record.get('pos_px')!='' ?'3CB371':'DC143C')+';"></td></tr></table></div>';
													}else if(record.get('tipo')==4){
														return '<img src="/images/icon/car.png">&nbsp;&nbsp;&nbsp;&nbsp;'+value;	
													}
													
												}
											},
											/*{
												text:'Estado',
												dataIndex:'estado',
												flex:1,
												align:'center',
											},*/
											{
												text:'Cantidad',
												dataIndex:'carga',
												width:60,
												align:'center',
											},
											{
												text:'Progreso',
												dataIndex:'estado',
												align:'center',
												width:120,
												renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
													var id = Ext.id();
													var cantidad = record.get('tot_ld')==0 ? 2000:record.get('tot_ld');
													var v = 1*cantidad;
													//console.log(cantidad);
								                    Ext.defer(function () {
								                        Ext.widget('progressbar', {
								                            renderTo: id,
								                            value: v / cantidad,
								                            width: 100,
								                            animate:true,
								                            //cls:'p_bar-orange',
								                            listeners:{
								                            	afterrender:function( obj, eOpts ){
								                            		//console.log(cantidad);
								                            		if (obj.getValue() < 0.5){
								                            		//if (cantidad==2000){
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
												text:'Tiempo desde</br>Recoleccion',
												align:'center',
												flex:1
											},
											{
												text:'Tiempo desde</br>Entrega',
												align:'center',
												flex:1
											},
									]
								},
								listeners:{
									beforeselect:function(obj, record, index, eOpts ){
										gestiontrans.id_unidad = record.get('id_unidad');
										var agencias = Ext.getCmp(gestiontrans.id+'-agencia').getValue();
										Ext.getCmp(gestiontrans.id+'-panel-porgeo').setVisible(false);
										Ext.getCmp(gestiontrans.id+'-secuencia-ruta').setVisible(true);
										Ext.getCmp(gestiontrans.id+'-ruta').getStore().load({
											params:{vp_agencia:agencias,vp_man_id:record.get('man_id')},
											callback:function(){
												gestiontrans.select_ruta();
												gestiontrans.pinta_unidad_actual(record.get('pos_px'),record.get('pos_py'),'/images/icon/moto32.png');
											}
										});
									},

								},
								viewConfig: {
		            				getRowClass: function(record, rowIndex, rowParams, store){	
		            					//console.log(record.get('pos_px')!='');
		            					//return record.get('pos_px')!='' ? '' : 'row-error';
		            					//return parseInt(record.get('dir_valido')) != 1 ? 'row-error' : '';
		            				}
		            			},
							}
							

					]
				});
				
				var panel = Ext.create('Ext.form.Panel',{
					id:gestiontrans.id+'form',
					border:false,
					layout:'border',
					defaults:{
						border:false
					},
					tbar:[
							{
								text:'',
								id:gestiontrans.id+'-btn-1',
								icon:'/images/icon/dispacher.png',
								tooltip:'Click Para Formulario del Dispacher',
								listeners:{
									click:function(obj,opts){
										//gestiontrans.dispacher();
										gestiontrans.toggleButton(0);
									}
								}
							},
							{
								text:'',
								id:gestiontrans.id+'-btn-2',
								iconAlign:'bottom',
								icon: '/images/icon/add.png',
								tooltip:'Click Para Crear Nuevo',
								listeners:{
									click:function(obj,opts){
									//	gestiontrans.nuevo();
									gestiontrans.toggleButton(1);
									}
								}
							},
							{
								text:'',
								id:gestiontrans.id+'-btn-3',
								iconAlign:'bottom',
								icon: '/images/icon/time2_01.png',
								tooltip:'Click Para Reprogramar',
								listeners:{
									click:function(obj,opts){
										//gestiontrans.reprogramar();
										gestiontrans.toggleButton(2);
									}
								}	
							},
							{
								xtype:'combo',
								id:gestiontrans.id+'-agencia',
								//fieldLabel:'Agencia',
								//labelAlign:'top',
								store:Ext.create('Ext.data.Store',{
								fields:[
										{name:'prov_codigo', type:'int'},
										{name:'prov_nombre', type:'string'},
								],
								proxy:{
									type:'ajax',
									url:gestiontrans.url+'get_usr_sis_provincias/',
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
								width:100,
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
							},'-',{ xtype: 'tbspacer',width:20 },'-',
							{
								xtype:'datefield',
								id:gestiontrans.id+'-fecha',
								//fieldLabel:'Agencia',
								//labelAlign:'top',
								width:100,
								value:new Date()
							},
							{
								text:'',
								iconAlign:'bottom',
								icon: '/images/icon/search.png',
								tooltip:'Buscar',
								listeners:{
									click:function(obj,opts){
										gestiontrans.buscar();
									}
								}
							},
							{
								xtype:'checkbox',
								fieldLabel:'Trafico',
								labelWidth:40,
								listeners:{
									change:function( obj, newValue, oldValue, eOpts ){
										if (newValue){
											 gestiontrans.varsmapa.trafficLayer.setMap(gestiontrans.map);
										}else{
											 gestiontrans.varsmapa.trafficLayer.setMap(null);
										}
										
									}
								}
							}
					],
					items:[
							{
								//xtype:'panel',
								region:'west',
								layout:'border',
								id:gestiontrans.id+'west',
								header:false,
								split: true,
								collapsible: true,
								hideCollapseTool:true,
								titleCollapse:false,
								floatable: false,
								border:false,
								width:420,
								collapseMode : 'mini',
								animCollapse : true,
								cls:'.x-accordion-hd',
								items:[
										{
											region:'center',
											layout:'fit',
											border:false,
											items:[west]
										},
										
								]
							},
							{
								region:'center',
								layout:'border',
								border:false,
								items:[
										{
											region:'west',
											id:gestiontrans.id+'-center-west',
											layout:'fit',
											width:450,
											//hidden:true,
											//split:true,
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
														width: 450,
        												defaults: {
												            // applied to each contained panel
												           // bodyStyle: 'padding:15px'
												        },
														layout:{
															type: 'accordion',
												            titleCollapse: false,
												            animate: true,
												            //activeOnTop: true
														},
														items:[
															{
																title:'Programar Un(a) Recojo/Entrega',
																id:gestiontrans.id+'-acoordion-recojo-entrega',
																//autoScroll: true,
																scrollable:'vertical',
																//layout:'fit',
																//height:'100%',
																bbar:[
																		{
																			text:'',
																			id:gestiontrans.id+'-save1',
																			icon:'/images/icon/save.png',
																			listeners:{
																				click:function(obj, e){
																					gestiontrans.save1();
																				}
																			}	
																		},
																		{
																			text:'',
																			icon:'/images/icon/new_file.ico',
																			listeners:{
																				click:function(obj,e){
																					gestiontrans.nuevo();
																				}
																			}
																		},
																		{
																			text:'',
																			icon:'/images/icon/get_back.png',
																			listeners:{
																				click:function(obj, e){
																				}
																			}
																		},
																		{xtype:'tbspacer',width:230},
																		{
																			xtype:'checkbox',
																			id:gestiontrans.id+'-recojo-frecuente',
																			fieldLabel:'Recojo Frecuente',
																			labelWidth:100,
																			listeners:{
																				change:function( obj, newValue, oldValue, eOpts ){
																					if (newValue){
																						Ext.getCmp(gestiontrans.id+'-form-frecuente').setVisible(true);
																						Ext.getCmp(gestiontrans.id+'form-diaria').setVisible(false);
																					}else{
																						Ext.getCmp(gestiontrans.id+'-form-frecuente').setVisible(false);
																						Ext.getCmp(gestiontrans.id+'form-diaria').setVisible(true);
																					}
																				}
																			}
																		}
																],
																items:[
																		{
																			xtype:'form',
																			id:gestiontrans.id+'-form1',
																			//layout:'form',
																			border:false,
																			items:[
																					{
																						xtype:'fieldset',
																						//border:false,
																						title:'',
																						layout:'column',
																						margin:'5 5 5 5',
																						padding:'5 5 2 5',
																						items:[
																								{
																									xtype:'panel',
																									//border:true,
																									columnWidth:0.7,
																									region:'west',
																									//width:200,
																									layout:'column',
																									items:[
																											{
																												xtype:'radiogroup',
																												allowBlank:false,
																												columnWidth: 0.6,
																												id:gestiontrans.id+'-rbtn-group-linea',
																												fieldLabel:'Linea',
																												columns:3,
																												vertical:true,
																												labelWidth:35,
																												items:[
																														{boxLabel:'Masivo',name:gestiontrans.id+'-rbtn',inputValue:'1',width:60},
																														{boxLabel:'Valorados',name:gestiontrans.id+'-rbtn',inputValue:'2',width:80},
																														{boxLabel:'Logistica',name:gestiontrans.id+'-rbtn',inputValue:'3',width:80}//,checked:true
																												],
																												listeners:{
																													change:function(obj, newValue, oldValue, eOpts){
																														var op = parseInt(newValue[gestiontrans.id+'-rbtn']);
																														gestiontrans.linea = op;
																														Ext.getCmp(gestiontrans.id+'-shipper').store.load({
																															params:{vp_linea:op},
																															callback:function(){
																															}
																														});
																													}
																												}
																											},
																											{
																												xtype:'combo',
																												columnWidth: 0.98,
																												id:gestiontrans.id+'-shipper',
																												padding:'0 0 5 0',
																												fieldLabel:'Shipper',
																												labelWidth:50,
																												store:Ext.create('Ext.data.Store',{
																												fields:[
																														{name: 'shi_codigo', type: 'int'},
																						                                {name: 'shi_nombre', type: 'string'},
																						                                {name: 'shi_id', type: 'string'}
																												],
																												proxy:{
																													type:'ajax',
																													url:gestiontrans.url+'get_usr_sis_shipper/',
																													reader:{
																														type:'json',
																														rootProperty:'data'
																													}
																												}
																												}),
																												queryMode:'local',
																												valueField:'shi_codigo',
																												displayField:'shi_nombre',
																												listConfig:{
																													minWidth:350
																												},
																												width:250,
																												forceSelection:true,
																												allowBlank:false,
																												selecOnFocus:true,
																												emptyText:'[ Seleccione ]',
																												listeners:{
																													afterrender:function(obj,record,options){
																														
																													},
																													'select':function(obj,records,eOpts){
																														
																														Ext.getCmp(gestiontrans.id+'-shipper').setReadOnly(true);
																														Ext.getCmp(gestiontrans.id+'-origen').setAgenciaShipper(records.get('shi_codigo'));
																														Ext.getCmp(gestiontrans.id+'-destino').setAgenciaShipper(records.get('shi_codigo'));
																														
																														
																														Ext.getCmp(gestiontrans.id+'-rbtn-group-linea').setReadOnly(true);
																														
																													}
																												}
																											}
																									]
																								},
																								{
																									xtype:'panel',
																									columnWidth:0.3,
																									region:'center',
																									items:[
																											{
																												xtype:'radiogroup',
																												allowBlank:false,
																												//columnWidth:0.4,
																												id:gestiontrans.id+'-rbtn-group-ejecutar',
																												fieldLabel:'Ejecutar',
																												columns:1,
																												vertical:false,
																												labelWidth:40,
																												items:[
																														{boxLabel:'Recojo',name:gestiontrans.id+'-rbtn-ejecutar',inputValue:'1', width:30},
																														{boxLabel:'Entrega',name:gestiontrans.id+'-rbtn-ejecutar',inputValue:'2', width:60}
																												],
																												listeners:{

																												}

																											}
																									]
																								}
																						]
																					},
																					{
																						xtype:'fieldset',
																						columnWidth:1,
																						title:'Cargamento',
																						padding: '0 5 5 5',
																						margin:'5 10 10 10',
																						defaults:{
																							padding: '0 5 5 5'
																						},
																						layout:'column',
																						items:[
																								{
																									xtype:'combo',
																									allowBlank:false,
																									id:gestiontrans.id+'-tip_pqt',
																									columnWidth:1,
																									fieldLabel:'Tipo de Paquete',
																									labelWidth:100,
																									store:Ext.create('Ext.data.Store',{
																										fields:[
																												{name:'descripcion',type:'string'},
																												{name:'id_elemento',type:'int'}
																										     
																										],
																										proxy:{
																											type:'ajax',
																											url:gestiontrans.url+'scm_scm_tabla_detalle/',
																											reader:{
																												type:'json',
																												root:'data'
																											}
																										}
																									}),
																									queryMode:'local',
																									displayField:'descripcion',
																									valueField:'id_elemento',
																									listeners:{
																										afterrender:function(obj){
																											obj.getStore().load({
																												params:{tab_id:'TDP'}
																											});
																										}
																									}

																								},
																								{
																									xtype:'numberfield',
																									allowBlank:false,
																									id:gestiontrans.id+'-cantidad',
																									columnWidth:0.6,
																									fieldLabel:'Total de Pieza',
																									minValue: 1,
																									labelWidth:100
																								},
																								{
																									xtype:'numberfield',
																									allowBlank:false,
																									id:gestiontrans.id+'-peso',
																									columnWidth:0.4,
																									fieldLabel:'Peso (mg)',
																									minValue: 1,
																									labelWidth:65
																								},
																								{
																									xtype:'numberfield',
																									allowBlank:false,
																									id:gestiontrans.id+'-alto',
																									columnWidth:0.3,
																									fieldLabel:'Alto (cm):',
																									minValue: 0,
																									allowDecimals: true,
																									decimalSeparator:'.',
																									labelWidth:25
																								},
																								{
																									xtype:'numberfield',
																									allowBlank:false,
																									id:gestiontrans.id+'-ancho',
																									columnWidth:0.3,
																									fieldLabel:'Ancho (cm):',
																									minValue: 0,
																									allowDecimals: true,
																									decimalSeparator:'.',
																									labelWidth:34
																								},
																								{
																									xtype:'numberfield',
																									allowBlank:false,
																									id:gestiontrans.id+'-largo',
																									columnWidth:0.4,
																									fieldLabel:'Largo (cm)',
																									minValue: 0,
																									allowDecimals: true,
																									decimalSeparator:'.',
																									labelWidth:40
																								},
																								{
																									xtype:'textareafield',
																									allowBlank:false,
																									id:gestiontrans.id+'-descri',
																									columnWidth:1,
																									fieldLabel:'Detalle',
																									height:5,
																									maskRe : /[a-z ñÑA-Z0-9]$/,
																									enforceMaxLength:true,
																									maxLength:80,
																									maxLengthText:'El Maximo de caracteres es {0}',
																									labelWidth:100,
																									
																								},
																								{
																									xtype:'textareafield',
																									allowBlank:false,
																									id:gestiontrans.id+'-observ',
																									columnWidth:1,
																									fieldLabel:'Observación',
																									height:6,
																									maskRe : /[a-z ñÑA-Z0-9]$/,
																									enforceMaxLength:true,
																									maxLength:160,
																									maxLengthText:'El Maximo de caracteres es {0}',
																									labelWidth:100,
																									
																								},

																						]
																					}
																			]
																		}
																],
																listeners:{
																	collapse:function( p, eOpts ){
																		var btn2 = Ext.getCmp(gestiontrans.id+'-save2').isDisabled();
																		if (btn2){
																			Ext.getCmp(gestiontrans.id+'-acoordion-recojo-entrega').setCollapsed(false);
																		}
																	}
																}
															},
															{
																title:'Confirmar Dirección',
																id:gestiontrans.id+'-acoordion-confirmar-direccion',
																scrollable:'vertical',
																bbar:[
																		{
																			text:'',
																			id:gestiontrans.id+'-save2',
																			disabled:true,
																			icon:'/images/icon/save.png',
																			listeners:{
																				click:function(obj, e){
																					gestiontrans.save2();
																				}
																			}	
																		},
																		{
																			text:'',
																			icon:'/images/icon/new_file.ico',
																			listeners:{
																				click:function(obj,e){
																					gestiontrans.nuevo();
																				}
																			}
																		},
																		{
																			text:'',
																			icon:'/images/icon/get_back.png',
																			listeners:{
																				click:function(obj, e){
																				}
																			}
																		}	
																],
																items:[
																		{
																			xtype:'panel',
																			id:gestiontrans.id+'-panel-origen',
																			title:'Origen - Dirección',

																			disabled:true,
																			layout:'fit',
																			height:450,
																			items:[
																					{
																						xtype:'searchdirection2',
																						id:gestiontrans.id+'-origen',
																						tipo:'O'
																					},
																			]
																		},
																		{
																			xtype:'panel',
																			id:gestiontrans.id+'-panel-destino',
																			disabled:true,
																			title:'Destino - Dirección',
																			layout:'fit',
																			height:450,
																			items:[
																					{
																						xtype:'searchdirection2',
																						id:gestiontrans.id+'-destino',
																						tipo:'D'
																					}
																			]
																		}
																],
																listeners:{
																	collapse:function( p, eOpts ){
																		var btn2 = Ext.getCmp(gestiontrans.id+'-save2').isDisabled();
																		var btn3 = Ext.getCmp(gestiontrans.id+'-save3').isDisabled();
																		if(btn2 && btn3){
																			Ext.getCmp(gestiontrans.id+'-acoordion-recojo-entrega').setCollapsed(false);
																		}else if(btn3){
																			Ext.getCmp(gestiontrans.id+'-acoordion-confirmar-direccion').setCollapsed(false);
																		}
																	}
																}
															},
															{
																title:'Programación de la Recolección',
																id:gestiontrans.id+'-datos-recojo',
																scrollable:'vertical',
																border:false,
																bbar:[
																		{
																			text:'',
																			id:gestiontrans.id+'-save3',
																			disabled:true,
																			icon:'/images/icon/save.png',
																			listeners:{
																				click:function(obj, e){
																					gestiontrans.save3();
																				}
																			}	
																		},
																		{
																			text:'',
																			icon:'/images/icon/new_file.ico',
																			listeners:{
																				click:function(obj,e){
																					gestiontrans.nuevo();
																				}
																			}
																		},
																		{
																			text:'',
																			icon:'/images/icon/get_back.png',
																			listeners:{
																				click:function(obj, e){
																				}

																			}
																		}
																],
																items:[
																		{	
																			xtype:'form',
																			title:'Programación Frecuente',
																			hidden:true,
																			id:gestiontrans.id+'-form-frecuente',
																			layout:'column',
																			padding:'10 15 10 10',
																			items:[
																					{
																						xtype:'fieldset',
																						margin:'15 0 0 0',
																						columnWidth:1,
																						title:'Vigencia de la Programación',
																						layout:'column',
																						items:[
																								{
																									xtype:'datefield',
																									id:gestiontrans.id+'-f-fecha-inicio',
																									allowBlank:false,
																									margin:'0 0 5 0',
																									fieldLabel:'Del',
																									columnWidth:0.5,
																									labelWidth:20,
																									value:new Date()

																								},
																								//{xtype:'tbspacer',columnWidth:0.1,width:30},
																								{
																									xtype:'datefield',
																									id:gestiontrans.id+'-f-fecha-fin',
																									allowBlank:false,
																									margin:'0 0 5 0',
																									columnWidth:0.5,
																									fieldLabel:'Al',
																									labelWidth:20,
																									value:new Date()
																								},
																						]
																					},
																					{
																						xtype:'label',
																						margin:'15 0 0 0',
																						columnWidth:1,
																						text:'* Programaciones Existentes:'
																					},
																					{
																						xtype:'grid',
																						id:gestiontrans.id+'-grid_frecuente',
																						height:200,
																						columnWidth:1,
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
																									{name:'hor_maxima',type:'string'}
																							],
																							proxy:{
																								type:'ajax',
																								url:gestiontrans.url+'scm_scm_dispatcher_horarios/',
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
																								obj.getStore().load({
																									params:{},
																								});
																							}
																						}
																					},
																					{
																						xtype:'fieldset',
																						layout:'column',
																						title:'Hora Programada',
																						columnWidth:1,
																						items:[
																								{
																									xtype:'textfield',
																									id:gestiontrans.id+'-f-hora-min',
																									allowBlank:false,
																									margin:'0 0 5 0',
																									columnWidth:0.5,
																									fieldLabel:'Hora Mínima',
																									plugins:  [new ueInputTextMask('99:99')],
																									inputAttrTpl:" data-qtip='Ingrese Hora y Minuto (15:15) Formato de 24 Horas'",
																									labelWidth:80,
																								},
																								{
																									xtype:'textfield',
																									id:gestiontrans.id+'-f-hora-max',
																									allowBlank:false,
																									margin:'0 0 5 0',
																									columnWidth:0.5,
																									fieldLabel:'Hora Maxima',
																									plugins:  [new ueInputTextMask('99:99')],
																									inputAttrTpl:" data-qtip='Ingrese Hora y Minuto (15:15) Formato de 24 Horas'",
																									labelWidth:80,
																								},
																						]
																					},
																					
																					{
																						xtype:'fieldset',
																						columnWidth:1,
																						title:'Dia de la Semana a Recolectar',
																						layout:'column',
																						items:[
																								{
																									xtype: 'checkboxgroup',
																									id:gestiontrans.id+'-f-chk-dia-semana',
																									columnWidth:1,
																									columns: 7,
																									vertical: true,
																									items:[
																											{boxLabel: 'Lunes',name:gestiontrans.id+'_semana',id:gestiontrans.id+'-lunes',inputValue: '1'},
																											{boxLabel: 'Martes',name:gestiontrans.id+'_semana',id:gestiontrans.id+'-martes',inputValue: '2'},
																											{boxLabel: 'Miercoles',name:gestiontrans.id+'_semana',id:gestiontrans.id+'-miercoles',inputValue: '3'},
																											{boxLabel: 'Jueves',name:gestiontrans.id+'_semana',id:gestiontrans.id+'-jueves',inputValue: '4'},
																											{boxLabel: 'Viernes',name:gestiontrans.id+'_semana',id:gestiontrans.id+'-viernes',inputValue: '5'},
																											{boxLabel: 'Sabado',name:gestiontrans.id+'_semana',id:gestiontrans.id+'-sabado',inputValue: '6'},
																											{boxLabel: 'Domingo',name:gestiontrans.id+'_semana',id:gestiontrans.id+'-domingo',inputValue: '7'}
																									]
																								}
																						]
																					}
																			]
																		},
																		{
																			xtype:'form',
																			title:'Programación Diaria',
																			id:gestiontrans.id+'form-diaria',
																			layout:'column',
																			padding:'10 15 10 10',
																			items:[
																					{
																						xtype:'fieldset',
																						margin:'15 0 0 0',
																						columnWidth:1,
																						title:'Fecha a Recolectar',
																						items:[
																								{
																									xtype:'datefield',
																									allowBlank:false,
																									id:gestiontrans.id+'-d-fecha-inicio',
																									margin:'0 0 5 0',
																									columnWidth:1,
																									fieldLabel:'Fecha Programada',
																									labelWidth:120,
																									value:new Date()
																								}
																						]
																					},
																					{
																						xtype:'label',
																						columnWidth:1,
																						text:'* Recolecciones Programadas',
																						margin:'15 0 0 0'
																					},
																					{
																						xtype:'grid',
																						id:gestiontrans.id+'-grid_diario',
																						height:200,
																						columnWidth:1,
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
																									{name:'hor_maxima',type:'string'}
																							],
																							proxy:{
																								type:'ajax',
																								url:gestiontrans.url+'scm_scm_dispatcher_horarios/',
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
																								obj.getStore().load({
																									params:{},
																								});
																							}
																						}
																					},
																					{
																						xtype:'fieldset',
																						layout:'column',
																						title:'Hora Programada',
																						columnWidth:1,
																						items:[
																								{
																									xtype:'textfield',
																									id:gestiontrans.id+'-d-hora-min',
																									allowBlank:false,
																									margin:'0 0 5 0',
																									columnWidth:0.5,
																									fieldLabel:'Hora Recolectar',
																									plugins:  [new ueInputTextMask('99:99')],
																									inputAttrTpl:" data-qtip='Ingrese Hora y Minuto (15:15) Formato de 24 Horas'",
																									labelWidth:100,
																								},
																								{
																									xtype:'textfield',
																									id:gestiontrans.id+'-d-hora-max',
																									allowBlank:false,
																									margin:'0 0 5 0',
																									columnWidth:0.5,
																									fieldLabel:'Hora Maxima',
																									plugins:  [new ueInputTextMask('99:99')],
																									inputAttrTpl:" data-qtip='Ingrese Hora y Minuto (15:15) Formato de 24 Horas'",
																									labelWidth:80,
																								}
																						]
																					},
																					{
																						xtype:'label',
																						columnWidth:1,
																						text:'* Rango Horario de 60 Min. Máximo'
																					}
																			]
																		}
																]
															},
														]
													}
											]

										},
										{
											region:'center',
											layout:'fit',
											id:gestiontrans.id+'cont_map',
											html:'<div id="'+gestiontrans.id+'-map" class="ue-map-canvas"></div>'
										},
										{
											region:'south',
											id:gestiontrans.id+'south',
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
					id:gestiontrans.id+'-tab',
					border:false,
					autoScroll:true,
					closable:true,
					layout:{
						type:'fit'
					},
					autoDestroy: true,
					items:[
						panel
					],
					listeners:{
						beforerender: function(obj, opts){
	                        global.state_item_menu(gestiontrans.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,gestiontrans.id_menu);
	                        gestiontrans.setMap();
	                      
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(gestiontrans.id_menu, false);
	                    },
	                    boxready:function(obj, width, height, eOpts ){
	                    	//gestiontrans.load_map();
	                    	Ext.getCmp(gestiontrans.id+'-center-west').setVisible(false);
	                    	Ext.getCmp(gestiontrans.id+'-secuencia-ruta').setVisible(false);
	                    	Ext.getCmp(gestiontrans.id+'west').setVisible(false);
							Ext.getCmp(gestiontrans.id+'south').setVisible(false);
	                    }

					}
				}).show();
			},
			setMap:function(){
				var directionsService = new google.maps.DirectionsService();
		        
		        var rendererOptions = {
					  draggable: true,
					 // suppressMarkers: true
				};
		        gestiontrans.varsmapa.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
		        gestiontrans.varsmapa.directionsService = new google.maps.DirectionsService();
		        var argentina = new google.maps.LatLng(-12.0473179,-77.0824867);
		        var mapOptions = {
		            zoom:12,
		            center: argentina,
		            //mapTypeId: google.maps.MapTypeId.ROADMAP
		        };
		        gestiontrans.map = new google.maps.Map(document.getElementById(gestiontrans.id+'-map'), mapOptions);
		        gestiontrans.varsmapa.directionsDisplay.setMap(gestiontrans.map);
		        

		        var homeControlDiv = document.createElement('div');
		        var homeControl = new HomeControl(homeControlDiv, gestiontrans.map, argentina);
		        homeControlDiv.index = 1;
		        gestiontrans.map.controls[google.maps.ControlPosition.TOP_CENTER].push(homeControlDiv);

		        var hdiv = document.createElement('div');
		        var hcontro = new gestiontrans.HHomeControl(hdiv,gestiontrans.map);
		        hdiv.index = 1;
		        gestiontrans.map.controls[google.maps.ControlPosition.TOP_LEFT].push(hdiv);

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
			  controlText.innerHTML = '<input type="checkbox" id="trafic" name="trafic" onClick="gestiontrans.trafic();"> <b>Ver Trafico</b>';
			  controlUI.appendChild(controlText);
			},
			trafic:function(){
				 if (document.getElementById("trafic").checked){
				 	//console.log(document.getElementById("trafic").checked);
				 	 gestiontrans.varsmapa.trafficLayer.setMap(gestiontrans.map);
				 }else{
				 	//console.log(document.getElementById("trafic").checked);
				 	gestiontrans.varsmapa.trafficLayer.setMap(null);
				 }

			},
			/*setMap: function(p){
				var rendererOptions = {
					 // draggable: false,
					  suppressMarkers: true
				};
				gestiontrans.varsmapa.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
				gestiontrans.varsmapa.directionsService = new google.maps.DirectionsService();
				var mapOptions = {
		            zoom:8,
		            center: new google.maps.LatLng(p.lat, p.lon)
		        };
		        gestiontrans.map = new google.maps.Map(document.getElementById(gestiontrans.id+'-map'), mapOptions);
		        var peru = new google.maps.LatLng(-11.782413062516948, -76.79493715625);
		        var homeControlDiv = document.createElement('div');
		        var homeControl = new HomeControl(homeControlDiv, gestiontrans.map, peru);
		        homeControlDiv.index = 1;
		        gestiontrans.map.controls[google.maps.ControlPosition.TOP_CENTER].push(homeControlDiv);

		        gestiontrans.varsmapa.directionsDisplay.setMap(gestiontrans.map);
				

			},*/
			/*marker_dispatcher_panel:function(map,image,coordenadas,gui_srec_id,tipo){

					var contentString = '<div id="content"  style="width:255px;">'+
					      //string +
					      '</div>';
					var infowindow = new google.maps.InfoWindow({
					      content: contentString,
					      maxWidth: 255
					});      
					Ext.getCmp(gestiontrans.id+'-destino').marker = new google.maps.Marker({
					      position: coordenadas,
					      map: Ext.getCmp(gestiontrans.id+'-destino').map,
					      title: '',
					      gui_srec_id:gui_srec_id,
					      tipo:tipo
				
					});
			
				    google.maps.event.addListener(marker, 'click', function() {
		
				        console.log(marker.gui_srec_id);
				        console.log(marker.tipo);
				        
				        var rec = Ext.getCmp(gestiontrans.id+'-recol-ruta').getSelectionModel().getSelection();
				        if (rec != '') {
				        	var placa = rec[0].data.placa;
					        var id_unidad =rec[0].data.id_unidad;
					        
					        global.Msg({
									msg:'gui_srec_id:'+marker.gui_srec_id+'</br>Tipo:'+marker.tipo+'</br>Placa:'+placa+'</br>id_unidad:'+id_unidad,
									icon:1,
									buttosn:1,
									fn:function(btn){
									}
								});	
				        }else{
				        	alert('Debes selecionar una unidad');
				        }
				        
				        
				    });
			},*/
			/*select_dispatcher_panel:function(vp_agencia,vp_fecha){
				Ext.getCmp(gestiontrans.id+'-destino').reset_maps();
				//var grid =Ext.getCmp(gestiontrans.id+'-recol-ruta');
				var vp_agencia = Ext.getCmp(gestiontrans.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(gestiontrans.id+'-fecha').getRawValue();

				var mask = new Ext.LoadMask(Ext.getCmp(inicio.id+'-tabContent'),{
					msg:'Consultando GPS'
				});
				Ext.Ajax.request({
					url:gestiontrans.url+'scm_scm_dispatcher_panel/',
					params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha},
					success:function(response,options){
						var res = Ext.decode(response.responseText).data;
						Ext.each(res, function(obj,index){
							//console.log(obj.dir_px);
							//console.log(obj.dir_py);
							Ext.getCmp(gestiontrans.id+'-destino').setMap({zoon: 4, lat: obj.dir_px, lon: obj.dir_py});
							var coordenadas = new google.maps.LatLng(obj.dir_px,obj.dir_py);
							gestiontrans.marker_dispatcher_panel(gestiontrans.map,'',coordenadas,obj.gui_srec_id,obj.tipo);
						});
					}
				});
			},*/
			buscar:function(){
				Ext.getCmp(gestiontrans.id+'-secuencia-ruta').setVisible(false);
				Ext.getCmp(gestiontrans.id+'-panel-porgeo').setVisible(true);
				gestiontrans.setMap();
				var grid =Ext.getCmp(gestiontrans.id+'-recol-ruta');
				var vp_agencia = Ext.getCmp(gestiontrans.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(gestiontrans.id+'-fecha').getRawValue();
				grid.getStore().load({
					params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha}
				});
				gestiontrans.por_georeferenciar();
			},
			asignacion_ruta_buscar:function(){
				var grid =Ext.getCmp(gestiontrans.id+'-unidades-asignacion-ruta');
				var vp_agencia = Ext.getCmp(gestiontrans.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(gestiontrans.id+'-fecha').getRawValue();
				grid.getStore().load({
					params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha},
					callback:function(){
						var value = Ext.getCmp(gestiontrans.id+'-origen').getUnidad();
						//gestiontrans.time_unidad();
						if (grid.getStore().getCount()>0){
							for(var i = 0; i < grid.getStore().getCount(); ++i){
							   var rec = grid.getStore().getAt(i);
							   if (rec.get('id_unidad')== value.id_unidad ){
							   		grid.getSelectionModel().select(i, true);
							   }
							}
						}

					}
				});
				//grid.getSelectionModel().select(0);
				//gestiontrans.unidad_actual();
			},
			time_unidad:function(){
				//falta 
				var service = new google.maps.DistanceMatrixService();
				var grid =Ext.getCmp(gestiontrans.id+'-unidades-asignacion-ruta');
				var store = grid.getStore();
				var value = Ext.getCmp(gestiontrans.id+'-origen').getUnidad();
				var destino = new google.maps.LatLng(value.age_x,value.age_y);
				var log = 0;
				if (grid.getStore().getCount()>0){
					for(var i = 0; i < grid.getStore().getCount(); ++i){
						var rec = grid.getStore().getAt(i);
						if (rec.get('pos_px') != ''){
							var origen = new google.maps.LatLng(rec.get('pos_px'),rec.get('pos_py'));
							var request = {
		                       origins: [origen],
		                       destinations: [destino],
		                       travelMode: google.maps.TravelMode.DRIVING,
		                       unitSystem: google.maps.UnitSystem.METRIC,
		                       avoidHighways: false,
		                       avoidTolls: false
			                }   
			                global.sleep(300);
							service.getDistanceMatrix(request, function(response, status){
								if (status == google.maps.DistanceMatrixStatus.OK){
									 if (response.rows[0].elements[0].status=='OK'){
									 	store.each(function(record, idx){
									 		if (parseInt(log) == parseInt(idx)){
									 			//console.log(response.rows[0].elements[0].duration.text);
									 			/*record.set('time', response.rows[0].elements[0].duration.text);
									 			record.set('distance', response.rows[0].elements[0].distance.text);*/

									 			record.set('time', 'aa');
									 			record.set('distance', 'bb');
									 			record.commit();
                								grid.getView().refresh(); 

									 		}
									 	});
									 }
								}
							});
						}	


					}
				}
			},
			unidad_actual:function(){
				//console.log('todas las unidades');
				var vp_agencia = Ext.getCmp(gestiontrans.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(gestiontrans.id+'-fecha').getRawValue();
				Ext.Ajax.request({
					url:gestiontrans.url+'scm_scm_dispatcher_unidades/',
					params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha},
					success:function(response,options){
						var res = Ext.decode(response.responseText).data;
						Ext.each(res, function(obj,index){
							if (obj.pos_px != ''){
								//Ext.getCmp(gestiontrans.id+'-origen').pinta_unidad_actual(parseFloat(obj.pos_px),parseFloat(obj.pos_py),'/images/icon/moto32.png',obj.placa);		
							}
						});
					}
				});
			},
			confirma_unidad:function(record){
				var siguiente = record.get('tot_ld');
				var placa = record.get('placa')
				var per_manager = record.get('per_manager');
				var per_ayudante =record.get('per_ayudante');
				var vp_man_id = record.get('man_id');
				var vp_und_id = record.get('id_unidad');
				siguiente=siguiente+1;
				gestiontrans.save.vp_man_id=vp_man_id;
				gestiontrans.save.vp_per_m=per_manager;
				gestiontrans.save.vp_per_id_h=per_ayudante;
				gestiontrans.save.vp_und_id=vp_und_id;
				//Ext.getCmp(gestiontrans.id+'siguiente-ejecu').update('Secuencia Ejecución:'+siguiente);
				var objeto = Ext.get(gestiontrans.id+'siguiente-ejecu');
				gestiontrans.tplInciPanel.overwrite(objeto,{cnt:siguiente});

				Ext.getCmp(gestiontrans.id+'-unidad').setValue(placa);
				//Ext.getCmp(gestiontrans.id+'-u-fecha')
				//console.log(per_manager);
				if (per_manager > 0 ){
					Ext.getCmp(gestiontrans.id+'-per_manager').setReadOnly(true);
				}else{
					Ext.getCmp(gestiontrans.id+'-per_manager').setReadOnly(false);
				}

				if (per_ayudante > 0){
					Ext.getCmp(gestiontrans.id+'-per_ayudante').setReadOnly(true);
				}else{
					Ext.getCmp(gestiontrans.id+'-per_ayudante').setReadOnly(false);
				}

				Ext.getCmp(gestiontrans.id+'-per_manager').setValue(parseInt(per_manager));
				Ext.getCmp(gestiontrans.id+'-per_ayudante').setValue(parseInt(per_ayudante));


			},
			save_unidad:function(){
				
				
				var form = Ext.getCmp(gestiontrans.id+'save-unidad').getForm();
				var agencia = Ext.getCmp(gestiontrans.id+'-agencia').getValue();
				var vp_man_id = gestiontrans.save.vp_man_id;
				var vp_per_m = Ext.getCmp(gestiontrans.id+'-per_manager').getValue();//gestiontrans.save.vp_per_m;
				var vp_per_id_h = Ext.getCmp(gestiontrans.id+'-per_ayudante').getValue();//gestiontrans.save.vp_per_id_h;
				var vp_und_id = gestiontrans.save.vp_und_id;
				var vp_srec_id = gestiontrans.save.vp_srec_id
				var val_des = Ext.getCmp(gestiontrans.id+'-destino').valida_coordenada();
				//var val_orgen =Ext.getCmp(gestiontrans.id+'-origen').valida_coordenada();
			
				if (form.isValid() && val_des){
					Ext.Ajax.request({
						url:gestiontrans.url+'scm_scm_dispatcher_add_ruta/',
						params:
						{	vp_man_id:vp_man_id,
							vp_srec_id:vp_srec_id,
							vp_per_m:vp_per_m,
							vp_per_id_h:vp_per_id_h,
							vp_und_id:vp_und_id,
							vp_agencia:agencia
						},
						success:function(response,options){
							var res = Ext.decode(response.responseText);
							if (parseInt(res.data[0].error_sql)==1){
								global.Msg({
									msg:res.data[0].error_info,
									icon:1,
									buttosn:1,
									fn:function(btn){
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
				var grid = Ext.getCmp(gestiontrans.id+'-por-georeferenciar');
				var vp_agencia = Ext.getCmp(gestiontrans.id+'-agencia').getValue();
				var vp_fecha = Ext.getCmp(gestiontrans.id+'-fecha').getRawValue();

				grid.getStore().load({
					params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha}
				});

				//Ext.getCmp(gestiontrans.id+'-destino').select_dispatcher_panel(vp_agencia,vp_fecha);
				gestiontrans.unidad_actual();

			},
			toggleButton:function(vi){
				var obj = ['-btn-1', '-btn-2', '-btn-3'];
				gestiontrans.actionToggle(vi);
				Ext.Object.each(obj, function(index, value){
	                if (index == vi){
		                Ext.getCmp(gestiontrans.id + value).toggle(true);
		            }else{
		            	Ext.getCmp(gestiontrans.id + value).toggle(false);
		            }   
	            });
			},
			actionToggle:function(vi){
				if (vi == 0){
					Ext.getCmp(gestiontrans.id+'west').setVisible(true);
					Ext.getCmp(gestiontrans.id+'south').setVisible(true);
					Ext.getCmp(gestiontrans.id+'-center-west').setVisible(false);
					gestiontrans.setMap();
				}else if (vi == 1){
					Ext.getCmp(gestiontrans.id+'west').setVisible(false);
					Ext.getCmp(gestiontrans.id+'south').setVisible(false);
					Ext.getCmp(gestiontrans.id+'-center-west').setVisible(true);
					gestiontrans.setMap();
				}else if (vi == 2){
					Ext.getCmp(gestiontrans.id+'-center-west').setVisible(false);
					gestiontrans.setMap();
				}
			},
			get_georeferencias:function(record){
				Ext.getCmp(gestiontrans.id+'west').setVisible(false);
				Ext.getCmp(gestiontrans.id+'-center-west').setVisible(true);
						
			},
			order_ruta:function(){
				//alert('estamos adentro');
				var grid = Ext.getCmp(gestiontrans.id+'-ruta');
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
				gestiontrans.arrayMaker = [];
				gestiontrans.setMap();

				var grid = Ext.getCmp(gestiontrans.id+'-ruta');
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
						tipo = parseInt(rec.get('tipo'));
						ruta = parseInt(rec.get('ruta'));
						id_doc = parseInt(rec.get('id_doc'));
						coordenadas = new google.maps.LatLng(dir_px,dir_py);
						gestiontrans.secuencia_ruta_marker(coordenadas,tipo,id_doc,ruta,i);
					}	
				}
			},
			secuencia_ruta_marker:function(LatLng,tipo,id_doc,ruta,i){
				var me = this;       
				i = i+1; 
				    //icon = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=R|4CBEFF|000001'
		        if (tipo == 2){
		            //icon = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld='+ruta+'|4CBEFF|000001'
		            var icon = '/images/icon/marker2.png';
		        }else if(tipo == 3){
		            //icon = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld='+ruta+'|FF9006|000001'
		            var icon = '/images/icon/marker1.png';
		        }

		        var contentString = '<div id="content"  style="width:15px;">'+
		              ruta +
		              '</div>';
		        var infowindow = new google.maps.InfoWindow({
		              content: contentString,
		              maxWidth: 15
		        });      
		        var marker = new google.maps.Marker({
		              position: LatLng,
		              map: gestiontrans.map,
		              animation: google.maps.Animation.DROP,
		              title: '',
		              icon:icon,
		              draggable:true,
		              ruta:ruta
		        });
		        google.maps.event.addListener(marker, 'click', function() {
		        	 infowindow.open(gestiontrans.map,marker);
		        });

		        gestiontrans.arrayMaker.push(marker);
			},
			findMaker:function(rec){
		        gestiontrans.setMap();
		        for (var i = 0; i < gestiontrans.arrayMaker.length; i++) {
		            if(parseInt(gestiontrans.arrayMaker[i].ruta) == rec){
		                gestiontrans.arrayMaker[i].setMap(gestiontrans.map);  
		                gestiontrans.arrayMaker[i].setAnimation(google.maps.Animation.BOUNCE);
		            }else{
		                gestiontrans.arrayMaker[i].setMap(gestiontrans.map);  
		            }
		        }
		        gestiontrans.waypoints_ruta_unidad();
		        //console.log(me.arrayMaker[0].gui_srec_id); 
		    },
		    pinta_unidad_actual:function(x,y,icono){

		        var unidad = new google.maps.LatLng(x,y);
		        var marker = new google.maps.Marker({
		                position: unidad,
		                map: gestiontrans.map,
		                title: '',
		                icon:icono
		        });
		    },
		    waypoints_ruta_unidad:function(){
		    	var grid = Ext.getCmp(gestiontrans.id+'-ruta');
		    	var o_dir_px;
		    	var o_dir_py;
		    	var d_dir_px;
		    	var d_dir_py;
		    	var location =[];
		    	var coordenada;
		    	Ext.Ajax.request({
		    		url:gestiontrans.url+'scm_scm_dispatcher_unidad_gps/',
		    		params:{vp_unidad:gestiontrans.id_unidad},
		    		success:function(response,options){
		    			var res = Ext.decode(response.responseText).data[0];
		    			if (res.dir_px==''){
		    				alert('Moto sin coordenada');
		    			}


		    			o_dir_px = parseFloat(res.dir_px);
		    			o_dir_py = parseFloat(res.dir_py);

		    			gestiontrans.pinta_unidad_actual(o_dir_px,o_dir_py,'/images/icon/moto32.png');

				    	if (grid.getStore().getCount() > 0){
				    		for(var i = 0; i < grid.getStore().getCount(); ++i){
				    			var rec = grid.getStore().getAt(i);
				    			//if (i == 1){
				    			//	o_dir_px = parseFloat(rec.get('dir_px'));
								//	o_dir_py = parseFloat(rec.get('dir_py'));	
				    			//}
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

				    	/*console.log(location);
				    	console.log(o_dir_px);
				    	console.log(o_dir_py);
				    	console.log(d_dir_px);
				    	console.log(d_dir_py);*/

				        var OriginLatlng = new google.maps.LatLng(o_dir_px,o_dir_py);
				        var destino = new google.maps.LatLng(d_dir_px,d_dir_py);
				        var request = {
				            origin:OriginLatlng,
				            destination:destino,
				            waypoints:location,//{location: agencia},{location: '-13.918217, -74.330162'}  
				            optimizeWaypoints: true,
				            travelMode: google.maps.TravelMode.DRIVING,
				            provideRouteAlternatives: true
				        };
				         gestiontrans.varsmapa.directionsService.route(request,function(response,status){
				            if (status == google.maps.DirectionsStatus.OK){
				                //console.log(response);
				                   //directionsDisplay.setDirections(response);
				                 //gestiontrans.varsmapa.directionsDisplay.setDirections(response);
				                
				            }else{
				                alert(status);
				            }
				        });
		    		}

		    	});

		    },
			origen_destino:function(record){
				var o_dir_px =record.get('origen_px');
				var o_dir_py =record.get('origen_py');
				var d_dir_px =record.get('destino_px');
				var d_dir_py =record.get('destino_py');
				var OriginLatlng = new google.maps.LatLng(o_dir_px,o_dir_py);
		        var destino = new google.maps.LatLng(d_dir_px,d_dir_py);
		        var request = {
		            origin:OriginLatlng,
		            destination:destino,
		          //  waypoints:[],//{location: agencia},{location: '-13.918217, -74.330162'}  
		            optimizeWaypoints: true,
		            travelMode: google.maps.TravelMode.DRIVING,
		            provideRouteAlternatives: true
		        };
		        gestiontrans.varsmapa.directionsService.route(request,function(response,status){
		            if (status == google.maps.DirectionsStatus.OK){
		                //console.log(response);
		                   //directionsDisplay.setDirections(response);
		               // gestiontrans.varsmapa.directionsDisplay.setDirections(response);
		                
		            }else{
		                alert('no hay ruta');
		                status;
		            }
		        });

			},
			nuevo:function(){
				Ext.getCmp(gestiontrans.id+'-destino').destroyVars();
				Ext.getCmp(gestiontrans.id+'-destino').resetTipo();
				Ext.getCmp(gestiontrans.id+'-origen').destroyVars();
				Ext.getCmp(gestiontrans.id+'-origen').resetTipo();
				Ext.getCmp(gestiontrans.id+'-shipper').setValue('');
				Ext.getCmp(gestiontrans.id+'-rbtn-group-linea').setReadOnly(false);
				Ext.getCmp(gestiontrans.id+'-shipper').setReadOnly(false);
				Ext.getCmp(gestiontrans.id+'-rbtn-group-linea').setValue({'gestiontrans-rbtn':'3'});
				//Ext.getCmp(gestiontrans.id+'-panel-origen').setDisabled(true);
				//Ext.getCmp(gestiontrans.id+'-panel-destino').setDisabled(true);
				//Ext.getCmp(gestiontrans.id+'-panel-origen').disable();
				//Ext.getCmp(gestiontrans.id+'-panel-destino').disable();
			},
			save1:function(){
				var x_linea = Ext.getCmp(gestiontrans.id+'-rbtn-group-linea').getValue();
				var linea = parseInt(x_linea['gestiontrans-rbtn']);

				var xejecutar = Ext.getCmp(gestiontrans.id+'-rbtn-group-ejecutar').getValue();
				var ejecutar = parseInt(xejecutar['gestiontrans-rbtn-ejecutar']);

				var shipper = Ext.getCmp(gestiontrans.id+'-shipper').getValue();
				var tip_pqt = Ext.getCmp(gestiontrans.id+'-tip_pqt').getValue();
				var cnt = Ext.getCmp(gestiontrans.id+'-cantidad').getValue();
				var peso = Ext.getCmp(gestiontrans.id+'-peso').getValue();
				var alto = Ext.getCmp(gestiontrans.id+'-alto').getValue();
				var ancho = Ext.getCmp(gestiontrans.id+'-ancho').getValue();
				var largo = Ext.getCmp(gestiontrans.id+'-largo').getValue();
				var descri = Ext.getCmp(gestiontrans.id+'-descri').getValue();
				var obs = Ext.getCmp(gestiontrans.id+'-observ').getValue();
				var tipo_srec =Ext.getCmp(gestiontrans.id+'-recojo-frecuente').getValue()== true ?1:0;
				var form = Ext.getCmp(gestiontrans.id+'-form1').getForm();
				
				var vp_srec_id = gestiontrans.select.vp_srec_id;
				//console.log(form.isValid());
				if (form.isValid()){
					Ext.Ajax.request({
						url:gestiontrans.url+'scm_scm_dispatcher_add_upd_orden/',
						params:{vp_srec_id:vp_srec_id,vp_shi_cod:shipper,vp_linea:linea,vp_tipo_srec:tipo_srec,
							vp_tipo_pqt:tip_pqt,vp_cantidad:cnt,vp_peso:peso,vp_alto:alto,vp_ancho:ancho,vp_largo:largo,
							vp_descri:descri,vp_observ:obs,vp_sentido_ruta:ejecutar},
						success:function(response,options){
							var res = Ext.decode(response.responseText);
							//console.log(res);
							if (parseInt(res.data[0].error_sql)==1){
								global.Msg({
									msg:res.data[0].error_info,
									icon:1,
									buttons:1,
									fn:function(btn){
										//Para poder usar y grabar las demas pestañas
										gestiontrans.select.vp_srec_id = res.data[0].id_recojo
										Ext.getCmp(gestiontrans.id+'-panel-origen').setDisabled(false);
										Ext.getCmp(gestiontrans.id+'-panel-destino').setDisabled(false);
										Ext.getCmp(gestiontrans.id+'-save2').enable();
										Ext.getCmp(gestiontrans.id+'-acoordion-recojo-entrega').setCollapsed(true);
										//console.log(res.data[0].id_recojo);
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
				}else{
					global.Msg({
						msg:'Debes Completar los datos para poder Graba',
						icon:0,
						buttons:1,
						fn:function(btn){
						}
					});
				}
			},
			save2:function(){

				var prov_codigo = Ext.getCmp(gestiontrans.id+'-agencia').getValue();
				var x_linea = Ext.getCmp(gestiontrans.id+'-rbtn-group-linea').getValue();
				var linea = parseInt(x_linea['gestiontrans-rbtn']);
				var shipper = Ext.getCmp(gestiontrans.id+'-shipper').getValue();
				
				var o = Ext.getCmp(gestiontrans.id+'-origen').getValues();
				//var os = Ext.getCmp(gestiontrans.id+'-origen').getNew_prog();

				var d = Ext.getCmp(gestiontrans.id+'-destino').getValues();
				//var ds = Ext.getCmp(gestiontrans.id+'-destino').getNew_prog();
				console.log(o);
				console.log(d);

				//********************Variables del Origen************************************/
				var os_rbtn = parseInt(o[0].new_prog.rbtn);
				var os_id_age = o[0].new_prog.id_agencia;
				var o_ciu_id = o[0].ciu_id;
				//console.log(o_ciu_id);
				var os_x = o[0].new_prog.x;
				var os_y = o[0].new_prog.y;
				var os_id_area = o[0].new_prog.id_area;
				var os_id_contacto = o[0].new_prog.id_contacto;
				var o_direccion = o[0].direccion;
				var o_dir_numvia = o[0].via;
				var o_id_geo = o[0].id_puerta;
				var o_dir_id = parseInt(o[0].id_direccion);
				var o_id_mza = o[0].id_mza;
				var o_manzana = o[0].manzana;
				var o_id_via = o[0].id_via;
				var o_referencia = o[0].referencia;
				var o_id_urb = o[0].id_urb;
				var o_urb = o[0].urb;
				var o_lote = o[0].lote;
				if (os_rbtn == 3){
					os_x = o[0].coordenadas[0].lat;
					os_y = o[0].coordenadas[0].lon;
				}
				var o_vp_id_origen;
				//1 prov_codigo 2 id_age 3 0
				if (os_rbtn ==1){
					o_vp_id_origen=prov_codigo;
				}else if(os_rbtn==2){
					o_vp_id_origen=os_id_age;
				}else if(os_rbtn==3){
					o_vp_id_origen=0;
				}

				//*********************Variables del Destino************************************/
		        var ds_rbtn = parseInt(d[0].new_prog.rbtn);
				var ds_id_age = d[0].new_prog.id_agencia;
				var d_ciu_id = d[0].ciu_id;
				var ds_x = d[0].new_prog.x;
				var ds_y = d[0].new_prog.y;
				var ds_id_area = d[0].new_prog.id_area;
				var ds_id_contacto = d[0].new_prog.id_contacto;

				var d_direccion= d[0].direccion;
				var d_dir_numvia = d[0].via;
				var d_id_geo = d[0].id_puerta;
				var d_dir_id = parseInt(d[0].id_direccion);
				var d_id_mza = o[0].id_mza;
				var d_manzana = o[0].manzana;
				var d_id_via = o[0].id_via;
				var d_referencia = o[0].referencia;
				var d_id_urb = o[0].id_urb;
				var d_urb = o[0].urb;
				var d_lote = o[0].lote;
				if (ds_rbtn == 3){
					ds_x = d[0].coordenadas[0].lat; 
					ds_y = d[0].coordenadas[0].lon;
				}
				//console.log(ds_y);
				var d_vp_id_origen;
				if (ds_rbtn == 1){
					d_vp_id_origen=prov_codigo;
				}else if(ds_rbtn == 2){
					d_vp_id_origen = ds_id_age;
				}else if(ds_rbtn == 3){
					d_vp_id_origen = 0;
				}
				
				/*var verdad;
				if (os_rbtn == 1){
					if (o_vp_id_origen > 0){
						verdad=true;
					}else{
						verdad=false;
					}
				}else if (os_rbtn == 2){
					if (os_id_age >0){
						verdad=true;
					}else{
						verdad=false;
					}

				}
				console.log(o);
				console.log(verdad);*/
				//****************************** Parametro del grabar Direccion*******************/
				if (!o[0].isValido){
					global.Msg({
						msg:'Origen: </br>'+o[0].msg,
						icon:0,
						buttosn:1,
						fn:function(btn){
						}
					});
				}else if (!d[0].isValido){
					global.Msg({
						msg:'Destino: </br>'+d[0].msg,
						icon:0,
						buttosn:1,
						fn:function(btn){
						}
					});
				}else if (o[0].isValido && d[0].isValido){
					Ext.Ajax.request({
						url:gestiontrans.url+'scm_scm_dispatcher_add_upd_ruta/',
						params:{
							o_vp_srec_id:gestiontrans.select.vp_srec_id,
							o_vp_id_ruta:'1',//origen
					        o_vp_origen:os_rbtn,
					        o_vp_id_origen:o_vp_id_origen,//1 prov_codigo 2 id_age 3 0
					        o_vp_ciu_id:o_ciu_id,
					        o_vp_id_contacto:os_id_contacto,
					        o_vp_id_area:os_id_area,
					        o_vp_id_geo:o_id_geo,
					        o_vp_via_id:o_id_via,
					        o_vp_dir_calle:o_direccion,
					        o_vp_dir_numvia:o_dir_numvia,
					        o_vp_dir_refere:o_referencia,
					        o_vp_urb_id:o_id_urb,
					        o_vp_urb_nom:o_urb,
					        o_vp_mz_id:o_id_mza,
					        o_vp_mz_mom:o_manzana,
					        o_vp_num_lote:o_lote,
					        o_vp_num_int:0,
					        //o_vp_dir_id:o_dir_id,
					        o_vp_dir_px:os_x,
					        o_vp_dir_py:os_y,

					        d_vp_srec_id:gestiontrans.select.vp_srec_id,
							d_vp_id_ruta:'2',//destino
					        d_vp_origen:ds_rbtn,
					        d_vp_id_origen:d_vp_id_origen,//1 prov_codigo 2 id_age 3 0
					        d_vp_ciu_id:d_ciu_id,
					        d_vp_id_contacto:ds_id_contacto,
					        d_vp_id_area:ds_id_area,
					        d_vp_id_geo:d_id_geo,
					        d_vp_via_id:d_id_via,
					        d_vp_dir_calle:d_direccion,
					        d_vp_dir_numvia:d_dir_numvia,
					        d_vp_dir_refere:d_referencia,
					        d_vp_urb_id:d_id_urb,
					        d_vp_urb_nom:d_urb,
					        d_vp_mz_id:d_id_mza,
					        d_vp_mz_mom:o_manzana,
					        d_vp_num_lote:d_lote,
					        d_vp_num_int:0,
					        //o_vp_dir_id:o_dir_id,
					        d_vp_dir_px:ds_x,
					        d_vp_dir_py:ds_y,

						},
						success:function(response,options){
							var res = Ext.decode(response.responseText);
							//console.log(res);
							if (parseInt(res.data.error_sql)==1){
								global.Msg({
									msg:res.data.error_info,
									icon:1,
									buttosn:1,
									fn:function(btn){
										Ext.getCmp(gestiontrans.id+'-save3').enable();
										Ext.getCmp(gestiontrans.id+'-acoordion-confirmar-direccion').setCollapsed(true);
									}
								});
							}else{
								global.Msg({
									msg:res.data.error_info,
									icon:0,
									buttosn:1,
									fn:function(btn){
									}
								});
							}
						}
					});	
				}
				
			},
			save3:function(){
				var chk = Ext.getCmp(gestiontrans.id+'-recojo-frecuente').getValue();
				var vp_srec_id = gestiontrans.select.vp_srec_id
				var frecuente = Ext.getCmp(gestiontrans.id+'-form-frecuente').getForm();
				var diaria = Ext.getCmp(gestiontrans.id+'form-diaria').getForm();
				//*****************Frecuente*************************//
				var f_fecha_ini = Ext.getCmp(gestiontrans.id+'-f-fecha-inicio').getRawValue();
				var f_fecha_fin = Ext.getCmp(gestiontrans.id+'-f-fecha-fin').getRawValue();
				var f_hora_min = Ext.getCmp(gestiontrans.id+'-f-hora-min').getValue();
				var f_hora_fin = Ext.getCmp(gestiontrans.id+'-f-hora-max').getValue();
				var f_dia_semana = Ext.getCmp(gestiontrans.id+'-f-chk-dia-semana').getValue();
				//*******************Diario**************************//
				var d_fecha_ini = Ext.getCmp(gestiontrans.id+'-d-fecha-inicio').getRawValue();
				var d_hora_mini = Ext.getCmp(gestiontrans.id+'-d-hora-min').getRawValue();
				var d_hora_max  = Ext.getCmp(gestiontrans.id+'-d-hora-max').getValue();

				if (chk){
					var vp_fecini = f_fecha_ini;
					var vp_horini = f_hora_min;
					var vp_fecfin = f_fecha_fin;
					var vp_horfin = f_hora_fin;
					var vp_semana = f_dia_semana;
					var cnt_dia = global.isEmptyJSON(vp_semana) == false ?vp_semana['gestiontrans_semana'].length : 0;
					var string_semana='';

				}else{
					var vp_fecini = d_fecha_ini;
					var vp_horini = d_hora_mini;
					var vp_horfin = d_hora_max;
				}
				if (frecuente.isValid() || diaria.isValid()){
					if (chk && cnt_dia > 0){
						/********Concatena los dias de la semana****************/
						Ext.each(vp_semana['gestiontrans_semana'],function(obj,idx){
							string_semana = string_semana+obj.toString();
						});
						/*******************************************************/
						Ext.Ajax.request({
							url:gestiontrans.url+'scm_scm_dispatcher_upd_programacion/',
							params:{vp_srec_id:vp_srec_id,vp_fecini:vp_fecini,vp_horini:vp_horini,vp_fecfin:vp_fecfin,vp_horfin:vp_horfin,vp_semana:string_semana},
							success:function(response,options){
								var res = Ext.decode(response.responseText);
								//console.log(res);
								if (parseInt(res.data[0].error_sql)==1){
									global.Msg({
										msg:res.data[0].error_info,
										icon:1,
										buttosn:1,
										fn:function(btn){
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
					}else if(!chk){
						Ext.Ajax.request({
							url:gestiontrans.url+'scm_scm_dispatcher_upd_programacion/',
							params:{vp_srec_id:vp_srec_id,vp_fecini:vp_fecini,vp_horini:vp_horini,vp_fecfin:vp_fecfin,vp_horfin:vp_horfin,vp_semana:string_semana},
							success:function(response,options){
								var res = Ext.decode(response.responseText);
								if (parseInt(res.data[0].error_sql)==1){
									global.Msg({
										msg:res.data[0].error_info,
										icon:1,
										buttosn:1,
										fn:function(btn){
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
							msg:'Debe seleccionar un dia de la semana por lo menos',
							icon:0,
							buttosn:1,
							fn:function(btn){
							}
						});
					}
				}else{
					global.Msg({
						msg:'Debe completar los datos',
						icon:0,
						buttosn:1,
						fn:function(btn){
						}
					});
				}
				
			}
			
		}
		Ext.onReady(gestiontrans.init,gestiontrans);
	}else{
		tab.setActiveTab(gestiontrans.id+'-tab');
	}
</script>
