<script type="text/javascript">
	/**
	 * @author  Jim 20150510 09:45 to 20150514 17:00
	 */
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if (!Ext.getCmp('pickup_service-tab')){
		var pickup_service = {
			id:'pickup_service',
			id_menu:'<?php echo $p["id_menu"];?>',
			url:'/gestion/recolecciones/',
			url_nv: '/gestion/novedades/',
			id_nov:0,
            idx:-1,
            type:'',
            view:{},
            record:{},
            id_msn:0,
            mapa:{
            	directionsDisplay: new google.maps.DirectionsRenderer(),
				directionsService : new google.maps.DirectionsService(),
				trafficLayer:new google.maps.TrafficLayer(),
				markers:[],
				markersService:[],
				currentinfowindow:null
            },
            addNew:true,
            filtro:false,
            pickup:{
            	tab:'A',
            	srec_id:0,
            	tipo:0,
            	id_dir_a:0,
            	id_dir_b:0,
            	record:{}
            },
            coordenadas:{
            	origen:{
            		geo_px:0,
            		geo_py:0
            	},
            	destino:{
            		geo_px:0,
            		geo_py:0
            	}
            },
            apiEvent:{
            	o_ciu_id:0,
            	o_prov_codigo:0,
            	d_ciu_id:0,
            	d_prov_codigo:0
            },
            dias:['LU','MA','MI','JU','VI','SA','DO'],
			init:function(){	
				Ext.tip.QuickTipManager.init();
				pickup_service.setCancelFrom({srec_id:0,tipo:'B'});
				
				var imageTplPointer = new Ext.XTemplate(
		            '<tpl for=".">',
		                '<div class="databox_list_pointer" >',
		                    '<div class="{class_line}" >',
		                        '<div class="">{linea}',
		                        '</div>',
		                    '</div>',
		                    '<div class="databox_mensage" >',
		                        '<div class="databox_bar">',
		                            '<div class="databox_title">',
		                                '<span>{titulo}</span>',
		                            '</div>',
		                            '<div class="databox_date"><span class="dbx_user">{tipo_orden}</span></div>',
		                        '</div>',
		                        '<div class="databox_message">{msn}</div>',
		                    '</div>',
		                    '<div class="databox_status_off" style="color:red;text-decoration: underline;"><a onClick="pickup_service.getCancelFromR({srec_id:{id_recojo},tipo:\'A\'});">ANULAR</a>',
                    		'</div>',
		                    '<div class="databox_btools">',
		                        '<hr></hr>',
		                        '<span class=""><p>FECHA:</p>{fecha}</span><span class=""><p>HORARIO:</p>{horario}</span>',
		                    '</div>',
		                    '<div class="databox_btools">',
		                        '<hr></hr>',
		                        '<span><p>PESO(gm):</p>{peso}</span><span><p>PIEZAS:</p>{pieza}</span><span><p>ESTADO:</p>{estado}</span>',
		                    '</div>',
		                    '<div class="databox_btools">',
		                        '<hr></hr>',
		                        '<span><p>ZONA:</p>{zona}</span><span><p>PLACA:</p>{placa}</span>',
		                    '</div>',
		                '</div>',
		            '</tpl>'
		        );
				
				this.store_linea=Ext.create('Ext.data.Store',{
		            fields: [
		                {name: 'id', type: 'int'},
		                {name: 'nombre', type: 'string'}
		            ],
		            autoLoad:true,
		            proxy:{
		                type: 'ajax',

		                url: pickup_service.url+'get_scm_linea/',
		                reader:{
		                    type: 'json',
		                    rootProperty: 'data'
		                }
		            },
		            listeners:{
		                load: function(obj, records, successful, opts){
		                    try{
		                    	if(obj.getCount()==1){
			                    	var rec = obj.getAt(0);
			                    	Ext.getCmp(pickup_service.id+'-_linea_').setValue(rec.get('id'));
			                    	Ext.getCmp(pickup_service.id+'-_linea_filtro').setValue(rec.get('id'));
			                    	pickup_service.getReloadShipper('A');
			                    	pickup_service.getReloadShipper('B');
			                    }
	                        }catch(err){
	                    		console.log(err);
	                    	}
		                }
		            }
		        });
				this.store_shipper=Ext.create('Ext.data.Store',{
					autoLoad:false,
					fields:[
							{name: 'shi_codigo', type: 'int'},
                            {name: 'shi_nombre', type: 'string'},
                            {name: 'shi_id', type: 'string'}
					],
					proxy:{
						type:'ajax',
						url:pickup_service.url+'get_usr_sis_shipper/',
						reader:{
							type:'json',
							rootProperty:'data'
						},
						extraParams:{
		                	vp_linea:0
		                }
					},
					listeners:{
						load:function(obj, records, successful,opts){
							//change if require linea
							//Ext.getCmp(pickup_service.id+'-shipper-filtro').setValue(parseInt('<?php echo SHI_CODIGO; ?>'));
							//Ext.getCmp(pickup_service.id+'-shipper').setValue(parseInt('<?php echo SHI_CODIGO; ?>'));
						}
					}
				});
				this.store_shipper_filtro=Ext.create('Ext.data.Store',{
					autoLoad:false,
					fields:[
							{name: 'shi_codigo', type: 'int'},
                            {name: 'shi_nombre', type: 'string'},
                            {name: 'shi_id', type: 'string'}
					],
					proxy:{
						type:'ajax',
						url:pickup_service.url+'get_usr_sis_shipper_filtro/',
						reader:{
							type:'json',
							rootProperty:'data'
						},
						extraParams:{
		                	vp_linea:0
		                }
					},
					listeners:{
						load:function(obj, records, successful,opts){
							//change if require linea
							//Ext.getCmp(pickup_service.id+'-shipper-filtro').setValue(parseInt('<?php echo SHI_CODIGO; ?>'));
							//Ext.getCmp(pickup_service.id+'-shipper').setValue(parseInt('<?php echo SHI_CODIGO; ?>'));
						}
					}
				});

				this.store_provincia=Ext.create('Ext.data.Store',{
					autoLoad:false,
					fields:[
							{name: 'prov_codigo', type: 'int'},
                            {name: 'prov_nombre', type: 'string'},
                            {name: 'prov_sigla', type: 'string'},
                            {name: 'dir_id', type: 'int'},
                            {name: 'prov_main', type: 'int'},
                            {name: 'dir_px', type: 'string'},
                            {name: 'dir_py', type: 'string'}
					],
					proxy:{
						type:'ajax',
						url:pickup_service.url+'get_scm_provincias/',
						reader:{
							type:'json',
							rootProperty:'data'
						}
					},
					listeners:{
						load:function(obj, records, successful,opts){

						}
					}
				});

				this.store_area = Ext.create('Ext.data.Store',{
					autoLoad:true,
					fields:[
							{name:'id_area',type:'int'},
							{name:'area_nombre',type:'string'},
							{name:'area_sigla',type:'string'}
					],
					proxy:{
						type:'ajax',
						url:pickup_service.url+'get_scm_area/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});
				this.store_agencia_shipper = Ext.create('Ext.data.Store',{
					autoLoad:false,
					fields:[
							{name:'agencia',type:'string'},
							{name:'id_agencia',type:'int'},
							{name:'dir_id',type:'int'},
							{name:'dir_px',type:'float'},
							{name:'dir_py',type:'float'},
							{name:'prov_codigo',type:'float'}
					     
					],
					proxy:{
						type:'ajax',
						url:pickup_service.url+'get_scm_agencia_shipper/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});

				this.store_contacto = Ext.create('Ext.data.Store',{
					autoLoad:false,
					fields:[
							{name:'contacto',type:'string'},
							{name:'id_contacto',type:'int'}
					     
					],
					proxy:{
						type:'ajax',
						url:pickup_service.url+'get_scm_contactos/',
						reader:{
							type:'json',
							root:'data'
						}
					}
				});

				this.store = Ext.create('Ext.data.Store',{
		            fields: [
		                {name: 'line', type: 'string'},
		                {name: 'class_line', type: 'string'},
		                {name: 'linea', string: 'int'},
		                {name: 'shipper', string: 'string'},
		                {name: 'servicio', string: 'string'},
		                {name: 'fecha', string: 'string'},
		                {name: 'horario', string: 'string'},
		                {name: 'estado', string: 'string'},
		                {name: 'zona', string: 'string'},
		                {name: 'placa', string: 'string'},
		                {name: 'px', string: 'float'},
		                {name: 'py', string: 'float'},
		                {name: 'id_recojo', string: 'int'},
		                {name: 'chk', string: 'string'},
		                {name: 'tipo', string: 'string'}
		            ],
		            autoLoad:true,
		            proxy:{
		                type: 'ajax',
		                url: pickup_service.url+'get_scm_lista_requerimientos/',
		                reader:{
		                    type: 'json',
		                    rootProperty: 'data'
		                },
		                extraParams:{
		                	vp_shi_codigo:0,//parseInt('<?php echo SHI_CODIGO; ?>'),
		                    vp_fecha:'<?php echo date("dmY");?>',
		                    vp_estado:'%%'
		                }
		            },
		            listeners:{
		                load: function(obj, records, successful, opts){
		                	pickup_service.pickup.record=records;
		                	pickup_service.setCoordMarckerService();
		                }
		            }
		        });
				
				tab.add({
					id:pickup_service.id+'-tab',
					border:false,
					autoScroll:true,
					closable:true,
					layout:'border',
					items:[
						{
							region:'west',
							id:pickup_service.id+'-region-west',
							width:400,
							layout:'border',
							header:false,
							split: true,
							collapsible: true,
							hideCollapseTool:true,
							titleCollapse:false,
							floatable: false,
							collapseMode : 'mini',
							animCollapse : true,
							border:false,
							items:[
								{
									region:'north',
									id:pickup_service.id+'content-north',
									border:false,
									layout:'fit',
									autoScroll:true,
									height:400,
									listeners:{
										afterrender:function(){
											pickup_service.getAddRequest();
										}
									},
									items:[
										{
											xtype: 'tabpanel',
										    //tabPosition: 'left',
										    //tabRotation: 0,
										    id:pickup_service.id+'tab-content',
										    layout:'fit',
										    tabBar: {
										        border: false
										    },
										    listeners: {
								                'tabchange': function(tabPanel, tab){
								                    tabPanel.doLayout();
								                }
								            },
								            bbar:[
								            	{
			                                        xtype:'button',
			                                        id: pickup_service.id+'-grabar-',
			                                        margin:'10, 0 0 0',
			                                        text:'Grabar',
			                                        icon:'/images/icon/save.png',
			                                        listeners:{
			                                            click:function(obj,e){
			                                            	pickup_service.setSaveRequest();
			                                            }
			                                        }
			                                    },
			                                    {
			                                        xtype:'button',
			                                        id: pickup_service.id+'-clear-',
			                                        margin:'10, 0 0 0',
			                                        text:'Nuevo',
			                                        icon:'/images/icon/new_file.ico',
			                                        listeners:{
			                                            click:function(obj,e){
			                                            	pickup_service.setClear();
			                                            }
			                                        }
			                                    },
									        	{
			                                        xtype:'button',
			                                        hidden:true,
			                                        id: pickup_service.id+'-conf-direccion',
			                                        margin:'10, 0 0 0',
			                                        text:'Confirmar Dirección',
			                                        icon:'/images/icon/close_nov.ico',
			                                        listeners:{
			                                            click:function(obj,e){
			                                            	
			                                            }
			                                        }
			                                    },
			                                    {
			                                        xtype:'button',
			                                        id:pickup_service.id+'-pickup-cancel',
			                                        margin:'10, 0 0 0',
			                                        text:'Cancelar',
			                                        icon:'/images/icon/cancel.png',
			                                        listeners:{
			                                            click:function(obj,e){
			                                            	pickup_service.getCancelFrom();
			                                            }
			                                        }
			                                    },
			                                    {
			                                        xtype:'button',
			                                        id:pickup_service.id+'-pickup-close',
			                                        hidden:true,
			                                        margin:'10, 0 0 0',
			                                        text:'Salir',
			                                        icon:'/images/icon/get_back.png',
			                                        listeners:{
			                                            click:function(obj,e){
			                                            	//add clear filter
			                                            	pickup_service.getCloseFrom();
			                                            }
			                                        }
			                                    },
			                                    '->',
			                                    {
													xtype:'checkbox',
													id:pickup_service.id+'-pickup-frecuente',
													fieldLabel:'Recojo Frecuente',
													labelWidth:100,
													listeners:{
														change:function( obj, newValue, oldValue, eOpts ){
															
														}
													}
												},
												'-'
									        ],
										    items: [
											    {
											        title: 'Datos Recojo/Entrega',
											        id: pickup_service.id+'-tab-datos',
											        border:false,
			                                        xtype: 'uePanelS',
			                                        logo: 'DT',
			                                        legend: 'Formulario de Registro',
											        layout:'fit',
											        listeners:{
											        	activate: function(tab, eOpts) {
											        		pickup_service.setTypeTab('A');
											                Ext.getCmp(pickup_service.id+'content-north').setHeight(400);
                											Ext.getCmp(pickup_service.id+'-content-region-center').show();
											            }
											        },
											        items:[
											        	{
			                                                xtype:'panel',
			                                                border:false,
			                                                bodyStyle: 'background: transparent',
			                                                padding:'3px 5px 3px 5px',
			                                                layout:'column',
			                                                items: [
			                                                	{
			                                                        columnWidth: 0.5,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
			                                                                xtype:'combo',
			                                                                fieldLabel: 'Linea',
			                                                                id:pickup_service.id+'-_linea_',
			                                                                store: pickup_service.store_linea,
			                                                                queryMode: 'local',
			                                                                triggerAction: 'all',
			                                                                valueField: 'id',
			                                                                displayField: 'nombre',
			                                                                emptyText: '[Seleccione]',
			                                                                //allowBlank: false,
			                                                                labelWidth: 43,
			                                                                width:'100%',
			                                                                anchor:'100%',
			                                                                //readOnly: true,
			                                                                listConfig:{
																				minWidth:180
																			},
			                                                                listeners:{
			                                                                    afterrender:function(obj, e){
			                                                                    },
			                                                                    select:function(obj, records, eOpts){
			                                                                        pickup_service.getReloadShipper('A');
			                                                                        Ext.getCmp(pickup_service.id+'-shipper').setValue(null);
			                                                                        Ext.getCmp(pickup_service.id+'-shipper').focus();
			                                                                    }
			                                                                }
			                                                            }
			                                                        ]
			                                                    },
			                                                    {
			                                                        columnWidth: 0.5,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
																			xtype:'radiogroup',
																			allowBlank:false,
																			//columnWidth:0.4,
																			id:pickup_service.id+'-rbtn-group-ejecutar',
																			fieldLabel:'Tipo',
																			columns:2,
																			vertical:false,
																			labelWidth:30,
																			items:[
																					{boxLabel:'Recojo',id:pickup_service.id+'_rbtn_ejecutar_a',name:'_rbtn_ejecutar',inputValue:1, width:60,checked:true},
																					{boxLabel:'Entrega',id:pickup_service.id+'_rbtn_ejecutar_b',name:'_rbtn_ejecutar',inputValue:2, width:70}
																			],
																			listeners:{

																			}
																		}
			                                                        ]
			                                                    }
			                                                ]
			                                            },
			                                            {
			                                                xtype:'panel',
			                                                border:false,
			                                                bodyStyle: 'background: transparent',
			                                                padding:'3px 5px 3px 5px',
			                                                layout:'column',
			                                                items: [
			                                                	{
			                                                        columnWidth: 1,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
			                                                                xtype:'combo',
			                                                                fieldLabel: 'Shipper',
			                                                                id:pickup_service.id+'-shipper',
			                                                                store: pickup_service.store_shipper,
			                                                                queryMode: 'local',
			                                                                triggerAction: 'all',
			                                                                valueField: 'shi_codigo',
			                                                                displayField: 'shi_nombre',
			                                                                emptyText: '[Seleccione]',
			                                                                //allowBlank: false,
			                                                                labelWidth: 42,
			                                                                width:'100%',
			                                                                anchor:'100%',
			                                                                //readOnly: true,
			                                                                listeners:{
			                                                                    afterrender:function(obj, e){
			                                                                        /*obj.getStore().load({
			                                                                            params: {
			                                                                                
			                                                                            },
			                                                                            callback: function(records, operation, success) {
			                                                                                var shi_codigo = parseInt('<?php echo SHI_CODIGO; ?>');
			                                                                                if(shi_codigo!=0 || shi_codigo!='' || shi_codigo!=null){
			                                                                                    obj.setValue(shi_codigo);
			                                                                                    solicitar_envios.selected_shi(obj);
			                                                                                }
			                                                                            },
			                                                                            scope: this
			                                                                        });*/
			                                                                    },
			                                                                    select:function(obj, records, eOpts){
			                                                                        pickup_service.getReloadAgenciaShipper();
			                                                                        Ext.getCmp(pickup_service.id+'-tipo_paquete').focus();
			                                                                    }
			                                                                }
			                                                            }
			                                                        ]
			                                                    }
			                                                ]
			                                            },
			                                            {
			                                                xtype:'panel',
			                                                border:false,
			                                                bodyStyle: 'background: transparent',
			                                                padding:'3px 5px 3px 5px',
			                                                layout:'column',
			                                                items: [
			                                                	{
			                                                        columnWidth: 1,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
			                                                                xtype:'combo',
			                                                                fieldLabel: 'Tipo Paquete',
			                                                                id:pickup_service.id+'-tipo_paquete',
			                                                                store:Ext.create('Ext.data.Store',{			                                                                	
																				fields:[
																						{name:'descripcion',type:'string'},
																						{name:'id_elemento',type:'int'}
																				     
																				],
																				proxy:{
																					type:'ajax',
																					url:pickup_service.url+'get_scm_tabla_detalle/',
																					reader:{
																						type:'json',
																						root:'data'
																					}
																				}
																			}),
			                                                                queryMode: 'local',
			                                                                triggerAction: 'all',
			                                                                displayField:'descripcion',
																			valueField:'id_elemento',
			                                                                emptyText: '[Seleccione]',
			                                                                //allowBlank: false,
			                                                                labelWidth: 75,
			                                                                width:'100%',
			                                                                anchor:'100%',
			                                                                //readOnly: true,
			                                                                listeners:{
			                                                                    afterrender:function(obj, e){
			                                                                    	obj.getStore().load({
																						params:{tab_id:'TDP'}
																					});
			                                                                    },
			                                                                    select:function(obj, records, eOpts){
			                                                                        //solicitar_envios.selected_shi(obj);
			                                                                        Ext.getCmp(pickup_service.id+'-cantidad').focus();
			                                                                    }
			                                                                }
			                                                            }
			                                                        ]
			                                                    }
			                                                ]
			                                            },
			                                            {
			                                                xtype:'panel',
			                                                border:false,
			                                                bodyStyle: 'background: transparent',
			                                                padding:'3px 5px 3px 5px',
			                                                layout:'column',
			                                                items: [
			                                                	{
			                                                        columnWidth: 0.5,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
																			xtype:'numberfield',
																			//allowBlank:false,
																			id:pickup_service.id+'-cantidad',
																			fieldLabel:'Total Piezas',
																			minValue: 1,
																			labelWidth:75
																		}
			                                                        ]
			                                                    },
			                                                    {
			                                                        columnWidth: 0.5,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
																			xtype:'numberfield',
																			//allowBlank:false,
																			id:pickup_service.id+'-peso',
																			fieldLabel:'Peso (gm)',
																			minValue: 1,
																			decimalSeparator:'.',
																			labelWidth:65
																		}
			                                                        ]
			                                                    }
			                                                ]
			                                            },
			                                            {
			                                                xtype:'panel',
			                                                border:false,
			                                                bodyStyle: 'background: transparent',
			                                                padding:'3px 5px 3px 5px',
			                                                layout:'column',
			                                                items: [
			                                                	{
			                                                        columnWidth: 0.35,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
																			xtype:'numberfield',
																			//allowBlank:false,
																			id:pickup_service.id+'-alto',
																			fieldLabel:'Alto',
																			emptyText: '(cm)',
																			minValue: 0,
																			allowDecimals: true,
																			decimalSeparator:'.',
																			labelWidth:43
																		}
			                                                        ]
			                                                    },
			                                                    {
			                                                        columnWidth: 0.32,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
																			xtype:'numberfield',
																			//allowBlank:false,
																			id:pickup_service.id+'-ancho',
																			fieldLabel:'Ancho',
																			emptyText: '(cm)',
																			minValue: 0,
																			allowDecimals: true,
																			decimalSeparator:'.',
																			labelWidth:40
																		}
			                                                        ]
			                                                    },
			                                                    {
			                                                        columnWidth: 0.33,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
																			xtype:'numberfield',
																			//allowBlank:false,
																			id:pickup_service.id+'-largo',
																			fieldLabel:'Largo',
																			emptyText: '(cm)',
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
			                                                xtype:'panel',
			                                                border:false,
			                                                bodyStyle: 'background: transparent',
			                                                padding:'3px 5px 3px 5px',
			                                                layout:'column',
			                                                items: [
			                                                	{
			                                                        columnWidth: 1,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
																			xtype:'textareafield',
																			//allowBlank:false,
																			id:pickup_service.id+'-observacion-',
																			fieldLabel:'Detalle',
																			//height:40,
																			maskRe : /[a-z ñÑA-Z0-9]$/,
																			enforceMaxLength:true,
																			maxLength:80,
																			maxLengthText:'El Maximo de caracteres es {0}',
																			labelWidth:43,
																			
																		}
			                                                        ]
			                                                    }
			                                                ]
			                                            },
			                                            {
			                                                xtype:'panel',
			                                                border:false,
			                                                bodyStyle: 'background: transparent',
			                                                padding:'3px 5px 3px 5px',
			                                                layout:'column',
			                                                items: [
			                                                	{
			                                                        columnWidth: 1,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
																			xtype:'textareafield',
																			//allowBlank:false,
																			id:pickup_service.id+'-descripcion-',
																			fieldLabel:'Observ.',
																			//height:6,
																			maskRe : /[a-z ñÑA-Z0-9]$/,
																			enforceMaxLength:true,
																			maxLength:160,
																			maxLengthText:'El Maximo de caracteres es {0}',
																			labelWidth:43
																			
																		}
			                                                        ]
			                                                    }
			                                                ]
			                                            }
											        ]
											    },
											    {
											        title: 'Confirmar Dirección',
											        id: pickup_service.id+'-tab-confirmar',
											        disabled:true,
											        layout:'border',
											        listeners:{
											        	activate: function(tab, eOpts) {
											        		pickup_service.setTypeTab('B');
											                pickup_service.setChangeHeightContent();
											            }
											        },
											        items:[
											        	{
											        		region:'center',
											        		bodyStyle: 'background: #fff',
											        		id:pickup_service.id+'-inter-cont-center',
											        		//autoScroll:true,
											        		layout:'border',
											        		tbar:[
											        			'-',
											        			'Destino',
											        			'-',
											        			'->',
											        			{
																	xtype:'radiogroup',
																	allowBlank:false,
																	//columnWidth:0.4,
																	id:pickup_service.id+'-rbtn-group-destino',
																	//fieldLabel:'Tipo',
																	columns:3,
																	vertical:false,
																	labelWidth:30,
																	items:[
																			{boxLabel:'Agencia',id:pickup_service.id+'_rbtn_b_a',name:'_rbtn_b',inputValue:1, width:70,checked:true},
																			{boxLabel:'Centro de Actividad',id:pickup_service.id+'_rbtn_b_b',name:'_rbtn_b',inputValue:2, width:130},
																			{boxLabel:'Otro',id:pickup_service.id+'_rbtn_b_c',name:'_rbtn_b',inputValue:3, width:70}
																	],
																	listeners:{
																		change: function (field, newValue, oldValue) {
																			pickup_service.setChangeHeightContent();
																			pickup_service.getChangeCmpItems();
														                }
																	}
																}
											        		],
											        		border:false,
											        		items:[
											        			{
											        				region:'north',
											        				id:pickup_service.id+'-panel-agencia-b',
											        				bodyStyle: 'background: #fff',
											        				border:false,
											        				height:100,
											        				//layout:'fit',
											        				margin:15,
											        				items:[
											        					{
			                                                                xtype:'combo',
			                                                                fieldLabel: 'Agencia',
			                                                                id:pickup_service.id+'-agencia-b',
			                                                                store:Ext.create('Ext.data.Store',{
																				autoLoad:false,
																				fields:[
																						{name: 'prov_codigo', type: 'int'},
															                            {name: 'prov_nombre', type: 'string'},
															                            {name: 'prov_sigla', type: 'string'},
															                            {name: 'dir_id', type: 'int'},
															                            {name: 'dir_px', type: 'string'},
															                            {name: 'dir_py', type: 'string'}
																				],
																				proxy:{
																					type:'ajax',
																					url:pickup_service.url+'get_scm_provincias/',
																					reader:{
																						type:'json',
																						rootProperty:'data'
																					}
																				},
																				listeners:{
																					load:function(obj, records, successful,opts){

																					}
																				}
																			}),
			                                                                queryMode: 'local',
			                                                                triggerAction: 'all',
			                                                                valueField: 'prov_codigo',
			                                                                displayField: 'prov_nombre',
			                                                                emptyText: '[Seleccione]',
			                                                                //allowBlank: false,
			                                                                //disabled:true,
			                                                                labelWidth: 60,
			                                                                width:'100%',
			                                                                anchor:'100%',
			                                                                //readOnly: true,
			                                                                listeners:{
			                                                                    afterrender:function(obj, e){
			                                                                    },
			                                                                    select:function(obj, records, eOpts){
			                                                                    	Ext.getCmp(pickup_service.id+'-_area_b').setValue(null);
			                                                                        pickup_service.getReloadPersonal('B');
			                                                                        pickup_service.coordenadas.destino.geo_px=records.get('dir_px');
			                                                                        pickup_service.coordenadas.destino.geo_py=records.get('dir_py');
			                                                                        pickup_service.setMarkerRequest();
			                                                                    }
			                                                                }
			                                                            },
			                                                            {
						                                                    xtype:'combo',
						                                                    fieldLabel: 'Área',
						                                                    id:pickup_service.id+'-_area_b',
						                                                    store:pickup_service.store_area,
						                                                    queryMode: 'local',
						                                                    triggerAction: 'all',
						                                                    valueField: 'id_area',
						                                                    displayField: 'area_nombre',
						                                                    emptyText: '[Seleccione]',
						                                                    //allowBlank: false,
						                                                    labelWidth: 60,
						                                                    width:'100%',
						                                                    anchor:'100%',
						                                                    //readOnly: true,
						                                                    listConfig:{
																				minWidth:200
																			},
						                                                    listeners:{
						                                                        afterrender:function(obj, e){
						                                                        },
						                                                        select:function(obj, records, eOpts){
						                                                        	Ext.getCmp(pickup_service.id+'-_personal_-b').setValue(null);
						                                                            pickup_service.getReloadPersonal('B')
						                                                        }
						                                                    }
						                                                },
			                                                            {
						                                                    xtype:'combo',
						                                                    fieldLabel: 'Personal',
						                                                    id:pickup_service.id+'-_personal_-b',
						                                                    store: Ext.create('Ext.data.Store',{
																				fields:[
																						{name:'nombre',type:'string'},
																						{name:'per_id',type:'int'}
																				     
																				],
																				proxy:{
																					type:'ajax',
																					url:pickup_service.url+'get_scm_personal/',
																					reader:{
																						type:'json',
																						root:'data'
																					}
																				}
																			}),
						                                                    queryMode: 'local',
						                                                    triggerAction: 'all',
						                                                    valueField: 'per_id',
						                                                    displayField: 'nombre',
						                                                    emptyText: '[Seleccione]',
						                                                    //allowBlank: false,
						                                                    labelWidth: 60,
						                                                    width:'100%',
						                                                    anchor:'100%',
						                                                    //readOnly: true,
						                                                    listConfig:{
																				minWidth:200
																			},
						                                                    listeners:{
						                                                        afterrender:function(obj, e){
						                                                        },
						                                                        select:function(obj, records, eOpts){
						                                                            
						                                                        }
						                                                    }
						                                                }
											        				]
											        			},
	                                                            {
	                                                            	region:'center',
	                                                            	id:pickup_service.id+'-panel-centro-costo-b',
	                                                            	bodyStyle: 'background: #fff',
	                                                            	border:false,
											        				//layout:'fit',
											        				margin:15,
	                                                            	hidden:true,
	                                                            	items:[
	                                                            		{
			                                                                xtype:'combo',
			                                                                fieldLabel: 'Centro de Actividad',
			                                                                id:pickup_service.id+'-cc-b',
			                                                                store:Ext.create('Ext.data.Store',{
																				autoLoad:false,
																				fields:[
																					{name:'agencia',type:'string'},
																					{name:'id_agencia',type:'int'},
																					{name:'dir_id',type:'int'},
																					{name:'dir_px',type:'float'},
																					{name:'dir_py',type:'float'},
																					{name:'prov_codigo',type:'float'}
																				],
																				proxy:{
																					type:'ajax',
																					url:pickup_service.url+'get_scm_agencia_shipper/',
																					reader:{
																						type:'json',
																						root:'data'
																					}
																				}
																			}),
			                                                                queryMode: 'local',
			                                                                triggerAction: 'all',
			                                                                valueField: 'id_agencia',
			                                                                displayField: 'agencia',
			                                                                emptyText: '[Seleccione]',
			                                                                //allowBlank: false,
			                                                                labelWidth: 115,
			                                                                width:'100%',
			                                                                anchor:'100%',
			                                                                //readOnly: true,
			                                                                listeners:{
			                                                                    afterrender:function(obj, e){
			                                                                    },
			                                                                    select:function(obj, records, eOpts){
			                                                                    	Ext.getCmp(pickup_service.id+'-contacto_-b').setValue(null);
			                                                                        pickup_service.getReloadContactos('B');
			                                                                        pickup_service.coordenadas.destino.geo_px=records.get('dir_px');
			                                                                        pickup_service.coordenadas.destino.geo_py=records.get('dir_py');
			                                                                        pickup_service.setMarkerRequest();
			                                                                    }
			                                                                }
			                                                            },
			                                                            {
						                                                    xtype:'combo',
						                                                    fieldLabel: 'Contacto',
						                                                    id:pickup_service.id+'-contacto_-b',
						                                                    store: Ext.create('Ext.data.Store',{
																				autoLoad:false,
																				fields:[
																						{name:'contacto',type:'string'},
																						{name:'id_contacto',type:'int'}
																				     
																				],
																				proxy:{
																					type:'ajax',
																					url:pickup_service.url+'get_scm_contactos/',
																					reader:{
																						type:'json',
																						root:'data'
																					}
																				}
																			}),
						                                                    queryMode: 'local',
						                                                    triggerAction: 'all',
						                                                    valueField: 'id_contacto',
						                                                    displayField: 'contacto',
						                                                    emptyText: '[Seleccione]',
						                                                    //allowBlank: false,
						                                                    labelWidth: 115,
						                                                    width:'100%',
						                                                    anchor:'100%',
						                                                    //readOnly: true,
						                                                    listConfig:{
																				minWidth:200
																			},
						                                                    listeners:{
						                                                        afterrender:function(obj, e){
						                                                        },
						                                                        select:function(obj, records, eOpts){
						                                                            
						                                                        }
						                                                    }
						                                                }
	                                                            	]
	                                                            },
	                                                            {
	                                                            	region:'south',
	                                                            	id:pickup_service.id+'-panel-destino-b',
	                                                            	bodyStyle: 'background: #fff',
	                                                            	border:false,
	                                                            	autoScroll:true,
	                                                            	//layout:'fit',
									                                height:'100%',
	                                                            	hidden:true,
	                                                            	items:[
													        			{
									                                       xtype: 'findlocation',
									                                       id: pickup_service.id+'-destino',
									                                       height:255,
									                                       border:false,
									                                       mapping: false,
									                                       clearReferent:false,
									                                       getMapping:false,
									                                       setMapping:pickup_service.id+'-map',
									                                       changeEvent:pickup_service.getChangeEventDestination,
									                                       trust:true,
									                                       listeners:{
									                                            afterrender: function(obj){
									                                            	Ext.getCmp(pickup_service.id+'-destino-finder-address').getHeader().hide();
									                                            }
									                                       }
									                                    },
									                                    {
							                                                xtype:'panel',
							                                                border:false,
							                                                bodyStyle: 'background: transparent',
							                                                padding:'0px 10px 0px 10px',
							                                                layout:'column',
							                                                items: [
							                                                	{
							                                                        columnWidth: 1,border:false,
							                                                        layout:'fit',
							                                                        padding:'0px 0px 0px 0px',  bodyStyle: 'background: transparent',
							                                                        items:[
							                                                            {
										                                                    xtype:'combo',
										                                                    fieldLabel: 'Contacto',
										                                                    id:pickup_service.id+'-contacto_-d-a',
										                                                    store: pickup_service.store_contacto,
										                                                    queryMode: 'local',
										                                                    triggerAction: 'all',
										                                                    valueField: 'id_contacto',
						                                                    				displayField: 'contacto',
										                                                    emptyText: '[Seleccione]',
										                                                    //allowBlank: false,
										                                                    labelWidth: 60,
										                                                    width:'100%',
										                                                    anchor:'100%',
										                                                    //readOnly: true,
										                                                    listConfig:{
																								minWidth:200
																							},
										                                                    listeners:{
										                                                        afterrender:function(obj, e){
										                                                        },
										                                                        select:function(obj, records, eOpts){
										                                                            
										                                                        }
										                                                    }
										                                                }
							                                                        ]
							                                                    }
							                                                ]
							                                            }
									                                ]
									                            }
											        		]
											        	},
											        	{
											        		region:'north',
											        		id:pickup_service.id+'-inter-cont-north',
											        		height:'50%',
											        		layout:'border',
											        		bodyStyle: 'background: #fff',
											        		tbar:[
											        			'-',
											        			'Origen',
											        			'-',
											        			'->',
											        			{
																	xtype:'radiogroup',
																	allowBlank:false,
																	//columnWidth:0.4,
																	id:pickup_service.id+'-rbtn-group-origen',
																	//fieldLabel:'Tipo',
																	columns:3,
																	vertical:false,
																	labelWidth:30,
																	items:[
																			{boxLabel:'Agencia',id:pickup_service.id+'_rbtn_a_a',name:'_rbtn_a',inputValue:1, width:70,checked:true},
																			{boxLabel:'Centro de Actividad',id:pickup_service.id+'_rbtn_a_b',name:'_rbtn_a',inputValue:2, width:130},
																			{boxLabel:'Otro',id:pickup_service.id+'_rbtn_a_c',name:'_rbtn_a',inputValue:3, width:70}
																	],
																	listeners:{
																		change: function (field, newValue, oldValue) {
																			var shi_codigo = Ext.getCmp(pickup_service.id+'-shipper').getValue();
																			pickup_service.setChangeHeightContent();
																			pickup_service.getChangeCmpItems();
														                }
																	}
																}
											        		],
											        		border:false,
											        		items:[
											        			{
											        				region:'north',
											        				id:pickup_service.id+'-panel-agencia-a',
											        				border:false,
											        				height:100,
											        				//layout:'fit',
											        				bodyStyle: 'background: #fff',
											        				margin:10,
											        				items:[
											        					{
			                                                                xtype:'combo',
			                                                                fieldLabel: 'Agencia',
			                                                                id:pickup_service.id+'-agencia-a',
			                                                                store:pickup_service.store_provincia,
			                                                                queryMode: 'local',
			                                                                triggerAction: 'all',
			                                                                valueField: 'prov_codigo',
			                                                                displayField: 'prov_nombre',
			                                                                emptyText: '[Seleccione]',
			                                                                //allowBlank: false,
			                                                                labelWidth: 60,
			                                                                width:'100%',
			                                                                anchor:'100%',
			                                                                //readOnly: true,
			                                                                listeners:{
			                                                                    afterrender:function(obj, e){
			                                                                    },
			                                                                    select:function(obj, records, eOpts){
			                                                                    	Ext.getCmp(pickup_service.id+'-_area_a').setValue(null);
			                                                                        pickup_service.getReloadPersonal('A');
			                                                                        pickup_service.coordenadas.origen.geo_px=records.get('dir_px');
			                                                                        pickup_service.coordenadas.origen.geo_py=records.get('dir_py');
			                                                                        pickup_service.setMarkerRequest();
			                                                                        pickup_service.getChangeCmpItems();
			                                                                    }
			                                                                }
			                                                            },
			                                                            {
						                                                    xtype:'combo',
						                                                    fieldLabel: 'Area',
						                                                    id:pickup_service.id+'-_area_a',
						                                                    store: pickup_service.store_area,
						                                                    queryMode: 'local',
						                                                    triggerAction: 'all',
						                                                    valueField: 'id_area',
						                                                    displayField: 'area_nombre',
						                                                    emptyText: '[Seleccione]',
						                                                    //allowBlank: false,
						                                                    labelWidth: 60,
						                                                    width:'100%',
						                                                    anchor:'100%',
						                                                    //readOnly: true,
						                                                    listConfig:{
																				minWidth:200
																			},
						                                                    listeners:{
						                                                        afterrender:function(obj, e){
						                                                        },
						                                                        select:function(obj, records, eOpts){
						                                                        	Ext.getCmp(pickup_service.id+'-_personal_-a').setValue(null);
						                                                            pickup_service.getReloadPersonal('A');
						                                                        }
						                                                    }
						                                                },
			                                                            {
						                                                    xtype:'combo',
						                                                    fieldLabel: 'Personal',
						                                                    id:pickup_service.id+'-_personal_-a',
						                                                    store: Ext.create('Ext.data.Store',{
																				fields:[
																						{name:'nombre',type:'string'},
																						{name:'per_id',type:'int'}
																				     
																				],
																				proxy:{
																					type:'ajax',
																					url:pickup_service.url+'get_scm_personal/',
																					reader:{
																						type:'json',
																						root:'data'
																					}
																				}
																			}),
						                                                    queryMode: 'local',
						                                                    triggerAction: 'all',
						                                                    valueField: 'per_id',
						                                                    displayField: 'nombre',
						                                                    emptyText: '[Seleccione]',
						                                                    //allowBlank: false,
						                                                    labelWidth: 60,
						                                                    width:'100%',
						                                                    anchor:'100%',
						                                                    //readOnly: true,
						                                                    listConfig:{
																				minWidth:200
																			},
						                                                    listeners:{
						                                                        afterrender:function(obj, e){
						                                                        },
						                                                        select:function(obj, records, eOpts){
						                                                            
						                                                        }
						                                                    }
						                                                }
											        				]
											        			},
	                                                            {
	                                                            	region:'center',
	                                                            	border:false,
	                                                            	id:pickup_service.id+'-panel-centro-costo-a',
	                                                            	//layout:'fit',
	                                                            	bodyStyle: 'background: #fff',
	                                                            	hidden:true,
	                                                            	margin:10,
	                                                            	items:[
	                                                            		{
			                                                                xtype:'combo',
			                                                                fieldLabel: 'Centro de Actividad',
			                                                                id:pickup_service.id+'-cc-a',
			                                                                store:pickup_service.store_agencia_shipper,
			                                                                queryMode: 'local',
			                                                                triggerAction: 'all',
			                                                                valueField: 'id_agencia',
			                                                                displayField: 'agencia',
			                                                                emptyText: '[Seleccione]',
			                                                                //allowBlank: false,
			                                                                labelWidth: 115,
			                                                                width:'100%',
			                                                                anchor:'100%',
			                                                                //readOnly: true,
			                                                                listeners:{
			                                                                    afterrender:function(obj, e){
			                                                                    },
			                                                                    select:function(obj, records, eOpts){
			                                                                    	var shi_codigo = Ext.getCmp(pickup_service.id+'-shipper').getValue();
			                                                                    	Ext.getCmp(pickup_service.id+'-contacto_-a').setValue(null);
			                                                                        pickup_service.getReloadContactos('A');
			                                                                        pickup_service.coordenadas.origen.geo_px=records.get('dir_px');
			                                                                        pickup_service.coordenadas.origen.geo_py=records.get('dir_py');
			                                                                        pickup_service.setMarkerRequest();
			                                                                        pickup_service.getChangeCmpItems();
			                                                                    }
			                                                                }
			                                                            },
			                                                            {
						                                                    xtype:'combo',
						                                                    fieldLabel: 'Contacto',
						                                                    id:pickup_service.id+'-contacto_-a',
						                                                    store: Ext.create('Ext.data.Store',{
																				autoLoad:false,
																				fields:[
																						{name:'contacto',type:'string'},
																						{name:'id_contacto',type:'int'}
																				     
																				],
																				proxy:{
																					type:'ajax',
																					url:pickup_service.url+'get_scm_contactos/',
																					reader:{
																						type:'json',
																						root:'data'
																					}
																				}
																			}),
						                                                    queryMode: 'local',
						                                                    triggerAction: 'all',
						                                                    valueField: 'id_contacto',
						                                                    displayField: 'contacto',
						                                                    emptyText: '[Seleccione]',
						                                                    //allowBlank: false,
						                                                    labelWidth: 115,
						                                                    width:'100%',
						                                                    anchor:'100%',
						                                                    //readOnly: true,
						                                                    listConfig:{
																				minWidth:200
																			},
						                                                    listeners:{
						                                                        afterrender:function(obj, e){
						                                                        },
						                                                        select:function(obj, records, eOpts){
						                                                            
						                                                        }
						                                                    }
						                                                }
	                                                            	]
	                                                            },
	                                                            {
	                                                            	region:'south',
	                                                            	id:pickup_service.id+'-panel-destino-a',
	                                                            	border:false,
	                                                            	//layout:'fit',
	                                                            	bodyStyle: 'background: #fff',
									                                height:'100%',
	                                                            	hidden:true,
	                                                            	items:[
													        			{
									                                       xtype: 'findlocation',
									                                       height:255,
									                                       border:false,
									                                       id: pickup_service.id+'-origen',
									                                       mapping: false,
									                                       clearReferent:false,
									                                       getMapping:false,
									                                       setMapping:pickup_service.id+'-map',
									                                       trust:true,
									                                       changeEvent:pickup_service.getChangeEventOrigin,
									                                       listeners:{
									                                            afterrender: function(obj){
									                                            	Ext.getCmp(pickup_service.id+'-origen-finder-address').getHeader().hide();
									                                            }
									                                       }
									                                    },
									                                    {
							                                                xtype:'panel',
							                                                border:false,
							                                                bodyStyle: 'background: transparent',
							                                                padding:'0px 10px 0px 10px',
							                                                layout:'column',
							                                                items: [
							                                                	{
							                                                        columnWidth: 1,border:false,
							                                                        layout:'fit',
							                                                        padding:'0px 0px 0px 0px',  bodyStyle: 'background: transparent',
							                                                        items:[
							                                                            {
										                                                    xtype:'combo',
										                                                    fieldLabel: 'Contacto',
										                                                    id:pickup_service.id+'-contacto_-o-a',
										                                                    store: pickup_service.store_contacto,
										                                                    queryMode: 'local',
										                                                    triggerAction: 'all',
										                                                    valueField: 'id_contacto',
						                                                    				displayField: 'contacto',
										                                                    emptyText: '[Seleccione]',
										                                                    //allowBlank: false,
										                                                    labelWidth: 60,
										                                                    width:'100%',
										                                                    anchor:'100%',
										                                                    //readOnly: true,
										                                                    listConfig:{
																								minWidth:200
																							},
										                                                    listeners:{
										                                                        afterrender:function(obj, e){
										                                                        },
										                                                        select:function(obj, records, eOpts){
										                                                            
										                                                        }
										                                                    }
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
											    },
											    {
											        title: 'Programación',
											        id: pickup_service.id+'-tab-programacion',
											        disabled:true,
											        layout:'border',
											        listeners:{
											        	activate: function(tab, eOpts) {
											        		pickup_service.setTypeTab('C');
											                Ext.getCmp(pickup_service.id+'content-north').setHeight(400);
                											Ext.getCmp(pickup_service.id+'-content-region-center').show();
											            }
											        },
											        items:[
											        	{
											        		region:'north',
											        		height:60,
											        		border:false,
											        		items:[
											        			{
					                                                xtype:'panel',
					                                                id:pickup_service.id+'-fecha-programacion-a',
					                                                border:false,
					                                                bodyStyle: 'background: transparent',
					                                                padding:'5px 5px 5px 5px',
					                                                layout:'column',
					                                                items: [
					                                                	{
					                                                        columnWidth: 1,border:false,
					                                                        layout:'fit',
					                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
					                                                        items:[
					                                                        	{
																					xtype:'fieldset',
																					title:'Fecha a Recolectar',
																					items:[
																							{
																								xtype:'datefield',
																								editable:false,
																								allowBlank:false,
																								id:pickup_service.id+'-d-fecha-inicio',
																								margin:'0 0 5 0',
																								columnWidth:1,
																								fieldLabel:'Fecha Programada',
																								labelWidth:120,
																								minValue:new Date(),
																								value:new Date(),
																								listeners: {
																									'select' : {
																						                fn:function(){
																						                    pickup_service.getReloadHourList();
																						                }
																						            },
																						            blur: function(o) {
																							            pickup_service.getReloadHourList();
																							        }
																					            }
																							}
																					]
																				}
					                                                        ]
					                                                    }
					                                                ]
					                                            },
					                                            {
					                                                xtype:'panel',
					                                                id:pickup_service.id+'-fecha-programacion-b',
					                                                border:false,
					                                                hidden:true,
					                                                bodyStyle: 'background: transparent',
					                                                padding:'5px 5px 5px 5px',
					                                                layout:'fit',
					                                                items: [
					                                                	{
																			xtype:'fieldset',
																			title:'Programación Frecuente / Vigencia de la Programación',
																			layout:'column',
																			items:[
							                                                	{
							                                                        columnWidth: 0.5,border:false,
							                                                        layout:'fit',
							                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
							                                                        items:[
																						{
																							xtype:'datefield',
																							editable:false,
																							id:pickup_service.id+'-f-fecha-inicio',
																							allowBlank:false,
																							margin:'0 0 5 0',
																							fieldLabel:'Del',
																							columnWidth:0.5,
																							labelWidth:20,
																							minValue:new Date(),
																							value:new Date(),
																							listeners: {
																								'select' : {
																					                fn:function(){
																					                    pickup_service.getReloadHourList();
																					                }
																					            },
																					            blur: function(o) {
																						            pickup_service.getReloadHourList();
																						        }
																				            }
																						}
																					]
																				},
																				{
							                                                        columnWidth: 0.5,border:false,
							                                                        layout:'fit',
							                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
							                                                        items:[
																						{
																							xtype:'datefield',
																							editable:false,
																							id:pickup_service.id+'-f-fecha-fin',
																							allowBlank:false,
																							margin:'0 0 5 0',
																							columnWidth:0.5,
																							fieldLabel:'Al',
																							labelWidth:20,
																							minValue:new Date(),
																							value:new Date(),
																							listeners: {
																								'select' : {
																					                fn:function(){
																					                    pickup_service.getReloadHourList();
																					                }
																					            },
																					            blur: function(o) {
																						            pickup_service.getReloadHourList();
																						        }
																				            }
																						}
																					]
																				}
					                                                        ]
					                                                    }
					                                                ]
					                                            }
											        		]
											        	},
											        	{
											        		region:'center',
											        		border:false,
											        		layout:'fit',
											        		items:[
											        			{
																	xtype:'grid',
																	id:pickup_service.id+'-grid_diario',
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
																			url:pickup_service.url+'get_scm_dispatcher_horarios/',
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
																				callback:function(){
																					var count = obj.getStore().getCount();
																					if(count==1){
																						var record = obj.getStore().getAt(0);
																						Ext.getCmp(pickup_service.id+'-d-hora-max').setValue(record.get('hor_max'));
																						Ext.getCmp(pickup_service.id+'-d-hora-min').setValue(record.get('hor_min'));
																					}
																				}
																			});
																		},
																		beforeselect:function(obj, record, index, eOpts ){
																			Ext.getCmp(pickup_service.id+'-d-hora-max').setValue(record.get('hor_max'));
																			Ext.getCmp(pickup_service.id+'-d-hora-min').setValue(record.get('hor_min'));
																		}
																	}
																}
											        		]
											        	},
											        	{
											        		region:'south',
											        		height:100,
											        		border:false,
											        		items:[
											        			{
																	xtype:'fieldset',
																	layout:'column',
																	title:'Hora Programada',
																	items:[
																			{
																				xtype:'timefield',
																				id:pickup_service.id+'-d-hora-min',
																				//editabled:false,
																				//readOnly:true,
																				//allowBlank:false,
																				margin:'0 0 5 0',
																				columnWidth:0.5,
																				fieldLabel:'Hora Recolectar',
																				plugins:  [new ueInputTextMask('99:99')],
																				inputAttrTpl:" data-qtip='Ingrese Hora y Minuto (15:15) Formato de 24 Horas'",
																				labelWidth:100,
																				enableKeyEvents:true,
																				format: 'H:i',
																				altFormats:'H:i',
																				increment: 30,
																				listeners:{
																					keypress:function(obj){
																						//obj.setValue(null);
																					},
					                                                                keyup:function(obj){
					                                                                    //obj.setValue(null);
					                                                                }
																				}
																			},
																			{
																				xtype:'timefield',
																				id:pickup_service.id+'-d-hora-max',
																				//allowBlank:false,
																				//editable:true,
																				//readOnly:true,
																				margin:'0 0 5 0',
																				columnWidth:0.5,
																				fieldLabel:'Hora Maxima',
																				plugins:  [new ueInputTextMask('99:99')],
																				inputAttrTpl:" data-qtip='Ingrese Hora y Minuto (15:15) Formato de 24 Horas'",
																				format: 'H:i',
																				altFormats:'H:i',
																				increment: 30,
																				labelWidth:80,
																				enableKeyEvents:true,
																				listeners:{
																					keypress:function(obj){
																						//obj.setValue(null);
																					},
					                                                                keyup:function(obj){
					                                                                    //obj.setValue(null);
					                                                                }
																				}
																			}
																	]
																},
																{
																	xtype:'fieldset',
																	id:pickup_service.id+'-dias-programacion',
																	//disabled:true,
																	columnWidth:1,
																	title:'Dia de la Semana a Recolectar',
																	layout:'column',
																	items:[
																			{
																				xtype: 'checkboxgroup',
																				id:pickup_service.id+'-f-chk-dia-semana',
																				columnWidth:1,
																				columns: 7,
																				vertical: true,
																				items:[
																						{boxLabel: 'LU',name:'_semana',id:pickup_service.id+'_semana_LU',inputValue: 1},
																						{boxLabel: 'MA',name:'_semana',id:pickup_service.id+'_semana_MA',inputValue: 2},
																						{boxLabel: 'MI',name:'_semana',id:pickup_service.id+'_semana_MI',inputValue: 3},
																						{boxLabel: 'JU',name:'_semana',id:pickup_service.id+'_semana_JU',inputValue: 4},
																						{boxLabel: 'VI',name:'_semana',id:pickup_service.id+'_semana_VI',inputValue: 5},
																						{boxLabel: 'SA',name:'_semana',id:pickup_service.id+'_semana_SA',inputValue: 6},
																						{boxLabel: 'DO',name:'_semana',id:pickup_service.id+'_semana_DO',inputValue: 7}
																				]
																			}
																	]
																}
																/*{
																	xtype:'label',
																	columnWidth:1,
																	text:'* Rango Horario de 60 Min. Máximo'
																}*/

											        		]
											        	}
											        ]
											    }
										   	]
										}
									]
								},
								{
									region:'center',
									id: pickup_service.id+'-content-region-center',
									bodyStyle: 'background: #fff',
									border:false,
									layout:'border',
									tbar:[
                                        {
                                            xtype:'panel',
                                            border:false,
                                            height:34,
                                            width:'100%',
                                            anchor:'100%',
                                            baseCls:'databox_menu_novedad_titulo',
                                            html:'<div class="databox_ttl">MIS REQUERIMIENTOS<div class="databox_add_novedad"><a href="#" onclick="pickup_service.getAddRequest();"><em></em></a></div><div class="databox_menu_novedad"><a href="#" onclick="pickup_service.getFilterRequest();"><em></em></a></div></div>'
                                        }
                                    ],
									items:[
										{
											region:'north',
											id:pickup_service.id+'-filtro-requierimiento',
											bodyStyle: 'background: #fff',
											height:0,
											hidden:true,
											border:false,
											//layout:'fit',
											bbar:[
												'->',
												{
			                                        xtype:'button',
			                                        margin:'5, 0 0 0',
			                                        text:'Buscar',
			                                        icon:'/images/icon/search.png',
			                                        listeners:{
			                                            click:function(obj,e){
			                                            	pickup_service.getReloadRequest();
			                                            }
			                                        }
			                                    }
											],
											items:[
												{
													xtype:'fieldset',
													title:'Filtro de Búsqueda',
													margin:5,
													items:[
														{
			                                                xtype:'panel',
			                                                border:false,
			                                                bodyStyle: 'background: transparent',
			                                                padding:'3px 5px 3px 5px',
			                                                layout:'column',
			                                                items: [
			                                                	{
			                                                        columnWidth: 1,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
			                                                                xtype:'combo',
			                                                                fieldLabel: 'Linea',
			                                                                id:pickup_service.id+'-_linea_filtro',
			                                                                store: pickup_service.store_linea,
			                                                                queryMode: 'local',
			                                                                triggerAction: 'all',
			                                                                valueField: 'id',
			                                                                displayField: 'nombre',
			                                                                emptyText: '[Seleccione]',
			                                                                //allowBlank: false,
			                                                                labelWidth: 43,
			                                                                width:'100%',
			                                                                anchor:'100%',
			                                                                //readOnly: true,
			                                                                listeners:{
			                                                                    afterrender:function(obj, e){
			                                                                    },
			                                                                    select:function(obj, records, eOpts){
			                                                                        pickup_service.getReloadShipper('B');
			                                                                    }
			                                                                }
			                                                            }
			                                                        ]
			                                                    }
			                                                ]
			                                            },
														{
			                                                xtype:'panel',
			                                                border:false,
			                                                bodyStyle: 'background: transparent',
			                                                padding:'3px 5px 3px 5px',
			                                                layout:'column',
			                                                items: [
			                                                	{
			                                                        columnWidth: 1,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
			                                                                xtype:'combo',
			                                                                fieldLabel: 'Shipper',
			                                                                id:pickup_service.id+'-shipper-filtro',
			                                                                store: pickup_service.store_shipper_filtro,
			                                                                queryMode: 'local',
			                                                                triggerAction: 'all',
			                                                                valueField: 'shi_codigo',
			                                                                displayField: 'shi_nombre',
			                                                                emptyText: '[Seleccione]',
			                                                                //allowBlank: false,
			                                                                labelWidth: 42,
			                                                                width:'100%',
			                                                                anchor:'100%',
			                                                                //readOnly: true,
			                                                                listeners:{
			                                                                    afterrender:function(obj, e){
			                                                                    },
			                                                                    select:function(obj, records, eOpts){
			                                                                        //solicitar_envios.selected_shi(obj);
			                                                                    }
			                                                                }
			                                                            }
			                                                        ]
			                                                    }
			                                                ]
			                                            },
			                                            {
			                                                xtype:'panel',
			                                                border:false,
			                                                bodyStyle: 'background: transparent',
			                                                padding:'3px 5px 3px 5px',
			                                                layout:'column',
			                                                items: [
			                                                	{
			                                                        columnWidth: 1,border:false,
			                                                        layout:'fit',
			                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
			                                                        items:[
			                                                            {
																			xtype:'datefield',
																			id:pickup_service.id+'-fecha-filtro',
																			allowBlank:false,
																			margin:'0 0 5 0',
																			columnWidth:0.5,
																			fieldLabel:'Fecha',
																			labelWidth:42,
																			value:new Date()
																		}
			                                                        ]
			                                                    }
			                                                ]
			                                            },
			                                            {
			                                                xtype:'panel',
			                                                border:false,
			                                                bodyStyle: 'background: transparent',
			                                                padding:'3px 5px 3px 5px',
			                                                layout:'column',
			                                                items: [
			                                                	{
		                                                            xtype:'combo',
		                                                            fieldLabel: 'Estado',
		                                                            id:pickup_service.id+'_estado_filtro',
		                                                            store: Ext.create('Ext.data.Store',{
			                                                            autoLoad:true,
																		fields:[
																				{name: 'chk', type: 'string'},
												                                {name: 'descri', type: 'string'}
																		],
																		proxy:{
																			type:'ajax',
																			url:pickup_service.url+'get_estados/',
																			reader:{
																				type:'json',
																				rootProperty:'data'
																			} 
																		}
																	}),
		                                                            queryMode: 'local',
		                                                            triggerAction: 'all',
		                                                            valueField: 'chk',
			                                                        displayField: 'descri',
		                                                            emptyText: '[Seleccione]',
		                                                            //allowBlank: false,
		                                                            labelWidth: 42,
		                                                            width:'100%',
		                                                            anchor:'100%',
		                                                            //readOnly: true,
		                                                            listeners:{
		                                                                afterrender:function(obj, e){
		                                                                },
		                                                                select:function(obj, records, eOpts){
		                                                                    //solicitar_envios.selected_shi(obj);
		                                                                }
		                                                            }
		                                                        }
			                                                ]
			                                            }
													]
												}
											]
										},
										{
											region:'center',
											border:false,
											layout:'fit',
											items:[
												{
							                        xtype: 'dataview',
							                        id: pickup_service.id+'-requerimientos-lista',
							                        layout:'fit',
							                        store: pickup_service.store,
							                        autoScroll: true,
							                        loadMask:true,
							                        autoHeight: false,
							                        tpl: imageTplPointer,
							                        multiSelect: false,
							                        singleSelect: false,
							                        loadingText:'Cargando Lista de Requerimientos...',
							                        itemSelector: 'div.thumb-wrap',
							                        emptyText: '<div class="databox_list_pointer"><div class="databox_none_data" ></div><div class="databox_title_clear_data">NO TIENE NINGUN REQUERIMIENTO</div></div>',
							                        itemSelector: 'div.databox_list_pointer',
							                        trackOver: true,
							                        overItemCls: 'databox_list_pointer-hover',
							                        listeners: {
							                            'itemclick': function(view, record, item, idx, event, opts) {
							                            	try{
							                            		if(pickup_service.mapa.currentinfowindow)pickup_service.mapa.currentinfowindow.close();
							                            	}catch(e){}
							                            	try{
							                            		google.maps.event.trigger(pickup_service.mapa.markersService[parseInt(record.get('id_recojo'))],'click');//set id service 
							                            	}catch(e){}
							                            },
							                            'afterrender':function(){
							                                //Ext.getCmp(config.id+'-nov-lista').refresh();
							                                //Ext.getCmp(config.id+'-nov-lista').refresh();
							                            }
							                        }
							                    }
											]
										}
									]
								}
							],
							listeners:{
								boxready: function(self) {
				       			}
							}
						},
						{
							region:'center',
							id:pickup_service.id+'-region-center',
							layout:'border',
							border:false,
							items:[
								{
									region:'center',
									layout:'fit',
									border:false,
									html:'<div id="'+pickup_service.id+'-map" class="ue-map-canvas"></div>',
									listeners:{
										boxready: function(self) {
						       			}
									}
								},
								{
									region:'south',
									layout:'border',
									hidden:true,
									border:false,
									height:200,
									items:[
										{
											region:'west',
											width:'50%',
											border:false,
											layout:'fit',
											items:[
												{
				                                    xtype:'panel',
				                                    id:pickup_service.id+'-pnl-nov-pm-',
				                                    layout:'fit',
				                                    bodyCls: 'white_fondo',
				                                    border:false,
				                                    items:[
				                                        {
				                                            xtype:'panel',
				                                            id:pickup_service.id+'-pnl-nov-',
				                                            border:false,
				                                            layout:'fit',
				                                            items:[
				                                                {
				                                                    xtype:'GridNovedades',
				                                                    id:pickup_service.id,
				                                                    url:pickup_service.url_nv,
				                                                    records:pickup_service.load_records,
				                                                    front:1,
				                                                    autoLoad:false
				                                                }
				                                            ]
				                                        },
				                                        {
				                                            xtype:'panel',
				                                            id:pickup_service.id+'-pnl-coment-',
				                                            border:false,
				                                            hidden:true,
				                                            layout:'fit',
				                                            items:[
				                                                {
				                                                    xtype:'GridNovedadesComentarios',
				                                                    id:pickup_service.id,
				                                                    url:pickup_service.url_nv,
				                                                    closed:1
				                                                }
				                                            ]
				                                        }
				                                    ]
				                                }
											]
										},
										{
											region:'center',
											layout:'fit',
											border:false,
											items:[
												{
		                                            xtype:'grid',
		                                            margin:'0 0 0 0',
		                                            store:Ext.create('Ext.data.Store',{
		                                                model:'grid_agencias',
		                                                proxy:{
		                                                    type:'ajax',
		                                                    url:pickup_service.url+'scm_scm_home_delivery_agencia_shipper/',
		                                                    reader:{
		                                                        type:'json',
		                                                        root:'data'
		                                                    }
		                                                },
		                                                fields:[
															{name:'id_agencia', type:'int'},
															{name:'age_codigo', type:'int'},
															{name:'agencia', type:'string'},
															{name:'dir_calle', type:'string'},
															{name:'dir_referen', type:'string'},
															{name:'ciu_iata', type:'string'},
															{name:'distrito', type:'string'},
															{name:'ciu_id', type:'int'},
															{name:'ciu_ubigeo', type:'string'},
															{name:'dir_px', type:'string'},
															{name:'dir_py', type:'string'},
															{name:'shi_logo', type:'string'},
															{name:'und_id', type:'int'},
															{name:'gps_dist_m', type:'string'},
															{name:'gps_dist_t', type:'string'},
															{name:'gps_time_s', type:'string'},
															{name:'gps_time_t', type:'string'},
															{name:'und_px', type:'string'},
															{name:'und_py', type:'string'},
															{name:'tipo', type:'string'},
															{name:'sentido', type:'string'},
															{name:'und_placa', type:'string'},
															{name:'dis_text', type:'string'},
															{name:'dis_value', type:'string'},
															{name:'dur_text', type:'string'},
															{name:'dur_value', type:'string'},
															{name:'time_total', type:'string'},
															{name:'time_t', type:'string'}
														]
		                                            }),
		                                            id:pickup_service.id+'-grid-agencias-longitud',
		                                            columnLines: true,
		                                            height:'50%',
		                                            columns:{
		                                                items:[
		                                                        {
		                                                            text:'Agencia',
		                                                            flex:1,
		                                                            dataIndex:'agencia'
		                                                        },
		                                                        {
		                                                            text:'Tiempo',
		                                                            flex:1,
		                                                            //align:'center',
		                                                            dataIndex:'dur_text'
		                                                        },
		                                                        {
		                                                            text:'Und.Cercana',
		                                                            flex:1,
		                                                            dataIndex:'und_placa'
		                                                        },
		                                                        {
		                                                            text:'T. Aprox',
		                                                            flex:1,
		                                                            dataIndex:'gps_time_t'
		                                                        },
		                                                        {
		                                                            text:'T. Total',
		                                                            flex:1,
		                                                            //align:'center',
		                                                            dataIndex:'time_t'
		                                                        }
		                                                ]
		                                            },
		                                            listeners:{
		                                                beforeselect:function(obj, record, index, eOpts ){
		                                                }
		                                            }
		                                        }
											]
										}
									]
								}
							],
							listeners:{
								boxready: function(self) {
				       			}
							}
						}
					],
					listeners:{
						beforerender: function(obj, opts){
	                        global.state_item_menu(pickup_service.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,pickup_service.id_menu);
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(pickup_service.id_menu, false);
	                    },
	                    boxready:function(obj, width, height, eOpts ){
	                    	pickup_service.setMap();
	                    }
					}
				}).show();
			},
			setMap:function(){
				var directionsService = new google.maps.DirectionsService();
		        var rendererOptions = {
					  draggable: false,
					  suppressMarkers: true
				};
		        pickup_service.mapa.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
		        pickup_service.mapa.directionsService = new google.maps.DirectionsService();

		        if(pickup_service.coordenadas.destino.geo_px!=0){
		        	var myLatlng = new google.maps.LatLng(pickup_service.coordenadas.destino.geo_px,pickup_service.coordenadas.destino.geo_py);
		        }else if(pickup_service.coordenadas.destino.geo_px!=0){
		        	var myLatlng = new google.maps.LatLng(pickup_service.coordenadas.origen.geo_px,pickup_service.coordenadas.origen.geo_py);
		        }else{
		        	var myLatlng = new google.maps.LatLng(-12.0473179,-77.0824867);
		        }

		        var mapOptions = {
					zoom: 11,
    				center: myLatlng,
    				mapTypeId: google.maps.MapTypeId.ROADMAP
				};
		        pickup_service.map = new google.maps.Map(document.getElementById(pickup_service.id+'-map'), mapOptions);
		        
		        var homeControlDiv = document.createElement('div');
		        var homeControl = new HomeControl(homeControlDiv, pickup_service.map, myLatlng);
		        homeControlDiv.index = 1;
		        pickup_service.map.controls[google.maps.ControlPosition.TOP_CENTER].push(homeControlDiv);

		        var hdiv = document.createElement('div');
		        var hcontro = new pickup_service.HHomeControl(hdiv,pickup_service.map);
		        hdiv.index = 1;
		        pickup_service.map.controls[google.maps.ControlPosition.TOP_LEFT].push(hdiv);

		        pickup_service.mapa.directionsDisplay.setMap(pickup_service.map);
			},
			HHomeControl:function(controlDiv, map) {
			  controlDiv.style.padding = '5px';

			  // Set CSS for the control border.
			  var controlUI = document.createElement('div');
			  controlUI.style.backgroundColor = 'gray';
			  controlUI.style.borderStyle = 'gray';
			  controlUI.style.borderWidth = '2px';
			  controlUI.style.borderColor = 'gray';

			  controlUI.style.width = '100%';
			  controlUI.style.height = '100%';

			  controlUI.style.cursor = 'pointer';
			  controlUI.style.textAlign = 'center';
			  controlUI.title = 'Click Para Mostrar el Tráfico';
			  controlDiv.appendChild(controlUI);

			  // Set CSS for the control interior.
			  var controlText = document.createElement('div');
			  controlText.style.fontFamily = 'Arial,sans-serif';
			  controlText.style.fontSize = '12px';
			  controlText.style.paddingLeft = '4px';
			  controlText.style.paddingRight = '4px';
			  controlText.style.color = 'white';
			  controlText.innerHTML = '<input type="checkbox" id="trafic" name="trafic" onClick="pickup_service.trafic();"> <b>Ver Tráfico</b>';
			  controlUI.appendChild(controlText);
			},
			trafic:function(){
				 if (document.getElementById("trafic").checked){
				 	 pickup_service.mapa.trafficLayer.setMap(pickup_service.map);
				 }else{
				 	pickup_service.mapa.trafficLayer.setMap(null);
				 }

			},
			load_records:function(view, record, item, idx, event, opts, type){
                pickup_service.show_nv(false);
                pickup_service.record=record;
                pickup_service.idx = idx;
                pickup_service.type=type;
                pickup_service.view=view;
                pickup_service.reload_comentarios(record.data.id_nov);
            },
            reload_comentarios:function(id_nov){
                var obj = pickup_service.view;
                Ext.getCmp(pickup_service.id+'-nov-list_comentarios').getStore().removeAll();
                Ext.getCmp(pickup_service.id+'-nov-list_comentarios').getStore().load(
                    {params: {vp_id_nov: id_nov},
                    callback:function(){
                        Ext.getCmp(pickup_service.id+'-nov-list_comentarios').refresh();
                        pickup_service.status();
                        obj.all.elements[pickup_service.idx].childNodes[2].className = 'databox_status_off';
                        setTimeout( "pickup_service.remove_visto()", 2000 );
                    }
                });
            },
            show_nv:function(bol){
                Ext.getCmp(pickup_service.id+'-btn-add-nv').setVisible(bol);
                Ext.getCmp(pickup_service.id+'-btn-back').setVisible(!bol);
                Ext.getCmp(pickup_service.id+'-pnl-nov-').setVisible(bol);
                Ext.getCmp(pickup_service.id+'-pnl-coment-').setVisible(!bol);
                if(Ext.isChrome){
                    setTimeout( "pickup_service.spacial()", 300 );
                }
            },
            remove_visto:function(){
                var obj_ = Ext.getCmp(pickup_service.id+'-nov-list_comentarios');
                Ext.Object.each(obj_.all.elements, function(index, v){
                    obj_.all.elements[index].childNodes[2].className = 'databox_status_msn_off';
                });
            },
            reload_novedad:function(){

	            /*var vp_id_nov = 0;
	            var vp_desde = Ext.getCmp(panel_novedades.id+'-nov-desde').getRawValue();
	            var vp_hasta = Ext.getCmp(panel_novedades.id+'-nov-hasta').getRawValue();
	            var vp_linea = Ext.getCmp(panel_novedades.id+'-nov-linea').getValue();
	            var vp_prov_codigo = Ext.getCmp(panel_novedades.id+'-provincia').getValue();
	            var vp_pnov_id = Ext.getCmp(panel_novedades.id+'-nov-referente').getValue();
	            var vp_tnov_id = Ext.getCmp(panel_novedades.id+'-nov-motivo').getValue();
	            var vp_tdoc_id = Ext.getCmp(panel_novedades.id+'-nov-tipo_doc').getValue();
	            var vp_doc_numero = Ext.getCmp(panel_novedades.id+'-nov-nro-doc').getValue();
	            var vp_shi_codigo = Ext.getCmp(panel_novedades.id+'-nov-shipper').getValue();
	            var vp_new = Ext.getCmp(panel_novedades.id+'_chk_nv_new').getValue();
	            var vp_you = Ext.getCmp(panel_novedades.id+'_chk_mis_novedades').getValue();
	            var vp_activas = (vp_new)?1:0;
	            var vp_you = (vp_you)?1:0;*/

	            Ext.getCmp(pickup_service.id+'-nov-lista').getStore().removeAll();
	            Ext.getCmp(pickup_service.id+'-nov-lista').getStore().load(
	                {
	                params: {
	                    vp_desde:'01/01/2014',vp_hasta:'11/05/2015'
	                },
	                callback:function(){
	                    /*if(params.msn)me.remove_novedad();
	                    if(params.hist)me.remove_historico();*/
	                }
	            });
	        },
            elimina_novedad:function(msn_id,id_nov,flag){
                if(parseInt(flag)==0)return;
                if(msn_id== null || msn_id==''){
                    global.Msg({msg:"Mensaje no existe.",icon:2,fn:function(){}});
                    return false;
                }
                global.Msg({
                    msg: '¿Seguro de eliminar comentario?',
                    icon: 3,
                    buttons: 3,
                    fn: function(btn){
                        if (btn == 'yes'){
                            Ext.Ajax.request({
                                url:pickup_service.url_nv+'set_scm_elimina_comentario',
                                params:{vp_msn_id:msn_id},
                                success:function(response,options){
                                    var res = Ext.decode(response.responseText);

                                    if(parseInt(res.data[0].error_sql)<=0){

                                        global.Msg({
                                            msg:res.data[0].error_info,
                                            icon:0,
                                            fn:function(){
                                                    
                                            }
                                        });

                                    }else{
                                        global.Msg({
                                            msg:res.data[0].error_info,
                                            icon:1,
                                            fn:function(){
                                                pickup_service.reload_comentarios(id_nov);
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    }
                });
            },
            download_novedad:function(id_file){
                if(parseInt(id_file)==0)return;
                document.location.href=pickup_service.url_nv+'get_forzar_descarga/?vp_id_file='+id_file;
            },
            setChangeHeightContent:function(){
            	var origen = parseInt(Ext.getCmp(pickup_service.id+'-rbtn-group-origen').getValue()._rbtn_a);
                var destino = parseInt(Ext.getCmp(pickup_service.id+'-rbtn-group-destino').getValue()._rbtn_b);
                switch(origen){
                	case 1:case 2:
                		Ext.getCmp(pickup_service.id+'-inter-cont-north').setHeight(120);
                		if(destino<=2){
                			Ext.getCmp(pickup_service.id+'content-north').setHeight(320);//tab
                		}else{
                			Ext.getCmp(pickup_service.id+'content-north').setHeight(505);
                		}
                	break;
                	case 3:
                		Ext.getCmp(pickup_service.id+'-inter-cont-north').setHeight(310);
                		if(destino<=2){
                			Ext.getCmp(pickup_service.id+'content-north').setHeight(505);
                		}else{
                			Ext.getCmp(pickup_service.id+'content-north').setHeight(640);
                		}
                	break;
                }

                if(origen==1){
        			Ext.getCmp(pickup_service.id+'-panel-agencia-a').show();
        			Ext.getCmp(pickup_service.id+'-panel-centro-costo-a').hide();
        			Ext.getCmp(pickup_service.id+'-panel-destino-a').hide();
        		}else if(origen==2){
        			Ext.getCmp(pickup_service.id+'-panel-agencia-a').hide();
        			Ext.getCmp(pickup_service.id+'-panel-centro-costo-a').show();
        			Ext.getCmp(pickup_service.id+'-panel-destino-a').hide();
        		}else{
        			Ext.getCmp(pickup_service.id+'-panel-agencia-a').hide();
        			Ext.getCmp(pickup_service.id+'-panel-centro-costo-a').hide();
        			Ext.getCmp(pickup_service.id+'-panel-destino-a').show();
        		}

        		if(destino==1){
        			Ext.getCmp(pickup_service.id+'-panel-agencia-b').show();
        			Ext.getCmp(pickup_service.id+'-panel-centro-costo-b').hide();
        			Ext.getCmp(pickup_service.id+'-panel-destino-b').hide();
        		}else if(destino==2){
        			Ext.getCmp(pickup_service.id+'-panel-agencia-b').hide();
        			Ext.getCmp(pickup_service.id+'-panel-centro-costo-b').show();
        			Ext.getCmp(pickup_service.id+'-panel-destino-b').hide();
        		}else{
        			Ext.getCmp(pickup_service.id+'-panel-agencia-b').hide();
        			Ext.getCmp(pickup_service.id+'-panel-centro-costo-b').hide();
        			Ext.getCmp(pickup_service.id+'-panel-destino-b').show();
        		}
        		Ext.getCmp(pickup_service.id+'-origen-referencia-r').setHeight(60);
        		Ext.getCmp(pickup_service.id+'-destino-referencia-r').setHeight(60);
        		Ext.getCmp(pickup_service.id+'tab-content').doLayout();
        		Ext.getCmp(pickup_service.id+'-tab-confirmar').doLayout();
        		Ext.getCmp(pickup_service.id+'-inter-cont-center').doLayout();
        		Ext.getCmp(pickup_service.id+'content-north').doLayout();
        		pickup_service.setMarkerRequest();
            },
            getAddRequest:function(){
            	if(pickup_service.addNew){
                    var p1 = Ext.getCmp(pickup_service.id+'content-north');
                    p1.animate({
                        to: {
                            height: 0
                        },
                        listeners: {
                            beforeanimate:  function() {
                            },
                            afteranimate: function() {
                            	Ext.getCmp(pickup_service.id+'content-north').hide();
                            	pickup_service.addNew=false;
                            	Ext.getCmp(pickup_service.id+'-content-region-center').setDisabled(false);
                            	pickup_service.getReloadHourList();
                            },
                            scope: this
                        }
                    });
            	}else{
                    var p1 = Ext.getCmp(pickup_service.id+'content-north');
                    p1.animate({
                        to: {
                            height: 400
                        },
                        listeners: {
                            beforeanimate:  function() {
                            	Ext.getCmp(pickup_service.id+'content-north').show();
                            },
                            afteranimate: function() {
                               	pickup_service.addNew=true;
                               	Ext.getCmp(pickup_service.id+'tab-content').doLayout();
				        		Ext.getCmp(pickup_service.id+'-tab-confirmar').doLayout();
				        		Ext.getCmp(pickup_service.id+'-inter-cont-center').doLayout();
				        		Ext.getCmp(pickup_service.id+'content-north').doLayout();
				        		Ext.getCmp(pickup_service.id+'-content-region-center').setDisabled(true);
                            },
                            scope: this
                        }
                    });
            	}
            },
            getFilterRequest:function(){
            	if(pickup_service.filtro){
                    var p1 = Ext.getCmp(pickup_service.id+'-filtro-requierimiento');
                    p1.animate({
                        to: {
                            height: 0
                        },
                        listeners: {
                            beforeanimate:  function() {
                            },
                            afteranimate: function() {
                            	Ext.getCmp(pickup_service.id+'-filtro-requierimiento').hide();
                            	pickup_service.filtro=false;
                            },
                            scope: this
                        }
                    });
            	}else{
                    var p1 = Ext.getCmp(pickup_service.id+'-filtro-requierimiento');
                    p1.animate({
                        to: {
                            height: 177
                        },
                        listeners: {
                            beforeanimate:  function() {
                            	Ext.getCmp(pickup_service.id+'-filtro-requierimiento').show();
                            },
                            afteranimate: function() {
                               	pickup_service.filtro=true;
                               	Ext.getCmp(pickup_service.id+'-filtro-requierimiento').doLayout();
                            },
                            scope: this
                        }
                    });
            	}
            },
            getReloadRequest:function(){
            	var shi_codigo = Ext.getCmp(pickup_service.id+'-shipper-filtro').getValue();
            	var fecha = Ext.getCmp(pickup_service.id+'-fecha-filtro').getRawValue();
            	var estado = Ext.getCmp(pickup_service.id+'_estado_filtro').getValue();
            	Ext.getCmp(pickup_service.id+'-requerimientos-lista').getStore().removeAll();
            	Ext.getCmp(pickup_service.id+'-requerimientos-lista').getStore().load({
	                params: {
	                    vp_shi_codigo:shi_codigo,vp_fecha:fecha,vp_estado:estado
	                },
	                callback:function(){
	                }
	            });
            },
            setSaveRequest:function(){
            	switch(pickup_service.pickup.tab){
            		case 'A':
		            	var descripcion=Ext.getCmp(pickup_service.id+'-descripcion-').getValue();
		            	var observacion=Ext.getCmp(pickup_service.id+'-observacion-').getValue();
		            	var largo=Ext.getCmp(pickup_service.id+'-largo').getValue();
		            	var ancho=Ext.getCmp(pickup_service.id+'-ancho').getValue();
		            	var alto=Ext.getCmp(pickup_service.id+'-alto').getValue();
		            	var peso=Ext.getCmp(pickup_service.id+'-peso').getValue();
		            	var cantidad=Ext.getCmp(pickup_service.id+'-cantidad').getValue();
		            	var tipo_paquete=Ext.getCmp(pickup_service.id+'-tipo_paquete').getValue();
						var shipper=Ext.getCmp(pickup_service.id+'-shipper').getValue();
						var linea=Ext.getCmp(pickup_service.id+'-_linea_').getValue();
						var tipo_ejecucion=Ext.getCmp(pickup_service.id+'-rbtn-group-ejecutar').getValue()._rbtn_ejecutar;
						var pickup_frecuente=Ext.getCmp(pickup_service.id+'-pickup-frecuente').getValue();
						pickup_frecuente=(pickup_frecuente)?2:0;
						tipo_ejecucion=(tipo_ejecucion==1)?'R':'E';

						if (linea==0 || linea=='' || linea== null){
							global.Msg({
								msg:'Selecciona una linea de negocio',
								icon:2,
								buttosn:1,
								fn:function(btn){
									Ext.getCmp(pickup_service.id+'-_linea_').focus();
								}
							});
							return;
						}

						if (shipper==0 || shipper=='' || shipper== null){
							global.Msg({
								msg:'Selecciona un shipper',
								icon:2,
								buttosn:1,
								fn:function(btn){
									Ext.getCmp(pickup_service.id+'-shipper').focus();
								}
							});
							return;
						}
						if (tipo_paquete==null){
							global.Msg({
								msg:'Selecciona el tipo de paquete',
								icon:2,
								buttosn:1,
								fn:function(btn){
									Ext.getCmp(pickup_service.id+'-tipo_paquete').focus();
								}
							});
							return;
						}
						if (cantidad==0 || cantidad=='' || cantidad== null){
							global.Msg({
								msg:'La cantidad no puede ser menor igual a "0"',
								icon:2,
								buttosn:1,
								fn:function(btn){
									Ext.getCmp(pickup_service.id+'-cantidad').focus();
								}
							});
							return;
						}

						if (peso==0 || peso=='' || peso== null){
							global.Msg({
								msg:'El peso no puede ser menor igual a "0"',
								icon:2,
								buttosn:1,
								fn:function(btn){
									Ext.getCmp(pickup_service.id+'-peso').focus();
								}
							});
							return;
						}

					    global.Msg({
			                msg: '¿Realmente deseas grabar el proceso?',
			                icon: 3,
			                buttons: 3,
			                fn: function(btn){
			                    if (btn == 'yes'){
					            	Ext.Ajax.request({
										url:pickup_service.url+'scm_dispatcher_add_upd_orden/',
										params:{
											vp_srec_id:pickup_service.pickup.srec_id,
											vp_shi_cod:shipper,
											vp_linea:linea,
											vp_sentido_ruta:tipo_ejecucion,
											vp_tipo_srec:pickup_frecuente,
											vp_tipo_pqt:tipo_paquete,
											vp_cantidad:cantidad,
											vp_peso:peso,
											vp_alto:alto,
											vp_ancho:ancho,
											vp_largo:largo,
											vp_descri:descripcion,
											vp_observ:observacion
										},
										success:function(response,options){
											var res = Ext.decode(response.responseText);
											//console.log(res);
											if (parseInt(res.data[0].error_sql)==1){
												global.Msg({
													msg:res.data[0].error_info,
													icon:1,
													buttons:1,
													fn:function(btn){
														pickup_service.pickup.srec_id = parseInt(res.data[0].id_recojo);
														pickup_service.setBlocket();
														Ext.getCmp(pickup_service.id+'-tab-confirmar').setDisabled(false);
														Ext.getCmp(pickup_service.id+'tab-content').setActiveTab(1);
														pickup_service.setChangeTypePickUp(pickup_frecuente);
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
								}
					        }
					    });
					break;
					case 'B':
						var origen = parseInt(Ext.getCmp(pickup_service.id+'-rbtn-group-origen').getValue()._rbtn_a);
						var destino = parseInt(Ext.getCmp(pickup_service.id+'-rbtn-group-destino').getValue()._rbtn_b);
						//VALIDATE DATA 
	            		switch(origen){
	            			case 1:
	            				var prov_codigo = Ext.getCmp(pickup_service.id+'-agencia-a').getValue();
	            				var id_area = Ext.getCmp(pickup_service.id+'-_area_a').getValue();
	            				var id_personal = Ext.getCmp(pickup_service.id+'-_personal_-a').getValue();
	            				if (prov_codigo==0 || prov_codigo=='' || prov_codigo== null){
									global.Msg({
										msg:'Seleccione la provincia de origen',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-agencia-a').focus();
										}
									});
									return;
								}
								if (id_area==0 || id_area=='' || id_area== null){
									global.Msg({
										msg:'Seleccione el área de origen',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-_area_a').focus();
										}
									});
									return;
								}
								if (id_personal==0 || id_personal=='' || id_personal== null){
									global.Msg({
										msg:'Seleccione un personal en el origen',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-_personal_-a').focus();
										}
									});
									return;
								}
	            			break;
	            			case 2:
	            				var vp_id_agencia = Ext.getCmp(pickup_service.id+'-cc-a').getValue();
	            				var vp_id_contacto = Ext.getCmp(pickup_service.id+'-contacto_-a').getValue();
	            				if (vp_id_agencia==0 || vp_id_agencia=='' || vp_id_agencia== null){
									global.Msg({
										msg:'Seleccione una agencia de origen',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-cc-a').focus();
										}
									});
									return;
								}
								if (vp_id_contacto==0 || vp_id_contacto=='' || vp_id_contacto== null){
									global.Msg({
										msg:'Seleccione un contacto en el origen',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-contacto_-a').focus();
										}
									});
									return;
								}
	            			break;
	            			case 3:
	            				var v = Ext.getCmp(pickup_service.id+'-origen').getValues();
	            				var vp_id_contacto = Ext.getCmp(pickup_service.id+'-contacto_-o-a').getValue();
	            				if (parseFloat(v[0].coordenadas[0].lat)==-11.782413062516948 || parseFloat(v[0].coordenadas[0].lon)==-76.79493715625 || parseFloat(v[0].coordenadas[0].lat)==0 || parseFloat(v[0].coordenadas[0].lon)==0 ){
						            global.Msg({
						                msg:'Debes Realizar la búsqueda de una direccion de origen...',
						                icon:2,
						                buttosn:1,
						                fn:function(btn){
						                }
						            });
						            return;
						        }
						        if (vp_id_contacto==0 || vp_id_contacto=='' || vp_id_contacto== null){
									global.Msg({
										msg:'Seleccione un contacto en el origen',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-contacto_-o-a').focus();
										}
									});
									return;
								}
	            			break;
	            		}
	            		switch(destino){
	            			case 1:
	            				var prov_codigo = Ext.getCmp(pickup_service.id+'-agencia-b').getValue();
	            				var id_area = Ext.getCmp(pickup_service.id+'-_area_b').getValue();
	            				var id_personal = Ext.getCmp(pickup_service.id+'-_personal_-b').getValue();
	            				if (prov_codigo==0 || prov_codigo=='' || prov_codigo== null){
									global.Msg({
										msg:'Seleccione la provincia de destino',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-agencia-b').focus();
										}
									});
									return;
								}
								if (id_area==0 || id_area=='' || id_area== null){
									global.Msg({
										msg:'Seleccione el área de destino',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-_area_b').focus();
										}
									});
									return;
								}
								if (id_personal==0 || id_personal=='' || id_personal== null){
									global.Msg({
										msg:'Seleccione un personal en el destino',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-_personal_-b').focus();
										}
									});
									return;
								}
	            			break;
	            			case 2:
	            				var vp_id_agencia = Ext.getCmp(pickup_service.id+'-cc-b').getValue();
	            				var vp_id_contacto = Ext.getCmp(pickup_service.id+'-contacto_-b').getValue();
	            				if (vp_id_agencia==0 || vp_id_agencia=='' || vp_id_agencia== null){
									global.Msg({
										msg:'Seleccione una agencia de destino',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-cc-b').focus();
										}
									});
									return;
								}
								if (vp_id_contacto==0 || vp_id_contacto=='' || vp_id_contacto== null){
									global.Msg({
										msg:'Seleccione un contacto en el destino',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-contacto_-b').focus();
										}
									});
									return;
								}
	            			break;
	            			case 3:
	            				var v = Ext.getCmp(pickup_service.id+'-destino').getValues();
	            				var vp_id_contacto = Ext.getCmp(pickup_service.id+'-contacto_-d-a').getValue();
	            				if (parseFloat(v[0].coordenadas[0].lat)==-11.782413062516948 || parseFloat(v[0].coordenadas[0].lon)==-76.79493715625 || parseFloat(v[0].coordenadas[0].lat)==0 || parseFloat(v[0].coordenadas[0].lon)==0 ){
						            global.Msg({
						                msg:'Debes Realizar la búsqueda de una direccion de origen...',
						                icon:2,
						                buttosn:1,
						                fn:function(btn){
						                }
						            });
						            return;
						        }
						        if (vp_id_contacto==0 || vp_id_contacto=='' || vp_id_contacto== null){
									global.Msg({
										msg:'Seleccione un contacto en el destino',
										icon:2,
										buttosn:1,
										fn:function(btn){
											Ext.getCmp(pickup_service.id+'-contacto_-d-a').focus();
										}
									});
									return;
								}
	            			break;
	            		}
	            		if(origen==destino){
	            			switch(origen){
	            				case 1:
	            					var A = Ext.getCmp(pickup_service.id+'-_area_a').getValue();
	            					var B = Ext.getCmp(pickup_service.id+'-_area_b').getValue();
	            					if(A==B){
	            						global.Msg({
											msg:'El area origen no debe ser igual a la área de destino',
											icon:2,
											buttosn:1,
											fn:function(btn){
												
											}
										});
										return;
	            					}
	            				break;
	            				case 2:
	            					var A = Ext.getCmp(pickup_service.id+'-cc-a').getValue();
	            					var B = Ext.getCmp(pickup_service.id+'-cc-b').getValue();
	            					if(A==B){
	            						global.Msg({
											msg:'El Centreo de Actividad origen no debe ser igual al Centre de Actividad de destino',
											icon:2,
											buttosn:1,
											fn:function(btn){
												
											}
										});
										return;
	            					}
	            				break;
	            				case 3:
	            					//VALIDAR DESTINO POR UBIGEO
	            					var A = Ext.getCmp(pickup_service.id+'-origen').getValues();
	            					var B = Ext.getCmp(pickup_service.id+'-destino').getValues();
	            					if(A[0].id_puerta!=0 || A[0].id_puerta!=null || A[0].id_puerta!=''){
		            					if(A[0].id_puerta==B[0].id_puerta){
		            						global.Msg({
												msg:'La Dirección de origen no debe ser igual a la Dirección de destino',
												icon:2,
												buttosn:1,
												fn:function(btn){
													
												}
											});
											return;
		            					}
		            					if(pickup_service.apiEvent.o_prov_codigo!=pickup_service.apiEvent.d_prov_codigo){
	            							global.Msg({
												msg:'La Ciudad de origen debe ser igual a la Ciudad de destino',
												icon:2,
												buttosn:1,
												fn:function(btn){
													
												}
											});
											return;
	            						}
	            					}
	            				break;
	            			}
	            		}
	            		global.Msg({
			                msg: '¿Realmente deseas grabar el proceso?',
			                icon: 3,
			                buttons: 3,
			                fn: function(btn){
			                    if (btn == 'yes'){
					            	pickup_service.getRecursiveFromPreventData(0);
					            }
					        }
					    });
						
					break;
					case 'C':
						var string_day='';
						var hora_max = Ext.getCmp(pickup_service.id+'-d-hora-max').getRawValue();
						var hora_min = Ext.getCmp(pickup_service.id+'-d-hora-min').getRawValue();
						switch(pickup_service.pickup.tipo){
							case 0:
								var fecha_inicio = Ext.getCmp(pickup_service.id+'-d-fecha-inicio').getRawValue();
								var fecha_fin=fecha_inicio;
							break;
							case 2:
								var fecha_inicio = Ext.getCmp(pickup_service.id+'-f-fecha-inicio').getRawValue();
								var fecha_fin = Ext.getCmp(pickup_service.id+'-f-fecha-fin').getRawValue();
								var count = 0;
								for(var i=0;i<pickup_service.dias.length;i++){
									var bool=Ext.getCmp(pickup_service.id+'_semana_'+pickup_service.dias[i]).getValue();
									if(bool){
										string_day+='S';
										count++;
									}else{
										string_day+='N';
									}
								}
								if (count==0){
									global.Msg({
										msg:'Seleccione Almenos un día en la Programación',
										icon:2,
										buttosn:1,
										fn:function(btn){
										}
									});
									return;
								}
							break;
						}
						//var origen = Ext.getCmp(pickup_service.id+'-grid_diario').getValue();
						if (fecha_inicio==''){
							global.Msg({
								msg:'Seleccione Almenos un día en la Programación',
								icon:2,
								buttosn:1,
								fn:function(btn){
								}
							});
							return;
						}
						if (hora_max==''){
							global.Msg({
								msg:'Seleccione el horario en el listado',
								icon:2,
								buttosn:1,
								fn:function(btn){
								}
							});
							return;
						}
						if (hora_min==''){
							global.Msg({
								msg:'Seleccione el horario en el listado',
								icon:2,
								buttosn:1,
								fn:function(btn){
								}
							});
							return;
						}
						global.Msg({
			                msg: '¿Realmente deseas grabar el proceso?',
			                icon: 3,
			                buttons: 3,
			                fn: function(btn){
			                    if (btn == 'yes'){
					            	Ext.Ajax.request({
										url:pickup_service.url+'scm_dispatcher_upd_programacion/',
										params:{
											vp_srec_id:pickup_service.pickup.srec_id,
											vp_fecini:fecha_inicio,
											vp_horini:hora_min,
											vp_fecfin:fecha_fin,
											vp_horfin:hora_max,
											vp_semana:string_day
										},
										success:function(response,options){
											var res = Ext.decode(response.responseText);
											//console.log(res);
											if (parseInt(res.data[0].error_sql)==1){
												global.Msg({
													msg:res.data[0].error_info,
													icon:1,
													buttons:1,
													fn:function(btn){
														//FIN DEL PROCESO
														//Ext.getCmp(pickup_service.id+'tab-content').setActiveTab(0);
														//pickup_service.setClear();
														Ext.getCmp(pickup_service.id+'-pickup-cancel').hide();
														Ext.getCmp(pickup_service.id+'-pickup-close').show();
														pickup_service.getCloseFrom();
														pickup_service.getReloadRequest();
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
					            }
					        }
					    });
					break;
				}
            },
            getRecursiveFromPreventData:function(i){
            	switch(i){
            		case 0:
            			var origen = parseInt(Ext.getCmp(pickup_service.id+'-rbtn-group-origen').getValue()._rbtn_a);
	            		switch(origen){
	            			case 1:
	            				var prov_codigo = Ext.getCmp(pickup_service.id+'-agencia-a').getValue();
	            				var id_area = Ext.getCmp(pickup_service.id+'-_area_a').getValue();
	            				var id_personal = Ext.getCmp(pickup_service.id+'-_personal_-a').getValue();  
	            				pickup_service.setRecursiveSaveData({
				            		i:i,
				            		vp_srec_id:pickup_service.pickup.srec_id,
				            		vp_id_ruta:1,
				            		vp_origen:origen,
				            		vp_id_dir:pickup_service.pickup.id_dir_a,
				            		vp_id_origen:prov_codigo,
				            		vp_id_area:id_area,
				            		vp_id_contacto:id_personal
				            	});
	            			break;
	            			case 2:
	            				var vp_id_agencia = Ext.getCmp(pickup_service.id+'-cc-a').getValue();
	            				var vp_id_contacto = Ext.getCmp(pickup_service.id+'-contacto_-a').getValue();
	            				pickup_service.setRecursiveSaveData({
				            		i:i,
				            		vp_srec_id:pickup_service.pickup.srec_id,
				            		vp_id_ruta:1,
				            		vp_origen:origen,
				            		vp_id_dir:pickup_service.pickup.id_dir_a,
				            		vp_id_origen:vp_id_agencia,
				            		vp_id_area:0,
				            		vp_id_contacto:vp_id_contacto
				            	});
	            			break;
	            			case 3:
	            				var v = Ext.getCmp(pickup_service.id+'-origen').getValues();
	            				var vp_id_contacto = Ext.getCmp(pickup_service.id+'-contacto_-o-a').getValue();
	            				pickup_service.setRecursiveSaveData({
				            		i:i,
				            		vp_srec_id:pickup_service.pickup.srec_id,
				            		vp_id_ruta:1,
				            		vp_origen:origen,
				            		vp_id_dir:pickup_service.pickup.id_dir_a,
				            		vp_id_origen:0,
				            		vp_ciu_id:v[0].ciu_id,
							        vp_id_contacto:vp_id_contacto,
							        vp_id_area:0,
							        vp_id_geo:v[0].id_puerta,
							        vp_via_id:v[0].id_via,
							        vp_dir_calle:v[0].dir_calle,
							        vp_dir_numvia:v[0].nro_via,
							        vp_dir_refere:v[0].referencia,
							        vp_urb_id:v[0].id_urb,
							        vp_urb_nom:v[0].nombre_urb,
							        vp_mz_id:v[0].id_mza,
							        vp_mz_nom:v[0].nombre_mza,
							        vp_num_lote:v[0].nro_lote,
							        vp_num_int:v[0].nro_interno,
							        vp_dir_px:v[0].coordenadas[0].lat,
							        vp_dir_py:v[0].coordenadas[0].lon
				            	});
	            			break;
	            		}
            		break;
            		case 1:
            			var destino = parseInt(Ext.getCmp(pickup_service.id+'-rbtn-group-destino').getValue()._rbtn_b);
	            		switch(destino){
	            			case 1:
	            				var prov_codigo = Ext.getCmp(pickup_service.id+'-agencia-b').getValue();
	            				var id_area = Ext.getCmp(pickup_service.id+'-_area_b').getValue();
	            				var id_personal = Ext.getCmp(pickup_service.id+'-_personal_-b').getValue();
	            				pickup_service.setRecursiveSaveData({
				            		i:i,
				            		vp_origen:destino,
				            		vp_srec_id:pickup_service.pickup.srec_id,
				            		vp_id_ruta:2,
				            		vp_id_origen:prov_codigo,
				            		vp_id_dir:pickup_service.pickup.id_dir_b,
				            		vp_id_area:id_area,
				            		vp_id_contacto:id_personal
				            	});
	            			break;
	            			case 2:
	            				var vp_id_agencia = Ext.getCmp(pickup_service.id+'-cc-b').getValue();
	            				var vp_id_contacto = Ext.getCmp(pickup_service.id+'-contacto_-b').getValue();
	            				pickup_service.setRecursiveSaveData({
				            		i:i,
				            		vp_origen:destino,
				            		vp_srec_id:pickup_service.pickup.srec_id,
				            		vp_id_ruta:2,
				            		vp_id_origen:vp_id_agencia,
				            		vp_id_dir:pickup_service.pickup.id_dir_b,
				            		vp_id_area:0,
				            		vp_id_contacto:vp_id_contacto
				            	});
	            			break;
	            			case 3:
	            				var v = Ext.getCmp(pickup_service.id+'-destino').getValues();
	            				var vp_id_contacto = Ext.getCmp(pickup_service.id+'-contacto_-d-a').getValue();
	            				pickup_service.setRecursiveSaveData({
				            		i:i,
				            		vp_srec_id:pickup_service.pickup.srec_id,
				            		vp_id_ruta:2,
				            		vp_origen:destino,
				            		vp_id_origen:0,
				            		vp_id_dir:pickup_service.pickup.id_dir_b,
				            		vp_ciu_id:v[0].ciu_id,
							        vp_id_contacto:vp_id_contacto,
							        vp_id_area:0,
							        vp_id_geo:v[0].id_puerta,
							        vp_via_id:v[0].id_via,
							        vp_dir_calle:v[0].dir_calle,
							        vp_dir_numvia:v[0].nro_via,
							        vp_dir_refere:v[0].referencia,
							        vp_urb_id:v[0].id_urb,
							        vp_urb_nom:v[0].nombre_urb,
							        vp_mz_id:v[0].id_mza,
							        vp_mz_nom:v[0].nombre_mza,
							        vp_num_lote:v[0].nro_lote,
							        vp_num_int:v[0].nro_interno,
							        vp_dir_px:v[0].coordenadas[0].lat,
							        vp_dir_py:v[0].coordenadas[0].lon
				            	});
	            			break;
	            		}
	            	break;
	            	default:
            			pickup_service.setRecursiveSaveData({i:i});
            		break;
            	}
            },
            setRecursiveSaveData:function(record){
            	//console.log(record);
            	if(record.i<=1){
	            	Ext.Ajax.request({
						url:pickup_service.url+'scm_dispatcher_add_upd_ruta_origen/',
						params:record,
						success:function(response,options){
							var res = Ext.decode(response.responseText);
							//console.log(res);
							if (parseInt(res.data[0].error_sql)==1){
								if(record.i!=0){
									global.Msg({
										msg:res.data[0].error_info,
										icon:1,
										buttons:1,
										fn:function(btn){
											pickup_service.pickup.id_dir_b=res.data[0].id_dir;
											pickup_service.getRecursiveFromPreventData(record.i+1);
										}
									});
								}else{
									pickup_service.pickup.id_dir_a=res.data[0].id_dir;
									pickup_service.getRecursiveFromPreventData(record.i+1);
								}
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
            		Ext.getCmp(pickup_service.id+'-tab-programacion').setDisabled(false);
            		Ext.getCmp(pickup_service.id+'tab-content').setActiveTab(2);
                    pickup_service.setMarkerRequest();
            		//BLOQUEAR TAB 2
            	}
            },
            setBlocket:function(){
            	//Ext.getCmp(pickup_service.id+'-tipo_paquete').getValue();
				Ext.getCmp(pickup_service.id+'-shipper').setDisabled(true);
				Ext.getCmp(pickup_service.id+'-_linea_').setDisabled(true);
				//Ext.getCmp(pickup_service.id+'-rbtn-group-ejecutar').disable();
				Ext.getCmp(pickup_service.id+'_rbtn_ejecutar_a').disable();
				Ext.getCmp(pickup_service.id+'_rbtn_ejecutar_b').disable();
				Ext.getCmp(pickup_service.id+'-pickup-frecuente').setDisabled(true);
            },
            setMarker:function(record){
				var point = new google.maps.LatLng(parseFloat(record.geo_px),parseFloat(record.geo_py));
                var marker = new google.maps.Marker({
                        position: point,
                        map: pickup_service.map,
                        animation: google.maps.Animation.DROP,
                        title: '',
                        icon:'/images/icon/'+record.icon,
                        tipo:record.tipo_marker,
                        id:record.id_marker
                });
                var infowindow = new google.maps.InfoWindow({
                      content: '<div id="content_info_'+record.id_marker+'"  style="width:100%;padding:0px;margin:0px;">'+record.nombre+'</div>',
                      maxWidth: 550
                });
                google.maps.event.addListener(marker, 'click', function() {
                	if(marker.id!=0){
	                	pickup_service.getInfoPanetMarker({
	                		id:marker.id
	                	});
                	}
                	if(pickup_service.mapa.currentinfowindow)pickup_service.mapa.currentinfowindow.close();
                	pickup_service.mapa.currentinfowindow=infowindow;
                    pickup_service.mapa.currentinfowindow.open(pickup_service.map,marker);
                    
                });
                
                switch(record.tipo_marker){
					case 'S':
                		pickup_service.mapa.markersService[parseInt(record.id_marker)]=marker;
                	break;
                	default:
                		pickup_service.mapa.markers.push(marker);
                	break;
                }
			},
			setClearMarker:function(){
				if (pickup_service.mapa.markers) {
			    	for (i in pickup_service.mapa.markers) {
			      		pickup_service.mapa.markers[i].setMap(null);
			    	}
			  	}
			  	if (pickup_service.mapa.markersService.length>0) {
			    	pickup_service.store.each(function(record){
			    		if(parseInt(record.get('px'))!=0){
			    			try{
			      				pickup_service.mapa.markersService[parseInt(record.get('id_recojo'))].setMap(null);
			      			}catch(e){}
			      		}
			    	});
			  	}
			},
			setMarkerRequest:function(){
				pickup_service.setMap();
				pickup_service.setClearMarker();
				if(pickup_service.coordenadas.origen.geo_px!=0){
					pickup_service.setMarker({geo_px:pickup_service.coordenadas.origen.geo_px,geo_py:pickup_service.coordenadas.origen.geo_py,icon:'map-marker-24.png',nombre:'',tipo_marker:'',id_marker:0});
				}
				if(pickup_service.coordenadas.destino.geo_px!=0){
					pickup_service.setMarker({geo_px:pickup_service.coordenadas.destino.geo_px,geo_py:pickup_service.coordenadas.destino.geo_py,icon:'home_delivery.png',nombre:'',tipo_marker:'',id_marker:0});
				}
			},
			setCoordMarckerService:function(){
            	if(pickup_service.pickup.record.length>0){
            		pickup_service.setMap();
					pickup_service.setClearMarker();
					for (var i = pickup_service.pickup.record.length - 1; i >= 0; i--) {
						if(parseFloat(pickup_service.pickup.record[i].data.px)!=0){
							if(pickup_service.pickup.record[i].data.tipo=='E'){
								pickup_service.setMarker({geo_px:parseFloat(pickup_service.pickup.record[i].data.px),geo_py:parseFloat(pickup_service.pickup.record[i].data.py),icon:'home_delivery.png',nombre:pickup_service.pickup.record[i].data.servicio+' <br> '+pickup_service.pickup.record[i].data.horario,tipo_marker:'S',id_marker:pickup_service.pickup.record[i].data.id_recojo});
							}else{
								pickup_service.setMarker({geo_px:parseFloat(pickup_service.pickup.record[i].data.px),geo_py:parseFloat(pickup_service.pickup.record[i].data.py),icon:'map-marker-24.png',nombre:pickup_service.pickup.record[i].data.servicio+' <br> '+pickup_service.pickup.record[i].data.horario,tipo_marker:'S',id_marker:pickup_service.pickup.record[i].data.id_recojo});
							}
						}	
					};
            	}
            },
            getInfoPanetMarker:function(record){
				Ext.Ajax.request({
					url:pickup_service.url+'scm_dispatcher_datos_servicios/',
					params:{
						vp_srec_id:record.id
					},
					success:function(response,options){
						var res = Ext.decode(response.responseText);
						//console.log(res);
						if (parseInt(res.data[0].error_sql)==0){
							pickup_service.getPanelMarker({
								id:record.id,
								shipper:res.data[0].shipper,
								servicio:res.data[0].servicio,
								carga:res.data[0].carga,
								horario:res.data[0].horario,
								direccion:res.data[0].direccion,
								referencia:res.data[0].referencia,
								distrito:res.data[0].distrito,
								tipo:res.data[0].tipo,
								estado:res.data[0].estado,
								observaciones:res.data[0].observaciones,
								propietario:res.data[0].propietario,
								ejecutor:res.data[0].ejecutor
							});
						}else{
							
						}
					}
				});
			},
			getPanelMarker:function(record){
				//console.log(record);
	            var imageTplPointerPanel = new Ext.XTemplate(
	                '<tpl for=".">',
	                    '<div class="" >',
	                        '<div class="databox_mensage" >',
	                            '<div class="databox_bar">',
	                                '<div class="databox_title">',
	                                    '<span>'+record.shipper+'</span>',
	                                '</div>',
	                                //'<div class="databox_date"><span class="dbx_user">'+record.data.usr_codigo+'</span><span class="dbx_fecha">'+record.data.fecha+'</span></div>',
	                            '</div>',
	                            '<div class="databox_message">'+record.servicio+'</div>',
	                        '</div>',
	                        '<div class="databox_btools">',
	                            '<hr></hr>',
	                            '<span><p>CARGA:</p>'+record.carga+'</span>',
	                            '<hr></hr>',
	                            '<span><p>HORARIOS:</p>'+record.horario+'</span>',
	                            '<hr></hr>',
	                            '<span><p>DIRECCIÓN:</p></span><span>'+record.direccion+'</span>',
	                            '<hr></hr>',
	                            '<span><p>REFERENCIA:</p></span><span>'+record.referencia+'</span>',
	                            '<hr></hr>',
	                            '<span><p>DISTRITO:</p></span><span>'+record.distrito+'</span>',
	                            '<hr></hr>',
	                            '<span><p>ESTADO:</p></span><span>'+record.tipo+'</span>',
	                            '<hr></hr>',
	                            '<span><p>OBSERVACIONES:</p></span><span>'+record.observaciones+'</span>',
	                            '<hr></hr>',
	                            '<span><p>PROPIETARIO:</p></span><span>'+record.propietario+'</span>',
	                            '<hr></hr>',
	                            '<span><p>EJECUTOR:</p></span><span>'+record.ejecutor+'</span>',
	                            '<hr></hr>',
	                        '</div>',
	                    '</div>',
	                '</tpl>'
	            );
				//console.log(imageTplPointerPanel);
	            Ext.get('content_info_'+record.id).setHtml(imageTplPointerPanel.html);
			},
            setClear:function(){
            	pickup_service.pickup={
	            	tab:'A',
	            	srec_id:0,
	            	tipo:0,
	            	id_dir_a:0,
	            	id_dir_b:0,
	            	record:{}
	            };
	            pickup_service.coordenadas={
	            	origen:{
	            		geo_px:0,
	            		geo_py:0
	            	},
	            	destino:{
	            		geo_px:0,
	            		geo_py:0
	            	}
	            };
	            pickup_service.apiEvent={
	            	o_ciu_id:0,
	            	o_prov_codigo:0,
	            	d_ciu_id:0,
	            	d_prov_codigo:0
	            };
            	Ext.getCmp(pickup_service.id+'-descripcion-').setValue(null);
            	Ext.getCmp(pickup_service.id+'-observacion-').setValue(null);
            	Ext.getCmp(pickup_service.id+'-largo').setValue(null);
            	Ext.getCmp(pickup_service.id+'-ancho').setValue(null);
            	Ext.getCmp(pickup_service.id+'-alto').setValue(null);
            	Ext.getCmp(pickup_service.id+'-peso').setValue(null);
            	Ext.getCmp(pickup_service.id+'-cantidad').setValue(null);
            	Ext.getCmp(pickup_service.id+'-tipo_paquete').setValue(null);
				//Ext.getCmp(pickup_service.id+'-shipper').setValue('');
				//Ext.getCmp(pickup_service.id+'-_linea_').setValue('');
				Ext.getCmp(pickup_service.id+'-rbtn-group-ejecutar').setValue(1);
				Ext.getCmp(pickup_service.id+'-pickup-frecuente').setValue(false);

				Ext.getCmp(pickup_service.id+'-shipper').setDisabled(false);
				Ext.getCmp(pickup_service.id+'-_linea_').setDisabled(false);
				//Ext.getCmp(pickup_service.id+'-rbtn-group-ejecutar').enable();
				Ext.getCmp(pickup_service.id+'_rbtn_ejecutar_a').enable();
				Ext.getCmp(pickup_service.id+'_rbtn_ejecutar_b').enable();
				Ext.getCmp(pickup_service.id+'-pickup-frecuente').setDisabled(false);
				pickup_service.setChangeTypePickUp(0);
				Ext.getCmp(pickup_service.id+'tab-content').setActiveTab(0);
				Ext.getCmp(pickup_service.id+'-tab-datos').setDisabled(false);
				Ext.getCmp(pickup_service.id+'-tab-confirmar').setDisabled(true);
				Ext.getCmp(pickup_service.id+'-tab-programacion').setDisabled(true);

				Ext.getCmp(pickup_service.id+'-inter-cont-north').enable();
				Ext.getCmp(pickup_service.id+'-rbtn-group-origen').enable();
				Ext.getCmp(pickup_service.id+'-rbtn-group-destino').enable();

				Ext.getCmp(pickup_service.id+'_rbtn_a_a').destroy();
				Ext.getCmp(pickup_service.id+'_rbtn_a_b').destroy();
				Ext.getCmp(pickup_service.id+'_rbtn_a_c').destroy();
				Ext.getCmp(pickup_service.id+'-rbtn-group-origen').add(
				{boxLabel:'Agencia',id:pickup_service.id+'_rbtn_a_a',name:'_rbtn_a',inputValue:1, width:70,checked:true},
				{boxLabel:'Centro de Actividad',id:pickup_service.id+'_rbtn_a_b',name:'_rbtn_a',inputValue:2, width:130},
				{boxLabel:'Otro',id:pickup_service.id+'_rbtn_a_c',name:'_rbtn_a',inputValue:3, width:70});

				Ext.getCmp(pickup_service.id+'_rbtn_b_a').destroy();
				Ext.getCmp(pickup_service.id+'_rbtn_b_b').destroy();
				Ext.getCmp(pickup_service.id+'_rbtn_b_c').destroy();

				Ext.getCmp(pickup_service.id+'-rbtn-group-destino').add(
				{boxLabel:'Agencia',id:pickup_service.id+'_rbtn_b_a',name:'_rbtn_b',inputValue:1, width:70,checked:true},
				{boxLabel:'Centro de Actividad',id:pickup_service.id+'_rbtn_b_b',name:'_rbtn_b',inputValue:2, width:130},
				{boxLabel:'Otro',id:pickup_service.id+'_rbtn_b_c',name:'_rbtn_b',inputValue:3, width:70});

				Ext.getCmp(pickup_service.id+'-pickup-cancel').show();
				Ext.getCmp(pickup_service.id+'-pickup-close').hide();

				for(var i=0;i<pickup_service.dias.length;i++){
					var bool=Ext.getCmp(pickup_service.id+'_semana_'+pickup_service.dias[i]).setValue(false);
				}

				Ext.getCmp(pickup_service.id+'-agencia-a').setValue(null);
				Ext.getCmp(pickup_service.id+'-_area_a').setValue(null);
				Ext.getCmp(pickup_service.id+'-_personal_-a').setValue(null);
				Ext.getCmp(pickup_service.id+'-_personal_-a').getStore().removeAll();
				Ext.getCmp(pickup_service.id+'-cc-a').setValue(null);
				Ext.getCmp(pickup_service.id+'-contacto_-a').setValue(null);
				Ext.getCmp(pickup_service.id+'-contacto_-a').getStore().removeAll();
				Ext.getCmp(pickup_service.id+'-origen').reset();
				Ext.getCmp(pickup_service.id+'-origen').reset_global_vars();
				Ext.getCmp(pickup_service.id+'-contacto_-o-a').setValue(null);

				Ext.getCmp(pickup_service.id+'-agencia-b').setValue(null);
				Ext.getCmp(pickup_service.id+'-_area_b').setValue(null);
				Ext.getCmp(pickup_service.id+'-_personal_-b').setValue(null);
				Ext.getCmp(pickup_service.id+'-cc-b').setValue(null);
				Ext.getCmp(pickup_service.id+'-contacto_-b').setValue(null);
				Ext.getCmp(pickup_service.id+'-destino').reset();
				Ext.getCmp(pickup_service.id+'-destino').reset_global_vars();
				Ext.getCmp(pickup_service.id+'-contacto_-d-a').setValue(null);
				
				Ext.getCmp(pickup_service.id+'-d-hora-max').setValue(null);
				Ext.getCmp(pickup_service.id+'-d-hora-min').setValue(null);

				Ext.getCmp(pickup_service.id+'-d-fecha-inicio').setValue('<?php echo date("dmY");?>');
				Ext.getCmp(pickup_service.id+'-f-fecha-inicio').setValue('<?php echo date("dmY");?>');
				Ext.getCmp(pickup_service.id+'-f-fecha-fin').setValue('<?php echo date("dmY");?>');
            },
            setChangeTypePickUp:function(tipo){
            	pickup_service.pickup.tipo=parseInt(tipo);
            	switch(parseInt(tipo)){
            		case 0:
            			//Ext.getCmp(pickup_service.id+'-f-chk-dia-semana').setDisabled(true);
            			pickup_service.setDisabledCheckBoxDay(true);
            			Ext.getCmp(pickup_service.id+'-fecha-programacion-b').hide();
            			Ext.getCmp(pickup_service.id+'-fecha-programacion-a').show();
            			pickup_service.setClearCheckBoxDay();
            		break;
            		case 2:
            			//Ext.getCmp(pickup_service.id+'-f-chk-dia-semana').setDisabled(false);
            			pickup_service.setDisabledCheckBoxDay(false);
            			Ext.getCmp(pickup_service.id+'-fecha-programacion-b').show();
            			Ext.getCmp(pickup_service.id+'-fecha-programacion-a').hide();
            		break;
            	}
            },
            setClearCheckBoxDay:function(){
            	for(var i=0;i<pickup_service.dias.length;i++){
					var bool=Ext.getCmp(pickup_service.id+'_semana_'+pickup_service.dias[i]).setValue(false);
				}
            },
            setDisabledCheckBoxDay:function(type){
				for(var i=0;i<pickup_service.dias.length;i++){
					var bool=Ext.getCmp(pickup_service.id+'_semana_'+pickup_service.dias[i]).setDisabled(type);
				}
            },
            setTypeTab:function(tab){
            	pickup_service.pickup.tab=tab;
            	switch(tab){
            		case 'A':
		            	Ext.getCmp(pickup_service.id+'-grabar-').show();
		            	Ext.getCmp(pickup_service.id+'-pickup-frecuente').show();
            		break;
            		case 'B':
		            	Ext.getCmp(pickup_service.id+'-grabar-').show();
		            	Ext.getCmp(pickup_service.id+'-pickup-frecuente').hide();
            		break;
            		case 'C':
		            	Ext.getCmp(pickup_service.id+'-grabar-').show();
		            	Ext.getCmp(pickup_service.id+'-pickup-frecuente').hide();
            		break;
            	}
            },
            getReloadShipper:function(tipo){
            	switch(tipo){
            		case 'A':
            			var vp_linea = Ext.getCmp(pickup_service.id+'-_linea_').getValue();
            			Ext.getCmp(pickup_service.id+'-shipper').getStore().removeAll();
		            	Ext.getCmp(pickup_service.id+'-shipper').getStore().load({
			                params: {
			                    vp_linea:vp_linea
			                },
			                callback:function(){
			                	Ext.getCmp(pickup_service.id+'-agencia-a').getStore().removeAll();
			                	Ext.getCmp(pickup_service.id+'-agencia-a').getStore().load({
					                params: {
					                    vp_linea:vp_linea,vp_prov_codigo:0
					                },
					                callback:function(){
					                }
					            });
			                }
			            });
            		break;
            		case 'B':
            			var vp_linea = Ext.getCmp(pickup_service.id+'-_linea_filtro').getValue();
            			Ext.getCmp(pickup_service.id+'-shipper-filtro').getStore().removeAll();
		            	Ext.getCmp(pickup_service.id+'-shipper-filtro').getStore().load({
			                params: {
			                    vp_linea:vp_linea
			                },
			                callback:function(){
			                }
			            });
            		break;
            	}
            },
            getReloadPersonal:function(tipo){
            	switch(tipo){
            		case 'A':
            			var prov_codigo = Ext.getCmp(pickup_service.id+'-agencia-a').getValue();
            			if(prov_codigo==''|| prov_codigo==0 || prov_codigo==null){
            				return;
            			}
            			var id_area = Ext.getCmp(pickup_service.id+'-_area_a').getValue();
            			if(id_area==''|| id_area==0 || id_area==null){
            				return;
            			}
            			Ext.getCmp(pickup_service.id+'-_personal_-a').getStore().removeAll();
            			Ext.getCmp(pickup_service.id+'-_personal_-a').getStore().load({
			                params: {
			                    vp_prov_codigo:prov_codigo,vp_cargo:0,vp_id_area:id_area
			                },
			                callback:function(){
			                }
			            });
            		break;
            		case 'B':
            			var prov_codigo = Ext.getCmp(pickup_service.id+'-agencia-b').getValue();
            			if(prov_codigo==''|| prov_codigo==0 || prov_codigo==null){
            				return;
            			}
            			var id_area = Ext.getCmp(pickup_service.id+'-_area_b').getValue();
            			if(id_area==''|| id_area==0 || id_area==null){
            				return;
            			}
            			Ext.getCmp(pickup_service.id+'-_personal_-b').getStore().removeAll();
            			Ext.getCmp(pickup_service.id+'-_personal_-b').getStore().load({
			                params: {
			                    vp_prov_codigo:prov_codigo,vp_cargo:0,vp_id_area:id_area
			                },
			                callback:function(){
			                }
			            });
            		break;
            	}
            },
            getReloadAgenciaShipper:function(){
            	var shi_codigo = Ext.getCmp(pickup_service.id+'-shipper').getValue();
            	Ext.getCmp(pickup_service.id+'-cc-a').getStore().removeAll();
    			Ext.getCmp(pickup_service.id+'-cc-a').getStore().load({
	                params: {
	                    vp_shi_codigo:shi_codigo
	                },
	                callback:function(){
	                	Ext.getCmp(pickup_service.id+'-contacto_-o-a').getStore().removeAll();
		    			Ext.getCmp(pickup_service.id+'-contacto_-o-a').getStore().load({
			                params: {
			                    vp_shi_codigo:shi_codigo,vp_id_agencia:0
			                },
			                callback:function(){
			                }
			            });
	                }
	            });
            },
            getReloadContactos:function(tipo){
            	switch(tipo){
            		case 'A':
            			var shi_codigo = Ext.getCmp(pickup_service.id+'-shipper').getValue();
            			var id_agencia = Ext.getCmp(pickup_service.id+'-cc-a').getValue();
            			Ext.getCmp(pickup_service.id+'-contacto_-a').getStore().removeAll();
            			Ext.getCmp(pickup_service.id+'-contacto_-a').getStore().load({
			                params: {
			                    vp_shi_codigo:shi_codigo,vp_id_agencia:id_agencia
			                },
			                callback:function(){
			                }
			            });
            		break;
            		case 'B':
            			var shi_codigo = Ext.getCmp(pickup_service.id+'-shipper').getValue();
            			var id_agencia = Ext.getCmp(pickup_service.id+'-cc-b').getValue();
            			Ext.getCmp(pickup_service.id+'-contacto_-b').getStore().removeAll();
            			Ext.getCmp(pickup_service.id+'-contacto_-b').getStore().load({
			                params: {
			                    vp_shi_codigo:shi_codigo,vp_id_agencia:id_agencia
			                },
			                callback:function(){
			                }
			            });
            		break;
            	}
            },
            getReloadHourList:function(){
            	switch(pickup_service.pickup.tipo){
					case 0:
						var fecha_inicio = Ext.getCmp(pickup_service.id+'-d-fecha-inicio').getRawValue();
						var fecha_fin=fecha_inicio;
					break;
					case 2:
						var fecha_inicio = Ext.getCmp(pickup_service.id+'-f-fecha-inicio').getRawValue();
						var fecha_fin = Ext.getCmp(pickup_service.id+'-f-fecha-fin').getRawValue();
					break;
				}
            	Ext.getCmp(pickup_service.id+'-grid_diario').getStore().removeAll();
    			Ext.getCmp(pickup_service.id+'-grid_diario').getStore().load({
	                params: {
	                    vp_srec_id:pickup_service.pickup.srec_id,vp_fecha_inicio:fecha_inicio,vp_fecha_fin:fecha_fin
	                },
	                callback:function(){
	                }
	            });
            },
            getCancelFrom:function(){
            	if(pickup_service.pickup.srec_id==0){
            		pickup_service.setClear();
            		pickup_service.getAddRequest();
            		pickup_service.getReloadRequest();
            	}else{
	            	global.Msg({
		                msg: '¿Realmente deseas cancelar el proceso?',
		                icon: 2,
		                buttons: 3,
		                fn: function(btn){
		                    if (btn == 'yes'){
				            	pickup_service.setCancelFrom({srec_id:pickup_service.pickup.srec_id,tipo:'A'});
				            }
				        }
				    });
				}
            },
            getCancelFromR:function(record){
            	global.Msg({
	                msg: '¿Realmente deseas anular el requerimiento?',
	                icon: 2,
	                buttons: 3,
	                fn: function(btn){
	                    if (btn == 'yes'){
			            	pickup_service.setCancelFrom(record);
			            }
			        }
			    });
            },
            setCancelFrom:function(record){
            	Ext.Ajax.request({
					url:pickup_service.url+'get_scm_dispatcher_anular_solicitud/',
					params:{
						vp_srec_id:record.srec_id
					},
					success:function(response,options){
						var res = Ext.decode(response.responseText);
						//console.log(res);
						try{
							if (parseInt(res.data[0].error_sql)==0){
								if(pickup_service.pickup.srec_id!=0){
									global.Msg({
										msg:res.data[0].error_info,
										icon:1,
										buttons:1,
										fn:function(btn){
											pickup_service.setClear();
											if(record.tipo=='A'){
												pickup_service.getAddRequest();
												pickup_service.getReloadRequest();
											}
										}
									});
								}else{
									pickup_service.setClear();
								}
							}else{
								global.Msg({
									msg:res.data[0].error_info,
									icon:0,
									buttons:1,
									fn:function(btn){
									}
								});
							}
						}catch(e){

						}
					}
				});
            },
            getCloseFrom:function(){
            	pickup_service.setClear();
            	pickup_service.getAddRequest();
            },
            getChangeCmpItems:function(){
            	var origen = parseInt(Ext.getCmp(pickup_service.id+'-rbtn-group-origen').getValue()._rbtn_a);
            	var destino = parseInt(Ext.getCmp(pickup_service.id+'-rbtn-group-destino').getValue()._rbtn_b);
            	var linea = parseInt(Ext.getCmp(pickup_service.id+'-_linea_').getValue());
            	var shi_codigo = Ext.getCmp(pickup_service.id+'-shipper').getValue();
            	var codsuc=0;
            	switch(origen){
					case 1:
						try{
							codsuc = Ext.getCmp(pickup_service.id+'-agencia-a').getSelection().get('prov_main');
						}catch(e){codsuc = Ext.getCmp(pickup_service.id+'-agencia-a').getValue();}
					break;
					case 2:
						try{
							codsuc = Ext.getCmp(pickup_service.id+'-cc-a').getSelection().get('prov_codigo');
						}catch(e){}
					break;
					case 3:
						//Ext.getCmp(pickup_service.id+'-origen').setChangeEvent();
						codsuc = pickup_service.apiEvent.o_prov_codigo;
					break;
				}
				codsuc=(codsuc!=null)?codsuc:0;
				switch(destino){
                	case 1:
                		Ext.getCmp(pickup_service.id+'-agencia-b').setValue(null);
                    	Ext.getCmp(pickup_service.id+'-agencia-b').getStore().removeAll();
                    	if(codsuc!=null || codsuc!=0){
                    		Ext.getCmp(pickup_service.id+'-agencia-b').getStore().load({
                            	params:{vp_linea:linea,vp_prov_codigo:codsuc},
                            	callback:function(){
                            		var count = Ext.getCmp(pickup_service.id+'-agencia-b').getStore().getCount();
                            		if(count==1)Ext.getCmp(pickup_service.id+'-agencia-b').setValue(codsuc);
                            		Ext.getCmp(pickup_service.id+'-contacto_-b').setValue(null);
                            		Ext.getCmp(pickup_service.id+'-contacto_-b').getStore().removeAll();
                            	}
                            });
                    	}else{
                    		Ext.getCmp(pickup_service.id+'-agencia-b').setValue(null);
                    	}
                    	Ext.getCmp(pickup_service.id+'-_personal_-b').setValue(null);
                    	Ext.getCmp(pickup_service.id+'-_personal_-b').getStore().removeAll();
                    	Ext.getCmp(pickup_service.id+'-_area_b').setValue(null);
                	break;
                	case 2:
                		Ext.getCmp(pickup_service.id+'-cc-b').setValue(null);
                		Ext.getCmp(pickup_service.id+'-cc-b').getStore().removeAll();
                		if(codsuc!=null || codsuc!=0){
                            Ext.getCmp(pickup_service.id+'-cc-b').getStore().load({
                            	params:{vp_shi_codigo:shi_codigo,vp_prov_codigo:codsuc},
                            	callback:function(){
                            		Ext.getCmp(pickup_service.id+'-contacto_-b').setValue(null);
                            		Ext.getCmp(pickup_service.id+'-contacto_-b').getStore().removeAll();
                            	}
                            });
                        }
                	break;
                	case 3:
                	break;
                }
            },
            getChangeEventOrigin:function(prov_codigo,api){
            	pickup_service.apiEvent.o_ciu_id=api[0].ciu_id;
            	pickup_service.apiEvent.o_prov_codigo=prov_codigo;
            	pickup_service.getChangeCmpItems();
            },
            getChangeEventDestination:function(prov_codigo,api){
            	pickup_service.apiEvent.d_ciu_id=api[0].ciu_id;
            	pickup_service.apiEvent.d_prov_codigo=prov_codigo;
            	//pickup_service.getChangeCmpItems();
            }
		}
		Ext.onReady(pickup_service.init,pickup_service);
	}else{
		tab.setActiveTab(pickup_service.id+'-tab');
	}
</script>