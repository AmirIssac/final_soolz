@extends('layouts.app')
@push('css_lib')
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
<!-- select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
{{--dropzone--}}
<link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.restaurant_plural')}} <small>{{trans('lang.restaurant_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('restaurants.index') !!}">{{trans('lang.restaurant_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.restaurant_create')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  @include('adminlte-templates::common.errors')
  <div class="clearfix"></div>
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        @can('restaurants.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('restaurants.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.restaurant_table')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.restaurant_create')}}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::open(['route' => 'restaurants.store']) !!}
      <div class="row">
        @include('restaurants.fields')
      </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@include('layouts.media_modal')
@endsection
@push('scripts_lib')
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- select2 -->
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
{{--dropzone--}}
<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
</script>

{{--map--}}
<script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
{{--<script>
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
</script>--}}

<script>
  // validate coordinate
  function validateCoordinate(lon,lat){
    //alert(lon+"and"+lat);
    var lonNum = parseFloat(lon); // convert from string to float
    var latNum = parseFloat(lat); // convert from string to float
    /*if (typeof map != 'undefined') {
          map.setTarget(null);
          delete ol;
    }*/
    var map = new ol.Map({
    target: 'map',
    layers: [
      new ol.layer.Tile({
        source: new ol.source.OSM()    // open street map
      })
    ],
    view: new ol.View({
      center: ol.proj.fromLonLat([lonNum, latNum]),
      zoom: 16
    })
  });
  var layer = new ol.layer.Vector({
     source: new ol.source.Vector({
         features: [
             new ol.Feature({
                 geometry: new ol.geom.Point(ol.proj.fromLonLat([lonNum, latNum]))
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
 // disable the validating button
 const btn = document.getElementById('validatebtn');
 btn.disabled= true;
  }
  </script>


  <script>
    function confirmMapLink(){
      
    //var  url='https://www.google.com/maps/place/Space+Needle/@47.620506,-122.349277,17z/data=!4m6!1m3!3m2!1s0x5490151f4ed5b7f9:0xdb2ba8689ed0920d!2sSpace+Needle!3m1!1s0x5490151f4ed5b7f9:0xdb2ba8689ed0920d';
    //var url ="https://www.google.com/maps/place/24%C2%B037'07.9%22N+46%C2%B039'10.9%22E/@24.6188611,46.6552165,17z/data=!3m1!4b1!4m6!3m5!1s0!7e2!8m2!3d24.6188541!4d46.6530354";
    var url = document.getElementById("maplink").value;
    var mySubString = url.substring(    // both long and lat
    url.lastIndexOf("@") + 1, 
    url.lastIndexOf(",")
    );
    var lat = mySubString.substring(    // lat
      0, 
      mySubString.lastIndexOf(",")
    );
    var long = mySubString.substring(   // long
      mySubString.lastIndexOf(",")+1 
    );
    document.getElementById("long").value = long;
    document.getElementById("lat").value = lat;
    //alert(mySubString+"and"+str1+"and"+str2);
    }
  </script>
@endpush
