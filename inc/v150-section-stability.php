<?php
/**
 * Tech Shop 3.8.11.50 - targeted homepage section stability.
 *
 * Keeps the v149 Events-parity architecture and corrects only the three
 * remaining live regressions: nested trust-row offsets, gallery Swiper
 * collapse, and process badges stretched by the grid cell rule.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_tech_v150_enqueue' ) ) {
    function wpbb_tech_v150_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = $dir . '/assets/suite-v150.css';
        $js  = $dir . '/assets/suite-v150.js';

        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v150',
                $uri . '/assets/suite-v150.css',
                array( 'wpbb-suite-v149' ),
                (string) filemtime( $css )
            );
            wp_add_inline_style( 'wpbb-suite-v150', (string) file_get_contents( $css ) );
        }

        if ( is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v150',
                $uri . '/assets/suite-v150.js',
                array( 'wpbb-suite-v149' ),
                (string) filemtime( $js ),
                true
            );
            wp_add_inline_script(
                'wpbb-suite-v150',
                'window.wpbbSuiteV150=' . wp_json_encode(
                    array(
                        'version' => '3.8.11.50',
                        'gallery' => array(
                            $uri . '/assets/img/gallery-v143/gallery-workspace.jpg',
                            $uri . '/assets/img/gallery-v143/gallery-audio.jpg',
                            $uri . '/assets/img/gallery-v143/gallery-smart-home.jpg',
                            $uri . '/assets/img/store/usb-c-travel-dock.jpg',
                        ),
                    )
                ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_tech_v150_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_tech_v150_body_class' ) ) {
    function wpbb_tech_v150_body_class( $classes ) {
        $classes[] = 'wpbb-v150';
        $classes[] = 'wpbb-v150-section-stability';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_tech_v150_body_class', PHP_INT_MAX );
