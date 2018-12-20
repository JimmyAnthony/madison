<script type="text/javascript">
	var show_celulares = {
		id:'show_celulares',
		id_menu:'<?php echo $p["id_menu"];?>',
		url: '/gestion/conf_eu/',
		vp_cel_id:0,
		array:JSON.parse( '<?php echo json_encode($p);?>' ),
		init:function(){

			var panel = Ext.create('Ext.form.Panel',{
				id:show_celulares.id+'-panel-horario',
				layout:'column',
				//bodyStyle: 'background: transparent',
				defaults:{
					margin:'5 5 5 5',
				},
				border:false,
				bbar:[
						{
							text:'',
							id:show_celulares.id+'-btn-graba-celulares',
							icon: '/images/icon/save.png',
							listeners:{
								click:function(obj){
									show_celulares.save_cel();
								}
							}
						},
						{
							text:'',
							icon: '/images/icon/get_back.png',
							listeners:{
								click:function(obj){
									Ext.getCmp(show_celulares.id+'-win-celulares').close();
								}
							}
						}
				],
				items:[
						
						{
							xtype:'textfield',
							id:show_celulares.id+'-cel-imei',
							fieldLabel:'Cel IMEI',
							maxLength:16,
							enforceMaxLength : true,
							maskRe : /[0-9]$/,
							columnWidth:0.5,
							allowBlank:false
						},
						{
							xtype:'textfield',
							id:show_celulares.id+'-cel-numero',
							fieldLabel:'Cel Numero',
							maskRe : /[0-9]$/,
							columnWidth:0.5,
							maxLength:9,
							enforceMaxLength : true,
							allowBlank:false

						},
						{
							xtype:'textfield',
							id:show_celulares.id+'-cel-rpm',
							fieldLabel:'Cel Numero RPM',
							columnWidth:0.5,
							maxLength:12,
							enforceMaxLength : true,
							allowBlank:false
						},
						{
							xtype:'combo',
							allowBlank:false,
							id:show_celulares.id+'-cel-propietario',
							fieldLabel:'Propietario',
							columnWidth:0.5,
							fieldLabel:'Tipo Propietario',
							store: Ext.create('Ext.data.Store',{
	                            fields:[
	                                {name: 'descripcion', type: 'string'},
	                                {name: 'id_elemento', type: 'int'},
	                                {name: 'des_corto', type: 'string'}
	                            ],
	                            proxy:{
	                                type: 'ajax',
	                                url: show_celulares.url + 'get_scm_tabla_detalles/',
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
	                            }
	                        }
						},
						{
							xtype:'radiogroup',
							allowBlank: false,
							columnWidth:0.2,
							allowBlank: false,
							id:show_celulares.id+'-cel-estado',
							fieldLabel:'Estado',

							//labelAlign:'top',
							columns: 2,
	                        vertical: true,
	                        items:[
	                        	{ boxLabel: 'Activo',id:show_celulares.id+'activo', name:'cel-estado', inputValue: '1'},	
	                        	{ boxLabel: 'Desactivado',id:show_celulares.id+'cese', name: 'cel-estado', inputValue: '0'}
	                        ]
						}
						
				]
			});

			Ext.create('Ext.window.Window',{
				id:show_celulares.id+'-win-celulares',
				title:'',
				cls:'popup_show',
				height: 172,
				width: 700,
				modal: true,
				closable: false,
				header: false,
				resizable:false,				
				layout:{
						type:'fit'
				},
				items:[
						//panel
						{
							xtype:'uePanel',
							title:'configuración de celulares',
							height:'100%',
							color:'x-color-top',
							legend:'Ingrese o Actualice los datos del celular',
							defaults:{
								border:false
							},
							items:[panel]
						}
				],
				listeners:{
					beforerender:function(){
						//console.log(show_celulares.array);
						if (global.isEmptyJSON(show_celulares.array.editar)){
							//console.log('varcio');
						}else{
							show_celulares.vp_cel_id = show_celulares.array.cel_id;
							Ext.getCmp(show_celulares.id+'-cel-imei').setValue(show_celulares.array.cel_imei);
							Ext.getCmp(show_celulares.id+'-cel-numero').setValue(show_celulares.array.cel_numero);
							Ext.getCmp(show_celulares.id+'-cel-rpm').setValue(show_celulares.array.cel_num_rp);
							Ext.getCmp(show_celulares.id+'-cel-propietario').setValue(show_celulares.array.tprop_id);
							Ext.getCmp(show_celulares.id+'-cel-estado').setValue({'cel-estado':show_celulares.array.cel_estado});
						}
					}
				}
			}).show().center();
		},
		save_cel:function(){
			var form = Ext.getCmp(show_celulares.id+'-panel-horario').getForm();
			var vp_cel_id = show_celulares.vp_cel_id;
			var imei = Ext.getCmp(show_celulares.id+'-cel-imei').getValue();
			var cel_numero = Ext.getCmp(show_celulares.id+'-cel-numero').getValue();
			var cel_rpm = Ext.getCmp(show_celulares.id+'-cel-rpm').getValue();
			var prop_id = Ext.getCmp(show_celulares.id+'-cel-propietario').getValue();
			var estado = Ext.getCmp(show_celulares.id+'-cel-estado').getValue()['cel-estado'];

			if (form.isValid()){
				Ext.Ajax.request({
					url:show_celulares.url+'scm_scm_hue_add_udp_celulares/',
					params:{vp_cel_id:vp_cel_id,vp_cel_imei:imei,vp_cel_numero:cel_numero,vp_cel_num_rp:cel_rpm,vp_tprop_id:prop_id,vp_cel_estado:estado},
					success:function(response,options){
						var res = Ext.JSON.decode(response.responseText);
						//console.log(res);
						if (parseInt(res.data[0].err_sql) == 0){
							global.Msg({
	                            msg:res.data[0].error_info,
	                            icon:1,
	                            buttons:1,
	                            fn:function(){
	                            	if (global.isEmptyJSON(show_celulares.array.editar)){
	                            		show_celulares.clear();	
	                            		conf_eu.buscar_celulares();
	                            		Ext.getCmp(show_celulares.id+'-win-celulares').close();
	                            	}else{
	                            		conf_eu.buscar_celulares();
	                            		Ext.getCmp(show_celulares.id+'-win-celulares').close();
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
			show_celulares.vp_cel_id = 0;
			Ext.getCmp(show_celulares.id+'-cel-imei').setValue('');
			Ext.getCmp(show_celulares.id+'-cel-numero').setValue('');
			Ext.getCmp(show_celulares.id+'-cel-rpm').setValue('');
			Ext.getCmp(show_celulares.id+'-cel-propietario').setValue('');
			Ext.getCmp(show_celulares.id+'-cel-estado').reset();
		}
	}
	Ext.onReady(show_celulares.init, show_celulares);
</script>