<script type="text/javascript">
	var panel_orden = {
		id:'panel_orden',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/gtransporte/',
		init:function(){
			var panel = Ext.create('Ext.form.Panel',{
				layout: 'border',
				border: false,
				tbar:[
						'Shipper:',
						{
							xtype:'combo',
							id:panel_orden.id+'-shipper',
							store:Ext.create('Ext.data.Store',{
								fields:[
										{name: 'shi_codigo', type: 'int'},
		                                {name: 'shi_nombre', type: 'string'},
		                                {name: 'shi_id', type: 'string'}
								],
								proxy:{
									type:'ajax',
									url:panel_orden.url+'get_usr_sis_shipper/',
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
							width:150,
							forceSelection:true,
							allowBlank:false,
							selecOnFocus:true,
							emptyText:'[ Seleccione ]',
							listeners:{
								afterrender:function(obj,record,options){
									obj.getStore().load({
										params:{
											vp_linea:0
										},
										/*callback:function(){
											obj.setValue(0);
										}*/
									});
								}
							}
						},	
						'-',
						'Desde:',
						{
							xtype:'datefield',
							id:panel_orden.id+'-desde',
							width:90,
							value: new Date()
						},
						'-',
						'Hasta:',
						{
							xtype:'datefield',
							id:panel_orden.id+'-hasta',
							width:90,
							value: new Date()
						},
						'-',
						{
							text:'',
							id:panel_orden.id+'-buscar',
							icon:'/images/icon/search.png',
							listeners:{

							}
						},
						{
							text:'',
							id:panel_orden.id+'-nuevo',
							icon:'/images/icon/new_file.ico',
							listeners:{
								click:function(obj,e){
									panel_orden.nuevo();
								}
							}
						},
						{
							text:'',
							id:panel_orden.id+'-regresar',
							icon:'/images/icon/get_back.png',
							listeners:{
								click:function(obj,e){
									Ext.getCmp(panel_orden.id+'-win').destroy();
									Ext.getCmp(panel_transporte.id+'-win').show();

								}
							}
						}		
				],
				items:[
						{
							region:'center',
							border:false,
							layout:'fit',
							items:[
									{
										xtype:'grid',
										id:panel_orden.id+'-grid',
										//store:store,
										columnLines:true,
										columns:{
											items:[
													{
														text:'Shipper',
														flex:1,
														dataIndex:''
													},
													{
														text:'Fech. Requerimiento',
														flex:1,
														dataIndex:''
													},
													{
														text:'Hora Requerimiento',
														flex:1,
														dataIndex:''
													},
													{
														text:'Tipo Ejecución',
														flex:1,
														dataIndex:''
													},
													{
														text:'Ruta',
														flex:1,
														dataIndex:''
													},
													{
														text:'Estado',
														flex:1,
														dataIndex:''
													},
													{
														text:'Tipo de Recojo',
														flex:1,
														dataIndex:''
													}
											]
										}
									}
							]
						}
				]
			});
		
			
			var win = Ext.create('Ext.window.Window',{
				id:panel_orden.id+'-win',
				title:'Panel de Orden',
				height:300,
				width:600,
				resizable:false,
				closable:false,
				minimizable:true,
				plaint:true,
				constrain:true,
				constrainHeader:true,
				renderTo:Ext.get(gtransporte.id+'cont_map'),
				header:true,
				border:false,
				layout:{
					type:'fit'
				},
				modal:false,
				items:[
						panel
				],
				listeners:{
					show:function(window,eOpts){
						window.alignTo(Ext.get(gtransporte.id+'Mapsa'),'bl-bl');
					},
					minimize:function(window,opts){
						window.collapse();
						window.setWidth(100);
						window.alignTo(Ext.get(gtransporte.id+'Mapsa'), 'bl-bl');
					}
				},
				tools:[{
						type:'restore',
						handler:function(evt, toolEl, owner, tool){
							var window = owner.up('window');
			                window.setWidth(600);
			                window.expand('', false);
			                window.alignTo(Ext.get(gtransporte.id+'Mapsa'), 'bl-bl');
						}
				}]
			}).show();	
		},
		nuevo:function(){
			Ext.getCmp(panel_orden.id+'-win').hide();
			win.show({vurl: panel_orden.url + 'form_new_orden/', id_menu: panel_orden.id_menu, class: ''});
		}
	}
	Ext.onReady(panel_orden.init,panel_orden);
</script>