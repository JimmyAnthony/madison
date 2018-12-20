<script type="text/javascript">

Ext.define('grid_actividad',{
	extend: 'Ext.data.Model',
	fields:[
		{name:'nom_age',type:'string'},
		{name:'cod_age',type:'string'},
		{name:'tip_age',type:'int'},
		{name:'dir_id',type:'int'},
		{name:'direccion',type:'string'},
		{name:'nombre_ubi',type:'string'},
		{name:'estado',type:'int'},
		
		{name:'id_agencia',type:'int'},
		{name:'dir_id',type:'int'},
		{name:'id_geo',type:'int'},
		{name:'ciu_id',type:'int'},
		{name:'via_id',type:'int'},
		{name:'dir_calle',type:'string'},
		{name:'dir_numvia',type:'string'},
		{name:'dir_referent',type:'string'},
		{name:'urb_nombre',type:'string'},
		{name:'mz_id',type:'int'},
		{name:'mz_nom',type:'string'},
		{name:'num_lote',type:'string'},
		{name:'num_int',type:'string'},
		{name:'lat_x',type:'float'},
		{name:'lon_y',type:'float'},
	],
});
Ext.define('grid_contactos',{
	extend:'Ext.data.Model',
	fields:[
		{name:'id_contacto',type:'string'},
		{name:'shi_codigo',type:'string'},
		{name:'con_codigo',type:'string'},
		{name:'id_agencia',type:'int'},
		{name:'age_nombre',type:'string'},
		{name:'tcon_id',type:'string'},
		{name:'tip_contac',type:'string'},
		{name:'con_nombre',type:'string'},
		{name:'con_cargo',type:'string'},
		{name:'con_email',type:'string'},
		{name:'dir_npiso',type:'string'},
		{name:'dir_office',type:'string'},
		{name:'con_estado',type:'string'},
		{name:'telf',type:'string'},
		{name:'anexo',type:'string'}

		
	]
});
	var socio_shipper = {
		id:'socio_shipper',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/socionegocio/',
		vl_id_sne:'<?php echo $p["vl_id_sne"];?>',
		vp_tip:'<?php echo $p["vp_tip"];?>',
		recordsToSend:undefined,
		recordsToSendContactos:undefined,
		shi_codigo:'<?php echo $p["shi_codigo"];?>',
		per_id_sac:'<?php echo $p["per_id_sac"];?>',
		per_id_ven:'<?php echo $p["per_id_ven"];?>',
		shi_nombre:'<?php echo $p["shi_nombre"];?>',
		fec_ingreso:'<?php echo $p["fec_ingreso"];?>',
		shi_id:'<?php echo $p["shi_id"];?>',
		shi_empresa:'<?php echo $p["shi_empresa"];?>',
		id_agencia:0,
		dir_id:0,
		id_contacto:'0',
		rowEditing2:Ext.create('Ext.grid.plugin.RowEditing',{clicksToMoveEditor: 1,autoCancel: false}),
		init:function(){

			var panel = Ext.create('Ext.form.Panel',{
				id:socio_shipper.id+'-panel-shipper',
				layout:'fit',
				border:false,
				items:[
						{
							region:'center',
							border:false,
							id:socio_shipper.id+'-center',
							layout:'column',
							defaults:{
								margin:'0 5 5 5',
							},
							items:[
									{
										xtype:'fieldset',
										title:'Datos del Cliente',
										layout:'column',
										margin:'20 0 0 0',
										defaults:{
											padding:'3 3 3 3',
										},
										columnWidth:1,
										id:socio_shipper.id+'-datos-cliente',
										items:[
												{
													xtype:'textfield',
													maskRe : /[0-9]$/,
													id:socio_shipper.id+'-shi_codigo',
													fieldLabel:'Codigo de Shipper:',
													allowBlank:false,
													labelWidth:105,
													columnWidth:0.3,
													listeners:{
														blur:function( obj, event, eOpts ){
															if (socio_shipper.vp_tip=='I'){
																socio_shipper.recupera_shipper(obj.value);
															}
															
														}
													}
												},
												{
													xtype:'textfield',
													fieldLabel:'Sigla:',
													allowBlank:false,
													id:socio_shipper.id+'-shi_id',
													plugins: [new ueInputTextMask('AA')],
													labelWidth:50,
													columnWidth:0.2
												},
												{
													xtype:'textfield',
													allowBlank:false,
													id:socio_shipper.id+'-shi_nombre',
													fieldLabel:'Shipper Nombre',
													labelWidth:105,
													columnWidth:0.5
												},
												{
													xtype:'datefield',
													id:socio_shipper.id+'-fecha_ingreso',
													fieldLabel:'Fecha de Ingreso',
													labelWidth:105,
													value:new Date(),
													columnWidth:0.5
												},
												{
													xtype:'radiogroup',
													id:socio_shipper.id+'-rbtn-group-empresa',
													columnWidth:0.5,
													fieldLabel:'Empresa',
													columns:2,
													vertical:true,
													labelWidth:50,
													items:[
														{boxLabel:'Urbano',name:socio_shipper.id+'-rbtn',inputValue:'U',width:80,checked:true},
														{boxLabel:'Geo-Retail',name:socio_shipper.id+'-rbtn',inputValue:'G',width:80},
													],
												},
												{
													xtype:'combo',
													fieldLabel:'Vendedor',
													labelWidth:105,
													columnWidth:1,
													id:socio_shipper.id+'-vendedor',
													store:Ext.create('Ext.data.Store',{
														fields:[
															{name:'nombre', type:'string'},
															{name:'per_id' , type:'int'}
														],
														proxy:{
															type:'ajax',
															url:socio_shipper.url+'scm_usr_sis_personal/',
															reader:{
																type:'json',
																rootProperty:'data'
															}
														}
													}),
													queryMode:'local',
													valueField:'per_id',
													displayField:'nombre',
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
																params:{vp_cargo:24,vp_area:0,vp_prov_codigo:0},//24 es cargo de vendedores
																callback:function(){
																}
															});
														}
													}
												},
												{
													xtype:'combo',
													fieldLabel:'SAC',
													labelWidth:105,
													columnWidth:1,
													id:socio_shipper.id+'-sac',
													store:Ext.create('Ext.data.Store',{
														fields:[
															{name:'nombre', type:'string'},
															{name:'per_id' , type:'int'}
														],
														proxy:{
															type:'ajax',
															url:socio_shipper.url+'scm_usr_sis_personal/',
															reader:{
																type:'json',
																rootProperty:'data'
															}
														}
													}),
													queryMode:'local',
													valueField:'per_id',
													displayField:'nombre',
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
																params:{vp_cargo:0,vp_area:4,vp_prov_codigo:0},//13 son cargos de ejecutivo de cuenta
																callback:function(){
																}
															});
														}
													}
												}


										]
									}
							]
						}
				]
			});
			var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
		        clicksToMoveEditor: 1,
		        autoCancel: false
		    });
		    /*var rowEditing2 = Ext.create('Ext.grid.plugin.RowEditing', {
		        clicksToMoveEditor: 1,
		        autoCancel: false
		    });*/
		 
			var store_grid_actividad = Ext.create('Ext.data.Store',{
				model: 'grid_actividad',
				proxy:{
					type:'ajax',
					url:socio_shipper.url+'scm_scm_socionegocio_select_agencia_shi/',
					reader:{
						type:'json',
						root:'data'
					}
				}
			});

			var store_grid_contactos = Ext.create('Ext.data.Store',{
				model:'grid_contactos',
				proxy:{
					type:'ajax',
					url:socio_shipper.url+'scm_scm_socionegocio_select_contacto_shi/',
					reader:{
						type:'json',
						root:'data'
					}
				}
			});

			var store_distritos = Ext.create('Ext.data.Store',{
				idProperty: 'ciu_id',
				fields:[
					{name:'completo',type:'string'},
					{name:'ciu_id',type:'int'}
				],
				autoLoad:true,
				proxy:{
					type:'ajax',
					url:socio_shipper.url+'getComboDistritosProvinciaDepartamento/?&va_departamento=4',
					reader:{
						type:'json',
						rootProperty:'data'
					}
				},
				listeners:{
					beforeload:function( obj, operation, eOpts ){
					//	console.log(obj);
					}
				}
			});

			var store_id_agencia = Ext.create('Ext.data.Store',{
				idProperty: 'id_agencia',
				fields:[
					{name:'id_agencia',type:'int'},
					{name:'agencia',type:'string'}
				],
				autoLoad:true,
				proxy:{
					type:'ajax',
					url:socio_shipper.url+'scm_scm_ss_agencia_shipper/?&vp_shi_codigo='+socio_shipper.shi_codigo,
					reader:{
						type:'json',
						rootProperty:'data'
					}
				},
				listeners:{
					beforeload:function( obj, operation, eOpts ){
					}
				}
			});

			var store_tcon_id = Ext.create('Ext.data.Store',{
				fields:[
					{name:'id_elemento',type:'int'},
					{name:'descripcion',type:'string'}
				],
				autoLoad:true,
				proxy:{
					type:'ajax',
					url:socio_shipper.url+'scm_scm_tabla_detalle/?&vp_tab_id=TCO&vp_shi_codigo=0',
					reader:{
						type:'json',
						rootProperty:'data'
					}
				},
				listeners:{
					beforeload:function( obj, operation, eOpts ){
					}
				}
			});
		
			var panelActividad = Ext.create('Ext.form.Panel',{
				layout:'fit',
				border:false,
				tbar:[
					{
						text:'Agregar Agencia',
						icon: '/images/icon/add.png',
						listeners:{
							click:function(obj,e){
								Ext.getCmp(socio_shipper.id+'-tab-registro-shipper').setActiveTab(socio_shipper.id+'-tabGeoreferenciar');
								Ext.getCmp(socio_shipper.id+'-tabGeoreferenciar').setDisabled(false);
								socio_shipper.id_agencia= 0;
								socio_shipper.dir_id = 0;
								Ext.getCmp(socio_shipper.id+'-map-centro-activity').setValue('');
								Ext.getCmp(socio_shipper.id+'-map-codigo-activity').setValue('');
								Ext.getCmp(socio_shipper.id+'-map-codigo-activity').setReadOnly(false);
								var map = Ext.getCmp(socio_shipper.id+'-direc');
								map.reset();
						        map.reset_maps();
						        map.reset_global_vars();

							}
						}
					}
				],
				items:[
						{
							xtype:'grid',
							id:socio_shipper.id+'-grid-actividad',
							store:store_grid_actividad,
							columnsLines:true,
							plugins: [rowEditing],
							columns:{
								items:[
										{
											text:'Menu',
											width:80,
											align:'center',
											renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
												metaData.style = "padding: 0px; margin: 0px";
												if (parseInt(record.get('id_agencia'))>0 ){
													var id_agencia = record.get('id_agencia');
													var cod_age = "'"+record.get('cod_age')+"'";
													var nom_age = "'"+record.get('nom_age')+"'";	
													var dir_id = "'"+record.get('dir_id')+"'";	
													var tip_age = "'"+record.get('tip_age')+"'";	
													if (record.get('estado')==1){
														return global.permisos({
				                                            type: 'link',
				                                            extraCss: 'ftp-procesar-link',
				                                            id_menu: socio_shipper.id_menu,
				                                            icons:[
				                                            	{id_serv: 77, img: 'ico_editar.gif', qtip: 'Click para Editar Agencia',js: 'socio_shipper.editar_agencia('+id_agencia+','+cod_age+','+nom_age+','+dir_id+','+tip_age+')'},
				                                            	{id_serv: 78, img: 'ok.png', qtip: 'Click para ver Desactivar.', js: 'socio_shipper.stado_agencia('+id_agencia+',0)'}
				                                            ]
				                                        });		

													}else{
														return global.permisos({
				                                            type: 'link',
				                                            extraCss: 'ftp-procesar-link',
				                                            id_menu: socio_shipper.id_menu,
				                                            icons:[
				                                            	{id_serv: 77, img: 'ico_editar.gif', qtip: 'Click para Editar Agencia',js: 'socio_shipper.editar_agencia('+id_agencia+','+cod_age+','+nom_age+','+dir_id+','+tip_age+')'},
				                                            	{id_serv: 78, img: 'close.png', qtip: 'Click para ver Activar.', js: 'socio_shipper.stado_agencia('+id_agencia+',1)'},
				                                            ]
				                                        });		
													}
													
												}
												
											}
										},
										{
											text:'Cod. Agencia',
											width:80,
											dataIndex:'cod_age',
										},
										{
											text:'Agencia',
											dataIndex:'nom_age',
											flex:2,
										},
										{
											text:'Direccion',
											dataIndex:'direccion',
											flex:2,
										},
										{
											text:'Distrito',
											width:200,
											dataIndex:'nombre_ubi',
										},
										{
											text:'Estado',
											flex:1,
											dataIndex:'estado',
											renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
												if (value == 1){
													return 'Activado';
												}else{
													return 'Desactivado';
												}
											}
										},
										/*{
											text:'x',
											dataIndex:'x',
										},
										{
											text:'y',
											dataIndex:'y',
										}*/
								],
								defaults:{
									sortable: true
								}
							},
							selModel:{
								 selType: 'cellmodel'
							},
							viewConfig:{
								stripeRows:true,
								enableTextSelection: false,
								markDirty: false
							},
							trackMouseOver: true,
							listeners:{
								beforecellclick:function( obj, td, cellIndex, record, tr, rowIndex, e, eOpts){

								}
							}
						}
				]
			});
			var panelContactos = Ext.create('Ext.form.Panel',{
				id:socio_shipper.id+'-panel-contactos',
				layout:'column',
				border:false,
				tbar:[
					{
						text:'Agregar Contacto',
						icon: '/images/icon/add.png',
						listeners:{
							click:function(obj,e){
								socio_shipper.add_contacto();
								Ext.getCmp(socio_shipper.id+'-grid-contactos').setHeight(370);
							}
						}	
					},
					{
						text:'Nuevo',
						icon:'/images/icon/new_file.ico',
						listeners:{
							click:function(obj,e){
								Ext.getCmp(socio_shipper.id+'-contenedor').show();
								Ext.getCmp(socio_shipper.id+'-grid-contactos').setHeight(260);
								socio_shipper.limpiar_contacto();
							}
						}
					}
				],
				items:[

						{
							xtype:'fieldset',
							id:socio_shipper.id+'-contenedor',
							hidden:true,
							title:'Datos del Cliente',
							layout:'column',
							columnWidth:1,
							defaults:{
								padding:'3 3 3 3',
							},
							items:[
									{
										xtype:'textfield',
										id:socio_shipper.id+'-con_codigo',
										fieldLabel:'Codigo',
										labelWidth:50,
										allowBlank:false,
										columnWidth:0.3,
									},
									{
										xtype:'textfield',
										id:socio_shipper.id+'-con_nombre',
										fieldLabel:'Nombre',
										labelWidth:50,
										allowBlank:false,
										columnWidth:0.4,
									},
									{
										xtype:'textfield',
										id:socio_shipper.id+'-con_cargo',
										fieldLabel:'Cargo',
										labelWidth:50,
										allowBlank:false,
										columnWidth:0.3,
									},
									{
										xtype:'textfield',
										id:socio_shipper.id+'-con_email',
										fieldLabel:'Email',
										//vtype: 'email',
										labelWidth:50,
										//allowBlank:false,
										columnWidth:0.5,
									},
									{
										xtype:'textfield',
										id:socio_shipper.id+'-dir_npiso',
										fieldLabel:'Piso',
										labelWidth:50,
										allowBlank:false,
										columnWidth:0.25,
									},
									{
										xtype:'textfield',
										id:socio_shipper.id+'-dir_office',
										fieldLabel:'Oficina',
										labelWidth:50,
										allowBlank:false,
										columnWidth:0.25,

									},
									{
										xtype:'textfield',
										id:socio_shipper.id+'-tel',
										fieldLabel:'Telefono',
										labelWidth:50,
										allowBlank:false,
										columnWidth:0.2
									},
									{
										xtype:'textfield',
										id:socio_shipper.id+'-anexo',
										fieldLabel:'Anexo',
										labelWidth:45,
										allowBlank:true,
										columnWidth:0.2
									},
									{
										xtype:'combo',
										id:socio_shipper.id+'-id_agencia',
										fieldLabel:'Centro de Actividad',
										labelWidth:110,
										allowBlank:false,
										columnWidth:0.3,
										store:Ext.create('Ext.data.Store',{
											fields:[
													{name:'id_agencia',type:'int'},
													{name:'agencia',type:'string'}
											],
											proxy:{
												type:'ajax',
												url:socio_shipper.url+'scm_scm_ss_agencia_shipper/',
												reader:{
													type:'json',
													rootProperty:'data'
												}
											}
										}),
										queryMode: 'local',
				                        valueField: 'id_agencia',
				                        displayField: 'agencia',
				                        listConfig:{
				                            minWidth: 200
				                        },
				                        width: 120,
				                        forceSelection: true,
				                        allowBlank: false,
				                        emptyText: '[ Seleccione ]',
				                        listeners:{
				                        	afterrender:function(obj){
				                        		obj.getStore().load({
				                        			params:{vp_shi_codigo:socio_shipper.shi_codigo},
				                        		});
				                        	}
				                        }
									},
									{
										xtype:'combo',
										id:socio_shipper.id+'-tcon_id',
										fieldLabel:'T. Contacto',
										labelWidth:70,
										columnWidth:0.3,
										allowBlank:false,
										store:Ext.create('Ext.data.Store',{
											fields:[
													{name:'id_elemento',type:'int'},
													{name:'descripcion',type:'string'}
											],
											proxy:{
												type:'ajax',
												url:socio_shipper.url+'scm_scm_tabla_detalle/',
												reader:{
													type:'json',
													rootProperty:'data'
												}
											}
										}),
										queryMode: 'local',
				                        valueField: 'id_elemento',
				                        displayField: 'descripcion',
				                        listConfig:{
				                            minWidth: 200
				                        },
				                        width: 120,
				                        forceSelection: true,
				                        allowBlank: false,
				                        emptyText: '[ Seleccione ]',
				                        listeners:{
				                        	afterrender:function(obj){
				                        		obj.getStore().load({
				                        			params:{vp_tab_id:'TCO',vp_shi_codigo:0},
				                        		});
				                        	}
				                        }
									}
							]
						},	
						{
							xtype:'grid',
							columnWidth:1,
							id:socio_shipper.id+'-grid-contactos',
							store:store_grid_contactos,
							columnsLines:true,
							height:370,
							//plugins: [socio_shipper.rowEditing2],
							columns:{
								items:[
										{
											text:'Menu',
											width:80,
											align:'center',
											renderer:function(value, metaData, records, rowIndex, colIndex, store, view){
												if (parseInt(records.get('id_contacto'))>0){
													if (records.get('con_estado')=='Activo'){

														return global.permisos({
				                                            type: 'link',
				                                            extraCss: 'ftp-procesar-link',
				                                            id_menu: socionegocio.id_menu,
				                                            icons:[
				                                               {id_serv: 78, img: 'ok.png', qtip: 'Click para ver Activar.', js: 'socio_shipper.stado_contacto('+parseInt(records.get('id_contacto'))+',0)'},
				                                               {id_serv: 77, img: 'ico_editar.gif', qtip: 'Click para ver Editar.', js: 'socio_shipper.setEditarContacto('+rowIndex+')'},
				                                            ]
				                                        });	
													}else{
														return global.permisos({
				                                            type: 'link',
				                                            extraCss: 'ftp-procesar-link',
				                                            id_menu: socionegocio.id_menu,
				                                            icons:[
				                                            	 {id_serv: 78, img: 'close.png', qtip: 'Click para ver Desactivar.', js: 'socio_shipper.stado_contacto('+parseInt(records.get('id_contacto'))+',1)'},
				                                            	 {id_serv: 77, img: 'ico_editar.gif', qtip: 'Click para ver Editar.', js: 'socio_shipper.setEditarContacto('+rowIndex+')'},
				                                                
				                                            ]
				                                        });	
													}
													
												}
											}
										},
										{
											text:'Codigo',
											flex:1,
											dataIndex:'con_codigo',
											editor: {
                                                allowBlank: false
                                            }
										},
										{
											text:'Agencia',
											dataIndex:'age_nombre',//id_agencia
											flex:2,
											/*renderer:function(value, metaData, records, rowIndex, colIndex, store, view) {	
												var record = store_id_agencia.findRecord('id_agencia' , value).get('agencia');
												
												return record;
											},
											editor: {
                                                allowBlank: false,
                                                xtype:'combo',
												id:socio_shipper.id+'-editc-id_agencia',
								                store:store_id_agencia,
								                listConfig:{
													minWidth:300
												},
												typeAhead: true,
								                typeAheadDelay :1,
								                allowBlank: false,
								                queryMode: 'local',
								                triggerAction: 'all',
								                valueField: 'id_agencia',
								                displayField: 'agencia',
								                emptyText: '[Seleccione]',	
								                listeners:{
								                	beforerender:function(obj){
								                		
														store_id_agencia.load({
															params:{vp_shi_codigo:socio_shipper.shi_codigo}	
														});
								                	}
								                }
								            
                                            }*/
                                            
										},
										{
											text:'Tipo de Contacto',
											flex:2,
											dataIndex:'tip_contac',//tcon_id
											/*renderer:function(value, metaData, records, rowIndex, colIndex, store, view) {	
												var record = store_tcon_id.findRecord('id_elemento' , value).get('descripcion');
												return record;
											},
											editor: {
                                                allowBlank: false,
                                                xtype:'combo',
												id:socio_shipper.id+'-edit-contacto-tcon_id',
								                store:store_tcon_id,
								                listConfig:{
													minWidth:300
												},
												typeAhead: true,
								                typeAheadDelay :1,
								                allowBlank: false,
								                queryMode: 'local',
								                triggerAction: 'all',
								                valueField: 'id_elemento',
				                        		displayField: 'descripcion',
								                emptyText: '[Seleccione]',
                                            }*/
										},
										{
											text:'Nombre',
											flex:2,
											dataIndex:'con_nombre',
											editor: {
                                                allowBlank: false
                                            }
										},
										{
											text:'Cargo',
											flex:1,
											dataIndex:'con_cargo',
											editor: {
                                                allowBlank: false
                                            }
										},
										{
											text:'Email',
											flex:1,
											dataIndex:'con_email',
											editor: {
                                                allowBlank: false,
                                                vtype: 'email',

                                            }
										},
										{
											text:'Piso',
											flex:1,
											dataIndex:'dir_npiso',
											editor: {
                                                allowBlank: false
                                            }
										},
										{
											text:'oficina',
											flex:1,
											dataIndex:'dir_office',
											editor: {
                                                allowBlank: false
                                            }
										},
										{
											text:'Telefono',
											flex:1,
											dataIndex:'telf',
											editor:{
												allowBlank:false
											}
										},
										{
											text:'Anexo',
											flex:1,
											dataIndex:'anexo',
											editor:{
												allowBlank:true
											}
										}
										/*telf:telf,
						    			anexo:anexo*/
										
								],
								defaults:{
									sortable: true
								}
							},
							selModel:{
								 selType: 'cellmodel'
							},
							viewConfig:{
								stripeRows:true,
								enableTextSelection: false,
								markDirty: false
							},
							trackMouseOver: true,
						}
				]
			});
			var georeferenciar = Ext.create('Ext.form.Panel',{
				layout:'fit',
				border:false,
				id:socio_shipper.id+'-panel-georeferenciar',
				tbar:[	
						'Codigo de Agencia',
						{
							xtype:'textfield',
							id:socio_shipper.id+'-map-codigo-activity',
							allowBlank:false
						},
						'-','Centro de Actividad:',
						{
							xtype:'textfield',
							id:socio_shipper.id+'-map-centro-activity',
							width:250,
							allowBlank:false
						},'-','Tipo',
						{
							xtype:'combo',
							id:socio_shipper.id+'-map-tipo-activity',
							allowBlank:false,
							store: Ext.create('Ext.data.Store',{
	                            fields:[
	                                {name: 'descripcion', type: 'string'},
	                                {name: 'id_elemento', type: 'int'}	                                
	                            ],
	                            proxy:{
	                                type: 'ajax',
	                                url: socio_shipper.url + 'scm_scm_tabla_detalle/',
	                                reader:{
	                                    type: 'json',
	                                    rootProperty: 'data'
	                                }
	                            }
	                        }),
	                        triggerAction:'all',
	                        queryMode: 'local',
	                        valueField: 'id_elemento',
	                        displayField: 'descripcion',
	                        listConfig:{
	                            minWidth: 150
	                        },
	                        width: 80,
	                        //forceSelection: true,
	                        //selectOnFocus:true,
	                        emptyText: '[ Seleccione ]',
	                        listeners:{
	                            afterrender: function(obj,record,options){
	                                obj.getStore().load({
	                                    params:{
	                                        vp_tab_id: 'TAG',
	                                        vp_shipper: 0
	                                    },
	                                    callback: function(){
	                                     //obj.setValue();  
	                                    }
	                                });
	                            }
	                        }
						},
						{
							text:'',
							icon: '/images/icon/add.png',
							id:socio_shipper.id+'-map-add-activity',
							tooltip:'Agrega Agencia a la lista de Items',
							listeners:{
								click:function(obj,e){
									var form = Ext.getCmp(socio_shipper.id+'-panel-georeferenciar').getForm();
									if (form.isValid()){
										var rec = Ext.getCmp(socio_shipper.id+'-direc').getValues();

										var shi_codigo = Ext.getCmp(socio_shipper.id+'-shi_codigo').getValue();
										var id_agencia = socio_shipper.id_agencia;
										var nom_age = Ext.getCmp(socio_shipper.id+'-map-centro-activity').getValue();
								        var cod_age = Ext.getCmp(socio_shipper.id+'-map-codigo-activity').getValue();
								        var tage_id = Ext.getCmp(socio_shipper.id+'-map-tipo-activity').getValue();

										var dir_id = socio_shipper.dir_id;
										var id_geo = rec[0].id_puerta == null ? 0:rec[0].id_puerta;
										var ciu_id = rec[0].ciu_id;
										var via_id = rec[0].id_via;
										var dir_calle = rec[0].dir_calle;
										var dir_numvia = rec[0].nro_via;
										var dir_referent = rec[0].referencia;
										var urb_id = rec[0].id_urb;
										var urb_nombre = rec[0].nombre_urb;
										var mz_id = rec[0].id_mza == null ? 0:rec[0].id_mza;
										var mz_nom = rec[0].nombre_mza;
										var num_lote = rec[0].nro_lote;
										var num_int = rec[0].nro_interno;	
										var lat_x = rec[0].coordenadas[0].lat;
										var lon_y = rec[0].coordenadas[0].lon;

										//if (socio_shipper.id_agencia == 0 ){
											Ext.Ajax.request({
												url:socio_shipper.url+'update_uno_scm_socionegocio_add_agencia/',
												params:{vp_shi_codigo:shi_codigo,vp_id_agencia:id_agencia,vp_nom_age:nom_age,vp_cod_age:cod_age,vp_tage_id:tage_id,vp_dir_id:dir_id,vp_id_geo:id_geo,vp_ciu_id:ciu_id,vp_via_id:via_id,vp_dir_calle:dir_calle,vp_dir_numvia:dir_numvia,
													vp_dir_referent:dir_referent,vp_urb_id:urb_id,vp_urb_nombre:urb_nombre,vp_mz_id:mz_id,vp_mz_nom:mz_nom,vp_num_lote:num_lote,
													vp_num_int:num_int,vp_lat:lat_x,vp_lon:lon_y},
												success:function(response,options){
													var res = Ext.decode(response.responseText);
													if (parseInt(res.data[0].error_sql) == 1){
														global.Msg({
															msg:res.data[0].error_info,
															icon:1,
															buttosn:1,
															fn:function(btn){
																Ext.getCmp(socio_shipper.id+'-direc').reset_global_vars();
																Ext.getCmp(socio_shipper.id+'-direc').reset();
																Ext.getCmp(socio_shipper.id+'-map-codigo-activity').setValue('');
																Ext.getCmp(socio_shipper.id+'-map-centro-activity').setValue('');
																Ext.getCmp(socio_shipper.id+'-map-tipo-activity').setValue('');

																Ext.getCmp(socio_shipper.id+'-tab-registro-shipper').setActiveTab(socio_shipper.id+'-tabActivity');
																Ext.getCmp(socio_shipper.id+'-grid-actividad').getStore().load({
																	params:{vp_shi_codigo:socio_shipper.shi_codigo},
																	callback:function(){
																		var cbo = Ext.getCmp(socio_shipper.id+'-id_agencia')
										                        		cbo.getStore().load({
										                        			params:{vp_shi_codigo:shi_codigo},
										                        		});
																	}
																});
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

										//}
									}


									/*if (form.isValid()){
										
											var records = Ext.getCmp(socio_shipper.id+'-direc').getValues();
											var ciu_id = records[0].ciu_id;
								            var id_geo = records[0].id_puerta;
								            var direccion = records[0].direccion;
								            var vp_ciu_px = records[0].coordenadas[0].lat;
								            var vp_ciu_py = records[0].coordenadas[0].lon;
								            var agencia = Ext.getCmp(socio_shipper.id+'-map-centro-activity').getValue();
								            var codigo = Ext.getCmp(socio_shipper.id+'-map-codigo-activity').getValue();
								            var referencia = '';//Ext.getCmp(socio_shipper.id+'-map-refeencia-activity').getValue();
								            //var id_geo = records[0].id_puerta;

								        if (socio_shipper.id_agencia == 0 ){    
											rowEditing.cancelEdit();
											var r = Ext.create('grid_actividad', {
												id_agencia:'0',
								                codigo:codigo,
								                nombre:agencia,
								                direccion:direccion,
								                distrito:ciu_id,
								                estado:'Activo',
								                op:'I',
								                x:vp_ciu_px,
								                y:vp_ciu_py,
								                referencia:referencia,
								                id_geo:id_geo

								            });
								            store_grid_actividad.insert(0,r);
								            //rowEditing.startEdit(0, 0);
								            Ext.getCmp(socio_shipper.id+'-direc').reset();
								            Ext.getCmp(socio_shipper.id+'-map-centro-activity').setValue('');
								            Ext.getCmp(socio_shipper.id+'-map-codigo-activity').setValue('');
								            Ext.getCmp(socio_shipper.id+'-map-refeencia-activity').setValue('');
								            Ext.getCmp(socio_shipper.id+'-tab-registro-shipper').setActiveTab(socio_shipper.id+'-tabActivity');
								            Ext.getCmp(socio_shipper.id+'-tabGeoreferenciar').setDisabled(true);
								            Ext.getCmp(socio_shipper.id+'-grid-actividad').getView().refresh();//esto es para refrescar ya que en elgunos caso no refresca automaticamente
										}else{
											Ext.Ajax.request({
												url:socio_shipper.url+'update_uno_scm_socionegocio_add_agencia/',
												params:{id_agencia:socio_shipper.id_agencia,codigo:codigo,nombre:agencia,direccion:direccion,distrito:ciu_id,estado:'Activo',op:'U',x:vp_ciu_px,y:vp_ciu_py,referencia:referencia,id_geo:id_geo},
												success:function(response,options){
													var res = Ext.decode(response.responseText);
													if (parseInt(res.data[0].error_sql)==0){
														global.Msg({
															msg:res.data[0].error_info,
															icon:1,
															buttosn:1,
															fn:function(btn){
																Ext.getCmp(socio_shipper.id+'-direc').reset();
																Ext.getCmp(socio_shipper.id+'-tab-registro-shipper').setActiveTab(socio_shipper.id+'-tabActivity');
																Ext.getCmp(socio_shipper.id+'-tabGeoreferenciar').setDisabled(true);
																Ext.getCmp(socio_shipper.id+'-grid-actividad').getStore().load({
																	params:{vp_shi_codigo:socio_shipper.shi_codigo}
																});
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
											


									}else{
										global.Msg({
											msg:'Debe Completar los datos',
											icon:0,
											buttosn:1,
											fn:function(btn){
											}
										});
									}*/
									
								}
							}
						}
				],
				bbar:[
						/*{
							xtype:'textarea',
							fieldLabel: 'Referencia',
							id:socio_shipper.id+'-map-refeencia-activity',
							margin:2,
							labelWidth:62,
							width: '100%',
                            anchor:'100%',
                            emptyText: 'Escribo un referencia...',
                            maxLength:200,
                            grow: true,
                            maxLengthText:'El maximo de caracteres permitidos para este campo es {0}',
                            enforceMaxLength:true

						}*/	
				],
				items:[
						{
							xtype:'findlocation',						
							id:socio_shipper.id+'-direc',
							trust:true,
							mapping: false
						}
						
				]
			});
			var panelX = Ext.create('Ext.form.Panel',{
				layout:'fit',
				border:false,
				bodyStyle: 'background: transparent',
				items:[
						{
							xtype:'uePanel',
							title:'Registor de Shipper',
							id:socio_shipper.id+'-uePanel',
							logo:'signup',
							layout:'fit',
							height:'100%',
							legend:'Ingresa los datos del Cliente.',
							bg: '#991919',
							items:[
									{
										region:'center',
										xtype:'tabpanel',
										id:socio_shipper.id+'-tab-registro-shipper',
										border:false,
										autoScroll:true,
										defaults:{
											border:false
										},
										items:[
												{
													title:'Shipper',
													id:socio_shipper.id+'-tabshipper',
													height:390,
													layout:{
														type:'fit'
													},
													items:[panel]
												},
												{
													title:'Centro de Actividad',
													id:socio_shipper.id+'-tabActivity',
													disabled:true,
													height:390,
													layout:{
														type:'fit'
													},
													items:[panelActividad]
												},
												{
													title:'GeoReferenciar',
													disabled:true,
													id:socio_shipper.id+'-tabGeoreferenciar',
													height:390,
													layout:{
														type:'fit'
													},
													items:[georeferenciar]
												},
												{
													title:'Contactos',
													disabled:true,
													id:socio_shipper.id+'tabcontactos',
													//height:390,
													layout:{
														type:'fit'
													},
													items:[panelContactos],
												}
										],
										listeners:{
											beforetabchange:function(tabPanel, newCard, oldCard, eOpts){
												//console.log(newCard.id);
												if (newCard.id ==socio_shipper.id+'-tabGeoreferenciar'){
													Ext.getCmp(socio_shipper.id+'-grabar').disable();
													Ext.getCmp(socio_shipper.id+'-new-file').disable();
												}
												else if ((socio_shipper.vp_tip=='E') && (newCard.id==socio_shipper.id+'-tabshipper')) {
													Ext.getCmp(socio_shipper.id+'-new-file').disable();
												}
												else if (newCard.id==socio_shipper.id+'-tabActivity'){
													//var grid = Ext.getCmp(socio_shipper.id+'-grid-actividad');
													//grid.getView().refresh();
													Ext.getCmp(socio_shipper.id+'-grabar').enable();
													Ext.getCmp(socio_shipper.id+'-new-file').disable();
													//Ext.getCmp(socio_shipper.id+'-new-file').enable();
							             			
												}else{
													
													Ext.getCmp(socio_shipper.id+'-grabar').enable();
													Ext.getCmp(socio_shipper.id+'-new-file').disable();
												}

												if (newCard.id == socio_shipper.id+'-tabshipper'){
													Ext.getCmp(socio_shipper.id+'-win').setHeight(300);
												}else{
													Ext.getCmp(socio_shipper.id+'-win').setHeight(500);
												}

												//Ext.getCmp(socio_shipper.id+'-win').center();
											},
											tabchange:function( grouptabPanel, newCard, oldCard, eOpts ){
												Ext.getCmp(socio_shipper.id+'-win').center();
											}
										},
									}
							]
						}
				]
			});
			var win = Ext.create('Ext.window.Window',{
				id:socio_shipper.id+'-win',
				header:false,
				bodyStyle: 'background: transparent',
				width:800,
				height:300,
				border:false,
				resizable:false,
				closable: false,
				layout:{
					type:'fit'
				},
				modal:true,
				items:[
						panelX
				],
				bbar:[	{
							xtype:'tbspacer',
							width:'42%'
						},
						{
							text:'',
							id:socio_shipper.id+'-grabar',
							icon: '/images/icon/save.png',		
							listeners:{
								beforerender:function(obj,opts){
								/*	global.permisos({
		                                    id_serv: 77, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: socio_shipper.id_menu,
		                                    fn: ['']
		                            });*/
								},
								click:function(obj){
									socio_shipper.add_shipper();
								}
							}
						},
						{
							text:'',
							icon:'/images/icon/new_file.ico',
							id:socio_shipper.id+'-new-file',
							listeners:{
								click:function(obj,e){
									socio_shipper.new_file();
								}
							}
						},
						{
							text:'',
							icon:'/images/icon/get_back.png',
							listeners:{
								click:function(obj,e){
									try {
										//Ext.getCmp(socio_shipper.id+'-tab-registro-shipper').setActiveTab(socio_shipper.id+'-tabGeoreferenciar');
										Ext.getCmp(socio_shipper.id+'-win').close();   
									}
									catch(err) {
									   console.log(err);
									}
								}
							}
						}
				],
				listeners:{
					afterrender:function(){
						var form = Ext.getCmp(socio_shipper.id+'-panel-shipper').getForm();
						if (socio_shipper.vp_tip=='E'){
							Ext.getCmp(socio_shipper.id+'-grid-actividad').getStore().load({
								params:{vp_shi_codigo:socio_shipper.shi_codigo}
							});
							Ext.getCmp(socio_shipper.id+'-grid-contactos').getStore().load({
								params:{vp_shi_codigo:socio_shipper.shi_codigo}
							});
							Ext.getCmp(socio_shipper.id+'-shi_codigo').setValue(socio_shipper.shi_codigo);
							Ext.getCmp(socio_shipper.id+'-shi_id').setValue(socio_shipper.shi_id);
							Ext.getCmp(socio_shipper.id+'-shi_nombre').setValue(socio_shipper.shi_nombre);
							Ext.getCmp(socio_shipper.id+'-fecha_ingreso').setValue(socio_shipper.fec_ingreso);
							Ext.getCmp(socio_shipper.id+'-sac').setValue(parseInt(socio_shipper.per_id_sac));
							Ext.getCmp(socio_shipper.id+'-vendedor').setValue(parseInt(socio_shipper.per_id_ven));
							Ext.getCmp(socio_shipper.id+'-rbtn-group-empresa').setValue({'socio_shipper-rbtn':socio_shipper.shi_empresa});

							var shi = socio_shipper.shi_codigo;
							var shi_id = socio_shipper.shi_id;
							var shi_nom = socio_shipper.shi_nombre;
							var fech = socio_shipper.fec_ingreso;
							var sac = socio_shipper.per_id_sac;
							var ven = parseInt(socio_shipper.per_id_ven);
							var emp = parseInt(socio_shipper.shi_empresa);
							

							/*console.log(shi);
							console.log(shi_id);
							console.log(shi_nom);
							console.log(fech);
							console.log(ven);
							console.log(sac);*/

							if (shi == '' || shi_id =='' || shi_nom =='' || fech == '' || sac == 0 || ven ==0 || ven == '' || emp == ''){
								Ext.getCmp(socio_shipper.id+'tabcontactos').setDisabled(true);
								Ext.getCmp(socio_shipper.id+'-tabActivity').setDisabled(true);	
							}else{
								Ext.getCmp(socio_shipper.id+'tabcontactos').setDisabled(false);
								Ext.getCmp(socio_shipper.id+'-tabActivity').setDisabled(false);	
							}


							
							
							Ext.getCmp(socio_shipper.id+'-new-file').disable();
						}
					}
				}
			
			}).show().center();
		},
		add_shipper:function(){
			var mask = new Ext.LoadMask(Ext.getCmp(inicio.id+'-tabContent'),{
				msg:'Guardando Datos....'
			});
			var form = Ext.getCmp(socio_shipper.id+'-panel-shipper').getForm();
			if (form.isValid()){
				mask.show();
				//vp_tip char(1),vp_id_sne integer, vp_shipper integer,vp_shi_id char(2),vp_shi_nombre varchar(80),vp_fec_ing date,vp_tip_emp char(1),vp_per_id_ven integer,vp_per_id_sac integer
				var shi_codigo = Ext.getCmp(socio_shipper.id+'-shi_codigo').getValue();
				var shi_id = Ext.getCmp(socio_shipper.id+'-shi_id').getValue();
				var shi_nombre = Ext.getCmp(socio_shipper.id+'-shi_nombre').getValue();
				var fecha_ingreso = Ext.getCmp(socio_shipper.id+'-fecha_ingreso').getRawValue();
				var per_id_ven = Ext.getCmp(socio_shipper.id+'-vendedor').getValue();
				var per_id_sac = Ext.getCmp(socio_shipper.id+'-sac').getValue();
				var emp = Ext.getCmp(socio_shipper.id+'-rbtn-group-empresa').getValue();
				var tip_emp = emp['socio_shipper-rbtn'];

				var id_sne = socio_shipper.vl_id_sne;
				var vp_tip = socio_shipper.vp_tip;
				//socio_shipper.leer_grid_activity();
				socio_shipper.leer_grid_contactos();
				//vp_tip_emp:tip_emp
				Ext.Ajax.request({
					url:socio_shipper.url+'scm_scm_socionegocio_add_shipper/',
					params:{vp_tip:vp_tip,vp_id_sne:id_sne,vp_shi_codigo:shi_codigo,vp_shi_id:shi_id,vp_fec_ing:fecha_ingreso,vp_shi_nombre:shi_nombre,vp_tip_emp:tip_emp,vp_per_id_ven:per_id_ven,vp_per_id_sac:per_id_sac,vp_agencias:socio_shipper.recordsToSend,vp_contactos:socio_shipper.recordsToSendContactos},
					success:function(response,options){
						var res = Ext.decode(response.responseText);
						mask.hide();
						//console.log(res.data[0].error_info);
						if (parseInt(res.data[0].error_sql)==0){
							global.Msg({
								msg:res.data[0].error_info,
								icon:1,
								buttosn:1,
								fn:function(btn){
									var obj = Ext.getCmp(socionegocio.id+'socio_shipper-grid');
									Ext.getCmp(socio_shipper.id+'-win').close();
									obj.getStore().load({
										params:{vp_id_sne:id_sne},
										callback:function(){
										}
									});

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
					msg:'Debe Completar los datos',
					icon:0,
					buttosn:1,
					fn:function(btn){
					}
				});
			}	
		},
		leer_grid_activity:function(){
			/*var modified = Ext.getCmp(socio_shipper.id+'-grid-actividad').getStore().getModifiedRecords();
			var recordsToSend = [];
			if(!Ext.isEmpty(modified)){
				Ext.each(modified,function(re){
					recordsToSend.push({id_agencia:re.data.id_agencia,codigo:re.data.codigo,nombre:re.data.nombre,direccion:re.data.direccion,distrito:parseInt(re.data.distrito),op:re.data.op,x:re.data.x,y:re.data.y,referencia:re.data.referencia,id_geo:re.data.id_geo});
				});
				socio_shipper.recordsToSend = Ext.encode(recordsToSend);
				//console.log(socio_shipper.recordsToSend);
			}*/
		},
		leer_grid_contactos:function(){
			var modified = Ext.getCmp(socio_shipper.id+'-grid-contactos').getStore().getModifiedRecords();
			var recordsToSendContactos = [];
			if(!Ext.isEmpty(modified)){
				Ext.each(modified,function(re){
					recordsToSendContactos.push({id_contacto:re.data.id_contacto,shi_codigo:re.data.shi_codigo,con_codigo:re.data.con_codigo,id_agencia:re.data.id_agencia,tcon_id:re.data.tcon_id,con_nombre:re.data.con_nombre,con_cargo:re.data.con_cargo,con_email:re.data.con_email,dir_npiso:re.data.dir_npiso,dir_office:re.data.dir_office,telf:re.data.telf,anexo:re.data.anexo});
				});
				socio_shipper.recordsToSendContactos = Ext.encode(recordsToSendContactos);
				//console.log(socio_shipper.recordsToSendContactos);
				
			}
		},
		editar_agencia:function(id_agencia,cod_age,nom_age,dir_id,tip_age){
			socio_shipper.id_agencia = id_agencia;
			socio_shipper.dir_id = dir_id;
			var cbo = Ext.getCmp(socio_shipper.id+'-map-tipo-activity');
			//console.log(dir_id);
			//console.log(tip_age);
			Ext.getCmp(socio_shipper.id+'-tabGeoreferenciar').setDisabled(false);
			Ext.getCmp(socio_shipper.id+'-tab-registro-shipper').setActiveTab(socio_shipper.id+'-tabGeoreferenciar');
			Ext.getCmp(socio_shipper.id+'-map-centro-activity').setValue(nom_age);
			Ext.getCmp(socio_shipper.id+'-map-codigo-activity').setValue(cod_age);
			
			cbo.getStore().load({
	            params:{
	                vp_tab_id: 'TAG',
	                vp_shipper: 0
	            },callback:function(){
	            	cbo.setValue(tip_age);
	            }
            });
			Ext.getCmp(socio_shipper.id+'-map-codigo-activity').setReadOnly(true);
			Ext.getCmp(socio_shipper.id+'-direc').setGeoLocalizar({dir_id:dir_id});
			
			

		},
		limpiar_contacto:function(){
				Ext.getCmp(socio_shipper.id+'-con_codigo').setValue('');
			    Ext.getCmp(socio_shipper.id+'-con_nombre').setValue('');
		    	Ext.getCmp(socio_shipper.id+'-con_cargo').setValue('');
		    	Ext.getCmp(socio_shipper.id+'-con_email').setValue('');
		    	Ext.getCmp(socio_shipper.id+'-dir_npiso').setValue('');
		    	Ext.getCmp(socio_shipper.id+'-dir_office').setValue('');
		    	Ext.getCmp(socio_shipper.id+'-id_agencia').setValue('');
		    	Ext.getCmp(socio_shipper.id+'-tcon_id').setValue('');
		    	Ext.getCmp(socio_shipper.id+'-tel').setValue('');
				Ext.getCmp(socio_shipper.id+'-anexo').setValue('');
				socio_shipper.id_contacto = '0';
		},
		stado_contacto:function(id_contacto,stado){
			Ext.Ajax.request({
				url:socio_shipper.url+'scm_scm_socionegocio_estado_contacto/',
				params:{vp_id_contacto:id_contacto,vp_stado:stado},
				success:function(response,options){
					var res = Ext.decode(response.responseText);
					if (parseInt(res.data[0].error_sql)==0){
						global.Msg({
							msg:res.data[0].error_info,
							icon:1,
							buttosn:1,
							fn:function(btn){
								Ext.getCmp(socio_shipper.id+'-grid-contactos').getStore().load({
									params:{vp_shi_codigo:socio_shipper.shi_codigo}
								});
							}
						});
					}else{
						global.Msg({
							msg:res.data[0].error_info,
							icon:0,
							fn:function(btn){

							}
						});
					}
				}
			});
		},
		stado_agencia:function(id_agencia,stado){
			Ext.Ajax.request({
				url:socio_shipper.url+'scm_scm_socionegocio_estado_activity/',
				params:{vp_id_agencia:id_agencia,vp_stado:stado},
				success:function(response,options){
					var res = Ext.decode(response.responseText);
					if (parseInt(res.data[0].error_sql)==0){
						global.Msg({
							msg:res.data[0].error_info,
							icon:1,
							buttosn:1,
							fn:function(btn){
								Ext.getCmp(socio_shipper.id+'-grid-actividad').getStore().load({
									params:{vp_shi_codigo:socio_shipper.shi_codigo}
								});
							}
						});
					}else{
						global.Msg({
							msg:res.data[0].error_info,
							icon:0,
							fn:function(btn){

							}
						});
					}
				}
			});
		},
		new_file:function(){
			Ext.getCmp(socio_shipper.id+'-shi_codigo').setValue('');
			Ext.getCmp(socio_shipper.id+'-shi_id').setValue('');
			Ext.getCmp(socio_shipper.id+'-shi_nombre').setValue('');
			Ext.getCmp(socio_shipper.id+'-fecha_ingreso').setValue('');
			Ext.getCmp(socio_shipper.id+'-sac').setValue('');
			Ext.getCmp(socio_shipper.id+'-vendedor').setValue('');
			//socio_shipper.limpiar_contacto();
		},
		recupera_shipper:function(vp_shi_codigo){
			var mask = new Ext.LoadMask(Ext.getCmp(socio_shipper.id+'-win'),{
					msg:'Recuperando Datos....'
			});
			mask.show();
			Ext.Ajax.request({
				url:socio_shipper.url+'scm_scm_socionegocio_select_shipper/',
				params:{vp_shi_codigo:vp_shi_codigo},
				success:function(response,options){
					mask.hide();
					var res = Ext.decode(response.responseText);
					if (parseInt(res.data[0].error_sql)==0){
						Ext.getCmp(socio_shipper.id+'-shi_id').setValue(res.data[0].shi_id);
						Ext.getCmp(socio_shipper.id+'-shi_nombre').setValue(res.data[0].shi_nombre);
						Ext.getCmp(socio_shipper.id+'-fecha_ingreso').setValue(res.data[0].fec_ingreso);
					}
				}
			});
		},
		setEditarContacto:function(rowIndex){
			Ext.getCmp(socio_shipper.id+'-contenedor').show();
			Ext.getCmp(socio_shipper.id+'-grid-contactos').setHeight(260);
			socio_shipper.limpiar_contacto();
			
			var grid = Ext.getCmp(socio_shipper.id+'-grid-contactos');
			var rec =  grid.getStore().getAt(rowIndex);
			Ext.getCmp(socio_shipper.id+'-con_codigo').setValue(rec.get('con_codigo'));
		    Ext.getCmp(socio_shipper.id+'-con_nombre').setValue(rec.get('con_nombre'));
	    	Ext.getCmp(socio_shipper.id+'-con_cargo').setValue(rec.get('con_cargo'));
	    	Ext.getCmp(socio_shipper.id+'-con_email').setValue(rec.get('con_email'));
	    	Ext.getCmp(socio_shipper.id+'-dir_npiso').setValue(rec.get('dir_npiso'));
	    	Ext.getCmp(socio_shipper.id+'-dir_office').setValue(rec.get('dir_office'));
	    	Ext.getCmp(socio_shipper.id+'-id_agencia').setValue(rec.get('id_agencia'));
	    	Ext.getCmp(socio_shipper.id+'-tcon_id').setValue(rec.get('tcon_id'));
	    	Ext.getCmp(socio_shipper.id+'-tel').setValue(rec.get('telf'));
	    	Ext.getCmp(socio_shipper.id+'-anexo').setValue(rec.get('anexo'));
	    	socio_shipper.id_contacto = rec.get('id_contacto');
		},
		add_contacto:function(){
			var form = Ext.getCmp(socio_shipper.id+'-panel-contactos').getForm();
			if (!form.isValid()){
				global.Msg({
					msg:'Debes Completar los datos',
					icon:0,
					fn:function(btn){

					}
				});
				return;
			}
			Ext.getCmp(socio_shipper.id+'-contenedor').setVisible(false);
			var con_codigo =	Ext.getCmp(socio_shipper.id+'-con_codigo').getValue();
		    var con_nombre =	Ext.getCmp(socio_shipper.id+'-con_nombre').getValue();
	    	var con_cargo  =	Ext.getCmp(socio_shipper.id+'-con_cargo').getValue();
	    	var con_email  =	Ext.getCmp(socio_shipper.id+'-con_email').getValue();
	    	var dir_npiso  =	Ext.getCmp(socio_shipper.id+'-dir_npiso').getValue();
	    	var dir_office =	Ext.getCmp(socio_shipper.id+'-dir_office').getValue();
	    	var id_agencia =	Ext.getCmp(socio_shipper.id+'-id_agencia').getValue();
	    	var age_nombre = 	Ext.getCmp(socio_shipper.id+'-id_agencia').getRawValue();
	    	var tcon_id    =	Ext.getCmp(socio_shipper.id+'-tcon_id').getValue();
	    	var tip_contac =	Ext.getCmp(socio_shipper.id+'-tcon_id').getRawValue();
	    	var telf 	   =	Ext.getCmp(socio_shipper.id+'-tel').getValue();
	    	var anexo 	   = 	Ext.getCmp(socio_shipper.id+'-anexo').getValue();
	    	
	    	var store = Ext.getCmp(socio_shipper.id+'-grid-contactos').getStore();
	    	//console.log(store);
	    	if (parseInt(socio_shipper.id_contacto) == 0 ){
	    		socio_shipper.rowEditing2.cancelEdit();
				var r = Ext.create('grid_contactos', {
					id_contacto:socio_shipper.id_contacto,
		    		shi_codigo:socio_shipper.shi_codigo,
		    		con_codigo:con_codigo,
		    		id_agencia:id_agencia,
		    		age_nombre:age_nombre,
		    		tcon_id:tcon_id,
		    		tip_contac:tip_contac,
		    		con_nombre:con_nombre,
		    		con_cargo:con_cargo,
		    		con_email:con_email,
		    		dir_npiso:dir_npiso,
		    		dir_office:dir_office,
		    		telf:telf,
		    		anexo:anexo
	            });
	            store.insert(0,r);
	            socio_shipper.limpiar_contacto();
	            Ext.getCmp(socio_shipper.id+'-grid-contactos').getView().refresh();
	    	}else{
	    		store.each(function(re,idx){
	    			if (parseInt(re.get('id_contacto')) == parseInt(socio_shipper.id_contacto)){
	    				re.set('id_contacto',socio_shipper.id_contacto);
			    		re.set('shi_codigo',socio_shipper.shi_codigo);
			    		re.set('con_codigo',con_codigo);
			    		re.set('id_agencia',id_agencia);
			    		re.set('age_nombre',age_nombre);
			    		re.set('tcon_id',tcon_id);
			    		re.set('tip_contac',tip_contac);
			    		re.set('con_nombre',con_nombre);
			    		re.set('con_cargo',con_cargo);
			    		re.set('con_email',con_email);
			    		re.set('dir_npiso',dir_npiso);
			    		re.set('dir_office',dir_office);
			    		re.set('telf',telf);
			    		re.set('anexo',anexo);	
	    			}
	    			
	    		});
	    		socio_shipper.limpiar_contacto();
	    	}
				
		}
	}
	Ext.onReady(socio_shipper.init,socio_shipper);
</script>