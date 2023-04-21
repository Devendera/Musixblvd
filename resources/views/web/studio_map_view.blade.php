@extends('webuserlayout.index')
@section('content')
    <div id="map_container" style="width: 100%; height: 100%;"> </div>
    <script>
        function HTMLMarker( markerLocation ) {
            var lat = +markerLocation[0];
            var lng = +markerLocation[1];
            this.image = markerLocation[3];
            this.pos = new google.maps.LatLng( lat, lng );
        }
        function initialize() {
            var myLatLng = new google.maps.LatLng(28.7041, 77.1025);
            var mapOptions = { zoom: 4, center: myLatLng, mapTypeId: google.maps.MapTypeId.ROADMAP };
            var gmap = new google.maps.Map(document.getElementById('map_container'), mapOptions);
            HTMLMarker.prototype = new google.maps.OverlayView();
            HTMLMarker.prototype.onRemove = function () {};

            HTMLMarker.prototype.onAdd = function () {
                var div = this.div = document.createElement('DIV');
                div.className = "htmlMarker";
                div.data = "data-price";
                div.innerHTML = "<img src='"+this.image+"' alt=''>";
                var panes = this.getPanes();
                panes.overlayImage.appendChild(div);
            };

            HTMLMarker.prototype.draw = function () {
                var overlayProjection = this.getProjection();
                var position = overlayProjection.fromLatLngToDivPixel(this.pos);
                var panes = this.getPanes();
                this.div.style.left = position.x - 30 + 'px';
                this.div.style.top = position.y - 48 + 'px';
            };

            var markerLocations = (<?php echo($studioJsonData);?>);
            for (var i = 0; i < markerLocations.length; i++) {
                addMarker( markerLocations[i] );
            }
            function addMarker( markerLocation ) {
                var htmlMarker = new HTMLMarker( markerLocation );
                htmlMarker.setMap(gmap);
            }
       }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBccj6ZlVp5d-4K_LfC5cpudMnpg4zIUHE&callback=initialize"></script>
@endsection
 