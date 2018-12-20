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
						{name: 'courier', type: 'string'},
						{name: 'dias', type: 'int'},
						{name: 'tot_ge', type: 'int'},
						{name: 'tot_ge_cod', type: 'int'},
						{name: 'efe_tot_ge', type: 'int'},
						{name: 'efe_montos', type: 'number'},
						{name: 'efe_cobrado', type: 'number'},
						{name: 'efe_pendiente', type: 'number'},
						{name: 'tar_tot_ge', type: 'int'},
						{name: 'tar_montos', type: 'number'},
						{name: 'tar_cobrado', type: 'number'},
						{name: 'tar_pendiente', type: 'number'},
						{name: 'per_id', type: 'int'},
					],
					proxy:{
						type:'ajax',
						url:cobranza.url+'csm_scm_COD_panel_rutas/',
						reader:{
							type:'json',
							rootProperty:'data'
						}
					}
				});

				var panel = Ext.create('Ext.form.Panel',{
					id:cobranza.id+'-form',
					border:false,
					layout:'fit',
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
		                                     	var per = Ext.getCmp(cobranza.id+'-per_id');
				                            	per.getStore().load({
													params:{vp_prov_codigo:prov_codigo},
													callback:function(){
													}
												});
		                                    }
		                                });
		                            },
		                            select:function(obj,record,options){
		                            	var per = Ext.getCmp(cobranza.id+'-per_id');
		                            	per.getStore().load({
											params:{vp_prov_codigo:record.get('prov_codigo')},
											callback:function(){
											}
										});
		                            }
		                        }
		                    },
							'Fecha',
							{
								xtype:'datefield',
								id:cobranza.id+'fini',
								value: new Date()
							},
							' Al ',
							{
								xtype:'datefield',
								id:cobranza.id+'ffin',
								value: new Date()
							},'Moneda',
							{
								xtype:'combo',
								allowBlank: false,
								id:cobranza.id+'-tmnd_id',
								columnWidth:0.5,
	            				store: Ext.create('Ext.data.Store',{
		                            fields:[
		                                {name: 'descripcion', type: 'string'},
		                                {name: 'id_elemento', type: 'int'},
		                                {name: 'des_corto', type: 'string'}
		                            ],
		                            proxy:{
		                                type: 'ajax',
		                                url: cobranza.url + 'get_scm_tabla_detalles/',
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
		                            minWidth: 150
		                        },
		                        width: 150,
		                        forceSelection: true,
		                        emptyText: '[ Seleccione ]',
		                        listeners:{
		                            afterrender: function(obj, e){
		                                obj.getStore().load({
		                                    params:{
		                                        vp_tab_id: 'MND',
		                                        vp_shipper: 0
		                                    },
		                                    callback: function(){
		                                        //obj.setValue(0);
		                                    }
		                                });
		                            },
		                            select:function(obj){
		                            }
		                        }
							},
							'Courier',
							{
								xtype:'combo',
								id:cobranza.id+'-per_id',
								store:Ext.create('Ext.data.Store',{
									fields:[
										{name:'nombre', type:'string'},
										{name:'per_id' , type:'string'}
									],
									proxy:{
										type:'ajax',
										url:cobranza.url+'scm_usr_sis_personal/',
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
									minWidth:340
								},
								width:140,
								forceSelection:true,
								selectOnFocus:true,
								//allowBlank:false,
								emptyText:'[ Seleccione ]',
								listeners:{
									afterrender:function(obj){
										obj.setValue(0);
									}
								}
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
								xtype:'grid',
								id:cobranza.id+'grid1',
								store:store_grid1,
								plugins: [
		                            {
		                                ptype: 'rowexpander',
		                                pluginId: cobranza.id + '-cellplugin',
		                                rowBodyTpl : new Ext.XTemplate(
		                                    '<div id="'+cobranza.id+'-{per_id}"></div>'
		                                )
		                            }
		                        ],
		                        features: [
				                            {
				                                ftype: 'summary',
				                                dock: 'bottom'
				                            }
		                        ],
								columns:{
									items:[
											{
												text:'Courier',
												dataIndex:'courier',
												flex:1,
												summaryRenderer: function(value, summaryData, dataIndex){
			                                        return '<div align="right" style="font-weight: bold;" >Totales:<div>';
			                                    }
											},
											{
												text:'Días',
												dataIndex:'dias',
												width:40,
												summaryType: 'average',
												summaryRenderer: function(value, summaryData, dataIndex){
			                                     	return '<div style="font-weight: bold;">'+value+'</div>';
			                                    }
											},
											{
												text:'Total GE',
												dataIndex:'tot_ge',
												align:'center',
												width:80,
												/*renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
			                                        return Ext.util.Format.number(value, '0,00');
			                                    },*/
												summaryType: 'sum',
												summaryRenderer: function(value, summaryData, dataIndex){
			                                     	return '<div style="font-weight: bold;">'+value+'</div>';
			                                    }
											},
											{
												text:'GE con COD',
												dataIndex:'tot_ge_cod',
												align:'center',
												width:90,
												/*renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
			                                        return Ext.util.Format.number(value, '0,00');
			                                    },*/
												summaryType: 'sum',
												summaryRenderer: function(value, summaryData, dataIndex){
			                                     	return '<div style="font-weight: bold;">'+value+'</div>';
			                                    }
											},
											{
												text:'Efectivo',												
												columns:[
															{
																text: 'GE',
																dataIndex:'efe_tot_ge',
																width:40,
																/*renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
							                                        return Ext.util.Format.number(value, '0,00');
							                                    },*/
																summaryType: 'sum',
																summaryRenderer: function(value, summaryData, dataIndex){
							                                     	return '<div style="font-weight: bold;">'+value+'</div>';
							                                    }
															},
															{
																text:'Importe',
																dataIndex:'efe_montos',
																width:60,
																/*renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
							                                        return Ext.util.Format.number(value, '0,00');
							                                    },*/
																summaryType: 'sum',
																summaryRenderer: function(value, summaryData, dataIndex){
																   return '<div style="font-weight: bold;">'+value.toFixed(2)+'</div>';
							                                       //return value.toFixed(2);//Ext.util.Format.number(value, '0,00');
							                                    }
															},
															{
																text:'Cobrados',
																dataIndex:'efe_cobrado',
																width:60,
																/*renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
							                                        return Ext.util.Format.number(value, '0,00');
							                                    },*/
																summaryType: 'sum',
																summaryRenderer: function(value, summaryData, dataIndex){
							                                     //  return Ext.util.Format.number(value, '0,00');
							                                     	//return value.toFixed(2);
							                                     	return '<div style="font-weight: bold;">'+value.toFixed(2)+'</div>';
							                                    }
															},
															{
																text:'Por Cobrar',
																dataIndex:'efe_pendiente',
																width:70,
																/*renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
							                                        return Ext.util.Format.number(value, '0,00');
							                                    },*/
																summaryType: 'sum',
																summaryRenderer: function(value, summaryData, dataIndex){
							                                     	return '<div style="font-weight: bold;">'+value.toFixed(2)+'</div>';
							                                    }
															}
												]
											},
											{
												text:'Tarjeta',
												columns:[
															{
																text: 'GE',
																dataIndex:'tar_tot_ge',
																width:40,
																/*renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
							                                        return Ext.util.Format.number(value, '0,00');
							                                    },*/
																summaryType: 'sum',
																summaryRenderer: function(value, summaryData, dataIndex){
							                                     	return '<div style="font-weight: bold;">'+value+'</div>';
							                                    }
															},
															{
																text:'Importe',
																dataIndex:'tar_montos',
																width:60,
																/*renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
							                                        return Ext.util.Format.number(value, '0,00');
							                                    },*/
																summaryType: 'sum',
																summaryRenderer: function(value, summaryData, dataIndex){
							                                       //return value.toFixed(2);
							                                       return '<div style="font-weight: bold;">'+value.toFixed(2)+'</div>';
							                                    }
															},
															{
																text:'Cobrados',
																dataIndex:'tar_cobrado',
																width:60,
																/*renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
							                                        return Ext.util.Format.number(value, '0,00');
							                                    },*/
																summaryType: 'sum',
																summaryRenderer: function(value, summaryData, dataIndex){
							                                     	//return value.toFixed(2);
							                                     	return '<div style="font-weight: bold;">'+value.toFixed(2)+'</div>';
							                                    }
															},
															{
																text:'Por Cobrar',
																dataIndex:'tar_pendiente',
																width:70,
																/*renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
							                                       return Ext.util.Format.number(value, '0,00');
							                                    },*/
																summaryType: 'sum',
																summaryRenderer: function(value, summaryData, dataIndex){
							                                      // return value.toFixed(2);
							                                      return '<div style="font-weight: bold;">'+value.toFixed(2)+'</div>';
							                                    }
															}
												]
											}
									]
								},
								viewConfig:{
									stripeRows: true,
		                            enableTextSelection: false,
		                            markDirty: false
								},
								trackMouseOver: false,
								listeners:{
									afterrender: function(obj){
										obj.getView().addListener('expandbody', function(rowNode, record, expandRow, eOpts){
											var vp_prov_codigo = Ext.getCmp(cobranza.id + '-agencia').getValue();
									        var vp_fecini = Ext.getCmp(cobranza.id+'fini').getRawValue();
									        var vp_fecfin = Ext.getCmp(cobranza.id+'ffin').getRawValue();
									        var vp_per_id = record.get('per_id');
											Ext.Ajax.request({
												url:cobranza.url+'scm_scm_COD_panel_rutas_detalle/',
												params:{vp_prov_codigo:vp_prov_codigo,vp_fecini:vp_fecini,vp_fecfin:vp_fecfin,vp_per_id:vp_per_id},
												success: function(response, options){
													var res = Ext.JSON.decode(response.responseText);
													//console.log(res);
													global.subtable({
														id:cobranza.id + '-subtable' + '-' + record.get('per_id'),
														columns: [

																{text:'Fecha',width:'70px',dataIndex:'fecha'},
																{text:'Manifiesto',width:'70px',dataIndex:'man_id'},
																{text:'Total GE',width:'70px',dataIndex:'tot_ge'},
																{text:'Total GE </br>con COD',width:'70px',dataIndex:'tot_ge_cod',align:'center'},
																

																{text: 'Efectivo </br>GE',dataIndex:'efe_tot_ge',width:'70px',align:'center'},
																{text:'Efectivo </br>Importe',dataIndex:'efe_montos',width:'70px',align:'center'},
																{text:'Efectivo </br>Cobrados',dataIndex:'efe_cobrado',width:'70px',align:'center'},
																{text:'Efectivo </br>Por Cobrar',dataIndex:'efe_pendiente',width:'70px',align:'center'},

																{text: 'Tarjeta </br>GE',dataIndex:'tar_tot_ge',width:'70px',align:'center'},
																{text:'Tarjeta </br>Importe',dataIndex:'tar_montos',width:'70px',align:'center'},
																{text:'Tarjeta </br>Cobrados',dataIndex:'tar_cobrado',width:'70px',align:'center'},
																{text:'Tarjeta </br>Por Cobrar',dataIndex:'tar_pendiente',width:'70px',align:'center'}
														],
														data: res.data,
														renderTo:cobranza.id + '-' + record.get('per_id')
													});
												}
												
											});
										});
									}
								}
							}
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
			        var vp_fecfin = Ext.getCmp(cobranza.id+'ffin').getRawValue();
			        var vp_per_id = Ext.getCmp(cobranza.id+'-per_id').getValue();
			        var vp_tmnd_id = Ext.getCmp(cobranza.id+'-tmnd_id').getValue();
					grid1.getStore().load({
						params:{vp_prov_codigo:vp_prov_codigo,vp_fecini:vp_fecini,vp_fecfin:vp_fecfin,vp_per_id:vp_per_id,vp_tmnd_id:vp_tmnd_id}
					});	
				}else{
					global.Msg({
						msg:'Debe Completar los datos',
						icon:0,
						fn:function(){
						}
					});
				}
				
			}
		}
		Ext.onReady(cobranza.init,cobranza);
	}else{
		tab.setActiveTab(cobranza.id+'-tab');
	}
</script>