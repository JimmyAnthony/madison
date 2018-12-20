<script type="text/javascript">
	var new_socio = {
		id:'new_socio',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/socionegocio/',
		vp_id_sne:'<?php echo $p["vp_id_sne"];?>',
		var_tipo:'<?php echo $p["var_tipo"];?>',
		sne_ruc:'<?php echo $p["sne_ruc"];?>',
		sne_nombre:'<?php echo $p["sne_nombre"];?>',
		tsec_id:'<?php echo $p["tsec_id"];?>',
		sne_abc:'<?php echo $p["sne_abc"];?>',
		sne_tipo:'<?php echo $p["sne_tipo"];?>',
		dir_factura:'<?php echo $p["dir_factura"];?>',
		ciu_id:'<?php echo $p["ciu_id"];?>',
		init:function(){
			var panel = Ext.create('Ext.form.Panel',{
				layout:'column',
				id:new_socio.id+'-form',
				border:false,
				defaults:{
					padding:'5 5 5 5'
				},
				items:[
						{
							xtype:'radiogroup',
							id:new_socio.id+'-rbtn-group-tipo',
							columnWidth:0.6,
							fieldLabel:'Tipo de Sector',
							columns:2,
							labelWidth:85,
							items:[
								{boxLabel:'Cliente',name:new_socio.id+'-rbtn-tipo',inputValue:'C',width:70,checked:true},
								{boxLabel:'Proveedor',name:new_socio.id+'-rbtn-tipo',inputValue:'P',width:80},
							],
						},
						/*{
							xtype:'tbspacer',
							width:50,
						},*/
						{
							xtype:'textfield',
							fieldLabel:'RUC:',
							id:new_socio.id+'new-ruc',
							plugins: [new ueInputTextMask('A9999999999')],
							allowBlank:false,
							columnWidth:0.4,
							labelWidth:30,
							//enableKeyEvents:true,
							listeners:{
								blur:function( obj, event, eOpts){
									if (obj.value !=''){
										if (!new_socio.valruc(obj.value)){
											global.Msg({
												msg:'Debe Ingresar un RUC Valido',
												icon:0,
												buttosn:1,
												fn:function(btn){
													obj.setValue('');
												}
											});
										}
									}
									
								}
							}
						},
						{
							xtype:'textfield',
							fieldLabel:'Nombre/Razón Social',
							id:new_socio.id+'-new-nombre',
							allowBlank:false,
							columnWidth:1,
							labelWidth:125,
						},
						{
							xtype:'textfield',
							fieldLabel:'Dirección Facturación',
							id:new_socio.id+'-new-direccion',
							allowBlank:false,
							columnWidth:1,
							labelWidth:125,
						},
						{
	                        xtype: 'combo',
	                        columnWidth:1,
	                        labelWidth:125,
	                        id: new_socio.id + '-completo',
	                        fieldLabel:'Dis. Prov. Dep.',
	                        store: Ext.create('Ext.data.Store',{
	                            fields:[
	                                {name: 'ciu_id', type: 'int'},
	                                {name: 'completo', type: 'string'}
	                            ],
	                            proxy:{
	                                type: 'ajax',
	                                url: new_socio.url + 'getDisProvDep/',
	                                reader:{
	                                    type: 'json',
	                                    rootProperty: 'data'
	                                }
	                            }
	                        }),
	                        queryMode: 'local',
	                        valueField: 'ciu_id',
	                        displayField: 'completo',
	                        listConfig:{
	                            minWidth: 200
	                        },
	                        width: 120,
	                        forceSelection: true,
	                        allowBlank: false,
	                        emptyText: '[ Seleccione ]',
	                        listeners:{
	                            afterrender: function(obj){
	                                obj.getStore().load({
	                                    params:{va_departamento:'4'},
	                                });
	                            },
	                            'select':function(obj, records, eOpts){
	                            }
	                        }
	                    },
						{
							xtype:'combo',
							fieldLabel:'Sector de Negocio',
							id:new_socio.id+'-new-sector',
							columnWidth:1,
							labelWidth:125,
							store:Ext.create('Ext.data.Store',{
								fields:[
									{name:'descripcion',type:'string'},
									{name:'id_elemento',type:'int'},
								],
								proxy:{
									type:'ajax',
									url:new_socio.url+'scm_scm_tabla_detalle/',
									reader:{
										type:'json',
										rootProperty:'data'
									}
								}
							}),
							queryMode:'local',
							valueField:'id_elemento',
							displayField:'descripcion',
							listConfig:{
								minWidth:140
							},
							width:140,
							forceSelection:true,
							allowBlank:false,
							empyText:'[ Seleccione]',
							listeners:{
								afterrender:function(obj){
									obj.getStore().load({
										params:{vp_tab_id:'SEC',vp_shi_codigo:0},
										callback:function(){
											//obj.setValue(99);
										}
									});
								}
							}
						},
						/*{
							xtype:'combo',
							fieldLabel:'Sector Tipo',
							id:new_socio.id+'serctor-tipo',
							columnWidth:0.5,
							labelWidth:80,
							store:Ext.create('Ext.data.Store',{
								fields:[
									{name:'tipo', type:'string'},
									{name:'Descri', type:'string'},
								],
								proxy:{
									type:'ajax',
									url:socionegocio.url+'scm_get_tipo/',
									reader:{
										type:'json',
										root:'data'
									}
								}
							}),
							queryMode: 'local',
	                        valueField: 'tipo',
	                        displayField: 'Descri',
	                        listConfig:{
	                            minWidth: 100
	                        },
	                        width: 100,
	                        forceSelection: true,
	                        allowBlank: false,
	                        selectOnFocus:true,
	                        listeners:{
	                        	afterrender:function(obj){
	                        		obj.getStore().load({
	                        			callback:function(){
	                        				//obj.setValue('C');
	                        			}
	                        		});
	                        	}
	                        }
						},*/
						{
							xtype:'combo',
							fieldLabel:'Sec. ABC',
							id:new_socio.id+'-new-abc',
							columnWidth:1,
							labelWidth:125,
							store:Ext.create('Ext.data.Store',{
								fields:[
									{name:'descripcion',type:'string'},
									{name:'id_elemento',type:'int'},
								],
								proxy:{
									type:'ajax',
									url:new_socio.url+'scm_scm_tabla_detalle/',
									reader:{
										type:'json',
										rootProperty:'data'
									}
								}
							}),
							queryMode:'local',
							valueField:'id_elemento',
							displayField:'descripcion',
							listConfig:{
								minWidth:140
							},
							width:140,
							forceSelection:true,
							allowBlank:false,
							empyText:'[ Seleccione]',
							listeners:{
	                        	afterrender:function(obj){
	                        		obj.getStore().load({
	                        			params:{vp_tab_id:'ABC',vp_shi_codigo:0},
	                        			callback:function(){
	                        				//obj.setValue(1);
	                        			}
	                        		});
	                        	}
	                        }
						}


				]
			});
			var panelX = Ext.create('Ext.form.Panel',{
				layout:'fit',
				border:false,
				bodyStyle: 'background: transparent',
				items:[
						{
							xtype:'uePanel',
							title:'Registro del Cliente / Proveedor',
							logo:'signup',
							layout:'fit',
							legend:'Ingresa los datos del Cliente / Proveedor',
							bg: '#991919',
							items:[
								panel
							]
						}
				]

			});

			var win = Ext.create('Ext.window.Window',{
				id:new_socio.id+'-win',
				//title:'Nevo Socio',
				header:false,
				bodyStyle: 'background: transparent',
				height:280,
				width:500,
				border:false,
				resizable:false,
				layout:{
					type:'fit'
				},
				modal:true,
				items:[panelX],
				dockedItems:[
								{
									xtype:'toolbar',
									dock:'bottom',
									ui:'footer',
									alignTarget:'center',
									layout:{
										pack:'center'
									},
									//baseCls:'gk-toolbar',
									items:[
											{
												text:'',
												icon:'/images/icon/new_file.ico',
												id:new_socio.id+'-limpiar',
												listeners:{
													click:function(obj,e){
														new_socio.limpiar();
													}
												}
											},
											{
												text:'',
												icon:'/images/icon/save.png',
												listeners:{
													beforerender:function(obj, opts){
														global.permisos({
						                                    id_serv: 72, 
						                                    id_btn: obj.getId(), 
						                                    id_menu: new_socio.id_menu,
						                                    fn: ['new_socio.add_socio']
						                                });

													},
													click:function(obj, e){
														new_socio.add_socio();
													}
												}
											},
											{
												text:'',
												icon:'/images/icon/get_back.png',
												listeners:{
													click:function(obj,e){
														Ext.getCmp(new_socio.id+'-win').close();
													}
												}
											}
									]
								}
				],
				listeners:{
					afterrender:function(obj){
						if (new_socio.var_tipo== 'E'){
							Ext.getCmp(new_socio.id+'new-ruc').setValue(new_socio.sne_ruc);
							Ext.getCmp(new_socio.id+'-new-nombre').setValue(new_socio.sne_nombre);
							Ext.getCmp(new_socio.id+'-new-sector').setValue(new_socio.tsec_id);
							Ext.getCmp(new_socio.id+'-new-abc').setValue(new_socio.sne_abc);
							Ext.getCmp(new_socio.id+'-new-direccion').setValue(new_socio.dir_factura);
							Ext.getCmp(new_socio.id+'-rbtn-group-tipo').setValue({'new_socio-rbtn-tipo':new_socio.sne_tipo});
							Ext.getCmp(new_socio.id + '-completo').setValue(new_socio.ciu_id);
							Ext.getCmp(new_socio.id+'-limpiar').disable();
						}
					}
				}
			}).show();
		},
		add_socio:function(){
			var form = Ext.getCmp(new_socio.id+'-form').getForm();
			//var vl_sne_tipo = Ext.getCmp(new_socio.id+'serctor-tipo').getValue();
			var radio = Ext.getCmp(new_socio.id+'-rbtn-group-tipo').getValue();
			var vl_sne_tipo = radio['new_socio-rbtn-tipo'];
			var vl_sne_ruc = Ext.getCmp(new_socio.id+'new-ruc').getValue();
			var vl_sne_nombre = Ext.getCmp(new_socio.id+'-new-nombre').getValue();
			var vl_tsec_id = Ext.getCmp(new_socio.id+'-new-sector').getValue();
			var vl_sne_abc = Ext.getCmp(new_socio.id+'-new-abc').getValue();
			var vl_dir_factura = Ext.getCmp(new_socio.id+'-new-direccion').getValue();
			var vl_ciu_id = Ext.getCmp(new_socio.id + '-completo').getValue();

			var mask = new Ext.LoadMask(Ext.getCmp(new_socio.id+'-win'),{
					msg:'Actualizando Datos....'
				});
			if (form.isValid()){
				mask.show();
				Ext.Ajax.request({
					url:new_socio.url+'scm_scm_socionegocio_add_ruc/',
					params:{
						vp_tip_insert:new_socio.var_tipo,// E edita update I inserta
						vp_id_sne:new_socio.vp_id_sne,//id del socionegocio
						vp_sne_tipo:vl_sne_tipo,
						vp_sne_ruc:vl_sne_ruc,
						vp_sne_nombre:vl_sne_nombre,
						vp_tsec_id:vl_tsec_id,
						vp_sne_abc:vl_sne_abc,
						vp_ciu_id:vl_ciu_id,
						vp_dir_factura:vl_dir_factura
					},
					success:function(response,options){
						mask.hide();
						var res = Ext.decode(response.responseText);
						if (parseInt(res.data[0].error_sql)==0){
							global.Msg({
								msg:res.data[0].error_info,
								icon:1,
								buttosn:1,
								fn:function(btn){
									Ext.getCmp(new_socio.id+'-win').close();
									socionegocio.grid_socio();
								}
							});
						}else{
							global.Msg({
								msg:res.data[0].error_info,
								icon:0,
								buttosn:1,
								fn:function(btn){
								}
							});
						}
					}
				
				});
			}else{
				global.Msg({
					msg:'Debe Completar los datos',
					icon:0,
					buttosn:1,
					fn:function(btn){
					}
				});
			}		
		},
		limpiar:function(){
			Ext.getCmp(new_socio.id+'new-ruc').setValue('');
			Ext.getCmp(new_socio.id+'-new-nombre').setValue('');
			Ext.getCmp(new_socio.id+'-new-sector').setValue('');
			Ext.getCmp(new_socio.id+'-new-abc').setValue('');
			Ext.getCmp(new_socio.id+'-new-direccion').setValue('');
			Ext.getCmp(new_socio.id+'-rbtn-group-tipo').reset();
		},
		valruc:function(valor){
		 	valor = valor.toString();
		 	console.log(valor);
		    if ( valor.length == 8 ){
		     /* suma = 0
		      for (i=0; i<valor.length-1;i++){
		        digito = valor.charAt(i) - '0';
		        if ( i==0 ) suma += (digito*2)
		        else suma += (digito*(valor.length-i))
		      }
		      resto = suma % 11;
		      if ( resto == 1) resto = 11;
		      if ( resto + ( valor.charAt( valor.length-1 ) - '0' ) == 11 ){
		        return true
		      }*/
		    }else if( valor.length == 11 ){
		      suma = 0;
		      x = 6;
		      for (i=0; i<valor.length-1;i++){
		        if ( i == 4 ) x = 8;
		        digito = valor.charAt(i) - '0';
		       // x--
		        if ( i==0 ) suma += (digito*x)
		        else suma += (digito*x);
		      }
		      resto = suma % 11;
		      resto = 11 - resto;
		      
		      if ( resto >= 10) resto = resto - 10;
		      if ( resto == valor.charAt( valor.length-1 ) - '0' ){
		        return true;
		      }      
		    }
		  //return false
		 return true;

		}

	}
	Ext.onReady(new_socio.init,new_socio);
</script>