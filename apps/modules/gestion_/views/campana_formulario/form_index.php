<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('campanaFormulario-tab')){
		var campanaFormulario = {
			id:'campanaFormulario',
			id_menu:'<?php echo $p["id_menu"];?>',
			url:'/gestion/campanaFormulario/',
			url_camp:'/gestion/campana/',
			url_form:'/gestion/campanaFormulario/',
			opcion:'I',
			cod_camp:0,
			init:function(){
				var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'cod_cam', type: 'string'},
                    {name: 'nombre', type: 'string'},
                    {name: 'descripcion', type: 'string'},
                    {name: 'imagen', type: 'string'},
                    {name: 'fec_crea', type: 'string'},
                    {name: 'estado', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: campanaFormulario.url_camp+'get_sis_list_campana/',
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
                    url: campanaFormulario.url+'get_campana_formulario/',
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
                    url: campanaFormulario.url+'get_list_formulario_no_incluido/',
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
                    url: campanaFormulario.url+'get_sis_list_shipper_campana/',
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
					id:campanaFormulario.id+'-form',
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
							width:'30%',
							padding:'5px 5px 5px 5px',
							layout:'border',
							items:[
								{
									region:'north',
									height:'50%',
									title:'Formularios Relacionados con la Campaña',
									border:false,
									items:[
										{
					                        xtype: 'grid',
					                        id: campanaFormulario.id + '-grid-formularios_relacion',
					                        store: store_relacion,
					                        columnLines: true,
					                        columns:{
					                            items:[
					                            	{
					                                    text: 'Orden',
					                                    dataIndex: 'orden',
					                                    width: 50
					                                },
					                                {
					                                    text: 'Nombre',
					                                    dataIndex: 'nombre',
					                                    width: 150
					                                },
					                                {
					                                    text: 'Descripcion',
					                                    dataIndex: 'descripcion',
					                                    flex: 1
					                                },
					                                {
					                                    text: 'Estado',
					                                    dataIndex: 'estado',
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
					                                            id_menu: campanaFormulario.id_menu,
					                                            icons:[
					                                                {id_serv: 5, img: 'remove.png', qtip: 'Click para Editar.', js: "campanaFormulario.setcampanaFormulario('D',"+record.get('cod_form')+")"}
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
					                                //campanaFormulario.getImagen('default.png');
					                            },
												beforeselect:function(obj, record, index, eOpts ){
													/*
													Ext.getCmp(campanaFormulario.id+'-form').el.mask('Cargando…', 'x-mask-loading');
													campanaFormulario.cod_form=record.get('cod_form');
													campanaFormulario.store_load_formuladio_componente_load.removeAll();
													campanaFormulario.store_load_formuladio_componente_load.load(
										                {params: {cod_form:record.get('cod_form')},
										                callback:function(){
										                	campanaFormulario.store_load_formuladio_detalle.removeAll();
										                	campanaFormulario.store_load_formuladio_detalle.load(
												                {params: {cod_form:record.get('cod_form')},
												                callback:function(){
												                	Ext.getCmp(campanaFormulario.id+'-form').el.unmask();
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
		                                    text: 'Buscar Formulario',
		                                    icon: '/images/icon/search.png',
		                                    listeners:{
		                                        beforerender: function(obj, opts){
		                                        },
		                                        click: function(obj, e){
		                                        	var nombre = Ext.getCmp(campanaFormulario.id+'-form-buscar').getValue();
		                                            campanaFormulario.getReloadGridcampanaFormulario(nombre);
		                                        }
		                                    }
		                                },
		                                {
					                        xtype: 'textfield',
					                        id:campanaFormulario.id+'-form-buscar',
					                        //fieldLabel: 'Buscar',
					                        //disabled:true,
					                        labelWidth:0,
					                        labelAlign:'right',
					                        width:'70%',
					                        anchor:'70%',
					                        listeners: {
                                                specialkey: function(f,e){
                                                	if(e.getKey() == e.ENTER){
	                                                    var nombre = Ext.getCmp(campanaFormulario.id+'-form-buscar').getValue();
			                                            campanaFormulario.getReloadGridcampanaFormulario(nombre);
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
					                        id: campanaFormulario.id + '-grid-formularios',
					                        store: store_formularios,
					                        columnLines: true,
					                        columns:{
					                            items:[
					                                {
					                                    text: 'Nombre',
					                                    dataIndex: 'nombre',
					                                    width: 150
					                                },
					                                {
					                                    text: 'Descripcion',
					                                    dataIndex: 'descripcion',
					                                    flex: 1
					                                },
					                                {
					                                    text: 'Estado',
					                                    dataIndex: 'estado',
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
					                                            id_menu: campanaFormulario.id_menu,
					                                            icons:[
					                                                {id_serv: 5, img: 'add.png', qtip: 'Click para Editar.', js: "campanaFormulario.setcampanaFormulario('A',"+record.get('cod_form')+")"}
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
					                                //campanaFormulario.getImagen('default.png');
					                            },
												beforeselect:function(obj, record, index, eOpts ){
													
													/*Ext.getCmp(campanaFormulario.id+'-form').el.mask('Cargando…', 'x-mask-loading');
													campanaFormulario.cod_form=record.get('cod_form');
													campanaFormulario.store_load_formuladio_componente_load.removeAll();
													campanaFormulario.store_load_formuladio_componente_load.load(
										                {params: {cod_form:record.get('cod_form')},
										                callback:function(){
										                	campanaFormulario.store_load_formuladio_detalle.removeAll();
										                	campanaFormulario.store_load_formuladio_detalle.load(
												                {params: {cod_form:record.get('cod_form')},
												                callback:function(){
												                	Ext.getCmp(campanaFormulario.id+'-form').el.unmask();
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
	                                title: 'Relación Campaña y Formularios',
	                                legend: 'Búsqueda de campañas registradas',
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
	                                                        fieldLabel: 'Campaña',
	                                                        id:campanaFormulario.id+'-txt-campana',
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
									                                //campanaFormulario.buscar_ge();
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
					                        id: campanaFormulario.id + '-grid',
					                        store: store,
					                        layout:'fit',
					                        columnLines: true,
					                        columns:{
					                            items:[
					                                {
					                                    text: 'Campaña',
					                                    dataIndex: 'nombre',
					                                    width: 150
					                                },
					                                {
					                                    text: 'Descripcion',
					                                    dataIndex: 'descripcion',
					                                    flex: 1
					                                },
					                                {
					                                    text: 'Logo',
					                                    dataIndex: 'imagen',
					                                    width: 150
					                                },
					                                {
					                                    text: 'Fecha Creación',
					                                    dataIndex: 'fec_crea',
					                                    width: 100
					                                },
					                                {
					                                    text: 'Estado',
					                                    dataIndex: 'estado',
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
					                                            id_menu: campanaFormulario.id_menu,
					                                            icons:[
					                                                {id_serv: 9, img: 'detail.png', qtip: 'Click para ver detalle.', js: 'campanaFormulario.getFormDetalleGestion()'}
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
													campanaFormulario.cod_camp=record.get('cod_cam');
													campanaFormulario.getCampanaFormulario();
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
					id:campanaFormulario.id+'-tab',
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
	                        global.state_item_menu(campanaFormulario.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,campanaFormulario.id_menu);
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(campanaFormulario.id_menu, false);
	                    }
					}

				}).show();
			},
			getFormMant:function(ID,nombre,descripcion,estado){

			},
			getCampanaFormulario:function(){
				Ext.getCmp(campanaFormulario.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				Ext.getCmp(campanaFormulario.id + '-grid-formularios_relacion').getStore().load(
	                {params: {vp_cod_camp:campanaFormulario.cod_camp},
	                callback:function(){
	                	Ext.getCmp(campanaFormulario.id+'-form').el.unmask();
	                	campanaFormulario.getReloadGridcampanaFormulario('');
	                }
	            });
			},
			getReloadGridcampanaFormulario:function(name){
				Ext.getCmp(campanaFormulario.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				Ext.getCmp(campanaFormulario.id + '-grid-formularios').getStore().load(
	                {params: {vp_cod_camp:campanaFormulario.cod_camp,vp_nombre:name},
	                callback:function(){
	                	Ext.getCmp(campanaFormulario.id+'-form').el.unmask();
	                }
	            });
			},
			getImagen:function(param){
				//win.getGalery({container:'GaleryFull',width:390,height:250,params:{forma:'F',img_path:'/campanaFormulario/'+param}});
			},
			setcampanaFormulario:function(op,cod_form){

				global.Msg({
					msg:'¿Desea actualizar la información?',
					icon:3,
					buttons:3,
					fn:function(obj){
						//console.log(obj);
						if (obj == 'yes'){
							Ext.Ajax.request({
								url:campanaFormulario.url+'set_insert_delete_relacion_camp_form/',
								params:{
									vp_op:op,
									cod_camp:campanaFormulario.cod_camp,
									cod_form:cod_form
								},
								success: function(response, options){
									var res = Ext.JSON.decode(response.responseText);
									if (res.error == 0 ){
										global.Msg({
											msg:res.data,
											icon:1,
											fn:function(){
												campanaFormulario.getCampanaFormulario();
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
				campanaFormulario.shi_codigo=0;
				campanaFormulario.getImagen('default.png');
				Ext.getCmp(campanaFormulario.id+'-txt-nombre').setValue('');
				Ext.getCmp(campanaFormulario.id+'-txt-descripcion').setValue('');
				Ext.getCmp(campanaFormulario.id+'-date-re').setValue('');
				Ext.getCmp(campanaFormulario.id+'-cmb-estado').setValue('');
				Ext.getCmp(campanaFormulario.id+'-txt-nombre').focus();
			}
		}
		Ext.onReady(campanaFormulario.init,campanaFormulario);
	}else{
		tab.setActiveTab(campanaFormulario.id+'-tab');
	}
</script>