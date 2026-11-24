# COD Cash & Online Payment Method

Documentation for sub payment methods with `required_action = "cod_cash_and_online"`.

## Overview

Payment methods can be nested (`parent`) and declare a `required_action`.

When a method has:

```text
required_action = cod_cash_and_online
```

the client must send:

- `cod_cash_amount`
- `cod_online_amount`

These amounts are stored on the order.

## Database

### `payment_methods`

| Column | Type | Default | Description |
|--------|------|---------|-------------|
| `parent` | integer | `0` | Parent payment method id (`0` = root) |
| `required_action` | string, nullable | `null` | Action required when selecting this method |

### `orders`

| Column | Type | Default | Description |
|--------|------|---------|-------------|
| `cod_cash_amount` | decimal(12,2) | `0` | Cash portion of COD |
| `cod_online_amount` | decimal(12,2) | `0` | Online portion of COD |

Migrations:

- `database/migrations/2026_11_24_114400_add_parent_and_required_action_to_payment_methods_table.php`
- `database/migrations/2026_11_24_115000_add_cod_amounts_to_orders_table.php`

Run:

```bash
php artisan migrate
```

## Dashboard setup

1. Open **وسائل الدفع** (`/dashboard/payment-methods`).
2. Create a root payment method (`parent = 0`) if needed.
3. Create a **child** method under that parent.
4. Set child `required_action` to:

```text
cod_cash_and_online
```

## Admin create / update order

Path: `/dashboard/orders/create` and `/dashboard/orders/{id}/edit`

- Selecting a payment method with `required_action = cod_cash_and_online` shows:
  - **مبلغ الكاش (COD Cash)** → `cod_cash_amount`
  - **مبلغ الاونلاين (COD Online)** → `cod_online_amount`
- Both fields are **required** for that payment method.
- Values are saved on the order.

## Driver API

### Order payload

`OrderResource` includes:

```json
{
  "payment_method_id": 1,
  "cod_cash_amount": "50.00",
  "cod_online_amount": "30.00",
  "payment_method": {
    "id": 1,
    "name": "COD",
    "parent": 0,
    "required_action": null
  },
  "available_payment_methods": [
    {
      "id": 5,
      "name": "COD Cash + Online",
      "parent": 1,
      "required_action": "cod_cash_and_online"
    }
  ]
}
```

`available_payment_methods` = children of the order’s current payment method.

### Change payment method

Auth: `auth:api-driver`

| Version | Method | Endpoint |
|---------|--------|----------|
| v1 | `POST` | `/api/v1/driver/orders/change-payment-method` |
| v2 | `POST` | `/api/v2/driver/orders/change-payment-method` |

#### Request body

```json
{
  "order_id": 123,
  "payment_method_id": 5,
  "cod_cash_amount": 50,
  "cod_online_amount": 30,
  "details": "optional note"
}
```

| Field | Required | Notes |
|-------|----------|-------|
| `order_id` | yes | Target order |
| `payment_method_id` | yes | Must be current method, a child, or a sibling under the same parent |
| `cod_cash_amount` | yes if `required_action = cod_cash_and_online` | numeric ≥ 0 |
| `cod_online_amount` | yes if `required_action = cod_cash_and_online` | numeric ≥ 0 |
| `details` | no | Stored in order log |

#### Success response

```json
{
  "data": {
    "order": { }
  },
  "message": "success",
  "code": 200
}
```

#### Error responses

| Case | `message` | `code` |
|------|-----------|--------|
| Order not found | no result | `404` |
| Payment method not found | `payment method not found` | `402` |
| Method not allowed for order | `payment method not allowed for this order` | `402` |
| Missing cash amount | `cod_cash_amount is required` | `402` |
| Missing online amount | `cod_online_amount is required` | `402` |

## Flow example

1. Order is created with parent payment method (e.g. COD).
2. Driver loads order → sees children in `available_payment_methods`.
3. Driver picks child with `required_action = "cod_cash_and_online"`.
4. Driver calls `change-payment-method` with both amounts.
5. Order updates `payment_method_id`, `cod_cash_amount`, `cod_online_amount`.
6. Admin can also set the same fields when creating/editing the order in the dashboard.

## Related files

- `app/Models/PaymentMethod.php`
- `app/Models/Order.php`
- `app/Http/Resources/Api/PMResource.php`
- `app/Http/Resources/Api/OrderResource.php`
- `app/Http/Controllers/Api/Driver/OrderController.php` → `changePaymentMethod`
- `app/Http/Controllers/Api/V2/Driver/OrderController.php` → `changePaymentMethod`
- `resources/views/admin/orders/form.blade.php`
- `resources/views/admin/payment-methods/form.blade.php`
- `routes/api.php`
