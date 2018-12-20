<script type="text/javascript">

	var newpersonal = {
		id: 'newpersonal',
		id_menu:'<?php echo $p["id_menu"];?>',
        valido:null,
		init:function(){

		
		var panel = Ext.create('Ext.form.Panel', {
			 	id:newpersonal.id+'-formnew',
			    border:false,
			  //  frame:true,
			    width: '100%',
			    layout: {
					type: 'form',
			    },
			    items: [
			    	{
			    		xtype:'container',
			    		layout:'hbox',
			    		padding:'5 5 5 5',

			    		items:[
 							    {
									xtype: 'container',		
									layout:'hbox',
									flex: 1,
									items:[	
											{
												xtype:'textfield',
												fieldLabel:'Codigo RRHH:',
												id:newpersonal.id+'-codigorrhh',
												labelAlign: 'top',
												allowBlank: false,
												enableKeyEvents: true,
			            						width:100,
			            						listeners:{
			            							keypress:function(id,e){
                                                        var code = e.getCharCode();
                                                        if(code==13){
                                                            Ext.getCmp(newpersonal.id+'-tipodoc').enable();
                                                            Ext.getCmp(newpersonal.id+'-codigorrhh').disable();
                                                            Ext.getCmp(newpersonal.id+'-DNI').enable();
                                                            Ext.getCmp(newpersonal.id+'-DNI').focus(3,true);
                                                    	}
                                                    }

			            						}
											},
									]
							    },
                                {
                                    xtype:'container',
                                    layout:'hbox',
                                    flex:1.2,
                                    items:[
                                            {
                                                xtype: 'combo',
                                                id: newpersonal.id + '-tipodoc',
                                                labelAlign: 'top',
                                                fieldLabel:'Tip. Documento',
                                                store: Ext.create('Ext.data.Store',{
                                                    fields:[
                                                        {name: 'tipo', type: 'int'},
                                                        {name: 'descripcion', type: 'string'}
                                                    ],
                                                    proxy:{
                                                        type: 'ajax',
                                                        url: personal.url + 'gestion_personal_tdi/',
                                                        reader:{
                                                            type: 'json',
                                                            rootProperty: 'data'
                                                        }
                                                    }
                                                }),
                                                editable:false,
                                                queryMode: 'local',
                                                triggerAction: 'all',
                                                valueField: 'tipo',
                                                displayField: 'descripcion',
                                                width: 120,
                                                listConfig:{
                                                    minWidth: 200
                                                },
                                                forceSelection: true,
                                                allowBlank: false,
                                                emptyText: '[ Seleccione ]',
                                                listeners:{
                                                    afterrender: function(obj){
                                                        obj.getStore().load({
                                                            params:{},
                                                            callback: function(){
                                                             obj.setValue(1);  
                                                            }

                                                        });

                                                    },
                                                    'select':function(obj, records, eOpts){
                                                        Ext.getCmp(newpersonal.id+'-DNI').enable();
                                                        Ext.getCmp(newpersonal.id+'-DNI').focus(1,true);
                                                    }
                                                }
                                            }


                                    ]
                                },
							    {
							    	xtype:'container',
							    	layout:'hbox',
							    	flex: 1.4,
							    	items: [
							    			{
												xtype:'textfield',
												fieldLabel:'N° Documento',
												id:newpersonal.id+'-DNI',
												labelAlign: 'top',
												//allowBlank: false,
							                    maskRe : /^[0-9]$/,
							                    enableKeyEvents: true,
			            						width:150,
                                                minWidth: 150,
                                                //msgTarget: 'side',
			            						listeners:{
			            							keypress:function(id,e){
                                                        var code = e.getCharCode();
                                                     
                                                        if(code == 13){
                                                            var dni = Ext.getCmp(newpersonal.id+'-DNI').getValue();
                                                            var tipo = Ext.getCmp(newpersonal.id+'-tipodoc').getValue();

                                                            Ext.Ajax.request({
                                                                url:personal.url + 'gestion_personal_val_dni/',
                                                                params:{vl_doc_numero:dni,vl_tip_doc:tipo},
                                                                success:function(response,options){
                                                                   var res = Ext.JSON.decode(response.responseText);
                                                                    if (parseInt(res.data[0].erro) == 0) {
                                                                        global.Msg({
                                                                            msg:res.data[0].mensaje,
                                                                            icon:1,
                                                                            buttons:1,
                                                                            fn:function(){
                                                                                Ext.getCmp(newpersonal.id+'-DNI').focus(10,true);
                                                                            }
                                                                        });

                                                                    }else{
                                                                        Ext.getCmp(newpersonal.id+'-codigorrhh').disable();
                                                                        Ext.getCmp(newpersonal.id+'-DNI').disable();
                                                                        Ext.getCmp(newpersonal.id+'-tipodoc').disable();
                                                                        
                                                                        Ext.getCmp(newpersonal.id+'-apellidos').enable();
                                                                        Ext.getCmp(newpersonal.id+'-nombres').enable();
                                                                        Ext.getCmp(newpersonal.id+'-direccion').enable();
                                                                        Ext.getCmp(newpersonal.id+'-departamento').enable();
                                                                        Ext.getCmp(newpersonal.id+'-provincia').enable();
                                                                        Ext.getCmp(newpersonal.id+'-distrito').enable();
                                                                        Ext.getCmp(newpersonal.id+'-agencia').enable();
                                                                        Ext.getCmp(newpersonal.id+'-area').enable();
                                                                        Ext.getCmp(newpersonal.id+'-cargo').enable();
                                                                        Ext.getCmp(newpersonal.id+'-telefono').enable();
                                                                        Ext.getCmp(newpersonal.id+'-rpm').enable();
                                                                        Ext.getCmp(newpersonal.id+'-correo').enable();
                                                                        Ext.getCmp(newpersonal.id+'-apellidos').focus(10,true);    
                                                                    }                          
                                                                }
                                                            });

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
									id:newpersonal.id+'-apellidos',
									labelAlign: 'top',
            						//minWidth :360,
            						enableKeyEvents:true,
            						flex: 1,
									listeners:{
            							keypress:function(id,e){
                                          /*  var code = e.getCharCode();
                                            if(code==13){
                                                Ext.getCmp(newpersonal.id+'-nombres').focus(10,true);
                                            }
                                          */  
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
									id:newpersonal.id+'-nombres',
									labelAlign: 'top',
									enableKeyEvents:true,
            						//width:360,
            						flex: 1,
            						listeners:{
            							keypress:function(id,e){
                                          /*  var code = e.getCharCode();
                                            if(code==13){
                                                Ext.getCmp(newpersonal.id+'-direccion').focus(10,true);
                                            }
                                           */ 
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
									id:newpersonal.id+'-direccion',
									labelAlign:'top',
									flex:1,
									enableKeyEvents:true,
									listeners:{
            							keypress:function(id,e){
                                          /*  var code = e.getCharCode();
                                            if(code==13){
                                                Ext.getCmp(newpersonal.id+'-departamento').focus(10,true);
                                            }
                                           */ 
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
						                        id: newpersonal.id + '-departamento',
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
                                                editable:false,
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
						                            	Ext.getCmp(newpersonal.id+'-provincia').store.load({params:{va_departamento:'2',va_provincia:records.get('iddepto')},
                                                            callback:function(){
                                                                Ext.getCmp(newpersonal.id+'-provincia').focus(10,true);
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
						                        id: newpersonal.id + '-provincia',
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
                                                editable:false,
						                        forceSelection: true,
						                        allowBlank: false,
						                        emptyText: '[ Seleccione ]',
						                        listeners:{
						                        		'select':function(obj, records, eOpts){
			                        			           Ext.getCmp(newpersonal.id+'-distrito').store.load({params:{va_departamento:'3',va_provincia:records.get('iddepto'),va_distrito:records.get('id_prov')},
                                                                    callback:function(){
                                                                        Ext.getCmp(newpersonal.id+'-distrito').focus(10,true);
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
						                        id: newpersonal.id + '-distrito',
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
						                        valueField: 'ciu_id',
						                        displayField: 'nom_dist',
						                        listConfig:{
						                            minWidth: 200
						                        },
						                        width: 120,
                                                editable:false,
						                        forceSelection: true,
						                        allowBlank: false,
						                        emptyText: '[ Seleccione ]',
						                        listeners:{
						                        	'select':function(obj, records, eOpts){
                                                        Ext.getCmp(newpersonal.id+'-agencia').focus(10,true);
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
												id:newpersonal.id+'-telefono',
												maskRe : /^[0-9]$/,
												labelAlign: 'top',
			            						width:100,
			            						
											},*/
											{
												xtype:'combo',
												id:newpersonal.id+'-telefono',
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
												id:newpersonal.id+'-rpm',
												labelAlign: 'top',
			            						width:100,
			            						//flex: 1,
											},*/
											{
												xtype:'combo',
												id:newpersonal.id+'-rpm',
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
												id:newpersonal.id+'-correo',
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
						                        id: newpersonal.id + '-agencia',
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
                                                editable:false,
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
                                                        Ext.getCmp(newpersonal.id+'-area').focus(10,true);
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
						                        id: newpersonal.id + '-area',
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
                                                editable:false,
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
                                                        Ext.getCmp(newpersonal.id+'-cargo').focus(10,true);
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
						                        id: newpersonal.id + '-cargo',
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
                                                editable:false,
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
                                    xtype:'datefield',
                                    id:newpersonal.id+'-fecha_ingreso',
                                    fieldLabel: 'Fecha Ingreso',
                                    value:new Date(),
                                    allowBlank:false,
                                    width:100,
                                    labelAlign:'top',
                                }
						]

					}
			    ]
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
                        legend: 'Ingresa los datos del Usuario',
                        bg: '#991919',
                        items: [
                            panel
                        ]
                    }
                ]
            });
		

			Ext.create('Ext.window.Window',{
				id:newpersonal.id+'-win',
				title:'Registro de Personal',
				cls:'popup_show',
				height: 508,
				width: 600,
				resizable:false,
				modal: true,
				layout:{
					type:'fit'
				},
				modal: true,
				closable: false,
				header: false,
				//isHidden:true,
				//baseCls: 'gk-window',
				items:[
					panelX
				],
				dockedItems:[
								{
									xtype:'toolbar',
									dock:'bottom',
									ui:'footer',
									alignTarget:'center',
									layout:{
										pack:'center',
									},
									baseCls: 'gk-toolbar',
									items:[
											{					
												text:'Grabar',
												id: newpersonal.id+'-Grabar',
												icon: '/images/icon/save.png',		
											    listeners:{
						                            beforerender: function(obj, opts){
						                                global.permisos({
						                                    id_serv: 7, 
						                                    id_btn: obj.getId(), 
						                                    id_menu: newpersonal.id_menu,
						                                    fn: ['newpersonal.grabar']
						                                });
						                            },
						                            click: function(obj, e){
						                                newpersonal.grabar();
						                            }
						                        }
											},
											{
												text:'Limpiar',
												id: newpersonal.id+'-limpiar',
												icon: '/images/icon/broom.png',
												listeners:{
													click:function(obj, e){
														//Ext.getCmp(newpersonal.id+'-win').close();
							                            Ext.getCmp(newpersonal.id+'-formnew').reset();
														newpersonal.estado(true);
													}
												}
											},
											{
												text:'Regresar',
												id:newpersonal.id+'-salir',
												icon: '/images/icon/get_back.png',
												listeners:{
				                                    click: function(obj, e){
				                                        Ext.getCmp(newpersonal.id + '-win').hide();
				                                    }
				                                }
											}
									]
								}
				],
				listeners:{
					'afterrender':function(obj, e){ 
						newpersonal.estado(true);
						
					},


				}
			}).hide().center();
		},
		estado:function(action){
			if (action){		
                

                Ext.getCmp(newpersonal.id+'-codigorrhh').enable();

                Ext.getCmp(newpersonal.id+'-tipodoc').disable();
				Ext.getCmp(newpersonal.id+'-DNI').disable();
				Ext.getCmp(newpersonal.id+'-apellidos').disable();
				Ext.getCmp(newpersonal.id+'-nombres').disable();
				Ext.getCmp(newpersonal.id+'-direccion').disable();
				Ext.getCmp(newpersonal.id+'-departamento').disable();
				Ext.getCmp(newpersonal.id+'-provincia').disable();
				Ext.getCmp(newpersonal.id+'-distrito').disable();
				Ext.getCmp(newpersonal.id+'-agencia').disable();
				Ext.getCmp(newpersonal.id+'-area').disable();
				Ext.getCmp(newpersonal.id+'-cargo').disable();
                Ext.getCmp(newpersonal.id+'-telefono').disable();
                Ext.getCmp(newpersonal.id+'-rpm').disable();
                Ext.getCmp(newpersonal.id+'-correo').disable();

                
                Ext.getCmp(newpersonal.id+'-tipodoc').setValue(1);

				Ext.getCmp(newpersonal.id+'-codigorrhh').setValue('');
				Ext.getCmp(newpersonal.id+'-DNI').setValue('');
				Ext.getCmp(newpersonal.id+'-apellidos').setValue('');
				Ext.getCmp(newpersonal.id+'-nombres').setValue('');
				Ext.getCmp(newpersonal.id+'-direccion').setValue('');
				Ext.getCmp(newpersonal.id+'-departamento').setValue('');
				Ext.getCmp(newpersonal.id+'-provincia').setValue('');
				Ext.getCmp(newpersonal.id+'-distrito').setValue('');
				Ext.getCmp(newpersonal.id+'-agencia').setValue('');
				Ext.getCmp(newpersonal.id+'-area').setValue('');
				Ext.getCmp(newpersonal.id+'-cargo').setValue('');
				Ext.getCmp(newpersonal.id+'-telefono').setValue('');
				Ext.getCmp(newpersonal.id+'-rpm').setValue('');
				Ext.getCmp(newpersonal.id+'-correo').setValue('');

			}
		},
		grabar:function(){
			var codigorhh = Ext.getCmp(newpersonal.id+'-codigorrhh').getValue();				
			var dni = Ext.getCmp(newpersonal.id+'-DNI').getValue();
			var apellidos = Ext.getCmp(newpersonal.id+'-apellidos').getValue();
			var nombres = Ext.getCmp(newpersonal.id+'-nombres').getValue();
			var direccion = Ext.getCmp(newpersonal.id+'-direccion').getValue();
			var distrito = Ext.getCmp(newpersonal.id+'-distrito').getValue();
			var agencia = Ext.getCmp(newpersonal.id+'-agencia').getValue();
			var area = Ext.getCmp(newpersonal.id+'-area').getValue();
			var cargo = Ext.getCmp(newpersonal.id+'-cargo').getValue();
			var fingreso = Ext.getCmp(newpersonal.id+'-fecha_ingreso').getRawValue();
			var telefono = Ext.getCmp(newpersonal.id+'-telefono').getValue();
			var rpm = Ext.getCmp(newpersonal.id+'-rpm').getValue();
			var email = Ext.getCmp(newpersonal.id+'-correo').getValue();
			var form1 = Ext.getCmp(newpersonal.id+'-formnew').getForm();
            var tip_doc =Ext.getCmp(newpersonal.id+'-tipodoc').getValue();

			if ( form1.isValid() ){
					global.Msg({
						msg:'Seguro de Grabar Los Datos...',
						icon:3,
						buttons:3,
						fn:function(btn){
							if ( btn == 'yes'){
								Ext.Ajax.request({
									url:personal.url + 'gestion_personal_nuevo/',
									params:{codigorhh:codigorhh,dni:dni,apellidos:apellidos,nombres:nombres,
										direccion:direccion,agencia:agencia,area:area,cargo:cargo,fingreso:fingreso,distrito:distrito,telefono:telefono,rpm:rpm,email:email,tip_doc:tip_doc
									},
									success:function(response,options){
										var res = Ext.decode(response.responseText);
										if ( parseInt(res.data[0].error_sql) <= 0 ){
											global.Msg({
												msg:res.data[0].error_info,
												icon:1,
												buttons:1,
											});
											newpersonal.estado(true);
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
		}
	}
	Ext.onReady(newpersonal.init, newpersonal);
</script>