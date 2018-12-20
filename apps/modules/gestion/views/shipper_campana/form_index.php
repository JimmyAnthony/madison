<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('shipperCampana-tab')){
		var shipperCampana = {
			id:'shipperCampana',
			id_menu:'<?php echo $p["id_menu"];?>',
			url:'/gestion/shipperCampana/',
			url_camp:'/gestion/campana/',
			url_form:'/gestion/shipperCampana/',
			url_shipper:'/gestion/shipper/',
			opcion:'I',
			shi_codigo:0,
			init:function(){
				var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'shi_codigo', type: 'string'},
                    {name: 'shi_nombre', type: 'string'},
                    {name: 'shi_logo', type: 'string'},
                    {name: 'campanas', type: 'string'},

                    {name: 'fec_ingreso', type: 'string'},
                    {name: 'shi_estado', type: 'string'},
                    {name: 'id_user', type: 'string'},
                    {name: 'fecha_actual', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: shipperCampana.url_shipper+'get_list_shipper/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        
                    }
                }
            });

			var store_campanas = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'cod_cam', type: 'string'},
                    {name: 'nombre', type: 'string'},
                    {name: 'descripcion', type: 'string'},
                    {name: 'estado', type: 'string'},
                    {name: 'imagen', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: shipperCampana.url_shipper+'get_list_campana_shipper/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        
                    }
                }
            });

            var store_no_incluido = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'cod_cam', type: 'string'},
                    {name: 'nombre', type: 'string'},
                    {name: 'descripcion', type: 'string'},
                    {name: 'estado', type: 'string'},
                    {name: 'imagen', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: shipperCampana.url+'get_list_campana_shipper_no_incluido/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        
                    }
                }
            });

			var store_relacion = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'cod_camp_form', type: 'int'},
                    {name: 'cod_camp', type: 'int'},
                    {name: 'cod_form', type: 'int'},
                    {name: 'orden', type: 'int'},
                    {name: 'estado', type: 'int'},
                    {name: 'nombre', type: 'string'},
                    {name: 'descripcion', type: 'string'}
                ],
                //autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: shipperCampana.url+'get_campana_formulario/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        
                    }
                }
            });
			var store_formularios = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'cod_form', type: 'string'},
                    {name: 'nombre', type: 'string'},
                    {name: 'descripcion', type: 'string'},
                    {name: 'estado', type: 'string'},

                    {name: 'fec_crea', type: 'string'},
                    {name: 'fec_mod', type: 'string'}
                ],
                //autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: shipperCampana.url+'get_list_formulario_no_incluido/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        
                    }
                }
            });

			var store_shipper = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'shi_codigo', type: 'string'},
                    {name: 'shi_nombre', type: 'string'},
                    {name: 'shi_logo', type: 'string'},
                    {name: 'fec_ingreso', type: 'string'},
                    {name: 'shi_estado', type: 'string'},
                    {name: 'id_user', type: 'string'},
                    {name: 'fecha_actual', type: 'string'}
                ],
                //autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: shipperCampana.url+'get_sis_list_shipper_campana/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        
                    }
                }
            });
			var myData = [
			    [1,'Activo'],
			    [0,'Inactivo']
			];
			var store_estado = Ext.create('Ext.data.ArrayStore', {
		        storeId: 'estado',
		        autoLoad: true,
		        data: myData,
		        fields: ['code', 'name']
		    });

				var panel = Ext.create('Ext.form.Panel',{
					id:shipperCampana.id+'-form',
					bodyStyle: 'background: transparent',
					border:false,
					layout:'border',
					defaults:{
						border:false
					},
					tbar:[],
					items:[
						{
							region:'east',
							border:true,
							width:'50%',
							padding:'5px 5px 5px 5px',
							layout:'border',
							items:[
								{
									region:'north',
									height:'50%',
									title:'Campañas Relacionados con el Cliente',
									border:false,
									items:[
										{
					                        xtype: 'grid',
					                        id: shipperCampana.id + '-grid-campanas',
					                        store: store_campanas,
					                        columnLines: true,
					                        columns:{
					                            items:[
					                            	{
					                                    text: 'Campaña',
					                                    dataIndex: 'nombre',
					                                    flex: 1
					                                },
					                                {
					                                    text: 'Descripcion',
					                                    dataIndex: 'descripcion',
					                                    width: 300
					                                },
													/*
					                                {
					                                    text: 'Estado',
					                                    dataIndex: 'estado',
					                                    width: 100,
					                                    align: 'center',
					                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
					                                        return value==1?'Activo':'Inactivo';
					                                    }
					                                },*/
					                                {
					                                    text: '&nbsp;',
					                                    dataIndex: '',
					                                    width: 30,
					                                    align: 'center',
					                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
					                                        metaData.style = "padding: 0px; margin: 0px";
					                                        return global.permisos({
					                                            type: 'link',
					                                            id_menu: shipperCampana.id_menu,
					                                            icons:[
					                                                {id_serv: 6, img: 'remove.png', qtip: 'Click para Editar.', js: "shipperCampana.setshipperCampana('D',"+record.get('cod_cam')+")"}
					                                            ]
					                                        });
					                                    }
					                                }
					                            ],
					                            defaults:{
					                                menuDisabled: true
					                            }
					                        },
					                        viewConfig: {
					                            stripeRows: true,
					                            enableTextSelection: false,
					                            markDirty: false
					                        },
					                        //trackMouseOver: false,
					                        listeners:{
					                            afterrender: function(obj){
					                                //shipperCampana.getImagen('default.png');
					                            },
												beforeselect:function(obj, record, index, eOpts ){
													/*
													Ext.getCmp(shipperCampana.id+'-form').el.mask('Cargando…', 'x-mask-loading');
													shipperCampana.cod_form=record.get('cod_form');
													shipperCampana.store_load_formuladio_componente_load.removeAll();
													shipperCampana.store_load_formuladio_componente_load.load(
										                {params: {cod_form:record.get('cod_form')},
										                callback:function(){
										                	shipperCampana.store_load_formuladio_detalle.removeAll();
										                	shipperCampana.store_load_formuladio_detalle.load(
												                {params: {cod_form:record.get('cod_form')},
												                callback:function(){
												                	Ext.getCmp(shipperCampana.id+'-form').el.unmask();
												                }
												            });
										                }
										            });*/
												}
					                        }
					                    }
									],
									bbar:[
										
					                    {
		                                    xtype:'button',
		                                    text: 'Buscar Campaña',
		                                    icon: '/images/icon/search.png',
		                                    listeners:{
		                                        beforerender: function(obj, opts){
		                                        },
		                                        click: function(obj, e){
		                                        	var nombre = Ext.getCmp(shipperCampana.id+'-form-buscar').getValue();
		                                            shipperCampana.getshipper();
		                                        }
		                                    }
		                                },
		                                {
					                        xtype: 'textfield',
					                        id:shipperCampana.id+'-form-buscar',
					                        //fieldLabel: 'Buscar',
					                        //disabled:true,
					                        labelWidth:0,
					                        labelAlign:'right',
					                        width:'70%',
					                        anchor:'70%',
					                        listeners: {
                                                specialkey: function(f,e){
                                                	if(e.getKey() == e.ENTER){
	                                                    var nombre = Ext.getCmp(shipperCampana.id+'-form-buscar').getValue();
			                                            shipperCampana.getReloadGridCampanasNoI();//shipperCampana.getReloadGridshipperCampana(nombre);
		                                        	}
                                                }
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
					                        xtype: 'grid',
					                        id: shipperCampana.id + '-grid-campañas-no-incluidas',
					                        store: store_no_incluido,
					                        columnLines: true,
					                        columns:{
					                            items:[
					                                {
					                                    text: 'Campaña',
					                                    dataIndex: 'nombre',
					                                    flex: 1
					                                },
					                                {
					                                    text: 'Descripcion',
					                                    dataIndex: 'descripcion',
					                                    width: 300
					                                },/*
					                                {
					                                    text: 'Estado',
					                                    dataIndex: 'estado',
					                                    width: 100,
					                                    align: 'center',
					                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
					                                        return value==1?'Activo':'Inactivo';
					                                    }
					                                },*/
					                                {
					                                    text: '&nbsp;',
					                                    dataIndex: '',
					                                    width: 30,
					                                    align: 'center',
					                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
					                                        metaData.style = "padding: 0px; margin: 0px";
					                                        return global.permisos({
					                                            type: 'link',
					                                            id_menu: shipperCampana.id_menu,
					                                            icons:[
					                                                {id_serv: 6, img: 'add.png', qtip: 'Click para Editar.', js: "shipperCampana.setshipperCampana('A',"+record.get('cod_cam')+")"}
					                                            ]
					                                        });
					                                    }
					                                }
					                            ],
					                            defaults:{
					                                menuDisabled: true
					                            }
					                        },
					                        viewConfig: {
					                            stripeRows: true,
					                            enableTextSelection: false,
					                            markDirty: false
					                        },
					                        //trackMouseOver: false,
					                        listeners:{
					                            afterrender: function(obj){
					                                //shipperCampana.getImagen('default.png');
					                            },
												beforeselect:function(obj, record, index, eOpts ){
													
													/*Ext.getCmp(shipperCampana.id+'-form').el.mask('Cargando…', 'x-mask-loading');
													shipperCampana.cod_form=record.get('cod_form');
													shipperCampana.store_load_formuladio_componente_load.removeAll();
													shipperCampana.store_load_formuladio_componente_load.load(
										                {params: {cod_form:record.get('cod_form')},
										                callback:function(){
										                	shipperCampana.store_load_formuladio_detalle.removeAll();
										                	shipperCampana.store_load_formuladio_detalle.load(
												                {params: {cod_form:record.get('cod_form')},
												                callback:function(){
												                	Ext.getCmp(shipperCampana.id+'-form').el.unmask();
												                }
												            });
										                }
										            });*/
												}
					                        }
					                    }
									]
								}
							]
						},
						{
							region:'center',
							border:false,
							//layout:'fit',
							items:[
								{
	                                //region:'north',
	                                border:false,
	                                xtype: 'uePanelS',
	                                logo: 'CL',
	                                title: 'Relación Cliente y Campaña',
	                                legend: 'Búsqueda de Clientes registrados',
	                                height:100,
	                                items:[
	                                    {
	                                        xtype:'panel',
	                                        border:false,
	                                        bodyStyle: 'background: transparent',
	                                        padding:'2px 5px 1px 5px',
	                                        layout:'column',
	                                        items: [
	                                            {
	                                                width:600,border:false,
	                                                padding:'0px 2px 0px 0px',  
	                                                bodyStyle: 'background: transparent',
	                                                items:[
	                                                    {
	                                                        xtype: 'textfield',
	                                                        fieldLabel: 'Cliente',
	                                                        id:shipperCampana.id+'-txt-cliente',
	                                                        labelWidth:80,
	                                                        //readOnly:true,
	                                                        labelAlign:'right',
	                                                        width:'100%',
	                                                        anchor:'100%'
	                                                    }
	                                                ]
	                                            },
	                                            {
	                                                width: 80,border:false,
	                                                padding:'0px 2px 0px 0px',  
	                                                bodyStyle: 'background: transparent',
	                                                items:[
	                                                    {
									                        xtype:'button',
									                        text: 'Buscar',
									                        icon: '/images/icon/binocular.png',
									                        listeners:{
									                            beforerender: function(obj, opts){
									                                /*global.permisos({
									                                    id: 15,
									                                    id_btn: obj.getId(), 
									                                    id_menu: gestion_devolucion.id_menu,
									                                    fn: ['panel_asignar_gestion.limpiar']
									                                });*/
									                            },
									                            click: function(obj, e){
									                                //shipperCampana.buscar_ge();
									                                shipperCampana.getshipper();
									                            }
									                        }
									                    }
	                                                ]
	                                            }
	                                        ]
	                                    }
	                                ]
	                            },
								{
									//region:'center',
									width:'100%',
									layout:'fit',
									items:[
										{
					                        xtype: 'grid',
					                        id: shipperCampana.id + '-grid',
					                        store: store,
					                        layout:'fit',
					                        columnLines: true,
					                        columns:{
					                            items:[
					                                {
					                                    text: 'Shipper',
					                                    dataIndex: 'shi_nombre',
					                                    flex: 1
					                                },
					                                {
					                                    text: 'N° de Campañas',
					                                    dataIndex: 'campanas',
					                                    width: 100
					                                },
					                                {
					                                    text: 'Estado',
					                                    dataIndex: 'shi_estado',
					                                    width: 100,
					                                    align: 'center',
					                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
					                                        return value==1?'Activo':'Inactivo';
					                                    }
					                                },
					                                {
					                                    text: '&nbsp;',
					                                    dataIndex: '',
					                                    width: 30,
					                                    align: 'center',
					                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view){
					                                        metaData.style = "padding: 0px; margin: 0px";
					                                        return global.permisos({
					                                            type: 'link',
					                                            id_menu: shipperCampana.id_menu,
					                                            icons:[
					                                                {id_serv: 6, img: 'detail.png', qtip: 'Click para ver detalle.', js: 'shipperCampana.getFormDetalleGestion()'}
					                                            ]
					                                        });
					                                    }
					                                }
					                            ],
					                            defaults:{
					                                menuDisabled: true
					                            }
					                        },
					                        viewConfig: {
					                            stripeRows: true,
					                            enableTextSelection: false,
					                            markDirty: false
					                        },
					                        trackMouseOver: false,
					                        listeners:{
					                            afterrender: function(obj){
					                                
					                            },
												beforeselect:function(obj, record, index, eOpts ){
													//console.log(record);
													shipperCampana.shi_codigo=record.get('shi_codigo');
													shipperCampana.getReloadGridCampanas(record.get('shi_codigo'));
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
					id:shipperCampana.id+'-tab',
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
	                        global.state_item_menu(shipperCampana.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,shipperCampana.id_menu);
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(shipperCampana.id_menu, false);
	                    }
					}

				}).show();
			},
			getFormMant:function(ID,nombre,descripcion,estado){

			},
			getshipper:function(){
				Ext.getCmp(shipperCampana.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				Ext.getCmp(shipperCampana.id + '-grid').getStore().load(
	                {params: {},
	                callback:function(){
	                	Ext.getCmp(shipperCampana.id+'-form').el.unmask();
	                }
	            });
			},
			getshipperCampana:function(){
				Ext.getCmp(shipperCampana.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				Ext.getCmp(shipperCampana.id + '-grid-formularios_relacion').getStore().load(
	                {params: {vp_cod_camp:shipperCampana.shi_codigo},
	                callback:function(){
	                	Ext.getCmp(shipperCampana.id+'-form').el.unmask();
	                	//shipperCampana.getReloadGridshipperCampana('');
	                }
	            });
			},
			getReloadGridshipperCampana:function(name){
				Ext.getCmp(shipperCampana.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				Ext.getCmp(shipperCampana.id + '-grid-formularios').getStore().load(
	                {params: {vp_cod_camp:shipperCampana.shi_codigo,vp_nombre:name},
	                callback:function(){
	                	Ext.getCmp(shipperCampana.id+'-form').el.unmask();
	                }
	            });
			},
			getImagen:function(param){
				//win.getGalery({container:'GaleryFull',width:390,height:250,params:{forma:'F',img_path:'/shipperCampana/'+param}});
			},
			setshipperCampana:function(op,cod_cam){

				global.Msg({
					msg:'¿Desea actualizar la información?',
					icon:3,
					buttons:3,
					fn:function(obj){
						//console.log(obj);
						if (obj == 'yes'){
							Ext.Ajax.request({
								url:shipperCampana.url+'set_insert_delete_relacion_shipper_campana/',
								params:{
									vp_op:op,
									cod_shipper:shipperCampana.shi_codigo,
									cod_camp:cod_cam
								},
								success: function(response, options){
									var res = Ext.JSON.decode(response.responseText);
									if (res.error == 0 ){
										global.Msg({
											msg:res.data,
											icon:1,
											fn:function(){
												shipperCampana.getReloadGridCampanas(shipperCampana.shi_codigo);
											}
										});
									}else{
										global.Msg({
											msg:res.errors,
											icon:0,
											fn:function(){
											}
										});
									}
								}
							});
						}
						
					}
				});
			},
			setNuevo:function(){
				shipperCampana.shi_codigo=0;
				shipperCampana.getImagen('default.png');
				Ext.getCmp(shipperCampana.id+'-txt-nombre').setValue('');
				Ext.getCmp(shipperCampana.id+'-txt-descripcion').setValue('');
				Ext.getCmp(shipperCampana.id+'-date-re').setValue('');
				Ext.getCmp(shipperCampana.id+'-cmb-estado').setValue('');
				Ext.getCmp(shipperCampana.id+'-txt-nombre').focus();
			},
			getReloadGridCampanas:function(shi_codigo){
				Ext.getCmp(shipperCampana.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				Ext.getCmp(shipperCampana.id + '-grid-campanas').getStore().load(
	                {params: {shi_codigo:shi_codigo},
	                callback:function(){
	                	Ext.getCmp(shipperCampana.id+'-form').el.unmask();
	                	shipperCampana.getReloadGridCampanasNoI();
	                }
	            });
			},
			getReloadGridCampanasNoI:function(){
				Ext.getCmp(shipperCampana.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				Ext.getCmp(shipperCampana.id + '-grid-campañas-no-incluidas').getStore().load(
	                {params: {shi_codigo:shipperCampana.shi_codigo},
	                callback:function(){
	                	Ext.getCmp(shipperCampana.id+'-form').el.unmask();
	                	shipperCampana.getshipper();
	                }
	            });
			}
		}
		Ext.onReady(shipperCampana.init,shipperCampana);
	}else{
		tab.setActiveTab(shipperCampana.id+'-tab');
	}
</script>