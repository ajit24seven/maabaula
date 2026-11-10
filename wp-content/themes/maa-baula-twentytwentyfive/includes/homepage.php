<?php
/**
 * Homepage setup and pattern.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}

function maa_baula_tt5_homepage_pattern_content() {
	$section_keys = array( 'home', 'about', 'services', 'industries', 'materials', 'infrastructure', 'values', 'careers', 'quote', 'contact' );
	$directory    = get_stylesheet_directory() . '/patterns/homepage-sections/';
	$content      = '';

	foreach ( $section_keys as $section_key ) {
		$section_path = $directory . $section_key . '.html';
		$section      = file_get_contents( $section_path );

		if ( false === $section ) {
			error_log( 'Maa Baula homepage setup could not read the "' . $section_key . '" section file.' );
			return new WP_Error( 'maa_baula_homepage_section_missing', __( 'A homepage section file could not be loaded.', 'maa-baula-twentytwentyfive' ) );
		}

		$content .= trim( $section ) . "\n\n";
	}

	return trim( $content );
}

function maa_baula_tt5_register_homepage_pattern() {
	$content = maa_baula_tt5_homepage_pattern_content();
	if ( is_wp_error( $content ) ) {
		return;
	}

	register_block_pattern(
		'maa-baula-twentytwentyfive/homepage',
		array(
			'title'      => __( 'Maa Baula homepage', 'maa-baula-twentytwentyfive' ),
			'categories' => array( 'featured' ),
			'content'    => $content,
		)
	);
}
add_action( 'init', 'maa_baula_tt5_register_homepage_pattern', 9 );

function maa_baula_tt5_setup_homepage() {
	if ( get_option( 'maa_baula_homepage_setup_complete' ) ) {
		return;
	}

	$front_page_id = absint( get_option( 'page_on_front' ) );
	$front_page    = $front_page_id ? get_post( $front_page_id ) : get_page_by_path( 'home', OBJECT, 'page' );
	if ( $front_page && 'page' !== $front_page->post_type ) {
		$front_page = null;
	}
	$set_as_front_page = ! $front_page_id || ! $front_page;

	if ( ! $front_page ) {
		$front_page_id = wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => __( 'Home', 'maa-baula-twentytwentyfive' ),
			'post_name'   => 'home',
		), true );

		if ( is_wp_error( $front_page_id ) ) {
			error_log( 'Maa Baula homepage setup could not create the Home page: ' . $front_page_id->get_error_message() );
			return;
		}

		$front_page = get_post( $front_page_id );
	}

	$blocks = maa_baula_tt5_homepage_pattern_content();
	if ( is_wp_error( $blocks ) ) {
		return;
	}

	if ( '' === trim( $front_page->post_content ) || has_shortcode( $front_page->post_content, 'maa_baula_homepage' ) ) {
		$result = wp_update_post( array(
			'ID'           => $front_page->ID,
			'post_content' => $blocks,
		), true );

		if ( is_wp_error( $result ) ) {
			error_log( 'Maa Baula homepage setup could not populate the Home page: ' . $result->get_error_message() );
			return;
		}
	}

	if ( $set_as_front_page ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page->ID );
	}

	update_option( 'maa_baula_homepage_setup_complete', $front_page->ID );
}
add_action( 'init', 'maa_baula_tt5_setup_homepage', 20 );
