/**
 * @class Ext.global.plugin.MenuView
 * @extends Ext.form.Panel
 * @author Jim
 */
Ext.define('Ext.global.plugin.MenuView',{
    extend: 'Ext.Container',
    xtype: 'MenuView',
    config: {
        layout: 'border',
        autoScroll:false,
        border:false
    },
    id_nov:0,
    idx:-1,
    config_:'',
    datafirst:{},
    constructor: function(config){
        var me = this;
        me.config_=config;
        //console.log(config);
        var imageTplPointer = new Ext.XTemplate(
            '<tpl for=".">',
                '<tpl if="nivel==0">',
                    '<div class="databox_list_menu_select" >',
                        '<div class="databox_menu_title_first">',
                            '<span>{nombre}</span>',
                        '</div>',
                    '</div>',
                '</tpl>',
                '<tpl if="nivel!=0">',
                    '<div class="databox_list_menu_select" >',
                        '<div class="databox_list_menu" >',
                            '<div class="databox_menu_bx" >',
                                '<div class="">',
                                    '<img src="/images/menu/{icono}" />',
                                '</div>',
                            '</div>',
                            '<div class="databox_menu_line" >',
                                '<div class="databox_menu_bar">',
                                    '<div class="databox_menu_title">',
                                        '<span>{nombre}</span>',
                                    '</div>',
                                '</div>',
                            '</div>',
                        '</div>',
                    '</div>',
                '</tpl>',
            '</tpl>'
        );
        var store = Ext.create('Ext.data.Store',{
            fields: [
                {name: 'padre', type: 'string'},
                {name: 'nivel', type: 'string'},
                {name: 'nombre', type: 'string'},
                {name: 'url', type: 'string'},
                {name: 'icono', type: 'string'},
                {name: 'id_menu', type: 'string'},
                {name: 'menu_class', type: 'string'}
            ],
            autoLoad:true,
            proxy:{
                type: 'ajax',
                url: config.url+'getDataMenuView/',
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
                    console.log(records);
                }
            }
        });

        me.items=[
            {
                region:'center',
                layout:'fit',
                frame:true,
                border:false,
                bodyCls: 'white_bg',
                items:[
                    {
                        xtype: 'dataview',
                        id: config.id+'-menu-view',
                        layout:'fit',
                        store: store,
                        autoScroll: true,
                        loadMask:true,
                        autoHeight: false,
                        tpl: imageTplPointer,
                        multiSelect: false,
                        singleSelect: false,
                        loadingText:'Cargando Menu...',
                        emptyText: '<div class="databox_list_menu"><div class="databox_none_data" ></div><div class="databox_title_clear_data">NO TIENE NINGUNA NOVEDAD</div></div>',
                        itemSelector: 'div.databox_list_menu_select',
                        trackOver: true,
                        overItemCls: 'databox_list_menu-hover',
                        listeners: {
                            'itemclick': function(view, record, item, idx, event, opts) {
                                me.idx=idx;
                                var record = this.getStore().getAt(idx);
                                var val =record.data;
                                var menu_class = val.menu_class == null || val.menu_class == '' ? '' : val.menu_class;
                                if(val.nivel!=0)
                                win.show({vurl: val.url, id_menu: idx, class: menu_class});//obj.getItemId().split('-')[1]  
                                
                            }
                        }
                    }
                ]
            }
        ];
        me.callParent();
    }
});

