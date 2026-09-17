<?php
/** Tech Shop 3.8.11.40 - accessible hero pager + compact homepage rhythm. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'wpbb_child_v138_enqueue' ) ) {
    function wpbb_child_v138_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = '/assets/suite-v138.css';
        $js  = '/assets/suite-v138.js';
        if ( is_readable( $dir . $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v138',
                $uri . $css,
                wp_style_is( 'wpbb-suite-v137', 'registered' ) || wp_style_is( 'wpbb-suite-v137', 'enqueued' ) ? array( 'wpbb-suite-v137' ) : array(),
                (string) filemtime( $dir . $css )
            );
        }
        if ( is_readable( $dir . $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v138',
                $uri . $js,
                wp_script_is( 'wpbb-suite-v137', 'registered' ) || wp_script_is( 'wpbb-suite-v137', 'enqueued' ) ? array( 'wpbb-suite-v137' ) : array(),
                (string) filemtime( $dir . $js ),
                true
            );
            wp_add_inline_script( 'wpbb-suite-v138', 'window.wpbbSuiteV138=' . wp_json_encode( array( 'version' => '3.8.11.40' ) ) . ';', 'before' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_child_v138_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v138_body_class' ) ) {
    function wpbb_child_v138_body_class( $classes ) {
        $classes[] = 'wpbb-v138';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_child_v138_body_class', PHP_INT_MAX );
