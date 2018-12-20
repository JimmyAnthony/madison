<script type="text/javascript">
	var new_horario = {
		id:'new_horario',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/red_despacho/',
		vl_cbe_id:null,
		init:function(){
			var panel = Ext.create('Ext.form.Panel',{
				layout:{
					type:'form',
					alingh:'stretch'
				},
				items:[
						{
							xtype:'fieldset',
							title:'Envio Terrestre',
							id:new_horario.id+'-fiel-terrestre',
							items:[
									{
										xtype:'panel',
										layout:'hbox',
										border:false,
										items:[
												{
													xtype:'timefield',
													margin:'5 5 5 0',
													id:new_horario.id+'-th_salida',
													fieldLabel:'Hora Salida',
													labelAlign: 'top',
													flex:1,
													format:'H:i'
												},
												/*{
													xtype:'numberfield',
													margin:'5 5 5 5',
													id:new_horario.id+'-tdias_transcurridos',
													fieldLabel:'Dias',
													labelAlign: 'top',
													minValue:0,
													width:50
												},*/
												{
													xtype:'textfield',
													margin:'5 5 5 5',
													id:new_horario.id+'th_llegada',
													fieldLabel:'Dia Hora : Minutos',
													labelAlign:'top',
													flex:1,
													plugins: [new ueInputTextMask('99 99:99')],
												},
												{
													xtype:'checkboxfield',
													margin:'5 5 5 5',
													id:new_horario.id+'t_estado',
													fieldLabel:'Estado',
													labelAlign:'top',
													inputValue:1,
													listeners:{
														change:function( obj, newValue, oldValue, eOpts ){
															if (newValue){
																Ext.getCmp(new_horario.id+'-th_salida').enable();
																Ext.getCmp(new_horario.id+'th_llegada').enable();
															}else{
																Ext.getCmp(new_horario.id+'-th_salida').disable();
																Ext.getCmp(new_horario.id+'th_llegada').disable();
																Ext.getCmp(new_horario.id+'-th_salida').setValue('');
																Ext.getCmp(new_horario.id+'th_llegada').setValue('');
															}
														},
														afterrender:function( obj, eOpts ){
															if (obj.getValue()){
																Ext.getCmp(new_horario.id+'-th_salida').enable();
																//Ext.getCmp(new_horario.id+'-tdias_transcurridos').enable();
																Ext.getCmp(new_horario.id+'th_llegada').enable();
															}else{
																Ext.getCmp(new_horario.id+'-th_salida').disable();
																//Ext.getCmp(new_horario.id+'-tdias_transcurridos').disable();
																Ext.getCmp(new_horario.id+'th_llegada').disable();	
															}
															
														}   
													}
												}
										]
									}
							]
						},
						{
							xtype:'fieldset',
							title:'Envio Aereo',
							id:new_horario.id+'-fiel-aereo',
							items:[
									{
										xtype:'panel',
										layout:'hbox',
										border:false,
										items:[
												{
													xtype:'timefield',
													margin:'5 5 5 0',
													id:new_horario.id+'-ah_salida',
													fieldLabel:'Hora Salida',
													labelAlign:'top',
													flex:1,
													format:'H:i'

												},
												/*{
													xtype:'numberfield',
													margin:'5 5 5 5',
													id:new_horario.id+'-adias_transcurridos',
													fieldLabel:'Dias',
													labelAlign:'top',
													minValue:0,
													width:50
												},*/
												{
													xtype:'textfield',
													margin:'5 5 5 5',
													id:new_horario.id+'ah_llegada',
													fieldLabel:'Dia Hora : Minutos',
													labelAlign:'top',
													flex:1,
													plugins:  [new ueInputTextMask('99 99:99')],
													enableKeyEvents: true,

													
												},
												{
													xtype:'checkboxfield',
													margin:'5 5 5 5',
													id:new_horario.id+'a_estado',
													fieldLabel:'Estado',
													labelAlign:'top',
													inputValue:1,
													listeners:{
														change:function(obj, newValue, oldValue, eOpts ){
															if (newValue){
																Ext.getCmp(new_horario.id+'-ah_salida').enable();
																Ext.getCmp(new_horario.id+'ah_llegada').enable();
															}else{
																Ext.getCmp(new_horario.id+'-ah_salida').disable();
																Ext.getCmp(new_horario.id+'ah_llegada').disable();
																Ext.getCmp(new_horario.id+'-ah_salida').setValue();
																Ext.getCmp(new_horario.id+'ah_llegada').setValue();	
															}

														},
														afterrender:function( obj, eOpts ){
															if (obj.getValue()){
																Ext.getCmp(new_horario.id+'-ah_salida').enable();
																//Ext.getCmp(new_horario.id+'-adias_transcurridos').enable();
																Ext.getCmp(new_horario.id+'ah_llegada').enable();
															}else{
																Ext.getCmp(new_horario.id+'-ah_salida').disable();
																//Ext.getCmp(new_horario.id+'-adias_transcurridos').disable();
																Ext.getCmp(new_horario.id+'ah_llegada').disable();	
															}
															
														}
													}
												}
										]
									}
							]
						},
						{
							xtype:'fieldset',
							title:'Otros Envios',
							id:new_horario.id+'-fiel-otros',
							items:[
									{
										xtype:'panel',
										layout:'hbox',
										border:false,
										items:[
												{
													xtype:'timefield',
													margin:'5 5 5 0',
													id:new_horario.id+'-oh_salida',
													fieldLabel:'Hora Salida',
													labelAlign:'top',
													flex:1,
													format:'H:i'
												},
												/*{
													xtype:'numberfield',
													margin:'5 5 5 5',
													id:new_horario.id+'-odias_transcurridos',
													fieldLabel:'Dias',
													labelAlign:'top',
													minValue:0,
													width:50
												},*/
												{
													xtype:'textfield',
													margin:'5 5 5 5',
													id:new_horario.id+'-oh_llegada',
													fieldLabel:'Dia Hora : Minutos',
													labelAlign:'top',
													flex:1,
													plugins:  [new ueInputTextMask('99 99:99')],
												},
												{
													xtype:'checkboxfield',
													margin:'5 5 5 5',
													id:new_horario.id+'-o_estado',
													fieldLabel:'Estado',
													labelAlign:'top',
													inputValue:1,
													listeners:{
														change:function( obj, newValue, oldValue, eOpts ){
															if (newValue){
																Ext.getCmp(new_horario.id+'-oh_salida').enable();
																Ext.getCmp(new_horario.id+'-oh_llegada').enable();
															}else{
																Ext.getCmp(new_horario.id+'-oh_salida').disable();
																Ext.getCmp(new_horario.id+'-oh_llegada').disable();
																Ext.getCmp(new_horario.id+'-oh_salida').setValue();
																Ext.getCmp(new_horario.id+'-oh_llegada').setValue();
															}
														},
														afterrender:function( obj, eOpts ){
															if (obj.getValue()){
																Ext.getCmp(new_horario.id+'-oh_salida').enable();
																//Ext.getCmp(new_horario.id+'-odias_transcurridos').enable();
																Ext.getCmp(new_horario.id+'oh_llegada').enable();
															}else{
																Ext.getCmp(new_horario.id+'-oh_salida').disable();
																//Ext.getCmp(new_horario.id+'-odias_transcurridos').disable();
																Ext.getCmp(new_horario.id+'-oh_llegada').disable();	
															}
															
														}   
													}
												}
										]
									}
							]
						}
				]
			});

			Ext.create('Ext.window.Window',{
				id:new_horario.id+'-win',
				title:'Horarios',
				//height:330,
				width:450,
				resizable:false,
				closable:false,
				plain:false,
				minimizable:false,
				constrain:true,
				constrainHeader:true,
				renderTo:red_despacho.contenedor,
				header:false,
				border:false,
				frame: false,
				layout:{
					type:'fit'
				},
				modal:true,
				items:[
						panel
				],
				dockedItems:[
								{
									xtype:'toolbar',
									dock:'bottom',
									ui:'footer',
									alingnTarget:'center',
									layout:{
										pack:'center'
									},
									baseCls:'gk-toolbar',
									items:[
											{
												text:'Modificar',
												id:new_horario.id+'-modificar',
												icon: '/images/icon/edit.png',
												listeners:{
													click:function(obj,e){
														new_horario.controles(false);
														new_horario.botones(false);
													}
												}
											},
											{
												text:'Grabar',
												id:new_horario.id+'grabar',
												icon: '/images/icon/save.png',	
												listeners:{
													click:function(obj,e){
														new_horario.save_horarios();
													}
												}
											},
											{
												text:'Cancelar',
												id:new_horario.id+'-cancelar',
												icon:'/images/icon/cancel.png',
												listeners:{
													click:function(obj,e){
														new_horario.controles(true);
														new_horario.botones(true);
													}
												}
											},
											{
												text:'Regresar',
												id:new_horario.id+'-regresar',
												icon:'/images/icon/get_back.png',
												listeners:{
													click:function(obj,e){
														Ext.getCmp(new_horario.id+'-win').close();
													}
												}
											}
									]
								}
				],
				listeners:{
					afterrender:function(obj, e){

						var  rec = Ext.getCmp(red_cobertura.id+'-cobertura').getSelectionModel().getSelection();
					 	new_horario.vl_cbe_id = rec[0].data.cbe_id;
						//console.log(new_horario.vl_cbe_id);

						Ext.Ajax.request({
							url:new_horario.url+'red_despacho_get_horarios/',
							params:{vl_cbe_id:new_horario.vl_cbe_id},
							success:function(response,options){
								var res = Ext.decode(response.responseText);
								///*****Terrestre****//
								Ext.getCmp(new_horario.id+'-th_salida').setValue(res.data[0].haul_salida1);
							//	Ext.getCmp(new_horario.id+'-tdias_transcurridos').setValue(res.data[0].haul_time1);
								Ext.getCmp(new_horario.id+'th_llegada').setValue(res.data[0].haul_arribo1);
								if (res.data[0].haul_estado1 == '1' ){
									Ext.getCmp(new_horario.id+'t_estado').setValue(true);
								}else{
									Ext.getCmp(new_horario.id+'t_estado').setValue(false);
								}
								///********Aereo********///
								Ext.getCmp(new_horario.id+'-ah_salida').setValue(res.data[0].haul_salida2);
								//Ext.getCmp(new_horario.id+'-adias_transcurridos').setValue(res.data[0].haul_time2);
								Ext.getCmp(new_horario.id+'ah_llegada').setValue(res.data[0].haul_arribo2);
								if (res.data[0].haul_estado2 == '1' ){
									Ext.getCmp(new_horario.id+'a_estado').setValue(true);
								}else{
									Ext.getCmp(new_horario.id+'a_estado').setValue(false);
								}

								///**********otros***********///
								Ext.getCmp(new_horario.id+'-oh_salida').setValue(res.data[0].haul_salida3);
								//Ext.getCmp(new_horario.id+'-odias_transcurridos').setValue(res.data[0].haul_time3);
								Ext.getCmp(new_horario.id+'-oh_llegada').setValue(res.data[0].haul_arribo3);

								if (res.data[0].haul_estado3 == '1' ){
									Ext.getCmp(new_horario.id+'-o_estado').setValue(true);
								}else{
									Ext.getCmp(new_horario.id+'-o_estado').setValue(false);
								}
								new_horario.controles(true);
								new_horario.botones(true);
								
							}
						});

					},
					show:function(window,eOpts){
						window.alignTo(Ext.get(red_despacho.id+'Mapsa'), 'bl-bl');
					},
					/*minimize:function(window,opts){
						window.collapse();
						window.setWidth(100);
						window.alignTo(Ext.getCmp(red_despacho.id+'Mapsa'), 'bl-bl');
					}*/
				},
				/*tools:[{
					type:'restore',
					handler:function(evt, toolEl, owner, tool){
						var window = owner.up('window');
						window.setWidth(400);
						window.expand('',false);
						window.alignTo(Ext.getCmp(red_despacho.id+'Mapsa'), 'bl-bl');
					}
				}]*/
			}).show().center();
		},
		save_horarios:function(){
			var th_salida = Ext.getCmp(new_horario.id+'-th_salida').getRawValue();
			//var tdias_transcurridos = Ext.getCmp(new_horario.id+'-tdias_transcurridos').getValue();
			var th_llegada = Ext.getCmp(new_horario.id+'th_llegada').getRawValue();
			var testado = Ext.getCmp(new_horario.id+'t_estado').getValue() == true ? '1':'0';
			
			var ah_salida = Ext.getCmp(new_horario.id+'-ah_salida').getRawValue();
			//var adias_transcurridos = Ext.getCmp(new_horario.id+'-adias_transcurridos').getValue();
			var ah_llegada = Ext.getCmp(new_horario.id+'ah_llegada').getRawValue();
			var aestado = Ext.getCmp(new_horario.id+'a_estado').getValue() == true ? '1':'0';

			var oh_salida = Ext.getCmp(new_horario.id+'-oh_salida').getRawValue();
			//var odias_transcurridos = Ext.getCmp(new_horario.id+'-odias_transcurridos').getValue();
			var oh_llegada = Ext.getCmp(new_horario.id+'-oh_llegada').getRawValue();
			var oestado = Ext.getCmp(new_horario.id+'-o_estado').getValue() == true ? '1':'0';

			//console.log(new_horario.vl_cbe_id);
			if (testado == '1'){
				if (th_salida=='' || th_llegada == ''){
					global.Msg({
						msg:'Debe completar los campos requeridos',
						icon:0,
						fn:function(btn){
						}
					});
					return;
				}
			}

			if (aestado == '1'){
				if (ah_salida=='' || ah_llegada == ''){
					global.Msg({
						msg:'Debe completar los campos requeridos',
						icon:0,
						fn:function(btn){
						}
					});
					return;
				}
			}

			if (oestado == '1'){
				if (oh_salida=='' || oh_llegada == ''){
					global.Msg({
						msg:'Debe completar los campos requeridos',
						icon:0,
						fn:function(btn){
						}
					});
					return;
				}
			}

			Ext.Ajax.request({
				url:new_horario.url+'red_despacho_graba_horarios/',
				params:{vl_cbe_id:new_horario.vl_cbe_id,th_salida:th_salida,th_llegada:th_llegada,ah_salida:ah_salida,
					ah_llegada:ah_llegada,oh_salida:oh_salida,oh_llegada:oh_llegada,testado:testado,aestado:aestado,oestado:oestado
				},
				success:function(response,options){
					var res = Ext.decode(response.responseText);
					if(parseInt(res.data[0].error_sql) < 0){
						global.Msg({
							msg:res.data[0].error_info,
							icon:0,
							fn:function(btn){
								/**/
								
							}
						});
					}else{
						global.Msg({
							msg:res.data[0].error_info,
							icon:1,
							fn:function(btn){
								Ext.getCmp(red_cobertura.id+'-cobertura').store.load({
									params:{vl_prov_origen:Ext.getCmp(red_cobertura.id+'-origen').getValue()},
									callback:function(){
									}
								});
								Ext.getCmp(new_horario.id+'-win').close()
							}
						});
					}
				}

			});

		},
		controles:function(control){
				Ext.getCmp(new_horario.id+'-th_salida').setReadOnly(control);
				//Ext.getCmp(new_horario.id+'-tdias_transcurridos').setReadOnly(control);
				Ext.getCmp(new_horario.id+'th_llegada').setReadOnly(control);
				Ext.getCmp(new_horario.id+'t_estado').setReadOnly(control);

				Ext.getCmp(new_horario.id+'-ah_salida').setReadOnly(control);
				//Ext.getCmp(new_horario.id+'-adias_transcurridos').setReadOnly(control);
				Ext.getCmp(new_horario.id+'ah_llegada').setReadOnly(control);
				Ext.getCmp(new_horario.id+'a_estado').setReadOnly(control);

				Ext.getCmp(new_horario.id+'-oh_salida').setReadOnly(control);
				//Ext.getCmp(new_horario.id+'-odias_transcurridos').setReadOnly(control);
				Ext.getCmp(new_horario.id+'-oh_llegada').setReadOnly(control);
				Ext.getCmp(new_horario.id+'-o_estado').setReadOnly(control);
		},
		botones:function(control){
			if (control){
				Ext.getCmp(new_horario.id+'-modificar').enable();
				Ext.getCmp(new_horario.id+'grabar').disable();
				Ext.getCmp(new_horario.id+'-cancelar').disable();	
				Ext.getCmp(new_horario.id+'-regresar').enable();
			}else{
				Ext.getCmp(new_horario.id+'grabar').enable();
				Ext.getCmp(new_horario.id+'-cancelar').enable();
				Ext.getCmp(new_horario.id+'-modificar').disable();
				Ext.getCmp(new_horario.id+'-regresar').disable();

			}
		}
	}
	Ext.onReady(new_horario.init,new_horario);
	
	
</script>