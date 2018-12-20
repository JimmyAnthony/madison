
<script type="text/javascript">

	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('red_despacho-tab')){
		var red_despacho = {
			id:'red_despacho',
			id_menu:'<?php echo $p["id_menu"];?>',
			url:'/gestion/red_despacho/',
			coord_origen_x:null,
			coord_origen_y:null,
			coord_destino_x:null,
			coord_destino_y:null,
			contenedor:null,
			info_origen:null,
			info_destino:null,
			origen_:0,
			destino_:0,
			params:{
				origen:0,
				destino:0,
				tip_via:0,
				x:0,
				y:0,
				info:''
			},
			maps:{},
			pointFirst:{},
			init: function(){



				var panel = Ext.create('Ext.form.Panel',{
					id: red_despacho.id +'-form',
					border:false,
					layout:'border',
					defaults:{
						border:false
					},
					items:[
							
							{
								xtype:'panel',
								region:'center',
								id:red_despacho.id+'cont_map',
								//border:true,
								//collapsible: true,
								layout: 'fit',
								/*width	: '100%',
								height	: '100%',*/
								//html: '<div class="maps-canvas" id="'+red_despacho.id+'Mapsa'+'"></div>',
								html: '<div id="toll_box"></div><div id="'+red_despacho.id+'Mapsa" class="ue-map-canvas"></div>',
								/*items:[

										{
						                    xtype: 'gmappanel',
						                    id:red_despacho.id+'Mapsa',
						                    center: {
						                        geoCodeAddr: '-12.0473179,-77.0824867',
						                        marker: {title: 'Mega Planta',zoom:5}
						                    },
						                }
										
								]*/
								listeners:{
									boxready: function(self) {
										red_despacho.contenedor = self.getEl();
					       			}
								}
							}
					]

				});

				tab.add({
					id:red_despacho.id+'-tab',
					border:false,
					autoScroll:true,
					closable:true,
					layout:{ 
						type:'fit'
					},
					autoDestroy: true,
					items:[
						panel
					],
					listeners:{
						beforerender: function(obj, opts){
	                        global.state_item_menu(red_despacho.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        /*Ext.getCmp(red_despacho.id+'-tab').setConfig({
	                            title: Ext.getCmp('menu-' + red_despacho.id_menu).text,
	                            icon: Ext.getCmp('menu-' + red_despacho.id_menu).icon
	                        });*/
	                      	global.state_item_menu_config(obj,red_despacho.id_menu);
	                    },
	                    close:function(){
	                    	global.state_item_menu(red_despacho.id_menu, false);
                        	//Ext.getCmp(red_despacho.id+'-tab').removeAll();
                        	//Ext.getCmp(red_despacho.id+'-tab').destroy();
                        	//console.log(form_transito_.id+'-win');
                        	try{
	                        	Ext.getCmp(red_cobertura.id+'-win').destroy();
	                        }catch(err){
                        		console.log(err);
                        	}
                        	
                        	try{
	                        	Ext.getCmp(new_age_coord.id+'-win').destroy();
                        	}catch(err){
                        		console.log(err);
                        	}	
	                        
	                        try{
	                        	Ext.getCmp(form_transito_.id+'-win').destroy();
                        	}catch(err){
                        		console.log(err);
                        	}	     

                        	try{
                        		Ext.getCmp(new_horario.id+'-win').destroy();
                        	}catch(err){
                        		console.log(err);
                        	}	
	                    },	
	                    beforeclose: function(obj, opts){
                        	global.state_item_menu(red_despacho.id_menu, false);
                        	//Ext.getCmp(red_despacho.id+'-tab').removeAll();
                        	//Ext.getCmp(red_despacho.id+'-tab').destroy();
                        	//console.log(form_transito_.id+'-win');
                        	try{
	                        	Ext.getCmp(red_cobertura.id+'-win').destroy();
	                        }catch(err){
                        		console.log(err);
                        	}
                        	
                        	try{
	                        	Ext.getCmp(new_age_coord.id+'-win').destroy();
                        	}catch(err){
                        		console.log(err);
                        	}	
	                        
	                        try{
	                        	Ext.getCmp(form_transito_.id+'-win').destroy();
                        	}catch(err){
                        		console.log(err);
                        	}	     

                        	try{
                        		Ext.getCmp(new_horario.id+'-win').destroy();
                        	}catch(err){
                        		console.log(err);
                        	}	                  			
                        	
                        },  
                        boxready:function( obj, width, height, eOpts ){
                        	red_despacho.get_provincia();
                        	//win.show({vurl: red_despacho.url + 'form_distribucion/', id_menu: red_despacho.id_menu, class: ''});
                        	win.show({vurl: red_despacho.url + 'form_coberturas/', id_menu: red_despacho.id_menu, class: ''});
                        	//win.show({vurl: red_despacho.url + 'form_transito/', id_menu: red_despacho.id_menu, class: ''});
                        }
					}
				
				}).show();
			},
			loadMap:function(origen,destino,tip_via){
				red_despacho.origen_=origen
				red_despacho.destino_=destino;
				Ext.Ajax.request({
					url:red_despacho.url+'get_coordenadas/',
					params:{vl_prov_origen:origen,vl_prov_destino:destino,tip_via:tip_via},
					success:function(response,options){
						var res = Ext.decode(response.responseText);
						red_despacho.coord_origen_x = res.data[0].coord_origen_x;
						red_despacho.coord_origen_y = res.data[0].coord_origen_y;
						red_despacho.coord_destino_x = res.data[0].coord_destino_x;
						red_despacho.coord_destino_y = res.data[0].coord_destino_y;

						red_despacho.info_origen = res.data[0].info_origen;
						red_despacho.info_destino = res.data[0].info_destino;
						red_despacho.params.origen=origen;
						red_despacho.params.destino=destino;
						red_despacho.params.tip_via=tip_via;
						red_despacho.setMap();
					}
				});
						
			},
			setMap:function(){
				if(red_despacho.coord_destino_x!=null){
				var map;
						
				//var map = Ext.getCmp(red_despacho.id+'Mapsa');
				var myLatlng = new google.maps.LatLng(red_despacho.coord_origen_x,red_despacho.coord_origen_y);
				var vl_destino = new google.maps.LatLng(red_despacho.coord_destino_x,red_despacho.coord_destino_y);
				//var vl_distrito = new google.maps.LatLng(red_despacho.params.x,red_despacho.params.y);

				var mapOptions = {
					zoom: 18,
    				center: myLatlng,
    				mapTypeId: google.maps.MapTypeId.ROADMAP
				};

				//console.log(document.getElementById(red_despacho.id+'Mapsa'));
		        map = new google.maps.Map(document.getElementById(red_despacho.id+'Mapsa'),mapOptions);
		        maps = map;
		       // var map = new google.maps.Map(Ext.get(red_despacho.id+'Mapsa'),mapOptions);
		       
		        var rendererOptions = {
					  draggable: false,
					  suppressMarkers: true
				};
				var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
				var directionsService = new google.maps.DirectionsService();
				
				var tip_envio;
				if (parseInt(red_despacho.params.tip_via) == 1){
					tip_envio = '/images/icon/transport_urbano.png';
				}else if (parseInt(red_despacho.params.tip_via) == 2 ){
					tip_envio = '/images/icon/Aircraft.ico';
				}else{
					//tip_envio = '/images/icon/Delivery-Truck.ico';
				}

				red_despacho.comentarios(map,myLatlng,red_despacho.info_origen,tip_envio);

				Ext.each(red_despacho.pointFirst.data, function(value){
					if(parseInt(value.prov_codigo) !=red_despacho.origen_)
						if(parseInt(value.prov_codigo) !=red_despacho.destino_)
						red_despacho.mark_prov(value.x,value.y,maps,value.prov_descri,value.dir_calle,value.prov_foto);
					
				});

				directionsDisplay.setMap(map);
			
				
					
				var request = {
				    origin:red_despacho.coord_origen_x+','+red_despacho.coord_origen_y,
				    destination:red_despacho.coord_destino_x+','+red_despacho.coord_destino_y,
				  //  waypoints:[],//{location: agencia},{location: '-13.918217, -74.330162'}  
				    optimizeWaypoints: true,
				    travelMode: google.maps.TravelMode.DRIVING,
				    provideRouteAlternatives: true
				};
			

				directionsService.route(request, function(response, status){
				    if (status == google.maps.DirectionsStatus.OK) {
					    directionsDisplay.setDirections(response);
					    var distancia = red_despacho.info_destino+'Distancia:'+(response.routes[0].legs[0].distance.text);//+'<br>Tiempo:'+(response.routes[0].legs[0].duration.text)
					    red_despacho.comentarios(map,vl_destino,distancia,'');
				    }else{
				    	try{
					    	red_despacho.comentarios(map,vl_destino,red_despacho.info_destino,'');
					    	var routes = [];
		                    routes.push(new google.maps.LatLng(red_despacho.coord_origen_x, red_despacho.coord_origen_y));
		                    routes.push(new google.maps.LatLng(red_despacho.coord_destino_x, red_despacho.coord_destino_y));
					    	var polyline = new google.maps.Polyline({
	                            path: routes
	                            , map: map
	                            , strokeColor: '#1874CD'
	                            , strokeWeight: 5
	                            , strokeOpacity: 0.5
	                            , clickable: false
	                            ,geodesic: true
	                        });
	                        map.setZoom(6);
					    }catch(err){

					    }
				    }
				});
				}
			},
			comentarios:function(map,coordenadas,string,image){
				if (string != ''){
					var contentString = '<div id="content"  style="width:255px;">'+
					      string +
					      '</div>';
					var infowindow = new google.maps.InfoWindow({
					      content: contentString,
					      maxWidth: 255
					});      
					var marker = new google.maps.Marker({
					      position: coordenadas,
					      map: map,
					      title: 'Red de Distribucion Urbano',
					      icon: image
					});
					
					infowindow.open(map,marker);
				    google.maps.event.addListener(marker, 'click', function() {
				        infowindow.open(map,marker);
				    });
			    }
			},
			get_provincia:function(){
				var myLatlng = new google.maps.LatLng(-12.0473179,-77.0824867);
				var mapOptions = {
							zoom: 5,
		    				center: myLatlng,
		    				mapTypeId: google.maps.MapTypeId.ROADMAP,
				};
				map = new google.maps.Map(document.getElementById(red_despacho.id+'Mapsa'),mapOptions);

				Ext.Ajax.request({
					url:red_despacho.url+'scm_get_provincia',
					params:{},
					success:function(response,options){
						var res = Ext.decode(response.responseText);
						red_despacho.pointFirst=res;
						var x;
						var y;
						var prov_descri;
						Ext.each(res.data, function(value){
							x = value.x;
							y = value.y;
							prov_descri = value.prov_descri;
							red_despacho.mark_prov(x,y,map,prov_descri,value.dir_calle,value.prov_foto);
						});

					}
				});

			},
			mark_prov:function(x,y,map,string,dir_calle,prov_foto){
				var myLatlng = new google.maps.LatLng(x,y);
				var marker = new google.maps.Marker({
				      position: myLatlng,
				      map: map,
				      title: 'Red de Distribucion Urbano',
				      icon: '/images/icon/stack_urbano_.png'
				});

				var contentString = '<div id="content"  style="width:205px;"><b>'+
					      string +'<br>'+
					      dir_calle +'<br>'+
					      '</b></div>';
				if(prov_foto!='')contentString+='<div style="padding:5px"><img src="/foto_agencias/'+prov_foto+'" width="205" /></div>';
				var infowindow = new google.maps.InfoWindow({
					      content: contentString,
					      maxWidth: 210
				});

				google.maps.event.addListener(marker, 'click', function() {
				        infowindow.open(map,marker);
			    });
			},
			provincias:function(ubigeo){

					geocoder = new google.maps.Geocoder();
			        var latLng = new google.maps.LatLng(-12.047085762023926,-77.08118438720703);
			        var myOptions = {
			           center: latLng,//centro del mapa
			           zoom: 18,//zoom del mapa
			           mapTypeId: google.maps.MapTypeId.ROADMAP //tipo de mapa, carretera, híbrido,etc
			         };
		     	    map = new google.maps.Map(document.getElementById(red_despacho.id+'Mapsa'), myOptions);
		     	    
			        marker = new google.maps.Marker({
			            map: map,//el mapa creado en el paso anterior
			            position: latLng,//objeto con latitud y longitud
			            draggable: true //que el marcador se pueda arrastrar
			        });
			        var address = ubigeo;//document.getElementById("direccion").value;
			        //hago la llamada al geodecoder
			        geocoder.geocode( { 'address': address}, function(results, status) {
			        if (status == google.maps.GeocoderStatus.OK) {
			            //centro el mapa en las coordenadas obtenidas
			            map.setCenter(results[0].geometry.location);
			            //coloco el marcador en dichas coordenadas
			            marker.setPosition(results[0].geometry.location);
			            //actualizo el formulario      
			           // updatePosition(results[0].geometry.location);
			            google.maps.event.addListener(marker, 'dragend', function(){
							//console.log(marker.getPosition());
				            //console.log(marker.getPosition().lat());
				           // console.log(marker.getPosition().lng());
				            Ext.getCmp(new_age_coord.id+'-x').setValue(marker.getPosition().lat());
				            Ext.getCmp(new_age_coord.id+'-y').setValue(marker.getPosition().lng());
			            });
			      } else {
			         global.Msg({
			         	msg:"No podemos encontrar la direcci&oacute;n, error: " + status,
			         	icon:0
			         });
			      }
			    });
			}


		}
		 Ext.onReady(red_despacho.init,red_despacho);
	}else{
		 tab.setActiveTab(red_despacho.id+'-tab');
	}

</script>