
<script type="text/javascript">
	var transDeposito = {
		id:'transDeposito',
		id_menu:'<?php echo $p["id_menu"];?>',
		url:'/gestion/trans_cod/',
		get:[],
		array:JSON.parse( '<?php echo json_encode($p);?>' ),
		init:function(){
			var panel2 = Ext.create('Ext.form.Panel',{
				id:transDeposito.id+'-form2',
				layout:'column',
				border:false,
				margin:'5 10 5 10',
				defaults:{
					padding:'0 15 10 0'
				},
				items:[
						{
							xtype:'combo',
							allowBlank:false,
							id:transDeposito.id+'-banco',
							store: Ext.create('Ext.data.Store',{
								//autoLoad:true,
								fields:[
										{name:'bco_id', type:'int'},
										{name:'bco_nombre', type:'string'}
								],
								proxy:{
									type:'ajax',
									url:transDeposito.url+'scm_usr_sis_bancos/',
									reader:{
										type:'json',
										rootProperty:'data'
									}
								}
							}),
							queryMode:'local',
							valueField:'bco_id',
							displayField:'bco_nombre',
							fieldLabel:'Banco',
							emptyText: '[ Seleccione ]',
							selectOnFocus:true,
							forceSelection: true,
							labelWidth:55,
							columnWidth:0.5,
							listeners:{
								afterrender:function(obj){
									obj.getStore().load({
										params:{vp_shi_codigo:0}
									});
								},
								select:function(obj,record,options){
									Ext.getCmp(transDeposito.id+'-cuenta').getStore().load({
										params:{vp_cta:record.get('bco_id'),vp_shi_codigo:0,vp_tmnd_id:transDeposito.get.tip_moneda},
										callback:function(){
											Ext.getCmp(transDeposito.id+'-cuenta').setValue();
										}
									});
								}
							}
						},
						{
							xtype:'combo',
							allowBlank:false,
							fieldLabel:'N° Cuenta',
							labelWidth:60,
							columnWidth:0.5,
							id: transDeposito.id+'-cuenta',
							store: Ext.create('Ext.data.Store',{
								fields:[
										{name: 'cta_id', type: 'int'},
										{name: 'cta_numero', type: 'string'},
										{name: 'tmnd_id', type: 'int'}
								],
								proxy:{
									type:'ajax',
									url:transDeposito.url+'scm_usr_sis_cta/',
									reader:{
										type:'json',
										rootProperty:'data'
									}
								}
							}),
							queryMode:'local',
							valueField:'cta_id',
							displayField:'cta_numero',
							forceSelection: true,
							selectOnFocus:true,
							emptyText: '[ Seleccione ]',
							listeners:{
								select:function(obj,record,options){
								}
							}
						},
						{
							xtype:'datefield',
							labelWidth:55,
							fieldLabel:'Fecha',
							columnWidth:0.5,
							id:transDeposito.id+'-fecha',
							value:new Date(),
							//minValue:transDeposito.get.fecha_dep,
							maxValue:new Date(),
							width:90,
							listeners:{
								beforerender:function(obj){
									//console.log(transDeposito.get.fecha_dep.toString());
									obj.setMinValue(transDeposito.get.fecha_dep.toString())

								}
							}
						},
						{
							xtype:'textfield',
							labelWidth:125,
							columnWidth:0.5,
							readOnly:true,
							id:transDeposito.id+'-importe',
							fieldLabel:'Importe (En Custodia)',
						},
						{
							xtype:'numberfield',
							labelWidth:98,
							allowBlank:false,
							id:transDeposito.id+'-retenido',
							columnWidth:0.5,
							allowDecimals:true,
							decimalPrecision:4,
							decimalSeparator:'.',
							fieldLabel:'Importe Retenido',
							listeners:{
								blur:function(obj){
									//console.log(obj.getValue());
									//console.log(transDeposito.get.monto);
									if (parseInt(obj.getValue()) > parseInt(transDeposito.get.importe)){
										global.Msg({
											msg:'El monto retenido no puede ser </br>mayor a lo depositado.',
											icon:0,
											fn:function(){
												obj.setValue('');
											}
										});
										
									}
								}
							}
						},
						{
							xtype:'textfield',
							labelWidth:80,
							allowBlank:false,
							id:transDeposito.id+'-n-operacion',
							columnWidth:0.5,
							//labelWidth:80,
							//maskRe : /[0-9]$/,
							fieldLabel:'N° Operación'
						},
						{
							xtype:'textarea',
							id:transDeposito.id+'-obs',
							labelWidth:55,
							fieldLabel:'Obs:',
							columnWidth:0.975,
							margin:2,
							maskRe : /[a-z ñÑA-Z0-9]$/,
							emptyText: 'Escribe un observación...',
							maxLength:100,
							maxLengthText:'El maximo de caracteres permitidos para este campo es {0}',
							enforceMaxLength:true,
							//grow: true,
						}
				]
			});

			var panel = Ext.create('Ext.form.Panel',{
				id:transDeposito.id+'-form',
				layout:'fit',
				border:false,
				items:[
						{
							xtype:'uePanel',
							title:'Depósito en Custodia',
							logo: 'signup',
							color:'x-color-top',
							legend: 'Confirma el Depósito en Custodia',
							defaults:{
								border:false
							},
							items:[panel2]
						}
				]
			});

			Ext.create('Ext.window.Window',{
				id:transDeposito.id+'-win',
				height: 255,
				width: 585,
				modal: true,
				closable: false,
				header: false,
				resizable:false,				
				layout:{
						type:'fit'
				},
				items:[panel],
				listeners:{
					afterrender:function(obj){
						//Ext.getCmp(transDeposito.id+'-moneda').setText(transDeposito.get.moneda);
						//Ext.getCmp(transDeposito.id+'-tip-pago').setText(transDeposito.get.medio_pago_des);
						//Ext.getCmp(transDeposito.id+'-monto').setText(' Monto a Depositar: '+trans_cod.formatoNumero(transDeposito.get.monto,2)+' - '+transDeposito.get.moneda);
						//console.log(transDeposito.array)
						Ext.getCmp(transDeposito.id+'-importe').setValue(transDeposito.get.importe);
						
					},
					beforerender:function(obj){
						transDeposito.getDatos();
						//transDeposito.get = trans_cod.getDatos();
						//console.log(transDeposito.array)
					}

				},
				dockedItems:[
					{
						xtype:'toolbar',
						dock: 'bottom',
						ui: 'footer',
						alignTarget: 'center',
						layout:{
                            pack: 'center'
                        },
                        items:[
                        		{
									text:'',
									id:transDeposito.id+'-grabar',
									tooltip:'Click Aqui para Grabar',
									icon: '/images/icon/save.png',
									listeners:{
										beforerender: function(obj, opts){
			                                global.permisos({
			                                    id_serv: 115, 
			                                    id_btn: obj.getId(), 
			                                    id_menu: transDeposito.id_menu,
			                                    fn: ['transDeposito.save']
			                                });
			                            },
										click:function(obj){
											transDeposito.save();
										}
									}
								},
								{
									text:'',
									id:transDeposito.id+'-cancelar',
									tooltip:'Cancelar',
									icon: '/images/icon/close.png',
									listeners:{
										click:function(obj){
											Ext.getCmp(transDeposito.id+'-win').close();
										}
									}

								},
                        ]
					}
				]


			}).show().center();
		},
		save:function(){
			var form = Ext.getCmp(transDeposito.id+'-form2').getForm();
			if (form.isValid()){

				var vp_id_dep = transDeposito.get.id_dep;
		        var vp_cta_id = Ext.getCmp(transDeposito.id+'-cuenta').getValue();
		        var vp_fecha = Ext.getCmp(transDeposito.id+'-fecha').getRawValue();
		        var vp_voucher = Ext.getCmp(transDeposito.id+'-n-operacion').getValue();
		        var vp_retenido = Ext.getCmp(transDeposito.id+'-retenido').getValue();
		        var vp_observ = Ext.getCmp(transDeposito.id+'-obs').getValue();
				/*var vp_cta_id = Ext.getCmp(transDeposito.id+'-cuenta').getValue();
			    var vp_moneda = transDeposito.get.tip_moneda;
			    var vp_cod_ids = transDeposito.get.id_cod;
			    var vp_montos =  transDeposito.get.monto;*/
			    //var vp_voucher = Ext.getCmp(transDeposito.id+'-n-operacion').getValue();
			   //var vp_per_id = Ext.getCmp(transDeposito.id+'-responsable').getValue();
				global.Msg({
					msg:'Desea grabar la Operación?',
					icon:3,
					buttons:3,
					fn:function(obj){
						//console.log(obj);
						if (obj == 'yes'){
							Ext.Ajax.request({
								url:transDeposito.url+'scm_scm_cod_financiero_grabar/',
								params:{
									vp_id_dep:vp_id_dep,
									vp_cta_id:vp_cta_id,
									vp_fecha:vp_fecha,
									vp_voucher:vp_voucher,
									vp_retenido:vp_retenido,
									vp_observ:vp_observ
								},
								success: function(response, options){
									var res = Ext.JSON.decode(response.responseText).data[0];
									if (res.error_sql < 0 ){
										global.Msg({
											msg:res.error_info,
											icon:0,
											fn:function(){
											}
										});
									}else{
										global.Msg({
											msg:res.error_info,
											icon:1,
											fn:function(){
												trans_cod.buscar();
												Ext.getCmp(transDeposito.id+'-win').close();
											}
										});
									}
								}
							});
						}
						
					}
				});
			}else{
				global.Msg({
					msg:'Debe Completar los datos',
					icon:0,
					fn:function(){
					}
				});
			}
		},
		getDatos:function(){
			var rowIndex = transDeposito.array.rowIndex;
			var grid = Ext.getCmp(trans_cod.id+'-grid');
			var store = grid.getStore();
			var rec = grid.getStore().getAt(rowIndex);
			transDeposito.get = rec.data;
			console.log(transDeposito.get);
		}
	}
	Ext.onReady(transDeposito.init, transDeposito);
</script>