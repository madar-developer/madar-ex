# WhatsApp Cloud API Integration

Production-oriented Meta WhatsApp Cloud API integration for Madar Express (Laravel 10).

## Overview

| Item | Value |
|------|--------|
| Meta App | LOC Backend Sender |
| WhatsApp Business Number | +966538825803 |
| Phone Number ID | `339316869258229` |
| WhatsApp Business Account ID | `304667792733449` |
| Graph API version | `v25.0` (configurable) |
| Auth to Meta | System User Access Token (env only) |
| Required permissions | `whatsapp_business_messaging`, `whatsapp_business_management` |

Sending acceptance by Meta is **not** the same as delivery. Delivery/read/failed updates arrive via webhooks.

## Meta Cloud API setup

1. In Meta Business Suite / Developers, open the WhatsApp product for **LOC Backend Sender**.
2. Confirm the phone number ID and WABA ID match the values above.
3. Create a **System User**, assign the WhatsApp assets, and generate a token with:
   - `whatsapp_business_messaging`
   - `whatsapp_business_management`
4. Store the token only in server environment variables (never in Git, logs, or frontend).
5. Create/approve message templates in the target languages before production sends.
6. Configure the webhook callback URL and verify token (see [Webhook setup](#webhook-setup)).

## Environment variables

Add to `.env` (see `.env.example`):

```dotenv
WHATSAPP_ENABLED=true
WHATSAPP_ACCESS_TOKEN=
WHATSAPP_PHONE_NUMBER_ID=339316869258229
WHATSAPP_BUSINESS_ACCOUNT_ID=304667792733449
WHATSAPP_API_VERSION=v25.0
WHATSAPP_DEFAULT_LANGUAGE=ar
WHATSAPP_WEBHOOK_VERIFY_TOKEN=
WHATSAPP_APP_SECRET=
```

Config is loaded from `config/whatsapp.php`.

Optional overrides:

- `WHATSAPP_GRAPH_BASE_URL` (default `https://graph.facebook.com`)
- `WHATSAPP_HTTP_TIMEOUT` / `WHATSAPP_HTTP_CONNECT_TIMEOUT`
- `WHATSAPP_SERVICE_WINDOW_HOURS` (default `24`)
- `WHATSAPP_QUEUE` (default `whatsapp`)

## System User token configuration

1. Meta Business Settings → Users → System Users.
2. Create/select a system user → Add Assets → WhatsApp account + phone number.
3. Generate token with the messaging permissions listed above.
4. Paste into `WHATSAPP_ACCESS_TOKEN` on the server only.
5. Rotate tokens periodically; update `.env` and reload PHP-FPM / queue workers.

Do **not** assume the token is already approved or present. If missing, the service returns a clear configuration error and does not call Meta.

## Messaging rules

- **Business-initiated** conversations must use an **approved template**.
- **Free-form text / image / document** are allowed only inside the **customer service window** (normally 24 hours after the customer’s last inbound message).
- Template **name** and **language** must match an approved template exactly.
- This integration does **not** bypass Meta policies.

Inbound webhooks update `whatsapp_conversations.last_inbound_at` to open/refresh the window.

## Database

Run migrations when ready (not executed automatically by this change):

```bash
php artisan migrate
```

Tables:

- `whatsapp_messages` — outbound tracking (`wa_message_id`, status, errors, idempotency)
- `whatsapp_conversations` — last inbound timestamp for the service window
- `whatsapp_webhook_events` — idempotent webhook event keys

## API endpoints

All send endpoints require:

- `Authorization: Bearer <company JWT>` (`auth:api-company`)
- Active company account
- Rate limit: `20` requests / minute / user

| Method | Path | Purpose |
|--------|------|---------|
| POST | `/api/whatsapp/send-orders` | Bulk send using company MessageTemplates |
| POST | `/api/whatsapp/send-template` | Send approved template |
| POST | `/api/whatsapp/send-text` | Free-form text (service window) |
| POST | `/api/whatsapp/send-image` | Image by URL (service window) |
| POST | `/api/whatsapp/send-document` | Document by URL (service window) |
| GET | `/api/webhooks/whatsapp` | Meta verify challenge |
| POST | `/api/webhooks/whatsapp` | Inbound events + statuses |

By default, send endpoints **queue** the message (`async=true`). Pass `"async": false` for a synchronous Graph API call (still subject to Meta policies).

### Example: send for order IDs (company templates)

Uses each order’s company + status MessageTemplate when WhatsApp is enabled on that template.

```http
POST /api/whatsapp/send-orders
Authorization: Bearer <company_jwt>
Content-Type: application/json

{
  "order_ids": [101, 102, 103],
  "status": "at_office",
  "async": true
}
```

- `status` is optional. If omitted, each order’s current `status` is used for template lookup.
- Only orders belonging to the authenticated company are processed.
- Configure `whatsapp_template_name`, language, and ordered `whatsapp_variables` on the admin Message Template screen.

### Example: send template (queued)

```http
POST /api/whatsapp/send-template
Authorization: Bearer <company_jwt>
Content-Type: application/json

{
  "phone": "+9665XXXXXXXX",
  "template_name": "hello_world",
  "language": "en_US",
  "components": [],
  "async": true,
  "idempotency_key": "order-123-hello"
}
```

Success response:

```json
{
  "data": { "queued": true },
  "message": "queued",
  "code": 200
}
```

### Example: send template (sync)

```json
{
  "phone": "+9665XXXXXXXX",
  "template_name": "hello_world",
  "language": "en_US",
  "components": [],
  "async": false
}
```

```json
{
  "data": {
    "message_id": "wamid....",
    "status": "accepted",
    "message_db_id": 1
  },
  "message": "success",
  "code": 200
}
```

### Example: send text

```json
{
  "phone": "+9665XXXXXXXX",
  "message": "مرحباً، شحنتك في الطريق",
  "async": true
}
```

Outside the service window this fails with a clear policy error — use a template instead.

### Phone numbers

- Must be international (E.164), e.g. `+9665XXXXXXXX` or `009665XXXXXXXX`.
- Country codes are preserved; the service **never** invents a default country (not EG, not SA, not anything else).
- Local numbers like `05xxxxxxxx` without a country code are rejected.

## Manual test: `hello_world`

1. Set `WHATSAPP_ENABLED=true` and a valid `WHATSAPP_ACCESS_TOKEN`.
2. Run migrations and a queue worker (or use `async: false` / `QUEUE_CONNECTION=sync` for a quick local check).
3. Obtain a company JWT via `POST /api/v1/company/signin`.
4. Send:

```bash
curl -X POST "%APP_URL%/api/whatsapp/send-template" ^
  -H "Authorization: Bearer YOUR_JWT" ^
  -H "Content-Type: application/json" ^
  -d "{\"phone\":\"+9665XXXXXXXX\",\"template_name\":\"hello_world\",\"language\":\"en_US\",\"components\":[],\"async\":false}"
```

5. Confirm Meta accepts the message (`status: accepted` + `message_id`). Delivery confirmation requires webhook status callbacks.

## Queue worker setup

Preferred production queue connection: `database` or `redis` (not `sync`).

```bash
php artisan queue:work --queue=whatsapp,default --tries=5 --timeout=60
```

Supervisor example command:

```text
php /path/to/madar-ex/artisan queue:work --queue=whatsapp,default --sleep=1 --tries=5 --timeout=60 --max-time=3600
```

Retry behaviour (`SendWhatsAppMessageJob`):

- Retries transient errors: `429`, `500`, `502`, `503`, `504`, connection failures
- Does **not** retry permanent client errors: `400`, `401`, `403`, validation/policy failures
- Backoff: 30s, 60s, 120s, 300s
- Optional `idempotency_key` avoids duplicate successful sends

## Webhook setup

1. Public HTTPS URL: `https://your-domain.com/api/webhooks/whatsapp`
2. In Meta App → WhatsApp → Configuration → Webhook:
   - Callback URL: the URL above
   - Verify token: same value as `WHATSAPP_WEBHOOK_VERIFY_TOKEN`
3. Subscribe to fields: `messages` (covers inbound messages and status updates)
4. Set `WHATSAPP_APP_SECRET` to the Meta App Secret (used for `X-Hub-Signature-256`)
5. Ensure queue workers are running so `ProcessWhatsAppWebhookJob` can process events

Verification (GET) checks `hub.mode`, `hub.verify_token`, and returns `hub.challenge`.

POST handling:

- Validates `X-Hub-Signature-256` with HMAC SHA-256 of the **raw** body
- Rejects invalid signatures
- Responds quickly with `EVENT_RECEIVED`
- Processes statuses (`sent`, `delivered`, `read`, `failed`) and inbound messages on the queue
- Deduplicates via `whatsapp_webhook_events.event_key`
- Does not store inbound message bodies

## Programmatic usage (backend)

```php
use App\Jobs\SendWhatsAppMessageJob;
use App\Services\WhatsApp\WhatsAppService;
use App\Support\OrderWhatsApp;

// Queue (preferred)
SendWhatsAppMessageJob::dispatch('template', '+9665XXXXXXXX', [
    'template_name' => 'hello_world',
    'language' => 'en_US',
    'components' => [],
], 'unique-key');

// Sync
app(WhatsAppService::class)->sendTemplate('+9665XXXXXXXX', 'hello_world', 'en_US');

// Approved Arabic delivery template (from an Order — queued)
OrderWhatsApp::sendImmediateDelivery($order);

// Same template with explicit variables
app(WhatsAppService::class)->sendImmediateDeliveryTemplate(
    '+9665XXXXXXXX',
    'أحمد محمد',
    'ORD-10254',
    'متجر الرياض',
    'الدفع عند الاستلام',
    '150.00',
    'MDX-98542'
);
```

## Error handling

| Situation | Behaviour |
|-----------|-----------|
| `WHATSAPP_ENABLED=false` | Exception / API error — no Meta call |
| Missing token / phone number ID | Configuration error |
| Invalid phone | Validation error (no Meta call) |
| Outside service window (text/media) | Policy error — use template |
| Meta `401` / `403` / `400` | Failed, not retried |
| Meta `429` / `5xx` | Failed record + job retry |
| Webhook bad signature | HTTP 403 |

Logs intentionally avoid message bodies and unnecessary phone dumps. Access tokens are never returned in API responses.

## Automated tests

```bash
php artisan test --filter=WhatsApp
```

Tests use `Http::fake()` and never call the real Graph API.

## Troubleshooting

| Symptom | Check |
|---------|--------|
| `(#100) Invalid parameter` | Template name/language/components mismatch |
| `(#190) OAuthException` | Token expired/revoked; regenerate System User token |
| `(#131047)` / messaging window errors | Customer has not messaged recently; send a template |
| Webhook verify fails | `WHATSAPP_WEBHOOK_VERIFY_TOKEN` mismatch |
| Webhook always 403 | `WHATSAPP_APP_SECRET` wrong or body altered by middleware |
| Queued but never sent | Queue worker not running / wrong queue name |
| Disabled errors | `WHATSAPP_ENABLED=true` |

## Production deployment checklist

- [ ] `WHATSAPP_ENABLED=true` only when ready
- [ ] System User token set on the server (not in Git)
- [ ] Phone Number ID + WABA ID confirmed
- [ ] Migrations applied
- [ ] Queue worker running for `whatsapp`
- [ ] Webhook URL HTTPS + verify token + app secret configured
- [ ] Webhook field `messages` subscribed
- [ ] Approved templates available (`hello_world` for smoke test; production templates for Arabic/ops)
- [ ] Rate limits and company JWT auth confirmed
- [ ] Log redaction reviewed (no tokens / message bodies)
- [ ] Manual `hello_world` / `en_US` smoke test completed on a test number you control

## Remaining manual Meta configuration

These cannot be completed from the Laravel codebase alone:

1. Generate and paste a valid System User access token.
2. Confirm token permissions on the WhatsApp Business assets.
3. Subscribe the webhook in Meta Developer Console.
4. Approve any production templates beyond Meta’s sample `hello_world`.
5. Optionally move the number out of any Meta “test/restricted” state for production traffic.
