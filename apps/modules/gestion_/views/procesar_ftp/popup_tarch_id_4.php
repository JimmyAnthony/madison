<script type="text/javascript">
	var tarch_id_4 = {
		id:'tarch_id_4',
		url:'/gestion/procesarftp/',
		init: function(){
			var panel = Ext.create('Ext.form.Panel',{
				id:tarch_id_4.id+'-form',
				border:false,
				defaults:{
					border:false,
					layout:{
						type:'border',

					}
				},
				items:[
						{
							xtype:'panel',
							layout:'fit',
							padding:'10 10 0 10',
							defaults:{
								border:false
							},
							items:[	 
									{
										xtype:'fieldset',
										id:tarch_id_4.id+'-fpanel',
										title:'Tipo de Reclamo',
										padding:'5 10 0 10',
										border:true,
										width: 100,
										height: 60,
										items:[
												{
													xtype:'panel',
													layout:'hbox',
													border:false,
													items:[
															{
																xtype:'combo',
																labelAlign: 'top',
															//	fieldLabel:'Tipo de Reclamo',
																id:	tarch_id_4.id+'-tipo_reclamo',
																store:Ext.create('Ext.data.Store',{
																	fields:[
																		{name: 'descripcion', type: 'string'},
										                                {name: 'id_elemento', type: 'int'},
										                                {name: 'des_corto', type: 'string'}
																	],
																	proxy:{
																		type:'ajax',
																		url:tarch_id_4.url+'get_scm_tabla_detalle_filtro/',
																		reader:{
																			type:'json',
																			rootProperty:'data',
																		}

																	}	
																}),
																queryMode:'local',
																triggerAction:'all',
																valueField:'id_elemento',
																displayField:'descripcion',
																emptyText:'[Seleccionar]',
																forceSelection: true,
																allowBlank: false,
																selectOnFocus:true,
																listeners:{
																	afterrender:function(obj,e){
																		obj.getStore().load({
																			params:{
																				vp_tab_id:'TAU',
																				vp_shipper: 0
																			},
																			callback:function(){
																				obj.setValue(1);
																			}
																		});
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

			});

			
			Ext.create('Ext.window.Window',{
				id:tarch_id_4.id +'-win',
				title:'Reclamos del Cliente',
				//height:100,
				//shadow: 'drop',
				width:200,
				resizable:false,
				baseCls: 'gk-window',
				layout:{
					type:'fit'
				},
				modal:true,
				items:[
						panel
				],
				buttonAlign:'center',
				dockedItems:[
								{
									xtype:'toolbar',
									dock:'bottom',
									ui:'footer',
									alignTarget:'center',
									layout:{
										pack:'center'
									},
									baseCls:'gk-toolbar',
									items:[
											{
												text:'Continuar',
												icon:'/images/icon/siguiente.png',
												id:tarch_id_4.id+'-continuar',
												listeners:{
													click:function(obj,e){
														tarch_id_4.procesar();
													}
												}
											}
									]
								}

				]


			}).show().center();
		},
		procesar:function(){
			var rec = Ext.getCmp(procesarftp.id + '-grid').getSelectionModel().getSelection();
			var tarch_id = Ext.getCmp(tarch_id_4.id+'-tipo_reclamo').getValue();
			var sol_id = rec[0].data.sol_id;
			//console.log(sol_id);

			      global.Msg({
                    msg:'Seguro de Realizar PU al Archivo',
                    ico:3,
                    buttons:3,
                    fn:function(btn){
                        if (btn=='yes'){
                            var mask = new Ext.LoadMask(Ext.getCmp(tarch_id_4.id+'-form'), {
                                msg:'Procesando Archivo...'
                            });
                            mask.show();
                            Ext.Ajax.request({
                                url:tarch_id_4.url + 'gestor_ftp_pu/',
                                params:{id_solicitud:sol_id,tarch_id:tarch_id},
                                success:function(response,options){
                                    mask.hide();
                                    var res = Ext.decode(response.responseText);
                                   // console.log(res.data[0].error_sql);
                                    if ( parseInt(res.data[0].error_sql) == 1 ){
                                        global.Msg({
                                            msg:res.data[0].error_info,
                                            icon:1,
                                            buttons:1,
                                            fn:function(btn){
                                            	procesarftp.consultar();
                                            	Ext.getCmp(tarch_id_4.id +'-win').close();
                                            }
                                        });
                                    }else{
                                        mask.hide();
                                         global.Msg({
                                            msg:res.data[0].error_info,
                                            icon:0,
                                            buttons:1,
                                        });
                                    }
                                }
                            });
                        }
                    }
                 }); 
		}
	}
	Ext.onReady(tarch_id_4.init,tarch_id_4);
</script>