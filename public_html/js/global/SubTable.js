/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */
Ext.define('Ext.global.SubTable',{
    extend: 'Ext.grid.plugin.RowExpander',
    alias: 'plugin.subtable',
    rowBodyTpl: "<p>Default row body</p>",
    constructor: function(config){
        var me = this;

        Ext.apply(me, config);

        me.superclass.superclass.constructor.call(me, [config]);

        var grid = me.getCmp();

        me.recordsExpanded = {};

        if (!me.url) {
            Ext.Error.raise("The 'url' config is required and is not defined.");
        }

        me.rowBodyTpl = Ext.create('Ext.XTemplate', me.rowBodyTpl);

        features = me.getFeatureConfig(grid);

        if (grid.features){
            grid.features = Ext.Array.push(features, grid.features);
        }else{
            grid.features = features;
        }

    },
    getFeatureConfig: function(grid){
        var me = this,
            features = [],
            featuresCfg = {
                ftype: 'rowbody',
                rowExpander: me,
                bodyBefore: me.bodyBefore,
                recordsExpanded: me.recordsExpanded,
                rowBodyHiddenCls: me.rowBodyHiddenCls,
                rowCollapsedCls: me.rowCollapsedCls,
                setupRowData: me.getRowBodyFeatureData,
                setup: me.setup
            };

        features.push(Ext.apply({
            lockableScope: 'normal',
            getRowBodyContents: me.getRowBodyContentsFn(me.rowBodyTpl)
        }, featuresCfg));

        
        
        
        if (grid.enableLocking) {
            features.push(Ext.apply({
                lockableScope: 'locked',
                getRowBodyContents: me.lockedTpl ? me.getRowBodyContentsFn(me.lockedTpl) : function() {return '';}
            }, featuresCfg));
        }

        return features;
    },
        expandbody: function(rowNode, record, expandRow, eOpts){
            console.log(rowNode);
        }
});