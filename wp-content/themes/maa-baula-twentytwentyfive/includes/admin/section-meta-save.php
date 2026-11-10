<?php
/**
 * Section editor persistence.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}

function maa_baula_tt5_save_industry_supports( $post_id ) {
	if ( ! isset( $_POST['maa_baula_industry_supports_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['maa_baula_industry_supports_nonce'] ) ), 'maa_baula_save_industry_supports' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( 'industries' !== get_post_meta( $post_id, '_maa_baula_section_key', true ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$submitted = isset( $_POST['maa_baula_industry_supports'] ) && is_array( $_POST['maa_baula_industry_supports'] ) ? wp_unslash( $_POST['maa_baula_industry_supports'] ) : array();
	$items = array();
	foreach ( $submitted as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}
		$title = isset( $item['title'] ) ? sanitize_text_field( $item['title'] ) : '';
		if ( '' === $title ) {
			continue;
		}
		$items[] = array(
			'title'       => $title,
			'description' => isset( $item['description'] ) ? sanitize_textarea_field( $item['description'] ) : '',
			'image'       => isset( $item['image'] ) ? esc_url_raw( $item['image'] ) : '',
		);
	}
	update_post_meta( $post_id, '_maa_baula_industry_supports', $items );
}
add_action( 'save_post_page', 'maa_baula_tt5_save_industry_supports' );

function maa_baula_tt5_save_material_supply( $post_id ) {
	if ( ! isset( $_POST['maa_baula_material_supply_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['maa_baula_material_supply_nonce'] ) ), 'maa_baula_save_material_supply' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( 'materials' !== get_post_meta( $post_id, '_maa_baula_section_key', true ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$submitted_intro = isset( $_POST['maa_baula_material_intro'] ) && is_array( $_POST['maa_baula_material_intro'] ) ? wp_unslash( $_POST['maa_baula_material_intro'] ) : array();
	$title = isset( $submitted_intro['title'] ) ? sanitize_textarea_field( $submitted_intro['title'] ) : '';
	$title = nl2br( esc_html( $title ), false );
	$intro = array(
		'eyebrow'     => isset( $submitted_intro['eyebrow'] ) ? sanitize_text_field( $submitted_intro['eyebrow'] ) : '',
		'title'       => $title,
		'description' => isset( $submitted_intro['description'] ) ? sanitize_textarea_field( $submitted_intro['description'] ) : '',
		'button_text' => isset( $submitted_intro['button_text'] ) ? sanitize_text_field( $submitted_intro['button_text'] ) : '',
		'button_url'  => isset( $submitted_intro['button_url'] ) ? esc_url_raw( $submitted_intro['button_url'] ) : '',
		'image'       => isset( $submitted_intro['image'] ) ? esc_url_raw( $submitted_intro['image'] ) : '',
		'image_alt'   => isset( $submitted_intro['image_alt'] ) ? sanitize_text_field( $submitted_intro['image_alt'] ) : '',
	);
	update_post_meta( $post_id, '_maa_baula_material_intro', $intro );
	$submitted = isset( $_POST['maa_baula_material_supply_items'] ) && is_array( $_POST['maa_baula_material_supply_items'] ) ? wp_unslash( $_POST['maa_baula_material_supply_items'] ) : array();
	$items = array();
	foreach ( $submitted as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}
		$title = isset( $item['title'] ) ? sanitize_text_field( $item['title'] ) : '';
		if ( '' === $title ) {
			continue;
		}
		$items[] = array(
			'title'       => $title,
			'description' => isset( $item['description'] ) ? sanitize_textarea_field( $item['description'] ) : '',
		);
	}
	update_post_meta( $post_id, '_maa_baula_material_supply_items', $items );
}
add_action( 'save_post_page', 'maa_baula_tt5_save_material_supply' );

function maa_baula_tt5_save_infrastructure( $post_id ) {
	if ( ! isset( $_POST['maa_baula_infrastructure_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['maa_baula_infrastructure_nonce'] ) ), 'maa_baula_save_infrastructure' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( 'infrastructure' !== get_post_meta( $post_id, '_maa_baula_section_key', true ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$submitted = isset( $_POST['maa_baula_infrastructure_section'] ) && is_array( $_POST['maa_baula_infrastructure_section'] ) ? wp_unslash( $_POST['maa_baula_infrastructure_section'] ) : array();
	$title = isset( $submitted['title'] ) ? wp_kses( $submitted['title'], array( 'em' => array(), 'strong' => array(), 'br' => array() ) ) : '';
	$items = array();
	if ( isset( $submitted['items'] ) && is_array( $submitted['items'] ) ) {
		foreach ( $submitted['items'] as $item ) {
			$item = sanitize_text_field( $item );
			if ( '' !== $item ) {
				$items[] = $item;
			}
		}
	}
	$section = array(
		'image'       => isset( $submitted['image'] ) ? esc_url_raw( $submitted['image'] ) : '',
		'image_alt'   => isset( $submitted['image_alt'] ) ? sanitize_text_field( $submitted['image_alt'] ) : '',
		'eyebrow'     => isset( $submitted['eyebrow'] ) ? sanitize_text_field( $submitted['eyebrow'] ) : '',
		'title'       => $title,
		'description' => isset( $submitted['description'] ) ? sanitize_textarea_field( $submitted['description'] ) : '',
		'items'       => $items,
		'link_text'   => isset( $submitted['link_text'] ) ? sanitize_text_field( $submitted['link_text'] ) : '',
		'link_url'    => isset( $submitted['link_url'] ) ? esc_url_raw( $submitted['link_url'] ) : '',
	);
	update_post_meta( $post_id, '_maa_baula_infrastructure_section', $section );
}
add_action( 'save_post_page', 'maa_baula_tt5_save_infrastructure' );
