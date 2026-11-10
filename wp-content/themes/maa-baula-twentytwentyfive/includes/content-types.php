<?php
/**
 * Custom content types and activation.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}

function maa_baula_tt5_register_services() {
	$labels = array(
		'name'               => __( 'Services', 'maa-baula-twentytwentyfive' ),
		'singular_name'      => __( 'Service', 'maa-baula-twentytwentyfive' ),
		'add_new_item'       => __( 'Add New Service', 'maa-baula-twentytwentyfive' ),
		'edit_item'          => __( 'Edit Service', 'maa-baula-twentytwentyfive' ),
		'new_item'           => __( 'New Service', 'maa-baula-twentytwentyfive' ),
		'view_item'          => __( 'View Service', 'maa-baula-twentytwentyfive' ),
		'search_items'       => __( 'Search Services', 'maa-baula-twentytwentyfive' ),
		'not_found'          => __( 'No services found', 'maa-baula-twentytwentyfive' ),
		'not_found_in_trash' => __( 'No services found in Trash', 'maa-baula-twentytwentyfive' ),
		'menu_name'          => __( 'Services', 'maa-baula-twentytwentyfive' ),
	);

	register_post_type( 'maa_service', array(
		'labels'        => $labels,
		'public'        => true,
		'has_archive'   => true,
		'rewrite'       => array( 'slug' => 'services' ),
		'menu_icon'     => 'dashicons-hammer',
		'show_in_rest'  => true,
		'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
	) );
}
add_action( 'init', 'maa_baula_tt5_register_services' );

function maa_baula_tt5_register_industry_supports() {
	register_post_type( 'maa_industry', array(
		'public'        => false,
		'show_ui'       => false,
		'show_in_rest'  => false,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
	) );
}
add_action( 'init', 'maa_baula_tt5_register_industry_supports' );

function maa_baula_tt5_theme_setup() {
	add_theme_support( 'post-thumbnails', array( 'maa_service', 'maa_industry' ) );
}
add_action( 'after_setup_theme', 'maa_baula_tt5_theme_setup' );

function maa_baula_tt5_activate() {
	maa_baula_tt5_register_services();
	maa_baula_tt5_register_industry_supports();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'maa_baula_tt5_activate' );
