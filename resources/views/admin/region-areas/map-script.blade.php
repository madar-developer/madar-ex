<script type="text/javascript" src="https://maps.google.com/maps/api/js?libraries=places&key={{ getMapsKey() }}&language=ar"></script>
<script type="text/javascript">
    var map, polygon, autocomplete;
    var defaultLat = 24.7136;
    var defaultLng = 46.6753;
    var existingCoords = [];
    var drawing = false;
    var drawPath = [];
    var drawMarkers = [];
    var drawPolyline = null;
    var previewLine = null;
    var mapClickListener = null;
    var mapDblClickListener = null;
    var mapMoveListener = null;

    @if(isset($area) && !empty($area->coordinates))
        existingCoords = @json($area->coordinates);
    @endif

    var polygonOptions = {
        editable: true,
        draggable: false,
        fillColor: '#4285F4',
        fillOpacity: 0.25,
        strokeColor: '#4285F4',
        strokeWeight: 2
    };

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

    function setHint(text) {
        var hint = document.getElementById('draw-hint');
        if (hint) {
            hint.textContent = text;
        }
    }

    function updateDrawButtons() {
        var finishBtn = document.getElementById('finish-polygon');
        var undoBtn = document.getElementById('undo-vertex');
        if (finishBtn) {
            finishBtn.disabled = !drawing || drawPath.length < 3;
        }
        if (undoBtn) {
            undoBtn.disabled = !drawing || drawPath.length === 0;
        }
    }

    function clearDraft() {
        drawPath = [];
        drawMarkers.forEach(function (marker) {
            marker.setMap(null);
        });
        drawMarkers = [];
        if (drawPolyline) {
            drawPolyline.setMap(null);
            drawPolyline = null;
        }
        if (previewLine) {
            previewLine.setMap(null);
            previewLine = null;
        }
    }

    function stopDrawingListeners() {
        if (mapClickListener) {
            google.maps.event.removeListener(mapClickListener);
            mapClickListener = null;
        }
        if (mapDblClickListener) {
            google.maps.event.removeListener(mapDblClickListener);
            mapDblClickListener = null;
        }
        if (mapMoveListener) {
            google.maps.event.removeListener(mapMoveListener);
            mapMoveListener = null;
        }
        if (map) {
            map.setOptions({
                draggableCursor: null,
                disableDoubleClickZoom: false
            });
        }
    }

    function addVertex(latLng) {
        drawPath.push(latLng);
        var isFirst = drawMarkers.length === 0;
        var marker = new google.maps.Marker({
            position: latLng,
            map: map,
            clickable: isFirst,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: isFirst ? 8 : 5,
                fillColor: '#4285F4',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2
            },
            zIndex: isFirst ? 999 : 1
        });
        if (isFirst) {
            google.maps.event.addListener(marker, 'click', function () {
                finishPolygon();
            });
        }
        drawMarkers.push(marker);

        if (!drawPolyline) {
            drawPolyline = new google.maps.Polyline({
                path: drawPath,
                map: map,
                strokeColor: '#4285F4',
                strokeWeight: 2
            });
        } else {
            drawPolyline.setPath(drawPath);
        }
        updateDrawButtons();
    }

    function undoVertex() {
        if (!drawing || drawPath.length === 0) {
            return;
        }
        drawPath.pop();
        var marker = drawMarkers.pop();
        if (marker) {
            marker.setMap(null);
        }
        if (drawPolyline) {
            drawPolyline.setPath(drawPath);
        }
        if (previewLine && drawPath.length === 0) {
            previewLine.setMap(null);
            previewLine = null;
        }
        updateDrawButtons();
    }

    function finishPolygon() {
        if (!drawing || drawPath.length < 3) {
            return;
        }
        var poly = new google.maps.Polygon(Object.assign({
            paths: drawPath.slice(),
            map: map
        }, polygonOptions));
        bindPolygon(poly);
        stopDrawing();
        setHint('يمكنك سحب رؤوس المضلع للتعديل، أو الضغط على «إعادة الرسم» لبدء مضلع جديد.');
    }

    function startDrawing() {
        drawing = true;
        if (polygon) {
            polygon.setMap(null);
            polygon = null;
        }
        document.getElementById('coordinates').value = '';
        clearDraft();
        stopDrawingListeners();
        map.setOptions({
            draggableCursor: 'crosshair',
            disableDoubleClickZoom: true
        });
        mapClickListener = google.maps.event.addListener(map, 'click', function (event) {
            addVertex(event.latLng);
        });
        mapDblClickListener = google.maps.event.addListener(map, 'dblclick', function (event) {
            if (event && event.stop) {
                event.stop();
            }
            // The second click of a double-click already added a vertex
            if (drawPath.length > 3) {
                undoVertex();
            }
            finishPolygon();
        });
        mapMoveListener = google.maps.event.addListener(map, 'mousemove', function (event) {
            if (!drawPath.length) {
                return;
            }
            var previewPath = [drawPath[drawPath.length - 1], event.latLng];
            if (!previewLine) {
                previewLine = new google.maps.Polyline({
                    path: previewPath,
                    map: map,
                    strokeColor: '#4285F4',
                    strokeOpacity: 0.6,
                    strokeWeight: 2
                });
            } else {
                previewLine.setPath(previewPath);
            }
        });
        updateDrawButtons();
        setHint('انقر على الخريطة لإضافة نقاط المضلع (ثلاث نقاط على الأقل)، ثم انقر نقراً مزدوجاً أو اضغط «إنهاء الرسم». النقر على أول نقطة يغلق المضلع.');
    }

    function stopDrawing() {
        drawing = false;
        stopDrawingListeners();
        clearDraft();
        updateDrawButtons();
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

        if (existingCoords.length) {
            var path = existingCoords.map(function (p) {
                return new google.maps.LatLng(p.lat, p.lng);
            });
            var existing = new google.maps.Polygon(Object.assign({
                paths: path,
                map: map
            }, polygonOptions));
            bindPolygon(existing);
            var bounds = new google.maps.LatLngBounds();
            path.forEach(function (p) { bounds.extend(p); });
            map.fitBounds(bounds);
            setHint('يمكنك سحب رؤوس المضلع للتعديل، أو الضغط على «إعادة الرسم» لبدء مضلع جديد.');
        } else {
            startDrawing();
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

        document.getElementById('finish-polygon').addEventListener('click', finishPolygon);
        document.getElementById('undo-vertex').addEventListener('click', undoVertex);
        document.getElementById('redraw-polygon').addEventListener('click', startDrawing);
        updateDrawButtons();
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
