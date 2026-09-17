<?php
/**
 * Tech Shop 3.8.11.48 - deterministic homepage rail/grid recovery.
 *
 * Based on the stable v44 DB/Woo build. On the front page only, retire the
 * heuristic v142 grid marker and use the semantic classes already stored in
 * the real homepage block markup. Every normal content row shares one 1180px
 * rail; full-width bands keep only their background, not their content width.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_child_v148_enqueue' ) ) {
    function wpbb_child_v148_enqueue() {
        if ( is_admin() || ! is_front_page() ) {
            return;
        }

        /* v142 remains registered because later v143/v144 assets depend on its
         * handles. The homepage body class is removed below, which makes the
         * v142 CSS inert, and suite-v142.js contains a v148 guard so its old
         * DOM-guessing grid marker does not run on the front page. */

        $dir  = get_stylesheet_directory();
        $uri  = get_stylesheet_directory_uri();
        $file = $dir . '/assets/suite-v148.css';
        if ( ! is_readable( $file ) ) {
            return;
        }

        $deps = array();
        foreach ( array( 'wpbb-suite-v144', 'wpbb-suite-v139', 'wpbb-suite-v138', 'wpbb-suite-v118' ) as $handle ) {
            if ( wp_style_is( $handle, 'registered' ) || wp_style_is( $handle, 'enqueued' ) ) {
                $deps[] = $handle;
                break;
            }
        }

        wp_enqueue_style(
            'wpbb-suite-v148',
            $uri . '/assets/suite-v148.css',
            $deps,
            (string) filemtime( $file )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_child_v148_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v148_body_class' ) ) {
    function wpbb_child_v148_body_class( $classes ) {
        if ( is_front_page() ) {
            $classes = array_values( array_diff( $classes, array( 'wpbb-v142' ) ) );
            $classes[] = 'wpbb-v148';
        }
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_child_v148_body_class', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v148_cache_bust_once' ) ) {
    function wpbb_child_v148_cache_bust_once() {
        $key = 'wpbb_child_v148_cache_bust_' . sanitize_key( get_stylesheet() );
        if ( '3.8.11.48' === (string) get_option( $key ) ) {
            return;
        }
        if ( function_exists( 'wp_cache_flush' ) ) {
            wp_cache_flush();
        }
        update_option( $key, '3.8.11.48', false );
    }
}
add_action( 'init', 'wpbb_child_v148_cache_bust_once', 1 );
