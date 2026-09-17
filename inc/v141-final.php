<?php
/**
 * Tech Shop 3.8.11.41 - deterministic homepage grid and final alignment owner.
 *
 * v137's DOM-shape grid detector is intentionally retired in favour of static,
 * section-specific CSS selectors. This keeps BBuilder/Woo markup untouched.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_child_v141_enqueue' ) ) {
    function wpbb_child_v141_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = $dir . '/assets/suite-v141.css';

        if ( is_readable( $css ) ) {
            $deps = array();
            foreach ( array( 'wpbb-suite-v139', 'wpbb-suite-v138', 'wpbb-suite-v137', 'wpbb-suite-v118' ) as $handle ) {
                if ( wp_style_is( $handle, 'registered' ) || wp_style_is( $handle, 'enqueued' ) ) {
                    $deps[] = $handle;
                    break;
                }
            }
            wp_enqueue_style(
                'wpbb-suite-v141',
                $uri . '/assets/suite-v141.css',
                $deps,
                (string) filemtime( $css )
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_child_v141_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v141_body_class' ) ) {
    function wpbb_child_v141_body_class( $classes ) {
        $classes[] = 'wpbb-v141';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_child_v141_body_class', PHP_INT_MAX );
