<?php
/**
 * Page navigation helpers.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}

function maa_baula_tt5_get_section_pages() {
	static $pages = null;
	if ( null === $pages ) {
		$pages = array();
		$posts = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_key'       => '_maa_baula_section_key',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		) );
		foreach ( $posts as $post ) {
			$key = get_post_meta( $post->ID, '_maa_baula_section_key', true );
			if ( '' !== $key ) {
				$pages[ $key ] = $post;
			}
		}
	}
	return $pages;
}

function maa_baula_tt5_section_page_url( $key ) {
	$pages = maa_baula_tt5_get_section_pages();
	return isset( $pages[ $key ] ) ? home_url( '/#' . $key ) : home_url( '/' );
}

function maa_baula_tt5_get_navigation_pages() {
	$home_id = absint( get_option( 'page_on_front' ) );

	return get_pages( array(
		'post_status' => 'publish',
		'sort_column' => 'menu_order,post_title',
		'sort_order'  => 'ASC',
		'exclude'     => $home_id ? array( $home_id ) : array(),
	) );
}

function maa_baula_tt5_navigation_label( $page ) {
	return get_the_title( $page );
}

function maa_baula_tt5_navigation_page_url( $page ) {
	$section_key = get_post_meta( $page->ID, '_maa_baula_section_key', true );
	return '' !== $section_key ? maa_baula_tt5_section_page_url( $section_key ) : get_permalink( $page );
}
