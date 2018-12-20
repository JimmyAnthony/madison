<script type="text/javascript">
	var sys_permiss ={
		id: 'sys_permiss',
		id_menu:'<?php echo $p["id_menu"];?>',
		per_id:'<?php echo $p["vp_per_id"];?>',
		url: '/gestion/personal/',
		id_user:0,
		init:function(){
			var usr_sys = Ext.create('Ext.form.Panel',{
				id:sys_permiss.id+'-usr_sys',
				layout:'column',
				border:false,
				bodyStyle: 'background: transparent',
				bbar:[
						{
							text:'',
							id:sys_permiss.id+'-btn-graba-permiso-usuario',
							icon: '/images/icon/save.png',
							listeners:{
								click:function(obj){
									sys_permiss.grabar1();
								}
							}
						},
						{
							text:'',
							id:sys_permiss.id+'-salir',
							icon: '/images/icon/get_back.png',
							listeners:{
                                click: function(obj, e){
                   					Ext.getCmp(sys_permiss.id+'-win').close();
                                }
                            }
						}
				],
				items:[
						{
							xtype:'fieldset',
							title:'Datos del Usuario',
							columnWidth:1,
							layout:'column',
							margin:'5 0 0 0',
							padding:'0 5 5 5',
							defaults:{
								margin:'0 5 0 0'
							},
							items:[

									{
										xtype:'combo',
										id:sys_permiss.id+'-cbo-user',
										fieldLabel:'Codigo de Acceso',
										labelWidth:105,
										columnWidth:0.5,
										store:Ext.create('Ext.data.Store',{
											fields:[
													{name:'id_user',type:'int'},
													{name:'usr_codigo',type:'string'},
													{name:'usr_passwd',type:'string'},
													{name:'usr_perfil',type:'string'},
													{name:'usr_estado',type:'string'},
													{name:'editable',type:'int'}
											],
											proxy:{
												type:'ajax',
												url:sys_permiss.url+'scm_scm_gestion_personal_get_usuario/',
												reader:{
													type:'json',
													rootProperty:'data'
												}
											}
										}),
										queryMode:'local',
										valueField:'id_user',
										displayField:'usr_codigo',
										listConfig:{
											minWidth:350
										},
										allowBlank:false,
										emptyText:'[ Seleccione ]',
										listeners:{
											afterrender:function(obj,record,options){
												obj.getStore().load({
													params:{vp_per_id:sys_permiss.per_id,vp_new:'1'},
												});
											},
											select:function( obj, record, eOpts ){
												if (record.get('editable')==1){
													sys_permiss.setUsuarios(record);

												}else{
													global.Msg({
														msg:'Usted No tiene Permisos Suficientes para editar este Usuario',
														icon:1,
														buttons:1,
														fn:function(btn){
															obj.clearValue();
															obj.getStore().load({
																params:{vp_per_id:sys_permiss.per_id,vp_new:'1'},
															});
														}
													});
												}
											}
										}

									},
									{
										xtype:'textfield',
										id:sys_permiss.id+'-txt-usuarios',
										hidden:true,
										fieldLabel:'Codigo de Acceso',
										labelWidth:105,
										enforceMaxLength:true,
										maxLength:15,
										maxLengthText:'el máximo de caracteres permitidos es {0}',
										columnWidth:0.5,
										enableKeyEvents:true,
										listeners:{
											keyup:function( obj, e, eOpts ){
												var valor = obj.getValue();
												//console.log(valor);
												valor = valor.toUpperCase();
												obj.setValue(valor);
											}
											
										}
									},
									{
										xtype:'textfield',
										id:sys_permiss.id+'-txtpasword',
										width:50,
										hidden:true,
										labelWidth:30,
										fieldLabel:'Clave',
										//allowBlank:false,
										inputType: 'password',
										columnWidth:0.5,
										maskRe : /[a-zA-Z0-9]$/,
									},
							]
						},
						{
							xtype:'fieldset',
							title:'Perfil de Acceso al Sistema',
							columnWidth:1,
							layout:'column',
							margin:'5 0 0 0',
							padding:'0 5 5 5',
							defaults:{
								margin:'0 5 0 0'
							},

							items:[
									{
										xtype:'combo',
										id:sys_permiss.id+'-perfil-acceso',
										fieldLabel:'Perfil de Acceso',
										labelWidth:95,
										columnWidth:0.7,
										store:Ext.create('Ext.data.Store',{
											autoLoad:true,
											fields:[
													{name:'descripcion',type:'string'},
													{name:'id_elemento',type:'int'}
											],
											proxy:{
												type:'ajax',
												url:sys_permiss.url+'scm_scm_gestion_personal_perfil/',
												reader:{
													type:'json',
													rootProperty:'data'
												}
											}
										}),
										queryMode:'local',
										valueField:'id_elemento',
										displayField:'descripcion',
										listConfig:{
											minWidth:200,
										},
										width:100,
										empyText:'[ Seleccione]',
										allowBlank:false,
										listeners:{
											afterrender:function(obj){
												
											},
											beforeselect:function( obj, record, index, eOpts ){ 
											//select:function( obj, record, eOpts ){
												var cbo = Ext.getCmp(sys_permiss.id+'-template-permiso');
												cbo.setValue('');
												cbo.getStore().load({
													params:{vp_tabid:'TME',vp_shi_codigo:record.get('id_elemento')},
												});

											}
										}
									},
									{
										xtype:'radiogroup',
										id:sys_permiss.id+'-estado-group',
										fieldLabel:'Estado',
										labelWidth:40,
										columnWidth:0.3,
										allowBlank:false,
										columns:3,
			                            vertical:true,
			                            items:[
			                            		{boxLabel:'Activo',name:sys_permiss.id+'-rbtn',inputValue:1, width:60/*,checked:true*/},
			                            		{boxLabel:'Inactivo',name:sys_permiss.id+'-rbtn',inputValue:0, width:60/*,checked:true*/},
			                            ]
									}
							]
						},
						{
							xtype:'fieldset',
							title:'Menu de Acceso al Sistema',
							columnWidth:1,
							layout:'column',
							margin:'5 0 0 0',
							padding:'0 5 5 5',
							defaults:{
								margin:'0 5 0 0',
							},
							items:[
									{
										xtype:'combo',
										id:sys_permiss.id+'-template-permiso',
										fieldLabel:'Permisos',
										labelWidth:85,
										columnWidth:1,
										store:Ext.create('Ext.data.Store',{
											fields:[
													{name:'descripcion',type:'string'},
													{name:'id_elemento',type:'int'},
													{name:'des_corto',type:'string'}                  
											],
											proxy:{
												type:'ajax',
												url:sys_permiss.url+'scm_scm_tabla_detalle/',
												reader:{
													type:'json',
													rootProperty:'data'
												}
											}
										}),
										queryMode:'local',
										valueField:'id_elemento',
										displayField:'descripcion',
										listConfig:{
											minWidth:200
										},
										width:100,
										empyText:'[ Seleccione]',
										allowBlank:false,
										listeners:{
											select:function( obj, record, eOpts ){
												var grid = Ext.getCmp(sys_permiss.id+'-grid-permisos');
												grid.getStore().load({
													params:{vp_tpl:record.get('id_elemento'),vp_id_user:sys_permiss.id_user}
												});

											}
										}

									},
							]
						},
						{
							xtype:'grid',
							id:sys_permiss.id+'-grid-permisos',
							margin:'5 0 0 0',
							columnWidth:1,
							height:264,
							store:Ext.create('Ext.data.Store',{
								//autoLoad:true,
								fields:[
										{name:'id_service',type:'int'},
										{name:'sis_nombre',type:'string'},
										{name:'mod_nombre',type:'string'},
										{name:'menu_nombre',type:'string'},
										{name:'serv_nombre',type:'string'},
										
										{name:'chk',type:'boolean'}
								],
								proxy:{
									type:'ajax',
									url:sys_permiss.url+'scm_scm_gestion_personal_servicio_menu/',
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
											text:'Sistema',
											dataIndex:'sis_nombre',
											flex:1
										},
										{
											text:'Modulo',
											dataIndex:'mod_nombre',
											flex:1
										},
										{
											text:'Menu',
											dataIndex:'menu_nombre',
											flex:1
										},
										{
											text:'Acción',
											dataIndex:'serv_nombre',
											flex:1
										},
										
										/*{
											text:'Servicio',
											dataIndex:'id_service',
											flex:1
										},*/
										{
											text:'Estado',
											dataIndex:'chk',
											xtype:'checkcolumn',
											flex:0.5
										}
								]
							}
						}
						
						
				]
			});

			var usr_nego = Ext.create('Ext.form.Panel',{
				id:sys_permiss.id+'-usr_nego',
				layout: 'column',
				border:false,
				bodyStyle: 'background: transparent',
				bbar:[
						{
							text:'',
							id:sys_permiss.id+'-btn-graba-orden',
							icon: '/images/icon/save.png',
							listeners:{
								click:function(obj){
									sys_permiss.grabar2();
								}
							}
						},
						{
							text:'',
							id:sys_permiss.id+'-salir-orden',
							icon: '/images/icon/get_back.png',
							listeners:{
                                click: function(obj, e){
                   					Ext.getCmp(sys_permiss.id+'-win').close();
                                }
                            }
						}
				],
				items:[
						/*{
							xtype:'combo',
							id:sys_permiss.id+'-cbo-user-servicio',
							fieldLabel:'Codigo de Acceso',
							labelWidth:105,
							columnWidth:0.5,
							store:Ext.create('Ext.data.Store',{
								fields:[
										{name:'id_user',type:'int'},
										{name:'usr_codigo',type:'string'},
										{name:'usr_passwd',type:'string'},
										{name:'usr_perfil',type:'string'},
										{name:'usr_estado',type:'string'},
										{name:'editable',type:'int'}
								],
								proxy:{
									type:'ajax',
									url:sys_permiss.url+'scm_scm_gestion_personal_get_usuario/',
									reader:{
										type:'json',
										rootProperty:'data'
									}
								}
							}),
							queryMode:'local',
							valueField:'id_user',
							displayField:'usr_codigo',
							listConfig:{
								minWidth:350
							},
							allowBlank:false,
							emptyText:'[ Seleccione ]',
							listeners:{
								afterrender:function(obj,record,options){
									obj.getStore().load({
										params:{vp_per_id:sys_permiss.per_id,vp_new:'0'},
									});
								},
								select:function( obj, record, eOpts ){
									if (record.get('editable')==1){
										//sys_permiss.setUsuarios(record);

									}else{
										global.Msg({
											msg:'Usted No tiene Permisos Suficientes para editar este Usuario',
											ico:1,
											buttons:1,
											fn:function(btn){
												
											}
										});
									}
								}
							}

						},*/	
						{
							xtype:'combo',
							id:sys_permiss.id+'shipper_servicio',
							fieldLabel:'Shipper',
							labelWidth:80,
							columnWidth:0.5,
							store:Ext.create('Ext.data.Store',{
								fields:[
										{name: 'shi_codigo', type: 'int'},
		                                {name: 'shi_nombre', type: 'string'},
		                                {name: 'shi_id', type: 'string'}
								],
								proxy:{
									type:'ajax',
									url:sys_permiss.url+'get_usr_sis_shipper/',
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
										});
									},
									beforeselect:function(obj,record){
										var  grid = Ext.getCmp(sys_permiss.id+'-grid-orden');
										grid.getStore().load({
											params:{vp_per_id:sys_permiss.per_id,vp_shi_codigo:record.get('shi_codigo')},
											callback:function(){
												Ext.getCmp(sys_permiss.id+'chk-header').autochekedAll();
											}
										});
									}
								}
						},
						{
							xtype:'grid',
							id:sys_permiss.id+'-grid-orden',
							columnWidth:1,
							height:398,
							store:Ext.create('Ext.data.Store',{
								fields:[
									{name:'id_orden',type:'int'},
									{name:'shi_codigo',type:'int'},
									{name:'con_descri',type:'string'},
									{name:'pro_codigo',type:'string'},
									{name:'pro_descri',type:'string'},
									{name:'lin_descri',type:'string'},
									{name:'estado',type:'boolean'}                                                                              
								],
								proxy:{
									type:'ajax',
									url:sys_permiss.url+'scm_scm_gestion_personal_servicio_orden/',
									reader:{
										type:'json',
										root:'data'
									}
								}
							}),
							columnLines:true,
							columns:{
								items:[
										/*{
											text:'Shipper',
											dataIndex:'shi_codigo',
											flex:2
										},*/
										{
											text:'Servicio',
											dataIndex:'con_descri',
											flex:2
										},
										{
											text:'Producto',
											dataIndex:'pro_codigo',
											flex:1
										},
										{
											text:'Linea',
											dataIndex:'lin_descri',
											flex:2
										},
										/*{
											text:'<input type="checkbox" id="sys_permiss_chk" onClick="sys_permiss.setautoChekedOrd();">',
											dataIndex:'estado',
											xtype:'checkcolumn',
											flex:0.5,
											menuDisabled: true,
											sortable: false,
											draggable: false,
											hideable: false,
											//cls: Ext.baseCSSPrefix + 'column-header-checkbox',
											listeners:{
												afterrender:function( obj, eOpts ){
													//console.log(obj.findParentByType( 'grid' ).id);
													//console.log(obj);
												}
											}

										},*/
										{
											xtype:'checkcolumnheader',
											dataIndex:'estado',
											id:sys_permiss.id+'chk-header',
											//fn:'sys_permiss.setheader',
											menuDisabled: true,
											sortable: false,
											draggable: false,
											hideable: false,
										}

								]
							}
							

						}
						
				]
			});

			var usr_area = Ext.create('Ext.form.Panel',{
				id:sys_permiss.id+'-usr_area',
				layout:'column',
				border:false,
				bodyStyle: 'background: transparent',
				bbar:[
						{
							text:'',
							id:sys_permiss.id+'-btn-graba-area-serv',
							icon: '/images/icon/save.png',
							listeners:{
								click:function(obj){
									sys_permiss.grabar3();
								}
							}
						},
						{
							text:'',
							id:sys_permiss.id+'-salir-areas',
							icon: '/images/icon/get_back.png',
							listeners:{
                                click: function(obj, e){
                   					Ext.getCmp(sys_permiss.id+'-win').close();
                                }
                            }
						}
				],
				items:[
						{
							xtype:'grid',
							id:sys_permiss.id+'-grid-areas-serv',
							columnWidth:1,
							height:422,
							store:Ext.create('Ext.data.Store',{
								fields:[
									{name:'id_area',type:'int'},
									{name:'area_nombre',type:'string'},
									{name:'estado',type:'boolean'},

								],
								
								proxy:{
									type:'ajax',
									url:sys_permiss.url+'scm_scm_gestion_personal_area_select/',
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
											text:'Nombre de Area',
											dataIndex:'area_nombre',
											flex:1
										},
										{
											
											xtype:'checkcolumnheader',
											id:sys_permiss.id+'-area-estado',
											width:60,
											dataIndex:'estado'
										}
								]
							},
							listeners:{
								afterrender:function(obj){
									//console.log(sys_permiss.per_id);
									obj.getStore().load({
										params:{vp_per_id:sys_permiss.per_id},
										callback:function(){
											Ext.getCmp(sys_permiss.id+'-area-estado').autochekedAll();											
										}
									});
								}
							}

						}
				]
			});

			var panel = Ext.create('Ext.form.Panel',{
				id:sys_permiss.id+'-panel-principal',
				layout: 'fit',
				border:false,
				items:[
						{
							xtype: 'uePanel',
		                    title: 'Permisos del Sistema',
		                    logo: 'signup',//upload
		                    legend: 'Agrega los datos del usuario a la Web.',
		                    bg: '#991919',
		                    items:[
		                    		{
		                    			region: 'center',
		                    			xtype:'tabpanel',
			                    		id:sys_permiss.id+'-tabpanel',
			                    		iconCls:'',
			                    		border:false,
	                        			autoScroll:true,
	                        			defaults:{
	                        				border:false
	                        			},
	            						items:[
	            								{
	            									title:'Usuarios del Sistema',
	            									id:sys_permiss.id+'-usr-sys',
	            									//layout:'fit',
	            									layout:{
	                        							type:'fit'
	                        						},
	                        						items:[usr_sys]
	            								},
	            								{
	            									title:'Servicios',
	            									id:sys_permiss.id+'-usr-nego',
	            									//layout:'fit',
	            									layout:{
	                        							type:'fit'
	                        						},
	                        						items:[usr_nego]
	            								},
	            								{
	            									title:'Areas de Servicio',
	            									id:sys_permiss.id+'-area-serv',
	            									layout:{
	            										type:'fit'
	            									},
	            									items:[usr_area]
	            								}
	            						],
	            						listeners:{

	            						}
		                    		}
		                    ]
						}
				]
			});
			

			

			Ext.create('Ext.window.Window',{
				id:sys_permiss.id+'-win',
				title:'Permisos del Sistema',
				cls:'popup_show',
				height: 535,
				width: 700,
				modal: true,
				closable: false,
				header: false,
				resizable:false,				
				layout:{
						type:'fit'
				},
				items:[
						panel
				],
				listeners:{

				}
			}).show().center();
		},
		setUsuarios:function(record){
			var id_user = parseInt(record.get('id_user'));
			sys_permiss.id_user=id_user;
			var usr_codigo = record.get('usr_codigo');
			var usr_passwd = record.get('usr_passwd');
			var usr_perfil = record.get('usr_perfil').toString();
			var usr_estado = record.get('usr_estado');
			var per_id = sys_permiss.per_id;

			Ext.getCmp(sys_permiss.id+'-perfil-acceso').setValue(usr_perfil);
			var cbop = Ext.getCmp(sys_permiss.id+'-perfil-acceso').getValue();
			var cbo = Ext.getCmp(sys_permiss.id+'-template-permiso');
			cbo.setValue('');
			cbo.getStore().load({
				params:{vp_tabid:'TME',vp_shi_codigo:cbop},
			});

			Ext.getCmp(sys_permiss.id+'-estado-group').setValue({'sys_permiss-rbtn': usr_estado});

			if (id_user > 0){
			}else{
				Ext.getCmp(sys_permiss.id+'-cbo-user').setVisible(false);
				Ext.getCmp(sys_permiss.id+'-txt-usuarios').setVisible(true);
				Ext.getCmp(sys_permiss.id+'-txtpasword').setVisible(true);
			}
		},
		/*setheader:function(){
			Ext.getCmp(sys_permiss.id+'chk-header').setautoChekedOrd();
		},*/
		read_grid_permiso:function(){
			var grid = Ext.getCmp(sys_permiss.id+'-grid-permisos');
			var store =grid.getStore();
			var arrayData=[];
			if (store.getCount() > 0 ){
				for(var i =0; i < store.getCount();++i){
					var rec = store.getAt(i);
					//console.log(rec.data.id_service);
					var id_service = rec.data.id_service;
					var chk = rec.data.chk == true ?1:0;
					arrayData.push({id_service:id_service,chk:chk});// 
				}
			}
			return  Ext.encode(arrayData);


		},
		read_grid_servici:function(){
			var grid = Ext.getCmp(sys_permiss.id+'-grid-orden');
			var store = grid.getStore();
			var arrayData=[];
			if (store.getCount() > 0 ){
				for(var i =0; i < store.getCount();++i){
					var rec = store.getAt(i);
					//console.log(rec.data);
					var id_orden = rec.data.id_orden;
					var estado = rec.data.estado == true ? '1':'0';
					arrayData.push({id_orden:id_orden,estado:estado});
				}
			}
			return  Ext.encode(arrayData);
		},
		/*autoChekedOrd:function(chk){
			var grid =Ext.getCmp(sys_permiss.id+'-grid-orden')
			var store = grid.getStore();
			store.each(function(record,idx){
				record.set('estado',chk);
				record.commit();
				//grid.getView().refresh(); 
			});
			grid.getView().refresh();
		},*/
		/*setautoChekedOrd:function(){
			var data = document.getElementById("sys_permiss_chk").checked;
			var grid =Ext.getCmp(sys_permiss.id+'-grid-orden')
			var store = grid.getStore();
			if (data){
				sys_permiss.autoChekedOrd('true');
			}else{
				sys_permiss.autoChekedOrd('false');
			}
		},*/
		/*autochekedAll:function(){
			var grid =Ext.getCmp(sys_permiss.id+'-grid-orden')
			var store = grid.getStore();
			var cnt_grid = store.getCount();
			var cnt_stado = 0;
			var st= 'estado';
			if (store.getCount() > 0 ){
				for(var i =0; i < store.getCount();++i){
					var rec = store.getAt(i);
					console.log(rec.get(st));

					var estado = rec.data.estado == true ? 1:0;
					if (estado > 0){
						cnt_stado = cnt_stado+1;
					}
				}
			}
			//console.log(estado);
			//console.log(cnt_grid);
			if (cnt_stado == cnt_grid){
				document.getElementById("sys_permiss_chk").checked = true;
			}
		},*/
		grabar1:function(){
			var cbo_user = Ext.getCmp(sys_permiss.id+'-cbo-user').getValue();
			var txt_user = Ext.getCmp(sys_permiss.id+'-txt-usuarios').getValue();
			var txt_pass = Ext.getCmp(sys_permiss.id+'-txtpasword').getValue();
			var perf_acceso = Ext.getCmp(sys_permiss.id+'-perfil-acceso').getValue();
			var estado = Ext.getCmp(sys_permiss.id+'-estado-group').getValue()['sys_permiss-rbtn'];
			var permiso = Ext.getCmp(sys_permiss.id+'-template-permiso').getValue();
			var form = Ext.getCmp(sys_permiss.id+'-usr_sys').getForm();
			var per_id = sys_permiss.per_id;
			var grid = sys_permiss.read_grid_permiso();
			//console.log(grid);
			if (form.isValid()){
				var mask = new Ext.LoadMask(Ext.getCmp(sys_permiss.id+'-win'),{
					msg:'Grabando Permisos...'
				});
				mask.show();
				Ext.Ajax.request({
					url:sys_permiss.url+'scm_scm_gestion_personal_add_udp_usuario/',
					params:{cbo_user:cbo_user,txt_user:txt_user,txt_pass:txt_pass,perf_acceso:perf_acceso,estado:estado,permiso:permiso,per_id:per_id,grid:grid},
					success:function(response,options){
						var res = Ext.decode(response.responseText);
						console.log(res);
						mask.hide();
						if (parseInt(res.data[0].error_sql)== 0){
							global.Msg({
								msg:res.data[0].error_info,
								icon:1,
								fn:function(){
								}
							});
						}else{
							global.Msg({
								msg:res.data[0].error_info,
								icon:0,
								fn:function(){
								}
							});
						}
					}
				});
			}else{
				global.Msg({
					msg:'Debe Completar los datos',
					icon:0,
					fn:function(){
					}
				});
			}
			
		},
		grabar2:function(){
			var grid = sys_permiss.read_grid_servici();
			//console.log(grid);
			var form = Ext.getCmp(sys_permiss.id+'-usr_nego').getForm();
			if (form.isValid()){
				var mask = new Ext.LoadMask(Ext.getCmp(sys_permiss.id+'-win'),{
					msg:'Grabando Permisos...'
				});
				mask.show();
				Ext.Ajax.request({
					url:sys_permiss.url+'scm_scm_gestion_personal_add_udp_servicio_orden/',
					params:{grid:grid,per_id:sys_permiss.per_id},
					success:function(response,options){
						var res = Ext.decode(response.responseText);
						//console.log(res);
						mask.hide();
						if (parseInt(res.data[0].error_sql)== 0){
							global.Msg({
								msg:res.data[0].error_info,
								icon:1,
								fn:function(){
								}
							});
						}else{
							global.Msg({
								msg:res.data[0].error_info,
								icon:0,
								fn:function(){
								}
							});
						}

					}
				});
			}else{
				global.Msg({
					msg:'Debe Completar los datos',
					icon:0,
					fn:function(){
					}
				});
			}
		},
		grabar3:function(){
			var grid = Ext.getCmp(sys_permiss.id+'-grid-areas-serv');
			var store = grid.getStore();
			var arrayData=[];
			var ajaxDecode;
			if (store.getCount() > 0 ){
				for(var i =0; i < store.getCount();++i){
					var rec = store.getAt(i);
					var id_area = rec.data.id_area;
					var estado = rec.data.estado == true ? '1':'0';
					//console.log(estado);
					arrayData.push({vp_per_id:sys_permiss.per_id,vp_id_area:id_area,vp_estado:estado});
				}
			}
			ajaxDecode = Ext.encode(arrayData);
			Ext.Ajax.request({
				url:sys_permiss.url+'scm_scm_gestion_personal_add_udp_p_area/',
				params:{grid:ajaxDecode},
				success:function(response,options){
					var res = Ext.decode(response.responseText).data[0];
					console.log(res);
					if (parseInt(res.error_sql) < 0){
						global.Msg({
							msg:res.error_info,
							icon:0,
							fn:function(){
							}
						});
					}else{
						global.Msg({
							msg:res.error_info,
							icon:1,
							fn:function(){
							}
						});
					}
				}

			});
			//console.log(ajaxDecode);
			//console.log(arrayData);
			//return  Ext.encode(arrayData);
		},
		sha1:function(str) {
		  //  discuss at: http://phpjs.org/functions/sha1/
		  // original by: Webtoolkit.info (http://www.webtoolkit.info/)
		  // improved by: Michael White (http://getsprink.com)
		  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		  //    input by: Brett Zamir (http://brett-zamir.me)
		  //   example 1: sha1('Kevin van Zonneveld');
		  //   returns 1: '54916d2e62f65b3afa6e192e6a601cdbe5cb5897'

		  var rotate_left = function (n, s) {
		    var t4 = (n << s) | (n >>> (32 - s));
		    return t4;
		  };

		  /*var lsb_hex = function (val) {
		   // Not in use; needed?
		    var str="";
		    var i;
		    var vh;
		    var vl;
		    for ( i=0; i<=6; i+=2 ) {
		      vh = (val>>>(i*4+4))&0x0f;
		      vl = (val>>>(i*4))&0x0f;
		      str += vh.toString(16) + vl.toString(16);
		    }
		    return str;
		  };*/

		  var cvt_hex = function (val) {
		    var str = '';
		    var i;
		    var v;

		    for (i = 7; i >= 0; i--) {
		      v = (val >>> (i * 4)) & 0x0f;
		      str += v.toString(16);
		    }
		    return str;
		  };

		  var blockstart;
		  var i, j;
		  var W = new Array(80);
		  var H0 = 0x67452301;
		  var H1 = 0xEFCDAB89;
		  var H2 = 0x98BADCFE;
		  var H3 = 0x10325476;
		  var H4 = 0xC3D2E1F0;
		  var A, B, C, D, E;
		  var temp;

		  // utf8_encode
		  str = unescape(encodeURIComponent(str));
		  var str_len = str.length;

		  var word_array = [];
		  for (i = 0; i < str_len - 3; i += 4) {
		    j = str.charCodeAt(i) << 24 | str.charCodeAt(i + 1) << 16 | str.charCodeAt(i + 2) << 8 | str.charCodeAt(i + 3);
		    word_array.push(j);
		  }

		  switch (str_len % 4) {
		  case 0:
		    i = 0x080000000;
		    break;
		  case 1:
		    i = str.charCodeAt(str_len - 1) << 24 | 0x0800000;
		    break;
		  case 2:
		    i = str.charCodeAt(str_len - 2) << 24 | str.charCodeAt(str_len - 1) << 16 | 0x08000;
		    break;
		  case 3:
		    i = str.charCodeAt(str_len - 3) << 24 | str.charCodeAt(str_len - 2) << 16 | str.charCodeAt(str_len - 1) <<
		      8 | 0x80;
		    break;
		  }

		  word_array.push(i);

		  while ((word_array.length % 16) != 14) {
		    word_array.push(0);
		  }

		  word_array.push(str_len >>> 29);
		  word_array.push((str_len << 3) & 0x0ffffffff);

		  for (blockstart = 0; blockstart < word_array.length; blockstart += 16) {
		    for (i = 0; i < 16; i++) {
		      W[i] = word_array[blockstart + i];
		    }
		    for (i = 16; i <= 79; i++) {
		      W[i] = rotate_left(W[i - 3] ^ W[i - 8] ^ W[i - 14] ^ W[i - 16], 1);
		    }

		    A = H0;
		    B = H1;
		    C = H2;
		    D = H3;
		    E = H4;

		    for (i = 0; i <= 19; i++) {
		      temp = (rotate_left(A, 5) + ((B & C) | (~B & D)) + E + W[i] + 0x5A827999) & 0x0ffffffff;
		      E = D;
		      D = C;
		      C = rotate_left(B, 30);
		      B = A;
		      A = temp;
		    }

		    for (i = 20; i <= 39; i++) {
		      temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0x6ED9EBA1) & 0x0ffffffff;
		      E = D;
		      D = C;
		      C = rotate_left(B, 30);
		      B = A;
		      A = temp;
		    }

		    for (i = 40; i <= 59; i++) {
		      temp = (rotate_left(A, 5) + ((B & C) | (B & D) | (C & D)) + E + W[i] + 0x8F1BBCDC) & 0x0ffffffff;
		      E = D;
		      D = C;
		      C = rotate_left(B, 30);
		      B = A;
		      A = temp;
		    }

		    for (i = 60; i <= 79; i++) {
		      temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0xCA62C1D6) & 0x0ffffffff;
		      E = D;
		      D = C;
		      C = rotate_left(B, 30);
		      B = A;
		      A = temp;
		    }

		    H0 = (H0 + A) & 0x0ffffffff;
		    H1 = (H1 + B) & 0x0ffffffff;
		    H2 = (H2 + C) & 0x0ffffffff;
		    H3 = (H3 + D) & 0x0ffffffff;
		    H4 = (H4 + E) & 0x0ffffffff;
		  }

		  temp = cvt_hex(H0) + cvt_hex(H1) + cvt_hex(H2) + cvt_hex(H3) + cvt_hex(H4);
		  return temp.toLowerCase();
		}
	}
	Ext.onReady(sys_permiss.init, sys_permiss);
</script>