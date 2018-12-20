<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('cobranza-tab')){
		var cobranza = {
			id:'cobranza',
			id_menu:'<?php echo $p["id_menu"];?>',
			url: '/gestion/cobranza/',
			init:function(){

				var store_grid1 = Ext.create('Ext.data.Store',{
					fields: [                 
						{name: 'medio_Pago', type: 'string'},
						{name: 'moneda', type: 'string'},
						{name: 'tot_ge_cod', type: 'number'},
						{name: 'importe_cod', type: 'number'},
						{name: 'tot_ge_en', type: 'number'},
						{name: 'importe_recaudo', type: 'number'},
						{name: 'importe_rendido', type: 'number'},
						{name: 'importe_pendiente', type: 'number'},
						{name: 'tot_ge_pendiente', type: 'number'},
						{name: 'tot_personal', type: 'number'},
						{name: 'progreso', type: 'number'},
						{name: 'Efectividad', type: 'number'},
						{name: 'id_medio_pago', type: 'number'},
						{name: 'id_moneda', type: 'number'},
						{name: 'fecha', type: 'string'}
					],
					proxy:{
						type:'ajax',
						url:cobranza.url+'scm_scm_cod_rendir_panel/',
						reader:{
							type:'json',
							rootProperty:'data'
						}
					}
				});
				var store_grid2 = Ext.create('Ext.data.Store',{
					fields: [
						{name: 'fecha', type: 'string'},
						{name: 'medio_pago', type: 'string'},
						{name: 'moneda', type: 'string'},
						{name: 'tot_ge_cod', type: 'number'},
						{name: 'importe_cod', type: 'number'},
						{name: 'tot_personal', type: 'number'},
						{name: 'id_medio_pago', type: 'int'},
						{name: 'id_moneda', type: 'int'}
					],
					proxy:{
						type:'ajax',
						url:cobranza.url+'scm_scm_cod_rendir_panel_last/',
						reader:{
							type:'json',
							rootProperty:'data'
						}
					}
				});

				var store_grid3 = Ext.create('Ext.data.Store',{
					fields:[
							{name: 'guia', type: 'int'},
							{name: 'codigo', type: 'string'},
							{name: 'shipper', type: 'string'},
							{name: 'servicio', type: 'string'},
							{name: 'fecha', type: 'string'},
							{name: 'cliente', type: 'string'},
							{name: 'medio_pago', type: 'string'},
							{name: 'moneda', type: 'string'},
							{name: 'importe', type: 'number'},
							{name: 'personal', type: 'string'}
					],
					proxy:{
						type:'ajax',
						url:cobranza.url+'scm_scm_cod_rendir_panel_pendientes/',
						reader:{
							type:'json',
							rootProperty:'data'
						}
					}
				});

				var panel = Ext.create('Ext.form.Panel',{
					id:cobranza.id+'-form',
					border:false,
					scrollable:'vertical',
					layout:'column',
					defaults:{
						border:false
					},
					tbar:[
							'Agencia:',
							 {
		                        xtype: 'combo',
		                        id: cobranza.id + '-agencia',
		                        store: Ext.create('Ext.data.Store',{
		                            fields:[
		                                {name: 'prov_codigo', type: 'int'},
		                                {name: 'prov_nombre', type: 'string'}
		                            ],
		                            proxy:{
		                                type: 'ajax',
		                                url: cobranza.url + 'get_usr_sis_provincias/',
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
		                            	var prov_codigo = '<?php echo PROV_CODIGO;?>';
		                                obj.getStore().load({
		                                    params:{vp_id_linea:0},
		                                    callback: function(){
		                                     	obj.setValue(prov_codigo);  
		                                     	cobranza.buscar();
		                                     	/*var per = Ext.getCmp(cobranza.id+'-per_id');
				                            	per.getStore().load({
													params:{vp_prov_codigo:prov_codigo},
													callback:function(){
													}
												});*/
		                                    }
		                                });
		                            },
		                            select:function(obj,record,options){
		                            	cobranza.buscar();
		                            	/*var per = Ext.getCmp(cobranza.id+'-per_id');
		                            	per.getStore().load({
											params:{vp_prov_codigo:record.get('prov_codigo')},
											callback:function(){
											}
										});*/
		                            }
		                        }
		                    },
							'Fecha',
							{
								xtype:'datefield',
								id:cobranza.id+'fini',
								value: new Date()
							},
							{
								text:'',
								id:cobranza.id+'-buscar',
								icon: '/images/icon/search.png',
								listeners:{
								    beforerender: function(obj, opts){
		                                global.permisos({
		                                    id_serv: 108, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: cobranza.id_menu,
		                                    fn: ['cobranza.buscar']
		                                });
		                            },
									click:function(obj){
										cobranza.buscar();
									}
								}
							},
							{
								text:'',
								icon: '/images/icon/dolar.png',
								listeners:{
									beforerender: function(obj, opts){
		                                global.permisos({
		                                    id_serv: 106, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: cobranza.id_menu,
		                                    fn: ['cobranza.show_retorno']
		                                });
		                            },
									click:function(obj){
										cobranza.show_retorno();
									}
								}
							}
					],
					items:[
							{
								xtype:'label',
								id:cobranza.id+'-lbl1',
								columnWidth:1,
								margin:'5 5 5 5',
								text:'',
								//style : 'font-weight: bold;font-size: 10pt;text-align: center;color:#0063DC',
								style : 'text-align: center',
							},
							{
								xtype:'panel',
								height:235,
								layout:{
									type:'vbox',
								 	align:'center',
								 },
								 columnWidth:1,
								 items:[
								 		{
											xtype:'grid',
											//columnWidth:1,
											cls:'grid-shadow',
											//border:false,
											id:cobranza.id+'grid1',
											store:store_grid1,
											width:'90%',
											height:200,
					                        features: [
							                            {
							                                ftype: 'summary',
							                                dock: 'bottom'
							                            }
					                        ],
											columns:{
												items:[
														{
															text:'Medio de Recaudo',
															dataIndex:'medio_pago',
															flex:2,
														},
														{
															text:'Moneda',
															dataIndex:'moneda',
															width:60,
															summaryRenderer: function(value, summaryData, dataIndex){
						                                       //return value.toFixed(2);
						                                       return '<div style="font-weight: bold;text-align:right;">Totales:</div>';
						                                    }
														},
														{
															text:'Total GE',
															dataIndex:'tot_ge_cod',
															width:60,
															summaryType: 'sum',
															align:'right',
															summaryRenderer: function(value, summaryData, dataIndex){
						                                       return '<div style="font-weight: bold;">'+value+'</div>';
						                                    }
														},
														{
															text:'Imp. en Ruta',
															dataIndex:'importe_cod',
															flex:1,
															align:'right',
															renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
																return cobranza.formatoNumero(value,2);
															},
															summaryType: 'sum',
															summaryRenderer: function(value, summaryData, dataIndex){
						                                       //return value.toFixed(2);
						                                       return '<div style="font-weight: bold;">'+cobranza.formatoNumero(value,2)+'</div>';
						                                    }
														},
														{
															text:'GE Entregado',
															dataIndex:'tot_ge_en',
															flex:1,
															align:'right',
															summaryType: 'sum',
															summaryRenderer: function(value, summaryData, dataIndex){
						                                       //return value.toFixed(2);
						                                       return '<div style="font-weight: bold;">'+value+'</div>';
						                                    }
														},
														{
															text:'Imp. Recaudo',
															dataIndex:'importe_recaudo',
															align:'right',
															flex:1,
															renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
																return cobranza.formatoNumero(value,2);
															},
															summaryType: 'sum',
															summaryRenderer: function(value, summaryData, dataIndex){
						                                       //return value.toFixed(2);
						                                       return '<div style="font-weight: bold;">'+cobranza.formatoNumero(value,2)+'</div>';
						                                    }
														},
														{
															text:'Imp. Rendido',
															dataIndex:'importe_rendido',
															align:'right',
															flex:1,
															renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
																return cobranza.formatoNumero(value,2);
															},
															summaryType: 'sum',
															summaryRenderer: function(value, summaryData, dataIndex){
						                                       //return value.toFixed(2);
						                                       return '<div style="font-weight: bold;">'+cobranza.formatoNumero(value,2)+'</div>';
						                                    }
														},
														{
															text:'Imp. Pendiente',
															dataIndex:'importe_pendiente',
															align:'right',
															flex:1,
															renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
																return cobranza.formatoNumero(value,2);
															},
															summaryType: 'sum',
															summaryRenderer: function(value, summaryData, dataIndex){
						                                       //return value.toFixed(2);
						                                       return '<div style="font-weight: bold;">'+cobranza.formatoNumero(value,2)+'</div>';
						                                    }
														},
														{
															text:'Tot GE Por Rendir',
															dataIndex:'tot_ge_pendiente',
															align:'center',															
															width:110,
															renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
						                                        var fecha = "'"+record.get('fecha')+"'";
						                                        var id_medio_pago = "'"+record.get('id_medio_pago')+"'";
						                                        var id_moneda = "'"+record.get('id_moneda')+"'";

						                                        metaData.style = "padding: 0px; margin: 0px";
						                                        return global.permisos({
						                                            type: 'link',
						                                            id_menu: cobranza.id_menu,
						                                            extraCss:'x-link',
						                                            icons:[
						                                                {id_serv: 108, qtip: 'Click para ver los pendientes', js: 'cobranza.let('+fecha+','+id_medio_pago+','+id_moneda+');',value:value,anchor:'#cobranza-pend'},
						                                            ]
						                                        });
						                                    },
															summaryType: 'sum',
															summaryRenderer: function(value, summaryData, dataIndex){
						                                       //return value.toFixed(2);
						                                       return '<div style="font-weight: bold;">'+value+'</div>';
						                                    }
														},
														{
															text:'Courier por Rendir',
															dataIndex:'tot_personal',
															align:'center',
															width:110,
															summaryType: 'sum',
															summaryRenderer: function(value, summaryData, dataIndex){
						                                       //return value.toFixed(2);
						                                       return '<div style="font-weight: bold;">'+value+'</div>';
						                                    }
														},
														{
															text:'Progreso',
															dataIndex:'progreso',
															flex:1,
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
															text:'Efectividad',
															dataIndex:'efectividad',
															flex:1,
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
														}
														
												]
											},
											viewConfig:{
												stripeRows: true,
					                            enableTextSelection: false,
					                            markDirty: false
											},
											trackMouseOver: false,
										},
								 ]
							},
							
							{
								xtype:'label',
								id:cobranza.id+'lbl2',
								columnWidth:1,
								margin:'5 5 5 5',
								//text:'Hay Pendientes de Rendir de Fechas Anteriores 10 GE ya Entregadas',
								//style : 'font-weight: bold;font-size: 10pt;text-align: center;color:#0063DC',
								style : 'text-align: center',
							},
							{
								xtype:'panel',
								height:300,
								layout:{
									type:'vbox',
								 	align:'center',
								 	//pack: 'center'
								},
								columnWidth:1,
								items:[
										{
											xtype:'panel',
											border:false,
											width:'90%',
											height:300,
											layout:'column',
											items:[
												{
													xtype:'grid',
													//border:false,
													//width:700,
													columnWidth:0.6,
													cls:'grid-shadow',
													id:cobranza.id+'-grid2',
													store:store_grid2,
													height:259,
													features: [
									                            {
									                                ftype: 'summary',
									                                dock: 'bottom'
									                            }
							                        ],
							                        columns:{
							                        	items:[
							                        			{
							                        				text:'Fecha',
							                        				dataIndex:'fecha',
							                        				width:80
							                        			},
							                        			{
							                        				text:'Medio De Pago',
							                        				dataIndex:'medio_pago',
							                        				flex:1
							                        			},
							                        			{
							                        				text:'Moneda',
							                        				dataIndex:'moneda',
							                        				width:60,
							                        				summaryRenderer: function(value, summaryData, dataIndex){
								                                       //return value.toFixed(2);
								                                       return '<div style="font-weight: bold;text-align:right;">Totales:</div>';
								                                    }
							                        			},
							                        			{
							                        				text:'Total GE',
							                        				dataIndex:'tot_ge_cod',
							                        				align:'center',
							                        				width:60,
							                        				renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
								                                        var fecha = "'"+record.get('fecha')+"'";
								                                        var id_medio_pago = "'"+record.get('id_medio_pago')+"'";
								                                        var id_moneda = "'"+record.get('id_moneda')+"'";

								                                        metaData.style = "padding: 0px; margin: 0px";
								                                        return global.permisos({
								                                            type: 'link',
								                                            id_menu: cobranza.id_menu,
								                                            extraCss:'x-link',
								                                            icons:[
								                                                {id_serv: 108, qtip: 'Click para ver los pendientes', js: 'cobranza.let('+fecha+','+id_medio_pago+','+id_moneda+');',value:value,anchor:'#cobranza-pend'},
								                                            ]
								                                        });
								                                    },
							                        				summaryType: 'sum',
																	summaryRenderer: function(value, summaryData, dataIndex){
								                                       //return value.toFixed(2);
								                                       return '<div style="font-weight: bold;">'+value+'</div>';
								                                    }
							                        			},
							                        			{
							                        				text:'Importe GE',
							                        				dataIndex:'importe_cod',
							                        				align:'right',
							                        				width:80,
							                        				renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
																		return cobranza.formatoNumero(value,2);
																	},
																	summaryType: 'sum',
																	summaryRenderer: function(value, summaryData, dataIndex){
								                                       //return value.toFixed(2);
								                                       return '<div style="font-weight: bold;">'+cobranza.formatoNumero(value,2)+'</div>';
								                                    }
							                        			},
							                        			{
							                        				text:'Courier por Rendir',
							                        				dataIndex:'tot_personal',
							                        				align:'center',
							                        				width:120,
							                        				summaryType: 'sum',
																	summaryRenderer: function(value, summaryData, dataIndex){
								                                       //return value.toFixed(2);
								                                       return '<div style="font-weight: bold;">'+value+'</div>';
								                                    }
							                        			}
							                        	]
							                        }
												},
												{
													xtype:'panel',
													id:cobranza.id+'-chart',
													margin:'0 0 0 20',
													border:false,
													columnWidth:0.4,
													//height:400,
													//width:400,
													html:'<div id="chart-container" style="width: 290px; height: 260px;"></div>',
													listeners:{
														afterrender:function(obj){
														//	cobranza.chart();
														}
													}
												}
											]
										}
								]
							},
							{
								xtype:'label',
								id:cobranza.id+'lbl3',
								//hidden:true,
								columnWidth:1,
								margin:'5 5 5 5',
								//text:'GE Con COD Pendientes de Rendir',
								html :'<a name="cobranza-pend"></a>',
								//style : 'font-weight: bold;font-size: 10pt;text-align: center;color:black',
								style : 'text-align: center',
							},
							{
								xtype:'panel',
								height:435,
								layout:{
									type:'vbox',
									align:'center'
								},
								columnWidth:1,
								items:[
										{
											xtype:'grid',
											hidden:true,
											id:cobranza.id+'-grid3',
											width:'90%',
											cls:'grid-shadow',
											//columnWidth:1,
											store:store_grid3,
											height:400,
											features: [
							                            {
							                                ftype: 'summary',
							                                dock: 'bottom'
							                            }
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
					                        				text:'N° GE',
					                        				dataIndex:'guia',
					                        				width:70
					                        			},
					                        			{
					                        				text:'Codigo Seguimiento',
					                        				dataIndex:'codigo',
					                        				width:120
					                        			},
					                        			{
					                        				text:'Shipper',
					                        				dataIndex:'shipper',
					                        				flex:1
					                        			},
					                        			{
					                        				text:'Servicio',
					                        				dataIndex:'servicio',
					                        				flex:1
					                        			},
					                        			{
					                        				text:'F. en Entrega',
					                        				dataIndex:'fecha',
					                        				width:80
					                        			},
					                        			{
					                        				text:'Cliente',
					                        				dataIndex:'cliente',
					                        				flex:1
					                        			},
					                        			{
					                        				text:'Medio de Pago',
					                        				dataIndex:'medio_pago',
					                        				flex:1
					                        			},
					                        			{
					                        				text:'Moneda',
					                        				dataIndex:'moneda',
					                        				width:55,
					                        				summaryRenderer: function(value, summaryData, dataIndex){
						                                       //return value.toFixed(2);
						                                       return '<div style="font-weight: bold;text-align:right;">Totales:</div>';
						                                    }
					                        			},
					                        			{
					                        				text:'Importe',
					                        				align:'right',
					                        				dataIndex:'importe',
					                        				width:80,
					                        				renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
																return cobranza.formatoNumero(value,2);
															},
															summaryType: 'sum',
															summaryRenderer: function(value, summaryData, dataIndex){
						                                       //return value.toFixed(2);
						                                       return '<div style="font-weight: bold;">'+cobranza.formatoNumero(value,2)+'</div>';
						                                    }
					                        			},
					                        			{
					                        				text:'Courier',
					                        				dataIndex:'personal',
					                        				flex:2
					                        			}
					                        	]
					                        }
										}
								]
							},
					]
				});

				tab.add({
					id:cobranza.id+'-tab',
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
	                		global.state_item_menu(cobranza.id_menu, true);	
	                    },
	                    afterrender: function(obj, e){
                        	tab.setActiveTab(obj);
                        	global.state_item_menu_config(obj,cobranza.id_menu);	
	                        
	                    },
	                    beforeclose: function(obj, opts){
	                    	global.state_item_menu(cobranza.id_menu, false);	
	                    }
	                }

				}).show();
			},
			show_retorno:function(){
				Ext.getCmp(cobranza.id+'-tab').disable();
				win.show({vurl: cobranza.url + 'show_retorno/', id_menu: cobranza.id_menu, class: '' });
			},
			buscar:function(){
				var form = Ext.getCmp(cobranza.id+'-form').getForm();
				if (form.isValid()){
					var grid1 = Ext.getCmp(cobranza.id+'grid1');
					var vp_prov_codigo = Ext.getCmp(cobranza.id + '-agencia').getValue();
			        var vp_fecini = Ext.getCmp(cobranza.id+'fini').getRawValue();
					grid1.getStore().load({
						params:{vp_prov_codigo:vp_prov_codigo,vp_fecini:vp_fecini},
						callback:function(){
							cobranza.getData1();
							var grid2 = Ext.getCmp(cobranza.id+'-grid2');
							grid2.getStore().load({
								params:{vp_prov_codigo:vp_prov_codigo,vp_fecini:vp_fecini},
								callback:function(){
									cobranza.getData2();
									cobranza.chart();
								}
							});
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
			getData1:function(){
				var fecini = Ext.getCmp(cobranza.id+'fini').getValue();	
				var lbl = Ext.getCmp(cobranza.id+'-lbl1');
				var grid = Ext.getCmp(cobranza.id+'grid1');
				var suma = 0;
				if (grid.getStore().getCount() > 0){
             		for(var i = 0; i < grid.getStore().getCount(); ++i){
             			var rec = grid.getStore().getAt(i);
             				suma = suma + parseFloat(rec.get('tot_ge_cod'));
             		}
             	}
             	var options = {weekday: "long", year: "numeric", month: "long", day: "numeric"};
				//alert(fecini.toLocaleDateString("es-ES", options));
				if (suma > 0){
					suma = '<a href="#" class="x-link">'+suma+'</a>';
				}
				lbl.setHtml(fecini.toLocaleDateString("es-ES", options)+' salieron '+suma+' GE con COD');
				//lbl.update('<a href="#cobranza-pend">Hoy '+fecini+' Salieron '+suma+' GE Con COD</a>');
			},
			getData2:function(){
				var grid2 = Ext.getCmp(cobranza.id+'-grid2');
				var lbl2 = Ext.getCmp(cobranza.id+'lbl2');
				var suma = 0;
				if (grid2.getStore().getCount() > 0){
             		for(var i = 0; i < grid2.getStore().getCount(); ++i){
             			var rec = grid2.getStore().getAt(i);
             				suma = suma + parseFloat(rec.get('tot_ge_cod'));
             		}
             	}
             	if (suma > 0){
					suma = '<a href="#" class="x-link">'+suma+'</a>';
				}

             	lbl2.setHtml('Hay Pendientes de Rendir de Fechas Anteriores '+suma+' GE ya Entregadas');

			},
			getData3:function(){
				Ext.getCmp(cobranza.id+'-grid3').setVisible(true);
				Ext.getCmp(cobranza.id+'lbl3').setVisible(true);
				var grid3 = Ext.getCmp(cobranza.id+'-grid3');
				var lbl3 = Ext.getCmp(cobranza.id+'lbl3');
				var suma = grid3.getStore().getCount();

             	/*if (suma > 0){
					//suma = '<a href="#" name="cobranza-pend" class="x-link">'+suma+'</a>';
				}*/
             	lbl3.setHtml(suma+' GE Con COD Pendientes de Rendir');
			},
			let:function(fecha,id_medio_pago,id_moneda){
				var vp_prov_codigo = Ext.getCmp(cobranza.id + '-agencia').getValue();
				Ext.getCmp(cobranza.id+'-grid3').getStore().load({
					params:{vp_prov_codigo:vp_prov_codigo,vp_fecha:fecha,vp_medio_pago:id_medio_pago,vp_moneda:id_moneda},
					callback:function(){
						cobranza.getData3();
					}
				});
			},

			formatoNumero:function(numero,decimales) {
			    var partes, array;
			    separadorDecimal='.';
			    separadorMiles=',';
			    if ( !isFinite(numero) || isNaN(numero = parseFloat(numero)) ) {
			        return "";
			    }
			    if (typeof separadorDecimal==="undefined") {
			        separadorDecimal = ".";
			    }
			    if (typeof separadorMiles==="undefined") {
			        separadorMiles = ",";
			    }
			    // Redondeamos
			    if ( !isNaN(parseInt(decimales)) ) {
			        if (decimales >= 0) {
			            numero = numero.toFixed(decimales);
			        } else {
			            numero = (
			                Math.round(numero / Math.pow(10, Math.abs(decimales))) * Math.pow(10, Math.abs(decimales))
			            ).toFixed();
			        }
			    } else {
			        numero = numero.toString();
			    }
			    // Damos formato
			    partes = numero.split(".", 2);
			    array = partes[0].split("");
			    for (var i=array.length-3; i>0 && array[i-1]!=="-"; i-=3) {
			        array.splice(i, 0, separadorMiles);
			    }
			    numero = array.join("");
			    if (partes.length>1) {
			        numero += separadorDecimal + partes[1];
			    }
			    return numero;
			},
			chart:function(){
				FusionCharts.ready(function () {
					Ext.getCmp(cobranza.id+'-chart').setHtml('<div id="chart-container" style="width: 290px; height: 260px;"></div>');
					var vp_prov_codigo = Ext.getCmp(cobranza.id + '-agencia').getValue();
			        var vp_fecini = Ext.getCmp(cobranza.id+'fini').getRawValue();
					Ext.Ajax.request({
						url:cobranza.url+'scm_pend_chart/',
						params:{vp_prov_codigo:vp_prov_codigo,vp_fecini:vp_fecini},
						success: function(response, options){
							var res = Ext.JSON.decode(response.responseText).data;
							var cate = [];
							var data = [];
							//console.log(res);
							Ext.each(res,function(obj,idx){
								//console.log(obj);
								cate.push({label:obj.fecha});
								data.push({label:obj.medio_pago,value:obj.tot_ge_cod});
							});
							//console.log(cate);
							if (!global.isEmptyJSON(res)){
								var chart = new FusionCharts({
				                    type: 'mscombi2d',
				                    renderAt: 'chart-container',
				                    width: Ext.getCmp(cobranza.id+'-chart').getWidth(),//'300',
				                    height: '260',
				                    dataFormat: 'json',
				                    dataSource: {
				                        "chart": {
				                            "caption": 'Pendiente de Rendir',
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
				                                		cate
				                                ]
				                            }
				                        ],
				                       "dataset": [
				                       				{
				                       					"data":[
				                       						data
				                       					],
				                       					"seriesname":"Pendientes"
				                       				},                     				
										]
				                    }
				              	});
								chart.render();	
								/*var chart = new FusionCharts({
									type: 'mscombi3d',
									renderAt: 'chart-container',
				                    width: Ext.getCmp(cobranza.id+'-chart').getWidth(),//'300',
				                    height: '260',
				                    dataFormat: 'json',
				                    dataSource:
										{
										    "chart": {
										        "caption": "Sales by salesperson",
										        "yaxisname": "Revenue",
										        //"numberprefix": "$",
										        "bgcolor": "#FFFFFF",
										        "showalternatehgridcolor": "0",
										        "showvalues": "1",
										       // "labeldisplay": "WRAP",
										        "divlinecolor": "#CCCCCC",
										        "divlinealpha": "70",
										        "useroundedges": "1",
										        "canvasbgcolor": "#FFFFFF",
										        "canvasbasecolor": "#CCCCCC",
										        "showcanvasbg": "0",
										        "animation": "0",
										        "palettecolors": "#008ee4,#6baa01,#f8bd19,#e44a00,#33bdda",
										        "showborder": "0"
										    },
										    "categories": [
					                            {
					                                "category":[
					                                		cate
					                                ]
					                            }
					                        ],
										    "dataset":[
										    	{
			                       					"data":[
			                       						data
			                       					],
			                       					"seriesname":"Pendientes"
			                       				},        
											]    
										}
								});
								chart.render();*/
							}
							
						}
					});
				
				
				});
			},
		}
		Ext.onReady(cobranza.init,cobranza);
	}else{
		tab.setActiveTab(cobranza.id+'-tab');
	}
</script>