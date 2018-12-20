<script type="text/javascript">
	var editpersonal = {
		id: 'editpersonal',
		id_menu:'<?php echo $p["id_menu"];?>',
		tab_panel:'',
		init:function(){	
			var  store = Ext.create('Ext.data.Store',{
				fields:[
						{name: 'prov_codigo', type:'int'},
						{name: 'prov_nombre', type:'string'},
						{name: 'masivo', type:'bool'},
						{name: 'valorados', type:'bool'},
						{name: 'logistica', type:'bool'},       
				],
				pageSize: 50,
				proxy:{
					type:'ajax',
					url:personal.url + 'listar_servicio/',
					reader:{
						type:'json',
						root:'data',
					}
				},
				listeners:{
					load:function(obj, records, successful, opts){
					}
				}

			});	
			var panel = Ext.create('Ext.form.Panel', {
				 	id:editpersonal.id+'-formnew',
				    border:false,
				   // frame:true,
				  //  padding:'5 5 0 5',
				    width: '100%',
				    layout: {
					type: 'form',
					align: 'stretch'
				    },
				    items: [
				    	{
				    		xtype:'container',
				    		layout:'hbox',
				    		padding:'5 5 5 5',
				    		items:[

				    				{
				    					xtype:'container',
				    					layout:'hbox',
				    					flex:1,
				    					items:[
				    							{
													xtype:'textfield',
													fieldLabel:'Id Personal',
													id:editpersonal.id+'-id_personal',
													labelAlign: 'top',
													readOnly: true,
													allowBlank: false,
													width:70,
												}
				    					]

				    				},
	 							    {
										xtype: 'container',		
										layout:'hbox',
										flex: 2,
										items:[	
												{
													xtype:'textfield',
													fieldLabel:'Codigo RRHH:',
													id:editpersonal.id+'-codigorrhh',
													labelAlign: 'top',
													//readOnly: true,
													//allowBlank: false,
													enableKeyEvents: true,
				            						width:150,
				            						listeners:{
				            							keypress:function(id,e){
	                                                        var code = e.getCharCode();
	                                                        if(code==13){
	                                                            Ext.getCmp(editpersonal.id+'-DNI').enable();
	                                                            Ext.getCmp(editpersonal.id+'-DNI').focus(true,100);
	                                                    	}
	                                                    }

				            						}
												},
										]
								    },
								    {
								    	xtype:'container',
								    	layout:'hbox',
								    	flex: 2,
								    	items: [
								    			{
													xtype:'textfield',
													fieldLabel:'N° Documento:',
													id:editpersonal.id+'-DNI',
													labelAlign: 'top',
													allowBlank: false,
													//readOnly: true,
								                    maskRe : /^[0-9]$/,
								                    enableKeyEvents: true,
				            						width:150,
				            						listeners:{
				            							keypress:function(id,e){
	                                                        var code = e.getCharCode();
	                                                        if(code==13){
	                                                            Ext.getCmp(editpersonal.id+'-apellidos').enable();
	                                                            Ext.getCmp(editpersonal.id+'-apellidos').focus(true,100);
	                                                        }
	                                                    }
				            						}
												}
								    	]

								    }
							]	    
						},
						{
							xtype:'container',
							layout:'hbox',
							padding:'5 5 0 5',	
							items:[
									{
										xtype:'textfield',
										fieldLabel:'Apellidos',
										allowBlank: false,
										id:editpersonal.id+'-apellidos',
										labelAlign: 'top',
	            						//width:360,
	            						enableKeyEvents:true,
	            						flex: 1,
										listeners:{
	            							keypress:function(id,e){
	                                            var code = e.getCharCode();
	                                            if(code==13){
	                                                Ext.getCmp(editpersonal.id+'-nombres').enable();
	                                                Ext.getCmp(editpersonal.id+'-nombres').focus(10,true);
	                                            }
	                                        }
	            						}
									},

							]
						},
						{
							xtype:'container',
							layout:'hbox',
							padding:'5 5 0 5',
							items:[
									{
										xtype:'textfield',
										allowBlank: false,
										fieldLabel:'Nombres',
										id:editpersonal.id+'-nombres',
										labelAlign: 'top',
										enableKeyEvents:true,
	            						//width:360,
	            						flex: 1,
	            						listeners:{
	            							keypress:function(id,e){
	                                            var code = e.getCharCode();
	                                            if(code==13){
	                                                Ext.getCmp(editpersonal.id+'-direccion').enable();
	                                                Ext.getCmp(editpersonal.id+'-direccion').focus(10,true);
	                                            }
	                                        }
	            						}
									},

							]
						},
						{
							xtype:'container',
							layout:'hbox',
							padding:'5 5 0 5',
							items:[
									{
										xtype:'textfield',
										allowBlank:false,
										fieldLabel:'Direccion',
										id:editpersonal.id+'-direccion',
										labelAlign:'top',
										flex:1,
										enableKeyEvents:true,
										listeners:{
	            							keypress:function(id,e){
	                                            var code = e.getCharCode();
	                                            if(code==13){
	                                                Ext.getCmp(editpersonal.id+'-departamento').enable();
	                                                Ext.getCmp(editpersonal.id+'-departamento').focus(10,true);
	                                            }
	                                        }
	            						}
									}

							]
						},
						{
							xtype:'container',
							layout:'hbox',
							padding:'5 5 0 5',
							items:[
									{
										xtype:'container',
										layout:'hbox',
										flex: 1,
										items:[
							                    {
							                        xtype: 'combo',
							                        id: editpersonal.id + '-departamento',
							                        labelAlign: 'top',
							                        fieldLabel:'Departamento',
							                        store: Ext.create('Ext.data.Store',{
							                            fields:[
							                                {name: 'iddepto', type: 'string'},
							                                {name: 'nom_nomdep', type: 'string'}
							                            ],
							                            proxy:{
							                                type: 'ajax',
							                                url: personal.url + 'getComboDepartamentos/',
							                                reader:{
							                                    type: 'json',
							                                    rootProperty: 'data'
							                                }
							                            }
							                        }),
							                        queryMode: 'local',
							                        valueField: 'iddepto',
							                        displayField: 'nom_nomdep',
							                        listConfig:{
							                            minWidth: 200
							                        },
							                        width: 120,
							                        forceSelection: true,
							                        allowBlank: false,
							                        emptyText: '[ Seleccione ]',
							                        listeners:{
							                            afterrender: function(obj){
							                                obj.getStore().load({
							                                    params:{va_departamento:'1'},
							                                });
							                            },
							                            'select':function(obj, records, eOpts){
							                            	Ext.getCmp(editpersonal.id+'-provincia').store.load({params:{va_departamento:'2',va_provincia:records.get('iddepto')},
	                                                            callback:function(){
	                                                            	Ext.getCmp(editpersonal.id+'-provincia').enable();
	                                                                Ext.getCmp(editpersonal.id+'-provincia').focus(10,true);
	                                                            }
	                                                        });

							                            }
							                        }
							                    }
										]
									},
									{
										xtype:'container',
										layout:'hbox',
										flex: 1,
										items:[
							                    {
							                        xtype: 'combo',
							                        id: editpersonal.id + '-provincia',
							                        labelAlign: 'top',
							                        fieldLabel:'Provincia',
							                        store: Ext.create('Ext.data.Store',{
							                            fields:[
							                                {name: 'iddepto', type: 'string'},
							                                {name: 'id_prov', type: 'string'},
							                                {name: 'nom_prov',type: 'string'}
							                            ],
							                            proxy:{
							                                type: 'ajax',
							                                url: personal.url + 'getComboProvincias/',
							                                reader:{
							                                    type: 'json',
							                                    rootProperty: 'data'
							                                }
							                            }
							                        }),
							                        queryMode: 'local',
							                        valueField: 'id_prov',
							                        displayField: 'nom_prov',
							                        listConfig:{
							                            minWidth: 200
							                        },
							                        width: 120,
							                        forceSelection: true,
							                        allowBlank: false,
							                        emptyText: '[ Seleccione ]',
							                        listeners:{
							                        		'select':function(obj, records, eOpts){
				                        			           Ext.getCmp(editpersonal.id+'-distrito').store.load({params:{va_departamento:'3',va_provincia:records.get('iddepto'),va_distrito:records.get('id_prov')},
	                                                                    callback:function(){
	                                                                    	Ext.getCmp(editpersonal.id+'-distrito').enable();
	                                                                        Ext.getCmp(editpersonal.id+'-distrito').focus(10,true);
	                                                                    }
	                                                           });

							                        		}
							                        }
							                    }
										]
									},
									{
										xtype:'container',
										layout:'hbox',
										flex: 1,
										items:[
							                    {
							                        xtype: 'combo',
							                        id: editpersonal.id + '-distrito',
							                        labelAlign: 'top',
							                        fieldLabel:'Distrito',
							                        store: Ext.create('Ext.data.Store',{
							                            fields:[
							                                {name: 'iddepto', type: 'string'},
							                                {name: 'id_prov', type: 'string'},
							                                {name: 'id_dist',type: 'string'},
							                                {name: 'nom_dist',type: 'string'},
							                                {name: 'ciu_id',type: 'int'},
							                            ],
							                            proxy:{
							                                type: 'ajax',
							                                url: personal.url + 'getComboDistritos/',
							                                reader:{
							                                    type: 'json',
							                                    rootProperty: 'data'
							                                }
							                            }
							                        }),
							                        queryMode: 'local',
							                        valueField: 'id_dist',
							                        displayField: 'nom_dist',
							                        listConfig:{
							                            minWidth: 200
							                        },
							                        width: 120,
							                        forceSelection: true,
							                        allowBlank: false,
							                        emptyText: '[ Seleccione ]',
							                        listeners:{
							                        	'select':function(obj, records, eOpts){
							                        		Ext.getCmp(editpersonal.id+'-agencia').enable();
	                                                        Ext.getCmp(editpersonal.id+'-agencia').focus(10,true);
							                        	}

							                        }
							                    }
										]
									}																

							]

						},
						{
							xtype:'container',
							layout:'hbox',
							padding:'5 5 0 5',
							items:[
									{
										xtype:'container',
										layout:'hbox',
										flex: 0.5,
										items:[
												/*{
													xtype:'textfield',
													fieldLabel:'Telefono',
													//allowBlank: false,
													id:editpersonal.id+'-telefono',
													maskRe : /^[0-9]$/,
													labelAlign: 'top',
				            						width:100,
				            						
												},*/
												{
													xtype:'combo',
													id:editpersonal.id+'-telefono',
													fieldLabel:'Tipo:',
													labelAlign: 'top',
													store:Ext.create('Ext.data.Store',{
														fields:[
															{name: 'descripcion', type: 'string'},
							                                {name: 'id_elemento', type: 'int'},
							                                {name: 'des_corto', type: 'string'}
														],
														proxy:{
															type:'ajax',
															url:personal.url+'scm_scm_tabla_detalle/',
															reader:{
																type:'json',
																root:'data'
															}
														}
													}),
													queryMode: 'local',
							                        triggerAction: 'all',
							                        valueField: 'id_elemento',
							                        displayField: 'descripcion',
							                        listConfig:{
							                            minWidth: 200
							                        },
							                        width: 100,
							                        forceSelection: true,
							                        emptyText: '[ Seleccione ]',
							                        listeners:{
							                            afterrender: function(obj, e){
							                                obj.getStore().load({
							                                    params:{
							                                        vp_tabid: 'PER',
							                                        vp_shi_codigo: 0
							                                    }
							                                });
							                            }
							                        }
												}

										]
									},
									{
										xtype:'container',
										layout:'hbox',
										flex: 0.5,
										items:[
												/*{
													xtype:'textfield',
													fieldLabel:'RPM',
													//allowBlank: false,
													id:editpersonal.id+'-rpm',
													labelAlign: 'top',
				            						width:100,
				            						//flex: 1,
												},*/
												{
													xtype:'combo',
													id:editpersonal.id+'-rpm',
													fieldLabel:'Celular',
													labelAlign: 'top',
													store: Ext.create('Ext.data.Store',{
													fields:[
							                               	{name:'cel_id', type:'int'},
															{name:'cel_imei', type:'string'},
															{name:'cel_numero', type:'string'},
															{name:'cel_num_rp', type:'string'},
															{name:'tprop_id', type:'int'},
															{name:'prop_descri', type:'string'},
															{name:'cel_estado', type:'string'}      
							                            ],
							                           // autoLoad:true,
							                            proxy:{
							                                type: 'ajax',
							                                url: personal.url+'scm_scm_hue_select_celulares/',
							                                reader:{
							                                    type: 'json',
							                                    rootProperty: 'data'
							                                }
							                            }
							                        }),
							                        queryMode: 'local',
							                        valueField: 'cel_id',
							                        displayField: 'cel_numero',
							                        listConfig:{
							                            minWidth: 200
							                        },
							                        width: 100,
							                        forceSelection: true,
							                        allowBlank: false,
							                       // selectOnFocus:true,
							                        emptyText: '[ Seleccione ]',
							                        listeners:{
							                            afterrender: function(obj,record,options){
							                               obj.getStore().load();
							                            }
							                        }
							                    }    

										]
									},
									{
										xtype:'container',
										layout:'hbox',
										flex: 1,
										items:[
												{
													xtype:'textfield',
													fieldLabel:'Correo',
													//allowBlank: false,
													id:editpersonal.id+'-correo',
													labelAlign: 'top',
													//allowBlank: false,
													//maskRe : /^[a-zA-Z0-9|@|a-zA-Z0-9|.|a-zA-Z0-9]$/,
				            						//width:280,
				            						flex: 1,
												},

										]
									},								

							]	

						},
						{
							xtype:'container',
							layout:'hbox',
							padding:'5 5 0 5',
							items:[
									{
										xtype:'container',
										layout:'hbox',
										flex:1,
										items:[
												{
							                        xtype: 'combo',
							                        id: editpersonal.id + '-agencia',
							                        labelAlign: 'top',
							                        fieldLabel:'Agencia',
							                        store: Ext.create('Ext.data.Store',{
							                            fields:[
							                                {name: 'prov_codigo', type: 'int'},
							                                {name: 'prov_nombre', type: 'string'}
							                            ],
							                            proxy:{
							                                type: 'ajax',
							                                url: personal.url + 'get_usr_sis_provincias/',
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
							                        width: 120,
							                        forceSelection: true,
							                        allowBlank: false,
							                        emptyText: '[ Seleccione ]',
							                        listeners:{
							                            afterrender: function(obj){
							                                obj.getStore().load({
							                                    params:{},
							                                    callback: function(){
							                                    // obj.setValue(0);  
							                                    }
							                                });
							                            },
							                            'select':function(obj, records, eOpts){
							                        		Ext.getCmp(editpersonal.id+'-area').enable();
	                                                        Ext.getCmp(editpersonal.id+'-area').focus(10,true);
							                        	}
							                        }
							                    }

										]
									},
									{
										xtype:'container',
										layout:'hbox',
										flex:1,
										items:[
												{
							                        xtype: 'combo',
							                        id: editpersonal.id + '-area',
							                        labelAlign: 'top',
							                        fieldLabel:'Area',
							                        store: Ext.create('Ext.data.Store',{
							                            fields:[
							                                {name: 'id_area', type: 'int'},
							                                {name: 'area_nombre', type: 'string'}
							                            ],
							                            proxy:{
							                                type: 'ajax',
							                                url: personal.url + 'gestion_personal_areas/',
							                                reader:{
							                                    type: 'json',
							                                    rootProperty: 'data'
							                                }
							                            }
							                        }),
							                        queryMode: 'local',
							                        valueField: 'id_area',
							                        displayField: 'area_nombre',
							                        listConfig:{
							                            minWidth: 300
							                        },
							                        width: 120,
							                        forceSelection: true,
							                        allowBlank: false,
							                        emptyText: '[ Seleccione ]',
							                        listeners:{
							                            afterrender: function(obj){
							                                obj.getStore().load({
							                                    params:{},
							                                    callback: function(){
							                                     obj.setValue(0);  
							                                    }
							                                });
							                            },
							                            'select':function(obj, records, eOpts){
							                        		Ext.getCmp(editpersonal.id+'-cargo').enable();
	                                                        Ext.getCmp(editpersonal.id+'-cargo').focus(10,true);
							                        	}
							                        }
							                    }
										]
									},
									{
										xtype:'container',
										layout:'hbox',
										flex:1,
										items:[
												{
							                        xtype: 'combo',
							                        id: editpersonal.id + '-cargo',
							                        labelAlign: 'top',
							                        fieldLabel:'Cargo',
							                        store: Ext.create('Ext.data.Store',{
							                            fields:[
							                                {name: 'id_cargo', type: 'int'},
							                                {name: 'car_nombre', type: 'string'}
							                            ],
							                            proxy:{
							                                type: 'ajax',
							                                url: personal.url + 'gestion_personal_cargos/',
							                                reader:{
							                                    type: 'json',
							                                    rootProperty: 'data'
							                                }
							                            }
							                        }),
							                        queryMode: 'local',
							                        valueField: 'id_cargo',
							                        displayField: 'car_nombre',
							                        listConfig:{
							                            minWidth: 200
							                        },
							                        width: 120,
							                        forceSelection: true,
							                        allowBlank: false,
							                        emptyText: '[ Seleccione ]',
							                        listeners:{
							                            afterrender: function(obj){
							                                obj.getStore().load({
							                                    params:{},
							                                    callback: function(){
							                                     //obj.setValue(0);  
							                                    }
							                                });
							                            }
							                        }
							                    }


										]
									}

							]

						},
						{
							xtype:'container',
							layout:'hbox',
							padding:'5 5 0 5',
							items:[
									{
										xtype:'container',
										layout:'hbox',
										flex:1,
										items:[
												{
				                                    xtype:'datefield',
				                                    id:editpersonal.id+'-fecha_ingreso',
				                                    fieldLabel: 'Fecha Ingreso',
				                                    value:new Date(),
				                                    allowBlank:false,
				                                    width:100,
				                                    labelAlign:'top',

				                                }
										]
									},
									{
										xtype:'container',
										//layout:'hbox',
										flex:1,
										items:[
												{
	                                                xtype: 'radiogroup',
	                                                id:editpersonal.id+'-va_radio',
	                                                fieldLabel: 'Estado',
	                                                //width:100,
	                                                labelAlign:'top',
	                                               // frame:true,
	                                                columns: 2,
	                                                vertical: true,
	                                                disabled:true,
	                                                items: [
	                                                    { boxLabel: 'Activo',id:editpersonal.id+'activo', name:'per_estado', inputValue: '1',checked: true },
	                                                    { boxLabel: 'Cesado',id:editpersonal.id+'cese', name: 'per_estado', inputValue: '0'}
	                                                ],
	                                                listeners: {
	                                                    change: function(el,val) {
	                                                        if(val.per_estado == 1){
	                                                           Ext.getCmp(editpersonal.id+'-fecha_cese').setVisible(false);
	                                                        }else{
	                                                           Ext.getCmp(editpersonal.id+'-fecha_cese').setVisible(true);
	                                                        }
	                                                    }
	                                                }
	                                            }

										]
									},
									{
										xtype:'container',
										layout:'hbox',
										flex:1,
										items:[	
						                        {
	                                                xtype:'datefield',
	                                                id:editpersonal.id+'-fecha_cese',
	                                                fieldLabel: 'Fecha Cese',
	                                                allowBlank:true,
	                                                width:100,
	                                                labelAlign:'top',
	                                                listeners:{
	                                                    'afterrender':function(){
	                                                        var estado = Ext.getCmp(editpersonal.id+'-va_radio').getValue();
	                                                        if(estado.per_estado == 1){
	                                                            Ext.getCmp(editpersonal.id+'-fecha_cese').setVisible(false);
	                                                        }
	                                                    }
	                                                }
	                                            }

										]
									}

	                               
							]

						}
				    ]
				});
			var servicio = Ext.create('Ext.form.Panel',{
				id:editpersonal.id+'-fromservicio',
				border:false,
				width:'100%',
				height:'100',
				layout: {
					type:'form',
					align:'stretch'
				},
				
				items:[	
							{
								xtype:'panel',
								id:editpersonal.id+'-panel_servicio',
								region:'center',
								border:false,
								layout:'fit',
								height:401,
								items:[
										{
											xtype:'grid',
											id:editpersonal.id+'grid_servicio',
											store:store,
											columnsLines:true,
											
									
											
											columns:{ 
												items:[
														/*{
															text:'Id Agencia',
															dataIndex:'prov_codigo',
														},*/

														{
															text:'Agencia',
															dataIndex:'prov_nombre',
															flex:1
														},
														{
															xtype:'checkcolumn',
															id:editpersonal.id+'-pmasivo',
															header: 'Postal Masivo',//'<input type="checkbox" name="masivo" onchange="chk()">Poastal Masivo',
															dataIndex:'masivo',
															disabled:true,
															width:120,
															listeners:{
																checkchange:function(value, rowIndex, checked, eOpts){
																	editpersonal.change_column(value, rowIndex, checked, eOpts);
																},
															}
														},
														{
															xtype:'checkcolumn',
															id:editpersonal.id+'-pvalorados',  
															header:'Postal Valorados',//'<input type="checkbox" name="masivo">Postal Valorados',
															dataIndex:'valorados',
															disabled:true,
															width:120,
															listeners:{
																checkchange:function(value, rowIndex, checked, eOpts){
																	editpersonal.change_column(value, rowIndex, checked, eOpts);
																},
															}
														},
														{
															xtype:'checkcolumn',
															id:editpersonal.id+'-logistica',
															header:'Logistica Liviana',//'<input type="checkbox" name="masivo">Logistica Liviana',
															dataIndex:'logistica',
															disabled:true,
															width:120,
															listeners:{
																checkchange:function(value, rowIndex, checked, eOpts){
																	editpersonal.change_column(value, rowIndex, checked, eOpts);	
																}
															}
														},
												],
												defaults:{
													sortable:true
												}
											},
											viewConfig:{
												stripRows:true,
												enableTextSeleccion:true,
												markDirty:false
											},
											trackMouseOver:true,
										}
								],
								bbar:Ext.create('Ext.PagingToolbar',{
									store:store,
									displayInfo:true,
									displayMsg: '{0} - {1} de {2} Registros',
									emptyMsg: "No existe registros",
									listeners:{
											beforechange:function(obj, page, opts){
												/*obj.store.proxy.extraParams={
													per_id:Ext.getCmp(editpersonal.id+'-id_personal').getValue();
												};*/
											},
									}
								})

							}

				],
			});
			
		    var panelX = Ext.create('Ext.form.Panel',{
	            layout: 'fit',
	            border: false,
	            bodyStyle: 'background: transparent',
	            items:[
	                {
	                    xtype: 'uePanel',
	                    title: 'Registro de Personal',
	                    logo: 'signup',//upload
	                    legend: 'Modifica los datos del Usuario',
	                    bg: '#991919',
	                    items: [
	                        		{
	                        			region: 'center',
	                        			xtype : 'tabpanel' ,
	                        			id:editpersonal.id+'-tab',
	                        			border:false,
	                        			autoScroll:true,
	                        			defaults:{
	                        				border:false
	                        			},
	                        			items:[
	                        					{
	                        						title:'Personal',
	                        						id:editpersonal.id+'-personal-tab',
	                        						iconCls:'',
	                        						layout:{
	                        							type:'fit'
	                        						},
	                        						items:[
	                        							panel
	                        						]
	                        					},
	                        					{
	                        						title:'Servicios',
	                        						id:editpersonal.id+'servicios-tab',
	                        						iconCls:'',
	                        						readOnly: true,
	                        						layout:{
	                        							type:'fit'
	                        						},
	                        						items:[
	                        							servicio	
	                        						]

	                        					}
	                        			],
	                        			listeners:{
	                        				beforetabchange:function( tabPanel, newCard, oldCard, eOpts ){
	                        					 editpersonal.tab_panel = newCard.id;
	                        					if (newCard.id == editpersonal.id+'servicios-tab'){
	                        						editpersonal.estado(true);
	                        						Ext.getCmp(editpersonal.id+'-grabar').disable();
	                        						Ext.getCmp(editpersonal.id+'-cancelar').disable();
	                        						Ext.getCmp(editpersonal.id+'-salir').enable();
	                        						
	                        					}else if (newCard.id == editpersonal.id+'-personal-tab'){
	                        						editpersonal.estado(true);                    						
	                        					}
	                        				}
	                        			}
	                        		}	
	                    ]
	                }
	            ]
	        });

				Ext.create('Ext.window.Window',{
					id:editpersonal.id+'-win',
					title:'Registro de Personal',
					cls:'popup_show',
					height: 535,
					width: 600,
					resizable:false,
					closable: false,
					animateTarget: 'show_editar',
					header: false,
					layout:{
						type:'fit'
					},
					modal: true,
					items:[
						panelX
					],
					//baseCls: 'gk-window',
					dockedItems:[
									{
	 									 xtype: 'toolbar',
				                         dock: 'bottom',
		 		                         ui: 'footer',
				                         alignTarget: 'center',
				                         layout:{
				                            pack: 'center'
				                         },
				                         baseCls: 'gk-toolbar',
				                         items:[			    {
										    	text:'',
										    	id:editpersonal.id+'editar',
										    	icon:'/images/icon/edit.png',
										    	listeners:{
							                        beforerender: function(obj, opts){
							                            global.permisos({
							                                id_serv: 3, 
							                                id_btn: obj.getId(), 
							                                id_menu: editpersonal.id_menu,
							                                fn: ['editpersonal.editar']
							                            });
							                        },
							                        click: function(obj, e){
							                            editpersonal.editar();
							                        }
							                    }

										    },
										    {					
												text:'',
												id: editpersonal.id+'-grabar',
												icon: '/images/icon/save.png',		
											    listeners:{
							                        beforerender: function(obj, opts){
							                            global.permisos({
							                                id_serv: 7,// 7
							                                id_btn: obj.getId(), 
							                                id_menu: editpersonal.id_menu,
							                                fn: ['editpersonal.grabar']
							                            });
							                        },
							                        click: function(obj, e){
							                            editpersonal.grabar();
							                        }
							                    }
											},'-',
											{
												text:'',
												id: editpersonal.id+'-cancelar',
												icon: '/images/icon/cancel.png',
												listeners:{
													click:function(obj, e){
														//Ext.getCmp(editpersonal.id+'-win').close();
														editpersonal.estado(true);
													}
												}
											},
											{
												text:'',
												id:editpersonal.id+'-salir',
												icon: '/images/icon/get_back.png',
												listeners:{
				                                    click: function(obj, e){
				                                        Ext.getCmp(editpersonal.id + '-win').close();
				                                    }
				                                }
											}

				                         ]
									}

					],
					listeners:{
						afterrender:function(obj, e){ 

							editpersonal.estado(true);
							var rec = Ext.getCmp(personal.id + '-grid').getSelectionModel().getSelection();
							console.log(rec);
							ciu_ubigeo = rec[0].data.ciu_ubigeo;
							dpto = ciu_ubigeo.substring(0,2);
			                prov = ciu_ubigeo.substring(2,4);
			                dist = ciu_ubigeo.substring(4,6);
			                vl_per_id = rec[0].data.per_id;
			            
			              Ext.getCmp(editpersonal.id+'grid_servicio').getStore().load({
			              	  params:{per_id:vl_per_id},
			              	  callback: function(){
			              	  }
			              });

							
							Ext.getCmp(editpersonal.id+'-id_personal').setValue(rec[0].data.per_id);
							Ext.getCmp(editpersonal.id+'-codigorrhh').setValue(rec[0].data.per_codigo);
							Ext.getCmp(editpersonal.id+'-DNI').setValue(rec[0].data.doc_numero);
							Ext.getCmp(editpersonal.id+'-apellidos').setValue(rec[0].data.per_apellido);
							Ext.getCmp(editpersonal.id+'-nombres').setValue(rec[0].data.per_nombre);

							Ext.getCmp(editpersonal.id+'-direccion').setValue(rec[0].data.direccion);
							Ext.getCmp(editpersonal.id+'-telefono').setValue(rec[0].data.l_per_telefono);
							Ext.getCmp(editpersonal.id+'-rpm').setValue(rec[0].data.cel_id);
							Ext.getCmp(editpersonal.id+'-correo').setValue(rec[0].data.l_per_email);
							Ext.getCmp(editpersonal.id+'-fecha_ingreso').setValue(rec[0].data.l_fec_ingreso);
							Ext.getCmp(editpersonal.id+'-agencia').setValue(rec[0].data.prov_codigo);
						
							

							var chk1 =Ext.getCmp(editpersonal.id+'activo');
							var chk2 =Ext.getCmp(editpersonal.id+'cese');

							if ( (rec[0].data.l_per_estado) == 1 ) {
								chk1.setValue(true);
							}
							if ( (rec[0].data.l_per_estado) == 0 ) {
								chk2.setValue(true);	
							}


							Ext.getCmp(editpersonal.id+'-fecha_cese').setValue(rec[0].data.fec_cese);

							

							Ext.getCmp(editpersonal.id+'-departamento').store.load({params:{va_departamento:'1'},
							       callback:function(){
							          Ext.getCmp(editpersonal.id+'-departamento').setValue(dpto);  
							       }
							});

							Ext.getCmp(editpersonal.id+'-provincia').store.load({params:{va_departamento:'2',va_provincia:dpto},
							       callback:function(){
							          Ext.getCmp(editpersonal.id+'-provincia').setValue(prov);   
							       }
							});

							Ext.getCmp(editpersonal.id+'-distrito').store.load({params:{va_departamento:'3',va_provincia:dpto,va_distrito:prov},
	                                callback:function(){
	                                    Ext.getCmp(editpersonal.id+'-distrito').setValue(dist);
	                                }

	                        });

	                        Ext.getCmp(editpersonal.id+'-agencia').store.load({params:{},
							        callback:function(){
							            Ext.getCmp(editpersonal.id+'-agencia').setValue(parseInt(rec[0].data.prov_codigo));
							        }
							});

							Ext.getCmp(editpersonal.id+'-area').store.load({
							        callback:function(){
							            Ext.getCmp(editpersonal.id+'-area').setValue(parseInt(rec[0].data.id_area));
							        }
							});

							Ext.getCmp(editpersonal.id+'-cargo').store.load({
							        callback:function(){
							            Ext.getCmp(editpersonal.id+'-cargo').setValue(parseInt(rec[0].data.id_cargo));
							        }
							});		

							//Ext.getCmp(editpersonal.id+'-grabar').disable();
							//Ext.getCmp(editpersonal.id+'-cancelar').disable();
										

						},
						close:function(){
							personal.consultar();
						}

					}
				}).show().center();
		},
		estado:function(action){



			if (action){		

				Ext.getCmp(editpersonal.id+'-codigorrhh').disable();
				Ext.getCmp(editpersonal.id+'-DNI').disable();
				Ext.getCmp(editpersonal.id+'-apellidos').disable();
				Ext.getCmp(editpersonal.id+'-nombres').disable();
				Ext.getCmp(editpersonal.id+'-direccion').disable();
				Ext.getCmp(editpersonal.id+'-departamento').disable();
				Ext.getCmp(editpersonal.id+'-provincia').disable();
				Ext.getCmp(editpersonal.id+'-distrito').disable();
				Ext.getCmp(editpersonal.id+'-agencia').disable();
				Ext.getCmp(editpersonal.id+'-area').disable();
				Ext.getCmp(editpersonal.id+'-cargo').disable();
				Ext.getCmp(editpersonal.id+'-telefono').disable();
				Ext.getCmp(editpersonal.id+'-rpm').disable();
				Ext.getCmp(editpersonal.id+'-correo').disable();
				Ext.getCmp(editpersonal.id+'-fecha_ingreso').disable();
				//Ext.getCmp(editpersonal.id+'-va_radio').disable();
				//Ext.getCmp(editpersonal.id+'-va_radio').disable();
				
				Ext.getCmp(editpersonal.id+'-fecha_cese').disable();
				Ext.getCmp(editpersonal.id+'-panel_servicio').enable();
				Ext.getCmp(editpersonal.id+'-grabar').disable();
				Ext.getCmp(editpersonal.id+'-cancelar').disable();
				Ext.getCmp(editpersonal.id+'editar').enable();
				Ext.getCmp(editpersonal.id+'-salir').enable();
				//Ext.getCmp(editpersonal.id+'servicios-tab').disable();
				Ext.getCmp(editpersonal.id+'-pmasivo').disable();
				Ext.getCmp(editpersonal.id+'-pvalorados').disable();
				Ext.getCmp(editpersonal.id+'-logistica').disable();


				
				
			}else{
				Ext.getCmp(editpersonal.id+'-codigorrhh').enable();
				Ext.getCmp(editpersonal.id+'-DNI').enable();
				Ext.getCmp(editpersonal.id+'-apellidos').enable();
				Ext.getCmp(editpersonal.id+'-nombres').enable();
				Ext.getCmp(editpersonal.id+'-direccion').enable();
				Ext.getCmp(editpersonal.id+'-departamento').enable();
				Ext.getCmp(editpersonal.id+'-provincia').enable();
				Ext.getCmp(editpersonal.id+'-distrito').enable();
				Ext.getCmp(editpersonal.id+'-agencia').enable();
				Ext.getCmp(editpersonal.id+'-area').enable();
				Ext.getCmp(editpersonal.id+'-cargo').enable();
				Ext.getCmp(editpersonal.id+'-telefono').enable();
				Ext.getCmp(editpersonal.id+'-rpm').enable();
				Ext.getCmp(editpersonal.id+'-correo').enable();
				Ext.getCmp(editpersonal.id+'-fecha_ingreso').enable();
				Ext.getCmp(editpersonal.id+'-va_radio').enable();
				Ext.getCmp(editpersonal.id+'-va_radio').setReadOnly(false);



				Ext.getCmp(editpersonal.id+'-fecha_cese').enable();
				Ext.getCmp(editpersonal.id+'-salir').disable();
				
				//Ext.getCmp(editpersonal.id+'-panel_servicio').disable();
				Ext.getCmp(editpersonal.id+'editar').disable();

				if (editpersonal.tab_panel == editpersonal.id+'servicios-tab'){
	            	Ext.getCmp(editpersonal.id+'-cancelar').disable();
					Ext.getCmp(editpersonal.id+'-grabar').disable();
					Ext.getCmp(editpersonal.id+'-salir').enable();

				}else{
					Ext.getCmp(editpersonal.id+'-cancelar').enable();
					Ext.getCmp(editpersonal.id+'-grabar').enable();	
					Ext.getCmp(editpersonal.id+'-salir').disable();				
				}



				

				Ext.getCmp(editpersonal.id+'-pmasivo').enable();
				Ext.getCmp(editpersonal.id+'-pvalorados').enable();
				Ext.getCmp(editpersonal.id+'-logistica').enable();
			}

		},
		change_column:function(value, rowIndex, checked, eOpts){
			//console.log(rowIndex);
			var rec = Ext.getCmp(editpersonal.id+'grid_servicio').getStore().getAt(rowIndex);
			vl_per_id = Ext.getCmp(editpersonal.id+'-id_personal').getValue();
			vl_linea = value.dataIndex;
			vl_prov_codigo = rec.data.prov_codigo;			
			//console.log(value.dataIndex);
			Ext.Ajax.request({
				url:personal.url + 'per_servicio/',
				params:{per_id:vl_per_id,linea:vl_linea,prov_codigo:vl_prov_codigo,check:checked},
				success:function(response,options){
					var res = Ext.decode(response.responseText);
					if (parseInt(res.data[0].error_sql)==-1){
						global.Msg({
							msg:res.data[0].error_info,
							icon:0,
							fn:function(btn){

							}
						});
					}else{
						//console.log(rec.data.prov_codigo);
						Ext.getCmp(editpersonal.id+'grid_servicio').getStore().load({
			              	  params:{per_id:vl_per_id},
			              	  callback: function(){
			              	  }
		              	});
					/*	global.Msg({
							msg:res.data[0].error_info,
							icon:0,
							buttons:1,
						});
					*/	
					}
				}

			});
			

		},
		grabar:function(){


			var per_id = Ext.getCmp(editpersonal.id+'-id_personal').getValue();
			var codigorhh = Ext.getCmp(editpersonal.id+'-codigorrhh').getValue();				
			var dni = Ext.getCmp(editpersonal.id+'-DNI').getValue();
			var apellidos = Ext.getCmp(editpersonal.id+'-apellidos').getValue();
			var nombres = Ext.getCmp(editpersonal.id+'-nombres').getValue();
			
			var direccion = Ext.getCmp(editpersonal.id+'-direccion').getValue();

			var distrito = Ext.getCmp(editpersonal.id+'-distrito').getValue();
			var provincia= Ext.getCmp(editpersonal.id + '-provincia').getValue();
			var departamento = Ext.getCmp(editpersonal.id + '-departamento').getValue();

			var agencia = Ext.getCmp(editpersonal.id+'-agencia').getValue();
			var area = Ext.getCmp(editpersonal.id+'-area').getValue();
			var cargo = Ext.getCmp(editpersonal.id+'-cargo').getValue();
			var fingreso = Ext.getCmp(editpersonal.id+'-fecha_ingreso').getRawValue();
			var telefono = Ext.getCmp(editpersonal.id+'-telefono').getValue();
			var rpm = Ext.getCmp(editpersonal.id+'-rpm').getValue();
			var email = Ext.getCmp(editpersonal.id+'-correo').getValue();
			//var estad = Ext.getCmp(editpersonal.id+'-va_radio').getValue();
			var fecha_cese = Ext.getCmp(editpersonal.id+'-fecha_cese').getRawValue();
		    
		    var chk1 = Ext.getCmp(editpersonal.id+'activo').getValue();
		    var chk2 = Ext.getCmp(editpersonal.id+'cese').getValue();
		    var ciu_ubigeo = departamento + provincia +distrito;

		    if (chk1) {estad = '1';}
		    if (chk2) {estad = '0';}

			var form1 = Ext.getCmp(editpersonal.id+'-formnew').getForm();

			if ( form1.isValid() ){
					//alert(valido);
					//console.log('sss');
					global.Msg({
						msg:'Seguro de Actualizar Los Datos...',
						icon:3,
						buttons:3,
						fn:function(btn){
							if ( btn == 'yes'){
								Ext.Ajax.request({
									url:personal.url + 'gestion_personal_update/',
									params:{codigorhh:codigorhh,dni:dni,apellidos:apellidos,nombres:nombres,
										direccion:direccion,agencia:agencia,area:area,cargo:cargo,fingreso:fingreso,ciu_ubigeo:ciu_ubigeo,per_id:per_id,per_estado:estad,fecha_cese:fecha_cese,telefono:telefono,
										rpm:rpm,email:email
									},
									success:function(response,options){
										var res = Ext.decode(response.responseText);
										if ( parseInt(res.data[0].error_sql) <= 0 ){
											global.Msg({
												msg:res.data[0].error_info,
												icon:1,
												buttons:1,
												fn:function(){
													Ext.getCmp(editpersonal.id+'-win').close();
												}
											});
											editpersonal.estado(true);
										}
									}
								});

							}
						}
					});

			}else{
			    global.Msg({
                    msg:'Debe Completar los campos requeridos',
                    icon:0,
                });
			}




		},
		editar:function(){
			editpersonal.estado(false);
		},

	}
	Ext.onReady(editpersonal.init, editpersonal);
</script>