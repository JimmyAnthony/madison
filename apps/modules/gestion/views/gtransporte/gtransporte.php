<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if (!Ext.getCmp('gtransporte-tab')){
		var gtransporte = {
			id:'gtransporte',
			id_menu:'<?php echo $p["id_menu"];?>',
			url:'/gestion/gtransporte/',
			map_g:null,
			directionsDisplay:null,
			data:{
				cnt:1
			},
			tplInciPanel:new Ext.XTemplate(
					'<tpl for=".">',
						'<div class="gt-tpl-box">',
							'<div class="gt-tpl-1">Se Encontraron</div>',
							'<div class="gt-tpl-2"> {cnt} </div>',
							'<div class="gt-tpl-3">Programaciones en el Mismo Horario, Puedes cambiarlo o esperar la Confimación del Dispacher</div>',
						'</div>',
					'</tpl>'
			),
			objeto : {
				width:700,
                start:false,
                fous:true	
			},
			objeto_south : {
                start:false,
                fous:true	
			},
			init:function(){
				/*var data ={
					cnt:1
				};*/

				var panel = Ext.create('Ext.form.Panel',{
					id:gtransporte.id+'-form',
					border:false,
					layout:'border',
					defaults:{
						border:false
					},
					items:[
							{
								xtype:'panel',
								region:'west',
								//title:'Solicitar Servicio de Transporte',
								id:gtransporte.id+'-slider',
								//titleCollapse:false,
								hideCollapseTool:true,
								hidden:true,
								//layout:'fit',
								//collapsible: true,
								collapsed:true,
								scrollable:true,
								width:700,
								layout:'column',
								border:false,
								tbar:[
										{
											text:'',
											icon:'/images/icon/new_file.ico'
										},
										{
											text:'',
											icon:'/images/icon/save.png',
											listeners:{
												click:function(obj, e){
												}
											}
										},
										{
											text:'',
											icon:'/images/icon/get_back.png',
											listeners:{
												click:function(obj, e){
													Ext.getCmp(panel_transporte.id+'-win').show();	
													gtransporte.get_menu_sh(gtransporte.objeto);
													
												}

											}
										}
								],
								items:[
										{
											xtype:'fieldset',
											title:'Datos del Cliente',
											layout:'column',
											columnWidth:1,
											margin:'5 8 5 8',
											items:[
													{
														xtype:'combo',
														columnWidth: 0.5,
														id:gtransporte.id+'-shipper',
														padding:'0 0 5 0',
														fieldLabel:'Shipper',
														labelWidth:50,
														store:Ext.create('Ext.data.Store',{
														fields:[
																{name: 'shi_codigo', type: 'int'},
								                                {name: 'shi_nombre', type: 'string'},
								                                {name: 'shi_id', type: 'string'}
														],
														proxy:{
															type:'ajax',
															url:gtransporte.url+'get_usr_sis_shipper/',
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
														width:250,
														forceSelection:true,
														allowBlank:false,
														selecOnFocus:true,
														emptyText:'[ Seleccione ]',
														listeners:{
															afterrender:function(obj,record,options){
																obj.getStore().load({
																	params:{
																		vp_linea:3
																	},
																	/*callback:function(){
																		obj.setValue(0);
																	}*/
																});
															},
															'select':function(obj,records,eOpts){
																//new_orden.cabecera(true);
																gtransporte.block_field(false);
																Ext.getCmp(gtransporte.id+'-shipper').setReadOnly(true);
															}
														}
													},
													{
														xtype:'radiogroup',
														id:gtransporte.id+'-rbtn-group-linea',
														columnWidth:0.5,
														margin:'0 0 0 30',
														fieldLabel:'Linea',
														columns:3,
														vertical:true,
														labelWidth:35,
														items:[
															{boxLabel:'Logistica',name:gtransporte.id+'-rbtn',inputValue:'3',width:80,checked:true},
															{boxLabel:'Masivo',name:gtransporte.id+'-rbtn',inputValue:'1',width:60},
															{boxLabel:'Valorados',name:gtransporte.id+'-rbtn',inputValue:'2',width:80}
														]
													}
											]
										},
										{
											xtype:'fieldset',
											title:'Origen del Servicio',
											layout:'column',
											margin:'5 8 5 8',
											columnWidth:0.5,
											items:[
													{
														xtype:'radiogroup',
														columnWidth:0.4,
														id:gtransporte.id+'-rbtn-group-origen',
														columns:1,
														vertical:false,
														labelWidth:40,
														items:[
																{boxLabel:'Agencia Urbano',name:gtransporte.id+'-rbtn-origen',inputValue:'1', width:110,checked:true},
																{boxLabel:'Agencia Shipper',name:gtransporte.id+'-rbtn-origen',inputValue:'2', width:110},
																{boxLabel:'Otra Dirección',name:gtransporte.id+'-rbtn-origen',inputValue:'3', width:100},
														],
														listeners:{
															change:function(obj,newValue,oldValue,eOpts){
																var op = parseInt(newValue[gtransporte.id+'-rbtn-origen']);
																var shipper = Ext.getCmp(gtransporte.id+'-shipper').getValue();
																Ext.getCmp(gtransporte.id+'-origen-agencia-shipper').setValue('');

																if ( op == 1 || op == 2){
																	if (op == 1){
																		Ext.getCmp(gtransporte.id+'-origen-agencia-shipper').store.load({params:{va_shipper:6}});
																	}else if (op == 2){
																		Ext.getCmp(gtransporte.id+'-origen-agencia-shipper').store.load({params:{va_shipper:shipper}});
																	}
																	Ext.getCmp(gtransporte.id+'-origen-agencia-shipper').show();
																}else{
																	Ext.getCmp(gtransporte.id+'-origen-agencia-shipper').hide();
																}

															}
														}
													},
													{
														xtype:'combo',
														id:gtransporte.id+'-origen-agencia-shipper',
														//hidden:true,
														columnWidth:0.6,
														fieldLabel:'Agencia',
														labelWidth:50,
														store: Ext.create('Ext.data.Store',{
															fields:[
																	{name:'id_agencia',type:'int'},
																	{name:'agencia' ,type:'string'},
																	{name:'dir_calle' ,type:'string'},
																	{name:'ciu_id',type:'int'},
																	{name:'ciu_ubigeo',type:'string'},
																	{name:'dir_px',type:'auto'},
																	{name:'dir_py',type:'auto'},
																	               
															],
															proxy:{
																type:'ajax',
																url:gtransporte.url+'getAgencia_shipper/',
																reader:{
																	type:'json',
																	rootProperty:'data'
																}
															}
														}),
														queryMode:'local',
														valueField:'id_agencia',
														displayField:'agencia',
														listConfig:{
															minWidth:200
														},
														width:220,
														forceSelection:true,
														allowBlank:false,
														emptyText:'[ Seleccione ]',
														listeners:{
															'select':function(obj,records,eOpts){
															},
															beforerender:function(){
																Ext.getCmp(gtransporte.id+'-origen-agencia-shipper').store.load({params:{va_shipper:6}});
															}
														}
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-origen-distrito',
														labelAlign:'top',
														columnWidth:1,
														readOnly:true,
														fieldLabel:'Distrito/Localidad',
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-origen-direccion',
														labelAlign:'top',
														columnWidth:1,
														readOnly:true,
														fieldLabel:'Dirección'
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-origen-contacto',
														labelAlign:'top',
														columnWidth:1,
														fieldLabel:'Contacto',
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-origen-telefono',
														labelAlign:'top',
														columnWidth:0.5,
														fieldLabel:'Telefono'
													},
													{
														xtype:'combo',
														id:gtransporte.id+'-origen-area',
														margin:'0 0 0 5',
														padding:'0 0 10 0',
														labelAlign:'top',
														columnWidth:0.5,
														fieldLabel:'Area'
													}
											]
										},
										{
											xtype:'fieldset',
											margin:'5 8 5 8',
											title:'Destino del Servicio',
											layout:'column',
											columnWidth:0.5,
											items:[
													{
														xtype:'radiogroup',
														columnWidth:0.4,
														id:gtransporte.id+'-rbtn-group-destino',
														columns:1,
														vertical:false,
														labelWidth:40,
														items:[
																{boxLabel:'Agencia Urbano',name:gtransporte.id+'-rbtn-destino',inputValue:'1', width:110,checked:true},
																{boxLabel:'Agencia Shipper',name:gtransporte.id+'-rbtn-destino',inputValue:'2', width:110},
																{boxLabel:'Otra Dirección',name:gtransporte.id+'-rbtn-destino',inputValue:'3', width:100}
														],
														listeners:{
															change:function(obj,newValue,oldValue,eOpts){
																var op = parseInt(newValue[gtransporte.id+'-rbtn-destino']);
																var shipper = Ext.getCmp(gtransporte.id+'-shipper').getValue();
																Ext.getCmp(gtransporte.id+'-destino-agencia-shipper').setValue('');

																if ( op == 1 || op == 2){
																	if (op == 1){
																		Ext.getCmp(gtransporte.id+'-destino-agencia-shipper').store.load({params:{va_shipper:6}});
																	}else if (op == 2){
																		Ext.getCmp(gtransporte.id+'-destino-agencia-shipper').store.load({params:{va_shipper:shipper}});
																	}
																	Ext.getCmp(gtransporte.id+'-destino-agencia-shipper').show();
																}else{
																	Ext.getCmp(gtransporte.id+'-destino-agencia-shipper').hide();
																}

															}
														}
													},
													{
														xtype:'combo',
														id:gtransporte.id+'-destino-agencia-shipper',
														//hidden:true,
														columnWidth:0.6,
														fieldLabel:'Agencia',
														labelWidth:50,
														store: Ext.create('Ext.data.Store',{
															fields:[
																	{name:'id_agencia',type:'int'},
																	{name:'agencia' ,type:'string'},
																	{name:'dir_calle' ,type:'string'},
																	{name:'ciu_id',type:'int'},
																	{name:'ciu_ubigeo',type:'string'},
																	{name:'dir_px',type:'auto'},
																	{name:'dir_py',type:'auto'},
																	               
															],
															proxy:{
																type:'ajax',
																url:gtransporte.url+'getAgencia_shipper/',
																reader:{
																	type:'json',
																	rootProperty:'data'
																}
															}
														}),
														queryMode:'local',
														valueField:'id_agencia',
														displayField:'agencia',
														listConfig:{
															minWidth:200
														},
														width:220,
														forceSelection:true,
														allowBlank:false,
														emptyText:'[ Seleccione ]',
														listeners:{
															'select':function(obj,records,eOpts){
															},
															beforerender:function(){
																Ext.getCmp(gtransporte.id+'-destino-agencia-shipper').store.load({params:{va_shipper:6}});
															} 
														}
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-destino-distrito',
														labelAlign:'top',
														columnWidth:1,
														readOnly:true,
														fieldLabel:'Distroto/Localidad',
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-destino-direccion',
														labelAlign:'top',
														columnWidth:1,
														readOnly:true,
														fieldLabel:'Dirección'
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-destino-contacto',
														labelAlign:'top',
														columnWidth:1,
														fieldLabel:'Contacto',
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-destino-telefono',
														labelAlign:'top',
														columnWidth:0.5,
														fieldLabel:'Telefono'
													},
													{
														xtype:'combo',
														id:gtransporte.id+'-destino-area',
														margin:'0 0 0 5',
														padding:'0 0 10 0',
														labelAlign:'top',
														columnWidth:0.5,
														fieldLabel:'Area'
													}
											]
										},
										{
											xtype:'fieldset',
											title:'Datos de la Carga',
											layout:'column',
											margin:'5 8 5 8',
											columnWidth:0.6,
											items:[
													{
														xtype:'combo',
														id:gtransporte.id+'-t-empaque',
														fieldLabel:'Tipo de Empaque',
														columnWidth:1,
														store:Ext.create('Ext.data.Store',{
															fields:[
																	{name:'temp_id',type:'int'},
																	{name:'temp_descri',type:'string'}
															],
															proxy:{
																type:'ajax',
																url:gtransporte.url+'get_tipo_empaque/',
																reader:{
																	type:'json',
																	rootProperty:'data'
																}
															}
														}),
														queryMode:'local',
														valueField:'temp_id',
														displayField:'temp_descri',
														listConfig:{
															minWidth:120
														},
														forceSelection:false,
														allowBlank:true,
														emptyText:'[ Seleccione ]',
														listeners:{
															afterrender:function(obj){
																obj.getStore().load({});
															}
														}
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-piezas',
														fieldLabel:'N° de Piezas',
														labelAlign:'top',
														columnWidth:0.5
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-peso',
														fieldLabel:'Peso Aprox. (Kg)',
														labelAlign:'top',
														margin:'0 0 5 10',
														columnWidth:0.5
													},
													{
														xtype:'label',
														text:'* Medidas de la Carga (Si es Grande)',
														margin: '0 0 5 0',
														columnWidth:1
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-largo',
														fieldLabel:'Largo',
														labelWidth:30,
														columnWidth:0.33
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-alto',
														fieldLabel:'Alto',
														margin:'0 0 0 5',
														labelWidth:27,
														columnWidth:0.33
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-ancho',
														fieldLabel:'Ancho:',
														margin:'0 0 0 5',
														labelWidth:35,
														columnWidth:0.33
													},
													{
														xtype:'textfield',
														id:gtransporte.id+'-des-servicio',
														fieldLabel:'Descripción del Servicio',
														labelAlign:'top',
														columnWidth:1,
													},
													{
														xtype:'textareafield',
														id:gtransporte.id+'-anotaciones',
														fieldLabel:'Anotaciones',
														labelAlign:'top',
														margin:'0 0 10 0',
														columnWidth:1
													},
											]
										},
										{
											xtype:'fieldset',
											title:'Horarios',
											layout:'border',
											height:230,
											margin:'5 8 5 8',
											columnWidth:0.4,
											items:[
													{
														region:'west',
														border:false,
														//width:200,
														layout:'column',
														defaults:{
															margin:'0 0 5 0'
														},
														items:[
																{
																	xtype:'datefield',
																	id:gtransporte.id+'-fecha',
																	width:'100%',
																	columnWidth:1,
																	value:new Date()
																},
																{
																	xtype:'textfield',
																	id:gtransporte.id+'h-minima',
																	fieldLabel:'Hora Minima',
																	columnWidth:1,
																	labelWidth:80
																},
																{
																	xtype:'textfield',
																	id:gtransporte.id+'h-maxima',
																	fieldLabel:'Hora Maxima',
																	columnWidth:1,
																	labelWidth:80
																}
														]
													},
													{
														region:'south',
														border:false,
														//width:100,
														height:120,
														items:[
																{
																	xtype:'label',
																	id:gtransporte.id+'-pendiente',
																	listeners:{
																		afterrender:function(obj,eOpts){
																			var objeto = Ext.get(gtransporte.id+'-pendiente');
													                      	gtransporte.tplInciPanel.overwrite(objeto,gtransporte.data);
																		},
																	}
																}
														]
													}
											]
										}
									
								],
								listeners:{
									collapse:function( obj, eOpts ){
									},
									afterrender:function( obj, eOpts ){
									}
									
								}
							},
							{
								xtype:'panel',
								region:'center',
								id:gtransporte.id+'cont_map',
								layout:'fit',
								html:'<div id="'+gtransporte.id+'Mapsa" class="ue-map-canvas"></div>',
								listeners:{
									boxready:function(self){

									}
								}
							},
							{
								xtype:'panel',
								region:'south',
								layout:'fit',
								id:gtransporte.id+'slider-south',
								//collapsed:true,
								scrollable:true,
								border:false,
								hidden:true,
								//width:200,
								height:'100%',
								tbar:[
										{
											xtype:'button'
										}
								],
								items:[
										{
                                           xtype: 'findlocation',
                                           id: gtransporte.id + '-bdireccion',
                                           mapping: false
                                        },
                                ]        

							}
					]
				});

				tab.add({
					id:gtransporte.id+'-tab',
					border:false,
					autoScroll:true,
					closable:true,
					layout:'border',
					autoDestroy: true,
					items:[
						{
							region:'west',
							split: true,
		                    collapsible: true,
		                    floatable: false,
							border:true,
							width:200,
							items:[]
						},
						{
							region:'center',
							layout:'border',
							border:false,
							items:[
								{
									region:'center',
									layout:'fit',
									border:false,
									items:panel
								},
								{
									region:'south',
									border:false,
									height:150,
									split: true,
				                    collapsible: true,
				                    floatable: false,
									items:[]
								}
							]
						}
					],
					listeners:{
						beforerender: function(obj, opts){
	                        global.state_item_menu(gtransporte.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        /*Ext.getCmp(gtransporte.id+'-tab').setConfig({
	                            title: Ext.getCmp('menu-' + gtransporte.id_menu).text,
	                            icon: Ext.getCmp('menu-' + gtransporte.id_menu).icon
	                        }); */
							gtransporte.block_field(true);
	                        global.state_item_menu_config(obj,gtransporte.id_menu);
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(gtransporte.id_menu, false);
	                    	try{
	                    		Ext.getCmp(gtransporte.id+'-tab').destroy();
	                    		Ext.getCmp(gtransporte.id+'-tab').close();	
	                    	}catch(err){
	                    	}
	                    	
	                    	try{
	                    		Ext.getCmp(panel_transporte.id+'-win').destroy();
	                    		Ext.getCmp(panel_transporte.id+'-win').close();
	                    	}catch(err){
	                    	}

	                    	try{
	                    		Ext.getCmp(panel_orden.id+'-win').destroy();
	                    		Ext.getCmp(panel_orden.id+'-win').close();
	                    	}catch(err){
	                    	}

	                    },
	                    boxready:function( obj, width, height, eOpts ){
	                    	win.show({vurl: gtransporte.url + 'form_transporte/', id_menu: gtransporte.id_menu, class: ''});
	                    	//win.show({vurl: gtransporte.url + 'form_orden/', id_menu: gtransporte.id_menu, class: ''});
	                    	gtransporte.load_map();
	                    }
					}

				}).show();

			},
			load_map: function(){
		        
		        var directionsService = new google.maps.DirectionsService();
		        var map;

		        var directionsDisplay = new google.maps.DirectionsRenderer();
		        var argentina = new google.maps.LatLng(-12.0473179,-77.0824867);
		        var mapOptions = {
		            zoom:7,
		            center: argentina
		        };
		        map = new google.maps.Map(document.getElementById(gtransporte.id+'Mapsa'), mapOptions);
		        directionsDisplay.setMap(map);

		    },
		    origen_servicio:function(estado){
		    	Ext.getCmp(gtransporte.id+'-rbtn-group-origen').setReadOnly(estado);
		    	Ext.getCmp(gtransporte.id+'-origen-agencia-shipper').setReadOnly(estado);
		    	Ext.getCmp(gtransporte.id+'-origen-contacto').setReadOnly(estado);
		    	Ext.getCmp(gtransporte.id+'-origen-telefono').setReadOnly(estado);
		    	Ext.getCmp(gtransporte.id+'-origen-area').setReadOnly(estado);
		    },
		    destino_servicio:function(estado){
		    	Ext.getCmp(gtransporte.id+'-rbtn-group-destino').setReadOnly(estado);
		    	Ext.getCmp(gtransporte.id+'-destino-agencia-shipper').setReadOnly(estado);
		    	Ext.getCmp(gtransporte.id+'-destino-contacto').setReadOnly(estado);
		    	Ext.getCmp(gtransporte.id+'-destino-telefono').setReadOnly(estado);
		    	Ext.getCmp(gtransporte.id+'-destino-area').setReadOnly(estado);	
		    },
		    dato_carga:function(estado){
				Ext.getCmp(gtransporte.id+'-t-empaque').setReadOnly(estado);	
				Ext.getCmp(gtransporte.id+'-piezas').setReadOnly(estado);	
				Ext.getCmp(gtransporte.id+'-peso').setReadOnly(estado);	
				Ext.getCmp(gtransporte.id+'-largo').setReadOnly(estado);	
				Ext.getCmp(gtransporte.id+'-alto').setReadOnly(estado);	
				Ext.getCmp(gtransporte.id+'-ancho').setReadOnly(estado);	
				Ext.getCmp(gtransporte.id+'-des-servicio').setReadOnly(estado);	
				Ext.getCmp(gtransporte.id+'-anotaciones').setReadOnly(estado);	
		    },
		    horarios:function(estado){
				Ext.getCmp(gtransporte.id+'-fecha').setReadOnly(estado);
				Ext.getCmp(gtransporte.id+'h-minima').setReadOnly(estado);
				Ext.getCmp(gtransporte.id+'h-maxima').setReadOnly(estado);
		    },
		    block_field:function(estado){
		    	gtransporte.origen_servicio(estado);
		    	gtransporte.destino_servicio(estado);
		    	gtransporte.dato_carga(estado);
		    	gtransporte.horarios(estado);
		    },
		    get_menu_sh:function(obj){
                var menu = Ext.getCmp(gtransporte.id+'-slider');
                if(obj.fous)menu.setVisible(!obj.start);
                menu.animate({
                   duration: 200,
                    to: {
                        width: ((!obj.start)?obj.width:0)
                    }
                });
                menu.doLayout();
                obj.start = (!obj.start);
                obj.fous=false;
            },
            get_south:function(obj){
            	var menu = Ext.getCmp(gtransporte.id+'slider-south');
            	if(obj.fous)menu.setVisible(!obj.start);

            	//menu.doLayout();
                obj.start = (!obj.start);
                obj.fous=false;

            }

		}

		Ext.onReady(gtransporte.init,gtransporte);
	}else{
		tab.setActiveTab(gtransporte.id+'-tab');
	}

</script>