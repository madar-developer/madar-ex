# Madar Express — Single Driver Real-Time Tracking

Integration guide for tracking **one driver** live on a map.

**Location source:** Firebase Firestore collection `drivers`  
**Not** MySQL. Live GPS is written by the driver app into Firestore.

---

## Architecture

```
Driver mobile app
      │  writes GPS
      ▼
Firestore  collection: drivers
      │  onSnapshot (real-time)
      ▼
Your web / app map  (marker moves when locations[0] changes)
```

| Piece | Value |
|--------|--------|
| Firebase project | `madarexpress` |
| Collection | `drivers` |
| Match driver by | document field `id` (integer) = your Madar driver id |
| Live position | `locations[0].lat` + `locations[0].long` |
| Optional time | `locations[0].timestamp` (milliseconds since epoch) |

---

## 1) What you need as input

| Input | Type | Example | Notes |
|-------|------|---------|--------|
| `driver_id` | integer | `52` | Madar driver primary key (`drivers.id` in MySQL) |

You do **not** need the Firestore document auto-id (e.g. `bCk9wQugCJfjKcGcpOaW`).  
Always match on the numeric field `data.id`.

---

## 2) Firebase Web SDK setup

### Scripts (Firebase v7 — same as Madar admin)

```html
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-firestore.js"></script>
```

### Config

```js
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
}

var db = firebase.firestore();
```

> For production, prefer injecting config from your backend/env instead of hardcoding.

---

## 3) Firestore document shape

Document id is usually a random Firestore id. The important fields:

```json
{
  "id": 52,
  "name": "بشار عبدالله",
  "phone": "05xxxxxxxx",
  "email": "driver@example.com",
  "order_count": 10,
  "delivering_orders_count": 2,
  "order_delivered_count": 7,
  "order_failed_count": 1,
  "cities": [],
  "locations": [
    {
      "lat": 24.6543418,
      "long": 46.7339637,
      "timestamp": 1741386305989
    }
  ]
}
```

### Location object (use only `locations[0]`)

| Field | Type | Required for map | Notes |
|-------|------|------------------|--------|
| `lat` | number | yes | Latitude |
| `long` | number | yes | Longitude (**`long`**, not `lng`) |
| `timestamp` | number | no | Epoch **ms** — use for “last update” label |

If `locations` is missing or `locations` is `[]`, the driver exists but **cannot** be plotted yet.

---

## 4) Recommended: listen to one driver only

Prefer a filtered query (cheaper / clearer than loading the whole collection):

```js
var driverId = 52; // your Madar driver id
var unsubscribe = null;
var driverMarker = null;
var map = /* your google.maps.Map instance */;
var infowindow = new google.maps.InfoWindow();

function updateDriverMarker(data, location) {
  var lat = location.lat;
  var lng = location.long; // IMPORTANT: field name is "long"
  if (lat == null || lng == null) {
    return;
  }

  var pos = new google.maps.LatLng(lat, lng);
  var updatedAt = location.timestamp
    ? new Date(location.timestamp).toLocaleString()
    : '';

  var html =
    '<div class="map-menu">' +
      '<div class="d-name">' + (data.name || ('Driver #' + driverId)) + '</div>' +
      (updatedAt ? '<div>Last update: ' + updatedAt + '</div>' : '') +
    '</div>';

  if (!driverMarker) {
    driverMarker = new google.maps.Marker({
      position: pos,
      map: map,
      title: data.name || ('Driver #' + driverId),
      icon: 'https://madarex.sa/Location.png' // optional Madar icon
    });
    driverMarker.addListener('click', function () {
      infowindow.setContent(html);
      infowindow.open(map, driverMarker);
    });
  } else {
    driverMarker.setPosition(pos);
  }

  // Optional: keep map centered on driver
  // map.panTo(pos);
}

function startTrackingDriver(driverId) {
  // Try integer match first (Firestore often stores id as number)
  unsubscribe = db.collection('drivers')
    .where('id', '==', Number(driverId))
    .limit(1)
    .onSnapshot(function (snapshot) {
      if (snapshot.empty) {
        // Fallback: some docs may store id as string
        listenDriverAsString(driverId);
        return;
      }

      snapshot.forEach(function (doc) {
        var data = doc.data() || {};
        if (data.locations && data.locations[0]) {
          updateDriverMarker(data, data.locations[0]);
        } else {
          console.warn('Driver found but locations[0] is empty');
        }
      });
    }, function (err) {
      console.error('Firestore listen error', err);
    });
}

function listenDriverAsString(driverId) {
  if (unsubscribe) unsubscribe();
  unsubscribe = db.collection('drivers')
    .where('id', '==', String(driverId))
    .limit(1)
    .onSnapshot(function (snapshot) {
      snapshot.forEach(function (doc) {
        var data = doc.data() || {};
        if (data.locations && data.locations[0]) {
          updateDriverMarker(data, data.locations[0]);
        }
      });
    });
}

// Start
startTrackingDriver(driverId);

// Stop when leaving the page
// if (unsubscribe) unsubscribe();
```

### Firestore index note

If the console asks for a composite index for `where('id', '==', …)`, create it from the Firebase link in the error, or ensure a single-field index exists on `id` (usually automatic).

---

## 5) Alternative: same pattern as Madar order show page

Madar currently listens to the full `drivers` collection and filters client-side:

```js
var driverId = 52;

db.collection('drivers').onSnapshot(function (querySnapshot) {
  var matched = false;

  querySnapshot.forEach(function (doc) {
    var data = doc.data() || {};

    // Match Madar MySQL driver id — NOT Firestore document id
    if (parseInt(data.id, 10) !== driverId) {
      return;
    }

    matched = true;

    if (data.locations !== undefined && data.locations[0] !== undefined) {
      updateDriverMarker(data, data.locations[0]);
    } else {
      console.warn('No current coordinates for this driver');
    }
  });

  if (!matched) {
    console.warn('Driver not found in Firestore');
  }
}, function () {
  console.error('Failed to connect to driver tracking');
});
```

Use this if a filtered query is blocked by rules/indexes. Prefer section 4 for production.

---

## 6) Google Maps marker essentials

```js
// First position
new google.maps.Marker({
  position: new google.maps.LatLng(lat, lng),
  map: map,
  icon: 'https://madarex.sa/Location.png'
});

// Later updates (do NOT recreate marker every snapshot)
driverMarker.setPosition(new google.maps.LatLng(lat, lng));
```

Madar admin order page also shows optional stats from the same document:

- `order_count`
- `delivering_orders_count`
- `order_delivered_count`
- `order_failed_count`

These are informational only; the map only needs `locations[0]`.

---

## 7) Matching rules (important)

| Do | Don't |
|----|--------|
| Match `parseInt(data.id, 10) === driverId` | Match Firestore `doc.id` (random string) alone |
| Read `locations[0].long` | Expect `lng` (driver app writes `long`) |
| Guard empty `locations` / `locations[0]` | Assume every driver has GPS |
| Reuse one marker and `setPosition` | Clear/recreate all markers on every snapshot |

`doc.id` example: `zNozfl7GoW8WAOMHc55C`  
`data.id` example: `52` ← this is what you filter on

---

## 8) Acceptance checklist

- [ ] Firebase initialized once (`firebase.apps.length` guard)
- [ ] Listening with `onSnapshot` (not a one-shot `get` only)
- [ ] Filter by Madar `driver_id` → field `id`
- [ ] Marker appears when `locations[0]` has `lat` + `long`
- [ ] Marker moves when the driver app updates GPS
- [ ] Empty `locations` shows a clear “no location yet” state
- [ ] Unsubscribe / detach listener on page destroy

---

## 9) Common issues

| Symptom | Cause | Fix |
|---------|--------|-----|
| Driver object in console, nothing on map | `locations: []` | Wait for GPS / check driver app is online |
| Never matches | Filtering by `doc.id` | Use `data.id` |
| Marker at (0,0) or NaN | Using `lng` instead of `long` | Use `location.long` |
| Works once, never updates | Used `.get()` only | Use `.onSnapshot()` |
| All drivers flash then disappear | Cleared markers without re-adding valid ones | Update only the one marker for your `driver_id` |

---

## 10) Minimal end-to-end example

```html
<div id="map" style="width:100%;height:480px;"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_KEY"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-firestore.js"></script>
<script>
  var DRIVER_ID = 52; // replace with real Madar driver id

  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: { lat: 24.7136, lng: 46.6753 },
    mapTypeId: 'roadmap'
  });

  firebase.initializeApp({
    apiKey: "AIzaSyASV6ryM8d7tfsgxEULmT9j3GIqEM0O7rY",
    authDomain: "madarexpress.firebaseapp.com",
    databaseURL: "https://madarexpress.firebaseio.com",
    projectId: "madarexpress",
    storageBucket: "madarexpress.appspot.com",
    messagingSenderId: "306328789253",
    appId: "1:306328789253:web:c874611b2b87e13bedd76c",
    measurementId: "G-S8PYJ0VE2T"
  });

  var db = firebase.firestore();
  var marker = null;

  db.collection('drivers').onSnapshot(function (snap) {
    snap.forEach(function (doc) {
      var data = doc.data() || {};
      if (parseInt(data.id, 10) !== DRIVER_ID) return;
      if (!data.locations || !data.locations[0]) return;

      var lat = data.locations[0].lat;
      var lng = data.locations[0].long;
      var pos = new google.maps.LatLng(lat, lng);

      if (!marker) {
        marker = new google.maps.Marker({
          position: pos,
          map: map,
          icon: 'https://madarex.sa/Location.png'
        });
        map.setCenter(pos);
      } else {
        marker.setPosition(pos);
      }
    });
  });
</script>
```

---

## Reference in this repo

| File | Role |
|------|------|
| `resources/views/admin/orders/show.blade.php` | Single-driver live map (best reference) |
| `resources/views/admin/main.blade.php` | Multi-driver dashboard map |
| `resources/views/company/orders/partials/tracking-scripts.blade.php` | Company single-driver map |
| `app/Services/FirestoreDriverLocationService.php` | Server-side one-shot read of same fields |

---

## Related docs (API / webhook — optional)

If the integrator cannot use Firestore client SDK and only needs pull/push HTTP:

- `docs/company-tracking-integration-guide.md`
- `docs/company-order-tracking.md`

Those APIs track by **order**, not by arbitrary driver id, and only while order status is `at_office`.
