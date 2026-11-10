<?php
/**
 * Section editor assets.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function maa_baula_tt5_industry_supports_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen      = get_current_screen();
	$page_id     = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	$section_key = $page_id ? get_post_meta( $page_id, '_maa_baula_section_key', true ) : '';
	$sections    = array( 'industries', 'materials', 'infrastructure' );

	if ( ! $screen || 'page' !== $screen->post_type || ! in_array( $section_key, $sections, true ) ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'maa-baula-tt5-section-fields',
		get_stylesheet_directory_uri() . '/assets/js/section-fields.js',
		array( 'jquery' ),
		MAA_BAULA_TT5_VERSION,
		true
	);
	wp_localize_script(
		'maa-baula-tt5-section-fields',
		'maaBaulaSectionFields',
		array(
			'chooseIndustryImage'        => __( 'Choose industry image', 'maa-baula-twentytwentyfive' ),
			'chooseMaterialIntroImage'   => __( 'Choose Material Supply image', 'maa-baula-twentytwentyfive' ),
			'chooseInfrastructureImage'  => __( 'Choose Infrastructure image', 'maa-baula-twentytwentyfive' ),
			'useImage'                   => __( 'Use this image', 'maa-baula-twentytwentyfive' ),
			'noImage'                    => __( 'No image selected', 'maa-baula-twentytwentyfive' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'maa_baula_tt5_industry_supports_admin_assets' );
