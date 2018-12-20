<script type="text/javascript">
	var red_cobertura = {
		id:'red_cobertura',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/red_despacho/',
		init:function(){
			var store = Ext.create('Ext.data.Store',{
				fields:[
					{name: 'cbe_id', type:'int'},
					{name: 'cbe_terres', type:'int'},
					{name: 'cbe_aereo', type:'int'},
					{name: 'cbe_otros', type:'int'},
					{name: 'cbe_estado', type:'int'},
					{name: 'prov_nombre', type:'string'},
					{name: 'prov_destino', type:'int'},
					{name: 'terrestre', type:'string'},
					{name: 'aereo', type:'string'},
					{name: 'otros', type:'string'},
					
					
				],
				proxy:{
					type:'ajax',
					url:red_cobertura.url+'get_cobertura/',
					reader:{
						type:'json',
						root:'data'
					},
				
				},
				listeners:{
					load: function(obj, records, successful, opts){    
                    }
				},

			});

			var cobertura = Ext.create('Ext.form.Panel',{
					layout:'border',
					items:[
						{
							region:'north',
							height:60,
							border:false,
							items:[

								{
									xtype:'fieldset',
									//margin:'5 5 5 5',
									title:'Parametro',
									layout: 'column',
									items:[
										{
											xtype:'combo',
											margin:'0 5 0 0',
											padding:'0 0 5 0',
											columnWidth: 1,
											id:red_cobertura.id+'-origen',
											fieldLabel:'Provincia Origen',
											store:Ext.create('Ext.data.Store',{
												fields:[
														{name:'prov_codigo', type:'int'},
														{name:'prov_nombre', type:'string'},
												],
												proxy:{
												 	type:'ajax',
												 	url:red_cobertura.url+'get_usr_sis_provincias/',
												 	reader:{
												 		type:'json',
												 		rootProperty:'data'
												 	}

												}
											}),
											queryMode: 'local',
											valueField: 'prov_codigo',
											displayField: 'prov_nombre',
											listConfig:{
												minWidth:300
											},
											width:320,
											forceSelection:true,
											allowBlank:false,
											empryText:'[ Seleccione]',
											listeners:{
												afterrender:function(obj){
													obj.getStore().load({
														params:{tipo:'O',prov_codigo:0},
														callback:function(){
															obj.setValue(parseInt('<?php echo PROV_CODIGO;?>'));
															Ext.getCmp(red_cobertura.id+'-cobertura').store.load({
																params:{vl_prov_origen:'<?php echo PROV_CODIGO;?>'},
																callback:function(){
																	
																}
															});
														}
													});
												},
												'select':function(obj, records, eOpts){
													Ext.getCmp(red_cobertura.id+'-cobertura').store.load({
														params:{vl_prov_origen:records.get('prov_codigo')},
														callback:function(){
															
														}
													});
												}
											}
										},
										{
											xtype: 'button',
											text:'',
											//height:30,
											//width: 20,
											id:red_cobertura.id+'-modificar',
											icon:'/images/icon/edit.png',
											listeners:{
												click:function(obj,e){
													if (Ext.getCmp(red_cobertura.id+'-origen').value != null){
														Ext.getCmp(red_cobertura.id+'-win').hide();
														win.show({vurl: red_cobertura.url + 'new_age_coord/', id_menu: red_cobertura.id_menu, class: ''});
													}else{
														global.Msg({
															msg:'Debe selecionar la Provicia Origen',
															icon:0

														});
													}
												}
											}
										},
										{
											xtype: 'button',
											text:'',
											//height:30,
											//width: 20,
											id:red_cobertura.id+'-tiempo-transito-',
											icon:'/images/icon/transport_.png',
											listeners:{
												click:function(obj,e){
													var origen = Ext.getCmp(red_cobertura.id+'-origen').getValue();
													if (origen != null){
														Ext.getCmp(red_cobertura.id+'-win').hide();
														red_cobertura.red_distribucion(origen,1);
													}else{
														global.Msg({
															msg:'Debe selecionar la Provicia Origen',
															icon:0

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
							region:'center',
							layout:'fit',
							border:false,
							items:[
								{
									xtype:'grid',
									id:red_cobertura.id+'-cobertura',
									store:store,
								//	height:170,
								//	width:470,
									columnLines:true,
									columns:{
										items:[     
												{
													text:'Ver',
													dataIndex:'',
													//width:60,
													flex:1,
													align:'center',
												    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
				                                        metaData.style = "padding: 0px; margin: 0px";
				                                        return global.permisos({
				                                            type: 'link',
				                                           // extraCss: 'ftp-procesar-link',
				                                            id_menu: red_cobertura.id_menu,
				                                            icons:[
				                                                {id_serv: 59, img: 'reloj.ico', qtip: 'Click para Modificar los horarios', js: 'red_cobertura.new_hoario('+record.get('prov_destino')+');'}//,
				                                                //{id_serv: 59, img: 'Logistic_7-16.png', qtip: 'Click para ver el tiempo de transito', js: 'red_cobertura.red_distribucion('+record.get('prov_destino')+');'}
				                                            ]
				                                        });
				                                    }
												},
												{
													text:'Line Haul Local',
													columns:[
																{
																	text:'Destino',
																	dataIndex:'prov_nombre',
																	width:150,	
																},
																{
																	text:'Envio Terrestre',
																	dataIndex:'cbe_terres',
																	//cls: 'column_header_double',
																	align: 'center',
																	width:100,
																	renderer:function(value, metaData, record, rowIndex, colIndex, store, view){

																		if (value=='1'){
																			return '<a href="#" onclick="red_cobertura.ver_mapa('+record.get('prov_destino')+','+1+')">Si</a>';
																			//return '<a href="#" onclick="red_cobertura.red_distribucion('+record.get('prov_destino')+','+1+')">Si</a>';


																		}else{
																			return 'No';
																		}
																	}
																	
																},
																{
																	text:'Envio Aereo',
																	dataIndex:'cbe_aereo',
																	align: 'center',
																	width:100,
																	renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																		if (value=='1'){
																			return '<a href="#" onclick="red_cobertura.ver_mapa('+record.get('prov_destino')+','+2+')">Si</a>';
																			//return '<a href="#" onclick="red_cobertura.red_distribucion('+record.get('prov_destino')+','+2+')">Si</a>';
																		}else{
																			return 'No';
																		}
																	}
																	
																},
																{
																	text:'Otros',
																	dataIndex:'cbe_otros',
																	align:'center',
																	width:75,
																	renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
																		if (value=='1'){
																			return '<a href="#" onclick="red_cobertura.ver_mapa('+record.get('prov_destino')+','+3+')">Si</a>';
																			//return '<a href="#" onclick="red_cobertura.red_distribucion('+record.get('prov_destino')+','+3+')">Si</a>';
																		}else{
																			return 'No';
																		}
																	}												
																	
																},

										   				]	
												}									                    
												
										],
										defaults:{
											 sortable: true
										}

									},
									viewConfig:{
										stripeRows: true,
			                            enableTextSelection: false,
			                            markDirty: false,
			                         
									},
									trackMouseOver:true,
								}
							]
						}
					]
			});
		

		var wind =	Ext.create('Ext.window.Window',{
				id:red_cobertura.id+'-win',
				title:'Cobertura',
				height:300,
				width:500,
				resizable:false,
				closable:false,
				plain:true,
				minimizable: true,
				constrain: true,
				constrainHeader:true,
				renderTo:Ext.get(red_despacho.id+'-tab'),//red_despacho.contenedor,
				header:true,
				border:false,
				layout:{
					type:'fit'
				},
				modal:false,
				items:[
						cobertura
				],

				listeners:{
					show:function( window, eOpts ){
						 window.alignTo(Ext.get(red_despacho.id+'Mapsa'), 'bl-bl');
					},
					minimize: function(window,opts){
				   		window.collapse();
		                window.setWidth(100);
		                window.alignTo(Ext.get(red_despacho.id+'Mapsa'), 'bl-bl');
				   	}
				},
				tools: [{
		            type: 'restore',
		            handler: function (evt, toolEl, owner, tool) {
		                var window = owner.up('window');
		                window.setWidth(500);
		                window.expand('', false);
		               //window.center();
		                window.alignTo(Ext.get(red_despacho.id+'Mapsa'), 'bl-bl');
		            }
		        }]
			}).show();

		},
		ver_mapa:function(prov_destino,tip_via){
			var prov_origen = Ext.getCmp(red_cobertura.id+'-origen').getValue();
			red_despacho.loadMap(prov_origen,prov_destino,tip_via);
		},
		new_hoario:function(prov_destino){
			win.show({vurl: red_cobertura.url + 'new_horario/', id_menu: red_cobertura.id_menu, class: ''});
		},
		show_transito:function(){
			win.show({vurl: red_despacho.url + 'form_transito/', id_menu: red_despacho.id_menu, class: ''});
		},
		red_distribucion:function(prov_destino,tipo){
			Ext.getCmp(red_cobertura.id+'-win').hide();
			var prov_codigo = Ext.getCmp(red_cobertura.id+'-origen').getValue();
			//red_despacho.loadMap(prov_codigo,prov_destino,tipo);
			win.show({vurl: red_despacho.url + 'form_transito/?vp_prov_codigo='+prov_codigo+'&vp_prov_destino='+prov_destino+'&vp_tipo='+tipo, id_menu: red_despacho.id_menu, class: ''});
		}
	}
	Ext.onReady(red_cobertura.init,red_cobertura);
</script>