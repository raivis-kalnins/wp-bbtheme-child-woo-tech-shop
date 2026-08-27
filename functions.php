<?php
defined( 'ABSPATH' ) || exit;

function wpbb_tech_assets() {
	$theme = wp_get_theme();
	wp_enqueue_style( 'wpbb-tech', get_stylesheet_uri(), array( 'wp-theme-style' ), $theme->get( 'Version' ) );
	wp_enqueue_script( 'wpbb-tech-navigation', get_stylesheet_directory_uri() . '/assets/js/theme.js', array(), $theme->get( 'Version' ), true );
	if ( function_exists( 'wp_theme_sector_customizer_css' ) ) {
		wp_add_inline_style( 'wpbb-tech', wp_theme_sector_customizer_css( '#2563eb', '16px', '--sector-primary', '--sector-radius' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'wpbb_tech_assets', 30 );

function wpbb_tech_demo_profile( $profile ) {
	return array_merge( $profile, array(
		'id' => 'tech', 'name' => __( 'Technology Store', 'wp-bbtheme-child-woo-tech' ), 'commerce' => true,
		'eyebrow' => __( 'Technology made useful', 'wp-bbtheme-child-woo-tech' ),
		'hero_title' => __( 'Better technology for work, home and everywhere between.', 'wp-bbtheme-child-woo-tech' ),
		'hero_text' => __( 'Discover considered devices, accessories and smart essentials with clear product information and fast filtering.', 'wp-bbtheme-child-woo-tech' ),
		'hero_image' => 'https://placehold.co/1200x900/dbeafe/172554?text=Technology+Store',
		'primary_label' => __( 'Shop technology', 'wp-bbtheme-child-woo-tech' ), 'primary_url' => '#shop',
		'secondary_label' => __( 'Buying advice', 'wp-bbtheme-child-woo-tech' ), 'secondary_url' => '#services',
		'industries' => array( __( 'Home office', 'wp-bbtheme-child-woo-tech' ), __( 'Mobile life', 'wp-bbtheme-child-woo-tech' ), __( 'Gaming', 'wp-bbtheme-child-woo-tech' ), __( 'Smart home', 'wp-bbtheme-child-woo-tech' ) ),
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
