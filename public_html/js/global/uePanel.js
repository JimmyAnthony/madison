/**
 * @author @remicioluis
 */
Ext.define('Ext.global.uePanel',{
    extend: 'Ext.Component',
    alias: 'widget.uePanel',
    border: false,
    initComponent: function(){
        var me = this;

        this.renderData = {
            logo: (me.logo == '' || me.logo == undefined) ? 'location' : me.logo,
            title: me.title,
            legend: me.legend,
            color:me.color,
            bg: (me.bg == '' || me.bg == undefined) ? '#696969' : me.bg,
            items: me.getItemsRender(),
            sectionStyle: (me.sectionStyle == '' || me.sectionStyle == undefined) ? 'margin: 5px 10px' : me.sectionStyle,
            bclose: me.bclose == true ? '1' : '0'
        };

        this.renderTpl = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="gk-panel" style="border-left: 0px solid {bg}">',
                    '<header style="background-color:{bg}" class="{color}">',
                        '<div class="cH">',
                            '<div class="logo"><span class="icon-{logo}" /></div>',
                            '<div class="cT">',
                                '<span class="title ue-uppercase">{title}</span>',
                                '<span class="sub-title ue-uppercase">{legend}</span>',
                            '</div>',
                        '</div>',
                        '<tpl if="bclose != \'0\'" >',
                            '<div class="cC">',
                                '<span id="' + me.id + '-bclose" class="icon-close"></span>',
                            '</div>',
                        '</tpl>',
                    '</header>',
                    '<section style="{sectionStyle}">',
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
                if (me.bclose){
                    Ext.get(me.id + '-bclose').on('click', function(){
                        me.evt_close();
                    });
                }
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
    },
    evt_close: function(){
        /**
         * Close evt.
         */
    }
});