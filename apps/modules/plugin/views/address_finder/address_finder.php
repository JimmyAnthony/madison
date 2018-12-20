<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>.: Zucuba :.</title>
    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/css/inicio_bootstrap.css">
    <script type="text/javascript" src="/js/ext-5.1.0/bootstrap.js"></script>
    <script type="text/javascript" src="/js/ext-5.1.0/packages/ext-locale/build/ext-locale-es.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
    <script src="/js/MooTools-Core-1.5.1.js"></script>

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
            goHomeText.innerHTML = '<a style="text-decoration: none;color:white" href="http://www.urbanoexpress.com" target="_blank"><b>Urbano</b></a>';
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
            'Ext.global.FindLocation'
        ]);

        var inicio = {
            id: 'inicio',
            url: '/plugin/finder/',
            accesos:{
                user:'<?php echo $_REQUEST["user"];?>',
                key:'<?php echo $_REQUEST["key"];?>'
            },
            init: function(){
                Ext.tip.QuickTipManager.init();
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
                           region:'center',
                           bodyStyle: 'background: transparent',
                           autoScroll:true,
                           layout:'fit',
                           border:true,
                           items:[
                                {
                                   xtype: 'findlocation',
                                   id: inicio.id + '-bdireccion',
                                   urlC:inicio.url,
                                   mapping: false,
                                   apiAddress:true,
                                   acceso:inicio.accesos,
                                   trust:true,
                                   listeners:{
                                        afterrender: function(obj){
                                            obj.showBanner({
                                                height:28,
                                                html:'<div style="background:#E6E6E6;height:28px;width:100%;padding:5px;"><img src="/images/logo_urbano.png" height="18"/></div>'
                                            });
                                            if(window.addEventListener){
                                                window.addEventListener('message',function(event){
                                                    var json = Ext.JSON.decode(event.data);
                                                    switch(json.switchs){
                                                        case 'response':
                                                            event.source.postMessage(obj.getValuesString(),event.origin);
                                                        break;
                                                        case 'clear':
                                                            inicio.setClear(obj);
                                                        break;
                                                        case 'disabled':
                                                            inicio.setDisabled(obj,json);
                                                        break;
                                                        case 'trust':
                                                            event.source.postMessage(obj.getTrust(),event.origin);
                                                        break;
                                                        case 'setAddress':
                                                            event.source.postMessage(obj.setGeoLocalizar({id_geo:json.id_geo,dir_id:json.dir_id}),event.origin);
                                                        break;
                                                    }
                                                },false);
                                            }else{
                                                window.attachEvent('onmessage',function(event){
                                                    var json = Ext.JSON.decode(event.data);
                                                    switch(json.switchs){
                                                        case 'response':
                                                            event.source.postMessage(obj.getValuesString(),event.origin);
                                                        break;
                                                        case 'clear':
                                                            inicio.setClear(obj);

                                                        break;
                                                        case 'disabled':
                                                            inicio.setDisabled(obj,json);
                                                        break;
                                                        case 'trust':
                                                            event.source.postMessage(obj.getTrust(),event.origin);
                                                        break;
                                                        case 'setAddress':
                                                            event.source.postMessage(obj.setGeoLocalizar({id_geo:json.id_geo,dir_id:json.dir_id}),event.origin);
                                                        break;
                                                    }
                                                },false);
                                            }
                                        }
                                   }
                                }
                           ] 
                        }
                    ],
                    listeners:{
                        afterrender: function(obj){}
                    }
                });
            },
            setClear:function(obj){
                var json={boolean:false};
                inicio.setDisabled(obj,json);
                obj.reset();
                obj.reset_global_vars();
                Ext.getCmp(obj.id + '-distrito').focus();
            },
            setDisabled:function(obj,json){
                Ext.getCmp(obj.id + '-lote').setDisabled(json.boolean);
                Ext.getCmp(obj.id + '-manzana').setDisabled(json.boolean);
                Ext.getCmp(obj.id + '-via').setDisabled(json.boolean);
                Ext.getCmp(obj.id + '-urbanizacion').setDisabled(json.boolean);
                Ext.getCmp(obj.id + '-nombre_via').setDisabled(json.boolean);
                Ext.getCmp(obj.id + '-tipo_via').setDisabled(json.boolean);
                Ext.getCmp(obj.id + '-distrito').setDisabled(json.boolean);
                Ext.getCmp(obj.id+'-referencia-r').setDisabled(json.boolean);
                Ext.getCmp(obj.id + '-nro-interno').setDisabled(json.boolean);
            }
        }
        Ext.onReady(inicio.init, inicio);
    </script>
</head>
<body>
</body>
</html>