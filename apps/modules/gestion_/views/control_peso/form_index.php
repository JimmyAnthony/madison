<script type="text/javascript">
/**
 * @link    https://github.com/hawkapparel
 * @author  Christian Tamayo
 * @version 1.0
 */
var tab = Ext.getCmp(inicio.id+'-tabContent');

if(!Ext.getCmp('controlpeso-tab')){

    var controlpeso = {
        id: 'controlpeso',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/gestionControlPeso/',
        init:function(){
            /*var form_control_peso = Ext.create('Ext.data.Store',{
                fields:[
                
                    {name:'error_sql', type:'int'},
                    {name:'error_info', type:'string'},
                    {name:'id_pieza', type:'int'},
                    {name:'txt_label', type:'string'}                                            
                ],
                proxy:{
                    type:'ajax',
                    url:controlpeso.url+'scm_control_peso_escaneo/',
                    reader:{
                        type:'json',
                        root:'data',
                    }
                }
            });*/

            tab.add({

                layout: 'border',
                id: controlpeso.id+'-tab',
                border: false,
                autoScroll: false,
                closable: true,
                bodyStyle: 'background: transparent',
                bodyPadding: 0,

                defaults: {
                    collapsible: false,
                    split: false,
                 },

                items:
                [
                    {
                        xtype: 'panel',
                        region: 'center',
                        cls: 'tabpanel-controlpeso',
                        width: '100%',
                        defaults: {
                            bodyPadding: 0,
                            scrollable: false
                        },
                        tbar:[
                            {
                                xtype: 'segmentedbutton',
                                vertical: false,
                                items:[
                                        {
                                            text: 'Piezas',
                                            cls: 'contenedor-titlePiezas',
                                            icon: '/images/icon/paquetes.ico',
                                            listeners:{
                                                click:function(obj){
                                                    controlpeso.pressed(1);
                                                }
                                            }
                                        },
                                        {
                                            text: 'Guias',
                                            cls: 'contenedor-titleGuias',
                                            icon: '/images/icon/file.png',
                                            listeners:{
                                                click:function(obj){
                                                    controlpeso.pressed(2);
                                                }
                                            }
                                        },
                                        {xtype:'tbspacer',width:160},
                                        {
                                            xtype:'fieldcontainer',
                                            id:controlpeso.id+'-menu-title-controlpeso',
                                            vertical:false,
                                            allowToggle:false,
                                            hidden:false,
                                            items:[
                                                    {
                                                        xtype:'component',
                                                        html: 'Control de peso',
                                                        padding: '3px 0px 0px 0px',
                                                        cls: 'single-bold',
                                                        listeners:{
                                                            click:function(obj){
                                                            }
                                                        }
                                                    }
                                            ]
                                        },
                                        {
                                            xtype:'fieldcontainer',
                                            id:controlpeso.id+'-menu-title-guias',
                                            vertical:false,
                                            allowToggle:false,
                                            hidden:true,
                                            items:[
                                                    {
                                                        xtype:'component',
                                                        html: 'Editar Guia de envio',
                                                        padding: '3px 0px 0px 0px',
                                                        cls: 'single-bold',
                                                        listeners:{
                                                            click:function(obj){
                                                            }
                                                        }
                                                    }
                                            ]
                                        }
                                    ]
                            }
                        ],
                        items: [
                            {
                                xtype: 'panel',
                                id: controlpeso.id+'-container-controlpeso',
                                collapsible: false,
                                hidden:false,
                                border: false,
                                region: 'center',
                                width: '100%',
                                height: 334,
                                bodyStyle: 'background: #fff',
                                cls: 'contenedor-controlpeso',
                                items: [
                                    {
                                        region: 'north',
                                        width: '100%',
                                        bodyStyle: 'background: #fff',
                                        height: 335,
                                        border: false,
                                        padding: '15px 0px 0px 0px',
                                        items:
                                        [
                                            {
                                                xtype:'panel',
                                                border:false,
                                                bodyStyle: 'background: transparent',
                                                padding:'2px 10px 1px 10px',
                                                layout:'column',
                                                height: 40,
                                                items: [
                                                    {
                                                        columnWidth: .30,border:false,
                                                        bodyStyle: 'background: transparent',
                                                        items:[
                                                            {
                                                                xtype: 'textfield',
                                                                id: controlpeso.id+'-codigo-pieza',
                                                                name: 'código de la pieza',
                                                                disabled: false,
                                                                allowBlank:false,
                                                                fieldLabel: 'Código de Pieza en Pesaje',
                                                                fieldStyle: 'text-align:center;',
                                                                labelWidth:110,
                                                                emptyText: '[ Enter ]',
                                                                labelAlign:'left',
                                                                width:'99%',
                                                                anchor:'99%',
                                                                hideTrigger: true,
                                                                keyNavEnabled: false,
                                                                mouseWheelEnabled: false,
                                                                enableKeyEvents: true,
                                                                listeners: {
                                                                    keypress:function(obj,e,opts){
                                                                        if (e.getKey() == 13){
                                                                            controlpeso.getValidateCodebar(obj);
                                                                        }
                                                                    },
                                                                }
                                                            }
                                                        ]
                                                    }
                                                ]
                                            },
                                            {
                                                xtype:'panel',
                                                border:false,
                                                bodyStyle: 'background: transparent',
                                                padding:'2px 10px 1px 10px',
                                                layout:'column',
                                                items: [
                                                    {
                                                        columnWidth: .30,border:false,
                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                        items:[
                                                            {
                                                                xtype: 'textfield',
                                                                id: controlpeso.id+'-id-pieza',
                                                                disabled: false,
                                                                fieldLabel: 'ID Pieza',
                                                                fieldStyle: 'font-weight: bold; height:40;font-size: 16pt;padding: 0px;text-align:center;',
                                                                labelStyle: 'font-weight: bold; height:00;font-size: 10pt;padding-top:7px;',
                                                                readOnly: true,
                                                                labelWidth:110,
                                                                labelAlign:'left',
                                                                width:'100%',
                                                                anchor:'100%',
                                                                minValue: 0,
                                                                border: true
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        columnWidth: .34,border:false,
                                                        padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                        items:[
                                                            {
                                                                xtype: 'textfield',
                                                                id: controlpeso.id+'-txt-cod-label',
                                                                disabled: false,
                                                                fieldLabel: '',
                                                                fieldStyle: 'font-weight: bold; height:40;font-size: 16pt;padding: 0px;text-align:center;',
                                                                labelStyle: 'font-weight: bold; height:00;font-size: 10pt;padding-top:7px;',
                                                                readOnly: true,
                                                                width:'97.5%',
                                                                anchor:'97.5%',
                                                                border: true
                                                            }
                                                        ]
                                                    }
                                                ]
                                            },
                                            {
                                                region:'center',
                                                height:220,
                                                xtype: 'fieldset',
                                                title: 'Datos del Pesaje',
                                                padding:'2px 5px 0px 2px',
                                                margin: '5px 10px 0px 10px',
                                                bodyStyle: 'background: transparent',
                                                border:true,
                                                items:[
                                                    {
                                                        xtype: 'numberfield',
                                                        id: controlpeso.id+'-peso-balanza',
                                                        name: 'peso balanza',
                                                        disabled: false,
                                                        fieldLabel: 'Peso Balanza',
                                                        fieldStyle: 'text-align:center;',
                                                        cls: 'float-left',
                                                        labelWidth:100,
                                                        labelAlign:'left',
                                                        width: 191,
                                                        padding: '0px 0px 0px 10px',
                                                        emptyText: '[ Enter ]',
                                                        allowBlank:false,
                                                        hideTrigger: true,
                                                        keyNavEnabled: false,
                                                        mouseWheelEnabled: false,
                                                        minValue: 0,
                                                        allowDecimals: true,
                                                        decimalSeparator:'.',
                                                        enableKeyEvents: true,
                                                        listeners: {
                                                            keypress:function(obj,e,opts){
                                                                if (e.getKey() == 13){
                                                                    controlpeso.validateFieldsAndSetPlus();
                                                                    controlpeso.setNextEnter(obj);
                                                                }
                                                            },
                                                        }                               
                                                    },
                                                    {
                                                        xtype: 'component',
                                                        html: 'kg',
                                                        width: 100,
                                                        padding: '5px 0px 0px 5px',
                                                        cls: 'field-non-border float-left'
                                                    },
                                                    {
                                                        xtype:'panel',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'2px 0px 0px 115px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: .28,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'component',
                                                                        html: 'Largo(cm):'
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .29,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'component',
                                                                        html: 'Ancho(cm):'
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .24,border:false,  
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'component',
                                                                        html: 'Alto(cm):',
                                                                        padding:'0px 0px 0px 5px'
                                                                    }
                                                                ]
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype:'panel',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'2px 10px 1px 10px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: .40,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                defaults:{
                                                                        hideTrigger: true,
                                                                        keyNavEnabled: false,
                                                                        mouseWheelEnabled: false
                                                                    },
                                                                items:[
                                                                    {
                                                                        xtype: 'numberfield',
                                                                        id: controlpeso.id+'-peso-vol-largo',
                                                                        name: 'largo',
                                                                        disabled: false,
                                                                        fieldLabel: 'Medidas volumétricas',
                                                                        emptyText: '[ Enter ]',
                                                                        labelWidth:100,
                                                                        fieldStyle: 'font-weight: bold; height:30px;font-size: 16pt;padding-top: 8px;text-align:center;',
                                                                        labelStyle: 'font-weight: bold; height:30px;font-size: 10pt;padding-top:3px;',
                                                                        labelAlign:'left',
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        minValue: 0,
                                                                        allowBlank:false,
                                                                        allowDecimals: true,
                                                                        decimalSeparator:'.',
                                                                        enableKeyEvents: true,
                                                                        listeners: {
                                                                            keypress:function(obj,e,opts){
                                                                                if (e.getKey() == 13){
                                                                                    controlpeso.validateFieldsAndSetPlus();
                                                                                    controlpeso.setNextEnter(obj);
                                                                                }
                                                                            },
                                                                        }
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .25,border:false,
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                defaults:{
                                                                        hideTrigger: true,
                                                                        keyNavEnabled: false,
                                                                        mouseWheelEnabled: false
                                                                    },
                                                                items:[
                                                                    {
                                                                        xtype: 'numberfield',
                                                                        id: controlpeso.id+'-peso-vol-ancho',
                                                                        name: 'ancho',
                                                                        disabled: false,
                                                                        fieldLabel: '',
                                                                        emptyText: '[ Enter ]',
                                                                        fieldStyle: 'font-weight: bold; height:30px;font-size: 16pt;padding-top: 8px;text-align:center;',
                                                                        labelStyle: 'font-weight: bold; height:30px;font-size: 10pt;padding-top:7px;',
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        minValue: 0,
                                                                        allowBlank:false,
                                                                        allowDecimals: true,
                                                                        decimalSeparator:'.',
                                                                        enableKeyEvents: true,
                                                                        listeners: {
                                                                            keypress:function(obj,e,opts){
                                                                                if (e.getKey() == 13){
                                                                                    controlpeso.validateFieldsAndSetPlus();
                                                                                    controlpeso.setNextEnter(obj);
                                                                                }
                                                                            },
                                                                        }
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .25,border:false,  
                                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                                defaults:{
                                                                        hideTrigger: true,
                                                                        keyNavEnabled: false,
                                                                        mouseWheelEnabled: false
                                                                    },
                                                                items:[
                                                                    {
                                                                        xtype: 'numberfield',
                                                                        id: controlpeso.id+'-peso-vol-alto',
                                                                        name: 'alto',
                                                                        disabled: false,
                                                                        fieldLabel: '',
                                                                        emptyText: '[ Enter ]',
                                                                        fieldStyle: 'font-weight: bold; height:30px;font-size: 16pt;padding-top: 8px;text-align:center;',
                                                                        labelStyle: 'font-weight: bold; height:30px;font-size: 10pt;padding-top:7px;',
                                                                        width:'100%',
                                                                        anchor:'100%',
                                                                        minValue: 0,
                                                                        allowBlank:false,
                                                                        allowDecimals: true,
                                                                        decimalSeparator:'.',
                                                                        enableKeyEvents: true,
                                                                        listeners: {
                                                                            keypress:function(obj,e,opts){
                                                                                if (e.getKey() == 13){
                                                                                    controlpeso.validateFieldsAndSetPlus();
                                                                                    controlpeso.setNextEnter(obj);
                                                                                }
                                                                            },
                                                                        }
                                                                    }
                                                                ]
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype: 'numberfield',
                                                        id: controlpeso.id+'-peso-total-volumetrico',
                                                        disabled: false,
                                                        readOnly: true,
                                                        fieldLabel: 'Peso Volumétrico',
                                                        labelWidth: 110,
                                                        padding: '10px 0px 0px 117px',
                                                        labelAlign:'left',
                                                        cls: 'float-left',
                                                        width:'50%',
                                                        anchor:'50%',
                                                        hideTrigger: true,
                                                        keyNavEnabled: false,
                                                        mouseWheelEnabled: false,
                                                        minValue: 0,
                                                        allowDecimals: true,
                                                        decimalSeparator:'.',
                                                    },
                                                    {
                                                        xtype: 'component',
                                                        html: 'kg',
                                                        width: 100,
                                                        padding: '15px 0px 0px 5px',
                                                        cls: 'float-left'
                                                    },
                                                    {
                                                        xtype:'panel',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'2px 10px 1px 10px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                xtype: 'numberfield',
                                                                id: controlpeso.id+'-peso-total-pieza',
                                                                disabled: false,
                                                                readOnly: true,
                                                                fieldLabel: 'Peso de la pieza',
                                                                labelWidth: 105,
                                                                fieldStyle: 'font-weight: bold;',
                                                                labelStyle: 'font-weight: bold;',
                                                                padding: '10px 0px 0px 0px',
                                                                labelAlign:'left',
                                                                cls: 'float-left',
                                                                width:191,
                                                                anchor:191,
                                                                hideTrigger: true,
                                                                keyNavEnabled: false,
                                                                mouseWheelEnabled: false,
                                                                minValue: 0,
                                                                allowDecimals: true,
                                                                decimalSeparator:'.',
                                                            },
                                                            {
                                                                xtype: 'component',
                                                                html: 'kg',
                                                                width: 100,
                                                                padding: '15px 0px 0px 5px',
                                                                cls: 'float-left'
                                                            },
                                                        ]
                                                    },
                                                    {
                                                        xtype:'panel',
                                                        border:false,
                                                        bodyStyle: 'background: transparent',
                                                        padding:'2px 10px 1px 10px',
                                                        layout:'column',
                                                        items: [
                                                            {
                                                                columnWidth: .50,border:false, cls: 'side-text-right',
                                                                padding:'0px 5px 0px 0px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'button',
                                                                        id: controlpeso.id+'-btn-grabar',
                                                                        icon: '/images/icon/save.png',
                                                                        text: 'Grabar',
                                                                        listeners:{
                                                                            'click':function(obj, e){
                                                                                //controlpeso.btnActionValidateFields();
                                                                                controlpeso.btnActionSave();
                                                                            }
                                                                        }
                                                                    }
                                                                ]
                                                            },
                                                            {
                                                                columnWidth: .50,border:false,
                                                                padding:'0px 2px 0px 5px',  bodyStyle: 'background: transparent',
                                                                items:[
                                                                    {
                                                                        xtype: 'button',
                                                                        id: controlpeso.id+'-btn-cancel',
                                                                        icon: '/images/icon/cancel.png',
                                                                        text: 'Cancelar',
                                                                        listeners:{
                                                                            'click': function(obj, e){
                                                                                controlpeso.btnActionCancel();
                                                                            }
                                                                        }
                                                                    }
                                                                ]
                                                            }
                                                        ]
                                                    }                                            
                                                ]
                                            }
                                        ]
                                    }
                                ],                                          
                            },
                            {
                                //ACA SE PONE EL CODIGO PARA EL PANEL DE GUIAS (EL ANTIGUO FORMULARIO)
                                xtype: 'panel',
                                id: controlpeso.id+'-container-guia',
                                collapsible: false,
                                hidden:true,
                                border: false,
                                region: 'center',
                                width: '100%',
                                height: 334,
                                bodyStyle: 'background: #fff',
                                items: [/*
                                    {
                                        xtype:'panel',
                                        border:false,
                                        bodyStyle: 'background: transparent',
                                        padding:'2px 10px 1px 10px',
                                        layout:'column',
                                        items: [
                                            {
                                                columnWidth: .30,border:false,
                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                items:[
                                                    {
                                                        xtype: 'textfield',
                                                        id: controlpeso.id+'-id-pieza',
                                                        disabled: false,
                                                        fieldLabel: 'ID Pieza',
                                                        fieldStyle: 'font-weight: bold; height:40;font-size: 16pt;padding: 0px;text-align:center;',
                                                        labelStyle: 'font-weight: bold; height:00;font-size: 10pt;padding-top:7px;',
                                                        readOnly: true,
                                                        labelWidth:110,
                                                        labelAlign:'left',
                                                        width:'100%',
                                                        anchor:'100%',
                                                        minValue: 0,
                                                        border: true
                                                    }
                                                ]
                                            },
                                            {
                                                columnWidth: .34,border:false,
                                                padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                items:[
                                                    {
                                                        xtype: 'textfield',
                                                        id: controlpeso.id+'-txt-cod-label',
                                                        disabled: false,
                                                        fieldLabel: '',
                                                        fieldStyle: 'font-weight: bold; height:40;font-size: 16pt;padding: 0px;text-align:center;',
                                                        labelStyle: 'font-weight: bold; height:00;font-size: 10pt;padding-top:7px;',
                                                        readOnly: true,
                                                        width:'97.5%',
                                                        anchor:'97.5%',
                                                        border: true
                                                    }
                                                ]
                                            }
                                        ]
                                    }*/
                                ]
                            }
                        ],
                    },
                    {
                        collapsible: false,
                        region: 'east',
                        width: '50%',
                        bodyStyle: 'background: #fff',
                        items: [
                                {
                                    xtype:'panel',
                                    border:false,
                                    bodyStyle: 'background: transparent',
                                    padding:'15px 10px 1px 10px',
                                    layout:'column',
                                    items: [
                                        {
                                            columnWidth: .70,border:false,
                                            padding:'0px 0px 0px 0px',  bodyStyle: 'background: transparent',
                                            defaults: {
                                                readOnly: true,
                                                border: false,
                                                cls: 'field-non-border',
                                                width:'48%',

                                            },
                                            items:[
                                                    {
                                                        xtype: 'component',
                                                        html: 'Estadísticas',
                                                        padding: '10px 0px 0px 0px',
                                                        width: '100%',
                                                        cls: 'side-text-center-title'
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Piezas Procesadas',
                                                        labelWidth: 150,
                                                        padding: '25px 0px 0px 60px',
                                                        emptyText: '45'
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Total Piezas Balanza',
                                                        labelWidth: 150,
                                                        padding: '0px 0px 0px 60px',
                                                        emptyText: '49.5',
                                                        cls: 'field-non-border float-left'
                                                    },
                                                    {
                                                        xtype: 'component',
                                                        html: 'kg',
                                                        width: 100,
                                                        cls: 'field-non-border float-left'
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Total Piezas Volumen',
                                                        labelWidth: 150,
                                                        padding: '0px 0px 15px 60px',
                                                        emptyText: '58.3',
                                                        cls: 'field-non-border float-left'
                                                    },
                                                    {
                                                        xtype: 'component',
                                                        html: 'kg<sup>3</sup>',
                                                        width: 100,
                                                        cls: 'field-non-border float-left'
                                                    }
                                            ]
                                        },
                                        {
                                            columnWidth: .30,border:false,
                                            padding:'0px 2px 0px 5px',  bodyStyle: 'background: transparent',
                                            defaults: {
                                                readOnly: true,
                                                border: false,
                                                cls: 'field-non-border'
                                            },
                                            items:[
                                                {
                                                    xtype: 'textfield',
                                                    fieldLabel: 'Usuario',
                                                    labelWidth: 60,
                                                    width:'95%',
                                                    padding: '10px 0px 0px 0px',
                                                    emptyText: 'lorem ipsum'
                                                },
                                                {
                                                    xtype: 'textfield',
                                                    fieldLabel: 'Fecha',
                                                    labelWidth: 60,
                                                    width:'95%',
                                                    emptyText: 'lorem ipsum'
                                                },
                                                {
                                                    xtype: 'textfield',
                                                    fieldLabel: 'Hora',
                                                    labelWidth: 60,
                                                    width:'95%',
                                                    emptyText: 'lorem ipsum'
                                                }                                                
                                            ]
                                        }
                                    ]
                                },
                                {
                                    xtype: 'component',
                                    cls: 'separator-line',
                                    width:'80%'
                                },
                                {
                                    xtype:'panel',
                                    border:false,
                                    bodyStyle: 'background: transparent',
                                    padding:'15px 10px 1px 10px',
                                    layout:'column',
                                    items: [
                                        {
                                            columnWidth: .70,border:false,
                                            padding:'0px 5px 0px 0px',  bodyStyle: 'background: transparent',
                                            defaults: {
                                                readOnly: true,
                                                border: false,
                                                cls: 'field-non-border',
                                                width:'48%'
                                            },
                                            items:[
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Piezas Ingresadas',
                                                        labelWidth: 150,
                                                        padding: '5px 0px 0px 60px',
                                                        emptyText: '45'
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Piezas Procesadas',
                                                        labelWidth: 150,
                                                        padding: '0px 0px 0px 60px',
                                                        emptyText: '49.5'
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Peso Local',
                                                        labelWidth: 150,
                                                        padding: '0px 0px 0px 60px',
                                                        emptyText: '58.3',
                                                        cls: 'field-non-border float-left'
                                                    },
                                                    {
                                                        xtype: 'component',
                                                        html: 'kg',
                                                        width: 100,
                                                        cls: 'field-non-border float-left'
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Peso Volumen Total',
                                                        labelWidth: 150,
                                                        padding: '0px 0px 0px 60px',
                                                        emptyText: '58.3',
                                                        cls: 'field-non-border float-left'
                                                    },
                                                    {
                                                        xtype: 'component',
                                                        html: 'kg<sup>3</sup>',
                                                        width: 100,
                                                        cls: 'field-non-border float-left'
                                                    }
                                            ]
                                        },
                                        {
                                            columnWidth: .30,border:false,
                                            padding:'0px 2px 0px 5px',  bodyStyle: 'background: transparent',
                                            defaults: {
                                                readOnly: true,
                                                border: false,
                                                cls: 'field-non-border'
                                            },
                                            items:[
                                                {
                                                    xtype: 'textfield',
                                                    fieldLabel: 'Planta',
                                                    labelWidth: 60,
                                                    width:'95%',
                                                    padding: '5px 0px 0px 0px',
                                                    emptyText: 'Lima'
                                                }                                               
                                            ]
                                        }
                                    ]
                                },
                                {
                                    xtype: 'textfield',
                                    fieldLabel: 'Piezas sin Procesar',
                                    labelWidth: 135,
                                    readOnly: true,
                                    border: false,
                                    width: 218,
                                    padding: '0px 30px 0px 0px',
                                    emptyText: '220',
                                    cls: 'field-non-border side-right bold'
                                }
                        ],
                    },
                    {
                        region: 'south',
                        height: '53%',
                        layout:'fit',
                        items:[
                            {
                                xtype:'grid',
                                //id:socionegocio.id+'socio_shipper-grid',
                                //store:socio_shipper_grid,
                                columnLines:true,
                                columns:{
                                    items:[
                                            {
                                                xtype:'rownumberer',
                                                dataIndex:'',
                                                width:30,
                                                align:'center',
                                            },
                                            {
                                                text:'ID Pieza',
                                                dataIndex:'',
                                                width:80,
                                                align:'center',/*
                                                renderer:function(value, metaData, record, rowIndex, colIndex, store, view){
                                                    metaData.style = "padding: 0px; margin: 0px";
                                                    var shi_codigo ="'"+record.get('shi_codigo')+"'";
                                                    var per_id_sac ="'"+record.get('per_id_sac')+"'";
                                                    var shi_nombre ="'"+record.get('shi_nombre')+"'";
                                                    var fec_ingreso ="'"+record.get('fec_ingreso')+"'";
                                                    var shi_id ="'"+record.get('shi_id')+"'";
                                                    var shi_empresa ="'"+record.get('shi_empresa')+"'";
                                                    var per_id_ven ="'"+record.get('per_id_ven')+"'";
                                                    var id_sne = "'"+record.get('id_sne')+"'";
                                                    
                                                    if (record.get('shi_estado')=='Activo'){
                                                        return global.permisos({
                                                            type: 'link',
                                                            id_menu: socionegocio.id_menu,
                                                            icons:[
                                                                {id_serv: 77, img: 'ico_editar.gif', qtip: 'Click para Editar', js: 'socionegocio.edit_socio_shipper('+shi_codigo+','+per_id_sac+','+shi_nombre+','+fec_ingreso+','+shi_id+','+shi_empresa+','+per_id_ven+')'},
                                                                {id_serv: 78, img: 'ok.png', qtip: 'Click para ver Desactivar', js: 'socionegocio.stado_socio_shipper('+id_sne+','+shi_codigo+',0)'}
                                                            ]
                                                        }); 
                                                    }else{
                                                        return global.permisos({
                                                            type: 'link',
                                                            id_menu: socionegocio.id_menu,
                                                            icons:[
                                                                {id_serv: 77, img: 'ico_editar.gif', qtip: 'Click para Editar', js: 'socionegocio.edit_socio_shipper('+shi_codigo+','+per_id_sac+','+shi_nombre+','+fec_ingreso+','+shi_id+','+shi_empresa+','+per_id_ven+')'},
                                                                {id_serv: 78, img: 'close.png', qtip: 'Click para ver Activar..', js: 'socionegocio.stado_socio_shipper('+id_sne+','+shi_codigo+',1)'}
                                                            ]
                                                        }); 
                                                    }
                                                    
                                                }*/
                                            },
                                            {
                                                text:'Barra',
                                                //dataIndex:'shi_codigo',
                                                align:'center',
                                                width:100,
                                                //cls: 'column_header_double',
                                            },
                                            {
                                                text:'Responsable',
                                                //dataIndex:'shi_nombre',
                                                align:'center',
                                                flex:2
                                                //cls: 'column_header_double',
                                            },
                                            {
                                                text:'CE',
                                                //dataIndex:'fec_ingreso',
                                                align:'center',
                                                width:100
                                                //cls: 'column_header_double',
                                            },
                                            {
                                                text:'Shipper',
                                                //dataIndex:'per_sac',
                                                align:'center',
                                                flex:1
                                                //cls: 'column_header_double',
                                            },
                                            {
                                                text:'Destino',
                                                //dataIndex:'per_comer',
                                                align:'center',
                                                flex:2
                                                //cls: 'column_header_double',
                                            },
                                            {
                                                text:'Cliente',
                                                //dataIndex:'shi_estado',
                                                align:'center',
                                                flex:1
                                                //cls: 'column_header_double',
                                            },


                                    ],
                                    defaults:{
                                        sortable: true
                                    }
                                },
                                viewConfig:{
                                    stripeRows:true,
                                    enableTextSelection: false,
                                    markDirty: false
                                },
                                trackMouseOver: true,
                                listeners:{
                                    afterrender:function(obj){

                                    }
                                }

                            }
                        ]
                    }
                ],

                listeners:{
                    beforerender: function(obj, opts){
                        global.state_item_menu(controlpeso.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        global.state_item_menu_config(obj, controlpeso.id_menu);
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(controlpeso.id_menu, false);
                    }
                }

            }).show();
        },


        getValidateCodebar:function(obj){

            if( !(obj.getValue() == null || obj.getValue() == "" ) ){
                Ext.Ajax.request({
                    url:controlpeso.url+'scm_control_peso_escaneo/',
                    params: {vp_barra:obj.getValue()},
                    success:function(response, options){
                        var res = Ext.JSON.decode(response.responseText).data[0];

                        if (res.error_sql < 0){
                            global.Msg({
                                msg:res.error_info,
                                icon:0,
                                buttosn:1,
                                fn:function(btn){
                                    Ext.getCmp(obj.id).setValue('');
                                }
                            });
                        }else{
                            Ext.getCmp(controlpeso.id+'-id-pieza').setValue(res.id_pieza);
                            Ext.getCmp(controlpeso.id+'-txt-cod-label').setValue(res.txt_label);                          
                            controlpeso.setNextEnter(obj);    
                        };
                    }

                });
            };
        },
  
        getInputFields:function(){
            var idpieza = Ext.getCmp(controlpeso.id+'-codigo-pieza');
            var pesobalanza = Ext.getCmp(controlpeso.id+'-peso-balanza');
            var pesovollargo = Ext.getCmp(controlpeso.id+'-peso-vol-largo');
            var pesovolancho = Ext.getCmp(controlpeso.id+'-peso-vol-ancho');
            var pesovolalto = Ext.getCmp(controlpeso.id+'-peso-vol-alto');
            var arrayinput = [idpieza, pesobalanza, pesovollargo, pesovolancho, pesovolalto];  
            return arrayinput;      
        },

        setNextEnter:function(obj){

            var arrayinput = controlpeso.getInputFields();
            var currentobject = obj;

            for (var i = 0; i < arrayinput.length; i++) {
                if (currentobject.id == arrayinput[i].id){
                    var cont = i + 1;
                    if( cont < (arrayinput.length) ){
                        Ext.getCmp(arrayinput[cont].id).focus();
                    }
                    else{
                        return false;
                    };
                    
                };                
            };
        },

        validateFieldsAndSetPlus:function(){
            var pesovollargo = Ext.getCmp(controlpeso.id+'-peso-vol-largo');
            var pesovolancho = Ext.getCmp(controlpeso.id+'-peso-vol-ancho');
            var pesovolalto = Ext.getCmp(controlpeso.id+'-peso-vol-alto');
            var pesototal = Ext.getCmp(controlpeso.id+'-peso-total-volumetrico');
            var pesobalanza = Ext.getCmp(controlpeso.id+'-peso-balanza');
            var pesototalpieza = Ext.getCmp(controlpeso.id+'-peso-total-pieza');

            if (!( pesovollargo.getValue() == "" || pesovolancho.getValue() == "" || pesovolalto.getValue() == "" || pesobalanza.getValue() == "" ||
                   pesovollargo.getValue() == null || pesovolancho.getValue() == null || pesovolalto.getValue() == null || pesobalanza.getValue() == null ||
                   pesovollargo.getValue() == 0 || pesovolancho.getValue() == 0 || pesovolalto.getValue() == 0 || pesobalanza.getValue() == 0
                ) ){
                var calpesovol = ( (pesovollargo.getValue()*pesovolancho.getValue()*pesovolalto.getValue())/5000 ).toFixed(2);
                controlpeso.setWeightPiece(calpesovol, pesobalanza);
                pesototal.setValue(calpesovol);
            }
            else{
                pesototal.setValue("");
                pesototalpieza.setValue("");
            }
        },
        
        setWeightPiece:function(pesovolumetrico, pesobalanza){
            var pesototalpieza = Ext.getCmp(controlpeso.id+'-peso-total-pieza');
                if (pesobalanza.getValue() >= pesovolumetrico){
                    pesototalpieza.setValue(pesobalanza.getValue());
                }
                else {
                    pesototalpieza.setValue(pesovolumetrico);
                };
        },

        btnActionValidateFields:function(){
            var arrayinput = controlpeso.getInputFields();
            var pesovolumetrico = Ext.getCmp(controlpeso.id+'-peso-total-volumetrico');
            var pesototalpieza = Ext.getCmp(controlpeso.id+'-peso-total-pieza'); 
            var validate = 0;
            for (var i = 0; i < arrayinput.length; i++) {
                 if (arrayinput[i].getValue() == '' || arrayinput[i].getValue() == null ){
                    validate = 1;
                    global.Msg({
                        msg: 'Ingrese ' + arrayinput[i].name,
                        icon:0,
                        buttosn:2,
                        fn:function(btn){
                            Ext.getCmp(arrayinput[i].id).focus();
                        }
                    });
                    return validate; /*En caso que existan campos vacios o nulos la función devolverá 1*/
                    break;
                }            
            };
                if (pesovolumetrico.getValue() == '' || pesovolumetrico.getValue() == null || pesototalpieza.getValue() == '' || pesototalpieza.getValue() == null){
                    controlpeso.validateFieldsAndSetPlus();
                };
                return validate; /*Si todos los campos estan llenos, devolvera 0*/
        },

        btnActionSave:function(){

            var pieza_numero = Ext.getCmp(controlpeso.id+'-codigo-pieza').getValue();
            var id_pieza =  Ext.getCmp(controlpeso.id+'-id-pieza').getValue();
            var peso_seco =  Ext.getCmp(controlpeso.id+'-peso-balanza').getValue();
            var vol_largo =  Ext.getCmp(controlpeso.id+'-peso-vol-largo').getValue();
            var vol_ancho =  Ext.getCmp(controlpeso.id+'-peso-vol-ancho').getValue();
            var vol_alto =  Ext.getCmp(controlpeso.id+'-peso-vol-alto').getValue();

            if ( !(controlpeso.btnActionValidateFields() == 1) ){/*Aqui hacemos la validación de los campos antes de guardar*/
                Ext.Ajax.request({
                    url:controlpeso.url+'scm_control_peso_pza_graba_peso/',
                    params: {vp_barra:pieza_numero,vp_id_pieza:id_pieza,vp_peso_seco:peso_seco, vp_vol_largo:vol_largo, vp_vol_ancho:vol_ancho, vp_vol_alto:vol_alto},

                    success:function(response, options){
                        var res = Ext.JSON.decode(response.responseText).data[0];

                            if (res.error_sql < 0){
                                global.Msg({
                                    msg:res.error_info,
                                    icon:0,
                                    buttosn:1,
                                    fn:function(btn){
                                    }
                                });
                            }else{
                                 global.Msg({
                                    msg:res.error_info,
                                    icon:1,
                                    buttosn:1,
                                    fn:function(btn){
                                    }
                                });
                            }
                    }

                });

            };

        },

        btnActionCancel:function() {
            controlpeso.clearAllFields();
        },

        clearAllFields:function(){
            var codpieza = Ext.getCmp(controlpeso.id+'-codigo-pieza');
            var idpieza = Ext.getCmp(controlpeso.id+'-id-pieza');
            var labelcodpieza = Ext.getCmp(controlpeso.id+'-txt-cod-label');
            var pesobalanza = Ext.getCmp(controlpeso.id+'-peso-balanza');
            var pesovollargo = Ext.getCmp(controlpeso.id+'-peso-vol-largo');
            var pesovolancho = Ext.getCmp(controlpeso.id+'-peso-vol-ancho');
            var pesovolalto = Ext.getCmp(controlpeso.id+'-peso-vol-alto');
            var pesototalvolumetrico = Ext.getCmp(controlpeso.id+'-peso-total-volumetrico');
            var pesototalpieza = Ext.getCmp(controlpeso.id+'-peso-total-pieza');
            var arrayinput = [codpieza, idpieza, labelcodpieza, pesobalanza, pesovollargo, pesovolancho, pesovolalto, pesototalvolumetrico, pesototalpieza];
            
            for (var i = 0; i < arrayinput.length; i++) {
                arrayinput[i].setValue("");               
            };

            codpieza.focus();

        },

        pressed:function(value){

            if (value == 1 ){
                Ext.getCmp(controlpeso.id+'-container-controlpeso').setVisible(true);  
                Ext.getCmp(controlpeso.id+'-menu-title-controlpeso').setVisible(true);
                Ext.getCmp(controlpeso.id+'-container-guia').setVisible(false);
                Ext.getCmp(controlpeso.id+'-menu-title-guias').setVisible(false);   
            }else if(value == 2){
                Ext.getCmp(controlpeso.id+'-container-guia').setVisible(true);
                Ext.getCmp(controlpeso.id+'-menu-title-guias').setVisible(true);
                Ext.getCmp(controlpeso.id+'-container-controlpeso').setVisible(false);  
                Ext.getCmp(controlpeso.id+'-menu-title-controlpeso').setVisible(false);
            };
        }

    }
    Ext.onReady(controlpeso.init, controlpeso);
}else{
    tab.setActiveTab(controlpeso.id+'-tab');
}
</script>