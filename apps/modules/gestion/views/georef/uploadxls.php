<script type="text/javascript">
	var uploadxls={
		id:'uploadxls',
		url: '/gestion/georef/',
		//myMask:[],
		tipo:1,
		init:function(){
			var panel = Ext.create('Ext.form.Panel',{
				id:uploadxls.id+'-form',
				border:false,
				defaults:{
					border:false,
					style:{
						margin:'5px'
					},
					layout:{
						type:'hbox'
					}
				},
				items:[
						{
							xtype:'filefield',
							id:uploadxls.id + '-file',
							name:uploadxls.id + '-file',
							labelWidth:50,
							fieldLabel:'Archivo',
							allowBlank:false,
							width:300,
							emptyText: 'Seleccione Archivo',
							buttonText:'',
							buttonConfig:{
								iconCls:'ftp-procesar-directory',
							},
							listeners:{
							    change: function(fld, value) {
		                            var newValue = value.replace(/C:\\fakepath\\/g, '');
		                            fld.setRawValue(newValue);
		                        }
							}
						}
				]
			});

			Ext.create('Ext.window.Window',{
				id:uploadxls.id +'-win',
				title:'Cargar Archivo',
				height:90,
				width:320,
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
									alignTarget: 'center',
									layout:{
										pack:'center'
									},
									baseCls: 'gk-toolbar',
									items:[
											{
												text:'Upload',
												icon:'/images/icon/upload-file.png',
												id:uploadxls.id +'-subir',
												listeners:{
													click:function(obj,e){
														var form = Ext.getCmp(uploadxls.id +'-form').getForm();
														if (form.isValid()){
															var mask = new Ext.LoadMask(Ext.getCmp(uploadxls.id +'-win'), {
																msg:'Upload Archivo...'
															});
															mask.show();
															form.submit({
																url:uploadxls.url + 'uploadExcel/',
																params:{},
																witMsg:'Upload....',
																success:function(fp,o){
																	mask.hide();
																	var res = o.result.data;
																	global.Msg({
																	    msg:res.msg,
								                                        icon:res.icon,
								                                        buttons:1,
								                                        fn:function(btn){
								                                        	Ext.getCmp(uploadxls.id+'-win').close();
								                                        }
																	});
																},
																failure:function(fp,o){
																	mask.hide();
																}

															});

														}

													}
												}
											},
											{
												text:'Cancelar',
												id:uploadxls.id +'-cancelar',
												icon:'/images/icon/remove.png',
												listeners:{
													click:function(obj,e){
														Ext.getCmp(uploadxls.id+'-win').close();	
													}
													
												}
											}

									]

								}
				]
			/*	buttons:[
						{
							text:'Subir',
							icon:'/images/icon/upload-file.png',
							listeners:{
								click:function(obj,e){
									var form = Ext.getCmp(uploadxls.id +'-form').getForm();
									if (form.isValid()){
										form.submit({
											url:'',
											params:{},
											witMsg:'Upload....',
											success:function(fp,o){
												var res = o.result.rpta;
												global.Msg({
												    msg:res.msg,
			                                        ico:1,
			                                        buttons:1,
			                                        fn:function(btn){
			                                        	Ext.getCmp(uploadxls.id+'-win').close();
			                                        }
												});
											},
											failure:function(fp,o){
											}

										});

									}

								}
							}
						},
						{
							text:'Cancelar',
							icon:'/images/icon/remove.png',
							listeners:{
								click:function(obj,e){
									Ext.getCmp(uploadxls.id+'-win').close();	
								}
								
							}
						}

				]*/

			}).show().center();

		}

	}
	Ext.onReady(uploadxls.init,uploadxls);

</script>
