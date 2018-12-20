/**
 * @class Ext.global.plugin.GridNovedadesHistorico
 * @extends Ext.form.Panel
 * @author Jim
 */
Ext.define('Ext.global.plugin.GridNovedadesHistorico',{
    extend: 'Ext.Container',
    xtype: 'GridNovedadesHistorico',
    config: {
        layout: 'border',
        autoScroll:false
    },  
    constructor: function(config){
        var me = this;
        var imageTplPointer_hst = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="databox_list_pointer" >',
                    '<div class="databox_nodedad_hst" >',
                        '<div class="">{linea}',
                        
                        '</div>',
                    '</div>',
                    '<div class="databox_mensage" >',
                        '<div class="databox_bar">',
                            '<div class="databox_title">',
                                '<span>{titulo}</span>',
                            '</div>',
                            '<div class="databox_date"><span class="dbx_user">{usr_codigo}</span><span class="dbx_fecha">{fecha}</span></div>',
                        '</div>',
                        '<div class="databox_message">{msn}</div>',
                    '</div>',
                    '<div class="{class_estado}" >{val_estado}',
                    '</div>',
                    '<div class="{class_cerrado}" >{val_cerrado}',
                    '</div>',
                    '<div class="databox_btools">',
                        '<hr></hr>',
                        '<span><p>DOC:</p>{doc_numero}</span><span><p>TIPO:</p>{tipo_doc}</span><span></span>',
                    '</div>',
                '</div>',
            '</tpl>'
        );
        var store = Ext.create('Ext.data.Store',{
            fields: [
                {name: 'nov_id', type: 'int'},
                {name: 'line', type: 'string'},
                {name: 'class_line', type: 'string'},
                {name: 'msn', type: 'string'},
                {name: 'fecha', type: 'string'},
                {name: 'clase', type: 'string'},
                {name: 'documento', type: 'string'},
                {name: 'doc_numero', type: 'string'},
                {name: 'estado', type: 'string'},
                {name: 'file_nombre', type: 'string'},
                {name: 'agencia', type: 'int'},
                {name: 'agencia_origen', string: 'string'},
                {name: 'doc_id', type: 'int'}
            ],
            autoLoad:false,
            proxy:{
                type: 'ajax',
                url: config.url+'get_scm_lista_novedades/',
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

        me.items=[
            {
                region:'center',
                layout:'fit',
                frame:true,
                items:[
                    {
                        xtype: 'dataview',
                        id: config.id+'-nov-list_historico',
                        layout:'fit',
                        store: store,
                        autoScroll: true,
                        loadMask:true,
                        autoHeight: false,
                        tpl: imageTplPointer_hst,
                        multiSelect: false,
                        singleSelect: false,
                        loadingText:'Cargando Novedades Relacionadas...',
                        itemSelector: 'div.thumb-wrap',
                        emptyText: '<div class="databox_list_pointer"><div class="databox_none_data" ></div><div class="databox_title_clear_data">NOVEDAD NO TIENE DOCUMENTOS RELACIONADOS</div></div>',
                        itemSelector: 'div.databox_list_pointer',
                        trackOver: true,
                        overItemCls: 'databox_list_pointer-hover',
                        listeners: {
                            'itemclick': function(view, record, item, idx, event, opts) {
                               if(config.records)config.records(view, record, item, idx, event, opts,'H');
                            },
                            'afterrender':function(){
                            }
                        }
                    }
                ]
            }
        ];
        me.callParent();
    }
});