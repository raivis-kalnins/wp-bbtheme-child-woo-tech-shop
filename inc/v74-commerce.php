<?php
/** v3.8.10.74 commerce repair: isolate managed demo products and refresh local product media. */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_child_v74_active_profile_id' ) ) {
    function wpbb_child_v74_active_profile_id() {
        $profile = function_exists( 'wp_theme_get_demo_profile' ) ? wp_theme_get_demo_profile() : array();
        return sanitize_key( (string) ( $profile['id'] ?? '' ) );
    }
}

if ( ! function_exists( 'wpbb_child_v74_local_product_attachment' ) ) {
    function wpbb_child_v74_local_product_attachment( $source, $product_id ) {
        $source = (string) $source;
        $product_id = absint( $product_id );
        if ( ! $product_id || ! is_readable( $source ) ) return 0;
        $key = sanitize_key( get_stylesheet() . '-' . $product_id . '-' . md5_file( $source ) );
        $existing = get_posts( array( 'post_type'=>'attachment', 'post_status'=>'inherit', 'posts_per_page'=>1, 'fields'=>'ids', 'meta_key'=>'_wpbb_v74_product_source', 'meta_value'=>$key ) );
        if ( $existing && is_readable( get_attached_file( $existing[0] ) ) ) return absint( $existing[0] );
        $uploads = wp_upload_dir();
        if ( ! empty( $uploads['error'] ) ) return 0;
        wp_mkdir_p( $uploads['path'] );
        $ext = strtolower( pathinfo( $source, PATHINFO_EXTENSION ) );
        $filename = wp_unique_filename( $uploads['path'], 'wpbb-' . sanitize_title( get_the_title( $product_id ) ) . '-v74.' . $ext );
        $target = trailingslashit( $uploads['path'] ) . $filename;
        if ( ! @copy( $source, $target ) ) return 0;
        $type = wp_check_filetype( $target );
        $attachment_id = wp_insert_attachment( array( 'post_mime_type'=>$type['type'] ?: 'image/jpeg', 'post_title'=>get_the_title( $product_id ), 'post_status'=>'inherit' ), $target, $product_id );
        if ( is_wp_error( $attachment_id ) || ! $attachment_id ) return 0;
        if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';
        $metadata = wp_generate_attachment_metadata( $attachment_id, $target );
        if ( $metadata ) wp_update_attachment_metadata( $attachment_id, $metadata );
        update_post_meta( $attachment_id, '_wpbb_v74_product_source', $key );
        return absint( $attachment_id );
    }
}

if ( ! function_exists( 'wpbb_child_v74_sync_commerce_demo' ) ) {
    function wpbb_child_v74_sync_commerce_demo() {
        if ( ! is_admin() || ! current_user_can( 'manage_options' ) || ! post_type_exists( 'product' ) ) return;
        $profile_id = wpbb_child_v74_active_profile_id();
        if ( ! $profile_id ) return;
        $done_key = 'wpbb_child_v74_commerce_' . sanitize_key( get_stylesheet() );
        if ( '3.8.10.74' === (string) get_option( $done_key ) ) return;

        // Remove only products explicitly created by another WP Base demo profile.
        $managed = get_posts( array( 'post_type'=>'product', 'post_status'=>'any', 'posts_per_page'=>-1, 'fields'=>'ids', 'meta_key'=>'_wpbb_child_woo_demo_product', 'meta_value'=>1 ) );
        foreach ( $managed as $product_id ) {
            $owner = sanitize_key( (string) get_post_meta( $product_id, '_wp_theme_demo_product_profile', true ) );
            if ( $owner && $owner !== $profile_id ) wp_delete_post( $product_id, true );
        }
        // Event ticket products use their own demo marker; keep them only in Events.
        if ( 'events' !== $profile_id ) {
            $tickets = get_posts( array( 'post_type'=>'product', 'post_status'=>'any', 'posts_per_page'=>-1, 'fields'=>'ids', 'meta_query'=>array( array( 'key'=>'_wpbb_events_demo_ticket_index', 'compare'=>'EXISTS' ) ) ) );
            foreach ( $tickets as $product_id ) wp_delete_post( $product_id, true );
        }

        if ( 'events' === $profile_id ) {
            if ( function_exists( 'wpbb_events_sync_demo_ticket_thumbnails' ) ) wpbb_events_sync_demo_ticket_thumbnails( true );
        } elseif ( function_exists( 'wpbb_child_woo_demo_product_data' ) ) {
            // Refresh only products that are already explicitly marked as this profile's demo data.
            // Do not create/overwrite products during a theme update.
            $profile = function_exists( 'wp_theme_get_demo_profile' ) ? wp_theme_get_demo_profile() : array( 'id'=>$profile_id );
            foreach ( wpbb_child_woo_demo_product_data() as $index => $data ) {
                if ( ! is_array( $data ) || empty( $data[1] ) ) continue;
                $product_post = get_page_by_path( sanitize_title( $data[1] ), OBJECT, 'product' );
                if ( ! $product_post ) continue;
                if ( 1 !== (int) get_post_meta( $product_post->ID, '_wpbb_child_woo_demo_product', true ) ) continue;
                if ( $profile_id !== sanitize_key( (string) get_post_meta( $product_post->ID, '_wp_theme_demo_product_profile', true ) ) ) continue;
                $local = apply_filters( 'wp_theme_woo_demo_product_image_path', '', $data, $index, $profile );
                if ( $local && is_readable( $local ) ) {
                    $attachment_id = wpbb_child_v74_local_product_attachment( $local, $product_post->ID );
                    if ( $attachment_id ) set_post_thumbnail( $product_post->ID, $attachment_id );
                }
            }
            if ( function_exists( 'wc_delete_product_transients' ) ) wc_delete_product_transients();
        }
        update_option( $done_key, '3.8.10.74', false );
    }
    add_action( 'admin_init', 'wpbb_child_v74_sync_commerce_demo', 140 );
}
