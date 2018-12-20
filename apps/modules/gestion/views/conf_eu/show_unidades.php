<script type="text/javascript">
	var show_unidades = {
		id:'show_unidades',
		id_menu:'<?php echo $p["id_menu"];?>',
		url: '/gestion/conf_eu/',
		und_id:0,
		array:JSON.parse( '<?php echo json_encode($p);?>' ),
		init:function(){

			var panel = Ext.create('Ext.form.Panel',{
				id:show_unidades.id+'-panel-unidades',
				layout:'column',
				//bodyStyle: 'background: transparent',
				defaults:{
					margin:'5 5 5 5',
				},
				border:false,
				bbar:[
						{
							text:'',
							id:show_unidades.id+'-btn-graba-unidades',
							icon: '/images/icon/save.png',
							listeners:{
								click:function(obj){
									show_unidades.save_und();
								}
							}
						},
						{
							text:'',
							icon: '/images/icon/get_back.png',
							listeners:{
								click:function(obj){
									Ext.getCmp(show_unidades.id+'-win-unidades').close();
								}
							}
						}
				],
				items:[

						{
							xtype:'textfield',
							allowBlank: false,
							id:show_unidades.id+'-und-placa',
							fieldLabel:'Placa',
							maxLength:9,
							enforceMaxLength : true,
							//labelWidth:30,
							columnWidth:0.5,
						},
						{
							xtype:'combo',
							allowBlank: false,
							id:show_unidades.id+'-und_tipo',
							fieldLabel:'Tipo de Unidad',
							columnWidth:0.5,
            				store: Ext.create('Ext.data.Store',{
	                            fields:[
	                                {name: 'descripcion', type: 'string'},
	                                {name: 'id_elemento', type: 'int'},
	                                {name: 'des_corto', type: 'string'}
	                            ],
	                            proxy:{
	                                type: 'ajax',
	                                url: show_unidades.url + 'get_scm_tabla_detalles/',
	                                reader:{
	                                    type: 'json',
	                                    root: 'data'
	                                }
	                            }
	                        }),
	                        queryMode: 'local',
	                        triggerAction: 'all',
	                        valueField: 'id_elemento',
	                        displayField: 'descripcion',
	                        listConfig:{
	                            minWidth: 200
	                        },
	                        width: 200,
	                        forceSelection: true,
	                        emptyText: '[ Seleccione ]',
	                        listeners:{
	                            afterrender: function(obj, e){
	                                obj.getStore().load({
	                                    params:{
	                                        vp_tab_id: 'TUN',
	                                        vp_shipper: 0
	                                    },
	                                    callback: function(){
	                                        //obj.setValue(0);
	                                    }
	                                });
	                            }
	                        }
						},
						{
							xtype:'combo',
							allowBlank: false,
							id:show_unidades.id+'-und-propietario',
							fieldLabel:'Tipo Propietario',
							columnWidth:0.3,
							store: Ext.create('Ext.data.Store',{
	                            fields:[
	                                {name: 'descripcion', type: 'string'},
	                                {name: 'id_elemento', type: 'int'},
	                                {name: 'des_corto', type: 'string'}
	                            ],
	                            proxy:{
	                                type: 'ajax',
	                                url: show_unidades.url + 'get_scm_tabla_detalles/',
	                                reader:{
	                                    type: 'json',
	                                    root: 'data'
	                                }
	                            }
	                        }),
	                        queryMode: 'local',
	                        triggerAction: 'all',
	                        valueField: 'id_elemento',
	                        displayField: 'descripcion',
	                        listConfig:{
	                            minWidth: 200
	                        },
	                        width: 200,
	                        forceSelection: true,
	                        emptyText: '[ Seleccione ]',
	                        listeners:{
	                            afterrender: function(obj, e){
	                                obj.getStore().load({
	                                    params:{
	                                        vp_tab_id: 'TIP',
	                                        vp_shipper: 0
	                                    },
	                                    callback: function(){
	                                        //obj.setValue(0);
	                                    }
	                                });
	                            },
	                            select:function(obj,record,e){
	                            	var id = record.get('id_elemento');
	                            	var combo = Ext.getCmp(show_unidades.id+'-und_tipo-sne');
	                            	if (id == 2){
	                            		combo.getStore().load({
	                            			params:{
		                                        vp_sne_ruc: '',
		                                        vp_sne_nombre: '',
		                                        vp_tipo:'P'
		                                    }
	                            		});
	                            		combo.setVisible(true);
	                            	}else{
	                            		combo.setVisible(false);
	                            		combo.setValue('');
	                            		combo.getStore().removeAll();
	                            	}

	                            	
	                            }
	                        }
						},
						{
							xtype:'combo',
							hidden:true,
							//allowBlank: false,
							id:show_unidades.id+'-und_tipo-sne',
							labelWidth:60,
							fieldLabel:'Proveedor',
							columnWidth:0.2,
							store: Ext.create('Ext.data.Store',{
	                            fields:[
	                                {name: 'id_sne', type: 'int'},
	                                {name: 'sne_ruc', type: 'string'},
	                                {name: 'sne_nombre', type: 'string'}
	                                            
	                            ],
	                            proxy:{
	                                type: 'ajax',
	                                url: show_unidades.url + 'scm_scm_socionegocio_ruc/',
	                                reader:{
	                                    type: 'json',
	                                    root: 'data'
	                                }
	                            }
	                        }),
	                        queryMode: 'local',
	                        triggerAction: 'all',
	                        valueField: 'id_sne',
	                        displayField: 'sne_nombre',
	                        listConfig:{
	                            minWidth: 200
	                        },
	                        width: 200,
	                        forceSelection: true,
	                        emptyText: '[ Seleccione ]',
	                        listeners:{
	                        	 afterrender: function(obj, e){
	                                //obj.
	                            },
	                        }
						},
						{
							xtype:'textfield',
							allowBlank: false,
							id:show_unidades.id+'-und-anio',
							fieldLabel:'Año',
							maskRe : /[0-9]$/,
							labelWidth:30,
							columnWidth:0.2,
							maxLength:4,
							enforceMaxLength : true,
							///plugins: [new ueInputTextMask('9999')],
						},
						{
							xtype:'textfield',
							allowBlank: false,
							id:show_unidades.id+'-und-marca',
							fieldLabel:'Marca',
							labelWidth:35,
							columnWidth:0.3,
						},
						
						{
							xtype:'textfield',
							allowBlank: false,
							id:show_unidades.id+'-und-capacidad',
							fieldLabel:'Capacidad (kg)',
							maskRe : /[0-9]$/,
							//labelWidth:85,
							columnWidth:0.3
						},
						{
							xtype:'textfield',
							allowBlank: false,
							id:show_unidades.id+'-und-descrip',
							fieldLabel:'Descripción',
							columnWidth:0.7,
						},
						/*{
							xtype:'textfield',
							allowBlank: false,
							id:show_unidades.id+'-und-km',
							fieldLabel:'Und Km',
							maskRe : /[0-9]$/,
							labelWidth:65,
							columnWidth:0.3
						},*/
						{
							xtype:'combo',
							allowBlank: false,
							id:show_unidades.id+'-und-provincia',
							fieldLabel:'Agencia de Trabajo',
							columnWidth:0.5,
							labelWidth:110,
            			    store: Ext.create('Ext.data.Store',{
                            fields:[
	                                {name: 'prov_codigo', type: 'int'},
	                                {name: 'prov_nombre', type: 'string'}
	                            ],
	                            proxy:{
	                                type: 'ajax',
	                                url: show_unidades.url + 'get_usr_sis_provinciass/',
	                                reader:{
	                                    type: 'json',
	                                    rootProperty: 'data'
	                                }
	                            }
	                        }),
	                        queryMode: 'local',
	                        valueField: 'prov_codigo',
	                        displayField: 'prov_nombre',
	                        listConfig:{
	                            minWidth: 200
	                        },
	                        width: 200,
	                        forceSelection: true,
	                       //allowBlank: false,
	                        selectOnFocus:true,
	                        emptyText: '[ Seleccione ]',
	                        listeners:{
	                            afterrender: function(obj,record,options){
	                                obj.getStore().load({
	                                    params:{vp_id_linea:0},
	                                    callback: function(){
	                                       // obj.setValue('<?php echo PROV_CODIGO;?>');  
	                                    }
	                                });
	                            }
	                        }
						},
						{
							xtype:'radiogroup',
							allowBlank: false,
							columnWidth:0.3,
							allowBlank: false,
							id:show_unidades.id+'-und-estado',
							fieldLabel:'Estado',
							labelWidth:50,
							//labelAlign:'top',
							columns: 2,
	                        vertical: true,
	                        items:[
	                        	{ boxLabel: 'Activo',id:show_unidades.id+'activo', name:'und-estado', inputValue: '1'},	
	                        	{ boxLabel: 'Desactivado',id:show_unidades.id+'cese', name: 'und-estado', inputValue: '0'}
	                        ]
						}
						
						
				]
			});

			Ext.create('Ext.window.Window',{
				id:show_unidades.id+'-win-unidades',
				title:'Unidades',
				cls:'popup_show',
				height: 205,
				width: 800,
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
							title:'configuración de Unidades',
							height:'100%',
							color:'x-color-top',
							legend:'Agregue o Edite las Unidades',
							defaults:{
								border:false
							},
							items:[panel]
						}
				],
				listeners:{
					beforerender:function(){
						//console.log(show_unidades.array);
						if (global.isEmptyJSON(show_unidades.array.editar)){

						}else{
							show_unidades.und_id = show_unidades.array.und_id;
							Ext.getCmp(show_unidades.id+'-und-provincia').setValue(show_unidades.array.prov_codigo);
							Ext.getCmp(show_unidades.id+'-und-placa').setValue(show_unidades.array.und_placa);
							Ext.getCmp(show_unidades.id+'-und_tipo').setValue(show_unidades.array.tund_id);
							Ext.getCmp(show_unidades.id+'-und-descrip').setValue(show_unidades.array.und_descri);
							Ext.getCmp(show_unidades.id+'-und-propietario').setValue(show_unidades.array.tprop_id);
							Ext.getCmp(show_unidades.id+'-und-anio').setValue(show_unidades.array.und_anio);
							Ext.getCmp(show_unidades.id+'-und-marca').setValue(show_unidades.array.und_marca);
							Ext.getCmp(show_unidades.id+'-und-capacidad').setValue(show_unidades.array.und_capacidad);
							//Ext.getCmp(show_unidades.id+'-und-km').setValue(show_unidades.array.und_kmact);
							Ext.getCmp(show_unidades.id+'-und-estado').setValue({'und-estado':show_unidades.array.und_estado});
						}

						
					}
				}
			}).show().center();
		},
		save_und:function(){
			var form = Ext.getCmp(show_unidades.id+'-panel-unidades').getForm();
			var und_id = show_unidades.und_id;
			var prov_codigo = Ext.getCmp(show_unidades.id+'-und-provincia').getValue();
			var placa = Ext.getCmp(show_unidades.id+'-und-placa').getValue();
			var und_tipo = Ext.getCmp(show_unidades.id+'-und_tipo').getValue();
			var und_descrip = Ext.getCmp(show_unidades.id+'-und-descrip').getValue();
			var tprop_id = Ext.getCmp(show_unidades.id+'-und-propietario').getValue();
			var anio = Ext.getCmp(show_unidades.id+'-und-anio').getValue();
			var marca = Ext.getCmp(show_unidades.id+'-und-marca').getValue();
			var capacidad = Ext.getCmp(show_unidades.id+'-und-capacidad').getValue();
			var und_km ='';// Ext.getCmp(show_unidades.id+'-und-km').getValue();
			var vp_estado =Ext.getCmp(show_unidades.id+'-und-estado').getValue()['und-estado'];
			var id_sne = Ext.getCmp(show_unidades.id+'-und_tipo-sne').getValue();
			
			if(form.isValid()){
				Ext.Ajax.request({
					url:show_unidades.url+'scm_scm_hue_add_udp_unidades/',
					params:{
						    vp_und_id:und_id,vp_prov_codigo:prov_codigo,vp_und_placa:placa,vp_tund_id:und_tipo,
					        vp_und_descri:und_descrip,vp_tprop_id:tprop_id,vp_und_anio:anio,vp_und_marca:marca,
					        vp_und_capacidad:capacidad,vp_und_kmact:und_km,vp_und_estado:vp_estado,vp_id_sne:id_sne
					},
					success:function(response,options){
						var res = Ext.JSON.decode(response.responseText);
						if (parseInt(res.data[0].err_sql)== 0){
							global.Msg({
	                            msg:res.data[0].error_info,
	                            icon:1,
	                            buttons:1,
	                            fn:function(){
	                            	if (global.isEmptyJSON(show_unidades.array.editar)){
	                            		show_unidades.clear();
	                            		conf_eu.busca_unidad();	
	                            	}else{
	                            		conf_eu.busca_unidad();	
	                            	}
	                            	
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
		clear:function(){
			show_unidades.und_id=0;
			Ext.getCmp(show_unidades.id+'-und-provincia').setValue('');
			Ext.getCmp(show_unidades.id+'-und-placa').setValue('');
			Ext.getCmp(show_unidades.id+'-und_tipo').setValue('');
			Ext.getCmp(show_unidades.id+'-und-descrip').setValue('');
			Ext.getCmp(show_unidades.id+'-und-propietario').setValue('');
			Ext.getCmp(show_unidades.id+'-und-anio').setValue('');
			Ext.getCmp(show_unidades.id+'-und-marca').setValue('');
			Ext.getCmp(show_unidades.id+'-und-capacidad').setValue('');
			//Ext.getCmp(show_unidades.id+'-und-km').setValue('');
			Ext.getCmp(show_unidades.id+'-und-estado').reset()
		}
	}
	Ext.onReady(show_unidades.init, show_unidades);
</script>