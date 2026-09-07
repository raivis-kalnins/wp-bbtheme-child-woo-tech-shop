<?php
/**
 * Complete classic WooCommerce single-product shell.
 * v3.8.10.75 explicitly initialises the WC_Product global before firing hooks;
 * this is required by gallery/price/add-to-cart modules when a block theme
 * resolves into this PHP compatibility template.
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--product">
  <div class="container wp-theme-woo-legacy__body wp-theme-woo-legacy__body--product">
    <?php
    if ( function_exists( 'woocommerce_breadcrumb' ) ) woocommerce_breadcrumb();
    if ( function_exists( 'wc_print_notices' ) ) wc_print_notices();
    while ( have_posts() ) : the_post();
        global $product;
        $current_product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
        if ( $current_product instanceof WC_Product ) $product = $current_product;
        if ( function_exists( 'wc_setup_product_data' ) ) wc_setup_product_data( get_the_ID() );
        if ( ! $product instanceof WC_Product ) continue;
        do_action( 'woocommerce_before_single_product' );
    ?>
      <article id="product-<?php the_ID(); ?>" <?php wc_product_class( 'wpbb-complete-product', $product ); ?>>
        <div class="wpbb-complete-product__main">
          <section class="wpbb-complete-product__media" aria-label="<?php esc_attr_e( 'Product gallery', 'woocommerce' ); ?>">
            <?php do_action( 'woocommerce_before_single_product_summary' ); ?>
          </section>
          <section class="summary entry-summary wpbb-complete-product__summary">
            <?php do_action( 'woocommerce_single_product_summary' ); ?>
          </section>
        </div>
        <div class="wpbb-complete-product__lower">
          <?php do_action( 'woocommerce_after_single_product_summary' ); ?>
        </div>
      </article>
      <?php do_action( 'woocommerce_after_single_product' ); ?>
    <?php endwhile; ?>
  </div>
</main>
<?php
if ( function_exists( 'wc_reset_loop' ) ) wc_reset_loop();
get_footer();
