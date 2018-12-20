<script type="text/javascript">
	var tab = Ext.getCmp(inicio.id+'-tabContent');
	if(!Ext.getCmp('formularioGestion-tab')){
		var formularioGestion = {
			id:'formularioGestion',
			id_menu:'<?php echo $p["id_menu"];?>',
			url:'/gestion/formularioGestion/',
			opcion:'I',
			selectGrid:false,
			cod_form:0,
			cod_form_comp:0,
			componente:'',
			rowEditing_x:{},
			store_x:{},
			init:function(){
				var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'cod_form', type: 'string'},
                    {name: 'nombre', type: 'string'},
                    {name: 'descripcion', type: 'string'},
                    {name: 'estado', type: 'string'},

                    {name: 'fec_crea', type: 'string'},
                    {name: 'fec_mod', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: formularioGestion.url+'get_list_formularios/',
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
                    {name: 'imagen', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: formularioGestion.url+'get_list_campana_formularioGestion/',
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

		    var store_componente = Ext.create('Ext.data.Store', {
		        //storeId: 'store_comp',
		        //autoLoad: true,
		        data: [],
		        fields: ['cod_comp', 'cod_type', 'name', 'nameview']
		    });


		    Ext.define('item_comp', {
                extend: 'Ext.data.Model',
                fields: [
                    {name: 'cod_comp', type: 'string'},
                    {name: 'cod_type', type: 'string'},
                    {name: 'name', type: 'string'},
                    {name: 'nameview', type: 'string'}
                ]
            });


            Ext.define('componentes', {
                extend: 'Ext.data.Model',
                fields: [
                    {name: 'cod_comp', type: 'string'},
                    {name: 'cod_type', type: 'string'},
                    {name: 'name', type: 'string'},
                    {name: 'nameview', type: 'string'}
                ]
            });

		    var store_load_campanas = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'cod_comp', type: 'string'},
                    {name: 'cod_type', type: 'string'},
                    {name: 'name', type: 'string'},
                    {name: 'nameview', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: formularioGestion.url+'get_list_componente/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                        Ext.each(records, function(record) { //step 2
		                    //recordsToSend.push(Ext.apply({id:record.id},record.data));
		                    console.log(record);
		                    var newRecord = Ext.create('componentes', {
	                            cod_comp: record.data.cod_comp,
	                            cod_type: record.data.cod_type,
	                            name: record.data.name,
	                            nameview: record.data.nameview
	                        });
			                var store = Ext.getCmp(formularioGestion.id + '-grid-componentes').getStore();
			                store.add(newRecord);
			                console.log(store);
			                var i = 0;
			                Ext.each(store.data.items, function (record,i) {
			                	record.questionNumber = ++i;
			                });
		                });
                    }
                }
            });

		    Ext.define('formuladio_componente', {
                extend: 'Ext.data.Model',
                fields: [
                    {name: 'cod_form_comp', type: 'int'},
                    {name: 'cod_form', type: 'int'},
                    {name: 'cod_comp', type: 'int'},
                    {name: 'orden', type: 'int'},
                    {name: 'value', type: 'string'},
                    {name: 'cod_type', type: 'int'},
                    {name: 'name', type: 'string'},
                    {name: 'nameview', type: 'string'}
                ]
            });

            this.store_load_formuladio_componente_load = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'cod_form_comp', type: 'int'},
                    {name: 'cod_form', type: 'int'},
                    {name: 'cod_comp', type: 'int'},
                    {name: 'orden', type: 'int'},
                    {name: 'value', type: 'string'},
                    {name: 'cod_type', type: 'int'},
                    {name: 'name', type: 'string'},
                    {name: 'nameview', type: 'string'}
                ],
                //autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: formularioGestion.url+'get_list_formulario_componente/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                    	Ext.getCmp(formularioGestion.id + '-grid-formulario-componentes').getStore().removeAll();
                        Ext.each(records, function(record) { //step 2
		                    //recordsToSend.push(Ext.apply({id:record.id},record.data));
		                    console.log(record);
		                    var newRecord = Ext.create('formuladio_componente', {
	                            cod_form_comp: record.data.cod_form_comp,
	                            cod_form: record.data.cod_form,
	                            cod_comp: record.data.cod_comp,
	                            orden: record.data.orden,
	                            value: record.data.value,
	                            cod_type: record.data.cod_type,
	                            name: record.data.name,
	                            nameview: record.data.nameview
	                        });
			                var store = Ext.getCmp(formularioGestion.id + '-grid-formulario-componentes').getStore();
			                store.add(newRecord);
			                console.log(store);
			                var i = 0;
			                Ext.each(store.data.items, function (record,i) {
			                	record.questionNumber = ++i;
			                });
		                });
                    }
                }
            });


            this.store_load_formuladio_detalle= Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id_det', type: 'int'},
                    {name: 'cod_form_comp', type: 'int'},
                    {name: 'cod_form', type: 'int'},
                    {name: 'cod_comp_estr', type: 'int'},
                    {name: 'name', type: 'string'},
                    {name: 'value', type: 'string'},
                    {name: 'nivel', type: 'int'},
                    {name: 'cod_padre', type: 'int'},
                    {name: 'estado', type: 'int'},
                    {name: 'mant', type: 'string'}
                ],
                //autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: formularioGestion.url+'get_formulario_detalle/',
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
			

		    var store_load_formuladio_componente = Ext.create('Ext.data.Store', {
		        //storeId: 'store_comp_',
		        //autoLoad: true,
		        data: [],
		        fields: ['cod_form_comp', 'cod_form', 'cod_comp', 'orden', 'value', 'cod_type', 'name', 'nameview']
		    });


				var panel = Ext.create('Ext.form.Panel',{
					id:formularioGestion.id+'-form',
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
									border:false,
									layout:'fit',
									items:[
										{
								            itemId: 'grid1',
								            id: formularioGestion.id + '-grid-componentes',
								            flex: 1,
								            xtype: 'grid',
								            multiSelect: true,
							                viewConfig: {
							                	copy:true,
								                plugins: {
								                    ptype: 'gridviewdragdrop',
								                    //pluginId: 'gridviewdragdrop',
								                    //containerScroll: true,
								                    dragGroup: formularioGestion.id + '-grid-group-1',
								                    //dropGroup: formularioGestion.id + '-grid-group-2',
								                    //allowParentInserts: true
								                },
								                listeners: {
								                    drop: function(node, data, dropRec, dropPosition) {alert('aaa');
								                        var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('name') : ' on empty view';
								                        //Ext.msg('Drag from right to left', 'Dropped ' + data.records[0].get('name') + dropOn);
								                    },
								                    beforedrop: function(node, data, dropRec, dropPosition) {
										                  /*Ext.Array.each(data.records, function(rec) {
										                        rec.setDirty();
										                  });*/
										            }
								                }
								            },
								            store: store_componente,
								            columns:[
									            {
									                text: 'Nombre Componente', 
									                flex: 1, 
									                sortable: true, 
									                dataIndex: 'nameview'
									            }
								            ],
								            stripeRows: true,
								            title: 'First Grid',
								            tools: [{
								                type: 'refresh',
								                tooltip: 'Reset both grids',
								                scope: this//,
								                //handler: this.onResetClick
								            }],
								            margin: '0 5 0 0',
								            listeners:{
					                            afterrender: function(obj){
					                                //formularioGestion.getImagen('default.png');
					                            },
												beforeselect:function(obj, record, index, eOpts ){
													formularioGestion.selectGrid=true;
												}
					                        }
								        }
									],
									bbar:[
										{
					                        xtype:'button',
					                        text: 'Guardar',
					                        icon: '/images/icon/save.png',
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
					                                //formularioGestion.buscar_ge();
					                                formularioGestion.setformularioGestion();
					                            }
					                        }
					                    },
					                    {
					                        xtype:'button',
					                        text: 'Nuevo',
					                        icon: '/images/icon/file.png',
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
					                                //formularioGestion.buscar_ge();
					                                formularioGestion.opcion='I';
					                                formularioGestion.setNuevo();
					                            }
					                        }
					                    }
									]
								},
								{
									region:'center',
									id: formularioGestion.id + '-form-dynamic-component',
									border:false,
									//layout:'fit',
									title:'Componentes',
									//margin:10,
									items:[
										
									]
								}
							]
						},
						{
							region:'center',
							border:false,
							layout:'border',
							items:[
								{
									region:'west',
									border:false,
									width:'50%',
									layout:'fit',
									title:'Gestor de Formularios',
									tbar:[
										{
		                                    xtype:'button',
		                                    text: 'Agregar Formulario',
		                                    icon: '/images/icon/add.png',
		                                    listeners:{
		                                        beforerender: function(obj, opts){
		                                        },
		                                        click: function(obj, e){
		                                            formularioGestion.getFormMant(0,'','',1);
		                                        }
		                                    }
		                                },
		                                '-',
		                                /*{
		                                	xtype:'label',
		                                	text:'Buscar:'
		                                },*/
		                                {
		                                    xtype:'button',
		                                    text: 'Buscar',
		                                    icon: '/images/icon/search.png',
		                                    listeners:{
		                                        beforerender: function(obj, opts){
		                                        },
		                                        click: function(obj, e){
		                                        	var nombre = Ext.getCmp(formularioGestion.id+'-form-buscar').getValue();
		                                            formularioGestion.getReloadGridformularioGestion(nombre);
		                                        }
		                                    }
		                                },
		                                {
					                        xtype: 'textfield',
					                        id:formularioGestion.id+'-form-buscar',
					                        //fieldLabel: 'Buscar',
					                        //disabled:true,
					                        labelWidth:0,
					                        labelAlign:'right',
					                        width:'70%',
					                        anchor:'70%',
					                        listeners: {
                                                specialkey: function(f,e){
                                                	if(e.getKey() == e.ENTER){
	                                                    var nombre = Ext.getCmp(formularioGestion.id+'-form-buscar').getValue();
			                                            formularioGestion.getReloadGridformularioGestion(nombre);
		                                        	}
                                                }
                                            }
					                    }
									],
									items:[
										{
					                        xtype: 'grid',
					                        id: formularioGestion.id + '-grid-formularios',
					                        store: store,
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
					                                            id_menu: formularioGestion.id_menu,
					                                            icons:[
					                                                {id_serv: 3, img: 'edit.png', qtip: 'Click para Editar.', js: "formularioGestion.getFormMant("+record.get('cod_form')+",'"+record.get('nombre')+"','"+record.get('descripcion')+"',"+record.get('estado')+")"}
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
					                                //formularioGestion.getImagen('default.png');
					                            },
												beforeselect:function(obj, record, index, eOpts ){
													
													Ext.getCmp(formularioGestion.id+'-form').el.mask('Cargando…', 'x-mask-loading');
													formularioGestion.cod_form=record.get('cod_form');
													formularioGestion.store_load_formuladio_componente_load.removeAll();
													formularioGestion.store_load_formuladio_componente_load.load(
										                {params: {cod_form:record.get('cod_form')},
										                callback:function(){
										                	formularioGestion.store_load_formuladio_detalle.removeAll();
										                	formularioGestion.store_load_formuladio_detalle.load(
												                {params: {cod_form:record.get('cod_form')},
												                callback:function(){
												                	Ext.getCmp(formularioGestion.id+'-form').el.unmask();
												                }
												            });
										                }
										            });
												}
					                        }
					                    }
									]
								},
								{
									region:'center',
									border:false,
									layout:'fit',
									title:'Items',
									items:[
										{
								            itemId: 'grid2',
								            id: formularioGestion.id + '-grid-formulario-componentes',
								            flex: 1,
								            xtype: 'grid',
								            viewConfig: {
								            	allowCopy: true,
								                plugins: {
								                    ptype: 'gridviewdragdrop',
								                    containerScroll: true,
								                    dragGroup: formularioGestion.id + '-grid-group-1',
								                    dropGroup: formularioGestion.id + '-grid-group-1'
								                },
								                listeners: {
								                    drop: function(node, data, dropRec, dropPosition) {
								                        var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('name') : ' on empty view';
								                        //Ext.msg('Drag from left to right', 'Dropped ' + data.records[0].get('name') + dropOn);
								                    },
								                    beforedrop: function ( node, data, overModel, dropPosition, dropHandlers ) {
								                    	Ext.getCmp(formularioGestion.id + '-grid-componentes').getSelectionModel().deselectAll();
														Ext.getCmp(formularioGestion.id + '-grid-componentes').getSelectionModel().clearSelections();
														Ext.getCmp(formularioGestion.id + '-grid-formulario-componentes').getSelectionModel().deselectAll();
														Ext.getCmp(formularioGestion.id + '-grid-formulario-componentes').getSelectionModel().clearSelections();
														var count = Ext.getCmp(formularioGestion.id + '-grid-formulario-componentes').getStore().getCount();
								                    	if(formularioGestion.selectGrid && formularioGestion.cod_form!=0){
									                      	  /*console.log(data);
											                  var newRecord = Ext.create('item_comp', {
											                  	cod_comp: data.records[0].data.cod_comp,
	                                                            cod_type: data.records[0].data.cod_type,
											                  	name:data.records[0].data.name,//Valor ' + (count+1),
	                                                            nameview: data.records[0].data.nameview
	                                                           });
											                  var store = Ext.getCmp(formularioGestion.id + '-grid-formulario-componentes').getStore();
											                  store.add(newRecord);
											                  console.log(store);
											                  var i = 0;
											                  Ext.each(store.data.items, function (record,i) 
											                  {
											                	record.questionNumber = ++i;
											                  });*/

											                  	Ext.Ajax.request({
																	url: formularioGestion.url + 'set_insert_formulario_detalle/',
												                    params:{
												                        cod_form: formularioGestion.cod_form,
												                        cod_comp:data.records[0].data.cod_comp
												                    },
												                    success: function( response, options){
												                    	//console.log(response);
												                    	var res = Ext.JSON.decode(response.responseText);
												                        //console.log(res);
												                        Ext.getCmp(formularioGestion.id+'-form').el.unmask();
												                        //console.log(res);
												                        if (parseInt(res.error) == 0){
												                            formularioGestion.getReloadGrid();
												                        } else{
												                            global.Msg({
												                                msg: 'Ocurrio un error intentalo nuevamente.', 
												                                icon: 0,
												                                buttons: 1,
												                                fn: function(btn){
												                                    //formularioGestion.getReloadGridformularioGestion('');
												                                    //formularioGestion.setNuevo();
												                                }
												                            });
												                        }
												                    }
																});
											                  return false;
										              	}else if(formularioGestion.cod_form==0){
										              		global.Msg({
								                                msg: 'Seleccione un registro de formulario.',
								                                icon: 2,
								                                buttons: 1,
								                                fn: function(btn){
								                                    //formularioGestion.getReloadGridformularioGestion('');
								                                    //formularioGestion.setNuevo();
								                                }
								                            });
										              		return false;
										              	}
                                                        /**/
								                    }
								                }
								            },
								            store: store_load_formuladio_componente,
								            columns:[{
								                text: 'Nombre Componente', 
								                width: 120, 
								                sortable: true, 
								                dataIndex: 'nameview'
								            }, {
								                text: 'Valor', 
								                flex: 1, 
								                sortable: true, 
								                dataIndex: 'value'
								            }, {
								                text: 'Otros', 
								                width: 80, 
								                sortable: true, 
								                dataIndex: 'column2'
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
			                                            id_menu: formularioGestion.id_menu,
			                                            icons:[
			                                                {id_serv: 3, img: 'close.png', qtip: 'Click para eliminar.', js: 'formularioGestion.setDeleteRecord('+record.get('cod_form_comp')+')'}
			                                            ]
			                                        });
			                                    }
			                                }],
								            stripeRows: true,
								            //title: 'Second Grid',
								            listeners:{
					                            afterrender: function(obj){
					                                //formularioGestion.getImagen('default.png');
					                            },
												beforeselect:function(obj, record, index, eOpts ){
													formularioGestion.selectGrid=false;
													formularioGestion.setConfig(obj,record,index);
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
					id:formularioGestion.id+'-tab',
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
	                        global.state_item_menu(formularioGestion.id_menu, true);
	                    },
	                    afterrender: function(obj, e){
	                        tab.setActiveTab(obj);
	                        global.state_item_menu_config(obj,formularioGestion.id_menu);
	                    },
	                    beforeclose:function(obj,opts){
	                    	global.state_item_menu(formularioGestion.id_menu, false);
	                    }
					}

				}).show();
			},
			getReloadGrid:function(){
				Ext.getCmp(formularioGestion.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				formularioGestion.store_load_formuladio_componente_load.removeAll();
				formularioGestion.store_load_formuladio_componente_load.load(
	                {params: {cod_form:formularioGestion.cod_form},
	                callback:function(){
	                	formularioGestion.store_load_formuladio_detalle.removeAll();
	                	formularioGestion.store_load_formuladio_detalle.load(
			                {params: {cod_form:formularioGestion.cod_form},
			                callback:function(){
			                	Ext.getCmp(formularioGestion.id+'-form').el.unmask();
			                }
			            });
	                }
	            });
			},
			getReloadGridDetalle:function(){
				Ext.getCmp(formularioGestion.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				formularioGestion.store_load_formuladio_detalle.removeAll();
            	formularioGestion.store_load_formuladio_detalle.load(
	                {params: {cod_form:formularioGestion.cod_form},
	                callback:function(){
	                	Ext.getCmp(formularioGestion.id+'-form').el.unmask();
	                	formularioGestion.getReloadGridCompomente(); 
	                }
	            });
			},
			setDeleteRecord:function(cod_form_comp){

				global.Msg({
					msg:'¿Desea eliminar el registro?',
					icon:3,
					buttons:3,
					fn:function(obj){
						//console.log(obj);
						if (obj == 'yes'){
							Ext.Ajax.request({
								url:formularioGestion.url+'set_delete_formulario_detalle/',
								params:{
									cod_form_comp:cod_form_comp
								},
								success: function(response, options){
									var res = Ext.JSON.decode(response.responseText);
									if (res.error == 0 ){
										global.Msg({
											msg:res.data,
											icon:1,
											fn:function(){
												formularioGestion.getReloadGrid();
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
			getImagen:function(param){
				win.getGalery({container:'GaleryFull',width:390,height:250,params:{forma:'F',img_path:'/formularioGestion/'+param}});
			},
			setformularioGestion:function(){
				var recordsToSend = [];
				Ext.each(formularioGestion.store_load_formuladio_detalle.data.items, function (recordx,i) {
		           	if(recordx.data.cod_form_comp == formularioGestion.cod_form_comp){
						switch(formularioGestion.componente){
							case 'edit_text':
								
		                		var val_mm = Ext.getCmp(formularioGestion.id+'_correo').getValue()?0:1;
		                		var val_mail = Ext.getCmp(formularioGestion.id+'_correo').getValue()?1:0;
								switch(recordx.data.name){
									case 'hint':
										var indicio = Ext.getCmp(formularioGestion.id+'_indicio').getValue();
										recordx.set('value',indicio);
									break;
									case 'v_min_length':case 'v_max_length':
										recordx.set('estado',val_mm);
									break;
									case 'v_email':
										recordx.set('estado',val_mail);
									break;
									case 'value':
										if(recordx.data.cod_padre=='3'){
											recordx.set('estado',val_mm);
											var min = Ext.getCmp(formularioGestion.id+'_numero_min').getValue();
											recordx.set('value',min);
										}
										if(recordx.data.cod_padre=='6'){
											recordx.set('estado',val_mm);
											var max = Ext.getCmp(formularioGestion.id+'_numero_max').getValue();
											recordx.set('value',max);
										}
										if(recordx.data.cod_padre=='9'){
											recordx.set('value',val_mail==1?'true':'false');
											recordx.set('estado',val_mail);
										}
									break;
									case 'err':
										if(recordx.data.cod_padre=='3'){
											recordx.set('estado',val_mm);
											var err_min = Ext.getCmp(formularioGestion.id+'_err_min').getValue();
											recordx.set('value',err_min);
										}
										if(recordx.data.cod_padre=='6'){
											recordx.set('estado',val_mm);
											var err_max=Ext.getCmp(formularioGestion.id+'_err_max').getValue();
											recordx.set('value',err_max);
										}
										if(recordx.data.cod_padre=='9'){
											recordx.set('estado',val_mail);
										}
									break;
								}
								recordx.commit();
								recordsToSend.push(Ext.apply({id:recordx.id},recordx.data));
				                	
							break;
							case 'label':
								switch(recordx.data.name){
									case 'text':
										var label = Ext.getCmp(formularioGestion.id+'_label').getValue();
										recordx.set('value',label);
									break;
								}
			                    recordx.commit();
								recordsToSend.push(Ext.apply({id:recordx.id},recordx.data));
							break;
							case 'choose_image':
								switch(recordx.data.name){
									case 'uploadButtonText':
										var btn = Ext.getCmp(formularioGestion.id+'_boton').getValue();
										recordx.set('value',btn);
									break;
									case 'v_required':case 'err':
										var val_boo = Ext.getCmp(formularioGestion.id+'_obligatorio').getValue()?1:0;
										recordx.set('estado',val_boo);
									break;
									case 'value':
										var val_boo = Ext.getCmp(formularioGestion.id+'_obligatorio').getValue()?1:0;
										recordx.set('value',val_boo==1?'true':'false');
										recordx.set('estado',val_boo);
									break;
								}
								recordx.commit();
								recordsToSend.push(Ext.apply({id:recordx.id},recordx.data));
							break;
							case 'choose_image':
								switch(recordx.data.name){
									case 'uploadButtonText':
										var btn = Ext.getCmp(formularioGestion.id+'_boton').getValue();
										recordx.set('value',btn);
									break;
									case 'v_required':case 'err':
										var val_boo = Ext.getCmp(formularioGestion.id+'_obligatorio').getValue()?1:0;
										recordx.set('estado',val_boo);
									break;
									case 'value':
										var val_boo = Ext.getCmp(formularioGestion.id+'_obligatorio').getValue()?1:0;
										recordx.set('value',val_boo==1?'true':'false');
										recordx.set('estado',val_boo);
									break;
								}
								recordx.commit();
								recordsToSend.push(Ext.apply({id:recordx.id},recordx.data));
							break;
							case 'spinner':
								switch(recordx.data.name){
									case 'hint':
										var hint = Ext.getCmp(formularioGestion.id+'_indicio_spinner').getValue();
										recordx.set('value',hint);
									break;
									case 'v_required':case 'err':
										var val_boo = Ext.getCmp(formularioGestion.id+'_obligatorio_spinner').getValue()?1:0;
										recordx.set('estado',val_boo);
									break;
									case 'value':
										if(recordx.data.cod_comp_estr==27){
											var val_boo = Ext.getCmp(formularioGestion.id+'_obligatorio_spinner').getValue()?1:0;
											recordx.set('value',val_boo==1?'true':'false');
											recordx.set('estado',val_boo);
										}
										if(recordx.data.cod_comp_estr==43){
											var val = Ext.getCmp(formularioGestion.id+'_cmb_auto').getVale();
											recordx.set('value',val);
										}
									break;
								}
								recordx.commit();
								recordsToSend.push(Ext.apply({id:recordx.id},recordx.data));
							break;
							case 'radio':
								switch(recordx.data.name){
									case 'label':
										var label = Ext.getCmp(formularioGestion.id+'_etiqueta').getValue();
										recordx.set('value',label);
									break;
									case 'value':
										if(recordx.data.cod_comp_estr==44){
											var val = Ext.getCmp(formularioGestion.id+'_cmb_radio').getValue();
											recordx.set('value',val);
										}
									break;
								}
								recordx.commit();
								recordsToSend.push(Ext.apply({id:recordx.id},recordx.data));
							break;
							case 'check_box':
								switch(recordx.data.name){
									case 'label':
										var label = Ext.getCmp(formularioGestion.id+'_etiqueta').getValue();
										recordx.set('value',label);
									break;
									case 'value':
									break;
								}
								recordx.commit();
								recordsToSend.push(Ext.apply({id:recordx.id},recordx.data));
							break;
							case 'audio':
							break;
						}
					}
				});
                recordsToSend = Ext.encode(recordsToSend);
                console.log(recordsToSend);
				//return;
                global.Msg({
					msg: '¿Está seguro de guardar la configuración?',
					icon:3,
					buttons:3,
					fn:function(obj){
						//console.log(obj);
						if (obj == 'yes'){
							Ext.getCmp(formularioGestion.id+'-form').el.mask('Cargando…', 'x-mask-loading');
							Ext.Ajax.request({
								url:formularioGestion.url+'set_update_formulario_detalle/',
								params:{
									vp_recordsToSend:recordsToSend
								},
								success: function(response, options){
									Ext.getCmp(formularioGestion.id+'-form').el.unmask();
									var res = Ext.JSON.decode(response.responseText);
									if (res.error == 0 ){
										global.Msg({
											msg:res.data,
											icon:1,
											fn:function(){
												formularioGestion.getReloadGrid();
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
			getReloadGridformularioGestion:function(name){
				Ext.getCmp(formularioGestion.id+'-form').el.mask('Cargando…', 'x-mask-loading');
				Ext.getCmp(formularioGestion.id + '-grid-formularios').getStore().load(
	                {params: {vp_nombre:name},
	                callback:function(){
	                	Ext.getCmp(formularioGestion.id+'-form').el.unmask();
	                	Ext.getCmp(formularioGestion.id + '-grid-formulario-componentes').getStore().removeAll();
	                	formularioGestion.store_load_formuladio_detalle.removeAll();
	                	var form = Ext.getCmp(formularioGestion.id + '-form-dynamic-component');
						form.setTitle('Propiedades');
						form.removeAll();
						formularioGestion.opcion='I';
						formularioGestion.selectGrid=false;
						formularioGestion.cod_form=0;
						formularioGestion.cod_form_comp=0;
						formularioGestion.componente='';
	                }
	            });
			},
			setNuevo:function(){
				formularioGestion.shi_codigo=0;
				formularioGestion.getImagen('default.png');
				Ext.getCmp(formularioGestion.id+'-txt-nombre').setValue('');
				Ext.getCmp(formularioGestion.id+'-date-re').setValue('');
				Ext.getCmp(formularioGestion.id+'-cmb-estado').setValue('');
				Ext.getCmp(formularioGestion.id+'-txt-nombre').focus();
			},
			setSaveForm:function(){
				var modified = Ext.getCmp(formularioGestion.id + '-grid-formulario-componentes').getStore().data.items
	            var recordsToSend = [];
	            if(!Ext.isEmpty(modified)){
	                Ext.each(modified, function(record) { //step 2
	                    recordsToSend.push(Ext.apply({id:record.id},record.data));
	                });
	                recordsToSend = Ext.encode(recordsToSend);
	            }console.log(recordsToSend);
			},/*
			getDeleteRecord:function(id){
				//Ext.getCmp(formularioGestion.id + '-grid-formulario-componentes').getStore().removeAt(index);
                //Ext.getCmp(formularioGestion.id + '-grid-formulario-componentes').getStore().sync();
                formularioGestion.setDeleteRecord();
			},*/
			setDeleteOnlyRecord:function(key){
				global.Msg({
					msg: '¿Está seguro de guardar la configuración?',
					icon:3,
					buttons:3,
					fn:function(obj){
						//console.log(obj);
						if (obj == 'yes'){
							Ext.getCmp(formularioGestion.id+'-form').el.mask('Cargando…', 'x-mask-loading');
							
							Ext.Ajax.request({
								url:formularioGestion.url+'set_delete_only_one_formulario_detalle/',
								params:{
									id_det:key,
									cod_form_comp:formularioGestion.cod_form_comp,
									cod_form:formularioGestion.cod_form
								},
								success: function(response, options){
									Ext.getCmp(formularioGestion.id+'-form').el.unmask();
									var res = Ext.JSON.decode(response.responseText);
									formularioGestion.getReloadGridDetalle();
								}
							});
						}
						
					}
				});
			},
			setDeleteOnlyRecordRC:function(key){
				global.Msg({
					msg: '¿Está seguro de guardar la configuración?',
					icon:3,
					buttons:3,
					fn:function(obj){
						//console.log(obj);
						if (obj == 'yes'){
							Ext.getCmp(formularioGestion.id+'-form').el.mask('Cargando…', 'x-mask-loading');
							
							Ext.Ajax.request({
								url:formularioGestion.url+'set_delete_chk_rdb_only_one_formulario_detalle/',
								params:{
									id_det:key,
									cod_form_comp:formularioGestion.cod_form_comp,
									cod_form:formularioGestion.cod_form
								},
								success: function(response, options){
									Ext.getCmp(formularioGestion.id+'-form').el.unmask();
									var res = Ext.JSON.decode(response.responseText);
									formularioGestion.getReloadGridDetalle();
								}
							});
						}
						
					}
				});
			},
			getReloadGridCompomente:function(){
				formularioGestion.store_x.removeAll();
				Ext.each(formularioGestion.store_load_formuladio_detalle.data.items, function (recordx,i) {
                	if(recordx.data.cod_form_comp == formularioGestion.cod_form_comp){
                		formularioGestion.rowEditing_x.cancelEdit();
						if(recordx.data.cod_padre == 24){
                            var r = Ext.create('modelo', {
                                value: recordx.data.value,
                                value_old:recordx.data.value,
                                key:recordx.data.id_det
                            });
                            formularioGestion.store_x.insert(0, r);
                            //formularioGestion.rowEditing_x.startEdit(0, 0);
						}else if((recordx.data.cod_padre == 32 || recordx.data.cod_padre == 36) && recordx.data.name=='text'){
							var r = Ext.create('modelo', {
                                value: recordx.data.value,
                                value_old:recordx.data.value,
                                key:recordx.data.id_det
                            });
                            formularioGestion.store_x.insert(0, r);
						}
                	}
                });
			},
			setConfig:function(obj,record,index){
				var form = Ext.getCmp(formularioGestion.id + '-form-dynamic-component');
				form.setTitle('Propiedades de : '+record.data.nameview);
				form.removeAll();
				formularioGestion.componente =record.data.name;
				formularioGestion.cod_form_comp=record.data.cod_form_comp;
				var index = 0;
				switch(record.data.name){
					case 'edit_text':
						var indicio = Ext.create('Ext.form.TextField', {
	                        id:formularioGestion.id+'_indicio',
	                        fieldLabel: 'Indicio',
	                        labelAlign: 'right',
	                        labelWidth:100,
	                        margin:5,
	                        emptyText:'Ingrese un texto de indicio de ayuda',
	                        //disabled: true,
	                        width: '90%',
	                        anchor: '100%'
	                    });
						
						var correo = Ext.create('Ext.form.Checkbox', {
	                        fieldLabel: '¿Es un campo de Correo?',
	                        id:formularioGestion.id+'_correo',
	                        labelWidth: 140,
	                        //boxLabel: '¿Novedad Pública?',
	                        listeners:{
	                            change:function(obj){
	                                Ext.getCmp(formularioGestion.id+'_obligatorio_txt').setVisible(!obj.getValue());
                                	Ext.getCmp(formularioGestion.id+'_numero_min').setVisible(!obj.getValue());
                                	Ext.getCmp(formularioGestion.id+'_err_min').setVisible(!obj.getValue());

                                	Ext.getCmp(formularioGestion.id+'_numero_max').setVisible(!obj.getValue());
                                	Ext.getCmp(formularioGestion.id+'_err_max').setVisible(!obj.getValue());
	                            }
	                        }
	                    });


						var obliga = Ext.create('Ext.form.Checkbox', {
	                        fieldLabel: '¿Campo Obligatoria?',
	                        id:formularioGestion.id+'_obligatorio_txt',
	                        labelWidth: 140,
	                        checked:true,
	                        //boxLabel: '¿Novedad Pública?',
	                        listeners:{
	                            change:function(obj){
	                                //if(obj.getValue())
	                            }
	                        }
	                    });

						var numero_min = Ext.create('Ext.form.field.Number', {
	                        id:formularioGestion.id+'_numero_min',
	                        fieldLabel: 'Longitud Mínima',
	                        labelAlign: 'right',
	                        labelWidth:100,
	                        margin:5,
	                        //disabled: true,
	                        value:3,
	                        width: '50%',
	                        anchor: '100%',
	                        listeners:{
	                        	change:function(obj){
	                        		Ext.getCmp(formularioGestion.id+'_err_min').setValue('La longitud mínima es de '+obj.getValue()+' caracteres');
	                        	}
	                        }
	                    });

						var err_min = Ext.create('Ext.form.TextField', {
	                        id:formularioGestion.id+'_err_min',
	                        fieldLabel: 'Error Mínimo',
	                        labelAlign: 'right',
	                        labelWidth:100,
	                        margin:5,
	                        value:'La longitud mínima es de 3 caracteres',
	                        disabled: true,
	                        width: '90%',
	                        anchor: '100%'
	                    });

						var numero_max = Ext.create('Ext.form.field.Number', {
	                        id:formularioGestion.id+'_numero_max',
	                        fieldLabel: 'Longitud Máxima',
	                        labelAlign: 'right',
	                        labelWidth:100,
	                        margin:5,
	                        //disabled: true,
	                        value:40,
	                        width: '50%',
	                        anchor: '100%',
	                        listeners:{
	                        	change:function(obj){
	                        		Ext.getCmp(formularioGestion.id+'_err_max').setValue('La longitud máxima es de '+obj.getValue()+' caracteres');
	                        	}
	                        }
	                    });

	                    var err_max = Ext.create('Ext.form.TextField', {
	                        id:formularioGestion.id+'_err_max',
	                        fieldLabel: 'Error Máximo',
	                        labelAlign: 'right',
	                        labelWidth:100,
	                        margin:5,
	                        disabled: true,
	                        value:'La longitud máxima es de 40 caracteres',
	                        width: '90%',
	                        anchor: '100%'
	                    });

	                    form.add(indicio);
	                    form.add(correo);
	                    form.add(obliga);
	                    form.add(numero_min);
	                    form.add(err_min);
						form.add(numero_max);
						form.add(err_max);

						form.show();

						Ext.each(formularioGestion.store_load_formuladio_detalle.data.items, function (recordx,i) {
		                	if(recordx.data.cod_form_comp == record.data.cod_form_comp){
		                		/*recordx.data.cod_form:1
								recordx.data.cod_form_comp:1
								recordx.data.cod_padre:6
								//id:"extModel563-8"
								recordx.data.id_det:8
								recordx.data.name:"err"
								recordx.data.nivel:2
								recordx.data.value:"Max length can be at most 10."*/

								switch(recordx.data.name){
									case 'hint':
										Ext.getCmp(formularioGestion.id+'_indicio').setValue(recordx.data.value);
										/*Ext.getCmp(formularioGestion.id+'_indicio').emptyText=recordx.data.value;
										Ext.getCmp(formularioGestion.id+'_indicio').applyEmptyText();
										Ext.getCmp(formularioGestion.id+'_indicio').reset();*/
									break;
									case 'value':
										if(recordx.data.cod_padre=='3'){
											Ext.getCmp(formularioGestion.id+'_numero_min').setValue(recordx.data.value);
										}
										if(recordx.data.cod_padre=='6'){
											Ext.getCmp(formularioGestion.id+'_numero_max').setValue(recordx.data.value);
										}
									break;
									case 'err':
										if(recordx.data.cod_padre=='3'){
											Ext.getCmp(formularioGestion.id+'_err_min').setValue(recordx.data.value);
										}
										if(recordx.data.cod_padre=='6'){
											Ext.getCmp(formularioGestion.id+'_err_max').setValue(recordx.data.value);
										}
									break;
								}
		                	}
		                });
					break;
					case 'label':
						var label = Ext.create('Ext.form.TextField', {
	                        id:formularioGestion.id+'_label',
	                        fieldLabel: 'Etiqueta',
	                        labelAlign: 'right',
	                        labelWidth:50,
	                        margin:5,
	                        value:'',
	                        emptyText:'Ingrese un texto referente a la etiqueta',
	                        //disabled: true,
	                        width: '90%',
	                        anchor: '100%'
	                    });
						

	                    form.add(label);

						form.show();

						Ext.each(formularioGestion.store_load_formuladio_detalle.data.items, function (recordx,i) {
		                	if(recordx.data.cod_form_comp == record.data.cod_form_comp){
								switch(recordx.data.name){
									case 'text':
										Ext.getCmp(formularioGestion.id+'_label').setValue(recordx.data.value);
									break;
								}
		                	}
		                });
					break;
					case 'choose_image':
						form.add({ 
							xtype: 'label', 
							margin:'10px 0px 0px 110px',
							text: 'Imagen:', 
							labelAlign: 'right',
							labelWidth:100
						});
						var formx = Ext.FormPanel({ 
						    margin:'10px 60px 10px 110px',
                        	border:true,
                        	height:120,
                        	width:200,
                        	html:'<div id="imagen_componente" class="links"></div>'
						});

						var boton = Ext.create('Ext.form.TextField', {
	                        id:formularioGestion.id+'_boton',
	                        fieldLabel: 'Texto del Boton',
	                        labelAlign: 'right',
	                        labelWidth:100,
	                        margin:5,
	                        value:'Selecciona Imagen',
	                        emptyText:'Ingrese un texto para el boton',
	                        //disabled: true,
	                        width: '90%',
	                        anchor: '100%'
	                    });

	                    var obliga = Ext.create('Ext.form.Checkbox', {
	                        fieldLabel: '¿Imagen Obligatoria?',
	                        id:formularioGestion.id+'_obligatorio',
	                        labelWidth: 120,
	                        //boxLabel: '¿Novedad Pública?',
	                        listeners:{
	                            change:function(obj){
	                                Ext.getCmp(formularioGestion.id+'_err_msn').setVisible(obj.getValue());
	                            }
	                        }
	                    });

	                    var err_msn = Ext.create('Ext.form.TextField', {
	                        id:formularioGestion.id+'_err_msn',
	                        fieldLabel: 'Mensaje Error',
	                        labelAlign: 'right',
	                        labelWidth:100,
	                        margin:5,
	                        disabled: true,
	                        value:'Por favor, elija una imagen para continuar.',
	                        width: '90%',
	                        anchor: '100%'
	                    });

						form.add(formx);
						form.add(boton);
						form.add(obliga);
						form.add(err_msn);
						form.show();
						win.getGalery({container:'imagen_componente',width:200,height:100,params:{forma:'F',img_path:'/shipper/default.png'}});

						Ext.each(formularioGestion.store_load_formuladio_detalle.data.items, function (recordx,i) {
		                	if(recordx.data.cod_form_comp == record.data.cod_form_comp){
								switch(recordx.data.name){
									case 'uploadButtonText':
										Ext.getCmp(formularioGestion.id+'_boton').setValue(recordx.data.value);
									break;
									case 'value':
										Ext.getCmp(formularioGestion.id+'_obligatorio').setValue(recordx.data.value);
										//Ext.getCmp(formularioGestion.id+'_err_msn').setVisible(recordx.data.value);
									break;
								}
		                	}
		                });

					break;
					case 'spinner':
						var indicio = Ext.create('Ext.form.TextField', {
	                        id:formularioGestion.id+'_indicio_spinner',
	                        fieldLabel: 'Indicio',
	                        labelAlign: 'right',
	                        labelWidth:60,
	                        margin:5,
	                        emptyText:'Ingrese un texto de indicio de ayuda',
	                        //disabled: true,
	                        width: '90%',
	                        anchor: '100%'
	                    });

						var model = Ext.define('modelo', {
			                extend: 'Ext.data.Model',
			                fields: [
			                    { name: 'value', type: 'string' },
			                    { name: 'value_old', type: 'string' },
			                    { name: 'key', type: 'string' }
			                ]
			            });
			            formularioGestion.store_x = Ext.create('Ext.data.Store',{
			                autoDestroy: true,
			                model: 'modelo',
			                proxy: {
			                    type: 'memory'
			                },
			                sorters: [{
			                    property: 'common',
			                    direction: 'ASC'
			                }]
			            });
			            
			            formularioGestion.rowEditing_x= Ext.create('Ext.grid.plugin.RowEditing', {
			                clicksToMoveEditor: 1,
			                autoCancel: false,
			                listeners: {
				                edit: function(editor, context, eOpts){
					                console.log(editor);
					                console.log(context.record.data);
					                console.log(eOpts);

					                /*Ext.each(formularioGestion.store_load_formuladio_detalle.data.items, function (recordx,i) {
		           						if(recordx.data.id_det == context.record.data.key){
		           							recordx.set('value',context.record.data.value);
											recordx.commit();
		           						}
		           					});*/

		           					Ext.Ajax.request({
										url:formularioGestion.url+'set_update_only_one_formulario_detalle/',
										params:{
											cod_form_comp:formularioGestion.cod_form_comp,
											cod_form:formularioGestion.cod_form,
											id_det:context.record.data.key,
											name:'',
											value:context.record.data.value,
											cod_padre:24
										},
										success: function(response, options){
											var res = Ext.JSON.decode(response.responseText);
											formularioGestion.getReloadGridDetalle(); 
										}
									});
					            }
				            }
			            });



	                    var obliga = Ext.create('Ext.form.Checkbox', {
	                        fieldLabel: 'Selección Obligatoria?',
	                        id:formularioGestion.id+'_obligatorio_spinner',
	                        labelWidth: 145, 
	                        labelAlign: 'right',
	                        //boxLabel: '¿Novedad Pública?',
	                        listeners:{
	                            change:function(obj){
	                                //if(obj.getValue())
	                            }
	                        }
	                    });

	                    var err_msn = Ext.create('Ext.form.TextField', {
	                        id:formularioGestion.id+'_err_msn_spinner',
	                        fieldLabel: 'Mensaje Error',
	                        labelAlign: 'right',
	                        labelWidth:100,
	                        margin:5,
	                        disabled: true,
	                        value:'Por favor, elija un valor para continuar.',
	                        width: '90%',
	                        anchor: '100%'
	                    });

	                    form.add(indicio);
	                    form.add(
	                    	{
	                            xtype: 'grid',
	                            id: formularioGestion.id+'-values-spinner',
	                            bbar:[
	                            	{
	                                    xtype:'button',
	                                    text: 'Agregar',
	                                    icon: '/images/icon/add.png',
	                                    listeners:{
	                                        beforerender: function(obj, opts){
	                                        },
	                                        click: function(obj, e){
	                                            /*rowEditing_spinner.cancelEdit();
	                                            var r = Ext.create('modelo', {
	                                                value: '',
	                                                value_old:'',
	                                                key:0
	                                            });

	                                            store_spinner.insert(0, r);
	                                            rowEditing_spinner.startEdit(0, 0);
												
	                                            */
	                                            Ext.Ajax.request({
													url:formularioGestion.url+'set_insert_only_one_formulario_detalle/',
													params:{
														cod_form_comp:formularioGestion.cod_form_comp,
														cod_form:formularioGestion.cod_form,
														name:'',
														value:'Insertar dato',
														cod_padre:24
													},
													success: function(response, options){
														var res = Ext.JSON.decode(response.responseText);
														formularioGestion.getReloadGridDetalle(); 
													}
												});
	                                        }
	                                    }
	                                }
	                            ],
	                            flex:1,
	                            height:200,
	                            store: formularioGestion.store_x,
	                            plugins: [
	                                formularioGestion.rowEditing_x
	                            ],
	                            columnLines: true,
	                            columns:{
	                                items:[
	                                    {
	                                        text: 'Value',
	                                        dataIndex: 'value',
	                                        flex: 1,
	                                        align: 'left',
	                                        editor: {
	                                            // defaults to textfield if no xtype is supplied
	                                            allowBlank: false
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
		                                            id_menu: formularioGestion.id_menu,
		                                            icons:[
		                                                {id_serv: 3, img: 'close.png', qtip: 'Click para eliminar.', js: 'formularioGestion.setDeleteOnlyRecord('+record.get('key')+')'}
		                                            ]
		                                        });
		                                    }
		                                }
	                                ],
	                                defaults:{
	                                    menuDisabled: true
	                                }
	                            },
	                            selModel: {
	                                selType: 'cellmodel'
	                            },
	                            viewConfig: {
	                                stripeRows: true,
	                                enableTextSelection: false,
	                                markDirty: false
	                            },
	                            trackMouseOver: false,
	                            listeners:{
	                                afterrender: function(obj){
	                                    
	                                }
	                            }
	                        }
	                    );
	                    form.add(obliga);
	                    form.add(err_msn);
	                    var auto=Ext.create('Ext.form.ComboBox', {
	                        fieldLabel: 'Auto Seleccionado',
	                        id:formularioGestion.id+'_cmb_auto',
	                        store: formularioGestion.store_x,
	                        queryMode: 'local',
	                        triggerAction: 'all',
	                        valueField: 'value',
	                        displayField: 'value',
	                        emptyText: '[Seleccione un valor por defecto]',
	                        //allowBlank: false,
	                        labelAlign: 'right',
	                        labelWidth: 120,
	                        margin:5,
	                        width:'90%',
	                        anchor:'100%',
	                        //readOnly: true,
	                        listeners:{
	                            afterrender:function(obj, e){
	                                // obj.getStore().load();
	                            },
	                            select:function(obj, records, eOpts){
	                                
	                            }
	                        }
	                    });
						form.add(auto);
	                    form.show();

	                    Ext.each(formularioGestion.store_load_formuladio_detalle.data.items, function (recordx,i) {
		                	if(recordx.data.cod_form_comp == record.data.cod_form_comp){
								switch(recordx.data.name){
									case 'hint':
										Ext.getCmp(formularioGestion.id+'_indicio_spinner').setValue(recordx.data.value);
									break;
									case 'values':
										//Ext.getCmp(formularioGestion.id+'_obligatorio').setValue(recordx.data.value);
										index = recordx.data.cod_comp_estr;
										//Ext.getCmp(formularioGestion.id+'_err_msn').setVisible(recordx.data.value);
									break;
									case 'v_required':case 'err':

									break;
									case 'value':
										if(recordx.data.cod_padre==0){
											Ext.getCmp(formularioGestion.id+'_cmb_auto').setValue(recordx.data.value);
										}else{
											Ext.getCmp(formularioGestion.id+'_obligatorio_spinner').setValue(recordx.data.value);
										}
									break;
								}
								if(recordx.data.cod_padre == 24){
									formularioGestion.rowEditing_x.cancelEdit();
                                    var r = Ext.create('modelo', {
                                        value: recordx.data.value,
                                        value_old:recordx.data.value,
                                        key:recordx.data.id_det
                                    });

                                    formularioGestion.store_x.insert(0, r);
                                    //formularioGestion.rowEditing_x.startEdit(0, 0);
								}
		                	}
		                });
					break;
					case 'radio':
						var etiqueta = Ext.create('Ext.form.TextField', {
	                        id:formularioGestion.id+'_etiqueta',
	                        fieldLabel: 'Etiqueta',
	                        labelAlign: 'right',
	                        labelWidth:80,
	                        margin:5,
	                        value:'',
	                        emptyText:'Ingrese un texto para la etiqueta del radio',
	                        //disabled: true,
	                        width: '90%',
	                        anchor: '100%'
	                    });
						form.add(etiqueta);

						var model = Ext.define('modelo', {
			                extend: 'Ext.data.Model',
			                fields: [
			                    { name: 'value', type: 'string' },
			                    { name: 'value_old', type: 'string' },
			                    { name: 'key', type: 'string' }
			                ]
			            });
			            formularioGestion.store_x = Ext.create('Ext.data.Store',{
			                autoDestroy: true,
			                model: 'modelo',
			                proxy: {
			                    type: 'memory'
			                },
			                sorters: [{
			                    property: 'common',
			                    direction: 'ASC'
			                }]
			            });
			            
			            formularioGestion.rowEditing_x= Ext.create('Ext.grid.plugin.RowEditing', {
			                clicksToMoveEditor: 1,
			                autoCancel: false,
			                listeners: {
				                edit: function(editor, context, eOpts){
					                console.log(editor);
					                console.log(context.record.data);
					                console.log(eOpts);

					                /*Ext.each(formularioGestion.store_load_formuladio_detalle.data.items, function (recordx,i) {
		           						if(recordx.data.id_det == context.record.data.key){
		           							recordx.set('value',context.record.data.value);
											recordx.commit();
		           						}
		           					});*/

		           					Ext.Ajax.request({
										url:formularioGestion.url+'set_update_only_one_formulario_detalle/',
										params:{
											cod_form_comp:formularioGestion.cod_form_comp,
											cod_form:formularioGestion.cod_form,
											id_det:context.record.data.key,
											name:'text',
											value:context.record.data.value,
											cod_padre:32
										},
										success: function(response, options){
											var res = Ext.JSON.decode(response.responseText);
											formularioGestion.getReloadGridDetalle(); 
										}
									});
					            }
				            }
			            });
	                    form.add(
	                    	{
	                            xtype: 'grid',
	                            id: formularioGestion.id+'-values-radio',
	                            margin:5,
	                            bbar:[
	                            	{
	                                    xtype:'button',
	                                    text: 'Agregar',
	                                    icon: '/images/icon/add.png',
	                                    listeners:{
	                                        beforerender: function(obj, opts){
	                                        },
	                                        click: function(obj, e){
	                                            /*rowEditing_radio.cancelEdit();
	                                            var r = Ext.create('modelo', {
	                                                value: ''
	                                            });

	                                            store_radio.insert(0, r);
	                                            rowEditing_radio.startEdit(0, 0);*/
	                                            Ext.Ajax.request({
													url:formularioGestion.url+'set_insert_chk_rdb_only_one_formulario_detalle/',
													params:{
														tipo:'R',
														cod_form_comp:formularioGestion.cod_form_comp,
														cod_form:formularioGestion.cod_form,
														name:'text',
														value:'Insertar dato',
														cod_padre:32
													},
													success: function(response, options){
														var res = Ext.JSON.decode(response.responseText);
														formularioGestion.getReloadGridDetalle(); 
													}
												});
	                                        }
	                                    }
	                                }
	                            ],
	                            flex:1,
	                            height:200,
	                            store: formularioGestion.store_x,
	                            plugins: [
	                                formularioGestion.rowEditing_x
	                            ],
	                            columnLines: true,
	                            columns:{
	                                items:[
	                                    {
	                                        text: 'Value',
	                                        dataIndex: 'value',
	                                        flex: 1,
	                                        align: 'left',
	                                        editor: {
	                                            // defaults to textfield if no xtype is supplied
	                                            allowBlank: false
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
		                                            id_menu: formularioGestion.id_menu,
		                                            icons:[
		                                                {id_serv: 3, img: 'close.png', qtip: 'Click para eliminar.', js: 'formularioGestion.setDeleteOnlyRecordRC('+record.get('key')+')'}
		                                            ]
		                                        });
		                                    }
		                                }
	                                ],
	                                defaults:{
	                                    menuDisabled: true
	                                }
	                            },
	                            selModel: {
	                                selType: 'cellmodel'
	                            },
	                            viewConfig: {
	                                stripeRows: true,
	                                enableTextSelection: false,
	                                markDirty: false
	                            },
	                            trackMouseOver: false,
	                            listeners:{
	                                afterrender: function(obj){
	                                    
	                                }
	                            }
	                        }
	                    );
						var combo=Ext.create('Ext.form.ComboBox', {
	                        fieldLabel: 'Seleccionado',
	                        id:formularioGestion.id+'_cmb_radio',
	                        store: formularioGestion.store_x,
	                        queryMode: 'local',
	                        triggerAction: 'all',
	                        valueField: 'value',
	                        displayField: 'value',
	                        emptyText: '[Seleccione un radio por defecto]',
	                        //allowBlank: false,
	                        labelAlign: 'right',
	                        labelWidth: 80,
	                        margin:5,
	                        width:'90%',
	                        anchor:'100%',
	                        //readOnly: true,
	                        listeners:{
	                            afterrender:function(obj, e){
	                                // obj.getStore().load();
	                            },
	                            select:function(obj, records, eOpts){
	                                
	                            }
	                        }
	                    });
						form.add(combo);
						form.show();
						Ext.each(formularioGestion.store_load_formuladio_detalle.data.items, function (recordx,i) {
		                	if(recordx.data.cod_form_comp == record.data.cod_form_comp){
								switch(recordx.data.name){
									case 'label':
										Ext.getCmp(formularioGestion.id+'_etiqueta').setValue(recordx.data.value);
									break;
									case 'value':
										Ext.getCmp(formularioGestion.id+'_cmb_radio').setValue(recordx.data.value);
									break;
								}
								if(recordx.data.cod_padre == 32 && recordx.data.name=='text'){
									formularioGestion.rowEditing_x.cancelEdit();
                                    var r = Ext.create('modelo', {
                                        value: recordx.data.value,
                                        value_old:recordx.data.value,
                                        key:recordx.data.id_det
                                    });

                                    formularioGestion.store_x.insert(0, r);
                                    //formularioGestion.rowEditing_x.startEdit(0, 0);
								}
		                	}
		                });
					break;
					case 'check_box':
						var etiqueta = Ext.create('Ext.form.TextField', {
	                        id:formularioGestion.id+'_etiqueta',
	                        fieldLabel: 'Etiqueta',
	                        labelAlign: 'right',
	                        labelWidth:80,
	                        margin:5,
	                        value:'',
	                        emptyText:'Ingrese un texto para la etiqueta del check',
	                        //disabled: true,
	                        width: '90%',
	                        anchor: '100%'
	                    });
						form.add(etiqueta);

						var model = Ext.define('modelo', {
			                extend: 'Ext.data.Model',
			                fields: [
			                    { name: 'value', type: 'string' },
			                    { name: 'value_old', type: 'string' },
			                    { name: 'key', type: 'string' }
			                ]
			            });
			            formularioGestion.store_x = Ext.create('Ext.data.Store',{
			                autoDestroy: true,
			                model: 'modelo',
			                proxy: {
			                    type: 'memory'
			                },
			                sorters: [{
			                    property: 'common',
			                    direction: 'ASC'
			                }]
			            });
			            
			            formularioGestion.rowEditing_x= Ext.create('Ext.grid.plugin.RowEditing', {
			                clicksToMoveEditor: 1,
			                autoCancel: false,
			                listeners: {
				                edit: function(editor, context, eOpts){
					                console.log(editor);
					                console.log(context.record.data);
					                console.log(eOpts);

					                /*Ext.each(formularioGestion.store_load_formuladio_detalle.data.items, function (recordx,i) {
		           						if(recordx.data.id_det == context.record.data.key){
		           							recordx.set('value',context.record.data.value);
											recordx.commit();
		           						}
		           					});*/

		           					Ext.Ajax.request({
										url:formularioGestion.url+'set_update_only_one_formulario_detalle/',
										params:{
											cod_form_comp:formularioGestion.cod_form_comp,
											cod_form:formularioGestion.cod_form,
											id_det:context.record.data.key,
											name:'text',
											value:context.record.data.value,
											cod_padre:36
										},
										success: function(response, options){
											var res = Ext.JSON.decode(response.responseText);
											formularioGestion.getReloadGridDetalle(); 
										}
									});
					            }
				            }
			            });
	                    form.add(
	                    	{
	                            xtype: 'grid',
	                            id: formularioGestion.id+'-values-radio',
	                            margin:5,
	                            bbar:[
	                            	{
	                                    xtype:'button',
	                                    text: 'Agregar',
	                                    icon: '/images/icon/add.png',
	                                    listeners:{
	                                        beforerender: function(obj, opts){
	                                        },
	                                        click: function(obj, e){
	                                            /*rowEditing_check.cancelEdit();
	                                            var r = Ext.create('modelo', {
	                                                value: ''
	                                            });

	                                            store_check.insert(0, r);
	                                            rowEditing_check.startEdit(0, 0);*/
	                                            Ext.Ajax.request({
													url:formularioGestion.url+'set_insert_chk_rdb_only_one_formulario_detalle/',
													params:{
														tipo:'C',
														cod_form_comp:formularioGestion.cod_form_comp,
														cod_form:formularioGestion.cod_form,
														name:'text',
														value:'Insertar dato',
														cod_padre:36
													},
													success: function(response, options){
														var res = Ext.JSON.decode(response.responseText);
														formularioGestion.getReloadGridDetalle(); 
													}
												});
	                                        }
	                                    }
	                                }
	                            ],
	                            flex:1,
	                            height:200,
	                            store: formularioGestion.store_x,
	                            plugins: [
	                                formularioGestion.rowEditing_x
	                            ],
	                            columnLines: true,
	                            columns:{
	                                items:[
	                                    {
	                                        text: 'Value',
	                                        dataIndex: 'value',
	                                        flex: 1,
	                                        align: 'left',
	                                        editor: {
	                                            // defaults to textfield if no xtype is supplied
	                                            allowBlank: false
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
		                                            id_menu: formularioGestion.id_menu,
		                                            icons:[
		                                                {id_serv: 3, img: 'close.png', qtip: 'Click para eliminar.', js: 'formularioGestion.setDeleteOnlyRecordRC('+record.get('key')+')'}
		                                            ]
		                                        });
		                                    }
		                                }
	                                ],
	                                defaults:{
	                                    menuDisabled: true
	                                }
	                            },
	                            selModel: {
	                                selType: 'cellmodel'
	                            },
	                            viewConfig: {
	                                stripeRows: true,
	                                enableTextSelection: false,
	                                markDirty: false
	                            },
	                            trackMouseOver: false,
	                            listeners:{
	                                afterrender: function(obj){
	                                    
	                                }
	                            }
	                        }
	                    );
						form.show();
						Ext.each(formularioGestion.store_load_formuladio_detalle.data.items, function (recordx,i) {
		                	if(recordx.data.cod_form_comp == record.data.cod_form_comp){
								switch(recordx.data.name){
									case 'label':
										Ext.getCmp(formularioGestion.id+'_etiqueta').setValue(recordx.data.value);
									break;
									case 'value':
										//Ext.getCmp(formularioGestion.id+'_cmb_radio').setValue(recordx.data.value);
									break;
								}
								if(recordx.data.cod_padre == 36 && recordx.data.name=='text'){
									formularioGestion.rowEditing_x.cancelEdit();
                                    var r = Ext.create('modelo', {
                                        value: recordx.data.value,
                                        value_old:recordx.data.value,
                                        key:recordx.data.id_det
                                    });

                                    formularioGestion.store_x.insert(0, r);
                                    //formularioGestion.rowEditing_x.startEdit(0, 0);
								}
		                	}
		                });
					break;
					case 'audio':
					break;
				}
			},
			getFormMant:function(ID,nombre,descripcion,estado){
				var myData = [
				    ['1','Activo'],
				    ['0','Inactivo']
				];
				var store_estado = Ext.create('Ext.data.ArrayStore', {
			        storeId: 'estado',
			        autoLoad: true,
			        data: myData,
			        fields: ['code', 'name']
			    });

				Ext.create('Ext.window.Window',{
	                id:formularioGestion.id+'-win-form',
	                plain: true,
	                title:'Formulario',
	                icon: '/images/icon/edit.png',
	                height: 200,
	                width: 450,
	                resizable:false,
	                modal: true,
	                border:false,
	                closable:true,
	                padding:20,
	                items:[
	                	{
	                        xtype: 'textfield',
	                        id:formularioGestion.id+'-form-nombre',
	                        fieldLabel: 'Nombre',
	                        //disabled:true,
	                        labelWidth:90,
	                        labelAlign:'right',
	                        width:'100%',
	                        anchor:'100%',
	                        value:nombre
	                    },
	                    {
	                        xtype: 'textfield',
	                        id:formularioGestion.id+'-form-descripcion',
	                        fieldLabel: 'Descripción',
	                        labelWidth:90,
	                        labelAlign:'right',
	                        width:'100%',
	                        anchor:'100%',
	                        value:descripcion
	                    },
	                    {
	                        xtype:'combo',
	                        fieldLabel: 'Estado',
	                        id:formularioGestion.id+'-form-cmb-estado',
	                        store: store_estado,
	                        queryMode: 'local',
	                        triggerAction: 'all',
	                        valueField: 'code',
	                        displayField: 'name',
	                        emptyText: '[Seleccione]',
	                        labelAlign:'right',
	                        //allowBlank: false,
	                        labelWidth: 90,
	                        width:'100%',
	                        anchor:'100%',
	                        //readOnly: true,
	                        listeners:{
	                            afterrender:function(obj, e){
	                                // obj.getStore().load();
	                                if(ID==0){
	                                	obj.setValue(1);
	                                }else{
	                                	obj.setValue(estado);
	                                }
	                            },
	                            select:function(obj, records, eOpts){
	                    
	                            }
	                        }
	                    }
	                ],
	                bbar:[       
	                    '->',
	                    '-',
	                    {
	                        xtype:'button',
	                        text: 'Guardar',
	                        icon: '/images/icon/save.png',
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
	                            	formularioGestion.setSaveRecordForm(ID);
	                            }
	                        }
	                    },
	                    '-',
	                    {
	                        xtype:'button',
	                        text: 'Salir',
	                        icon: '/images/icon/get_back.png',
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
	                                Ext.getCmp(formularioGestion.id+'-win-form').close();
	                            }
	                        }
	                    },
	                    '-'
	                ],
	                listeners:{
	                    'afterrender':function(obj, e){ 
	                        //panel_asignar_gestion.getDatos();
	                    },
	                    'close':function(){
	                        //if(panel_asignar_gestion.guarda!=0)gestion_devolucion.buscar();
	                    }
	                }
	            }).show().center();
			},
			setSaveRecordForm:function(ID){
				var nombre = Ext.getCmp(formularioGestion.id+'-form-nombre').getValue();
				var descripcion = Ext.getCmp(formularioGestion.id+'-form-descripcion').getValue();
				var estado = Ext.getCmp(formularioGestion.id+'-form-cmb-estado').getValue();

				if(nombre==''){
	               global.Msg({msg:"Ingrese Un nombre para el formulario.",icon:2,fn:function(){}});
	                return;
	            }
	            if(estado==''){
	               global.Msg({msg:"Seleccione un estado para el formulario.",icon:2,fn:function(){}});
	                return;
	            }
				global.Msg({
					msg:'¿Desea guardar el registro?',
					icon:3,
					buttons:3,
					fn:function(obj){
						//console.log(obj);
						if (obj == 'yes'){
							Ext.Ajax.request({
								url:formularioGestion.url+'set_formulario/',
								params:{
									vp_op:ID==0?'I':'U',
									cod_form:ID,
									nombre:nombre,
									descripcion:descripcion,
									estado:estado
								},
								success: function(response, options){
									var res = Ext.JSON.decode(response.responseText);
									if (res.error == 0 ){
										global.Msg({
											msg:res.data,
											icon:1,
											fn:function(){
												formularioGestion.getReloadGridformularioGestion('');
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
				
			}
		}
		Ext.onReady(formularioGestion.init,formularioGestion);
	}else{
		tab.setActiveTab(formularioGestion.id+'-tab');
	}
</script>