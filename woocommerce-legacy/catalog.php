<?php
defined( 'ABSPATH' ) || exit;
get_header();
$is_tax = is_product_taxonomy();
$title = $is_tax ? single_term_title( '', false ) : __( 'Shop the collection.', 'wp-theme' );
$eyebrow = $is_tax ? __( 'Collection', 'wp-theme' ) : __( 'Shop', 'wp-theme' );
?>
<main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--catalog wp-theme-woo-archive">
  <section class="wp-theme-woo-legacy__hero">
    <div class="container">
      <p class="wp-theme-sector-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
      <h1><?php echo esc_html( $title ); ?></h1>
      <?php if ( $is_tax && term_description() ) : ?><div class="wp-theme-woo-legacy__intro"><?php echo wp_kses_post( term_description() ); ?></div><?php endif; ?>
    </div>
  </section>
  <div class="container wp-theme-woo-legacy__body">
    <?php
    if ( shortcode_exists( 'iws_product_filter' ) && shortcode_exists( 'iws_product_filter_results' ) ) {
        echo do_shortcode( '[iws_product_filter posts_per_page="12"]' );
        echo do_shortcode( '[iws_product_filter_results posts_per_page="12"]' );
    } elseif ( function_exists( 'woocommerce_content' ) ) {
        woocommerce_content();
    }
    ?>
  </div>
</main>
<?php get_footer(); ?>
