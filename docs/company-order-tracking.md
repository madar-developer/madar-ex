# Company order tracking API & location webhook

Companies can **pull** driver location for an order, and optionally receive **push** updates via webhook.

Tracking is available only when order status is **`at_office`** (out for delivery).  
For any other status, the API returns the current status and `tracking_available: false`.

**Driver location source:** Firebase Firestore collection `drivers` → `locations[0].lat` / `locations[0].long`  
(same as the admin order show live map — not MySQL).

---

## 1) Tracking API (company pulls)

Company calls Madar with the order `refrence_no`. Madar returns order details + driver location when trackable.

### Merchant REST token (recommended for integrations)

```
POST /api/get-order-tracking
POST /api/v1/get-order-tracking
```

| Field | Required | Description |
|-------|----------|-------------|
| `rest_token` | yes | Company integration token from `get-token` |
| `refrence_no` | yes | Merchant order reference (`orders.refrence_no`) |

#### Example

```bash
curl -X POST "https://madarex.sa/api/get-order-tracking" \
  -d "rest_token=YOUR_TOKEN" \
  -d "refrence_no=ORD-2024-001"
```

#### Trackable response (`status = at_office`)

```json
{
  "data": {
    "tracking_available": true,
    "status": "at_office",
    "order": {
      "id": 69163,
      "refrence_no": "ORD-2024-001",
      "serial": "mx-...",
      "status": "at_office",
      "status_txt": "...",
      "recipent_name": "...",
      "phone": "...",
      "city_name": "...",
      "adress_details": "...",
      "latitude": 24.7,
      "longitude": 46.6,
      "driver_id": 52,
      "driver_name": "بشار عبدالله"
    },
    "driver_location": {
      "lat": 24.6543418,
      "lng": 46.7339637,
      "timestamp": 1741386305989,
      "updated_at": "2026-09-05 12:00:00",
      "driver_id": 52,
      "driver_name": "بشار عبدالله"
    },
    "message": "success"
  },
  "message": "success",
  "code": 200
}
```

#### Not trackable (any other status)

```json
{
  "data": {
    "tracking_available": false,
    "status": "at_madar",
    "order": { "...": "order details still included" },
    "driver_location": null,
    "message": "Tracking is only available when order status is at_office. Current status: at_madar"
  },
  "message": "Tracking is only available when order status is at_office. Current status: at_madar",
  "code": 200
}
```

### Company JWT app

```
GET|POST /api/v1/company/orders-tracking?refrence_no=ORD-2024-001
Authorization: Bearer {company_jwt}
```

Same response shape.

---

## 2) Location webhook (Madar pushes to company)

Company stores a URL on their account. Whenever the assigned driver location changes for an `at_office` order, Madar **POSTs** to that URL.

### Configuration

| Place | Field |
|-------|-------|
| Admin → company form | **Webhook — location_notify_url** |
| Company portal → settings | **Location Webhook URL** |

Column: `companies.location_notify_url`  
Leave empty to disable.

### When Madar calls it

1. Driver app posts GPS to `POST /api/v1/driver/location`
2. Driver has company orders with `status = at_office`
3. Company has `location_notify_url` set
4. Throttle: moved ≥ 50m and ≥ 30s since last push

### Payload Madar sends

`POST` · `Content-Type: application/x-www-form-urlencoded`

| Field | Description |
|-------|-------------|
| `refrence_no` | Your order reference |
| `serial` | Madar serial |
| `order_id` | Madar order id |
| `driver_id` | Driver id |
| `lat` | Driver latitude |
| `lng` | Driver longitude |
| `timestamp` | Unix milliseconds |
| `status` | Order status (`at_office`) |

```bash
# Example of what Madar sends to your server
curl -X POST "https://your-store.com/webhook/driver-location" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "refrence_no=ORD-2024-001&serial=mx-1&order_id=69163&driver_id=52&lat=24.65&lng=46.73&timestamp=1741386305989&status=at_office"
```

### Queue

Uses `SendLocationWebhookJob` (3 retries, 10s backoff). Run:

```bash
php artisan queue:work
```

---

## 3) Driver location source (internal)

Driver mobile app should call:

```
POST /api/v1/driver/location
Authorization: Bearer {driver_jwt}
```

```json
{ "lat": 24.6543418, "lng": 46.7339637, "timestamp": 1741386305989 }
```

This stores location on the driver and triggers company webhooks.

---

## Related

- Status change webhook (`notify_url`): `docs/company-order-webhook.md` — different from location
- Migration: `database/migrations/2026_09_05_000001_add_driver_location_and_company_location_notify_url.php`
