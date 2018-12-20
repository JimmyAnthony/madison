<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if (!Ext.getCmp('planviaje')){
		var planviaje = {
			id:'planviaje',
			id_menu:'<?php echo $p["id_menu"];?>',
			url: '/gestion/planviaje/',
			mapa:{
				directionsDisplay:new google.maps.DirectionsRenderer(),
				directionsService:new google.maps.DirectionsService(),
				trafficLayer:new google.maps.TrafficLayer(),
				map:null,
			},
				
			init:function(){
				this.store_car = Ext.create('Ext.data.Store',{
		            fields: [
		                {name: 'id', type: 'int'},
		                //{name: 'nivel', type: 'int'}
		            ],
		            autoLoad:true,
		            proxy:{
		                type: 'ajax',
		                url: planviaje.url+'usr_sis_provincias/',
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
		                	//console.log(records);
		                	planviaje.setMap();
		                	google.load("visualization", "1",{"callback" : planviaje.drawLine});
		                }
		            }
		        });
		        
				var imageTplPointer = new Ext.XTemplate(
		            /*'<tpl for=".">',
		                '<div class="planviaje_list_transport_select" >',
	                        '<div class="planviaje_list_transport" >',
	                            '<div class="planviaje_resume_transport" >',
	                            	'<div class="planviaje_resum_content">',
	                            		'<div class="planviaje_aling_r">',
			                                '<div class="">',//databox_barx
			                                	'<div class="planviaje_img">',
				                                    '<img src="/images/icon/delivery_track.png" />',
				                                '</div>',
					                            '<div class="planviaje_placa_transport">',
					                                '<span>AQP - BJA-6542</span>',
					                                '<div class="planviaje_user_transport"><span class="dbx_user">GR-U:01500543</span></div>',
					                                '<div class="planviaje_user_transport"><span class="dbx_user">GR-U:01500543</span></div>',
					                            '</div>',
					                        '</div>',
											'<div class="planviaje_cell">',
						                        '<span>Pieza:6  Peso:34.5Kg</span>',
											'</div>',
										'</div>',
									'</div>',
							

									'<div class="planviaje_battery_transport">',
		                            '</div>',

	                            '</div>',
	                            '<div class="databox_time_transport" >',
	                            	'<div class="styleTimeLine" >',
		                            	'<div id="planviaje-timeline-{id}">',
		                            	'</div>',
	                            	'</div>',
	                            '</div>',
	                        '</div>',

	                        '<div class="databox_api_resumen" >',
	                        	'<div id="databox_api_resumen_{id}" >',
	                        	'</div>',
	                        '</div>',

	                    '</div>',
		            '</tpl>'*/
		            '<tpl for=".">',
	                '<div class="pdatabox_list_transport_select" >',
                        '<div class="pdatabox_list_transport" >',
                        	//'<div class="" >{styleactive}</div>',
                            '<div class="pdatabox_resume_transport" >',
                            	'<div class="pdatabox_user_transport"><span class="pdbx_user">nombre chofer</span></div>',
                            	'<div class="pdatabox_resum_content">',
                            		'<div class="pdatabox_aling_r">',
		                                '<div class="pdatabox_barx">',
		                                	'<div class="pbox_img">',
			                                    '<img src="/images/icon/delivery_track.png" />',
			                                '</div>',
				                            '<div class="pdatabox_placa_transport">',
				                                '<span>Placa</span>',
				                            '</div>',
				                        '</div>',
				                        '<div class="pdatabox_progress">',
					                        '<div id="progress">',
											    //'<div id="progress-bar" class="progress-bar-striped active" role="progressbar" aria-valuenow="{progreso}" aria-valuemin="0" aria-valuemax="100" style="width:{progreso}%" >',
											    '<div id="progress-bar" class="progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%" >',
											      '50%',
											    '</div>',
											'</div>',
										'</div>',
										'<div class="pdatabox_cell">',
					                        '<span>5532160</span>',
										'</div>',
									'</div>',
								'</div>',
								'<div class="pdatabox_pie_chart_stl">',
									'<div id="pdatabox_pie_chart_{id}">',
									'</div>',
								'</div>',
								'<div class="databox_battery_transport">',
	                                '<div class="battery_box"><div class="battery_5"></div></div>',
	                                '<div class="battery_text">100%</div>',
	                                '<div class="countTransport">35</div>',
	                            '</div>',
                            '</div>',
                            '<div class="pdatabox_time_transport" >',
                            	'<div class="pstyleTimeLine" >',
	                            	'<div id="pmy-timeline_{id}">',
	                            	'</div>',
                            	'</div>',
                            '</div>',
                        '</div>',
                        '<div class="pdatabox_api_resumen" >',
                        	'<div id="pdatabox_api_resumen_{id}" >',
                        	'</div>',
                        '</div>',
                    '</div>',
	            '</tpl>'
		        );

				var panel = Ext.create('Ext.form.Panel',{
					border:false,
					layout:'border',
					defaults:{
						//border:false
					},
					items:[
							{
								region:'west',
								width:'60%',
								//frame:true,
								border:'5 5 5 5',
								layout:'fit',
								tbar:[
										{
											xtype:'combo',
											fieldLabel:'Origen',
											labelWidth:40
										},
										{
											xtype:'tbspacer',
											width:20
										},
										{
											xtype:'combo',
											fieldLabel:'Destino',
											labelWidth:45
										},
										{
											xtype:'button',
											icon: '/images/icon/search.png',
										}
								],
								items:[
										{
											xtype:'dataview',
											id:planviaje.id+'-despachos',
											layout:'fit',
											store:planviaje.store_car,
											autoScroll:true,
											loadMask:true,
											autoHeight:false,
											tpl:imageTplPointer,
											multiSelect: false,
				                        	singleSelect: false,
				                        	loadingText:'Cargando Despachos...',
				                        	//emptyText: '<div class="databox_list_transport"><div class="databox_none_data" ></div><div class="databox_title_clear_data">NO EXISTE REGISTRO</div></div>',
				                        	itemSelector: 'div.planviaje_list_transport_select',
				                        	trackOver: true,
				                        	//overItemCls: 'databox_list_transport-hover',
				                        	listeners:{
				                        		afterrender:function( obj, eOpts ){
				                        			//console.log(this.getData( ) );
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
														layout:'fit',
														//anchor:'100%',
														//height:'100%',
														//width:'100%',
														border:false,
														html:'<div id="'+planviaje.id+'-map" class="ue-map-canvas"></div>',
														listeners:{
															beforerender:function(obj){
																
															}
														}
													}
											]
										}
								]
							}
					]
				});

				tab.add({
					id:planviaje.id+'-tab',
					border:false,
					autoScroll: true,
					closable: true,
					layout:{
	                    type: 'fit'
	                },
	                items:[
	                	panel
	                ],
	                listeners:{
	                	beforerender: function(obj, opts){
	                		global.state_item_menu(planviaje.id_menu, true);	
	                    },
	                    afterrender: function(obj, e){
                        	tab.setActiveTab(obj);
                        	global.state_item_menu_config(obj,planviaje.id_menu);	
	                    },
	                    beforeclose: function(obj, opts){
	                    	global.state_item_menu(planviaje.id_menu, false);	
	                    }
	                }

				}).show();
			},
			drawLine:function(rec){
				//console.log(rec);

				planviaje.store_car.each(function(rec){
					//console.log(rec.id);
					/*$('#pase'+rec.id).on('mousedown', function(e){
						planviaje.moveSlider(e,rec.id);
						$(this).on('mousemove', function(e){
							planviaje.moveSlider(e,rec.id);
						});
					}).on('mouseup', function(){
						$(this).off('mousemove');
					});    */

					id = rec.id;
					var timeline;
					var data = new google.visualization.DataTable();
					data.addColumn('datetime', 'start');
		            data.addColumn('datetime', 'end');
		            data.addColumn('string', 'content');
		            data.addColumn('string', 'className');
		            
			        data.addRows([
		                [new Date(2015,6,21,21,25,13), , '<img src="/images/icon/marker_r.png>','astyle'],
		                [new Date(2015,6,21,21,0,0), , '<img src="/images/icon/marker_r.png">','astyle'],
		                [new Date(2015,6,21,22,0,0), , '<img src="/images/icon/marker_r.png">','astyle'],
		                [new Date(2015,6,21,23,30,0), , '<img src="/images/icon/marker_r.png">','astyle'],
		                [new Date(2015,6,21,24,0,0), , '<img src="/images/icon/marker_r.png">','bstyle'],
		                [new Date(2015,6,21,25,20,0), , '<img src="/images/icon/marker_r.png">','bstyle'],
		                [new Date(2015,6,21,26,45,0), , '<img src="/images/icon/marker_r.png">','bstyle'],
		                [new Date(2015,6,21,27,5,0), , '<img src="/images/icon/marker_r.png">','bstyle']
		            ]);

		            var options = {
		                "width":  "100%",
		                "height": "80px",
		                "style": "box",
		                "min":new Date(2015,5,21),
		                "max":new Date(2015,7,21),
		                "showCurrentTime":"true",
		                "showCustomTime":"true",
		                "locale":"ES",
		                axisOnTop: false,
			            eventMargin: 10,  // minimal margin between events
			            eventMarginAxis: 4, // minimal margin beteen events and the axis
			            editable: false,
			            showNavigation: false,
			            showMajorLabels: false,
			            groupsChangeable : false,
	                	//groupsOnRight: false
		            };
		            timeline = new links.Timeline(document.getElementById('pmy-timeline_'+id), options);
		            timeline.draw(data);
		        });
		        
			},
			setMap:function(){
				//var directionsService = new google.maps.DirectionsService();
		        var rendererOptions = {
					  draggable: true,
					  suppressMarkers: true
				};
		        planviaje.mapa.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
		        planviaje.mapa.directionsService = new google.maps.DirectionsService();
		        var myLatlng = new google.maps.LatLng(-12.0473179,-77.0824867);
		        var mapOptions = {
					zoom: 11,
					center: myLatlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
		        planviaje.mapa.map = new google.maps.Map(document.getElementById(planviaje.id+'-map'), mapOptions);
		        planviaje.mapa.directionsDisplay.setMap(planviaje.mapa.map);
			},
			/*moveSlider:function(e,idd){
				e.preventDefault();
				var pos 	= $(e.currentTarget).offset()
				,	posY	= e.pageY - pos.top
				,	value	= parseInt((posY*100/$(e.currentTarget).outerHeight()-100) *-1);

				if(posY >= 0 && posY <= $(e.currentTarget).outerHeight()){
					$('#pase'+idd+' .progress').css('height', posY+'px');
					$('#pase'+idd+' .indicator').css('top', posY+'px');
					console.log(value);
					//get();
					//console.log($(e.currentTarget).offset());
				}
			},
			setvalue:function(por,idd){
				//por = 10;
				por = 100 -por;
				var height = $('#pase'+idd).height();
				var posY = (por*height)/100;
				$('#pase'+idd+' .progress').css('height', posY+'px');
				$('#pase'+idd+' .indicator').css('top', posY+'px');
				console.log(por);
			}*/

		}
		Ext.onReady(planviaje.init,planviaje);
	}else{
		tab.setActiveTab(planviaje.id+'-tab');
	}
</script>