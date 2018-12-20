<script type="text/javascript">
	var red_distribucion = {
		id:'red_distribucion',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/red_despacho/',
		init:function(){
			var distribucion = Ext.create('Ext.form.Panel',{
				layout:{
					type:'form',
					aling:'stretch'
				},
				items:[
						{
							xtype:'fieldset',
							title:'Parametro',
							items:[
									{
										xtype:'panel',
										layout:'hbox',
										border:false,
										items:[
												{
													xtype:'combo',
													margin:'5 5 5 5',
													id:red_distribucion.id+'-origen',
													fieldLabel:'Provincia Origen',
													store:Ext.create('Ext.data.Store',{
														fields:[
																{name:'prov_codigo',type:'int'},
																{name:'prov_nombre', type:'string'},
														],
														proxy:{
															type:'ajax',
															url:red_distribucion.url+'provincias_red_despacho',
															reader:{
																type:'json',
																rootProperty:'data'
															}
														}
													}),
													queryMode:'local',
													valueField: 'prov_codigo',
													displayField: 'prov_nombre',
													listConfig:{
															minWidth:300
													},
													width:230,
													forceSelection:true,
													allowBlank:false,
													empryText:'[ Seleccione]',
													listeners:{
														afterrender:function(obj){
															obj.getStore().load({
																	params:{tipo:'O',prov_codigo:0},
																	callback:function(){
																	}
															});
														}

													}

												}
										]
									}
							]
						},
						{
							xtype:'grid',
							id:red_distribucion.id+'-distribucion',
							store:'',
							height:170,
							width:470,
							columnLines:true,
							columns:{
								items:[
										{
											text:'ver',
											dataIndex:'',
											flex:0.5,
											aling:'center'
										},
										{
											text:'Destino',
											dataIndex:'Ciudad',
											flex:1
										},
										{
											text:'Diario',
											dataIndex:'',
											flex:1
										},
										{
											text:'Estado',
											dataIndex:'',
											flex:1
										}
								],
								defaults:{
									sortable:true
								}
							},
							viewConfig:{
								stripeRow:true,
								enableTextSelection:false,
								marDirty:false
							},
							trackMouseOver:true,
						}
				]
			});
		
			Ext.create('Ext.window.Window',{
				id:red_distribucion.id+'-win',
				title:'distribución',
				height:300,
				width:500,
				resizable:false,
				closable:false,
				plain:true,
				minimizable: true,
				constrain: true,
				constrainHeader:true,
				renderTo:red_despacho.contenedor,
				header:true,
				border:false,
				layout:{
					type:'fit'
				},
				modal:false,
				items:[
						distribucion
				],
				listeners:{
					show:function( window, eOpts ){
						window.alignTo(Ext.getCmp(red_despacho.id+'Mapsa'), 'bl-bl');
					},
					minimize:function(window,opts){
						window.collapse();
						window.setWidth(100);
						window.alignTo(Ext.getCmp(red_despacho.id+'Mapsa'), 'bl-bl');
					}
				},
				tools:[
						{
							type:'restore',
							handler:function(evt, toolEl, owner, tool){
								var window = owner.up('window');
								window.setWidth(500);
								window.expand('',false);
								window.alignTo(Ext.getCmp(red_despacho.id+'Mapsa'), 'bl-bl');
							}
						}
				]
			}).show();

		}
	}
	Ext.onReady(red_distribucion.init,red_distribucion);
</script>