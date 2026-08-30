# WP BBTheme Child Woo Tech Shop 3.7.0

Technology WooCommerce child theme. Reusable ecommerce filtering/minicart functionality remains in **WP Theme Woo Support**; this theme owns presentation and Woo page shells.

## v3.7 classic Woo customer journey

The child now routes the front end through version-safe PHP shells for:

- Shop and product taxonomy catalogue (retaining the shared AJAX filter/results layer).
- Single product through the installed WooCommerce legacy product engine.
- Basket/Cart through `WC_Shortcode_Cart`.
- Checkout and order-received through `WC_Shortcode_Checkout`.
- My Account and endpoints through `WC_Shortcode_My_Account`.

This deliberately uses the legacy templates shipped by the **installed WooCommerce version** instead of bundling stale copies. The child adds the polished tables, forms, checkout/order, account navigation, responsive behaviour and product-gallery theme support.

The latest supplied localhost DB reports WooCommerce 11.0.1 with HPOS enabled.

Run `yarn prod`. After upgrading, run **Appearance → Starter Setup → Import / Refresh Starter Website**.
