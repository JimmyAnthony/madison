<script type="text/javascript">
	var tot_error ={
		id:'tot_error',
		url:'/gestion/procesarftp/',
		init:function(){
			var store = Ext.create('Ext.data.Store',{ 
						fields:[
							{name: 'shi_descri', type:'string'},
							{name: 'pro_descri', type:'string'},
							{name: 'cic_inicio', type:'string'},
							{name: 'cli_codigo', type:'string'},
							{name: 'cli_nombre', type:'string'},
							{name: 'cli_direcc', type:'string'},
							{name: 'cli_referen', type:'string'},
							{name: 'cli_localid', type:'string'},
							{name: 'rec_descri', type:'string'},
							{name: 'rec_fecha', type:'string'},
							{name: 'err_observ', type:'string'},
							
						],
						proxy:{
							type:'ajax',
							url:tot_error.url+'grid_reclamos/',
							reader:{
								type:'json',
								root:'data'
							}
						},
						listeners:{
							load: function(obj, records, successful, opts){
							}
						}
			});

			var panel = Ext.create('Ext.form.Panel',{
				id:tot_error.id +'-form',
				border:false,
				layout:'border',
				items:[
						{
							region: 'center',
							xtype:'tabpanel',
							id:tot_error.id+'-tab',
							//activeItem:0,
							border:false,
							autoScroll:true,
							defaults:{
								border:false
							},
							items:[
									{
										title:'Reclamo de Clientes',
										id:tot_error.id+'-tab_reclamos',
										iconCls: '',
										layout:{
											type:'fit'
										},
										items:[
												{
													xtype:'grid',
													id:tot_error.id+'-grid_reclamos',
													store:store,
													columnLines: true,
													columns:{
														items:[
																{
																	xtype: 'rownumberer'
																},
																{
																	text:'Shipper',
																	dataIndex:'shi_descri',
																	width: 100,
                                    								align: 'left',
																},
																{
																	text:'Producto',
																	dataIndex:'pro_descri',
																},
																{
																	text:'Ciclo',
																	dataIndex:'cic_inicio',
																},
																{
																	text:'Nombre',
																	dataIndex:'cli_nombre',
																},
																{
																	text:'Dirección',
																	dataIndex:'cli_direcc',
																},
																{
																	text:'Localidad',
																	dataIndex:'cli_localid',
																},
																{
																	text:'Fec. Reclamo',
																	dataIndex:'rec_fecha',
																},
																{
																	text:'Error Obs',
																	dataIndex:'err_observ',
																}
														],
														defaults:{
															sortable:true
														}
													},
													viewConfig:{
														stripeRows: true,
							                            enableTextSelection: false,
							                            markDirty: false
													},
													trackMouseOver: true,	
												}
										]
									},
									{
										title:'Datos de Emisión',
										id:tot_error.id+'-tab_emision',
										iconCls:'',
										layout:{
											type:'fit'
										},
										items:[
										]
									},
							]
						}

				],
				listeners:{
					afterrender:function(obj){
						var rec = Ext.getCmp(procesarftp.id + '-grid').getSelectionModel().getSelection();
						var sol_id = rec[0].data.sol_id;
						var tarch_id = rec[0].data.tarch_id;
						Ext.getCmp(tot_error.id+'-tab_reclamos').disable();
						Ext.getCmp(tot_error.id+'-tab_emision').disable();			
						var tab = Ext.getCmp(tot_error.id+'-tab');

						if (parseInt(tarch_id)== 4){	
							Ext.getCmp(tot_error.id+'-tab_reclamos').enable();
							tab.setActiveTab(tot_error.id+'-tab_reclamos');
							Ext.getCmp(tot_error.id+'-grid_reclamos').getStore().load({
								params:{id_solicitud:sol_id},
								callback:function(){
								}

							});
						}	
				

					}
				}

			});

			Ext.create('Ext.window.Window',{
				id:tot_error.id+'-win',
				title:'Gestor De Errores',
				height:400,
				width:800,
				resizable:false,
				baseCls:'gk-window',
				layout:{
					type:'fit'
				},
				modal:true,
				items:[
					panel
				],
				buttonAling:'center',
				dockedItem:[
							{
								xtype:'toolbar',
								dock:'bottom',
								ui:'footer',
								alingTarget:'center',
								layout:{
									pack:'center'
								},
								baseCls:'gk-toolbar',
								items:[

								]
							}
				]

			}).show().center();
		},



	}
	Ext.onReady(tot_error.init,tot_error);


</script>