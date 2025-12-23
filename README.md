# Company Order Metadata

WooCommerce plugin to add **immutable internal reference codes** to orders at creation time.

The reference code is generated once per order, stored as order meta, and displayed in the WooCommerce admin.  
It is designed to be lightweight, predictable, and safe for high-volume stores.

---

## ✨ Features

- Generates a unique internal reference code on order creation
- Code is **immutable** (created once, never regenerated)
- Displayed as **read-only** in the WooCommerce admin order view
- Filterable reference format for extensibility
- Compatible with WooCommerce **HPOS (High-Performance Order Storage)**
- No settings screen, no background processes, no overhead

---

## 🧱 Architecture

- Fully namespaced plugin
- Single-responsibility classes
- No global functions
- Pure PHP logic isolated for unit testing
- WooCommerce hooks only (no custom tables)

The plugin is intentionally minimal and avoids unnecessary abstractions.

---

## 🪝 Hooks Used

### `woocommerce_checkout_order_processed`

Used to ensure:
- The order ID already exists
- The reference code is generated **only once**
- No duplication on order updates or edits

---

### `woocommerce_admin_order_data_after_order_details`

Used to display the reference code as **read-only metadata** in the admin order screen.

---

## 🧩 Reference Code Format

Default format: CMP-{ORDER_ID}-{YEAR}


Example: CMP-3324-2025


The format is deterministic and human-readable by design.

---

## 🔌 Filters

### `company_order_reference_code`

Allows modifying the reference code before it is saved.

```php
add_filter( 'company_order_reference_code', function ( $code, $order ) {
	return 'CUSTOM-' . $order->get_id();
}, 10, 2 );
```

## 🧪 Testing

This project includes **pure PHPUnit unit tests**.  
No WordPress bootstrap or database is required.

### What is tested

- Reference code generation logic
- Explicit year handling
- Default current-year fallback
- Filter override behavior
- No-filter fallback behavior

### Run tests locally

```bash
php vendor/phpunit/phpunit/phpunit -c phpunit.xml.dist
```

## 🚀 Performance Considerations

- Runs only once per order
- Single meta write + single read
- No frontend execution
- No additional database queries
- Safe for large WooCommerce stores

---

## 📦 Requirements

- WordPress 5.8+
- PHP 7.4+
- WooCommerce 5.0+

---

## 📜 License

GPL v2 or later  
Fully compatible with WordPress and WooCommerce licensing.

