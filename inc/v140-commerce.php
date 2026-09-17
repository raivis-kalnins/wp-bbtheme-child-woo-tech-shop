<?php
/**
 * Tech Shop 3.8.11.40 - PHP-side WooCommerce parity with the Automotive reference.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_tech_v140_ensure_woo_pages' ) ) {
    function wpbb_tech_v140_ensure_woo_pages() {
        if ( ! function_exists( 'WC' ) && ! class_exists( 'WooCommerce' ) ) return;
        $map = array(
            'woocommerce_shop_page_id'      => 'shop',
            'woocommerce_cart_page_id'      => 'cart',
            'woocommerce_checkout_page_id'  => 'checkout',
            'woocommerce_myaccount_page_id' => 'my-account',
        );
        foreach ( $map as $option => $slug ) {
            $page = get_page_by_path( $slug, OBJECT, 'page' );
            if ( ! $page || 'publish' !== $page->post_status ) continue;
            if ( (int) get_option( $option ) !== (int) $page->ID ) update_option( $option, (int) $page->ID, false );
        }
    }
}
add_action( 'init', 'wpbb_tech_v140_ensure_woo_pages', 94 );

if ( ! function_exists( 'wpbb_tech_v140_checkout_callbacks' ) ) {
    function wpbb_tech_v140_checkout_callbacks() {
        if ( ! function_exists( 'is_checkout' ) ) return;
        if ( function_exists( 'woocommerce_order_review' ) && false === has_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review' ) ) {
            add_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
        }
        if ( function_exists( 'woocommerce_checkout_payment' ) && false === has_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment' ) ) {
            add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
        }
    }
}
add_action( 'wp_loaded', 'wpbb_tech_v140_checkout_callbacks', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_tech_v140_strip_marketing_checkout_fields' ) ) {
    function wpbb_tech_v140_strip_marketing_checkout_fields( $fields ) {
        if ( ! is_array( $fields ) ) return $fields;
        $pattern = '/newsletter|newslatter|marketing|email[ _-]*updates|subscribe/i';
        foreach ( $fields as $key => $value ) {
            if ( ! is_array( $value ) ) continue;
            $looks_like_field = array_key_exists( 'type', $value ) || array_key_exists( 'label', $value ) || array_key_exists( 'class', $value );
            if ( $looks_like_field ) {
                $label = isset( $value['label'] ) ? wp_strip_all_tags( (string) $value['label'] ) : '';
                if ( preg_match( $pattern, (string) $key . ' ' . $label ) ) unset( $fields[ $key ] );
                continue;
            }
            foreach ( $value as $field_key => $field ) {
                if ( ! is_array( $field ) ) continue;
                $label = isset( $field['label'] ) ? wp_strip_all_tags( (string) $field['label'] ) : '';
                if ( preg_match( $pattern, (string) $field_key . ' ' . $label ) ) unset( $fields[ $key ][ $field_key ] );
            }
        }
        return $fields;
    }
}
add_filter( 'woocommerce_checkout_fields', 'wpbb_tech_v140_strip_marketing_checkout_fields', PHP_INT_MAX );
add_filter( 'woocommerce_get_additional_checkout_fields', 'wpbb_tech_v140_strip_marketing_checkout_fields', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_tech_v140_related_args' ) ) {
    function wpbb_tech_v140_related_args( $args ) {
        if ( ! is_array( $args ) ) return $args;
        $args['posts_per_page'] = max( 3, isset( $args['posts_per_page'] ) ? (int) $args['posts_per_page'] : 3 );
        $args['columns'] = 3;
        return $args;
    }
}
add_filter( 'woocommerce_output_related_products_args', 'wpbb_tech_v140_related_args', PHP_INT_MAX );


/* Keep the logged-in account navigation complete and in the same predictable
 * order as the reference commerce build. */
if ( ! function_exists( 'wpbb_tech_v140_account_menu' ) ) {
    function wpbb_tech_v140_account_menu( $items ) {
        if ( ! is_array( $items ) ) return $items;
        $preferred = array(
            'dashboard'       => __( 'Dashboard', 'wp-theme' ),
            'orders'          => __( 'Orders', 'wp-theme' ),
            'downloads'       => __( 'Downloads', 'wp-theme' ),
            'edit-address'    => __( 'Addresses', 'wp-theme' ),
            'payment-methods' => __( 'Payment methods', 'wp-theme' ),
            'edit-account'    => __( 'Account details', 'wp-theme' ),
            'customer-logout' => __( 'Log out', 'wp-theme' ),
        );
        $out = array();
        foreach ( $preferred as $key => $label ) {
            if ( isset( $items[ $key ] ) || 'payment-methods' !== $key ) {
                $out[ $key ] = isset( $items[ $key ] ) ? $items[ $key ] : $label;
            }
        }
        foreach ( $items as $key => $label ) {
            if ( ! isset( $out[ $key ] ) ) $out[ $key ] = $label;
        }
        return $out;
    }
}
add_filter( 'woocommerce_account_menu_items', 'wpbb_tech_v140_account_menu', 999 );
