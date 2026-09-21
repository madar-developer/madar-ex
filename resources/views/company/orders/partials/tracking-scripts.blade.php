@if(!empty($trackingData))
<script type="text/javascript"
    src="https://maps.google.com/maps/api/js?sensor=false&libraries=places&key={{ getMapsKey() }}&language=ar"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-database.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-analytics.js"></script>
<script>
    var orderTrackingData = @json($trackingData);

    (function () {
        var mapEl = document.getElementById('order-live-track-map');
        var metaEl = document.getElementById('order-live-track-meta');
        var alertEl = document.getElementById('order-live-track-alert');
        if (!mapEl || typeof google === 'undefined' || !google.maps) {
            return;
        }

        var defaultCenter = { lat: 24.7255553, lng: 47.1027146 };
        var map = new google.maps.Map(mapEl, {
            zoom: 12,
            center: defaultCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var infowindow = new google.maps.InfoWindow();
        var bounds = new google.maps.LatLngBounds();
        var hasPoint = false;
        var mapFitted = false;
        var markers = {};
        var driverMarker = null;
        var routeLine = null;
        var driverId = parseInt(orderTrackingData.driver_id, 10);

        function extendBounds(lat, lng) {
            if (lat == null || lng == null || isNaN(lat) || isNaN(lng)) {
                return null;
            }
            var pos = { lat: parseFloat(lat), lng: parseFloat(lng) };
            bounds.extend(pos);
            hasPoint = true;
            return pos;
        }

        function addMarker(key, pos, options) {
            if (!pos) {
                return null;
            }
            if (markers[key]) {
                markers[key].setMap(null);
            }
            var marker = new google.maps.Marker(Object.assign({
                position: pos,
                map: map
            }, options || {}));
            markers[key] = marker;
            return marker;
        }

        function updateRouteLine() {
            if (routeLine) {
                routeLine.setMap(null);
            }
            var path = [];
            if (markers.start) {
                path.push(markers.start.getPosition().toJSON());
            }
            if (driverMarker) {
                path.push(driverMarker.getPosition().toJSON());
            }
            if (markers.destination) {
                path.push(markers.destination.getPosition().toJSON());
            }
            if (path.length < 2) {
                return;
            }
            routeLine = new google.maps.Polyline({
                path: path,
                geodesic: true,
                strokeColor: '#1565c0',
                strokeOpacity: 0.85,
                strokeWeight: 4,
                map: map
            });
        }

        function fitMapOnce() {
            if (mapFitted || !hasPoint) {
                return;
            }
            map.fitBounds(bounds, 48);
            mapFitted = true;
        }

        function showAlert(message) {
            if (!alertEl) {
                return;
            }
            alertEl.style.display = 'block';
            alertEl.textContent = message;
        }

        function hideAlert() {
            if (!alertEl) {
                return;
            }
            alertEl.style.display = 'none';
            alertEl.textContent = '';
        }

        if (orderTrackingData.start) {
            var startPos = extendBounds(orderTrackingData.start.lat, orderTrackingData.start.lng);
            var startMarker = addMarker('start', startPos, {
                title: orderTrackingData.start.label,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: '#2e7d32',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 2
                }
            });
            if (startMarker) {
                startMarker.addListener('click', function () {
                    var html = '<strong>' + orderTrackingData.start.label + '</strong>';
                    if (orderTrackingData.start.time) {
                        html += '<br>' + orderTrackingData.start.time;
                    }
                    infowindow.setContent(html);
                    infowindow.open(map, startMarker);
                });
            }
        } else {
            showAlert('لا يوجد سجل حضور للسائق — لن تظهر نقطة البداية.');
        }

        function setDestinationMarker(pos, title) {
            var destMarker = addMarker('destination', pos, {
                title: title || orderTrackingData.destination.label,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: '#c62828',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 2
                }
            });
            if (destMarker) {
                destMarker.addListener('click', function () {
                    infowindow.setContent('<strong>' + (title || orderTrackingData.destination.label) + '</strong>');
                    infowindow.open(map, destMarker);
                });
            }
            updateRouteLine();
            fitMapOnce();
        }

        function resolveDestination() {
            var dest = orderTrackingData.destination || {};
            if (dest.lat != null && dest.lng != null && !isNaN(dest.lat) && !isNaN(dest.lng)) {
                var destPos = extendBounds(dest.lat, dest.lng);
                setDestinationMarker(destPos, dest.label);
                return;
            }
            if (!dest.address) {
                showAlert('لا يوجد عنوان أو إحداثيات لموقع التسليم.');
                fitMapOnce();
                return;
            }
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: dest.address }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    var loc = results[0].geometry.location;
                    var destPos = extendBounds(loc.lat(), loc.lng());
                    setDestinationMarker(destPos, dest.label);
                } else {
                    showAlert('تعذر تحديد موقع عنوان التسليم على الخريطة.');
                    fitMapOnce();
                }
            });
        }

        function updateDriverMarker(data, location) {
            var lat = location.lat;
            var lng = location.long;
            var pos = extendBounds(lat, lng);
            if (!pos) {
                return;
            }

            var updatedAt = '';
            if (location.timestamp) {
                updatedAt = new Date(location.timestamp).toLocaleString('ar-SA');
            }

            var htmlCard = '<div class="map-menu">' +
                '<div class="d-name">' + (data.name || orderTrackingData.driver_name || 'السائق') + '</div>' +
                (updatedAt ? '<div style="margin-top:8px;font-size:12px;">آخر تحديث: ' + updatedAt + '</div>' : '') +
            '</div>';

            if (!driverMarker) {
                driverMarker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    title: data.name || orderTrackingData.driver_name || 'السائق',
                    icon: 'https://madarex.sa/Location.png'
                });
                driverMarker.addListener('click', function () {
                    infowindow.setContent(htmlCard);
                    infowindow.open(map, driverMarker);
                });
            } else {
                driverMarker.setPosition(pos);
            }

            if (metaEl && updatedAt) {
                metaEl.textContent = 'آخر تحديث لموقع السائق: ' + updatedAt;
            }

            hideAlert();
            updateRouteLine();
            fitMapOnce();
        }

        resolveDestination();

        if (!orderTrackingData.driver_id) {
            showAlert('لم يتم تعيين سائق لهذا الطلب — لن يظهر موقع السائق.');
            return;
        }

        if (typeof firebase === 'undefined') {
            showAlert('تعذر تحميل خدمة تتبع السائق.');
            return;
        }

        var firebaseConfig = {
            apiKey: "AIzaSyASV6ryM8d7tfsgxEULmT9j3GIqEM0O7rY",
            authDomain: "madarexpress.firebaseapp.com",
            databaseURL: "https://madarexpress.firebaseio.com",
            projectId: "madarexpress",
            storageBucket: "madarexpress.appspot.com",
            messagingSenderId: "306328789253",
            appId: "1:306328789253:web:c874611b2b87e13bedd76c",
            measurementId: "G-S8PYJ0VE2T"
        };

        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
            firebase.analytics();
        }

        var db = firebase.firestore();
        var driverFound = false;

        db.collection('drivers').onSnapshot(function (querySnapshot) {
            var matched = false;

            querySnapshot.forEach(function (doc) {
                var data = doc.data() || {};
                if (parseInt(data.id, 10) !== driverId) {
                    return;
                }

                matched = true;

                if (data.locations !== undefined && data.locations[0] !== undefined) {
                    driverFound = true;
                    updateDriverMarker(data, data.locations[0]);
                } else {
                    showAlert('لا توجد إحداثيات حالية للسائق.');
                }
            });

            if (!matched && !driverFound) {
                showAlert('لم يتم العثور على موقع السائق في Firestore.');
            }
        }, function () {
            showAlert('حدث خطأ أثناء الاتصال بخدمة تتبع السائق.');
        });
    })();
</script>
@endif
