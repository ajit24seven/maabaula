<?php
/**
 * Homepage section migrations.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}

function maa_baula_tt5_migrate_homepage_sections() {
	$migration_version = absint( get_option( 'maa_baula_homepage_sections_migrated' ) );
	if ( $migration_version >= 2 ) {
		return;
	}

	if ( 1 === $migration_version ) {
		$services_pages = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => 1,
			'meta_key'       => '_maa_baula_section_key',
			'meta_value'     => 'services',
		) );
		if ( $services_pages && 'our-services' !== $services_pages[0]->post_name ) {
			$result = wp_update_post( array(
				'ID'        => $services_pages[0]->ID,
				'post_name' => 'our-services',
			), true );
			if ( is_wp_error( $result ) ) {
				error_log( 'Maa Baula section migration could not update the services page URL: ' . $result->get_error_message() );
				return;
			}
		}
		update_option( 'maa_baula_homepage_sections_migrated', 2 );
		flush_rewrite_rules( false );
		return;
	}

	$home_id = absint( get_option( 'page_on_front' ) );
	$home    = $home_id ? get_post( $home_id ) : null;
	if ( ! $home || 'page' !== $home->post_type ) {
		return;
	}

	$definitions = array(
		'about'          => array( 'title' => __( 'About Us', 'maa-baula-twentytwentyfive' ), 'slug' => 'about', 'order' => 10 ),
		'services'       => array( 'title' => __( 'Services', 'maa-baula-twentytwentyfive' ), 'slug' => 'our-services', 'order' => 20 ),
		'industries'     => array( 'title' => __( 'Industries', 'maa-baula-twentytwentyfive' ), 'slug' => 'industries', 'order' => 30 ),
		'materials'      => array( 'title' => __( 'Material Supply', 'maa-baula-twentytwentyfive' ), 'slug' => 'materials', 'order' => 40 ),
		'infrastructure' => array( 'title' => __( 'Infrastructure', 'maa-baula-twentytwentyfive' ), 'slug' => 'infrastructure', 'order' => 50 ),
		'values'         => array( 'title' => __( 'Our Approach', 'maa-baula-twentytwentyfive' ), 'slug' => 'our-approach', 'order' => 60 ),
		'careers'        => array( 'title' => __( 'Careers', 'maa-baula-twentytwentyfive' ), 'slug' => 'careers', 'order' => 70 ),
		'quote'          => array( 'title' => __( 'Request a Quote', 'maa-baula-twentytwentyfive' ), 'slug' => 'request-a-quote', 'order' => 80 ),
		'contact'        => array( 'title' => __( 'Contact', 'maa-baula-twentytwentyfive' ), 'slug' => 'contact', 'order' => 90 ),
	);
	$home_blocks = parse_blocks( $home->post_content );
	$sections    = array();

	foreach ( $home_blocks as $index => $block ) {
		$key = isset( $block['attrs']['anchor'] ) ? $block['attrs']['anchor'] : '';
		if ( '' === $key && ! empty( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'quote-section' ) ) {
			$key = 'quote';
		}
		if ( isset( $definitions[ $key ] ) ) {
			$sections[ $key ] = array(
				'index'   => $index,
				'content' => serialize_block( $block ),
			);
		}
	}

	if ( empty( $sections ) ) {
		return;
	}

	$pages = array();
	foreach ( $definitions as $key => $definition ) {
		if ( ! isset( $sections[ $key ] ) ) {
			continue;
		}

		$matching_pages = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => 1,
			'meta_key'       => '_maa_baula_section_key',
			'meta_value'     => $key,
		) );
		$page = $matching_pages ? $matching_pages[0] : null;
		if ( ! $page ) {
			$matching_pages = get_posts( array(
				'post_type'      => 'page',
				'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
				'name'           => $definition['slug'],
				'posts_per_page' => 1,
			) );
			$page = $matching_pages ? $matching_pages[0] : null;
		}

		if ( $page ) {
			$stored_key = get_post_meta( $page->ID, '_maa_baula_section_key', true );
			if ( $stored_key !== $key ) {
				error_log( 'Maa Baula section migration stopped because the "' . $definition['slug'] . '" page already exists and was not created by this migration.' );
				return;
			}
			if ( $page->post_name !== $definition['slug'] ) {
				$result = wp_update_post( array(
					'ID'        => $page->ID,
					'post_name' => $definition['slug'],
				), true );
				if ( is_wp_error( $result ) ) {
					error_log( 'Maa Baula section migration could not set the "' . $definition['slug'] . '" page URL: ' . $result->get_error_message() );
					return;
				}
				$page = get_post( $page->ID );
			}
		} else {
			$page_id = wp_insert_post( array(
				'post_type'   => 'page',
				'post_status' => 'draft',
				'post_title'  => $definition['title'],
				'post_name'   => $definition['slug'],
				'menu_order'  => $definition['order'],
			), true );

			if ( is_wp_error( $page_id ) ) {
				error_log( 'Maa Baula section migration could not create the "' . $definition['slug'] . '" page: ' . $page_id->get_error_message() );
				return;
			}

			update_post_meta( $page_id, '_maa_baula_section_key', $key );
			update_post_meta( $page_id, '_maa_baula_section_migration_pending', 1 );
			$page = get_post( $page_id );
		}
		$pages[ $key ] = $page;
	}

	foreach ( $pages as $key => $page ) {
		if ( ! get_post_meta( $page->ID, '_maa_baula_section_migration_pending', true ) ) {
			continue;
		}

		$content = $sections[ $key ]['content'];
		foreach ( $pages as $target_key => $target_page ) {
			$content = str_replace(
				'href="#' . $target_key . '"',
				'href="' . esc_url( get_permalink( $target_page ) ) . '"',
				$content
			);
		}

		$result = wp_update_post( array(
			'ID'           => $page->ID,
			'post_status'  => 'publish',
			'post_content' => $content,
		), true );
		if ( is_wp_error( $result ) ) {
			error_log( 'Maa Baula section migration could not populate the "' . $key . '" page: ' . $result->get_error_message() );
			return;
		}
		delete_post_meta( $page->ID, '_maa_baula_section_migration_pending' );
	}

	$remaining_blocks = array();
	foreach ( $home_blocks as $index => $block ) {
		$is_migrated_section = false;
		foreach ( $sections as $section ) {
			if ( $section['index'] === $index ) {
				$is_migrated_section = true;
				break;
			}
		}
		if ( ! $is_migrated_section ) {
			$remaining_blocks[] = $block;
		}
	}

	$home_content = serialize_blocks( $remaining_blocks );
	foreach ( $pages as $target_key => $target_page ) {
		$home_content = str_replace(
			'href="#' . $target_key . '"',
			'href="' . esc_url( get_permalink( $target_page ) ) . '"',
			$home_content
		);
	}

	$result = wp_update_post( array(
		'ID'           => $home->ID,
		'post_content' => $home_content,
	), true );
	if ( is_wp_error( $result ) ) {
		error_log( 'Maa Baula section migration could not update the Home page: ' . $result->get_error_message() );
		return;
	}

	update_option( 'maa_baula_homepage_sections_migrated', 2 );
	flush_rewrite_rules( false );
}
add_action( 'init', 'maa_baula_tt5_migrate_homepage_sections', 30 );

function maa_baula_tt5_sync_reference_content() {
	if ( absint( get_option( 'maa_baula_reference_content_synced' ) ) >= 4 ) {
		return;
	}

	$directory   = get_stylesheet_directory() . '/patterns/homepage-sections/';
	$home_id     = absint( get_option( 'page_on_front' ) );
	$section_keys = array( 'home', 'about', 'services', 'industries', 'materials', 'infrastructure', 'values', 'careers', 'quote', 'contact' );
	$section_pages = maa_baula_tt5_get_section_pages();

	if ( ! $home_id || ! get_post( $home_id ) ) {
		error_log( 'Maa Baula reference content sync could not find the front page.' );
		return;
	}

	foreach ( $section_keys as $section_key ) {
		$section_path = $directory . $section_key . '.html';
		$section      = file_get_contents( $section_path );
		if ( false === $section ) {
			error_log( 'Maa Baula reference content sync could not read the "' . $section_key . '" section file.' );
			return;
		}

		if ( 'home' === $section_key ) {
			$page_id = $home_id;
		} elseif ( isset( $section_pages[ $section_key ] ) ) {
			$page_id = $section_pages[ $section_key ]->ID;
		} else {
			continue;
		}

		$result = wp_update_post( array(
			'ID'           => $page_id,
			'post_content' => trim( $section ),
		), true );
		if ( is_wp_error( $result ) ) {
			error_log( 'Maa Baula reference content sync could not update the "' . $section_key . '" section: ' . $result->get_error_message() );
			return;
		}
	}

	update_option( 'maa_baula_reference_content_synced', 4 );
}
add_action( 'init', 'maa_baula_tt5_sync_reference_content', 31 );
