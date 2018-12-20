<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('demoTable-tab')){
    var demoTable = {
        id: 'demoTable',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/inicio/index/',
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id', type: 'int'},
                    {name: 'nombre', type: 'string'},
                    {name: 'descripcion', type: 'string'}
                ],
                proxy:{
                    type: 'ajax',
                    url: demoTable.url+'get_dataTest/',
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

            var panel = Ext.create('Ext.form.Panel',{
                id: demoTable.id+'-form',
                border:false,
                layout: 'fit',
                defaults:{
                    border: false
                },
                items:[
                    {
                        xtype: 'grid',
                        id: demoTable.id + '-grid',
                        store: store,
                        columnLines: true,
                        plugins: [
                            {
                                ptype: 'rowexpander',
                                pluginId: demoTable.id + '-cellplugin',
                                rowBodyTpl : new Ext.XTemplate(
                                    '<div id="'+demoTable.id+'-{id}"></div>'
                                )
                            }
                        ],
                        columns:{
                            items:[
                                {
                                    text: 'Id',
                                    dataIndex: 'id'
                                },
                                {
                                    text: 'Name',
                                    dataIndex: 'nombre',
                                    flex: 1,
                                    hideable: false
                                },
                                {
                                    text: 'Descripción',
                                    width: 140,
                                    dataIndex: 'descripcion'
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
                        trackMouseOver: false,
                        listeners:{
                            afterrender: function(obj){
                                obj.getView().addListener('expandbody', function(rowNode, record, expandRow, eOpts){
                                    Ext.Ajax.request({
                                        url: demoTable.url + 'get_dataTest/',
                                        params:[],
                                        success: function(response, options){
                                            var res = Ext.JSON.decode(response.responseText);
                                            global.subtable({
                                                id: demoTable.id + '-subtable' + '-' + record.get('id'),
                                                columns:[
                                                    {
                                                        text: '',
                                                        width: '30px',
                                                        dataIndex: 'id',
                                                        align: 'center',
                                                        renderer: function(value, record){
                                                            return global.permisos({
                                                                type: 'link',
                                                                id_menu: demoTable.id_menu,
                                                                icons:[
                                                                    {id_serv: 8, img: 'detail.png', qtip: 'Click para ver detalle.', js: 'demoTable.test('+value+',\''+record.fecha+'\');'}
                                                                ]
                                                            });
                                                        }
                                                    },
                                                    {text: 'Id', width: '50px', dataIndex: 'id'},
                                                    {text: 'Nombre', width: '150px', dataIndex: 'nombre'},
                                                    {text: 'Descripción', width: 'auto', dataIndex: 'descripcion'}
                                                ],
                                                data: res.data,
                                                renderTo: demoTable.id + '-' + record.get('id')
                                            });
                                        }
                                    });
                                });

                                obj.getStore().load({
                                    params:{

                                    },
                                    callback: function(){

                                    }
                                });
                            }
                        }
                    }
                ]
            });

            tab.add({
                id: demoTable.id+'-tab',
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
                        global.state_item_menu(demoTable.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        Ext.getCmp(demoTable.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + demoTable.id_menu).text,
                            icon: Ext.getCmp('menu-' + demoTable.id_menu).icon
                        });
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(demoTable.id_menu, false);
                    }
                }
            }).show();
        },
        test: function(){
            console.log('Llegó');
        }
    }
    Ext.onReady(demoTable.init, demoTable);
}else{
    tab.setActiveTab(demoTable.id+'-tab');
}
</script>