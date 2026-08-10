# Company order status webhook (`notify_url`)

Per-company webhook for notifying external systems when an order **status changes**.

## Overview

Each company (store) can have a `notify_url` on the `companies` table. When an order belonging to that company gets a new status, Madar dispatches `SendCompanyWebhookJob`, which sends an HTTP **POST** request to that URL.

This is intended for **developer / integration** use (e.g. syncing status back to a merchant store or ERP).

## Configuration

Set the webhook URL in either place:

| Location | Path |
|----------|------|
| Admin — company create/edit | `/dashboard/companies/create` or `/dashboard/companies/{id}/edit` → field **Webhook — notify_url (للمطورين)** |
| Company portal — settings | Company settings → **Webhook Notify URL (POST method)** |

- Column: `companies.notify_url` (nullable string)
- Leave empty to disable webhooks for that company

## When it fires

The webhook runs when **all** of the following are true:

1. The order’s company has a non-empty `notify_url`
2. The request includes a `status` field
3. The new status is **different** from the current order status
4. The new status is not `new` (admin/driver flows)

### Trigger points in code

| Source | File |
|--------|------|
| Admin order update | `app/Traits/Admin/OrderOperations.php` → `UpdateRecords()` |
| Driver app — single order status update | `app/Http/Controllers/Api/Driver/OrderController.php` |
| Driver app — bulk status update | Same controller (batch update loop) |

Delivery is **asynchronous** via the queue (`SendCompanyWebhookJob` implements `ShouldQueue`).

## HTTP request

### Method

`POST`

### Headers

```
Content-Type: application/x-www-form-urlencoded
```

### Body (form fields)

| Field | Type | Description |
|-------|------|-------------|
| `refrence_no` | string | Merchant/reference order id stored on the order (`orders.refrence_no`) |
| `status` | string | New status key (see below) |

> **Note:** The field name is `refrence_no` (project spelling), not `reference_no`.

### Example

```http
POST /webhook/madar HTTP/1.1
Host: merchant.example.com
Content-Type: application/x-www-form-urlencoded

refrence_no=ORD-2024-001&status=delivered
```

### Example receiver (PHP)

```php
$refrenceNo = request('refrence_no');
$status     = request('status');

// Update your store order by refrence_no ...
return response()->json(['ok' => true]);
```

### Example with cURL (testing)

```bash
curl -X POST "https://merchant.example.com/webhook/madar" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "refrence_no=ORD-2024-001&status=delivered"
```

## Status values

`status` is the internal order status **key** (string), for example:

| Key | Typical meaning |
|-----|-----------------|
| `init` | Picked up / out for delivery |
| `not_received` | Not received from store |
| `at_madar` | At Madar hub |
| `at_office` | At branch office |
| `reschedule` | Rescheduled |
| `deliver_failed` | Delivery failed |
| `delivered` | Delivered |
| `returned` | Returned to merchant |
| `cancelled` | Cancelled |

Full labels and workflow are defined in `order_statuses` and `App\Models\Order::getLevels()`.

## Queue, retries, and logging

Job class: `app/Jobs/SendCompanyWebhookJob.php`

| Setting | Value |
|---------|--------|
| Attempts | 3 |
| Backoff between retries | 10 seconds |
| Connect timeout | 10 s |
| Request timeout | 30 s |

### Logs

- **Success:** `Log::info('Company Webhook Sent', …)` — includes URL, payload, HTTP status code, response body
- **Failure:** `Log::error('SendCompanyWebhookJob failed', …)` — includes URL, payload, error message

Ensure a queue worker is running in environments where webhooks must be delivered:

```bash
php artisan queue:work
```

## Implementation reference

| Piece | Location |
|-------|----------|
| DB column migration | `database/migrations/2022_03_27_074101_add_notify_url_to_companies.php` |
| Company model | `app/Models/Company.php` (`notify_url` in `$fillable`) |
| Webhook job | `app/Jobs/SendCompanyWebhookJob.php` |
| Admin company form | `resources/views/admin/companies/form.blade.php` |
| Company settings form | `resources/views/company/settings/admins-edit.blade.php` |

## Security notes

- Use **HTTPS** endpoints in production.
- Validate incoming requests on your side (shared secret header, IP allowlist, etc.) — not built into Madar today.
- The webhook sends only `refrence_no` and `status`; no customer PII is included in the payload.

## Related integrations

This webhook is **separate** from:

- **Salla** webhook routes under `/webhook/salla`
- **Madarx**-specific jobs (`SendMadarxWebhookJob`, `sendMadarxWebhook()`) used for specific company integrations

Those flows use different payloads and triggers.
