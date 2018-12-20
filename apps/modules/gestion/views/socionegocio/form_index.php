<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('solicitar_envios-tab')){
		var socionegocio ={
			id:'socionegocio',
			id_menu:'<?php echo $p["id_menu"];?>',
			url:'/gestion/socionegocio/',
			usr_tipo:'<?php echo USR_TIPO;?>',
			vl_id_sne:0,
			init:function(){
				var socionegocio_grid = Ext.create('Ext.data.Store',{
					fields:[
						{name:'id_sne', type:'int'},
						{name:'sne_ruc', type:'string'},
						{name:'sne_nombre', type:'string'},
						{name:'tsec_id', type:'string'},
						{name:'sne_abc', type:'string'},
						{name:'sne_estado', type:'string'},
						{name:'tip_sec', type:'string'},
						{name:'tipo', type:'string'},
						{name:'sne_tipo', type:'string'},
						{name:'dir_factura', type:'string'},
						{name:'ciu_id', type:'int'},
						
						                                                
					],
					proxy:{
						type:'ajax',
						url:socionegocio.url+'scm_scm_socionegocio_ruc/',
						reader:{
							type:'json',
							root:'data',
						}
					}
				});

				var socio_shipper_grid = Ext.create('Ext.data.Store',{
					fields:[
						{name:'id_sne', type:'int'},
						{name:'shi_codigo', type:'int'},
						{name:'shi_estado', type:'string'},
						{name:'per_id_sac', type:'int'},
						{name:'shi_nombre', type:'string'},
						{name:'fec_ingreso', type:'string'},
						{name:'per_sac', type:'string'},
						{name:'per_comer', type:'string'},
						{name:'shi_id', type:'string'},
						{name:'shi_empresa', type:'string'},
						{name:'per_id_ven', type:'int'},

					],
					proxy:{
						type:'ajax',
						url:socionegocio.url+'scm_scm_socionegocio_shipper/',
						reader:{
							type:'json',
							root:'data',
						}
					}
				});
				var combo_tipo = Ext.create('Ext.data.Store',{
					fields:[
						{name:'tipo', type:'string'},
						{name:'Descri', type:'string'},
					],
					proxy:{
						type:'ajax',
						url:socionegocio.url+'scm_get_tipo/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});

				var panel = Ext.create('Ext.form.Panel',{
					id:socionegocio.id+'-form',
					border:false,
					layout:'border',
					defaults:{
						border:false
					},
					height:300,
					tbar:[
							'Tipo:',
							{
								xtype:'combo',
								id:socionegocio.id+'-tipo-c',
								store:combo_tipo,
								queryMode: 'local',
		                        valueField: 'tipo',
		                        displayField: 'Descri',
		                        listConfig:{
		                            minWidth: 100
		                        },
		                        width: 100,
		                        forceSelection: true,
		                        allowBlank: false,
		                        selectOnFocus:true,
		                        listeners:{
		                        	afterrender:function(obj){
		                        		obj.getStore().load({
		                        			callback:function(){
		                        				obj.setValue('C');
		                        			}
		                        		});
		                        	}
		                        }


							},'-','N° RUC',
							{
								xtype:'textfield',
								id:socionegocio.id+'-ruc',
								plugins: [new ueInputTextMask('A9999999999')],
								enableKeyEvents: true,
								listeners:{
									keypress:function(obj,e){
										var code = e.getCharCode();
										if (code == 13){
											socionegocio.grid_socio();
										}
									}	
								}
								

							},'-','Nombre/Razón Social',
							{
								xtype:'textfield',
								id:socionegocio.id+'-razon',
								enableKeyEvents: true,
								listeners:{
									keypress:function(obj,e){
										var code = e.getCharCode();
										if (code == 13){
											socionegocio.grid_socio();
										}
									}
								}
							},'-',
							{
								text:'',
								id:socionegocio.id+'-buscar',
								icon: '/images/icon/search.png',
								tooltip:'Buscar',
								listeners:{
									beforerender:function(obj, opts){
										global.permisos({
		                                    id_serv: 71, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: socionegocio.id_menu,
		                                    fn: ['socionegocio.grid_socio']
		                                });

									},
									Click:function(obj,e){
											socionegocio.grid_socio();
									}	
								}				
							},
							{
								text:'',
								id:socionegocio.id+'-agregar',
								icon: '/images/icon/new_file.ico',
								tooltip:'Nuevo Socio',
								listeners:{
									beforerender:function(obj, opts){
										 global.permisos({
		                                    id_serv: 72, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: socionegocio.id_menu,
		                                    fn: ['socionegocio.new_socio']
		                                });
									},
									Click:function(obj,e){
										socionegocio.new_socio();
									}
								}

							}
					],
					items:[
							{
								xtype:'panel',
								region:'center',
								layout:'fit',
								id:socionegocio.id+'-panel-center',
								//width:600,
								items:[
										{
											xtype:'grid',
											id:socionegocio.id+'socionegocio-grid',
											store:socionegocio_grid,
											columnLines:true,
											features: [
					                         /*   {
					                                ftype: 'summary',
					                                dock: 'bottom',
					                              }
					                         */   
					                        ],
											columns:{
												items:[
														{
															xtype:'rownumberer',
															dataIndex:'',
															width:30,
															align:'center',
														},
														{
															text:'Menu',
															dataIndex:'',
															width:80,
															align:'center',
															renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																metaData.style = "padding: 0px; margin: 0px";
																var sne_ruc = "'"+record.get('sne_ruc')+"'";
																var sne_nombre ="'"+record.get('sne_nombre')+"'";
																var tsec_id = "'"+record.get('tsec_id')+"'";
																var sne_abc = "'"+record.get('sne_abc')+"'";
																var sne_tipo = "'"+record.get('sne_tipo')+"'";
																var dir_factura = "'"+record.get('dir_factura')+"'";
																var ciu_id = "'"+record.get('ciu_id')+"'";
																
																//console.log(sne_nombre);
																if (record.get('sne_estado')=='Activo'){
																	return global.permisos({
							                                            type: 'link',
							                                            extraCss: 'ftp-procesar-link',
							                                            id_menu: socionegocio.id_menu,
							                                            icons:[
							                                            	{id_serv: 73, img: 'ico_editar.gif', qtip: 'Click para Editar Registro.',js: 'socionegocio.edit_socio('+record.get('id_sne')+','+sne_ruc+','+sne_nombre+','+tsec_id+','+sne_abc+','+sne_tipo+','+dir_factura+','+ciu_id+')'},
							                                               // {id_serv: 75, img: 'novedad_visto.png', qtip: 'Click para ver Detalle.', js: 'socionegocio.grid_shipper('+record.get('id_sne')+')'},
							                                                {id_serv: 74, img: 'ok.png', qtip: 'Click para Desactivar.', js: 'socionegocio.update_socio_estado('+record.get('id_sne')+',0)'},
							                                                
							                                            ]
							                                        });	
																}else{
																	return global.permisos({
							                                            type: 'link',
							                                            extraCss: 'ftp-procesar-link',
							                                            id_menu: socionegocio.id_menu,
							                                            icons:[
							                                            	{id_serv: 73, img: 'ico_editar.gif', qtip: 'Click para Editar Registro.',js: 'socionegocio.edit_socio('+record.get('id_sne')+','+sne_ruc+','+sne_nombre+','+tsec_id+','+sne_abc+','+sne_tipo+','+dir_factura+','+ciu_id+')'},
							                                               // {id_serv: 75, img: 'novedad_visto.png', qtip: 'Click para ver Detalle.', js: 'socionegocio.grid_shipper('+record.get('id_sne')+')'},
							                                                {id_serv: 74, img: 'close.png', qtip: 'Click para Activar.', js: 'socionegocio.update_socio_estado('+record.get('id_sne')+',1)'},
							                                                
							                                                
							                                            ]
							                                        });	
																}
																
															}
														},
														{
															text:'N° RUC',
															dataIndex:'sne_ruc',
															align:'left',
															flex:1,
															//cls: 'column_header_double',
														},
														{
															text:'Nombre / Razón Social',
															dataIndex:'sne_nombre',
															align:'left',
															flex:3
															//cls: 'column_header_double',
														},
														{
															text:'Tipo',
															dataIndex:'tipo',
															align:'center',
															flex:1
															//cls: 'column_header_double',
														},
														{
															text:'Sector',
															dataIndex:'tip_sec',
															align:'center',
															flex:1
															//cls: 'column_header_double',
														},
														{
															text:'Estado',
															dataIndex:'sne_estado',
															align:'center',
															flex:1
															//cls: 'column_header_double',
														},
														{
															text:'Tipo',
															dataIndex:'sne_tipo',
															renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																if (value == 'C'){
																	return 'Cliente';
																}else{
																	return 'Proveedor';
																}
															}
														}

												],
												defaults:{
													sortable: true
												}
											},
											viewConfig:{
												stripeRows:true,
												enableTextSelection: false,
												markDirty: false
											},
											trackMouseOver: true,
											listeners:{
												afterrender:function(obj){
													//obj.getStore().load({});
												},
												beforeselect:function(obj, record, index, eOpts ){
													socionegocio.grid_shipper(record.get('id_sne'));
												}
											}
										}	
								]
							},
							{
								xtype:'panel',
								region:'south',
								layout:'fit',
								height:300,
								id:socionegocio.id+'-panel-south',
								tbar:[	
										{
											xtype:'tbspacer',
											width: '97%',
										},
										{
											text:'',
											id: socionegocio.id + '-btn-nuevo-detalle',
											icon: '/images/icon/add.png',
											listeners:{
												beforerender:function(obj, opts){
													 global.permisos({
					                                    id_serv: 76, 
					                                    id_btn: obj.getId(), 
					                                    id_menu: socionegocio.id_menu,
					                                    fn: ['socionegocio.new_socio_shipper']
					                                });
												},
												Click:function(obj){
													socionegocio.new_socio_shipper();
												}
											}
										}
								],
								items:[
										{
											xtype:'grid',
											id:socionegocio.id+'socio_shipper-grid',
											store:socio_shipper_grid,
											columnLines:true,
											columns:{
												items:[
														{
															xtype:'rownumberer',
															dataIndex:'',
															width:30,
															align:'center',
														},
														{
															text:'Menu',
															dataIndex:'',
															width:80,
															align:'center',
															renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																metaData.style = "padding: 0px; margin: 0px";
																var shi_codigo ="'"+record.get('shi_codigo')+"'";
																var per_id_sac ="'"+record.get('per_id_sac')+"'";
																var shi_nombre ="'"+record.get('shi_nombre')+"'";
																var fec_ingreso ="'"+record.get('fec_ingreso')+"'";
																var shi_id ="'"+record.get('shi_id')+"'";
																var shi_empresa ="'"+record.get('shi_empresa')+"'";
																var per_id_ven ="'"+record.get('per_id_ven')+"'";
																var id_sne = "'"+record.get('id_sne')+"'";
																
																if (record.get('shi_estado')=='Activo'){
																	return global.permisos({
							                                            type: 'link',
							                                            id_menu: socionegocio.id_menu,
							                                            icons:[
							                                                {id_serv: 77, img: 'ico_editar.gif', qtip: 'Click para Editar', js: 'socionegocio.edit_socio_shipper('+shi_codigo+','+per_id_sac+','+shi_nombre+','+fec_ingreso+','+shi_id+','+shi_empresa+','+per_id_ven+')'},
							                                                {id_serv: 78, img: 'ok.png', qtip: 'Click para ver Desactivar', js: 'socionegocio.stado_socio_shipper('+id_sne+','+shi_codigo+',0)'}
							                                            ]
							                                        });	
																}else{
																	return global.permisos({
							                                            type: 'link',
							                                            id_menu: socionegocio.id_menu,
							                                            icons:[
							                                                {id_serv: 77, img: 'ico_editar.gif', qtip: 'Click para Editar', js: 'socionegocio.edit_socio_shipper('+shi_codigo+','+per_id_sac+','+shi_nombre+','+fec_ingreso+','+shi_id+','+shi_empresa+','+per_id_ven+')'},
							                                                {id_serv: 78, img: 'close.png', qtip: 'Click para ver Activar..', js: 'socionegocio.stado_socio_shipper('+id_sne+','+shi_codigo+',1)'}
							                                            ]
							                                        });	
																}
																
															}
														},
														{
															text:'Codigo',
															dataIndex:'shi_codigo',
															align:'center',
															width:80,
															//cls: 'column_header_double',
														},
														{
															text:'Nombre Comercial',
															dataIndex:'shi_nombre',
															align:'center',
															flex:2
															//cls: 'column_header_double',
														},
														{
															text:'Fecha Ingreso',
															dataIndex:'fec_ingreso',
															align:'center',
															flex:1
															//cls: 'column_header_double',
														},
														/*{
															text:'Centro de Actividad',
															dataIndex:'',
															align:'center',
															flex:1
															//cls: 'column_header_double',
														},
														{
															text:'Centro de Costos',
															dataIndex:'',
															align:'center',
															flex:1
															//cls: 'column_header_double',
														},*/
														{
															text:'SAC',
															dataIndex:'per_sac',
															align:'center',
															flex:1
															//cls: 'column_header_double',
														},
														{
															text:'Comercial',
															dataIndex:'per_comer',
															align:'center',
															flex:1
															//cls: 'column_header_double',
														},
														{
															text:'Estado',
															dataIndex:'shi_estado',
															align:'center',
															flex:1
															//cls: 'column_header_double',
														},


												],
												defaults:{
													sortable: true
												}
											},
											viewConfig:{
												stripeRows:true,
												enableTextSelection: false,
												markDirty: false
											},
											trackMouseOver: true,
											listeners:{
												afterrender:function(obj){

												}
											}

										}

								]
							}
					]

				});

				tab.add({
					id:socionegocio.id+'-tab',
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
	                        global.state_item_menu(socionegocio.id_menu, true);
	                        //socionegocio.get_permiso();
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,socionegocio.id_menu);
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(socionegocio.id_menu, false);
	                    }

					}

				}).show();
			},
			new_socio_shipper:function(){
				if (parseInt(socionegocio.vl_id_sne) != 0){
					//vp_tip =  (I) Inserta (E) actualiza
					win.show({vurl: socionegocio.url + 'form_new_socio_shipper/?&vl_id_sne='+socionegocio.vl_id_sne+'&vp_tip=I', id_menu: socionegocio.id_menu, class: ''});	
				}else{
					global.Msg({
						msg:'No se ha encontrado datos',
						icon:1,
						buttosn:1,
						fn:function(btn){
						}
					});
				}
				
			},
			edit_socio_shipper:function(shi_codigo,per_id_sac,shi_nombre,fec_ingreso,shi_id,shi_empresa,per_id_ven){
				if (parseInt(socionegocio.vl_id_sne) != 0){
					//vp_tip =  (I) Inserta (E) actualiza
					win.show({vurl: socionegocio.url + 'form_new_socio_shipper/?&vl_id_sne='+socionegocio.vl_id_sne+'&vp_tip=E&shi_codigo='+shi_codigo+'&per_id_sac='+per_id_sac+'&shi_nombre='+shi_nombre+'&fec_ingreso='+fec_ingreso+'&shi_id='+shi_id+'&shi_empresa='+shi_empresa+'&per_id_ven='+per_id_ven, id_menu: socionegocio.id_menu, class: ''});	
				}else{
					global.Msg({
						msg:'No se ha encontrado datos',
						icon:1,
						buttosn:1,
						fn:function(btn){
						}
					});
				}
			},
			new_socio:function(){
				win.show({vurl: socionegocio.url + 'form_new_socio/?&var_tipo=I', id_menu: socionegocio.id_menu, class: ''});
			},
			edit_socio:function(id_sne,sne_ruc,sne_nombre,tsec_id,sne_abc,sne_tipo,dir_factura,ciu_id){
				win.show({vurl: socionegocio.url + 'form_new_socio/?&var_tipo=E&vp_id_sne='+id_sne+'&sne_ruc='+sne_ruc+'&sne_nombre='+sne_nombre+'&tsec_id='+tsec_id+'&sne_abc='+sne_abc+'&sne_tipo='+sne_tipo+'&dir_factura='+dir_factura+'&ciu_id='+ciu_id, id_menu: socionegocio.id_menu, class: ''});
			},
			grid_socio:function(){
				var ruc =Ext.getCmp(socionegocio.id+'-ruc').getValue();
				var nombre = Ext.getCmp(socionegocio.id+'-razon').getValue();
				var tipo = Ext.getCmp(socionegocio.id+'-tipo-c').getValue()
				Ext.getCmp(socionegocio.id+'socionegocio-grid').getStore().load({
					params:{vp_sne_ruc:ruc,vp_sne_nombre:nombre,vp_tipo:tipo},
					callback:function(){
						Ext.getCmp(socionegocio.id+'socio_shipper-grid').getStore().removeAll();
					}
				});
			},
			grid_shipper:function(vl_id_sne){
				socionegocio.vl_id_sne=vl_id_sne;
				var obj = Ext.getCmp(socionegocio.id+'socio_shipper-grid');
				obj.getStore().load({
					params:{vp_id_sne:vl_id_sne}
				});
			},


			/*get_permiso:function(){
				
				var grid = Ext.getCmp(socionegocio.id+'socio_shipper-grid');
				if (socionegocio.usr_tipo == 'E'){
					Ext.getCmp(socionegocio.id+'-panel-center').setHidden(true);
					grid.getStore().load({});
					if (grid.getStore().getCount()>0){
						for(var i = 0; i < grid.getStore().getCount(); ++i){
							var rec = grid.getStore().getAt(i);
							socionegocio.vl_id_sne = rec.get('id_sne');
						}
					}
				}else{
					Ext.getCmp(socionegocio.id+'-panel-south').setHeight(300);
				}

			},*/
			update_socio_estado:function(vl_id_sne,vl_estado){
				var mask = new Ext.LoadMask(Ext.getCmp(inicio.id+'-tabContent'),{
					msg:'Actualizando Datos....'
				});
				mask.show();
				Ext.Ajax.request({
					url:socionegocio.url+'scm_scm_socionegocio_estado_ruc/',
					params:{vp_id_sne:vl_id_sne,vp_sne_estado:vl_estado},
					success:function(response,options){
						mask.hide();
						var res = Ext.decode(response.responseText);
						//console.log(res);
						if (parseInt(res.data[0].error_sql)==0){
							global.Msg({
								msg:res.data[0].error_info,
								icon:1,
								buttosn:1,
								fn:function(btn){
									socionegocio.grid_socio();
								}
							});
						}

					}
				});
			},
			stado_socio_shipper:function(vp_id_sne,vp_shi_codigo,vp_stado){
				Ext.Ajax.request({
					url:socionegocio.url+'scm_scm_socionegocio_estado_socio_shipper/',
					params:{vp_id_sne:vp_id_sne,vp_shi_codigo:vp_shi_codigo,vp_stado:vp_stado},
					success:function(response,options){
						var res = Ext.decode(response.responseText);
						if (parseInt(res.data[0].error_sql)==0){
							global.Msg({
								msg:res.data[0].error_info,
								icon:1,
								buttosn:1,
								fn:function(btn){
									var obj = Ext.getCmp(socionegocio.id+'socio_shipper-grid');
									obj.getStore().load({
										params:{vp_id_sne:socionegocio.vl_id_sne}
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

			}
		}
		Ext.onReady(socionegocio.init,socionegocio);
	}else{
		tab.setActiveTab(socionegocio.id+'-tab');
	}
</script>