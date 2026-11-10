<?php
/**
 * Homepage section shortcodes.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}

function maa_baula_tt5_industry_supports_shortcode() {
	$page_id = maa_baula_tt5_industry_page_id();
	$items   = $page_id ? get_post_meta( $page_id, '_maa_baula_industry_supports', true ) : array();
	if ( ! is_array( $items ) || empty( $items ) ) {
		return '';
	}
	ob_start();
	?>
	<div class="industry-grid">
		<?php foreach ( $items as $item ) : ?>
			<?php
			$title       = isset( $item['title'] ) ? $item['title'] : '';
			$description = isset( $item['description'] ) ? $item['description'] : '';
			$image       = isset( $item['image'] ) ? $item['image'] : '';
			if ( '' === $title ) {
				continue;
			}
			?>
			<article class="industry-item">
				<?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy"><?php endif; ?>
				<div class="industry-item-copy">
					<h3><?php echo esc_html( $title ); ?></h3>
					<?php if ( '' !== $description ) : ?><p class="industry-description"><?php echo esc_html( $description ); ?></p><?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
	<?php
	$output = ob_get_clean();
	return preg_replace( '/>\s+</', '><', $output );
}
add_shortcode( 'maa_baula_industry_supports', 'maa_baula_tt5_industry_supports_shortcode' );

function maa_baula_tt5_section_page_id( $section_key ) {
	$pages = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
		'posts_per_page' => 1,
		'meta_key'       => '_maa_baula_section_key',
		'meta_value'     => $section_key,
	) );
	return empty( $pages ) ? 0 : $pages[0]->ID;
}

function maa_baula_tt5_industry_page_id() {
	return maa_baula_tt5_section_page_id( 'industries' );
}

function maa_baula_tt5_materials_page_id() {
	return maa_baula_tt5_section_page_id( 'materials' );
}

function maa_baula_tt5_infrastructure_page_id() {
	return maa_baula_tt5_section_page_id( 'infrastructure' );
}

function maa_baula_tt5_infrastructure_shortcode() {
	$page_id = maa_baula_tt5_infrastructure_page_id();
	$section = $page_id ? get_post_meta( $page_id, '_maa_baula_infrastructure_section', true ) : array();
	if ( ! is_array( $section ) || empty( $section ) ) {
		return '';
	}
	$image       = isset( $section['image'] ) ? $section['image'] : '';
	$image_alt   = isset( $section['image_alt'] ) ? $section['image_alt'] : '';
	$eyebrow     = isset( $section['eyebrow'] ) ? $section['eyebrow'] : '';
	$title       = isset( $section['title'] ) ? $section['title'] : '';
	$description = isset( $section['description'] ) ? $section['description'] : '';
	$items       = isset( $section['items'] ) && is_array( $section['items'] ) ? $section['items'] : array();
	$link_text   = isset( $section['link_text'] ) ? $section['link_text'] : '';
	$link_url    = isset( $section['link_url'] ) ? $section['link_url'] : '';
	ob_start();
	?>
	<section class="wp-block-group alignfull infrastructure-section">
		<?php if ( '' !== $image ) : ?><figure class="wp-block-image size-full infrastructure-image"><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>"></figure><?php else : ?><div class="infrastructure-image"></div><?php endif; ?>
		<div class="wp-block-group infrastructure-copy">
			<?php if ( '' !== $eyebrow ) : ?><p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
			<?php if ( '' !== $title ) : ?><h2 class="section-title"><?php echo wp_kses( $title, array( 'em' => array(), 'strong' => array(), 'br' => array() ) ); ?></h2><?php endif; ?>
			<?php if ( '' !== $description ) : ?><p><?php echo esc_html( $description ); ?></p><?php endif; ?>
			<?php if ( ! empty( $items ) ) : ?><ul class="wp-block-list check-list"><?php foreach ( $items as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul><?php endif; ?>
			<?php if ( '' !== $link_text && '' !== $link_url ) : ?><p class="text-link"><a href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_text ); ?></a></p><?php endif; ?>
		</div>
	</section>
	<?php
	return preg_replace( '/>\s+</', '><', ob_get_clean() );
}
add_shortcode( 'maa_baula_infrastructure_section', 'maa_baula_tt5_infrastructure_shortcode' );

function maa_baula_tt5_material_supply_shortcode() {
	$page_id = maa_baula_tt5_materials_page_id();
	$items   = $page_id ? get_post_meta( $page_id, '_maa_baula_material_supply_items', true ) : array();
	if ( ! is_array( $items ) || empty( $items ) ) {
		return '';
	}
	ob_start();
	foreach ( $items as $index => $item ) :
		$title       = isset( $item['title'] ) ? $item['title'] : '';
		$description = isset( $item['description'] ) ? $item['description'] : '';
		if ( '' === $title ) {
			continue;
		}
		?>
		<div class="wp-block-group material-row"><p class="material-number"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></p><h3><?php echo esc_html( $title ); ?></h3><p><?php echo esc_html( $description ); ?></p><p class="material-plus" aria-hidden="true">+</p></div>
		<?php
	endforeach;
	return preg_replace( '/>\s+</', '><', ob_get_clean() );
}
add_shortcode( 'maa_baula_material_supply', 'maa_baula_tt5_material_supply_shortcode' );

function maa_baula_tt5_material_intro_shortcode() {
	$page_id = maa_baula_tt5_materials_page_id();
	$intro   = $page_id ? get_post_meta( $page_id, '_maa_baula_material_intro', true ) : array();
	if ( ! is_array( $intro ) || empty( $intro ) ) {
		return '';
	}
	$eyebrow     = isset( $intro['eyebrow'] ) ? $intro['eyebrow'] : '';
	$title       = isset( $intro['title'] ) ? $intro['title'] : '';
	$description = isset( $intro['description'] ) ? $intro['description'] : '';
	$button_text = isset( $intro['button_text'] ) ? $intro['button_text'] : '';
	$button_url  = isset( $intro['button_url'] ) ? $intro['button_url'] : '';
	$image       = isset( $intro['image'] ) ? $intro['image'] : '';
	$image_alt   = isset( $intro['image_alt'] ) ? $intro['image_alt'] : '';
	ob_start();
	?>
	<div class="wp-block-column materials-intro">
		<?php if ( '' !== $eyebrow ) : ?><p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
		<?php if ( '' !== $title ) : ?><h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2><?php endif; ?>
		<?php if ( '' !== $description ) : ?><p><?php echo esc_html( $description ); ?></p><?php endif; ?>
		<?php if ( '' !== $button_text && '' !== $button_url ) : ?><p class="materials-intro-button"><a class="wp-block-button__link wp-element-button button button-navy" href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $button_text ); ?></a></p><?php endif; ?>
		<?php if ( '' !== $image ) : ?><figure class="wp-block-image size-large materials-photo"><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>"></figure><?php endif; ?>
	</div>
	<?php
	return preg_replace( '/>\s+</', '><', ob_get_clean() );
}
add_shortcode( 'maa_baula_material_intro', 'maa_baula_tt5_material_intro_shortcode' );
