<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('trans_cod-tab')){
		var trans_cod = {
			id:'trans_cod',
			id_menu: '<?php echo $p["id_menu"];?>',
			url:'/gestion/trans_cod/',
			init:function(){
				var store = Ext.create('Ext.data.Store',{
					fields:[                                                     
					     {name: 'agencia',type: 'string'},
					     //{name: 'shipper',type: 'string'},
					     //{name: 'producto',type: 'string'},
					     {name: 'fecha',type: 'string'},
					     {name: 'banco',type: 'string'},
					     {name: 'cuenta',type: 'string'},
					     {name: 'voucher',type: 'string'},
					     {name: 'moneda',type: 'string'},
					     {name: 'importe',type: 'number'},
					     {name: 'estado',type: 'string'},
					     {name: 'id_cod',type: 'int'},
					     {name: 'tip_moneda',type: 'int'},
					     {name: 'mot_id',type: 'int'},
					     {name: 'fecha_dep',type: 'string'},
					     //{name: 'shi_id',type: 'int'},
					     {name: 'chk',type: 'boolean'},
					],
					proxy:{
						type:'ajax',
						url:trans_cod.url+'scm_scm_cod_financiero_panel/',
						reader:{
							type:'json',
							rootProperty:'data'
						}
					}
				});

				var panel = Ext.create('Ext.form.Panel',{
					id:trans_cod.id+'-panel',
					border:false,
					layout:'fit',
					defaults:{
						border:false
					},
					tbar:[
							'Operación',
							{
								xtype:'combo',
								allowBlank:false,
								labelWidth:60,
								id:trans_cod.id+'-operacion',
								store: Ext.create('Ext.data.Store',{
		                            fields:[
		                                {name: 'descripcion', type: 'string'},
		                                {name: 'id_elemento', type: 'int'},
		                            ],
		                            proxy:{
		                                type: 'ajax',
		                                url: trans_cod.url + 'get_scm_get_estado/',
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
		                            	var val = obj.getValue();
		                            	console.log(val);
		                            	if (val == 52 || val == 177){
		                            		//console.log('shipper');
		                            		Ext.getCmp(trans_cod.id+'-shipper').setValue(0);
		                            		Ext.getCmp(trans_cod.id+'-shipper').setReadOnly(true);
		                            		Ext.getCmp(trans_cod.id+'-agencia').setReadOnly(false);
		                            	}else if (val == 178 || val == 179){
		                            		//console.log('agencia');
		                            		Ext.getCmp(trans_cod.id+'-agencia').setValue(0);
		                            		Ext.getCmp(trans_cod.id+'-agencia').setReadOnly(true);
		                            		Ext.getCmp(trans_cod.id+'-shipper').setReadOnly(false);
		                            	}else{
		                            		Ext.getCmp(trans_cod.id+'-shipper').setReadOnly(false);
		                            		Ext.getCmp(trans_cod.id+'-agencia').setReadOnly(false);
		                            	}
		                            	trans_cod.buscar();
		                            	if (val == 52 || val == 177 /*|| val==178*/){
		                            		Ext.getCmp(trans_cod.id+'-grid').columns[0].setVisible(true);	
		                            		Ext.getCmp(trans_cod.id+'-grid').setVisible(true);
		                            		Ext.getCmp(trans_cod.id+'-grid2').setVisible(false);
		                            	}else if(val == 179 || val == 178){
		                            		Ext.getCmp(trans_cod.id+'-grid').setVisible(false);
		                            		Ext.getCmp(trans_cod.id+'-grid2').setVisible(true);
		                            	}
		                            	if (val == 52 /*|| val ==179*/){
		                            		Ext.getCmp(trans_cod.id+'-grid').columns[9].setVisible(false);	
		                            		Ext.getCmp(trans_cod.id+'-grid').columns[10].setVisible(false);	
		                            	}else if(val ==177){
		                            		Ext.getCmp(trans_cod.id+'-grid').columns[9].setVisible(false);
		                            		Ext.getCmp(trans_cod.id+'-grid').columns[10].setVisible(true);	
		                            	}else if(val ==178){
		                            		/*Ext.getCmp(trans_cod.id+'-grid').columns[8].setVisible(true);	
		                            		Ext.getCmp(trans_cod.id+'-grid').columns[9].setVisible(false);	*/
		                            	}


		                            	if (val == 52){
		                            		Ext.getCmp(trans_cod.id+'-estado').setValue(0);
		                            		Ext.getCmp(trans_cod.id+'-estado').setReadOnly(true);
		                            		Ext.getCmp(trans_cod.id+'-estado').setVisible(true);
		                            	}else if (val == 177){
		                            		Ext.getCmp(trans_cod.id+'-estado').setValue(0);
		                            		Ext.getCmp(trans_cod.id+'-estado').setReadOnly(false);
		                            		Ext.getCmp(trans_cod.id+'-estado').setVisible(true);
		                            	}else if (val == 178){
		                            		Ext.getCmp(trans_cod.id+'-estado').setValue(0);
		                            		Ext.getCmp(trans_cod.id+'-estado').setReadOnly(true);
		                            		Ext.getCmp(trans_cod.id+'-estado').setVisible(false);
		                            	}else if (val == 179){
		                            		Ext.getCmp(trans_cod.id+'-estado').setValue(1);
		                            		Ext.getCmp(trans_cod.id+'-estado').setReadOnly(true);
		                            		Ext.getCmp(trans_cod.id+'-estado').setVisible(false);
		                            	}
		                            	

		                            }
		                        }
							},
							{
								xtype:'combo',
								id:trans_cod.id+'-estado',
								fieldLabel:'Estado',
								labelWidth:40,
								store: Ext.create('Ext.data.Store',{
		                            fields:[
		                                {name: 'descripcion', type: 'string'},
		                                {name: 'id', type: 'int'},
		                            ],
		                            autoLoad:true,
		                            proxy:{
		                                type: 'ajax',
		                                url: trans_cod.url + 'get_estado/',
		                                reader:{
		                                    type: 'json',
		                                    root: 'data'
		                                }
		                            }
		                        }),
		                        queryMode: 'local',
		                        triggerAction: 'all',
		                        valueField: 'id',
		                        displayField: 'descripcion',
		                        listConfig:{
		                            minWidth: 150
		                        },
		                        width: 150,
		                        forceSelection: true,
		                        emptyText: '[ Seleccione ]',
		                        listeners:{
		                        	select:function(obj){
		                        	},
		                        	change:function( obj, newValue, oldValue, eOpts ){
		                        		if (newValue == 0){
		                        			Ext.getCmp(trans_cod.id+'-fini').setReadOnly(true);
		                        			Ext.getCmp(trans_cod.id+'-ffin').setReadOnly(true);
		                        			Ext.getCmp(trans_cod.id+'-fini').setValue('');
		                        			Ext.getCmp(trans_cod.id+'-ffin').setValue('');
		                        		}else{
		                        			Ext.getCmp(trans_cod.id+'-fini').setReadOnly(false);
		                        			Ext.getCmp(trans_cod.id+'-ffin').setReadOnly(false);
		                        			Ext.getCmp(trans_cod.id+'-fini').setValue(new Date());
		                        			Ext.getCmp(trans_cod.id+'-ffin').setValue(new Date());
		                        		}
		                        	}
		                        }
							},
							'Fecha',
							{
								xtype:'datefield',
								//allowBlank:false,
								id:trans_cod.id+'-fini',
								value:new Date(),
								width:90
							},
							'Al',
							{
								xtype:'datefield',
								//allowBlank:false,
								id:trans_cod.id+'-ffin',
								value:new Date(),
								width:90
							},
							'Shipper',
							{
								xtype:'combo',
								id:trans_cod.id+'-shipper',
								store:Ext.create('Ext.data.Store',{
								fields:[
										{name: 'shi_codigo', type: 'int'},
		                                {name: 'shi_nombre', type: 'string'},
		                                {name: 'shi_id', type: 'string'}
								],
								proxy:{
									type:'ajax',
									url:trans_cod.url+'get_usr_sis_shipper/',
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
									}
								}
							},
							'Agencia',
							{
								xtype:'combo',
								id:trans_cod.id+'-agencia',
								store: Ext.create('Ext.data.Store',{
		                            fields:[
		                                {name: 'prov_codigo', type: 'int'},
		                                {name: 'prov_nombre', type: 'string'}
		                            ],
		                            proxy:{
		                                type: 'ajax',
		                                url: trans_cod.url + 'get_usr_sis_provincias/',
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
		                        				//obj.setValue(prov_codigo);
		                        			}
		                        		});
		                        	}
		                        }
							},
							{
								text:'',
								id:trans_cod.id+'-buscar',
								icon: '/images/icon/search.png',
								listeners:{
								    beforerender: function(obj, opts){
		                                global.permisos({
		                                    id_serv: 114, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: trans_cod.id_menu,
		                                    fn: ['trans_cod.buscar']
		                                });
		                            },
									click:function(obj){
										trans_cod.buscar();
									}
								}
							},
							{
								text:'',
								id:trans_cod.id+'-cobrar',
								icon: '/images/icon/dolar.png',
								listeners:{
									beforerender: function(obj, opts){
		                                global.permisos({
		                                    id_serv: 113, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: trans_cod.id_menu,
		                                    fn: ['trans_cod.show_deposito']
		                                });
		                            },
									click:function(obj){
										trans_cod.show_deposito();
									}
								}
							}
					],
					items:[
							{
								xtype:'grid',
								id:trans_cod.id+'-grid',
								store:store,
								features: [
				                            {
				                                ftype: 'summary',
				                                dock: 'bottom'
				                            }
		                        ],
		                        columns:{
		                        	items:[

		                        			{
		                        				text:'Agencia',
		                        				dataIndex:'agencia',
		                        				width:100,
		                        				menuDisabled:true
		                        			},
		                        			/*{
		                        				text:'Shipper',
		                        				dataIndex:'shipper',
		                        				width:150,
		                        				hidden:true
		                        			},
		                        			{
		                        				text:'Servicio',
		                        				dataIndex:'producto',
		                        				width:150,
		                        				hidden:true
		                        			},*/
		                        			{
		                        				text:'Fecha',
		                        				dataIndex:'fecha',
		                        				width:80,
		                        				menuDisabled:true
		                        			},
		                        			{
		                        				text:'Moneda',
		                        				dataIndex:'moneda',
		                        				flex:1,
		                        				menuDisabled:true
		                        			},
		                        			{
		                        				text:'Banco',
		                        				dataIndex:'banco',
		                        				flex:1,
		                        				menuDisabled:true
		                        			},
		                        			{
		                        				text:'N° Cuenta',
		                        				dataIndex:'cuenta',
		                        				flex:1,
		                        				menuDisabled:true
		                        			},
		                        			{
		                        				text:'N° Voucher',
		                        				dataIndex:'voucher',
		                        				flex:1,
		                        				menuDisabled:true,
		                        				summaryRenderer: function(value, summaryData, dataIndex){
		                        					return '<div align="right" style="font-weight: bold;font-size: 12pt";>Monto:</div>';
		                        				}
		                        			},
		                        			{
		                        				text:'F. Depósito',
		                        				dataIndex:'fecha_dep',
		                        				width:100,
		                        				menuDisabled:true
		                        			},
		                        			{
		                        				text:'Importe',
		                        				dataIndex:'importe',
		                        				align:'right',
		                        				flex:1,
		                        				menuDisabled:true,
		                        				summaryRenderer: function(value, summaryData, dataIndex){
		                        					var grid = Ext.getCmp(trans_cod.id+'-grid');
		                        					var suma = 0;
		                        					if (grid.getStore().getCount() > 0){
		                        						for(var i = 0; i < grid.getStore().getCount(); ++i){
		                        							var rec = grid.getStore().getAt(i);
		                        							if (rec.get('chk')){
			                                     				suma = suma + parseFloat(rec.get('importe').toFixed(2));
			                                     			}
		                        						}
		                        					}

		                        					if (suma == 0){
		                        						Ext.getCmp(trans_cod.id+'-cobrar').setVisible(false);
		                        						return '';
		                        					}else{
		                        						Ext.getCmp(trans_cod.id+'-cobrar').setVisible(true);
		                        						 //Monto a Depositar: 
		                        						return '<div style="font-weight: bold;font-size: 12pt"> '+trans_cod.formatoNumero(suma,2)+'</div>';
		                        					}

		                        				}
		                        			},
		                        			{
		                        				text:'Estado',
		                        				dataIndex:'estado',
		                        				flex:1,
		                        				menuDisabled:true
		                        			},
		                        			{
		                        				xtype:'checkcolumn',
		                        				dataIndex:'chk',
		                        				width:30,
		                        				menuDisabled:true,
		                        				renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
		                        					var setDisable = record.get('disabled');
		                        					//console.log(setDisable);
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
									            	checkchange:function(checkcolumn, rowIndex, checked, eOpts) {
		                        						trans_cod.checkchange();
												    },
									            	beforecheckchange:function(checkcolumn, rowIndex, checked, eOpts) {
										                var row = this.getView().getRow(rowIndex),
										                    record = this.getView().getRecord(row);
										                    //console.log(record.get('disabled'));
										                    if (record.get('disabled') == false){
										                    	return true;
										                    }else{
										                    	return false;
										                    }
										            }
									            }
		                        			},
		                        			{
		                        				text:'Confirmar',
		                        				//dataIndex:'chk',
		                        				align:'center',
		                        				width:60,
		                        				menuDisabled:true,
		                        				renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
		                        					metaData.style = "padding: 0px; margin: 0px";
			                                        return global.permisos({
			                                            type: 'link',
			                                            id_menu: trans_cod.id_menu,
			                                            icons:[
			                                                {id_serv: 113, img: 'dolar.png', qtip: 'Click Para Confirmar el Deposito', js: 'trans_cod.show_deposito('+rowIndex+')'},
			                                            ]
			                                        });
		                        				}
		                        			}

		                        	]
		                        }

							},
							{
								xtype:'grid',
								hidden:true,
								id:trans_cod.id+'-grid2',
								store:'',
								features: [
				                            {
				                                ftype: 'summary',
				                                dock: 'bottom'
				                            }
		                        ],
		                        columns:{
		                        	items:[
		                        			{
		                        				text:'Shipper',
		                        				dataIndex:'',
		                        				//width:100,
		                        				flex:1
		                        				//menuDisabled:true
		                        			},
		                        			{
		                        				text:'Servicio',
		                        				dataIndex:'',
		                        				//width:100,
		                        				flex:1
		                        				//menuDisabled:true
		                        			},
		                        			{
		                        				text:'Moneda',
		                        				dataIndex:'',
		                        				//width:100,
		                        				flex:1
		                        				//menuDisabled:true
		                        			},
		                        			{
		                        				text:'Importe Recaudado',
		                        				dataIndex:'',
		                        				//width:100,
		                        				flex:1
		                        				//menuDisabled:true
		                        			},
		                        			{
		                        				text:'Tot. GE con COD',
		                        				dataIndex:'',
		                        				//width:100,
		                        				flex:1
		                        				//menuDisabled:true
		                        			},
		                        			{
		                        				text:'Importe GE',
		                        				dataIndex:'',
		                        				//width:100,
		                        				flex:1
		                        				//menuDisabled:true
		                        			},
		                        			{
		                        				text:'Retención/Custodia',
		                        				dataIndex:'',
		                        				//width:100,
		                        				flex:1
		                        				//menuDisabled:true
		                        			},
		                        			{
		                        				text:'Ultimo Pago',
		                        				dataIndex:'',
		                        				//width:100,
		                        				flex:1
		                        				//menuDisabled:true
		                        			},
		                        			{
		                        				text:'Importe Ult. Pago',
		                        				dataIndex:'',
		                        				//width:100,
		                        				flex:1
		                        				//menuDisabled:true
		                        			},
		                        			{
		                        				text:'',
		                        				dataIndex:'',
		                        				width:35,
		                        				//menuDisabled:true
		                        			},
		                        	]
		                        }
							}
					]
				});

				tab.add({
					id:trans_cod.id+'-tab',
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
	                        global.state_item_menu(trans_cod.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,trans_cod.id_menu);
	                    },
	                    beforeclose: function(obj, opts){
	                        global.state_item_menu(trans_cod.id_menu, false);
	                    }
	                }
				}).show();
			},
			buscar:function(){
			    
				var form = Ext.getCmp(trans_cod.id+'-panel').getForm();
				if (form.isValid()){
			        var vp_operacion = Ext.getCmp(trans_cod.id+'-operacion').getValue();
					var vp_estado = Ext.getCmp(trans_cod.id+'-estado').getValue();
					var vp_fecini = Ext.getCmp(trans_cod.id+'-fini').getRawValue();
					var vp_fecfin = Ext.getCmp(trans_cod.id+'-ffin').getRawValue();
					var vp_shipper = Ext.getCmp(trans_cod.id+'-shipper').getValue();
					var vp_provincia = Ext.getCmp(trans_cod.id+'-agencia').getValue();

					Ext.getCmp(trans_cod.id+'-grid').getStore().load({
						params:{vp_operacion:vp_operacion,vp_estado:vp_estado,vp_fecini:vp_fecini,vp_fecfin:vp_fecfin,vp_shipper:vp_shipper,vp_provincia:vp_provincia},
						callback:function(){
						}
					});	
				}else{
					global.Msg({
						msg:'Debe Seleccionar Estado',
						icon:0,
						fn:function(){
						}
					});
				}
			    
			},
			show_deposito:function(rowIndex){
				win.show({vurl: trans_cod.url + 'show_deposito/?rowIndex='+rowIndex, id_menu: trans_cod.id_menu, class: '' });
			},
			checkchange:function(){
				var grid = Ext.getCmp(trans_cod.id+'-grid')
				var store = grid.getStore();
				var suma = 0;
				var mot_id = 0;
				var tip_moneda = 0;
				var regla = 'S';
				var shi_id = 0;
				store.each(function(rec,idx){
					if (rec.get('chk') && !rec.get('disabled')){
						suma = suma + 1;
						if (suma == 1 ){
							mot_id = parseInt(rec.get('mot_id'));
							tip_moneda = parseInt(rec.get('tip_moneda'));
							if (mot_id == 178){
								regla = res.get('regla') == 'S' ? 'S':'P';//P
								shi_id = res.get('shi_id');
							}
						}
					}
				});

				store.each(function(rec,idx){
					if (suma > 0){
						if(parseInt(rec.get('mot_id')) != mot_id || parseInt(rec.get('tip_moneda')) != tip_moneda ){
							rec.set('disabled',true);	
						}
						if (mot_id == 178 && rec.get('regla') != regla && shi_id != res.get('shi_id')){
							rec.set('disabled',true);	
						}
						rec.commit();	
					}
				});

				grid.getView().refresh();
				//console.log(suma);
				if (suma == 0){
					trans_cod.buscar();
				}
			},
			getDatos:function(){
				var grid = Ext.getCmp(trans_cod.id+'-grid');
				var suma = 0;
				var arrayId_cod = [];
				var id_cod = '';
				//var tip_pago = 0;
				var shi_id = 0;
				var moneda = '';
				var tip_moneda = 0;
				if (grid.getStore().getCount() > 0){
             		for(var i = 0; i < grid.getStore().getCount(); ++i){
             			var rec = grid.getStore().getAt(i);
             			if (rec.get('chk')){
             				suma = suma + parseFloat(rec.get('importe').toFixed(2));
             				//id_cod     tip_moneda     mot_id     shi_id
             				arrayId_cod.push(rec.get('id_cod'));
             				//tip_pago = rec.get('tip_pago');
             				moneda = rec.get('moneda');
             				tip_moneda = rec.get('tip_moneda');
             				shi_id = rec.get('shi_id');

             			}
             			
             		}
             		id_cod = arrayId_cod.join(',');	
             	}
             	var datos = {
             		id_cod:id_cod,
             		//tip_pago:'ssss',//tip_pago,
             		moneda:moneda,
             		tip_moneda:tip_moneda,
             		monto:suma,
             		shi_id:shi_id
             	}
             	return datos;
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
		}
		Ext.onReady(trans_cod.init, trans_cod);
	}else{
		tab.setActiveTab(trans_cod.id+'-tab');
	}
</script>
