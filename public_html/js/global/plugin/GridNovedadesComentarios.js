/**
 * @class Ext.global.plugin.GridNovedadesComentarios
 * @extends Ext.form.Panel
 * @author Jim
 */
Ext.define('Ext.global.plugin.GridNovedadesComentarios',{
    extend: 'Ext.Container',
    xtype: 'GridNovedadesComentarios',
    config: {
        layout: 'border',
        autoScroll:false
    },  
    config_:'',
    id_nov:0,
    download:1,
    clock_v:{},
    constructor: function(config){
        var me = this;
        me.config_=config;
        Ext.tip.QuickTipManager.init();
        Ext.QuickTips.init();
        me.clock_v = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'd/m/Y g:i:s A')});
        var imageTplPointer_cmt = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="databox_list_pointer" >',
                    '<div class="databox_img_user" >',

                    '</div>',
                    '<div class="databox_mensage" >',
                        '<div class="databox_bar">',
                            '<div class="databox_title">',
                                '<span>{usr_nombre}</span>',
                            '</div>',
                            '<div class="databox_date"><span class="dbx_user">{usr_codigo}</span><span class="dbx_fecha">{msn_fecha}</span><span class="dbx_hora">{msn_hora}</span></div>',
                        '</div>',
                        '<div class="databox_message">{msn_texto}</div>',
                    '</div>',
                    '<div class="{class_visto}" >{value_visto}',
                    '</div>',
                    '<div class="databox_tools" >',
                        '<a class="{class_user}"></a>',
                        '<a class="{class_download}" onclick="'+config.id+'.download_novedad({id_file})"></a>',
                        '<a class="{class_elimina}" onclick="'+config.id+'.elimina_novedad({msn_id},{id_nov},{elimina})"></a>',
                    '</div>',
                '</div>',
            '</tpl>'
        );
        var store_cmt = Ext.create('Ext.data.Store',{
            fields: [
                {name: 'msn_id', type: 'int'},
                {name: 'nov_id', type: 'int'},
                {name: 'prov_codigo', type: 'int'},
                {name: 'id_user', type: 'int'},
                {name: 'msn_texto', type: 'string'},
                {name: 'msn_fecha', type: 'string'},
                {name: 'usr_codigo', type: 'string'},
                {name: 'usr_nombre', type: 'string'},
                {name: 'class_download', type: 'string'}
            ],
            autoLoad:false,
            proxy:{
                type: 'ajax',

                url: config.url+'get_scm_lista_comentarios/',
                reader:{
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            listeners:{
                load: function(obj, records, successful, opts){
                    me.id_nov =records[0].get('id_nov');
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
                        id: config.id+'-nov-list_comentarios',
                        layout:'fit',
                        store: store_cmt,
                        autoScroll: true,
                        loadMask:true,
                        autoHeight: false,
                        tpl: imageTplPointer_cmt,
                        multiSelect: false,
                        singleSelect: false,
                        loadingText:'Cargando Comentarios...',
                        itemSelector: 'div.thumb-wrap',
                        emptyText: '<div class="databox_list_pointer"><div class="databox_none_data" ></div><div class="databox_title_clear_data">NOVEDAD NO TIENE COMENTARIOS</div></div>',
                        itemSelector: 'div.databox_list_pointer',
                        trackOver: true,
                        overItemCls: 'databox_list_pointer-hover',
                        listeners: {
                            'itemclick': function(view, record, item, idx, event, opts) {
                               //panel_novedades.filtra_novedad(record);
                               /*var obj = Ext.getCmp(panel_novedades.id+'-nov-list_comentarios');
                               obj.all.elements[idx].childNodes[2].className = 'databox_status_msn_off';*/
                            },
                            'afterrender':function(){
                            }
                        }
                    }
                ]
            },
            {
                region:'south',
                border:false,
                split:true,
                layout:'fit',
                height:120,
                bbar:[
                    Ext.create('Ext.form.Panel',{
                        id: config.id+'-nov-form-archivo-cmt',
                        padding:'0px 0px 0px 0px',
                        //layout:'form',
                        submitValue: true,
                        width:'100%',
                        bodyStyle: 'background: transparent',
                        border:false,
                        labelWidth:120,
                        items:[
                            {
                                xtype: 'filefield',
                                id:config.id+'-file-novedad_cmt',
                                name: 'nov-form-file',
                                emptyText: 'Seleccione un archivo',
                                fieldLabel: 'Archivo',
                                labelWidth: 55,
                                width: '100%',
                                anchor:'100%',
                                regex: /^.*\.(AIS|ais|BMP|bmp|BW|bw|CDR|cdr|CDT|cdt|CGM|cgm|CMX|cmx|CPT|xpt|DCX|dcx|DIB|dib|EMF|emf|GBR|gbr|GIF|gif|GIH|gih|ICO|ico|IFF|iff|ILBM|ilbm|JFIF|jfif|JIF|jif|JPE|jpe|JPEG|jpeg|JPG|jpg|KDC|kdc|LBM|lbm|MAC|mac|PAT|pat|PCD|pcd|PCT|pct|PCX|pcx|PIC|pic|PICT|pict|PNG|png|PNTG|pntg|PIX|pix|PSD|psd|PSP|psp|QTI|qti|QTIF|qtif|RGB|rgb|RGBA|rgba|RIF|rif|RLE|rle|SGI|sgi|TGA|tga|TIF|tif|TIFF|tiff|WMF|wmf|XCF|xcf|DIC|dic|DOC|doc|DIZ|diz|DOCHTML|dochtml|EXC|exc|IDX|idx|LOG|log|PDF|pfd|RTF|rtf|SCP|scp|TXT|txt|WRI|wri|WTX|wtx|DOT|dot|DOTHTML|dothtml|WBK|wbk|WIZ|wiz|CSV|csv|DIF|dif|DQY|dqy|XLA|xla|XLB|xlb|XLC|xlc|XLD|xld|XLK|xlk|XLL|xll|XLM|xlm|XLS|xls|XLSHTML|xlshtml|XLT|xlt|XLTHTML|xlthtml|XLV|xlv|XLW|xlw|DOCX|docx|odt|pdf|oft|zip|ZIP|rar|RAR|)$/,
                                regexText:'Archivo no aceptado, por favor seleccione otro archivo',
                                buttonConfig: {
                                    text : '',
                                    icon: '/images/icon/upload_reg.ico'
                                }
                            }
                        ],
                        listeners: {
                            render: {
                                fn: function(){
                                     Ext.fly(me.clock_v.getEl().parent()).addCls('x-status-text-panel').createChild({cls:'spacer'});
                                     Ext.TaskManager.start({
                                         run: function(){
                                             Ext.fly(me.clock_v.getEl()).update(Ext.Date.format(new Date(), 'd/m/Y g:i:s A'));
                                         },
                                         interval: 1000
                                     });
                                },
                                delay: 100
                            }
                        }
                    })
                ],
                items:[
                    {
                        xtype: 'textarea',
                        id:config.id+'-nov-comentario-cmt',
                        margin:2,
                        //flex: 1,
                        width: '100%',
                        anchor:'100%',
                        emptyText: '200 caracteres máximo permitidos...',
                        maxLength:200,
                        grow: true,
                        maxLengthText:'El maximo de caracteres permitidos para este campo es {0}',
                        enforceMaxLength:true
                    }
                ],
                tbar:[
                    {
                        xtype:'label',
                        width:5
                    },
                    '-',
                    me.clock_v,
                    '-',
                    {
                        xtype:'button',
                        text: 'Cerrar',
                        id:config.id+'-nov-cerrar-cmt',
                        hidden:(config.closed)?false:true,
                        icon: '/images/icon/padlock-closed.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                /*global.permisos({
                                    id: 13, 
                                    id_btn: obj.getId(), 
                                    id_menu: gestion_devolucion.id_menu,
                                    fn: ['panel_asignar_gestion.guardar']
                                });*/
                            },
                            click: function(obj, e){
                                if(me.id_nov!=0)me.show_cierre();
                            }
                        }
                    },
                    '-',
                    '->',
                    '-',
                    {
                        xtype: 'checkboxfield',
                        fieldLabel: '¿Pública?',
                        labelWidth: 60,
                        //boxLabel: '¿Novedad Pública?',
                        id:config.id+'_chk_public_cmt',
                        listeners:{
                            change:function(obj){
                                //if(obj.getValue())
                            }
                        }
                    },
                    '-',
                    {
                        xtype:'button',
                        text: 'Grabar',
                        id:config.id+'-nov-grabar-cmt',
                        icon: '/images/icon/save.png',
                        listeners:{
                            beforerender: function(obj, opts){
                                /*global.permisos({
                                    id: 13, 
                                    id_btn: obj.getId(), 
                                    id_menu: gestion_devolucion.id_menu,
                                    fn: ['panel_asignar_gestion.guardar']
                                });*/
                            },
                            click: function(obj, e){
                                me.registra_comentario();
                            }
                        }
                    },
                    '-',
                    {
                        xtype:'label',
                        width:5
                    }
                ]
            }
        ];
        me.callParent();
    },
    registra_comentario:function(){
        var me = this;
        //var store = Ext.getCmp(me.config_.id+'-nov-list_comentarios').getStore();
        //var count = store.getCount();
        if(me.id_nov==0 || me.id_nov==null){
            global.Msg({msg:"Seleccione una novedad antes de comentar por favor.",icon:2,fn:function(){}});
            return false;
        }
        var publico = Ext.getCmp(me.config_.id+'_chk_public_cmt').getValue();
        var comentario = Ext.getCmp(me.config_.id+'-nov-comentario-cmt').getValue();
        publico =(publico)?1:0;

        if(comentario== null || comentario==''){
            global.Msg({msg:"Ingrese un comentario por favor.",icon:2,fn:function(){}});
            return false;
        }
        var form = Ext.getCmp(me.config_.id+'-nov-form-archivo-cmt').getForm();

        form.submit({
            url:me.config_.url+'set_scm_registro_comentario',
            params:{
                vp_id_nov:me.id_nov,vp_msn_publico:publico,vp_msn_texto:comentario
            },
            waitMsg: 'Procesando Información...',
            success:function(fp,o){
                var res = Ext.JSON.decode(o.response.responseText);
                var res = res.data[0];
                if(parseInt(res.error_sql)<=0){

                    global.Msg({
                        msg:res.error_info,
                        icon:0,
                        fn:function(){
                                
                        }
                    });

                }else{
                    me.reload_comentarios();
                    me.limpia_registros();
                }
                                
            }
        },me);
     },
     reload_comentarios:function(){
        var me_ = this;
        Ext.getCmp(me_.config_.id+'-nov-list_comentarios').getStore().removeAll();
        Ext.getCmp(me_.config_.id+'-nov-list_comentarios').getStore().load(
            {params: {vp_id_nov: me_.id_nov},
            callback:function(){
                
            }
        });
    },
    limpia_registros:function(){
        var me = this;
        Ext.getCmp(me.config_.id+'-nov-comentario-cmt').setValue('');
        Ext.getCmp(me.config_.id+'-file-novedad_cmt').setValue('');
    },
    download:function(id_file){
        var me = this;
        if(me.download)return;
        document.location.href=this.config_.url+'get_forzar_descarga/?vp_id_file='+id;
    },
    show_cierre:function(){
        var me = this;
        var clock = Ext.create('Ext.toolbar.TextItem', {text: Ext.Date.format(new Date(), 'd/m/Y g:i:s A')});

        var motivo = Ext.create('Ext.data.Store',{
            fields: [
                {name: 'tnov_id', type: 'int'},
                {name: 'tnov_descri', type: 'string'},
                {name: 'mnov_publico', type: 'int'},
                {name: 'mnov_priori', type: 'int'}
            ],
            autoLoad:true,
            proxy:{
                type: 'ajax',
                url: me.config_.url+'get_scm_motivo/?vp_pnov_id=1',
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
        if(Ext.getCmp(me.id+'_responsable_cierre'))Ext.getCmp(me.id+'_responsable_cierre').destroy();
        if(Ext.getCmp(me.id+'_valor_cierre'))Ext.getCmp(me.id+'_valor_cierre').destroy();
        if(Ext.getCmp(me.id+'_motivo_cierre'))Ext.getCmp(me.id+'_motivo_cierre').destroy();

        var imageTplPointerPanelRegCierre = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="databox_panel_nov_reg_cierre" >',
                    '<div class="databox_nodedad_cierre" >',
                        '<div class="">CN',
                        
                        '</div>',
                    '</div>',
                    '<div class="databox_mensage" >',
                        '<div class="databox_bar">',
                            '<div class="databox_title_reg">',
                                '<span>CIERRE DE NOVEDAD</span>',
                            '</div>',
                        '</div>',
                        '<div class="databox_message">INGRESE LOS DATOS PARA CERRAR LA NOVEDAD</div>',
                    '</div>',
                    '<div class="databox_btools_reg">',
                        '<hr></hr>',
                        '<span id="responsable-cie-nov-box"></span>',
                        '<div id="valor-cie-nov-box"></div>',
                        '<span id="motivo-cie-nov-box"></span>',
                        '<hr></hr>',
                    '</div>',
                '</div>',
            '</tpl>'
        );
        var form = Ext.widget({
            xtype: 'panel',
            layout: 'border',
            border:false,
            frame:true,
            id: 'innerTabsForm',
            collapsible: false,
            bodyPadding: 0,
            bodyStyle: 'background: transparent',
            fieldDefaults: {
                labelAlign: 'top',
                msgTarget: 'side'
            },
            defaults: {
                anchor: '100%'
            },
            items:[
                {
                    region:'center',
                    border:false,
                    html:imageTplPointerPanelRegCierre
                },
                {
                    region:'south',
                    border:false,
                    split:true,
                    layout:'fit',
                    height:130,
                    bbar:[
                        Ext.create('Ext.form.Panel',{
                            id: me.id+'-nov-form-archivo-cmt-c',
                            padding:'0px 0px 0px 0px',
                            //layout:'form',
                            submitValue: true,
                            width:'100%',
                            bodyStyle: 'background: transparent',
                            border:false,
                            labelWidth:120,
                            items:[
                                {
                                    xtype: 'filefield',
                                    id:me.id+'-file-novedad_cmt-c',
                                    name: 'nov-form-file',
                                    emptyText: 'Seleccione un archivo',
                                    fieldLabel: 'Archivo',
                                    labelWidth: 55,
                                    width: '100%',
                                    anchor:'100%',
                                    regex: /^.*\.(AIS|ais|BMP|bmp|BW|bw|CDR|cdr|CDT|cdt|CGM|cgm|CMX|cmx|CPT|xpt|DCX|dcx|DIB|dib|EMF|emf|GBR|gbr|GIF|gif|GIH|gih|ICO|ico|IFF|iff|ILBM|ilbm|JFIF|jfif|JIF|jif|JPE|jpe|JPEG|jpeg|JPG|jpg|KDC|kdc|LBM|lbm|MAC|mac|PAT|pat|PCD|pcd|PCT|pct|PCX|pcx|PIC|pic|PICT|pict|PNG|png|PNTG|pntg|PIX|pix|PSD|psd|PSP|psp|QTI|qti|QTIF|qtif|RGB|rgb|RGBA|rgba|RIF|rif|RLE|rle|SGI|sgi|TGA|tga|TIF|tif|TIFF|tiff|WMF|wmf|XCF|xcf|DIC|dic|DOC|doc|DIZ|diz|DOCHTML|dochtml|EXC|exc|IDX|idx|LOG|log|PDF|pfd|RTF|rtf|SCP|scp|TXT|txt|WRI|wri|WTX|wtx|DOT|dot|DOTHTML|dothtml|WBK|wbk|WIZ|wiz|CSV|csv|DIF|dif|DQY|dqy|XLA|xla|XLB|xlb|XLC|xlc|XLD|xld|XLK|xlk|XLL|xll|XLM|xlm|XLS|xls|XLSHTML|xlshtml|XLT|xlt|XLTHTML|xlthtml|XLV|xlv|XLW|xlw|DOCX|docx|odt|pdf|oft|zip|ZIP|rar|RAR|)$/,
                                    regexText:'Archivo no aceptado, por favor seleccione otro archivo',
                                    buttonConfig: {
                                        text : '',
                                        icon: '/images/icon/upload_reg.ico'
                                    }
                                }
                            ],
                            listeners: {
                                render: {
                                    fn: function(){
                                         Ext.fly(clock.getEl().parent()).addCls('x-status-text-panel').createChild({cls:'spacer'});
                                         Ext.TaskManager.start({
                                             run: function(){
                                                 Ext.fly(clock.getEl()).update(Ext.Date.format(new Date(), 'd/m/Y g:i:s A'));
                                             },
                                             interval: 1000
                                         });
                                    },
                                    delay: 100
                                }
                            }
                        })
                    ],
                    items:[
                        {
                            xtype: 'textarea',
                            id:me.id+'-nov-comentario-cmt-cc',
                            margin:2,
                            //flex: 1,
                            width: '100%',
                            anchor:'100%',
                            emptyText: '200 caracteres máximo permitidos...',
                            maxLength:200,
                            grow: true,
                            maxLengthText:'El maximo de caracteres permitidos para este campo es {0}',
                            enforceMaxLength:true
                        }
                    ],
                    tbar:[
                        {
                            xtype:'label',
                            width:5
                        },
                        '-',
                        clock,
                        '-',
                        '->',
                        '-',
                        {
                            xtype: 'checkboxfield',
                            fieldLabel: '¿Pública?',
                            labelWidth: 60,
                            //boxLabel: '¿Novedad Pública?',
                            id:me.id+'_chk_public_cmt_c',
                            listeners:{
                                change:function(obj){
                                    //if(obj.getValue())
                                }
                            }
                        },
                        '-',
                        {
                            xtype:'button',
                            text: 'Grabar',
                            icon: '/images/icon/save.png',
                            listeners:{
                                beforerender: function(obj, opts){
                                    /*global.permisos({
                                        id: 13, 
                                        id_btn: obj.getId(), 
                                        id_menu: gestion_devolucion.id_menu,
                                        fn: ['panel_asignar_gestion.guardar']
                                    });*/
                                },
                                click: function(obj, e){
                                    me.registra_cierre();
                                }
                            }
                        },
                        '-',
                        {
                            xtype:'button',
                            text: 'Salir',
                            icon: '/images/icon/get_back.png',
                            listeners:{
                                beforerender: function(obj, opts){
                                    /*global.permisos({
                                        id: 16, 
                                        id_btn: obj.getId(), 
                                        id_menu: gestion_devolucion.id_menu,
                                        fn: ['panel_asignar_gestion.salir']
                                    });*/
                                },
                                click: function(obj, e){
                                    me.salir_cierre();
                                }
                            }
                        },
                        '-',
                        {
                            xtype:'label',
                            width:5
                        }
                    ],
                    listeners:{
                        'afterrender':function(){
                            Ext.create('Ext.form.field.Text', {
                                id:me.id+'_responsable_cierre',
                                renderTo:'responsable-cie-nov-box',
                                fieldLabel: 'Responsable',
                                labelAlign: 'right',
                                labelWidth:80,
                                width: '100%',
                                anchor: '100%'
                            });
                            Ext.create('Ext.form.field.Number', {
                                id:me.id+'_valor_cierre',
                                renderTo:'valor-cie-nov-box',
                                fieldLabel: 'Valor',
                                labelAlign: 'right',
                                labelWidth:85,
                                width: '100%',
                                anchor: '100%'
                            });
                            Ext.create('Ext.form.ComboBox', {
                                fieldLabel: 'Motivo',
                                id:me.id+'_motivo_cierre',
                                renderTo:'motivo-cie-nov-box',
                                store: motivo,
                                queryMode: 'local',
                                triggerAction: 'all',
                                valueField: 'tnov_id',
                                displayField: 'tnov_descri',
                                emptyText: '[Seleccione]',
                                labelAlign: 'right',
                                //allowBlank: false,
                                labelWidth: 80,
                                width:'100%',
                                anchor:'100%',
                                //readOnly: true,
                                listeners:{
                                    afterrender:function(obj, e){
                                        // obj.getStore().load();
                                    },
                                    select:function(obj, records, eOpts){
                                        if(parseInt(records[0].data.mnov_publico)==1){
                                            Ext.getCmp(me.id+'_chk_public_cmt_c').setValue(true);
                                            Ext.getCmp(me.id+'_chk_public_cmt_c').setDisabled(false);
                                        }else{
                                            Ext.getCmp(me.id+'_chk_public_cmt_c').setValue(false);
                                            Ext.getCmp(me.id+'_chk_public_cmt_c').setDisabled(true);
                                        }
                                        
                                    }
                                }
                            });
                        }
                    }
                }
            ]
        });

        this.win = Ext.create('Ext.window.Window',{
            id:me.id+'-win-cierre',
            plain: true,
            //title:'CIERRE DE NOVEDAD',
            //icon: '/images/icon/alert_red_min.ico',
            height: 380,
            width: 450,
            resizable:false,
            layout:{
                type:'fit'
            },
            modal: true,
            border:false,
            closable:true,
            items:[form],
            listeners:{
                'afterrender':function(obj, e){ 
                    //panel_asignar_gestion.getDatos();
                },
                'close':function(){
                    //if(panel_asignar_gestion.guarda!=0)gestion_devolucion.buscar();
                }
            }
        }).show().center();
    },
    registra_cierre:function(){
        var me = this;
        var responsable = Ext.getCmp(me.id+'_responsable_cierre').getValue();
        var valor = Ext.getCmp(me.id+'_valor_cierre').getValue();
        var motivo = Ext.getCmp(me.id+'_motivo_cierre').getValue();
        var publico = Ext.getCmp(me.id+'_chk_public_cmt_c').getValue();
        var file = Ext.getCmp(me.id+'-file-novedad_cmt-c').getValue();
        var comentario = Ext.getCmp(me.id+'-nov-comentario-cmt-cc').getValue();
        publico =(publico)?1:0;

        /*if(responsable== null || responsable==''){
            global.Msg({msg:"Ingrese un Responsable de Cierre de la Novedad",icon:2,fn:function(){}});
            return false; 
        }

        if(valor== null || valor==''){
            global.Msg({msg:"Ingrese un valor",icon:2,fn:function(){}});
            return false; 
        }*/
        if(motivo== null || motivo==''){
            global.Msg({msg:"Seleccione un motivo por favor.",icon:2,fn:function(){}});
            return false; 
        }

        if(comentario== null || comentario==''){
            global.Msg({msg:"Ingrese un comentario por favor.",icon:2,fn:function(){}});
            return false;
        }
        global.Msg({
            msg: '¿Seguro de Crear la Novedad?',
            icon: 3,
            buttons: 3,
            fn: function(btn){
                if (btn == 'yes'){

                    var form = Ext.getCmp(me.id+'-nov-form-archivo-cmt-c').getForm();

                    form.submit({
                        url:me.config_.url+'set_scm_registro_cierre',
                        params:{
                            vp_id_nov:me.id_nov,vp_responsable:responsable,vp_valor:valor,vp_tnov_id:motivo,vp_msn_publico:publico,vp_msn_texto:comentario
                        },
                        waitMsg: 'Procesando Información...',
                        success:function(fp,o){
                            var res = Ext.JSON.decode(o.response.responseText);
                            var res = res.data[0];
                            if(parseInt(res.error_sql)<=0){

                                global.Msg({
                                    msg:res.error_info,
                                    icon:0,
                                    fn:function(){
                                            
                                    }
                                });

                            }else{
                                global.Msg({
                                    msg:res.error_info,
                                    icon:1,
                                    fn:function(){
                                        Ext.getCmp(me.id+'-win-cierre').close();
                                    }
                                });
                                

                            }
                                            
                        }
                    },me);
                }
            }
        });
    },
    salir_cierre:function(){
        Ext.getCmp(this.id+'-win-cierre').close();
    }
});