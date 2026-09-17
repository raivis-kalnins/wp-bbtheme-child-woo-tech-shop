<?php
/**
 * WP BBTheme Child Woo Tech Shop 3.8.11.40.
 *
 * Final navigation/commerce pass:
 * - closer desktop mega-menu positioning + readable light-panel copy;
 * - deterministic two-column basket owner;
 * - My Account endpoint routing that works even before rewrite rules refresh;
 * - white article CTA links.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_child_v139_enqueue' ) ) {
    function wpbb_child_v139_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = '/assets/suite-v139.css';
        $js  = '/assets/suite-v139.js';

        if ( is_readable( $dir . $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v139',
                $uri . $css,
                wp_style_is( 'wpbb-suite-v138', 'registered' ) || wp_style_is( 'wpbb-suite-v138', 'enqueued' ) ? array( 'wpbb-suite-v138' ) : array(),
                (string) filemtime( $dir . $css )
            );
        }
        if ( is_readable( $dir . $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v139',
                $uri . $js,
                wp_script_is( 'wpbb-suite-v138', 'registered' ) || wp_script_is( 'wpbb-suite-v138', 'enqueued' ) ? array( 'wpbb-suite-v138' ) : array(),
                (string) filemtime( $dir . $js ),
                true
            );
            wp_add_inline_script(
                'wpbb-suite-v139',
                'window.wpbbSuiteV139=' . wp_json_encode( array( 'version' => '3.8.11.40' ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_child_v139_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v139_body_class' ) ) {
    function wpbb_child_v139_body_class( $classes ) {
        $classes[] = 'wpbb-v139';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_child_v139_body_class', PHP_INT_MAX );

/**
 * Return WooCommerce endpoint keys and their active URL slugs. WC_Query is the
 * primary source so custom endpoint settings are respected; defaults keep the
 * demo repair safe while WooCommerce is still initialising.
 */
if ( ! function_exists( 'wpbb_child_v139_account_endpoint_map' ) ) {
    function wpbb_child_v139_account_endpoint_map() {
        $map = array(
            'orders'                     => 'orders',
            'view-order'                 => 'view-order',
            'downloads'                  => 'downloads',
            'edit-address'               => 'edit-address',
            'payment-methods'            => 'payment-methods',
            'add-payment-method'          => 'add-payment-method',
            'delete-payment-method'       => 'delete-payment-method',
            'set-default-payment-method'  => 'set-default-payment-method',
            'edit-account'               => 'edit-account',
            'lost-password'              => 'lost-password',
            'customer-logout'             => 'customer-logout',
        );

        if ( function_exists( 'WC' ) && WC() && isset( WC()->query ) && is_object( WC()->query ) && method_exists( WC()->query, 'get_query_vars' ) ) {
            $vars = (array) WC()->query->get_query_vars();
            foreach ( $map as $key => $fallback ) {
                if ( isset( $vars[ $key ] ) && '' !== trim( (string) $vars[ $key ] ) ) {
                    $map[ $key ] = sanitize_title( (string) $vars[ $key ] );
                }
            }
        }

        return array_filter( $map, 'strlen' );
    }
}

/** Keep WooCommerce pointed at the published /my-account/ Page. */
if ( ! function_exists( 'wpbb_child_v139_ensure_account_page' ) ) {
    function wpbb_child_v139_ensure_account_page() {
        if ( ! function_exists( 'WC' ) && ! class_exists( 'WooCommerce' ) ) return;
        $page = get_page_by_path( 'my-account', OBJECT, 'page' );
        if ( ! $page || 'publish' !== $page->post_status ) return;
        if ( (int) get_option( 'woocommerce_myaccount_page_id' ) !== (int) $page->ID ) {
            update_option( 'woocommerce_myaccount_page_id', (int) $page->ID, false );
        }
    }
}
add_action( 'init', 'wpbb_child_v139_ensure_account_page', 95 );

/** Register explicit endpoint rules as a normal WordPress fallback. */
if ( ! function_exists( 'wpbb_child_v139_account_rewrites' ) ) {
    function wpbb_child_v139_account_rewrites() {
        if ( ! function_exists( 'WC' ) && ! class_exists( 'WooCommerce' ) ) return;
        foreach ( wpbb_child_v139_account_endpoint_map() as $slug ) {
            $slug = sanitize_title( (string) $slug );
            if ( '' === $slug ) continue;
            add_rewrite_endpoint( $slug, EP_PAGES );
            add_rewrite_rule(
                '^my-account/' . preg_quote( $slug, '~' ) . '/?$',
                'index.php?pagename=my-account&' . $slug . '=',
                'top'
            );
            add_rewrite_rule(
                '^my-account/' . preg_quote( $slug, '~' ) . '/([^/]+)/?$',
                'index.php?pagename=my-account&' . $slug . '=$matches[1]',
                'top'
            );
        }
    }
}
add_action( 'init', 'wpbb_child_v139_account_rewrites', 120 );

if ( ! function_exists( 'wpbb_child_v139_query_vars' ) ) {
    function wpbb_child_v139_query_vars( $vars ) {
        foreach ( wpbb_child_v139_account_endpoint_map() as $slug ) {
            if ( ! in_array( $slug, $vars, true ) ) $vars[] = $slug;
        }
        return $vars;
    }
}
add_filter( 'query_vars', 'wpbb_child_v139_query_vars', PHP_INT_MAX );

/**
 * Resolve /my-account/<endpoint>/ directly from REQUEST_URI. This avoids a 404
 * when a cloned demo has stale rewrite_rules and works before the one-time flush.
 */
if ( ! function_exists( 'wpbb_child_v139_account_request' ) ) {
    function wpbb_child_v139_account_request( $query_vars ) {
        if ( is_admin() || ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) ) return $query_vars;

        $request_path = (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
        $home_path    = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
        if ( $home_path && '/' !== $home_path && 0 === strpos( $request_path, $home_path ) ) {
            $request_path = substr( $request_path, strlen( $home_path ) );
        }
        $path = trim( $request_path, '/' );
        if ( 'my-account' === $path || 0 !== strpos( $path, 'my-account/' ) ) return $query_vars;

        $parts = array_values( array_filter( explode( '/', substr( $path, strlen( 'my-account/' ) ) ), 'strlen' ) );
        if ( empty( $parts ) ) return $query_vars;
        $requested_slug = sanitize_title( rawurldecode( (string) $parts[0] ) );
        $map = wpbb_child_v139_account_endpoint_map();
        if ( ! in_array( $requested_slug, $map, true ) ) return $query_vars;

        $query_vars['pagename'] = 'my-account';
        $query_vars[ $requested_slug ] = isset( $parts[1] ) ? sanitize_text_field( rawurldecode( (string) $parts[1] ) ) : '';
        unset( $query_vars['error'], $query_vars['name'] );
        return $query_vars;
    }
}
add_filter( 'request', 'wpbb_child_v139_account_request', 1 );

/** Generate every account endpoint from the actual /my-account/ permalink. */
if ( ! function_exists( 'wpbb_child_v139_endpoint_url' ) ) {
    function wpbb_child_v139_endpoint_url( $url, $endpoint, $value, $permalink ) {
        $page = get_page_by_path( 'my-account', OBJECT, 'page' );
        if ( ! $page || 'publish' !== $page->post_status ) return $url;

        $map = wpbb_child_v139_account_endpoint_map();
        $slug = isset( $map[ $endpoint ] ) ? $map[ $endpoint ] : ( in_array( $endpoint, $map, true ) ? $endpoint : '' );
        if ( '' === $slug ) return $url;

        $target = trailingslashit( get_permalink( $page ) ) . trailingslashit( $slug );
        if ( '' !== (string) $value ) $target .= trailingslashit( rawurlencode( (string) $value ) );
        return $target;
    }
}
add_filter( 'woocommerce_get_endpoint_url', 'wpbb_child_v139_endpoint_url', PHP_INT_MAX, 4 );

/** Flush once for this release; the request fallback above covers the same URLs immediately. */
if ( ! function_exists( 'wpbb_child_v139_flush_rewrites_once' ) ) {
    function wpbb_child_v139_flush_rewrites_once() {
        if ( ! function_exists( 'WC' ) && ! class_exists( 'WooCommerce' ) ) return;
        $key = 'wpbb_child_v139_rewrites_' . sanitize_key( get_stylesheet() );
        if ( '3.8.11.40' === (string) get_option( $key ) ) return;
        flush_rewrite_rules( false );
        update_option( $key, '3.8.11.40', false );
    }
}
add_action( 'wp_loaded', 'wpbb_child_v139_flush_rewrites_once', PHP_INT_MAX );
