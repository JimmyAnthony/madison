<script type="text/javascript">
	var new_orden = {
		id:'new_orden',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/gtransporte/',
		linea:3,
		ejecutar:1,
		origen:3,
		destino:3,
		origen_x:null,
		origen_y:null,
		origen_age_x:null,
		origen_age_y:null,
		destino_age_x:null,
		destino_age_y:null,
		init:function(){
			var panel = Ext.create('Ext.form.Panel',{
				layout:'border',
				border:false,
				items:[
						{
							region:'north',
							border:false,
							layout:'fit',
							items:[
									
									{
										xtype:'fieldset',
										title:'Datos del Cliente',
										layout:'column',
										items:[
												{
													xtype:'radiogroup',
													columnWidth: 0.6,
													id:new_orden.id+'-rbtn-group-linea',
													fieldLabel:'Linea',
													columns:3,
													vertical:true,
													labelWidth:35,
													items:[
															{boxLabel:'Masivo',name:new_orden.id+'-rbtn',inputValue:'1',width:60},
															{boxLabel:'Valorados',name:new_orden.id+'-rbtn',inputValue:'2',width:80},
															{boxLabel:'Logistica',name:new_orden.id+'-rbtn',inputValue:'3',width:80,checked:true}
													],
													listeners:{
														change:function(obj, newValue, oldValue, eOpts){
															var op = parseInt(newValue[new_orden.id+'-rbtn']);
															new_orden.linea = op;
															Ext.getCmp(new_orden.id+'-shipper').store.load({
																params:{vp_linea:op},
																callback:function(){
																}
															});
														}
													}
												},
												/*{
													xtype:'radiogroup',
													columnWidth: 0.2,
													id:new_orden.id+'-rbtn-group-ejecutar',
													fieldLabel:'Ejecutar',
													columns:2,
													vertical:true,
													labelWidth:40,
													items:[
															{boxLabel:'Recojo',name:new_orden.id+'-rbtn-ejecutar',inputValue:'1', width:60,checked:true},
															{boxLabel:'Entrega',name:new_orden.id+'-rbtn-ejecutar',inputValue:'2', width:60}
													],
													listeners:{
														change:function(obj, newValue, oldValue, eOpts){
															var op = parseInt(newValue[new_orden.id+'-rbtn-ejecutar']);
															new_orden.ejecutar = op;
														}
													}
												},*/
												{
													xtype:'combo',
													columnWidth: 1,
													padding:'0 0 5 0',
													id:new_orden.id+'-shipper',
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
															url:new_orden.url+'get_usr_sis_shipper/',
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
															obj.getStore().load({
																params:{
																	vp_linea:3
																},
																/*callback:function(){
																	obj.setValue(0);
																}*/
															});
														},
														'select':function(obj,records,eOpts){
															new_orden.cabecera(true);
														}
													}
												},
												

										]
									},


							]
						},
						{
							region:'center',
							border:false,
							layout:'fit',
							defaults:{
								border:false
							},
							items:[
									{
										xtype:'tabpanel',
										id:new_orden.id+'-tabpanel',
										border:false,
										disabled:true,
										defaults:{
											border:false
										},
										sutoScroll:true,
										items:[
												{
													title:'Origen',
													id:new_orden.id+'-origen',
													items:[
															{
																xtype: 'fieldset',
																title: 'Datos del Origen',
																layout:'column',
																items:[
																		{
																			xtype:'radiogroup',
																			columnWidth: 0.6,
																			id:new_orden.id+'-rbtn-group-origen',
																			columns:3,
																			vertical:true,
																			labelWidth:40,
																			items:[
																					{boxLabel:'Agencia Urbano',name:new_orden.id+'-rbtn-origen',inputValue:'1', width:110},
																					{boxLabel:'Agencia Shipper',name:new_orden.id+'-rbtn-origen',inputValue:'2', width:110},
																					{boxLabel:'Otra Dirección',name:new_orden.id+'-rbtn-origen',inputValue:'3', width:100,checked:true},
																			],
																			listeners:{
																				change:function(obj, newValue, oldValue, eOpts){
																					var op = parseInt(newValue[new_orden.id+'-rbtn-origen']);
																					new_orden.origen = op;
																					var shipper = Ext.getCmp(new_orden.id+'-shipper').getValue();
																					Ext.getCmp(new_orden.id+'-origen-agencia-shipper').setValue('');
																					if (op==1 || op == 2){
																						if (op == 1){
																							Ext.getCmp(new_orden.id+'-origen-agencia-shipper').store.load({params:{va_shipper:6}});
																						}else if (op == 2 ){
																							Ext.getCmp(new_orden.id+'-origen-agencia-shipper').store.load({params:{va_shipper:shipper}});
																						}	
																						new_orden.cuerpo_origen(true);
																						Ext.getCmp(new_orden.id+'-origen-agencia-shipper').show(); 
																					}else{
																						new_orden.limpia_cuerpo_origen();
																						new_orden.cuerpo_origen(false);
																						Ext.getCmp(new_orden.id+'-origen-agencia-shipper').hide(); 
																					}
																				}
																			}
																		},
																		{
																			xtype:'combo',
																			id:new_orden.id+'-origen-agencia-shipper',
																			//hidden:true,
																			padding:'0 0 5 0',
																			columnWidth: 0.4,
																			fieldLabel:'Agencia',
																			labelWidth:50,
																			store: Ext.create('Ext.data.Store',{
																				fields:[
																						{name:'id_agencia',type:'int'},
																						{name:'agencia' ,type:'string'},
																						{name:'dir_calle' ,type:'string'},
																						{name:'ciu_id',type:'int'},
																						{name:'ciu_ubigeo',type:'string'},
																						{name:'dir_px',type:'auto'},
																						{name:'dir_py',type:'auto'},
																						               
																				],
																				proxy:{
																					type:'ajax',
																					url:new_orden.url+'getAgencia_shipper/',
																					reader:{
																						type:'json',
																						rootProperty:'data'
																					}
																				}
																			}),
																			queryMode:'local',
																			valueField:'id_agencia',
																			displayField:'agencia',
																			listConfig:{
																				minWidth:200
																			},
																			width:120,
																			forceSelection:true,
																			allowBlank:false,
																			emptyText:'[ Seleccione ]',
																			listeners:{
																				'select':function(obj,records,eOpts){
																					var ciu_ubigeo = records.get('ciu_ubigeo');
																					dpto = ciu_ubigeo.substring(0,2);
																					prov = ciu_ubigeo.substring(2,4);
		                															dist = ciu_ubigeo.substring(4,6);
		                															
		                															Ext.getCmp(new_orden.id+'-origen-departament').setValue(dpto);

		                															Ext.getCmp(new_orden.id+'-origen-provincia').store.load({
		                																params:{va_departamento:'2',va_provincia:dpto},
		                																callback:function(){
		                																	Ext.getCmp(new_orden.id+'-origen-provincia').setValue(prov);		
		                																}
		                															});
		                															
		                															Ext.getCmp(new_orden.id+'-origen-distrito').store.load({
		                																params:{va_departamento:'3',va_provincia:dpto,va_distrito:prov},
		                																callback:function(){
		                																	Ext.getCmp(new_orden.id+'-origen-distrito').setValue(dist);		
		                																}
		                															});
		                															Ext.getCmp(new_orden.id+'-origen-direccion').setValue(records.get('dir_calle'));
		                															Ext.getCmp(new_orden.id+'-origen-x').setValue(records.get('dir_px'));
		                															Ext.getCmp(new_orden.id+'-origen-y').setValue(records.get('dir_py'));

		                															new_orden.origen_age_x = records.get('dir_px');
		                															new_orden.origen_age_y = records.get('dir_py');
		                															new_orden.cuerpo_origen(true);
		                															new_orden.call_track(new_orden.origen_x+','+new_orden.origen_y,new_orden.destino_x+','+new_orden.destino_y,new_orden.origen_age_x+','+new_orden.origen_age_y,new_orden.destino_age_x+','+new_orden.destino_age_y);
		                															
																				}
																			}

																		}
																]
															},
															{
																xtype:'fieldset',
																title:'',
																layout:'column',
																padding:'5 0 0 0',
																defaults:{
																	padding:'0 5 5 5'
																},
																items:[

																		{
																			xtype:'combo',
																			columnWidth:0.4,
																			id:new_orden.id+'-origen-departament',
																			fieldLabel:'Departamento',
																			labelWidth:80,
																			store:Ext.create('Ext.data.Store',{
																				fields:[
																						{name: 'iddepto', type: 'string'},
						                                								{name: 'nom_nomdep', type: 'string'}
																				],
																				proxy:{
																					type:'ajax',
																					url:new_orden.url+'getComboDepartamentos/',
																					reader:{
																						type:'json',
																						rootProperty:'data'
																					}
																				}
																			}),
																			queryMode:'local',
																			valueField:'iddepto',
																			displayField:'nom_nomdep',
																			listConfig:{
																				minWidth:200
																			},
																			width:120,
																			forceSelection:true,
																			allowBlank:false,
																			emptyText:'[Seleccione]',
																			listeners:{
																				afterrender:function(obj){
																					obj.getStore().load({
																						params:{va_departamento:'1'},
																					});
																				},
																				'select':function(obj,records,eOpts){
																					Ext.getCmp(new_orden.id+'-origen-provincia').store.load({
																						params:{va_departamento:'2',va_provincia:records.get('iddepto')},
																						callback:function(){
																							Ext.getCmp(new_orden.id+'-origen-provincia').focus(10,true);
																						}
																					});
																				}
																			}

																			
																		},
																		{
																			xtype:'combo',
																			columnWidth:0.3,
																			id:new_orden.id+'-origen-provincia',
																			fieldLabel:'Provincia',
																			labelWidth:50,
																			store:Ext.create('Ext.data.Store',{
																				fields:[
																					{name: 'iddepto', type: 'string'},
													                                {name: 'id_prov', type: 'string'},
													                                {name: 'nom_prov',type: 'string'}
																				],
																				proxy:{
																					type:'ajax',
																					url:new_orden.url+'getComboProvincias/',
																					reader:{
																						type:'json',
																						rootProperty:'data'
																					}
																				}
																			}),
																			queryMode:'local',
																			valueField:'id_prov',
																			displayField:'nom_prov',
																			listConfig:{
																				minWidth:200
																			},
																			width:120,
																			forceSelection:true,
																			allowBlank:false,
																			emptyText: '[ Seleccione ]',
																			listeners:{
																				'select':function(obj,records,eOpts){
																					Ext.getCmp(new_orden.id+'-origen-distrito').store.load({
																						params:{va_departamento:'3',va_provincia:records.get('iddepto'),va_distrito:records.get('id_prov')},
																						callback:function(){
																							Ext.getCmp(new_orden.id+'-origen-distrito').focus(10,true);
																						}
																					});
																				}
																			}
																		},
																		{
																			xtype:'combo',
																			columnWidth:0.3,
																			id:new_orden.id+'-origen-distrito',
																			fieldLabel:'Distrito',
																			labelWidth:50,
																			store:Ext.create('Ext.data.Store',{
																				fields:[
																						{name: 'iddepto', type: 'string'},
														                                {name: 'id_prov', type: 'string'},
														                                {name: 'id_dist',type: 'string'},
														                                {name: 'nom_dist',type: 'string'},
														                                {name: 'ciu_id',type: 'int'},
														                                {name: 'ciu_px',type:'float'},
														                                {name: 'ciu_py',type:'float'}
																				],
																				proxy:{
																					type:'ajax',
																					url:new_orden.url+'getComboDistritos/',
																					reader:{
																						type:'json',
																						rootProperty:'data'
																					}
																				}
																			}),
																			queryMode:'local',
																			valueField:'id_dist',
																			displayField:'nom_dist',
																			listConfig:{
																				minWidth:200
																			},
																			width:120,
																			forceSelection:true,
																			allowBlank:false,
																			emptyText:'[ Seleccione ]',
																			listeners:{
																				'select':function(obj,records,eOpts){
																					new_orden.origen_x = records.get('ciu_px');
																					new_orden.origen_y = records.get('ciu_py');
																					new_orden.call_track(new_orden.origen_x+','+new_orden.origen_y,new_orden.destino_x+','+new_orden.destino_y,new_orden.origen_age_x+','+new_orden.origen_age_y,new_orden.destino_age_x+','+new_orden.destino_age_y);
																				}
																			}
																		},	
																		{
																			xtype:'textfield',
																			columnWidth:1,
																			id:new_orden.id+'-origen-direccion',
																			fieldLabel:'Dirección:',
																			labelWidth:58,
																		},
																		{
																			xtype:'textfield',
																			columnWidth:1,
																			id:new_orden.id+'-origen-referencia',
																			fieldLabel:'Referencia:',
																			labelWidth:58
																		},
																		{
																			xtype:'textfield',
																			columnWidth:0.5,
																			id:new_orden.id+'-origen-contacto',
																			fieldLabel:'Contacto',
																			labelWidth:59
																		},
																		{
																			xtype:'textfield',
																			columnWidth:0.5,
																			id:new_orden.id+'-origen-telefono',
																			fieldLabel:'Teléfono',
																			labelWidth:60
																		},
																		{
																			xtype:'textfield',
																			columnWidth:0.5,
																			id:new_orden.id+'-origen-x',
																			fieldLabel:'Coordenada X',
																			readOnly:true,
																			labelWidth:90
																		},
																		{
																			xtype:'textfield',
																			columnWidth:0.5,
																			id:new_orden.id+'-origen-y',
																			fieldLabel:'Coordenada Y',
																			readOnly:true,
																			labelWidth:90
																		}
																]
															}
													]
												},
												{
													title:'Destino',
													id:new_orden.id+'-destino',
													items:[
															{
																xtype: 'fieldset',
																title: 'Datos del Destino',
																layout:'column',
																items:[
																		{
																			xtype:'radiogroup',
																			columnWidth: 0.6,
																			id:new_orden.id+'-rbtn-group-destino',
																			columns:3,
																			vertical:true,
																			labelWidth:40,
																			items:[
																					{boxLabel:'Agencia Urbano',name:new_orden.id+'-rbtn-destino',inputValue:'1', width:110},
																					{boxLabel:'Agencia Shipper',name:new_orden.id+'-rbtn-destino',inputValue:'2', width:110},
																					{boxLabel:'Otra Dirección',name:new_orden.id+'-rbtn-destino',inputValue:'3', width:100,checked:true},
																			],
																			listeners:{
																				change:function(obj, newValue, oldValue, eOpts){
																					var op = parseInt(newValue[new_orden.id+'-rbtn-destino']);
																					new_orden.destino = op;
																					var shipper = Ext.getCmp(new_orden.id+'-shipper').getValue();
																					Ext.getCmp(new_orden.id+'-destino-agencia-shipper').setValue('');
																					if (op==1 || op == 2){
																						if (op == 1){
																							Ext.getCmp(new_orden.id+'-destino-agencia-shipper').store.load({params:{va_shipper:6}});
																						}else if (op == 2){
																							Ext.getCmp(new_orden.id+'-destino-agencia-shipper').store.load({params:{va_shipper:shipper}});
																						}
																						new_orden.cuerpo_destino(true);
																						Ext.getCmp(new_orden.id+'-destino-agencia-shipper').show(); 
																					}else{
																						new_orden.limpia_cuerpo_destino();
																						new_orden.cuerpo_destino(false);
																						Ext.getCmp(new_orden.id+'-destino-agencia-shipper').hide(); 
																					}
																				}
																			}
																		},
																		{
																			xtype:'combo',
																			id:new_orden.id+'-destino-agencia-shipper',
																			hidden:true,
																			padding:'0 0 5 0',
																			columnWidth: 0.4,
																			fieldLabel:'Agencia',
																			labelWidth:50,
																			store: Ext.create('Ext.data.Store',{
																				fields:[
																						{name:'id_agencia',type:'int'},
																						{name:'agencia' ,type:'string'},
																						{name:'dir_calle' ,type:'string'},
																						{name:'ciu_id',type:'int'},
																						{name:'ciu_ubigeo',type:'string'},
																						{name:'dir_px',type:'float'},
																						{name:'dir_py',type:'float'},
																				],
																				proxy:{
																					type:'ajax',
																					url:new_orden.url+'getAgencia_shipper/',
																					reader:{
																						type:'json',
																						rootProperty:'data'
																					}
																				}
																			}),
																			queryMode:'local',
																			valueField:'id_agencia',
																			displayField:'agencia',
																			listConfig:{
																				minWidth:200
																			},
																			width:120,
																			forceSelection:true,
																			allowBlank:false,
																			emptyText:'[ Seleccione ]',
																			listeners:{
																				'select':function(obj,records,eOpts){
																					var ciu_ubigeo = records.get('ciu_ubigeo');
																					dpto = ciu_ubigeo.substring(0,2);
																					prov = ciu_ubigeo.substring(2,4);
		                															dist = ciu_ubigeo.substring(4,6);
		                															
		                															Ext.getCmp(new_orden.id+'-destino-departament').setValue(dpto);

		                															Ext.getCmp(new_orden.id+'-destino-provincia').store.load({
		                																params:{va_departamento:'2',va_provincia:dpto},
		                																callback:function(){
		                																	Ext.getCmp(new_orden.id+'-destino-provincia').setValue(prov);		
		                																}
		                															});
		                															
		                															Ext.getCmp(new_orden.id+'-destino-distrito').store.load({
		                																params:{va_departamento:'3',va_provincia:dpto,va_distrito:prov},
		                																callback:function(){
		                																	Ext.getCmp(new_orden.id+'-destino-distrito').setValue(dist);		
		                																}
		                															});

		                															Ext.getCmp(new_orden.id+'-destino-direccion').setValue(records.get('dir_calle'));
		                															Ext.getCmp(new_orden.id+'-destino-x').setValue(records.get('dir_px'));
		                															Ext.getCmp(new_orden.id+'-destino-y').setValue(records.get('dir_py'));
		                															new_orden.destino_age_x = records.get('dir_px');
		                															new_orden.destino_age_y = records.get('dir_py');
		                															new_orden.call_track(new_orden.origen_x+','+new_orden.origen_y,new_orden.destino_x+','+new_orden.destino_y,new_orden.origen_age_x+','+new_orden.origen_age_y,new_orden.destino_age_x+','+new_orden.destino_age_y);
		                															new_orden.cuerpo_destino(true)
																				}
																			}


																		}
																]
															},
															{
																xtype:'fieldset',
																title:'',
																layout:'column',
																padding:'5 0 0 0',
																defaults:{
																	padding:'0 5 5 5'
																},
																items:[

																		{
																			xtype:'combo',
																			columnWidth:0.4,
																			id:new_orden.id+'-destino-departament',
																			fieldLabel:'Departamento',
																			labelWidth:80,
																			store:Ext.create('Ext.data.Store',{
																				fields:[
																						{name: 'iddepto', type: 'string'},
						                                								{name: 'nom_nomdep', type: 'string'}
																				],
																				proxy:{
																					type:'ajax',
																					url:new_orden.url+'getComboDepartamentos/',
																					reader:{
																						type:'json',
																						rootProperty:'data'
																					}
																				}
																			}),
																			queryMode:'local',
																			valueField:'iddepto',
																			displayField:'nom_nomdep',
																			listConfig:{
																				minWidth:200
																			},
																			width:120,
																			forceSelection:true,
																			allowBlank:false,
																			emptyText:'[Seleccione]',
																			listeners:{
																				afterrender:function(obj){
																					obj.getStore().load({
																						params:{va_departamento:'1'},
																					});
																				},
																				'select':function(obj,records,eOpts){
																					Ext.getCmp(new_orden.id+'-destino-provincia').store.load({
																						params:{va_departamento:'2',va_provincia:records.get('iddepto')},
																						callback:function(){
																							Ext.getCmp(new_orden.id+'-destino-provincia').focus(10,true);
																						}
																					});
																				}
																			}

																			
																		},
																		{
																			xtype:'combo',
																			columnWidth:0.3,
																			id:new_orden.id+'-destino-provincia',
																			fieldLabel:'Provincia',
																			labelWidth:50,
																			store:Ext.create('Ext.data.Store',{
																				fields:[
																					{name: 'iddepto', type: 'string'},
													                                {name: 'id_prov', type: 'string'},
													                                {name: 'nom_prov',type: 'string'}
																				],
																				proxy:{
																					type:'ajax',
																					url:new_orden.url+'getComboProvincias/',
																					reader:{
																						type:'json',
																						rootProperty:'data'
																					}
																				}
																			}),
																			queryMode:'local',
																			valueField:'id_prov',
																			displayField:'nom_prov',
																			listConfig:{
																				minWidth:200
																			},
																			width:120,
																			forceSelection:true,
																			allowBlank:false,
																			emptyText: '[ Seleccione ]',
																			listeners:{
																				'select':function(obj,records,eOpts){
																					Ext.getCmp(new_orden.id+'-destino-distrito').store.load({
																						params:{va_departamento:'3',va_provincia:records.get('iddepto'),va_distrito:records.get('id_prov')},
																						callback:function(){
																							Ext.getCmp(new_orden.id+'-destino-distrito').focus(10,true);
																						}
																					});
																				}
																			}
																		},
																		{
																			xtype:'combo',
																			columnWidth:0.3,
																			id:new_orden.id+'-destino-distrito',
																			fieldLabel:'Distrito',
																			labelWidth:50,
																			store:Ext.create('Ext.data.Store',{
																				fields:[
																						{name: 'iddepto', type: 'string'},
														                                {name: 'id_prov', type: 'string'},
														                                {name: 'id_dist',type: 'string'},
														                                {name: 'nom_dist',type: 'string'},
														                                {name: 'ciu_id',type: 'int'},
														                                {name: 'ciu_px',type:'float'},
														                                {name: 'ciu_py',type:'float'}
																				],
																				proxy:{
																					type:'ajax',
																					url:new_orden.url+'getComboDistritos/',
																					reader:{
																						type:'json',
																						rootProperty:'data'
																					}
																				}
																			}),
																			queryMode:'local',
																			valueField:'id_dist',
																			displayField:'nom_dist',
																			listConfig:{
																				minWidth:200
																			},
																			width:120,
																			forceSelection:true,
																			allowBlank:false,
																			emptyText:'[ Seleccione ]',
																			listeners:{
																				'select':function(obj,records,eOpts){
																					new_orden.destino_x = records.get('ciu_px');
																					new_orden.destino_y = records.get('ciu_py');
																					//new_orden.call_origen(new_orden.origen_x+','+new_orden.origen_y,new_orden.destino_x+','+new_orden.destino_y);
																					new_orden.call_track(new_orden.origen_x+','+new_orden.origen_y,new_orden.destino_x+','+new_orden.destino_y,new_orden.origen_age_x+','+new_orden.origen_age_y,new_orden.destino_age_x+','+new_orden.destino_age_y);
																				}
																			}
																		},	
																		{
																			xtype:'textfield',
																			columnWidth:1,
																			id:new_orden.id+'-destino-direccion',
																			fieldLabel:'Dirección:',
																			labelWidth:58,
																		},
																		{
																			xtype:'textfield',
																			columnWidth:1,
																			id:new_orden.id+'-destino-referencia',
																			fieldLabel:'Referencia:',
																			labelWidth:58
																		},
																		{
																			xtype:'textfield',
																			columnWidth:0.5,
																			id:new_orden.id+'-destino-contacto',
																			fieldLabel:'Contacto',
																			labelWidth:59
																		},
																		{
																			xtype:'textfield',
																			columnWidth:0.5,
																			id:new_orden.id+'-destino-telefono',
																			fieldLabel:'Teléfono',
																			labelWidth:60
																		},
																		{
																			xtype:'textfield',
																			columnWidth:0.5,
																			id:new_orden.id+'-destino-x',
																			fieldLabel:'Coordenada X',
																			readOnly:true,
																			labelWidth:90
																		},
																		{
																			xtype:'textfield',
																			columnWidth:0.5,
																			id:new_orden.id+'-destino-y',
																			fieldLabel:'Coordenada Y',
																			readOnly:true,
																			labelWidth:90
																		}
																]
															}


													]
												},
												{
													title:'Carga/Horario',
													id:new_orden.id+'carga-horario',
													layout:'fit',
													items:[
															{
																xtype:'panel',
																border:false,
																defaults:{
																	border:false
																},
																layout:'hbox',
																items:[
																		{
																			region:'north',
																			//layout:'fit',
																			width:350,
																			items:[
																					{
																						xtype:'fieldset',
																						title:'Cargamento',
																						defaults:{
																							padding: '0 5 5 5'
																						},
																						layout:'column',
																						items:[
																								{
																									xtype:'combo',
																									columnWidth:1,
																									fieldLabel:'Tipo de Paquete',
																									labelWidth:100,
																								},
																								{
																									xtype:'textareafield',
																									columnWidth:1,
																									fieldLabel:'Detalle',
																									height:40,
																									labelWidth:100
																								},
																								{
																									xtype:'numberfield',
																									columnWidth:1,
																									fieldLabel:'Peso (Kg)',
																									minValue: 1,
																									labelWidth:100
																								},
																								{
																									xtype:'numberfield',
																									columnWidth:1,
																									fieldLabel:'Total de Pieza',
																									minValue: 1,
																									labelWidth:100
																								},
																								{
																									xtype:'numberfield',
																									columnWidth:0.3,
																									fieldLabel:'Alto',
																									minValue: 0,
																									allowDecimals: true,
																									decimalSeparator:'.',
																									labelWidth:25
																								},
																								{
																									xtype:'numberfield',
																									columnWidth:0.3,
																									fieldLabel:'Ancho:',
																									minValue: 0,
																									allowDecimals: true,
																									decimalSeparator:'.',
																									labelWidth:34
																								},
																								{
																									xtype:'numberfield',
																									columnWidth:0.4,
																									fieldLabel:'Largo',
																									minValue: 0,
																									allowDecimals: true,
																									decimalSeparator:'.',
																									labelWidth:40
																								}
																						]
																					}
																			]
																		},
																		{
																			region:'center',
																			//layout:'fit',
																			width:300,
																			items:[
																			]
																		}
																]
															}
													]
												}
										]
									}
							]
						}
				]
			});

			var win = Ext.create('Ext.window.Window',{
				id:new_orden.id+'-win',
				title:'Nueva Orden de Recojo',
				height:350,
				width:600,
				resizable:false,
				closable:false,
				minimizable:true,
				plaint:true,
				constrain:true,
				constrainHeader:true,
				renderTo:Ext.get(gtransporte.id+'cont_map'),
				header:true,
				border:false,
				layout:{
					type:'fit'
				},
				modal:false,
				items:[panel],
				listeners:{
					show:function(window,eOpts){
						window.alignTo(Ext.get(gtransporte.id+'Mapsa'),'bl-bl');
					},
					minimize:function(window,opts){
						window.collapse();
						window.setWidth(100);
						window.alignTo(Ext.get(gtransporte.id+'Mapsa'), 'bl-bl');
					},
					beforerender:function(obj,opts){
						//new_orden.call_origen();
					}
				},
				tools:[{
						type:'restore',
						handler:function(evt, toolEl, owner, tool){
							var window = owner.up('window');
							window.setWidth(600);
							window.expand('',false);
							window.alignTo(Ext.get(gtransporte.id+'Mapsa'), 'bl-bl');
						}
				}]

			}).show();
		},
		cabecera:function(estado){
			Ext.getCmp(new_orden.id+'-rbtn-group-linea').setReadOnly(estado);
			//Ext.getCmp(new_orden.id+'-rbtn-group-ejecutar').setReadOnly(estado);
			Ext.getCmp(new_orden.id+'-shipper').setReadOnly(estado);

			if (estado){
				Ext.getCmp(new_orden.id+'-tabpanel').enable();	
			}else{
				Ext.getCmp(new_orden.id+'-tabpanel').disable();	
			}
		},
		cuerpo_origen:function(estado){
			Ext.getCmp(new_orden.id+'-origen-departament').setReadOnly(estado);
			Ext.getCmp(new_orden.id+'-origen-provincia').setReadOnly(estado);
			Ext.getCmp(new_orden.id+'-origen-distrito').setReadOnly(estado);
			Ext.getCmp(new_orden.id+'-origen-direccion').setReadOnly(estado);
			Ext.getCmp(new_orden.id+'-origen-referencia').setReadOnly(estado);
			//Ext.getCmp(new_orden.id+'-origen-x').setReadOnly(true);
			//Ext.getCmp(new_orden.id+'-origen-y').setReadOnly(true);
		},
		limpia_cuerpo_origen:function(){
			Ext.getCmp(new_orden.id+'-origen-departament').setValue('');
			Ext.getCmp(new_orden.id+'-origen-provincia').setValue('');
			Ext.getCmp(new_orden.id+'-origen-distrito').setValue('');
			Ext.getCmp(new_orden.id+'-origen-direccion').setValue('');
			Ext.getCmp(new_orden.id+'-origen-referencia').setValue('');
			Ext.getCmp(new_orden.id+'-origen-x').setValue('');
			Ext.getCmp(new_orden.id+'-origen-y').setValue('');	
		},
		cuerpo_destino:function(estado){
			Ext.getCmp(new_orden.id+'-destino-departament').setReadOnly(estado);
			Ext.getCmp(new_orden.id+'-destino-provincia').setReadOnly(estado);
			Ext.getCmp(new_orden.id+'-destino-distrito').setReadOnly(estado);
			Ext.getCmp(new_orden.id+'-destino-direccion').setReadOnly(estado);
			Ext.getCmp(new_orden.id+'-destino-referencia').setReadOnly(estado);
			//Ext.getCmp(new_orden.id+'-destino-x').setReadOnly(true);
			//Ext.getCmp(new_orden.id+'-destino-y').setReadOnly(true);
		},
		limpia_cuerpo_destino:function(){
			Ext.getCmp(new_orden.id+'-destino-departament').setValue('');
			Ext.getCmp(new_orden.id+'-destino-provincia').setValue('');
			Ext.getCmp(new_orden.id+'-destino-distrito').setValue('');
			Ext.getCmp(new_orden.id+'-destino-direccion').setValue('');
			Ext.getCmp(new_orden.id+'-destino-referencia').setValue('');
			Ext.getCmp(new_orden.id+'-destino-x').setValue('');
			Ext.getCmp(new_orden.id+'-destino-y').setValue('');		
		},
		call_track:function(origen_,destino_,age_origen,age_destino){

			console.log(origen_,destino_,age_origen,age_destino);
			var find_origen, find_destino;
			if (new_orden.origen == 3){
				find_origen = origen_;
			}else{
				find_origen = age_origen;
			}

			if (new_orden.destino == 3){
				find_destino = destino_;
			}else{
				find_destino = age_destino;
			}

			new_orden.call_origen(find_origen,find_destino);
		},
		call_origen:function(origen,destino){
		    	var directionsDisplay;
		        var directionsService = new google.maps.DirectionsService();
		        //var map;
		        var rendererOptions = {
					  draggable: true,
					  suppressMarkers: false
				};
		        directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
		        var argentina = new google.maps.LatLng(-12.047085762023926, -77.08118438720703);
		        var mapOptions = {
		            zoom:7,
		            center: argentina,
		            mapTypeId: google.maps.MapTypeId.ROADMAP
		        };
		        map = new google.maps.Map(document.getElementById(gtransporte.id+'Mapsa'), mapOptions);
		        directionsDisplay.setMap(map);
		        var directionsService = new google.maps.DirectionsService();
		    	var request = {
			        origin: origen, //-11.832281   -77.10657100000003
			        destination: destino, //-12.0472236 -77.0850135
			       /* waypoints: [{
			          location: "Preston",
			          stopover:true}],*/
			        optimizeWaypoints: true,
			        travelMode: google.maps.DirectionsTravelMode.DRIVING
			    };
			    directionsService.route(request, function(response, status) {
			      if (status == google.maps.DirectionsStatus.OK) {
			        directionsDisplay.setDirections(response);
			        google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
			        	if (parseInt(new_orden.origen) == 3 ){//Solo pinta cuando es otra direccion (3)
			        		Ext.getCmp(new_orden.id+'-origen-x').setValue(directionsDisplay.dragResult.routes[0].legs[0].steps[0].start_location.k);
							Ext.getCmp(new_orden.id+'-origen-y').setValue(directionsDisplay.dragResult.routes[0].legs[0].steps[0].start_location.D);	
			        	}
			        	if (parseInt(new_orden.destino) == 3){//Solo pinta cuando es otra direccion (3)
			        		Ext.getCmp(new_orden.id+'-destino-x').setValue(directionsDisplay.dragResult.routes[0].legs[0].steps[0].end_location.k);
							Ext.getCmp(new_orden.id+'-destino-y').setValue(directionsDisplay.dragResult.routes[0].legs[0].steps[0].end_location.D);			
			        	}
			  			//console.log(directionsDisplay.dragResult.routes[0].legs[0].steps[0].start_location);
			  			//console.log(directionsDisplay.dragResult.routes[0].legs[0].steps[0].end_location);
			   		});
			      }else{
			        alert("directions response "+status);
			      }
			    });
			   
		    }

	}
	Ext.onReady(new_orden.init,new_orden);
</script>