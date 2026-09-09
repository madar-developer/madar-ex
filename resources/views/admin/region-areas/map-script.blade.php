<script type="text/javascript" src="https://maps.google.com/maps/api/js?libraries=places,drawing&key={{ getMapsKey() }}&language=ar"></script>
<script type="text/javascript">
    var map, polygon, drawingManager, autocomplete;
    var defaultLat = 24.7136;
    var defaultLng = 46.6753;
    var existingCoords = [];

    @if(isset($area) && !empty($area->coordinates))
        existingCoords = @json($area->coordinates);
    @endif

    function pathToJson(path) {
        var coords = [];
        path.forEach(function (latLng) {
            coords.push({ lat: latLng.lat(), lng: latLng.lng() });
        });
        document.getElementById('coordinates').value = JSON.stringify(coords);
    }

    function bindPolygon(poly) {
        if (polygon && polygon !== poly) {
            polygon.setMap(null);
        }
        polygon = poly;
        pathToJson(polygon.getPath());
        google.maps.event.addListener(polygon.getPath(), 'set_at', function () {
            pathToJson(polygon.getPath());
        });
        google.maps.event.addListener(polygon.getPath(), 'insert_at', function () {
            pathToJson(polygon.getPath());
        });
        google.maps.event.addListener(polygon.getPath(), 'remove_at', function () {
            pathToJson(polygon.getPath());
        });
    }

    function initialize() {
        var center = new google.maps.LatLng(defaultLat, defaultLng);
        if (existingCoords.length) {
            center = new google.maps.LatLng(existingCoords[0].lat, existingCoords[0].lng);
        }

        map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: existingCoords.length ? 13 : 11,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: existingCoords.length ? null : google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [google.maps.drawing.OverlayType.POLYGON]
            },
            polygonOptions: {
                editable: true,
                draggable: false,
                fillColor: '#4285F4',
                fillOpacity: 0.25,
                strokeColor: '#4285F4',
                strokeWeight: 2
            }
        });
        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, 'polygoncomplete', function (poly) {
            bindPolygon(poly);
            drawingManager.setDrawingMode(null);
        });

        if (existingCoords.length) {
            var path = existingCoords.map(function (p) {
                return new google.maps.LatLng(p.lat, p.lng);
            });
            var existing = new google.maps.Polygon({
                paths: path,
                map: map,
                editable: true,
                fillColor: '#4285F4',
                fillOpacity: 0.25,
                strokeColor: '#4285F4',
                strokeWeight: 2
            });
            bindPolygon(existing);
            var bounds = new google.maps.LatLngBounds();
            path.forEach(function (p) { bounds.extend(p); });
            map.fitBounds(bounds);
        }

        var inputField = document.getElementById('autocomplete');
        autocomplete = new google.maps.places.Autocomplete(inputField);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            if (place.geometry) {
                map.panTo(place.geometry.location);
                map.setZoom(15);
            }
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);

    document.getElementById('region-area-form').addEventListener('submit', function (e) {
        var value = document.getElementById('coordinates').value;
        var coords = [];
        try { coords = JSON.parse(value || '[]'); } catch (err) { coords = []; }
        if (!coords.length || coords.length < 3) {
            e.preventDefault();
            alert('يرجى رسم منطقة على الخريطة (ثلاث نقاط على الأقل)');
        }
    });
</script>
