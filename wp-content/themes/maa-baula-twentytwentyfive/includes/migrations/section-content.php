<?php
/**
 * Section content migrations.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}

function maa_baula_tt5_collect_material_supply_items( $blocks, &$items ) {
	foreach ( $blocks as $block ) {
		if ( 'core/group' === $block['blockName'] && ! empty( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'material-row' ) ) {
			$item = array( 'title' => '', 'description' => '' );
			maa_baula_tt5_read_material_supply_fields( $block['innerBlocks'], $item );
			if ( '' !== $item['title'] ) {
				$items[] = $item;
			}
			continue;
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			maa_baula_tt5_collect_material_supply_items( $block['innerBlocks'], $items );
		}
	}
}

function maa_baula_tt5_material_intro_blocks( $blocks ) {
	foreach ( $blocks as $block ) {
		if ( 'core/column' === $block['blockName'] && ! empty( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'materials-intro' ) ) {
			return $block['innerBlocks'];
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			$intro_blocks = maa_baula_tt5_material_intro_blocks( $block['innerBlocks'] );
			if ( null !== $intro_blocks ) {
				return $intro_blocks;
			}
		}
	}
	return null;
}

function maa_baula_tt5_collect_material_intro_fields( $blocks, &$intro ) {
	foreach ( $blocks as $block ) {
		$class_name = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';
		if ( 'core/paragraph' === $block['blockName'] && false !== strpos( $class_name, 'eyebrow' ) && '' === $intro['eyebrow'] ) {
			$intro['eyebrow'] = html_entity_decode( trim( wp_strip_all_tags( $block['innerHTML'] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) );
		} elseif ( 'core/heading' === $block['blockName'] && '' === $intro['title'] && preg_match( '/<h[1-6]\b[^>]*>(.*?)<\/h[1-6]>/is', $block['innerHTML'], $matches ) ) {
			$intro['title'] = wp_kses_post( trim( $matches[1] ) );
		} elseif ( 'core/paragraph' === $block['blockName'] && '' === $intro['description'] && '' === $class_name ) {
			$intro['description'] = html_entity_decode( trim( wp_strip_all_tags( $block['innerHTML'] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) );
		} elseif ( 'core/button' === $block['blockName'] && '' === $intro['button_text'] ) {
			if ( preg_match( '/<a\b[^>]*href=(["\'])(.*?)\1[^>]*>(.*?)<\/a>/is', $block['innerHTML'], $matches ) ) {
				$intro['button_url']  = esc_url_raw( html_entity_decode( $matches[2], ENT_QUOTES, get_bloginfo( 'charset' ) ) );
				$intro['button_text'] = html_entity_decode( trim( wp_strip_all_tags( $matches[3] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) );
			}
		} elseif ( 'core/image' === $block['blockName'] && '' === $intro['image'] ) {
			$intro['image'] = isset( $block['attrs']['url'] ) ? esc_url_raw( $block['attrs']['url'] ) : '';
			$intro['image_alt'] = isset( $block['attrs']['alt'] ) ? sanitize_text_field( $block['attrs']['alt'] ) : '';
			if ( preg_match( '/<img\b[^>]*src=(["\'])(.*?)\1[^>]*>/is', $block['innerHTML'], $matches ) ) {
				if ( '' === $intro['image'] ) {
					$intro['image'] = esc_url_raw( html_entity_decode( $matches[2], ENT_QUOTES, get_bloginfo( 'charset' ) ) );
				}
				if ( preg_match( '/\balt=(["\'])(.*?)\1/is', $matches[0], $alt_matches ) ) {
					$intro['image_alt'] = sanitize_text_field( html_entity_decode( $alt_matches[2], ENT_QUOTES, get_bloginfo( 'charset' ) ) );
				}
			}
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			maa_baula_tt5_collect_material_intro_fields( $block['innerBlocks'], $intro );
		}
	}
}

function maa_baula_tt5_read_material_supply_fields( $blocks, &$item ) {
	foreach ( $blocks as $block ) {
		$class_name = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';
		if ( 'core/heading' === $block['blockName'] && '' === $item['title'] ) {
			$item['title'] = html_entity_decode( trim( wp_strip_all_tags( $block['innerHTML'] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) );
		} elseif ( 'core/paragraph' === $block['blockName'] && false === strpos( $class_name, 'material-number' ) && false === strpos( $class_name, 'material-plus' ) && '' === $item['description'] ) {
			$item['description'] = html_entity_decode( trim( wp_strip_all_tags( $block['innerHTML'] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) );
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			maa_baula_tt5_read_material_supply_fields( $block['innerBlocks'], $item );
		}
	}
}

function maa_baula_tt5_collect_infrastructure_fields( $blocks, &$section ) {
	foreach ( $blocks as $block ) {
		$class_name = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';
		if ( 'core/image' === $block['blockName'] && '' === $section['image'] ) {
			$section['image']     = isset( $block['attrs']['url'] ) ? esc_url_raw( $block['attrs']['url'] ) : '';
			$section['image_alt'] = isset( $block['attrs']['alt'] ) ? sanitize_text_field( $block['attrs']['alt'] ) : '';
			if ( preg_match( '/<img\b[^>]*src=(["\'])(.*?)\1[^>]*>/is', $block['innerHTML'], $matches ) ) {
				if ( '' === $section['image'] ) {
					$section['image'] = esc_url_raw( html_entity_decode( $matches[2], ENT_QUOTES, get_bloginfo( 'charset' ) ) );
				}
				if ( preg_match( '/\balt=(["\'])(.*?)\1/is', $matches[0], $alt_matches ) ) {
					$section['image_alt'] = sanitize_text_field( html_entity_decode( $alt_matches[2], ENT_QUOTES, get_bloginfo( 'charset' ) ) );
				}
			}
		} elseif ( 'core/paragraph' === $block['blockName'] && false !== strpos( $class_name, 'eyebrow' ) && '' === $section['eyebrow'] ) {
			$section['eyebrow'] = html_entity_decode( trim( wp_strip_all_tags( $block['innerHTML'] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) );
		} elseif ( 'core/heading' === $block['blockName'] && '' === $section['title'] && preg_match( '/<h[1-6]\b[^>]*>(.*?)<\/h[1-6]>/is', $block['innerHTML'], $matches ) ) {
			$section['title'] = wp_kses( trim( $matches[1] ), array( 'em' => array(), 'strong' => array(), 'br' => array() ) );
		} elseif ( 'core/paragraph' === $block['blockName'] && false === strpos( $class_name, 'text-link' ) && '' === $section['description'] ) {
			$section['description'] = html_entity_decode( trim( wp_strip_all_tags( $block['innerHTML'] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) );
		} elseif ( 'core/list' === $block['blockName'] && empty( $section['items'] ) ) {
			if ( ! empty( $block['innerBlocks'] ) ) {
				foreach ( $block['innerBlocks'] as $list_item ) {
					$text = trim( wp_strip_all_tags( $list_item['innerHTML'] ) );
					if ( '' !== $text ) {
						$section['items'][] = html_entity_decode( $text, ENT_QUOTES, get_bloginfo( 'charset' ) );
					}
				}
			} elseif ( preg_match_all( '/<li\b[^>]*>(.*?)<\/li>/is', $block['innerHTML'], $matches ) ) {
				foreach ( $matches[1] as $list_item ) {
					$text = trim( wp_strip_all_tags( $list_item ) );
					if ( '' !== $text ) {
						$section['items'][] = html_entity_decode( $text, ENT_QUOTES, get_bloginfo( 'charset' ) );
					}
				}
			}
		} elseif ( 'core/paragraph' === $block['blockName'] && false !== strpos( $class_name, 'text-link' ) ) {
			if ( preg_match( '/<a\b[^>]*href=(["\'])(.*?)\1[^>]*>(.*?)<\/a>/is', $block['innerHTML'], $matches ) ) {
				$section['link_url']  = esc_url_raw( html_entity_decode( $matches[2], ENT_QUOTES, get_bloginfo( 'charset' ) ) );
				$section['link_text'] = html_entity_decode( trim( wp_strip_all_tags( $matches[3] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) );
			}
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			maa_baula_tt5_collect_infrastructure_fields( $block['innerBlocks'], $section );
		}
	}
}

function maa_baula_tt5_migrate_infrastructure_section() {
	if ( absint( get_option( 'maa_baula_infrastructure_section_migrated' ) ) >= 2 ) {
		return;
	}
	$page_id = maa_baula_tt5_infrastructure_page_id();
	if ( ! $page_id ) {
		return;
	}
	$section = get_post_meta( $page_id, '_maa_baula_infrastructure_section', true );
	$content = get_post_field( 'post_content', $page_id );
	$blocks  = parse_blocks( $content );
	$has_shortcode = has_shortcode( $content, 'maa_baula_infrastructure_section' );
	if ( false === strpos( $content, 'infrastructure-section' ) ) {
		$revisions = wp_get_post_revisions( $page_id, array( 'order' => 'DESC' ) );
		foreach ( $revisions as $revision ) {
			if ( false !== strpos( $revision->post_content, 'infrastructure-section' ) ) {
				$blocks = parse_blocks( $revision->post_content );
				break;
			}
		}
	}
	$defaults = array(
		'image'       => '',
		'image_alt'   => '',
		'eyebrow'     => '',
		'title'       => '',
		'description' => '',
		'items'       => array(),
		'link_text'   => '',
		'link_url'    => '',
	);
	$section = is_array( $section ) ? array_merge( $defaults, $section ) : $defaults;
	$source_section = $defaults;
	maa_baula_tt5_collect_infrastructure_fields( $blocks, $source_section );
	foreach ( $defaults as $key => $default ) {
		if ( empty( $section[ $key ] ) && ! empty( $source_section[ $key ] ) ) {
			$section[ $key ] = $source_section[ $key ];
		}
	}
	update_post_meta( $page_id, '_maa_baula_infrastructure_section', $section );
	if ( ! $has_shortcode ) {
		$replaced = false;
		$replace_section = function ( &$nested_blocks ) use ( &$replace_section, &$replaced ) {
			foreach ( $nested_blocks as &$block ) {
				if ( 'core/group' === $block['blockName'] && ! empty( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'infrastructure-section' ) ) {
					$block = array(
						'blockName'    => 'core/shortcode',
						'attrs'        => array(),
						'innerBlocks'  => array(),
						'innerHTML'    => '[maa_baula_infrastructure_section]',
						'innerContent' => array( '[maa_baula_infrastructure_section]' ),
					);
					$replaced = true;
					continue;
				}
				if ( ! empty( $block['innerBlocks'] ) ) {
					$replace_section( $block['innerBlocks'] );
				}
			}
			unset( $block );
		};
		$replace_section( $blocks );
		if ( $replaced ) {
			$result = wp_update_post( array(
				'ID'           => $page_id,
				'post_content' => serialize_blocks( $blocks ),
			), true );
			if ( is_wp_error( $result ) ) {
				error_log( 'Maa Baula Infrastructure migration could not update the page: ' . $result->get_error_message() );
				return;
			}
		}
	}
	update_option( 'maa_baula_infrastructure_section_migrated', 2 );
}
add_action( 'init', 'maa_baula_tt5_migrate_infrastructure_section', 34 );

function maa_baula_tt5_migrate_material_supply() {
	if ( absint( get_option( 'maa_baula_material_supply_migrated' ) ) >= 3 ) {
		return;
	}
	$page_id = maa_baula_tt5_materials_page_id();
	if ( ! $page_id ) {
		return;
	}
	$items = get_post_meta( $page_id, '_maa_baula_material_supply_items', true );
	$blocks = parse_blocks( get_post_field( 'post_content', $page_id ) );
	$intro = get_post_meta( $page_id, '_maa_baula_material_intro', true );
	if ( ! is_array( $intro ) || empty( $intro ) ) {
		$intro_blocks = maa_baula_tt5_material_intro_blocks( $blocks );
		if ( null !== $intro_blocks ) {
			$intro = array(
				'eyebrow'     => '',
				'title'       => '',
				'description' => '',
				'button_text' => '',
				'button_url'  => '',
				'image'       => '',
				'image_alt'   => '',
			);
			maa_baula_tt5_collect_material_intro_fields( $intro_blocks, $intro );
			update_post_meta( $page_id, '_maa_baula_material_intro', $intro );
		}
	}
	if ( ! is_array( $items ) || empty( $items ) ) {
		$items = array();
		maa_baula_tt5_collect_material_supply_items( $blocks, $items );
		if ( ! empty( $items ) ) {
			update_post_meta( $page_id, '_maa_baula_material_supply_items', $items );
		}
	}
	$shortcode_added = false;
	$replace_rows = function ( &$nested_blocks ) use ( &$replace_rows, &$shortcode_added ) {
		$updated_blocks = array();
		foreach ( $nested_blocks as $block ) {
			if ( 'core/group' === $block['blockName'] && ! empty( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'material-row' ) ) {
				if ( ! $shortcode_added ) {
					$updated_blocks[] = array(
						'blockName'    => 'core/shortcode',
						'attrs'        => array(),
						'innerBlocks'  => array(),
						'innerHTML'    => '[maa_baula_material_supply]',
						'innerContent' => array( '[maa_baula_material_supply]' ),
					);
					$shortcode_added = true;
				}
				continue;
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$replace_rows( $block['innerBlocks'] );
			}
			$updated_blocks[] = $block;
		}
		$nested_blocks = $updated_blocks;
	};
	$intro_shortcode_added = false;
	$replace_intro = function ( &$nested_blocks ) use ( &$replace_intro, &$intro_shortcode_added ) {
		foreach ( $nested_blocks as &$block ) {
			if ( 'core/column' === $block['blockName'] && ! empty( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'materials-intro' ) ) {
				$block = array(
					'blockName'    => 'core/shortcode',
					'attrs'        => array(),
					'innerBlocks'  => array(),
					'innerHTML'    => '[maa_baula_material_intro]',
					'innerContent' => array( '[maa_baula_material_intro]' ),
				);
				$intro_shortcode_added = true;
				continue;
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$replace_intro( $block['innerBlocks'] );
			}
		}
		unset( $block );
	};
	$current_content = get_post_field( 'post_content', $page_id );
	if ( false !== strpos( $current_content, 'materials-intro' ) && false === strpos( $current_content, '[maa_baula_material_intro]' ) ) {
		$replace_intro( $blocks );
	}
	if ( false !== strpos( get_post_field( 'post_content', $page_id ), 'material-row' ) && false === strpos( get_post_field( 'post_content', $page_id ), '[maa_baula_material_supply]' ) ) {
		$replace_rows( $blocks );
	}
	if ( $shortcode_added || $intro_shortcode_added ) {
		$result = wp_update_post( array(
			'ID'           => $page_id,
			'post_content' => serialize_blocks( $blocks ),
		), true );
		if ( is_wp_error( $result ) ) {
			error_log( 'Maa Baula material migration could not update the Material Supply page: ' . $result->get_error_message() );
			return;
		}
	}
	update_option( 'maa_baula_material_supply_migrated', 3 );
}
add_action( 'init', 'maa_baula_tt5_migrate_material_supply', 33 );

function maa_baula_tt5_migrate_industry_supports() {
	if ( absint( get_option( 'maa_baula_industry_supports_migrated' ) ) >= 3 ) {
		return;
	}
	$page_id = maa_baula_tt5_industry_page_id();
	if ( ! $page_id ) {
		return;
	}
	$items = get_post_meta( $page_id, '_maa_baula_industry_supports', true );
	if ( ! is_array( $items ) || empty( $items ) ) {
		$legacy_posts = get_posts( array(
			'post_type'      => 'maa_industry',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		) );
		$items = array();
		foreach ( $legacy_posts as $legacy_post ) {
			$image = get_post_meta( $legacy_post->ID, '_maa_baula_industry_image', true );
			if ( ! $image ) {
				$image = get_the_post_thumbnail_url( $legacy_post->ID, 'large' );
			}
			$items[] = array(
				'title'       => html_entity_decode( $legacy_post->post_title, ENT_QUOTES, get_bloginfo( 'charset' ) ),
				'description' => wp_strip_all_tags( $legacy_post->post_content ),
				'image'       => $image,
			);
		}
	}
	if ( ! empty( $items ) ) {
		update_post_meta( $page_id, '_maa_baula_industry_supports', $items );
	}
	update_option( 'maa_baula_industry_supports_migrated', 3 );
}
add_action( 'init', 'maa_baula_tt5_migrate_industry_supports', 32 );
