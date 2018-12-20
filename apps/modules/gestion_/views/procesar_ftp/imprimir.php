<script type="text/javascript">
	var imprimir = {
		id:'imprimir',
		url:'/gestion/procesarftp/',
		tipo:1,
		solicitud:"<?php echo $p['solicitud'];?>",
		init:function(){
			var panel = Ext.create('Ext.panel.Panel',{ 
				id:imprimir.id + '-form',
				border:false,
				defaults:{
					border:false,
					padding:'10 15 10 15',
				},

				//width: 300,,
    			layout: 'column',
				items:[
						{
							region:'west',
							layout:'column',
							columnWidth:0.7,
							border:false,
							items:[
									{
										xtype:'combo',
										margin:'0 0 5 0',
										columnWidth:1,
										labelWidth:100,
										id:imprimir.id+'-cbo-tipo',
										fieldLabel:'Tipo Impresión:',
										store: Ext.create('Ext.data.Store', { 
											fields:[
													{name: 'id_elemento', type: 'int'},
													{name: 'descripcion', type: 'string'},
											],
											proxy:{
												type:'ajax',
												url:imprimir.url + 'get_scm_tabla_detalle_filtro/',
												reader:{
													type:'json',
													rootProperty:'data'
												}
											}
										}),
										queryMode:'local',
										valueField:'id_elemento',
										displayField:'descripcion',
										listConfig:{
											minWidth:200
										},
										width:255,
										forceSelection:true,
										allowBlank:false,
										emptyText:'[ Seleccione Reporte]',
										listeners:{
											afterrender: function(obj){
												obj.getStore().load({
													params:{vp_tab_id:'TFM',vp_shipper:0},
												});
											},
											select:function(obj,record,opts){
												Ext.getCmp(imprimir.id+'-cbo-etiqueta').getStore().load({
													params:{vp_solicitud:imprimir.solicitud,vp_tipo:obj.getValue()}
												});
											}
										}

									},
									{
										xtype:'radiogroup',
										id:imprimir.id+'-rbtn_orden',
										labelWidth:100,
										columnWidth:1,
										fieldLabel:'Compaginado',
										columns:2,
										vertical:true,
										items:[
												{boxLabel: 'Normal', name:imprimir.id+'-rbtn2',inputValue:'N',width:80, checked:true},
												{boxLabel: 'Apilado', name:imprimir.id+'-rbtn2',inputValue:'A',width:80},
										]
									},
									{
										xtype:'combo',
										id:imprimir.id+'-cbo-etiqueta',
										columnWidth:1,
										labelWidth:100,
										fieldLabel:'Formato:',
										store: Ext.create('Ext.data.Store', { 
											fields:[
													{name: 'frm_descri', type: 'string'},
													{name: 'frm_class', type: 'string'},
											],
											proxy:{
												type:'ajax',
												url:imprimir.url + 'ftp_procesar_lista_etiquetas/',
												reader:{
													type:'json',
													rootProperty:'data'
												}
											}
										}),
										queryMode:'local',
										valueField:'frm_class',
										displayField:'frm_descri',
										listConfig:{
											minWidth:200
										},
										width:255,
										forceSelection:true,
										allowBlank:false,
										emptyText:'[ Seleccione Reporte]',
										listeners:{
											select:function(obj,record,opts){
												console.log(obj.getValue());
											}
										}

									}
							]
						},
						{	
							region:'center',
							columnWidth:0.3,
							border:false,
							items:[
									{ 
										xtype:'image',
										id:imprimir.id+'-imgen',
										width	:	80,
										height	:	80,
										src:'/images/icon/imprimir.png',
									}
							]
						}
						
				]
			});

			Ext.create('Ext.window.Window',{
				id:imprimir.id+'-win',
				title:'Gestor de Impresiones',
				//heigth:100,
				width:480,
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
							  	dock: 'bottom',
							  	ui:'footer',
							  	alignTarget:'center',
							  	layout:{
							  		pack:'center'
							  	},
							  	baseCls:'gk-toolbar',
							  	items:[
							  			{
							  				text:'Imprimir',
							  				id: imprimir.id +'-btn-imprimir',
							  				icon:'/images/icon/pdf.png',
							  				listeners:{
							  					click:function(obj,e){
							  						imprimir.imprimir();
							  					}
							  				}
							  			},
							  			{
							  				text:'Salir',
							  				id:imprimir.id +'-btn-salir',
							  				icon:'/images/icon/get_back.png',
							  				listeners:{
							  					click:function(obj,e){
							  						Ext.getCmp(imprimir.id+'-win').close();
							  					}
							  				}
							  			}

							  	]
							  }
				]
			}).show().center();
		},
		imprimir:function(){
			comand = Ext.getCmp(imprimir.id+'-cbo-etiqueta').getValue();
			window.open(imprimir.url + comand+'/?id_solicitud='+imprimir.solicitud);
		}
	}
	Ext.onReady(imprimir.init,imprimir)
</script>