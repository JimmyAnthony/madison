<script type="text/javascript">
	var panel_transporte = {
		id:'panel_transporte',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/gtransporte/',
		clock: Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'g:i:s A'),ui:''}),
		init:function(){

			var panel = Ext.create('Ext.form.Panel',{
				layout:'border',
				border:false,
				tbar:[
						'Agencia',
						{
							xtype:'combo',
							id:panel_transporte.id+'-agencia',
							store:Ext.create('Ext.data.Store',{
								fields:[
										{name:'prov_codigo', type:'int'},
										{name:'prov_nombre', type:'string'},
								],
								proxy:{
									type:'ajax',
									url:panel_transporte.url+'get_usr_sis_provincias/',
									reader:{
										type:'json',
										rootProperty:'data'
									}
								}
							}),
							queryMode:'local',
							valueField:'prov_codigo',
							displayField:'prov_nombre',
							listConfig:{
								minWidth:300
							},
							width:140,
							forceSelection:true,
							allowBlank:false,
							empyText:'[ Seleccione]',
							listeners:{
								afterrender:function(obj){
									obj.getStore().load({
										params:{linea:3},
										callback:function(){
											obj.setValue(parseInt('<?php echo PROV_CODIGO;?>'));
										}
									})
								}
							}
						},
						'-',
						'Fecha:',
						{
							xtype:'datefield',
							id:panel_transporte.id +'-fecha',
							width:90,
							value:new Date()
						},
						'-',
						'Ruta',
						{
							xtype:'combo',
							id:panel_transporte.id+'-ruta',
							store:Ext.create('Ext.data.Store',{
								fields:[
										{name:'', type:'int'},
										{name:'', type:'string'},
								],
								proxy:'ajax',
								url:panel_transporte.url+'/',
								reader:{
									type:'json',
									rootProperty:'data'
								}
							}),
							queryMode:'local',
							valueField:'',
							displayField:'',
							listConfig:{
								minWidth:140
							},
							width:80,
							forceSelection:false,
							allowBlank:true,
							empyText: '[ Seleccione ]',

						},
						'-',
						{
							text:'',
							id:panel_transporte.id+'-buscar',
							icon:'/images/icon/search.png',
							listeners:{

							}
						},
						{
							text:'',
							id:panel_transporte.id+'-nuevo',
							icon:'/images/icon/new_file.ico',
							listeners:{
								click:function(obj,e){
									panel_transporte.nuevo();
								}
							}
						},
						{
							text:'',
							id:panel_transporte.id+'-siguiente',
							icon:'/images/icon/siguiente.png',
							listeners:{
								click:function(obj, e){
									
								}
							}
						}							
				],
				items:[

						{
							region:'west',
							border:false,
							layout:'fit',
							width:200,
							items:[
									{
										xtype:'uePanelHtml',
										id:panel_transporte.id+'-resumen',
										title:'Resumen',
										flex:1,
										listeners:{
											afterrender:function(obj,e){
												//console.log(panel_transporte.clock.text);
												obj.setHtml(
													new Ext.XTemplate(
														'<p>',
															'<label style="width: 150px;" id="panel_transporte-vencidos">Vencidos: </label>',
															'<span></span>',
														'</p>',
														'<p>',
															'<label style="width: 170px;" id="panel_transporte-Tiempo">A Tiempo: </label>',
															'<span></span>',
														'</p>',
														'<p>',
															'<label style="width: 280px;" id="panel_transporte-1hora">Dentro de 1 Hora: </label>',
															'<span></span>',
														'</p>',
														'<p>',
															'<label style="width: 180px;" id="panel_transporte-Recolectados">Recolectados: </label>',
															'<span></span>',
														'</p>',
														'<p>',
															'<label style="width: 150px;" id="panel_transporte-noRecolectados">No Recolectados: </label>',
															'<span></span>',
														'</p>',
														'<p>',
															'<label style="width: 150px;" id="panel_transporte-Re-Programados">Re-Programados: </label>',
															'<span></span>',
														'</p>',
														'<p>',
															'<label style="width: 150px;" id="panel_transporte-Solicitud">Total Solicitud: </label>',
															'<span></span>',
														'</p>',
														'<p>',
															'<label style="width: 200px;" id="panel_transporte-hora" >Hora: </label>',
															'<span ></span>',
														'</p>'
														
													),{}
												);
												new Ext.Panel({
												    renderTo: 'panel_transporte-vencidos',
												    border:false,
												    width: 80,
												    bodyStyle: 'padding:3px;',
												    items: []
												});
												new Ext.Panel({
												    renderTo: 'panel_transporte-Tiempo',
												    border:false,
												    width: 80,
												    bodyStyle: 'padding:3px;',
												    items: []
												});
												new Ext.Panel({
												    renderTo: 'panel_transporte-1hora',
												    border:false,
												    width: 40,
												    bodyStyle: 'padding:3px;',
												    items: []
												});
												new Ext.Panel({
												    renderTo: 'panel_transporte-Recolectados',
												    border:false,
												    width: 80,
												    bodyStyle: 'padding:3px;',
												    items: []
												});

												new Ext.Panel({
												    renderTo: 'panel_transporte-noRecolectados',
												    border:false,
												    width: 30,
												    bodyStyle: 'padding:3px;',
												    items: []
												});
												new Ext.Panel({
												    renderTo: 'panel_transporte-Re-Programados',
												    border:false,
												    width: 30,
												    bodyStyle: 'padding:3px;',
												    items: []
												});	
												new Ext.Panel({
												    renderTo: 'panel_transporte-Solicitud',
												    border:false,
												    width: 30,
												    bodyStyle: 'padding:3px;',
												    items: []
												});	
												
												new Ext.Panel({
												    renderTo: 'panel_transporte-hora',
												    border:false,
												    width: 80,
												    bodyStyle: 'background:#89CB9F; padding:3px;',
												    items: [panel_transporte.clock]
												});
			
												
											},
											render:function(){
												 Ext.TaskManager.start({
			                                    	 run: function(){
			                                            Ext.fly(panel_transporte.clock.getEl()).update(Ext.Date.format(new Date(), 'g:i:s A'));
			                                            //panel_transporte.resumen();
			                                         },
			                                         interval: 1000
			                                     });
											},
											delay: 100
										}
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
										border:false,
										id:panel_transporte.id+ '-grid',
										//store:store,
										columnLines:true,
										columns:{
											items:[
													{
														text:'Unidad.',
														flex:1,
														dataIndex:''
													},
													{
														text:'Total de Carga',
														flex:1,
														dataIndex:''
													},
													{
														text:'Ruta',
														flex:1,
														dataIndex:''
													},
													{
														text:'Ultima Ruta',
														flex:1,
														dataIndex:''
													},
													{
														text:'Total Asignado',
														flex:1,
														dataIndex:''
													},
													{
														text:'',
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
				id:panel_transporte.id+'-win',
				title:'Panel de Transporte',
				bodyStyle: 'background: transparent',
				height:310,
				width:650,
				resizable:false,
				closable:false,
				minimizable: true,
				plaint:true,
				constrain: true,
				constrainHeader:true,
				renderTo:Ext.get(gtransporte.id+'cont_map'),//Ext.get(gtransporte.id+'Mapsa'),
				header:true,
				border:false,
				layout:{
					type:'fit'
				},
				modal:false,
				items:[panel],
				listeners:{
					show:function(window,eOpts){
						window.alignTo(Ext.get(gtransporte.id+'Mapsa'),'bl-bl');
					},
					minimize:function(window,opts){
						window.collapse();
		                window.setWidth(100);
		                window.alignTo(Ext.get(gtransporte.id+'Mapsa'), 'bl-bl');
					},

				},
				tools:[{
						type:'restore',
						handler: function(evt, toolEl, owner, tool){
							var window = owner.up('window');
			                window.setWidth(700);
			                window.expand('', false);
			                window.alignTo(Ext.get(gtransporte.id+'Mapsa'), 'bl-bl');
						}
					}]
			}).show();
		},
		nuevo:function(){
			
			/*var objeto = {
				width:700,
                start:false,
                fous:true	
			};*/
			gtransporte.get_menu_sh(gtransporte.objeto);

			Ext.getCmp(gtransporte.id+'-slider').show();
			Ext.getCmp(gtransporte.id+'-slider').setCollapsed(false);
			Ext.getCmp(panel_transporte.id+'-win').hide();		
			
		}

	}
	Ext.onReady(panel_transporte.init,panel_transporte);
</script>
