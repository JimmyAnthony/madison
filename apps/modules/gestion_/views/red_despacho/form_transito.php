<script type="text/javascript">

	var form_transito_ = {
		id:'form_transito_',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/red_despacho/',
		vp_tipo:'<?php echo $_GET["vp_tipo"];?>',
		prov_codigo:'<?php echo $_GET["vp_prov_codigo"];?>',
		pointFirst:{},
		ciu_id:0,
		init:function(){
			
			Ext.tip.QuickTipManager.init();

			Ext.define('Task', {
			    extend: 'Ext.data.TreeModel',
			    fields: [
			        {name: 'id1', type:'int'},
					{name: 'id2', type:'int'},
					{name: 'distrito', type:'string'},
					{name: 'estado', type:'int'},
					{name: 'dia', type:'string'},
					{name: 'dia_', type:'string'},
					{name: 'hor_salida', type: 'date', dateFormat:'H:i'},
					{name: 'haul_time', type:'string'},
					{name: 'haul_arribo', type:'string'},
					{name: 'haul_diario', type:'string'},
					{name: 'haul_parada', type:'string'},
					{name: 'tiempo_transito', type:'string'},
					{name: 'tiempo_transito_t', type:'string'},
					{name: 'tiempo_transito', type:'string'},
					{name: 'dir_px', type:'string'},
					{name: 'dir_py', type:'string'},
					{name: 'x', type:'string'},
					{name: 'y', type:'string'},
					{name: 'info', type:'string'}
			    ]
			});

		var store = Ext.create('Ext.data.TreeStore', {
	        model: 'Task',
	        autoLoad:false,
	        proxy: {
	            type: 'ajax',
	            url:form_transito_.url+'scm_red_distribucion/',
	            
	        },
	        lazyFill: true,
	        listeners:{
	        	beforeload: function (store, operation, opts) {
			        Ext.apply(operation, {
			            params: {
			                to: 'test1',
    						from: 'test2'
			            }
			       });
			    },
			    load:function(){
			    	var store_ = Ext.getCmp(form_transito_.id+'-tree-').getStore();
					var count =store_.getCount();
					if (count != 0){
						var record = store_.getAt(0);
						red_despacho.coord_origen_x=record.get('x');
						red_despacho.coord_origen_y=record.get('y');
					}
					form_transito_.get_distrito();
			    }
	        }
	    });

		var tree = Ext.create('Ext.tree.Panel', {
	        //title: 'Core Team Projects',
	        id:form_transito_.id+'-tree-',
	        height:270,
			width:550,
	        collapsible: false,
	        useArrows: false,
	        rootVisible: false,
	        store: store,
	        multiSelect: false,
	        columns: [
		        {
		            xtype: 'treecolumn', 
		            text: 'Distrito',
		            flex: 2.5,
		            sortable: true,
		            dataIndex: 'distrito',
		            renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                        metaData.style = "padding: 0px; margin: 0px";
                        //console.log(record.data);
                        if(parseInt(record.data.nivel)==2){
	                        return '<div style="height:13.4px;display:inline-block;width:65px;"><table width="65px"><tr><td width="60px";>'+value+'</td><td  width="5px"; style="background-color: #'+((parseInt(record.data.estado)==1)?'3CB371':'DC143C')+';"></td></tr></table></div>';
	                    }else{
	                    	return value;
	                    }
                    }
		        },
		        {
	                text: 'Line Haul Local',
	                columns: [
	                {
	                    text     : 'Salida',
	                    width    : 50,
	                    sortable : true,
	                    dataIndex: 'hor_salida',
	                    formatter: 'date("H:i")',
	                    editor:{
							xtype:'timefield',
							margin:'5 5 5 0',
							labelAlign: 'top',
							flex:1,
							format:'H:i',
							formatter: 'date("H:i")'
						}
	                },
	                {
	                    text     : 'Tiempo Transporte',
	                    width    : 110,
	                    sortable : true,
	                    dataIndex: 'haul_time',
	                    align:'center'
	                },
	                {
	                    text     : 'Arribo',
	                    width    : 50,
	                    sortable : true,
	                    dataIndex: 'haul_arribo'
	                },
	                {
	                    text     : 'Parada',
	                    width    : 60,
	                    sortable : true,
	                    dataIndex: 'haul_parada'
	                },
	                {
		                text     : 'Haul Local',
		                width    : 70,
		                sortable : true,
		                dataIndex: 'tiempo_transito',
		                renderer:form_transito_.myRenderColorGG,
		                align:'center'
		            }]
	            },
	            {
		            text: 'Ver',
		            width:30,
		            sortable: true,
                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                        metaData.style = "padding: 0px; margin: 0px";
                        //console.log(record.data);
                        if(parseInt(record.data.nivel)==1){
	                        return '<a href="#" onclick="form_transito_.showMap('+rowIndex+')"><img src="/images/icon/novedad_visto.png" style="padding:2px 5px 2px 5px;" /></a>';
	                    }else{
	                    	return '';	
	                    }
                    }
		        },
	            {
		            text: 'OP',
		            width:30,
		            sortable: true,
                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
                        metaData.style = "padding: 0px; margin: 0px";
                        //console.log(record.data);
                        if(parseInt(record.data.nivel)==1){
	                        return '<a href="#" onclick="form_transito_.show_mod('+rowIndex+')"><img src="/images/icon/edit.png" style="padding:2px 5px 2px 5px;" /></a>';
	                    }else{
	                    	return '';	
	                    }
                    }
		        }
		        ]
	    });

		Ext.create('Ext.window.Window',{
				id:form_transito_.id+'-win',
				title:'Distribución',
				height:270,
				width:550,
				resizable:false,
				closable:false,
				plain:true,
				minimizable: false,
				maximizable: false,
				constrain: true,
				constrainHeader:false,
				renderTo:Ext.get(red_despacho.id+'-tab'),//red_despacho.contenedor,
				header:true,
				border:false,
				layout:'border',
				modal:false,
				bbar:[
					'->',
					{
						text:'Salir',
						id:form_transito_.id+'-regresar',
						icon:'/images/icon/get_back.png',
						listeners:{
							click:function(obj,e){
								Ext.getCmp(form_transito_.id+'-win').destroy();
								Ext.getCmp(red_cobertura.id+'-win').show();
								try{
									document.getElementById('toll_box').innerHTML ='';
									document.getElementById("pac-input").style.visibility = "hidden";
								}catch(err){

								}
							}
						}
					}
				],
				items:[
					{
                        region:'center',
                        //frame:true,
                        border:false,
                        layout:'fit',
                        items:[
							tree,
                            {
                            	xtype:'panel',
                            	id:form_transito_.id+'man-tiempo',
                            	layout:'border',
                            	hidden:true,
                            	border:false,
                            	height:240,
								width:520,
								tbar:[
									'-',
									{
										text:'',
										html:'<div id="texto-distrito" style="font-weight:bold"></div>'
									},
									'->',
									{
										text:'Grabar',
										id:form_transito_.id+'-grabar-',
										//disabled:true,
										icon:'/images/icon/save.png',
										listeners:{
											click:function(obj,e){
												form_transito_.save_form(0);
											}
										}
									},
									'-',
									{
										text:'Regresar',
										icon:'/images/icon/get_back.png',
										listeners:{
											click:function(obj,e){
												form_transito_.setClearValuesForm();
												Ext.getCmp(form_transito_.id+'man-tiempo').hide();
												Ext.getCmp(form_transito_.id+'-tree-').show();
												Ext.getCmp(form_transito_.id+'-grabar-').setDisabled(true);
												try{
													document.getElementById('toll_box').innerHTML ='';
													document.getElementById("pac-input").style.visibility = "hidden";
												}catch(err){
													
												}
											}
										}
									},
									'-'
								],
                            	items:[
                            		{
                            			region:'center',
                            			border:false,
                            			layout: 'column',
                            			bbar:[
                            				'-',
                            				{
								                xtype: 'textfield',
								                id:form_transito_.id+'-x-cord',
								                width:160,
								                fieldLabel: 'X',
								                labelWidth:10,
								                readOnly:true,
								                disabled:true,
									            listeners:{
									            	change:function(obj){
									            		//form_transito_.save_form();
									            	}
									            }
								            },
								            '-',
								            {
								                xtype: 'textfield',
								                id:form_transito_.id+'-y-cord',
								                width:160,
								                fieldLabel: 'Y',
								                labelWidth:10,
								                readOnly:true,
								                disabled:true,
									            listeners:{
									            	change:function(obj){
									            		//form_transito_.save_form();
									            	}
									            }
								            },
								            '-',
								            {
										        xtype: 'checkboxfield',
										        name: 'checkbox1',
										        id:form_transito_.id+'-chk-cord',
										        fieldLabel: '',
										        boxLabel: 'Activar Mantenimiento',
										        listeners:{
										        	change: function (checkbox, newVal, oldVal) {
						                            	form_transito_.block_coord(newVal);
						                            }
										        }
										    }
                            			],
                            			items:[
                            				{
                            					xtype:'panel',
                            					columnWidth: 0.5,
                            					margin:'2px 5px 0px 15px',
                            					border:false,
                            					items:[
                            						{
		                            					xtype: 'fieldset',
		                            					margin:5,
		                            					title: '¿Transporte Diario?',		                            					
		                            					layout: 'column',
		                            					items:[		                            						
														    {
													            xtype: 'radiogroup',
													            //fieldLabel: '¿Activo?',
													            id:form_transito_.id+'diario-',
													            columnWidth: 1,
													            cls: 'x-check-group-alt',
													            labelWidth:1,
													            items: [
													                {id:form_transito_.id+'diario-a',boxLabel: 'SI', name: 'rb_auto_', inputValue: 1 ,labelWidth:10},
													                {id:form_transito_.id+'diario-b',boxLabel: 'NO', name: 'rb_auto_', inputValue: 2, checked: true,labelWidth:10}
													            ],
													            listeners:{
													            	change:function(obj){
													            		form_transito_.save_form(1);
													            	}
													            }
													        }
		                            					]
		                            				},
		                            				{
		                            					xtype: 'fieldset',
		                            					margin:5,
		                            					title: '¿Tiempo Transporte local?',
		                            					layout: 'column',
		                            					height:45,
		                            					items:[
														    {
												                xtype: 'textfield',
												                id:form_transito_.id+'-tiempo-transporte',
												                columnWidth: 1,
												                fieldLabel: '',
												                labelWidth:1,
												                plugins:  [new ueInputTextMask('99 99:99')],
													            listeners:{
													            	change:function(obj){
													            		//form_transito_.save_form();
													            	}
													            }
												            }
		                            					]
		                            				},
		                            				{
		                            					xtype: 'fieldset',
		                            					margin:5,
		                            					title: '¿Tiempo Parada?',
		                            					layout: 'column',
		                            					height:45,
		                            					items:[
														    {
												                xtype: 'textfield',
												                id:form_transito_.id+'-tiempo-parada',
												                columnWidth: 1,
												                fieldLabel: '',
												                labelWidth:1,
												                plugins:  [new ueInputTextMask('99 99:99')],
													            listeners:{
													            	change:function(obj){
													            		//form_transito_.save_form();
													            	}
													            }
												            }
		                            					]
		                            				}
                            					]
                            				},
                            				{
                            					xtype:'panel',
                            					columnWidth: 0.5,
                            					margin:'2px 20px 15px 10px',
                            					border:false,
                            					items:[
                            						{
		                            					xtype: 'fieldset',
		                            					margin:5,
		                            					id:form_transito_.id+'-aplica',
		                            					title: '¿Se Aplica para?',
		                            					//layout: 'column',
		                            					//height:45,
		                            					items:[
														    {
																xtype:'combo',
																margin:'2',
																//columnWidth: 0.1,
																id:form_transito_.id+'-dias',
																fieldLabel:'Días',
																store:Ext.create('Ext.data.Store',{
																	fields:[
																		{name: 'id_elemento', type:'int'},
																		{name: 'descripcion', type:'string'},
																		{name: 'dia_estado', type:'int'},
																		{name: 'hor_salida', type:'string'}
																	],
																	autoLoad:false,
																	proxy:{
																		type:'ajax',
																		url:red_despacho.url+'get_scm_red_distribucion_dias/',
																		reader:{
																			type:'json',
																			root:'data'
																		}
																	},
																	listeners:{
																		load: function(obj, records, successful, opts){    
													                    }
																	}
																}),
																queryMode: 'local',
																valueField: 'id_elemento',
																displayField: 'descripcion',
																width:'100%',
																anchor:'100%',
																labelWidth:40,
																forceSelection:true,
																empryText:'[ Seleccione]',
																listeners:{
																	afterrender:function(obj){
																		
																	},
																	'select':function(obj, records, eOpts){
																		Ext.getCmp(form_transito_.id+'-horas-salida').setValue(records.get('hor_salida'));

																		if(records.get('dia_estado')==1){
																			Ext.getCmp(form_transito_.id+'-act-a').setValue(true);
																		}else{
																			Ext.getCmp(form_transito_.id+'-act-b').setValue(true);
																		}
																	}
																}
															},
															{
													            xtype: 'radiogroup',
													            id:form_transito_.id+'-activo',
													            fieldLabel: '¿Activo?',
													            cls: 'x-check-group-alt',
													            labelWidth:55,
													            items: [
													                {id:form_transito_.id+'-act-a',boxLabel: 'SI', name: 'rb_auto', inputValue: 1 ,labelWidth:10},
													                {id:form_transito_.id+'-act-b',boxLabel: 'NO', name: 'rb_auto', inputValue: 0, checked: true,labelWidth:10}
													            ],
													            listeners:{
													            	change:function(obj){
													            		//form_transito_.save_form();
													            	}
													            }
													        }
		                            					]
		                            				},
		                            				{
		                            					xtype: 'fieldset',
		                            					margin:5,
		                            					title: '¿Hora Salida?',
		                            					layout: 'column',
		                            					height:45,
		                            					items:[
														    {
												                xtype: 'timefield',
												                id:form_transito_.id+'-horas-salida',
												                columnWidth: 1,
												                fieldLabel: 'Horas',
												                labelWidth:40,
												                width:'95%',
																anchor:'95%',
												                format:'H:i',
													            listeners:{
													            	change:function(obj){
													            		//form_transito_.save_form();
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
                            }
                    	]
                  	}
				],
				listeners:{
					show:function( window, eOpts ){
						 window.alignTo(Ext.get(red_despacho.id+'Mapsa'), 'bl-bl');
					},
					minimize: function(window,opts){
				   		window.collapse();
		                window.setWidth(100);
		                window.alignTo(Ext.get(red_despacho.id+'Mapsa'), 'bl-bl');
				   	},
				   	afterrender:function(){
				   		form_transito_.reload_grid_distribucion();
				   	}
				},
				tools: [{
		            type: 'restore',
		            handler: function (evt, toolEl, owner, tool) {
		                var window = owner.up('window');
		                window.setWidth(500);
		                window.expand('', false);
		               //window.center();
		                window.alignTo(Ext.get(red_despacho.id+'Mapsa'), 'bl-bl');
		            }
		        }]
			}).show();

		},
		ver_mapa:function(){
			//red_despacho.setMap();
			var coor=red_despacho.params.x;
			if(coor==null)coor='';
			if(coor==''){
	        	red_despacho.params.x=red_despacho.coord_origen_x;
	        	red_despacho.params.y = red_despacho.coord_origen_y;
	        }
			//var map = Ext.getCmp(red_despacho.id+'Mapsa');
			var myLatlng = new google.maps.LatLng(red_despacho.coord_origen_x,red_despacho.coord_origen_y);
			//var vl_destino = new google.maps.LatLng(red_despacho.coord_destino_x,red_despacho.coord_destino_y);
			var vl_distrito = new google.maps.LatLng(red_despacho.params.x,red_despacho.params.y);

			var mapOptions = {
				zoom: 18,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
			};

			//console.log(document.getElementById(red_despacho.id+'Mapsa'));
	        var map = new google.maps.Map(document.getElementById(red_despacho.id+'Mapsa'),mapOptions);
	       // var map = new google.maps.Map(Ext.get(red_despacho.id+'Mapsa'),mapOptions);
	       
	        var rendererOptions = {
				  draggable: false,
				  suppressMarkers: true
			};
			var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
			var directionsService = new google.maps.DirectionsService();
			
			
			form_transito_.markerPoint(map,myLatlng,'','/images/icon/transport_urbano.png');
			
			form_transito_.pointDist(map);
			directionsDisplay.setMap(map);
	
			
		    var request = {
			    origin:red_despacho.coord_origen_x+','+red_despacho.coord_origen_y,
			    destination:red_despacho.params.x+','+red_despacho.params.y,
			    optimizeWaypoints: true,
			    travelMode: google.maps.TravelMode.DRIVING,
			    provideRouteAlternatives: true,
			};

			directionsService.route(request, function(response, status){
			    if (status == google.maps.DirectionsStatus.OK) {
			      directionsDisplay.setDirections(response);
			      var distancia = red_despacho.params.info+'Distancia:'+(response.routes[0].legs[0].distance.text);//+'<br>Tiempo:'+(response.routes[0].legs[0].duration.text)
			      form_transito_.markerPoint(map,vl_distrito,distancia,'');
			    }
			});
				
			
			red_despacho.params.x=0;
			red_despacho.params.y=0;
			//red_despacho.params.info='';
		},
		markerPoint:function(map,coordenadas,string,image){
				string=(string==null)?'':string;
				if (string != ''){
					var contentString = '<div id="content"  style="width:225px;">'+
				      string +
				      '</div>';
					var infowindow = new google.maps.InfoWindow({
					      content: contentString,
					      maxWidth: 225
					}); 
				}     
				var marker = new google.maps.Marker({
				      position: coordenadas,
				      map: map,
				      title: 'Red de Distribucion Urbano',
				      icon: image
				});
				
				if (string != '')infowindow.open(map,marker);
			    google.maps.event.addListener(marker, 'click', function() {
			        infowindow.open(map,marker);
			    });
		    
		},
		new_hoario:function(prov_destino){
			win.show({vurl: form_transito_.url + 'new_horario/', id_menu: form_transito_.id_menu, class: ''});
		},
		myRenderColorB:function (value, metaData, record, rowIndex, colIndex, store, view) {
            metaData.tdStyle = 'background-color:#CCE5FF !important;';
        	return value
    	},
    	myRenderColorG:function (value, metaData, record, rowIndex, colIndex, store, view) {
            metaData.tdStyle = 'background-color:#606060 !important;color:#FFFFFF';
        	return value
    	},
    	myRenderColorGG:function (value, metaData, record, rowIndex, colIndex, store, view) {
            metaData.tdStyle = 'background-color:#3CB371 !important;color:#FFFFFF';
        	return value
    	},
		setClearValuesForm:function(){
			Ext.getCmp(form_transito_.id+'-dias').setValue('');
			Ext.getCmp(form_transito_.id+'-horas-salida').setValue('');
			Ext.getCmp(form_transito_.id+'-tiempo-parada').setValue('');
			Ext.getCmp(form_transito_.id+'-tiempo-transporte').setValue('');
			Ext.getCmp(form_transito_.id+'-x-cord').setDisabled(true);
			Ext.getCmp(form_transito_.id+'-y-cord').setDisabled(true);
			Ext.getCmp(form_transito_.id+'-chk-cord').setValue(false)
		},
		setValueFormtempo:function(store_,index){
			var record = store_.getAt(index);
			//Ext.getCmp(form_transito_.id+'-horas-salida').setValue(record.get('hor_salida'));
			Ext.getCmp(form_transito_.id+'-tiempo-parada').setValue(record.get('haul_parada'));
			Ext.getCmp(form_transito_.id+'-tiempo-transporte').setValue(record.get('haul_time'));
		},
		setDisabledAplica:function(bool){
			Ext.getCmp(form_transito_.id+'-dias').setDisabled(bool);
			//Ext.getCmp(form_transito_.id+'-activo').setDisabled(bool);
			Ext.getCmp(form_transito_.id+'-act-a').setDisabled(bool);
			Ext.getCmp(form_transito_.id+'-act-b').setDisabled(bool);
		},
		save_form:function(op){		
			

			var diario = Ext.getCmp(form_transito_.id+'diario-').getValue().rb_auto_;
			var activo = Ext.getCmp(form_transito_.id+'-activo').getValue().rb_auto;

			var tim_trans = Ext.getCmp(form_transito_.id+'-tiempo-transporte').getRawValue();
			var tim_para = Ext.getCmp(form_transito_.id+'-tiempo-parada').getRawValue();
			var tdia_id = Ext.getCmp(form_transito_.id+'-dias').getValue();
			
			var hor_sal = Ext.getCmp(form_transito_.id+'-horas-salida').getRawValue();

			var mant_coord=0;
			var coor_x='';
			var coor_y='';

			if(op!=1){
				if(diario!=1){
					if(tdia_id== null || tdia_id==''){
			            global.Msg({msg:"Seleccione el día por favor.",icon:2,fn:function(){}});
			            return false; 
			        }
		        }

				if(hor_sal== null || hor_sal==''){
		            global.Msg({msg:"Seleccione la hora de salida por favor.",icon:2,fn:function(){}});
		            return false; 
		        }

		        if(tim_trans== null || tim_trans==''){
		            global.Msg({msg:"Ingrese el tiempo de transporte por favor.",icon:2,fn:function(){}});
		            return false; 
		        }

		        if(tim_para== null || tim_para==''){
		            global.Msg({msg:"Ingrese el tiempo de parada por favor.",icon:2,fn:function(){}});
		            return false; 
		        }
		        mant_coord = Ext.getCmp(form_transito_.id+'-chk-cord').getValue();
		        mant_coord = (mant_coord)?1:0;
		        coor_x = Ext.getCmp(form_transito_.id+'-x-cord').getValue();
				coor_y = Ext.getCmp(form_transito_.id+'-y-cord').getValue();

				hora = hor_sal.split(':');
				if(parseInt(hora[0])>23){
					global.Msg({msg:"Ingrese una hora correcta por favor.",icon:2,fn:function(){}});
		            return false; 
				}

				if(parseInt(hora[1])>59){
					global.Msg({msg:"Ingrese minutos correctos por favor.",icon:2,fn:function(){}});
		            return false; 
				}
	        }
			var store_ = Ext.getCmp(form_transito_.id+'-tree-').getStore();
			var count =store_.getCount();
			var record = store_.getAt(form_transito_.index);
			
    		Ext.Ajax.request({
				url:form_transito_.url+'scm_red_distribucion_graba_horarios/',
				params:{
					vp_op:op,vp_id:record.get('id1'),vp_haul_diario:diario,vp_tdia_id:tdia_id,vp_hor_salida:hor_sal,
					vp_haul_time:tim_trans,vp_haul_parada:tim_para,vp_dia_estado:activo,vp_mant_coord:mant_coord,
					vp_coor_x:coor_x,vp_coor_y:coor_y
				},
				success:function(response,options){
					var res = Ext.decode(response.responseText);
					//console.log(res);
					///*****Terrestre****//
					if(res.data[0].rpt==1){
						if(op!=1)global.Msg({msg:"Registro Modificado Correctamente.",icon:1,fn:function(){}});
						var ciu_id = '';//Ext.getCmp(form_transito_.id+'-origen').getValue();
						Ext.getCmp(form_transito_.id+'-tree-').getStore().load({
							params:{vp_prov_origen:form_transito_.prov_origen,vp_prov_codigo:form_transito_.prov_codigo,vp_ciu_id:ciu_id,vp_tipo:form_transito_.vp_tipo},
							callback:function(){
								form_transito_.setDatosForm();
							}
						});
					}
    			}
    		});
			    	
		},
		reload_grid_distribucion:function(){
			//,vp_ciu_id:obj.getValue()
			Ext.getCmp(form_transito_.id+'-tree-').getStore().removeAll();

			Ext.apply(Ext.getCmp(form_transito_.id+'-tree-').getStore().proxy.extraParams, {
				vp_prov_origen:form_transito_.prov_origen,vp_prov_codigo:form_transito_.prov_codigo,vp_tipo:form_transito_.vp_tipo
			});
			Ext.getCmp(form_transito_.id+'-tree-').getStore().load({
				params:{vp_prov_origen:form_transito_.prov_origen,vp_prov_codigo:form_transito_.prov_codigo,vp_tipo:form_transito_.vp_tipo},
				callback:function(){
					
				}
			});
		},
		show_mod:function(index){
			Ext.getCmp(form_transito_.id+'man-tiempo').show();
			Ext.getCmp(form_transito_.id+'-tree-').hide();
			Ext.getCmp(form_transito_.id+'-grabar-').setDisabled(false);
			form_transito_.index=index;
			form_transito_.setDatosForm();
		},
		showMap:function(index){
			form_transito_.index=index;
			var store_ = Ext.getCmp(form_transito_.id+'-tree-').getStore();
			var count =store_.getCount();
			if (count != 0){
				var record = store_.getAt(form_transito_.index);
				red_despacho.coord_origen_x=record.get('x');
				red_despacho.coord_origen_y=record.get('y');
				red_despacho.params.x=record.get('dir_px');
				red_despacho.params.y=record.get('dir_py');
				red_despacho.params.info=record.get('info');
				red_despacho.coord_destino_x=0;
				//Ext.getCmp(form_transito_.id+'-gear1').setDisabled(false);
				form_transito_.ciu_id=record.get('ciu_id');
				form_transito_.ver_mapa();
			}
		},
    	setDatosForm:function(){
			var store_ = Ext.getCmp(form_transito_.id+'-tree-').getStore();
			var count =store_.getCount();
			form_transito_.setClearValuesForm();
			if (count != 0){
				var record = store_.getAt(form_transito_.index);
				form_transito_.setValueFormtempo(store_,form_transito_.index);
				document.getElementById('texto-distrito').innerHTML = record.get('distrito');
				Ext.getCmp(form_transito_.id+'-x-cord').setValue(record.get('dir_px'));
				Ext.getCmp(form_transito_.id+'-y-cord').setValue(record.get('dir_py'));
				if (record.get('haul_diario') == 'S'){
					Ext.getCmp(form_transito_.id+'diario-a').setValue(true);
					form_transito_.setDisabledAplica(true);
					Ext.getCmp(form_transito_.id+'-horas-salida').setValue(record.get('hor_salida'));
				}else{
					form_transito_.reload_dias();
					Ext.getCmp(form_transito_.id+'diario-b').setValue(true);
					form_transito_.setDisabledAplica(false);
				}
				red_despacho.coord_origen_x=record.get('x');
				red_despacho.coord_origen_y=record.get('y');
				red_despacho.params.x=record.get('dir_px');
				red_despacho.params.y=record.get('dir_py');
				red_despacho.params.info=record.get('info');
				red_despacho.coord_destino_x=0;
				form_transito_.ciu_id=record.get('ciu_id');
				//Ext.getCmp(form_transito_.id+'-gear1').setDisabled(false);
				form_transito_.ver_mapa();
			}
		},
		reload_dias:function(id){
			var store_ = Ext.getCmp(form_transito_.id+'-tree-').getStore();
			var count =store_.getCount();
			var record = store_.getAt(form_transito_.index);
			Ext.getCmp(form_transito_.id+'-dias').getStore().load({
				params:{vp_id:record.get('id1')},
				callback:function(){
					
				}
			});
		},
		block_coord:function(bool){
			Ext.getCmp(form_transito_.id+'-x-cord').setDisabled(!bool);
			Ext.getCmp(form_transito_.id+'-y-cord').setDisabled(!bool);
			if(bool){
				var store_ = Ext.getCmp(form_transito_.id+'-tree-').getStore();
				var record = store_.getAt(form_transito_.index);
				form_transito_.coordenadas_distrito(record.get('dir_px'),record.get('dir_py'));
			}
		},
		coordenadas_distrito:function(coor_x,coor_y){	
			document.getElementById('toll_box').innerHTML = '<input id="pac-input" class="controls" type="text" placeholder="Buscar Destino">';
			if(coor_x==null)coor_x = '';
	        if(coor_x==''){
	        	coordenadas=red_despacho.coord_origen_x+','+red_despacho.coord_origen_y;
	        }else{
	        	coordenadas=coor_x+','+coor_y;
	        }

			geocoder = new google.maps.Geocoder();
	        var latLng = new google.maps.LatLng(-12.047085762023926,-77.08118438720703);
	        var myOptions = {
	           center: latLng,
	           zoom: 10,
	           mapTypeId: google.maps.MapTypeId.ROADMAP
	         };

	          var markers = [];
			  var map = new google.maps.Map(document.getElementById(red_despacho.id+'Mapsa'), 
			    myOptions
			  );

			  var defaultBounds = new google.maps.LatLngBounds(
			      new google.maps.LatLng(-12.047085762023926, -77.08118438720703),
			      new google.maps.LatLng(-12.047085762023926, -77.08118438720703));
			  map.fitBounds(defaultBounds);

			  var input = (document.getElementById('pac-input'));
			  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

			  var searchBox = new google.maps.places.SearchBox((input));

			  google.maps.event.addListener(searchBox, 'places_changed', function() {
			    var places = searchBox.getPlaces();

			    if (places.length == 0) {
			      return;
			    }
			    for (var i = 0, marker; marker = markers[i]; i++) {
			      marker.setMap(null);
			    }

			    form_transito_.pointDist(map);

			    markers = [];
			    var bounds = new google.maps.LatLngBounds();
			    for (var i = 0, place; place = places[i]; i++) {
			      var image = {
			        url: place.icon,
			        size: new google.maps.Size(71, 71),
			        origin: new google.maps.Point(0, 0),
			        anchor: new google.maps.Point(17, 34),
			        scaledSize: new google.maps.Size(25, 25)
			      };

			      var marker = new google.maps.Marker({
			        map: map,
			        icon: image,
			        title: place.name,
			        position: place.geometry.location,
			        draggable: true
			      });

			      markers.push(marker);

			      bounds.extend(place.geometry.location);
			      var new_coord = place.geometry.location.k+','+place.geometry.location.D;
			      
			      geocoder.geocode( { 'address': new_coord}, function(results, status) {
			        if (status == google.maps.GeocoderStatus.OK) {
			            map.setCenter(results[0].geometry.location);
			            marker.setPosition(results[0].geometry.location);

			            Ext.getCmp(form_transito_.id+'-x-cord').setValue(marker.getPosition().lat());
				  		Ext.getCmp(form_transito_.id+'-y-cord').setValue(marker.getPosition().lng());

			            google.maps.event.addListener(marker, 'dragend', function(){
				            Ext.getCmp(form_transito_.id+'-x-cord').setValue(marker.getPosition().lat());
				            Ext.getCmp(form_transito_.id+'-y-cord').setValue(marker.getPosition().lng());
			            });
				      } else {
				         global.Msg({
				         	msg:"No podemos encontrar la direcci&oacute;n, error: " + status,
				         	icon:0
				         });
				      }
				    });
			    }

			    map.fitBounds(bounds);
			  });
			  
			  google.maps.event.addListener(map, 'bounds_changed', function() {
			    var bounds = map.getBounds();
			    searchBox.setBounds(bounds);
			  });
			var marker = new google.maps.Marker({
                map: map,
                position: latLng,
                draggable: true
            });
			geocoder.geocode( { 'address': coordenadas}, function(results, status) {
	            if (status == google.maps.GeocoderStatus.OK) {
	                map.setCenter(results[0].geometry.location);
	                marker.setPosition(results[0].geometry.location);
                	google.maps.event.addListener(marker, 'dragend', function(){
			            Ext.getCmp(form_transito_.id+'-x-cord').setValue(marker.getPosition().lat());
			            Ext.getCmp(form_transito_.id+'-y-cord').setValue(marker.getPosition().lng());
		            });
                } else {
                 global.Msg({
                        msg:"No podemos encontrar la direcci&oacute;n, error: " + status,
                        icon:0
	                 });
	              }
	        });
	        map.setZoom(10);
            
		        
		},
		get_distrito:function(){
			
			var myLatlng = new google.maps.LatLng(red_despacho.coord_origen_x,red_despacho.coord_origen_y);
			var mapOptions = {
						zoom: 11,
	    				center: myLatlng,
	    				mapTypeId: google.maps.MapTypeId.ROADMAP,
			};
			map = new google.maps.Map(document.getElementById(red_despacho.id+'Mapsa'),mapOptions);

			Ext.Ajax.request({
				url:form_transito_.url+'scm_get_distrito',
				params:{vp_prov_codigo:form_transito_.prov_codigo},
				success:function(response,options){
					var res = Ext.decode(response.responseText);
					form_transito_.pointFirst=res;
					var x;
					var y;
					var prov_descri;
					Ext.each(res.data, function(value){
						form_transito_.mark_prov(value.x,value.y,map,value.prov_descri,value.tipo_zona,'');
					});

				}
			});

		},
		pointDist:function(maps){
		    Ext.each(form_transito_.pointFirst.data, function(value){
				if(parseInt(value.ciu_id) !=form_transito_.ciu_id)
					form_transito_.mark_prov(value.x,value.y,maps,value.prov_descri,'','');
			});
		},
		mark_prov:function(x,y,map,string,dir_calle,prov_foto){
				var myLatlng = new google.maps.LatLng(x,y);
				var marker = new google.maps.Marker({
				      position: myLatlng,
				      map: map,
				      title: 'Red de Distribucion Urbano',
				      icon: ''
				});

				var contentString = '<div id="content"  style="width:225px;"><b>'+
					      string +'<br>'+
					      dir_calle +'<br>'+
					      '</b></div>';
				if(prov_foto!='')contentString+='<div style="padding:5px"><img src="/foto_agencias/'+prov_foto+'" width="205" /></div>';
				var infowindow = new google.maps.InfoWindow({
					      content: contentString,
					      maxWidth: 225
				});

				google.maps.event.addListener(marker, 'click', function() {
				        infowindow.open(map,marker);
			    });
			}
	}
	Ext.onReady(form_transito_.init,form_transito_);
</script>