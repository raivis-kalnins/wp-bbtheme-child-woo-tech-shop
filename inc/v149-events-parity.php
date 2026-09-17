<?php
/**
 * Tech Shop 3.8.11.49 - Events-reference layout owner.
 *
 * Retires the accumulated v119-v148 frontend geometry/runtime layers and uses
 * the same proven model as Woo Events 3.8.11.40: shared v118 base + one final
 * measured-rail owner. Server-side Woo fixes from later includes remain active.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_tech_v149_enqueue' ) ) {
    function wpbb_tech_v149_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        foreach ( range( 119, 148 ) as $n ) {
            $handles = array( 'wpbb-suite-v' . $n, 'wpbb-suite-v' . $n . '-js' );
            foreach ( $handles as $handle ) {
                wp_dequeue_style( $handle );
                wp_deregister_style( $handle );
                wp_dequeue_script( $handle );
                wp_deregister_script( $handle );
            }
        }

        $base_css = $dir . '/assets/suite-v118.css';
        $base_js  = $dir . '/assets/suite-v118.js';
        if ( is_readable( $base_css ) ) {
            wp_enqueue_style( 'wpbb-suite-v118', $uri . '/assets/suite-v118.css', array(), (string) filemtime( $base_css ) );
        }
        if ( is_readable( $base_js ) ) {
            wp_enqueue_script( 'wpbb-suite-v118', $uri . '/assets/suite-v118.js', array(), (string) filemtime( $base_js ), false );
        }

        $css = $dir . '/assets/suite-v149.css';
        $js  = $dir . '/assets/suite-v149.js';
        if ( is_readable( $css ) ) {
            wp_enqueue_style( 'wpbb-suite-v149', $uri . '/assets/suite-v149.css', array( 'wpbb-suite-v118' ), (string) filemtime( $css ) );
            /* Defeat stale proxy/CDN HTML that may retain an old asset response. */
            wp_add_inline_style( 'wpbb-suite-v149', (string) file_get_contents( $css ) );
        }
        if ( is_readable( $js ) ) {
            wp_enqueue_script( 'wpbb-suite-v149', $uri . '/assets/suite-v149.js', array( 'wpbb-suite-v118' ), (string) filemtime( $js ), true );
            wp_add_inline_script(
                'wpbb-suite-v149',
                'window.wpbbSuiteV149=' . wp_json_encode( array(
                    'version'     => '3.8.11.49',
                    'galleryBase' => trailingslashit( $uri ) . 'assets/img/gallery-v143/',
                ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_tech_v149_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_tech_v149_body_class' ) ) {
    function wpbb_tech_v149_body_class( $classes ) {
        $classes[] = 'wpbb-v149';
        $classes[] = 'wpbb-v149-events-parity';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_tech_v149_body_class', PHP_INT_MAX );
