<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--product">
  <div class="container wp-theme-woo-legacy__body wp-theme-woo-legacy__body--product">
    <?php while ( have_posts() ) : the_post(); ?>
      <?php do_action( 'woocommerce_before_single_product' ); ?>
      <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'wpbb-complete-product', get_the_ID() ); ?>>
        <div class="wpbb-complete-product__main">
          <div class="wpbb-complete-product__media"><?php do_action( 'woocommerce_before_single_product_summary' ); ?></div>
          <div class="summary entry-summary wpbb-complete-product__summary"><?php do_action( 'woocommerce_single_product_summary' ); ?></div>
        </div>
        <div class="wpbb-complete-product__lower"><?php do_action( 'woocommerce_after_single_product_summary' ); ?></div>
      </div>
      <?php do_action( 'woocommerce_after_single_product' ); ?>
    <?php endwhile; ?>
  </div>
</main>
<?php get_footer(); ?>
