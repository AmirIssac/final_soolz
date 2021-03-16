
@extends('layouts.app')

@section('content')
<style>
    .map {
        margin-top: 57px;
      height: 600px;
      width: 100%;
    }
  </style>
<div id="map" class="map">
  <div id="popup" class="ol-popup">   <!-- clicable popup -->
    <a href="#" id="popup-closer" class="ol-popup-closer"></a>
    <div id="popup-content"></div>
</div>
</div>
@endsection
@section('my_script')
<script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
<script>
    var map = new ol.Map({
    target: 'map',
    layers: [
      new ol.layer.Tile({
        source: new ol.source.OSM()    // open street map
      })
    ],
    view: new ol.View({
      center: ol.proj.fromLonLat([46.738586, 24.774265]),
      zoom: 16
    })
  });
  var layer = new ol.layer.Vector({
     source: new ol.source.Vector({
         features: [
             new ol.Feature({
                 geometry: new ol.geom.Point(ol.proj.fromLonLat([46.738586, 24.774265]))
             })
         ]
     })
 });
 map.addLayer(layer);
 var container = document.getElementById('popup');
 var content = document.getElementById('popup-content');
 var closer = document.getElementById('popup-closer');

 var overlay = new ol.Overlay({
     element: container,
     autoPan: true,
     autoPanAnimation: {
         duration: 250
     }
 });
 map.addOverlay(overlay);

 closer.onclick = function() {
     overlay.setPosition(undefined);
     closer.blur();
     return false;
 };
 map.on('singleclick', function (event) {
     if (map.hasFeatureAtPixel(event.pixel) === true) {
         var coordinate = event.coordinate;

         content.innerHTML = '<b>موقعي</b>';
         overlay.setPosition(coordinate);
     } else {
         overlay.setPosition(undefined);
         closer.blur();
     }
 });
</script>
@endsection