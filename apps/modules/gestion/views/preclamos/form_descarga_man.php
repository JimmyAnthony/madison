<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('descargaManis-tab')){
    var descargaManis = {
        id: 'descargaManis',
        url: '/gestion/preclamos/',
        id_menu: '<?php echo $p["id_menu"];?>',
        vp_chk_id: 0,
        vp_reclamo: parseInt('<?php echo $p["vp_reclamo"];?>'),
        per_aud_id: 0,
        vp_id_viv: 0,
        dni: '',
        nombre: '',
        telefono: '',
        window: parseInt('<?php echo $p["window"];?>'),
        init:function(){

            var panel = Ext.create('Ext.form.Panel',{
                id: descargaManis.id + '-form',
                border: false,
                layout: 'border',
                defaults:{
                    border: false
                },
                tbar:[
                    {
                        text: 'Retornar',
                        icon: '/images/icon/get_back.png',
                        listeners:{
                            click: function(obj, e){
                                Ext.getCmp(descargaManis.id+'-tab').close();
                            }
                        }
                    },
                    '-',
                    {
                        text: 'Grabar',
                        id: descargaManis.id + '-btn_grabar',
                        icon: '/images/icon/save.png',
                        listeners:{
                            click: function(obj, e){
                                descargaManis.save();
                            }
                        }
                    }/*,
                    {
                        text: 'Cancelar',
                        id: descargaManis.id + '-btn_cancelar',
                        icon: '/images/icon/cancel.png',
                        listeners:{
                            click: function(obj, e){
                                Ext.getCmp(descargaManis.id + '-tab').close();
                            }
                        }
                    }*/
                ],
                items:[
                    {
                        region: 'west',
                        width: 750,
                        defaults:{
                            style:{
                                margin: '1px'
                            },
                            border: false
                        },
                        autoScroll: true,
                        items:[
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                items:[
                                    {
                                        xtype: 'textfield',
                                        id: descargaManis.id + '-barra',
                                        fieldLabel: 'Barra',
                                        width: 170,
                                        labelWidth: 50,
                                        labelAlign: 'right',
                                        enableKeyEvents: true,
                                        maxLength: 10,
                                        enforceMaxLength: true,
                                        listeners:{
                                            keyup: function(obj, e, opts){
                                                obj.setValue(Ext.util.Format.uppercase(obj.getValue()));
                                            },
                                            keypress: function(obj, e, opts){
                                                if (e.getKey() == 13){
                                                    var barra = Ext.getCmp(descargaManis.id + '-barra').getValue();
                                                    descargaManis.get_barra(barra);
                                                }
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'textfield',
                                        id: descargaManis.id + '-auditor',
                                        fieldLabel: 'Auditor',
                                        readOnly: true,
                                        flex: 1,
                                        labelWidth: 50,
                                        labelAlign: 'right'
                                    },
                                    {
                                        xtype: 'textfield',
                                        id: descargaManis.id + '-id_manifiesto',
                                        fieldLabel: 'Id Manifiesto',
                                        readOnly: true,
                                        width: 150,
                                        labelWidth: 80,
                                        labelAlign: 'right'
                                    },
                                    {
                                        xtype: 'textfield',
                                        id: descargaManis.id + '-fecha',
                                        fieldLabel: 'Fecha',
                                        readOnly: true,
                                        width: 130,
                                        labelWidth: 50,
                                        labelAlign: 'right'
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                id: descargaManis.id + '-fdreclamo',
                                listeners:{
                                    afterrender: function(obj){
                                        descargaManis.get_html_datos_reclamos();
                                    }
                                }
                            },
                            {
                                xtype: 'panel',
                                id: descargaManis.id + '-p_auditoria',
                                layout: 'hbox',
                                disabled: true,
                                items:[
                                    {
                                        xtype: 'datefield',
                                        id: descargaManis.id + '-f_auditoria',
                                        fieldLabel: 'Fecha de auditoria',
                                        disabled: false,
                                        labelWidth: 110,
                                        width: 210,
                                        enableKeyEvents: true,
                                        listeners:{
                                            keypress: function(obj, e, opts){
                                                if (e.getKey() == 13){
                                                    Ext.getCmp(descargaManis.id + '-h_auditoria').focus(true, 500);
                                                }
                                            },
                                            select: function(field, value, eOpts){
                                                Ext.getCmp(descargaManis.id + '-h_auditoria').focus(true, 500);
                                                Ext.getCmp(descargaManis.id + '-h_auditoria').expand();
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'timefield',
                                        id: descargaManis.id + '-h_auditoria',
                                        hideLabel: true,
                                        disabled: false,
                                        style:{
                                            marginLeft: '2px'
                                        },
                                        width: 60,
                                        increment: 30,
                                        format:'H:i'
                                    },
                                    {
                                        xtype: 'radiogroup',
                                        id: descargaManis.id + '-rbtn_g_resultado',
                                        vertical: true,
                                        columns: 2,
                                        fieldLabel: 'Resultado',
                                        labelWidth: 70,
                                        labelAlign: 'right',
                                        disabled: false,
                                        items:[
                                            {boxLabel: 'Procedente', name: descargaManis.id + '-rbtn_resul', id: descargaManis.id + '-rbtn_resul_01',inputValue: '1', width: 100},
                                            {boxLabel: 'No procedente',name: descargaManis.id + '-rbtn_resul', id: descargaManis.id + '-rbtn_resul_02', inputValue: '2', width: 100}
                                        ],
                                        listeners:{
                                            change: function(obj, newValue, oldValue){
                                                var op = parseInt(newValue['descargaManis-rbtn_resul']);
                                                descargaManis.vp_chk_id = op;
                                                switch(op){
                                                    case 1:
                                                        Ext.getCmp(descargaManis.id + '-f_entrevistado').disable();
                                                        Ext.getCmp(descargaManis.id + '-f_dvivienda').disable();
                                                        Ext.getCmp(descargaManis.id + '-f_rauditoria').enable();
                                                        Ext.getCmp(descargaManis.id + '-f_rdocumento').enable();
                                                        Ext.getCmp(descargaManis.id + '-p_observaciones').enable();
                                                    break;
                                                    case 2:
                                                        Ext.getCmp(descargaManis.id + '-f_entrevistado').enable();
                                                        Ext.getCmp(descargaManis.id + '-f_dvivienda').enable();
                                                        Ext.getCmp(descargaManis.id + '-f_rauditoria').enable();
                                                        Ext.getCmp(descargaManis.id + '-f_rdocumento').enable();
                                                        Ext.getCmp(descargaManis.id + '-p_observaciones').enable();

                                                        Ext.getCmp(descargaManis.id + '-parentesco').focus(true, 500);
                                                        Ext.getCmp(descargaManis.id + '-parentesco').expand();
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                ]
                            },
                            {
                                xtype: 'fieldset',
                                title: 'Entrevistado',
                                id: descargaManis.id + '-f_entrevistado',
                                style:{
                                    margin: '1px',
                                    padding: '1px'
                                },
                                border: true,
                                disabled: false,
                                defaults:{
                                    border: false
                                },
                                items:[
                                    {
                                        xtype: 'panel',
                                        layout: 'hbox',
                                        defaults:{
                                            style:{
                                                margin: '2px'
                                            },
                                            labelAlign: 'top'
                                        },
                                        items:[
                                            {
                                                xtype: 'combo',
                                                id: descargaManis.id + '-parentesco',
                                                fieldLabel: 'Parentesco',
                                                width: 150,
                                                store: Ext.create('Ext.data.Store',{
                                                    fields:[
                                                        {name: 'descripcion', type: 'string'},
                                                        {name: 'id_elemento', type: 'int'},
                                                        {name: 'des_corto', type: 'string'}
                                                    ],
                                                    proxy:{
                                                        type: 'ajax',
                                                        url: descargaManis.url + 'get_scm_tabla_detalle/',
                                                        reader:{
                                                            type: 'json',
                                                            rootProperty: 'data'
                                                        }
                                                    }
                                                }),
                                                queryMode: 'local',
                                                triggerAction: 'all',
                                                valueField: 'id_elemento',
                                                displayField: 'descripcion',
                                                emptyText: '[ Seleccione ]',
                                                listeners:{
                                                    afterrender: function(obj){
                                                        obj.getStore().load({
                                                            params:{
                                                                vp_tab_id: 'TPA',
                                                                vp_shipper: 0
                                                            },
                                                            callback: function(){

                                                            }
                                                        });
                                                    },
                                                    select: function(combo, records, eOpts){
                                                        if (parseInt(records[0].get('id_data')) == 1){
                                                            Ext.Ajax.request({
                                                                url: descargaManis.url + 'get_scm_reclamo_qry_persona_aud/',
                                                                params:{
                                                                    vp_reclamo: descargaManis.vp_reclamo,
                                                                    vp_tpar: '1',
                                                                    vp_dni: ''
                                                                },
                                                                success: function(response, options){
                                                                    var res = Ext.JSON.decode(response.responseText);
                                                                    // console.log(res);
                                                                    Ext.getCmp(descargaManis.id + '-dni').setValue(res.dni);
                                                                    Ext.getCmp(descargaManis.id + '-nombre').setValue(res.nombre);
                                                                    Ext.getCmp(descargaManis.id + '-n_telefono').setValue(res.telefono);

                                                                    descargaManis.per_aud_id = parseInt(res.per_aud_id);
                                                                    descargaManis.dni = Ext.util.Format.trim(res.dni);
                                                                    descargaManis.nombre = Ext.util.Format.trim(res.nombre);
                                                                    descargaManis.telefono = Ext.util.Format.trim(res.telefono);

                                                                    Ext.getCmp(descargaManis.id + '-n_suministro').focus(true, 500);
                                                                }
                                                            });
                                                        }else{

                                                        }
                                                    }
                                                }
                                            },
                                            {
                                                xtype: 'textfield',
                                                id: descargaManis.id + '-dni',
                                                fieldLabel: 'DNI',
                                                width: 100,
                                                enableKeyEvents: true,
                                                maxLength: 12,
                                                enforceMaxLength: true,
                                                listeners:{
                                                    keypress: function(obj, e, opts){
                                                        if (e.getKey() == 13){
                                                            var vp_dni = Ext.getCmp(descargaManis.id + '-dni').getValue();
                                                            Ext.Ajax.request({
                                                                url: descargaManis.url + 'get_scm_reclamo_qry_persona_aud/',
                                                                params:{
                                                                    vp_reclamo: descargaManis.vp_reclamo,
                                                                    vp_tpar: '1',
                                                                    vp_dni: vp_dni
                                                                },
                                                                success: function(response, options){
                                                                    var res = Ext.JSON.decode(response.responseText);
                                                                    // console.log(res);
                                                                    if (parseInt(res.error_sql) >= 0){
                                                                        Ext.getCmp(descargaManis.id + '-dni').setValue(res.dni);
                                                                        Ext.getCmp(descargaManis.id + '-nombre').setValue(res.nombre);
                                                                        Ext.getCmp(descargaManis.id + '-n_telefono').setValue(res.telefono);
                                                                        descargaManis.per_aud_id = res.per_aud_id;
                                                                        Ext.getCmp(descargaManis.id + '-n_suministro').focus(true, 500);
                                                                    }else{
                                                                        global.Msg({
                                                                            msg: res.error_info,
                                                                            icon: 0,
                                                                            buttons: 1,
                                                                            fn: function(btn){
                                                                                
                                                                            }
                                                                        });
                                                                    }
                                                                }
                                                            });
                                                        }
                                                    }
                                                }
                                            },
                                            {
                                                xtype: 'textfield',
                                                id: descargaManis.id + '-nombre',
                                                fieldLabel: 'Nombre',
                                                flex: 1,
                                                enableKeyEvents: true,
                                                maxLength: 80,
                                                enforceMaxLength: true,
                                                listeners:{
                                                    keypress: function(obj, e, opts){
                                                        if (e.getKey() == 13){
                                                            Ext.getCmp(descargaManis.id + '-n_telefono').focus(true, 500);
                                                        }
                                                    }
                                                }
                                            },
                                            {
                                                xtype: 'textfield',
                                                id: descargaManis.id + '-n_telefono',
                                                fieldLabel: 'Nº Teléfono',
                                                width: 100,
                                                enableKeyEvents: true,
                                                maxLength: 16,
                                                enforceMaxLength: true,
                                                listeners:{
                                                    keypress: function(obj, e, opts){
                                                        if (e.getKey() == 13){

                                                        }
                                                    }
                                                }
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                xtype: 'fieldset',
                                title: 'Datos de la vivienda',
                                id: descargaManis.id + '-f_dvivienda',
                                style:{
                                    margin: '1px',
                                    padding: '1px'
                                },
                                defaults:{
                                    style:{
                                        margin: '2px'
                                    },
                                    border: false
                                },
                                // disabled: true,
                                border: true,
                                items:[
                                    {
                                        xtype: 'panel',
                                        layout: 'hbox',
                                        items:[
                                            {
                                                xtype: 'textfield',
                                                id: descargaManis.id + '-n_suministro',
                                                fieldLabel: 'Nº Suministro',
                                                width: 180,
                                                labelWidth: 80,
                                                enableKeyEvents: true,
                                                maxLength: 16,
                                                enforceMaxLength: true,
                                                listeners:{
                                                    keypress: function(obj, e, opts){
                                                        if (e.getKey() == 13){
                                                            Ext.getCmp(descargaManis.id + '-tipo_vivienda').focus(true, 500);
                                                            Ext.getCmp(descargaManis.id + '-tipo_vivienda').expand();
                                                        }
                                                    }
                                                }
                                            },
                                            {
                                                xtype: 'combo',
                                                id: descargaManis.id + '-tipo_vivienda',
                                                fieldLabel: 'Tipo vivienda',
                                                labelWidth: 80,
                                                labelAlign: 'right',
                                                width: 200,
                                                store: Ext.create('Ext.data.Store',{
                                                    fields:[
                                                        {name: 'descripcion', type: 'string'},
                                                        {name: 'id_elemento', type: 'int'},
                                                        {name: 'des_corto', type: 'string'}
                                                    ],
                                                    proxy:{
                                                        type: 'ajax',
                                                        url: descargaManis.url + 'get_scm_tabla_detalle/',
                                                        reader:{
                                                            type: 'json',
                                                            rootProperty: 'data'
                                                        }
                                                    }
                                                }),
                                                queryMode: 'local',
                                                triggerAction: 'all',
                                                valueField: 'id_elemento',
                                                displayField: 'descripcion',
                                                emptyText: '[ Seleccione ]',
                                                listeners:{
                                                    afterrender: function(obj){
                                                        obj.getStore().load({
                                                            params:{
                                                                vp_tab_id: 'TVI',
                                                                vp_shipper: 0
                                                            },
                                                            callback: function(){

                                                            }
                                                        });
                                                    },
                                                    select: function(combo, records, eOpts){
                                                        Ext.getCmp(descargaManis.id + '-pisos').focus(true, 500);
                                                    }
                                                }
                                            },
                                            {
                                                xtype: 'numberfield',
                                                id: descargaManis.id + '-pisos',
                                                fieldLabel: 'Pisos',
                                                labelWidth: 50,
                                                width: 100,
                                                labelAlign: 'right',
                                                hideTrigger: true,
                                                minValue: 0,
                                                allowDecimals: false,
                                                value: 0,
                                                enableKeyEvents: true,
                                                listeners:{
                                                    keypress: function(obj, e, opts){
                                                        if (e.getKey() == 13){
                                                            Ext.getCmp(descargaManis.id + '-c_fachada').focus(true, 500);
                                                        }
                                                    }
                                                }
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'panel',
                                        layout: 'hbox',
                                        items:[
                                            {
                                                xtype: 'textfield',
                                                id: descargaManis.id + '-c_fachada',
                                                fieldLabel: 'Color fachada',
                                                width: 350,
                                                labelWidth: 80,
                                                maxLength: 30,
                                                enforceMaxLength: true,
                                                enableKeyEvents: true,
                                                listeners:{
                                                    keypress: function(obj, e, opts){
                                                        if (e.getKey() == 13){
                                                            Ext.getCmp(descargaManis.id + '-tipo_puerta').focus(true, 500);
                                                            Ext.getCmp(descargaManis.id + '-tipo_puerta').expand();
                                                        }
                                                    }
                                                }
                                            },
                                            {
                                                xtype: 'combo',
                                                id: descargaManis.id + '-tipo_puerta',
                                                fieldLabel: 'Puerta exterior',
                                                labelWidth: 110,
                                                labelAlign: 'right',
                                                width: 220,
                                                store: Ext.create('Ext.data.Store',{
                                                    fields:[
                                                        {name: 'descripcion', type: 'string'},
                                                        {name: 'id_elemento', type: 'int'},
                                                        {name: 'referencia', type: 'string'}
                                                    ],
                                                    proxy:{
                                                        type: 'ajax',
                                                        url: descargaManis.url + 'get_scm_tabla_detalle/',
                                                        reader:{
                                                            type: 'json',
                                                            rootProperty: 'data'
                                                        }
                                                    }
                                                }),
                                                queryMode: 'local',
                                                triggerAction: 'all',
                                                valueField: 'id_elemento',
                                                displayField: 'descripcion',
                                                emptyText: '[ Seleccione ]',
                                                listeners:{
                                                    afterrender: function(obj){
                                                        obj.getStore().load({
                                                            params:{
                                                                vp_tab_id: 'TPU',
                                                                vp_shipper: 0
                                                            },
                                                            callback: function(){

                                                            }
                                                        });
                                                    }
                                                }
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'panel',
                                        layout: 'hbox',
                                        items:[
                                            {
                                                xtype: 'checkboxfield',
                                                id: descargaManis.id + '-chk_g_venta',
                                                vertical: true,
                                                fieldLabel: 'Ventana(En fachada)',
                                                labelWidth: 130,
                                                labelAlign: 'left',
                                                width: 180,
                                                checked: false,
                                                boxLabel: 'No',
                                                listeners:{
                                                    change: function(obj, newValue, oldValue){
                                                        if (newValue)
                                                            Ext.getCmp(descargaManis.id + '-chk_g_venta').getEl().down('label.x-form-cb-label').update('Si')
                                                        else
                                                            Ext.getCmp(descargaManis.id + '-chk_g_venta').getEl().down('label.x-form-cb-label').update('No')
                                                    }
                                                }
                                            },
                                            {
                                                xtype: 'checkboxgroup',
                                                id: descargaManis.id + '-chk_g_vivienda',
                                                vertical: true,
                                                columns: 3,
                                                fieldLabel: 'Vivienda cuenta con',
                                                labelWidth: 120,
                                                labelAlign: 'right',
                                                width: 360,
                                                items:[
                                                    {boxLabel: 'Buzón', name: descargaManis.id + '-chk_vivienda', id: descargaManis.id + '-chk_vivienda_01', inputValue: '1', width: 60},
                                                    {boxLabel: 'Timbre', name: descargaManis.id + '-chk_vivienda', id: descargaManis.id + '-chk_vivienda_02', inputValue: '2', width: 60},
                                                    {boxLabel: 'Intercomunicador', name: descargaManis.id + '-chk_vivienda', id: descargaManis.id + '-chk_vivienda_03', inputValue: '3', width: 120}
                                                ],
                                                listeners:{
                                                    change: function(obj, newValue, oldValue){
                                                        // console.log(newValue['descargaManis-chk_vivienda']);
                                                    }
                                                }
                                            },
                                            {
                                                xtype: 'checkboxfield',
                                                id: descargaManis.id + '-chk_g_bpbz',
                                                vertical: true,
                                                fieldLabel: 'BP / BZ',
                                                labelWidth: 70,
                                                labelAlign: 'right',
                                                width: 190,
                                                checked: false,
                                                boxLabel: 'No',
                                                listeners:{
                                                    change: function(obj, newValue, oldValue){
                                                        if (newValue)
                                                            Ext.getCmp(descargaManis.id + '-chk_g_bpbz').getEl().down('label.x-form-cb-label').update('Si')
                                                        else
                                                            Ext.getCmp(descargaManis.id + '-chk_g_bpbz').getEl().down('label.x-form-cb-label').update('No')
                                                    }
                                                }
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'panel',
                                        layout: 'hbox',
                                        items:[
                                            {
                                                xtype: 'combo',
                                                id: descargaManis.id + '-d_correcta',
                                                fieldLabel: 'Dirección correcta',
                                                width: 270,
                                                labelWidth: 110,
                                                store: Ext.create('Ext.data.Store',{
                                                    fields:[
                                                        {name: 'descripcion', type: 'string'},
                                                        {name: 'id_elemento', type: 'int'},
                                                        {name: 'referencia', type: 'string'}
                                                    ],
                                                    proxy:{
                                                        type: 'ajax',
                                                        url: descargaManis.url + 'get_scm_tabla_detalle/',
                                                        reader:{
                                                            type: 'json',
                                                            rootProperty: 'data'
                                                        }
                                                    }
                                                }),
                                                queryMode: 'local',
                                                triggerAction: 'all',
                                                valueField: 'id_elemento',
                                                displayField: 'descripcion',
                                                emptyText: '[ Seleccione ]',
                                                listeners:{
                                                    afterrender: function(obj){
                                                        obj.getStore().load({
                                                            params:{
                                                                vp_tab_id: 'RDI',
                                                                vp_shipper: 0
                                                            },
                                                            callback: function(){

                                                            }
                                                        });
                                                    },
                                                    select: function(combo, records, eOpts){
                                                        Ext.getCmp(descargaManis.id + '-observaciones').focus(true, 500);
                                                    }
                                                }
                                            },
                                            {
                                                xtype: 'textfield',
                                                id: descargaManis.id + '-observaciones',
                                                fieldLabel: 'Observaciones',
                                                labelWidth: 100,
                                                labelAlign: 'right',
                                                flex: 1,
                                                maxLength: 80,
                                                enforceMaxLength: true
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                xtype: 'fieldset',
                                title: 'Resultado de la auditoria',
                                id: descargaManis.id + '-f_rauditoria',
                                style:{
                                    margin: '1px',
                                    padding: '1px'
                                },
                                defaults:{
                                    style:{
                                        margin: '1px'
                                    },
                                    border: false
                                },
                                disabled: false,
                                border: true,
                                items:[
                                    {
                                        xtype: 'panel',
                                        layout: 'hbox',
                                        items:[
                                            {
                                                xtype: 'checkboxgroup',
                                                id: descargaManis.id + '-chk_g_rauditoria',
                                                vertical: true,
                                                columns: 3,
                                                hideLabel: true,
                                                width: 360,
                                                defaults:{
                                                    margin: '1px'
                                                },
                                                items:[
                                                    {boxLabel: 'No llegó EECC (Doc.)', name: descargaManis.id + '-chk_auditoria', id: descargaManis.id + '-chk_auditoria_01', inputValue: '1', width: 220},
                                                    {boxLabel: 'Reclamo por la facturación', name: descargaManis.id + '-chk_auditoria', id: descargaManis.id + '-chk_auditoria_02', inputValue: '2', width: 220},
                                                    {boxLabel: 'No brindó información', name: descargaManis.id + '-chk_auditoria', id: descargaManis.id + '-chk_auditoria_03', inputValue: '3', width: 220},
                                                    {boxLabel: 'Llegó vencido', name: descargaManis.id + '-chk_auditoria', id: descargaManis.id + '-chk_auditoria_04', inputValue: '4', width: 220},
                                                    {boxLabel: 'Llegó al vecino', name: descargaManis.id + '-chk_auditoria', id: descargaManis.id + '-chk_auditoria_05', inputValue: '5', width: 220},
                                                    {boxLabel: 'No realizó ningún reclamo', name: descargaManis.id + '-chk_auditoria', id: descargaManis.id + '-chk_auditoria_06', inputValue: '6', width: 220},
                                                    {boxLabel: 'Si llegó', name: descargaManis.id + '-chk_auditoria', id: descargaManis.id + '-chk_auditoria_07', inputValue: '7', width: 220},
                                                    {boxLabel: 'Desconoce motivo del reclamo', name: descargaManis.id + '-chk_auditoria', id: descargaManis.id + '-chk_auditoria_08', inputValue: '8', width: 220},
                                                    {boxLabel: 'Llegó abierto', name: descargaManis.id + '-chk_auditoria', id: descargaManis.id + '-chk_auditoria_09', inputValue: '9', width: 220}
                                                ],
                                                listeners:{
                                                    change: function(obj, newValue, oldValue){
                                                        // console.log(newValue['descargaManis-chk_auditoria']);
                                                    }
                                                }
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                xtype: 'panel',
                                layout: 'hbox',
                                items:[
                                    {
                                        xtype: 'fieldset',
                                        id: descargaManis.id + '-f_rdocumento',
                                        title: 'Como recepciona el documento',
                                        style:{
                                            margin: '1px',
                                            padding: '1px'
                                        },
                                        defaults:{
                                            style:{
                                                margin: '0px'
                                            },
                                            border: false
                                        },
                                        disabled: false,
                                        border: true,
                                        // width: 610,
                                        flex: 1,
                                        items:[
                                            {
                                                xtype: 'panel',
                                                layout: 'hbox',
                                                items:[
                                                    {
                                                        xtype: 'checkboxgroup',
                                                        id: descargaManis.id + '-chk_g_recepciona',
                                                        vertical: true,
                                                        columns: 3,
                                                        hideLabel: true,
                                                        width: 360,
                                                        defaults:{
                                                            margin: '1px'
                                                        },
                                                        items:[
                                                            {boxLabel: 'Solo por medio de sello', name: descargaManis.id + '-chk_recepciona', id: descargaManis.id + '-chk_recepciona_01', inputValue: '1', width: 210},
                                                            {boxLabel: 'Con sello y/o datos', name: descargaManis.id + '-chk_recepciona', id: descargaManis.id + '-chk_recepciona_02', inputValue: '2', width: 210},
                                                            {boxLabel: 'Predio con inquilinos', name: descargaManis.id + '-chk_recepciona', id: descargaManis.id + '-chk_recepciona_03', inputValue: '3', width: 210},
                                                            {boxLabel: 'Portero no recibió documento', name: descargaManis.id + '-chk_recepciona', id: descargaManis.id + '-chk_recepciona_04', inputValue: '4', width: 210},
                                                            {boxLabel: 'No hay portero', name: descargaManis.id + '-chk_recepciona', id: descargaManis.id + '-chk_recepciona_05', inputValue: '5', width: 210},
                                                            {boxLabel: 'No hay acceso al interior', name: descargaManis.id + '-chk_recepciona', id: descargaManis.id + '-chk_recepciona_06', inputValue: '6', width: 210}
                                                        ],
                                                        listeners:{
                                                            change: function(obj, newValue, oldValue){
                                                                // console.log(newValue['descargaManis-chk_recepciona']);
                                                            }
                                                        }
                                                    }
                                                ]
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'fieldset',
                                        id: descargaManis.id + '-f_dcargo',
                                        title: 'Datos del cargo',
                                        style:{
                                            margin: '2px',
                                            padding: '1px'
                                        },
                                        defaults:{
                                            style:{
                                                margin: '0px'
                                            },
                                            border: false
                                        },
                                        disabled: false,
                                        border: true,
                                        width: 110,
                                        items:[
                                            {
                                                xtype: 'panel',
                                                layout: 'hbox',
                                                items:[
                                                    {
                                                        xtype: 'checkboxfield',
                                                        id: descargaManis.id + '-chk_d_cargo',
                                                        vertical: true,
                                                        fieldLabel: 'Datos coinciden',
                                                        labelWidth: 110,
                                                        labelAlign: 'left',
                                                        width: 160,
                                                        checked: false,
                                                        boxLabel: 'No',
                                                        labelAlign: 'top',
                                                        listeners:{
                                                            change: function(obj, newValue, oldValue){
                                                                if (newValue)
                                                                    Ext.getCmp(descargaManis.id + '-chk_d_cargo').getEl().down('label.x-form-cb-label').update('Si')
                                                                else
                                                                    Ext.getCmp(descargaManis.id + '-chk_d_cargo').getEl().down('label.x-form-cb-label').update('No')
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
                                xtype: 'panel',
                                id: descargaManis.id + '-p_observaciones',
                                defaults:{
                                    border: false
                                },
                                items:[
                                    {
                                        xtype: 'panel',
                                        layout: 'hbox',
                                        items:[
                                            {
                                                xtype: 'textarea',
                                                id: descargaManis.id + '-ocomentarios',
                                                fieldLabel: 'Observaciones y comentarios',
                                                disabled: false,
                                                labelWidth: 100,
                                                height: 70,
                                                flex: 1
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        region: 'center',
                        border: false,
                        layout: 'border',
                        items:[
                            {
                                region: 'north',
                                id: descargaManis.id + '-pimagen-cargo',
                                title: 'Tiene cargo de mensajero.',
                                height: 300,
                                collapsible: true,
                                collapsed: true,
                                html:'<div id="viewer1"></div>',
                                listeners:{
                                    afterrender: function(obj){
                                        
                                    }
                                }
                            },
                            {
                                region: 'center',
                                border: true,
                                html:'<div id="viewer"></div>',
                                listeners:{
                                    afterrender: function(obj){

                                    }
                                }
                            }
                        ]
                    }
                ],
                listeners:{
                    afterrender: function(obj){
                        if (descargaManis.vp_reclamo == 0)
                            Ext.getCmp(descargaManis.id + '-barra').focus(true, 500);
                        else{
                            descargaManis.get_barra(descargaManis.vp_reclamo);
                        }
                    }
                }
            });

            tab.add({
                id: descargaManis.id+'-tab',
                border: false,
                autoScroll: true,
                closable: true,
                layout:{
                    type: 'fit'
                },
                items:[
                    panel
                ],
                listeners:{
                    beforerender: function(obj, opts){
                        Ext.getCmp(auditando.id+'-tab').disable();
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                    },
                    beforeclose: function(obj, opts){
                        Ext.getCmp(auditando.id+'-tab').enable();
                        Ext.getCmp(inicio.id+'-tabContent').setActiveTab(Ext.getCmp(auditando.id+'-tab'))
                    }
                }
            }).show();
        },
        save: function(){

            var vp_reclamo = descargaManis.vp_reclamo;

            var vp_fec_aud = Ext.getCmp(descargaManis.id + '-f_auditoria').getRawValue();
            var vp_hor_aud = Ext.getCmp(descargaManis.id + '-h_auditoria').getRawValue();
            var vp_chk_id = descargaManis.vp_chk_id;
            var vp_resultado = descargaManis.get_resultado_chk();
            var vp_recepcion = descargaManis.get_recepcion_chk();
            var vp_acuse = Ext.getCmp(descargaManis.id + '-chk_d_cargo').getValue() ? 1 : 0;
            var vp_observa = Ext.getCmp(descargaManis.id + '-ocomentarios').getValue();
            
            var vp_tpar = Ext.getCmp(descargaManis.id + '-parentesco').getValue();
            var vp_nrodni = Ext.util.Format.trim(Ext.getCmp(descargaManis.id + '-dni').getValue());
            var vp_nombre = Ext.util.Format.trim(Ext.getCmp(descargaManis.id + '-nombre').getValue());
            var vp_telefono = Ext.util.Format.trim(Ext.getCmp(descargaManis.id + '-n_telefono').getValue());

            var vp_id_per = descargaManis.get_per_id(vp_nrodni, vp_nombre, vp_telefono);

            var vp_id_viv = descargaManis.vp_id_viv;
            var vp_numsum = Ext.getCmp(descargaManis.id + '-n_suministro').getValue();
            var vp_tipviv = Ext.getCmp(descargaManis.id + '-tipo_vivienda').getValue();
            vp_tipviv = vp_tipviv == null ? 0 : vp_tipviv;
            var vp_pisos = Ext.getCmp(descargaManis.id + '-pisos').getValue();
            var vp_color = Ext.util.Format.trim(Ext.getCmp(descargaManis.id + '-c_fachada').getValue());
            var vp_tpuerta = Ext.getCmp(descargaManis.id + '-tipo_puerta').getValue();
            vp_tpuerta = vp_tpuerta == null ? 0 : vp_tpuerta;
            var vp_ventana = Ext.getCmp(descargaManis.id + '-chk_g_venta').getValue() ? 1 : 0;
            var vp_buzon = Ext.getCmp(descargaManis.id + '-chk_vivienda_01').getValue() ? 1 : 0;
            var vp_timbre = Ext.getCmp(descargaManis.id + '-chk_vivienda_02').getValue() ? 1 : 0;
            var vp_intercom = Ext.getCmp(descargaManis.id + '-chk_vivienda_03').getValue() ? 1 : 0;
            var vp_bz_bp = Ext.getCmp(descargaManis.id + '-chk_g_bpbz').getValue() ? 1 : 0;
            var vp_dir_ok = Ext.getCmp(descargaManis.id + '-d_correcta').getValue();
            vp_dir_ok = vp_dir_ok == null ? 0 : vp_dir_ok;
            var vp_observ = Ext.getCmp(descargaManis.id + '-observaciones').getValue();

            if (parseInt(vp_chk_id) == 2){
                if (vp_tipviv == 0){
                    global.Msg({
                        msg: 'Debe de seleccionar un tipo de vivienda!',
                        icon: 2,
                        buttons: 1,
                        fn: function(btn){
                            Ext.getCmp(descargaManis.id + '-tipo_vivienda').focus(true, 500);
                            Ext.getCmp(descargaManis.id + '-tipo_vivienda').expand();
                        }
                    });
                    return false;
                }
                if (vp_color == ''){
                    global.Msg({
                        msg: 'Debe de ingresar el color de fachada!',
                        icon: 2,
                        buttons: 1,
                        fn: function(btn){
                            Ext.getCmp(descargaManis.id + '-c_fachada').focus(true, 500);
                        }
                    });
                    return false;
                }
                if (vp_dir_ok == 0){
                    global.Msg({
                        msg: 'Debe de seleccionar el estado de la dirección!',
                        icon: 2,
                        buttons: 1,
                        fn: function(btn){
                            Ext.getCmp(descargaManis.id + '-d_correcta').focus(true, 500);
                            Ext.getCmp(descargaManis.id + '-d_correcta').expand();
                        }
                    });
                    return false;
                }

                if (parseInt(vp_tpar) != 1){
                    if (vp_nombre == ''){
                        global.Msg({
                            msg: 'Debe de completar el nombre del entrevistado!',
                            icon: 2,
                            buttons: 1,
                            fn: function(btn){
                                Ext.getCmp(descargaManis.id + '-nombre').focus(true, 500);
                            }
                        });
                        return false;
                    }
                }
            }

            global.Msg({
                msg: '¿Seguro de grabar?',
                icon: 3,
                buttons: 3,
                fn: function(btn){
                    if (btn == 'yes'){
                        Ext.getCmp(descargaManis.id + '-tab').el.mask('Cargando…', 'x-mask-loading');
                        Ext.Ajax.request({
                            url: descargaManis.url + 'set_graba_man/',
                            params:{
                                vp_reclamo: vp_reclamo,
                                vp_fec_aud: vp_fec_aud,
                                vp_hor_aud: vp_hor_aud,
                                vp_chk_id: vp_chk_id,
                                vp_resultado: vp_resultado,
                                vp_recepcion: vp_recepcion,
                                vp_acuse: vp_acuse,
                                vp_observa: vp_observa,
                                vp_tpar: vp_tpar,
                                vp_nrodni: vp_nrodni,
                                vp_nombre: vp_nombre,
                                vp_telefono: vp_telefono,
                                vp_id_per: vp_id_per,
                                vp_id_viv: vp_id_viv,
                                vp_numsum: vp_numsum,
                                vp_tipviv: vp_tipviv,
                                vp_pisos: vp_pisos,
                                vp_color: vp_color,
                                vp_tpuerta: vp_tpuerta,
                                vp_ventana: vp_ventana,
                                vp_buzon: vp_buzon,
                                vp_timbre: vp_timbre,
                                vp_intercom: vp_intercom,
                                vp_bz_bp: vp_bz_bp,
                                vp_dir_ok: vp_dir_ok,
                                vp_observ: vp_observ
                            },
                            success: function(response, options){
                                Ext.getCmp(descargaManis.id + '-tab').el.unmask();
                                var res = Ext.JSON.decode(response.responseText);
                                // console.log(res[0]);
                                // console.log(res[1]);
                                if (parseInt(res[0].error_sql) >= 0 && parseInt(res[1].error_sql) >= 0){
                                    global.Msg({
                                        msg: res[0].error_info,
                                        icon: 1,
                                        buttons: 1,
                                        fn: function(btn){
                                            // if (descargaManis.window == 0)
                                            //     Ext.getCmp(descargaManis.id + '-tab').close();
                                            // else
                                            //     descargaManis.next_reclamo();
                                        }
                                    });
                                }else{
                                    if (parseInt(res[0].error_sql) < 0){
                                        global.Msg({
                                            msg: res[0].error_info,
                                            icon: 0,
                                            buttons: 1,
                                            fn: function(btn){

                                            }
                                        });
                                        return false;
                                    }else{
                                        global.Msg({
                                            msg: res[1].error_info,
                                            icon: 0,
                                            buttons: 1,
                                            fn: function(btn){

                                            }
                                        });
                                        return false;
                                    }
                                }
                            }
                        });
                    }
                }
            });
        },
        get_recepcion_chk: function(){
            var a = [
                Ext.getCmp(descargaManis.id + '-chk_recepciona_01').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_recepciona_02').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_recepciona_03').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_recepciona_04').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_recepciona_05').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_recepciona_06').getValue() ? 1 : 0, 
            ];
            var resultado = '';
            Ext.each(a, function(value, index){
                resultado+= value;
            });
            return resultado;
        },
        get_resultado_chk: function(){
            var a = [
                Ext.getCmp(descargaManis.id + '-chk_auditoria_01').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_auditoria_02').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_auditoria_03').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_auditoria_04').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_auditoria_05').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_auditoria_06').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_auditoria_07').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_auditoria_08').getValue() ? 1 : 0, 
                Ext.getCmp(descargaManis.id + '-chk_auditoria_09').getValue() ? 1 : 0, 
            ];
            var resultado = '';
            Ext.each(a, function(value, index){
                resultado+= value;
            });
            return resultado;
        },
        get_per_id: function(dni, nombre, telefono){
            if (descargaManis.dni == dni && descargaManis.nombre == nombre && descargaManis.telefono == telefono)
                return descargaManis.per_aud_id;
            else
                return 0;
        },
        get_barra: function(barra){
            // var barra = Ext.getCmp(descargaManis.id + '-barra').getValue();
            Ext.Ajax.request({
                url: descargaManis.url + 'get_scm_reclamo_descarga_scaneo/',
                params:{
                    vp_barra: barra
                },
                success: function(response, options){
                    var res = Ext.JSON.decode(response.responseText);
                    // console.log(res);
                    if (parseInt(res.error_sql) >= 0){
                        if (parseInt(res.exists_cargo) == 0)
                            Ext.getCmp(descargaManis.id + '-pimagen-cargo').setTitle('No tiene cargo de mensajero.');

                        var $ = jQuery;
                        $("#viewer1").iviewer({
                            src: "/tmp/reclamos/" + res.reclamo + '-cargo.jpg',
                            update_on_resize: false,
                            zoom: 45,
                            initCallback: function (){
                                var object = this;
                                $("#in").click(function(){ object.zoom_by(1);});
                                $("#out").click(function(){ object.zoom_by(-1);});
                                $("#fit").click(function(){ object.fit();});
                                $("#orig").click(function(){ object.set_zoom(100); });
                                $("#update").click(function(){ object.update_container_info();});
                            },
                            onFinishLoad: function(){
                                
                            }
                        });

                        $("#viewer").iviewer({
                            src: "/tmp/reclamos/" + res.reclamo + '-imagen.jpg',
                            update_on_resize: false,
                            zoom: 25,
                            initCallback: function (){
                                var object = this;
                                $("#in").click(function(){ object.zoom_by(1);});
                                $("#out").click(function(){ object.zoom_by(-1);});
                                $("#fit").click(function(){ object.fit();});
                                $("#orig").click(function(){ object.set_zoom(100); });
                                $("#update").click(function(){ object.update_container_info();});
                            },
                            onFinishLoad: function(){
                                
                            }
                        });

                        Ext.getCmp(descargaManis.id + '-auditor').setValue(res.auditor);
                        Ext.getCmp(descargaManis.id + '-id_manifiesto').setValue(res.man_id);
                        Ext.getCmp(descargaManis.id + '-fecha').setValue(res.fecha_ld);
                        Ext.get(descargaManis.id + '-reclamo').update(res.reclamo);
                        Ext.get(descargaManis.id + '-fecha_reclamo').update(res.fecha_reclamo);
                        Ext.get(descargaManis.id + '-motivo').update(res.motivo);
                        Ext.get(descargaManis.id + '-shipper').update(res.shipper);
                        Ext.get(descargaManis.id + '-servicio').update(res.servicio);
                        Ext.get(descargaManis.id + '-ciclo').update(res.ciclo);
                        Ext.get(descargaManis.id + '-cliente').update(res.cliente);
                        Ext.get(descargaManis.id + '-direccion').update(res.direccion);
                        Ext.get(descargaManis.id + '-localidad').update(res.distrito);
                        Ext.get(descargaManis.id + '-courier').update(res.courrier);

                        descargaManis.vp_reclamo = parseInt(res.reclamo);

                        Ext.getCmp(descargaManis.id + '-barra').disable();
                        Ext.getCmp(descargaManis.id + '-p_auditoria').enable();
                        Ext.getCmp(descargaManis.id + '-f_auditoria').focus(true, 500);
                        Ext.getCmp(descargaManis.id + '-f_auditoria').expand();
                    }else{
                        global.Msg({
                            msg: res.error_info,
                            icon: 0,
                            buttons: 1,
                            fn: function(btn){
                                Ext.getCmp(descargaManis.id + '-barra').setValue('');
                                Ext.getCmp(descargaManis.id + '-barra').focus(true, 500);
                            }
                        });
                    }
                }
            });
        },
        get_html_datos_reclamos: function(){
            var panel = Ext.getCmp(descargaManis.id + '-fdreclamo');
            panel.removeAll();

            var vhtml = '<div class="cont_descargar">'+
                '<fieldset>'+
                    '<legend>Datos del reclamo</legend>'+
                    '<div class="row">'+
                        '<div>'+
                            '<label style="width: 65px;">Nº Reclamo:</label>'+
                            '<span id="'+descargaManis.id + '-reclamo'+'" class="texto_left" style="width: 40px;"></span>'+
                        '</div>'+
                        '<div>'+
                            '<label>Fecha:</label>'+
                            '<span id="'+descargaManis.id + '-fecha_reclamo'+'" class="texto_left" style="width: 60px;"></span>'+
                        '</div>'+
                        '<div>'+
                            '<label>Motivo:</label>'+
                            '<span id="'+descargaManis.id + '-motivo'+'" class="texto_left" style="width: 490px;"></span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div>'+
                            '<label style="width: 65px;">Shipper:</label>'+
                            '<span id="'+descargaManis.id + '-shipper'+'" class="texto_left" style="width: 300px;"></span>'+
                        '</div>'+
                        '<div>'+
                            '<label>Servicio:</label>'+
                            '<span id="'+descargaManis.id + '-servicio'+'" class="texto_left" style="width: 320px;"></span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div>'+
                            '<label style="width: 65px;">Ciclo:</label>'+
                            '<span id="'+descargaManis.id + '-ciclo'+'" class="texto_left" style="width: 65px;"></span>'+
                        '</div>'+
                        '<div>'+
                            '<label>Cliente:</label>'+
                            '<span id="'+descargaManis.id + '-cliente'+'" class="texto_left" style="width: 559px;"></span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div>'+
                            '<label style="width: 65px;">Dirección:</label>'+
                            '<span id="'+descargaManis.id + '-direccion'+'" class="texto_left" style="width: 663px;"></span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div>'+
                            '<label style="width: 65px;">Localidad:</label>'+
                            '<span id="'+descargaManis.id + '-localidad'+'" class="texto_left" style="width: 300px;"></span>'+
                        '</div>'+
                        '<div>'+
                            '<label>Courier:</label>'+
                            '<span id="'+descargaManis.id + '-courier'+'" class="texto_left" style="width: 322px;"></span>'+
                        '</div>'+
                    '</div>'+
                '</fieldset>'+
            '</div>';

            panel.update(vhtml);
        },
        next_reclamo: function(){
            var grid = Ext.getCmp(fAuditando.id+'-grid');
            var store = grid.getStore();
            store.reload({
                callback: function(){
                    if (store.getCount() > 0){
                        var vp_reclamo = store.getAt(0).get('reclamo');
                        // console.log(vp_reclamo);
                        Ext.getCmp(descargaManis.id + '-win').close();
                        fAuditando.get_form_descarga_man(vp_reclamo);
                    }else{
                        Ext.getCmp(descargaManis.id + '-win').close();
                    }
                }
            });
        }
    }
    Ext.onReady(descargaManis.init, descargaManis);
}else{
    tab.setActiveTab(descargaManis.id+'-tab');
}
</script>