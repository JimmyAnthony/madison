<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <title>Simple markers</title>
        <script type="text/javascript" src="/js/ext-5.1.0/bootstrap.js"></script>
        <script type="text/javascript" src="/js/ext-5.1.0/packages/ext-locale/build/ext-locale-es.js"></script>
        <style>
            html, body, #map-canvas {
                height: 100%;
                margin: 0px;
                padding: 0px
            }
        </style>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
        <script>
            var inicio = {
                myLatlng: new google.maps.LatLng(-25.363882,131.044922),
                mapOptions:{
                    zoom: 4,
                    center: null
                },
                map: null,
                marker: null,
                data: Ext.JSON.decode('<?php echo $this->getDataGoogleMaps($p);?>'),
                init: function(){

                    // console.log(inicio.data);

                    inicio.myLatlng = new google.maps.LatLng(-25.363882,131.044922);

                    inicio.mapOptions.zoom = 4;
                    inicio.mapOptions.center = inicio.myLatlng;
                    inicio.map = new google.maps.Map(document.getElementById('map-canvas'), inicio.mapOptions);

                    Ext.Object.each(inicio.data, function(index, value){
                        // console.log(value.latitude + '-' + value.longitude);
                        inicio.myLatlng = new google.maps.LatLng(value.latitude, value.logitude);
                        inicio.marker = new google.maps.Marker({
                            position: inicio.myLatlng,
                            map: inicio.map,
                            title: ''
                        });
                    });
                },
                setMap: function(p){
                    var mapOptions = {
                        zoom: p.zoon,
                        center: new google.maps.LatLng(p.lat, p.lon)
                    };
                    inicio.map = new google.maps.Map(document.getElementById('-map-canvas'), mapOptions);
                }
            }
            Ext.onReady(inicio.init, inicio);

/*
function initialize() {
  var myLatlng = new google.maps.LatLng(-25.363882,131.044922);
  var mapOptions = {
    zoom: 4,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'Hello World!'
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

*/

        </script>
    </head>
    <body>
        <div id="map-canvas"></div>
    </body>
</html>