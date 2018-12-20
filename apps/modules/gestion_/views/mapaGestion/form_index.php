<script type="text/javascript">
/**
 * @author  Jim
 * Refactorizado por: Christian Tamayo
 * when I wrote this code, God and I knew what it meant. 
 * It is probable that God knows it still; but as for me, I have totally forgotten.
 */
var tab = Ext.getCmp(inicio.id+'-tabContent');
if (!Ext.getCmp('mapa_gestion-tab')){
    var mapa_gestion = {
        id:'mapa_gestion',
        id_menu:'<?php echo $p["id_menu"];?>',
        url:'/gestion/mapaGestion/',
        mapa:{
            map:'',
            directionsDisplay: new google.maps.DirectionsRenderer(),
            directionsService : new google.maps.DirectionsService(),
            trafficLayer:new google.maps.TrafficLayer(),
            markers:[],
            positionMarkers:[], 
            lat:-12.047926902770996,
            lng:-77.0811996459961,
            zoom:13,
            currentinfowindow:null
        },
        cmp:{
            trafic:false,
            cmpAct:0,
            lineStore:[],
            dataTimeLine:[],
            timeline:[],
            reload:false,
            dataForSetMap: [],
            getGuia: '',
            dataForMarker: [],
            itemclick: 0,
            bodyhe: '',
            actualID: [],
            pruebatpl: {},
            numberOfUnitShow: 0,
            recordsForStoreCar: {},
            clickBtnUpdateGMaps: 0,
            objectClicked: {},
            idPlacaClick: 0
        },
        dataNow:{vp_fecha:'',vp_id_placa:0,placa:'',chofer:''},
        init:function(){
            Ext.tip.QuickTipManager.init();

            this.store_car = Ext.create('Ext.data.Store',{
                id:mapa_gestion.id+'-store_car',
                fields: [
                    {name: 'id', type: 'int'},
                    {name: 'nivel', type: 'int'},
                    {name: 'placa', type: 'string'},
                    {name: 'chofer', type: 'string'},
                    {name: 'telefono', type: 'string'},
                    {name: 'bateria', type: 'int'},
                    {name: 'batery', type: 'int'},
                    {name: 'foto', type: 'string'},
                    {name: 'tot_servicio', type: 'int'},
                    {name: 'progreso', type: 'string'},
                    {name: 'tot_dl', type: 'int'},
                    {name: 'tot_ausente', type: 'int'},
                    {name: 'tot_rechazo', type: 'int'},
                    {name: 'rezagos', type: 'int'},
                    {name: 'pendientes', type: 'int'},
                    {name: 'hora_ini', type: 'string'},
                    {name: 'hora_total', type: 'string'},
                    {name: 'hora_ultimo', type: 'string'},
                    {name: 'hora_inicio', type: 'string'},
                    {name: 'hora_final', type: 'string'},
                    {name: 'none_time', type: 'string'},
                    {name: 'id_placa', type: 'int'},
                    {name: 'estado', type: 'string'},
                    {name: 'minutos', type: 'string'},
                    {name: 'pos_px', type: 'string'},
                    {name: 'pos_py', type: 'string'},
                    {name: 'fecha', type: 'string'},
                    {name: 'agencia', type: 'int'},
                    {name: 'styleactive',type:'string'},
                    {name: 'observacion',type:'string'},
                    {name: 'numberOfCars', type: 'int'},
                    {name: 'icono', type: 'string'},
                    {name: 'total_km',type:'string'}

                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: mapa_gestion.url+'get_list_shipper/',
                    reader:{
                        type: 'json', 
                        rootProperty: 'data'
                    },
                    extraParams:{
                        sis_id: 1
                    },
                    callback:function(){
                    }
                },
                listeners:{
                    load: function(obj, records, successful, opts){//activo1
                        mapa_gestion.cmp.recordsForStoreCar=records;
                        mapa_gestion.setPointTrack(obj);//para el google Maps con los carritos
                        //google.load("visualization", "1",{"callback" : mapa_gestion.drawVisualization});
                        mapa_gestion.setLoadDinamicComponent();//carga las tortas de los carritos
                        mapa_gestion.cmp.itemclick = 0;
                        mapa_gestion.cmp.actualID = [];
                    }
                }
            });


            this.store_table_guias = Ext.create('Ext.data.Store',{
                fields: [
                    {name: 'id', type: 'int'},
                    {name: 'id_guia', type: 'int'},
                    {name: 'servicio', type: 'string'},
                    {name: 'orden', type: 'int'},
                    {name: 'ge_texto', type: 'string'},
                    {name: 'hora_estimada', type: 'string'},
                    {name: 'hora_real', type: 'string'},
                    {name: 'hora_actual', type: 'string'},
                    {name: 'hora_line', type: 'string'},
                    {name: 'secuencia', type: 'string'},
                    {name: 'guia', type: 'string'},
                    {name: 'estado', type: 'string'},
                    {name: 'minutos', type: 'string'},
                    {name: 'cliente', type: 'string'},
                    {name: 'dir_px', type: 'string'},
                    {name: 'dir_py', type: 'string'},
                    {name: 'gps_px', type: 'string'},
                    {name: 'gps_py', type: 'string'},
                    {name: 'stylestatus', type: 'string'},
                    {name: 'iconimage', type: 'string'},
                    {name: 'total_img', type: 'string'}
                ],
                autoLoad:true,
                proxy:{
                    type: 'ajax',
                    url: mapa_gestion.url+'get_scm_track_panel_unidades_carga/',
                    reader:{
                        type: 'json',
                        rootProperty: 'data'
                    }/*,
                    extraParams:{
                        vp_agencia:1,vp_id_placa: 45,vp_fecha: '16/10/2015'
                    }*/
                },
                listeners:{
                    load: function(obj, records, successful, opts){
                    }
                }
            });


            var imageTplPointer = new Ext.XTemplate(
                '<tpl for=".">',
                    '<div id="container_databox_list_{id}" class="databox_list_transport_select {id}" data-id="{id}">',
                        '<div class="databox_list_transport" id="databox_list_transport_select_{id}" onclick="mapa_gestion.renderTableByCar({id_placa});" >',
                            '<div class="container_top_data" >',
                                '{styleactive}',
                                '<div class="databox_hour_begin">',
                                    '<span class="hour_begin_text {none_time_ini}">{hora_ini}</span>',
                                '</div>',
                                '<div class="databox_hour_last">',
                                    '<img class="time_last" data-qtip="{observacion}" src="/images/icon/{icono}.png">',
                                    '<span class="hour_last_finish_text {none_time_ultimo}">{hora_ultimo}</span>',
                                '</div>',
                            '</div>',
                            '<div class="databox_resume_transport" >',
                                '<div class="databox_user_transport"><span class="dbx_user">{chofer}</span></div>',
                                '<div class="databox_resum_content">',
                                    '<div class="databox_aling_r">',
                                        '<div class="databox_barx">',
                                            '<div class="box_img">',
                                                '<img src="/images/icon/delivery_track.png" />',
                                            '</div>',
                                            '<div class="box_dat">',
                                                '<div class="databox_placa_transport" data-id-placa={id_placa}>',
                                                    '<span>{placa}</span>',
                                                '</div>',
                                                '<div class="databox_cell">',
                                                    '<span>{telefono}</span>',
                                                '</div>',
                                            '</div>',
                                            '<div class="databox_battery_transport" style="font-size:5px;margin-top:8px;">',
                                                '<div class="battery_box"><div class="battery_{batery}"></div></div>',
                                                '<div class="battery_text">{bateria}%</div>',
                                            '</div>',
                                        '</div>',
                                        '<div class="databox_progress">',
                                            '<div id="progress">',
                                                '<div id="progress-bar" class="progress-bar-striped active" role="progressbar" aria-valuenow="{progreso}" aria-valuemin="0" aria-valuemax="100" style="width:{progreso}%" >',
                                                  '{progreso}%',
                                                '</div>',
                                            '</div>',
                                        '</div>',
                                        '<div class="databox_summary">',
                                            '<div class="countTransport" data-qtip="Total" >{tot_servicio}</div>',
                                            '<div class="countTransport color_summary_blue" data-qtip="Ejecutado" >{progress}</div>',
                                            '<div class="countTransport color_summary_green" data-qtip="Entregados" >{DL}</div>',
                                            '<div class="countTransport color_summary_gris" data-qtip="Rezagados" >{MOT}</div>',
                                        '</div>',
                                        /*
                                        '<div class="databox_summary_txt">',
                                            '<div class="summary_txt">TSR</div>',
                                            '<div class="summary_txt">TPR</div>',
                                            '<div class="summary_txt">DL</div>',
                                            '<div class="summary_txt">MOT</div>',
                                        '</div>',
                                        */
                                    '</div>',
                                '</div>',
                                '<div class="databox_pie_chart_stl">',
                                    '<div class="databox_pie_chart_{id} container_graphic" style="float:left;">',
                                    '</div>',
                                    '<div class="databox_summary_pie">',
                                        '<div class="countTransport_pie color_summary_au">AU({P_AU}%)</div>',
                                        '<div class="countTransport_pie color_summary_red">RC({P_RC}%)</div>',
                                        '<div class="countTransport_pie color_summary_blue">RZ({P_RZ}%)</div>',
                                    '</div>',
                                    '<div class="databox_summary_pie_bt">',
                                        '<div class="countTransport_pie_bt color_summary_green">DL({P_DL}%)</div>',
                                        '<div class="countTransport_pie_bt color_summary_gris" id="no_border_gris">PN({P_PE}%)</div>',
                                    '</div>',
                                    '<div class="databox_hours_data">',
                                        '<div class="databox_total_hour">',
                                            '<img class="time_total" data-qtip="Total de horas en ruta" src="/images/icon/time_inicio.png">',
                                            '<span class="hour_total_text {none_time_total}">{hora_total}</span>',
                                        '</div>',
                                        '<div class="databox_km">',
                                            '<img class="time_last" data-qtip="Kilometros recorridos" src="/images/icon/km-recorridos.png">',
                                            '<span class="hour_last_finish_text">{total_km} km</span>',
                                        '</div>',
                                    '</div>',
                                '</div>',
                            '</div>',
                        '</div>',
                        '<div class="databox_transport_title_first view_dynamic_table" id="tbar_databox_{id}">',
                            '<span style="width:5%;text-align: center;">#</span>',
                            '<span style="width:10%;text-align: center;"></span>',
                            '<span style="width:26%;text-align: center;">GuÃ­a</span>',
                            '<span style="width:17%;text-align: center;">Programado</span>',
                            '<span style="width:14%;text-align: center;">Ejecutado</span>',
                            '<span style="width:15%;text-align: center;">Estado</span>',
                            '<span style="width:8%;text-align: center;">Fotos</span>',
                            '<span class="close_table" style="text-align: center;" onclick="mapa_gestion.closeTableButton()"><img src="/images/icon/arrow_top_white.png"></span>',
                        '</div>',
                        '<div id="container_api_resumen_{id}"" class="databox_api_resumen view_dynamic_table" class="container_databox" style="clear:both;">',
                            '<div id="databox_api_resumen_{id}" >',
                            '</div>',
                        '</div>',
                    '</div>',
                '</tpl>'
            );

            var imageTplPointerTotal = new Ext.XTemplate(
                '<tpl for=".">',
                    '<div class="databox_list_transport_select_total {id}" >',
                        '<div class="databox_list_transport_total" >',
                            '<div class="databox_resume_transport" >',
                                '<div class="databox_user_transport">',
                                    '<span class="dbx_user total-title-transport">ESTADÃSTICAS TOTALES</span>',
                                    '<span class="dbx_user total-tot-unid">Total unidades: {numberOfCars}</span>',
                                '</div>',
                                '<div class="databox_resum_content data_resum_total">',
                                    '<div class="databox_aling_r align_stat_total">',
                                        '<div class="databox_barx">',
                                            '<div class="box_img">',
                                                '<img/>',
                                            '</div>',
                                            '<div class="box_dat">',
                                                '<div class="databox_placa_transport" data-id-placa={id_placa}>',
                                                    '<span></span>',
                                                '</div>',
                                                '<div class="databox_cell">',
                                                    '<span></span>',
                                                '</div>',
                                            '</div>',
                                            '<div class="databox_battery_transport" style="font-size:5px;margin-top:8px;">',
                                                '<div class="battery_box"><div class=""></div></div>',
                                                '<div class="battery_text"></div>',
                                            '</div>',
                                        '</div>',
                                        '<div class="databox_progress">',
                                            '<div id="progress">',
                                                '<div id="progress-bar" class="progress-bar-striped active" role="progressbar" aria-valuenow="{progreso}" aria-valuemin="0" aria-valuemax="100" style="width:{progreso}%" >',
                                                  '{progreso}%',
                                                '</div>',
                                            '</div>',
                                        '</div>',
                                        '<div class="databox_summary">',
                                            '<div class="countTransport" data-qtip="Total" >{tot_servicio}</div>',
                                            '<div class="countTransport color_summary_blue" data-qtip="Ejecutado" >{progress}</div>',
                                            '<div class="countTransport color_summary_green" data-qtip="Entregados" >{DL}</div>',
                                            '<div class="countTransport color_summary_gris" data-qtip="Rezagados" >{MOT}</div>',
                                        '</div>',
                                        /*
                                        '<div class="databox_summary_txt">',
                                            '<div class="summary_txt">TSR</div>',
                                            '<div class="summary_txt">TPR</div>',
                                            '<div class="summary_txt">DL</div>',
                                            '<div class="summary_txt">MOT</div>',
                                        '</div>',
                                        */
                                    '</div>',
                                '</div>',
                                '<div class="databox_pie_chart_stl container_total_databox">',
                                    '<div class="databox_pie_chart_total_{id} container_graphic" style="float:left;">',
                                    '</div>',
                                    '<div class="databox_summary_pie summary_pie_total">',
                                        '<div class="countTransport_pie color_summary_au">AU({P_AU}%)</div>',
                                        '<div class="countTransport_pie color_summary_red">RC({P_RC}%)</div>',
                                        '<div class="countTransport_pie color_summary_blue">RZ({P_RZ}%)</div>',
                                    '</div>',
                                    '<div class="databox_summary_pie_bt">',
                                        '<div class="countTransport_pie_bt color_summary_green">DL({P_DL}%)</div>',
                                        '<div class="countTransport_pie_bt color_summary_gris" id="no_border_gris">PN({P_PE}%)</div>',
                                    '</div>',
                                '</div>',
                            '</div>',
                            '<div class="data_summary_prom">',
                                '<div>',
                                    '<img class="time_last" data-qtip="Promedio de hora de inicio" src="/images/icon/satellite-car-icon.png">',
                                    '<span class="hour_total_text">{hora_ini}</span>',
                                '</div>',
                                '<div>',
                                    '<img class="time_last" data-qtip="Promedio de los ultimos reportes" src="/images/icon/phone-signal.png">',
                                    '<span class="hour_total_text">{hora_ultimo}</span>',
                                '</div>',
                                '<div>',
                                    '<img class="time_last" data-qtip="Promedio del total de horas de ruta" src="/images/icon/time_inicio.png">',
                                    '<span class="hour_total_text">{hora_total}</span>',
                                '</div>',
                                '<div>',
                                    '<img class="time_last" data-qtip="Promedio de los kilometros recorridos" src="/images/icon/km-recorridos.png">',
                                    '<span class="hour_total_text">{total_km} km</span>',
                                '</div>',
                            '</div>',
                        '</div>',
                    '</div>',
                '</tpl>'
            );

            tab.add({
                id:mapa_gestion.id+'-tab',
                border:false,
                autoScroll:true,
                closable:true,
                layout:{
                    type:'fit'
                },
                tbar:[
                    '-',
                    {
                        xtype:'combo',
                        id:mapa_gestion.id+'-agencia',
                        fieldLabel:'Agencia',
                        labelWidth:50,
                        store:Ext.create('Ext.data.Store',{
                        fields:[
                                {name:'prov_codigo', type:'int'},
                                {name:'prov_nombre', type:'string'}
                        ],
                        proxy:{
                            type:'ajax',
                            url:mapa_gestion.url+'get_usr_sis_provincias/',
                            reader:{
                                type:'json',
                                rootProperty:'data'
                            }
                        }
                        }),
                        queryMode:'local',
                        valueField:'prov_codigo',
                        displayField:'prov_nombre',
                        listConfig:{
                            minWidth:250
                        },
                        width:180,
                        forceSelection:true,
                        allowBlank:false,
                        selecOnFocus:true,
                        emptyText:'[ Seleccione ]',
                        listeners:{
                            afterrender:function(obj,record,options){
                                obj.getStore().load({
                                    params:{linea:3},
                                    callback:function(){
                                        obj.setValue(parseInt('<?php echo PROV_CODIGO;?>'));
                                    }
                                });
                            },
                            select: function(obj, records, opts){
                                mapa_gestion.getReloadTack();
                            }
                        }
                    },'-',
                    {
                        xtype:'datefield',
                        id:mapa_gestion.id+'-fecha',
                        fieldLabel:'Fecha',
                        labelWidth:35,
                        width:150,
                        value:new Date(),
                        listeners:{
                            select: function(obj, records, opts){
                                mapa_gestion.getReloadTack();
                            }
                        }
                    },
                    {
                        xtype:'textfield',
                        id:mapa_gestion.id+'-placa',
                        width:80,
                        listeners:{

                        }
                    },
                    {
                        text:'',
                        id:mapa_gestion.id+'-btn-find',
                        icon: '/images/icon/search.png',
                        tooltip:'Buscar',
                        listeners:{
                            click:function(obj,opts){
                                mapa_gestion.cmp.itemclick = 0;
                                mapa_gestion.getReloadTack();
                            }
                        }
                    },
                    /*{
                        text:'',
                        id:mapa_gestion.id+'-stadistico',
                        icon:'/images/icon/bar-2.png',
                        tooltip:'Estadistico',
                        listeners:{
                            click:function(obj,epts){
                                mapa_gestion.stadistico();
                            }
                        }
                    } */
                ],
                items:[
                    {
                        xtype:'panel',
                        layout:'border',
                        border:false,
                        items:[
                            {
                                region:'west',
                                id: mapa_gestion.id+'-region-west',
                                layout:'fit',
                                border:false,
                                frame:true,
                                width: 450,
                                header:false,
                                split: true,
                                collapsible: true,
                                hideCollapseTool:true,
                                titleCollapse:false,
                                floatable: false,
                                collapseMode : 'mini',
                                animCollapse : true,
                                bbar:[
                                    '',
                                {
                                    xtype: 'dataview',
                                    id: mapa_gestion.id+'-total-score-info',
                                    //layout:'fit',
                                    maxHeight:80,
                                    store: mapa_gestion.store_car,
                                    //autoScroll: true,
                                    //loadMask:true,
                                    cls: 'contenedor-dataview-total',
                                    //autoHeight: true,
                                    tpl: imageTplPointerTotal,
                                    focusable : false,
                                    //multiSelect: false,
                                    //singleSelect: false,
                                    //loadingText:'Cargando Registros...',
                                    //emptyText: '<div class="databox_list_transport_none"><div class="databox_none_data_transport" ></div><div class="databox_title_clear_data_transport">NO EXISTEN REGISTROS</div></div>',
                                    itemSelector: 'div.databox_list_transport_select_total',
                                    disableSelection: true,
                                    //overItemCls: 'databox_list_transport-hover',


                                    //xtype: 'component',
                                    
                                    //layout: 'fit',
                                    //html: '<div class="databox_list_transport_select 0"></div>'
                                    //height: '12%',
                                    
                                    listeners: {
                                        afterrender:function(obj){
                                            /*
                                            var lastobj = document.getElementsByClassName("databox_list_transport_select 0");
                                            Ext.getCmp(mapa_gestion.id+'-total-score-info').setHtml('<div class="databox_list_transport_select 0"></div>');
                                            */
                                        },
                                        viewready:function(){
                                        }
                                        
                                    }
                                } 
                                ],
                                listeners:{
                                    afterrender: function(obj, e){
                                    },
                                
                                },
                                items:[
                                    {
                                        xtype: 'dataview',
                                        id: mapa_gestion.id+'-grid-car',
                                        //layout:'fit',
                                        //maxHeight: '82%',
                                        store: mapa_gestion.store_car,
                                        autoScroll: true,
                                        loadMask:true,
                                        cls: 'contenedor-dataview',
                                        //autoHeight: true,
                                        tpl: imageTplPointer,
                                        multiSelect: false,
                                        singleSelect: false,
                                        loadingText:'Cargando Registros...',
                                        emptyText: '<div class="databox_list_transport_none"><div class="databox_none_data_transport" ></div><div class="databox_title_clear_data_transport">NO EXISTEN REGISTROS</div></div>',
                                        itemSelector: 'div.databox_list_transport_select',
                                        trackOver: true,
                                        overItemCls: 'databox_list_transport-hover',
                                        listeners: {
                                            itemclick: function(view, record, item, idx, event, opts) {
                                                //llama a la table de resumen

                                                //console.log("ENTRO AL ITEMCLICK DE GRID CAR");
                                                //console.log(record);
                                                //mapa_gestion.getRouteTrack(true,record);
                                            },
                                            afterrender:function(obj, eOpts){
                                            }
                                        }
                                    }
                                ]
                            },
                            {
                                region:'center',
                                layout:'fit',
                                border:false,
                                items:[
                                    {
                                        layout:'border',
                                        border:false,
                                        items:[
                                            {
                                                region:'center',
                                                id:mapa_gestion.id+'-center-map',
                                                layout:'fit',
                                                anchor:'100%',
                                                border:false,
                                                html:'<div id="'+mapa_gestion.id+'-map" class="ue-map-canvas"></div>'
                                            },
                                            {
                                                region:'south',
                                                id:mapa_gestion.id+'-north-data-client',
                                                hidden:true,
                                                layout:'fit',
                                                height: 275,
                                                border:false,
                                                header:false,
                                                split: true,
                                                collapsible: true,
                                                hideCollapseTool:true,
                                                titleCollapse:false,
                                                floatable: false,
                                                collapseMode : 'mini',
                                                animCollapse : true,
                                                bbar:[
                                                    '->',
                                                    {
                                                        xtype:'button',
                                                        text: 'Regresar',
                                                        tooltip:'Regresar',
                                                        icon: '/images/icon/get_back.png',
                                                        handler:function(btn) {
                                                            Ext.getCmp(mapa_gestion.id+'-north-data-client').hide();
                                                        }
                                                    }
                                                ],
                                                items:[
                                                    {
                                                        layout:'border',
                                                        border:false,
                                                        items:[
                                                            {
                                                                region:'center',
                                                                id:'-panel-detail',
                                                                layout:'fit',
                                                                border:false,
                                                                autoScroll:true
                                                            }
                                                        ]
                                                    }
                                                ]
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ],
                listeners:{
                    beforerender: function(obj, opts){
                        global.state_item_menu(mapa_gestion.id_menu, true);
                    },
                    afterrender: function(obj, e){
                        tab.setActiveTab(obj);
                        global.state_item_menu_config(obj,mapa_gestion.id_menu);
                    },
                    beforeclose:function(obj,opts){
                        global.state_item_menu(mapa_gestion.id_menu, false);
                        try{
                            mapa_gestion.setClearCmp(mapa_gestion.cmp.cmpAct);
                        }catch(e){}
                    },
                    boxready:function(obj, width, height, eOpts ){
                        mapa_gestion.setIconModule();
                    }
                }
            }).show();
        },
        renderTableByCar:function(id_placa){

            //Aqui vuelvo a setear mi clase que borra la tabla a todos los hijos del contenedor
            //Esta es una funcion recursiva, ya que se llama asi misma una y otra vez, para que lo 
            //entiendas mejor te recomiendo que veas este video: https://www.youtube.com/watch?v=V1pIboUZQF4
            /*
            console.log("averss");
            console.log(mapa_gestion.cmp.numberOfUnitShow);
            console.log(mapa_gestion.cmp.recordsForStoreCar);*/
            var activeMap = 0;
            var recordsSpecialStoreCar = mapa_gestion.cmp.recordsForStoreCar;
            Ext.getCmp(mapa_gestion.id+'-north-data-client').setHidden(true);
            var databox_list_transport_select = document.getElementsByClassName("databox_list_transport_select");
            var tbar_databox = document.getElementsByClassName("databox_transport_title_first");


            if (mapa_gestion.cmp.clickBtnUpdateGMaps == 1){

                activeMap = 1;
                if (activeMap == 1){
                    mapa_gestion.getRouteTrack(true,mapa_gestion.cmp.objectClicked);
                };
                //activo1
                //llama a la table de resumen
                mapa_gestion.setApiComponentResume(id_placa);
                mapa_gestion.cmp.clickBtnUpdateGMaps = 0;

            }else if(mapa_gestion.cmp.clickBtnUpdateGMaps == 0){
                mapa_gestion.cmp.idPlacaClick = id_placa;

                for (var i = 0; i < mapa_gestion.cmp.numberOfUnitShow; i++) {
                    var databox_api_resumen_box = databox_list_transport_select[i].children[2];
                    databox_api_resumen_box.classList.add('view_dynamic_table');
                    tbar_databox[i].classList.add('view_dynamic_table');
                    if (recordsSpecialStoreCar[i].id == id_placa ){
                        activeMap = 1;
                        mapa_gestion.cmp.objectClicked = recordsSpecialStoreCar[i];
                    };
                };

                var elementClicked = mapa_gestion.setPositionItemAfterClick(id_placa);
                var container_api_resumen = document.getElementById("container_api_resumen_"+id_placa);
                var tbar_databox_by_Id = document.getElementById("tbar_databox_"+id_placa);

                container_api_resumen.classList.remove('view_dynamic_table');
                tbar_databox_by_Id.classList.remove('view_dynamic_table');

                if (activeMap == 1){
                    mapa_gestion.getRouteTrack(true,mapa_gestion.cmp.objectClicked);
                };
                //activo1
                //llama a la table de resumen
                mapa_gestion.setApiComponentResume(id_placa);

            }

        },
        setPositionItemAfterClick:function(id_placa){
        
        var elementClickedById = document.getElementById("container_databox_list_"+id_placa);
        
        // Obtenemos la referencia del elemento al cual queremos insertar el nuevo nodo 
        var parentContainerBox = document.getElementById("mapa_gestion-grid-car");
        var firstChildForParent = parentContainerBox.children[0];
        // Insertamos el nuevo elemento antÃ©s del primer hijo
        parentContainerBox.insertBefore(elementClickedById, firstChildForParent);

        return elementClickedById;
        },
        setApiComponentResume:function(id_placa){
            //mapa_gestion.cmp.cmpAct=record.get('id');
            var vp_agencia = Ext.getCmp(mapa_gestion.id+'-agencia').getValue();
            var vp_fecha = Ext.getCmp(mapa_gestion.id+'-fecha').getRawValue();

            var mask = new Ext.LoadMask(Ext.getCmp(mapa_gestion.id+"-grid-car"),{
            msg:'Actualizando Datos....'
            });
            mask.show();
            

            Ext.create('Ext.view.View', {
                id: mapa_gestion.id+"_cmp_content_table",
                store: Ext.create('Ext.data.Store',{
                    fields: [
                        {name: 'id', type: 'int'},
                        {name: 'id_guia', type: 'int'},
                        {name: 'servicio', type: 'string'},
                        {name: 'orden', type: 'int'},
                        {name: 'ge_texto', type: 'string'},
                        {name: 'hora_estimada', type: 'string'},
                        {name: 'hora_real', type: 'string'},
                        {name: 'hora_actual', type: 'string'},
                        {name: 'hora_line', type: 'string'},
                        {name: 'secuencia', type: 'string'},
                        {name: 'guia', type: 'string'},
                        {name: 'estado', type: 'string'},
                        {name: 'minutos', type: 'string'},
                        {name: 'cliente', type: 'string'},
                        {name: 'dir_px', type: 'string'},
                        {name: 'dir_py', type: 'string'},
                        {name: 'gps_px', type: 'string'},
                        {name: 'gps_py', type: 'string'},
                        {name: 'stylestatus', type: 'string'},
                        {name: 'iconimage', type: 'string'},
                        {name: 'total_img', type: 'string'},
                        {name: 'tot_fotos', type: 'int'}
                        /*
                        {name: 'hide_img', type: 'string'},
                        {name: 'txt_css_icon', type: 'string'},
                        {shi_icono}*/
                    ],
                    autoLoad:true,
                    proxy:{
                        type: 'ajax',
                        url: mapa_gestion.url+'get_scm_track_panel_unidades_carga/',
                        reader:{
                            type: 'json',
                            root:'data'
                        }
                    }
                }),
                //store: mapa_gestion.store_table_guias,
                layout:'fit',
                height:"100%",
                width: "100%",
                anchor:"100%",
                autoScroll: true,
                loadMask:false,
                autoHeight: false,
                bodyCls: 'content-body',
                focusCls : 'databox_list_trpt_select_focus',
                multiSelect: false,
                singleSelect: false,
                loadingText:'Cargando datos...',
                emptyText: '<div class="databox_list_transport"><div class="databox_none_data" ></div><div class="databox_title_clear_data">NO EXISTE REGISTRO</div></div>',
                plugins: {
                    xclass: 'Ext.ux.DataView.Animated'
                },
                itemSelector: 'div.dataview-multisort-item',
                trackOver: true,
                overItemCls: 'databox_list_transport-hover',
                tpl: [
                    '<div id="databox_list_container" >',
                        '<tpl for=".">',
                            '<div class="databox_list_trpt_select back_by_type_{servicio}" id="container_select_{id_guia}" data-servicio="{servicio}" onclick="mapa_gestion.getDataClientEvent('+id_placa+',\'{hora_line}\',{id_guia},{guia},\'{ge_texto}\',\'{dir_px}\',\'{dir_py}\',\'{gps_px}\',\'{gps_py}\',\'{servicio}\',\'{txt_remite}\');">',
                                '<tpl if="nivel!=0">',//hover_title_second tiene que ir eso en la clase agregar dinamicamente
                                    '<div class="databox_transport_title_second" id="container_title_second_{id_guia}">',
                                        '<span style="width:5%;text-align: center;">{orden}</span>',
                                        '<span data-qtip="{shi_nombre}" class="shipper-class-icon" style="width:10%;text-align: center;">',
                                            '<img class="{hide_img}" src="/images/icon/{shi_icono}">',
                                            '<span class="txt_base {style_like_google} {txt_css_icon}">',
                                                '{txt_css_icon}',
                                            '</span>',
                                        '</span>',
                                        '<span style="width:26%;text-align: left;" data-qtip="{cliente}" >{ge_texto} {pago}</span>',
                                        '<span style="width:17%;text-align: center;">{hora_estimada}</span>',
                                        '<span style="width:14%;text-align: center;">{hora_real}</span>',                                                    
                                        '<span style="width:15%;text-align: center;" class="estado_icon" data-estado="{estado}" data-guia="{guia}"><img src="/images/icon/{iconimage}" data-qtip="{estado}"></span>',
                                        '<span class="container_camera_icon" style="width:8%;text-align: center;"><div class="back_camera_icon"><span class="number_of_photos">{tot_fotos}</span></div></span>',
                                        '<span style="text-align: center;"></span>',
                                    '</div>',
                                '</tpl>',
                            '</div>',
                            
                        '</tpl>',
                    '</div>'
                ],
                listeners:{
                    afterrender: function(obj, e){
                    }
                },
                renderTo: 'databox_api_resumen_'+id_placa
            });
            
            Ext.getCmp(mapa_gestion.id+"_cmp_content_table").getStore().load({
                params:{
                    vp_agencia:vp_agencia,vp_id_placa:id_placa,vp_fecha:vp_fecha
                },
                callback:function(){
                    mapa_gestion.getPointDelivery();
                    mask.hide();
                }
            });
        },
        setLoadDinamicComponent:function(){
            //setTimeout( mapa_gestion.setHeightForDataviewCar() , 1000 );
            //mapa_gestion.setHeightForDataviewCar();
            mapa_gestion.store_car.each(function(record){
                try{
                    mapa_gestion.setPieCharts(record);
                    mapa_gestion.setPieChartsTotal(record);
                }catch(e){}
            });
            mapa_gestion.getTotalScore();
        },
        getTotalScore:function(){
            var arrayObj = document.getElementsByClassName("databox_list_transport_select_total");
            var arrayOriginal = document.getElementsByClassName("databox_list_transport_select");

            mapa_gestion.cmp.numberOfUnitShow = parseInt(arrayOriginal.length);

            for (var i = 0; i < arrayObj.length; i++) {
                var dataId = parseInt( arrayOriginal[i].getAttribute("data-id") );
                if (dataId == 0){
                    arrayOriginal[arrayOriginal.length-1].style.display = "none";
                }
                if ( dataId != 0 ){
                    arrayObj[i].style.display = "none";
                };

            };       

        },
        setClearCmp:function(id){
            try{
                if(Ext.getCmp(mapa_gestion.id+"_cmp_content_"+id))Ext.getCmp(mapa_gestion.id+"_cmp_content_"+id).destroy();
                Ext.get("databox_api_resumen_"+id).update('');
            }catch(e){}
        },
        setApiComponentResumeTwo:function(record){

            mapa_gestion.cmp.cmpAct=record.get('id');
        },
        setHeightForDataviewCar:function(){
        var heightOfTotalScoreContainer = 90;
        var extHeightContainer = Ext.getCmp(mapa_gestion.id+"-region-west").getHeight();
        var CalculateHeightForTotaScore = extHeightContainer - heightOfTotalScoreContainer;
        var text = CalculateHeightForTotaScore + "px";
        document.getElementById("mapa_gestion-grid-car").style.height = text;
        },
        closeTableButton:function(){
            mapa_gestion.cmp.itemclick = 0;
            mapa_gestion.cmp.actualID = [];
            mapa_gestion.setDestroyCmp();
            mapa_gestion.getReloadTack();
        },
        setPieChartsTotal:function(record){
                try{    
                    document.getElementsByClassName("databox_pie_chart_total_"+record.get('id')).innerHTML = "";
                }catch(e){}
                var width = 60,height = 50,offset = 10,radius = Math.min(width, height) / 2;
                var color = d3.scale.ordinal().range(["#16a765", "#9F9F9F","#65A6BF", "#9AC4D5", "#CCE2EA"]);
                var arc = d3.svg.arc().outerRadius(radius - 5).innerRadius(radius - 17);
                // second arc for labels
                var arc2 = d3.svg.arc().outerRadius(radius).innerRadius(radius + 10);
                var pie = d3.layout.pie().sort(null).startAngle(1.1*Math.PI).endAngle(3.1*Math.PI).value(function(d) { return d.songs; });

                var tot_servicio=parseFloat(record.get('tot_servicio'));
                var tot_dl=parseFloat(record.get('tot_dl'));
                var tot_ausente=parseFloat(record.get('tot_ausente'));
                var tot_rechazo=parseFloat(record.get('tot_rechazo'));
                var rezagos=parseFloat(record.get('rezagos'));
                var pendientes=parseFloat(record.get('pendientes'));

                var por_dl = Math.ceil(((tot_dl*100)/tot_servicio)* 100)/100;
                var por_ausente = Math.ceil(((tot_ausente*100)/tot_servicio)* 100)/100;
                var por_rechazo = Math.ceil(((tot_rechazo*100)/tot_servicio)* 100)/100;
                var por_rezagos = Math.ceil(((rezagos*100)/tot_servicio)* 100)/100;
                var por_pendientes = Math.ceil(((pendientes*100)/tot_servicio)* 100)/100;

                var data = [
                  {genre: '', songs: por_dl,i:0},
                  {genre: '', songs: por_ausente,i:1},
                  {genre: '', songs: por_rechazo,i:2},
                  {genre: '', songs: por_rezagos,i:3},
                  {genre: '', songs: por_pendientes,i:4}
                ];

                var svg = d3.select(".databox_pie_chart_total_"+record.get('id')).append("svg")
                    .attr("class", "chart_"+record.get('id'))
                    .attr("width", width + offset)
                    .attr("height", height + offset)
                    .attr("style", "float:left;")
                    .attr('perserveAspectRatio', 'xMinYMid')
                  .append("g")
                    .attr("transform", "translate(" + (width+offset) / 2 + "," + (height + offset) / 2 + ")");

                  data.forEach(function(d) {
                    d.songs = +d.songs;
                  });

                  var g = svg.selectAll(".arc")
                      .data(pie(data))
                    .enter().append("g")
                      .attr("class", "arc");

                  g.append("path")
                      .style("fill", function(d) { return mapa_gestion.getColorPieChart(d.data.i) })//color(d.data.genre);
                      .transition().delay(function(d, i) { return i * 500; }).duration(500)
                      .attrTween('d', function(d) {
                         var i = d3.interpolate(d.startAngle+0.1, d.endAngle);
                         return function(t) {
                           d.endAngle = i(t);
                           return arc(d);
                         };
                      });

                  g.append("text")
                      .attr("transform", function(d) { return "translate(" + arc2.centroid(d) + ")"; })
                      .attr("dy", ".35em")
                      .attr("class", "d3-label")
                      .style("text-anchor", "middle")
                      .text(function(d) { return d.data.genre; });
        },
        setPieCharts:function(record){
                try{    
                    document.getElementsByClassName("databox_pie_chart_"+record.get('id')).innerHTML = "";
                }catch(e){}
                var width = 60,height = 50,offset = 10,radius = Math.min(width, height) / 2;
                var color = d3.scale.ordinal().range(["#16a765", "#9F9F9F","#65A6BF", "#9AC4D5", "#CCE2EA"]);
                var arc = d3.svg.arc().outerRadius(radius - 5).innerRadius(radius - 17);
                // second arc for labels
                var arc2 = d3.svg.arc().outerRadius(radius).innerRadius(radius + 10);
                var pie = d3.layout.pie().sort(null).startAngle(1.1*Math.PI).endAngle(3.1*Math.PI).value(function(d) { return d.songs; });

                var tot_servicio=parseFloat(record.get('tot_servicio'));
                var tot_dl=parseFloat(record.get('tot_dl'));
                var tot_ausente=parseFloat(record.get('tot_ausente'));
                var tot_rechazo=parseFloat(record.get('tot_rechazo'));
                var rezagos=parseFloat(record.get('rezagos'));
                var pendientes=parseFloat(record.get('pendientes'));

                var por_dl = Math.ceil(((tot_dl*100)/tot_servicio)* 100)/100;
                var por_ausente = Math.ceil(((tot_ausente*100)/tot_servicio)* 100)/100;
                var por_rechazo = Math.ceil(((tot_rechazo*100)/tot_servicio)* 100)/100;
                var por_rezagos = Math.ceil(((rezagos*100)/tot_servicio)* 100)/100;
                var por_pendientes = Math.ceil(((pendientes*100)/tot_servicio)* 100)/100;

                var data = [
                  {genre: '', songs: por_dl,i:0},
                  {genre: '', songs: por_ausente,i:1},
                  {genre: '', songs: por_rechazo,i:2},
                  {genre: '', songs: por_rezagos,i:3},
                  {genre: '', songs: por_pendientes,i:4}
                ];

                var svg = d3.select(".databox_pie_chart_"+record.get('id')).append("svg")
                    .attr("class", "chart_"+record.get('id'))
                    .attr("width", width + offset)
                    .attr("height", height + offset)
                    .attr("style", "float:left;")
                    .attr('perserveAspectRatio', 'xMinYMid')
                  .append("g")
                    .attr("transform", "translate(" + (width+offset) / 2 + "," + (height + offset) / 2 + ")");

                  data.forEach(function(d) {
                    d.songs = +d.songs;
                  });

                  var g = svg.selectAll(".arc")
                      .data(pie(data))
                    .enter().append("g")
                      .attr("class", "arc");

                  g.append("path")
                      .style("fill", function(d) { return mapa_gestion.getColorPieChart(d.data.i) })//color(d.data.genre);
                      .transition().delay(function(d, i) { return i * 500; }).duration(500)
                      .attrTween('d', function(d) {
                         var i = d3.interpolate(d.startAngle+0.1, d.endAngle);
                         return function(t) {
                           d.endAngle = i(t);
                           return arc(d);
                         };
                      });

                  g.append("text")
                      .attr("transform", function(d) { return "translate(" + arc2.centroid(d) + ")"; })
                      .attr("dy", ".35em")
                      .attr("class", "d3-label")
                      .style("text-anchor", "middle")
                      .text(function(d) { return d.data.genre; });
        },
        getColorPieChart:function(i){
            switch(i){
                case 0://dl
                    color="#16a765";
                break;
                case 1://ausente
                    color="#000000";
                break;
                case 2://rechazo
                    color="#FA5858";
                break;
                case 3://rezagos
                    color="#337ab7";
                break;
                case 4://pendientes
                    color="#B5B5B5";
                break;
            }
            return color;
        },
        setTimeLineTransport:function(record){
            mapa_gestion.setMap({lat:parseFloat(res.data[res.data.length-1].rut_px),lng:parseFloat(res.data[res.data.length-1].rut_py),zoom:16});
        },
        setDetailForm:function(record,servicio,txt_remite){
            var data;
            if(record==null){
                data={
                    guia:'',
                    id_track:'',
                    remitente:'',
                    remite_direc:'',
                    servicio:'',
                    piezas:'',
                    peso_aprox:'',
                    peso:'',
                    valor:'',
                    destinatario:'',
                    direccion:'',
                    localidad:'',
                    referenc:'',
                    dir_px:'',
                    dir_py:'',
                    telefonos:'',
                    e_mail:'',
                    contenido:''
                };
            }else{
                Ext.getCmp(mapa_gestion.id+'-north-data-client').show();
                data=record.data[0];
                mapa_gestion.cmp.dataForMarker = record.data[0];
            }

            var imageTplPointerPanel = new Ext.XTemplate(
                '<tpl for=".">',
                    '<div class="main-container" >',
                        '<div class="top-container" >',
                            '<div class="main-remite-container" >',
                                '<div class="remite-container" >',
                                    '<div class="label-remite back_tipo_'+servicio+'">'+txt_remite+'</div>',
                                    '<div class="info-remite" >'+data.remitente+'</div>',
                                '</div>',
                                '<div class="direccion-remite" >'+data.remite_direc+'</div>',          
                            '</div>',
                            '<div class="main-descripcion-container" >',//contenedor principal de descripcion
                                '<div class="ge-container" >',
                                    '<div class="label-ge back_tipo_'+servicio+'" >',
                                    '<p class="no-pad-mar">','G.E: '+data.guia+'</p>',//G.E de la guia
                                    '</div>',  
                                    '<div class="info-ge" >',
                                        '<p class="no-pad-mar">','Track: '+data.id_track+'</p>',//Track id
                                    '</div>',                              
                                '</div>',/*
                                 '<div class="buttons-container" >',
                                    '<div class="label-description-buttons">',
                                        '<button class="cls-button">opcion 1</button>',
                                    '</div>',
                                    '<div class="label-description-buttons">',
                                        '<button class="cls-button">opcion 2</button>',
                                    '</div>',
                                    '<div class="label-description-buttons">',
                                        '<button class="cls-button">opcion 3</button>',
                                    '</div>',
                                    '<div class="label-description-buttons">',
                                        '<button class="cls-button">opcion 4</button>',
                                    '</div>',
                                '</div>',*/                            
                                '<div class="items-label-description" >',
                                    '<div class="label-description back_tipo_'+servicio+'" >Piezas</div>',
                                    '<div class="label-description back_tipo_'+servicio+'" >P. Estimado</div>',
                                    '<div class="label-description back_tipo_'+servicio+'" >R. Validado</div>',
                                    '<div class="label-description back_tipo_'+servicio+'" >Valor</div>',  
                                '</div>',
                                '<div class="data-description-container" >',
                                    '<div class="data-description" >'+data.piezas+'</div>',
                                    '<div class="data-description" >'+data.peso_aprox+'</div>',
                                    '<div class="data-description" >'+data.peso+'</div>',
                                    '<div class="data-description" >'+data.valor+'</div>',
                                '</div>',             
                            '</div>',
                        '</div>', 
                        '<div class="center-container" >',
                            '<div class="destinatario-container" >',
                                '<div class="label-destinatario back_tipo_'+servicio+'" >Destinatario</div>',
                                '<div class="info-destinatario" >'+data.destinatario+'</div>', 
                            '</div>',
                            '<div class="full-info-container" >',
                                '<div class="main-info-direccion" >',
                                    '<p class="info-direccion"><b>DirecciÃ³n: </b>'+data.direccion+'</p><p class="info-localidad"><b>Distrito: </b>'+data.localidad+'</p>',//direccion
                                '</div>',
                                '<p class="float-left width-100"><b>Referencia: </b>'+data.referenc+'</p>',//referencia

                                '<div class="main-info-telef-mail" >',
                                    '<p class="float-left"><img class="icon-padding" src="/images/icon/telephone_bg.png">'+data.telefonos+'</p>',//telefono esto es float left
                                    '<p class="float-left"><img class="icon-padding" id="email-pointer" src="/images/icon/email_pointer.png">'+data.e_mail+'</p>',//correo esto es float left
                                '</div>',
                                '<p class="float-left width-100"><b>Producto: </b>'+data.contenido+'</p>',//contenido o producto
                            '</div>',                          
                        '</div>',
                        '<div class="bottom-container" >',
                            '<div id="GaleryFull" class="links"></div>',                        
                        '</div>',
                    '</div>',
                '</tpl>'
            );
            Ext.getCmp('-panel-detail').setHtml(imageTplPointerPanel);
        },
        setMarkerInsideGMapByGuide:function(dir_px,dir_py,gps_px,gps_py,response){

            mapa_gestion.setClearPositionMarker();//CHEQUEAR ESTOO
            var coordenadaDesdeBaseDeDatosPx = parseFloat(dir_px);
            var coordenadaDesdeBaseDeDatosPy = parseFloat(dir_py);
            var coordenadaDesdeIridioPx = parseFloat(gps_px);
            var coordenadaDesdeIridioPy = parseFloat(gps_py);
            var data=response.data[0];

            /*
            console.log(data);
            console.log("desde Iridio PX: "+coordenadaDesdeIridioPx);
            console.log("desde Iridio PY: "+coordenadaDesdeIridioPy);
            console.log("desde Base de datos PX: "+coordenadaDesdeBaseDeDatosPx);
            console.log("desde Base de datos PY: "+coordenadaDesdeBaseDeDatosPy);
            */

            if (coordenadaDesdeIridioPx!=0 || coordenadaDesdeIridioPy!=0){

                var coordinateForGuide = new google.maps.LatLng(coordenadaDesdeIridioPx,coordenadaDesdeIridioPy);

                mapa_gestion.mapa.directionsDisplay.setMap(mapa_gestion.mapa.map);
                var new_position = coordinateForGuide;
                mapa_gestion.mapa.map.setCenter(new_position);
                mapa_gestion.mapa.map.setZoom(15);

                mapa_gestion.setMarker({dir_px:coordenadaDesdeIridioPx,dir_py:coordenadaDesdeIridioPy,logo:"",nombre:"<b>"+data.remitente+"</b>"+" <br>"+data.destinatario+" <br>"+data.direccion+" "+data.localidad,tipo_marker:'P',id_marker:0,tipo_setMarker:1});
            }else{
                if (coordenadaDesdeIridioPx==0 && coordenadaDesdeIridioPy==0 && coordenadaDesdeBaseDeDatosPx==0 && coordenadaDesdeBaseDeDatosPy==0){

                    
                    Command: toastr["error"]("No existen coordenadas de Georeferencia")

                    toastr.options = {
                      "closeButton": true,
                      "debug": false,
                      "newestOnTop": true,
                      "progressBar": false,
                      "positionClass": "toast-bottom-right",
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "200",
                      "hideDuration": "1000",
                      "timeOut": "1500",
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut"
                    }
                    
                }

                if (coordenadaDesdeBaseDeDatosPx!=0 || coordenadaDesdeBaseDeDatosPy!=0){

                    var coordinateForGuide = new google.maps.LatLng(coordenadaDesdeBaseDeDatosPx,coordenadaDesdeBaseDeDatosPy);

                    mapa_gestion.mapa.directionsDisplay.setMap(mapa_gestion.mapa.map);
                    var new_position = coordinateForGuide;
                    mapa_gestion.mapa.map.setCenter(new_position);
                    mapa_gestion.mapa.map.setZoom(15);

                    mapa_gestion.setMarker({dir_px:coordenadaDesdeBaseDeDatosPx,dir_py:coordenadaDesdeBaseDeDatosPy,logo:"",nombre:"<b>"+data.remitente+"</b>"+" <br>"+data.destinatario+" <br>"+data.direccion+" "+data.localidad,tipo_marker:'P',id_marker:0,tipo_setMarker:1});

                }
                
            }

            //var data_marker = mapa_gestion.cmp.dataForMarker;



            /*
                for (var i = 0; i < pickup_service.mapa.getMarker.length; i++) {
                        pickup_service.mapa.getInfoWindowsMapeo[i].close();
                    if (parseInt(pickup_service.mapa.getMarker[i].id) == id_recojo && parseInt(pickup_service.mapa.getInfoWindowsMapeo[i].id) == id_recojo ){
                        (pickup_service.mapa.getMarker[i]).setAnimation(google.maps.Animation.BOUNCE);
                        pickup_service.mapa.getInfoWindowsMapeo[i].open(pickup_service.mapa.map, pickup_service.mapa.getMarker[i]);
                        /*
                        google.maps.event.addListener(pickup_service.mapa.getInfoWindowsMapeo[i], 'domready', function(){
                            $(".gm-style-iw").next("div").hide();
                        });
                        *//*
                    };
                 }   
            */
            /*
            pickup_service.mapa.directionsDisplay.setMap(pickup_service.mapa.map);
            var new_position = new google.maps.LatLng(posx,posy);
            pickup_service.mapa.map.setCenter(new_position);
            pickup_service.mapa.map.setZoom(15);
            */

            //mapa_gestion.setPositionGoogleMaps({lat:objrec[i].co_px,lng:objrec[i].co_py,zoom:17});

            /*
            validateTable = document.getElementById("databox_list_container");
            if (validateTable != null){
                var guia = mapa_gestion.cmp.getGuia;  
                var val = 0;
                var default_lat = -12.047926902770996;
                var default_lng = -77.0811996459961;
                var default_zoom = 13;

                var objrec = mapa_gestion.cmp.dataForSetMap;
                if(!(guia=='' || guia==0 || guia==null)){
                    for (var i = 0; i < objrec.length; i++) {
                        if ( (guia ) == (objrec[i].ge) ){
                            val = 1;
                            mapa_gestion.setPositionGoogleMaps({lat:objrec[i].co_px,lng:objrec[i].co_py,zoom:17});
                            break;
                        }
                    };
                    if(val==0){
                        global.Msg({
                            msg:"No existen coordenadas",
                            icon:0,
                            buttosn:1,
                            fn:function(btn){
                            }
                        });
                    };
                }
                mapa_gestion.cmp.dataForSetMap = [];
                mapa_gestion.cmp.getGuia = '';
               
            };
             */
       
        },
        trafic:function(){
            if (mapa_gestion.cmp.trafic){
                mapa_gestion.mapa.trafficLayer.setMap(mapa_gestion.mapa.map);
                try{
                    Ext.getCmp(mapa_gestion.id+'btn-trafic').setStyle('background','#D2D2D2');
                }catch(e){}
            }else{
                mapa_gestion.mapa.trafficLayer.setMap(null);
                Ext.getCmp(mapa_gestion.id+'btn-trafic').setStyle('background','');
            }

        },
        setIconModule:function(){
            var controlModule = document.createElement('div');
            controlModule.className = 'contetDivTransport';
            controlModule.innerHTML = '<div id="contetDivTransportA"><span></span></div><div id="contetDivTransportC"><span></span></div><div id="contetDivTransportB"><span></span></div>';
            document.getElementById(mapa_gestion.id+'-tab').appendChild(controlModule);
            mapa_gestion.setButtonModule();
        },
        setButtonModule:function(){
            try{
                Ext.getCmp(mapa_gestion.id+'btn-trafic').destroy();
            }catch(err){
            }
            Ext.create('Ext.button.Button', {
                id:mapa_gestion.id+'btn-trafic',
                text: '<div style="font-size:20px;font-weight: bold;color:#fff" height="35"><img src="/images/icon/trafic_transt.png"></div>',
                cls:'DivTrackTrafic',
                //icon: '/images/icon/refresh-24.png',
                renderTo: 'contetDivTransportC',
                handler : function(btn) {
                    mapa_gestion.cmp.trafic=!mapa_gestion.cmp.trafic;
                    mapa_gestion.trafic();
                }
            });
            try{
                Ext.getCmp(mapa_gestion.id+'btn-fullscreen').destroy();
            }catch(err){
                //console.log(err);
            }
            Ext.create('Ext.button.Button', {
                id:mapa_gestion.id+'btn-fullscreen',
                text: '<div style="font-size:20px;font-weight: bold;color:#fff" height="35"><img src="/images/icon/arrows-out-16.png"></div>',
                cls:'DivTrackFullScreen',
                //icon: '/images/icon/refresh-24.png',
                renderTo: 'contetDivTransportA',
                handler : function(btn) {
                    mapa_gestion.setFullScreen(!mapa_gestion.cmp.reload,btn);
                    if(!mapa_gestion.getRouteTrack(false,null))mapa_gestion.getReloadTack();
                }
            });
            try{
                Ext.getCmp(mapa_gestion.id+'btn-reload').destroy();
            }catch(err){
                //console.log(err);
            }
            Ext.create('Ext.button.Button', {
                id:mapa_gestion.id+'btn-reload',
                text: '<div style="font-size:20px;font-weight: bold;color:#fff" height="35"><img src="/images/icon/refresh-24.png"></div>',
                cls:'DivTrackReload',
                //icon: '/images/icon/refresh-24.png',
                renderTo: 'contetDivTransportB',
                handler : function(btn) {
                    mapa_gestion.cmp.clickBtnUpdateGMaps = 1;
                    var idPlacaClick = mapa_gestion.cmp.idPlacaClick;
                    mapa_gestion.renderTableByCar(idPlacaClick);//activo1
                    //mapa_gestion.getRouteTrack(false,null);
                }
            });
        },
        setFullScreen:function(hidden,btn){
            mapa_gestion.cmp.reload=hidden;
            btn.setText('<div style="font-size:20px;font-weight: bold;color:#fff" height="35"><img src="/images/icon/'+((hidden)?'arrows-in-16.png':'arrows-out-16.png')+'"></div>');
            Ext.getCmp(mapa_gestion.id+'-north-data-client').setHidden(true);
            Ext.getCmp(mapa_gestion.id+'-region-west').setHidden(hidden);
        },
        drawVisualization:function(){
            /*
            mapa_gestion.cmp.lineStore=[];

            mapa_gestion.store_car.each(function(record){
                console.log("record del store car");
                console.log(record);
                
                mapa_gestion.cmp.lineStore[record.get('id')] = Ext.create('Ext.data.Store',{
                    fields: [
                        {name: 'id', type: 'int'},
                        {name: 'id_guia', type: 'int'},
                        {name: 'servicio', type: 'string'},
                        {name: 'orden', type: 'int'},
                        {name: 'ge_texto', type: 'string'},
                        {name: 'hora_estimada', type: 'string'},
                        {name: 'hora_real', type: 'string'},
                        {name: 'hora_actual', type: 'string'},
                        {name: 'hora_line', type: 'string'},
                        {name: 'secuencia', type: 'string'},
                        {name: 'guia', type: 'string'},
                        {name: 'estado', type: 'string'},
                        {name: 'minutos', type: 'string'},
                        {name: 'cliente', type: 'string'},
                        {name: 'dir_px', type: 'string'},
                        {name: 'dir_py', type: 'string'},
                        {name: 'gps_px', type: 'string'},
                        {name: 'gps_py', type: 'string'},
                        {name: 'stylestatus', type: 'string'},
                        {name: 'iconimage', type: 'string'},
                        {name: 'total_img', type: 'string'}
                    ],
                    autoLoad:true,
                    proxy:{
                        type: 'ajax',
                        url: mapa_gestion.url+'get_scm_track_panel_unidades_carga/',
                        reader:{
                            type: 'json',
                            rootProperty: 'data'
                        },
                        extraParams:{
                            vp_agencia:record.get('agencia'),vp_id_placa: record.get('id_placa'),vp_fecha:record.get('fecha')
                        }
                    },
                    listeners:{
                        load: function(obj, records, successful, opts){
                            console.log("este es el record del LINESTORE");
                            console.log(records);
                            console.log("==============================");
                        }
                    }
                });
            
            });
            */
        },
        getLineTime:function(hora_ultimo,obj,record){
                // Create and populate a data table.
                mapa_gestion.cmp.dataTimeLine[record.get("id")] = new google.visualization.DataTable();
                mapa_gestion.cmp.dataTimeLine[record.get("id")].addColumn('datetime', 'start');
                mapa_gestion.cmp.dataTimeLine[record.get("id")].addColumn('datetime', 'end');
                mapa_gestion.cmp.dataTimeLine[record.get("id")].addColumn('string', 'content');
                mapa_gestion.cmp.dataTimeLine[record.get("id")].addColumn('string', 'className');
                var line = new Array();
                if(hora_ultimo!=null || hora_ultimo!=''){
                    var h=hora_ultimo.split(':');
                    line.push([new Date(2015,6,20,h[0],h[1],0), , '<img src="/images/icon/car-16.png">','']);
                }
                obj.each(function(record_t){
                    if(parseInt(record_t.get('nivel'))!=0){
                        if(record_t.data.hora_line!=null || record_t.data.hora_line!=''){
                            var h=record_t.data.hora_line.split(':');
                            line.push([new Date(2015,6,20,h[0],h[1],0), , '<img src="/images/icon/ER_'+record_t.data.servicio+'.png">',record_t.get("stylestatus")]);
                        }
                    }
                });
                mapa_gestion.cmp.dataTimeLine[record.get("id")].addRows(line);

                // specify options
                var options = {
                    "width":  "100%",
                    "height": "80px",
                    "style": "box",
                    "min":new Date(2015,5,20),
                    "max":new Date(2015,7,20),
                    "showCurrentTime":"true",
                    "showCustomTime":"true",
                    "locale":"ES",
                    /*"scale":5,
                    "step":5,*/
                    axisOnTop: false,
                    eventMargin: 5,  // minimal margin between events
                    eventMarginAxis: 5, // minimal margin beteen events and the axis
                    editable: false,
                    showNavigation: false,
                    showMajorLabels: false,
                    groupsChangeable : false
                    //groupsOnRight: false
                };

                // Instantiate our timeline object.
                mapa_gestion.cmp.timeline[record.get("id")] = new links.Timeline(document.getElementById('my-timeline_'+record.get("id")), options);
                //google.visualization.events.addListener(mapa_gestion.cmp.timeline[record.get("id")], 'rangechange', mapa_gestion.setOnRangeChange);
                eval("mapa_gestion.getOnSelect_"+record.get("id")+"=function(){var row = mapa_gestion.getSelectedRow("+record.get("id")+");if(row != undefined)mapa_gestion.getResolvetEvent("+record.get("id")+",row,mapa_gestion.cmp.dataTimeLine["+record.get("id")+"].getValue(row, 2));}");

                google.visualization.events.addListener(mapa_gestion.cmp.timeline[record.get("id")], 'select', eval("mapa_gestion.getOnSelect_"+record.get("id")));
                // Draw our timeline with the created data and options
                mapa_gestion.cmp.timeline[record.get("id")].draw(mapa_gestion.cmp.dataTimeLine[record.get("id")]);
        },
        getOnSelect:function(id){
            var row = mapa_gestion.getSelectedRow(id);
            if (row != undefined) {
                mapa_gestion.getResolvetEvent(id,row,mapa_gestion.cmp.dataTimeLine[id].getValue(row, 2));
            }
        },
        getSelectedRow:function(id) {
            var row = undefined;
            var sel = mapa_gestion.cmp.timeline[id].getSelection();
            if (sel.length) {
                if (sel[0].row != undefined) {
                    row = sel[0].row;
                }
            }
            return row;
        },
        getResolvetEvent:function(id,row,data){
            mapa_gestion.getDataClient( mapa_gestion.cmp.lineStore[id].getAt(row+1).data.id_guia , mapa_gestion.cmp.lineStore[id].getAt(row+1).data.guia); 
        },
        getDataClientEvent:function(id,hora,id_guia,guia,ge_texto,dir_px,dir_py,gps_px,gps_py,servicio,txt_remite){
            
            var databox_list_trpt_select = document.getElementsByClassName("databox_list_trpt_select");
            var databox_transport_title_second = document.getElementsByClassName("databox_transport_title_second");
            var container_select = document.getElementById("container_select_"+id_guia);
            var container_select_second = document.getElementById("container_title_second_"+id_guia);

            /*Limpiamos los selecciones*/

            for (var i = 0; i < databox_transport_title_second.length; i++) {
                databox_list_trpt_select[i].classList.remove('select_hover_registry');
                databox_transport_title_second[i].classList.remove('hover_title_second');
            };

            //---------------------------------------

            container_select.classList.add('select_hover_registry');
            container_select_second.classList.add('hover_title_second');

            mapa_gestion.cmp.getGuia = ge_texto;
            mapa_gestion.getDataClient(id_guia,guia,dir_px,dir_py,gps_px,gps_py,servicio,txt_remite);
        },
        getDataClient:function(id_guia,guia,dir_px,dir_py,gps_px,gps_py,servicio,txt_remite){
            Ext.Ajax.request({
                url:mapa_gestion.url+'get_scm_api_track_datos_cliente/',
                params:{vp_guia:guia},
                success:function(response,options){
                    var res = Ext.decode(response.responseText);

                    mapa_gestion.setMarkerInsideGMapByGuide(dir_px,dir_py,gps_px,gps_py,Ext.decode(response.responseText));
                    mapa_gestion.setDetailForm(Ext.decode(response.responseText),servicio,txt_remite);
                    mapa_gestion.getImgVisita({id_guia:id_guia,guia:guia});

                }
            });
        },
        getDataForNewPositionGmaps:function(record){
            var objrec = mapa_gestion.cmp.dataForSetMap;
        },      
        setOnRangeChange:function(){
            //mapa_gestion.cmp.timeline[3].setVisibleChartRangeNow();
            //var range = mapa_gestion.cmp.timeline[1].getVisibleChartRange();
            //document.getElementById('startDate').value = mapa_gestion.dateFormat(range.start);
            //document.getElementById('endDate').value   = mapa_gestion.dateFormat(range.end);
            //Ext.get("startDate").update(mapa_gestion.dateFormat(range.start));
            //Ext.get("endDate").update(mapa_gestion.dateFormat(range.end));
        },
        dateFormat:function(date) {
            var datetime =   date.getFullYear() + "-" +
                    ((date.getMonth()   <  9) ? "0" : "") + (date.getMonth() + 1) + "-" +
                    ((date.getDate()    < 10) ? "0" : "") +  date.getDate() + " " +
                    ((date.getHours()   < 10) ? "0" : "") +  date.getHours() + ":" +
                    ((date.getMinutes() < 10) ? "0" : "") +  date.getMinutes() + ":" +
                    ((date.getSeconds() < 10) ? "0" : "") +  date.getSeconds();
            return datetime;
        },
        setMarkerSpecial:function(record){
            var point = new google.maps.LatLng(parseFloat(record.dir_px),parseFloat(record.dir_py));
            mapa_gestion.cmp.dataForSetMap.push({ge:record.nombre,co_px:parseFloat(record.dir_px), co_py:parseFloat(record.dir_py)});

            switch(record.tipo_marker){
                case 'P':
                    var marker = new google.maps.Marker({
                            position: point,
                            map: mapa_gestion.mapa.map,
                            animation: google.maps.Animation.DROP,
                            title: '',
                            icon:record.logo,
                            tipo:record.tipo_marker,
                            id:record.id_marker
                    });
                break;
                default:
                    var marker = new google.maps.Marker({
                            position: point,
                            map: mapa_gestion.mapa.map,
                            animation: google.maps.Animation.DROP,
                            title: '',
                            icon:'/images/icon/'+record.logo,
                            tipo:record.tipo_marker,
                            id:record.id_marker
                    });
                break;
            }
            var infowindow = new google.maps.InfoWindow({
                  content: '<div id="content" style="width:300px;">'+record.nombre+'</div>',
                  maxWidth: 300
            });
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(mapa_gestion.mapa.map,marker);
            });
            mapa_gestion.mapa.markers.push(marker);
        },
        setMarker:function(record){

            /*
            console.log("VALOR DE RECORD EN SETMARKER");
            console.log(record);
            */
            var point = new google.maps.LatLng(parseFloat(record.dir_px),parseFloat(record.dir_py));

            switch(record.tipo_marker){
                case 'P':
                    var marker = new google.maps.Marker({
                            position: point,
                            map: mapa_gestion.mapa.map,
                            animation: google.maps.Animation.DROP,
                            title: '',
                            icon:record.logo,
                            tipo:record.tipo_marker,
                            id:record.id_marker
                    });
                break;
                default:
                    var marker = new google.maps.Marker({
                            position: point,
                            map: mapa_gestion.mapa.map,
                            animation: google.maps.Animation.DROP,
                            title: '',
                            icon:'/images/icon/'+record.logo,
                            tipo:record.tipo_marker,
                            id:record.id_marker
                    });
                break;
            }
            var infowindow = new google.maps.InfoWindow({
                  content: '<div id="content"  style="width:300px;">'+record.nombre+'</div>',
                  maxWidth: 300
            });
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(mapa_gestion.mapa.map,marker);
            });

            if (record.tipo_setMarker == 0){
                mapa_gestion.mapa.markers.push(marker);
            }else if (record.tipo_setMarker == 1){
                mapa_gestion.mapa.positionMarkers.push(marker);
            }


        },
        setClearMarker:function(){
            if (mapa_gestion.mapa.markers){
                for (i in mapa_gestion.mapa.markers) {
                    mapa_gestion.mapa.markers[i].setMap(null);
                }
            }
        },
        setClearPositionMarker:function(){
            if (mapa_gestion.mapa.positionMarkers){
                for (i in mapa_gestion.mapa.positionMarkers) {
                    mapa_gestion.mapa.positionMarkers[i].setMap(null);
                }
            }
        },
        setPointTrack:function(obj){
            mapa_gestion.setClearMarker();
            mapa_gestion.setMap(null);
            obj.each(function(record){
                if(parseFloat(record.get('pos_px')) || record.get('pos_px')!=null){
                    mapa_gestion.setMarker({dir_px:parseFloat(record.get('pos_px')),dir_py:parseFloat(record.get('pos_py')),logo:'transport_urbano_min_A.png',nombre:record.get('placa')+" <br>"+record.get('hora_ultimo'),tipo_marker:'M',id_marker:record.get('id_placa'),tipo_setMarker:0});
                }
            });
        },
        getReloadTack:function(){
            Ext.getCmp(mapa_gestion.id+'-north-data-client').setHidden(true);
            var placa = Ext.getCmp(mapa_gestion.id+'-placa').getValue();
            var fecha = Ext.getCmp(mapa_gestion.id+'-fecha').getRawValue();
            var agencia = Ext.getCmp(mapa_gestion.id+'-agencia').getValue();
            var mask = new Ext.LoadMask(Ext.getCmp(mapa_gestion.id+'-tab'),{
                msg:'Obteniendo InformaciÃ³n...'
            });
            mask.show();
            Ext.getCmp(mapa_gestion.id+'-grid-car').getStore().removeAll();
            mapa_gestion.setDestroyCmp();
            Ext.getCmp(mapa_gestion.id+'-grid-car').getStore().load(
                {params: {vp_placa:placa ,vp_fecha:fecha,vp_agencia:agencia},
                callback:function(){
                    mask.hide();
                    mapa_gestion.trafic();
                }
            });
        },
        setDestroyCmp:function(){
            if(mapa_gestion.cmp.cmpAct!=0)mapa_gestion.setClearCmp(mapa_gestion.cmp.cmpAct);
            mapa_gestion.cmp.cmpAct=0;
            mapa_gestion.dataNow={vp_fecha:'',vp_id_placa:0,placa:'',chofer:''};
        },
        getRouteTrack:function(type,record){//activo1

            if(type){
                mapa_gestion.dataNow={vp_fecha:record.get('fecha'),vp_id_placa:record.get('id_placa'),placa:record.get('placa'),chofer:record.get('chofer')};
            }else{//SI ES FALSE
                if(mapa_gestion.dataNow.vp_id_placa==0){ 
                    mapa_gestion.setPointTrack(Ext.getCmp(mapa_gestion.id+'-grid-car').getStore());
                    return false;
                }
            }
            mapa_gestion.setClearMarker();
            var mask = new Ext.LoadMask(Ext.getCmp(mapa_gestion.id+'-center-map'),{
                msg:'Obteniendo Ruta espere...'
            });
            mask.show();
            var routes = [];
            Ext.Ajax.request({
                url:mapa_gestion.url+'get_scm_track_panel_gps_unidad/',
                params:mapa_gestion.dataNow,
                success:function(response,options){
                    mask.hide();
                    var res = Ext.decode(response.responseText);
                    if(res.data.length>0){
                        if(parseFloat(res.data[res.data.length-1].rut_px)!=0){
                            mapa_gestion.setMap({lat:parseFloat(res.data[res.data.length-1].rut_px),lng:parseFloat(res.data[res.data.length-1].rut_py),zoom:11});
                            mapa_gestion.trafic();
                        }else{
                            mapa_gestion.setMap(null);
                        }

                        for(var p=0;p<res.data.length;p++){
                            if(p==0){
                                mapa_gestion.setMarker({dir_px:parseFloat(res.data[p].rut_px),dir_py:parseFloat(res.data[p].rut_py),logo:'point_map_A8.png',nombre:res.data[p].hora,tipo_marker:'H',id_marker:0,tipo_setMarker:0});
                            }else{
                                mapa_gestion.setMarker({dir_px:parseFloat(res.data[p].rut_px),dir_py:parseFloat(res.data[p].rut_py),logo:(p!=res.data.length-1)?'mini_clock.png':'transport_urbano_min_A.png',nombre:res.data[p].hora,tipo_marker:'H',id_marker:0,tipo_setMarker:0});
                            }
                            if(parseFloat(res.data[p].rut_px)!=0 || parseFloat(res.data[p].rut_px) !=null){
                                //aqui agrego los puntos especiales al array routes al final de la cola
                                routes.push(new google.maps.LatLng(parseFloat(res.data[p].rut_px), parseFloat(res.data[p].rut_py)));
                                if(p>0){
                                    mapa_gestion.getPolyline(routes,'#000000');
                                    var act=routes[1];
                                    routes = [];
                                    routes[0]=act;
                                }
                            }
                        }
                    }else{
                        mapa_gestion.setMap(null);
                    }
                    
                    //setTimeout ('mapa_gestion.getPointDelivery()', 2000);
                    //setTimeout ('mapa_gestion.setDriver()', 2000);
                    //mapa_gestion.getPointDelivery();
                    //mapa_gestion.setDriver();
                    //mapa_gestion.mapa.map.setZoom(12);
                }
            });
            return true;
        },
        getPointDelivery:function(){
            var routes = [];
            var n = 0;
            mapa_gestion.cmp.dataForSetMap = [];

            
            var grid  = Ext.getCmp(mapa_gestion.id+'_cmp_content_table');
            var store_table_guias = grid.getStore();


            store_table_guias.each(function(record_t){
             
                /*
                console.log("======================================");
                console.log("datos que entrar a dibujar el mapa cuando entro a getPointDelivery");
                console.log(record_t);
                */
                
                if(parseFloat(record_t.get('dir_px'))!=0){//aca
                    //console.log("entro a getIconStatus OJO");
                    mapa_gestion.setMarker({dir_px:parseFloat(record_t.get('gps_px')),dir_py:parseFloat(record_t.get('gps_py')),logo:mapa_gestion.getIconStatus(record_t.get('chk')),nombre:record_t.get('ge_texto'),tipo_marker:'H',id_marker:0});

                    mapa_gestion.setMarker({dir_px:parseFloat(record_t.get('dir_px')),dir_py:parseFloat(record_t.get('dir_py')),logo:mapa_gestion.getIconFirstStatus(record_t.get('chk')),nombre:record_t.get('ge_texto'),tipo_marker:'H',id_marker:0});
                }
                else if(parseFloat(record_t.get('gps_px'))!=0){
                    mapa_gestion.setMarker({dir_px:parseFloat(record_t.get('gps_px')),dir_py:parseFloat(record_t.get('gps_py')),logo:mapa_gestion.getIconStatus(record_t.get('chk')),nombre:record_t.get('ge_texto'),tipo_marker:'H',id_marker:0});
                }

                if(parseFloat(record_t.get('dir_px'))!=0 && parseFloat(record_t.get('gps_px'))!=0){
                    routes = [];
                    routes.push( new google.maps.LatLng(parseFloat(record_t.get('dir_px') ), parseFloat(record_t.get('dir_py'))) );
                    routes.push( new google.maps.LatLng(parseFloat(record_t.get('gps_px') ), parseFloat(record_t.get('gps_py'))) );
                    mapa_gestion.getPolyline(routes,'#979797');
                    
                }
            });
        },
        getIconStatus:function(type){
            switch(type){
                case 'DL':
                    return 'point_map_A4.png';
                break;
                case 'CV':
                    return 'point_map_A2.png';
                break;
                case 'LD':
                    return 'point_map_A7.png';
                break;
            }
        },
        getIconFirstStatus:function(type){
            switch(type){
                case 'DL':
                    return 'point_map_A6.png';
                break;
                case 'CV':
                    return 'point_map_A6.png';
                break;
                case 'LD':
                    return 'point_map_A5.png';
                break;
            }
        },
        getPolyline:function(routes,color){
            new google.maps.Polyline({
                path: routes
                , map: mapa_gestion.mapa.map
                , strokeColor: color
                , strokeWeight: 3
                , strokeOpacity: 0.5
                , clickable: false
                ,geodesic: true
            });
        },
        getImgVisita:function(params){

            win.getGalery({container:'GaleryFull',url:'/gestion/gestionTransporte/get_img_tracks/',params:{vp_rut_id:params.id_guia,vp_guia:params.guia}});

            /*var mask = new Ext.LoadMask(Ext.getCmp(mapa_gestion.id+'-grid-img-visita'),{
                msg:'Obteniendo Informacion...'
            });
            Ext.getCmp(mapa_gestion.id+'-grid-img-visita').getStore().removeAll();
            Ext.getCmp(mapa_gestion.id+'-grid-img-visita').getStore().load(
                {params: {vp_rut_id:params.id_guia ,vp_guia:params.guia},
                callback:function(){
                    mask.hide();
                    Ext.getCmp(mapa_gestion.id+'-grid-img-visita').refresh();
                }
            });*/
        },
        setDriver:function(){
            Ext.get("contetDivDriver").update('<span style="width:20px;background:red;padding:5px;">'+mapa_gestion.dataNow.placa+'</span><span style="padding:5px;background: #000;">'+mapa_gestion.dataNow.chofer+'</span>');
            //mapa_gestion.ultimo();
        },
        setDriverPro:function(){
            Ext.get("contetDivDriver").update('<span style="width:20px;background:red;padding:5px;">'+mapa_gestion.dataNow.placa+'</span><span style="padding:5px;background: #000;">'+mapa_gestion.dataNow.chofer+'</span>');
        },
        HHomeControl:function(controlDiv, map) {
          controlDiv.style.padding = '5px';

          // Set CSS for the control border.
          var controlUI = document.createElement('div');
          controlUI.style.backgroundColor = 'gray';
          controlUI.style.borderStyle = 'gray';
          controlUI.style.borderWidth = '2px';
          controlUI.style.borderColor = 'gray';

          controlUI.style.width = '100%';
          controlUI.style.height = '100%';

          controlUI.style.cursor = 'pointer';
          controlUI.style.textAlign = 'center';
          controlUI.style.opacity = '0.5';
          controlUI.title = 'Click Para Mostrar el TrÃ¡fico';
          controlUI.innerHTML = '<div id="contetDivDriver"></div>';
          controlDiv.appendChild(controlUI);
        },
        setMap:function(params){
            var directionsService = new google.maps.DirectionsService();
            
            var rendererOptions = {
                  draggable: true,
                  suppressMarkers: true
            };
            mapa_gestion.mapa.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
            mapa_gestion.mapa.directionsService = new google.maps.DirectionsService();

            var myLatlng = new google.maps.LatLng((params==null)?mapa_gestion.mapa.lat:params.lat,(params==null)?mapa_gestion.mapa.lng:params.lng);
            var mapOptions = {
                zoom: (params==null)?mapa_gestion.mapa.zoom:params.zoom,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            mapa_gestion.mapa.map = new google.maps.Map(document.getElementById(mapa_gestion.id+'-map'), mapOptions);

            var hdiv = document.createElement('div');
            var hcontro = new mapa_gestion.HHomeControl(hdiv,mapa_gestion.mapa.map);
            hdiv.index = 1;
            mapa_gestion.mapa.map.controls[google.maps.ControlPosition.TOP_LEFT].push(hdiv);

            mapa_gestion.mapa.directionsDisplay.setMap(mapa_gestion.mapa.map);
            
        },
        setPositionGoogleMaps:function(params){
            var data_marker = mapa_gestion.cmp.dataForMarker;

            mapa_gestion.mapa.directionsDisplay.setMap(mapa_gestion.mapa.map);
            var new_position = new google.maps.LatLng(params.lat,params.lng);
            mapa_gestion.mapa.map.setCenter(new_position);
            mapa_gestion.mapa.map.setZoom(15);

        mapa_gestion.setMarker({dir_px:params.lat,dir_py:params.lng,logo:"",nombre:"<b>"+data_marker.remitente+"</b>"+" <br>"+data_marker.destinatario+" <br>"+data_marker.direccion+" "+data_marker.localidad,tipo_marker:'P',id_marker:0,tipo_setMarker:0});

        //setTimeout ('mapa_gestion.setDriverPro()', 1000);
        },
        stadistico:function(){
            win.show({vurl: mapa_gestion.url+'estadistico/', id_menu: mapa_gestion.id_menu, class: '' });
        }
    }
    Ext.onReady(mapa_gestion.init,mapa_gestion);
}else{
    tab.setActiveTab(mapa_gestion.id+'-tab');
}
//$('#my-timeline_1').timelinexml({ src : '/timeline/timeline.xml' });
/*$('#my-timeline').timelinexml({ 
    src : '/timeline/timeline.xml?a=<?php echo rand(5, 15);?>',
    showLatest : false, 
    selectLatest : false,
    eventTagName : "event",
    dateTagName : "date",
    titleTagName : "title",
    //thumbTagName : "thumb",
    contentTagName : "content",
    linkTagName : "link",
    htmlEventClassName : "timeline-event",
    htmlDateClassName : "timeline-date",
    htmlTitleClassName : "timeline-title",
    htmlContentClassName : "timeline-content",
    htmlLinkClassName : "timeline-link",
    //htmlThumbClassName : "timeline-thumb"
});*/
</script>
