# Madar Express — Order Tracking Integration Guide

This guide is for **companies / merchants** integrating with Madar Express to track delivery driver location.

You have **two options** (you can use either or both):

| # | Method | Who calls whom | Use case |
|---|--------|----------------|----------|
| 1 | **Tracking API** | Your system → Madar | Pull order details + current driver location |
| 2 | **Location Webhook** | Madar → Your system | Receive live driver location updates automatically |

**Base URL:** `https://madarex.sa`

**Important:** Live driver tracking is available only when the order status is `at_office` (out for delivery).

> Field name spelling: use `refrence_no` (not `reference_no`).

---

## Authentication

First get your `rest_token`:

```bash
curl -X POST "https://madarex.sa/api/get-token" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "email=YOUR_COMPANY_EMAIL" \
  -d "password=YOUR_PASSWORD"
```

**Sample response:**

```json
{
  "data": {
    "company": {
      "id": 10,
      "name": "My Store",
      "email": "store@example.com",
      "rest_token": "a1b2c3d4e5f6..."
    }
  },
  "message": "success",
  "code": 200
}
```

Save `rest_token` and use it in the tracking API below.

---

## 1) Tracking API (Pull)

Call Madar with your order reference number. Madar returns order details and, when available, the driver’s current location.

### Endpoint

```
POST https://madarex.sa/api/get-order-tracking
```

Also available as:

```
POST https://madarex.sa/api/v1/get-order-tracking
```

### Request parameters

| Parameter | Required | Type | Description |
|-----------|----------|------|-------------|
| `rest_token` | Yes | string | Your company integration token |
| `refrence_no` | Yes | string | Your order reference number (same value you sent when creating the order) |

### cURL example

```bash
curl -X POST "https://madarex.sa/api/get-order-tracking" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "rest_token=YOUR_REST_TOKEN" \
  -d "refrence_no=ORD-2024-001"
```

### Sample response — tracking available (`status = at_office`)

```json
{
  "data": {
    "tracking_available": true,
    "status": "at_office",
    "order": {
      "id": 69163,
      "refrence_no": "ORD-2024-001",
      "serial": "mx-20240900123",
      "serial_no": "20240900123",
      "status": "at_office",
      "status_txt": "جاري التوصيل",
      "recipent_name": "أحمد محمد",
      "phone": "0500000000",
      "city_name": "الرياض",
      "district_name": "الملز",
      "adress_details": "شارع الأمير محمد بن عبدالعزيز",
      "latitude": 24.7136,
      "longitude": 46.6753,
      "packages_number": 1,
      "price": 150,
      "driver_id": 52,
      "driver_name": "بشار عبدالله",
      "created_at": "2026-09-01 10:15:00"
    },
    "driver_location": {
      "lat": 24.6543418,
      "lng": 46.7339637,
      "timestamp": 1741386305989,
      "updated_at": "2026-09-05 12:00:05",
      "driver_id": 52,
      "driver_name": "بشار عبدالله",
      "source": "firestore"
    },
    "message": "success"
  },
  "message": "success",
  "code": 200
}
```

### Sample response — tracking not available (other status)

When the order is not out for delivery (for example `at_madar`, `delivered`, `init`, …):

```json
{
  "data": {
    "tracking_available": false,
    "status": "at_madar",
    "order": {
      "id": 69163,
      "refrence_no": "ORD-2024-001",
      "serial": "mx-20240900123",
      "serial_no": "20240900123",
      "status": "at_madar",
      "status_txt": "في مقر مدار",
      "recipent_name": "أحمد محمد",
      "phone": "0500000000",
      "city_name": "الرياض",
      "district_name": "الملز",
      "adress_details": "شارع الأمير محمد بن عبدالعزيز",
      "latitude": 24.7136,
      "longitude": 46.6753,
      "packages_number": 1,
      "price": 150,
      "driver_id": null,
      "driver_name": null,
      "created_at": "2026-09-01 10:15:00"
    },
    "driver_location": null,
    "message": "Tracking is only available when order status is at_office. Current status: at_madar"
  },
  "message": "Tracking is only available when order status is at_office. Current status: at_madar",
  "code": 200
}
```

### Sample response — order trackable but driver GPS not yet available

```json
{
  "data": {
    "tracking_available": true,
    "status": "at_office",
    "order": {
      "id": 69163,
      "refrence_no": "ORD-2024-001",
      "serial": "mx-20240900123",
      "status": "at_office",
      "status_txt": "جاري التوصيل",
      "recipent_name": "أحمد محمد",
      "phone": "0500000000",
      "driver_id": 52,
      "driver_name": "بشار عبدالله"
    },
    "driver_location": null,
    "message": "Order is trackable but driver location is not available yet"
  },
  "message": "Order is trackable but driver location is not available yet",
  "code": 200
}
```

### Error responses

**Invalid token**

```json
{
  "data": [],
  "errors": ["token error"],
  "message": "token error",
  "code": 103
}
```

**Missing `refrence_no`**

```json
{
  "data": [],
  "errors": ["refrence_no is required"],
  "message": "refrence_no is required",
  "code": 103
}
```

**Order not found**

```json
{
  "data": [],
  "errors": ["not found"],
  "message": "order not found",
  "code": 404
}
```

### How to use the response

```text
IF tracking_available == true AND driver_location != null
    → show map marker at driver_location.lat / driver_location.lng
ELSE IF tracking_available == false
    → show order.status / order.status_txt (tracking not available)
ELSE
    → order is out for delivery but GPS not received yet
```

---

## 2) Location Webhook (Push)

Register a URL on your Madar company account. Madar will **POST** to that URL whenever the driver location changes for your orders that are out for delivery (`at_office`).

### Setup

1. Open **Company settings** in the Madar portal  
   (or ask Madar admin to set it on your company profile)
2. Set **Location Webhook URL** (`location_notify_url`)  
   Example: `https://your-domain.com/webhooks/madar-driver-location`
3. Leave empty if you do not want push updates

### What Madar sends to your URL

| Item | Value |
|------|-------|
| Method | `POST` |
| Content-Type | `application/x-www-form-urlencoded` |

| Field | Type | Description |
|-------|------|-------------|
| `refrence_no` | string | Your order reference |
| `serial` | string | Madar tracking serial |
| `order_id` | number | Madar internal order id |
| `driver_id` | number | Driver id |
| `lat` | number | Driver latitude |
| `lng` | number | Driver longitude |
| `timestamp` | number | Location time (Unix milliseconds) |
| `status` | string | Order status (`at_office`) |

### Example of the request Madar sends to your server

```http
POST /webhooks/madar-driver-location HTTP/1.1
Host: your-domain.com
Content-Type: application/x-www-form-urlencoded

refrence_no=ORD-2024-001&serial=mx-20240900123&order_id=69163&driver_id=52&lat=24.6543418&lng=46.7339637&timestamp=1741386305989&status=at_office
```

### cURL — simulate the webhook Madar sends (for testing your endpoint)

Use this to test your webhook receiver before going live:

```bash
curl -X POST "https://your-domain.com/webhooks/madar-driver-location" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "refrence_no=ORD-2024-001" \
  -d "serial=mx-20240900123" \
  -d "order_id=69163" \
  -d "driver_id=52" \
  -d "lat=24.6543418" \
  -d "lng=46.7339637" \
  -d "timestamp=1741386305989" \
  -d "status=at_office"
```

### Expected response from your server

Return HTTP `200` quickly. Example:

```json
{
  "ok": true
}
```

Madar retries up to **3 times** (about 10 seconds apart) if your endpoint fails or times out.

### Sample PHP receiver

```php
<?php
// https://your-domain.com/webhooks/madar-driver-location

$refrenceNo = $_POST['refrence_no'] ?? null;
$lat        = $_POST['lat'] ?? null;
$lng        = $_POST['lng'] ?? null;
$status     = $_POST['status'] ?? null;
$timestamp  = $_POST['timestamp'] ?? null;

// 1) Find your order by refrence_no
// 2) Save / broadcast driver location (lat, lng)
// 3) Respond 200

header('Content-Type: application/json');
echo json_encode(['ok' => true]);
```

### Sample Node.js receiver (Express)

```js
app.post('/webhooks/madar-driver-location', express.urlencoded({ extended: true }), (req, res) => {
  const { refrence_no, lat, lng, status, timestamp, driver_id, serial, order_id } = req.body;

  // Update your order map / database...
  console.log({ refrence_no, lat, lng, status, timestamp });

  res.status(200).json({ ok: true });
});
```

---

## Quick comparison

| | Tracking API | Location Webhook |
|-|--------------|------------------|
| Direction | You call Madar | Madar calls you |
| Auth | `rest_token` | Your public HTTPS URL |
| When | On demand (poll) | On driver movement |
| Best for | On-demand map / status page | Live map without polling |

**Recommended:** use the **webhook** for live maps, and the **tracking API** as fallback / first load.

---

## Notes

1. Tracking works only for status `at_office`.
2. `driver_location` is read **live from Firebase Firestore** (same source as Madar admin live map).
3. Use HTTPS for your webhook URL in production.
4. `timestamp` is Unix time in **milliseconds**.
5. Coordinates are WGS84 (`lat` / `lng`).
6. Status change notifications use a separate webhook (`notify_url`) — not the same as location tracking.

---

## Support

For token issues or webhook configuration, contact Madar Express support or your account manager.
