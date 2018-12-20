/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */
Ext.define('Ext.global.FindLocation',{
    extend: 'Ext.panel.Panel',
    alias: 'widget.findlocation',
    layout: 'border',
    defaults:{
        border: false
    },
    url: '/gestion/callcenter/',
    mapping: false,
    task: null,
    map: null,
    flightPath: null,
    marker: null,
    mapa: 0,
    ciu_id: 0,
    tip_via: 0,
    id_via: 0,
    id_urb: 0,
    id_puerta: null,
    id_mza: null,
    segmentos:[],
    isVia: false,
    geocoder: null,
    lat: -11.782413062516948,
    lon: -76.79493715625,
    developer:1,
    acceso:{},
    clearRefers:true,
    divMap:'',
    movecoord:0,
    initComponent: function(){
        var me = this;
        me.getMapping=(me.getMapping==undefined)?true:me.getMapping;
        me.clearRefers=(me.clearReferent==undefined)?true:me.clearReferent;
        if(me.apiAddress){
            me.url=me.urlC;
            me.acceso=me.acceso;
        }
        if (!me.mapping){
            this.items = [
                {                    
                    region: (me.getMapping)?'west':'center',
                    width: (me.getMapping)?300:'100%',
                    border: true,
                    defaults:{
                        border: false,
                        style:{
                            margin: '2px'
                        }
                    },
                    header:false,
                    split: true,
                    collapsible: true,
                    hideCollapseTool:true,
                    titleCollapse:false,
                    floatable: false,
                    collapseMode : 'mini',
                    animCollapse : true,
                    border: false,
                    bodyStyle: 'background: #fff;',
                    layout:'border',
                    items:[
                        {
                            region:'north',
                            id:me.id + '-finder-address_banner',
                            border:false,
                            height:20,
                            hidden:true,
                            html:'<div id="'+me.id + '-finder-address'+'-html-banner"></div>'
                        },
                        {
                            region:'center',
                            layout:'fit',
                            border:false,
                            items:[
                                {
                                    //xtype: 'uePanel',
                                    title: 'Buscador de direcciones',
                                    id:me.id + '-finder-address',
                                    legend: 'Ingresar Datos Solicitados',
                                    defaults:{
                                        border: false,
                                        padding:'0px 5px 0px 5px'
                                    },
                                    items:[
                                        {
                                            xtype:'panel',
                                            border:false,
                                            bodyStyle: 'background: transparent',
                                            //padding:'0px 5px 0px 5px',
                                            layout:'column',
                                            items: [
                                                {
                                                    columnWidth: 0.70,
                                                    border:false,
                                                    layout:'fit',
                                                    padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                    items:[
                                                        {
                                                            xtype: 'combo',
                                                            id: me.id + '-distrito',
                                                            fieldLabel: 'Distrito / Provincia / Departamento',
                                                            labelStyle:'font-size:11px;',
                                                            flex: 1,
                                                            labelAlign: 'top',
                                                            store: Ext.create('Ext.data.Store',{
                                                                fields: [
                                                                    {name: 'ciudad', type: 'string'},
                                                                    {name: 'ciu_id', type: 'int'},
                                                                    {name: 'ciu_px', type: 'float'},
                                                                    {name: 'ciu_py', type: 'float'},
                                                                    {name: 'mapa', type: 'int'},
                                                                    {name: 'prov_codigo', type: 'int'},
                                                                ],
                                                                proxy:{
                                                                    type: 'ajax',
                                                                    url: me.url + 'get_gis_busca_distrito/',
                                                                    reader:{
                                                                        type: 'json',
                                                                        rootProperty: 'data'
                                                                    }
                                                                },
                                                                listeners:{
                                                                    load: function(store, records, successful, eOpts){
                                                                        //console.log(store.getProxy().getReader().rawData.debug.sql);
                                                                        me.clearconsole();
                                                                    },
                                                                    beforeload: function(store, operation, opts){
                                                                        me.clearconsole();
                                                                        if(me.apiAddress){
                                                                            var proxy = store.getProxy();
                                                                            proxy.setExtraParam('user',{
                                                                                user: me.acceso.user
                                                                            });
                                                                            proxy.setExtraParam('key',{
                                                                                key: me.acceso.key
                                                                            });
                                                                        }
                                                                    }
                                                                }
                                                            }),
                                                            typeAhead: false,
                                                            hideTrigger: true,
                                                            valueField: 'ciu_id',
                                                            displayField: 'ciudad',
                                                            emptyText: '[ search ]',
                                                            queryParam: 'vp_nomdis',
                                                            minChars: 3,
                                                            enableKeyEvents: true,
                                                            caseSensitive: true,
                                                            autoSelect: false,
                                                            listConfig:{
                                                                minWidth: 350
                                                            },
                                                            listeners:{
                                                                afterrender: function(obj){
                                                                    obj.focus(true, 500);
                                                                },
                                                                beforeselect: function( obj, record, index, eOpts ){
                                                                    if (index >= 0)
                                                                        return true;
                                                                    else
                                                                        return false;
                                                                },
                                                                select: function(obj, records, opts){
                                                                    me.reset_global_vars();
                                                                    me.mapa = parseInt(records.get('mapa'));
                                                                    me.ciu_id = records.get('ciu_id');
                                                                    me.getComboCalle();
                                                                    me.getComboUrbanizacion();
                                                                    me.getCombo_via_manz_lote();
                                                                    //me.getComboInterno();
                                                                    Ext.getCmp(me.id + '-tipo_via').enable();
                                                                    if(records.get('ciu_px')!=""){
                                                                        me.setMap({zoon: 13, lat: records.get('ciu_px'), lon: records.get('ciu_py')});
                                                                        me.flightPath.setMap(me.map);
                                                                    }
                                                                    me.setChangeTrust();
                                                                    try{
                                                                        me.changeEvent(records.get('prov_codigo'),me.getValues());
                                                                    }catch(e){}
                                                                    Ext.getCmp(me.id + '-nombre_via').focus(true, 500);
                                                                },
                                                                keypress: function(obj, event, opts){
                                                                    /*var val = Ext.util.Format.trim(obj.getRawValue());
                                                                    if (val == ''){*/
                                                                        Ext.getCmp(me.id + '-nombre_via').setValue(null);
                                                                        Ext.getCmp(me.id + '-urbanizacion').setValue(null);
                                                                        Ext.getCmp(me.id + '-via').setValue(null);
                                                                        Ext.getCmp(me.id + '-lote').setValue(null);
                                                                        Ext.getCmp(me.id + '-manzana').setValue(null);
                                                                        if(me.clearRefers){
                                                                            Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                                                            Ext.getCmp(me.id + '-nro-interno').setValue(null);
                                                                        }
                                                                        me.reset_global_vars();
                                                                        me.setChangeTrust();
                                                                        me.getComboCalle();
                                                                        me.getComboUrbanizacion();
                                                                        try{
                                                                            me.changeEvent(0,me.getValues());
                                                                        }catch(e){}

                                                                        /*if(event.getKey() == 13){
                                                                            if(records.get('ciu_px')!=""){
                                                                                me.setMap({zoon: 13, lat: records.get('ciu_px'), lon: records.get('ciu_py')});
                                                                                me.flightPath.setMap(me.map);
                                                                                try{
                                                                                    me.changeEvent(me.getValues());
                                                                                }catch(e){}
                                                                            }
                                                                        }*/
                                                                        
                                                                    //}
                                                                },
                                                                blur: function(obj, event, opts){
                                                                    var val = Ext.util.Format.trim(obj.getRawValue());
                                                                    if (val == ''){
                                                                        Ext.getCmp(me.id + '-nombre_via').setValue(null);
                                                                        Ext.getCmp(me.id + '-urbanizacion').setValue(null);
                                                                        Ext.getCmp(me.id + '-via').setValue(null);
                                                                        Ext.getCmp(me.id + '-lote').setValue(null);
                                                                        Ext.getCmp(me.id + '-manzana').setValue(null);
                                                                        if(me.clearRefers){
                                                                            Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                                                            Ext.getCmp(me.id + '-nro-interno').setValue(null);
                                                                        }
                                                                        me.reset_global_vars();
                                                                        me.setChangeTrust();
                                                                        me.getComboCalle();
                                                                        me.getComboUrbanizacion();
                                                                        try{
                                                                            me.changeEvent(0,me.getValues());
                                                                        }catch(e){}
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    ]
                                                },
                                                {
                                                    columnWidth: 0.30,border:false,
                                                    layout:'fit',
                                                    padding:'0px 2px 0px 0px',  bodyStyle: 'background: transparent',
                                                    items:[
                                                        {
                                                            xtype: 'combo',
                                                            id: me.id + '-tipo_via',
                                                            fieldLabel: 'Tipo de vía',
                                                            labelStyle:'font-size:11px;',
                                                            flex: 1,
                                                            labelAlign: 'top',
                                                            //disabled: true,
                                                            store: Ext.create('Ext.data.Store',{
                                                                fields:[
                                                                    {name: 'descripcion', type: 'string'},
                                                                    {name: 'id_elemento', type: 'int'},
                                                                    {name: 'des_corto', type: 'string'}
                                                                ],
                                                                proxy:{
                                                                    type: 'ajax',
                                                                    url: me.url + 'get_scm_tabla_detalle/',
                                                                    reader:{
                                                                        type: 'json',
                                                                        rootProperty: 'data'
                                                                    }
                                                                },
                                                                listeners:{
                                                                    load: function(store, records, successful, eOpts){
                                                                        //console.log(store.getProxy().getReader().rawData.debug.sql);
                                                                        me.clearconsole();
                                                                    }
                                                                }
                                                            }),
                                                            queryMode: 'local',
                                                            triggerAction: 'all',
                                                            valueField: 'id_elemento',
                                                            displayField: 'descripcion',
                                                            emptyText: '[ Seleccione ]',
                                                            enableKeyEvents: true,
                                                            listConfig:{
                                                                minWidth: 200
                                                            },
                                                            listeners:{
                                                                afterrender: function(obj){
                                                                    var user='';
                                                                    var key ='';
                                                                    if(me.apiAddress){
                                                                        user=me.acceso.user;
                                                                        key=me.acceso.key;
                                                                    }
                                                                    obj.getStore().load({
                                                                        params:{
                                                                            vp_tab_id: 'VIA',
                                                                            vp_shipper: 0,
                                                                            user:user,
                                                                            key:key
                                                                        },
                                                                        callback: function(){
                                                                            me.tip_via = 0;
                                                                            obj.setValue(me.tip_via);
                                                                        }
                                                                    });
                                                                },
                                                                select: function(obj, records, opts){
                                                                    me.tip_via = records.get('id_elemento');
                                                                    Ext.getCmp(me.id + '-nombre_via').setValue(null);
                                                                    Ext.getCmp(me.id + '-urbanizacion').setValue(null);
                                                                    Ext.getCmp(me.id + '-via').setValue(null);
                                                                    Ext.getCmp(me.id + '-lote').setValue(null);
                                                                    Ext.getCmp(me.id + '-manzana').setValue(null);
                                                                    if(me.clearRefers){
                                                                        Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                                                        Ext.getCmp(me.id + '-nro-interno').setValue(null);
                                                                    }
                                                                    me.id_via = 0;
                                                                    me.id_urb = 0;
                                                                    me.id_puerta = null;
                                                                    me.id_mza = null;
                                                                    me.segmentos = [];
                                                                    me.isVia = false;
                                                                    me.geocoder = null;
                                                                    me.lat= -11.782413062516948;
                                                                    me.lon= -76.79493715625;
                                                                    me.setChangeTrust();
                                                                    Ext.getCmp(me.id + '-nombre_via').focus(true, 500);
                                                                },
                                                                keypress: function(obj, event, opts){
                                                                    Ext.getCmp(me.id + '-nombre_via').setValue(null);
                                                                    Ext.getCmp(me.id + '-urbanizacion').setValue(null);
                                                                    Ext.getCmp(me.id + '-via').setValue(null);
                                                                    Ext.getCmp(me.id + '-lote').setValue(null);
                                                                    Ext.getCmp(me.id + '-manzana').setValue(null);
                                                                    if(me.clearRefers){
                                                                        Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                                                        Ext.getCmp(me.id + '-nro-interno').setValue(null);
                                                                    }
                                                                    me.id_via = 0;
                                                                    me.id_urb = 0;
                                                                    me.id_puerta = null;
                                                                    me.id_mza = null;
                                                                    me.segmentos = [];
                                                                    me.geocoder = null;
                                                                    me.lat= -11.782413062516948;
                                                                    me.lon= -76.79493715625;
                                                                    me.flightPath.setMap(null);
                                                                    me.marker.setMap(null);
                                                                    me.setChangeTrust();
                                                                    me.getComboUrbanizacion();
                                                                },
                                                                blur: function(obj, event, opts){
                                                                    var val = Ext.util.Format.trim(obj.getRawValue());
                                                                    if (val == ''){
                                                                        Ext.getCmp(me.id + '-nombre_via').setValue(null);
                                                                        Ext.getCmp(me.id + '-urbanizacion').setValue(null);
                                                                        Ext.getCmp(me.id + '-via').setValue(null);
                                                                        Ext.getCmp(me.id + '-lote').setValue(null);
                                                                        Ext.getCmp(me.id + '-manzana').setValue(null);
                                                                        if(me.clearRefers){
                                                                            Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                                                            Ext.getCmp(me.id + '-nro-interno').setValue(null);
                                                                        }
                                                                        me.id_via = 0;
                                                                        me.id_urb = 0;
                                                                        me.id_puerta = null;
                                                                        me.id_mza = null;
                                                                        me.segmentos = [];
                                                                        me.geocoder = null;
                                                                        me.lat= -11.782413062516948;
                                                                        me.lon= -76.79493715625;
                                                                        me.flightPath.setMap(null);
                                                                        me.marker.setMap(null);
                                                                        me.setChangeTrust();
                                                                        me.getComboUrbanizacion();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            xtype: 'panel',
                                            id: me.id + '-panel-combo-via',
                                            layout: 'hbox',
                                            listeners:{
                                                scope: this,
                                                afterrender: function(obj){
                                                    this.getComboCalle();
                                                }
                                            }
                                        },
                                        {
                                            xtype: 'panel',
                                            id: me.id + '-panel-combo-urbanizacion',
                                            layout: 'hbox',
                                            listeners:{
                                                scope: this,
                                                afterrender: function(obj){
                                                    this.getComboUrbanizacion();
                                                }
                                            }
                                        },
                                        {
                                            xtype: 'panel',
                                            id: me.id + '-panel-combo-via_manz_lote',
                                            layout: 'hbox',
                                            defaults:{
                                                style:{
                                                    margin: '1px'
                                                }
                                            },
                                            listeners:{
                                                scope: this,
                                                afterrender: function(obj){
                                                    this.getCombo_via_manz_lote();
                                                }
                                            }
                                        },
                                        {
                                            xtype: 'panel',
                                            layout: 'hbox',
                                            items:[
                                                {
                                                    xtype: 'textarea',
                                                    id:me.id+'-referencia-r',
                                                    margin:2,
                                                    labelWidth:62,
                                                    //flex: 1,
                                                    labelAlign: 'top',
                                                    fieldLabel: 'Referencia',
                                                    width: '100%',
                                                    height:80,
                                                    //anchor:'100%',
                                                    emptyText: 'Escribo un referencia...',
                                                    maxLength:100,
                                                    grow: true,
                                                    maxLengthText:'El maximo de caracteres permitidos para este campo es {0}',
                                                    enforceMaxLength:true
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        }
                    ],
                    listeners:{
                        afterrender: function(obj){
                            Ext.getCmp(me.id + '-distrito').focus(true, 500);
                        }
                    }
                },
                {
                    region: (me.getMapping)?'center':'west',
                    hidden:(me.getMapping)?false:true,
                    layout: 'fit',
                    html: '<div id="'+me.id+'-map-canvas" class="ue-map-canvas"></div>',
                    listeners:{
                        afterrender: function(obj){
                            me.setMap({zoon: 4, lat: me.lat, lon: me.lon});
                        }
                    }
                }
            ]
        }else{
            this.items = [
                {
                    region: 'center',
                    layout: 'fit',
                    html: '<div id="'+me.id+'-map-canvas" class="ue-map-canvas"></div>',
                    listeners:{
                        scope: this,
                        afterrender: function(obj){
                            me.setMap({zoon: 4, lat: me.lat, lon: me.lon});
                        }
                    }
                }
            ]
        }

        this.callParent();
    },
    reload: function(){
        var me = this;
        if (me.map == 1){
            Ext.getCmp(me.id + '-tipo_via').clearValue();
            Ext.getCmp(me.id + '-nombre_via').clearValue();
        }
    },
    events: function(){
        var me = this;
        var tipo_via = Ext.getCmp(me.id + '-tipo_via');
        if (me.ciu_id != null){
            tipo_via.enable();
            var calle = Ext.getCmp(me.id + '-nombre_via');
            var urbanizacion = Ext.getCmp(me.id + '-urbanizacion');
            calle.enable();
            urbanizacion.enable();
        }else{
            tipo_via.disable();
            var calle = Ext.getCmp(me.id + '-nombre_via');
            var urbanizacion = Ext.getCmp(me.id + '-urbanizacion');
            calle.disable();
            urbanizacion.disable();
        }
    },
    setMap: function(p){
        var me = this;

        var mapOptions = {
            zoom: p.zoon,
            center: new google.maps.LatLng(p.lat, p.lon)
        };
        if(me.getMapping){
            me.map = new google.maps.Map(document.getElementById(me.id + '-map-canvas'), mapOptions);
            me.divMap=me.id + '-map-canvas';
        }else{
            me.map = new google.maps.Map(document.getElementById(me.setMapping), mapOptions);
            me.divMap=me.setMapping;
        }

        var peru = new google.maps.LatLng(-11.782413062516948, -76.79493715625);

        var homeControlDiv = document.createElement('div');
        var homeControl = new HomeControl(homeControlDiv, me.map, peru);

        homeControlDiv.index = 1;

        me.map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);
        me.setChangeTrust();
    },
    getComboCalle: function(){
        var me = this;
        var p = Ext.getCmp(me.id + '-panel-combo-via');
        p.removeAll();
        var state = me.ciu_id == 0 ? true : false;
        if (state == true || me.mapa == 1){
            p.add({
                xtype: 'combo',
                id: me.id + '-nombre_via',
                fieldLabel: 'Nombre de calle / vía',
                flex: 1,
                labelAlign: 'top',
               // disabled: state,
                store: Ext.create('Ext.data.Store',{
                    fields:[
                        {name: 'nombre_via', type: 'string'},
                        {name: 'nombre_calle', type: 'string'},
                        {name: 'tipo_via', type: 'string'},
                        {name: 'id_via', type: 'int'},
                        {name: 'id_tipo_via', type: 'int'}
                    ],
                    proxy:{
                        type: 'ajax',
                        url: me.url + 'get_gis_busca_via/',
                        reader:{
                            type: 'json',
                            rootProperty: 'data'
                        }
                    },
                    listeners:{
                        load: function(store, records, successful, eOpts){
                            me.clearconsole();
                            //console.log(store.getProxy().getReader().rawData.debug.sql);
                        },
                        beforeload: function(store, operation, opts){
                            me.clearconsole();
                            var proxy = store.getProxy();
                            proxy.setExtraParam('vp_ciu_id',{
                                vp_ciu_id: me.ciu_id
                            });
                            proxy.setExtraParam('vp_tipvia',{
                                vp_tipvia: me.tip_via
                            });
                            if(me.apiAddress){
                                var proxy = store.getProxy();
                                proxy.setExtraParam('user',{
                                    user: me.acceso.user
                                });
                                proxy.setExtraParam('key',{
                                    key: me.acceso.key
                                });
                            }
                        }
                    }
                }),
                typeAhead: false,
                hideTrigger: true,
                valueField: 'id_via',
                displayField: 'nombre_via',
                emptyText: '[ search ]',
                minChars: 1,
                queryParam: 'vp_nomvia',
                enableKeyEvents: true,
                caseSensitive: true,
                autoSelect: false,
                listConfig:{
                    minWidth: 400
                },
                listeners:{
                    scope: this,
                    afterrender: function(obj){

                    },
                    beforeselect: function( obj, record, index, eOpts ){
                        if (index >= 0)
                            return true;
                        else
                            return false;
                    },
                    select: function(obj, records, opts){
                        me.isVia = true;
                        Ext.getCmp(me.id + '-tipo_via').setValue(records.get('id_tipo_via'));
                        Ext.getCmp(me.id + '-distrito').setRawValue(records.get('distrito'));
                        Ext.getCmp(me.id + '-nombre_via').setRawValue(records.get('nombre_calle'));
                        me.ciu_id=records.get('ciu_id');
                        me.mapa = parseInt(records.get('mapa'));
                        me.id_via = records.get('id_via');
                        me.tip_via=records.get('id_tipo_via');

                        if(records.get('ciu_px')!=""){
                            me.setMap({zoon: 16, lat: records.get('ciu_px'), lon: records.get('ciu_py')});
                            me.flightPath.setMap(me.map);
                        }

                        Ext.getCmp(me.id + '-via').setValue('');
                        Ext.getCmp(me.id + '-manzana').setValue('');
                        Ext.getCmp(me.id + '-lote').setValue('');
                        if(me.clearRefers){
                            Ext.getCmp(me.id+'-referencia-r').setValue('');
                            Ext.getCmp(me.id + '-nro-interno').setValue('');
                        }

                        me.id_urb = 0;
                        me.id_puerta = null;
                        me.id_mza = null;
                        me.segmentos = [];
                        me.geocoder = null;
                        me.lat= -11.782413062516948;
                        me.lon= -76.79493715625;

                        me.getSegmentos();
                        me.getComboUrbanizacion();
                        me.getCombo_via_manz_lote();
                        //this.getComboInterno();
                        Ext.getCmp(me.id + '-tipo_via').enable();
                        me.setChangeTrust();
                        Ext.getCmp(me.id + '-nombre_via').focus(true, 500);
                    },
                    keypress: function(obj, event, opts){
                        Ext.getCmp(me.id + '-via').setValue(null);
                        Ext.getCmp(me.id + '-manzana').setValue(null);
                        Ext.getCmp(me.id + '-lote').setValue(null);
                        if(me.clearRefers){
                            Ext.getCmp(me.id+'-referencia-r').setValue(null);
                            Ext.getCmp(me.id + '-nro-interno').setValue(null);
                        }
                        me.isVia = false;
                        me.id_via = 0;
                        me.id_urb = 0;
                        me.id_puerta = null;
                        me.id_mza = null;
                        me.segmentos = [];
                        me.geocoder = null;
                        me.lat= -11.782413062516948;
                        me.lon= -76.79493715625;
                        me.flightPath.setMap(null);
                        me.marker.setMap(null);
                        me.setChangeTrust();
                        me.getComboUrbanizacion();
                    },
                    blur: function(obj, event, opts){
                        var val = Ext.util.Format.trim(obj.getRawValue());
                        if (val == ''){
                            Ext.getCmp(me.id + '-via').setValue(null);
                            Ext.getCmp(me.id + '-manzana').setValue(null);
                            Ext.getCmp(me.id + '-lote').setValue(null);
                            if(me.clearRefers){
                                Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                Ext.getCmp(me.id + '-nro-interno').setValue(null);
                            }
                            me.isVia = false;
                            me.id_via = 0;
                            me.id_urb = 0;
                            me.id_puerta = null;
                            me.id_mza = null;
                            me.segmentos = [];
                            me.geocoder = null;
                            me.lat= -11.782413062516948;
                            me.lon= -76.79493715625;
                            me.flightPath.setMap(null);
                            me.marker.setMap(null);
                            me.setChangeTrust();
                            me.getComboUrbanizacion();
                        }
                    }
                }
            });
        }else{
            p.add({
                xtype: 'textfield',
                id: me.id + '-nombre_via',
                fieldLabel: 'Nombre de calle / vía',
                flex: 1,
                labelAlign: 'top',
                disabled: state,
                listeners:{
                    scope: this,
                    blur: function(obj, e, opts){
                        me.geoLocalizar();
                        me.setChangeTrust();
                    }
                }
            });
        }
        
        p.doLayout();
    },
    getComboUrbanizacion: function(){
        var me = this;
        var p = Ext.getCmp(me.id + '-panel-combo-urbanizacion');
        p.removeAll();
        var state = me.ciu_id == 0 ? true : false;
        if (me.mapa == 1){
            if (me.isVia){
                p.add({
                    xtype: 'combo',
                    id: me.id + '-urbanizacion',
                    fieldLabel: 'Urbanización / AAHH / Barrio (Grupo vía)',
                    flex: 1,
                    labelAlign: 'top',
                    disabled: state,
                    store: Ext.create('Ext.data.Store',{
                        fields:[
                            {name: 'nombre', type: 'string'},
                            {name: 'punto_x', type: 'float'},
                            {name: 'punto_y', type: 'float'},
                            {name: 'id_grupo_viv', type: 'int'}
                        ],
                        proxy:{
                            type: 'ajax',
                            url: me.url + 'get_gis_busca_via_grupoviviendas/',
                            reader:{
                                type: 'json',
                                rootProperty: 'data'
                            }
                        },
                        listeners:{
                            load: function(store, records, successful, eOpts){
                                me.clearconsole();
                                //console.log(store.getProxy().getReader().rawData.debug.sql);
                            }
                        }
                    }),
                    queryMode: 'local',
                    valueField: 'id_grupo_viv',
                    displayField: 'nombre',
                    emptyText: '[ search ]',
                    listeners:{
                        afterrender: function(obj){
                            var user='';
                            var key ='';
                            if(me.apiAddress){
                                user=me.acceso.user;
                                key=me.acceso.key;
                            }
                            obj.getStore().load({
                                params:{
                                    vp_id_via: me.id_via,
                                    vp_ciu_id: me.ciu_id,
                                    user:user,
                                    key:key
                                },
                                callback: function(){
                                    me.clearconsole();
                                    obj.focus(true, 500);
                                }
                            });
                        },
                        select: function(obj, records, opts){
                            var rec = records;
                            me.id_urb = rec.get('id_grupo_viv');
                            Ext.getCmp(me.id + '-via').setValue(null);
                            Ext.getCmp(me.id + '-lote').setValue(null);
                            Ext.getCmp(me.id + '-manzana').setValue(null);

                            me.id_puerta = null;
                            me.id_mza = null;
                            me.segmentos = [];
                            me.geocoder = null;
                            me.lat= -11.782413062516948;
                            me.lon= -76.79493715625;
                            if(me.clearRefers){
                                Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                Ext.getCmp(me.id + '-nro-interno').setValue(null);
                            }

                            me.setCoordenadas(records.get('punto_x'), records.get('punto_y'));
                            var user='';
                            var key ='';
                            if(me.apiAddress){
                                user=me.acceso.user;
                                key=me.acceso.key;
                            }
                            Ext.getCmp(me.id + '-manzana').clearValue();
                            Ext.getCmp(me.id + '-manzana').getStore().load({
                                params:{
                                    vp_ciu_id: me.ciu_id,
                                    vp_id_urb: me.id_urb,
                                    vp_id_via: me.id_via,
                                    user:user,
                                    key:key
                                },
                                callback: function(){
                                    me.clearconsole();
                                }
                            });


                            me.flightPath.setMap(null);
                            me.marker.setMap(null);
                            
                            var myLatlng = new google.maps.LatLng(rec.get('punto_x'),rec.get('punto_y'));
                            me.marker = new google.maps.Marker({
                                position: myLatlng,
                                map: me.map,
                                title: ''
                            });
                            me.setChangeTrust();
                            Ext.getCmp(me.id + '-via').focus(true, 500);
                        },
                        keypress: function(obj, event, opts){
                            Ext.getCmp(me.id + '-via').setValue(null);
                            Ext.getCmp(me.id + '-lote').setValue(null);
                            Ext.getCmp(me.id + '-manzana').setValue(null);
                            if(me.clearRefers){
                                Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                Ext.getCmp(me.id + '-nro-interno').setValue(null);
                            }
                            me.id_urb = 0;
                            me.id_puerta = null;
                            me.id_mza = null;
                            //me.segmentos = [];
                            //me.geocoder = null;
                            me.flightPath.setMap(null);
                            me.marker.setMap(null);
                            me.setChangeTrust();
                        },
                        blur: function(obj, event, opts){
                            var val = Ext.util.Format.trim(obj.getRawValue());
                            if (val == ''){
                                Ext.getCmp(me.id + '-via').setValue(null);
                                Ext.getCmp(me.id + '-lote').setValue(null);
                                Ext.getCmp(me.id + '-manzana').setValue(null);
                                if(me.clearRefers){
                                    Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                    Ext.getCmp(me.id + '-nro-interno').setValue(null);
                                }
                                me.id_urb = 0;
                                me.id_puerta = null;
                                me.id_mza = null;
                                //me.segmentos = [];
                                //me.geocoder = null;
                                me.flightPath.setMap(null);
                                me.marker.setMap(null);
                                me.setChangeTrust();
                            }
                        }
                    }
                });
            }else{
                p.add({
                    xtype: 'combo',
                    id: me.id + '-urbanizacion',
                    fieldLabel: 'Urbanización / AAHH / Barrio (Grupo vía)',
                    flex: 1,
                    labelAlign: 'top',
                    disabled: state,
                    store: Ext.create('Ext.data.Store',{
                        fields:[
                            {name: 'nombre_grupo', type: 'string'},
                            {name: 'grupo_px', type: 'float'},
                            {name: 'grupo_py', type: 'float'},
                            {name: 'id_grupo', type: 'int'}
                        ],
                        proxy:{
                            type: 'ajax',
                            url: me.url + 'get_gis_busca_grupoviviendas/',
                            reader:{
                                type: 'json',
                                rootProperty: 'data'
                            }
                        },
                        listeners:{
                            load: function(store, records, successful, eOpts){
                                me.clearconsole();
                                //console.log(store.getProxy().getReader().rawData.debug.sql);
                            },
                            beforeload: function(store, operation, opts){
                                me.clearconsole();
                                var proxy = store.getProxy();
                                proxy.setExtraParam('vp_ciu_id',{
                                    vp_ciu_id: me.ciu_id
                                });
                                if(me.apiAddress){
                                    var proxy = store.getProxy();
                                    proxy.setExtraParam('user',{
                                        user: me.acceso.user
                                    });
                                    proxy.setExtraParam('key',{
                                        key: me.acceso.key
                                    });
                                }
                            }
                        }
                    }),
                    typeAhead: false,
                    hideTrigger: true,
                    valueField: 'id_grupo',
                    displayField: 'nombre_grupo',
                    emptyText: '[ search ]',
                    queryParam: 'vp_nombre',
                    minChars: 1,
                    enableKeyEvents: true,
                    caseSensitive: true,
                    autoSelect: false,
                    listeners:{
                        afterrender: function(obj){

                        },
                        beforeselect: function( obj, record, index, eOpts ){
                            if (index >= 0)
                                return true;
                            else
                                return false;
                        },
                        select: function(obj, records, opts){
                            var rec = records;
                            me.id_urb = rec.get('id_grupo');
                            Ext.getCmp(me.id + '-via').setValue(null);
                            Ext.getCmp(me.id + '-lote').setValue(null);
                            Ext.getCmp(me.id + '-manzana').setValue(null);
                            if(me.clearRefers){
                                Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                Ext.getCmp(me.id + '-nro-interno').setValue(null);
                            }

                            me.id_puerta = null;
                            me.id_mza = null;
                            me.segmentos = [];
                            me.geocoder = null;
                            me.lat= -11.782413062516948;
                            me.lon= -76.79493715625;
                            me.setCoordenadas(records.get('grupo_px'), records.get('grupo_py'));

                            me.flightPath.setMap(null);
                            me.marker.setMap(null);

                            me.setMap({zoon: 16, lat: me.lat, lon: me.lon});

                            var user='';
                            var key ='';
                            if(me.apiAddress){
                                user=me.acceso.user;
                                key=me.acceso.key;
                            }
                            Ext.getCmp(me.id + '-manzana').clearValue();
                            Ext.getCmp(me.id + '-manzana').getStore().load({
                                params:{
                                    vp_ciu_id: me.ciu_id,
                                    vp_id_urb: me.id_urb,
                                    vp_id_via: me.id_via,
                                    user:user,
                                    key:key
                                },
                                callback: function(){
                                    me.clearconsole();
                                }
                            });
                            //console.log(records.get('grupo_px'));
                            var myLatlng = new google.maps.LatLng(records.get('grupo_px'),records.get('grupo_py'));
                            me.marker = new google.maps.Marker({
                                position: myLatlng,
                                map: me.map,
                                title: ''
                            });
                            me.setChangeTrust();
                        },
                        keypress: function(obj, event, opts){
                            Ext.getCmp(me.id + '-via').setValue(null);
                            Ext.getCmp(me.id + '-lote').setValue(null);
                            Ext.getCmp(me.id + '-manzana').setValue(null);
                            if(me.clearRefers){
                                Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                Ext.getCmp(me.id + '-nro-interno').setValue(null);
                            }
                            me.id_urb = 0;
                            me.id_puerta = null;
                            me.id_mza = null;
                            //me.segmentos = [];
                            //me.geocoder = null;
                            me.flightPath.setMap(null);
                            me.marker.setMap(null);
                            me.setChangeTrust();
                        },
                        blur: function(obj, event, opts){
                            var val = Ext.util.Format.trim(obj.getRawValue());
                            if (val == ''){
                                Ext.getCmp(me.id + '-via').setValue(null);
                                Ext.getCmp(me.id + '-lote').setValue(null);
                                Ext.getCmp(me.id + '-manzana').setValue(null);
                                if(me.clearRefers){
                                    Ext.getCmp(me.id+'-referencia-r').setValue(null);
                                    Ext.getCmp(me.id + '-nro-interno').setValue(null);
                                }
                                me.id_urb = 0;
                                me.id_puerta = null;
                                me.id_mza = null;
                                //me.segmentos = [];
                                //me.geocoder = null;
                                me.flightPath.setMap(null);
                                me.marker.setMap(null);
                                me.setChangeTrust();
                            }
                        }
                    }
                });
            }
        }else{
            p.add({
                xtype: 'textfield',
                id: me.id + '-urbanizacion',
                fieldLabel: 'Urbanización / AAHH / Barrio (Grupo vía)',
                flex: 1,
                labelAlign: 'top',
                disabled: state,
                listeners:{
                    keypress: function(obj, event, opts){
                        me.setChangeTrust();
                    },
                    blur: function(obj, event, opts){
                        me.setChangeTrust();
                    }
                }
            });
        }
        
        p.doLayout();
    },
    getSegmentos: function(){
        var me = this;
        var user='';
        var key ='';
        if(me.apiAddress){
            user=me.acceso.user;
            key=me.acceso.key;
        }
        Ext.Ajax.request({
            url: me.url + 'get_gis_busca_via_segmentos/',
            params:{
                vp_id_via: me.id_via,
                vp_ciu_id: me.ciu_id,
                user:user,
                key:key
            },
            success: function(response, options){
                var res = Ext.JSON.decode(response.responseText);
                me.clearconsole();
                //console.log(res.debug.sql);
                me.segmentos = [];
                Ext.Object.each(res.data, function(key, value){
                    me.segmentos.push(new google.maps.LatLng(value.inicio_px, value.inicio_py))
                    me.segmentos.push(new google.maps.LatLng(value.final_px, value.final_py))
                });
                me.setMap({zoon: 16, lat: res.data[0].inicio_px, lon: res.data[0].inicio_py});
                me.flightPath = new google.maps.Polyline({
                    path: me.segmentos,
                    geodesic: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 1
                });

                me.flightPath.setMap(me.map);
            }
        });
    },
    getCombo_via_manz_lote: function(){
        var me = this;
        var p = Ext.getCmp(me.id + '-panel-combo-via_manz_lote');
        p.removeAll();
        var state = me.ciu_id == 0 ? true : false;
        if (me.mapa == 1){
            p.add({
                xtype: 'combo',
                id: me.id + '-via',
                fieldLabel: 'Nº Puerta',
                flex: 1,
                labelAlign: 'top',
                disabled: state,
                store: Ext.create('Ext.data.Store',{
                    fields:[
                        {name: 'numero', type: 'int'},
                        {name: 'punto_x', type: 'float'},
                        {name: 'punto_y', type: 'float'},
                        {name: 'marca', type: 'int'},
                        {name: 'id_puerta', type: 'int'}
                    ],
                    proxy:{
                        type: 'ajax',
                        url: me.url + 'get_gis_busca_via_numero_lote/',
                        reader:{
                            type: 'json',
                            rootProperty: 'data'
                        }
                    },
                    listeners:{
                        load: function(store, records, successful, eOpts){
                            me.clearconsole();
                           // console.log(store.getProxy().getReader().rawData.debug.sql);
                        },
                        beforeload: function(store, operation, opts){
                            me.clearconsole();
                            var proxy = store.getProxy();
                            proxy.setExtraParam('vp_ciu_id',{
                                vp_ciu_id: me.ciu_id
                            });
                            proxy.setExtraParam('vp_id_via',{
                                vp_id_via: me.id_via
                            });
                            proxy.setExtraParam('vp_id_urb',{
                                vp_id_urb: me.id_urb
                            });
                            if(me.apiAddress){
                                var proxy = store.getProxy();
                                proxy.setExtraParam('user',{
                                    user: me.acceso.user
                                });
                                proxy.setExtraParam('key',{
                                    key: me.acceso.key
                                });
                            }
                        }
                    }
                }),
                typeAhead: false,
                hideTrigger: true,
                valueField: 'id_puerta',
                displayField: 'numero',
                queryParam: 'vp_numero',
                minChars: 1,
                emptyText: '[seleccione]',
                enableKeyEvents: true,
                caseSensitive: true,
                autoSelect: false,
                listeners:{
                    afterrender: function(obj, e){
                        
                    },
                    beforeselect: function( obj, record, index, eOpts ){
                        if (index >= 0)
                            return true;
                        else
                            return false;
                    },
                    select: function(obj, records, opts){
                        me.id_puerta = records.get('id_puerta');
                        if(me.clearRefers){
                            Ext.getCmp(me.id+'-referencia-r').setValue(null);
                            Ext.getCmp(me.id + '-nro-interno').setValue(null);
                        }

                        me.setCoordenadas(records.get('punto_x'), records.get('punto_y'));

                        me.setMap({zoon: 16, lat: records.get('punto_x'), lon: records.get('punto_y')});

                        me.flightPath.setMap(null);
                        me.marker.setMap(null);

                        var myLatlng = new google.maps.LatLng(records.get('punto_x'),records.get('punto_y'));
                        me.marker = new google.maps.Marker({
                            position: myLatlng,
                            map: me.map,
                            title: ''
                        });
                        me.setChangeTrust();
                        Ext.getCmp(me.id + '-manzana').focus(true, 500);
                    },
                    keypress: function(obj, event, opts){
                        var vals = Ext.util.Format.trim(Ext.getCmp(me.id + '-lote').getRawValue());
                        if (vals == ''){
                            Ext.getCmp(me.id + '-manzana').setValue(null);
                            Ext.getCmp(me.id + '-lote').setValue(null);
                            me.id_puerta = null;
                            me.id_mza = null;
                            me.flightPath.setMap(null);
                            me.marker.setMap(null);
                        }
                        me.setChangeTrust();
                    },
                    blur: function(obj, event, opts){
                        var val = Ext.util.Format.trim(obj.getRawValue());
                        if (val == ''){
                            var vals = Ext.util.Format.trim(Ext.getCmp(me.id + '-lote').getRawValue());
                            if (vals == ''){
                                Ext.getCmp(me.id + '-manzana').setValue(null);
                                Ext.getCmp(me.id + '-lote').setValue(null);
                                me.id_puerta = null;
                                me.id_mza = null;
                                me.flightPath.setMap(null);
                                me.marker.setMap(null);
                            }
                        }
                        me.setChangeTrust();
                    }
                }
            },
            {
                xtype: 'combo',
                id: me.id + '-manzana',
                fieldLabel: 'Manzana',
                flex: 1,
                labelAlign: 'top',
                disabled: state,
                store: Ext.create('Ext.data.Store',{
                    fields:[
                        {name: 'manzana', type: 'string'},
                        {name: 'punto_x', type: 'float'},
                        {name: 'punto_y', type: 'float'},
                        {name: 'id_mza', type: 'int'}
                    ],
                    proxy:{
                        type: 'ajax',
                        url: me.url + 'get_gis_busca_manzanas/',
                        reader:{
                            type: 'json',
                            rootProperty: 'data'
                        }
                    },
                    listeners:{
                        load: function(store, records, successful, eOpts){
                            me.clearconsole();
                            //console.log(store.getProxy().getReader().rawData.debug.sql);
                        },
                        beforeload: function(store, operation, opts){
                            if(me.apiAddress){
                                var proxy = store.getProxy();
                                proxy.setExtraParam('user',{
                                    user: me.acceso.user
                                });
                                proxy.setExtraParam('key',{
                                    key: me.acceso.key
                                });
                            }
                        }
                    }
                }),
                queryMode: 'local',
                triggerAction: 'all',
                valueField: 'id_mza',
                displayField: 'manzana',
                emptyText: '[seleccione]',
                listeners:{
                    afterrender: function(obj, e){
                        
                    },
                    select: function(obj, records, opts){
                        me.id_mza = records.get('id_mza');
                        Ext.getCmp(me.id + '-via').setValue(null);
                        Ext.getCmp(me.id + '-lote').setValue(null);
                        if(me.clearRefers){
                            Ext.getCmp(me.id+'-referencia-r').setValue(null);
                            Ext.getCmp(me.id + '-nro-interno').setValue(null);
                        }
                        me.id_puerta = null;

                        me.setCoordenadas(records.get('punto_x'), records.get('punto_y'));

                        me.setMap({zoon: 16, lat: records.get('punto_x'), lon: records.get('punto_y')});

                        me.flightPath.setMap(null);
                        var myLatlng = new google.maps.LatLng(records.get('punto_x'),records.get('punto_y'));
                        var marker = new google.maps.Marker({
                            position: myLatlng,
                            map: me.map,
                            title: ''
                        });

                        
                        var user='';
                        var key ='';
                        if(me.apiAddress){
                            user=me.acceso.user;
                            key=me.acceso.key;
                        }
                        
                        Ext.getCmp(me.id + '-lote').clearValue();
                        Ext.getCmp(me.id + '-lote').getStore().load({
                            params:{
                                vp_ciu_id: me.ciu_id,
                                vp_id_mza: me.id_mza,
                                user:user,
                                key:key
                            },
                            callback: function(){
                                me.clearconsole();
                                Ext.getCmp(me.id + '-lote').focus(true, 500);
                            }
                        });
                        me.setChangeTrust();
                    },
                    keypress: function(obj, event, opts){
                        Ext.getCmp(me.id + '-lote').setValue(null);
                        var vals = Ext.util.Format.trim(Ext.getCmp(me.id + '-via').getRawValue());
                        if (vals == ''){
                            Ext.getCmp(me.id + '-via').setValue(null);
                            me.id_puerta = null;
                            me.id_mza = null;
                            me.flightPath.setMap(null);
                            me.marker.setMap(null);
                        }
                        me.setChangeTrust();
                    },
                    blur: function(obj, event, opts){
                        var val = Ext.util.Format.trim(obj.getRawValue());
                        if (val == ''){                            
                            Ext.getCmp(me.id + '-lote').setValue(null);
                            var vals = Ext.util.Format.trim(Ext.getCmp(me.id + '-via').getRawValue());
                            if (vals == ''){
                                Ext.getCmp(me.id + '-via').setValue(null);
                                me.id_puerta = null;
                                me.id_mza = null;
                                me.flightPath.setMap(null);
                                me.marker.setMap(null);
                            }
                        }
                        me.setChangeTrust();
                    }
                }
            },
            {
                xtype: 'combo',
                id: me.id + '-lote',
                fieldLabel: 'Lote/Casa',
                flex: 1,
                labelAlign: 'top',
                disabled: state,
                store: Ext.create('Ext.data.Store',{
                    fields:[
                        {name: 'lote', type: 'string'},
                        {name: 'punto_x', type: 'float'},
                        {name: 'punto_y', type: 'float'},
                        {name: 'id_puerta', type: 'int'},
                        {name: 'puerta', type: 'string'}
                    ],
                    proxy:{
                        type: 'ajax',
                        url: me.url + 'get_gis_busca_lotes/',
                        reader:{
                            type: 'json',
                            rootProperty: 'data'
                        }
                    },
                    listeners:{
                        load: function(store, records, successful, eOpts){
                            me.clearconsole();
                          //  console.log(store.getProxy().getReader().rawData.debug.sql);
                        },
                        beforeload: function(store, operation, opts){
                            if(me.apiAddress){
                                var proxy = store.getProxy();
                                proxy.setExtraParam('user',{
                                    user: me.acceso.user
                                });
                                proxy.setExtraParam('key',{
                                    key: me.acceso.key
                                });
                            }
                        }

                    }
                }),
                queryMode: 'local',
                triggerAction: 'all',
                valueField: 'id_puerta',
                displayField: 'lote',
                emptyText: '[seleccione]',
                listConfig:{
                    minWidth: 100
                },
                listeners:{
                    afterrender: function(obj, e){

                    },
                    select: function(obj, records, opts){
                        me.id_puerta = records.get('id_puerta');
                        if(me.clearRefers){
                            Ext.getCmp(me.id+'-referencia-r').setValue(null);
                            Ext.getCmp(me.id + '-nro-interno').setValue(null);
                        }
                        Ext.getCmp(me.id + '-via').setRawValue(records.get('puerta'));
                        
                        me.setCoordenadas(records.get('punto_x'), records.get('punto_y'));

                        me.setMap({zoon: 16, lat: records.get('punto_x'), lon: records.get('punto_y')});

                        me.flightPath.setMap(null);
                        var myLatlng = new google.maps.LatLng(records.get('punto_x'),records.get('punto_y'));
                        var marker = new google.maps.Marker({
                            position: myLatlng,
                            map: me.map,
                            title: ''
                        });
                        me.setChangeTrust();
                    },
                    keypress: function(obj, event, opts){
                        var vals = Ext.util.Format.trim(Ext.getCmp(me.id + '-via').getRawValue());
                        if (vals == ''){
                            me.id_puerta = null;
                            me.flightPath.setMap(null);
                            me.marker.setMap(null);
                        }
                        me.setChangeTrust();
                    },
                    blur: function(obj, event, opts){
                        var val = Ext.util.Format.trim(obj.getRawValue());
                        if (val == ''){
                            var vals = Ext.util.Format.trim(Ext.getCmp(me.id + '-via').getRawValue());
                            if (vals == ''){
                                me.id_puerta = null;
                                me.flightPath.setMap(null);
                                me.marker.setMap(null);
                            }
                        }
                        me.setChangeTrust();
                    }
                }
            },
            {
                xtype: 'textfield',
                id: me.id + '-nro-interno',
                fieldLabel: 'Nº Interior',
                flex: 1,
                disabled: state,
                labelAlign: 'top'
            });
        }else{
            p.add({
                xtype: 'textfield',
                id: me.id + '-via',
                fieldLabel: 'Nº Puerta',
                flex: 1,
                disabled: state,
                labelAlign: 'top',
                listeners:{
                    keypress: function(obj, event, opts){
                        me.setChangeTrust();
                    },
                    blur: function(obj, event, opts){
                        me.setChangeTrust();
                    }
                }
            },{
                xtype: 'textfield',
                id: me.id + '-manzana',
                fieldLabel: 'Manzana',
                flex: 1,
                disabled: state,
                labelAlign: 'top',
                listeners:{
                    keypress: function(obj, event, opts){
                        me.setChangeTrust();
                    },
                    blur: function(obj, event, opts){
                        me.setChangeTrust();
                    }
                }
            },{
                xtype: 'textfield',
                id: me.id + '-lote',
                fieldLabel: 'Lote/Casa',
                flex: 1,
                disabled: state,
                labelAlign: 'top',
                listeners:{
                    keypress: function(obj, event, opts){
                        me.setChangeTrust();
                    },
                    blur: function(obj, event, opts){
                        me.setChangeTrust();
                    }
                }
            },{
                xtype: 'textfield',
                id: me.id + '-nro-interno',
                fieldLabel: 'Nº Interior',
                flex: 1,
                disabled: state,
                labelAlign: 'top'
            });
        }
        
        p.doLayout();
    },
    geoLocalizar: function(){
        var me = this;
        me.movecoord=0;
        me.setChangeTrust();
        var distrito = Ext.getCmp(me.id + '-distrito').getRawValue();
        var aDistrito = distrito.split('-');
        me.setMap({zoon: 16, lat: me.lat, lon: me.lon});
        me.geocoder = new google.maps.Geocoder();
        var address = Ext.getCmp(me.id + '-nombre_via').getValue() + ',' + aDistrito[0] + ',' + aDistrito[2] + ', Peru';
       // console.log(address);
        me.geocoder.geocode( { 'address': address}, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                me.map.setCenter(results[0].geometry.location);
                me.marker.setMap(null);
                me.marker = new google.maps.Marker({
                    map: me.map,
                    draggable: true,
                    position: results[0].geometry.location
                });
                me.movecoord=1;
                me.setChangeTrust();
                google.maps.event.addListener(me.marker, 'dragend', function(){
                   // console.log(marker.getPosition().lat());
                   // console.log(marker.getPosition().lng());
                    me.setCoordenadas(me.marker.getPosition().lat(),me.marker.getPosition().lng());
                    me.movecoord=2;
                    me.setChangeTrust();
                });
            } else {
                //alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    },
    reset: function(){
        var me = this;
        me.movecoord=0;
        Ext.getCmp(me.id + '-distrito').setValue();

        //Ext.getCmp(me.id + '-tipo_via').clearValue();
        Ext.getCmp(me.id + '-tipo_via').setValue('');
        me.getComboCalle();
        me.getComboUrbanizacion();
        me.getCombo_via_manz_lote();

        Ext.getCmp(me.id + '-distrito').focus(true, 1000);
        if(me.clearRefers){
            Ext.getCmp(me.id+'-referencia-r').setValue('');
            Ext.getCmp(me.id + '-nro-interno').setValue('');
        }
    },
    reset_maps: function(){
        var me = this;
        me.lat = -11.782413062516948;
        me.lon = -76.79493715625;
        me.setMap({zoon: 4, lat: me.lat, lon: me.lon});
        me.flightPath = new google.maps.Polyline();
        me.flightPath.setMap(null);
        me.marker = new google.maps.Marker();
        me.marker.setMap(null);
        me.movecoord=0;
        if(me.clearRefers){
            Ext.getCmp(me.id+'-referencia-r').setValue(null);
            Ext.getCmp(me.id + '-nro-interno').setValue(null);
        }
    },
    reset_global_vars: function(){
        var me = this;
        /**
         * Setting global vars
         */
        me.mapa = 0;
        me.ciu_id = 0;
        me.tip_via = 0;
        me.id_via = 0;
        me.id_urb = 0;
        me.id_puerta = null;
        me.id_mza = null;
        me.segmentos = [];
        me.isVia = false;
        me.geocoder = null;
        me.movecoord=0;
        me.lat= -11.782413062516948;
        me.lon= -76.79493715625;

        me.reset_maps();
        if(me.clearRefers){
            Ext.getCmp(me.id+'-referencia-r').setValue(null);
            Ext.getCmp(me.id + '-nro-interno').setValue(null);
        }
    },
    setCoordenadas: function(lat, lon){
        var me = this;
        me.lat = lat;
        me.lon = lon;
    },
    getCoordenadas: function(){
        var me = this;
        return {latitud: me.lat, longitud: me.lon};
    },
    setobjToString:function(obj){
        var me =this;
        obj=obj[0];
        var tabjson=[];
        for (var p in obj) {
            if (obj.hasOwnProperty(p)) {
                if (obj[p] instanceof Array){
                    tabjson.push('"'+p +'"'+ ':' + me.setobjToString(obj[p]));
                }else{
                    tabjson.push('"'+p +'"'+':"'+obj[p]+'"');
                }
            }
        }  tabjson.push()
        return '{'+tabjson.join(',')+'}';
    },
    getValuesString:function(){
        return this.setobjToString(this.getValuesAddress());
    },
    getValues: function(){
        /**
         * Returning all values of maps
         */
        var me = this;

        var lote = Ext.getCmp(me.id + '-lote').getRawValue();
        var manzana = Ext.getCmp(me.id + '-manzana').getRawValue();
        var nro_via = Ext.getCmp(me.id + '-via').getRawValue();
        var urb = Ext.getCmp(me.id + '-urbanizacion').getRawValue();
        var nom_via = Ext.getCmp(me.id + '-nombre_via').getRawValue();
        var tipo_via = Ext.getCmp(me.id + '-tipo_via').getRawValue();
        var distrito = Ext.getCmp(me.id + '-distrito').getRawValue();
        //var direccion = tipo_via+' '+nom_via+' Urb:'+urb+' VIA:'+nro_via+' MZ:'+manzana+' LT:'+lote;
        var referencia = Ext.getCmp(me.id+'-referencia-r').getValue();
        var nro_interno= Ext.getCmp(me.id + '-nro-interno').getValue();

        var params = [
            {
                ciu_id: me.ciu_id,
                tip_via: me.tip_via,
                id_via: me.id_via,
                id_urb: me.id_urb,
                id_mza: me.id_mza,
                nombre_mza:manzana,
                nombre_urb:urb,
                nro_via:nro_via,
                nro_lote:lote,
                nro_interno:nro_interno,
                coordenadas: [
                    {
                        lat: me.lat,
                        lon: me.lon
                    }
                ],
                dir_calle:nom_via,
                referencia:referencia,
                id_puerta: me.id_puerta
            }
        ];
        return params;
    },
    getValuesAddress: function(){
        /**
         * Returning all values of maps
         */
        var me = this;

        var lote = Ext.getCmp(me.id + '-lote').getRawValue();
        var manzana = Ext.getCmp(me.id + '-manzana').getRawValue();
        var nro_via = Ext.getCmp(me.id + '-via').getRawValue();
        var urb = Ext.getCmp(me.id + '-urbanizacion').getRawValue();
        var nom_via = Ext.getCmp(me.id + '-nombre_via').getRawValue();
        var tipo_via = Ext.getCmp(me.id + '-tipo_via').getRawValue();
        var distrito = Ext.getCmp(me.id + '-distrito').getRawValue();
        //var direccion = tipo_via+' '+nom_via+' Urb:'+urb+' VIA:'+nro_via+' MZ:'+manzana+' LT:'+lote;
        var referencia = Ext.getCmp(me.id+'-referencia-r').getValue();
        var nro_interno= Ext.getCmp(me.id + '-nro-interno').getValue();

        var params = [
            {
                ciu_id: me.ciu_id,
                tip_via: me.tip_via,
                id_via: me.id_via,
                id_urb: me.id_urb,
                id_mza: me.id_mza,
                nombre_mza:manzana,
                nombre_urb:urb,
                nro_via:nro_via,
                nro_lote:lote,
                nro_interno:nro_interno,
                dir_calle:nom_via,
                referencia:referencia,
                id_puerta: me.id_puerta
            }
        ];
        return params;
    },
    getReferencia:function(){
        return Ext.getCmp(me.id+'-referencia-r').getValue();
    },
    setdistrito:function(params){
        var me = this;
        me.reset();
        me.reset_maps();
        me.reset_global_vars();

        var me = this;
        var user='';
        var key ='';
        if(me.apiAddress){
            user=me.acceso.user;
            key=me.acceso.key;
        }

        Ext.getCmp(me.id + '-tipo_via').enable();
        Ext.getCmp(me.id + '-distrito').setValue(null);
        Ext.getCmp(me.id + '-distrito').clearValue();
        me.ciu_id=params.ciu_id;
       
        Ext.getCmp(me.id + '-distrito').getStore().load({
            params:{
                 vp_nomdis: params.nombre_ubi,user:user,key:key
             },
             callback:function(){
                me.clearconsole();
                Ext.getCmp(me.id + '-distrito').setValue(parseInt(params.ciu_id));
                Ext.getCmp(me.id + '-nombre_via').enable();
                var cartografia = Ext.getCmp(me.id + '-distrito').findRecord('ciu_id',parseInt(params.ciu_id));
                //console.log(cartografia.data.mapa);
                me.mapa = cartografia.data.mapa;
             }
        });
        
    },
    setGeoLocalizar: function(params){
        var me = this;
        me.reset();
        me.reset_maps();
        me.reset_global_vars();
        var user='';
        var key ='';
        if(me.apiAddress){
            user=me.acceso.user;
            key=me.acceso.key;
        }

        me.setCoordenadas(params.dir_px,params.dir_py);

        Ext.Ajax.request({
            url:me.url + 'get_gis_export_puerta/',
            params:{
                vp_id_geo:params.id_geo,
                vp_dir_id:params.dir_id,
                user:user,key:key
            },
            success:function(response,options){
                var res = Ext.decode(response.responseText);
                me.clearconsole();
                if(parseInt(res.data[0].error_sql)==0){

                    me.id_puerta = res.data[0].id_geo;
                    me.ciu_id=res.data[0].ciu_id;
                    me.tip_via=res.data[0].tvia_id;
                    me.id_urb=res.data[0].id_urb;
                    me.id_via=res.data[0].id_via;
                    me.id_mza=res.data[0].id_man;
                    me.mapa=res.data[0].mapa;
                    me.isVia = true;
                    me.getComboCalle();
                    me.getComboUrbanizacion();
                    me.getCombo_via_manz_lote();

                    Ext.getCmp(me.id + '-via').enable();
                    Ext.getCmp(me.id + '-tipo_via').setValue(res.data[0].tvia_id);
                    Ext.getCmp(me.id + '-nombre_via').enable();
                    Ext.getCmp(me.id + '-urbanizacion').enable();
                    Ext.getCmp(me.id + '-manzana').enable();
                    Ext.getCmp(me.id + '-lote').enable();
                    Ext.getCmp(me.id+'-referencia-r').enable();
                    Ext.getCmp(me.id + '-nro-interno').enable();

                    

                    Ext.getCmp(me.id + '-distrito').setRawValue(res.data[0].nombre_ubi);
                    Ext.getCmp(me.id + '-nombre_via').setRawValue(res.data[0].via_nombre);
                    Ext.getCmp(me.id + '-urbanizacion').setRawValue(res.data[0].urb_nombre);
                    Ext.getCmp(me.id + '-via').setRawValue(res.data[0].pue_numero);
                    Ext.getCmp(me.id + '-manzana').setRawValue(res.data[0].man_nombre);
                    Ext.getCmp(me.id + '-lote').setRawValue(res.data[0].pue_lote);
                    Ext.getCmp(me.id+'-referencia-r').setValue(res.data[0].dir_referen);
                    Ext.getCmp(me.id + '-nro-interno').setValue(res.data[0].dir_nro_int);

                    if(res.data[0].dir_px!=0){
                        me.flightPath.setMap(null);
                        me.marker.setMap(null);
                        me.setCoordenadas(res.data[0].dir_px, res.data[0].dir_py);
                        me.setMap({zoon: 16, lat: res.data[0].dir_px, lon: res.data[0].dir_py});

                        me.flightPath.setMap(null);
                        var myLatlng = new google.maps.LatLng(res.data[0].dir_px, res.data[0].dir_py);
                        var marker = new google.maps.Marker({
                            position: myLatlng,
                            map: me.map,
                            title: ''
                        });
                    }else{
                        me.geoLocalizar();
                    }
                }
            }
        });
        
    },
    hideHeader:function(){
        var me = this;
        Ext.getCmp(me.id + '-finder-address').getHeader().hide();
    },
    showBanner:function(obj){
        var me = this;
        me.hideHeader();
        Ext.getCmp(me.id + '-finder-address_banner').show();
        Ext.getCmp(me.id + '-finder-address_banner').setHeight(obj.height);
        document.getElementById(me.id + '-finder-address'+'-html-banner').innerHTML=obj.html;
    },
    getTrust:function(){
        var me = this;

        var ciu_id = (me.ciu_id!=null)?me.ciu_id:0;
        var id_via = (me.id_via!=null)?me.id_via:0;
        var id_urb = (me.id_urb!=null)?me.id_urb:0;
        var id_mza = (me.id_mza!=null)?me.id_mza:0;
        var id_puerta = (me.id_puerta!=null)?me.id_puerta:0;

        var trust = 0;
        if(me.mapa==1){
            trust+=(ciu_id!=0)?1:0;
            trust=(id_via!=0)?2:trust;
            trust=(id_urb!=0)?3:trust;
            trust=(id_mza!=0)?4:trust;
            trust=(id_puerta!=0)?5:trust;
        }else{
            trust+=(ciu_id!=0)?1:0;
            trust=(me.movecoord==1)?3:trust;
            trust=(me.movecoord==2)?4:trust;
        }
        return trust;
    },
    setChangeTrust:function(){
        var trust = this.getTrust();
        if(this.trust==undefined)return false;
        if(!this.trust)return false;
        if(trust==0)return false;
        this.setControlTrust();
        var p1 = Ext.get('sliderTrust');
        var color = '#000';
        switch(trust){
            case 1:
                color = '#000';
            break;
            case 2:
                color = '#DF0101';
            break;
            case 3:
                color = '#DF7401';
            break;
            case 4:
                color = '#DBA901';
            break;
            case 5:
                color = '#04B404';
            break;
        }
        p1.show();
        p1.animate({
            to: {
                width:((trust*20)-trust),
                backgroundColor: color
            },
            listeners: {
                beforeanimate:  function() {
                   document.getElementById('TextTrust').innerHTML="Loading...";
                },
                afteranimate: function() {
                    document.getElementById('TextTrust').innerHTML="Confianza ("+(trust*20)+"%)";
                },
                scope: this
            }
        });
    },
    setControlTrust:function(){
        var panelTrust = document.createElement('div');
        panelTrust.setAttribute("id", "panelTrust");
        panelTrust.className = 'panelTrust';
        panelTrust.style.cssText = 'position:absolute;top:10px;left:80px;z-index:11;width:99px;height:10px;-moz-border-radius:2px;border:1px  solid #000;-moz-box-shadow: 0px 0px 8px  #000;;opacity:0.7;';
        document.getElementById(this.divMap).appendChild(panelTrust);

        var SliderTrust = document.createElement('div');
        SliderTrust.setAttribute("id", "sliderTrust");
        SliderTrust.className = 'sliderTrust';
        SliderTrust.style.cssText = 'width:0px;height:6px;margin:1px;background:#000;';
        document.getElementById('panelTrust').appendChild(SliderTrust);

        var TextTrust = document.createElement('div');
        TextTrust.setAttribute("id", "TextTrust");
        TextTrust.className = 'TextTrust';
        TextTrust.style.cssText = 'width:99px;height:10px;font-size:9px;font-weight: bold;';
        document.getElementById('panelTrust').appendChild(TextTrust);
    },
    clearconsole:function() {
      if(window.console || window.console.firebug) {
       if(this.developer==0)console.clear();
      }
    },
    setChangeEvent:function(){
        try{
            var prov_codigo = Ext.getCmp(this.id + '-distrito').getSelection().get('prov_codigo');
            if(prov_codigo!=0 || prov_codigo!=null){
                this.changeEvent(prov_codigo,this.getValues());
            }else{
                this.changeEvent(0,this.getValues());
            }
        }catch(e){
            this.changeEvent(0,this.getValues());
        }
    }
});