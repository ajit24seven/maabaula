<?php
/**
 * Section editor fields.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}

function maa_baula_tt5_add_industry_supports_metabox( $post ) {
	if ( $post && 'industries' === get_post_meta( $post->ID, '_maa_baula_section_key', true ) ) {
		add_meta_box(
			'maa-baula-industry-supports',
			__( 'Industry Supports', 'maa-baula-twentytwentyfive' ),
			'maa_baula_tt5_render_industry_supports_metabox',
			'page',
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes_page', 'maa_baula_tt5_add_industry_supports_metabox' );

function maa_baula_tt5_add_material_supply_metabox( $post ) {
	if ( $post && 'materials' === get_post_meta( $post->ID, '_maa_baula_section_key', true ) ) {
		add_meta_box(
			'maa-baula-material-supply',
			__( 'Material Supply Categories', 'maa-baula-twentytwentyfive' ),
			'maa_baula_tt5_render_material_supply_metabox',
			'page',
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes_page', 'maa_baula_tt5_add_material_supply_metabox' );

function maa_baula_tt5_add_infrastructure_metabox( $post ) {
	if ( $post && 'infrastructure' === get_post_meta( $post->ID, '_maa_baula_section_key', true ) ) {
		add_meta_box(
			'maa-baula-infrastructure',
			__( 'Infrastructure Section', 'maa-baula-twentytwentyfive' ),
			'maa_baula_tt5_render_infrastructure_metabox',
			'page',
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes_page', 'maa_baula_tt5_add_infrastructure_metabox' );

function maa_baula_tt5_render_infrastructure_metabox( $post ) {
	$section = get_post_meta( $post->ID, '_maa_baula_infrastructure_section', true );
	if ( ! is_array( $section ) ) {
		$section = array();
	}
	$items = isset( $section['items'] ) && is_array( $section['items'] ) ? $section['items'] : array();
	wp_nonce_field( 'maa_baula_save_infrastructure', 'maa_baula_infrastructure_nonce' );
	?>
	<p><?php esc_html_e( 'Edit the Infrastructure section image, heading, description, support list and link.', 'maa-baula-twentytwentyfive' ); ?></p>
	<div class="maa-infrastructure-image-row" style="display:grid;grid-template-columns:150px minmax(0,1fr);gap:16px;align-items:start;">
		<div class="maa-infrastructure-image-preview" style="margin-bottom:8px;">
			<?php if ( ! empty( $section['image'] ) ) : ?><img src="<?php echo esc_url( $section['image'] ); ?>" alt="" style="display:block;width:150px;height:110px;object-fit:cover;"><?php else : ?><div style="width:150px;height:110px;display:grid;place-items:center;background:#f0f0f1;"><?php esc_html_e( 'No image selected', 'maa-baula-twentytwentyfive' ); ?></div><?php endif; ?>
		</div>
		<div>
			<input class="maa-infrastructure-image-url" type="hidden" name="maa_baula_infrastructure_section[image]" value="<?php echo esc_url( isset( $section['image'] ) ? $section['image'] : '' ); ?>">
			<p><button type="button" class="button maa-select-infrastructure-image"><?php esc_html_e( 'Choose image', 'maa-baula-twentytwentyfive' ); ?></button> <button type="button" class="button-link-delete maa-remove-infrastructure-image"><?php esc_html_e( 'Remove image', 'maa-baula-twentytwentyfive' ); ?></button></p>
			<p><label><strong><?php esc_html_e( 'Image alt text', 'maa-baula-twentytwentyfive' ); ?></strong><br><input class="widefat" type="text" name="maa_baula_infrastructure_section[image_alt]" value="<?php echo esc_attr( isset( $section['image_alt'] ) ? $section['image_alt'] : '' ); ?>"></label></p>
		</div>
	</div>
	<p><label><strong><?php esc_html_e( 'Eyebrow', 'maa-baula-twentytwentyfive' ); ?></strong><br><input class="widefat" type="text" name="maa_baula_infrastructure_section[eyebrow]" value="<?php echo esc_attr( isset( $section['eyebrow'] ) ? $section['eyebrow'] : '' ); ?>"></label></p>
	<p><label><strong><?php esc_html_e( 'Heading', 'maa-baula-twentytwentyfive' ); ?></strong><br><textarea class="widefat" rows="2" name="maa_baula_infrastructure_section[title]"><?php echo esc_textarea( isset( $section['title'] ) ? $section['title'] : '' ); ?></textarea><span class="description"><?php esc_html_e( 'You can use &lt;em&gt; and &lt;/em&gt; around words to highlight them.', 'maa-baula-twentytwentyfive' ); ?></span></label></p>
	<p><label><strong><?php esc_html_e( 'Description', 'maa-baula-twentytwentyfive' ); ?></strong><br><textarea class="widefat" rows="3" name="maa_baula_infrastructure_section[description]"><?php echo esc_textarea( isset( $section['description'] ) ? $section['description'] : '' ); ?></textarea></label></p>
	<h3><?php esc_html_e( 'Project Support Items', 'maa-baula-twentytwentyfive' ); ?></h3>
	<div id="maa-infrastructure-item-list">
		<?php foreach ( $items as $index => $item ) : ?>
			<?php maa_baula_tt5_render_infrastructure_item( $index, $item ); ?>
		<?php endforeach; ?>
	</div>
	<p><button type="button" class="button button-secondary" id="maa-add-infrastructure-item"><?php esc_html_e( 'Add New Support Item', 'maa-baula-twentytwentyfive' ); ?></button></p>
	<script type="text/html" id="tmpl-maa-infrastructure-item">
		<?php maa_baula_tt5_render_infrastructure_item( '__INDEX__', '' ); ?>
	</script>
	<hr>
	<div class="maa-infrastructure-link-row" style="display:grid;grid-template-columns:minmax(200px,1fr) minmax(0,2fr);gap:16px;">
		<p><label><strong><?php esc_html_e( 'Link text', 'maa-baula-twentytwentyfive' ); ?></strong><br><input class="widefat" type="text" name="maa_baula_infrastructure_section[link_text]" value="<?php echo esc_attr( isset( $section['link_text'] ) ? $section['link_text'] : '' ); ?>"></label></p>
		<p><label><strong><?php esc_html_e( 'Link URL', 'maa-baula-twentytwentyfive' ); ?></strong><br><input class="widefat" type="text" name="maa_baula_infrastructure_section[link_url]" value="<?php echo esc_attr( isset( $section['link_url'] ) ? $section['link_url'] : '' ); ?>"></label></p>
	</div>
	<?php
}

function maa_baula_tt5_render_infrastructure_item( $index, $item ) {
	$item = is_string( $item ) ? $item : '';
	?>
	<div class="maa-infrastructure-item-row" style="display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px;align-items:center;padding:12px;margin:10px 0;border:1px solid #dcdcde;background:#fff;">
		<input class="widefat" type="text" name="maa_baula_infrastructure_section[items][<?php echo esc_attr( $index ); ?>]" value="<?php echo esc_attr( $item ); ?>">
		<button type="button" class="button-link-delete maa-remove-infrastructure-item"><?php esc_html_e( 'Remove item', 'maa-baula-twentytwentyfive' ); ?></button>
	</div>
	<?php
}

function maa_baula_tt5_render_material_supply_metabox( $post ) {
	$items = get_post_meta( $post->ID, '_maa_baula_material_supply_items', true );
	if ( ! is_array( $items ) ) {
		$items = array();
	}
	$intro = get_post_meta( $post->ID, '_maa_baula_material_intro', true );
	if ( ! is_array( $intro ) ) {
		$intro = array();
	}
	wp_nonce_field( 'maa_baula_save_material_supply', 'maa_baula_material_supply_nonce' );
	?>
	<h3><?php esc_html_e( 'Material Supply Introduction', 'maa-baula-twentytwentyfive' ); ?></h3>
	<p><?php esc_html_e( 'Edit the left-side heading, introduction, button and image shown in the Material Supply section.', 'maa-baula-twentytwentyfive' ); ?></p>
	<p><label><strong><?php esc_html_e( 'Eyebrow', 'maa-baula-twentytwentyfive' ); ?></strong><br><input class="widefat" type="text" name="maa_baula_material_intro[eyebrow]" value="<?php echo esc_attr( isset( $intro['eyebrow'] ) ? $intro['eyebrow'] : '' ); ?>"></label></p>
	<p><label><strong><?php esc_html_e( 'Heading', 'maa-baula-twentytwentyfive' ); ?></strong><br><textarea class="widefat" rows="2" name="maa_baula_material_intro[title]"><?php echo esc_textarea( isset( $intro['title'] ) ? wp_strip_all_tags( str_replace( array( '<br>', '<br/>', '<br />' ), "\n", $intro['title'] ) ) : '' ); ?></textarea></label></p>
	<p><label><strong><?php esc_html_e( 'Description', 'maa-baula-twentytwentyfive' ); ?></strong><br><textarea class="widefat" rows="3" name="maa_baula_material_intro[description]"><?php echo esc_textarea( isset( $intro['description'] ) ? $intro['description'] : '' ); ?></textarea></label></p>
	<div class="maa-material-intro-row" style="display:grid;grid-template-columns:minmax(200px,1fr) minmax(0,2fr);gap:16px;">
		<p><label><strong><?php esc_html_e( 'Button text', 'maa-baula-twentytwentyfive' ); ?></strong><br><input class="widefat" type="text" name="maa_baula_material_intro[button_text]" value="<?php echo esc_attr( isset( $intro['button_text'] ) ? $intro['button_text'] : '' ); ?>"></label></p>
		<p><label><strong><?php esc_html_e( 'Button link', 'maa-baula-twentytwentyfive' ); ?></strong><br><input class="widefat" type="text" name="maa_baula_material_intro[button_url]" value="<?php echo esc_attr( isset( $intro['button_url'] ) ? $intro['button_url'] : '' ); ?>"></label></p>
	</div>
	<div class="maa-material-intro-image" style="display:grid;grid-template-columns:150px minmax(0,1fr);gap:16px;align-items:start;">
		<div class="maa-material-intro-image-preview" style="margin-bottom:8px;">
			<?php if ( ! empty( $intro['image'] ) ) : ?><img src="<?php echo esc_url( $intro['image'] ); ?>" alt="" style="display:block;width:150px;height:110px;object-fit:cover;"><?php else : ?><div style="width:150px;height:110px;display:grid;place-items:center;background:#f0f0f1;"><?php esc_html_e( 'No image selected', 'maa-baula-twentytwentyfive' ); ?></div><?php endif; ?>
		</div>
		<div>
			<input class="maa-material-intro-image-url" type="hidden" name="maa_baula_material_intro[image]" value="<?php echo esc_url( isset( $intro['image'] ) ? $intro['image'] : '' ); ?>">
			<p><button type="button" class="button maa-select-material-intro-image"><?php esc_html_e( 'Choose image', 'maa-baula-twentytwentyfive' ); ?></button> <button type="button" class="button-link-delete maa-remove-material-intro-image"><?php esc_html_e( 'Remove image', 'maa-baula-twentytwentyfive' ); ?></button></p>
			<p><label><strong><?php esc_html_e( 'Image alt text', 'maa-baula-twentytwentyfive' ); ?></strong><br><input class="widefat" type="text" name="maa_baula_material_intro[image_alt]" value="<?php echo esc_attr( isset( $intro['image_alt'] ) ? $intro['image_alt'] : '' ); ?>"></label></p>
		</div>
	</div>
	<hr>
	<h3><?php esc_html_e( 'Material Supply Categories', 'maa-baula-twentytwentyfive' ); ?></h3>
	<p><?php esc_html_e( 'Edit each material category and description. Use Add New Material to add another row.', 'maa-baula-twentytwentyfive' ); ?></p>
	<div id="maa-material-supply-list">
		<?php foreach ( $items as $index => $item ) : ?>
			<?php maa_baula_tt5_render_material_supply_row( $index, $item ); ?>
		<?php endforeach; ?>
	</div>
	<p><button type="button" class="button button-secondary" id="maa-add-material-supply"><?php esc_html_e( 'Add New Material', 'maa-baula-twentytwentyfive' ); ?></button></p>
	<script type="text/html" id="tmpl-maa-material-supply-row">
		<?php maa_baula_tt5_render_material_supply_row( '__INDEX__', array() ); ?>
	</script>
	<?php
}

function maa_baula_tt5_render_material_supply_row( $index, $item ) {
	$title       = isset( $item['title'] ) ? $item['title'] : '';
	$description = isset( $item['description'] ) ? $item['description'] : '';
	$base        = 'maa_baula_material_supply_items[' . $index . ']';
	?>
	<div class="maa-material-supply-row" style="display:grid;grid-template-columns:minmax(200px,1fr) minmax(0,2fr);gap:16px;padding:16px;margin:12px 0;border:1px solid #dcdcde;background:#fff;">
		<p><label><strong><?php esc_html_e( 'Title', 'maa-baula-twentytwentyfive' ); ?></strong><br><input class="widefat" type="text" name="<?php echo esc_attr( $base . '[title]' ); ?>" value="<?php echo esc_attr( $title ); ?>" required></label></p>
		<p><label><strong><?php esc_html_e( 'Description', 'maa-baula-twentytwentyfive' ); ?></strong><br><textarea class="widefat" rows="3" name="<?php echo esc_attr( $base . '[description]' ); ?>"><?php echo esc_textarea( $description ); ?></textarea></label><br><button type="button" class="button-link-delete maa-remove-material-supply"><?php esc_html_e( 'Remove material', 'maa-baula-twentytwentyfive' ); ?></button></p>
	</div>
	<?php
}

function maa_baula_tt5_render_industry_supports_metabox( $post ) {
	$items = get_post_meta( $post->ID, '_maa_baula_industry_supports', true );
	if ( ! is_array( $items ) ) {
		$items = array();
	}
	wp_nonce_field( 'maa_baula_save_industry_supports', 'maa_baula_industry_supports_nonce' );
	?>
	<p><?php esc_html_e( 'Add an image, title and description for each industry card. Use Add New Support to create another card.', 'maa-baula-twentytwentyfive' ); ?></p>
	<div id="maa-industry-support-list">
		<?php foreach ( $items as $index => $item ) : ?>
			<?php maa_baula_tt5_render_industry_support_row( $index, $item ); ?>
		<?php endforeach; ?>
	</div>
	<p><button type="button" class="button button-secondary" id="maa-add-industry-support"><?php esc_html_e( 'Add New Support', 'maa-baula-twentytwentyfive' ); ?></button></p>
	<script type="text/html" id="tmpl-maa-industry-support-row">
		<?php maa_baula_tt5_render_industry_support_row( '__INDEX__', array() ); ?>
	</script>
	<?php
}

function maa_baula_tt5_render_industry_support_row( $index, $item ) {
	$title       = isset( $item['title'] ) ? $item['title'] : '';
	$description = isset( $item['description'] ) ? $item['description'] : '';
	$image       = isset( $item['image'] ) ? $item['image'] : '';
	$base        = 'maa_baula_industry_supports[' . $index . ']';
	?>
	<div class="maa-industry-support-row" style="display:grid;grid-template-columns:150px minmax(0,1fr);gap:16px;padding:16px;margin:12px 0;border:1px solid #dcdcde;background:#fff;">
		<div>
			<div class="maa-industry-image-preview" style="margin-bottom:8px;">
				<?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" alt="" style="display:block;width:150px;height:110px;object-fit:cover;"><?php else : ?><div style="width:150px;height:110px;display:grid;place-items:center;background:#f0f0f1;"><?php esc_html_e( 'No image selected', 'maa-baula-twentytwentyfive' ); ?></div><?php endif; ?>
			</div>
			<input class="maa-industry-image-url" type="hidden" name="<?php echo esc_attr( $base . '[image]' ); ?>" value="<?php echo esc_url( $image ); ?>">
			<button type="button" class="button maa-select-industry-image"><?php esc_html_e( 'Choose image', 'maa-baula-twentytwentyfive' ); ?></button>
			<button type="button" class="button-link-delete maa-remove-industry-image"><?php esc_html_e( 'Remove image', 'maa-baula-twentytwentyfive' ); ?></button>
		</div>
		<div>
			<p><label><strong><?php esc_html_e( 'Title', 'maa-baula-twentytwentyfive' ); ?></strong><br><input class="widefat" type="text" name="<?php echo esc_attr( $base . '[title]' ); ?>" value="<?php echo esc_attr( $title ); ?>" required></label></p>
			<p><label><strong><?php esc_html_e( 'Description', 'maa-baula-twentytwentyfive' ); ?></strong><br><textarea class="widefat" rows="3" name="<?php echo esc_attr( $base . '[description]' ); ?>"><?php echo esc_textarea( $description ); ?></textarea></label></p>
			<button type="button" class="button-link-delete maa-remove-industry-support"><?php esc_html_e( 'Remove support', 'maa-baula-twentytwentyfive' ); ?></button>
		</div>
	</div>
	<?php
}
