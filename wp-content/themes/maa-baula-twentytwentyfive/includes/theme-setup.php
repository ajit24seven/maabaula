<?php
/**
 * Theme assets and setup.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}

function maa_baula_tt5_assets() {
	wp_enqueue_style( 'maa-baula-tt5-fonts', 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'maa-baula-tt5-style', get_stylesheet_uri(), array( 'wp-block-library' ), MAA_BAULA_TT5_VERSION );
	wp_enqueue_script( 'maa-baula-tt5-navigation', get_stylesheet_directory_uri() . '/assets/js/navigation.js', array(), MAA_BAULA_TT5_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'maa_baula_tt5_assets' );
