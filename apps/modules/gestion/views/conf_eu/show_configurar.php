<script type="text/javascript">
	var show_configurar = {
		id:'show_configurar',
		id_menu:'<?php echo $p["id_menu"];?>',
		url: '/gestion/conf_eu/',
		//prov_codigo:'<?php echo PROV_CODIGO;?>',//combio en el afterender el prov_codigo cuando es edicion
		array:JSON.parse( '<?php echo json_encode($p);?>' ),
		init:function(){
			var store = Ext.create('Ext.data.Store',{
				//autoLoad:true,
				fields:[
						{name:'cel_id',type:'string'},
						{name:'cel_imei',type:'string'},
						{name:'cel_numero',type:'string'},
						{name:'estado',type:'boolean'},
						                   
				],
				proxy:{
					type:'ajax',
					url:show_configurar.url+'scm_scm_hue_select_permiso_mac/',
					reader:{
						type:'json',
						root:'data'
					}
				}
			});


			var panel = Ext.create('Ext.form.Panel',{
				id:show_configurar.id+'-panel-configurar',
				layout:'column',
				defaults:{
					margin:'5 5 5 5',
				},
				border:false,
				bbar:[
						{
							text:'',
							icon: '/images/icon/save.png',
							listeners:{
								click:function(obj){
									show_configurar.save();
								}
							}
						},
						{
							text:'',
							icon: '/images/icon/get_back.png',
							listeners:{
								click:function(obj){
									Ext.getCmp(show_configurar.id+'-win-configurar').close();
								}
							}
						}
				],
				items:[
						/*{
							xtype:'combo',
							allowBlank: false,
							fieldLabel:'Personal',
							id:show_configurar.id+'-per_id',
							columnWidth:0.33,
							store: Ext.create('Ext.data.Store',{
							fields:[
	                                {name: 'id_per', type: 'int'},
	                                {name: 'codigo', type: 'string'},
	                                {name: 'nombres', type: 'string'}          
	                            ],
	                            proxy:{
	                                type: 'ajax',
	                                url: show_configurar.url+'scm_scm_lista_personal/',
	                                reader:{
	                                    type: 'json',
	                                    rootProperty: 'data'
	                                }
	                            }
	                        }),
	                        queryMode: 'local',
	                        valueField: 'id_per',
	                        displayField: 'nombres',
	                        listConfig:{
	                            minWidth: 400
	                        },
	                        width: 200,
	                        forceSelection: true,
	                        allowBlank: false,
	                        selectOnFocus:true,
	                        emptyText: '[ Seleccione ]',
	                        listeners:{
	                            afterrender: function(obj,record,options){
	                            	obj.getStore().load({
	                            		params:{vp_prov:show_configurar.prov_codigo,vp_linea:2,vp_cargo:0}
	                            	});
	                               
	                            }
	                        }
						},*/
						
						{
							xtype:'combo',
							id:show_configurar.id+'-id_turno',
							fieldLabel:'Turno',
							columnWidth:0.5,
							store: Ext.create('Ext.data.Store',{
							fields:[
	                               	{name:'id_turno' , type:'int'},
									{name:'hora_ini' , type:'string'},
									{name:'hora_fin' , type:'string'},
									{name:'hora_ini_ref' , type:'string'},
									{name:'hora_fin_ref' , type:'string'},
									{name:'tur_estado' , type:'string'}      
	                            ],
	                            autoLoad:true,
	                            proxy:{
	                                type: 'ajax',
	                                url: show_configurar.url+'scm_scm_hue_select_turnos_laboral/',
	                                reader:{
	                                    type: 'json',
	                                    rootProperty: 'data'
	                                }
	                            }
	                        }),
	                        queryMode: 'local',
	                        valueField: 'id_turno',
	                        displayField: 'rango',
	                        listConfig:{
	                            minWidth: 200
	                        },
	                        width: 200,
	                        forceSelection: true,
	                        allowBlank: false,
	                      //  selectOnFocus:true,
	                        emptyText: '[ Seleccione ]',
	                        listeners:{
	                            afterrender: function(obj,record,options){
	                            	/*obj.getStore().load({
	                            		params:{vp_prov:show_configurar.prov_codigo,vp_linea:2,vp_cargo:0}
	                            	});*/
	                               
	                            }
	                        }
						},
						{
							xtype:'combo',
							id:show_configurar.id+'-cbo-celular',
							fieldLabel:'Celular',
							columnWidth:0.5,
							store: Ext.create('Ext.data.Store',{
							fields:[
	                               	{name:'cel_id', type:'int'},
									{name:'cel_imei', type:'string'},
									{name:'cel_numero', type:'string'},
									{name:'cel_num_rp', type:'string'},
									{name:'tprop_id', type:'int'},
									{name:'prop_descri', type:'string'},
									{name:'cel_estado', type:'string'}      
	                            ],
	                           // autoLoad:true,
	                            proxy:{
	                                type: 'ajax',
	                                url: show_configurar.url+'scm_scm_hue_select_celulares/',
	                                reader:{
	                                    type: 'json',
	                                    rootProperty: 'data'
	                                }
	                            }
	                        }),
	                        queryMode: 'local',
	                        valueField: 'cel_id',
	                        displayField: 'cel_numero',
	                        listConfig:{
	                            minWidth: 200
	                        },
	                        width: 200,
	                        forceSelection: true,
	                        allowBlank: false,
	                       // selectOnFocus:true,
	                        emptyText: '[ Seleccione ]',
	                        listeners:{
	                            afterrender: function(obj,record,options){
	                               obj.getStore().load();
	                            }
	                        }
						},
						{
							xtype:'combo',
							id:show_configurar.id+'-cbo-unidad',
							fieldLabel:'Unidad',
							columnWidth:0.5,
							store: Ext.create('Ext.data.Store',{
							fields:[
	                               		{name:'und_id', type:'int'},
										{name:'und_placa' , type:'string'},      
		                    ],
	                           // autoLoad:true,
	                            proxy:{
	                                type: 'ajax',
	                                url: show_configurar.url+'scm_scm_hue_select_unidades/',
	                                reader:{
	                                    type: 'json',
	                                    rootProperty: 'data'
	                                }
	                            }
	                        }),
	                        queryMode: 'local',
	                        valueField: 'und_id',
	                        displayField: 'und_placa',
	                        listConfig:{
	                            minWidth: 200
	                        },
	                        width: 200,
	                        forceSelection: true,
	                        allowBlank: false,
	                        //selectOnFocus:true,
	                        emptyText: '[ Seleccione ]',
	                       // minChars: 2,
	                      //  typeAhead: false,
	                       // hideTrigger: true,
	                       // Sensitive: true,
	                       // queryParam: 'vp_placa',
	                        listeners:{
	                            afterrender: function(obj,record,options){
	                               obj.getStore().load({
	                            		params:{vp_und_id:0,
								        vp_prov_codigo:show_configurar.array.prov_codigo,
								        vp_placa:'%',
								        vp_tipo:0}
	                            	});
	                            },
	                            load: function(store, records, successful, eOpts){
	                            	
				                },

	                        }
						},
						{
							xtype:'combo',
							id:show_configurar.id+'-cbo-zona',
							columnWidth:0.5,
							fieldLabel:'Zona',
							store: Ext.create('Ext.data.Store',{
							fields:[
	                                {name: 'zon_id', type: 'int'},
	                                {name: 'cua_id', type: 'int'},
	                                {name: 'zon_codigo', type: 'string'},
	                                {name: 'zon_tipo', type: 'string'}  
	                            ],
	                            proxy:{
	                                type: 'ajax',
	                                url: show_configurar.url+'scm_usr_sis_zonas/',
	                                reader:{
	                                    type: 'json',
	                                    rootProperty: 'data'
	                                }
	                            }
	                        }),
	                        queryMode: 'local',
	                        valueField: 'zon_id',
	                        displayField: 'zon_codigo',
	                        listConfig:{
	                            minWidth: 200
	                        },
	                        width: 200,
	                        forceSelection: true,
	                        allowBlank: false,
	                       // selectOnFocus:true,
	                        emptyText: '[ Seleccione ]',
	                        listeners:{
	                            afterrender: function(obj,record,options){
	                            	obj.getStore().load({
	                            		params:{vp_prov:show_configurar.array.prov_codigo,vp_linea:1}
	                            	});
	                               
	                            }
	                        }
						},
						{
							xtype:'combo',
							id:show_configurar.id+'-cbo-user',
							fieldLabel:'Codigo de Acceso',
							labelWidth:105,
							columnWidth:1,
							store:Ext.create('Ext.data.Store',{
								fields:[
										{name:'id_user',type:'int'},
										{name:'usr_codigo',type:'string'},
										{name:'usr_passwd',type:'string'},
										{name:'usr_perfil',type:'string'},
										{name:'usr_estado',type:'string'},
										{name:'editable',type:'int'}
								],
								proxy:{
									type:'ajax',
									url:show_configurar.url+'scm_scm_gestion_personal_get_usuario/',
									reader:{
										type:'json',
										rootProperty:'data'
									}
								}
							}),
							queryMode:'local',
							valueField:'id_user',
							displayField:'usr_codigo',
							listConfig:{
								minWidth:350
							},
							allowBlank:false,
							emptyText:'[ Seleccione ]',
							listeners:{
								afterrender:function(obj,record,options){
									obj.getStore().load({
										params:{vp_per_id:show_configurar.array.per_id,vp_new:'0'},
									});
								},
								select:function( obj, record, eOpts ){
									var grid = Ext.getCmp(show_configurar.id+'-grid-permiso');
									grid.getStore().load({
										params:{vp_id_user:record.get('id_user'),query:''},
										callback:function(){
										}
									});
								}
							}

						},
						{
							xtype:'grid',
							columnWidth:1,
							id:show_configurar.id+'-grid-permiso',
							height:200,
							store: store,
							dockedItems: [{
					            dock: 'top',
					            xtype: 'toolbar',
					            baseCls: 'gk-toolbar',
					            items: [{
					                width: 400,
					                fieldLabel: 'Buscar',
					                labelWidth: 105,
					                xtype: 'searchfield',
					                store: store
					            }/*, '->', {
					                xtype: 'label',
					                itemId: 'status',
					                tpl: 'Matching threads: {count}',
					                style: 'margin-right:5px'
					            }*/]
					        }],
					        selModel: {
					            pruneRemoved: false
					        },
					        viewConfig: {
					            trackOver: false,
					            deferEmptyText: false,
					            emptyText: '<h1 style="margin:20px">No se encontraron resultados</h1>'
					        },
							columnLines:true,
							columns:{
								items:[
										{
											text:'Celular',
											dataIndex:'cel_numero',
											flex:1
										},
										{
											text:'Estado',
											xtype:'checkcolumn',
											dataIndex:'estado',
											flex:0.1
										}
								
								]
							}


						}
						

					
				]
			});
			Ext.create('Ext.window.Window',{
				id:show_configurar.id+'-win-configurar',
				height: 383,
				cls:'popup_show',
				width: 700,
				modal: true,
				closable: false,
				header: false,
				resizable:false,	
				layout:{
						type:'fit'
				},
				items:[
						{
							xtype:'uePanel',
							title:'Configurar Equipo',
							height:'100%',
							color:'x-color-top',
							legend:'Agregue o Edite la Configuración de su Equipo de Trabajo',
							defaults:{
								border:false
							},
							items:[panel]
						}
				],
				listeners:{
					beforerender:function(){
						//console.log(show_configurar.array);

						if (global.isEmptyJSON(show_configurar.array.editar)){
						}else{
							Ext.getCmp(show_configurar.id+'-id_turno').setValue(show_configurar.array.id_turno);
							Ext.getCmp(show_configurar.id+'-cbo-celular').setValue(show_configurar.array.cel_id);
							Ext.getCmp(show_configurar.id+'-cbo-unidad').setValue(show_configurar.array.und_id);
							Ext.getCmp(show_configurar.id+'-cbo-zona').setValue(show_configurar.array.zon_id);
							
						}
					}
				}
			}).show().center();
		},
		save:function(){
			

			var form = Ext.getCmp(show_configurar.id+'-panel-configurar').getForm();
			var per_id = show_configurar.array.per_id
			var id_turno = Ext.getCmp(show_configurar.id+'-id_turno').getValue();
			var cel_id = Ext.getCmp(show_configurar.id+'-cbo-celular').getValue();
			var und_id = Ext.getCmp(show_configurar.id+'-cbo-unidad').getValue();
			var id_zona = Ext.getCmp(show_configurar.id+'-cbo-zona').getValue();
			var user = Ext.getCmp(show_configurar.id+'-cbo-user').getValue();
			var grid = show_configurar.read_grid();
			var mask = new Ext.LoadMask(Ext.getCmp(show_configurar.id+'-win-configurar'),{
				msg:'Grabando Permisos para el Celular...'
			});

			mask.show();
			if (form.isValid()){
				Ext.Ajax.request({
					url:show_configurar.url+'scm_scm_hue_add_udp_config',
					params:{vp_per_id:per_id,vp_id_turno:id_turno,vp_cel_id:cel_id,vp_und_id:und_id,vp_id_zona:id_zona,grid:grid,user:user},
					success:function(response,options){
						var res = Ext.JSON.decode(response.responseText);
						mask.hide();
						if (parseInt(res.data[0].err_sql)== 0){
							global.Msg({
	                            msg:res.data[0].error_info,
	                            icon:1,
	                            buttons:1,
	                            fn:function(){
	                            	conf_eu.buscar_config();
	                            	Ext.getCmp(show_configurar.id+'-win-configurar').close();
	                            }
	                        });
						}else{
							global.Msg({
	                            msg:res.data[0].error_info,
	                            icon:0,
	                            buttons:1,
	                            fn:function(){
	                            }
	                        });
						}
					}
				});
			}else{
				global.Msg({
                    msg:'Debe completar los datos requeridos',
                    icon:0,
                    buttons:1,
                    fn:function(){
                    }
                });
			}

		},
		read_grid:function(){
			var grid = Ext.getCmp(show_configurar.id+'-grid-permiso');
			var store =grid.getStore();
			var modified = store.getModifiedRecords();
			var arrayData=[];
			/*if (store.getCount() > 0 ){
				for(var i =0; i < store.getCount();++i){
					var rec = store.getAt(i);
					var cel_id = rec.data.cel_id;
					var estado = rec.data.estado == true ?1:0;
					arrayData.push({cel_id:cel_id,estado:estado});
				}
			}
			return  Ext.encode(arrayData);*/
			if (!Ext.isEmpty(modified)){
				Ext.each(modified,function(rec){
					var cel_id = rec.data.cel_id;
					var estado = rec.data.estado == true ?1:0;
					arrayData.push({cel_id:cel_id,estado:estado});
				});
			}
			return  Ext.encode(arrayData);
		}
	}

	Ext.onReady(show_configurar.init, show_configurar);
</script>

