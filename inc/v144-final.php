<?php
/**
 * Tech Shop 3.8.11.44 — DB-informed homepage + WooCommerce stability pass.
 *
 * The 2026-09-17 DB backup confirms the front page is page 135134, the home
 * catalogue is a wpbb/catalogue block, and WooCommerce owns the canonical
 * shop/cart/checkout/my-account pages. This layer therefore fixes rendering
 * rather than rewriting page assignments or rebuilding the full homepage.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_child_v144_request_path' ) ) {
    function wpbb_child_v144_request_path() {
        $request = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
        $path = (string) wp_parse_url( $request, PHP_URL_PATH );
        $home_path = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
        if ( $home_path && '/' !== $home_path && 0 === strpos( $path, $home_path ) ) {
            $path = substr( $path, strlen( $home_path ) );
        }
        return trim( $path, '/' );
    }
}

if ( ! function_exists( 'wpbb_child_v144_is_account_request' ) ) {
    function wpbb_child_v144_is_account_request() {
        $path = wpbb_child_v144_request_path();
        return 'my-account' === $path || 0 === strpos( $path, 'my-account/' );
    }
}

/* -------------------------------------------------------------------------
 * Front-end assets.
 * ---------------------------------------------------------------------- */
if ( ! function_exists( 'wpbb_child_v144_enqueue' ) ) {
    function wpbb_child_v144_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        /* v143's homepage hero geometry caused the large blank/offset hero seen
         * in the supplied screenshot. Keep its Woo CSS, but let v118 + the
         * Automotive-style measured edge own the homepage hero again. */
        if ( is_front_page() ) {
            wp_dequeue_style( 'wpbb-suite-v143' );
            wp_deregister_style( 'wpbb-suite-v143' );
        }

        $css = '/assets/suite-v144.css';
        $js  = '/assets/suite-v144.js';
        if ( is_readable( $dir . $css ) ) {
            $deps = array();
            foreach ( array( 'wpbb-suite-v143', 'wpbb-suite-v142', 'wpbb-suite-v139', 'wpbb-suite-v118' ) as $handle ) {
                if ( wp_style_is( $handle, 'registered' ) || wp_style_is( $handle, 'enqueued' ) ) {
                    $deps[] = $handle;
                    break;
                }
            }
            wp_enqueue_style( 'wpbb-suite-v144', $uri . $css, $deps, (string) filemtime( $dir . $css ) );
        }
        if ( is_readable( $dir . $js ) ) {
            $deps = array();
            foreach ( array( 'wpbb-suite-v143', 'wpbb-suite-v142-js', 'wpbb-suite-v139', 'wpbb-suite-v118' ) as $handle ) {
                if ( wp_script_is( $handle, 'registered' ) || wp_script_is( $handle, 'enqueued' ) ) {
                    $deps[] = $handle;
                    break;
                }
            }
            wp_enqueue_script( 'wpbb-suite-v144', $uri . $js, $deps, (string) filemtime( $dir . $js ), true );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_child_v144_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v144_body_class' ) ) {
    function wpbb_child_v144_body_class( $classes ) {
        $classes[] = 'wpbb-v144';
        if ( wpbb_child_v144_is_account_request() ) {
            $classes[] = 'woocommerce-account';
            $classes[] = 'wp-theme-uses-woo-legacy-shell';
            $classes[] = 'wpbb-v144-account-route';
        }
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_child_v144_body_class', PHP_INT_MAX );

/* -------------------------------------------------------------------------
 * Deterministic homepage product catalogue.
 *
 * The backup proves the broken section is a wpbb/catalogue block with the
 * class wp-theme-home-product-catalogue. Render only that block with a known
 * Woo product grid so nested Bootstrap/BBuilder grid owners cannot collapse
 * cards into the 1–2px columns shown in the screenshot.
 * ---------------------------------------------------------------------- */
if ( ! function_exists( 'wpbb_child_v144_home_products' ) ) {
    function wpbb_child_v144_home_products( $limit = 8 ) {
        if ( ! function_exists( 'wc_get_products' ) ) return array();
        $args = array(
            'status'  => 'publish',
            'limit'   => max( 1, (int) $limit ),
            'orderby' => 'menu_order',
            'order'   => 'ASC',
            'return'  => 'objects',
        );
        $products = wc_get_products( $args );
        return is_array( $products ) ? array_values( array_filter( $products, static function( $product ) {
            return $product instanceof WC_Product && $product->is_visible();
        } ) ) : array();
    }
}

if ( ! function_exists( 'wpbb_child_v144_theme_product_image_url' ) ) {
    function wpbb_child_v144_theme_product_image_url( $product ) {
        if ( ! $product instanceof WC_Product ) return '';
        $slug = sanitize_title( $product->get_slug() );
        $map = array(
            'ultralight-laptop'            => 'ultralight-laptop.jpg',
            '4k-studio-monitor'            => 'studio-monitor.jpg',
            'mechanical-keyboard'          => 'mechanical-keyboard.jpg',
            'precision-mouse'              => 'precision-mouse.jpg',
            'noise-cancelling-headphones'  => 'noise-cancelling-headphones.jpg',
            'portable-bluetooth-speaker'   => 'portable-speaker.jpg',
            'smart-home-hub'               => 'smart-home-hub.jpg',
            'indoor-security-camera'       => 'security-camera.jpg',
            'usb-c-travel-dock'            => 'usb-c-travel-dock.jpg',
            'fast-charging-station'        => 'charging-station.jpg',
            'smart-watch-strap'            => 'smart-watch-strap.jpg',
            'tech-organiser'               => 'tech-organiser.jpg',
        );
        if ( empty( $map[ $slug ] ) ) return '';
        $relative = 'assets/img/store/' . $map[ $slug ];
        $path = trailingslashit( get_stylesheet_directory() ) . $relative;
        if ( ! is_readable( $path ) ) return '';
        return add_query_arg( 'v', (string) filemtime( $path ), trailingslashit( get_stylesheet_directory_uri() ) . $relative );
    }
}

if ( ! function_exists( 'wpbb_child_v144_render_home_catalogue' ) ) {
    function wpbb_child_v144_render_home_catalogue( $block_content, $block ) {
        if ( is_admin() || ! is_front_page() || ! is_array( $block ) ) return $block_content;
        if ( 'wpbb/catalogue' !== (string) ( $block['blockName'] ?? '' ) ) return $block_content;
        $attrs = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();
        $class = (string) ( $attrs['className'] ?? '' );
        if ( false === strpos( ' ' . $class . ' ', ' wp-theme-home-product-catalogue ' ) ) return $block_content;

        /* Preserve the real BBuilder catalogue whenever it rendered product cards.
         * v144 JS/CSS repairs the exact owning row using the Events v139/v140
         * approach, so filters/actions remain functional. The deterministic PHP
         * catalogue below is only a fallback for a genuinely empty/malformed
         * dynamic-block response. */
        if ( preg_match( '/class=(?:"|\')[^"\']*(?:wpbb-catalogue-card|product(?:\s|[^"\']*\s)type-product|iws-product-card|product-card)[^"\']*(?:"|\')/i', (string) $block_content ) ) {
            return $block_content;
        }

        $limit = isset( $attrs['postsToShow'] ) ? max( 1, min( 12, (int) $attrs['postsToShow'] ) ) : 8;
        $products = wpbb_child_v144_home_products( $limit );
        if ( ! $products ) return $block_content;

        ob_start();
        ?>
        <div class="wp-theme-home-product-catalogue wpbb-v144-home-products" data-wpbb-v144-catalogue="1">
            <div class="wpbb-v144-home-products__grid">
                <?php foreach ( $products as $product ) :
                    $permalink = get_permalink( $product->get_id() );
                    $image_url = wpbb_child_v144_theme_product_image_url( $product );
                    $summary   = trim( wp_strip_all_tags( $product->get_short_description() ) );
                    if ( '' === $summary ) $summary = trim( wp_strip_all_tags( $product->get_description() ) );
                    $summary = wp_trim_words( $summary, 18, '…' );
                    ?>
                    <article class="wpbb-v144-product-card">
                        <a class="wpbb-v144-product-card__media" href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
                            <?php if ( $image_url ) : ?>
                                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy" decoding="async">
                            <?php else : ?>
                                <?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy', 'decoding' => 'async' ) ) ); ?>
                            <?php endif; ?>
                        </a>
                        <div class="wpbb-v144-product-card__body">
                            <h3 class="wpbb-v144-product-card__title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
                            <?php if ( $summary ) : ?><p class="wpbb-v144-product-card__excerpt"><?php echo esc_html( $summary ); ?></p><?php endif; ?>
                            <div class="wpbb-v144-product-card__footer">
                                <div class="wpbb-v144-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
                                <a class="wpbb-v144-product-card__button" href="<?php echo esc_url( $permalink ); ?>"><?php esc_html_e( 'View product', 'wp-bbtheme-child-woo-tech' ); ?></a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return trim( (string) ob_get_clean() );
    }
}
add_filter( 'render_block', 'wpbb_child_v144_render_home_catalogue', PHP_INT_MAX, 2 );

/* -------------------------------------------------------------------------
 * WooCommerce page ownership and My Account routing.
 * ---------------------------------------------------------------------- */
if ( ! function_exists( 'wpbb_child_v144_ensure_woo_pages' ) ) {
    function wpbb_child_v144_ensure_woo_pages() {
        if ( ! function_exists( 'WC' ) && ! class_exists( 'WooCommerce' ) ) return;
        $map = array(
            'woocommerce_shop_page_id'      => 'shop',
            'woocommerce_cart_page_id'      => 'cart',
            'woocommerce_checkout_page_id'  => 'checkout',
            'woocommerce_myaccount_page_id' => 'my-account',
        );
        foreach ( $map as $option => $slug ) {
            $page = get_page_by_path( $slug, OBJECT, 'page' );
            if ( $page && 'publish' === $page->post_status && (int) get_option( $option ) !== (int) $page->ID ) {
                update_option( $option, (int) $page->ID, false );
            }
        }
    }
}
add_action( 'init', 'wpbb_child_v144_ensure_woo_pages', 22 );

if ( ! function_exists( 'wpbb_child_v144_account_rewrites' ) ) {
    function wpbb_child_v144_account_rewrites() {
        if ( ! function_exists( 'WC' ) && ! class_exists( 'WooCommerce' ) ) return;
        add_rewrite_rule( '^my-account/?$', 'index.php?pagename=my-account', 'top' );
        $map = function_exists( 'wpbb_child_v139_account_endpoint_map' ) ? wpbb_child_v139_account_endpoint_map() : array(
            'orders' => 'orders', 'view-order' => 'view-order', 'downloads' => 'downloads',
            'edit-address' => 'edit-address', 'payment-methods' => 'payment-methods',
            'add-payment-method' => 'add-payment-method', 'edit-account' => 'edit-account',
            'lost-password' => 'lost-password', 'customer-logout' => 'customer-logout',
        );
        foreach ( $map as $key => $slug ) {
            $slug = sanitize_title( (string) $slug );
            if ( '' === $slug ) continue;
            add_rewrite_endpoint( $slug, EP_PAGES );
            add_rewrite_rule( '^my-account/' . preg_quote( $slug, '~' ) . '/?$', 'index.php?pagename=my-account&' . $key . '=', 'top' );
            add_rewrite_rule( '^my-account/' . preg_quote( $slug, '~' ) . '/([^/]+)/?$', 'index.php?pagename=my-account&' . $key . '=$matches[1]', 'top' );
        }
    }
}
add_action( 'init', 'wpbb_child_v144_account_rewrites', 125 );

if ( ! function_exists( 'wpbb_child_v144_account_request' ) ) {
    function wpbb_child_v144_account_request( $query_vars ) {
        if ( is_admin() || ! wpbb_child_v144_is_account_request() ) return $query_vars;
        $path = wpbb_child_v144_request_path();
        $query_vars['pagename'] = 'my-account';
        unset( $query_vars['error'], $query_vars['name'] );
        if ( 'my-account' === $path ) return $query_vars;
        $parts = array_values( array_filter( explode( '/', substr( $path, strlen( 'my-account/' ) ) ), 'strlen' ) );
        if ( ! $parts ) return $query_vars;
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
add_filter( 'request', 'wpbb_child_v144_account_request', -10 );

if ( ! function_exists( 'wpbb_child_v144_account_template' ) ) {
    function wpbb_child_v144_account_template( $template ) {
        if ( is_admin() || ! wpbb_child_v144_is_account_request() ) return $template;
        $legacy = trailingslashit( get_stylesheet_directory() ) . 'woocommerce-legacy/account.php';
        return is_readable( $legacy ) ? $legacy : $template;
    }
}
add_filter( 'template_include', 'wpbb_child_v144_account_template', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_child_v144_flush_rewrites_once' ) ) {
    function wpbb_child_v144_flush_rewrites_once() {
        if ( ! function_exists( 'WC' ) && ! class_exists( 'WooCommerce' ) ) return;
        $key = 'wpbb_child_v144_rewrites_' . sanitize_key( get_stylesheet() );
        if ( '3.8.11.44' === (string) get_option( $key ) ) return;
        flush_rewrite_rules( false );
        update_option( $key, '3.8.11.44', false );
    }
}
add_action( 'wp_loaded', 'wpbb_child_v144_flush_rewrites_once', PHP_INT_MAX );

/* Refresh the theme's known demo product images under a new release key. */
if ( ! function_exists( 'wpbb_child_v144_refresh_product_media' ) ) {
    function wpbb_child_v144_refresh_product_media() {
        if ( ! current_user_can( 'manage_options' ) || ! post_type_exists( 'product' ) || ! function_exists( 'wpbb_child_v75_product_attachment' ) ) return;
        $key = 'wpbb_child_v144_product_media_' . sanitize_key( get_stylesheet() );
        if ( '3.8.11.44' === (string) get_option( $key ) ) return;
        $products = apply_filters( 'wp_theme_woo_demo_product_data', array() );
        foreach ( array_values( is_array( $products ) ? $products : array() ) as $index => $data ) {
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
        update_option( $key, '3.8.11.44', false );
    }
}
add_action( 'admin_init', 'wpbb_child_v144_refresh_product_media', 280 );
add_action( 'after_switch_theme', 'wpbb_child_v144_refresh_product_media', 280 );
add_action( 'wp_theme_after_demo_import', 'wpbb_child_v144_refresh_product_media', 280 );
