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

## SCSS structure (3.8.10.9)

Frontend styles are split into `tokens`, `tools`, `base`, `header`, `footer`, `components`, `swiper`, `motion`, `forms`, `blog`, `quality`, `sector`, `responsive` and `features`. Fluid typography uses the suite `fluid-font()` mixin and explicit viewport guards rather than `clamp()`. The generated production CSS intentionally contains no `!important` declarations.

### Build compatibility

The child build is dependency-free and works with Yarn 1.22.x as well as newer Yarn versions. No Corepack step is required. Use:

```sh
yarn prod
```

The command runs `node tools/build.mjs` and rebuilds the hashed CSS/JS manifest directly.
