<script type="text/javascript">
var tab = Ext.getCmp(inicio.id+'-tabContent');
if(!Ext.getCmp('demo_maps-tab')){
    var demo_maps = {
        id: 'demo_maps',
        url: '/inicio/index/',
        init:function(){

            var panel = Ext.create('Ext.form.Panel',{
                id: demo_maps.id+'-form',
                border: false,
                layout: 'fit',
                defaults:{
                    border: false
                },
                html: '<div class="gk-maps-logistica" id="'+demo_maps.id+'-contenedor-map'+'"></div>',
                listeners:{
                    afterrender: function(obj, e){
                        var html = '';
                        html+='<div class="panel-form" id="'+demo_maps.id+'-panel">';
                        html+='<b>Start: </b>';
                        html+='<select id="'+demo_maps.id+'-start" onchange="calcRoute();">';
                            html+='<option value="chicago, il">Chicago</option>';
                            html+='<option value="st louis, mo">St Louis</option>';
                            html+='<option value="joplin, mo">Joplin, MO</option>';
                            html+='<option value="oklahoma city, ok">Oklahoma City</option>';
                            html+='<option value="amarillo, tx">Amarillo</option>';
                            html+='<option value="gallup, nm">Gallup, NM</option>';
                            html+='<option value="flagstaff, az">Flagstaff, AZ</option>';
                            html+='<option value="winona, az">Winona</option>';
                            html+='<option value="kingman, az">Kingman</option>';
                            html+='<option value="barstow, ca">Barstow</option>';
                            html+='<option value="san bernardino, ca">San Bernardino</option>';
                            html+='<option value="los angeles, ca">Los Angeles</option>';
                        html+='</select>';
                        html+='<b>End: </b>';
                        html+='<select id="'+demo_maps.id+'-end" onchange="calcRoute();">';
                            html+='<option value="chicago, il">Chicago</option>';
                            html+='<option value="st louis, mo">St Louis</option>';
                            html+='<option value="joplin, mo">Joplin, MO</option>';
                            html+='<option value="oklahoma city, ok">Oklahoma City</option>';
                            html+='<option value="amarillo, tx">Amarillo</option>';
                            html+='<option value="gallup, nm">Gallup, NM</option>';
                            html+='<option value="flagstaff, az">Flagstaff, AZ</option>';
                            html+='<option value="winona, az">Winona</option>';
                            html+='<option value="kingman, az">Kingman</option>';
                            html+='<option value="barstow, ca">Barstow</option>';
                            html+='<option value="san bernardino, ca">San Bernardino</option>';
                            html+='<option value="los angeles, ca">Los Angeles</option>';
                        html+='</select>';
                        html+='</div>';
                        html+='<div class="maps-canvas" id="'+demo_maps.id+'-map-canvas'+'"></div>';

                        Ext.get(demo_maps.id+'-contenedor-map').update(html)

                        demo_maps.load_map();
                    }
                }
            });

            tab.add({
                id: demo_maps.id+'-tab',
                title: 'Demo maps',
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
                    beforerender: function(obj, opts){
                        
                    },
                    afterrender: function(obj, e){
                        
                    },
                    beforeclose: function(obj, opts){
                        
                    }
                }
            }).show();
        },
        load_map: function(){
            var directionsDisplay;
            var directionsService = new google.maps.DirectionsService();
            var map;

            directionsDisplay = new google.maps.DirectionsRenderer();
            var chicago = new google.maps.LatLng(41.850033, -87.6500523);
            var mapOptions = {
                zoom:7,
                center: chicago
            };
            map = new google.maps.Map(document.getElementById(demo_maps.id+'-map-canvas'), mapOptions);
            directionsDisplay.setMap(map);
        }
    }
    Ext.onReady(demo_maps.init, demo_maps);
}else{
    tab.setActiveTab(demo_maps.id+'-tab');
}
</script>