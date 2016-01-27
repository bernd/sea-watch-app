var view = new ol.View({
  center: [0, 0],
  zoom: 2
});
var iconStyle = new ol.style.Style({
    image: new ol.style.Icon(({
      anchor: [0.5, 46],
      anchorXUnits: 'fraction',
      anchorYUnits: 'pixels',
      opacity: 0.75,
      src: 'data/icon.png'
   }))
});


var element = document.getElementById('olControlDiv');



var vectorSrc = new ol.source.Vector();
var map = new ol.Map({
  layers: [new ol.layer.Tile({
                        source: new ol.source.OSM()
                    }),
                    new ol.layer.Tile({
                        source: new ol.source.XYZ({
                            url: 'http://t1.openseamap.org/seamark/{z}/{x}/{y}.png'
                        })
                    }),
                    new ol.layer.Vector({
                        source: vectorSrc
                    })
                ],
  renderer: exampleNS.getRendererFromQueryString(),
  target: 'map',
  view: view,
  controls: ol.control.defaults({
    attributionOptions: /** @type {olx.control.AttributionOptions} */ ({
      collapsed: true
    })
  })
});

var geolocation = new ol.Geolocation({
  projection: view.getProjection(),
  tracking: true
});
geolocation.once('change:position', function() {
  view.setCenter(geolocation.getPosition());
  view.setResolution(2.388657133911758);
});

// Use FastClick to eliminate the 300ms delay between a physical tap
// and the firing of a click event on mobile browsers.
// See http://updates.html5rocks.com/2013/12/300ms-tap-delay-gone-away
// for more information.
$(function() {
  FastClick.attach(document.body);
});
