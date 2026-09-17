<?php
/**
 * Tech Shop 3.8.11.42 - restore Automotive parity for grids and hero alignment.
 */
defined( 'ABSPATH' ) || exit;

/* v141's static grid/1320px owner did not match every rendered BBuilder wrapper. */
remove_action( 'wp_enqueue_scripts', 'wpbb_child_v141_enqueue', PHP_INT_MAX );
remove_filter( 'body_class', 'wpbb_child_v141_body_class', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v142_enqueue' ) ) {
    function wpbb_child_v142_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        /* Safety for cached/plugin-reordered enqueue stacks. */
        wp_dequeue_style( 'wpbb-suite-v141' );
        wp_deregister_style( 'wpbb-suite-v141' );

        /* v41 cached a no-op v137 grid marker in some browsers. Keep the v137
         * dependency handle for v138/v139, but run grid ownership from a fresh
         * v142 URL so stale browser/proxy caches cannot win. */

        $css = '/assets/suite-v142.css';
        if ( is_readable( $dir . $css ) ) {
            $deps = array();
            foreach ( array( 'wpbb-suite-v139', 'wpbb-suite-v138', 'wpbb-suite-v137', 'wpbb-suite-v118' ) as $handle ) {
                if ( wp_style_is( $handle, 'registered' ) || wp_style_is( $handle, 'enqueued' ) ) {
                    $deps[] = $handle;
                    break;
                }
            }
            wp_enqueue_style( 'wpbb-suite-v142', $uri . $css, $deps, (string) filemtime( $dir . $css ) );
        }

        $js = '/assets/suite-v142.js';
        if ( is_readable( $dir . $js ) ) {
            $js_deps = array();
            foreach ( array( 'wpbb-suite-v139', 'wpbb-suite-v138', 'wpbb-suite-v137', 'wpbb-suite-v118' ) as $handle ) {
                if ( wp_script_is( $handle, 'registered' ) || wp_script_is( $handle, 'enqueued' ) ) {
                    $js_deps[] = $handle;
                    break;
                }
            }
            wp_enqueue_script( 'wpbb-suite-v142-js', $uri . $js, $js_deps, (string) filemtime( $dir . $js ), true );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_child_v142_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v142_body_class' ) ) {
    function wpbb_child_v142_body_class( $classes ) {
        $classes[] = 'wpbb-v142';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_child_v142_body_class', PHP_INT_MAX );
