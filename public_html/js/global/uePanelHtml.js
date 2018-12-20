/**
 * @author: Luis Remicio
 */
Ext.define('Ext.global.uePanelHtml',{
    extend: 'Ext.Component',
    alias: 'widget.uePanelHtml',
    initComponent: function(){
        var me = this;
        
        this.renderData = {
            id: me.id,
            title: me.title,
            logo: (me.logo == '' || me.logo == undefined) ? 'info' : me.logo,
        };

        this.renderTpl = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="gk-panel-box">',
                    '<header>',
                        '<div class="cH">',
                            '<div class="logo"><span class="icon-{logo}" /></div>',
                            '<div class="cT">',
                                '<span class="title ue-uppercase">{title}</span>',
                            '</div>',
                        '</div>',
                    '</header>',
                    '<section id="ue-contenedor-{id}">',
                    '</section>',
                '</div>',
            '</tpl>'
        );

        this.listeners = {
            scope: this,
            afterrender: function(obj){
                var me = this;
            }
        }
        
        this.callParent();
    },
    setHtml: function(tpl, data){
        var me = this;
        tpl.overwrite('ue-contenedor-' + me.id);
    },
    setHtmlComponet:function(tpl, data){
        var me = this;
        tpl.overwrite('ue-contenedor-' + me.id,data);
    }    
});