<?php
/**
 * Classic WooCommerce compatibility fallback.
 * v3.6 routes the primary shop/customer pages through woocommerce-legacy/*.php,
 * but keeping woocommerce.php also makes the theme a conventional classic Woo shell.
 */
defined( 'ABSPATH' ) || exit;
$file = ( function_exists( 'is_product' ) && is_product() ) ? 'product.php' : 'catalog.php';
$path = trailingslashit( get_stylesheet_directory() ) . 'woocommerce-legacy/' . $file;
if ( is_readable( $path ) ) require $path;
