<?php
/**
 * Tech Shop 3.8.11.43 — screenshot-led homepage and WooCommerce repair.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_child_v143_enqueue' ) ) {
    function wpbb_child_v143_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = '/assets/suite-v143.css';
        $js  = '/assets/suite-v143.js';

        if ( is_readable( $dir . $css ) ) {
            $deps = wp_style_is( 'wpbb-suite-v142', 'registered' ) || wp_style_is( 'wpbb-suite-v142', 'enqueued' ) ? array( 'wpbb-suite-v142' ) : array();
            wp_enqueue_style( 'wpbb-suite-v143', $uri . $css, $deps, (string) filemtime( $dir . $css ) );
        }
        if ( is_readable( $dir . $js ) ) {
            $deps = wp_script_is( 'wpbb-suite-v142-js', 'registered' ) || wp_script_is( 'wpbb-suite-v142-js', 'enqueued' ) ? array( 'wpbb-suite-v142-js' ) : array();
            wp_enqueue_script( 'wpbb-suite-v143', $uri . $js, $deps, (string) filemtime( $dir . $js ), true );
            wp_add_inline_script(
                'wpbb-suite-v143',
                'window.wpbbSuiteV143=' . wp_json_encode( array(
                    'version'     => '3.8.11.43',
                    'galleryBase' => trailingslashit( $uri ) . 'assets/img/gallery-v143/',
                ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_child_v143_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v143_body_class' ) ) {
    function wpbb_child_v143_body_class( $classes ) {
        $classes[] = 'wpbb-v143';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_child_v143_body_class', PHP_INT_MAX );

/** Ensure the canonical My Account page exists before WordPress parses the request. */
if ( ! function_exists( 'wpbb_child_v143_ensure_account_page' ) ) {
    function wpbb_child_v143_ensure_account_page() {
        if ( ! class_exists( 'WooCommerce' ) && ! function_exists( 'WC' ) ) return;
        $page = get_page_by_path( 'my-account', OBJECT, 'page' );
        if ( ! $page ) {
            $id = wp_insert_post( array(
                'post_type'    => 'page',
                'post_status'  => 'publish',
                'post_title'   => __( 'My account', 'wp-bbtheme-child-woo-tech' ),
                'post_name'    => 'my-account',
                'post_content' => '<!-- wp:shortcode -->[wpbb_tech_account_page]<!-- /wp:shortcode -->',
            ), true );
            if ( ! is_wp_error( $id ) && $id ) $page = get_post( $id );
        }
        if ( $page && 'publish' === $page->post_status && (int) get_option( 'woocommerce_myaccount_page_id' ) !== (int) $page->ID ) {
            update_option( 'woocommerce_myaccount_page_id', (int) $page->ID, false );
        }
    }
}
add_action( 'init', 'wpbb_child_v143_ensure_account_page', 20 );

/** Resolve both /my-account/ and its endpoints even with stale cloned rewrite rules. */
if ( ! function_exists( 'wpbb_child_v143_account_request' ) ) {
    function wpbb_child_v143_account_request( $query_vars ) {
        if ( is_admin() || ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) ) return $query_vars;
        $path = (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
        $home_path = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
        if ( $home_path && '/' !== $home_path && 0 === strpos( $path, $home_path ) ) $path = substr( $path, strlen( $home_path ) );
        $path = trim( $path, '/' );
        if ( 'my-account' !== $path && 0 !== strpos( $path, 'my-account/' ) ) return $query_vars;

        $query_vars['pagename'] = 'my-account';
        unset( $query_vars['error'], $query_vars['name'] );
        if ( 'my-account' === $path ) return $query_vars;

        $parts = array_values( array_filter( explode( '/', substr( $path, strlen( 'my-account/' ) ) ), 'strlen' ) );
        if ( empty( $parts ) ) return $query_vars;
        $requested = sanitize_title( rawurldecode( (string) $parts[0] ) );
        $map = function_exists( 'wpbb_child_v139_account_endpoint_map' ) ? wpbb_child_v139_account_endpoint_map() : array();
        foreach ( $map as $key => $slug ) {
            if ( $requested !== sanitize_title( (string) $slug ) ) continue;
            $query_vars[ $key ] = isset( $parts[1] ) ? sanitize_text_field( rawurldecode( (string) $parts[1] ) ) : '';
            break;
        }
        return $query_vars;
    }
}
add_filter( 'request', 'wpbb_child_v143_account_request', 0 );

if ( ! function_exists( 'wpbb_child_v143_force_account_template' ) ) {
    function wpbb_child_v143_force_account_template( $template ) {
        if ( is_admin() || ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || is_feed() ) return $template;
        $path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
        $home_path = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
        if ( $home_path && 0 === strpos( $path, $home_path . '/' ) ) $path = substr( $path, strlen( $home_path ) + 1 );
        if ( 'my-account' !== $path && 0 !== strpos( $path, 'my-account/' ) ) return $template;
        $legacy = trailingslashit( get_stylesheet_directory() ) . 'woocommerce-legacy/account.php';
        return is_readable( $legacy ) ? $legacy : $template;
    }
}
add_filter( 'template_include', 'wpbb_child_v143_force_account_template', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v143_flush_rewrites_once' ) ) {
    function wpbb_child_v143_flush_rewrites_once() {
        if ( ! class_exists( 'WooCommerce' ) && ! function_exists( 'WC' ) ) return;
        $key = 'wpbb_child_v143_rewrites_' . sanitize_key( get_stylesheet() );
        if ( '3.8.11.43' === (string) get_option( $key ) ) return;
        flush_rewrite_rules( false );
        update_option( $key, '3.8.11.43', false );
    }
}
add_action( 'wp_loaded', 'wpbb_child_v143_flush_rewrites_once', PHP_INT_MAX );

/** Re-attach the child theme's corrected demo-product images on the first admin load. */
if ( ! function_exists( 'wpbb_child_v143_refresh_product_media' ) ) {
    function wpbb_child_v143_refresh_product_media( $page_id = 0, $profile = array() ) {
        if ( ! current_user_can( 'manage_options' ) || ! post_type_exists( 'product' ) || ! function_exists( 'wpbb_child_v75_product_attachment' ) ) return;
        $key = 'wpbb_child_v143_product_media_' . sanitize_key( get_stylesheet() );
        if ( '3.8.11.43' === (string) get_option( $key ) ) return;

        $products = apply_filters( 'wp_theme_woo_demo_product_data', array() );
        if ( is_array( $products ) ) {
            foreach ( array_values( $products ) as $index => $data ) {
                if ( ! is_array( $data ) || empty( $data[1] ) ) continue;
                $post = get_page_by_path( sanitize_title( (string) $data[1] ), OBJECT, 'product' );
                if ( ! $post ) continue;
                $source = apply_filters( 'wp_theme_woo_demo_product_image_path', '', $data, $index, array( 'id' => 'tech' ) );
                if ( ! $source || ! is_readable( $source ) ) continue;
                $attachment_id = wpbb_child_v75_product_attachment( $source, $post->ID );
                if ( ! $attachment_id ) continue;
                set_post_thumbnail( $post->ID, $attachment_id );
                if ( function_exists( 'wc_delete_product_transients' ) ) wc_delete_product_transients( $post->ID );
            }
        }
        update_option( $key, '3.8.11.43', false );
    }
}
add_action( 'admin_init', 'wpbb_child_v143_refresh_product_media', 260 );
add_action( 'wp_theme_after_demo_import', 'wpbb_child_v143_refresh_product_media', 260 );
