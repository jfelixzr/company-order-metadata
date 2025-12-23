Company Order Metadata

Extends WooCommerce to add an internal, immutable reference code to each order at creation time.

Overview

This plugin generates a custom internal reference code for WooCommerce orders using the format:

CMP-{ORDER_ID}-{YEAR}


The reference code is:

Generated once at order creation

Stored as order meta

Displayed as read-only in the WooCommerce admin

Fully compatible with WooCommerce HPOS (High-Performance Order Storage)

Architecture

Namespaced plugin (CompanyOrderMetadata)

Single entry point (CompanyOrderMetadataPlugin)

Clear separation of responsibilities:

Domain logic (ReferenceCode)

WooCommerce integration (OrderMetadata)

No global functions

Extensibility via WordPress hooks (no inheritance)

Hooks Used
woocommerce_checkout_order_processed

Used to generate and persist the reference code once the order ID exists.

Idempotent: prevents duplicate generation

Runs only on order creation

woocommerce_admin_order_data_after_order_details

Displays the reference code in the order admin screen as read-only metadata.

Extensibility

The reference code can be modified by third-party code using the filter:

add_filter(
  'company_order_reference_code',
  function ( $code, $order ) {
    return 'CUSTOM-' . $code;
  },
  10,
  2
);

HPOS Compatibility

This plugin is fully compatible with WooCommerce High-Performance Order Storage (HPOS).

Uses only the WC_Order API

No direct database queries

Declares compatibility using FeaturesUtil

FeaturesUtil::declare_compatibility(
  'custom_order_tables',
  __FILE__,
  true
);

Testing

This project includes unit tests only (no WordPress or database dependency).

Why unit tests only?

Local development on Windows / LocalWP

Faster and more reliable CI execution

Business logic isolated from infrastructure

Integration tests (WordPress + WooCommerce + MySQL) are intended to be run in Linux/Docker or CI environments if required.

Running Tests Locally

From the plugin root directory:

composer install
php vendor/phpunit/phpunit/phpunit -c phpunit.xml.dist

Test Coverage

Unit tests validate:

Reference code format

Default year handling

Filter override behavior

Filter isolation between tests

Tests are located in:

tests/unit/

Performance Considerations

Executes only once per order

Single meta read + single meta write

No queries on admin list or frontend

Safe for high-volume WooCommerce stores

File Structure
company-order-metadata/
├─ .github/workflows/
│  └─ unit-tests.yml
├─ includes/
│  ├─ class-order-metadata.php
│  └─ class-reference-code.php
├─ tests/
│  ├─ bootstrap.php
│  └─ unit/
│     └─ ReferenceCodeTest.php
├─ company-order-metadata.php
├─ composer.json
├─ phpunit.xml.dist
└─ README.md

Possible Improvements

REST API exposure for reference code

Optional regeneration tool (admin-only)

Integration test suite using Docker / CI

Bulk export support

License

GPL v2 or later