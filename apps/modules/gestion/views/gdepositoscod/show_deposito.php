<script type="text/javascript">
	var deposito = {
		id:'deposito',
		id_menu:'<?php echo $p["id_menu"];?>',
		url: '/gestion/gdepositoscod/',
		get:[],
		tip_m_rec:0,
		init:function(){
			var panel2 = Ext.create('Ext.form.Panel',{
				id:deposito.id+'-form2',
				layout:'column',
				border:false,
				margin:'5 10 5 10',
				defaults:{
					padding:'0 15 10 0'
				},
				items:[
						
						{
							xtype:'label',
							id:deposito.id+'-moneda',
							text:'cash',
							labelWidth:55,
							columnWidth:0.5,
							style : 'font-weight: bold;font-size: 12pt;text-align: center;color:#069742',
						},
						{
							xtype:'label',
							id:deposito.id+'-tip-pago',
							text:'Tipo de Pago',
							labelWidth:80,
							columnWidth:0.5,
							style : 'font-weight: bold;font-size: 12pt;text-align: center;color:#069742',
						},
						{
							xtype:'label',
							id:deposito.id+'-monto',
							columnWidth:1,
							text:'',
							style : 'font-weight: bold;font-size: 16pt;text-align: center;color:#069742',
						},
						/*{
							xtype:'label',
							columnWidth:0.5,
							text:'5001.52',
							style : 'font-weight: bold;font-size: 16pt;',
						}*/
						{
							xtype:'combo',
							allowBlank:false,
							id:deposito.id+'-banco',
							store: Ext.create('Ext.data.Store',{
								//autoLoad:true,
								fields:[
										{name:'bco_id', type:'int'},
										{name:'bco_nombre', type:'string'}
								],
								proxy:{
									type:'ajax',
									url:deposito.url+'scm_usr_sis_bancos/',
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
										params:{vp_shi_codigo:6}
									});
								},
								select:function(obj,record,options){
									Ext.getCmp(deposito.id+'-cuenta').getStore().load({
										params:{vp_cta:record.get('bco_id'),vp_shi_codigo:6,vp_tmnd_id:0},
										callback:function(){
											Ext.getCmp(deposito.id+'-cuenta').setValue('');
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
							id: deposito.id+'-cuenta',
							store: Ext.create('Ext.data.Store',{
								fields:[
										{name: 'cta_id', type: 'int'},
										{name: 'cta_numero', type: 'string'},
										{name: 'tmnd_id', type: 'int'}
								],
								proxy:{
									type:'ajax',
									url:deposito.url+'scm_usr_sis_cta/',
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
									deposito.tip_m_rec = parseInt(record.get('tmnd_id'));
									var tip_m_rec = parseInt(record.get('tmnd_id'));
									var tip_m_get = parseInt(deposito.get.tip_moneda);
									/*console.log(record.get('tmnd_id'));
									console.log(deposito.get.tip_moneda);*/
									if (tip_m_rec != tip_m_get){
										//console.log('diferente');
										Ext.getCmp(deposito.id+'-tip-cambio').setReadOnly(false);
										Ext.getCmp(deposito.id+'-tip-cambio').setValue('');
										Ext.getCmp(deposito.id+'-monto').setText('Monto a Depositar: '+deposito.get.MontoCambio);
									}else{
										deposito.get.MontoCambio = 0;
										Ext.getCmp(deposito.id+'-tip-cambio').setReadOnly(true);
										Ext.getCmp(deposito.id+'-tip-cambio').setValue('0');
										Ext.getCmp(deposito.id+'-monto').setText('Monto a Depositar: '+deposito.get.monto);
									}

								}
							}
						},
						{
							xtype:'numberfield',
							allowBlank:false,
							id:deposito.id+'-tip-cambio',
							columnWidth:0.5,
							allowDecimals:true,
							decimalPrecision:4,
							decimalSeparator:'.',
							//maskRe : /[0-9.]$/,
							enableKeyEvents:true,
							fieldLabel:'Tipo de Cambio',
							listeners:{
								keyup:function( obj, e, eOpts ){
									var tip_m_rec = deposito.tip_m_rec;//lo que seleccione
									var tip_moneda = deposito.get.tip_moneda;//lo que llego de base
									var monto = 0;
									if (tip_m_rec != tip_moneda){
										//soles a dolares
										if (tip_moneda == 1 && tip_m_rec == 2){
											//console.log(deposito.get.monto / obj.getValue());	
											monto = parseFloat(deposito.get.monto / obj.getValue());
											Ext.getCmp(deposito.id+'-monto').setText('Monto a Depositar: $.'+gdepositoscod.formatoNumero(monto,2));
										}
										//dolares a soles
										if (tip_moneda == 2 && tip_m_rec == 1){
											//console.log(deposito.get.monto * obj.getValue());	
											monto = parseFloat(deposito.get.monto * obj.getValue());
											Ext.getCmp(deposito.id+'-monto').setText('Monto a Depositar: S/.'+gdepositoscod.formatoNumero(monto,2));
										}
										deposito.get.MontoCambio = monto.toFixed(2);
									}
									
								}
							}
						},
						{
							xtype:'textfield',
							allowBlank:false,
							id:deposito.id+'-n-operacion',
							columnWidth:0.5,
							labelWidth:80,
							//maskRe : /[0-9]$/,
							fieldLabel:'N° Operación'
						},
						{
							xtype:'combo',
							id:deposito.id+'-responsable',
							allowBlank:false,
							columnWidth:1,
							fieldLabel:'Responsable:',
							id:deposito.id+'-responsable',
							store:Ext.create('Ext.data.Store',{
								fields:[
									{name:'nombre', type:'string'},
									{name:'per_id' , type:'int'}
								],
								proxy:{
									type:'ajax',
									url:deposito.url+'scm_usr_sis_personal/',
									reader:{
										type:'json',
										rootProperty:'data'
									}
								}
							}),
							queryMode:'local',
							valueField:'per_id',
							displayField:'nombre',
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
										params:{vp_cargo:0,vp_area:0,vp_prov_codigo:0},//24 es cargo de vendedores
										callback:function(){
										}
									});
								}
							}
						
						},

						


				]
			});

			var panel = Ext.create('Ext.form.Panel',{
				id:deposito.id+'-form',
				layout:'fit',
				border:false,
				items:[
						{
							xtype:'uePanel',
							title:'Depósitos',
							logo: 'signup',
							color:'x-color-top',
							legend: 'Realiza un Depósito',
							defaults:{
								border:false
							},
							items:[panel2]
						}
				]
			});

			Ext.create('Ext.window.Window',{
				id:deposito.id+'-win',
				//cls:'popup_show',
				height: 250,
				width: 535,
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
						deposito.get = gdepositoscod.getDatos();
						Ext.getCmp(deposito.id+'-moneda').setText(deposito.get.moneda);
						Ext.getCmp(deposito.id+'-tip-pago').setText(deposito.get.medio_pago_des);
						Ext.getCmp(deposito.id+'-monto').setText('Monto a Depositar: '+deposito.get.monto);
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
	                        //baseCls: 'gk-toolbar',
	                        items:[
	                        		{
										text:'',
										id:deposito.id+'-grabar',
										tooltip:'Click Aqui para Grabar',
										icon: '/images/icon/save.png',
										listeners:{
											beforerender: function(obj, opts){
				                                global.permisos({
				                                    id_serv: 104, 
				                                    id_btn: obj.getId(), 
				                                    id_menu: deposito.id_menu,
				                                    fn: ['deposito.grabar']
				                                });
				                            },
											click:function(obj){
												deposito.grabar();
											}
										}
									},
									{
										text:'',
										id:deposito.id+'-cancelar',
										tooltip:'Cancelar',
										icon: '/images/icon/close.png',
										listeners:{
											click:function(obj){
												Ext.getCmp(deposito.id+'-win').close();
											}
										}

									},
	                        ]
						}

						
				]
			}).show().center();
		},
		grabar:function(){
			var form = Ext.getCmp(deposito.id+'-form2').getForm();
			if (form.isValid()){
				var banco = Ext.getCmp(deposito.id+'-banco').getValue();
				var cuenta = Ext.getCmp(deposito.id+'-cuenta').getValue();
				var tip_cambio = Ext.getCmp(deposito.id+'-tip-cambio').getValue();
				var n_opera = Ext.getCmp(deposito.id+'-n-operacion').getValue();
				var per_id = Ext.getCmp(deposito.id+'-responsable').getValue();
				var monto = deposito.get.MontoCambio == 0 ? deposito.get.monto:deposito.get.MontoCambio;

				global.Msg({
					msg:'Desea grabar la Operación?',
					icon:3,
					buttons:3,
					fn:function(obj){
						//console.log(obj);
						if (obj == 'yes'){
							Ext.Ajax.request({
								url:deposito.url+'scm_scm_cod_deposito_grabar/',
								params:{
									vp_provincia:deposito.get.prov_codigo,
									vp_cta_id:cuenta,
									vp_tmp_id:deposito.get.tip_pago,
									vp_moneda:deposito.get.tip_moneda,
									vp_per_id:per_id,
									vp_cod_ids:deposito.get.id_cod,
									vp_montos:monto,
									vp_voucher:n_opera,
									vp_tcambio:tip_cambio
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
												gdepositoscod.buscar();
												Ext.getCmp(deposito.id+'-win').close();
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
		}
	}
	Ext.onReady(deposito.init, deposito);
</script>