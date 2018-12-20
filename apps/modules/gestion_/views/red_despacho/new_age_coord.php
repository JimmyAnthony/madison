<script type="text/javascript">
	var new_age_coord = {
		id:'new_age_coord',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/red_despacho/',
		init:function(){
			var panel = Ext.create('Ext.form.Panel',{
				/*layout:{
					type:'form',
					alingn:'stretch'
				},*/
				layout:'fit',
				border:false,
				defaults:{
					border:false
				},
				items:[
						{
							xtype:'panel',
							layout:'border',
							border:false,
							items:[
									{
									   region:'center',
									   border:false,
			                           xtype: 'findlocation',
			                           id: new_age_coord.id+'-direc',
			                           mapping: false,
			                           clearReferent:false,
			                           getMapping:false,
			                           setMapping:red_despacho.id+'Mapsa',
			                           listeners:{
			                                afterrender: function(obj){
			                                }
			                           }
			                        },
			                        {
			                        	region:'south',
			                        	height:50,
						 				xtype:'form',
						 				border:false,
						 				layout:'hbox',
						 				id:new_age_coord.id+'-image',
						 				items:[
						 						{
										            xtype: 'filefield',
										            padding:'0 0 0 10',
										            id:new_age_coord.id+'-image-up',
										            disabled:true,
										            emptyText: 'Ingrese una imagen',
										            labelWidth:40,
										            labelAlign: 'top',
										            anchor:'95%',
										            width:'95%',
										            fieldLabel: 'Foto de Agencia',
										            name: 'photo-path',
										            buttonConfig: {
										                text : '',
										                iconCls: 'upload-icon'
										            }
										        }
						 				]
									}
							]
						},
                        
				]
			});

			Ext.create('Ext.window.Window',{
				id:new_age_coord.id+'-win',
				title:'',
				height:380,
				width:400,
				resizable:false,
				closable:false,
				plain:true,
				minimizable: true,
				constrain: true,
				constrainHeader:true,
				renderTo:Ext.get(red_despacho.id+'-tab'),//red_despacho.contenedor,
				header:false,
				border:false,
				layout:{
					type:'fit'
				},
				modal:false,
				items:[
						panel
				],
				dockedItems:[
								{
									xtype:'toolbar',
									dock:'bottom',
									ui:'footer',
									alignTarget:'center',
									layout:{
										pack:'center'
									},
									baseCls: 'gk-toolbar',
									items:[
											{
												text:'Modificar',
												id:new_age_coord.id+'-Modificar',
												icon: '/images/icon/edit.png',
												listeners:{
				                                    click: function(obj, e){
				                                    	new_age_coord.botones(false);
				                                    	new_age_coord.controles(false);
				                                    }
				                                }
											},
										
											{
												text:'Grabar',
												id:new_age_coord.id+'-grabar',
												icon: '/images/icon/save.png',	
												listeners:{
													beforerender: function(obj, opts){
														/*global.permisos({
						                                    id_serv: 0, 
						                                    id_btn: obj.getId(), 
						                                    id_menu: new_age_coord.id_menu,
						                                    fn: ['']
						                                });*/
													},
													click:function(obj,e){
														new_age_coord.save_newdir();
													}
												}	
											},
											/*{
												text:'Buscar',
												id:new_age_coord.id+'-Buscar',
												icon: '/images/icon/search.png',
												listeners:{
													click:function(obj,opts){
														new_age_coord.busca_direccion();
													}
												}
											},*/
											{
												text:'Cancelar',
												id:new_age_coord.id+'-cancelar',
												icon:'/images/icon/cancel.png',
												listeners:{
													click:function(obj,e){
														new_age_coord.controles(true);
														new_age_coord.botones(true);
													}
												}
											},
											{
												text:'Regresar',
												id:new_age_coord.id+'-salir',
												icon: '/images/icon/get_back.png',
												listeners:{
				                                    click: function(obj, e){
				                                    	//Ext.getCmp(red_cobertura.id+'-win').restore;
				                                    	Ext.getCmp(red_cobertura.id+'-win').show();
				                                        Ext.getCmp(new_age_coord.id+'-win').close();

				                                    }
				                                }
											},
											
									]
								}
				],
				listeners:{
					afterrender:function(obj, e){
						var origen = Ext.getCmp(red_cobertura.id+'-origen').getValue();
						Ext.Ajax.request({
							url:new_age_coord.url+'red_despacho_get_linehaul/',
							params:{vl_prov_origen:origen},
							success:function(response,options){
								var res = Ext.decode(response.responseText);
								var dir_id = parseInt(res.data[0].dir_id);
								Ext.getCmp(new_age_coord.id+'-direc').setGeoLocalizar({dir_id:dir_id});
								//console.log(res.data[0].dir_px);
								//Ext.getCmp(new_age_coord.id+'-dirección').setValue(res.data[0].dir_calle);
								//Ext.getCmp(new_age_coord.id+'-referencia').setValue(res.data[0].dir_referen);
								//Ext.getCmp(new_age_coord.id +'-ciudad').setValue(parseInt(res.data[0].ciu_id));
								//Ext.getCmp(new_age_coord.id+'-x').setValue(res.data[0].dir_px);
								//Ext.getCmp(new_age_coord.id+'-y').setValue(res.data[0].dir_py);
								//red_despacho.provincias(res.data[0].dir_px +','+res.data[0].dir_py);
							}
						});

						new_age_coord.controles(true);
						new_age_coord.botones(true);

					},
					show:function(window,eOpts){
						window.alignTo(Ext.get(red_despacho.id+'Mapsa'), 'bl-bl');
					},
					minimize: function(window,opts){
				   		window.collapse();
		                window.setWidth(100);
		                window.alignTo(Ext.get(red_despacho.id+'Mapsa'), 'bl-bl');
				   	}

				},
				tools:[{
					type:'restore',
					handler:function (evt, toolEl, owner, tool){
						var window = owner.up('window');
		                window.setWidth(400);
		                window.expand('', false);
		                window.alignTo(Ext.get(red_despacho.id+'Mapsa'), 'bl-bl');
					}

				}]
			}).show();

		},
		busca_direccion:function(){
			var ciudad = Ext.getCmp(new_age_coord.id +'-ciudad').getRawValue();
			var dir = Ext.getCmp(new_age_coord.id+'-dirección').getRawValue();
			//console.log(dir);
			red_despacho.provincias(dir +' '+ciudad);
		},
		save_newdir:function(){
			var rec = Ext.getCmp(new_age_coord.id+'-direc').getValues();
			var vp_prov_codigo = Ext.getCmp(red_cobertura.id+'-origen').getValue();

			var dir_id = 0;
			var id_geo = rec[0].id_puerta == null ? 0:rec[0].id_puerta;
			var ciu_id = rec[0].ciu_id;
			var via_id = rec[0].id_via;
			var dir_calle = rec[0].dir_calle;
			var dir_numvia = rec[0].nro_via;
			var dir_referent = rec[0].referencia;
			var urb_id = rec[0].id_urb;
			var urb_nombre = rec[0].nombre_urb;
			var mz_id = rec[0].id_mza == null ? 0:rec[0].id_mza;
			var mz_nom = rec[0].nombre_mza;
			var num_lote = rec[0].nro_lote;
			var num_int = rec[0].nro_interno;	
			var lat_x = rec[0].coordenadas[0].lat;
			var lon_y = rec[0].coordenadas[0].lon;
			//console.log(mz_id);

			//Ext.Ajax.request({
			Ext.getCmp(new_age_coord.id+'-image').getForm().submit({				
				url: new_age_coord.url+'red_despacho_graba_provincia/',
				params:{vp_prov_codigo:vp_prov_codigo,vp_dir_id:dir_id,vp_id_geo:id_geo,vp_ciu_id:ciu_id,vp_via_id:via_id,vp_dir_calle:dir_calle,vp_dir_numvia:dir_numvia,
						vp_dir_referent:dir_referent,vp_urb_id:urb_id,vp_urb_nombre:urb_nombre,vp_mz_id:mz_id,vp_mz_nom:mz_nom,vp_num_lote:num_lote,
						vp_num_int:num_int,vp_lat:lat_x,vp_lon:lon_y},
				//success: function(response,options){
				success: function(options,resp){					
					//var res = Ext.decode(response.responseText);
					var res = Ext.decode(resp.response.responseText);
					//console.log(res);
					if (parseInt(res.data[0].error_sql) < 0){
						global.Msg({
							msg:res.data[0].error_info,
							icon:0,
							fn:function(btn){
							}
						});
					}else{
						global.Msg({
							msg:res.data[0].error_info,
							icon:1,
							fn:function(btn){
								red_despacho.get_provincia();
								new_age_coord.controles(true);
								new_age_coord.botones(true);
							}
						});
					}
				}		
			});
			


			//console.log(dir);
			/*
			var vp_prov_codigo = Ext.getCmp(red_cobertura.id+'-origen').getValue();
			var vp_ciu_id 	   = Ext.getCmp(new_age_coord.id +'-ciudad').getValue();	
			var vp_dir_calle   = Ext.getCmp(new_age_coord.id+'-dirección').getValue();
			var vp_dir_referen = Ext.getCmp(new_age_coord.id+'-referencia').getValue();
			var vp_dir_px 	   = Ext.getCmp(new_age_coord.id+'-x').getValue();	
			var vp_dir_py	   = Ext.getCmp(new_age_coord.id+'-y').getValue();*/
			
			
			/*Ext.getCmp(new_age_coord.id+'-image').getForm().submit({
                url: new_age_coord.url+'red_despacho_graba_provincia/',
                params:{vp_prov_codigo:vp_prov_codigo,vp_ciu_id:vp_ciu_id,vp_dir_calle:vp_dir_calle,vp_dir_referen:vp_dir_referen,vp_dir_px:vp_dir_px,vp_dir_py:vp_dir_py},
                success: function(options,resp){
                	var res = Ext.decode(resp.response.responseText);
					if (parseInt(res.data[0].error_sql) < 0){
						global.Msg({
							msg:res.data[0].error_info,
							icon:0,
							fn:function(btn){
								
							}
						});
					}else{
						global.Msg({
							msg:res.data[0].error_info,
							icon:1,
							fn:function(btn){
								red_despacho.get_provincia();
								new_age_coord.controles(true);
								new_age_coord.botones(true);
							}
						});
					}
                }
            });*/

			/*return
			Ext.Ajax.request({
				url:new_age_coord.url+'red_despacho_graba_provincia/',
				params:{vp_prov_codigo:vp_prov_codigo,vp_ciu_id:vp_ciu_id,vp_dir_calle:vp_dir_calle,vp_dir_referen:vp_dir_referen,vp_dir_px:vp_dir_px,vp_dir_py:vp_dir_py},
				success:function(response,options){
					var res = Ext.decode(response.responseText);
					if (parseInt(res.data[0].error_sql) < 0){
						global.Msg({
							msg:res.data[0].error_info,
							icon:0,
							fn:function(btn){
							}
						});
					}else{
						global.Msg({
							msg:res.data[0].error_info,
							icon:1,
							fn:function(btn){
							}
						});
					}
				}
			});*/
		},
		controles:function(control){
			//Ext.getCmp(new_age_coord.id+'-dirección').setReadOnly(control);
			//Ext.getCmp(new_age_coord.id+'-referencia').setReadOnly(control);
			//Ext.getCmp(new_age_coord.id +'-ciudad').setReadOnly(control);
			Ext.getCmp(new_age_coord.id+'-image-up').setDisabled(control);
		},
		botones:function(control){
			if (control){
				Ext.getCmp(new_age_coord.id+'-Modificar').enable();
				Ext.getCmp(new_age_coord.id+'-grabar').disable();
				//Ext.getCmp(new_age_coord.id+'-Buscar').disable();
				Ext.getCmp(new_age_coord.id+'-cancelar').disable();
				Ext.getCmp(new_age_coord.id+'-salir').enable();
			}else{
				Ext.getCmp(new_age_coord.id+'-Modificar').disable();
				Ext.getCmp(new_age_coord.id+'-grabar').enable();
				//Ext.getCmp(new_age_coord.id+'-Buscar').enable();
				Ext.getCmp(new_age_coord.id+'-cancelar').enable();
				Ext.getCmp(new_age_coord.id+'-salir').disable();
			}

		}

	}
	Ext.onReady(new_age_coord.init,new_age_coord);
</script>