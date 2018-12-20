<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>.: Madison :.</title>
    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/css/inicio_bootstrap.css">
    <link rel="stylesheet" href="/js/iviewer/jquery.iviewer.css" />
    <!--<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="/js/ext-5.0.1/packages/sencha-charts/build/classic/resources/sencha-charts-all.css" />
    
    <script type="text/javascript" src="/js/ext-5.1.0/bootstrap.js"></script>
    <script type="text/javascript" src="/js/ext-5.1.0/packages/ext-locale/build/ext-locale-es.js"></script>
    <script type="text/javascript" src="/js/global.js"></script>
    <!--
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
    -->
   <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZTYdq5n5j-iOv5xAZCCTyuGQwkFU3CMU&callback=initMap"type="text/javascript"></script>

    <!--<script type="text/javascript" src="/js/jquery-1.8.0.min.js" ></script>-->
    <script type="text/javascript" src="/js/ext-5.1.0/packages/sencha-charts/build/sencha-charts.js"></script>
    <script type="text/javascript" src="/js/jquery-1.11.1.min.js" ></script>
    <script type="text/javascript" src="/js/jquery-migrate-1.2.1.min.js" ></script>
    <!--<script type="text/javascript" src="/js/iviewer/jqueryui.js" ></script>
    <script type="text/javascript" src="/js/iviewer/jquery.mousewheel.min.js" ></script>
    <script type="text/javascript" src="/js/iviewer/jquery.iviewer.min.js" ></script>-->
    <script type="text/javascript" src="/js/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/jquery.bpopup.min.js"></script>
    <script type="text/javascript" src="/js/fusioncharts/js/fusioncharts.js"></script>
    <script type="text/javascript" src="/js/fusioncharts/js/fusioncharts.charts.js"></script>
    <script type="text/javascript" src="/js/fusioncharts/js/themes/fusioncharts.theme.fint.js"></script>

    <script type="text/javascript" src="/js/d3.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript" src="/timeline/timeline.js"></script>
    <link rel="stylesheet" type="text/css" href="/timeline/timeline.css">

    <link rel="stylesheet" href="/js/Gallery-2.16.0/css/blueimp-gallery.css">
    <link rel="stylesheet" href="/js/Gallery-2.16.0/css/blueimp-gallery-indicator.css">
    <link rel="stylesheet" href="/js/Gallery-2.16.0/css/demo.css">


    <script type="text/javascript">

        HomeControl.prototype.home_ = null;

        HomeControl.prototype.getHome = function() {
            return this.home_;
        }

        HomeControl.prototype.setHome = function(home) {
            this.home_ = home;
        }

        function HomeControl(controlDiv, map, home) {
            var control = this;
            control.home_ = home;
            controlDiv.style.padding = '5px';

            var goHomeUI = document.createElement('div');
            goHomeUI.style.backgroundColor = 'gray';
            goHomeUI.style.borderStyle = 'solid';
            goHomeUI.style.borderColor = 'gray';
            goHomeUI.style.borderWidth = '1px';
            goHomeUI.style.cursor = 'pointer';
            goHomeUI.style.textAlign = 'center';
            goHomeUI.title = 'Click to set the map to Home';
            controlDiv.appendChild(goHomeUI);

            var goHomeText = document.createElement('div');
            goHomeText.style.fontFamily = 'Arial,sans-serif';
            goHomeText.style.fontSize = '12px';
            goHomeText.style.color = 'white';
            goHomeText.style.paddingLeft = '4px';
            goHomeText.style.paddingRight = '4px';
            goHomeText.innerHTML = '<b>Urbano Express Perú</b>';
            goHomeUI.appendChild(goHomeText);

            google.maps.event.addDomListener(goHomeUI, 'click', function() {
                var currentHome = control.getHome();
                map.setZoom(4);
                map.setCenter(currentHome);
            });
        }

        Ext.Loader.setConfig({
            enabled: true,
            paths: {
                'Ext.ux': '/js/ext-5.0.1/ux',
                'Ext.global': '/js/global'
            }
        });

        Ext.require([
            '*',
            'Ext.global.plugin.MenuView',
            'Ext.global.uePanelS',
            'Ext.global.uePanel',
            'Ext.global.SubTable',
            //'Ext.global.MenuDB',
            'Ext.global.FindLocation',
            'Ext.global.checkcolumnheader',
            'Ext.global.plugin.GridNovedades',
            'Ext.global.plugin.GridNovedadesComentarios',
            'Ext.global.plugin.GridNovedadesHistorico',
            'Ext.global.plugin.RegistroNovedades',
            'Ext.global.ueCarousel',
            'Ext.global.uePanelHtml',
            'Ext.global.ueInputTextMask'
            //'Ext.ux.GMapPanel'
            
        ]);

        var inicio = {
            id: 'inicio',
            url: '/inicio/index/',
            url_nv: '/gestion/novedades/',
            development: parseInt('<?php echo DEVELOPMENT;?>'),
            runner: new Ext.util.TaskRunner(),
            task: null,
            cofingM:{
                width:240,
                start:false,
                fous:true
            },
            id_nov:0,
            fil:0,
            idx:-1,
            type:'',
            view:{},
            record:{},
            id_msn:0,
            init: function(){
                Ext.tip.QuickTipManager.init();

                inicio.task = inicio.runner.newTask({
                    run: function(){
                        inicio.status();
                    },
                    interval: (1000 * 30)
                });

                inicio.task.start();

                /* ----------------------------------------------------- */
                var html_header = '<header class="Header">' +
                    '<div class="logo"></div>' +
                    '<div class="flag"></div>' +
                    '<div class="sistemas" id="sistemas"></div>' +
                    '<nav id="menu" class="menu"></nav>' +
                    '<div class="usuario">' +
                        '<img src="/images/icon/user.png" class="user_profile" data-qtip="Click para modificar contraseña."/>' +
                        '<span class="tit_usuario">Usuario: <?php echo USR_LOGIN;?> - </span>' +
                        '<span class="nom_usuario"><?php echo USR_NOMBRE;?></span>' +
                    '</div>' +
                    '<div class="exit" onclick="inicio.logout();" data-qtip="Click para salir del sistema."></div>' +
                '</header>';

                var html_footer = '<footer class="Footer">' +
                    '<div class="sello_izq"></div>' +
                    '<div class="creditos">' +
                        '<p>' +
                            '<span>Copyright&copy; Urbano Perú</span>' +
                            '<span>Todos los derechos reservados</span>' +
                        '</p>' +
                    '</div>' +
                    '<div class="sello_der"></div>' +
                '</footer>';

                var htmlFondo = '<div class="fondo_principal ">'+
                    '<div class="nX">'+
                        '<div class="oP">'+
                            '<div class="j"></div>'+
                            '<div class="j1"></div>'+
                        '</div>'+
                    '</div>'+
                '</div>';
                /* ----------------------------------------------------- */
                Ext.create('Ext.container.Viewport',{
                    id: inicio.id + '-contenedor',
                    // padding: '5 5 5 5',
                    border: false,
                    defaults:{
                        border: false,
                        style:{
                            margin: '0px'
                        }
                    },
                    layout: 'border',
                    items:[
                        {
                            region: 'north',
                            layout:'border',
                            height: 40,
                            border:false,
                            //html: html_header,
                            bodyCls: 'red_bg',
                            items:[
                                {
                                    region:'west',
                                    layout: 'column',
                                    width:250,
                                    border:false,
                                    bodyCls: 'transparent',
                                    items:[
                                        {
                                            width: 40,
                                            bodyCls: 'transparent',
                                            border:false,
                                            html: '<a id="cmp_clk" href="#" onClick="inicio.get_menu_sh(inicio.cofingM);" class="az dS"></a>'
                                        },
                                        {
                                            columnWidth: 1,
                                            padding:'0px 0px 0px 40px',
                                            bodyCls: 'transparent',
                                            border:false,
                                            html: '<div class="logo_init"></div>'
                                        }
                                    ]
                                },
                                {
                                    region:'center',
                                    layout:'fit',
                                    border:false,
                                    bodyCls: 'transparent',
                                    items:[

                                    ]
                                },
                                {
                                    region:'east',
                                    layout:'column',
                                    width:220,
                                    border:false,
                                    bodyCls: 'transparent',
                                    margin:7,
                                    items:[
                                        {
                                            width: 40,
                                            border:false,
                                            bodyCls: 'transparent',
                                            items:[
                                                {
                                                    xtype: 'button',
                                                    id:inicio.id+'-btn-nv-',
                                                    scale: 'medium',
                                                    icon: null,
                                                    bodyCls: 'transparent',
                                                    cls:'icon_cmp',
                                                    arrowVisible:false,
                                                    border:false,
                                                    //width:30,
                                                    menuAlign:'t-bl',
                                                    //icon: '/images/front/message_attention.png',
                                                    text:'',
                                                    menu: [
                                                        {
                                                            xtype:'panel',
                                                            id:inicio.id+'-pnl-nov-pm-',
                                                            layout:'fit',
                                                            width:380,
                                                            height:400,
                                                            bodyCls: 'white_fondo',
                                                            border:false,
                                                            items:[
                                                                {
                                                                    xtype:'panel',
                                                                    id:inicio.id+'-pnl-nov-',
                                                                    border:false,
                                                                    layout:'fit',
                                                                    items:[
                                                                        {
                                                                            xtype:'GridNovedades',
                                                                            id:inicio.id,
                                                                            url:inicio.url_nv,
                                                                            records:inicio.load_records,
                                                                            front:1,
                                                                            autoLoad:false
                                                                        }
                                                                    ]
                                                                },
                                                                {
                                                                    xtype:'panel',
                                                                    id:inicio.id+'-pnl-coment-',
                                                                    border:false,
                                                                    hidden:true,
                                                                    layout:'fit',
                                                                    items:[
                                                                        {
                                                                            xtype:'GridNovedadesComentarios',
                                                                            id:inicio.id,
                                                                            url:inicio.url_nv,
                                                                            closed:1
                                                                        }
                                                                    ]
                                                                }
                                                            ],
                                                            bbar:[
                                                                {
                                                                    xtype:'panel',
                                                                    height:40,
                                                                    layout:'border',
                                                                    width:380,
                                                                    border:false,
                                                                    bodyCls: 'white_fondo',
                                                                    items:[
                                                                        {
                                                                            border:false,
                                                                            region:'center',
                                                                            layout:'fit',
                                                                            bodyCls: 'transparent',
                                                                            items:[
                                                                                {
                                                                                    xtype: 'button',
                                                                                    text:'Gestor de Novedades',
                                                                                    id:inicio.id+'-btn-ges-nv',
                                                                                    scale: 'medium',
                                                                                    icon: '/images/front/message_attention.png',
                                                                                    arrowVisible:false,
                                                                                    bodyCls: 'transparent',
                                                                                    cls:'icon_cmp',
                                                                                    border:false,
                                                                                    listeners:{
                                                                                        click: function(obj, e){
                                                                                            win.show({vurl: inicio.url_nv + 'form_panel/', id_menu: 17, class: ''});
                                                                                        }
                                                                                    }
                                                                                }
                                                                            ]
                                                                        },
                                                                        {
                                                                            border:false,
                                                                            width:190,
                                                                            region:'east',
                                                                            layout:'fit',
                                                                            bodyCls: 'transparent',
                                                                            items:[
                                                                                {
                                                                                    xtype: 'button',
                                                                                    text:'Crear Novedad',
                                                                                    id:inicio.id+'-btn-add-nv',
                                                                                    scale: 'medium',
                                                                                    icon: '/images/front/More_2-24.png',
                                                                                    arrowVisible:false,
                                                                                    bodyCls: 'transparent',
                                                                                    cls:'icon_cmp',
                                                                                    border:false,
                                                                                    listeners:{
                                                                                        click: function(obj, e){
                                                                                            var obj = new Ext.global.plugin.RegistroNovedades();
                                                                                            obj.show_novedad();
                                                                                        }
                                                                                    }
                                                                                },
                                                                                {
                                                                                    xtype: 'button',
                                                                                    id:inicio.id+'-btn-back',
                                                                                    text:'Regresar Listado',
                                                                                    scale: 'medium',
                                                                                    icon: '/images/front/back-32.png',
                                                                                    arrowVisible:false,
                                                                                    bodyCls: 'transparent',
                                                                                    cls:'icon_cmp',
                                                                                    border:false,
                                                                                    listeners:{
                                                                                        click: function(obj, e){
                                                                                            inicio.show_nv(true);
                                                                                        }
                                                                                    }
                                                                                }
                                                                            ]
                                                                        }
                                                                    ]
                                                                }
                                                            ]
                                                        }
                                                    ],
                                                    listeners:{
                                                        render:function(o){
                                                            //console.log(o);
                                                        }
                                                    }
                                                }
                                            ]
                                        },
                                        {
                                            border:false,
                                            width: 40,
                                            bodyCls: 'transparent',
                                            items:[
                                                {
                                                    xtype: 'button',
                                                    scale: 'medium',
                                                    padding:'3px 0px 10px 0px',
                                                    icon: null,
                                                    arrowVisible:false,
                                                    bodyCls: 'transparent',
                                                    cls:'icon_cmp',
                                                    border:false,
                                                    //width:30,
                                                    menuAlign:'t-bl',
                                                    icon: '/images/front/user_male-24_000.png',
                                                    text:'',
                                                    menu: [
                                                        {
                                                            xtype:'panel',
                                                            layout:'border',
                                                            width:350,
                                                            height:130,
                                                            bodyCls: 'white_fondo',
                                                            border:false,
                                                            items:[
                                                                {
                                                                    region:'west',
                                                                    layout:'fit',
                                                                    width:80,
                                                                    border:false,
                                                                    padding:'5px 5px 0px 5px',
                                                                    html:'<img src="/images/front/user_male-64.png" class="icon_cmp"/>'
                                                                },
                                                                {
                                                                    region:'center',
                                                                    layout:'fit',
                                                                    border:false,
                                                                    html:'<div class="form_user"><p><span class="tit_usuario"><b><?php echo USR_NOMBRE;?></b></span></p>' +
                                                                    '<p>Usuario : <span class="nom_usuario"><?php echo USR_LOGIN;?></span></p></div>'
                                                                }
                                                            ],
                                                            bbar:[
                                                                {
                                                                    xtype:'panel',
                                                                    height:40,
                                                                    layout:'border',
                                                                    width:350,
                                                                    border:false,
                                                                    bodyCls: 'white_fondo',
                                                                    items:[
                                                                        {
                                                                            border:false,
                                                                            region:'center',
                                                                            layout:'fit',
                                                                            bodyCls: 'transparent',
                                                                            items:[
                                                                                {
                                                                                    xtype: 'button',
                                                                                    text:'Modificar Contraseña',
                                                                                    scale: 'medium',
                                                                                    icon: '/images/front/key-24.png',
                                                                                    arrowVisible:false,
                                                                                    bodyCls: 'transparent',
                                                                                    cls:'icon_cmp',
                                                                                    border:false
                                                                                }
                                                                            ]
                                                                        },
                                                                        {
                                                                            border:false,
                                                                            width:175,
                                                                            region:'east',
                                                                            layout:'fit',
                                                                            bodyCls: 'transparent',
                                                                            items:[
                                                                                {
                                                                                    xtype: 'button',
                                                                                    text:'Cerrar Sessión',
                                                                                    scale: 'medium',
                                                                                    icon: '/images/front/cancel.png',
                                                                                    arrowVisible:false,
                                                                                    bodyCls: 'transparent',
                                                                                    cls:'icon_cmp',
                                                                                    border:false,
                                                                                    listeners:{
                                                                                        click: function(obj, e){
                                                                                            inicio.logout();
                                                                                        }
                                                                                    }
                                                                                }
                                                                            ]
                                                                        }
                                                                    ]
                                                                }
                                                            ]
                                                        }
                                                    ],
                                                    listeners:{
                                                        render:function(o){
                                                            //console.log(o);
                                                        }
                                                    }
                                                }
                                            ]
                                        },
                                        {
                                            border:false,
                                            width: 20,
                                            bodyCls: 'transparent',
                                            padding:'5px 0px 0px 0px',
                                            html:'<div class="cls_age_a"></div>'
                                        },
                                        {
                                            border:false,
                                            columnWidth: 1,
                                            bodyCls: 'transparent',
                                            padding:'9px 0px 0px 2px',
                                            //html:'<div class="cls_age"><div class="cls_age_a">AGENCIA</div><div class="cls_age_b"><?php echo PROV_NOMBRE;?></div></div>'
                                            html:'<div class="cls_age_b"><?php echo PROV_NOMBRE;?></div>'
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            region:'center',
                            layout:'border',
                            //cls:'cmp_contn',
                            id: inicio.id+'-region-content',
                            border:false,
                            items:[
                                {
                                    region:'west',
                                    border:false,
                                    hidden:true,
                                    layout:'fit',
                                    items:[
                                        {
                                            xtype:'panel',
                                            id:'index_web_carga',
                                            hidden:true,
                                            hideMode:'offsets'
                                        }
                                    ]
                                },
                                {
                                    region: 'center',
                                    xtype: 'tabpanel',
                                    id: inicio.id+'-tabContent',
                                    activeItem: 0,
                                    autoScroll: false,
                                    defaults:{
                                        closable: true,
                                        autoScroll: true
                                    },
                                    border: true,
                                    layout: 'fit',
                                    tabPosition: 'left',
                                    // tabRotation: 0,
                                    items:[
                                        {
                                            title: '',
                                            icon: '/images/icon/home.png',
                                            closable: false,
                                            layout: 'fit',
                                            html: htmlFondo,
                                            /*items:[
                                                {
                                                    xtype: 'panel',
                                                    items:[
                                                        {
                                                            xtype: 'textfield',
                                                            id: inicio.id + '-campo1',
                                                            allowBlank: false,
                                                            flex: 1
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            id: inicio.id + '-campo2',
                                                            allowBlank: false,
                                                            flex: 1
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            id: inicio.id + '-campo3',
                                                            allowBlank: false,
                                                            flex: 1
                                                        },
                                                        {
                                                            xtype: 'textfield',
                                                            id: inicio.id + '-campo4',
                                                            allowBlank: false,
                                                            flex: 1
                                                        }
                                                    ]
                                                }
                                            ],*/
                                            listeners:{
                                                afterrender: function(obj){
                                                    // win.show({vurl: '/inicio/index/demo_maps/'});
                                                }
                                            }
                                        }
                                    ]
                                }
                            ]
                        }
                        /*,
                        {
                            region: 'south',
                            height: 28,
                            html: html_footer,
                            bodyCls: 'transparent'
                        }*/
                    ],
                    listeners:{
                        afterrender: function(obj){
                            inicio.renderMenu();
                            Ext.getCmp(inicio.id+'-btn-nv-').setText('<div id="sts_novedad" class="cls_nv_"><div class="cls_nv">0</div><div/>');
                            inicio.status();
                            inicio.menu_vw();
                            /*var cmb_sistemas = Ext.create('Ext.form.field.ComboBox',{
                                id: inicio.id + '-sistema',
                                store: Ext.create('Ext.data.Store',{
                                    fields:[
                                        {name: 'sis_id', type: 'int'},
                                        {name: 'nombre', type: 'string'},
                                        {name: 'icono', type: 'string'}
                                    ],
                                    proxy:{
                                        type: 'ajax',
                                        url: inicio.url + 'get_sistemas/',
                                        reader:{
                                            type: 'json',
                                            rootProperty: 'data'
                                        }
                                    }
                                }),
                                queryMode: 'local',
                                valueField: 'sis_id',
                                displayField: 'nombre',
                                forceSelection: true,
                                width: 175,
                                renderTo: 'sistemas',
                                fieldCls: 'combo_3',
                                inputWrapCls: 'combo_4',
                                triggerCls: 'combo_5',
                                listeners:{
                                    afterrender: function(obj, e){
                                        obj.getStore().load({
                                            params:{

                                            },
                                            callback: function(){
                                                obj.setValue(<?php echo SIS_ID;?>);
                                                inicio.change_sistema();
                                            }
                                        });
                                    },
                                    select: function(obj, records, eOpts){
                                        inicio.change_sistema();
                                    }
                                }
                            });*/
                        }
                    }
                });
            },
            change_sistema: function(){
                var sis_id = Ext.getCmp(inicio.id + '-sistema').getValue();
                var div_menu = Ext.get('menu');
                if (!Ext.get(inicio.id + '-menutb')){
                    inicio.getMenu(sis_id); 
                }else{
                    Ext.getCmp(inicio.id + '-menutb').removeAll();
                    inicio.getMenu(sis_id);                    
                }
            },
            getMenu: function(sis_id){
                var tb = Ext.create('Ext.toolbar.Toolbar',{
                    id: inicio.id + '-menutb',
                    enableOverflow: true,
                    overflowHandler: 'scroller',
                    border: false,
                    cls: 'gk-toolbar-menu',
                    // bodyCls: 'transparent',
                    items:[
                        {
                            xtype: 'menudb',
                            store: Ext.create('Ext.data.Store',{
                                fields:[
                                    {name: 'padre', type: 'string'},
                                    {name: 'nivel', type: 'string'},
                                    {name: 'nombre', type: 'string'},
                                    {name: 'url', type: 'string'},
                                    {name: 'icono', type: 'string'},
                                    {name: 'id_menu', type: 'string'},
                                    {name: 'menu_class', type: 'string'}
                                ],
                                proxy:{
                                    type: 'ajax',
                                    url: inicio.url + 'getDataMenu/',
                                    reader:{
                                        type: 'json',
                                        rootProperty: 'data'
                                    },
                                    extraParams:{
                                        sis_id: sis_id
                                    }
                                }
                            })
                        }
                    ],
                    renderTo: 'menu'
                });
            },
            status: function(){
                Ext.Ajax.request({
                    url: '/login/index/status_session/',
                    params:{},
                    success: function(response, options){
                        var res = Ext.JSON.decode(response.responseText);
                        if (parseInt(res.time) == 0 ){
                            inicio.task.stop();
                            global.Msg({
                                msg: 'Su sesión de usuario ha caducado, volver a ingresar al sistema.',
                                icon: 1,
                                buttons: 1,
                                fn: function(btn){
                                    window.location = '/inicio/index/'
                                }
                            });
                        }
                        var clss = (parseInt(res.novedad)==0)?'cls_nv':'cls_nv_altr';
                            
                        Ext.getCmp(inicio.id+'-btn-nv-').setText('<div id="sts_novedad" class="cls_nv_"><div class="'+clss+'">'+res.novedad+'</div><div/>');
                        if(parseInt(res.novedad)!=0 && inicio.id_msn!=parseInt(res.msn_id)){
                            inicio.id_msn=parseInt(res.msn_id);
                            inicio.reload_novedad();
                        }
                    }
                });
            },
            logout: function(){
                Ext.getCmp(inicio.id + '-contenedor').mask('Saliendo del Sistema...');
                Ext.Ajax.request({
                    url: inicio.url + 'logout/',
                    params:{},
                    success: function(response, options){
                        Ext.getCmp(inicio.id + '-contenedor').unmask();
                        window.location = '/inicio/index/';
                    }
                });
            },
            popup_demo: function(){
                $('#popup_jquery').bPopup({
                    modalClose: false,
                    opacity: 0.6,
                    // easing: 'easeOutBack',
                    // transition: 'slideDown',
                    // speed: 450,
                    // positionStyle: 'absolute',
                    onOpen: function(){
                        inicio.get_charts();
                    },
                    onClose: function(){

                    }
                },
                function(){

                });
            },
            get_charts: function(){
                
            },
            get_menu_sh:function(obj){
                var menu = Ext.getCmp(inicio.id+'-contentMenu');
                //var spinner = Ext.get('menu_spinner');
                if(obj.fous)menu.setVisible(!obj.start);
                menu.animate({
                   duration: 200,
                    to: {
                        width: ((!obj.start)?obj.width:0),
                        height:Ext.getCmp(inicio.id + '-contenedor').getHeight()
                    }
                });
                /*spinner.animate({
                   duration: 200,
                    to: {
                        top: ((!obj.start)?'50%':'-50%')
                    }
                });*/
                menu.doLayout();
                obj.start = (!obj.start);
                obj.fous=false;
                Ext.getCmp(inicio.id+'-region-content').doLayout();
                Ext.getCmp(inicio.id+'-contentMenu').doLayout();
                Ext.getCmp(inicio.id + '-contenedor').doLayout();
                //Ext.getCmp(inicio.id + '-grid').collapseAll();
            },
            load_records:function(view, record, item, idx, event, opts, type){
                inicio.show_nv(false);
                inicio.record=record;
                inicio.idx = idx;
                inicio.type=type;
                inicio.view=view;
                inicio.reload_comentarios(record.data.id_nov);
            },
            reload_comentarios:function(id_nov){
                var obj = inicio.view;
                Ext.getCmp(inicio.id+'-nov-list_comentarios').getStore().removeAll();
                Ext.getCmp(inicio.id+'-nov-list_comentarios').getStore().load(
                    {params: {vp_id_nov: id_nov},
                    callback:function(){
                        Ext.getCmp(inicio.id+'-nov-list_comentarios').refresh();
                        inicio.status();
                        obj.all.elements[inicio.idx].childNodes[2].className = 'databox_status_off';
                        setTimeout( "inicio.remove_visto()", 2000 );
                    }
                });
            },
            show_nv:function(bol){
                Ext.getCmp(inicio.id+'-btn-add-nv').setVisible(bol);
                Ext.getCmp(inicio.id+'-btn-back').setVisible(!bol);
                Ext.getCmp(inicio.id+'-pnl-nov-').setVisible(bol);
                Ext.getCmp(inicio.id+'-pnl-coment-').setVisible(!bol);
                if(Ext.isChrome){
                    setTimeout( "inicio.spacial()", 300 );
                }
            },
            spacial:function(){
                //case-spacial-relative
                Ext.getCmp(inicio.id+'-btn-nv-').el.dom.click();
                Ext.getCmp(inicio.id+'-btn-ges-nv').focus();//Este foco permite mantener el evento mouseleave y tiene que ser un componente visible.
            },
            remove_visto:function(){
                var obj_ = Ext.getCmp(inicio.id+'-nov-list_comentarios');
                Ext.Object.each(obj_.all.elements, function(index, v){
                    obj_.all.elements[index].childNodes[2].className = 'databox_status_msn_off';
                });
            },
            reload_novedad:function(){
                Ext.getCmp(inicio.id+'-nov-lista').getStore().removeAll();
                Ext.getCmp(inicio.id+'-nov-lista').getStore().load(
                    {
                    params: {
                    },
                    callback:function(){
                    }
                });
            },
            elimina_novedad:function(msn_id,id_nov,flag){
                if(parseInt(flag)==0)return;
                if(msn_id== null || msn_id==''){
                    global.Msg({msg:"Mensaje no existe.",icon:2,fn:function(){}});
                    return false;
                }
                global.Msg({
                    msg: '¿Seguro de eliminar comentario?',
                    icon: 3,
                    buttons: 3,
                    fn: function(btn){
                        if (btn == 'yes'){
                            Ext.Ajax.request({
                                url:inicio.url_nv+'set_scm_elimina_comentario',
                                params:{vp_msn_id:msn_id},
                                success:function(response,options){
                                    var res = Ext.decode(response.responseText);

                                    if(parseInt(res.data[0].error_sql)<=0){

                                        global.Msg({
                                            msg:res.data[0].error_info,
                                            icon:0,
                                            fn:function(){
                                                    
                                            }
                                        });

                                    }else{
                                        global.Msg({
                                            msg:res.data[0].error_info,
                                            icon:1,
                                            fn:function(){
                                                inicio.reload_comentarios(id_nov);
                                            }
                                        });
                                        

                                    }
                                }
                            });
                        }
                    }
                });
            },
            download_novedad:function(id_file){
                if(parseInt(id_file)==0)return;
                document.location.href=inicio.url_nv+'get_forzar_descarga/?vp_id_file='+id_file;
            },
            renderMenu:function(){
                Ext.create('Ext.Panel',{
                renderTo:'menu_split',
                id:inicio.id+'-contentMenu',
                border:false,
                layout:'fit',
                floatable: false,
                collapsible: false,
                //split: true,
                border:false,
                //bodyPadding: 10,
                //margin: '5 0 0 0',
                width: 0,
                hidden:true,
                cls: 'cmp_menu',
                bodyCls: 'cmp_menu',
                html:'<div id="menu_spinner" class="spinner"><div class="cube1"></div><div class="cube2"></div></div>',
                items:[
                        {
                            xtype:'MenuView',
                            id:inicio.id,
                            url:inicio.url
                        }
                    ]
                });
            },
            menu_vw:function(){
                $( "#menu_split" ).mouseenter(function() {
                    
                }).mouseleave(function() {
                    inicio.get_menu_sh(inicio.cofingM);
                });
            }
        }
        Ext.onReady(inicio.init, inicio);

        
    </script>
</head>
<body>
    <div id="popup_jquery">
        <span class="button b-close"><span>X</span></span>
        <div id="popup_jquery_in" class="content" style="height: auto; width: auto;"></div>
    </div>
    <div id="menu_split">
    </div>

    <div id="blueimp-gallery" class="blueimp-gallery">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>

    <script src="/js/Gallery-2.16.0/js/blueimp-helper.js"></script>
    <script src="/js/Gallery-2.16.0/js/blueimp-gallery.js"></script>
    <script src="/js/Gallery-2.16.0/js/blueimp-gallery-fullscreen.js"></script>
    <script src="/js/Gallery-2.16.0/js/blueimp-gallery-indicator.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>

    </script>
    <script src="/js/Gallery-2.16.0/js/jquery.blueimp-gallery.js"></script>
    <!--<script src="/js/Gallery-2.16.0/js/demo.js"></script>-->
</body>
</html>