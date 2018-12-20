/**
 * @class Ext.global.plugin.ItemTrackingTransport
 * @extends Ext.form.Panel
 * @author Jim
 */
Ext.define('Ext.global.plugin.ItemTrackingTransport',{
    extend: 'Ext.Container',
    requires: [
        'Ext.view.View',
        'Ext.ux.DataView.Animated'
    ],
    xtype: 'ItemTrackingTransport',
    config: {
        layout: 'border',
        autoScroll:false,
        border:false,
        height:350
    },
    idItem:100,
    constructor: function(config){
        var me = this;
        
        me.items=[
            {
                region:'center',
                layout:'fit',
                border:false,
                items:[
                    {
                        xtype:'panel',
                        layout:'border',
                        border:false,
                        items:[
                            {
                                region:'west',
                                layout:'fit',
                                width:"50%",
                                border:false,
                                items:[
                                    {
                                        xtype:'ItemDetailTrackingTransport'
                                    }
                                ]
                            },
                            {
                                region:'center',
                                layout:'fit',
                                border:false,
                                items:[
                                    {
                                        layout:'border',
                                        border:false,
                                        items:[
                                            {
                                                region:'north',
                                                border:false,
                                                height:'50%',
                                                layout:'fit',
                                                items:[
                                                    {
                                                        xtype: 'dataview',
                                                        //id: gestion_transporte.id+'-grid-car',
                                                        layout:'fit',
                                                        store: Ext.create('Ext.data.Store',{
                                                            fields: [
                                                                {name: 'id', type: 'int'},
                                                                {name: 'nivel', type: 'string'}
                                                            ],
                                                            autoLoad:true,
                                                            proxy:{
                                                                type: 'ajax',
                                                                url: '/gestion/gestionTransporte/usr_sis_provincias/',
                                                                reader:{
                                                                    type: 'json',
                                                                    rootProperty: 'data'
                                                                },
                                                                extraParams:{
                                                                    sis_id: 1
                                                                }
                                                            },
                                                            listeners:{
                                                                load: function(obj, records, successful, opts){
                                                                }
                                                            }
                                                        }),
                                                        autoScroll: true,
                                                        loadMask:true,
                                                        autoHeight: false,
                                                        tpl: [
                                                            '<tpl for=".">',
                                                                '<div class="dataview-multisort-item">',
                                                                    '<img src="/images/icon/Logistic_shipping_delivery_service_transportation.png" />',
                                                                    '<h3>{name}</h3>',
                                                                '</div>',
                                                            '</tpl>'
                                                        ],
                                                        //component:{xtype:'ItemTrackingTransport'},
                                                        multiSelect: false,
                                                        singleSelect: false,
                                                        loadingText:'Cargando Imagenes...',
                                                        emptyText: '<div class="databox_list_transport"><div class="databox_none_data" ></div><div class="databox_title_clear_data">NO EXISTE REGISTRO</div></div>',
                                                        plugins: {
                                                            xclass: 'Ext.ux.DataView.Animated'
                                                        },
                                                        itemSelector: 'div.dataview-multisort-item',
                                                        trackOver: true,
                                                        overItemCls: 'databox_list_transport-hover',
                                                        listeners: {
                                                            'itemclick': function(view, record, item, idx, event, opts) {
                                                                
                                                            },
                                                            afterrender:function(obj){
                                                            }
                                                        }
                                                    }
                                                ]
                                            },
                                            {
                                                region:'center',
                                                border:false,
                                                layout:'fit',
                                                items:[
                                                    {
                                                        xtype:'GridNovedades',
                                                        //id:panel_novedades.id,
                                                        url:'/gestion/novedades/',
                                                        front:0,
                                                        autoLoad:true,
                                                        /*msn:1,
                                                        hist:1,*/
                                                        //records:panel_novedades.load_records
                                                    }
                                                ]
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ];
        me.callParent();
    }
});

