<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('retorno_dinero-tab')){
		var retorno_dinero = {
			id:'retorno_dinero',
			id_menu:'<?php echo $p["id_menu"];?>',
			url: '/gestion/cobranza/',
			id_grupo:0,
			init:function(){
				Ext.define('retorno_dinero_grid_scaneo',{
					extend: 'Ext.data.Model',
					fields:[
						/*{name:'shipper',type:'string'},
						{name:'producto',type:'string'},
						{name:'guia',type:'string'},
						{name:'fecha_ao',type:'string'},
						{name:'fecha_ld',type:'string'},
						{name:'moneda',type:'string'},
						{name:'importe_sol',type:'number'},
						{name:'importe_dol',type:'number'},
						{name:'id_grupo',type:'int'},
						{name:'id_data',type:'int'},*/

						{name:'guia',type:'int'},
						{name:'shipper',type:'string'},
						{name:'cliente',type:'string'},
						{name:'direccion',type:'string'},
						{name:'fecha_ao',type:'string'},
						{name:'fecha_ld',type:'string'},
						{name:'man_id',type:'int'},
						{name:'moneda',type:'string'},
						{name:'importe_sol',type:'number'},
						{name:'importe_dol',type:'number'},
						{name:'id_grupo',type:'int'},
						{name:'id_data',type:'int'},
						{name:'chk',type:'boolean'},
						{name:'light',type:'string'},
						
					]
				});

				var store_grid_scaneo = Ext.create('Ext.data.Store',{
					model:'retorno_dinero_grid_scaneo',
					proxy:{
						type:'ajax',
						url:retorno_dinero.url+'scm_scm_cod_rendir_ge_personal/',
						reader:{
							type:'json',
							rootProperty:'data'
						}
					}
				});

				var panel = Ext.create('Ext.form.Panel',{
					id:retorno_dinero.id+'-panel',
					border:false,
					//height:'100%',
					bodyStyle: 'background: transparent',
					layout:'border',
					items:[
							{
								region:'north',
								height:170,
								layout:'column',
								defaults:{
									padding:'7 8 0 0',
									
								},
								border:false,
								tbar:[	'Courier:',
										{
											xtype:'combo',
											id:retorno_dinero.id+'-per_id',
											store:Ext.create('Ext.data.Store',{
												fields:[
													{name:'nombre', type:'string'},
													{name:'per_id' , type:'string'}
												],
												proxy:{
													type:'ajax',
													url:retorno_dinero.url+'scm_usr_sis_personals/',
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
												minWidth:380
											},
											width:340,
											forceSelection:true,
											selectOnFocus:true,
											//allowBlank:false,
											emptyText:'[ Seleccione ]',
											listeners:{
												afterrender:function(obj){
													obj.getStore().load({
														params:{vp_id_cargo:'22,15',vp_id_area:''},
														callback:function(){
														}
													});
												},
												select:function(obj){
													var cbo = Ext.getCmp(retorno_dinero.id+'-med_pago').getRawValue();;
													//console.log(cbo);
													if (cbo.trim() !=''){
					                            		retorno_dinero.get_montos();
					                            		retorno_dinero.get_ge_personal();
													}
					                            }
											}
										},
										'Tipo de Cobranza',
										{
											xtype:'combo',
											allowBlank: false,
											id:retorno_dinero.id+'-med_pago',
											columnWidth:0.5,
				            				store: Ext.create('Ext.data.Store',{
					                            fields:[
					                                {name: 'descripcion', type: 'string'},
					                                {name: 'id_elemento', type: 'int'},
					                                {name: 'des_corto', type: 'string'}
					                            ],
					                            proxy:{
					                                type: 'ajax',
					                                url: retorno_dinero.url + 'get_scm_tabla_detalles/',
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
					                            minWidth: 200
					                        },
					                        width: 200,
					                        forceSelection: true,
					                        emptyText: '[ Seleccione ]',
					                        listeners:{
					                            afterrender: function(obj, e){
					                                obj.getStore().load({
					                                    params:{
					                                        vp_tab_id: 'COD',
					                                        vp_shipper: 0
					                                    },
					                                    callback: function(){
					                                        //obj.setValue(0);
					                                    }
					                                });
					                            },
					                            select:function(obj){
					                            	retorno_dinero.get_montos();
					                            	retorno_dinero.get_ge_personal();
					                            }
					                        }
										},
										{
											text: '',
											id:retorno_dinero.id+'-save',
											tooltip:'Grabar la Rendicion',
											icon: '/images/icon/save.png',
											disabled:true,
											listeners:{
												beforerender: function(obj, opts){
					                                global.permisos({
					                                    id_serv: 109, 
					                                    id_btn: obj.getId(), 
					                                    id_menu: retorno_dinero.id_menu,
					                                    fn: ['retorno_dinero.save']
					                                });
					                            },
												click:function(obj){
													retorno_dinero.save();
												}
											}
										},
										{
											text:'',
											id:retorno_dinero.id+'-cancelar',
											tooltip:'Anula la rendicion actual',
											icon: '/images/icon/close.png',
											disabled:true,
											listeners:{
												click:function(obj){
													retorno_dinero.cancelar();
												}
											}

										},
										{
											text:'',
											id:retorno_dinero.id+'-back',
											tooltip:'Regresar al Menu Anterior',
											icon: '/images/icon/get_back.png',
											listeners:{
												click:function(obj){
													Ext.getCmp(retorno_dinero.id+'-tab').close();
													Ext.getCmp(cobranza.id+'-tab').enable();
													tab.setActiveTab(cobranza.id+'-tab');
													retorno_dinero.closeUp();
												}
											}
										},
								],
								items:[
										{
											xtype:'fieldset',
											columnWidth:1,
											layout:'column',
											margin:'10 10 10 10',
											defaults:{
												border:false
											},
											items:[
												
												{
													xtype:'fieldset',
													layout:'column',
													defaults:{
														padding:'0 0 5 0'
													},
													columnWidth:0.25,
													items:[
															{
																xtype:'textfield',
																id:retorno_dinero.id+'-tot-ge',
																value:'0',
																readOnly:true,
																margin:'20 0 0 0',
																columnWidth:1,
																labelWidth:90,
																fieldLabel:'GE por Rendir'
															},
															{
																xtype:'textfield',
																id:retorno_dinero.id+'-ge-rendido',
																fieldStyle: 'font-weight: bold;',
																labelStyle: 'font-weight: bold;',
																readOnly:true,
																value:'0',
																columnWidth:1,
																labelWidth:90,
																fieldLabel:'GE Rendido'
															},
															{
																xtype:'textfield',
																id:retorno_dinero.id+'ge-pendiente',
																readOnly:true,
																columnWidth:1,
																labelWidth:90,
																fieldLabel:'GE Pendiente'
															},
													]
												},
												{
													xtype:'fieldset',
													layout:'column',
													defaults:{
														padding:'0 0 5 0'
													},
													columnWidth:0.25,
													items:[
															{
																xtype:'label',
																margin:'0 0 0 90',
																columnWidth:1,
																text:'Moneda Local (S/.)',
																style : 'font-weight: bold;text-decoration: underline;',
															},
															{
																xtype:'textfield',
																id:retorno_dinero.id+'-monto-soles',
																value:'0.00',
																readOnly:true,
																columnWidth:1,
																labelWidth:105,
																fieldLabel:'Monto por Rendir'
															},
															{
																xtype:'textfield',
																id:retorno_dinero.id+'-rindiendo-soles',
																fieldStyle: 'font-weight: bold;',
																labelStyle: 'font-weight: bold;',
																readOnly:true,
																value:'0.00',
																columnWidth:1,
																labelWidth:105,
																fieldLabel:'Monto Rindiendo'
															},
															{
																xtype:'textfield',
																id:retorno_dinero.id+'-pen-soles',
																readOnly:true,
																columnWidth:1,
																labelWidth:105,
																fieldLabel:'Monto Pendiente'
															}
													]
												},
												{
													xtype:'fieldset',
													layout:'column',
													defaults:{
														padding:'0 0 5 0'
													},
													columnWidth:0.25,
													items:[
															{
																xtype:'label',
																margin:'0 0 0 90',
																columnWidth:1,
																text:'Moneda Extranjera ($.)',
																style : 'font-weight: bold;text-decoration: underline;',
															},
															{
																xtype:'textfield',
																id:retorno_dinero.id+'-monto-dolares',
																value:'0.00',
																readOnly:true,
																columnWidth:1,
																labelWidth:105,
																fieldLabel:'Monto por Rendir'
															},
															{
																xtype:'textfield',
																id:retorno_dinero.id+'-rindiendo-dolares',
																fieldStyle: 'font-weight: bold;',
																labelStyle: 'font-weight: bold;',
																readOnly:true,
																value:'0.00',

																columnWidth:1,
																labelWidth:105,
																fieldLabel:'Monto Rindiendo'
															},
															{
																xtype:'textfield',
																id:retorno_dinero.id+'-pen-dolare',
																readOnly:true,
																columnWidth:1,
																labelWidth:105,
																fieldLabel:'Monto Pendiente'
															},
													]
												},
												{
													xtype:'fieldset',
													layout:'column',
													defaults:{
														padding:'30 0 0 0'
													},
													columnWidth:0.25,
													items:[
															{
																xtype:'textfield',
																id:retorno_dinero.id+'-barra',
																fieldStyle: 'font-weight: bold; height:30;font-size: 16pt; ',
																labelStyle: 'font-weight: bold; height:30;font-size: 10pt;',
																columnWidth:1,
																height:40,
																labelWidth:35,
																fieldLabel:'Cod. Barra',
																readOnly:true,
																enableKeyEvents: true,
																listeners:{
																	keypress:function(obj,e,opts){
																		if (e.getKey() == 13){
																			retorno_dinero.get_barra(obj.getValue());
																		}
																	},
																	keyup:function( obj, e, eOpts ){
																		obj.setValue(obj.getValue().toUpperCase());
																	},
																	focus:function(obj,e,opts){

																	}
																}
															},
															
													]
												}
											]
										},

								]
							},
							{
								region:'center',
								padding:'10 0 0 0',
								//padding:'0 30 0 30',
								border:false,
								layout:'fit',
								items:[
										{
											xtype:'grid',
											id:retorno_dinero.id+'-grid-scaneo',
											height:320,
											columnLines: true,
											store:store_grid_scaneo,
											features: [
					                            {
					                                ftype: 'summary',
					                                dock: 'bottom',
					                            }
					                        ],
											
											columns:{
												items:[
														{
															text:'',
															dataIndex:'light',
															width:30,
															renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
																if (value == 'V'){
																	return '<img src="/images/icon/green_light.png"/>';	
																}else if (value == 'A'){
																	return '<img src="/images/icon/yellow_light.png"/>';	
																}else if (value == 'R'){
																	return '<img src="/images/icon/red_light.png"/>';	
																}
																
															},
														},
														{
															text:'N° GE',
															dataIndex:'guia',
															width:100
														},
														{
															text:'Shipper',
															dataIndex:'shipper',
															flex:1.5
														},
														{
															text:'Cliente',
															dataIndex:'cliente',
															flex:1.5,
														},
														{
															text:'Dirección',
															dataIndex:'direccion',
															flex:2,
														},
														{
															text:'Fecha. de GE',
															dataIndex:'fecha_ao',
															width:90
														},
														{
															text:'Fecha. Ruta',
															dataIndex:'fecha_ld',
															width:90
														},
														{
															text:'Moneda',
															dataIndex:'moneda',
															width:60,
															summaryRenderer: function(value, summaryData, dataIndex, metaData){
																return '<div style="font-weight: bold;text-align:right;">Totales:</div>';
															}

														},
														{
															text:'Importe S/',
															dataIndex:'importe_sol',
															summaryType: 'sum',
															align:'right',
															flex:1,
															renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
																return cobranza.formatoNumero(value,2);
															},
															summaryRenderer: function(value, summaryData, dataIndex, metaData){
																var grid = Ext.getCmp(retorno_dinero.id+'-grid-scaneo');
																var suma = 0.00;
																var sol1 = 0.00;
																var sol3 = 0.00;
																var contador = 0;
																
																if (grid.getStore().getCount() > 0){
																	for(var i = 0; i < grid.getStore().getCount(); ++i){
																		var rec = grid.getStore().getAt(i);
																		if (rec.get('chk')){
																			suma = suma + parseFloat(rec.get('importe_sol').toFixed(2));
																			contador = contador+1;
																		}
																		
																	}
																}
																
																Ext.getCmp(retorno_dinero.id+'-ge-rendido').setValue(contador);

																var tot_ge = Ext.getCmp(retorno_dinero.id+'-tot-ge').getValue();
																var ge_rendido = Ext.getCmp(retorno_dinero.id+'-ge-rendido').getValue();
																var ge_pendiente = tot_ge - ge_rendido;

																Ext.getCmp(retorno_dinero.id+'ge-pendiente').setValue(ge_pendiente);


																sol1 = Ext.getCmp(retorno_dinero.id+'-monto-soles').getValue();
																sol3 = parseFloat(sol1) - suma;
																Ext.getCmp(retorno_dinero.id+'-pen-soles').setValue(sol3.toFixed(2));

																Ext.getCmp(retorno_dinero.id+'-rindiendo-soles').setValue(suma.toFixed(2));
																//return suma.toFixed(2);//Ext.util.Format.number(suma, '###,###.##');
																return '<div style="font-weight: bold;">'+cobranza.formatoNumero(suma,2)+'</div>';
																
						                                    }
														},
														{
															text:'Importe $',
															dataIndex:'importe_dol',
															summaryType: 'sum',
															align:'right',
															flex:1,
															summaryRenderer: function(value, summaryData, dataIndex, metaData){
																var grid = Ext.getCmp(retorno_dinero.id+'-grid-scaneo');
																var suma = 0.00;
																var dol1 = 0.00;
																var dol3 = 0.00;
																if (grid.getStore().getCount() > 0){
																	for(var i = 0; i < grid.getStore().getCount(); ++i){
																		var rec = grid.getStore().getAt(i);
																		if (rec.get('chk')){
																			suma = suma + parseFloat(rec.get('importe_dol').toFixed(2));
																		}
																		
																	}
																}
																dol1 = Ext.getCmp(retorno_dinero.id+'-monto-dolares').getValue();
																dol3 = parseFloat(dol1) - suma;
																Ext.getCmp(retorno_dinero.id+'-pen-dolare').setValue(dol3.toFixed(2));

																Ext.getCmp(retorno_dinero.id+'-rindiendo-dolares').setValue(suma.toFixed(2));
																//return suma.toFixed(2);//Ext.util.Format.number(suma, '###,###.##');
																return '<div style="font-weight: bold;">'+cobranza.formatoNumero(suma,2)+'</div>';
						                                    }
														},
														{
															xtype:'checkcolumn',
															dataIndex:'chk',
															summaryType: '',
															width:30,
															renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
					                        					var setDisable = record.get('disabled');
												                var cssPrefix = Ext.baseCSSPrefix,
												                //cls = cssPrefix + 'grid-checkcolumn';
												                cls = cssPrefix + 'grid-checkcolumn';
												                    
												                if (/*this.disabled || */ setDisable == true ) {
												                    metaData.tdCls += ' ' + this.disabledCls;
												                }
												                if (value) {
												                    cls += ' ' + cssPrefix + 'grid-checkcolumn-checked';
												                }
												                return '<img class="' + cls + '" src="' + Ext.BLANK_IMAGE_URL + '"/>';
												            },
															listeners:{
																checkchange:function(value, rowIndex, checked, eOpts){
																	var grid = Ext.getCmp(retorno_dinero.id+'-grid-scaneo');
																	var rec = grid.getStore().getAt(rowIndex);
																	var id_data = rec.get('id_data');
																	console.log(id_data);
																	var estado;
																	if (checked){
																		estado = 1;
																	}else{
																		estado = 0;
																	}
																	retorno_dinero.setEstado(id_data,estado)
																},
																beforecheckchange:function(checkcolumn, rowIndex, checked, eOpts) {
													                var row = this.getView().getRow(rowIndex),
													                    record = this.getView().getRecord(row);
													                    if (record.get('disabled') == false){
													                    	return true;
													                    }else{
													                    	return false;
													                    }
													            }
															}
														}
												],
												defaults:{
					                                menuDisabled: true
					                            }
											},
											disableSelection:true,
											viewConfig: {
					                            //stripeRows: true,
					                            //enableTextSelection: false,
					                            //markDirty: false,
					                            getRowClass: function(record, rowIndex, rowParams, store){				            					
					            					return record.get('chk') != false ? 'row-error' : '';
					            				}
					                        },
					                        trackMouseOver: false,

										}
								]
								//layout:'fit'
							}
					]
					
				});
				tab.add({
					id:retorno_dinero.id+'-tab',
					title:'Descaga de Moneda',
					border:false,
					autoScroll: true,
					closable: false,
					layout:{
	                    type: 'fit'
	                },
	                items:[
	                	panel
	                ],
	                listeners:{
	                	beforerender: function(obj, opts){
	                		//global.state_item_menu(retorno_dinero.id_menu, true);	
	                    },
	                    afterrender: function(obj, e){
	                    	tab.setActiveTab(obj);	
	                    	retorno_dinero.getPendiente();
	                		//global.state_item_menu_config(obj,retorno_dinero.id_menu);	
	                    },
	                    beforeclose: function(obj, opts){
	                        //global.state_item_menu(retorno_dinero.id_menu, false);
	                    }
	                }
				}).show();
				/*Ext.create('Ext.window.Window',{
					id:retorno_dinero.id+'-win',
					cls:'popup_show',
					height:500,
					width:1000,
					resizable:false,
					modal:true,
					layout:{
						type:'fit'
					},
					//close:false,
					header:false,
					items:[
							{
								xtype:'uePanel',
								title:'Cobranza',
								height:'100%',
								color:'x-color-top',
								legend:'ss',
								defaults:{
									border:false
								},
								items:[panel],
							}
					]
				}).show().center();*/
			},
			get_montos:function(){
				var vp_per_id = Ext.getCmp(retorno_dinero.id+'-per_id').getValue();
				var vp_med_pago = Ext.getCmp(retorno_dinero.id+'-med_pago').getValue();
				Ext.Ajax.request({
					url:retorno_dinero.url+'scm_scm_cod_rendir_datos_personal/',
					params:{vp_per_id:vp_per_id,vp_med_pago:vp_med_pago},
					success: function(response, options){
						var res = Ext.JSON.decode(response.responseText).data[0];
						Ext.getCmp(retorno_dinero.id+'-barra').setReadOnly(false);
						Ext.getCmp(retorno_dinero.id+'-tot-ge').setValue(res.tot_ge);
						Ext.getCmp(retorno_dinero.id+'-monto-soles').setValue(res.monto_sol);
						Ext.getCmp(retorno_dinero.id+'-monto-dolares').setValue(res.monto_dol);
						Ext.getCmp(retorno_dinero.id+'ge-pendiente').setValue(res.tot_ge);
						Ext.getCmp(retorno_dinero.id+'-pen-soles').setValue(res.monto_sol);
						Ext.getCmp(retorno_dinero.id+'-pen-dolare').setValue(res.monto_dol);
					}
				});
			},
			get_ge_personal:function(){
				var mask = new Ext.LoadMask(Ext.getCmp(inicio.id+'-tabContent'),{
					msg:'Cargando datos....'
				});
				var vp_per_id = Ext.getCmp(retorno_dinero.id+'-per_id').getValue();
				var vp_med_pago = Ext.getCmp(retorno_dinero.id+'-med_pago').getValue();
				mask.show();
				Ext.getCmp(retorno_dinero.id+'-grid-scaneo').getStore().load({
					params:{vp_per_id:vp_per_id,vp_med_pago:vp_med_pago},
					callback:function(){
						mask.hide();
					}
				});
			},
			get_barra:function(gui_numero){
				var vp_per_id = Ext.getCmp(retorno_dinero.id+'-per_id').getValue();
				var vp_med_pago = Ext.getCmp(retorno_dinero.id+'-med_pago').getValue();
				var vp_id_grupo = retorno_dinero.id_grupo;
				Ext.Ajax.request({
					url:retorno_dinero.url+'scm_scm_cod_rendir_escaneo/',
					params:{vp_per_id:vp_per_id,vp_med_pago:vp_med_pago,vp_gui_numero:gui_numero.trim(),vp_id_grupo:vp_id_grupo},
					success: function(response, options){
						var res = Ext.JSON.decode(response.responseText).data[0];
						//res.chk = true;
						if (res.error_sql < 0 ){
							global.Msg({
								msg:res.error_info,
								icon:0,
								buttosn:1,
								fn:function(btn){
								}
							});
						}else{
							var grid = Ext.getCmp(retorno_dinero.id+'-grid-scaneo');
							var store = grid.getStore();
							store.each(function(rec,idx){
								if (rec.get('guia') == res.guia){
									rec.set('id_data',res.id_data);
									rec.set('id_grupo',res.id_grupo);
									rec.set('disabled',false);
									rec.set('chk',true);
									rec.commit();	
								}
							});
							grid.getView().refresh();
							retorno_dinero.id_grupo = res.id_grupo;
							/*var arrayData = [];
							if (grid.getStore().getCount() > 0){
								for(var i = 0; i < grid.getStore().getCount(); ++i){
									var rec = grid.getStore().getAt(i);
									arrayData.push(rec);
								}
							}
							arrayData.push(res);
							grid.getStore().loadData(arrayData);
							grid.getView().refresh();*/

							/***************estado********************/	
							Ext.getCmp(retorno_dinero.id+'-save').enable();
							Ext.getCmp(retorno_dinero.id+'-cancelar').enable();
							Ext.getCmp(retorno_dinero.id+'-barra').setValue('');
							Ext.getCmp(retorno_dinero.id+'-back').disable();
							Ext.getCmp(retorno_dinero.id+'-barra').focus(true, 100);
							retorno_dinero.setCombo(true);
						}
					}
				});
			},
			update_montos:function(rec){
				array = rec.data;
				if (array.chk){
					//checked
				}else{
					// no checked
				}
			},
			save:function(){
				var vp_id_grupo = retorno_dinero.id_grupo;
				var vp_per_id = Ext.getCmp(retorno_dinero.id+'-per_id').getValue();

				Ext.Ajax.request({
					url:retorno_dinero.url+'scm_cod_rendir_grabar/',
					params:{vp_id_grupo:vp_id_grupo,vp_per_id:vp_per_id},
					success: function(response, options){
						var res = Ext.JSON.decode(response.responseText).data[0];
						if (res.error_sql < 0 ){
							global.Msg({
								msg:res.error_info,
								icon:0,
								buttosn:1,
								fn:function(btn){
								}
							});
						}else{
							global.Msg({
								msg:res.error_info,
								icon:1,
								buttosn:1,
								fn:function(btn){
									retorno_dinero.resetAll();
									Ext.getCmp(retorno_dinero.id+'-tab').close();
									Ext.getCmp(cobranza.id+'-tab').enable();
									tab.setActiveTab(cobranza.id+'-tab');
									retorno_dinero.closeUp();
								}
							});
						}
					}

				});
			},
			resetAll:function(){
				var grid = Ext.getCmp(retorno_dinero.id+'-grid-scaneo');
				grid.getStore().removeAll();
				retorno_dinero.id_grupo = 0;
				retorno_dinero.setCombo(false);
				Ext.getCmp(retorno_dinero.id+'-per_id').setValue('');
				Ext.getCmp(retorno_dinero.id+'-med_pago').setValue('');
				Ext.getCmp(retorno_dinero.id+'-tot-ge').setValue('');
				Ext.getCmp(retorno_dinero.id+'-monto-soles').setValue('');
				Ext.getCmp(retorno_dinero.id+'-monto-dolares').setValue('');

				Ext.getCmp(retorno_dinero.id+'ge-pendiente').setValue('');
				Ext.getCmp(retorno_dinero.id+'-pen-soles').setValue('');
				Ext.getCmp(retorno_dinero.id+'-pen-dolare').setValue('');

				Ext.getCmp(retorno_dinero.id+'-ge-rendido').setValue('');
				Ext.getCmp(retorno_dinero.id+'-rindiendo-soles').setValue('');
				Ext.getCmp(retorno_dinero.id+'-rindiendo-dolares').setValue('');
				Ext.getCmp(retorno_dinero.id+'-save').disable();
				Ext.getCmp(retorno_dinero.id+'-back').enable();
				Ext.getCmp(retorno_dinero.id+'-cancelar').disable();
			},
			closeUp:function(){
				cobranza.buscar();
				Ext.getCmp(cobranza.id+'-grid3').setVisible(false);
				Ext.getCmp(cobranza.id+'lbl3').setHtml('<a name="cobranza-pend"></a>');
			},
			setCombo:function(val){
				Ext.getCmp(retorno_dinero.id+'-per_id').setReadOnly(val);
				Ext.getCmp(retorno_dinero.id+'-med_pago').setReadOnly(val);
			},
			setEstado:function(vp_doc_id,vp_estado){
				var mask = new Ext.LoadMask(Ext.getCmp(inicio.id+'-tabContent'),{
					msg:'Actualizando Datos....'
				});
				mask.show();
				Ext.Ajax.request({
					url:retorno_dinero.url+'scm_scm_cod_rendir_escaneo_estado/',
					params:{vp_doc_id:vp_doc_id,vp_estado:vp_estado},
					success:function(response, options){
						var res = Ext.JSON.decode(response.responseText).data[0];
						if (res.error_sql < 0){
							global.Msg({
								msg:res.error_info,
								icon:0,
								buttosn:1,
								fn:function(btn){
									//retorno_dinero.setCombo(false);
									//retorno_dinero.id_grupo = 0;//
								}
							});
						}else{
							console.log(res.error_info);
							mask.hide();
							/*global.Msg({
								msg:res.error_info,
								icon:1,
								buttosn:1,
								fn:function(btn){
								}
							});*/
						}
					}
				});
			},
			getPendiente:function(){
				var mask = new Ext.LoadMask(Ext.getCmp(inicio.id+'-tabContent'),{
					msg:'Verificando Rendiciones Pendientes....'
				});
				var mask2 = new Ext.LoadMask(Ext.getCmp(inicio.id+'-tabContent'),{
					msg:'Recuperando Pendientes....'
				});
				mask.show();
				Ext.Ajax.request({
					url:retorno_dinero.url+'scm_scm_cod_rendir_smart_user/',
					params:{},
					success:function(response, options){
						var res = Ext.JSON.decode(response.responseText).data[0];
						mask.hide();
						if (res.error_sql < 0){
							global.Msg({
								msg:'Usted Tiene Rendiciones Pendientes',
								icon:0,
								buttosn:1,
								fn:function(btn){
									mask2.show();
									retorno_dinero.id_grupo = res.id_grupo;
									Ext.getCmp(retorno_dinero.id+'-per_id').setValue(res.per_id);
									Ext.getCmp(retorno_dinero.id+'-med_pago').setValue(res.tpm_id);
									
									Ext.getCmp(retorno_dinero.id+'-per_id').setReadOnly(true);
									Ext.getCmp(retorno_dinero.id+'-med_pago').setReadOnly(true);
									retorno_dinero.get_montos();
									//retorno_dinero.get_ge_personal();
									Ext.getCmp(retorno_dinero.id+'-save').enable();
									Ext.getCmp(retorno_dinero.id+'-barra').setValue('');
									Ext.getCmp(retorno_dinero.id+'-back').disable();
									Ext.getCmp(retorno_dinero.id+'-cancelar').enable();

									Ext.getCmp(retorno_dinero.id+'-grid-scaneo').getStore().load({
										params:{vp_per_id:res.per_id,vp_med_pago:res.tpm_id},
										callback:function(){
											Ext.Ajax.request({
												url:retorno_dinero.url+'scm_scm_cod_rendir_smart_detalle/',
												params:{vp_id_grupo:res.id_grupo},
												success:function(response, options){
													mask2.hide();
													var resp = Ext.JSON.decode(response.responseText).data;
													var grid = Ext.getCmp(retorno_dinero.id+'-grid-scaneo');
													var store = grid.getStore();
													//console.log(resp);
													Ext.Object.each(resp,function(index,val){
														store.each(function(rec,idx){
															if (rec.get('guia') == val.guia){
																rec.set('id_data',val.id_data);
																rec.set('id_grupo',val.id_grupo);
																rec.set('estado',val.estado);
																rec.set('disabled',false);
																rec.set('chk',parseInt(val.estado) == 1 ?true:false);
																rec.commit();	
															}
														});
													});
													grid.getView().refresh();
												}
											});
										}
									});
									/*Ext.getCmp(retorno_dinero.id+'-grid-scaneo').getStore().load({
										params:{vp_id_grupo:retorno_dinero.id_grupo},
										callback:function(){
											mask2.hide();
										}
									});*/
								}
							});
						}
					}
				});
			},
			cancelar:function(){
				if (parseInt(retorno_dinero.id_grupo)>0){
					Ext.Ajax.request({
						url:retorno_dinero.url+'scm_scm_cod_rendir_anular/',
						params:{vp_id_grupo:retorno_dinero.id_grupo},
						success:function(response, options){
							var res = Ext.JSON.decode(response.responseText).data[0];
							if (res.error_sql < 0){
								global.Msg({
									msg:res.error_info,
									icon:0,
									buttosn:1,
									fn:function(btn){
									}
								});
							}else{
								global.Msg({
									msg:res.error_info,
									icon:1,
									buttosn:1,
									fn:function(btn){
										retorno_dinero.resetAll();
									}
								});
							}
						}
					});	
				}else{
					global.Msg({
						msg:'El id_grupo '+retorno_dinero.id_grupo+' No es Válido',
						icon:0,
						buttosn:1,
						fn:function(btn){
						}
					});
				}
				
			}

		}
		Ext.onReady(retorno_dinero.init, retorno_dinero);
	}else{
		tab.setActiveTab(retorno_dinero.id+'-tab');
	}	
	
</script>