/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */
Ext.define('Ext.global.MenuDB',{
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.menudb',
    requires:['Ext.data.Store'],
    border: false,
    initComponent: function(){
        var me = this;

        var store = me.getStore().load({
            params:{},
            callback: function(){
                var tbar = [];
                me.getStore().each(function(v, index){
                    var menu = null;
                    if (v.get('children').length > 0)
                        menu = me.getItemsMenu(v.get('children'));
                    tbar.push({
                        // id: 'menu-' + v.get('id_menu'), 
                        id: Ext.id(),
                        text: v.get('nombre'),
                        icon: '/images/menu/' + ( (v.get('icono') == null || v.get('icono') == '') ? 'form.png' : v.get('icono') ),
                        menu: menu
                    });
                });

                me.add(tbar);
            }
        });
        

        this.callParent();
    },
    getStore: function(){
        return this.store;
    },
    getItemsMenu: function(v){
        var items = [];
        Ext.Object.each(v, function(index, val){
            items.push({
                id: 'menu-' + val.id_menu, 
                text: val.nombre,
                icon: '/images/menu/' + ( (val.icono == null || val.icono == '') ? 'form.png' : val.icono ),
                permisos: Ext.JSON.encode(val.permisos),
                listeners:{
                    click: function(obj, item, e, eOpts){
                        var menu_class = val.menu_class == null || val.menu_class == '' ? '' : val.menu_class;
                        win.show({vurl: val.url, id_menu: obj.getItemId().split('-')[1], class: menu_class});
                    }
                }
            });
        });
        var menu = {
            items: items
        };
        return menu;
    }
});