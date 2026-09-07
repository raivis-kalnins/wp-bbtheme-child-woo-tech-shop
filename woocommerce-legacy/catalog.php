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
    echo wpbb_tech_render_native_products_v64();
    ?>
  </div>
</main>
<?php get_footer(); ?>
