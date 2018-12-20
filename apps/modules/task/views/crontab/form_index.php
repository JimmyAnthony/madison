<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('crontab-tab')){
    var crontab = {
        id: 'crontab',
        url: '',
        init:function(){

            var store = Ext.create('Ext.data.Store',{
                fields: [
                    {name: '', type: 'string'}
                ],
                proxy:{
                    type: 'ajax',
                    url: crontab.url+'/',
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
                id: crontab.id+'-form',
                border:false
            });

            tab.add({
                title: 'crontab',
                id: crontab.id+'-tab',
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
        }
    }
    Ext.onReady(crontab.init, crontab);
}else{
    tab.setActiveTab(crontab.id+'-tab');
}
</script>
