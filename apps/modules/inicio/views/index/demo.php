<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('demo-tab')){
    var demo = {
        id: 'demo',
        url: '',
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: '', type: 'string'}
                ],
                proxy:{
                    type: 'ajax',
                    url: demo.url+'/',
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
                id: demo.id+'-form',
                border:false,
                tbar:[
                    {
                        text: 'Nuevo',
                        icon: '/images/icon/add.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id: 1, 
                                    id_btn: obj.getId(), 
                                    id_menu: '<?php echo $p["id_menu"];?>', 
                                    fn: ['demo.agregar']
                                });
                            },
                            click: function(obj, e){

                            }
                        }
                    },
                    {
                        text: 'Consultar',
                        icon: '/images/icon/search.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id: 4, 
                                    id_btn: obj.getId(), 
                                    id_menu: '<?php echo $p["id_menu"];?>', 
                                    fn: []
                                });
                            },
                            click: function(obj, e){

                            }
                        }
                    },
                    {
                        text: 'Ver detalle',
                        icon: '/images/icon/detail.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                global.permisos({
                                    id: 8, 
                                    id_btn: obj.getId(), 
                                    id_menu: '<?php echo $p["id_menu"];?>', 
                                    fn: []
                                });
                            },
                            click: function(obj, e){

                            }
                        }
                    }
                ]
            });

            tab.add({
                title: 'demo',
                id: demo.id+'-tab',
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
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                    }
                }
            }).show();
        },
        agregar: function(){
            console.log('Esta funcion es para agregar!!!');
        }
    }
    Ext.onReady(demo.init, demo);
}else{
    tab.setActiveTab(demo.id+'-tab');
}
</script>
