var overlay;

function initialize() {
  var myLatLng = new google.maps.LatLng(44.73532729516236, 14.806330871582077);
  var mapOptions = {
    zoom: 14,
    center: myLatLng,
    mapTypeId: google.maps.MapTypeId.READMAP,
  };

  var gmap = new google.maps.Map(
    document.getElementById("mapCanvas"),
    mapOptions
  );

  function HTMLMarker(lat, lng) {
    this.lat = lat;
    this.lng = lng;
    this.pos = new google.maps.LatLng(lat, lng);
  }

  HTMLMarker.prototype = new google.maps.OverlayView();
  HTMLMarker.prototype.onRemove = function () {};

  //init your html element here
  HTMLMarker.prototype.onAdd = function () {
    div = document.createElement("DIV");
    div.className = "arrow_box";
    div.innerHTML = "<img src='https://picsum.photos/200' alt=''>";
    var panes = this.getPanes();
    panes.overlayImage.appendChild(div);
  };

  HTMLMarker.prototype.draw = function () {
    var overlayProjection = this.getProjection();
    var position = overlayProjection.fromLatLngToDivPixel(this.pos);
    var panes = this.getPanes();
    panes.overlayImage.style.left = position.x + "px";
    panes.overlayImage.style.top = position.y - 30 + "px";
  };

  //to use it
  var htmlMarker = new HTMLMarker(44.73532729516236, 14.806330871582077);
  htmlMarker.setMap(gmap);
}

/* (function () {

    var pos = new google.maps.LatLng(43.714604, -79.334078);

    var map = new google.maps.Map($('#mapCanvas')[0], {
        center: pos,
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROAD
    });

    var marker = new google.maps.Marker({
        position: pos,
        map: map
    });
    marker.tooltipContent = 'this content should go inside the tooltip';
    var infoWindow = new google.maps.InfoWindow({
        content: 'This is an info window'
    });

    google.maps.event.addListener(marker, 'click', function () {
        infoWindow.open(map, marker);
    });

    google.maps.event.addListener(marker, 'mouseover', function () {
        var point = fromLatLngToPoint(marker.getPosition(), map);
        $('#marker-tooltip').html(marker.tooltipContent + '<br>Pixel coordinates: ' + point.x + ', ' + point.y).css({
            'left': point.x,
                'top': point.y
        }).show();
    });

    google.maps.event.addListener(marker, 'mouseout', function () {
        $('#marker-tooltip').hide();
    });
    
})();

function fromLatLngToPoint(latLng, map) {
    var topRight = map.getProjection().fromLatLngToPoint(map.getBounds().getNorthEast());
    var bottomLeft = map.getProjection().fromLatLngToPoint(map.getBounds().getSouthWest());
    var scale = Math.pow(2, map.getZoom());
    var worldPoint = map.getProjection().fromLatLngToPoint(latLng);
    return new google.maps.Point((worldPoint.x - bottomLeft.x) * scale, (worldPoint.y - topRight.y) * scale);
} */




$("#toggle").click(function() {
		$(this).toggleClass("on");
		$("#menu").slideToggle();
});