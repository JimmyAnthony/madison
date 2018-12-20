/**
 * @class Ext.global.plugin.RegistroNovedades
 * @extends Ext.form.Panel
 * @author Jim
 */
Ext.define('Ext.global.plugin.RegistroNovedades', {
     id: 'RN',
     url:'/gestion/novedades/',
     loadPro:0,
     omega:[1,1,1,0],//config type of doc - (4 GE)
     show_novedad: function(s) {
        var me = this;
        var linea = Ext.create('Ext.data.Store',{
            fields: [
                {name: 'id', type: 'int'},
                {name: 'nombre', type: 'string'}
            ],
            autoLoad:true,
            proxy:{
                type: 'ajax',

                url: me.url+'get_scm_linea/',
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
        var check = Ext.create('Ext.data.Store',{
            fields: [
                {name: 'id_elemento', type: 'int'},
                {name: 'descripcion', type: 'string'}
            ],
            autoLoad:true,
            proxy:{
                type: 'ajax',

                url: me.url+'get_scm_check_novedad/?vp_tipo=NOV',
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
        var shipper = Ext.create('Ext.data.Store',{
            fields: [
                {name: 'shi_codigo', type: 'int'},
                {name: 'shi_nombre', type: 'string'}
            ],
            autoLoad:true,
            proxy:{
                type: 'ajax',
                url: me.url+'get_scm_shipper/',
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
        var motivo = Ext.create('Ext.data.Store',{
            fields: [
                {name: 'tnov_id', type: 'int'},
                {name: 'tnov_descri', type: 'string'},
                {name: 'mnov_publico', type: 'int'},
                {name: 'mnov_priori', type: 'int'}
            ],
            autoLoad:false,
            proxy:{
                type: 'ajax',

                url: me.url+'get_scm_motivo/',
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
        var tipo_doc = Ext.create('Ext.data.Store',{
            fields: [
                {name: 'id_elemento', type: 'int'},
                {name: 'descripcion', type: 'string'}
            ],
            autoLoad:true,
            proxy:{
                type: 'ajax',

                url: me.url+'get_scm_check_novedad/?vp_tipo=NTD',
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
        var provincias = Ext.create('Ext.data.Store',{
            fields: [
                {name: 'prov_codigo', type: 'int'},
                {name: 'prov_nombre', type: 'string'},
                {name: 'prov_sigla', type: 'string'}
            ],
            autoLoad:false,
            proxy:{
                type: 'ajax',

                url: me.url+'get_scm_provincias/',
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
        
        if(Ext.getCmp(me.id+'_linea'))Ext.getCmp(me.id+'_linea').destroy();
        if(Ext.getCmp(me.id+'_check'))Ext.getCmp(me.id+'_check').destroy();
        if(Ext.getCmp(me.id+'_shipper'))Ext.getCmp(me.id+'_shipper').destroy();
        if(Ext.getCmp(me.id+'_motivo'))Ext.getCmp(me.id+'_motivo').destroy();
        if(Ext.getCmp(me.id+'_chk_public'))Ext.getCmp(me.id+'_chk_public').destroy();
        if(Ext.getCmp(me.id+'_chk_doc'))Ext.getCmp(me.id+'_chk_doc').destroy();
        if(Ext.getCmp(me.id+'_chk_doc'))Ext.getCmp(me.id+'_chk_doc').destroy();
        if(Ext.getCmp(me.id+'_tipo_doc'))Ext.getCmp(me.id+'_tipo_doc').destroy();
        if(Ext.getCmp(me.id+'_provincia'))Ext.getCmp(me.id+'_provincia').destroy();
        if(Ext.getCmp(me.id+'_nro_doc'))Ext.getCmp(me.id+'_nro_doc').destroy();

        var imageTplPointerPanelReg = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="databox_panel_nov_reg" >',
                    '<div class="databox_nodedad_panel" >',
                        '<div class="">RN',
                        
                        '</div>',
                    '</div>',
                    '<div class="databox_mensage" >',
                        '<div class="databox_bar">',
                            '<div class="databox_title_reg">',
                                '<span>REGISTRO DE NOVEDADES</span>',
                            '</div>',
                        '</div>',
                        '<div class="databox_message">INGRESE LOS DATOS PARA GENERAR UNA NUEVA NOVEDAD</div>',
                    '</div>',
                    '<div class="databox_btools_reg">',
                        '<hr></hr>',
                        '<span id="linea-nov-box"></span>',
                        '<div id="shipper-nov-box" class="box-display-reg-on"></div>',
                        '<span id="proceso-nov-box"></span>',
                        '<span id="motivo-nov-box"></span>',
                        '<hr></hr>',
                        '<span><p id="tipo-novedad-nov-box"></p><p id="tipo-public-nov-box"></p></span>',
                        '<div id="tipo-doc-nov-box" class="box-display-reg-off"></div>',
                        '<div id="provincia-nov-box" class="box-display-reg-off"></div>',
                        '<div id="nro-doc-nov-box" class="box-display-reg-off"></div>',
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
                    bodyStyle: 'background: #fff',
                    border:false,
                    html:imageTplPointerPanelReg
                },
                {
                    region:'south',
                    border:false,
                    //split:true,
                    layout:'fit',
                    height:125,
                    items:[
                        {
                            xtype: 'textarea',
                            id:me.id+'-nov-comentario-r',
                            margin:2,
                            //flex: 1,
                            width: '100%',
                            anchor:'100%',
                            emptyText: 'Escribo un comentario...',
                            maxLength:200,
                            grow: true,
                            maxLengthText:'El maximo de caracteres permitidos para este campo es {0}',
                            enforceMaxLength:true
                        }
                    ],
                    tbar:[
                        Ext.create('Ext.form.Panel',{
                            id: me.id+'-nov-form-archivo-c',
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
                                    id:me.id+'-file-novedad',
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
                            ]
                        })
                    ],
                    bbar:[
                        {
                            xtype:'panel',
                            bodyStyle: 'background: transparent',
                            height:13,
                            html:'* <a id="txt_maxLengthText" style="color:red;"></a>200 caracteres máximo permitidos',
                            border:false
                        }
                    ]
                }
            ],
            bbar:[
                '-',
                {
                    xtype:'button',
                    text: 'Limpiar',
                    icon: '/images/icon/new_file.ico',
                    listeners:{
                        beforerender: function(obj, opts){
                            /*global.permisos({
                                id: 15,
                                id_btn: obj.getId(), 
                                id_menu: gestion_devolucion.id_menu,
                                fn: ['panel_asignar_gestion.limpiar']
                            });*/
                        },
                        click: function(obj, e){
                            me.limpiar();
                        }
                    }
                },
                '-',
                '->',
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
                            me.registra_novedad();
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
                            me.salir();
                        }
                    }
                },
                '-'
            ],
            listeners:{
                'afterrender':function(){
                    Ext.create('Ext.form.ComboBox', {
                        fieldLabel: 'Linea',
                        id:me.id+'_linea',
                        renderTo:'linea-nov-box',
                        store: linea,
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'id',
                        displayField: 'nombre',
                        emptyText: '[Seleccione]',
                        //allowBlank: false,
                        labelAlign: 'right',
                        labelWidth: 60,
                        width:'100%',
                        anchor:'100%',
                        //readOnly: true,
                        listeners:{
                            afterrender:function(obj, e){
                                // obj.getStore().load();
                            },
                            select:function(obj, records, eOpts){
                                
                            }
                        }
                    });
                    Ext.create('Ext.form.ComboBox', {
                        fieldLabel: 'Shipper',
                        id:me.id+'_shipper',
                        renderTo:'shipper-nov-box',
                        store: shipper,
                        //forceSelection: false,
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'shi_codigo',
                        displayField: 'shi_nombre',
                        emptyText: '[Seleccione]',
                        //allowBlank: false,
                        labelAlign: 'right',
                        labelWidth: 65,
                        width:'100%',
                        anchor:'100%',
                        //editable:true,
                        //readOnly: true,
                        listeners:{
                            afterrender:function(obj, e){
                                // obj.getStore().load();
                            },
                            select:function(obj, records, eOpts){
                                
                            }
                        }
                    });
                    Ext.create('Ext.form.ComboBox', {
                        fieldLabel: 'Proceso',
                        id:me.id+'_check',
                        renderTo:'proceso-nov-box',
                        store: check,
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'id_elemento',
                        displayField: 'descripcion',
                        emptyText: '[Seleccione]',
                        //allowBlank: false,
                        labelAlign: 'right',
                        labelWidth: 60,
                        width:'100%',
                        anchor:'100%',
                        //readOnly: true,
                        listeners:{
                            afterrender:function(obj, e){
                                // obj.getStore().load();
                            },
                            select:function(obj, records, eOpts){
                                Ext.getCmp(me.id+'_motivo').setValue('');
                                Ext.getCmp(me.id+'_motivo').getStore().removeAll();
                                Ext.getCmp(me.id+'_chk_public').setValue(false);
                                Ext.getCmp(me.id+'_chk_public').setDisabled(true);
                                Ext.getCmp(me.id+'_motivo').getStore().load(
                                    {params: {vp_pnov_id: obj.getValue('id_elemento')},
                                    callback:function(){
                                        
                                    }
                                });
                            }
                        }
                    });
                    Ext.create('Ext.form.ComboBox', {
                        fieldLabel: 'Motivo',
                        id:me.id+'_motivo',
                        renderTo:'motivo-nov-box',
                        store: motivo,
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'tnov_id',
                        displayField: 'tnov_descri',
                        emptyText: '[Seleccione]',
                        //allowBlank: false,
                        labelAlign: 'right',
                        labelWidth: 60,
                        width:'100%',
                        anchor:'100%',
                        //readOnly: true,
                        listeners:{
                            afterrender:function(obj, e){
                                // obj.getStore().load();
                            },
                            select:function(obj, records, eOpts){
                                if(parseInt(records[0].data.mnov_publico)==1){
                                    Ext.getCmp(me.id+'_chk_public').setValue(true);
                                    Ext.getCmp(me.id+'_chk_public').setDisabled(false);
                                }else{
                                    Ext.getCmp(me.id+'_chk_public').setValue(false);
                                    Ext.getCmp(me.id+'_chk_public').setDisabled(true);
                                }
                                
                            }
                        }
                    });
                    Ext.create('Ext.form.Checkbox', {
                        fieldLabel: '¿Novedad Pública?',
                        renderTo:'tipo-novedad-nov-box',
                        labelWidth: 120,
                        //boxLabel: '¿Novedad Pública?',
                        id:me.id+'_chk_public',
                        disabled:true,
                        listeners:{
                            change:function(obj){
                                //if(obj.getValue())
                            }
                        }
                    });
                    Ext.create('Ext.form.Checkbox', {
                        fieldLabel: '¿Documento Relacionado?',
                        renderTo:'tipo-public-nov-box',
                        labelWidth: 170,
                        //boxLabel: '¿Novedad Pública?',
                        id:me.id+'_chk_doc',
                        listeners:{
                            change:function(obj){
                                var alfa = true;
                                if(obj.getValue())alfa = false;
                                Ext.getCmp(me.id+'_tipo_doc').setValue('');
                                Ext.getCmp(me.id+'_nro_doc').setValue('');
                                Ext.getCmp(me.id+'_provincia').setValue('');
                                Ext.getCmp(me.id+'_tipo_doc').setDisabled(alfa);
                                Ext.getCmp(me.id+'_nro_doc').setDisabled(alfa);
                                Ext.getCmp(me.id+'_provincia').setDisabled(true);

                                if(!alfa){
                                    Ext.fly('shipper-nov-box').removeCls('box-display-reg-on');
                                    Ext.fly('shipper-nov-box').addCls('box-display-reg-off');

                                    Ext.fly('tipo-doc-nov-box').removeCls('box-display-reg-off');
                                    Ext.fly('tipo-doc-nov-box').addCls('box-display-reg-on');

                                    Ext.fly('provincia-nov-box').removeCls('box-display-reg-off');
                                    Ext.fly('provincia-nov-box').addCls('box-display-reg-on');

                                    Ext.fly('nro-doc-nov-box').removeCls('box-display-reg-off');
                                    Ext.fly('nro-doc-nov-box').addCls('box-display-reg-on');

                                    Ext.getCmp(me.id+'-win').setSize(450,540);
                                    Ext.getCmp(me.id+'-win').doLayout();
                                    Ext.getCmp(me.id+'-win').center();
                                }else{
                                    Ext.fly('shipper-nov-box').removeCls('box-display-reg-off');
                                    Ext.fly('shipper-nov-box').addCls('box-display-reg-on');

                                    Ext.fly('tipo-doc-nov-box').removeCls('box-display-reg-on');
                                    Ext.fly('tipo-doc-nov-box').addCls('box-display-reg-off');

                                    Ext.fly('provincia-nov-box').removeCls('box-display-reg-on');
                                    Ext.fly('provincia-nov-box').addCls('box-display-reg-off');

                                    Ext.fly('nro-doc-nov-box').removeCls('box-display-reg-on');
                                    Ext.fly('nro-doc-nov-box').addCls('box-display-reg-off');
                                    
                                    Ext.getCmp(me.id+'-win').setSize(450,470);
                                    Ext.getCmp(me.id+'-win').doLayout();
                                }
                            }
                        }
                    });
                    Ext.create('Ext.form.ComboBox', {
                        fieldLabel: 'Tipo Doc.',
                        id:me.id+'_tipo_doc',
                        renderTo:'tipo-doc-nov-box',
                        store: tipo_doc,
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'id_elemento',
                        displayField: 'descripcion',
                        emptyText: '[Seleccione]',
                        //allowBlank: false,
                        labelAlign: 'right',
                        labelWidth: 60,
                        width:'100%',
                        anchor:'100%',
                        disabled: true,
                        listeners:{
                            afterrender:function(obj, e){
                                // obj.getStore().load();
                            },
                            select:function(obj, records, eOpts){
                                var alfa = true;
                                if(me.omega[obj.getValue()-1]){
                                    if(!me.loadPro){me.load_provincia();me.loadPro=1;}
                                    alfa = false;
                                }else{
                                    Ext.getCmp(me.id+'_provincia').setValue('');
                                }
                                Ext.getCmp(me.id+'_provincia').setDisabled(alfa);
                            }
                        }
                    });
                    Ext.create('Ext.form.ComboBox', {
                        fieldLabel: 'Provincia',
                        id:me.id+'_provincia',
                        renderTo:'provincia-nov-box',
                        store: provincias,
                        queryMode: 'local',
                        triggerAction: 'all',
                        valueField: 'prov_codigo',
                        displayField: 'prov_nombre',
                        emptyText: '[Seleccione]',
                        //allowBlank: false,
                        listConfig:{
                            minWidth: 200
                        },
                        labelWidth: 60,
                        labelAlign: 'right',
                        width:'100%',
                        anchor:'100%',
                        disabled: true,
                        listeners:{
                            afterrender:function(obj, e){
                                // obj.getStore().load();
                            },
                            select:function(obj, records, eOpts){
                                
                            }
                        }
                    });
                    Ext.create('Ext.form.field.Number', {
                        id:me.id+'_nro_doc',
                        renderTo:'nro-doc-nov-box',
                        fieldLabel: 'Nro. Doc',
                        labelAlign: 'right',
                        labelWidth:60,
                        disabled: true,
                        width: '100%',
                        anchor: '100%'
                    });
                }
            }
        });
        
        this.win = Ext.create('Ext.window.Window',{
            id:me.id+'-win',
            plain: true,
            //title:'REGISTRO DE NOVEDAD',
            //icon: '/images/icon/alert_red_min.ico',
            height: 470,
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
     salir:function(){
        Ext.getCmp(this.id+'-win').close();
     },
     load_provincia:function(){
        Ext.getCmp(this.id+'_provincia').setValue('');
        Ext.getCmp(this.id+'_provincia').getStore().removeAll();
        Ext.getCmp(this.id+'_provincia').getStore().load(
            {params: {},
            callback:function(){
                
            }
        });
     },
     registra_novedad:function(){
        var me = this;
        var linea = Ext.getCmp(me.id+'_linea').getValue();
        var shipper = Ext.getCmp(me.id+'_shipper').getValue();
        var referente = Ext.getCmp(me.id+'_check').getValue();
        var motivo = Ext.getCmp(me.id+'_motivo').getValue();
        var publico = Ext.getCmp(me.id+'_chk_public').getValue();
        var flag_doc = Ext.getCmp(me.id+'_chk_doc').getValue();
        var tipo_doc = Ext.getCmp(me.id+'_tipo_doc').getValue();
        var nro_doc = Ext.getCmp(me.id+'_nro_doc').getValue();
        var provincia = Ext.getCmp(me.id+'_provincia').getValue();
        var file = Ext.getCmp(me.id+'-file-novedad').getValue();
        var comentario = Ext.getCmp(me.id+'-nov-comentario-r').getValue();
        publico =(publico)?1:0;

        if(linea== null || linea==''){
            global.Msg({msg:"Seleccione un linea de negocio por favor.",icon:2,fn:function(){}});
            return false; 
        }

        if(!flag_doc){
            if(shipper== null || shipper==''){
                global.Msg({msg:"Seleccione un shipper por favor.",icon:2,fn:function(){}});
                return false; 
            }
        }else{
            shipper = '';
        }

        if(referente== null || referente==''){
            global.Msg({msg:"Seleccione un registro referente a la novedad por favor.",icon:2,fn:function(){}});
            return false; 
        }
        if(motivo== null || motivo==''){
            global.Msg({msg:"Seleccione un motivo por favor.",icon:2,fn:function(){}});
            return false; 
        }

        if(flag_doc){
            if(tipo_doc== null || tipo_doc==''){
                global.Msg({msg:"Seleccione un tipo de documento por favor.",icon:2,fn:function(){}});
                return false; 
            }
            if(nro_doc== null || nro_doc==''){
                global.Msg({msg:"Ingrese un número de documento por favor.",icon:2,fn:function(){}});
                return false; 
            }
            if(me.omega[tipo_doc-1]){
                if(provincia== null || provincia==''){
                    global.Msg({msg:"Seleccione un motivo por favor.",icon:2,fn:function(){}});
                    return false; 
                }
            }
        }else{
            tipo_doc = '';
            nro_doc = '';
            provincia = '';
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

                    var form = Ext.getCmp(me.id+'-nov-form-archivo-c').getForm();

                    form.submit({
                        url:me.url+'set_scm_registro',
                        params:{
                            vp_fac_linea:linea,vp_prov_codigo:provincia,vp_tipo_doc:tipo_doc,vp_doc_numero:nro_doc,
                            vp_pnov_id:referente,vp_tnov_id:motivo,vp_msn_publico:publico,vp_msn_texto:comentario,vp_shi_codigo:shipper
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
                                        Ext.getCmp(me.id+'_nro_doc').setValue('');
                                        Ext.getCmp(me.id+'-file-novedad').setValue('');
                                        Ext.getCmp(me.id+'-nov-comentario-r').setValue('');
                                        Ext.getCmp(me.id+'-win').close();
                                    }
                                });
                                

                            }
                                            
                        }
                    },me);
                }
            }
        });
     },
     limpiar:function(){
        var me = this;
        Ext.getCmp(me.id+'_linea').setValue('');
        Ext.getCmp(me.id+'_check').setValue('');
        Ext.getCmp(me.id+'_shipper').setValue('');
        Ext.getCmp(me.id+'_motivo').setValue('');
        Ext.getCmp(me.id+'_chk_public').setValue(false);
        Ext.getCmp(me.id+'_chk_doc').setValue(false);
        Ext.getCmp(me.id+'_tipo_doc').setValue('');
        Ext.getCmp(me.id+'_nro_doc').setValue('');
        Ext.getCmp(me.id+'_provincia').setValue('');
        Ext.getCmp(me.id+'-file-novedad').setValue('');
        Ext.getCmp(me.id+'-nov-comentario-r').setValue('');
     }
 });
