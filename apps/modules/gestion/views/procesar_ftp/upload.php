<script type="text/javascript">
	var uploadExcel={
		id:'uploadExcel',
		url: '/gestion/procesarftp/',
		//myMask:[],
		tipo:1,
		init:function(){
			var panel = Ext.create('Ext.form.Panel',{
				id:uploadExcel.id+'-form',
				border:false,
				defaults:{
					border:false,
					style:{
						margin:'20px'
					},
					layout:{
						type:'hbox'
					}
				},
				items:[
						{
							xtype:'panel',
							defaults:{
								labelWidth:60,
								flex:1,
								border:false
							},
							items:[
									{
										xtype:'panel',
										layout:'center',
										items:[
											{
												xtype:'radiogroup',
												id:uploadExcel.id + '-rbtn_group',
												fieldLabel:'Tipo',
												columns:5,
												vertical:true,
												labelWidth:35,
												items:[
					                                    {boxLabel: 'Datos para PU', name: uploadExcel.id+'-rbtn', inputValue: '1', width: 110, checked: true},
					                                    {boxLabel: 'Para coordinaciones', name: uploadExcel.id+'-rbtn', inputValue: '2', width: 140},
					                                    {boxLabel: 'Recolección', name: uploadExcel.id+'-rbtn', inputValue: '3', width: 95},
					                                    {boxLabel: 'Reclamos', name: uploadExcel.id+'-rbtn', inputValue: '4', width: 75},
					                                    {boxLabel: 'Auditoria', name: uploadExcel.id+'-rbtn', inputValue: '5', width: 70}
												],
												listeners:{
													change:function (obj, newValue, oldValue, eOpts){
														var op = parseInt(newValue[uploadExcel.id+'-rbtn']);
														uploadExcel.tipo = op;
													}
												}
											}

										]
									},
									
							]
						},
						{
							xtype:'panel',
							layout:'hbox',
							//border:false,
							items:[
									{
										xtype:'panel',
										layout:'hbox',
										border:false,
										items:[ 
												{ xtype: 'tbspacer',width:140 },
												
												{
													xtype:'filefield',
													id:uploadExcel.id + '-file',
													name:uploadExcel.id + '-file',
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
									}
									

							]
						}
						
				]
			});

			Ext.create('Ext.window.Window',{
				id:uploadExcel.id +'-win',
				title:'Cargar Archivo',
				height:150,
				width:650,
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
												id:uploadExcel.id +'-subir',
												listeners:{
													click:function(obj,e){
														var form = Ext.getCmp(uploadExcel.id +'-form').getForm();
														if (form.isValid()){
															var mask = new Ext.LoadMask(Ext.getCmp(uploadExcel.id +'-win'), {
																msg:'Upload Archivo...'
															});
															mask.show();
															var vl_tipo = uploadExcel.tipo;
															form.submit({
																url:uploadExcel.url + 'uploadExcel/',
																params:{vl_tipo:vl_tipo},
																witMsg:'Upload....',
																success:function(fp,o){
																	mask.hide();
																	var res = o.result.data;
																	global.Msg({
																	    msg:res.msg,
								                                        ico:1,
								                                        buttons:1,
								                                        fn:function(btn){
								                                        	Ext.getCmp(uploadExcel.id+'-win').close();
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
												id:uploadExcel.id +'-cancelar',
												icon:'/images/icon/remove.png',
												listeners:{
													click:function(obj,e){
														Ext.getCmp(uploadExcel.id+'-win').close();	
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
									var form = Ext.getCmp(uploadExcel.id +'-form').getForm();
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
			                                        	Ext.getCmp(uploadExcel.id+'-win').close();
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
									Ext.getCmp(uploadExcel.id+'-win').close();	
								}
								
							}
						}

				]*/

			}).show().center();

		}

	}
	Ext.onReady(uploadExcel.init,uploadExcel);

</script>
