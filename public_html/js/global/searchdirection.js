/**
 * Xim php (https://twitter.com/JimAntho)
 * @link    http://zucuba.com/
 * @author  Jimmy Anthony B.S.
 * @version 1.0
 */

Ext.define('grid_agencias',{
    extend:'Ext.data.Model',
    fields:[
        {name:'id_agencia',type:'int'},
        {name:'age_codigo',type:'string'},
        {name:'agencia',type:'string'},
        {name:'dir_calle',type:'string'},
        {name:'dir_referen',type:'string'},
        {name:'ciu_iata',type:'string'},
        {name:'distrito',type:'string'},
        {name:'ciu_id',type:'int'},
        {name:'ciu_ubigeo',type:'string'},
        {name:'dir_px',type:'float'},
        {name:'dir_py',type:'float'},
        {name:'shi_logo',type:'string'},
        {name:'und_id',type:'int'},
        {name:'gps_dist_m',type:'string'},
        {name:'gps_dist_t',type:'string'},
        {name:'gps_time_s',type:'string'},
        {name:'gps_time_t',type:'string'},
        {name:'und_px',type:'float'},
        {name:'und_py',type:'float'},
        {name:'tipo',type:'string'},
        {name:'sentido',type:'string'},
        {name:'dis_text',type:'string'},
        {name:'dis_value',type:'int'},
        {name:'dur_text',type:'string'},
        {name:'dur_value',type:'int'},
        {name:'und_placa',type:'string'},
        {name:'time_t',type:'string'}
    ]
});

Ext.define('Ext.global.searchdirection',{
    extend: 'Ext.panel.Panel',
    alias: 'widget.searchdirection',
    layout: 'fit',
    defaults:{
        border: false
    },
    url: '/gestion/callcenter/',
    url2:'/gestion/gtransporte/',
    task: null,
    map:null,
    flightPath:new google.maps.Polyline(),
    marker: null,
    mapa: 0,
    ciu_id: null,
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
    id_direccion:0,
    id_shipper:0,
    id_age:0,
    gui_srec_id:0,
    vp_vp_guia:0,
    gui_srec_id2:0,
    cnt_recol:0,
    tipo:null,
    record:null,
    directionsDisplay: null,
    directionsService :null,
    arrayMaker:[],
    arrayData:[],
    arrayData2:[],
    arrayDataa:[],
    unidades:[],
    log:{
        id_unidad:0,
        id_agencia:0
    },
    grid2:{
        id_age:0,
    },
    initComponent: function(){
        var me = this;
        this.items = [
                        {
                            xtype:'panel',
                            //cls:'x-accordion-hd',
                            layout: 'border',
                             tbar:[
                                    {
                                        xtype:'radiogroup',
                                        id:me.id+'-rbtn-group',
                                        columns:3,
                                        vertical:true,
                                        items:[
                                                {boxLabel:'Agencia Urbano',name:me.id+'-rbtn',inputValue:'1', width:110,readOnly:true},
                                                {boxLabel:'Agencia Shipper',name:me.id+'-rbtn',inputValue:'2', width:110,readOnly:true},
                                                {boxLabel:'Otra Dirección',name:me.id+'-rbtn',inputValue:'3', width:100,checked:true,readOnly:true},
                                        ],
                                        listeners:{
                                            change:function(obj, newValue, oldValue, eOpts){
                                                var op = parseInt(newValue[me.id+'-rbtn']);
                                                if (op == 1 || op==2){
                                                    Ext.getCmp(me.id+'-direcciones').setHidden(true);
                                                    Ext.getCmp(me.id+'-agencias').setHidden(false);
                                                }else{
                                                    Ext.getCmp(me.id+'-direcciones').setHidden(false);
                                                    Ext.getCmp(me.id+'-agencias').setHidden(true);
                                                }   

                                               // me.get_agenciShipper();
                                                /*if (op == 1){
                                                    me.get_agenciShipper(6);
                                                }else if (op == 2){
                                                    me.get_agenciShipper(me.id_shipper);
                                                }else if (op == 3){
                                                    //console.log('direcion');
                                                }*/
                                               
                                            },
                                            afterrender:function(){
                                                Ext.getCmp(me.id+'-rbtn-group').setVisible(false);
                                            }
                                        }
                                    }
                            ],
                            defaults:{
                                border: false
                            },
                            items:[
                                    {
                                        region: 'west',
                                        id:me.id+'-direcciones',
                                        width: 400,
                                        columnWidth:1,
                                        layout:'fit',
                                        defaults:{
                                            border: false,
                                            style:{
                                                margin: '2px'
                                            }
                                        },
                                        border: false,
                                        bodyStyle: 'background: #fff;',
                                        bbar:[
                                            {
                                                xtype:'button',
                                                columnWidth:0.5,
                                                margin:'10, 0 0 0',
                                                text:'Confirmar Direccion',
                                                icon:'/images/icon/close_nov.ico',
                                                listeners:{
                                                    click:function(obj,e){
                                                        //me.update_direccion();
                                                        if (me.tipo == 'D'){
                                                             me.dispatcher_upd_destino(0);  
                                                             
                                                        }else if (me.tipo == 'O'){
                                                             me.dispatcher_upd_origen(0); 
                                                        }
                                                    }
                                                }
                                            },
                                            {
                                                xtype:'button',
                                                text:'Cancelar',
                                                columnWidth:0.1,
                                                margin:'10, 0 0 0',
                                                icon: '/images/icon/close.png',
                                                listeners:{
                                                    click:function(){
                                                        Ext.getCmp(g_transporte.id+'-center-west').setVisible(false);
                                                        //Ext.getCmp(g_transporte.id+'west').setVisible(true);
                                                        //Ext.getCmp(g_transporte.id+'-chk_vista').setValue(false);
                                                    }
                                                }
                                            }
                                        ],
                                        items:[
                                            {
                                               xtype: 'findlocation',
                                               id: me.id+'-destino',
                                               mapping: false,
                                               getMapping:false,
                                               setMapping:me.setMapping,
                                               listeners:{
                                                    afterrender: function(obj){
                                                    }
                                               }
                                            }
                                            
                                        ]
                                    },
                                    {
                                        region:'center',
                                        id:me.id+'-agencias',
                                        columnWidth:1,
                                        width:400,
                                        layout:'column',
                                        border:false,
                                        bbar:[
                                                {
                                                    xtype:'button',
                                                    text:'Confirmar Agencia',
                                                    icon:'/images/icon/close_nov.ico',
                                                    listeners:{
                                                        click:function(obj,e){
                                                            if (me.tipo=='O'){
                                                                me.dispatcher_upd_origen(me.record);
                                                            }else if(me.tipo='D'){
                                                                me.dispatcher_upd_destino(me.record);    
                                                            }
                                                        }
                                                    }
                                                },
                                                {
                                                    xtype:'button',
                                                    text:'Cancelar',
                                                    columnWidth:0.1,
                                                    margin:'10, 0 0 0',
                                                    icon: '/images/icon/close.png',
                                                    listeners:{
                                                        click:function(){
                                                             Ext.getCmp(g_transporte.id+'-center-west').setVisible(false);
                                                            //Ext.getCmp(g_transporte.id+'-center-west').setVisible(false);
                                                            //Ext.getCmp(g_transporte.id+'west').setVisible(true);
                                                        }
                                                    }
                                                },
                                                {
                                                    xtype:'button',
                                                    text:'Re-Calcular',
                                                    icon: '/images/icon/reloj.ico',
                                                    listeners:{
                                                        click:function(){
                                                            me.get_agenciShipper();
                                                            g_transporte.unidad_actual();
                                                        }
                                                    }
                                                },
                                                {
                                                    xtype:'checkbox',
                                                    id:me.id+'-otra-recolec',
                                                    fieldLabel:'Otra </br>Recolección',
                                                    labelWidth:60,
                                                    listeners:{
                                                        change:function( obj, newValue, oldValue, eOpts ){
                                                            if (newValue){
                                                                Ext.getCmp(me.id+'-grid-recoleccion').setVisible(true);
                                                            }else{
                                                                Ext.getCmp(me.id+'-grid-recoleccion').setVisible(false);
                                                            }
                                                        }
                                                    }
                                                }
                                        ],
                                        items:[
                                                {
                                                    xtype:'grid',
                                                    margin:'5 0 0 0',
                                                    columnWidth:1,
                                                    store:Ext.create('Ext.data.Store',{
                                                        model:'grid_agencias',
                                                        proxy:{
                                                            type:'ajax',
                                                            url:me.url2+'scm_scm_home_delivery_agencia_shipper/',
                                                            reader:{
                                                                type:'json',
                                                                root:'data'
                                                            }
                                                        }
                                                    }),
                                                    id:me.id+'-grid-agencias-longitud',
                                                    columnsLines:true,
                                                    height:'50%',
                                                    columns:{
                                                        items:[
                                                                {
                                                                    text:'Agencia',
                                                                    flex:2.4,
                                                                    dataIndex:'agencia'
                                                                },
                                                               /* {
                                                                    text:'Age.Dist.',
                                                                    flex:1,
                                                                    dataIndex:'gps_dist_t'
                                                                },*/
                                                                {
                                                                    text:'Tiempo',
                                                                    flex:1,
                                                                    //align:'center',
                                                                    dataIndex:'dur_text'
                                                                },
                                                                {
                                                                    text:'Und.Cercana',
                                                                    flex:1,
                                                                    dataIndex:'und_placa'
                                                                },
                                                                {
                                                                    text:'T. Aprox',
                                                                    flex:1,
                                                                    dataIndex:'gps_time_t'
                                                                },
                                                                {
                                                                    text:'T. Total',
                                                                    flex:1,
                                                                    //align:'center',
                                                                    dataIndex:'time_t'
                                                                }
                                                        ]
                                                    },
                                                    listeners:{
                                                        beforeselect:function(obj, record, index, eOpts ){
                                                            me.record = record;
                                                            //console.log(record);
                                                            if (me.tipo=='O'){
                                                                //me.dispatcher_upd_origen(record);    
                                                                   var d = Ext.getCmp(me.id+'-destino').getValues();
                                                                    d_dir_px = parseFloat(d[0].coordenadas[0].lat);
                                                                    d_dir_py = parseFloat(d[0].coordenadas[0].lon);

                                                                    age_dir_px = parseFloat(record.get('dir_px'));
                                                                    age_dir_py = parseFloat(record.get('dir_py'));
                                                                    
                                                                    und_px = parseFloat(record.get('und_px'));
                                                                    und_py = parseFloat(record.get('und_py'));
                                                                    //console.log(record);
                                                                   // console.log(d_dir_px +'</br>'+d_dir_py+'</br>'+o_dir_px+'</br>'+o_dir_py);
                                                                    //me.google_ruta2(o_dir_px,o_dir_py,d_dir_px,d_dir_py,und_px,und_py);
                                                                    me.google_ruta2(und_px,und_py,age_dir_px,age_dir_py,d_dir_px,d_dir_py);
                                                                    me.setUnidad(record.get('und_id'),record.get('id_agencia'),d_dir_px,d_dir_py);
                                                            }else if(me.tipo='D'){
                                                                //me.dispatcher_upd_destino(record);    
                                                            }
                                                        }
                                                    }
                                                },
                                                {
                                                    xtype:'grid',
                                                    id:me.id+'-grid-recoleccion',
                                                    margin:'5 0 0 0',
                                                    columnWidth:1,
                                                    columnsLines:true,
                                                    height:'50%',
                                                    store:Ext.create('Ext.data.Store',{
                                                        model:'grid_agencias',
                                                        proxy:{
                                                            type:'ajax',
                                                            url:me.url2+'getAgencia_shipper/',
                                                            reader:{
                                                                type:'json',
                                                                root:'data'
                                                            }
                                                        }
                                                    }),
                                                    columns:{
                                                        items:[
                                                                {
                                                                    text:'Agencia',
                                                                    flex:1,
                                                                    dataIndex:'agencia'
                                                                },
                                                        ]
                                                    },
                                                    listeners:{
                                                         beforeselect:function(obj, record, index, eOpts ){
                                                            me.record_grid2(record);
                                                         }
                                                    }
                                                }
                                        ]

                                                
                                    }
                            ]
                        }
                            
        ]
        

        this.callParent();
    },
    record_grid2:function(record){
        var  me = this;
        me.grid2.id_age = record.get('id_agencia');
    },
    setMap: function(p){
        var me = this;
        me.map = g_transporte.map;
        me.marker = g_transporte.varsmapa.marker;
        me.directionsDisplay = g_transporte.varsmapa.directionsDisplay;
        me.directionsService = g_transporte.varsmapa.directionsService;
    },
    
    getValues: function(){
        /**
         * Returning all values of maps
         */
        var me = this;

                var lote = Ext.getCmp(me.id + '-lote').getRawValue();
                var manzana = Ext.getCmp(me.id + '-manzana').getRawValue();
                var via = Ext.getCmp(me.id + '-via').getRawValue();
                var urb = Ext.getCmp(me.id + '-urbanizacion').getRawValue();
                var nom_via = Ext.getCmp(me.id + '-nombre_via').getRawValue();
                var tipo_via = Ext.getCmp(me.id + '-tipo_via').getRawValue();
                var distrito = Ext.getCmp(me.id + '-distrito').getRawValue();
                var nro_interno= Ext.getCmp(me.id + '-nro-interno').getValue();
                var referencia = Ext.getCmp(me.id+'-referencia-r').getValue();
    
                var direccion = tipo_via+' '+nom_via+' Urb:'+urb+' VIA:'+via+' MZ:'+manzana+' LT:'+lote;

                var params = [
                    {
                        ciu_id: me.ciu_id,
                        tip_via: me.tip_via,
                        id_via: me.id_via,
                        id_urb: me.id_urb,
                        id_puerta: me.id_puerta == null ? 0:me.id_puerta,
                        id_mza: me.id_mza == null ? 0:me.id_mza,
                        tipo_via:tipo_via,
                        nom_via:nom_via,
                        urb:urb,
                        via:via,
                        lote:lote,
                        manzana:manzana,
                        id_direccion:parseInt(me.id_direccion),
                        coordenadas: [
                            {
                                lat: me.lat,
                                lon: me.lon
                            }
                        ],
                        direccion:direccion,
                        nro_interno:nro_interno,
                        referencia:referencia
                    }
                ];
                return params;
    },
    get_puerta:function(id_puerta){
        var me = this;
        if (id_puerta == 0){
            return;
        }else{
            Ext.Ajax.request({
                url:g_transporte.url+'scm_scm_socionegocio_id_puerta/',
                params:{vl_id_puerta:id_puerta},
                success:function(response,options){
                    var res = Ext.decode(response.responseText);
                    if (parseInt(res.data[0].error_sql)==0){
                        me.setGeoLocalizar(res.data[0]);
                        //console.log(res.data[0])
                    }
                }
            }); 
        }
    },
    select_dispatcher_panel:function(vp_agencia,vp_fecha){
        var me = this;
        me.reset_maps();
        var mask = new Ext.LoadMask(Ext.getCmp(inicio.id+'-tabContent'),{
            msg:'Consultando GPS'
        });
        Ext.Ajax.request({
            url:g_transporte.url+'scm_scm_dispatcher_panel/',
            params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha},
            success:function(response,options){
                var res = Ext.decode(response.responseText).data;
                Ext.each(res, function(obj,index){
                    var coordenadas = new google.maps.LatLng(obj.dir_px,obj.dir_py);
                    if (parseFloat(obj.dir_px) != 0){
                        //console.log(obj.dir_px);
                        //console.log(obj.dir_py);
                        me.setCoordenadas(obj.dir_px, obj.dir_py);
                        me.marker_dispatcher_panel(me.map,coordenadas,obj.gui_srec_id,obj.tipo);    
                    }
                    
                });
                me.setzoon(12);
            }
        });

    },
    marker_dispatcher_panel:function(map,coordenadas,gui_srec_id,tipo){
          var me = this;        
        if (tipo == 'R'){
            //var icon = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=R|4CBEFF|000001'
            //var icon = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=R|4CBEFF|000003|1.7';
            var icon = '/images/icon/marker2.png';
        }else if(tipo == 'E'){
            //var icon = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=E|FF9006|000001';
            var icon = '/images/icon/marker1.png';
        }

        var contentString = '<div id="content"  style="width:255px;">'+
              //string +
              '</div>';
        var infowindow = new google.maps.InfoWindow({
              content: contentString,
              maxWidth: 255
        });      
        var marker = new google.maps.Marker({
              position: coordenadas,
              map: me.map,
              animation: google.maps.Animation.DROP,
              title: '',
              icon:icon,
              gui_srec_id:gui_srec_id,
              tipo:tipo
        });
        //me.setzoon(18);
       // me.arrayMaker.push(marker);
        google.maps.event.addListener(marker, 'click', function() {
            //console.log(marker.gui_srec_id);
            //console.log(marker.tipo);
            //console.log(marker);
            
            var rec = Ext.getCmp(g_transporte.id+'-recol-ruta').getSelectionModel().getSelection();
            if (rec != '') {
                var placa = rec[0].data.placa;
                var id_unidad =rec[0].data.id_unidad;
                
                global.Msg({
                        msg:'gui_srec_id:'+marker.gui_srec_id+'</br>Tipo:'+marker.tipo+'</br>Placa:'+placa+'</br>id_unidad:'+id_unidad,
                        icon:1,
                        buttosn:1,
                        fn:function(btn){
                        }
                    }); 
            }else{
                alert('Debes selecionar una unidad');
            }
        });
    },
    findMaker:function(rec){
        var me = this;
        g_transporte.setMap();
        me.setMap('p');
        for (var i = 0; i < me.arrayMaker.length; i++) {
            if(parseInt(me.arrayMaker[i].gui_srec_id) == rec){
                //console.log(me.arrayMaker[i].gui_srec_id);
                me.arrayMaker[i].setMap(me.map);  
                me.arrayMaker[i].setAnimation(google.maps.Animation.BOUNCE);
            }else{
                me.arrayMaker[i].setMap(me.map);  
            }
        }
        
        //console.log(me.arrayMaker[0].gui_srec_id); 
    },
    setDirections:function(value){
        var me = this;
      
        me.id_shipper = value.id_shipper;
        me.gui_srec_id = value.gui_srec_id;
        me.id_direccion= value.id_direccion;
        me.vp_guia=value.vp_guia;

        //console.log(me.id_direccion);
        //console.log(value.id_direccion);
        if (value.tipo == 'O'){
              console.log(value);
            Ext.getCmp(me.id+'-rbtn-group').setValue({'g_transporte-origen-rbtn':value.valor}); 
            me.id_age = value.id_age;
            /*** Validad Variables***/
            me.gui_srec_id2 = value.gui_srec_id2;
            me.cnt_recol = value.cnt_recol;
            //console.log(me.cnt_recol)
            /*******/
            Ext.getCmp(me.id+'-grid-recoleccion').getStore().load({
               params:{va_shipper:me.id_shipper},
               callback:function(){
                    if (parseInt(me.cnt_recol) > 1){
                        Ext.getCmp(me.id+'-grid-recoleccion').setVisible(true);
                        Ext.getCmp(me.id+'-otra-recolec').setValue(true);
                    }else{
                        Ext.getCmp(me.id+'-grid-recoleccion').setVisible(false);    
                    }
                    
               }
            });
        }
        if (value.tipo == 'D'){
            Ext.getCmp(me.id+'-rbtn-group').setValue({'g_transporte-destino-rbtn':value.valor});
        }    

    },
    get_record_grid:function(id_direccion,record){
        var me = this;
       // me.id_direccion=id_direccion;
        me.id_shipper = record.get('id_shipper');
        
       // me.gui_srec_id = record.get('gui_srec_id');
        //console.log(me.gui_srec_id);
    },
    update_direccion:function(){
        var me = this;
        var mask = new Ext.LoadMask(Ext.getCmp(inicio.id+'-tabContent'),{
            msg:'Loading....'
        });
        mask.show();
        var direc = Ext.getCmp(me.id+'-destino').getValues();
   
        var id_direccion = me.id_direccion;
        var ciu_id = direc[0].ciu_id;
        var tip_via = direc[0].tip_via;
        var id_via = direc[0].id_via;
        var nom_via = direc[0].nom_via;
        var id_urb = direc[0].id_urb;
        var urb = direc[0].urb;
        var id_puerta = direc[0].id_puerta;
        var via = via;
        var x = direc[0].coordenadas[0].lat;
        var y = direc[0].coordenadas[0].lon;
        var id_mza = direc[0].id_mza;
        var lote = direc[0].lote;
        var manzana = direc[0].manzana;

        Ext.Ajax.request({
            url:me.url2+'scm_scm_dispatcher_upd_direccion/',
            params:{id_direccion:id_direccion,ciu_id:ciu_id,tip_via:tip_via,id_via:id_via,nom_via:nom_via,id_urb:id_urb,urb:urb,id_puerta:id_puerta,via:via,x:x,y:y,id_mza:id_mza,lote:lote,manzana:manzana},
            success:function(response,options){
                mask.hide();
                var res = Ext.decode(response.responseText);
                if (parseInt(res.data[0].error_sql)==1){
                    global.Msg({
                        msg:res.data[0].error_info,
                        icon:1,
                        buttosn:1,
                        fn:function(btn){
                            grid = Ext.getCmp(me.id+'-grid-agencias-longitud');
                            if (grid.getStore().getCount()>0){
                                var rec = grid.getStore().getAt(0);
                                //g_transporte.google_ruta(rec.get('o_dir_px') ,rec.get('o_dir_py') ,rec.get('d_dir_px'),rec.get('d_dir_py'));
                            }
                        }
                    });
                }else{
                    global.Msg({
                        msg:res.data[0].error_info,
                        icon:0,
                        buttosn:1,
                        fn:function(btn){
                        }
                    });
                }
            }
        });
    },
    clear_maps:function(){
        var me = this;
        g_transporte.setMap();
        me.setMap('p');
    },
    get_agenciShipper:function(){
        var me = this;
       // me.unidades_xy();
        me.clear_maps();
        me.arrayDataa = [];
        var va_shipper=0;
        var chek = Ext.getCmp(me.id+'-rbtn-group').getValue();
        var op;

        if (me.tipo == 'D'){
            op = chek['g_transporte-destino-rbtn'];
        }else if (me.tipo == 'O'){
            op = chek['g_transporte-origen-rbtn'];;    
        }

        if (parseInt(op) == 1){
            va_shipper = 6;
            //me.get_agenciShipper(6);
        }else if (parseInt(op) == 2){
           // me.get_agenciShipper(me.id_shipper);
           va_shipper = me.id_shipper;
        }else if (parseInt(op) == 3){
            
        }

        var mask = new Ext.LoadMask(Ext.getCmp(g_transporte.id+'-tab'),{
            msg:'Calculando Rutas...'
        });
        mask.show();
        var arrayAgencia = [];
        grid = Ext.getCmp(me.id+'-grid-agencias-longitud');
        grid.getStore().load({
            params:{va_shipper:va_shipper},
            callback:function(){
                Ext.Ajax.request({
                    url:me.url2+'scm_scm_home_delivery_agencia_shipper/',//'getAgencia_shipper/',
                    params:{va_shipper:va_shipper},
                    success:function(response,options){
                        var res = Ext.decode(response.responseText).data;
                        Ext.each(res,function(v,index){
                            arrayAgencia.push(v);
                            var agencia = new google.maps.LatLng(v.dir_px,v.dir_py);
                            var marker = new google.maps.Marker({
                                    position: agencia,
                                    map: me.map,
                                    title: '',
                                    icon:'/images/icon/'+v.shi_logo//'/images/icon/MIFARMA.jpg'
                            });
                            var contentString = '<div id="content"  style="width:80px;">'+
                                v.agencia +
                                '</div>';
                            var infowindow = new google.maps.InfoWindow({
                                  content: contentString,
                                  maxWidth: 80
                            }); 
                            google.maps.event.addListener(marker, 'click', function() {
                                infowindow.open(me.map,marker);
                            });
                        }); 
                        //me.google_time(arrayAgencia);   
                        me.time_destino();
                        mask.hide();
                    }
                });
            }

        });
            
    },
    time_destino:function(){
        var me = this;
        var arrayData = [];
        var va_shipper=me.id_shipper;
        var d = Ext.getCmp(me.id+'-destino').getValues();
        grid = Ext.getCmp(me.id+'-grid-agencias-longitud');
        store = grid.getStore();

        ent_dir_px = parseFloat(d[0].coordenadas[0].lat);
        ent_dir_py = parseFloat(d[0].coordenadas[0].lon);
        Ext.Ajax.request({
            url:me.url2+'setCalculoDistanciaDuracion/',
            params:{pos_px:ent_dir_px,pos_py:ent_dir_py,va_shipper:va_shipper},
            success:function(response,options){
                var res = Ext.decode(response.responseText).data;
                //console.log(res);
                store = grid.getStore();
                store.each(function(record, idx){
                    var id_agencia = parseInt(record.get('id_agencia'));
                    var und_time = parseInt(record.get('gps_time_t'));

                    Ext.each(res,function(obj,index){
                        var id_agencia2 = parseInt(obj.id_agencia);
                        if(id_agencia == id_agencia2){
                           var age_time = parseInt(obj.dur_text);
                           var time_tot = age_time+und_time;
                           time_tot = time_tot.toString()+' min';
                           record.set('dis_text', obj.dis_text);
                           record.set('dis_value', obj.dis_value);
                           record.set('dur_text', obj.dur_text);
                           record.set('dur_value', obj.dur_value);
                           record.set('time_t', time_tot);
                           record.commit();
                        }
                        
                    });

                });
                grid.getView().refresh(); 
                store.each(function(record, idx){
                    arrayData.push(record);
                });
                arrayData.sort(function(a,b) { return parseInt(a.data.time_t) - parseInt(b.data.time_t)});
                //console.log(arrayData);
                grid.getStore().loadData(arrayData);
                grid.getView().refresh(); 
            }

        });
    },
    google_time:function(array){
        var me =this;
        //me.setMap('p');
        arrayData = [];
        var xy;

        if (me.tipo == 'D'){
            xy = Ext.getCmp(g_transporte.id+'-origen').getValues();    
        }else if(me.tipo == 'O'){
            xy = Ext.getCmp(me.id+'-destino').getValues();    
        }

        //console.log(me.tipo);
        //console.log(me.tipo);
        //console.log(xy[0].coordenadas[0].lat);
        //console.log(xy[0].coordenadas[0].lon);

        grid = Ext.getCmp(me.id+'-grid-agencias-longitud');
        d_dir_px = parseFloat(xy[0].coordenadas[0].lat);
        d_dir_py = parseFloat(xy[0].coordenadas[0].lon);
        var destino = new google.maps.LatLng(d_dir_px,d_dir_py);
        //console.log(destino);
        //console.log(array);
        var count = array.length -1 ;
        Ext.each(array,function(v,index){
            var OriginLatlng = new google.maps.LatLng(parseFloat(v.dir_px),parseFloat(v.dir_py));
            var request = {
                origin:OriginLatlng,
                destination:destino,
              //  waypoints:[],//{location: agencia},{location: '-13.918217, -74.330162'}  
                //optimizeWaypoints: true,
                travelMode: google.maps.TravelMode.DRIVING,
                //provideRouteAlternatives: true
            };

            global.sleep(200);
            me.directionsService.route(request,function(response,status){
                if (status == google.maps.DirectionsStatus.OK){
                    arrayData.push({
                        age_codigo:v.age_codigo,
                        agencia:v.agencia,
                        ciu_iata:v.ciu_iata,
                        ciu_id:v.ciu_id,
                        ciu_ubigeo:v.ciu_ubigeo,
                        dir_calle:v.dir_calle,
                        o_dir_px:parseFloat(v.dir_px),
                        o_dir_py:parseFloat(v.dir_py),
                        d_dir_px:parseFloat(d_dir_px),
                        d_dir_py:parseFloat(d_dir_py),
                        dir_referen:v.dir_referen,
                        distrito:v.distrito,
                        id_agencia:v.id_agencia,
                        distance:response.routes[0].legs[0].distance.text,
                        duration:response.routes[0].legs[0].duration.text,
                        distancev:parseInt(response.routes[0].legs[0].distance.text),
                        durationv:parseInt(response.routes[0].legs[0].duration.text),
                        aduration:100000000000,
                        total:0,
                        placa:''

                    });
                    arrayData.sort(function(a,b) { return parseInt(a.durationv) - parseInt(b.durationv)});
                    grid.getStore().loadData(arrayData);
                    grid.getView().refresh(); 
                }else{
                    arrayData.push({
                        age_codigo:v.age_codigo,
                        agencia:v.agencia,
                        ciu_iata:v.ciu_iata,
                        ciu_id:v.ciu_id,
                        ciu_ubigeo:v.ciu_ubigeo,
                        dir_calle:v.dir_calle,
                        o_dir_px:parseFloat(v.dir_px),
                        o_dir_py:parseFloat(v.dir_py),
                        d_dir_px:parseFloat(d_dir_px),
                        d_dir_py:parseFloat(d_dir_py),
                        dir_referen:v.dir_referen,
                        distrito:v.distrito,
                        id_agencia:v.id_agencia,
                        distance:'No se encontraron datos',
                        duration:'No se encontraron datos',
                        distancev:1000000000,
                        durationv:1000000000,
                        aduration:100000000000,
                        total:0,
                        placa:''

                    });
                    arrayData.sort(function(a,b) { return parseInt(a.durationv) - parseInt(b.durationv)});
                    grid.getStore().loadData(arrayData);
                    grid.getView().refresh(); 
                    if (status ==  'OVER_QUERY_LIMIT'){
                         global.Msg({
                            msg:'Debes esperar 10 segundos </br> Antes de Volver a Confirmar Destino',
                            icon:1,
                            buttosn:1,
                            fn:function(btn){
                                 Ext.getCmp(me.id+'-grid-agencias-longitud').getStore().removeAll();  
                            }
                        }); 
                    }
                   
                   
                }
                
                /*if (count == index){
                   // console.log(count);
                   // console.log(index);
                    Ext.each(array,function(a){
                        var OriginLatlng = new google.maps.LatLng(parseFloat(a.dir_px),parseFloat(a.dir_py));
                        me.arrayDataa = null;
                        me.arrayDataa = [];
                        var a = me.distanceUnidad(OriginLatlng,a.id_agencia);
                    });
                }*/
              
            }); 
        });     
    },

    distanceUnidad:function(destino,id_agencias){
        console.log(id_agencias);
        var me = this;
        //var unidades = me.unidades_xy();
        var unidades = me.unidades;
        
      
        me.arrayDataa = null;
        me.arrayDataa = [];
        var clear = [];
        var contador=0;
        Ext.each(unidades,function(ab,index){
            if (ab.pos_px != ''){
                contador=contador+1;
            }
        });

      
        Ext.each(unidades, function(obj,index){
            var origen = new google.maps.LatLng(parseFloat(obj.pos_px),parseFloat(obj.pos_py));
            //var destino = new google.maps.LatLng(-11.782413062516948,-76.79493715625);
            if (obj.pos_px != ''){
                //console.log(obj.pos_px)
                var request = {
                    origin:origen,
                    destination:destino,
                    //waypoints:[],//{location: agencia},{location: '-13.918217, -74.330162'}  
                   // optimizeWaypoints: true,
                    travelMode: google.maps.TravelMode.DRIVING,
                   // provideRouteAlternatives: true
                };
               
                //global.sleep(1001);
                me.directionsService.route(request,function(response,status){
                        if (status == google.maps.DirectionsStatus.OK){ 
                           // console.log(response);
                                me.arrayDataa.push({
                                    distancetext:response.routes[0].legs[0].distance.text,
                                    distance:parseInt(response.routes[0].legs[0].distance.text),
                                    durationtext:response.routes[0].legs[0].duration.text,
                                    duration:parseInt(response.routes[0].legs[0].duration.text),
                                    id_unidad:obj.id_unidad,
                                    id_agencia:id_agencias,
                                    pos_px:parseFloat(obj.pos_px),
                                    pos_py:parseFloat(obj.pos_py),
                                    placa:obj.placa
                                });                              
                        }else{
                            console.log(status);
                            me.arrayDataa.push({
                                distancetext:'No se encontro datos',
                                distance:1000000,
                                durationtext:'No se encontro datos',
                                duration:1000000,
                                id_unidad:obj.id_unidad,
                                id_agencia:id_agencias,
                                pos_px:parseFloat(obj.pos_px),
                                pos_py:parseFloat(obj.pos_py),
                                placa:obj.placa
                            });  
                            if (status ==  'OVER_QUERY_LIMIT' || status == 'UNKNOWN_ERROR'){
                                me.arrayDataa.push({
                                    distancetext:'No se encontro datos',
                                    distance:1000000,
                                    durationtext:'No se encontro datos',
                                    duration:1000000,
                                    id_unidad:obj.id_unidad,
                                    id_agencia:id_agencias,
                                    pos_px:parseFloat(obj.pos_px),
                                    pos_py:parseFloat(obj.pos_py),
                                    placa:obj.placa
                                });  
                            }
                        }
                       // global.sleep(100);
                        me.arrayDataa.sort(function(a,b) { return parseInt(a.duration) - parseInt(b.duration)});
                        //console.log(me.arrayDataa[0]);
                        //clear=me.arrayData[0]; 
                       // me.setdata();
                       if (index == contador-1){
                            //console.log(me.arrayDataa);
                            //console.log(contador);
                            //console.log(me.arrayDataa.length);
                            //global.sleep(100);
                            me.call_distance_moto(); 
                            me.arrayDataa=[];
                       }
                       

                });
            }
        });
       /*  var me = this;
       
        var unidades = me.unidades;
        
      
        me.arrayDataa = null;
        me.arrayDataa = [];
        var clear = [];
        var contador=0;
        Ext.each(unidades,function(ab,index){
            if (ab.pos_px != ''){
                contador=contador+1;
            }
        });
        var service = new google.maps.DistanceMatrixService();
        global.sleep(200);         
        Ext.each(unidades, function(obj,index){
            var origen = new google.maps.LatLng(parseFloat(obj.pos_px),parseFloat(obj.pos_py));
            //var destino = new google.maps.LatLng(-11.782413062516948,-76.79493715625);
            if (obj.pos_px != ''){
               // console.log(destino);
              //console.log(origen);
                var request = {
                       origins: [origen],
                       destinations: [destino],//es la agencia
                       travelMode: google.maps.TravelMode.DRIVING,
                       unitSystem: google.maps.UnitSystem.METRIC,
                       avoidHighways: false,
                       avoidTolls: false
                }   

               // global.sleep(300); 
                
                global.sleep(200);
                service.getDistanceMatrix(request, function(response, status){
                       
                        if (status == google.maps.DistanceMatrixStatus.OK){
                            if (response.rows[0].elements[0].status=='OK'){
                                //console.log(parseInt(response.rows[0].elements[0].distance.value));
                                me.arrayDataa.push({
                                    distancetext:response.rows[0].elements[0].distance.text,
                                    distance:parseInt(response.rows[0].elements[0].distance.value),
                                    durationtext:response.rows[0].elements[0].duration.text,
                                    duration:parseInt(response.rows[0].elements[0].duration.value),
                                    id_unidad:obj.id_unidad,
                                    id_agencia:id_agencias,
                                    pos_px:obj.pos_px,
                                    pos_py:obj.pos_py,
                                    placa:obj.placa
                                });

                            }else{
                                 me.arrayDataa.push({
                                    distancetext:'No se encontro datos',
                                    distance:1000000,
                                    durationtext:'No se encontro datos',
                                    duration:1000000,
                                    id_unidad:obj.id_unidad,
                                    id_agencia:id_agencias,
                                    pos_px:obj.pos_px,
                                    pos_py:obj.pos_py,
                                    placa:obj.placa
                                });    

                            }                                
                        }else{
                            me.arrayDataa.push({
                                distancetext:'No se encontro datos',
                                distance:1000000,
                                durationtext:'No se encontro datos',
                                duration:1000000,
                                id_unidad:obj.id_unidad,
                                id_agencia:id_agencias,
                                pos_px:obj.pos_px,
                                pos_py:obj.pos_py,
                                placa:obj.placa
                            });  

                        }
                        global.sleep(100);
                        me.arrayDataa.sort(function(a,b) { return parseInt(a.duration) - parseInt(b.duration)});
                        //console.log(me.arrayDataa[0]);
                        //clear=me.arrayData[0]; 
                       // me.setdata();
                       if (index == contador-1){
                            console.log(me.arrayDataa);
                            //global.sleep(100);
                            me.call_distance_moto(); 
                       }
                       

                });
            }
        });*/
      
    },
    setdata:function(){
        var me =this;
        console.log(me.arrayDataa);
        //me.arrayDataa=[];
    },
    call_distance_moto:function(){
        
        var me = this;
        //console.log(me.arrayDataa);
       // var arrayData =[];
       // var arrayData2 =[];
        //var distance = [];
        var result = me.arrayDataa[0];
        grid = Ext.getCmp(me.id+'-grid-agencias-longitud');
        store = grid.getStore();
       // console.log(result);
        store.each(function(record, idx){
            //console.log(record);
            var id_agencia = record.get('id_agencia');
            var val2 = record.get('adurationtext');
            var aduration = record.get('aduration');
            var duration =record.get('duration');
            if (parseInt(result.id_agencia) == parseInt(id_agencia)){//&& parseInt(result.duration) < parseInt(aduration)
                global.sleep(200);
                //console.log(result.placa);
                //var segundos = parseInt(record.get('durationv'))+parseInt(result.duration);
                //var total = (segundos*1)/60;
                //console.log(total);
                //console.log(parseInt(record.get('durationv')));
                //console.log(parseInt(result.duration));
                var time_unidad = result.durationtext.split(" ");
                var time_agencia = duration.split(" ");
                var total = parseInt(time_unidad[0])+parseInt(time_agencia[0]);
                total = total.toString() +' min';
                 global.sleep(200);
                record.set('adurationtext', result.durationtext);
                record.set('aduration',result.duration);
                record.set('id_unidad', result.id_unidad);
                record.set('und_px', result.pos_px);
                record.set('und_py', result.pos_py);
                record.set('total', total);
                record.set('placa', result.placa);
                record.commit();
                grid.getView().refresh(); 
            }
           
        });
        grid.getView().refresh(); 
        me.arrayDataa=[];


    },
    unidades_xy:function(){
        /*var me = this;
        var vp_agencia = Ext.getCmp(g_transporte.id+'-agencia').getValue();
        var vp_fecha = Ext.getCmp(g_transporte.id+'-fecha').getRawValue();
        var ajax = Ext.Ajax.request({
            async:false,
            url:g_transporte.url+'scm_scm_home_delivery_unidad_gps/',
            params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha},
            success:function(response,options){
               // var res = Ext.decode(response.responseText).data;
               // console.log(res);
                //response = res;
            }
        });   
        //console.log(ajax);
        var respuesta = Ext.decode(ajax.responseText);
        me.unidades = respuesta.data;
       // return respuesta.data;*/
    },
    google_ruta:function(o_dir_px,o_dir_py,d_dir_px,d_dir_py){
        var me =this;
        me.pinta_destino(d_dir_px,d_dir_py);

        var OriginLatlng = new google.maps.LatLng(o_dir_px,o_dir_py);
        var destino = new google.maps.LatLng(d_dir_px,d_dir_py);
        var request = {
            origin:OriginLatlng,
            destination:destino,
          //  waypoints:[],//{location: agencia},{location: '-13.918217, -74.330162'}  
            optimizeWaypoints: true,
            travelMode: google.maps.TravelMode.DRIVING,
            provideRouteAlternatives: true
        };
        me.directionsService.route(request,function(response,status){
            if (status == google.maps.DirectionsStatus.OK){
                me.directionsDisplay.setDirections(response);
                
            }else{
                alert('no hay ruta');
            }
            /*******************Captura los Punto y dibuja la Pilylinea*******************************/
            var consolidado=[];
            me.flightPath.setMap(null);
            var getRuta = me.directionsDisplay.getDirections();
            Ext.each(getRuta.routes[0].legs,function(obj1,index){
                Ext.each(obj1.steps,function(obj2,inde2){
                    if (obj2.lat_lngs.length > 0){
                        Ext.each(obj2.lat_lngs,function(obj3,index3){
                            var OLatlng = new google.maps.LatLng(obj3.k,obj3.D);
                            consolidado.push(OLatlng);
                        });
                    }    
                });
            });
            me.flightPath = new google.maps.Polyline({
                path: consolidado,
                geodesic: true,
                strokeColor: '#2A02DC',
                strokeOpacity: 0.5,
                strokeWeight: 10,
                //draggable: true,
                map: me.map
            });
            console.log(consolidado);
            google.maps.event.addListener(me.directionsDisplay, 'directions_changed', function() {
                consolidado=[];
                me.flightPath.setMap(null);
                var getRuta = me.directionsDisplay.getDirections();
                Ext.each(getRuta.routes[0].legs,function(obj1,index){
                    Ext.each(obj1.steps,function(obj2,inde2){
                        if (obj2.lat_lngs.length > 0){
                            Ext.each(obj2.lat_lngs,function(obj3,index3){
                                var OLatlng = new google.maps.LatLng(obj3.k,obj3.D);
                                consolidado.push(OLatlng);
                            });
                        }    
                    });
                });
                console.log(consolidado);
                me.flightPath = new google.maps.Polyline({
                    path: consolidado,
                    geodesic: true,
                    strokeColor: '#2A02DC',
                    strokeOpacity: 0.5,
                    strokeWeight: 10,
                    //draggable: true,
                    /*icons: [{
                      icon: lineSymbol,
                      offset: '100%'
                    }],*/
                    map: me.map
                });
            });  
            /*********************************************************************************************/
        });
    },
    //google_ruta2:function(o_dir_px,o_dir_py,d_dir_px,d_dir_py,und_x,und_y){
    google_ruta2:function(und_px,und_py,age_dir_px,age_dir_py,d_dir_px,d_dir_py){    
        var me =this;
        me.pinta_destino(d_dir_px,d_dir_py);
        var origen = new google.maps.LatLng(und_px,und_py); 
        var agencia = new google.maps.LatLng(age_dir_px,age_dir_py);
        var destino = new google.maps.LatLng(parseFloat(d_dir_px),parseFloat(d_dir_py));
        var request = {
            origin:origen,
            destination:destino,
            waypoints:[{location:agencia}],//{location: agencia},{location: '-13.918217, -74.330162'}  
            optimizeWaypoints: true,
            travelMode: google.maps.TravelMode.DRIVING,
            provideRouteAlternatives: true,
        };
        me.directionsService.route(request,function(response,status){
            if (status == google.maps.DirectionsStatus.OK){
                //console.log(response);
                //directionsDisplay.setDirections(response);
                me.directionsDisplay.setDirections(response);
                
            }else{
                alert('no hay ruta');
            }
            var consolidado=[];
            var lineSymbol = {
                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
            };
            /*google.maps.event.addListener(me.directionsDisplay, 'directions_changed', function() {
                consolidado=[];
                me.flightPath.setMap(null);
                var getRuta = me.directionsDisplay.getDirections();
                Ext.each(getRuta.routes[0].legs,function(obj1,index){
                    Ext.each(obj1.steps,function(obj2,inde2){
                            if (obj2.lat_lngs.length > 0){
                                Ext.each(obj2.lat_lngs,function(obj3,index3){
                                    var OLatlng = new google.maps.LatLng(obj3.k,obj3.D);
                                    consolidado.push(OLatlng);
                                });
                            }    
                    });
                });
                me.flightPath = new google.maps.Polyline({
                    path: consolidado,
                    geodesic: true,
                    strokeColor: '#FF0101',
                    strokeOpacity: 1,//0.5,
                    strokeWeight: 10,
                    //draggable: true,
                    icons: [{
                      icon: lineSymbol,
                      offset: '100%'
                    }],
                    map: me.map
                });
            });   */
        });
    },
    pinta_unidad_actual:function(x,y,icono,placa){
        var me = this;
        me.setMap();
        var contentString = '<div id="content"  style="width:70px;">'+
                          placa
                          '</div>';
        var infowindow = new google.maps.InfoWindow({
              content: contentString,
              maxWidth: 70
        });
        var unidad = new google.maps.LatLng(x,y);
        var marker = new google.maps.Marker({
                position: unidad,
                map: me.map,
                title: '',
                draggable: true,
                icon:icono
        });
        //infowindow.open(me.map,marker);

        google.maps.event.addListener(marker, 'click', function() {
             infowindow.open(me.map,marker);
        });
    },
    pinta_destino:function(d_dir_px,d_dir_py){
        var me = this;
        var agencia = new google.maps.LatLng(d_dir_px,d_dir_py);
        me.marker = new google.maps.Marker({
                position: agencia,
                map: me.map,
                title: '',
                icon:'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%C2%B0|0BBCDC|000001'
                
        });
        /*var chek;
        var vp_origen;
        var va_shipper;
        if (me.tipo== 'O'){
            chek = Ext.getCmp(me.id+'-rbtn-group').getValue();
            vp_origen = chek['g_transporte-origen-rbtn'];    
        }else if (me.tipo='D'){
            chek = Ext.getCmp(me.id+'-rbtn-group').getValue();
            vp_origen = chek['g_transporte-destino-rbtn'];    
        }

        if (parseInt(vp_origen) == 1){
            va_shipper = 6;
        }else if (parseInt(vp_origen) == 2){
            va_shipper=me.id_shipper;
        }*/

        /*var me = this;
        var mask = new Ext.LoadMask(Ext.getCmp(g_transporte.id+'-tab'),{
            msg:'Calculando Rutas...!'
        });
        mask.show();
        var arrayAgencia = [];
        Ext.Ajax.request({
            url:me.url2+'getAgencia_shipper/',
            params:{va_shipper:va_shipper},
            success:function(response,options){
                var res = Ext.decode(response.responseText).data;
                Ext.each(res,function(v,index){
                    arrayAgencia.push(v);
                    var agencia = new google.maps.LatLng(v.dir_px,v.dir_py);
                    me.marker = new google.maps.Marker({
                            position: agencia,
                            map: me.map,
                            title: '',
                            icon:'/images/icon/MIFARMA.jpg'
                    });
                }); 
                mask.hide();
            }
        });*/
    },
    dispatcher_upd_origen:function(record){
        
        var me = this;        
        var chek = Ext.getCmp(me.id+'-rbtn-group').getValue();
        
        var vp_srec_id = me.gui_srec_id;
        var vp_srec_id2 = me.gui_srec_id2;
        var vp_origen = chek['g_transporte-origen-rbtn'];
        var vp_provincia =0;
        var vp_id_age=0;
        var vp_dir_id=0;
        var vp_ciu_id=0;
        var vp_id_contacto=0;
        var vp_id_area=0;
        var vp_id_geo=0;
        var vp_dir_calle='';
        var vp_dir_refere='';
        var vp_dir_numvia='';
        var vp_dir_px=0;
        var vp_dir_py=0;

        if (parseInt(vp_origen)==2 ){
            vp_id_age=record.get('id_agencia');
            vp_ciu_id=record.get('ciu_id');

           /* var d = Ext.getCmp(g_transporte.id+'-destino').getValues();
            d_dir_px = parseFloat(d[0].coordenadas[0].lat);
            d_dir_py = parseFloat(d[0].coordenadas[0].lon);

            o_dir_px = parseFloat(record.get('o_dir_px'));
            o_dir_py = parseFloat(record.get('o_dir_py'));*/
            //console.log(record);
           // console.log(d_dir_px +'</br>'+d_dir_py+'</br>'+o_dir_px+'</br>'+o_dir_py);
            //me.google_ruta(o_dir_px,o_dir_py,d_dir_px,d_dir_py);
        }

        Ext.Ajax.request({
           // url:me.url2+'scm_scm_dispatcher_upd_origen/',
            url:me.url2+'scm_scm_home_delivery_upd_origen/',
            params:{
                vp_guia:me.vp_guia,
                vp_srec_id:vp_srec_id,
                vp_origen:vp_origen,
                vp_provincia:vp_provincia,
                vp_id_age:vp_id_age,
                vp_dir_id:vp_dir_id,
                vp_ciu_id:vp_ciu_id,
                vp_id_geo:vp_id_geo,
                vp_dir_calle:vp_dir_calle,
                vp_dir_numvia:vp_dir_numvia,
                vp_dir_refere:vp_dir_refere,
                vp_dir_px:vp_dir_px,
                vp_dir_py:vp_dir_py,
                },
            success:function(response,options){
                var res = Ext.decode(response.responseText);
               // console.log(me.grid2.id_age);
                if (parseInt(res.data[0].error_sql)==1){
                    global.Msg({
                        msg:res.data[0].error_info,
                        icon:1,
                        buttosn:1,
                        fn:function(btn){
                            var valor = Ext.getCmp(me.id+'-otra-recolec').getValue();
                            if (valor){
                                Ext.Ajax.request({
                                     url:me.url2+'scm_scm_home_delivery_upd_origen/',
                                     params:{
                                        vp_guia:me.vp_guia,
                                        vp_srec_id:vp_srec_id2,
                                        vp_origen:vp_origen,
                                        vp_provincia:vp_provincia,
                                        vp_id_age:me.grid2.id_age,
                                        vp_dir_id:vp_dir_id,
                                        vp_ciu_id:vp_ciu_id,
                                        vp_id_geo:vp_id_geo,
                                        vp_dir_calle:vp_dir_calle,
                                        vp_dir_numvia:vp_dir_numvia,
                                        vp_dir_refere:vp_dir_refere,
                                        vp_dir_px:vp_dir_px,
                                        vp_dir_py:vp_dir_py,
                                     },
                                     success:function(response,options){
                                        var res = Ext.decode(response.responseText);
                                           if (parseInt(res.data[0].error_sql)==1){
                                              global.Msg({
                                                msg:res.data[0].error_info,
                                                icon:1,
                                                buttosn:1,
                                                fn:function(btn){
                                                     Ext.getCmp(g_transporte.id+'-acoordion-ruta').setCollapsed(false);
                                                }
                                              });
                                           }else{
                                                global.Msg({
                                                    msg:res.data[0].error_info,
                                                    icon:0,
                                                    buttosn:1,
                                                    fn:function(btn){
                                                    }
                                                });
                                           }
                                     }
                                });
                            }else{
                             Ext.getCmp(g_transporte.id+'-acoordion-ruta').setCollapsed(false);
                            }
                        }
                    });
                }else{
                    global.Msg({
                        msg:res.data[0].error_info,
                        icon:0,
                        buttosn:1,
                        fn:function(btn){
                        }
                    });
                }
            }
        });
    },
    dispatcher_upd_destino:function(record){
        var  me = this;        
        var chek = Ext.getCmp(me.id+'-rbtn-group').getValue();
        
        var vp_srec_id = me.gui_srec_id;
        var vp_origen = chek['g_transporte-destino-rbtn'];
        var vp_provincia =0;
        var vp_id_age=0;
        var vp_dir_id=0;
        var vp_ciu_id=0;
        var vp_id_contacto=0;
        var vp_id_area=0;
        var vp_id_geo=0;
        var vp_dir_calle='';
        var vp_dir_refere='';
        var vp_dir_numvia='';
        var vp_dir_px=0;
        var vp_dir_py=0;

        if (parseInt(vp_origen)==2 ){
            vp_id_age=record.get('id_agencia');
            vp_ciu_id=record.get('ciu_id');
        }else if(parseInt(vp_origen)==3 ){
           // console.log(me.getValues());
            var v = Ext.getCmp(me.id+'-destino').getValues();
            vp_dir_id= v[0].id_direccion;
            vp_ciu_id= v[0].ciu_id;
            vp_id_geo= v[0].id_puerta;
            vp_dir_calle= v[0].direccion;
            vp_dir_numvia= v[0].via;
            vp_dir_px = v[0].coordenadas[0].lat;
            vp_dir_py = v[0].coordenadas[0].lon;
            
        }

        if (parseInt(vp_origen)==3 && parseFloat(vp_dir_px)==-11.782413062516948 || parseFloat(vp_dir_py)==-76.79493715625 || parseFloat(vp_dir_px)==0 || parseFloat(vp_dir_py)==0 ){
            global.Msg({
                msg:'Debes Realizar la busqueda...',
                icon:1,
                buttosn:1,
                fn:function(btn){
                }
            });
        }else{
               // Ext.getCmp(g_transporte.id+'-origen').get_agenciShipper();
                Ext.Ajax.request({
                    //url:me.url2+'scm_scm_dispatcher_upd_destino/',
                    url:me.url2+'scm_scm_home_delivery_upd_destino/',
                    params:{
                        vp_srec_id:vp_srec_id,
                       // vp_origen:vp_origen,
                      //  vp_provincia:vp_provincia,
                      //  vp_id_age:vp_id_age,
                        vp_dir_id:vp_dir_id,
                        vp_ciu_id:vp_ciu_id,
                      //  vp_id_contacto:vp_id_contacto,
                       // vp_id_area:vp_id_area,
                        vp_id_geo:vp_id_geo,
                        vp_dir_calle:vp_dir_calle,
                        vp_dir_numvia:vp_dir_numvia,
                        vp_dir_refere:vp_dir_refere,
                        vp_dir_px:vp_dir_px,
                        vp_dir_py:vp_dir_py},
                    success:function(response,options){
                        var res = Ext.decode(response.responseText);
                        if (parseInt(res.data[0].error_sql)==1){
                            global.Msg({
                                msg:res.data[0].error_info,
                                icon:1,
                                buttosn:1,
                                fn:function(btn){
                                    Ext.getCmp(g_transporte.id+'-acoordion-origen').setCollapsed(false);  
                                }
                            });
                        }else{
                            global.Msg({
                                msg:res.data[0].error_info,
                                icon:0,
                                buttosn:1,
                                fn:function(btn){
                                }
                            });
                        }
                    }
                });
        } 
    },
    valida_coordenada:function(){
        var me = this    
        var v = Ext.getCmp(me.id+'-destino').getValues();
        //vp_dir_id= v[0].id_direccion;
        //vp_ciu_id= v[0].ciu_id;
        //vp_id_geo= v[0].id_puerta;
        //vp_dir_calle= v[0].direccion;
        //vp_dir_numvia= v[0].via;
        vp_dir_px = v[0].coordenadas[0].lat;
        vp_dir_py = v[0].coordenadas[0].lon;
        if (parseFloat(vp_dir_px)==-11.782413062516948 || parseFloat(vp_dir_py)==-76.79493715625 || parseFloat(vp_dir_px)==0 || parseFloat(vp_dir_py)==0){
            return false;
        }else{
            return true;
        }
    },
    setUnidad:function(id_unidad,id_agencia,d_dir_px,d_dir_py){
        var me = this;
        me.log.id_unidad = id_unidad;
        me.log.id_agencia = id_agencia
        me.log.d_dir_px = d_dir_px;
        me.log.d_dir_py = d_dir_py;
        //me.log
        /*me.log.age_x = age_x;
        me.log.age_y = age_y;*/

    },
    getUnidad:function(){
        var me = this;
        var value ={
            id_unidad : parseInt(me.log.id_unidad),
            id_agencia : parseInt(me.log.id_agencia),
            d_dir_px:parseFloat(me.log.d_dir_px),
            d_dir_py:parseFloat(me.log.d_dir_py)
            /*age_x : parseFloat(me.log.age_x),
            age_y : parseFloat(me.log.age_y)*/

        }
        return value;
    }
});





Ext.define('Ext.global.searchdirection2',{
    extend: 'Ext.panel.Panel',
    alias: 'widget.searchdirection2',
    layout: 'fit',
    defaults:{
        border: false
    },
    url: '/gestion/callcenter/',
    url2:'/gestion/gestiontrans/',
    task: null,
    map:null,
    flightPath:new google.maps.Polyline(),
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
    id_direccion:0,
    id_shipper:0,
    id_age:0,
    gui_srec_id:0,
    vp_vp_guia:0,
    gui_srec_id2:0,
    cnt_recol:0,
    tipo:null,
    record:null,
    directionsDisplay: null,
    directionsService :null,
    arrayMaker:[],
    arrayData:[],
    arrayData2:[],
    arrayDataa:[],
    unidades:[],
    log:{
        id_unidad:0,
        id_agencia:0
    },
    grid2:{
        id_age:0,
    },
    /**********serach 2***************/
    new_prog:{
            x:-11.782413062516948,
            y:-76.79493715625,
            logo:'',
            agencia:'',
            id_agencia:0,
            rbtn:3,
            id_contacto:0,
            id_area:0,
            id_contacto:0
    },
    rbtn:3,
    id_area:0,
    id_contacto:0,
    initComponent: function(){
        var me = this;
        this.items = [
                        {
                            xtype:'panel',
                            //cls:'x-accordion-hd',
                            layout: 'border',
                             tbar:[
                                    {
                                        xtype:'radiogroup',
                                        id:me.id+'-rbtn-group',
                                        columns:3,
                                        vertical:true,
                                        items:[
                                                {boxLabel:'Agencia Urbano',name:me.id+'-rbtn',inputValue:'1', width:110},
                                                {boxLabel:'Agencia Shipper',name:me.id+'-rbtn',inputValue:'2', width:110},
                                                {boxLabel:'Otra Dirección',name:me.id+'-rbtn',inputValue:'3', width:100,checked:true},
                                        ],
                                        listeners:{
                                            change:function(obj, newValue, oldValue, eOpts){
                                                var op = parseInt(newValue[me.id+'-rbtn']);
                                                me.destroyVars();
                                                me.rbtn = parseInt(op);
                                                me.new_prog.rbtn = parseInt(op);

                                                if (op == 1 || op == 2){
                                                    Ext.getCmp(me.id+'-direcciones').setHidden(true);
                                                    Ext.getCmp(me.id+'-agencias').setHidden(false);
                                                }else{
                                                    Ext.getCmp(me.id+'-direcciones').setHidden(false);
                                                    Ext.getCmp(me.id+'-agencias').setHidden(true);
                                                }   

                                                // me.get_agenciShipper();
                                                //console.log(me.id_shipper);
                                                if (op == 1){
                                                    me.get_agenciShipper(6);
                                                    Ext.getCmp(me.id+'-contacto').setVisible(false);
                                                    Ext.getCmp(me.id+'-area').setVisible(true);
                                                    me.new_prog.id_contacto=0;
                                                    me.id_contacto=0

                                                }else if (op == 2){
                                                    me.get_agenciShipper(me.id_shipper);
                                                    Ext.getCmp(me.id+'-contacto').setVisible(true);
                                                    Ext.getCmp(me.id+'-area').setVisible(false);
                                                    me.new_prog.id_area=0;
                                                    me.id_area=0
                                                }else if (op == 3){
                                                    //console.log('direcion');
                                                }
                                            },
                                            afterrender:function(){
                                              //Ext.getCmp(me.id+'-rbtn-group').setVisible(false);
                                             // console.log(me.id+'-rbtn-group');
                                            }
                                        }
                                    }
                            ],
                            defaults:{
                                border: false
                            },
                            items:[
                                    {
                                        region: 'west',
                                        id:me.id+'-direcciones',
                                        width: 450,
                                        columnWidth:1,
                                        layout:'column',
                                        defaults:{
                                            border: false,
                                            style:{
                                                margin: '2px'
                                            }
                                        },
                                        border: false,
                                        bodyStyle: 'background: #fff;',
                                        items:[
                                            {
                                                xtype: 'uePanel',
                                                padding:'0 20 0 0',
                                                title: 'Buscador de direcciones',
                                                legend: 'Ingresar Datos Solicitados',
                                                defaults:{
                                                    border: false
                                                },
                                                items:[
                                                    {
                                                        xtype: 'panel',
                                                        layout: 'column',
                                                        items:[
                                                            {
                                                                xtype: 'combo',
                                                                columnWidth:0.7,
                                                                margin:'0 2 0 0',
                                                                id: me.id + '-distrito',
                                                                fieldLabel: 'Distrito / Provincia / Departamento',
                                                                labelAlign: 'top',
                                                                store: Ext.create('Ext.data.Store',{
                                                                    fields: [
                                                                        {name: 'ciudad', type: 'string'},
                                                                        {name: 'ciu_id', type: 'int'},
                                                                        {name: 'ciu_px', type: 'float'},
                                                                        {name: 'ciu_py', type: 'float'},
                                                                        {name: 'mapa', type: 'int'}
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
                                                                        Ext.getCmp(me.id + '-tipo_via').enable();
                                                                        Ext.getCmp(me.id + '-nombre_via').focus(true, 500);
                                                                    }
                                                                }
                                                            },
                                                            {
                                                                xtype: 'combo',
                                                                columnWidth:0.3,
                                                                id: me.id + '-tipo_via',
                                                                fieldLabel: 'Tipo de vía',
                                                                labelAlign: 'top',
                                                                disabled: true,
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
                                                                        }
                                                                    }
                                                                }),
                                                                queryMode: 'local',
                                                                triggerAction: 'all',
                                                                valueField: 'id_elemento',
                                                                displayField: 'descripcion',
                                                                emptyText: '[ Seleccione ]',
                                                                enableKeyEvents: true,
                                                                listeners:{
                                                                    afterrender: function(obj){
                                                                       /* obj.getStore().load({
                                                                            params:{
                                                                                vp_tab_id: 'VIA',
                                                                                vp_shipper: 0
                                                                            },
                                                                            callback: function(){
                                                                                me.tip_via = 0;
                                                                                obj.setValue(me.tip_via);
                                                                            }
                                                                        });*/
                                                                    var user='';
                                                                    var key ='';
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
                                                                        me.id_via = '';
                                                                        me.id_urb = '';
                                                                        Ext.getCmp(me.id + '-nombre_via').setValue('');
                                                                        Ext.getCmp(me.id + '-urbanizacion').setValue('');
                                                                        Ext.getCmp(me.id + '-via').setValue('');
                                                                        Ext.getCmp(me.id + '-lote').setValue('');
                                                                        Ext.getCmp(me.id + '-manzana').setValue('');
                                                                        me.id_mza ='';
                                                                        me.id_puerta='';
                                                                        Ext.getCmp(me.id + '-nombre_via').focus(true, 500);
                                                                    }
                                                                }
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype: 'panel',
                                                        layout: 'column',
                                                        items:[
                                                            
                                                        ]
                                                    },
                                                    {
                                                        xtype: 'panel',
                                                        columnWidth:1,
                                                        id: me.id + '-panel-combo-via',
                                                        layout: 'column',
                                                        listeners:{
                                                            scope: this,
                                                            afterrender: function(obj){
                                                                this.getComboCalle();
                                                            }
                                                        }
                                                    },
                                                    {
                                                        xtype: 'panel',
                                                        columnWidth:1,
                                                        id: me.id + '-panel-combo-urbanizacion',
                                                        layout: 'column',
                                                        listeners:{
                                                            scope: this,
                                                            afterrender: function(obj){
                                                                this.getComboUrbanizacion();
                                                            }
                                                        }
                                                    },
                                                    {
                                                        xtype: 'panel',
                                                        columnWidth:1,
                                                        //bodyStyle:{"background-color":"red"} 
                                                        height:'100%',
                                                        id: me.id + '-panel-combo-via_manz_lote',
                                                        layout: 'column',
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
                                                        },
                                                    },
                                                    {
                                                        xtype:'panel',
                                                        columnWidth:1,
                                                        items:[
                                                                {
                                                                    xtype: 'textarea',
                                                                    id:me.id+'-referencia-r',
                                                                    margin:2,
                                                                    labelWidth:62,
                                                                    //flex: 1,
                                                                    labelAlign: 'top',
                                                                    fieldLabel: 'Referencia',
                                                                    width: '98%',
                                                                    height:80,
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
                                        ],
                                        listeners:{
                                            afterrender: function(obj){
                                                me.setMap({zoon: 4, lat: me.lat, lon: me.lon});
                                                Ext.getCmp(me.id + '-distrito').focus(true, 500);
                                            }
                                        }
                                    },
                                    {
                                        region:'center',
                                        id:me.id+'-agencias',
                                        columnWidth:1,
                                        width:400,
                                        layout:'column',
                                        border:false,
                                        bbar:[
                                     
                                                    {
                                                        xtype:'combo',
                                                        id:me.id+'-contacto',
                                                        labelWidth:50,
                                                        hidden:true,
                                                        fieldLabel:'Contacto',
                                                        flex:1,
                                                        store:Ext.create('Ext.data.Store',{
                                                            fields:[
                                                                {name:'id_contacto',type:'int'},
                                                                {name:'contacto', type:'string'}
                                                            ],
                                                            proxy:{
                                                                type:'ajax',
                                                                url:me.url2+'scm_scm_ss_lista_contactos/',
                                                                reader:{
                                                                    type:'json',
                                                                    rootProperty:'data'
                                                                }
                                                            }
                                                        }),
                                                        queryMode:'local',
                                                        valueField:'id_contacto',
                                                        displayField:'contacto',
                                                        listConfig:{
                                                            minWidth:350
                                                        },
                                                        width:350,
                                                        forceSelection:true,
                                                        allowBlank:false,
                                                        selecOnFocus:true,
                                                        emptyText:'[ Seleccione ]',
                                                        listeners:{
                                                            afterrender:function(obj,record,options){
                                                            },
                                                            select:function(obj,record,options){
                                                                me.id_contacto = record.get('id_contacto');
                                                                me.new_prog.id_contacto = record.get('id_contacto');
                                                            }
                                                        }
                                                    },
                                                    {
                                                        xtype:'combo',
                                                        hidden:true,
                                                        id:me.id+'-area',
                                                        fieldLabel:'Area',
                                                        flex:1,
                                                        labelWidth:40,
                                                        store:Ext.create('Ext.data.Store',{
                                                            fields:[
                                                                    {name:'id_area',type:'int'},
                                                                    {name:'area_nombre',type:'string'}
                                                            ],
                                                            proxy:{
                                                                type:'ajax',
                                                                url:me.url2+'scm_scm_gestion_personal_areas/',
                                                                reader:{
                                                                    type:'json',
                                                                    rootProperty:'data'
                                                                }
                                                            }
                                                        }),
                                                        queryMode:'local',
                                                        valueField:'id_area',
                                                        displayField:'area_nombre',
                                                        listConfig:{
                                                            minWidth:350
                                                        },
                                                        width:350,
                                                        forceSelection:true,
                                                        allowBlank:false,
                                                        selecOnFocus:true,
                                                        emptyText:'[ Seleccione ]',
                                                        listeners:{
                                                            afterrender:function(obj,record,options){
                                                                obj.getStore().load({
                                                                    params:{}
                                                                });
                                                            },
                                                            select:function(obj,record,opts){
                                                                me.id_area = record.get('id_area');
                                                                me.new_prog.id_area = record.get('id_area');

                                                            }
                                                        }
                                                    }
                                            
                                                
                                        ],
                                        items:[
                                                {
                                                    xtype:'grid',
                                                    margin:'5 0 0 0',
                                                    columnWidth:1,
                                                    store:Ext.create('Ext.data.Store',{
                                                        model:'grid_agencias',
                                                        proxy:{
                                                            type:'ajax',
                                                            url:me.url2+'scm_scm_home_delivery_agencia_shipper/',
                                                            reader:{
                                                                type:'json',
                                                                root:'data'
                                                            }
                                                        }
                                                    }),
                                                    id:me.id+'-grid-agencias-longitud',
                                                    columnsLines:true,
                                                    height:'50%',
                                                    columns:{
                                                        items:[
                                                                {
                                                                    text:'Agencia',
                                                                    flex:2.4,
                                                                    dataIndex:'agencia'
                                                                },
                                                               /* {
                                                                    text:'Age.Dist.',
                                                                    flex:1,
                                                                    dataIndex:'gps_dist_t'
                                                                },*/
                                                                {
                                                                    text:'Tiempo',
                                                                    flex:1,
                                                                    //align:'center',
                                                                    dataIndex:'dur_text'
                                                                },
                                                                {
                                                                    text:'Und.Cercana',
                                                                    flex:1,
                                                                    dataIndex:'und_placa'
                                                                },
                                                                {
                                                                    text:'T. Aprox',
                                                                    flex:1,
                                                                    dataIndex:'gps_time_t'
                                                                },
                                                                {
                                                                    text:'T. Total',
                                                                    flex:1,
                                                                    //align:'center',
                                                                    dataIndex:'time_t'
                                                                }
                                                              /*  {
                                                                    text:'Unidad',
                                                                    flex:1,
                                                                    dataIndex:'adurationtext'
                                                                },
                                                                {
                                                                    text:'Angecia',
                                                                    flex:1,
                                                                    dataIndex:'duration',
                                                                },
                                                                {
                                                                    text:'Distancia',
                                                                    flex:1,
                                                                    dataIndex:'distance'
                                                                },
                                                                {
                                                                    text:'T. Total',
                                                                    flex:1,
                                                                    dataIndex:'total',
                                                                },
                                                                {
                                                                    text:'Placa',
                                                                    flex:1,
                                                                    dataIndex:'placa'
                                                                }*/
                                                                /*{
                                                                    text:'id_unidad',
                                                                    flex:1,
                                                                    dataIndex:'id_unidad'
                                                                },
                                                                {
                                                                    text:'und_px',
                                                                    flex:1,
                                                                    dataIndex:'und_px'
                                                                },
                                                                {
                                                                    text:'und_py',
                                                                    flex:1,
                                                                    dataIndex:'und_py'
                                                                }*/

                                                                /*
                                                                {
                                                                    text:'o_dir_py',
                                                                    dataIndex:'o_dir_py'
                                                                },
                                                                {
                                                                    text:'d_dir_px',
                                                                    dataIndex:'d_dir_px'
                                                                },
                                                                {
                                                                    text:'d_dir_px',
                                                                    dataIndex:'d_dir_py'
                                                                }*/
                                                        ]
                                                    },
                                                    listeners:{
                                                        beforeselect:function(obj, record, index, eOpts ){
                                                            me.record = record;
                                                            console.log(record);
                                                            me.clear_maps('p');
                                                            me.pinta_agencia_select();
                                                           // console.log(record);
                                                            me.setCoordenadas(parseFloat(record.get('dir_px')), parseFloat(record.get('dir_py')));
                                                            me.setNew_prog(parseFloat(record.get('dir_px')),parseFloat(record.get('dir_py')),'/images/icon/'+record.get('shi_logo'),record.get('agencia'),record.get('id_agencia'));

                                                            /******************************/
                                                            //console.log(me.rbtn);
                                                            if (me.rbtn == 2){
                                                                var cbo = Ext.getCmp(me.id+'-contacto');
                                                                cbo.getStore().removeAll();
                                                                cbo.getStore().load({
                                                                    params:{vp_id_agencia:parseInt(record.get('id_agencia')),vp_shi_codigo:me.id_shipper},
                                                                });
                                                            }
                                                                
                                                            /******************************/
                                                      
                                                            if (me.tipo=='O'){
                                                                var d = Ext.getCmp(gestiontrans.id+'-destino').getNew_prog();
                                                                var d_dir_px = d.x;
                                                                var d_dir_py = d.y;
                                                                var logo = d.logo;
                                                                var agencia = d.agencia;
                                                                var rbtn = parseInt(d.rbtn);

                                                                var o_dir_px = parseFloat(record.get('dir_px'));
                                                                var o_dir_py = parseFloat(record.get('dir_py'));
                                                               //console.log(rbtn);
                                                                if (rbtn == 3){
                                                                    d = Ext.getCmp(gestiontrans.id+'-destino').getCoordenadas();
                                                                    d_dir_px = d.latitud;
                                                                    d_dir_py = d.longitud;
                                                                    me.pinta_unidad_actual(d_dir_px,d_dir_py,'/images/icon/marker1.png','Punto de Origen');
                                                                }else{
                                                                    me.pinta_unidad_actual(d_dir_px,d_dir_py,logo,agencia);
                                                                }
                                                                me.pinta_unidad_actual(o_dir_px,o_dir_py,'/images/icon/'+record.get('shi_logo'),record.get('agencia'));
                                                                me.google_ruta(o_dir_px,o_dir_py,d_dir_px,d_dir_py);

                                                            }else if (me.tipo='D'){
                                                                var o = Ext.getCmp(gestiontrans.id+'-origen').getNew_prog();
                                                                var o_dir_px = o.x;
                                                                var o_dir_py = o.y;
                                                                var logo = o.logo;
                                                                var agencia = o.agencia;
                                                                var rbtn = parseInt(o.rbtn);

                                                                var d_dir_px = parseFloat(record.get('dir_px'));
                                                                var d_dir_py = parseFloat(record.get('dir_py'));
                                                                //console.log(rbtn);
                                                                if (rbtn == 3){
                                                                    o = Ext.getCmp(gestiontrans.id+'-origen').getCoordenadas();
                                                                    o_dir_px = o.latitud;
                                                                    o_dir_py = o.longitud;
                                                                    me.pinta_unidad_actual(o_dir_px,o_dir_py,'/images/icon/marker1.png','Punto de Entrega');
                                                                }else{
                                                                    me.pinta_unidad_actual(o_dir_px,o_dir_py,logo,agencia);
                                                                }
                                                                me.pinta_unidad_actual(d_dir_px,d_dir_py,'/images/icon/'+record.get('shi_logo'),record.get('agencia'));
                                                                me.google_ruta(o_dir_px,o_dir_py,d_dir_px,d_dir_py);
                                                            }
                                                          
                                                            
                                                        }
                                                    }
                                                }
                                        ]       
                                    }
                            ]
                        }
                            
        ]
        

        this.callParent();
    },
    setNew_prog:function(x_,y_,logo,agencia,id_agencia){
        var me = this;
        me.new_prog={
                x:x_,
                y:y_,
                logo:logo,
                agencia:agencia,
                id_agencia:id_agencia,
                rbtn:me.rbtn,
                id_area:me.id_area,
                id_contacto:me.id_contacto
        };
    },
    getNew_prog:function(){
        var me = this;
        return me.new_prog;
    },
    destroyVars:function(){
        var me = this;
        me.new_prog={
            x:-11.782413062516948,
            y:-76.79493715625,
            logo:'',
            agencia:'',
            id_agencia:0,
            id_area:0,
            id_contacto:0
           // rbtn:0
        };
        me.reset();
        me.reset_global_vars();
    },
    direccion_select:function(x_,y_){
        var me = this;
        me.clear_maps('p');
        if (me.tipo=='O'){
            var d = Ext.getCmp(gestiontrans.id+'-destino').getNew_prog();
            var d_dir_px = d.x;
            var d_dir_py = d.y;
            var logo = d.logo;
            var agencia = d.agencia;
            var rbtn = d.rbtn;

            var o_dir_px = parseFloat(x_);
            var o_dir_py = parseFloat(y_);
            //console.log(rbtn);
            if (parseInt(rbtn) == 3){
                d = Ext.getCmp(gestiontrans.id+'-destino').getCoordenadas();
                d_dir_px = d.latitud;
                d_dir_py = d.longitud;
                me.pinta_unidad_actual(d_dir_px,d_dir_py,'/images/icon/marker1.png','Punto de Entrega');
            }else{
                me.pinta_unidad_actual(d_dir_px,d_dir_py,logo,agencia);
            }
            me.pinta_unidad_actual(o_dir_px,o_dir_py,'/images/icon/map-marker-24.png','Punto de Origen');
            me.google_ruta(o_dir_px,o_dir_py,d_dir_px,d_dir_py);

        }else if (me.tipo='D'){
            var o = Ext.getCmp(gestiontrans.id+'-origen').getNew_prog();
            var o_dir_px = o.x;
            var o_dir_py = o.y;
            var logo = o.logo;
            var agencia = o.agencia;
            var rbtn = o.rbtn;

            var d_dir_px = parseFloat(x_);
            var d_dir_py = parseFloat(y_);
            //console.log(rbtn);
            if (parseInt(rbtn) == 3){
                o = Ext.getCmp(gestiontrans.id+'-origen').getCoordenadas();
                o_dir_px = o.latitud;
                o_dir_py = o.longitud;
                me.pinta_unidad_actual(o_dir_px,o_dir_py,'/images/icon/map-marker-24.png','Punto de Origen');
            }else{
                me.pinta_unidad_actual(o_dir_px,o_dir_py,logo,agencia);
            }
            me.pinta_unidad_actual(d_dir_px,d_dir_py,'/images/icon/marker1.png','Punto de Entrega');
            me.google_ruta(o_dir_px,o_dir_py,d_dir_px,d_dir_py);
        }
    },
    record_grid2:function(record){
        var  me = this;
        me.grid2.id_age = record.get('id_agencia');
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
        me.map = gestiontrans.map;
        me.marker = gestiontrans.varsmapa.marker;
        me.directionsDisplay = gestiontrans.varsmapa.directionsDisplay;
        me.directionsService = gestiontrans.varsmapa.directionsService;
        //console.log(document.getElementById(gestiontrans.id+'-map'));
        /*var rendererOptions = {
             // draggable: false,
              suppressMarkers: true
        };
        me.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
        me.directionsService = new google.maps.DirectionsService();
        var mapOptions = {
            zoom: p.zoon,
            center: new google.maps.LatLng(p.lat, p.lon)
        };
        me.map = new google.maps.Map(document.getElementById(me.apimapa), mapOptions);
        me.directionsDisplay.setMap(me.map);

        var peru = new google.maps.LatLng(-11.782413062516948, -76.79493715625);

        var homeControlDiv = document.createElement('div');
        var homeControl = new HomeControl(homeControlDiv, me.map, peru);
        homeControlDiv.index = 1;
        me.map.controls[google.maps.ControlPosition.TOP_CENTER].push(homeControlDiv);*/
    },
    setzoon:function(zoom){
        var me =this;
        me.map.setZoom(zoom);
        var centro = new google.maps.LatLng(me.lat, me.lon);
        me.map.setCenter(centro);
    },
    getComboCalle: function(){
        var me = this;
        var p = Ext.getCmp(me.id + '-panel-combo-via');
        p.removeAll();
        //console.log(me.ciu_id);
        var state = me.ciu_id == 0 ? true : false;
        //var ciu_id =Ext.getCmp(me.id + '-distrito').getValue();
       // if (me.mapa == 1){
        //console.log(ciu_id);
       if (state == true || me.mapa == 1){     
            p.add({
                xtype: 'combo',
                columnWidth:1,
                id: me.id + '-nombre_via',
                fieldLabel: 'Nombre de calle / vía',
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
                            console.log(store.getProxy().getReader().rawData.debug.sql);
                        },
                        beforeload: function(store, operation, opts){
                            var proxy = store.getProxy();
                            proxy.setExtraParam('vp_ciu_id',{
                                vp_ciu_id: me.ciu_id
                            });
                            proxy.setExtraParam('vp_tipvia',{
                                vp_tipvia: me.tip_via
                            });
                        }
                    }
                }),
                typeAhead: false,
                hideTrigger: true,
                valueField: 'id_via',
                displayField: 'nombre_via',
                emptyText: '[ search ]',
                minChars: 3,
                queryParam: 'vp_nomvia',
                enableKeyEvents: true,
                caseSensitive: true,
                autoSelect: false,
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
                       /* me.isVia = true;
                        me.id_via = records.get('id_via');
                        Ext.getCmp(me.id + '-via').clearValue();
                        Ext.getCmp(me.id + '-manzana').clearValue();
                        Ext.getCmp(me.id + '-lote').clearValue();
                        me.getSegmentos();
                        me.getComboUrbanizacion();*/
                        
                        me.isVia = true;
                        Ext.getCmp(me.id + '-tipo_via').setValue(records.get('id_tipo_via'));
                        Ext.getCmp(me.id + '-distrito').setRawValue(records.get('distrito'));
                        Ext.getCmp(me.id + '-nombre_via').setRawValue(records.get('nombre_calle'));
                        me.ciu_id=records.get('ciu_id');
                        me.mapa = parseInt(records.get('mapa'));
                        me.id_via = records.get('id_via');


                        if(records.get('ciu_px')!=""){
                            me.setMap({zoon: 16, lat: records.get('ciu_px'), lon: records.get('ciu_py')});
                            me.flightPath.setMap(me.map);
                        }

                        Ext.getCmp(me.id + '-via').setValue('');
                        Ext.getCmp(me.id + '-manzana').setValue('');
                        Ext.getCmp(me.id + '-lote').setValue('');
                        me.id_mza ='';
                        me.id_puerta='';

                        me.getSegmentos();
                        me.getComboUrbanizacion();
                        me.getCombo_via_manz_lote();
                        Ext.getCmp(me.id + '-tipo_via').enable();
                        Ext.getCmp(me.id + '-nombre_via').focus(true, 500);
                    },
                    blur: function(obj, event, opts){
                        var val = Ext.util.Format.trim(obj.getRawValue());
                        if (val == ''){
                            me.isVia = false;
                            me.id_via = 0;
                            me.flightPath.setMap(null);
                            me.marker.setMap(null);
                            me.getComboUrbanizacion();
                        }
                    }
                }
            });
        }else{
            p.add({
                xtype: 'textfield',
                columnWidth:1,
                id: me.id + '-nombre_via',
                fieldLabel: 'Nombre de calle / vía',
                labelAlign: 'top',
                disabled: state,
                listeners:{
                    scope: this,
                    blur: function(obj, e, opts){
                        me.geoLocalizar();
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
                    columnWidth:1,
                    id: me.id + '-urbanizacion',
                    fieldLabel: 'Urbanización / AAHH / Barrio (Grupo vía)',
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
                                console.log(store.getProxy().getReader().rawData.debug.sql);
                            }
                        }
                    }),
                    queryMode: 'local',
                    valueField: 'id_grupo_viv',
                    displayField: 'nombre',
                    emptyText: '[ search ]',
                    listeners:{
                        afterrender: function(obj){
                            /*obj.getStore().load({
                                params:{
                                    vp_id_via: me.id_via,
                                    vp_ciu_id: me.ciu_id
                                },
                                callback: function(){
                                    obj.focus(true, 500);
                                }
                            });*/
                            var user='';
                            var key ='';
                            obj.getStore().load({
                                params:{
                                    vp_id_via: me.id_via,
                                    vp_ciu_id: me.ciu_id,
                                    user:user,
                                    key:key
                                },
                                callback: function(){
                                    obj.focus(true, 500);
                                }
                            });
                        },
                        select: function(obj, records, opts){
                            /*var rec = records;
                            me.id_urb = rec.get('id_grupo_viv');
                            me.setCoordenadas(records.get('punto_x'), records.get('punto_y'));
                            Ext.getCmp(me.id + '-manzana').clearValue();
                            Ext.getCmp(me.id + '-manzana').getStore().load({
                                params:{
                                    vp_ciu_id: me.ciu_id,
                                    vp_id_urb: me.id_urb,
                                    vp_id_via: me.id_via
                                },
                                callback: function(){
                                    me.direccion_select(records.get('punto_x'),records.get('punto_y'));
                                }
                            });
                            me.flightPath.setMap(null);
                            me.marker.setMap(null);
                            Ext.getCmp(me.id + '-via').focus(true, 500);*/
                            var rec = records;
                            me.id_urb = rec.get('id_grupo_viv');
                            Ext.getCmp(me.id + '-via').setValue('');
                            Ext.getCmp(me.id + '-lote').setValue('');
                            Ext.getCmp(me.id + '-manzana').setValue('');
                            me.id_mza ='';
                            me.id_puerta='';

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
                                     me.direccion_select(records.get('punto_x'),records.get('punto_y'));
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

                            Ext.getCmp(me.id + '-via').focus(true, 500);
                        }
                    }
                });
            }else{
                p.add({
                    xtype: 'combo',
                    id: me.id + '-urbanizacion',
                    columnWidth:1,
                    fieldLabel: 'Urbanización / AAHH / Barrio (Grupo vía)',
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
                                console.log(store.getProxy().getReader().rawData.debug.sql);
                            },
                            beforeload: function(store, operation, opts){
                                var proxy = store.getProxy();
                                proxy.setExtraParam('vp_ciu_id',{
                                    vp_ciu_id: me.ciu_id
                                });
                            }
                        }
                    }),
                    typeAhead: false,
                    hideTrigger: true,
                    valueField: 'id_grupo',
                    displayField: 'nombre_grupo',
                    emptyText: '[ search ]',
                    queryParam: 'vp_nombre',
                    minChars: 3,
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
                           /* var rec = records;
                            me.id_urb = rec.get('id_grupo');
                            me.setCoordenadas(records.get('grupo_px'), records.get('grupo_py'));

                            me.flightPath.setMap(null);
                            me.marker.setMap(null);

                            me.setMap({zoon: 16, lat: me.lat, lon: me.lon});

                            Ext.getCmp(me.id + '-manzana').clearValue();
                            Ext.getCmp(me.id + '-manzana').getStore().load({
                                params:{
                                    vp_ciu_id: me.ciu_id,
                                    vp_id_urb: me.id_urb,
                                    vp_id_via: me.id_via
                                },
                                callback: function(){
                                    me.direccion_select(records.get('grupo_px'),records.get('grupo_py'));
                                }
                            });*/
                            var rec = records;
                            me.id_urb = rec.get('id_grupo');
                            Ext.getCmp(me.id + '-via').setValue('');
                            Ext.getCmp(me.id + '-lote').setValue('');
                            Ext.getCmp(me.id + '-manzana').setValue('');
                            me.id_mza ='';
                            me.id_puerta='';

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

                                }
                            });
                            //console.log(records.get('grupo_px'));
                            var myLatlng = new google.maps.LatLng(records.get('grupo_px'),records.get('grupo_py'));
                            me.marker = new google.maps.Marker({
                                position: myLatlng,
                                map: me.map,
                                title: ''
                            });
                          
                        }
                        

                    }
                });
            }
        }else{
            p.add({
                xtype: 'textfield',
                columnWidth:1,
                id: me.id + '-urbanizacion',
                fieldLabel: 'Urbanización / AAHH / Barrio (Grupo vía)',
                labelAlign: 'top',
                disabled: state
            });
        }
        
        p.doLayout();
    },
    getSegmentos: function(){
        var me = this;
        var user='';
        var key ='';
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
                    strokeWeight: 2
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
                columnWidth:0.25,
                id: me.id + '-via',
                fieldLabel: 'Nº Vía',
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
                            console.log(store.getProxy().getReader().rawData.debug.sql);
                        },
                        beforeload: function(store, operation, opts){
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

                        Ext.getCmp(me.id + '-manzana').focus(true, 500);
                    }
                }
            },
            {
                xtype: 'combo',
                columnWidth:0.25,
                id: me.id + '-manzana',
                fieldLabel: 'Manzana',
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
                            console.log(store.getProxy().getReader().rawData.debug.sql);
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
                        /*me.id_mza = records.get('id_mza');
                        me.setCoordenadas(records.get('punto_x'), records.get('punto_y'));
                        me.setMap({zoon: 16, lat: records.get('punto_x'), lon: records.get('punto_y')});
                        me.flightPath.setMap(null);
                        var myLatlng = new google.maps.LatLng(records.get('punto_x'),records.get('punto_y'));
                        var marker = new google.maps.Marker({
                            position: myLatlng,
                            map: me.map,
                            title: ''
                        });

                        Ext.getCmp(me.id + '-lote').clearValue();
                        Ext.getCmp(me.id + '-lote').getStore().load({
                            params:{
                                vp_ciu_id: me.ciu_id,
                                vp_id_mza: me.id_mza
                            },
                            callback: function(){
                                Ext.getCmp(me.id + '-lote').focus(true, 500);
                            }
                        });*/
                        me.id_mza = records.get('id_mza');
                        Ext.getCmp(me.id + '-via').setValue('');
                        Ext.getCmp(me.id + '-lote').setValue('');
                        me.id_puerta='';

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
                                Ext.getCmp(me.id + '-lote').focus(true, 500);
                            }
                        });
                    }
                }
            },
            {
                xtype: 'combo',
                columnWidth:0.25,
                id: me.id + '-lote',
                fieldLabel: 'Lote',
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
                            console.log(store.getProxy().getReader().rawData.debug.sql);
                        }
                    }
                }),
                queryMode: 'local',
                triggerAction: 'all',
                valueField: 'id_puerta',
                displayField: 'lote',
                emptyText: '[seleccione]',
                listeners:{
                    afterrender: function(obj, e){

                    },
                    select: function(obj, records, opts){
                        /*me.id_puerta = records.get('id_puerta');
                        me.setCoordenadas(records.get('punto_x'), records.get('punto_y'));
                        me.setMap({zoon: 16, lat: records.get('punto_x'), lon: records.get('punto_y')});
                        me.flightPath.setMap(null);
                        var myLatlng = new google.maps.LatLng(records.get('punto_x'),records.get('punto_y'));
                        var marker = new google.maps.Marker({
                            position: myLatlng,
                            map: me.map,
                            title: ''
                        });*/
                        me.id_puerta = records.get('id_puerta');
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
                    }
                }
            },
            {
                xtype: 'textfield',
                id: me.id + '-nro-interno',
                fieldLabel: 'Nº Interno',
                columnWidth:0.25,
                disabled: state,
                labelAlign: 'top'
            });
        }else{
            p.add({
                xtype: 'textfield',
                columnWidth:0.25,
                id: me.id + '-via',
                fieldLabel: 'Nº Vía',
                disabled: state,
                labelAlign: 'top'
            },{
                xtype: 'textfield',
                columnWidth:0.25,
                id: me.id + '-manzana',
                fieldLabel: 'Manzana',
                disabled: state,
                labelAlign: 'top'
            },{
                xtype: 'textfield',
                columnWidth:0.25,
                id: me.id + '-lote',
                fieldLabel: 'Lote',
                disabled: state,
                labelAlign: 'top'
            },{
                xtype: 'textfield',
                id: me.id + '-nro-interno',
                fieldLabel: 'Nº Interno',
                columnWidth:0.25,
                disabled: state,
                labelAlign: 'top'
            });
        }
        
        p.doLayout();
    },
    geoLocalizar: function(){
        var me = this;
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
                google.maps.event.addListener(me.marker, 'dragend', function(){
                   // console.log(marker.getPosition().lat());
                   // console.log(marker.getPosition().lng());
                    me.setCoordenadas(me.marker.getPosition().lat(),me.marker.getPosition().lng());
                });
            } else {
               // alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    },
    reset: function(){
        var me = this;

        Ext.getCmp(me.id + '-distrito').clearValue();

        Ext.getCmp(me.id + '-tipo_via').clearValue();
        Ext.getCmp(me.id + '-tipo_via').setValue(0);
        me.getComboCalle();
        me.getComboUrbanizacion();
        me.getCombo_via_manz_lote();

        Ext.getCmp(me.id + '-distrito').focus(true, 1000);
    },
    reset_maps: function(){
        var me = this;
        //gestiontrans.setMap();//
        me.lat = -11.782413062516948;
        me.lon = -76.79493715625;
        me.setMap({zoon: 4, lat: me.lat, lon: me.lon});
        me.flightPath = new google.maps.Polyline();
        me.flightPath.setMap(null);
        me.marker = new google.maps.Marker();
        me.marker.setMap(null);
        Ext.getCmp(me.id+'-referencia-r').setValue('');
    },
    clear_maps:function(){
        var me = this;
        gestiontrans.setMap();
        me.setMap('p')
    },
    reset_global_vars: function(){
        var me = this;
        /**
         * Setting global vars
         */
        me.mapa = 0;
        me.ciu_id = null;
        me.tip_via = 0;
        me.id_via = 0;
        me.id_urb = 0;
        me.id_puerta = null;
        me.id_mza = null;
        me.segmentos = [];
        me.isVia = false;
        me.geocoder = null;
        me.reset_maps();
        Ext.getCmp(me.id+'-referencia-r').setValue('');
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
    getValues: function(){
        /**
         * Returning all values of maps
         */
        var me = this;
        var lote = Ext.getCmp(me.id + '-lote').getRawValue();
        var manzana = Ext.getCmp(me.id + '-manzana').getRawValue();
        var via = Ext.getCmp(me.id + '-via').getRawValue();
        var urb = Ext.getCmp(me.id + '-urbanizacion').getRawValue();
        var nom_via = Ext.getCmp(me.id + '-nombre_via').getRawValue();
        var tipo_via = Ext.getCmp(me.id + '-tipo_via').getRawValue();
        var distrito = Ext.getCmp(me.id + '-distrito').getRawValue();
        var direccion = tipo_via+' '+nom_via+' Urb:'+urb+' VIA:'+via+' MZ:'+manzana+' LT:'+lote;
        var referencia = Ext.getCmp(me.id+'-referencia-r').getValue();
        var nro_interno= Ext.getCmp(me.id + '-nro-interno').getValue();
        
        var new_prog = me.getNew_prog();
        var isValido = false;
        var msg='';
        if(new_prog.rbtn == 1){
            if (new_prog.id_agencia > 0){//prov_codigo
                if (new_prog.id_area>0){
                    isValido = true;
                }else{
                    isValido = false;
                    msg = 'Debe Seleccionar el Area';
                }
            }else{
                isValido = false;
                msg = 'Debe Seleccionar una Agencia de Urbano';
            }
        }else if(new_prog.rbtn == 2){
            if (new_prog.id_agencia > 0){//agencia
                if (new_prog.id_contacto > 0){
                    isValido = true;
                }else{
                    isValido = false;
                    msg = 'Debe Seleccionar el Contacto';
                }
            }else{
                isValido = false;
                msg = 'Debe Seleccionar una Agencia del Cliente';
            }
        }else if (new_prog.rbtn == 3){//direccion
            if (distrito != '' && nom_via !=''){
                isValido = true;
            }else{
                isValido = false;
                msg = 'Distrito o Nombre de la Calle estan Vacios';
            }
        }

                var params = [
                    {
                        ciu_id: me.ciu_id,
                        tip_via: me.tip_via,
                        id_via: me.id_via,
                        id_urb: me.id_urb,
                        id_puerta: me.id_puerta == '' ? 0:me.id_puerta,
                        id_mza: me.id_mza == '' ? 0:me.id_mza,
                        tipo_via:tipo_via,
                        nom_via:nom_via,
                        urb:urb,
                        via:via,
                        lote:lote,
                        manzana:manzana,
                        //id_direccion:parseInt(me.id_direccion),
                        coordenadas: [
                            {
                                lat: me.lat,
                                lon: me.lon
                            }
                        ],
                        direccion:direccion,
                        referencia:referencia,
                        new_prog:new_prog,
                        isValido:isValido,
                        msg:msg,
                        nro_interno:nro_interno
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
        var user='';
        var key ='';
        Ext.getCmp(me.id + '-tipo_via').enable();
        Ext.getCmp(me.id + '-distrito').setValue('');
        Ext.getCmp(me.id + '-distrito').clearValue();
        me.ciu_id=params.ciu_id;
       
        Ext.getCmp(me.id + '-distrito').getStore().load({
            params:{
                 //vp_nomdis: params.nombre_ubi,
                 vp_nomdis: params.nombre_ubi,user:user,key:key
             },
             callback:function(){
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

        me.setCoordenadas(params.dir_px,params.dir_py);
        params.via_nombre=params.via_nombre.replace(/\s/g,'');
        //console.log(params.via_nombre);

        Ext.getCmp(me.id + '-tipo_via').enable();
        Ext.getCmp(me.id + '-tipo_via').setValue(parseInt(params.tvia_id));

        me.id_puerta = params.id_geo;
        me.ciu_id=params.ciu_id;
        me.tip_via=params.tvia_id;
        me.id_urb=params.id_urb;
        me.id_via=params.id_via;
        me.mapa=1;
        me.isVia = true;
        me.getComboCalle();
        me.getComboUrbanizacion();
        me.getCombo_via_manz_lote();
        
        Ext.getCmp(me.id + '-distrito').setValue('');
        Ext.getCmp(me.id + '-distrito').clearValue();
        Ext.getCmp(me.id + '-distrito').getStore().load({
            params:{
                vp_nomdis: params.nombre_ubi,
            },
            callback: function(){
                Ext.getCmp(me.id + '-distrito').setValue(parseInt(params.ciu_id));
                Ext.getCmp(me.id + '-nombre_via').enable();
                Ext.getCmp(me.id + '-nombre_via').clearValue();
                Ext.getCmp(me.id + '-nombre_via').getStore().load({
                    params:{
                        vp_ciu_id: params.ciu_id,
                        vp_tipvia: params.tvia_id,
                        vp_nomvia: params.via_nombre
                    },
                    callback: function(){
                        Ext.getCmp(me.id + '-nombre_via').setValue(params.id_via);
                        Ext.getCmp(me.id + '-urbanizacion').enable();
                        Ext.getCmp(me.id + '-urbanizacion').clearValue();
                        Ext.getCmp(me.id + '-urbanizacion').getStore().load({
                            params:{
                                vp_ciu_id: params.ciu_id,
                                vp_id_via: params.id_via
                            },
                            callback: function(){
                                Ext.getCmp(me.id + '-urbanizacion').setValue(parseInt(params.id_urb));

                                Ext.getCmp(me.id + '-via').enable();
                                Ext.getCmp(me.id + '-via').clearValue();
                                params.pue_numero = (params.pue_numero==0 || params.pue_numero==null)?'':params.pue_numero;
                                if(params.pue_numero!=''){
                                    Ext.getCmp(me.id + '-via').getStore().load({
                                        params:{
                                            vp_ciu_id: params.ciu_id,
                                            vp_id_via: params.id_via,
                                            vp_id_urb: params.id_urb,
                                            vp_numero: params.pue_numero
                                        },
                                        callback: function(){
                                            Ext.getCmp(me.id + '-via').setValue(parseInt(params.id_geo));
                                        }
                                    });
                                }
                                Ext.getCmp(me.id + '-manzana').clearValue();
                                Ext.getCmp(me.id + '-manzana').enable();
                                params.id_man = (params.id_man==0 || params.id_man==null)?'':params.id_man;
                                if(params.id_man!=''){
                                    Ext.getCmp(me.id + '-manzana').getStore().load({
                                        params:{
                                            vp_ciu_id: params.ciu_id,
                                            vp_id_via: params.id_via,
                                            vp_id_urb: params.id_urb
                                        },
                                        callback: function(){
                                            Ext.getCmp(me.id + '-manzana').setValue(parseInt(params.id_man));
                                        }
                                    });
                                }
                                Ext.getCmp(me.id + '-lote').enable();
                                Ext.getCmp(me.id + '-lote').clearValue();
                                params.pue_lote = (params.pue_lote==0 || params.pue_lote==null)?'':params.pue_lote;
                                if(params.pue_lote!=''){
                                    Ext.getCmp(me.id + '-lote').getStore().load({
                                        params:{
                                            vp_ciu_id: params.ciu_id,
                                            vp_id_mza: params.id_man,
                                            vp_id_urb: params.id_urb,
                                            vp_numero:params.pue_lote
                                        },
                                        callback: function(){
                                            Ext.getCmp(me.id + '-lote').setValue(params.id_geo);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }
        });

        me.flightPath.setMap(null);
        me.marker.setMap(null);
        me.setCoordenadas(params.dir_px, params.dir_py);

        if (parseInt(params.id_geo) > 0){
            me.setMap({zoon: 16, lat: params.dir_px, lon: params.dir_py});
            var myLatlng = new google.maps.LatLng(params.dir_px,params.dir_py);
            me.marker = new google.maps.Marker({
                position: myLatlng,
                map: me.map,
                title: '',
                icon:'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%C2%B0|0BBCDC|000001'
            });
        }else{
            gestiontrans.setMap({zoon: 8, lat: params.dir_px, lon: params.dir_py});
        } 
    },
    get_puerta:function(id_puerta){
        var me = this;
        if (id_puerta == 0){
            return;
        }else{
            Ext.Ajax.request({
                url:gestiontrans.url+'scm_scm_socionegocio_id_puerta/',
                params:{vl_id_puerta:id_puerta},
                success:function(response,options){
                    var res = Ext.decode(response.responseText);
                    if (parseInt(res.data[0].error_sql)==0){
                        me.setGeoLocalizar(res.data[0]);
                        //console.log(res.data[0])
                    }
                }
            }); 
        }
    },
    select_dispatcher_panel:function(vp_agencia,vp_fecha){
        var me = this;
        me.reset_maps();
        var mask = new Ext.LoadMask(Ext.getCmp(inicio.id+'-tabContent'),{
            msg:'Consultando GPS'
        });
        Ext.Ajax.request({
            url:gestiontrans.url+'scm_scm_dispatcher_panel/',
            params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha},
            success:function(response,options){
                var res = Ext.decode(response.responseText).data;
                Ext.each(res, function(obj,index){
                    var coordenadas = new google.maps.LatLng(obj.dir_px,obj.dir_py);
                    if (parseFloat(obj.dir_px) != 0){
                        //console.log(obj.dir_px);
                        //console.log(obj.dir_py);
                        me.setCoordenadas(obj.dir_px, obj.dir_py);
                        me.marker_dispatcher_panel(me.map,coordenadas,obj.gui_srec_id,obj.tipo);    
                    }
                    
                });
                me.setzoon(12);
            }
        });
    },
    marker_dispatcher_panel:function(map,coordenadas,gui_srec_id,tipo){
        var me = this;        
        if (tipo == 'R'){
            //var icon = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=R|4CBEFF|000001'
            //var icon = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=R|4CBEFF|000003|1.7';
            var icon = '/images/icon/marker2.png';
        }else if(tipo == 'E'){
            //var icon = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=E|FF9006|000001';
            var icon = '/images/icon/marker1.png';
        }

        var contentString = '<div id="content"  style="width:255px;">'+
              //string +
              '</div>';
        var infowindow = new google.maps.InfoWindow({
              content: contentString,
              maxWidth: 255
        });      
        var marker = new google.maps.Marker({
              position: coordenadas,
              map: me.map,
              animation: google.maps.Animation.DROP,
              title: '',
              icon:icon,
              gui_srec_id:gui_srec_id,
              tipo:tipo
        });
        //me.setzoon(18);
       // me.arrayMaker.push(marker);
        google.maps.event.addListener(marker, 'click', function() {
            //console.log(marker.gui_srec_id);
            //console.log(marker.tipo);
            //console.log(marker);
            
            var rec = Ext.getCmp(gestiontrans.id+'-recol-ruta').getSelectionModel().getSelection();
            if (rec != '') {
                var placa = rec[0].data.placa;
                var id_unidad =rec[0].data.id_unidad;
                
                global.Msg({
                        msg:'gui_srec_id:'+marker.gui_srec_id+'</br>Tipo:'+marker.tipo+'</br>Placa:'+placa+'</br>id_unidad:'+id_unidad,
                        icon:1,
                        buttosn:1,
                        fn:function(btn){
                        }
                    }); 
            }else{
                alert('Debes selecionar una unidad');
            }
        });
    },
    findMaker:function(rec){
        var me = this;
        gestiontrans.setMap();
        me.setMap('p');
        for (var i = 0; i < me.arrayMaker.length; i++) {
            if(parseInt(me.arrayMaker[i].gui_srec_id) == rec){
                //console.log(me.arrayMaker[i].gui_srec_id);
                me.arrayMaker[i].setMap(me.map);  
                me.arrayMaker[i].setAnimation(google.maps.Animation.BOUNCE);
            }else{
                me.arrayMaker[i].setMap(me.map);  
            }
        }
        
        //console.log(me.arrayMaker[0].gui_srec_id); 
    },
    setAgenciaShipper:function(id_shipper){
        var me = this;
        me.id_shipper = id_shipper;//shi_codigo
    },
    getAgenciaShipper:function(value){
        var me = this;
        return me.id_shipper;  //shi_codigo
    },
    setDirections:function(value){
        var me = this;
        me.id_shipper = value.id_shipper;
        me.gui_srec_id = value.gui_srec_id;
        me.id_direccion= value.id_direccion;
        me.vp_guia=value.vp_guia;

        if (value.tipo == 'O'){
            Ext.getCmp(me.id+'-rbtn-group').setValue({'gestiontrans-origen-rbtn':value.valor}); 
            me.id_age = value.id_age;
           
            me.gui_srec_id2 = value.gui_srec_id2;
            me.cnt_recol = value.cnt_recol;
           
            Ext.getCmp(me.id+'-grid-recoleccion').getStore().load({
               params:{va_shipper:me.id_shipper},
               callback:function(){
                    if (parseInt(me.cnt_recol) > 1){
                        Ext.getCmp(me.id+'-grid-recoleccion').setVisible(true);
                        Ext.getCmp(me.id+'-otra-recolec').setValue(true);
                    }else{
                        Ext.getCmp(me.id+'-grid-recoleccion').setVisible(false);    
                    }
                    
               }
            });
        }
        if (value.tipo == 'D'){
            Ext.getCmp(me.id+'-rbtn-group').setValue({'gestiontrans-destino-rbtn':value.valor});
        }  
    },
    get_record_grid:function(id_direccion,record){
        var me = this;
       // me.id_direccion=id_direccion;
        me.id_shipper = record.get('id_shipper');
        
       // me.gui_srec_id = record.get('gui_srec_id');
        //console.log(me.gui_srec_id);
    },
    update_direccion:function(){
        var me = this;
        var mask = new Ext.LoadMask(Ext.getCmp(inicio.id+'-tabContent'),{
            msg:'Loading....'
        });
        mask.show();
        var direc = me.getValues();
   
        var id_direccion = me.id_direccion;
        var ciu_id = direc[0].ciu_id;
        var tip_via = direc[0].tip_via;
        var id_via = direc[0].id_via;
        var nom_via = direc[0].nom_via;
        var id_urb = direc[0].id_urb;
        var urb = direc[0].urb;
        var id_puerta = direc[0].id_puerta;
        var via = via;
        var x = direc[0].coordenadas[0].lat;
        var y = direc[0].coordenadas[0].lon;
        var id_mza = direc[0].id_mza;
        var lote = direc[0].lote;
        var manzana = direc[0].manzana;

        Ext.Ajax.request({
            url:me.url2+'scm_scm_dispatcher_upd_direccion/',
            params:{id_direccion:id_direccion,ciu_id:ciu_id,tip_via:tip_via,id_via:id_via,nom_via:nom_via,id_urb:id_urb,urb:urb,id_puerta:id_puerta,via:via,x:x,y:y,id_mza:id_mza,lote:lote,manzana:manzana},
            success:function(response,options){
                mask.hide();
                var res = Ext.decode(response.responseText);
                if (parseInt(res.data[0].error_sql)==1){
                    global.Msg({
                        msg:res.data[0].error_info,
                        icon:1,
                        buttosn:1,
                        fn:function(btn){
                            grid = Ext.getCmp(me.id+'-grid-agencias-longitud');
                            if (grid.getStore().getCount()>0){
                                var rec = grid.getStore().getAt(0);
                                //gestiontrans.google_ruta(rec.get('o_dir_px') ,rec.get('o_dir_py') ,rec.get('d_dir_px'),rec.get('d_dir_py'));
                            }
                        }
                    });
                }else{
                    global.Msg({
                        msg:res.data[0].error_info,
                        icon:0,
                        buttosn:1,
                        fn:function(btn){
                        }
                    });
                }
            }
        });
    },
    pinta_agencia_select:function(){
        var me = this;
        me.clear_maps();
        var arrayAgencia = [];
        var va_shipper = Ext.getCmp(gestiontrans.id+'-shipper').getValue();
        Ext.Ajax.request({
            url:me.url2+'scm_scm_home_delivery_agencia_shipper/',
            params:{va_shipper:va_shipper},
            success:function(response,options){
                var res = Ext.decode(response.responseText).data;
                Ext.each(res,function(v,index){
                    arrayAgencia.push(v);
                    var agencia = new google.maps.LatLng(v.dir_px,v.dir_py);
                    var marker = new google.maps.Marker({
                            position: agencia,
                            map: me.map,
                            title: '',
                            icon:'/images/icon/'+v.shi_logo//'/images/icon/MIFARMA.jpg'
                            //icon:'/images/icon/MIFARMA.jpg'
                    });
                    var contentString = '<div id="content"  style="width:80px;">'+
                        v.agencia +
                        '</div>';
                    var infowindow = new google.maps.InfoWindow({
                          content: contentString,
                          maxWidth: 80
                    }); 
                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.open(me.map,marker);
                    });
                }); 
            }
        });
    },
    get_agenciShipper:function(){
        var me = this;
        me.clear_maps();
        var va_shipper=0;
        var chek = Ext.getCmp(me.id+'-rbtn-group').getValue();
        var op;
        if (me.tipo == 'D'){
            op = chek['gestiontrans-destino-rbtn'];
        }else if (me.tipo == 'O'){
            op = chek['gestiontrans-origen-rbtn'];;    
        }

        if (parseInt(op) == 1){
            va_shipper = 6;
            //me.get_agenciShipper(6);
        }else if (parseInt(op) == 2){
           // me.get_agenciShipper(me.id_shipper);
           va_shipper = me.id_shipper;
        }else if (parseInt(op) == 3){
            
        }

        /*var mask = new Ext.LoadMask(Ext.getCmp(gestiontrans.id+'-tab'),{
            msg:'Calculando Rutas...'
        });
        mask.show();*/
        //var shi_select = Ext.getCmp(gestiontrans.id+'-shipper').getValue();
        //console.log(shi_select);
        var arrayAgencia = [];
        grid = Ext.getCmp(me.id+'-grid-agencias-longitud');
        grid.getStore().load({
            params:{va_shipper:va_shipper},
            callback:function(){
                Ext.Ajax.request({
                    url:me.url2+'scm_scm_home_delivery_agencia_shipper/',
                    params:{va_shipper:va_shipper},//
                    success:function(response,options){
                        var res = Ext.decode(response.responseText).data;
                        Ext.each(res,function(v,index){
                            arrayAgencia.push(v);
                            var agencia = new google.maps.LatLng(v.dir_px,v.dir_py);
                            var marker = new google.maps.Marker({
                                    position: agencia,
                                    map: me.map,
                                    title: '',
                                    icon:'/images/icon/'+v.shi_logo//'/images/icon/MIFARMA.jpg'
                                    //icon:'/images/icon/MIFARMA.jpg'
                            });
                            var contentString = '<div id="content"  style="width:80px;">'+
                                v.agencia +
                                '</div>';
                            var infowindow = new google.maps.InfoWindow({
                                  content: contentString,
                                  maxWidth: 80
                            }); 
                            google.maps.event.addListener(marker, 'click', function() {
                                infowindow.open(me.map,marker);
                            });
                        }); 
                        //me.google_time(arrayAgencia);   
                      //  me.time_destino();
                       // mask.hide();
                    }
                });
            }

        });
    },
    time_destino:function(){
        var me = this;
        var arrayData = [];
        var va_shipper=me.id_shipper;
        var d = Ext.getCmp(gestiontrans.id+'-destino').getValues();
        grid = Ext.getCmp(me.id+'-grid-agencias-longitud');
        store = grid.getStore();

        ent_dir_px = parseFloat(d[0].coordenadas[0].lat);
        ent_dir_py = parseFloat(d[0].coordenadas[0].lon);
        Ext.Ajax.request({
            url:me.url2+'setCalculoDistanciaDuracion/',
            params:{pos_px:ent_dir_px,pos_py:ent_dir_py,va_shipper:va_shipper},
            success:function(response,options){
                var res = Ext.decode(response.responseText).data;
                //console.log(res);
                store = grid.getStore();
                store.each(function(record, idx){
                    var id_agencia = parseInt(record.get('id_agencia'));
                    var und_time = parseInt(record.get('gps_time_t'));

                    Ext.each(res,function(obj,index){
                        var id_agencia2 = parseInt(obj.id_agencia);
                        if(id_agencia == id_agencia2){
                           var age_time = parseInt(obj.dur_text);
                           var time_tot = age_time+und_time;
                           time_tot = time_tot.toString()+' min';
                           record.set('dis_text', obj.dis_text);
                           record.set('dis_value', obj.dis_value);
                           record.set('dur_text', obj.dur_text);
                           record.set('dur_value', obj.dur_value);
                           record.set('time_t', time_tot);
                           record.commit();
                        }
                        
                    });

                });
                grid.getView().refresh(); 
                store.each(function(record, idx){
                    arrayData.push(record);
                });
                arrayData.sort(function(a,b) { return parseInt(a.data.time_t) - parseInt(b.data.time_t)});
                //console.log(arrayData);
                grid.getStore().loadData(arrayData);
                grid.getView().refresh(); 
            }

        });
    },
    google_time:function(array){
        var me =this;
        //me.setMap('p');
        arrayData = [];
        var xy;

        if (me.tipo == 'D'){
            xy = Ext.getCmp(gestiontrans.id+'-origen').getValues();    
        }else if(me.tipo == 'O'){
            xy = Ext.getCmp(gestiontrans.id+'-destino').getValues();    
        }

        //console.log(me.tipo);
        //console.log(me.tipo);
        //console.log(xy[0].coordenadas[0].lat);
        //console.log(xy[0].coordenadas[0].lon);

        grid = Ext.getCmp(me.id+'-grid-agencias-longitud');
        d_dir_px = parseFloat(xy[0].coordenadas[0].lat);
        d_dir_py = parseFloat(xy[0].coordenadas[0].lon);
        var destino = new google.maps.LatLng(d_dir_px,d_dir_py);
        //console.log(destino);
        //console.log(array);
        var count = array.length -1 ;
        Ext.each(array,function(v,index){
            var OriginLatlng = new google.maps.LatLng(parseFloat(v.dir_px),parseFloat(v.dir_py));
            var request = {
                origin:OriginLatlng,
                destination:destino,
              //  waypoints:[],//{location: agencia},{location: '-13.918217, -74.330162'}  
                //optimizeWaypoints: true,
                travelMode: google.maps.TravelMode.DRIVING,
                //provideRouteAlternatives: true
            };

            global.sleep(200);
            me.directionsService.route(request,function(response,status){
                if (status == google.maps.DirectionsStatus.OK){
                    arrayData.push({
                        age_codigo:v.age_codigo,
                        agencia:v.agencia,
                        ciu_iata:v.ciu_iata,
                        ciu_id:v.ciu_id,
                        ciu_ubigeo:v.ciu_ubigeo,
                        dir_calle:v.dir_calle,
                        o_dir_px:parseFloat(v.dir_px),
                        o_dir_py:parseFloat(v.dir_py),
                        d_dir_px:parseFloat(d_dir_px),
                        d_dir_py:parseFloat(d_dir_py),
                        dir_referen:v.dir_referen,
                        distrito:v.distrito,
                        id_agencia:v.id_agencia,
                        distance:response.routes[0].legs[0].distance.text,
                        duration:response.routes[0].legs[0].duration.text,
                        distancev:parseInt(response.routes[0].legs[0].distance.text),
                        durationv:parseInt(response.routes[0].legs[0].duration.text),
                        aduration:100000000000,
                        total:0,
                        placa:''

                    });
                    arrayData.sort(function(a,b) { return parseInt(a.durationv) - parseInt(b.durationv)});
                    grid.getStore().loadData(arrayData);
                    grid.getView().refresh(); 
                }else{
                    arrayData.push({
                        age_codigo:v.age_codigo,
                        agencia:v.agencia,
                        ciu_iata:v.ciu_iata,
                        ciu_id:v.ciu_id,
                        ciu_ubigeo:v.ciu_ubigeo,
                        dir_calle:v.dir_calle,
                        o_dir_px:parseFloat(v.dir_px),
                        o_dir_py:parseFloat(v.dir_py),
                        d_dir_px:parseFloat(d_dir_px),
                        d_dir_py:parseFloat(d_dir_py),
                        dir_referen:v.dir_referen,
                        distrito:v.distrito,
                        id_agencia:v.id_agencia,
                        distance:'No se encontraron datos',
                        duration:'No se encontraron datos',
                        distancev:1000000000,
                        durationv:1000000000,
                        aduration:100000000000,
                        total:0,
                        placa:''

                    });
                    arrayData.sort(function(a,b) { return parseInt(a.durationv) - parseInt(b.durationv)});
                    grid.getStore().loadData(arrayData);
                    grid.getView().refresh(); 
                    if (status ==  'OVER_QUERY_LIMIT'){
                         global.Msg({
                            msg:'Debes esperar 10 segundos </br> Antes de Volver a Confirmar Destino',
                            icon:1,
                            buttosn:1,
                            fn:function(btn){
                                 Ext.getCmp(me.id+'-grid-agencias-longitud').getStore().removeAll();  
                            }
                        }); 
                    }
                   
                   
                }
                
                /*if (count == index){
                   // console.log(count);
                   // console.log(index);
                    Ext.each(array,function(a){
                        var OriginLatlng = new google.maps.LatLng(parseFloat(a.dir_px),parseFloat(a.dir_py));
                        me.arrayDataa = null;
                        me.arrayDataa = [];
                        var a = me.distanceUnidad(OriginLatlng,a.id_agencia);
                    });
                }*/
              
            }); 
        });     
    },
    distanceUnidad:function(destino,id_agencias){
        console.log(id_agencias);
        var me = this;
        //var unidades = me.unidades_xy();
        var unidades = me.unidades;
        
      
        me.arrayDataa = null;
        me.arrayDataa = [];
        var clear = [];
        var contador=0;
        Ext.each(unidades,function(ab,index){
            if (ab.pos_px != ''){
                contador=contador+1;
            }
        });

      
        Ext.each(unidades, function(obj,index){
            var origen = new google.maps.LatLng(parseFloat(obj.pos_px),parseFloat(obj.pos_py));
            //var destino = new google.maps.LatLng(-11.782413062516948,-76.79493715625);
            if (obj.pos_px != ''){
                //console.log(obj.pos_px)
                var request = {
                    origin:origen,
                    destination:destino,
                    //waypoints:[],//{location: agencia},{location: '-13.918217, -74.330162'}  
                   // optimizeWaypoints: true,
                    travelMode: google.maps.TravelMode.DRIVING,
                   // provideRouteAlternatives: true
                };
               
                //global.sleep(1001);
                me.directionsService.route(request,function(response,status){
                        if (status == google.maps.DirectionsStatus.OK){ 
                           // console.log(response);
                                me.arrayDataa.push({
                                    distancetext:response.routes[0].legs[0].distance.text,
                                    distance:parseInt(response.routes[0].legs[0].distance.text),
                                    durationtext:response.routes[0].legs[0].duration.text,
                                    duration:parseInt(response.routes[0].legs[0].duration.text),
                                    id_unidad:obj.id_unidad,
                                    id_agencia:id_agencias,
                                    pos_px:parseFloat(obj.pos_px),
                                    pos_py:parseFloat(obj.pos_py),
                                    placa:obj.placa
                                });                              
                        }else{
                            console.log(status);
                            me.arrayDataa.push({
                                distancetext:'No se encontro datos',
                                distance:1000000,
                                durationtext:'No se encontro datos',
                                duration:1000000,
                                id_unidad:obj.id_unidad,
                                id_agencia:id_agencias,
                                pos_px:parseFloat(obj.pos_px),
                                pos_py:parseFloat(obj.pos_py),
                                placa:obj.placa
                            });  
                            if (status ==  'OVER_QUERY_LIMIT' || status == 'UNKNOWN_ERROR'){
                                me.arrayDataa.push({
                                    distancetext:'No se encontro datos',
                                    distance:1000000,
                                    durationtext:'No se encontro datos',
                                    duration:1000000,
                                    id_unidad:obj.id_unidad,
                                    id_agencia:id_agencias,
                                    pos_px:parseFloat(obj.pos_px),
                                    pos_py:parseFloat(obj.pos_py),
                                    placa:obj.placa
                                });  
                            }
                        }
                       // global.sleep(100);
                        me.arrayDataa.sort(function(a,b) { return parseInt(a.duration) - parseInt(b.duration)});
                        //console.log(me.arrayDataa[0]);
                        //clear=me.arrayData[0]; 
                       // me.setdata();
                       if (index == contador-1){
                            //console.log(me.arrayDataa);
                            //console.log(contador);
                            //console.log(me.arrayDataa.length);
                            //global.sleep(100);
                            me.call_distance_moto(); 
                            me.arrayDataa=[];
                       }
                       

                });
            }
        });
       /*  var me = this;
       
        var unidades = me.unidades;
        
      
        me.arrayDataa = null;
        me.arrayDataa = [];
        var clear = [];
        var contador=0;
        Ext.each(unidades,function(ab,index){
            if (ab.pos_px != ''){
                contador=contador+1;
            }
        });
        var service = new google.maps.DistanceMatrixService();
        global.sleep(200);         
        Ext.each(unidades, function(obj,index){
            var origen = new google.maps.LatLng(parseFloat(obj.pos_px),parseFloat(obj.pos_py));
            //var destino = new google.maps.LatLng(-11.782413062516948,-76.79493715625);
            if (obj.pos_px != ''){
               // console.log(destino);
              //console.log(origen);
                var request = {
                       origins: [origen],
                       destinations: [destino],//es la agencia
                       travelMode: google.maps.TravelMode.DRIVING,
                       unitSystem: google.maps.UnitSystem.METRIC,
                       avoidHighways: false,
                       avoidTolls: false
                }   

               // global.sleep(300); 
                
                global.sleep(200);
                service.getDistanceMatrix(request, function(response, status){
                       
                        if (status == google.maps.DistanceMatrixStatus.OK){
                            if (response.rows[0].elements[0].status=='OK'){
                                //console.log(parseInt(response.rows[0].elements[0].distance.value));
                                me.arrayDataa.push({
                                    distancetext:response.rows[0].elements[0].distance.text,
                                    distance:parseInt(response.rows[0].elements[0].distance.value),
                                    durationtext:response.rows[0].elements[0].duration.text,
                                    duration:parseInt(response.rows[0].elements[0].duration.value),
                                    id_unidad:obj.id_unidad,
                                    id_agencia:id_agencias,
                                    pos_px:obj.pos_px,
                                    pos_py:obj.pos_py,
                                    placa:obj.placa
                                });

                            }else{
                                 me.arrayDataa.push({
                                    distancetext:'No se encontro datos',
                                    distance:1000000,
                                    durationtext:'No se encontro datos',
                                    duration:1000000,
                                    id_unidad:obj.id_unidad,
                                    id_agencia:id_agencias,
                                    pos_px:obj.pos_px,
                                    pos_py:obj.pos_py,
                                    placa:obj.placa
                                });    

                            }                                
                        }else{
                            me.arrayDataa.push({
                                distancetext:'No se encontro datos',
                                distance:1000000,
                                durationtext:'No se encontro datos',
                                duration:1000000,
                                id_unidad:obj.id_unidad,
                                id_agencia:id_agencias,
                                pos_px:obj.pos_px,
                                pos_py:obj.pos_py,
                                placa:obj.placa
                            });  

                        }
                        global.sleep(100);
                        me.arrayDataa.sort(function(a,b) { return parseInt(a.duration) - parseInt(b.duration)});
                        //console.log(me.arrayDataa[0]);
                        //clear=me.arrayData[0]; 
                       // me.setdata();
                       if (index == contador-1){
                            console.log(me.arrayDataa);
                            //global.sleep(100);
                            me.call_distance_moto(); 
                       }
                       

                });
            }
        });*/   
    },
    setdata:function(){
        var me =this;
        console.log(me.arrayDataa);
        //me.arrayDataa=[];
    },
    call_distance_moto:function(){
        
        var me = this;
        //console.log(me.arrayDataa);
       // var arrayData =[];
       // var arrayData2 =[];
        //var distance = [];
        var result = me.arrayDataa[0];
        grid = Ext.getCmp(me.id+'-grid-agencias-longitud');
        store = grid.getStore();
       // console.log(result);
        store.each(function(record, idx){
            //console.log(record);
            var id_agencia = record.get('id_agencia');
            var val2 = record.get('adurationtext');
            var aduration = record.get('aduration');
            var duration =record.get('duration');
            if (parseInt(result.id_agencia) == parseInt(id_agencia)){//&& parseInt(result.duration) < parseInt(aduration)
                global.sleep(200);
                //console.log(result.placa);
                //var segundos = parseInt(record.get('durationv'))+parseInt(result.duration);
                //var total = (segundos*1)/60;
                //console.log(total);
                //console.log(parseInt(record.get('durationv')));
                //console.log(parseInt(result.duration));
                var time_unidad = result.durationtext.split(" ");
                var time_agencia = duration.split(" ");
                var total = parseInt(time_unidad[0])+parseInt(time_agencia[0]);
                total = total.toString() +' min';
                 global.sleep(200);
                record.set('adurationtext', result.durationtext);
                record.set('aduration',result.duration);
                record.set('id_unidad', result.id_unidad);
                record.set('und_px', result.pos_px);
                record.set('und_py', result.pos_py);
                record.set('total', total);
                record.set('placa', result.placa);
                record.commit();
                grid.getView().refresh(); 
            }
           
        });
        grid.getView().refresh(); 
        me.arrayDataa=[];
    },
    unidades_xy:function(){
        /*var me = this;
        var vp_agencia = Ext.getCmp(gestiontrans.id+'-agencia').getValue();
        var vp_fecha = Ext.getCmp(gestiontrans.id+'-fecha').getRawValue();
        var ajax = Ext.Ajax.request({
            async:false,
            url:gestiontrans.url+'scm_scm_home_delivery_unidad_gps/',
            params:{vp_agencia:vp_agencia,vp_fecha:vp_fecha},
            success:function(response,options){
               // var res = Ext.decode(response.responseText).data;
               // console.log(res);
                //response = res;
            }
        });   
        //console.log(ajax);
        var respuesta = Ext.decode(ajax.responseText);
        me.unidades = respuesta.data;
       // return respuesta.data;*/
    },
    google_ruta:function(o_dir_px,o_dir_py,d_dir_px,d_dir_py){
        var me =this;
        //me.pinta_destino(d_dir_px,d_dir_py);
       // me.pinta_destino(d_dir_px,d_dir_py);

        var OriginLatlng = new google.maps.LatLng(o_dir_px,o_dir_py);
        var destino = new google.maps.LatLng(d_dir_px,d_dir_py);
        var request = {
            origin:OriginLatlng,
            destination:destino,
          //  waypoints:[],//{location: agencia},{location: '-13.918217, -74.330162'}  
            optimizeWaypoints: true,
            travelMode: google.maps.TravelMode.DRIVING,
            provideRouteAlternatives: true
        };
        me.directionsService.route(request,function(response,status){
            if (status == google.maps.DirectionsStatus.OK){
                me.directionsDisplay.setDirections(response);
                
            }else{
               // alert('no hay ruta');
            }
            /*******************Captura los Punto y dibuja la Pilylinea*******************************/
            var consolidado=[];
            me.flightPath.setMap(null);
            var getRuta = me.directionsDisplay.getDirections();
            Ext.each(getRuta.routes[0].legs,function(obj1,index){
                Ext.each(obj1.steps,function(obj2,inde2){
                    if (obj2.lat_lngs.length > 0){
                        Ext.each(obj2.lat_lngs,function(obj3,index3){
                            var OLatlng = new google.maps.LatLng(obj3.k,obj3.D);
                            consolidado.push(OLatlng);
                        });
                    }    
                });
            });
            me.flightPath = new google.maps.Polyline({
                path: consolidado,
                geodesic: true,
                strokeColor: '#2A02DC',
                strokeOpacity: 0.1,
                strokeWeight: 10,
                //draggable: true,
                map: me.map
            });
           // console.log(consolidado);
            google.maps.event.addListener(me.directionsDisplay, 'directions_changed', function() {
                consolidado=[];
                me.flightPath.setMap(null);
                var getRuta = me.directionsDisplay.getDirections();
                Ext.each(getRuta.routes[0].legs,function(obj1,index){
                    Ext.each(obj1.steps,function(obj2,inde2){
                        if (obj2.lat_lngs.length > 0){
                            Ext.each(obj2.lat_lngs,function(obj3,index3){
                                var OLatlng = new google.maps.LatLng(obj3.k,obj3.D);
                                consolidado.push(OLatlng);
                            });
                        }    
                    });
                });
                //console.log(consolidado);
                me.flightPath = new google.maps.Polyline({
                    path: consolidado,
                    geodesic: true,
                    strokeColor: '#2A02DC',
                    strokeOpacity: 0.1,
                    strokeWeight: 10,
                    //draggable: true,
                    /*icons: [{
                      icon: lineSymbol,
                      offset: '100%'
                    }],*/
                    map: me.map
                });
            });  
            /*********************************************************************************************/
        });
    },
    //google_ruta2:function(o_dir_px,o_dir_py,d_dir_px,d_dir_py,und_x,und_y){
    google_ruta2:function(und_px,und_py,age_dir_px,age_dir_py,d_dir_px,d_dir_py){    
        var me =this;
        me.pinta_destino(d_dir_px,d_dir_py);
        var origen = new google.maps.LatLng(und_px,und_py); 
        var agencia = new google.maps.LatLng(age_dir_px,age_dir_py);
        var destino = new google.maps.LatLng(parseFloat(d_dir_px),parseFloat(d_dir_py));
        var request = {
            origin:origen,
            destination:destino,
            waypoints:[{location:agencia}],//{location: agencia},{location: '-13.918217, -74.330162'}  
            optimizeWaypoints: true,
            travelMode: google.maps.TravelMode.DRIVING,
            provideRouteAlternatives: true,
        };
        me.directionsService.route(request,function(response,status){
            if (status == google.maps.DirectionsStatus.OK){
                //console.log(response);
                //directionsDisplay.setDirections(response);
                me.directionsDisplay.setDirections(response);
                
            }else{
               // alert('no hay ruta');
            }
            var consolidado=[];
            var lineSymbol = {
                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
            };
            /*google.maps.event.addListener(me.directionsDisplay, 'directions_changed', function() {
                consolidado=[];
                me.flightPath.setMap(null);
                var getRuta = me.directionsDisplay.getDirections();
                Ext.each(getRuta.routes[0].legs,function(obj1,index){
                    Ext.each(obj1.steps,function(obj2,inde2){
                            if (obj2.lat_lngs.length > 0){
                                Ext.each(obj2.lat_lngs,function(obj3,index3){
                                    var OLatlng = new google.maps.LatLng(obj3.k,obj3.D);
                                    consolidado.push(OLatlng);
                                });
                            }    
                    });
                });
                me.flightPath = new google.maps.Polyline({
                    path: consolidado,
                    geodesic: true,
                    strokeColor: '#FF0101',
                    strokeOpacity: 1,//0.5,
                    strokeWeight: 10,
                    //draggable: true,
                    icons: [{
                      icon: lineSymbol,
                      offset: '100%'
                    }],
                    map: me.map
                });
            });   */
        });
    },
    pinta_unidad_actual:function(x,y,icono,placa){
        var me = this;
        me.setMap();
        var contentString = '<div id="content"  style="width:70px;">'+
                          placa
                          '</div>';
        var infowindow = new google.maps.InfoWindow({
              content: contentString,
              maxWidth: 70
        });
        var unidad = new google.maps.LatLng(x,y);
        var marker = new google.maps.Marker({
                position: unidad,
                map: me.map,
                title: '',
                draggable: true,
                icon:icono
        });
        //infowindow.open(me.map,marker);

        google.maps.event.addListener(marker, 'click', function() {
             infowindow.open(me.map,marker);
        });
    },
    pinta_destino:function(d_dir_px,d_dir_py){
        var me = this;
       // me.marker.setMap(null);
        gestiontrans.varsmapa.marker.setMap(null);
        var agencia = new google.maps.LatLng(d_dir_px,d_dir_py);
        me.marker = new google.maps.Marker({
                position: agencia,
                map: me.map,
                title: '',
                icon:'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%C2%B0|0BBCDC|000001'
                
        });
        /*var chek;
        var vp_origen;
        var va_shipper;
        if (me.tipo== 'O'){
            chek = Ext.getCmp(me.id+'-rbtn-group').getValue();
            vp_origen = chek['gestiontrans-origen-rbtn'];    
        }else if (me.tipo='D'){
            chek = Ext.getCmp(me.id+'-rbtn-group').getValue();
            vp_origen = chek['gestiontrans-destino-rbtn'];    
        }

        if (parseInt(vp_origen) == 1){
            va_shipper = 6;
        }else if (parseInt(vp_origen) == 2){
            va_shipper=me.id_shipper;
        }*/

        /*var me = this;
        var mask = new Ext.LoadMask(Ext.getCmp(gestiontrans.id+'-tab'),{
            msg:'Calculando Rutas...!'
        });
        mask.show();
        var arrayAgencia = [];
        Ext.Ajax.request({
            url:me.url2+'getAgencia_shipper/',
            params:{va_shipper:va_shipper},
            success:function(response,options){
                var res = Ext.decode(response.responseText).data;
                Ext.each(res,function(v,index){
                    arrayAgencia.push(v);
                    var agencia = new google.maps.LatLng(v.dir_px,v.dir_py);
                    me.marker = new google.maps.Marker({
                            position: agencia,
                            map: me.map,
                            title: '',
                            icon:'/images/icon/MIFARMA.jpg'
                    });
                }); 
                mask.hide();
            }
        });*/
    },
    dispatcher_upd_origen:function(record){
        
        var me = this;        
        var chek = Ext.getCmp(me.id+'-rbtn-group').getValue();
        
        var vp_srec_id = me.gui_srec_id;
        var vp_srec_id2 = me.gui_srec_id2;
        var vp_origen = chek['gestiontrans-origen-rbtn'];
        var vp_provincia =0;
        var vp_id_age=0;
        var vp_dir_id=0;
        var vp_ciu_id=0;
        var vp_id_contacto=0;
        var vp_id_area=0;
        var vp_id_geo=0;
        var vp_dir_calle='';
        var vp_dir_refere='';
        var vp_dir_numvia='';
        var vp_dir_px=0;
        var vp_dir_py=0;

        if (parseInt(vp_origen)==2 ){
            vp_id_age=record.get('id_agencia');
            vp_ciu_id=record.get('ciu_id');

           /* var d = Ext.getCmp(gestiontrans.id+'-destino').getValues();
            d_dir_px = parseFloat(d[0].coordenadas[0].lat);
            d_dir_py = parseFloat(d[0].coordenadas[0].lon);

            o_dir_px = parseFloat(record.get('o_dir_px'));
            o_dir_py = parseFloat(record.get('o_dir_py'));*/
            //console.log(record);
           // console.log(d_dir_px +'</br>'+d_dir_py+'</br>'+o_dir_px+'</br>'+o_dir_py);
            //me.google_ruta(o_dir_px,o_dir_py,d_dir_px,d_dir_py);
        }

        Ext.Ajax.request({
           // url:me.url2+'scm_scm_dispatcher_upd_origen/',
            url:me.url2+'scm_scm_home_delivery_upd_origen/',
            params:{
                vp_guia:me.vp_guia,
                vp_srec_id:vp_srec_id,
                vp_origen:vp_origen,
                vp_provincia:vp_provincia,
                vp_id_age:vp_id_age,
                vp_dir_id:vp_dir_id,
                vp_ciu_id:vp_ciu_id,
                vp_id_geo:vp_id_geo,
                vp_dir_calle:vp_dir_calle,
                vp_dir_numvia:vp_dir_numvia,
                vp_dir_refere:vp_dir_refere,
                vp_dir_px:vp_dir_px,
                vp_dir_py:vp_dir_py,
                },
            success:function(response,options){
                var res = Ext.decode(response.responseText);
               // console.log(me.grid2.id_age);
                if (parseInt(res.data[0].error_sql)==1){
                    global.Msg({
                        msg:res.data[0].error_info,
                        icon:1,
                        buttosn:1,
                        fn:function(btn){
                            var valor = Ext.getCmp(me.id+'-otra-recolec').getValue();
                            if (valor){
                                Ext.Ajax.request({
                                     url:me.url2+'scm_scm_home_delivery_upd_origen/',
                                     params:{
                                        vp_guia:me.vp_guia,
                                        vp_srec_id:vp_srec_id2,
                                        vp_origen:vp_origen,
                                        vp_provincia:vp_provincia,
                                        vp_id_age:me.grid2.id_age,
                                        vp_dir_id:vp_dir_id,
                                        vp_ciu_id:vp_ciu_id,
                                        vp_id_geo:vp_id_geo,
                                        vp_dir_calle:vp_dir_calle,
                                        vp_dir_numvia:vp_dir_numvia,
                                        vp_dir_refere:vp_dir_refere,
                                        vp_dir_px:vp_dir_px,
                                        vp_dir_py:vp_dir_py,
                                     },
                                     success:function(response,options){
                                        var res = Ext.decode(response.responseText);
                                           if (parseInt(res.data[0].error_sql)==1){
                                              global.Msg({
                                                msg:res.data[0].error_info,
                                                icon:1,
                                                buttosn:1,
                                                fn:function(btn){
                                                     Ext.getCmp(gestiontrans.id+'-acoordion-ruta').setCollapsed(false);
                                                }
                                              });
                                           }else{
                                                global.Msg({
                                                    msg:res.data[0].error_info,
                                                    icon:0,
                                                    buttosn:1,
                                                    fn:function(btn){
                                                    }
                                                });
                                           }
                                     }
                                });
                            }else{
                             Ext.getCmp(gestiontrans.id+'-acoordion-ruta').setCollapsed(false);
                            }
                        }
                    });
                }else{
                    global.Msg({
                        msg:res.data[0].error_info,
                        icon:0,
                        buttosn:1,
                        fn:function(btn){
                        }
                    });
                }
            }
        });
    },
    dispatcher_upd_destino:function(record){
        var  me = this;        
        var chek = Ext.getCmp(me.id+'-rbtn-group').getValue();
        
        var vp_srec_id = me.gui_srec_id;
        var vp_origen = chek['gestiontrans-destino-rbtn'];
        var vp_provincia =0;
        var vp_id_age=0;
        var vp_dir_id=0;
        var vp_ciu_id=0;
        var vp_id_contacto=0;
        var vp_id_area=0;
        var vp_id_geo=0;
        var vp_dir_calle='';
        var vp_dir_refere='';
        var vp_dir_numvia='';
        var vp_dir_px=0;
        var vp_dir_py=0;

        if (parseInt(vp_origen)==2 ){
            vp_id_age=record.get('id_agencia');
            vp_ciu_id=record.get('ciu_id');
        }else if(parseInt(vp_origen)==3 ){
           // console.log(me.getValues());
            var v = me.getValues();
            vp_dir_id= v[0].id_direccion;
            vp_ciu_id= v[0].ciu_id;
            vp_id_geo= v[0].id_puerta;
            vp_dir_calle= v[0].direccion;
            vp_dir_numvia= v[0].via;
            vp_dir_px = v[0].coordenadas[0].lat;
            vp_dir_py = v[0].coordenadas[0].lon;
            
        }

        if (parseInt(vp_origen)==3 && parseFloat(vp_dir_px)==-11.782413062516948 || parseFloat(vp_dir_py)==-76.79493715625 || parseFloat(vp_dir_px)==0 || parseFloat(vp_dir_py)==0 ){
            global.Msg({
                msg:'Debes Realizar la busqueda...',
                icon:1,
                buttosn:1,
                fn:function(btn){
                }
            });
        }else{
               // Ext.getCmp(gestiontrans.id+'-origen').get_agenciShipper();
                Ext.Ajax.request({
                    //url:me.url2+'scm_scm_dispatcher_upd_destino/',
                    url:me.url2+'scm_scm_home_delivery_upd_destino/',
                    params:{
                        vp_srec_id:vp_srec_id,
                       // vp_origen:vp_origen,
                      //  vp_provincia:vp_provincia,
                      //  vp_id_age:vp_id_age,
                        vp_dir_id:vp_dir_id,
                        vp_ciu_id:vp_ciu_id,
                      //  vp_id_contacto:vp_id_contacto,
                       // vp_id_area:vp_id_area,
                        vp_id_geo:vp_id_geo,
                        vp_dir_calle:vp_dir_calle,
                        vp_dir_numvia:vp_dir_numvia,
                        vp_dir_refere:vp_dir_refere,
                        vp_dir_px:vp_dir_px,
                        vp_dir_py:vp_dir_py},
                    success:function(response,options){
                        var res = Ext.decode(response.responseText);
                        if (parseInt(res.data[0].error_sql)==1){
                            global.Msg({
                                msg:res.data[0].error_info,
                                icon:1,
                                buttosn:1,
                                fn:function(btn){
                                    Ext.getCmp(gestiontrans.id+'-acoordion-origen').setCollapsed(false);  
                                }
                            });
                        }else{
                            global.Msg({
                                msg:res.data[0].error_info,
                                icon:0,
                                buttosn:1,
                                fn:function(btn){
                                }
                            });
                        }
                    }
                });
        } 
    },
    valida_coordenada:function(){
        var me = this    
        var v = me.getValues();
        //vp_dir_id= v[0].id_direccion;
        //vp_ciu_id= v[0].ciu_id;
        //vp_id_geo= v[0].id_puerta;
        //vp_dir_calle= v[0].direccion;
        //vp_dir_numvia= v[0].via;
        vp_dir_px = v[0].coordenadas[0].lat;
        vp_dir_py = v[0].coordenadas[0].lon;
        if (parseFloat(vp_dir_px)==-11.782413062516948 || parseFloat(vp_dir_py)==-76.79493715625 || parseFloat(vp_dir_px)==0 || parseFloat(vp_dir_py)==0){
            return false;
        }else{
            return true;
        }
    },
    setUnidad:function(id_unidad,id_agencia,d_dir_px,d_dir_py){
        var me = this;
        me.log.id_unidad = id_unidad;
        me.log.id_agencia = id_agencia
        me.log.d_dir_px = d_dir_px;
        me.log.d_dir_py = d_dir_py;
        //me.log
        /*me.log.age_x = age_x;
        me.log.age_y = age_y;*/
    },
    getUnidad:function(){
        var me = this;
        var value ={
            id_unidad : parseInt(me.log.id_unidad),
            id_agencia : parseInt(me.log.id_agencia),
            d_dir_px:parseFloat(me.log.d_dir_px),
            d_dir_py:parseFloat(me.log.d_dir_py)
            /*age_x : parseFloat(me.log.age_x),
            age_y : parseFloat(me.log.age_y)*/

        }
        return value;
    },
    resetTipo:function(){
        var me = this;
        if (me.tipo == 'O'){
            Ext.getCmp(me.id+'-rbtn-group').setValue({'gestiontrans-origen-rbtn':3});
        }else if (me.tipo == 'D'){
            Ext.getCmp(me.id+'-rbtn-group').setValue({'gestiontrans-destino-rbtn':3});
        }
    }
});














