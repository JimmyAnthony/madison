<script type="text/javascript">
/**
 * @author  Jim
 */
var tab = Ext.getCmp(inicio.id+'-tabContent');
if (!Ext.getCmp('gestion_transporte-tab')){
	var gestion_transporte = {
		id:'gestion_transporte',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/gestionTransporte/',
		mapa:{
			map:'',
			directionsDisplay: new google.maps.DirectionsRenderer(),
			directionsService : new google.maps.DirectionsService(),
			trafficLayer:new google.maps.TrafficLayer(),
			markers:[],
			lat:-12.047926902770996,
			lng:-77.0811996459961,
			zoom:13,
			currentinfowindow:null
		},
		cmp:{
			trafic:false,
			cmpAct:0,
			lineStore:[],
			dataTimeLine:[],
			timeline:[],
			reload:false
		},
		dataNow:{vp_fecha:'',vp_id_placa:0,placa:'',chofer:''},
		init:function(){
			Ext.tip.QuickTipManager.init();

			this.store_car = Ext.create('Ext.data.Store',{
				id:gestion_transporte.id+'-store_car',
	            fields: [
	                {name: 'id', type: 'int'},
	                {name: 'nivel', type: 'int'},
	                {name: 'placa', type: 'string'},
	                {name: 'chofer', type: 'string'},
	                {name: 'telefono', type: 'string'},
	                {name: 'bateria', type: 'int'},
	                {name: 'batery', type: 'int'},
	                {name: 'foto', type: 'string'},
	                {name: 'tot_servicio', type: 'int'},
	                {name: 'progreso', type: 'string'},
	                {name: 'tot_dl', type: 'int'},
	                {name: 'tot_ausente', type: 'int'},
	                {name: 'tot_rechazo', type: 'int'},
	                {name: 'rezagos', type: 'int'},
	                {name: 'pendientes', type: 'int'},
	                {name: 'hora_ultimo', type: 'string'},
	                {name: 'hora_inicio', type: 'string'},
	                {name: 'hora_final', type: 'string'},
	                {name: 'id_placa', type: 'int'},
	                {name: 'estado', type: 'string'},
	                {name: 'minutos', type: 'string'},
	                {name: 'pos_px', type: 'string'},
	                {name: 'pos_py', type: 'string'},
	                {name: 'fecha', type: 'string'},
	                {name: 'agencia', type: 'int'},
	                {name: 'styleactive',type:'string'}
	            ],
	            autoLoad:true,
	            proxy:{
	                type: 'ajax',
	                url: gestion_transporte.url+'get_scm_track_panel_unidades/',
	                timeout: 990000,
	                reader:{
	                    type: 'json', 
	                    rootProperty: 'data'
	                },
	                extraParams:{
	                    sis_id: 1
	                }
	            },
	            listeners:{
	                load: function(obj, records, successful, opts){
	                    gestion_transporte.setPointTrack(obj);
				        google.load("visualization", "1",{"callback" : gestion_transporte.drawVisualization});
				        gestion_transporte.setLoadDinamicComponent();
				    }
	            }
	        });

			var imageTplPointer = new Ext.XTemplate(
	            '<tpl for=".">',
	                '<div class="databox_list_transport_select" >',
                        '<div class="databox_list_transport" >',
                        	'<div class="" >{styleactive}</div>',
                            '<div class="databox_resume_transport" >',
                            	'<div class="databox_user_transport"><span class="dbx_user">{chofer}</span></div>',
                            	'<div class="databox_resum_content">',
                            		'<div class="databox_aling_r">',
		                                '<div class="databox_barx">',
		                                	'<div class="box_img">',
			                                    '<img src="/images/icon/delivery_track.png" />',
			                                '</div>',
			                                '<div class="box_dat">',
					                            '<div class="databox_placa_transport">',
					                                '<span>{placa}</span>',
					                            '</div>',
					                            '<div class="databox_cell">',
						                        	'<span>{telefono}</span>',
												'</div>',
											'</div>',
											'<div class="databox_battery_transport" style="font-size:5px;margin-top:8px;">',
				                                '<div class="battery_box"><div class="battery_{batery}"></div></div>',
				                                '<div class="battery_text">{bateria}%</div>',
				                            '</div>',
				                        '</div>',
				                        '<div class="databox_progress">',
					                        '<div id="progress">',
											    '<div id="progress-bar" class="progress-bar-striped active" role="progressbar" aria-valuenow="{progreso}" aria-valuemin="0" aria-valuemax="100" style="width:{progreso}%" >',
											      '{progreso}%',
											    '</div>',
											'</div>',
										'</div>',
										'<div class="databox_summary">',
											'<div class="countTransport">{tot_servicio}</div>',
											'<div class="countTransport color_summary_blue">{progress}</div>',
											'<div class="countTransport color_summary_green">{DL}</div>',
											'<div class="countTransport color_summary_gris">{MOT}</div>',
										'</div>',
										'<div class="databox_summary_txt">',
											'<div class="summary_txt">TSR</div>',
											'<div class="summary_txt">TPR</div>',
											'<div class="summary_txt">DL</div>',
											'<div class="summary_txt">MOT</div>',
										'</div>',
									'</div>',
								'</div>',
								'<div class="databox_pie_chart_stl">',
									'<div id="databox_pie_chart_{id}" style="float:left;">',
									'</div>',
									'<div class="databox_summary_pie">',
										'<div class="countTransport_pie color_summary_au">AU({P_AU}%)</div>',
										'<div class="countTransport_pie color_summary_red">RC({P_RC}%)</div>',
										'<div class="countTransport_pie color_summary_blue">RZ({P_RZ}%)</div>',
									'</div>',
									'<div class="databox_summary_pie_bt">',
										'<div class="countTransport_pie_bt color_summary_green">DL({P_DL}%)</div>',
										'<div class="countTransport_pie_bt color_summary_gris">PN({P_PE}%)</div>',
									'</div>',
								'</div>',
                            '</div>',
                            '<div class="databox_time_transport" >',
                            	'<div class="styleTimeLine" >',
	                            	'<div id="my-timeline_{id}">',
	                            	'</div>',
                            	'</div>',
                            '</div>',
                        '</div>',
                        '<div class="databox_api_resumen" style="clear:both;">',
                        	'<div id="databox_api_resumen_{id}" >',
                        	'</div>',
                        '</div>',
                    '</div>',
	            '</tpl>'
	        );

			tab.add({
				id:gestion_transporte.id+'-tab',
				border:false,
				autoScroll:true,
				closable:true,
				layout:{
					type:'fit'
				},
				tbar:[
					'-',
					{
						xtype:'combo',
						id:gestion_transporte.id+'-agencia',
						fieldLabel:'Agencia',
						labelWidth:50,
						store:Ext.create('Ext.data.Store',{
						fields:[
								{name:'prov_codigo', type:'int'},
								{name:'prov_nombre', type:'string'}
						],
						proxy:{
							type:'ajax',
							url:gestion_transporte.url+'get_usr_sis_provincias/',
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
							minWidth:250
						},
						width:180,
						forceSelection:true,
						allowBlank:false,
						selecOnFocus:true,
						emptyText:'[ Seleccione ]',
						listeners:{
							afterrender:function(obj,record,options){
								obj.getStore().load({
									params:{linea:3},
									callback:function(){
										obj.setValue(parseInt('<?php echo PROV_CODIGO;?>'));
									}
								});
							},
							select: function(obj, records, opts){
								gestion_transporte.getReloadTack();
							}
						}
					},'-',
					{
						xtype:'datefield',
						id:gestion_transporte.id+'-fecha',
						fieldLabel:'Fecha',
						labelWidth:35,
						//labelAlign:'top',
						width:150,
						value:new Date(),
						listeners:{
							select: function(obj, records, opts){
								gestion_transporte.getReloadTack();
							}
						}
					},
					{
						xtype:'textfield',
						id:gestion_transporte.id+'-placa',
						width:80,
						listeners:{

						}
					},
					{
						text:'',
						id:gestion_transporte.id+'-btn-find',
						icon: '/images/icon/search.png',
						tooltip:'Buscar',
						listeners:{
							click:function(obj,opts){
								gestion_transporte.getReloadTack();
							}
						}
					}
				],
				items:[
					{
						xtype:'panel',
						layout:'border',
						border:false,
						items:[
							{
								region:'west',
								id: gestion_transporte.id+'-region-west',
								layout:'fit',
								border:false,
								frame:true,
								width:'50%',
								header:false,
								split: true,
								collapsible: true,
								hideCollapseTool:true,
								titleCollapse:false,
								floatable: false,
								collapseMode : 'mini',
								animCollapse : true,
								items:[
									{
				                        xtype: 'dataview',
				                        id: gestion_transporte.id+'-grid-car',
				                        layout:'fit',
				                        store: gestion_transporte.store_car,
				                        autoScroll: true,
				                        loadMask:true,
				                        autoHeight: false,
				                        tpl: imageTplPointer,
				                        //component:{xtype:'ItemTrackingTransport'},
				                        multiSelect: false,
				                        singleSelect: false,
				                        loadingText:'Cargando Registros...',
				                        emptyText: '<div class="databox_list_transport_none"><div class="databox_none_data_transport" ></div><div class="databox_title_clear_data_transport">NO EXISTEN REGISTROS</div></div>',
				                        itemSelector: 'div.databox_list_transport_select',
				                        trackOver: true,
				                        overItemCls: 'databox_list_transport-hover',
				                        listeners: {
				                            'itemclick': function(view, record, item, idx, event, opts) {
				                            	//console.log(record);
				                            	if(gestion_transporte.cmp.cmpAct!=record.get('id')){
				                            		Ext.getCmp(gestion_transporte.id+'-north-data-client').hide();
				                            		gestion_transporte.setDetailForm(null);
					                            	if(gestion_transporte.cmp.cmpAct!=0)gestion_transporte.setClearCmp(gestion_transporte.cmp.cmpAct);
					                            	gestion_transporte.setApiComponentResume(record);
					                            	gestion_transporte.getRouteTrack(true,record);
				                            	}
				                            	/*
													if(gestion_transporte.cmp.cmpAct!=0)
				                            	gestion_transporte.setClearCmp(gestion_transporte.cmp.cmpAct);
				                            	if(gestion_transporte.cmp.cmpAct!=record.get('id')){
				                            		gestion_transporte.setApiComponentResume(record);
				                            	}else{
				                            		gestion_transporte.cmp.cmpAct=0;
				                            	}
				                            	*/
				                            },
				                            afterrender:function(obj){
											}
				                        }
				                    }
								]
							},
							{
								region:'center',
								layout:'fit',
								border:false,
								items:[
									{
										layout:'border',
										border:false,
										items:[
											{
												region:'center',
												id:gestion_transporte.id+'-center-map',
												layout:'fit',
												//height:'100%',
												//width:'100%',
												anchor:'100%',
												border:false,
												html:'<div id="'+gestion_transporte.id+'-map" class="ue-map-canvas"></div>'
											},
											{
												region:'south',
												id:gestion_transporte.id+'-north-data-client',
												hidden:true,
												layout:'fit',
												height:340,
												border:false,
												header:false,
												split: true,
												collapsible: true,
												hideCollapseTool:true,
												titleCollapse:false,
												floatable: false,
												collapseMode : 'mini',
												animCollapse : true,
												bbar:[
													'->',
													{
														xtype:'button',
														icon: '/images/icon/get_back.png',
														handler:function(btn) {
	 														Ext.getCmp(gestion_transporte.id+'-north-data-client').hide();
	 														gestion_transporte.getRouteTrack(false,null);
														}
													}
												],
												items:[
													{
														layout:'border',
														border:false,
														items:[
															/*{
																region:'north',
																layout:'fit',
																id:'-panel-detail',
																border:true,
																height:200,
																items:[],
																listeners:{
																	afterrender:function(){
																		gestion_transporte.setDetailForm(null);
																		gestion_transporte.setMap(null);
																	}
																}
															},*/
															{
																region:'center',
																id:'-panel-detail',
																layout:'fit',
																border:false,
																autoScroll:true,
																//html:'<div id="GaleryFull" class="links"></div>',
																items:[
																	/*{
				                                                        xtype: 'dataview',
				                                                        id: gestion_transporte.id+'-grid-img-visita',
				                                                        layout:'fit',
				                                                        store: Ext.create('Ext.data.Store',{
				                                                            fields: [
				                                                                {name: 'img_tipo', type: 'string'},
				                                                                {name: 'img_path', type: 'string'},
				                                                                {name: 'img_px', type: 'string'},
				                                                                {name: 'img_py', type: 'string'},
				                                                                {name: 'time', type: 'string'}
				                                                            ],
				                                                            autoLoad:false,
				                                                            proxy:{
				                                                                type: 'ajax',
				                                                                url: '/gestion/gestionTransporte/get_img_tracks/',
				                                                                reader:{
				                                                                    type: 'json',
				                                                                    rootProperty: 'data'
				                                                                },
				                                                                extraParams:{
				                                                                    sis_id: 1
				                                                                }
				                                                            },
				                                                            listeners:{
				                                                                load: function(obj, records, successful, opts){
				                                                                }
				                                                            }
				                                                        }),
				                                                        autoScroll: true,
				                                                        loadMask:true,
				                                                        autoHeight: false,
				                                                        tpl: [
				                                                            '<tpl for=".">',
				                                                                '<div class="dataview-multisort-item">',
				                                                                    '<a href="{img_path}" data-lightbox="roadtrip"><img src="{img_path}" style="width:50px;height:50px"/></a>',
				                                                                '</div>',
				                                                            '</tpl>'
				                                                        ],
				                                                        //component:{xtype:'ItemTrackingTransport'},
				                                                        multiSelect: false,
				                                                        singleSelect: false,
				                                                        loadingText:'Cargando Imagenes...',
				                                                        emptyText: '<div class="databox_list_transport"><div class="databox_none_data" ></div><div class="databox_title_clear_data">NO EXISTE REGISTRO</div></div>',
				                                                        /*plugins: {
				                                                            xclass: 'Ext.ux.DataView.Animated'
				                                                        },*/
				                                                        /*itemSelector: 'div.dataview-multisort-item',
				                                                        trackOver: true,
				                                                        overItemCls: 'databox_list_transport-hover',
				                                                        listeners: {
				                                                            'itemclick': function(view, record, item, idx, event, opts) {
				                                                                alert(1111);
				                                                            },
				                                                            afterrender:function(obj){
				                                                            }
				                                                        }
				                                                    }*/
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
						]
					}
				],
				listeners:{
					beforerender: function(obj, opts){
                        global.state_item_menu(gestion_transporte.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        global.state_item_menu_config(obj,gestion_transporte.id_menu);
                    },
                    beforeclose:function(obj,opts){
                    	global.state_item_menu(gestion_transporte.id_menu, false);
                    	try{
                    		gestion_transporte.setClearCmp(gestion_transporte.cmp.cmpAct);
                    	}catch(e){}
                    },
                    boxready:function(obj, width, height, eOpts ){
                    	gestion_transporte.setIconModule();
                    }
				}
			}).show();
		},
		setLoadDinamicComponent:function(){
			gestion_transporte.store_car.each(function(record){
				try{
					gestion_transporte.setPieCharts(record);
				}catch(e){}
				gestion_transporte.setTimeLineTransport(record);
			});
		},
		setClearCmp:function(id){
			try{
				if(Ext.getCmp(gestion_transporte.id+"_cmp_content_"+id))Ext.getCmp(gestion_transporte.id+"_cmp_content_"+id).destroy();
				Ext.get("databox_api_resumen_"+id).update('');
			}catch(e){}
		},
		setApiComponentResume:function(record){
			gestion_transporte.cmp.cmpAct=record.get('id');
			//gestion_transporte.setClearCmp(record.get('id'));
			Ext.create('Ext.panel.Panel', {
				id:gestion_transporte.id+"_cmp_content_"+record.get('id'),
			    width: "100%",
			    height:"100%",
			    layout:'fit',
			    items:[
                    /*{
                        xtype:'ItemTrackingTransport',
                        id:gestion_transporte.id+"_cmp_item_"+record.get('id')
                    }*/
                    {
                        xtype: 'dataview',
                        //id: gestion_transporte.id+'-grid-car',
                        layout:'fit',
                        height:200,
                        width: "100%",
                        anchor:"100%",
                        store: gestion_transporte.cmp.lineStore[record.get('id')],
                        autoScroll: true,
                        loadMask:true,
                        autoHeight: false,
                        tpl: [
                            '<tpl for=".">',
                            	'<div class="databox_list_trpt_select" >',
                            		
					                '<tpl if="nivel==0">',
				                        '<div class="databox_transport_title_first">',
				                        	'<span style="width:5%;text-align: center;">#</span>',
				                            '<span style="width:15%;text-align: center;">Guía</span>',
				                            '<span style="width:35%;text-align: center;">Cliente</span>',
				                            '<span style="width:10%;text-align: center;">Estimado</span>',
				                            '<span style="width:10%;text-align: center;">Real</span>',
				                            '<span style="width:25%;text-align: center;">Estado</span>',
				                        '</div>',
					                '</tpl>',
					                '<tpl if="nivel!=0">',
						                '<a onclick="gestion_transporte.getDataClientEvent('+record.get('id')+',\'{hora_line}\',{id_guia},{guia});">',
					                        '<div class="databox_transport_title_second" >',
					                            '<span style="width:5%;text-align: center;">{orden}</span>',
					                            '<span style="width:15%;text-align: left;">{ge_texto}</span>',
					                            '<span style="width:35%;">{cliente}</span>',
					                            '<span style="width:10%;text-align: center;">{hora_estimada}</span>',
					                            '<span style="width:10%;text-align: center;">{hora_real}</span>',
					                            '<span style="width:25%;text-align: center;">{estado}</span>',
					                        '</div>',
					                    '</a>',
					                '</tpl>',
				                '</div>',
				                
				            '</tpl>'
                        ],
                        //component:{xtype:'ItemTrackingTransport'},
                        multiSelect: false,
                        singleSelect: false,
                        loadingText:'Cargando Imagenes...',
                        emptyText: '<div class="databox_list_transport"><div class="databox_none_data" ></div><div class="databox_title_clear_data">NO EXISTE REGISTRO</div></div>',
                        plugins: {
                            xclass: 'Ext.ux.DataView.Animated'
                        },
                        itemSelector: 'div.dataview-multisort-item',
                        trackOver: true,
                        overItemCls: 'databox_list_transport-hover',
                        listeners: {
                            'itemclick': function(view, record, item, idx, event, opts) {
                                gestion_transporte.setDetailForm(record);
                            },
                            afterrender:function(obj){
                            }
                        }
                    }
                ],
			    renderTo: 'databox_api_resumen_'+record.get('id')
			});
		},
		setPieCharts:function(record){
				try{
					document.getElementById("databox_pie_chart_"+record.get('id')).innerHTML = "";
				}catch(e){}
				var width = 60,height = 50,offset = 10,radius = Math.min(width, height) / 2;
				var color = d3.scale.ordinal().range(["#16a765", "#9F9F9F","#65A6BF", "#9AC4D5", "#CCE2EA"]);
				var arc = d3.svg.arc().outerRadius(radius - 5).innerRadius(radius - 17);
				// second arc for labels
				var arc2 = d3.svg.arc().outerRadius(radius).innerRadius(radius + 10);
				var pie = d3.layout.pie().sort(null).startAngle(1.1*Math.PI).endAngle(3.1*Math.PI).value(function(d) { return d.songs; });

				var tot_servicio=parseFloat(record.get('tot_servicio'));
				var tot_dl=parseFloat(record.get('tot_dl'));
				var tot_ausente=parseFloat(record.get('tot_ausente'));
				var tot_rechazo=parseFloat(record.get('tot_rechazo'));
				var rezagos=parseFloat(record.get('rezagos'));
				var pendientes=parseFloat(record.get('pendientes'));

				var por_dl = Math.ceil(((tot_dl*100)/tot_servicio)* 100)/100;
				var por_ausente = Math.ceil(((tot_ausente*100)/tot_servicio)* 100)/100;
				var por_rechazo = Math.ceil(((tot_rechazo*100)/tot_servicio)* 100)/100;
				var por_rezagos = Math.ceil(((rezagos*100)/tot_servicio)* 100)/100;
				var por_pendientes = Math.ceil(((pendientes*100)/tot_servicio)* 100)/100;
				//console.log(por_dl+" - "+por_ausente+" - "+por_rechazo+" - "+por_rezagos+" - "+por_pendientes);

				var data = [
				  {genre: '', songs: por_dl,i:'DL'},
				  {genre: '', songs: por_ausente,i:'AU'},
				  {genre: '', songs: por_rechazo,i:'RC'},
				  {genre: '', songs: por_rezagos,i:'RZ'},
				  {genre: '', songs: por_pendientes,i:'PE'}
				];

				var svg = d3.select("#databox_pie_chart_"+record.get('id')).append("svg")
				    .attr("id", "chart_"+record.get('id'))
				    .attr("width", width + offset)
				    .attr("height", height + offset)
				    .attr("style", "float:left;")
				    //.attr('viewBox', '0 0 ' + width + offset + ''+ width + offset +'')
				    .attr('perserveAspectRatio', 'xMinYMid')
				  .append("g")
				    .attr("transform", "translate(" + (width+offset) / 2 + "," + (height + offset) / 2 + ")");

				  data.forEach(function(d) {
				    d.songs = +d.songs;
				  });

				  var g = svg.selectAll(".arc")
				      .data(pie(data))
				    .enter().append("g")
				      .attr("class", "arc");

				  g.append("path")
				      .style("fill", function(d) { return gestion_transporte.getColorPieChart(d.data.i) })//color(d.data.genre);
				      .transition().delay(function(d, i) { return i * 500; }).duration(500)
				      .attrTween('d', function(d) {
				         var i = d3.interpolate(d.startAngle+0.1, d.endAngle);
				         return function(t) {
				           d.endAngle = i(t);
				           return arc(d);
				         };
				      });

				  g.append("text")
				      .attr("transform", function(d) { return "translate(" + arc2.centroid(d) + ")"; })
				      .attr("dy", ".35em")
				      .attr("class", "d3-label")
				      .style("text-anchor", "middle")
				      .text(function(d) { return d.data.genre; });

				//var aspect = width / height,chart = $("#chart");
				/*$(window).on("resize", function() {
				    var targetWidth = Math.min(width + offset, chart.parent().width());
				    chart.attr("width", targetWidth);
				    chart.attr("height", targetWidth / aspect);
				}).trigger('resize');*/
		},
		getColorPieChart:function(i){
			switch(i){
				case 'DL'://dl
					color="#16a765";
				break;
				case 'AU'://ausente
					color="#000000";
				break;
				case 'RC'://rechazo
					color="#FA5858";
				break;
				case 'RZ'://rezagos
					color="#337ab7";
				break;
				case 'PE'://pendientes
					color="#B5B5B5";
				break;
			}
			return color;
		},
		setTimeLineTransport:function(record){

		},
		setDetailForm:function(record){
			console.log(record);
			var data;
			if(record==null){
				data={
					guia:'',
					id_track:'',
					remitente:'',
					remite_direc:'',
					servicio:'',
					piezas:'',
					peso_aprox:'',
					peso:'',
					valor:'',
					destinatario:'',
					direccion:'',
					localidad:'',
					referenc:'',
					dir_px:'',
					dir_py:'',
					telefonos:'',
					e_mail:'',
					contenido:''
				};
			}else{
				Ext.getCmp(gestion_transporte.id+'-north-data-client').show();
				data=record.data[0];
			}

	        var imageTplPointerPanel = new Ext.XTemplate(
	            '<tpl for=".">',
	                '<div class="databox_panel_transpot" >',

	                    '<div class="databox_panel_A" >',
	                        '<table class="height_table">',
	                            '<tr>',
	                                '<td class="color_td">REMITE:</td><td class="color_td_b">'+data.remitente+'</td>',
	                            '</tr>',
	                            '<tr>',
	                                '<td colspan="2" class="color_td_b">'+data.remite_direc+'</td>',
	                            '</tr>',
	                        '</table>',
	                    '</div>',
	                    '<div class="databox_panel_B" >',
	                        '<table class="height_table">',
	                            '<tr>',
	                                '<td class="color_td">GE:</td><td class="color_td_b">'+data.guia+'</td><td class="color_td">TRACK:</td><td class="color_td_b">'+data.id_track+'</td>',
	                            '</tr>',
	                            '<tr>',
	                                '<td class="color_td">Piezas:</td><td class="color_td">P.Est</td><td class="color_td">P.Valido</td><td class="color_td">Valor</td>',
	                            '</tr>',
	                            '<tr>',
	                                '<td class="color_td_b">'+data.piezas+'</td><td class="color_td_b">'+data.peso_aprox+'</td><td class="color_td_b">'+data.peso+'</td><td class="color_td_b">'+data.valor+'</td>',
	                            '</tr>',
	                        '</table>',
	                    '</div>',

	                    '<div class="databox_panel_C" >',
	                        '<table class="height_table">',
	                            '<tr>',
	                                '<td class="color_td" width="20">DESTINATARIO:</td><td class="color_td_b" border="1">'+data.destinatario+'</td>',
	                            '</tr>',
	                            '<tr>',
	                                '<td colspan="2" class="color_td_b">'+data.direccion+'</td>',
	                            '</tr>',
	                        '</table>',
	                    '</div>',

	                    '<div class="databox_panel_D" >',
	                        '<table class="height_table">',
	                            '<tr>',
	                                '<td class="color_td">98998989</td><td class="color_td" border="1">'+data.telefonos+'</td><td class="color_td" border="1">'+data.e_mail+'</td>',
	                            '</tr>',
	                            '<tr>',
	                                '<td class="color_td" border="1" colspan="3">'+data.contenido+'</td>',
	                            '</tr>',
	                        '</table>',
	                    '</div>',

	                '</div>',
	                '<div id="GaleryFull" class="links"></div>',
	            '</tpl>'
	        );
	        Ext.getCmp('-panel-detail').setHtml(imageTplPointerPanel);
	    },
	    setMap:function(params){

			var directionsService = new google.maps.DirectionsService();
	        
	        var rendererOptions = {
				  draggable: true,
				  suppressMarkers: true
			};
	        gestion_transporte.mapa.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
	        gestion_transporte.mapa.directionsService = new google.maps.DirectionsService();

	        var myLatlng = new google.maps.LatLng((params==null)?gestion_transporte.mapa.lat:params.lat,(params==null)?gestion_transporte.mapa.lng:params.lng);
	        var mapOptions = {
				zoom: (params==null)?gestion_transporte.mapa.zoom:params.zoom,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
	        gestion_transporte.mapa.map = new google.maps.Map(document.getElementById(gestion_transporte.id+'-map'), mapOptions);
	        

	        /*var homeControlDiv = document.createElement('div');
	        var homeControl = new HomeControl(homeControlDiv, gestion_transporte.mapa.map, myLatlng);
	        homeControlDiv.index = 1;
	        gestion_transporte.mapa.map.controls[google.maps.ControlPosition.TOP_CENTER].push(homeControlDiv);*/

	        var hdiv = document.createElement('div');
	        var hcontro = new gestion_transporte.HHomeControl(hdiv,gestion_transporte.mapa.map);
	        hdiv.index = 1;
	        gestion_transporte.mapa.map.controls[google.maps.ControlPosition.TOP_LEFT].push(hdiv);

	        gestion_transporte.mapa.directionsDisplay.setMap(gestion_transporte.mapa.map);
	        
		},
		HHomeControl:function(controlDiv, map) {
		  controlDiv.style.padding = '5px';

		  // Set CSS for the control border.
		  var controlUI = document.createElement('div');
		  controlUI.style.backgroundColor = 'gray';
		  controlUI.style.borderStyle = 'gray';
		  controlUI.style.borderWidth = '2px';
		  controlUI.style.borderColor = 'gray';

		  controlUI.style.width = '100%';
		  controlUI.style.height = '100%';

		  controlUI.style.cursor = 'pointer';
		  controlUI.style.textAlign = 'center';
		  controlUI.style.opacity = '0.5';
		  //controlUI.title = 'Click Para Mostrar el Tráfico';
		  controlUI.innerHTML = '<div id="contetDivDriver"></div>';
		  controlDiv.appendChild(controlUI);

		  // Set CSS for the control interior.
		  /*var controlText = document.createElement('div');
		  controlText.innerHTML = '<div id="contetDivDriver"></div>';
		  controlUI.appendChild(controlText);*/
		},
		trafic:function(){
			//document.getElementById("trafic").checked
			if (gestion_transporte.cmp.trafic){
				gestion_transporte.mapa.trafficLayer.setMap(gestion_transporte.mapa.map);
				try{
					Ext.getCmp(gestion_transporte.id+'btn-trafic').setStyle('background','#D2D2D2');
				}catch(e){}
			}else{
				gestion_transporte.mapa.trafficLayer.setMap(null);
				Ext.getCmp(gestion_transporte.id+'btn-trafic').setStyle('background','');
			}

		},
		setIconModule:function(){
			var controlModule = document.createElement('div');
			controlModule.className = 'contetDivTransport';
			controlModule.innerHTML = '<div id="contetDivTransportA"><span></span></div><div id="contetDivTransportC"><span></span></div><div id="contetDivTransportB"><span></span></div>';
			document.getElementById(gestion_transporte.id+'-tab').appendChild(controlModule);
			gestion_transporte.setButtonModule();
		},
		setButtonModule:function(){
			try{
            	Ext.getCmp(gestion_transporte.id+'btn-trafic').destroy();
            }catch(err){
        		console.log(err);
        	}
		    Ext.create('Ext.button.Button', {
		    	id:gestion_transporte.id+'btn-trafic',
		        text: '<div style="font-size:20px;font-weight: bold;color:#fff" height="35"><img src="/images/icon/trafic_transt.png"></div>',
		        cls:'DivTrackTrafic',
		        //icon: '/images/icon/refresh-24.png',
		        renderTo: 'contetDivTransportC',
		        handler : function(btn) {
		        	gestion_transporte.cmp.trafic=!gestion_transporte.cmp.trafic;
		        	gestion_transporte.trafic();
		        }
		    });
			try{
            	Ext.getCmp(gestion_transporte.id+'btn-fullscreen').destroy();
            }catch(err){
        		console.log(err);
        	}
		    Ext.create('Ext.button.Button', {
		    	id:gestion_transporte.id+'btn-fullscreen',
		        text: '<div style="font-size:20px;font-weight: bold;color:#fff" height="35"><img src="/images/icon/arrows-out-16.png"></div>',
		        cls:'DivTrackFullScreen',
		        //icon: '/images/icon/refresh-24.png',
		        renderTo: 'contetDivTransportA',
		        handler : function(btn) {
		        	gestion_transporte.setFullScreen(!gestion_transporte.cmp.reload,btn);
		        }
		    });
        	try{
            	Ext.getCmp(gestion_transporte.id+'btn-reload').destroy();
            }catch(err){
        		console.log(err);
        	}
		    Ext.create('Ext.button.Button', {
		    	id:gestion_transporte.id+'btn-reload',
		        text: '<div style="font-size:20px;font-weight: bold;color:#fff" height="35"><img src="/images/icon/refresh-24.png"></div>',
		        cls:'DivTrackReload',
		        //icon: '/images/icon/refresh-24.png',
		        renderTo: 'contetDivTransportB',
		        handler : function(btn) {
		        	gestion_transporte.getRouteTrack(false,null);
		        }
		    });
		},
		setFullScreen:function(hidden,btn){
			gestion_transporte.cmp.reload=hidden;
			btn.setText('<div style="font-size:20px;font-weight: bold;color:#fff" height="35"><img src="/images/icon/'+((hidden)?'arrows-in-16.png':'arrows-out-16.png')+'"></div>');
			Ext.getCmp(gestion_transporte.id+'-north-data-client').setHidden(true);
			Ext.getCmp(gestion_transporte.id+'-region-west').setHidden(hidden);
			gestion_transporte.setMap(null);
			if(!gestion_transporte.getRouteTrack(false,null))gestion_transporte.getReloadTack();
		},
		drawVisualization:function(){
			gestion_transporte.cmp.lineStore=[];
			gestion_transporte.store_car.each(function(record){
				gestion_transporte.cmp.lineStore[record.get('id')] = Ext.create('Ext.data.Store',{
		            fields: [
		            	{name: 'id', type: 'int'},
		            	{name: 'id_guia', type: 'int'},
		                {name: 'servicio', type: 'string'},
		                {name: 'orden', type: 'int'},
		                {name: 'ge_texto', type: 'string'},
		                {name: 'hora_estimada', type: 'string'},
		                {name: 'hora_real', type: 'string'},
		                {name: 'hora_actual', type: 'string'},
		                {name: 'hora_line', type: 'string'},
		                {name: 'secuencia', type: 'string'},
		                {name: 'guia', type: 'string'},
		                {name: 'estado', type: 'string'},
		                {name: 'minutos', type: 'string'},
		                {name: 'cliente', type: 'string'},
		                {name: 'dir_px', type: 'string'},
		                {name: 'dir_py', type: 'string'},
		                {name: 'gps_px', type: 'string'},
		                {name: 'gps_py', type: 'string'},
		                {name: 'stylestatus', type: 'string'}
		            ],
		            autoLoad:true,
		            proxy:{
		                type: 'ajax',
		                url: gestion_transporte.url+'get_scm_track_panel_unidades_carga/',
		                timeout: 990000,
		                reader:{
		                    type: 'json',
		                    rootProperty: 'data'
		                },
		                extraParams:{
		                    vp_agencia:record.get('agencia'),vp_id_placa: record.get('id_placa'),vp_fecha:record.get('fecha')
		                }
		            },
		            listeners:{
		                load: function(obj, records, successful, opts){
		                    gestion_transporte.getLineTime(record.get('hora_ultimo'),obj,record);
					    }
		            }
		        });
			});
	    },
	    getLineTime:function(hora_ultimo,obj,record){
	            // Create and populate a data table.
	            gestion_transporte.cmp.dataTimeLine[record.get("id")] = new google.visualization.DataTable();
	            gestion_transporte.cmp.dataTimeLine[record.get("id")].addColumn('datetime', 'start');
	            gestion_transporte.cmp.dataTimeLine[record.get("id")].addColumn('datetime', 'end');
	            gestion_transporte.cmp.dataTimeLine[record.get("id")].addColumn('string', 'content');
	            gestion_transporte.cmp.dataTimeLine[record.get("id")].addColumn('string', 'className');
	            var line = new Array();
	            if(hora_ultimo!=null || hora_ultimo!=''){
	            	var h=hora_ultimo.split(':');
	            	line.push([new Date(2015,6,20,h[0],h[1],0), , '<img src="/images/icon/car-16.png">','']);
	            }
	            obj.each(function(record_t){
	            	if(parseInt(record_t.get('nivel'))!=0){
		            	if(record_t.data.hora_line!=null || record_t.data.hora_line!=''){
		            		var h=record_t.data.hora_line.split(':');
		            		line.push([new Date(2015,6,20,h[0],h[1],0), , '<img src="/images/icon/ER_'+record_t.data.servicio+'.png">',record_t.get("stylestatus")]);
		            	}
		            }
		        });
	            gestion_transporte.cmp.dataTimeLine[record.get("id")].addRows(line);

	            // specify options
	            var options = {
	                "width":  "100%",
	                "height": "95px",
	                "style": "box",
	                "padding-top":"15px",
	                "min":new Date(2015,5,20),
	                "max":new Date(2015,7,20),
	                "showCurrentTime":"true",
	                "showCustomTime":"true",
	                "locale":"ES",
	                /*"scale":5,
	                "step":5,*/
	                axisOnTop: false,
		            eventMargin: 5,  // minimal margin between events
		            eventMarginAxis: 5, // minimal margin beteen events and the axis
		            editable: false,
		            showNavigation: false,
		            showMajorLabels: false,
		            groupsChangeable : false
                	//groupsOnRight: false
	            };

	            // Instantiate our timeline object.
	            gestion_transporte.cmp.timeline[record.get("id")] = new links.Timeline(document.getElementById('my-timeline_'+record.get("id")), options);
	            //google.visualization.events.addListener(gestion_transporte.cmp.timeline[record.get("id")], 'rangechange', gestion_transporte.setOnRangeChange);
	            eval("gestion_transporte.getOnSelect_"+record.get("id")+"=function(){var row = gestion_transporte.getSelectedRow("+record.get("id")+");if(row != undefined)gestion_transporte.getResolvetEvent("+record.get("id")+",row,gestion_transporte.cmp.dataTimeLine["+record.get("id")+"].getValue(row, 2));}");

	            google.visualization.events.addListener(gestion_transporte.cmp.timeline[record.get("id")], 'select', eval("gestion_transporte.getOnSelect_"+record.get("id")));
	            // Draw our timeline with the created data and options
	            gestion_transporte.cmp.timeline[record.get("id")].draw(gestion_transporte.cmp.dataTimeLine[record.get("id")]);
	            //var t = new Date(2010,6,20,16,30,15);
	            //gestion_transporte.cmp.timeline[record.get("id")].setCurrentTime(t.getTime());
	            //gestion_transporte.cmp.timeline[record.get("id")].setVisibleChartRangeNow();
	            //gestion_transporte.cmp.timeline[record.get("id")].zoom(0.6,new Date(2015,6,20,18,31,15));
	    },
	    getOnSelect:function(id){
	    	var row = gestion_transporte.getSelectedRow(id);
            if (row != undefined) {
             	gestion_transporte.getResolvetEvent(id,row,gestion_transporte.cmp.dataTimeLine[id].getValue(row, 2));
            }
	    },
	    getSelectedRow:function(id) {
            var row = undefined;
            var sel = gestion_transporte.cmp.timeline[id].getSelection();
            if (sel.length) {
                if (sel[0].row != undefined) {
                    row = sel[0].row;
                }
            }
            return row;
        },
        getResolvetEvent:function(id,row,data){
        	gestion_transporte.getDataClient(gestion_transporte.cmp.lineStore[id].getAt(row+1).data.id_guia,gestion_transporte.cmp.lineStore[id].getAt(row+1).data.guia); 
        },
        getDataClientEvent:function(id,hora,id_guia,guia){
        	/*if(hora!=null || hora!=''){
        		var h=hora.split(':');
        		gestion_transporte.cmp.timeline[id].zoom(0.6,new Date(2015,6,20,h[0],h[1],0));
        	}*/
        	gestion_transporte.getDataClient(id_guia,guia);
        },
        getDataClient:function(id_guia,guia){
        	Ext.Ajax.request({
				url:gestion_transporte.url+'get_scm_api_track_datos_cliente/',
				timeout: 990000,
				params:{vp_guia:guia},
				success:function(response,options){
					var res = Ext.decode(response.responseText);
					gestion_transporte.setDetailForm(Ext.decode(response.responseText));
					gestion_transporte.getImgVisita({id_guia:id_guia,guia:guia});
				}
			});
        },
	    setOnRangeChange:function(){
	    	//gestion_transporte.cmp.timeline[3].setVisibleChartRangeNow();
	    	//var range = gestion_transporte.cmp.timeline[1].getVisibleChartRange();
            //document.getElementById('startDate').value = gestion_transporte.dateFormat(range.start);
            //document.getElementById('endDate').value   = gestion_transporte.dateFormat(range.end);
            //Ext.get("startDate").update(gestion_transporte.dateFormat(range.start));
            //Ext.get("endDate").update(gestion_transporte.dateFormat(range.end));
	    },
	    dateFormat:function(date) {
            var datetime =   date.getFullYear() + "-" +
                    ((date.getMonth()   <  9) ? "0" : "") + (date.getMonth() + 1) + "-" +
                    ((date.getDate()    < 10) ? "0" : "") +  date.getDate() + " " +
                    ((date.getHours()   < 10) ? "0" : "") +  date.getHours() + ":" +
                    ((date.getMinutes() < 10) ? "0" : "") +  date.getMinutes() + ":" +
                    ((date.getSeconds() < 10) ? "0" : "") +  date.getSeconds();
            return datetime;
        },
        setMarker:function(record){
			var point = new google.maps.LatLng(parseFloat(record.dir_px),parseFloat(record.dir_py));
			switch(record.tipo_marker){
				case 'P':
					var marker = new google.maps.Marker({
	                        position: point,
	                        map: gestion_transporte.mapa.map,
	                        animation: google.maps.Animation.DROP,
	                        title: '',
	                        icon:record.logo,
	                        tipo:record.tipo_marker,
	                        id:record.id_marker
	                });
				break;
				default:
					var marker = new google.maps.Marker({
	                        position: point,
	                        map: gestion_transporte.mapa.map,
	                        animation: google.maps.Animation.DROP,
	                        title: '',
	                        icon:'/images/icon/'+record.logo,
	                        tipo:record.tipo_marker,
	                        id:record.id_marker
	                });
				break;
			}
            var infowindow = new google.maps.InfoWindow({
                  content: '<div id="content"  style="width:100px;">'+record.nombre+'</div>',
                  maxWidth: 100
            });
            google.maps.event.addListener(marker, 'click', function() {
            	//gestion_transporte.setPositionReload({tipo_marker:marker.tipo,id_marker:marker.id});
                infowindow.open(gestion_transporte.mapa.map,marker);
            });
            gestion_transporte.mapa.markers.push(marker);
		},
		setClearMarker:function(){
			if (gestion_transporte.mapa.markers){
		    	for (i in gestion_transporte.mapa.markers) {
		      		gestion_transporte.mapa.markers[i].setMap(null);
		    	}
		  	}
		},
		setPointTrack:function(obj){
			gestion_transporte.setClearMarker();
			gestion_transporte.setMap(null);
			obj.each(function(record){
				if(parseFloat(record.get('pos_px')) || record.get('pos_px')!=null){
					gestion_transporte.setMarker({dir_px:parseFloat(record.get('pos_px')),dir_py:parseFloat(record.get('pos_py')),logo:'transport_urbano_min_A.png',nombre:record.get('placa')+" <br>"+record.get('hora_ultimo'),tipo_marker:'M',id_marker:record.get('id_placa')});
				}
			});
		},
		getReloadTack:function(){
			Ext.getCmp(gestion_transporte.id+'-north-data-client').setHidden(true);
			var placa = Ext.getCmp(gestion_transporte.id+'-placa').getValue();
			var fecha = Ext.getCmp(gestion_transporte.id+'-fecha').getRawValue();
			var agencia = Ext.getCmp(gestion_transporte.id+'-agencia').getValue();
			var mask = new Ext.LoadMask(Ext.getCmp(gestion_transporte.id+'-tab'),{
	            msg:'Obteniendo Información...'
	        });
	        mask.show();
	        Ext.getCmp(gestion_transporte.id+'-grid-car').getStore().removeAll();
	        gestion_transporte.setDestroyCmp();
            Ext.getCmp(gestion_transporte.id+'-grid-car').getStore().load(
                {params: {vp_placa:placa ,vp_fecha:fecha,vp_agencia:agencia},
                callback:function(){
                	mask.hide();
                	gestion_transporte.trafic();
                    //Ext.getCmp(gestion_transporte.id+'-grid-car').refresh();
                }
            });
		},
		setDestroyCmp:function(){
			if(gestion_transporte.cmp.cmpAct!=0)gestion_transporte.setClearCmp(gestion_transporte.cmp.cmpAct);
			gestion_transporte.cmp.cmpAct=0;
			gestion_transporte.dataNow={vp_fecha:'',vp_id_placa:0,placa:'',chofer:''};
		},
		getRouteTrack:function(type,record){
			if(type){
	        	gestion_transporte.dataNow={vp_fecha:record.get('fecha'),vp_id_placa:record.get('id_placa'),placa:record.get('placa'),chofer:record.get('chofer')};
	    	}else{
	    		if(gestion_transporte.dataNow.vp_id_placa==0){ gestion_transporte.setPointTrack(Ext.getCmp(gestion_transporte.id+'-grid-car').getStore());return false;}
	    	}
			gestion_transporte.setClearMarker();
			var mask = new Ext.LoadMask(Ext.getCmp(gestion_transporte.id+'-center-map'),{
	            msg:'Obteniendo Ruta espere...'
	        });
	    	mask.show();
			var routes = [];
			Ext.Ajax.request({
				url:gestion_transporte.url+'get_scm_track_panel_gps_unidad/',
				timeout: 990000,
				params:gestion_transporte.dataNow,
				success:function(response,options){
					mask.hide();
					var res = Ext.decode(response.responseText);
					if(res.data.length>0){
						if(parseFloat(res.data[res.data.length-1].rut_px)!=0){
							gestion_transporte.setMap({lat:parseFloat(res.data[res.data.length-1].rut_px),lng:parseFloat(res.data[res.data.length-1].rut_py),zoom:16});
							gestion_transporte.trafic();
						}else{
							gestion_transporte.setMap(null);
						}
						for(var p=0;p<res.data.length;p++){
							if(p==0){
								gestion_transporte.setMarker({dir_px:parseFloat(res.data[p].rut_px),dir_py:parseFloat(res.data[p].rut_py),logo:'point_map_A8.png',nombre:res.data[p].hora,tipo_marker:'H',id_marker:0});
							}else{
								gestion_transporte.setMarker({dir_px:parseFloat(res.data[p].rut_px),dir_py:parseFloat(res.data[p].rut_py),logo:(p!=res.data.length-1)?'mini_clock.png':'transport_urbano_min_A.png',nombre:res.data[p].hora,tipo_marker:'H',id_marker:0});
							}
							if(parseFloat(res.data[p].rut_px)!=0 || parseFloat(res.data[p].rut_px) !=null){
								routes.push(new google.maps.LatLng(parseFloat(res.data[p].rut_px), parseFloat(res.data[p].rut_py)));
								if(p>0){
		                    		gestion_transporte.getPolyline(routes,'#000000');
					                var act=routes[1];routes = [];routes[0]=act;
		                    	}
		                    }
						}
					}else{
						gestion_transporte.setMap(null);
					}
					gestion_transporte.getPointDelivery();
					setTimeout ('gestion_transporte.setDriver()', 1000);
	                //gestion_transporte.mapa.map.setZoom(12);
				}
			});
			return true;
		},
		getPointDelivery:function(){
			var routes = [];
			gestion_transporte.cmp.lineStore[gestion_transporte.dataNow.vp_id_placa].each(function(record_t){
	            if(parseFloat(record_t.get('dir_px'))!=0){
	            	gestion_transporte.setMarker({dir_px:parseFloat(record_t.get('dir_px')),dir_py:parseFloat(record_t.get('dir_py')),logo:gestion_transporte.getIconFirstStatus(record_t.get('chk')),nombre:record_t.get('ge_texto'),tipo_marker:'H',id_marker:0});
	            }
	            if(parseFloat(record_t.get('gps_px'))!=0){
	            	gestion_transporte.setMarker({dir_px:parseFloat(record_t.get('gps_px')),dir_py:parseFloat(record_t.get('gps_py')),logo:gestion_transporte.getIconStatus(record_t.get('chk')),nombre:record_t.get('ge_texto')+"<br>"+record_t.get('hora_real'),tipo_marker:'H',id_marker:0});
	            }

	            if(parseFloat(record_t.get('dir_px'))!=0 && parseFloat(record_t.get('gps_px'))!=0){
	            	routes.push(new google.maps.LatLng(parseFloat(record_t.get('dir_px')), parseFloat(record_t.get('dir_py'))));
	            	routes.push(new google.maps.LatLng(parseFloat(record_t.get('gps_px')), parseFloat(record_t.get('gps_py'))));
	            	gestion_transporte.getPolyline(routes,'#979797');
	            	routes = [];
	            }
	        });
		},
		getIconStatus:function(type){
			switch(type){
				case 'DL':
					return 'point_map_A4.png';
				break;
				case 'CV':
					return 'point_map_A2.png';
				break;
				case 'LD':
					return 'point_map_A7.png';
				break;
			}
		},
		getIconFirstStatus:function(type){
			switch(type){
				case 'DL':
					return 'point_map_A6.png';
				break;
				case 'CV':
					return 'point_map_A6.png';
				break;
				case 'LD':
					return 'point_map_A5.png';
				break;
			}
		},
		getPolyline:function(routes,color){
			new google.maps.Polyline({
                path: routes
                , map: gestion_transporte.mapa.map
                , strokeColor: color
                , strokeWeight: 3
                , strokeOpacity: 0.5
                , clickable: false
                ,geodesic: true
            });
		},
		getImgVisita:function(params){
			win.getGalery({container:'GaleryFull',url:'/gestion/gestionTransporte/get_img_tracks/',params:{vp_rut_id:params.id_guia,vp_guia:params.guia}});

			/*var mask = new Ext.LoadMask(Ext.getCmp(gestion_transporte.id+'-grid-img-visita'),{
	            msg:'Obteniendo Informacion...'
	        });
			Ext.getCmp(gestion_transporte.id+'-grid-img-visita').getStore().removeAll();
            Ext.getCmp(gestion_transporte.id+'-grid-img-visita').getStore().load(
                {params: {vp_rut_id:params.id_guia ,vp_guia:params.guia},
                callback:function(){
                	mask.hide();
                    Ext.getCmp(gestion_transporte.id+'-grid-img-visita').refresh();
                }
            });*/
		},
		setDriver:function(){
			Ext.get("contetDivDriver").update('<span style="width:20px;background:red;padding:5px;">'+gestion_transporte.dataNow.placa+'</span><span style="padding:5px;background: #000;">'+gestion_transporte.dataNow.chofer+'</span>');
		},
		getFromFinishRoute:function(){
			Ext.create('Ext.window.Window',{
				id:gestion_transporte.id+'-win',
				title:'Descarga Recolección',
				height:250,
				width:400,
				resizable:false,
				closable:true,
				minimizable: false,
				maximizable: false,
				header:true,
				border:false,
				layout:'border',
				modal:true,
				bbar:[
					'->',
					{
						text:'Salir',
						id:gestion_transporte.id+'-regresar',
						icon:'/images/icon/get_back.png',
						listeners:{
							click:function(obj,e){
								
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
                            {
				                xtype: 'textfield',
				                id:gestion_transporte.id+'-y-cord',
				                fieldLabel: 'KML',
				                labelWidth:20,
				                disabled:true,
					            listeners:{
					            	change:function(obj){
					            		//form_transito_.save_form();
					            	}
					            }
				            }
                    	]
                  	}
				],
				listeners:{
					show:function( window, eOpts ){
					},
					minimize: function(window,opts){
				   	},
				   	afterrender:function(){
				   	}
				}
			}).show();
		}
	}
	Ext.onReady(gestion_transporte.init,gestion_transporte);
}else{
	tab.setActiveTab(gestion_transporte.id+'-tab');
}
//$('#my-timeline_1').timelinexml({ src : '/timeline/timeline.xml' });
/*$('#my-timeline').timelinexml({ 
	src : '/timeline/timeline.xml?a=<?php echo rand(5, 15);?>',
	showLatest : false, 
	selectLatest : false,
	eventTagName : "event",
	dateTagName : "date",
	titleTagName : "title",
	//thumbTagName : "thumb",
	contentTagName : "content",
	linkTagName : "link",
	htmlEventClassName : "timeline-event",
	htmlDateClassName : "timeline-date",
	htmlTitleClassName : "timeline-title",
	htmlContentClassName : "timeline-content",
	htmlLinkClassName : "timeline-link",
	//htmlThumbClassName : "timeline-thumb"
});*/
</script>