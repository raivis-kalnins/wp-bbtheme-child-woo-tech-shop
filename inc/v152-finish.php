<?php
/**
 * Tech Shop 3.8.11.52 - safe final rail + hero pagination finish.
 *
 * This intentionally builds on the v149/v150 Events-reference layout. It does
 * not introduce a new hero/container width system. v152 only reinforces the
 * shared measured rail, restores the trust strip to that rail, adds an
 * accessible hero pager, and normalises section rhythm.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_tech_v152_enqueue' ) ) {
    function wpbb_tech_v152_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = $dir . '/assets/suite-v152.css';
        $js  = $dir . '/assets/suite-v152.js';

        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v152',
                $uri . '/assets/suite-v152.css',
                array( 'wpbb-suite-v150' ),
                (string) filemtime( $css )
            );
            // Keep the rail/pager corrections resilient to stale proxy HTML.
            wp_add_inline_style( 'wpbb-suite-v152', (string) file_get_contents( $css ) );
        }

        if ( is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v152',
                $uri . '/assets/suite-v152.js',
                array( 'wpbb-suite-v150' ),
                (string) filemtime( $js ),
                true
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_tech_v152_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_tech_v152_body_class' ) ) {
    function wpbb_tech_v152_body_class( $classes ) {
        $classes[] = 'wpbb-v152';
        $classes[] = 'wpbb-v152-safe-finish';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_tech_v152_body_class', PHP_INT_MAX );
