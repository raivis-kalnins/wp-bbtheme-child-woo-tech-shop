<?php
/**
 * WP BBTheme Child Woo Tech Shop 3.8.11.42 - Automotive-style scoped recovery.
 *
 * Uses the same stable architecture as the Automotive reference theme:
 * v118 remains the shared base; v119-v136 emergency geometry layers are retired;
 * proven v130/v132 commerce assets are loaded only on WooCommerce routes; and
 * v137 owns homepage alignment/grids without rebuilding or moving content.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* v137 is the only late layout owner. */
remove_action( 'wp_enqueue_scripts', 'wpbb_child_v136_enqueue', PHP_INT_MAX );
remove_filter( 'body_class', 'wpbb_child_v136_body_class', PHP_INT_MAX );
remove_filter( 'body_class', 'wpbb_child_v135_body_class', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v137_is_commerce_route' ) ) {
    function wpbb_child_v137_is_commerce_route() {
        return ( function_exists( 'is_woocommerce' ) && is_woocommerce() )
            || ( function_exists( 'is_cart' ) && is_cart() )
            || ( function_exists( 'is_checkout' ) && is_checkout() )
            || ( function_exists( 'is_account_page' ) && is_account_page() );
    }
}

if ( ! function_exists( 'wpbb_child_v137_enqueue' ) ) {
    function wpbb_child_v137_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        /* Retire the accumulated emergency layout stack exactly as Automotive v137 does. */
        foreach ( range( 119, 136 ) as $n ) {
            $handle = 'wpbb-suite-v' . $n;
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }

        $base_css = $dir . '/assets/suite-v118.css';
        $base_js  = $dir . '/assets/suite-v118.js';
        if ( is_readable( $base_css ) ) {
            wp_enqueue_style( 'wpbb-suite-v118', $uri . '/assets/suite-v118.css', array(), (string) filemtime( $base_css ) );
        }
        if ( is_readable( $base_js ) ) {
            wp_enqueue_script( 'wpbb-suite-v118', $uri . '/assets/suite-v118.js', array(), (string) filemtime( $base_js ), false );
        }

        /* Automotive's proven commerce/account layers, adapted to Tech Shop. */
        if ( wpbb_child_v137_is_commerce_route() ) {
            foreach ( array( 130, 132 ) as $commerce_version ) {
                $css = '/assets/suite-v' . $commerce_version . '.css';
                $js  = '/assets/suite-v' . $commerce_version . '.js';
                $handle = 'wpbb-suite-v' . $commerce_version;
                if ( is_readable( $dir . $css ) ) {
                    wp_enqueue_style(
                        $handle,
                        $uri . $css,
                        is_readable( $base_css ) ? array( 'wpbb-suite-v118' ) : array(),
                        (string) filemtime( $dir . $css )
                    );
                }
                if ( is_readable( $dir . $js ) ) {
                    wp_enqueue_script(
                        $handle,
                        $uri . $js,
                        is_readable( $base_js ) ? array( 'wpbb-suite-v118' ) : array(),
                        (string) filemtime( $dir . $js ),
                        true
                    );
                    wp_add_inline_script(
                        $handle,
                        'window.wpbbSuiteV' . $commerce_version . '=' . wp_json_encode(
                            array(
                                'themeSlug' => basename( $dir ),
                                'version'   => '3.8.11.42',
                            )
                        ) . ';',
                        'before'
                    );
                }
            }
        }

        $css = '/assets/suite-v137.css';
        $js  = '/assets/suite-v137.js';
        if ( is_readable( $dir . $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v137',
                $uri . $css,
                is_readable( $base_css ) ? array( 'wpbb-suite-v118' ) : array(),
                (string) filemtime( $dir . $css )
            );
        }
        if ( is_readable( $dir . $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v137',
                $uri . $js,
                is_readable( $base_js ) ? array( 'wpbb-suite-v118' ) : array(),
                (string) filemtime( $dir . $js ),
                true
            );
            wp_add_inline_script(
                'wpbb-suite-v137',
                'window.wpbbSuiteV137=' . wp_json_encode( array( 'version' => '3.8.11.42', 'cards' => array() ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_child_v137_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v137_body_class' ) ) {
    function wpbb_child_v137_body_class( $classes ) {
        $classes[] = 'wpbb-v137';
        $classes[] = 'wpbb-v137-theme-tech';
        if ( wpbb_child_v137_is_commerce_route() ) {
            $classes[] = 'wpbb-v130-theme-tech';
            $classes[] = 'wpbb-v132-theme-tech';
        }
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_child_v137_body_class', PHP_INT_MAX );
