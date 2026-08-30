<?php
defined('ABSPATH') || exit;
function wpbb_tech_project_mode($mode){ return 'woocommerce'; }
add_filter('wp_theme_project_mode','wpbb_tech_project_mode');
function wpbb_tech_woo_profile($profile){ return 'store'; }
add_filter('wp_theme_woo_support_default_profile','wpbb_tech_woo_profile');

function wpbb_tech_assets() {
    $theme = wp_get_theme();
    wp_enqueue_style('wpbb_tech-meta', get_stylesheet_uri(), array('wp-theme-style'), $theme->get('Version'));
    $manifest = get_stylesheet_directory() . '/dist/.vite/manifest.json';
    if (!is_readable($manifest)) return;
    $data = json_decode((string) file_get_contents($manifest), true);
    if (!is_array($data)) return;
    if (!empty($data['src/scss/public.scss']['file'])) {
        wp_enqueue_style('wpbb_tech-app', get_stylesheet_directory_uri() . '/dist/' . ltrim($data['src/scss/public.scss']['file'], '/'), array('wpbb_tech-meta'), $theme->get('Version'));
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
		'hero_image' => trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/store/tech-hero.jpg',
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
		array( '12', __( 'Curated demo products', 'wp-bbtheme-child-woo-tech' ) ),
		array( '1', __( 'Clean WooCommerce system', 'wp-bbtheme-child-woo-tech' ) ),
	);
	$profile['process'] = array(
		array( '01', __( 'Choose', 'wp-bbtheme-child-woo-tech' ), __( 'Use categories, search and instant filters to narrow a large catalogue quickly.', 'wp-bbtheme-child-woo-tech' ) ),
		array( '02', __( 'Compare', 'wp-bbtheme-child-woo-tech' ), __( 'Review the details and options that actually change a buying decision.', 'wp-bbtheme-child-woo-tech' ) ),
		array( '03', __( 'Get set up', 'wp-bbtheme-child-woo-tech' ), __( 'Keep delivery, account and support journeys close to the purchase.', 'wp-bbtheme-child-woo-tech' ) ),
	);
	$profile['cta_title'] = __( 'Build a better everyday setup.', 'wp-bbtheme-child-woo-tech' );
	$profile['cta_text'] = __( 'Use the demo catalogue, filters and buying-guide sections as a complete starting point for a specialist technology retailer.', 'wp-bbtheme-child-woo-tech' );
	$profile['footer_text'] = __( 'A specialist WooCommerce starter for useful technology, clear advice and fast product discovery.', 'wp-bbtheme-child-woo-tech' );
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
