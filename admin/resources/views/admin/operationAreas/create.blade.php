@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/users.users") !!} :: @parent
@stop

{{-- Content --}}
@section('main')

    

<style>
    #map{
        width:400px;
            min-width:400px;
        height:300px;
    }
</style>
<script src="{{ URL::to('js/ol.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="{{ URL::to('css/ol.css') }}" type="text/css">
<div class="container">
<div class="row-fluid">
    <form class="form-horizontal"  style="max-width:550px;" action='' method="POST">
      <div class="form-group">
        <label class="col-sm-3 control-label" for="title">Title</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="title" id="title" placeholder="title">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="title">Area</label>
        <div class="col-sm-9">
            
            <div id="map" class="map"></div>
            <label style="display:none;">Geometry type &nbsp;</label>
            <select id="type" style="display:none;">
              <option value="None">None</option>
              <option value="Point">Point</option>
              <option value="LineString">LineString</option>
              <option value="Polygon">Polygon</option>
              <option value="Circle">Circle</option>
              <option value="Square">Square</option>
              <option value="Box">Box</option>
            </select>
            
        </div>
      </div>
      <div class="form-group" style="display:none;">
        <label class="col-sm-3 control-label" for="polygon">Polygon</label>
        <div class="col-sm-9">
            
          <input type="text" class="form-control" name="polygon_coordinates" id="polygon" placeholder="polygon">
            
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-9">
            <input type="submit" value="Add Area">
        </div>
      </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>

</div>

<script>
    var mapz;
$(document).ready(function(){
    
    
    
    
var raster = new ol.layer.Tile({
  source:new ol.source.OSM()
});

var source = new ol.source.Vector({wrapX: false});

var vector = new ol.layer.Vector({
  source: source,
  style: new ol.style.Style({
    fill: new ol.style.Fill({
      color: 'rgba(255, 255, 255, 0.2)'
    }),
    stroke: new ol.style.Stroke({
      color: '#ffcc33',
      width: 2
    }),
    image: new ol.style.Circle({
      radius: 7,
      fill: new ol.style.Fill({
        color: '#ffcc33'
      })
    })
  })
});
var schladming = [9.99489,53.51603]; // longitude first, then latitude
// since we are using OSM, we have to transform the coordinates...
var schladmingWebMercator = ol.proj.fromLonLat(schladming);

mapz = new ol.Map({
  layers: [raster, vector],
  target: 'map',
  view: new ol.View({
    zoom: 4,
    center: schladmingWebMercator
  })
});

var typeSelect = document.getElementById('type');

var draw; // global so we can remove it later
function addInteraction() {
  var value = 'Polygon';
  if (value !== 'None') {
    var geometryFunction, maxPoints;

    var ondrawend = function(e) {
        var coord = e.feature.getGeometry().getCoordinates();
        
        var results = [];
        var resultString = '';
        
        $.each(e.feature.getGeometry().getCoordinates()[0], function(index, xy){
            results.push(ol.proj.toLonLat(xy));
            resultString += ol.proj.toLonLat(xy)[0]+'-'+ol.proj.toLonLat(xy)[1]+';';
        });
        
        $('#polygon').val(JSON.stringify(results));
        console.log(results);
    };


    draw = new ol.interaction.Draw({
      source: source,
      type: /** @type {ol.geom.GeometryType} */ (value),
      geometryFunction: geometryFunction,
      maxPoints: maxPoints,
    });
    draw.on('drawend', ondrawend);
    mapz.addInteraction(draw);
    
  }
}


/**
 * Let user change the geometry type.
 * @param {Event} e Change event.
 */
typeSelect.onchange = function(e) {
  mapz.removeInteraction(draw);
  addInteraction();
};


addInteraction();

//delay for draw on init bug..
setTimeout(function(){ 
    mapz.updateSize();
},1000);

    
    
});</script>


@stop

{{-- Scripts --}}
@section('scripts')
@stop
