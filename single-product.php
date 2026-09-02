<?php
/**
 * WooCommerce single product compatibility entrypoint.
 * Keeps classic Woo hooks working even when WordPress selects a block template first.
 */
defined( 'ABSPATH' ) || exit;
$legacy = trailingslashit( get_stylesheet_directory() ) . 'woocommerce-legacy/product.php';
if ( is_readable( $legacy ) ) {
    require $legacy;
    return;
}
if ( function_exists( 'wc_get_template' ) ) wc_get_template( 'content-single-product.php' );
