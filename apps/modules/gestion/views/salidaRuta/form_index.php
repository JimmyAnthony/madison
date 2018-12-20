<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('salidaRuta-tab')){
		var salidaRuta = {
			id:'salidaRuta',
			id_menu:'<?php echo $p["id_menu"];?>',
			init:function(){
				var panel = Ext.create('Ext.form.Panel',{
					id:salidaRuta.id+'-form',
					bodyStyle: 'background: transparent',
					border:false,
					layout:'border',
					defaults:{
						border:false
					},
					tbar:[
							{
								xtype:'fieldcontainer',
								layout:'column',
								defaults:{
									padding: '5 2 2 2'
								},
								height:60,
								width:900,
								items:[
										{
											xtype:'textfield',
											fieldLabel:'N° Manifiesto',
											labelWidth:80,
											columnWidth:0.2
										},
										{
											xtype:'combo',
											fieldLabel:'Courier:',
											labelWidth:40,
											columnWidth:0.2
										},
										{
											xtype:'combo',
											fieldLabel:'Estado',
											labelWidth:40,
											columnWidth:0.2
										},
										{
											xtype:'combo',
											fieldLabel:'Tipo de Zona',
											labelWidth:80,
											columnWidth:0.2
										},
										{
											xtype:'combo',
											fieldLabel:'Unidad',
											labelWidth:40,
											columnWidth:0.2
										},
										{
											xtype:'combo',
											fieldLabel:'Agencia',
											labelWidth:45,
											columnWidth:0.2
										},
										{
											xtype:'datefield',
											fieldLabel:'Desde:',
											labelWidth:40,
											columnWidth:0.2
										},
										{
											xtype:'datefield',
											fieldLabel:'Hasta',
											labelWidth:40,
											columnWidth:0.2
										},
										{
											xtype:'button',
											text:'',
											columnWidth:0.04	
										}
								]
							}
							


					],
					items:[

					]
				});
				tab.add({
					id:salidaRuta.id+'-tab',
					border:false,
					autoScroll:true,
					closable:true,
					layout:{
						type:'fit'
					},
					items:[
						panel
					],
					listeners:{
						beforerender: function(obj, opts){
	                        global.state_item_menu(salidaRuta.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,salidaRuta.id_menu);
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(salidaRuta.id_menu, false);
	                    }
					}

				}).show();
			}
		}
		Ext.onReady(salidaRuta.init,salidaRuta);
	}else{
		tab.setActiveTab(salidaRuta.id+'-tab');
	}
</script>