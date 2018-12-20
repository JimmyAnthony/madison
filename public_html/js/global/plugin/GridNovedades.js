/**
 * @class Ext.global.plugin.GridNovedades
 * @extends Ext.form.Panel
 * @author Jim
 */
Ext.define('Ext.global.plugin.GridNovedades',{
    extend: 'Ext.Container',
    xtype: 'GridNovedades',
    config: {
        layout: 'border',
        autoScroll:false
    },
    id_nov:0,
    idx:-1,
    config_:'',
    datafirst:{},
    constructor: function(config){
        var me = this;
        me.config_=config;
        //console.log(config);
        var imageTplPointer = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="databox_list_pointer" >',
                    '<div class="{class_line}" >',
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
        var auto =(config.autoLoad)?true:false;
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
                {name: 'doc_id', type: 'int'},
                {name: 'tipo_novedad', string: 'string'},
                {name: 'publico', string: 'int'},
                {name: 'estado_decripcion', string: 'string'},
                {name: 'nov_estado', string: 'int'}
            ],
            autoLoad:auto,
            proxy:{
                type: 'ajax',
                url: config.url+'get_scm_lista_novedades/',
                reader:{
                    type: 'json',
                    rootProperty: 'data'
                },
                extraParams:{
                    front: ((config.front=='')?0:config.front)
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
                        id: config.id+'-nov-lista',
                        layout:'fit',
                        store: store,
                        autoScroll: true,
                        loadMask:true,
                        autoHeight: false,
                        tpl: imageTplPointer,
                        multiSelect: false,
                        singleSelect: false,
                        loadingText:'Cargando Lista de Novedades...',
                        itemSelector: 'div.thumb-wrap',
                        emptyText: '<div class="databox_list_pointer"><div class="databox_none_data" ></div><div class="databox_title_clear_data">NO TIENE NINGUNA NOVEDAD</div></div>',
                        itemSelector: 'div.databox_list_pointer',
                        trackOver: true,
                        overItemCls: 'databox_list_pointer-hover',
                        listeners: {
                            'itemclick': function(view, record, item, idx, event, opts) {
                                me.idx=idx;
                                if(config.msn)me.filtra_novedad(record);
                                if(config.hist)me.reload_historico();
                                if(config.records)config.records(view, record, item, idx, event, opts,'N');
                            },
                            'afterrender':function(){
                                Ext.getCmp(config.id+'-nov-lista').refresh();
                                Ext.getCmp(config.id+'-nov-lista').refresh();
                            }
                        }
                    }
                ]
            }
        ];
        me.callParent();
    },
    filtra_novedad:function(record){
           this.remove_novedad();
           this.id_nov=record.data.id_nov;
           this.datafirst=record.data;
           this.reload_comentarios();
    },
    remove_novedad:function(){
        this.id_nov=0;
        Ext.getCmp(this.config_.id+'-nov-list_comentarios').getStore().removeAll();
    },
    reload_comentarios:function(){
        me_ = this;
        var obj = Ext.getCmp(me_.config_.id+'-nov-lista');
        Ext.getCmp(me_.config_.id+'-nov-list_comentarios').getStore().load(
            {params: {vp_id_nov: me_.id_nov},
            callback:function(){
                Ext.getCmp(me_.config_.id+'-nov-list_comentarios').refresh();
                obj.all.elements[me_.idx].childNodes[2].className = 'databox_status_off';
                me_.idx=-1;
                setTimeout( "me_.remove_visto()", 2000 );
                if(me_.config_.hist)me_.reload_historico();
            }
        });
    },
    remove_visto:function(){
        var obj_ = Ext.getCmp(this.config_.id+'-nov-list_comentarios');
        Ext.Object.each(obj_.all.elements, function(index, v){
            obj_.all.elements[index].childNodes[2].className = 'databox_status_msn_off';
        });
    },
    remove_historico:function(){
        Ext.getCmp(me_.config_.id+'-nov-list_historico').refresh();
        Ext.getCmp(me_.config_.id+'-nov-list_historico').getStore().removeAll();
    },
    reload_historico:function(){
        me_ = this;
        Ext.getCmp(me_.config_.id+'-nov-list_historico').refresh();
        Ext.getCmp(me_.config_.id+'-nov-list_historico').getStore().removeAll();
        if(me_.datafirst.doc_numero!='' ){
            var obj = Ext.getCmp(me_.config_.id+'-nov-list_historico');
            Ext.getCmp(me_.config_.id+'-nov-list_historico').getStore().load(
                {params: {vp_id_nov: me_.datafirst.id_nov,vp_doc_numero:me_.datafirst.doc_numero},
                callback:function(){
                    Ext.getCmp(me_.config_.id+'-nov-list_historico').refresh();
                }
            });
        }
    },
    load_detalle:function(record){
        me_ = this;
        var imageTplPointerPanel = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="databox_panel_fill" >',
                    '<div class="databox_nodedad_panel" >',
                        '<div class="">NV',
                        
                        '</div>',
                    '</div>',
                    '<div class="databox_mensage" >',
                        '<div class="databox_bar">',
                            '<div class="databox_title">',
                                '<span>'+record.data.titulo+'</span>',
                            '</div>',
                            '<div class="databox_date"><span class="dbx_user">'+record.data.usr_codigo+'</span><span class="dbx_fecha">'+record.data.fecha+'</span></div>',
                        '</div>',
                        '<div class="databox_message">'+record.data.msn+'</div>',
                    '</div>',
                    '<div class="databox_btools">',
                        '<hr></hr>',
                        '<span><p>PROVINCIA:</p>'+record.data.provincia+'</span>',
                        '<hr></hr>',
                        '<span><p>DOC:</p>'+record.data.tipo_doc+'</span><span><p>TIPO:</p>'+record.data.doc_numero+'</span>',
                        '<hr></hr>',
                        '<span><p>PROCESO:</p></span><span>'+record.data.checkd+'</span>',
                        '<hr></hr>',
                        '<span><p>MOTIVO:</p></span><span>'+record.data.motivo+'</span>',
                        '<hr></hr>',
                        '<span><p>TIPO NOVEDAD:</p></span><span>'+record.data.tipo_novedad+'</span>',
                        '<hr></hr>',
                    '</div>',
                '</div>',
            '</tpl>'
        );
        Ext.getCmp(me_.config_.id+'-panel-detalle').setHtml(imageTplPointerPanel);
    }
});

