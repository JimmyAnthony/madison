/**
 * @author @Jim
 */
Ext.define('Ext.global.uePanelS',{
    extend: 'Ext.Component',
    alias: 'widget.uePanelS',
    border: false,
    config: {
        //layout: 'border',
        autoScroll:false
    },
    random:0,
    initComponent: function(){
        var me = this;
        me.random= Math.floor((Math.random() * 1000) + 1);

        this.renderData = {
            logo: me.logo,
            title: me.title,
            legend: me.legend,
            items: me.getItemsRender()
        };

        this.renderTpl = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="ue-panelS">',
                    '<header>',
                        '<div class="logo"><span>{logo}</span></div>',
                        '<div class="title ue-uppercase">{title}</div>',
                        '<div class="legend ue-uppercase">{legend}</div>',
                    '</header>',
                    '<div class="separator"></div>',
                        '<section>',
                            '<tpl for="items">',
                                '<div class="item" id="' + me.id + '-ue-panel-item-{id}"></div>',
                            '</tpl>',
                        '</section>',
                    '</div>',
            '</tpl>'
        );

        this.listeners = {
            render: function(obj){
                Ext.Object.each(obj.items, function(key, value){
                    Ext.create('Ext.panel.Panel',{
                        id: me.id + '-ue-panel-parent-' + key,
                        items: value,
                        border: false,
                        defaults:{
                            border: false
                        },
                        renderTo: me.id + '-ue-panel-item-' + key
                    });
                });
            },
            beforedestroy: function(obj, eopts){
                Ext.each(obj.items, function(value, key){
                    Ext.getCmp(me.id + '-ue-panel-parent-' + key).destroy();
                });
            }
        }

        this.callParent();
    },
    getItemsRender: function(){
        var me = this;
        var a = [];
        for(var i = 0; i < me.items.length; ++i)
            a.push({id: i});
        return a;
    }
});