<script type="text/javascript">


var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('registro_novedad-tab')){
    var registro_novedad = {
        id: 'registro_novedad',
        id_menu: '<?php echo $p["id_menu"];?>',
        url: '/gestion/novedades/',
        init:function(){
            
            var panel = Ext.create('Ext.form.Panel',{
                id: registro_novedad.id+'-form',
                border:false,
                layout: 'border',
                defaults:{
                    border: false
                },
                items:[
                    {
                        region:'west',
                        //frame:true,
                        width:320,
                        border:false,
                        split:true,
                        layout:'fit',
                        items:[
                            
                        ]
                    },
                    {
                        region:'center',
                        //frame:true,
                        border:true,
                        layout:'fit',
                        items:[
                            {
                                xtype:'GridNovedadesComentarios',
                                id:registro_novedad.id,
                                url:registro_novedad.url
                            }
                        ]
                    },
                    {
                        region:'east',
                        //frame:true,
                        width:300,
                        border:false,
                        split:true,
                        layout:'fit',
                        items:[
                            
                        ]
                    }
                ]
            });

            tab.add({
                id: registro_novedad.id+'-tab',
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
                        global.state_item_menu(registro_novedad.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        Ext.getCmp(registro_novedad.id+'-tab').setConfig({
                            title: Ext.getCmp('menu-' + registro_novedad.id_menu).text,
                            icon: Ext.getCmp('menu-' + registro_novedad.id_menu).icon
                        });
                    },
                    beforeclose: function(obj, opts){
                        global.state_item_menu(registro_novedad.id_menu, false);
                    }
                }
            }).show();
        }
    }
    Ext.onReady(registro_novedad.init, registro_novedad);
}else{
    tab.setActiveTab(registro_novedad.id+'-tab');
}
</script>
