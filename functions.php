<?php
defined('ABSPATH') || exit;

require_once __DIR__ . '/inc/frontend-password-protection.php';
function wpbb_tech_project_mode($mode){ return 'woocommerce'; }
add_filter('wp_theme_project_mode','wpbb_tech_project_mode');
function wpbb_tech_woo_profile($profile){ return 'store'; }
add_filter('wp_theme_woo_support_default_profile','wpbb_tech_woo_profile');

function wpbb_tech_assets() {
    $theme = wp_get_theme();
    $manifest = get_stylesheet_directory() . '/dist/.vite/manifest.json';
    if (!is_readable($manifest)) return;
    $data = json_decode((string) file_get_contents($manifest), true);
    if (!is_array($data)) return;
    if (!empty($data['src/scss/public.scss']['file'])) {
        wp_enqueue_style('wpbb_tech-app', get_stylesheet_directory_uri() . '/dist/' . ltrim($data['src/scss/public.scss']['file'], '/'), array(), $theme->get('Version'));
        if (function_exists('wp_theme_sector_customizer_css')) wp_add_inline_style('wpbb_tech-app', wp_theme_sector_customizer_css('#1d63ed', '14px', '--sector-primary', '--sector-radius'));
    }
    if (!empty($data['src/js/main.js']['file'])) wp_enqueue_script('wpbb_tech-app', get_stylesheet_directory_uri() . '/dist/' . ltrim($data['src/js/main.js']['file'], '/'), array(), $theme->get('Version'), true);
}
add_action('wp_enqueue_scripts', 'wpbb_tech_assets', 30);

function wpbb_tech_dark_mode_bootstrap() { echo '<script>(function(){try{var m=localStorage.getItem("wpThemeMode");if(m==="dark"){document.documentElement.classList.add("is-dark-theme");document.documentElement.setAttribute("data-theme","dark");}}catch(e){}})();</script>'; }
add_action('wp_head', 'wpbb_tech_dark_mode_bootstrap', 1);
function wpbb_tech_demo_profile( $profile ) {
	return array_merge( $profile, array(
		'id' => 'tech', 'name' => __( 'Technology Store', 'wp-bbtheme-child-woo-tech' ), 'commerce' => true,
		'eyebrow' => __( 'Technology made useful', 'wp-bbtheme-child-woo-tech' ),
		'hero_title' => __( 'Better technology for work, home and everywhere between.', 'wp-bbtheme-child-woo-tech' ),
		'hero_text' => __( 'Discover considered devices, accessories and smart essentials with clear product information and fast filtering.', 'wp-bbtheme-child-woo-tech' ),
		'hero_image' => trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/store/tech-workspace.jpg',
		'about_image' => trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/store/studio-monitor.jpg',
		'primary_label' => __( 'Shop technology', 'wp-bbtheme-child-woo-tech' ), 'primary_url' => '#shop',
		'secondary_label' => __( 'Buying advice', 'wp-bbtheme-child-woo-tech' ), 'secondary_url' => '#services',
		'services_eyebrow' => __( 'Store services', 'wp-bbtheme-child-woo-tech' ),
		'services_heading' => __( 'Technology retail with useful advice built in.', 'wp-bbtheme-child-woo-tech' ),
		'about_eyebrow' => __( 'Curated technology', 'wp-bbtheme-child-woo-tech' ),
		'industries_eyebrow' => __( 'Shop by setup', 'wp-bbtheme-child-woo-tech' ),
		'industries_heading' => __( 'Build a setup around the way you work, listen and live.', 'wp-bbtheme-child-woo-tech' ),
		'shop_eyebrow' => __( 'Featured catalogue', 'wp-bbtheme-child-woo-tech' ),
		'shop_heading' => __( 'Products worth comparing, with filters that stay out of the way.', 'wp-bbtheme-child-woo-tech' ),
		'process_eyebrow' => __( 'Buying journey', 'wp-bbtheme-child-woo-tech' ),
		'process_heading' => __( 'Choose confidently, compare quickly and get set up without friction.', 'wp-bbtheme-child-woo-tech' ),
		'faq_heading' => __( 'Useful answers for buying technology online.', 'wp-bbtheme-child-woo-tech' ),
		'industries' => array(
			array( __( 'Home office', 'wp-bbtheme-child-woo-tech' ), __( 'Displays, input devices and accessories for focused work.', 'wp-bbtheme-child-woo-tech' ), 'design' ),
			array( __( 'Mobile life', 'wp-bbtheme-child-woo-tech' ), __( 'Portable power, audio and accessories that travel well.', 'wp-bbtheme-child-woo-tech' ), 'delivery' ),
			array( __( 'Gaming', 'wp-bbtheme-child-woo-tech' ), __( 'Responsive peripherals and audio without unnecessary complexity.', 'wp-bbtheme-child-woo-tech' ), 'spark' ),
			array( __( 'Smart home', 'wp-bbtheme-child-woo-tech' ), __( 'Practical connected devices with clear compatibility guidance.', 'wp-bbtheme-child-woo-tech' ), 'home' ),
		),
		'services' => array(
			array( __( 'Expert guidance', 'wp-bbtheme-child-woo-tech' ), __( 'Plain-language advice for choosing the right device.', 'wp-bbtheme-child-woo-tech' ) ),
			array( __( 'Fast fulfilment', 'wp-bbtheme-child-woo-tech' ), __( 'Clear stock information and dependable delivery options.', 'wp-bbtheme-child-woo-tech' ) ),
			array( __( 'Useful support', 'wp-bbtheme-child-woo-tech' ), __( 'Help with setup, accessories and the life of your purchase.', 'wp-bbtheme-child-woo-tech' ) ),
		),
	) );
}
add_filter( 'wp_theme_demo_profile', 'wpbb_tech_demo_profile' );

function wpbb_tech_demo_products( $products ) {
	return array(
		array( 'simple', 'Ultralight Laptop', 'Computing', 899, 'A capable lightweight laptop for focused work anywhere.' ),
		array( 'simple', '4K Studio Monitor', 'Computing', 429, 'Crisp colour-accurate display with USB-C connectivity.' ),
		array( 'simple', 'Mechanical Keyboard', 'Accessories', 119, 'Tactile low-profile keyboard built for long work sessions.' ),
		array( 'simple', 'Precision Mouse', 'Accessories', 69, 'Comfortable wireless mouse with programmable controls.' ),
		array( 'simple', 'Noise Cancelling Headphones', 'Audio', 249, 'Immersive wireless audio with all-day comfort.' ),
		array( 'simple', 'Portable Bluetooth Speaker', 'Audio', 89, 'Room-filling sound in a compact weather-resistant design.' ),
		array( 'simple', 'Smart Home Hub', 'Smart Home', 129, 'Bring lights, sensors and routines together securely.' ),
		array( 'simple', 'Indoor Security Camera', 'Smart Home', 79, 'Private, sharp monitoring with useful alerts.' ),
		array( 'simple', 'USB-C Travel Dock', 'Accessories', 59, 'Seven essential ports in a pocket-size aluminium hub.' ),
		array( 'simple', 'Fast Charging Station', 'Accessories', 49, 'Charge three everyday devices from one compact station.' ),
		array( 'variable', 'Smart Watch Strap', 'Wearables', 29, 'Comfortable replacement strap in three colours and sizes.' ),
		array( 'variable', 'Tech Organiser', 'Accessories', 39, 'A travel organiser for cables, drives and small devices.' ),
	);
}
add_filter( 'wp_theme_woo_demo_product_data', 'wpbb_tech_demo_products' );

function wpbb_tech_demo_product_image( $path, $product, $index ) {
	$images = array(
		'ultralight-laptop.jpg', 'studio-monitor.jpg', 'mechanical-keyboard.jpg', 'precision-mouse.jpg',
		'noise-cancelling-headphones.jpg', 'portable-speaker.jpg', 'smart-home-hub.jpg', 'security-camera.jpg',
		'usb-c-travel-dock.jpg', 'charging-station.jpg', 'smart-watch-strap.jpg', 'tech-organiser.jpg',
	);
	return isset( $images[ $index ] ) ? get_stylesheet_directory() . '/assets/img/store/' . $images[ $index ] : $path;
}
add_filter( 'wp_theme_woo_demo_product_image_path', 'wpbb_tech_demo_product_image', 10, 3 );

function wpbb_tech_demo_variation_options( $options, $product ) {
	$name = isset( $product[1] ) ? $product[1] : '';
	if ( 'Smart Watch Strap' === $name ) {
		return array( 'colors' => array( 'Midnight', 'Ocean', 'Stone' ), 'sizes' => array( 'Small', 'Medium', 'Large' ) );
	}
	if ( 'Tech Organiser' === $name ) {
		return array( 'colors' => array( 'Graphite', 'Navy', 'Sand' ), 'sizes' => array( 'Compact', 'Standard', 'Large' ) );
	}
	return $options;
}
add_filter( 'wp_theme_woo_demo_variation_options', 'wpbb_tech_demo_variation_options', 10, 2 );


function wpbb_tech_demo_profile_premium( $profile ) {
	if ( empty( $profile['id'] ) || 'tech' !== $profile['id'] ) {
		return $profile;
	}
	$profile['about_title'] = __( 'Technology chosen for the way people actually use it.', 'wp-bbtheme-child-woo-tech' );
	$profile['about_text'] = __( 'Clear specifications, useful buying guidance and a fast catalogue experience make complex product ranges easier to shop.', 'wp-bbtheme-child-woo-tech' );
	$profile['stats'] = array(
		array( '24h', __( 'Fast dispatch on stocked lines', 'wp-bbtheme-child-woo-tech' ) ),
		array( '2yr', __( 'Support on core devices', 'wp-bbtheme-child-woo-tech' ) ),
		array( '12', __( 'Curated products', 'wp-bbtheme-child-woo-tech' ) ),
		array( '1', __( 'Clean WooCommerce system', 'wp-bbtheme-child-woo-tech' ) ),
	);
	$profile['process'] = array(
		array( '01', __( 'Choose', 'wp-bbtheme-child-woo-tech' ), __( 'Use categories, search and instant filters to narrow a large catalogue quickly.', 'wp-bbtheme-child-woo-tech' ) ),
		array( '02', __( 'Compare', 'wp-bbtheme-child-woo-tech' ), __( 'Review the details and options that actually change a buying decision.', 'wp-bbtheme-child-woo-tech' ) ),
		array( '03', __( 'Get set up', 'wp-bbtheme-child-woo-tech' ), __( 'Keep delivery, account and support journeys close to the purchase.', 'wp-bbtheme-child-woo-tech' ) ),
	);
	$profile['cta_title'] = __( 'Build a better everyday setup.', 'wp-bbtheme-child-woo-tech' );
	$profile['cta_text'] = __( 'Use the catalogue, filters and buying guides to compare useful technology with less noise.', 'wp-bbtheme-child-woo-tech' );
	$profile['footer_text'] = __( 'A specialist technology store with clear advice and fast product discovery.', 'wp-bbtheme-child-woo-tech' );
	$profile['page_labels'] = array( 'about' => __( 'About', 'wp-bbtheme-child-woo-tech' ), 'services' => __( 'Buying advice', 'wp-bbtheme-child-woo-tech' ), 'industries' => __( 'Use cases', 'wp-bbtheme-child-woo-tech' ), 'contact' => __( 'Support', 'wp-bbtheme-child-woo-tech' ), 'blog' => __( 'Guides', 'wp-bbtheme-child-woo-tech' ) );
	return $profile;
}
add_filter( 'wp_theme_demo_profile', 'wpbb_tech_demo_profile_premium', 20 );

function wpbb_tech_pattern_markup( $name ) {
	$path = get_stylesheet_directory() . '/patterns/' . sanitize_file_name( $name ) . '.php';
	if ( ! is_readable( $path ) ) { return ''; }
	ob_start(); include $path; return trim( (string) ob_get_clean() );
}
function wpbb_tech_after_hero_sections( $content, $profile ) {
	if ( empty( $profile['id'] ) || 'tech' !== $profile['id'] ) { return $content; }
	return $content . wpbb_tech_pattern_markup( 'tech-trust' ) . wpbb_tech_pattern_markup( 'tech-category-strip' );
}
add_filter( 'wp_theme_demo_after_hero_sections', 'wpbb_tech_after_hero_sections', 20, 2 );
function wpbb_tech_extra_home_sections( $content, $profile ) {
	if ( empty( $profile['id'] ) || 'tech' !== $profile['id'] ) { return $content; }
	return $content . wpbb_tech_pattern_markup( 'tech-promo' );
}
add_filter( 'wp_theme_demo_extra_home_sections', 'wpbb_tech_extra_home_sections', 20, 2 );
function wpbb_tech_demo_menu_settings( $settings, $profile ) {
	if ( ! empty( $profile['id'] ) && 'tech' === $profile['id'] ) {
		$settings['search_bar'] = true;
		$settings['customer_account'] = true;
		$settings['mini_cart'] = true;
		$settings['sticky_header'] = true;
		$settings['light_dark'] = true;
	}
	return $settings;
}
add_filter( 'wp_theme_demo_menu_settings', 'wpbb_tech_demo_menu_settings', 20, 2 );

function wpbb_tech_product_category_url( $name ) {
	$term = get_term_by( 'name', $name, 'product_cat' );
	if ( ! $term || is_wp_error( $term ) ) return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	$url = get_term_link( $term );
	return is_wp_error( $url ) ? home_url( '/shop/' ) : $url;
}

/** Product-led editable Shop mega menu using the shared Brandsafe-style system. */
function wpbb_tech_mega_menu_definitions( $definitions, $profile ) {
	if ( empty( $profile['id'] ) || 'tech' !== $profile['id'] ) return $definitions;
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	$definitions['shop'] = array(
		'title'      => __( 'Technology shop navigation', 'wp-bbtheme-child-woo-tech' ),
		'target_key' => 'shop',
		'eyebrow'    => __( 'Shop technology', 'wp-bbtheme-child-woo-tech' ),
		'heading'    => __( 'Find the right setup faster.', 'wp-bbtheme-child-woo-tech' ),
		'intro'      => __( 'Browse by use case, product type or the support you need.', 'wp-bbtheme-child-woo-tech' ),
		'columns'    => array(
			array( 'title' => __( 'Popular categories', 'wp-bbtheme-child-woo-tech' ), 'links' => array(
				array( __( 'Computing', 'wp-bbtheme-child-woo-tech' ), __( 'Laptops, displays and desktop essentials.', 'wp-bbtheme-child-woo-tech' ), wpbb_tech_product_category_url( 'Computing' ) ),
				array( __( 'Accessories', 'wp-bbtheme-child-woo-tech' ), __( 'Keyboards, mice, hubs and everyday add-ons.', 'wp-bbtheme-child-woo-tech' ), wpbb_tech_product_category_url( 'Accessories' ) ),
				array( __( 'Audio', 'wp-bbtheme-child-woo-tech' ), __( 'Headphones, speakers and conferencing.', 'wp-bbtheme-child-woo-tech' ), wpbb_tech_product_category_url( 'Audio' ) ),
			) ),
			array( 'title' => __( 'Shop by need', 'wp-bbtheme-child-woo-tech' ), 'links' => array(
				array( __( 'Home office', 'wp-bbtheme-child-woo-tech' ), __( 'Build a more capable workspace.', 'wp-bbtheme-child-woo-tech' ), add_query_arg( 'search', 'office', $shop ) ),
				array( __( 'Smart home', 'wp-bbtheme-child-woo-tech' ), __( 'Connected devices for everyday use.', 'wp-bbtheme-child-woo-tech' ), wpbb_tech_product_category_url( 'Smart Home' ) ),
				array( __( 'On sale', 'wp-bbtheme-child-woo-tech' ), __( 'Current offers across the catalogue.', 'wp-bbtheme-child-woo-tech' ), add_query_arg( 'on_sale', '1', $shop ) ),
			) ),
			array( 'title' => __( 'Advice & account', 'wp-bbtheme-child-woo-tech' ), 'links' => array(
				array( __( 'Buying advice', 'wp-bbtheme-child-woo-tech' ), __( 'Plain-language guidance before you order.', 'wp-bbtheme-child-woo-tech' ), wp_theme_demo_page_url( 'services' ) ),
				array( __( 'My account', 'wp-bbtheme-child-woo-tech' ), __( 'Orders, addresses and account details.', 'wp-bbtheme-child-woo-tech' ), function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url() ),
				array( __( 'Contact', 'wp-bbtheme-child-woo-tech' ), __( 'Ask for product or setup advice.', 'wp-bbtheme-child-woo-tech' ), wp_theme_demo_page_url( 'contact' ) ),
			) ),
		),
	);
	return $definitions;
}
add_filter( 'wp_theme_demo_mega_menu_definitions', 'wpbb_tech_mega_menu_definitions', 20, 2 );

/** v3.5 sector editorial labels. */
function wpbb_tech_blog_profile_v35( $profile ) {
    if ( ( $profile['id'] ?? '' ) !== 'tech' ) return $profile;
    $profile['blog_eyebrow'] = __( 'Guides & advice', 'wp-bbtheme-child-woo-tech' );
    $profile['blog_archive_title'] = __( 'Technology guides that help you choose with confidence.', 'wp-bbtheme-child-woo-tech' );
    $profile['blog_archive_intro'] = __( 'Straightforward comparisons, setup advice and buying guides without the usual specification overload.', 'wp-bbtheme-child-woo-tech' );
    return $profile;
}
add_filter( 'wp_theme_demo_profile', 'wpbb_tech_blog_profile_v35', 90 );

/** v3.6: classic WooCommerce PHP shells for the full customer journey. */
function wpbb_tech_woocommerce_support_v36() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'wpbb_tech_woocommerce_support_v36', 30 );

function wpbb_tech_woocommerce_legacy_template_v36( $template ) {
    if ( is_admin() || ! function_exists( 'WC' ) || wp_doing_ajax() || is_feed() ) return $template;
    $base = trailingslashit( get_stylesheet_directory() ) . 'woocommerce-legacy/';
    $candidate = '';
    if ( function_exists( 'is_cart' ) && is_cart() ) $candidate = 'cart.php';
    elseif ( function_exists( 'is_checkout' ) && is_checkout() ) $candidate = 'checkout.php';
    elseif ( function_exists( 'is_account_page' ) && is_account_page() ) $candidate = 'account.php';
    elseif ( function_exists( 'is_product' ) && is_product() ) $candidate = 'product.php';
    elseif ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) ) $candidate = 'catalog.php';
    if ( $candidate && is_readable( $base . $candidate ) ) return $base . $candidate;
    return $template;
}
add_filter( 'template_include', 'wpbb_tech_woocommerce_legacy_template_v36', 99 );

function wpbb_tech_woocommerce_legacy_body_class_v36( $classes ) {
    if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) $classes[] = 'wp-theme-uses-woo-legacy-shell';
    return $classes;
}
add_filter( 'body_class', 'wpbb_tech_woocommerce_legacy_body_class_v36' );


/** v3.8.10.7: complete technology single-product editorial content. */
function wpbb_tech_seed_product_content_v107( $page_id = 0, $profile = array() ) {
    if ( ! post_type_exists( 'product' ) ) return;
    foreach ( get_posts(array('post_type'=>'product','post_status'=>'publish','posts_per_page'=>-1,'fields'=>'ids')) as $id ) {
        if ( trim( wp_strip_all_tags( (string) get_post_field('post_content',$id) ) ) ) continue;
        $content='<h2>What to know</h2><p>Key specifications, intended use and compatibility are grouped here so customers can make a confident choice.</p><h2>Compatibility</h2><p>Check device, connection and operating-system requirements before ordering accessories or peripherals.</p><h2>Support</h2><p>Use the contact route for setup, warranty or product-selection questions.</p>';
        wp_update_post(array('ID'=>$id,'post_content'=>$content));
    }
}
add_action('wp_theme_after_demo_import','wpbb_tech_seed_product_content_v107',45,2);

/**
 * v3.8.10.20: keep editable Mega Menu content out of public discovery / SEO.
 * The parent already registers these objects as private; child filters make the
 * intent explicit for Core XML sitemaps and common SEO plugins too.
 */
function wpbb_child_private_megamenu_post_type_args( $args, $post_type ) {
    if ( 'megamenu' !== $post_type ) return $args;
    $args['public'] = false;
    $args['publicly_queryable'] = false;
    $args['exclude_from_search'] = true;
    $args['has_archive'] = false;
    $args['rewrite'] = false;
    $args['query_var'] = false;
    return $args;
}
add_filter( 'register_post_type_args', 'wpbb_child_private_megamenu_post_type_args', 20, 2 );

function wpbb_child_private_megamenu_taxonomy_args( $args, $taxonomy ) {
    if ( 'megamenu-cat' !== $taxonomy ) return $args;
    $args['public'] = false;
    $args['publicly_queryable'] = false;
    $args['rewrite'] = false;
    $args['query_var'] = false;
    return $args;
}
add_filter( 'register_taxonomy_args', 'wpbb_child_private_megamenu_taxonomy_args', 20, 2 );

function wpbb_child_core_sitemap_post_types( $post_types ) {
    unset( $post_types['megamenu'] );
    return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'wpbb_child_core_sitemap_post_types', 20 );

function wpbb_child_core_sitemap_taxonomies( $taxonomies ) {
    unset( $taxonomies['megamenu-cat'] );
    return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'wpbb_child_core_sitemap_taxonomies', 20 );

function wpbb_child_mega_robots( $robots ) {
    if ( is_singular( 'megamenu' ) || is_tax( 'megamenu-cat' ) ) {
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
    }
    return $robots;
}
add_filter( 'wp_robots', 'wpbb_child_mega_robots', 20 );

function wpbb_child_yoast_exclude_megamenu_post_type( $excluded, $post_type ) {
    return 'megamenu' === $post_type ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_post_type', 'wpbb_child_yoast_exclude_megamenu_post_type', 20, 2 );

function wpbb_child_yoast_exclude_megamenu_taxonomy( $excluded, $taxonomy ) {
    return 'megamenu-cat' === $taxonomy ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_taxonomy', 'wpbb_child_yoast_exclude_megamenu_taxonomy', 20, 2 );

function wpbb_child_yoast_mega_robots( $robots ) {
    if ( is_singular( 'megamenu' ) || is_tax( 'megamenu-cat' ) ) return 'noindex, nofollow';
    return $robots;
}
add_filter( 'wpseo_robots', 'wpbb_child_yoast_mega_robots', 20 );


/**
 * v3.8.10.21: global request-a-quote UI is opt-in by child theme.
 * Sector themes with their own quote journeys can keep it; the rest do not
 * expose an unrelated floating "My Quote" control or public route.
 */
if ( ! function_exists( 'wpbb_child_request_quote_enabled' ) ) {
    function wpbb_child_request_quote_enabled() {
        $enabled_themes = array(
            'wp-bbtheme-child-automotive',
            'wp-bbtheme-child-building-services',
            'wp-bbtheme-child-insurance',
            'wp-bbtheme-child-logistics',
            'wp-bbtheme-child-medicine',
            'wp-bbtheme-child-woo-tech-shop',
        );
        $enabled = in_array( get_stylesheet(), $enabled_themes, true );
        return (bool) apply_filters( 'wpbb_child_request_quote_enabled', $enabled, get_stylesheet() );
    }
}

function wpbb_child_request_quote_body_class( $classes ) {
    $classes[] = wpbb_child_request_quote_enabled() ? 'wpbb-request-quote-enabled' : 'wpbb-request-quote-disabled';
    return $classes;
}
add_filter( 'body_class', 'wpbb_child_request_quote_body_class', 30 );

function wpbb_child_request_quote_menu_items( $items ) {
    if ( wpbb_child_request_quote_enabled() ) return $items;
    $target = trim( (string) wp_parse_url( home_url( '/request-a-quote/' ), PHP_URL_PATH ), '/' );
    foreach ( $items as $key => $item ) {
        $path = trim( (string) wp_parse_url( $item->url, PHP_URL_PATH ), '/' );
        if ( $target && $path === $target ) unset( $items[ $key ] );
    }
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'wpbb_child_request_quote_menu_items', 30 );

function wpbb_child_request_quote_disable_route() {
    if ( wpbb_child_request_quote_enabled() ) return;
    $request = isset( $GLOBALS['wp']->request ) ? trim( (string) $GLOBALS['wp']->request, '/' ) : '';
    if ( ! is_page( 'request-a-quote' ) && 'request-a-quote' !== $request ) return;

    global $wp_query;
    if ( $wp_query ) $wp_query->set_404();
    status_header( 404 );
    nocache_headers();
    $template = get_404_template();
    if ( $template ) {
        include $template;
        exit;
    }
    wp_die( esc_html__( 'Page not found.', 'wp-bbtheme-child' ), esc_html__( 'Not found', 'wp-bbtheme-child' ), array( 'response' => 404 ) );
}
add_action( 'template_redirect', 'wpbb_child_request_quote_disable_route', 1 );

function wpbb_child_request_quote_sitemap_args( $args, $post_type ) {
    if ( wpbb_child_request_quote_enabled() || 'page' !== $post_type ) return $args;
    $page = get_page_by_path( 'request-a-quote' );
    if ( $page ) {
        $excluded = isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array();
        $excluded[] = (int) $page->ID;
        $args['post__not_in'] = array_values( array_unique( $excluded ) );
    }
    return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'wpbb_child_request_quote_sitemap_args', 30, 2 );

require_once get_stylesheet_directory() . '/inc/seo-guardrails.php';

/** v3.8.10.24: identify generated legal pages independently of translated slugs. */
function wpbb_child_legal_page_body_class_v381024( $classes ) {
    if ( ! is_page() ) return $classes;
    $post = get_queried_object();
    if ( ! $post instanceof WP_Post ) return $classes;

    $is_legal = function_exists( 'is_privacy_policy' ) && is_privacy_policy();
    if ( ! $is_legal && false !== strpos( (string) $post->post_content, 'wp-theme-legal-section' ) ) {
        $is_legal = true;
    }
    if ( $is_legal ) $classes[] = 'wpbb-legal-page';
    return array_values( array_unique( $classes ) );
}
add_filter( 'body_class', 'wpbb_child_legal_page_body_class_v381024', 40 );

/** v3.8.10.25: remove generated empty spacing without touching authored copy. */
if ( ! function_exists( 'wpbb_child_remove_empty_paragraphs_v381025' ) ) {
    function wpbb_child_remove_empty_paragraphs_v381025( $content ) {
        if ( is_admin() || ! is_string( $content ) || '' === $content ) return $content;
        return (string) preg_replace(
            '~<p(?:\\s[^>]*)?>(?:\\s|&nbsp;|&#160;|<br\\s*/?>)*</p>~i',
            '',
            $content
        );
    }
}
add_filter( 'the_content', 'wpbb_child_remove_empty_paragraphs_v381025', 120 );

/** v3.8.10.25: do not output a completely empty CTA block above the footer. */
if ( ! function_exists( 'wpbb_child_remove_empty_cta_v381025' ) ) {
    function wpbb_child_remove_empty_cta_v381025( $block_content, $block ) {
        if ( empty( $block['blockName'] ) || 'wpbb/cta-section' !== $block['blockName'] || ! is_string( $block_content ) ) return $block_content;
        if ( preg_match( '~<(?:img|picture|video|iframe|form|button|a)\\b~i', $block_content ) ) return $block_content;
        $plain = trim( html_entity_decode( wp_strip_all_tags( $block_content ), ENT_QUOTES | ENT_HTML5, get_bloginfo( 'charset' ) ) );
        return '' === $plain ? '' : $block_content;
    }
}
add_filter( 'render_block', 'wpbb_child_remove_empty_cta_v381025', 120, 2 );



/** v3.8.10.29: make demo switching/imports self-healing across child themes. */
if ( ! function_exists( 'wpbb_child_demo_refresh_on_activation_v381029' ) ) {
    function wpbb_child_demo_refresh_on_activation_v381029() {
        // The parent importer stores one global version/profile. When a different
        // child theme is activated, invalidate that marker so its own profile is
        // imported instead of reusing the previous child's demo state.
        delete_option( 'wp_theme_demo_import_version' );
        delete_option( 'wp_theme_demo_menu_profile' );
    }
    add_action( 'after_switch_theme', 'wpbb_child_demo_refresh_on_activation_v381029', 5 );
}

if ( ! function_exists( 'wpbb_child_demo_integrity_guard_v381029' ) ) {
    function wpbb_child_demo_integrity_guard_v381029( $page_id = 0, $profile = array() ) {
        $page_id = absint( $page_id ?: get_option( 'page_on_front' ) );
        if ( ! $page_id || 'page' !== get_post_type( $page_id ) ) return;

        $content = (string) get_post_field( 'post_content', $page_id );
        // Never rewrite a real imported or edited homepage. This is only a guard
        // for the genuinely empty/near-empty page seen after switching demos.
        if ( strlen( trim( $content ) ) >= 120 ) return;

        if ( ! is_array( $profile ) ) $profile = array();
        $eyebrow = (string) ( $profile['eyebrow'] ?? __( 'Welcome', 'wp-theme' ) );
        $title = (string) ( $profile['hero_title'] ?? get_bloginfo( 'name' ) );
        $intro = (string) ( $profile['hero_text'] ?? __( 'A practical WordPress starter site ready to edit.', 'wp-theme' ) );
        $primary_label = (string) ( $profile['primary_label'] ?? __( 'Get started', 'wp-theme' ) );
        $primary_url = (string) ( $profile['primary_url'] ?? home_url( '/contact/' ) );
        $secondary_label = (string) ( $profile['secondary_label'] ?? __( 'Explore', 'wp-theme' ) );
        $secondary_url = (string) ( $profile['secondary_url'] ?? home_url( '/services/' ) );
        $services_heading = (string) ( $profile['services_heading'] ?? __( 'Useful services, clearly presented.', 'wp-theme' ) );
        $about_title = (string) ( $profile['about_title'] ?? __( 'A flexible starting point for the real site.', 'wp-theme' ) );
        $about_text = (string) ( $profile['about_text'] ?? $intro );
        $hero_image = esc_url( (string) ( $profile['hero_image'] ?? '' ) );
        $about_image = esc_url( (string) ( $profile['about_image'] ?? $hero_image ) );
        $services = ! empty( $profile['services'] ) && is_array( $profile['services'] ) ? array_slice( $profile['services'], 0, 4 ) : array();
        $stats = ! empty( $profile['stats'] ) && is_array( $profile['stats'] ) ? array_slice( $profile['stats'], 0, 4 ) : array();

        $out = '<!-- wp:group {"className":"wp-theme-section-shell wp-theme-sector-hero wp-theme-demo-repair","layout":{"type":"default"}} --><div class="wp-block-group wp-theme-section-shell wp-theme-sector-hero wp-theme-demo-repair"><!-- wp:wpbb/row {"containerClass":"container","customClasses":"align-items-center"} --><!-- wp:wpbb/column {"xs":12,"lg":6} --><p class="wp-theme-sector-eyebrow">' . esc_html( $eyebrow ) . '</p><h1>' . esc_html( $title ) . '</h1><p class="wp-theme-sector-lead">' . esc_html( $intro ) . '</p><div class="wp-theme-demo-buttons"><a class="btn btn-primary" href="' . esc_url( $primary_url ) . '">' . esc_html( $primary_label ) . '</a><a class="btn btn-outline-primary" href="' . esc_url( $secondary_url ) . '">' . esc_html( $secondary_label ) . '</a></div><!-- /wp:wpbb/column -->';
        if ( $hero_image ) $out .= '<!-- wp:wpbb/column {"xs":12,"lg":6} --><figure class="wp-theme-sector-page-image"><img src="' . $hero_image . '" alt="" loading="eager" decoding="async"></figure><!-- /wp:wpbb/column -->';
        $out .= '<!-- /wp:wpbb/row --></div><!-- /wp:group -->';

        if ( 'automotive' === ( $profile['id'] ?? '' ) ) {
            $out .= '<!-- wp:group {"className":"wp-theme-section-shell wpbb-automotive-finder-section","layout":{"type":"default"}} --><div class="wp-block-group wp-theme-section-shell wpbb-automotive-finder-section" id="finder"><!-- wp:wpbb/row {"containerClass":"container"} --><!-- wp:wpbb/column {"xs":12} --><!-- wp:wpbb/sector-finder {"context":"automotive","limit":8} /--><!-- /wp:wpbb/column --><!-- /wp:wpbb/row --></div><!-- /wp:group -->';
        }

        $out .= '<!-- wp:group {"className":"wp-theme-section-shell wp-theme-services-section","layout":{"type":"default"}} --><div class="wp-block-group wp-theme-section-shell wp-theme-services-section"><!-- wp:wpbb/row {"containerClass":"container"} --><!-- wp:wpbb/column {"xs":12} --><p class="wp-theme-sector-eyebrow">' . esc_html( (string) ( $profile['services_eyebrow'] ?? __( 'Services', 'wp-theme' ) ) ) . '</p><h2>' . esc_html( $services_heading ) . '</h2><!-- wp:wpbb/row {"gutterX":"gx-4","gutterY":"gy-4"} -->';
        foreach ( $services as $service ) {
            $service_title = is_array( $service ) ? (string) ( $service[0] ?? '' ) : '';
            $service_text = is_array( $service ) ? (string) ( $service[1] ?? '' ) : '';
            if ( '' === trim( $service_title ) ) continue;
            $out .= '<!-- wp:wpbb/column {"xs":12,"md":6,"lg":3} --><article class="wp-theme-sector-card"><h3>' . esc_html( $service_title ) . '</h3><p>' . esc_html( $service_text ) . '</p></article><!-- /wp:wpbb/column -->';
        }
        $out .= '<!-- /wp:wpbb/row --><!-- /wp:wpbb/column --><!-- /wp:wpbb/row --></div><!-- /wp:group -->';

        $out .= '<!-- wp:group {"className":"wp-theme-section-shell wp-theme-about-section","layout":{"type":"default"}} --><div class="wp-block-group wp-theme-section-shell wp-theme-about-section"><!-- wp:wpbb/row {"containerClass":"container","customClasses":"align-items-center"} -->';
        if ( $about_image ) $out .= '<!-- wp:wpbb/column {"xs":12,"lg":6} --><figure class="wp-theme-sector-page-image"><img src="' . $about_image . '" alt="" loading="lazy" decoding="async"></figure><!-- /wp:wpbb/column -->';
        $out .= '<!-- wp:wpbb/column {"xs":12,"lg":6} --><p class="wp-theme-sector-eyebrow">' . esc_html( (string) ( $profile['about_eyebrow'] ?? __( 'About', 'wp-theme' ) ) ) . '</p><h2>' . esc_html( $about_title ) . '</h2><p class="wp-theme-sector-lead">' . esc_html( $about_text ) . '</p><!-- /wp:wpbb/column --><!-- /wp:wpbb/row --></div><!-- /wp:group -->';

        if ( $stats ) {
            $out .= '<!-- wp:group {"className":"wp-theme-section-shell wp-theme-sector-proof","layout":{"type":"default"}} --><div class="wp-block-group wp-theme-section-shell wp-theme-sector-proof"><!-- wp:wpbb/row {"containerClass":"container","gutterX":"gx-3","gutterY":"gy-3"} -->';
            foreach ( $stats as $stat ) {
                $number = is_array( $stat ) ? (string) ( $stat[0] ?? '' ) : '';
                $label = is_array( $stat ) ? (string) ( $stat[1] ?? '' ) : '';
                $out .= '<!-- wp:wpbb/column {"xs":6,"lg":3} --><div class="wp-theme-sector-proof__item"><h3>' . esc_html( $number ) . '</h3><p>' . esc_html( $label ) . '</p></div><!-- /wp:wpbb/column -->';
            }
            $out .= '<!-- /wp:wpbb/row --></div><!-- /wp:group -->';
        }

        $out .= '<!-- wp:wpbb/cta-section {"title":"' . esc_attr( (string) ( $profile['cta_title'] ?? __( 'Ready to make it yours?', 'wp-theme' ) ) ) . '","titleTag":"h2","text":"' . esc_attr( (string) ( $profile['cta_text'] ?? $intro ) ) . '","buttonText":"' . esc_attr( $primary_label ) . '","buttonUrl":"' . esc_url( $primary_url ) . '","className":"wp-theme-home-cta wp-theme-home-cta--bbuilder"} /-->';

        wp_update_post( array( 'ID' => $page_id, 'post_content' => $out ) );
        update_post_meta( $page_id, '_wp_theme_demo_repaired_381029', current_time( 'mysql' ) );
    }
    add_action( 'wp_theme_after_demo_import', 'wpbb_child_demo_integrity_guard_v381029', 99, 2 );
}


/* v3.8.10.30 visual icon configuration */
function wpbb_woo_tech_shop_visual_icon_config() {
    $config = array( 'base' => get_stylesheet_directory_uri(), 'icons' => array('device-laptop', 'camera', 'briefcase', 'users', 'chart-line', 'shield', 'map-pin', 'calendar') );
    echo '<script>window.wpbbChildVisuals=' . wp_json_encode( $config ) . ';</script>';
}
add_action( 'wp_footer', 'wpbb_woo_tech_shop_visual_icon_config', 1 );


/* v3.8.10.30: realistic demo blog featured images. Runs only after the theme's explicit demo import. */
function wpbb_woo_tech_shop_demo_blog_photo_attachment( $filename, $title ) {
    $slug = sanitize_title( pathinfo( $filename, PATHINFO_FILENAME ) );
    $existing = get_page_by_path( 'woo-tech-shop-blog-' . $slug, OBJECT, 'attachment' );
    if ( $existing ) {
        if ( function_exists( 'wpbb_woo_tech_shop_refresh_bundled_attachment_v381041' ) ) wpbb_woo_tech_shop_refresh_bundled_attachment_v381041( (int) $existing->ID, 'assets/img/blog' );
        return (int) $existing->ID;
    }
    $source = get_stylesheet_directory() . '/assets/img/blog/' . basename( $filename );
    if ( ! is_readable( $source ) ) return 0;
    $uploads = wp_upload_dir();
    $dir = trailingslashit( $uploads['basedir'] ) . 'woo-tech-shop-blog';
    wp_mkdir_p( $dir );
    $target = $dir . '/' . basename( $filename );
    if ( ! file_exists( $target ) ) copy( $source, $target );
    $filetype = wp_check_filetype( $target );
    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';
    $id = wp_insert_attachment( array(
        'post_mime_type' => $filetype['type'] ?: 'image/jpeg',
        'post_title' => $title,
        'post_name' => 'woo-tech-shop-blog-' . $slug,
        'post_status' => 'inherit',
    ), $target );
    if ( $id && ! is_wp_error( $id ) ) {
        if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';
        $meta = wp_generate_attachment_metadata( $id, $target );
        if ( $meta ) wp_update_attachment_metadata( $id, $meta );
        update_post_meta( $id, '_wp_attachment_image_alt', $title );
        return (int) $id;
    }
    return 0;
}
function wpbb_woo_tech_shop_seed_demo_blog_photos( $page_id = 0, $profile = array() ) {
    $posts = get_posts( array( 'post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>12, 'orderby'=>'date', 'order'=>'DESC' ) );
    if ( ! $posts ) return;
    $images = array( 'blog-1.jpg','blog-2.jpg','blog-3.jpg','blog-4.jpg','blog-5.jpg','blog-6.jpg' );
    foreach ( $posts as $index => $post ) {
        $filename = $images[ $index % count( $images ) ];
        $attachment = wpbb_woo_tech_shop_demo_blog_photo_attachment( $filename, get_the_title( $post ) );
        if ( $attachment ) set_post_thumbnail( $post->ID, $attachment );
    }
}
add_action( 'wp_theme_after_demo_import', 'wpbb_woo_tech_shop_seed_demo_blog_photos', 70, 2 );


/** v3.8.10.31: apply bundled realistic media to already-imported demos after theme upgrade. */

/**
 * Refresh an already-imported demo attachment from the current child-theme asset.
 *
 * Image optimisation may have changed `_wp_attached_file` from e.g. item-1.jpg to
 * item-1.avif/webp. Resolve the bundled source by filename stem instead of requiring
 * the child theme to ship every generated format, then regenerate all WP sub-sizes.
 */
function wpbb_woo_tech_shop_refresh_bundled_attachment_v381041( $attachment_id, $asset_dir ) {
    $attachment_id = absint( $attachment_id );
    if ( ! $attachment_id || 'attachment' !== get_post_type( $attachment_id ) ) return false;

    $attached = (string) get_post_meta( $attachment_id, '_wp_attached_file', true );
    $stem = pathinfo( basename( $attached ), PATHINFO_FILENAME );
    if ( '' === $stem ) return false;

    $base = trailingslashit( get_stylesheet_directory() ) . trailingslashit( $asset_dir ) . $stem;
    $source = '';
    foreach ( array( '.jpg', '.jpeg', '.png', '.webp', '.avif' ) as $extension ) {
        if ( is_readable( $base . $extension ) ) {
            $source = $base . $extension;
            break;
        }
    }
    if ( ! $source ) return false;

    $target = get_attached_file( $attachment_id );
    if ( ! $target ) return false;

    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';

    $source_ext = strtolower( (string) pathinfo( $source, PATHINFO_EXTENSION ) );
    $target_ext = strtolower( (string) pathinfo( $target, PATHINFO_EXTENSION ) );
    $written = false;

    if ( $source_ext === $target_ext ) {
        $written = (bool) @copy( $source, $target );
    } else {
        $target_type = wp_check_filetype( $target );
        $target_mime = ! empty( $target_type['type'] ) ? (string) $target_type['type'] : '';
        $editor = wp_get_image_editor( $source );
        if ( ! is_wp_error( $editor ) && 0 === strpos( $target_mime, 'image/' ) ) {
            $saved = $editor->save( $target, $target_mime );
            $written = ! is_wp_error( $saved ) && is_readable( $target );
        }
    }

    // Some hosts can read AVIF/WebP but cannot encode it. Fall back to the bundled
    // source extension and update WordPress to the new original file explicitly.
    if ( ! $written ) {
        $fallback = trailingslashit( dirname( $target ) ) . $stem . '.' . $source_ext;
        if ( ! @copy( $source, $fallback ) ) return false;
        update_attached_file( $attachment_id, $fallback );
        $filetype = wp_check_filetype( $fallback );
        if ( ! empty( $filetype['type'] ) ) {
            wp_update_post( array( 'ID' => $attachment_id, 'post_mime_type' => $filetype['type'] ) );
        }
        $target = $fallback;
    }

    // Remove old generated sizes first. Otherwise stale JPG thumbnails can remain
    // referenced after the original was converted to AVIF/WebP by an optimiser.
    $old_meta = wp_get_attachment_metadata( $attachment_id );
    if ( is_array( $old_meta ) && ! empty( $old_meta['sizes'] ) && is_array( $old_meta['sizes'] ) ) {
        foreach ( $old_meta['sizes'] as $old_size ) {
            if ( empty( $old_size['file'] ) ) continue;
            $old_file = trailingslashit( dirname( $target ) ) . basename( (string) $old_size['file'] );
            if ( is_file( $old_file ) && wp_normalize_path( $old_file ) !== wp_normalize_path( $target ) ) @unlink( $old_file );
        }
    }

    $meta = wp_generate_attachment_metadata( $attachment_id, $target );
    if ( $meta ) wp_update_attachment_metadata( $attachment_id, $meta );
    clean_attachment_cache( $attachment_id );
    return true;
}

function wpbb_woo_tech_shop_realistic_media_upgrade_v381041() {
    if ( ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) ) return;
    $done_key = 'wpbb_woo_tech_shop_realistic_media_upgrade_v381041';
    if ( get_option( $done_key ) ) return;
    if ( ! current_user_can( 'manage_options' ) ) return;

    $pairs = array(array('woo-tech-shop-blog','assets/img/blog'));
    foreach ( $pairs as $pair ) {
        $upload_prefix = $pair[0];
        $asset_dir = $pair[1];
        $ids = get_posts( array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => array( array( 'key'=>'_wp_attached_file', 'value'=>$upload_prefix . '/', 'compare'=>'LIKE' ) ),
        ) );
        foreach ( $ids as $attachment_id ) {
            wpbb_woo_tech_shop_refresh_bundled_attachment_v381041( $attachment_id, $asset_dir );
        }
    }
    if ( function_exists( 'wpbb_woo_tech_shop_seed_demo_blog_photos' ) ) wpbb_woo_tech_shop_seed_demo_blog_photos( 0, array() );
    if ( post_type_exists( 'product' ) && function_exists( 'wpbb_tech_demo_products' ) && function_exists( 'wpbb_tech_demo_product_image' ) ) {
        $products = wpbb_tech_demo_products( array() );
        foreach ( $products as $index => $product_data ) {
            $title = isset( $product_data[1] ) ? (string) $product_data[1] : '';
            if ( '' === $title ) continue;
            $matches = get_posts( array( 'post_type'=>'product', 'post_status'=>'any', 'posts_per_page'=>1, 'title'=>$title ) );
            if ( ! $matches ) continue;
            $source = wpbb_tech_demo_product_image( '', $product_data, $index );
            if ( ! $source || ! is_readable( $source ) ) continue;
            $attachment_slug = 'wpbb-woo-tech-shop-realistic-' . sanitize_title( $title );
            $existing = get_posts( array( 'post_type'=>'attachment', 'name'=>$attachment_slug, 'post_status'=>'inherit', 'posts_per_page'=>1, 'fields'=>'ids' ) );
            $attachment_id = $existing ? (int) $existing[0] : 0;
            if ( ! $attachment_id ) {
                $uploads = wp_upload_dir();
                $dir = trailingslashit( $uploads['basedir'] ) . 'wpbb-woo-tech-shop-realistic';
                wp_mkdir_p( $dir );
                $target = $dir . '/' . basename( $source );
                if ( ! @copy( $source, $target ) ) continue;
                $filetype = wp_check_filetype( $target );
                if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';
                $attachment_id = wp_insert_attachment( array( 'post_mime_type'=>$filetype['type'] ?: 'image/jpeg', 'post_title'=>$title, 'post_name'=>$attachment_slug, 'post_status'=>'inherit' ), $target );
            } else {
                $target = get_attached_file( $attachment_id );
                if ( $target ) @copy( $source, $target );
            }
            if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                $target = get_attached_file( $attachment_id );
                if ( $target ) {
                    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';
                    $meta = wp_generate_attachment_metadata( $attachment_id, $target );
                    if ( $meta ) wp_update_attachment_metadata( $attachment_id, $meta );
                }
                set_post_thumbnail( $matches[0]->ID, $attachment_id );
            }
        }
    }
    update_option( $done_key, current_time( 'mysql' ), false );
}
add_action( 'admin_init', 'wpbb_woo_tech_shop_realistic_media_upgrade_v381041', 120 );


/* v3.8.10.42: full-width single-column demo rows + optional frontend demo protection. */
function wpbb_child_381042_repair_single_columns( $blocks ) {
    foreach ( $blocks as &$block ) {
        if ( 'wpbb/row' === ( $block['blockName'] ?? '' ) && ! empty( $block['innerBlocks'] ) ) {
            $column_indexes = array();
            foreach ( $block['innerBlocks'] as $index => $inner ) {
                if ( 'wpbb/column' === ( $inner['blockName'] ?? '' ) ) $column_indexes[] = $index;
            }
            if ( 1 === count( $column_indexes ) ) {
                $idx = $column_indexes[0];
                $attrs = $block['innerBlocks'][ $idx ]['attrs'] ?? array();
                if ( 12 === (int) ( $attrs['xs'] ?? 12 ) ) {
                    $attrs['xs'] = 12;
                    foreach ( array( 'sm', 'md', 'lg', 'xl', 'xxl' ) as $breakpoint ) unset( $attrs[ $breakpoint ] );
                    $block['innerBlocks'][ $idx ]['attrs'] = $attrs;
                }
            }
        }
        if ( ! empty( $block['innerBlocks'] ) ) $block['innerBlocks'] = wpbb_child_381042_repair_single_columns( $block['innerBlocks'] );
    }
    unset( $block );
    return $blocks;
}

function wpbb_child_381042_repair_demo_page_widths() {
    $pages = get_posts( array(
        'post_type' => 'page', 'post_status' => 'any', 'posts_per_page' => -1,
        'meta_key' => '_wp_theme_demo_managed', 'meta_value' => '1', 'fields' => 'ids',
    ) );
    foreach ( $pages as $page_id ) {
        $content = (string) get_post_field( 'post_content', $page_id );
        if ( false === strpos( $content, 'wpbb/column' ) ) continue;
        $blocks = parse_blocks( $content );
        $repaired = serialize_blocks( wpbb_child_381042_repair_single_columns( $blocks ) );
        if ( $repaired !== $content ) wp_update_post( array( 'ID' => $page_id, 'post_content' => $repaired ) );
    }
}
add_action( 'wp_theme_after_demo_import', 'wpbb_child_381042_repair_demo_page_widths', 140 );
function wpbb_child_381042_repair_demo_page_widths_once() {
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) return;
    $key = 'wpbb_381042_single_col_' . sanitize_key( get_stylesheet() );
    if ( get_option( $key ) ) return;
    wpbb_child_381042_repair_demo_page_widths();
    update_option( $key, 1, false );
}
add_action( 'admin_init', 'wpbb_child_381042_repair_demo_page_widths_once', 40 );

/**
 * v3.8.10.43: repair shared demo alignment and force one fresh media pass.
 *
 * The previous media migration was intentionally one-shot. This release uses a
 * new per-theme marker so sites that already ran v381041 receive the current
 * child-owned room/product/project/blog images as well.
 */
if ( ! function_exists( 'wpbb_child_381043_normalize_text' ) ) {
    function wpbb_child_381043_normalize_text( $value ) {
        $value = html_entity_decode( wp_strip_all_tags( (string) $value ), ENT_QUOTES | ENT_HTML5, get_bloginfo( 'charset' ) );
        return trim( preg_replace( '/\\s+/u', ' ', $value ) );
    }
}

if ( ! function_exists( 'wpbb_child_381043_dedupe_single_body' ) ) {
    function wpbb_child_381043_dedupe_single_body( $content, $excerpt = '' ) {
        $excerpt_text = wpbb_child_381043_normalize_text( $excerpt );
        if ( '' === $excerpt_text ) return $content;

        $content_text = wpbb_child_381043_normalize_text( $content );
        if ( $content_text === $excerpt_text ) return '';

        if ( preg_match( '~^\\s*<p(?:\\s[^>]*)?>(.*?)</p>~is', (string) $content, $match ) ) {
            if ( wpbb_child_381043_normalize_text( $match[1] ) === $excerpt_text ) {
                return ltrim( substr( (string) $content, strlen( $match[0] ) ) );
            }
        }
        return $content;
    }
}

if ( ! function_exists( 'wpbb_child_381043_repair_block_alignment' ) ) {
    function wpbb_child_381043_repair_block_alignment( $blocks ) {
        foreach ( $blocks as &$block ) {
            if ( 'wpbb/row' === ( $block['blockName'] ?? '' ) ) {
                $attrs = $block['attrs'] ?? array();
                $classes = preg_split( '/\\s+/', trim( (string) ( $attrs['customClasses'] ?? '' ) ) );
                $classes = array_values( array_filter( array_map( 'sanitize_html_class', $classes ) ) );
                if ( in_array( 'wp-theme-sector-media-text', $classes, true ) ) {
                    $classes = array_values( array_diff( $classes, array( 'align-items-center', 'align-items-end' ) ) );
                    if ( ! in_array( 'align-items-start', $classes, true ) ) $classes[] = 'align-items-start';
                    $attrs['customClasses'] = implode( ' ', $classes );
                    $block['attrs'] = $attrs;
                }
            }
            if ( ! empty( $block['innerBlocks'] ) ) {
                $block['innerBlocks'] = wpbb_child_381043_repair_block_alignment( $block['innerBlocks'] );
            }
        }
        unset( $block );
        return $blocks;
    }
}

if ( ! function_exists( 'wpbb_child_381043_repair_demo_pages' ) ) {
    function wpbb_child_381043_repair_demo_pages() {
        // Repair every page that actually contains the theme's media/text row.
        // This also covers front pages imported before the managed-page marker existed.
        $page_ids = get_posts( array(
            'post_type' => 'page',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ) );
        foreach ( $page_ids as $page_id ) {
            $content = (string) get_post_field( 'post_content', $page_id );
            if ( false === strpos( $content, 'wp-theme-sector-media-text' ) ) continue;
            $repaired = serialize_blocks( wpbb_child_381043_repair_block_alignment( parse_blocks( $content ) ) );
            if ( $repaired !== $content ) {
                wp_update_post( array( 'ID' => $page_id, 'post_content' => $repaired ) );
                clean_post_cache( $page_id );
            }
        }
    }
}

if ( ! function_exists( 'wpbb_child_381043_refresh_media_once' ) ) {
    function wpbb_child_381043_refresh_media_once( $page_id = 0, $profile = array() ) {
        if ( ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) ) return;
        if ( ! current_user_can( 'manage_options' ) ) return;

        $current_stylesheet = sanitize_key( get_stylesheet() );
        $done_key = 'wpbb_child_381043_media_' . $current_stylesheet;
        $owner_key = 'wpbb_child_381043_media_owner';
        // Demo posts are shared while child themes are switched. Refresh again
        // whenever a different child theme last supplied the active media.
        if ( get_option( $done_key ) && $current_stylesheet === (string) get_option( $owner_key ) ) return;

        $defined = get_defined_functions();
        foreach ( (array) ( $defined['user'] ?? array() ) as $function_name ) {
            if ( ! preg_match( '/^wpbb_[a-z0-9_]+_realistic_media_upgrade_v381041$/', $function_name ) ) continue;
            delete_option( $function_name );
            call_user_func( $function_name );
        }

        // Correct stale titles/alt text left behind when the same demo posts were
        // reused while switching child themes.
        $post_ids = get_posts( array(
            'post_type' => 'any',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'meta_key' => '_thumbnail_id',
            'fields' => 'ids',
        ) );
        foreach ( $post_ids as $post_id ) {
            $thumbnail_id = (int) get_post_thumbnail_id( $post_id );
            if ( ! $thumbnail_id ) continue;
            $attached = (string) get_post_meta( $thumbnail_id, '_wp_attached_file', true );
            $attachment_name = (string) get_post_field( 'post_name', $thumbnail_id );
            if ( false === strpos( $attached, '-blog/' ) && 0 !== strpos( $attachment_name, 'wpbb-' ) ) continue;
            $title = get_the_title( $post_id );
            if ( '' === trim( (string) $title ) ) continue;
            wp_update_post( array( 'ID' => $thumbnail_id, 'post_title' => $title ) );
            update_post_meta( $thumbnail_id, '_wp_attachment_image_alt', $title );
            clean_post_cache( $post_id );
            clean_attachment_cache( $thumbnail_id );
        }

        wpbb_child_381043_repair_demo_pages();
        update_option( $done_key, current_time( 'mysql' ), false );
        update_option( $owner_key, $current_stylesheet, false );
    }
}
add_action( 'wp_theme_after_demo_import', 'wpbb_child_381043_refresh_media_once', 180, 2 );
add_action( 'admin_init', 'wpbb_child_381043_refresh_media_once', 130 );

/**
 * v3.8.10.45: shared rhythm, contrast, sector-media and gallery repair.
 */
require_once __DIR__ . '/inc/sector-consistency.php';
