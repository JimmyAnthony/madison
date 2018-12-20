<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('geoRef-tab')){
		var geoRef = {
			id:'geoRef',
			id_menu:'<?php echo $p["id_menu"];?>',
			url: '/gestion/georef/',
			init:function(){
				var store = Ext.create('Ext.data.Store',{
					fields:[
						{name: 'prov_codigo', type: 'int'},
						{name: 'prov_nombre', type: 'string'},
						{name: 'shi_codigo', type: 'int'},
						{name: 'shi_nombre', type: 'string'},
						{name: 'con_descri', type: 'string'},
						{name: 'ciclo', type: 'string'},
						{name: 'guias', type: 'int'},
						{name: 'adm', type: 'int'},
						{name: 'pro_id', type: 'int'},
						{name: 'id_orden', type: 'int'},
						
						{name: 'pend', type: 'int'}                       
					],
					proxy:{
						type:'ajax',
						url:geoRef.url+'scm_scm_georeferenciar_select/',
						reader:{
							type:'json',
							rootProperty:'data'
						}
					}
				});
				var panel = Ext.create('Ext.form.Panel',{
					id:geoRef.id+'-form',
					border:false,
					layout:'fit',
					defaults:{
						border:false
					},
					tbar:[
							'Agencia',
							{
								xtype:'combo',
								id:geoRef.id+'-agencia',
								store: Ext.create('Ext.data.Store',{
		                            fields:[
		                                {name: 'prov_codigo', type: 'int'},
		                                {name: 'prov_nombre', type: 'string'}
		                            ],
		                            proxy:{
		                                type: 'ajax',
		                                url: geoRef.url + 'get_usr_sis_provincias/',
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
		                        	afterrender:function(obj,record,options){
		                        		var prov_codigo = '<?php echo PROV_CODIGO;?>';
		                        		obj.getStore().load({
		                        			params:{vp_id_linea:0},
		                        			callback:function(){
		                        				obj.setValue(prov_codigo);
		                        			}
		                        		});
		                        	}
		                        }
							},
							'Shipper',
							{
								xtype:'combo',
								id:geoRef.id+'-shipper',
								store:Ext.create('Ext.data.Store',{
								fields:[
										{name: 'shi_codigo', type: 'int'},
		                                {name: 'shi_nombre', type: 'string'},
		                                {name: 'shi_id', type: 'string'}
								],
								proxy:{
									type:'ajax',
									url:geoRef.url+'get_usr_sis_shipper/',
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
								//allowBlank:false,
								selecOnFocus:true,
								emptyText:'[ Seleccione ]',
								listeners:{
									afterrender:function(obj){
										obj.getStore().load({
											params:{vp_linea:0}
										});
									},
									select:function(obj){
										Ext.getCmp(geoRef.id+'-servicio').setValue();
										Ext.getCmp(geoRef.id+'-servicio').getStore().load({
											params:{
												vp_shipper:obj.getValue(),
												vp_linea:0
											}
										});
									}
								}
							},
							'Servicio',
							{
								xtype:'combo',
								id:geoRef.id+'-servicio',
								store: Ext.create('Ext.data.Store',{
		                            fields:[
		                                {name: 'id_orden', type: 'int'},
		                                {name: 'pro_nombre', type: 'string'}
		                            ],
		                            proxy:{
		                                type: 'ajax',
		                                url: geoRef.url + 'get_usr_sis_productos/',
		                                reader:{
		                                    type: 'json',
		                                    rootProperty: 'data'
		                                }
		                            }
		                        }),
		                        queryMode: 'local',
		                        valueField: 'id_orden',
		                        displayField: 'pro_nombre',
		                        listConfig:{
		                            minWidth: 250
		                        },
		                        width: 100,
		                        forceSelection: true,
		                        //allowBlank: false,
		                        selectOnFocus:true,
		                        emptyText: '[ Seleccione ]',
		                        listeners:{
		                            afterrender: function(obj,record,options){
		                               /* obj.getStore().load({
		                                    params:{},
		                                    callback: function(){
		                                     //obj.setValue();  
		                                    }
		                                });*/
		                            }
		                        }
							},
							'Fecha:',
							{
								xtype:'datefield',
								id:geoRef.id+'-fini',
								value:new Date()
							},
							'Hasta',
							{
								xtype:'datefield',
								id:geoRef.id+'-ffin',
								value:new Date()
							},
							{
								text:'',
								id:geoRef.id+'-buscar',
								icon: '/images/icon/search.png',
								listeners:{
								    beforerender: function(obj, opts){
		                                /*global.permisos({
		                                    id_serv: 108, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: cobranza.id_menu,
		                                    fn: ['cobranza.buscar']
		                                });*/
		                            },
									click:function(obj){
										geoRef.buscar();
									}
								}
							},
							{
								text:'',
								id:geoRef.id+'-dowload-a',
								tooltip:'Descargar todo el listado',
								icon: '/images/icon/download_a.png',
								listeners:{
								    beforerender: function(obj, opts){
		                                /*global.permisos({
		                                    id_serv: 108, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: cobranza.id_menu,
		                                    fn: ['cobranza.buscar']
		                                });*/
		                            },
									click:function(obj){
										geoRef.dowload_all();
									}
								}
							},
							{
								text:'',
								id:geoRef.id+'-upload',
								icon: '/images/icon/upload_icon.png',
								tooltip:'Cargar el archivo de Coordenadas',
								listeners:{
								    beforerender: function(obj, opts){
		                                /*global.permisos({
		                                    id_serv: 108, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: cobranza.id_menu,
		                                    fn: ['cobranza.buscar']
		                                });*/
		                            },
									click:function(obj){
										geoRef.upload();
									}
								}
							},

							
							
					],
					items:[
							{
								xtype:'grid',
								store:store,
								id:geoRef.id+'-grid',
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
												text:'Agencia',
		                        				dataIndex:'prov_nombre',
		                        				width:100
											},
											{
												text:'Shipper',
		                        				dataIndex:'shi_nombre',
		                        				flex:1
											},
											{
												text:'Servicio',
		                        				dataIndex:'con_descri',
		                        				flex:1
											},
											
											{
												text:'Ciclo',
		                        				dataIndex:'ciclo',
		                        				width:100
											},
											{
												text:'T. GE',
		                        				dataIndex:'guias',
		                        				align:'center',
		                        				width:100,
		                        				renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
			                                        metaData.style = "padding: 0px; margin: 0px";
			                                        return global.permisos({
			                                            type: 'link',
			                                            id_menu: geoRef.id_menu,
			                                            extraCss:'x-link',
			                                            icons:[
			                                                {id_serv: 56, qtip: 'Click para Re-Imprimir', js: 'geoRef.show_re_imprimir('+rowIndex+')',value:value},
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
												text:'T. Admitidos',
		                        				dataIndex:'adm',
		                        				align:'center',
		                        				width:100,
		                        				summaryType: 'sum',
												summaryRenderer: function(value, summaryData, dataIndex){
			                                       //return value.toFixed(2);
			                                       return '<div style="font-weight: bold;">'+value+'</div>';
			                                    }
											},
											{
												text:'T. Sin Admitir',
		                        				dataIndex:'pend',
		                        				align:'center',
		                        				width:100,
		                        				summaryType: 'sum',
												summaryRenderer: function(value, summaryData, dataIndex){
			                                       //return value.toFixed(2);
			                                       return '<div style="font-weight: bold;">'+value+'</div>';
			                                    }
											},
											{
												text:'Menu',
												align:'center',
		                        				dataIndex:'',
		                        				width:100,
		                        				renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
			                                        //console.log(record);
			                                        var prov_codigo = record.get('prov_codigo');
			                                        var ord_numero = record.get('ord_numero');

			                                        metaData.style = "padding: 0px; margin: 0px";
			                                        return global.permisos({
			                                            type: 'link',
			                                            id_menu: geoRef.id_menu,
			                                            extraCss: 'ftp-procesar-link',
			                                            icons:[
			                                                {id_serv: 0, img: 'georeferencia.png', qtip: 'Click para Georeferencias.', js: ''},
			                                                {id_serv: 53, img: 'download_a.png', qtip: 'Click para Descargar Archivo', js: 'geoRef.dowload('+prov_codigo+','+ord_numero+')'},
			                                                
			                                                //scm_dowload
			                                            ]
			                                        });
			                                    }
											},
		                        	]
		                        }

							}
					]
				})

				tab.add({
					id:geoRef.id+'-tab',
					//title:'menu prueba',
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
	                		global.state_item_menu(geoRef.id_menu, true);	
	                    },
	                    afterrender: function(obj, e){
                        	tab.setActiveTab(obj);
                        	global.state_item_menu_config(obj,geoRef.id_menu);	
	                        
	                    },
	                    beforeclose: function(obj, opts){
	                    	global.state_item_menu(geoRef.id_menu, false);	
	                    }
	                }

				}).show();
			},
			buscar:function(){
				var grid = Ext.getCmp(geoRef.id+'-grid')
				var store = grid.getStore();
				var vp_prov_codigo = Ext.getCmp(geoRef.id+'-agencia').getValue();
		        var vp_shi_codigo = Ext.getCmp(geoRef.id+'-shipper').getValue();
		        var vp_pro_id = Ext.getCmp(geoRef.id+'-servicio').getValue();
		        var vp_fini	= Ext.getCmp(geoRef.id+'-fini').getRawValue();
		        var vp_ffin = Ext.getCmp(geoRef.id+'-ffin').getRawValue();
				store.load({
					params:{
						vp_prov_codigo:vp_prov_codigo,
						vp_shi_codigo:vp_shi_codigo,
						vp_pro_id:vp_pro_id,
						vp_fini:vp_fini,
						vp_ffin:vp_ffin
					}
				});

				
			},
			show_re_imprimir:function(rowIndex){
				win.show({vurl: geoRef.url + 'show_re_imprimir/?rowIndex='+rowIndex, id_menu: geoRef.id_menu, class: '' });
			},
			dowload:function(prov_codigo,ord_numero){
				//win.show({vurl: geoRef.url + 'scm_dowload/?vp_prov_codigo='+prov_codigo+'&ord_numero='+ord_numero, id_menu: geoRef.id_menu, class: '' });	
				 
			},
			dowload_all:function(){
				var vp_prov_codigo = Ext.getCmp(geoRef.id+'-agencia').getValue();
		        var vp_shi_codigo = Ext.getCmp(geoRef.id+'-shipper').getValue();
		        var vp_pro_id = Ext.getCmp(geoRef.id+'-servicio').getValue();
		        var vp_fini	= Ext.getCmp(geoRef.id+'-fini').getRawValue();
		        var vp_ffin = Ext.getCmp(geoRef.id+'-ffin').getRawValue();
				window.open(geoRef.url+'scm_dowload_all/?&vp_prov_codigo='+vp_prov_codigo+'&vp_shi_codigo='+vp_shi_codigo+'&vp_pro_id='+vp_pro_id+'&vp_fini='+vp_fini+'&vp_ffin='+vp_ffin);  
			},
			upload:function(){
            	 win.show({vurl: geoRef.url + 'scm_upload/', id_menu: geoRef.id_menu, class: '' });
        	},

		}
		Ext.onReady(geoRef.init,geoRef);
	}else{
		tab.setActiveTab(geoRef.id+'-tab');
	}

</script>