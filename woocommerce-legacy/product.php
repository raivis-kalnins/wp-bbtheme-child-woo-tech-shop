<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--product">
  <div class="container wp-theme-woo-legacy__body wp-theme-woo-legacy__body--product">
    <?php if ( function_exists( 'woocommerce_content' ) ) { woocommerce_content(); } ?>
  </div>
</main>
<?php get_footer(); ?>
