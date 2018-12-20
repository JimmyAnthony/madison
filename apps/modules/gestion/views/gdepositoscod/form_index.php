<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('gdepositoscod-tab')){
		var gdepositoscod = {
			id:'gdepositoscod',
			id_menu:'<?php echo $p["id_menu"];?>',
			url: '/gestion/gdepositoscod/',
			tip_pago:0,
			init:function(){
				var store_grid1 = Ext.create('Ext.data.Store',{
					fields:[
						{name: 'fecha', type: 'string'},
						{name: 'medio_pago', type: 'string'},
						{name: 'moneda', type: 'string'},
						{name: 'importe', type: 'number'},
						{name: 'banco', type: 'string'},
						{name: 'cuenta', type: 'string'},
						{name: 'fecha_deposito', type: 'string'},
						{name: 'estado', type: 'string'},
						{name: 'id_cod', type: 'int'},
						{name: 'tip_pago', type: 'int'},
						{name: 'tip_moneda', type: 'int'},
						{name: 'mot_id', type: 'int'},
						{name: 'chk', type: 'boolean'},

					],
					proxy:{
						type:'ajax',
						url:gdepositoscod.url+'scm_scm_cod_deposito_panel/',
						reader:{
							type:'json',
							rootProperty:'data'
						}
					}
				});

				var panel = Ext.create('Ext.form.Panel',{
					id:gdepositoscod.id+'-form',
					border:false,
					layout:'fit',
					defaults:{
						border:false
					},
					tbar:[
							'Agencia:',
							{
		                        xtype: 'combo',
		                        id: gdepositoscod.id + '-agencia',
		                        store: Ext.create('Ext.data.Store',{
		                            fields:[
		                                {name: 'prov_codigo', type: 'int'},
		                                {name: 'prov_nombre', type: 'string'}
		                            ],
		                            proxy:{
		                                type: 'ajax',
		                                url: gdepositoscod.url + 'get_usr_sis_provincias/',
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
		                                    }
		                                });
		                            },
		                            select:function(obj,record,options){
		                            	gdepositoscod.buscar();
		                            }
		                        }
		                    },
		                    'Fecha',
							{
								xtype:'datefield',
								width:100,
								id:gdepositoscod.id+'fini',
								value: new Date()
							},
							' Al ',
							{
								xtype:'datefield',
								width:100,
								id:gdepositoscod.id+'ffin',
								value: new Date()
							},
							'Medio de Pago',
							{
								xtype:'combo',
								allowBlank: false,
								id:gdepositoscod.id+'-med_pago',
								columnWidth:0.5,
	            				store: Ext.create('Ext.data.Store',{
		                            fields:[
		                                {name: 'descripcion', type: 'string'},
		                                {name: 'id_elemento', type: 'int'},
		                                {name: 'des_corto', type: 'string'}
		                            ],
		                            proxy:{
		                                type: 'ajax',
		                                url: gdepositoscod.url + 'get_scm_tabla_detalles/',
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
		                            }
		                        }
							},
							'Estado',
							{
								xtype:'combo',
								id:gdepositoscod.id+'-estado',
								columnWidth:0.5,
	            				store: Ext.create('Ext.data.Store',{
		                            fields:[
		                                {name: 'chk_id', type: 'int'},
		                                {name: 'mot_id', type: 'int'},
		                                {name: 'mot_codigo', type: 'string'},
		                                {name: 'mot_descri', type: 'string'}                 
		                            ],
		                            autoLoad:true,
		                            proxy:{
		                                type: 'ajax',
		                                url: gdepositoscod.url + 'scm_scm_cod_mot_codigo/',
		                                reader:{
		                                    type: 'json',
		                                    root: 'data'
		                                }
		                            }
		                        }),
		                        queryMode: 'local',
		                        triggerAction: 'all',
		                        valueField: 'mot_id',
		                        displayField: 'mot_descri',
		                        listConfig:{
		                            minWidth: 200
		                        },
		                        width: 200,
		                        forceSelection: true,
		                        emptyText: '[ Seleccione ]'
							},
							{
								text:'',
								id:gdepositoscod.id+'-buscar',
								icon: '/images/icon/search.png',
								listeners:{
									beforerender: function(obj, opts){
		                                global.permisos({
		                                    id_serv: 103, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: gdepositoscod.id_menu,
		                                    fn: ['gdepositoscod.buscar']
		                                });
		                            },
									click:function(obj){
										gdepositoscod.buscar();
									}
								}
							},
							{
								text:'',
								id:gdepositoscod.id+'-cobrar',
								icon: '/images/icon/dolar.png',
								tooltip:'Click Para Realizar el Depósito',
								listeners:{
									beforerender: function(obj, opts){
		                                global.permisos({
		                                    id_serv: 102, 
		                                    id_btn: obj.getId(), 
		                                    id_menu: gdepositoscod.id_menu,
		                                    fn: ['gdepositoscod.show_deposito']
		                                });
		                            },
									click:function(obj){
										gdepositoscod.show_deposito()
									}
								}
							}
					],
					items:[
							{
								xtype:'grid',
								id:gdepositoscod.id+'-grid1',
								store:store_grid1,
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
		                        				width:90
		                        			},
		                        			{
		                        				text:'Medio de Pago',
		                        				dataIndex:'medio_pago',
		                        				flex:1,
		                        			},
		                        			{
		                        				text:'Moneda',
		                        				dataIndex:'moneda',
		                        				summaryRenderer: function(value, summaryData, dataIndex){
			                                        return '<div align="right" style="font-weight: bold;font-size: 12pt;">Totales:<div>';
			                                    }
		                        			},
		                        			{
		                        				text:'Monto',
		                        				dataIndex:'importe',
		                        				align:'right',
		                        				summaryType: 'sum',
		                        				summaryRenderer: function(value, summaryData, dataIndex){
			                                     	return '<div style="font-weight: bold;font-size: 12pt;">'+gdepositoscod.formatoNumero(value,2)+'</div>';
			                                    }
		                        			},
		                        			{
		                        				text:'Banco',
		                        				dataIndex:'banco',
		                        				flex:1.3,
		                        				summaryRenderer: function(value, summaryData, dataIndex){
			                                     	var grid = Ext.getCmp(gdepositoscod.id+'-grid1');
			                                     	var suma = 0;
			                                     	if (grid.getStore().getCount() > 0){
			                                     		for(var i = 0; i < grid.getStore().getCount(); ++i){
			                                     			var rec = grid.getStore().getAt(i);
			                                     			if (rec.get('chk')){
			                                     				suma = suma + parseFloat(rec.get('importe').toFixed(2));
			                                     			}

			                                     		}
			                                     	}
			                                     	//console.log(suma);
			                                     	//<a href="http://www.w3schools.com">Visit W3Schools</a>
			                                     	if (suma == 0){
			                                     		Ext.getCmp(gdepositoscod.id+'-cobrar').setVisible(false);
			                                     		return '';
			                                     	}else{//color: #0063DC;
			                                     		Ext.getCmp(gdepositoscod.id+'-cobrar').setVisible(true);
			                                     		return '<div style="font-weight: bold;font-size: 12pt"> Monto a Depositar: '+gdepositoscod.formatoNumero(suma,2)+'</div>';
			                                     	}
			                                     	
			                                    }
		                        			},
		                        			{
		                        				text:'N° Cuenta',
		                        				dataIndex:'cuenta',
		                        				flex:1
		                        			},
		                        			{
		                        				text:'Estado',
		                        				dataIndex:'estado',
		                        				flex:1
		                        			},
		                        			{
		                        				text:'Fecha Depósito',
		                        				dataIndex:'fecha_deposito',
		                        				width:120
		                        			},
		                        			
		                        			{
		                        				xtype:'checkcolumn',
		                        				dataIndex:'chk',
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
		                        					checkchange:function(checkcolumn, rowIndex, checked, eOpts) {
		                        						gdepositoscod.checkchange();
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
		                        	]
		                        }
							}
					]
				});
				tab.add({
					id:gdepositoscod.id+'-tab',
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
	                		global.state_item_menu(gdepositoscod.id_menu, true);	
	                    },
	                    afterrender: function(obj, e){
                        	tab.setActiveTab(obj);
                        	global.state_item_menu_config(obj,gdepositoscod.id_menu);	
	                        
	                    },
	                    beforeclose: function(obj, opts){
	                    	global.state_item_menu(gdepositoscod.id_menu, false);	
	                    }
	                }
				}).show();
			},
			buscar:function(){
				var grid = Ext.getCmp(gdepositoscod.id+'-grid1');
				var vp_provincia = Ext.getCmp(gdepositoscod.id + '-agencia').getValue();
				var vp_fecini = Ext.getCmp(gdepositoscod.id+'fini').getRawValue();
				var vp_fecfin = Ext.getCmp(gdepositoscod.id+'ffin').getRawValue();
				var vp_medio_pago = Ext.getCmp(gdepositoscod.id+'-med_pago').getValue();
				var vp_estado = Ext.getCmp(gdepositoscod.id+'-estado').getValue();
				grid.getStore().load({
					params:{vp_provincia:vp_provincia,vp_fecini:vp_fecini,vp_fecfin:vp_fecfin,vp_medio_pago:vp_medio_pago,vp_estado:vp_estado}
				});
			},
			checkchange:function(){
				//los datos bienen de scm_scm_cod_deposito_panel
				var grid = Ext.getCmp(gdepositoscod.id+'-grid1');
				var suma = 0;
				var tip_pago = 0;
				var tip_moneda = 0;
				var store = grid.getStore();
				/******************************
					Conf el primer chk
				******************************/
				store.each(function(rec,idx){
					if (rec.get('chk') && !rec.get('disabled')){
						suma = suma + 1;
						if (suma = 1 ){
							tip_pago = parseInt(rec.get('tip_pago'));
							tip_moneda = parseInt(rec.get('tip_moneda'));
							/*console.log(tip_pago);
							console.log(tip_moneda);*/
						}
					}
				});
				/******************************
					Conf los bloqueados 
					segun tipo de pago
				******************************/
				store.each(function(rec,idx){
					//console.log(tip_pago+'-'+rec.get('tip_pago'));
					if (suma > 0){
						if (parseInt(rec.get('tip_pago')) != tip_pago || parseInt(rec.get('tip_moneda')) != tip_moneda){
							//console.log(tip_pago);
							/*console.log(tip_pago);
							console.log(tip_moneda);*/
							rec.set('disabled',true);	
						}
						rec.commit();	
					}
				});
				grid.getView().refresh();
				if (suma == 0){
					gdepositoscod.buscar();
				}

			},
			getDatos:function(){
				var grid = Ext.getCmp(gdepositoscod.id+'-grid1');
				var prov_codigo = Ext.getCmp(gdepositoscod.id + '-agencia').getValue();
				var suma = 0;
				var arrayId_cod = [];
				var id_cod = '';
				var medio_pago_des='';
				var tip_pago = 0;
				var moneda = '';
				var tip_moneda = 0;
				if (grid.getStore().getCount() > 0){
             		for(var i = 0; i < grid.getStore().getCount(); ++i){
             			var rec = grid.getStore().getAt(i);
             			if (rec.get('chk')){
             				suma = suma + parseFloat(rec.get('importe').toFixed(2));
             				arrayId_cod.push(rec.get('id_cod'));
             				medio_pago_des = rec.get('medio_pago');
             				tip_pago = rec.get('tip_pago');
             				moneda = rec.get('moneda');
             				tip_moneda = rec.get('tip_moneda');
             			}
             			
             		}
             		id_cod = arrayId_cod.join(',');	
             	}
             	var datos = {
             		id_cod:id_cod,
             		medio_pago_des:medio_pago_des,
             		tip_pago:tip_pago,
             		moneda:moneda,
             		tip_moneda:tip_moneda,
             		monto:suma,
             		prov_codigo:prov_codigo,
             		MontoCambio:0
             	}
             	return datos;
			},
			show_deposito:function(){
				win.show({vurl: gdepositoscod.url + 'show_deposito/', id_menu: gdepositoscod.id_menu, class: '' });
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
		Ext.onReady(gdepositoscod.init,gdepositoscod);
	}else{
		tab.setActiveTab(gdepositoscod.id+'-tab');
	}
</script>