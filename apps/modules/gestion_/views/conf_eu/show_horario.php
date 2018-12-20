<script type="text/javascript">
	var show_horario = {
		id:'show_horario',
		id_menu:'<?php echo $p["id_menu"];?>',
		url: '/gestion/conf_eu/',
		id_turno:0,
		array:JSON.parse( '<?php echo json_encode($p);?>' ),
		init:function(){

			var panel = Ext.create('Ext.form.Panel',{
				id:show_horario.id+'-panel-horario',
				layout:'column',
				//bodyStyle: 'background: transparent',
				defaults:{
					margin:'5 5 5 5',
				},
				border:false,
				bbar:[
						{
							text:'',
							id:show_horario.id+'-btn-graba',
							icon: '/images/icon/save.png',
							listeners:{
								click:function(obj){
									show_horario.save();
								}
							}
						},
						{
							text:'',
							id:show_horario.id+'-btn-cerrar',
							icon: '/images/icon/get_back.png',
							listeners:{
								click:function(obj){
									Ext.getCmp(show_horario.id+'-win').close();
								}
							}
						}
				],
				items:[
						//id_turno     hora_ini     hora_fin     hora_ini_ref     hora_fin_ref     tur_estado
						{
							xtype:'timefield',
							allowBlank: false,
							id:show_horario.id+'-entrada',
							fieldLabel:'Hora Entrada',
							columnWidth:0.5,
							format: 'H:i',
							altFormats:'H:i',
							increment: 60,
							plugins: [new ueInputTextMask('99:99')],
						},
						{
							xtype:'timefield',
							allowBlank: false,
							id:show_horario.id+'-salida',
							fieldLabel:'Hora Salida',
							columnWidth:0.5,
							labelWidth:80,
							format: 'H:i',
							altFormats:'H:i',
							increment: 60,
							plugins: [new ueInputTextMask('99:99')],
						},
						{
							xtype:'timefield',
							allowBlank: false,
							id:show_horario.id+'-i-refri',
							fieldLabel:'Inicio Refrigerio',
							columnWidth:0.5,
							format: 'H:i',
							altFormats:'H:i',
							increment: 60,
							plugins: [new ueInputTextMask('99:99')],
						},
						{
							xtype:'timefield',
							allowBlank: false,
							id:show_horario.id+'-f-regri',
							fieldLabel:'Fin Refrigerio',
							labelWidth:80,
							//readOnly:true,
							columnWidth:0.5,
							format: 'H:i',
							altFormats:'H:i',
							increment: 60,
							plugins: [new ueInputTextMask('99:99')],
						},
						{
							xtype:'radiogroup',
							columnWidth:0.1,
							allowBlank: false,
							id:show_horario.id+'-estado',
							fieldLabel:'Estado',

							//labelAlign:'top',
							columns: 2,
	                        vertical: true,
	                        items:[
	                        	{ boxLabel: 'Activo',id:show_horario.id+'activo', name:'estado', inputValue: '1'},	
	                        	{ boxLabel: 'Desactivado',id:show_horario.id+'cese', name: 'estado', inputValue: '0'}
	                        ]
						}
				]
			});

			Ext.create('Ext.window.Window',{
				id:show_horario.id+'-win',
				title:'Horarios',
				cls:'popup_show',
				height: 173,
				width: 500,
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
							title:'Horarios',
							height:'100%',
							width:'100%',
							color:'x-color-top',
							legend:'Configure los horarios Laborales',
							defaults:{
								border:false
							},
							items:[panel]
						}
				],
				listeners:{
					beforerender:function(){
						//console.log(show_horario.array);
						if (global.isEmptyJSON(show_horario.array.editar)){
						}else{
							Ext.getCmp(show_horario.id+'-entrada').setValue(show_horario.array.hora_ini);
							Ext.getCmp(show_horario.id+'-salida').setValue(show_horario.array.hora_fin);
							Ext.getCmp(show_horario.id+'-i-refri').setValue(show_horario.array.ini_ref);
							Ext.getCmp(show_horario.id+'-f-regri').setValue(show_horario.array.fin_ref)
							Ext.getCmp(show_horario.id+'-estado').setValue({'estado':show_horario.array.estado});
							show_horario.id_turno= show_horario.array.id_turno;
						}
					}
				}
			}).show().center();
		},
		save:function(){
			var form = Ext.getCmp(show_horario.id+'-panel-horario').getForm();
			var vp_id_turno = show_horario.id_turno;
			var entrada = Ext.getCmp(show_horario.id+'-entrada').getSubmitValue();
			var salida = Ext.getCmp(show_horario.id+'-salida').getSubmitValue();
			var irefri = Ext.getCmp(show_horario.id+'-i-refri').getSubmitValue();
			var frefri = Ext.getCmp(show_horario.id+'-f-regri').getSubmitValue();
			var vp_estado =Ext.getCmp(show_horario.id+'-estado').getValue()['estado'];

			if (form.isValid()){
				Ext.Ajax.request({
					url:show_horario.url+'scm_scm_hue_add_udp_turnos_laboral/',
					params:{vp_id_turno:vp_id_turno,vp_entrada:entrada,vp_salida:salida,vp_irefri:irefri,vp_frefri:frefri,vp_estado:vp_estado},
					success:function(response,options){
						var res = Ext.JSON.decode(response.responseText);
						if (parseInt(res.data[0].err_sql)== 0){
							global.Msg({
	                            msg:res.data[0].error_info,
	                            icon:1,
	                            buttons:1,
	                            fn:function(){
	                            	Ext.getCmp(conf_eu.id+'-grid-horario').getStore().load({
	                            		params:{vp_id_turno:0}
	                            	});	
	                            	if (global.isEmptyJSON(show_horario.array.editar)){
	                            		show_horario.clear();
	                            	}else{
	                            		
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
			Ext.getCmp(show_horario.id+'-entrada').setValue('');
			Ext.getCmp(show_horario.id+'-salida').setValue('');
			Ext.getCmp(show_horario.id+'-i-refri').setValue('');
			Ext.getCmp(show_horario.id+'-f-regri').setValue('');
			Ext.getCmp(show_horario.id+'-estado').reset();
		}
	}
	Ext.onReady(show_horario.init, show_horario);
</script>