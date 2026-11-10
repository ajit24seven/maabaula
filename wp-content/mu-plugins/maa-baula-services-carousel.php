<?php
/**
 * Plugin Name: Maa Baula Services Banner Carousel
 * Description: Displays published Services as an accessible carousel in the homepage banner.
 * Version: 1.0.5
 *
 * @package Maa_Baula_Services_Carousel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function maa_baula_services_carousel_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	$plugin_url = plugin_dir_url( __FILE__ );
	$version    = '1.0.5';

	wp_enqueue_style( 'maa-baula-services-carousel', $plugin_url . 'assets/services-carousel.css', array(), $version );
	wp_enqueue_script( 'maa-baula-services-carousel', $plugin_url . 'assets/services-carousel.js', array(), $version, true );
}
add_action( 'wp_enqueue_scripts', 'maa_baula_services_carousel_assets' );

function maa_baula_services_carousel_shortcode() {
	$services = new WP_Query( array(
		'post_type'      => 'maa_service',
		'post_status'    => 'publish',
		'posts_per_page' => 6,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	) );

	if ( ! $services->have_posts() ) {
		return '';
	}

	$slides = array();
	while ( $services->have_posts() ) {
		$services->the_post();
		$post_id = get_the_ID();
		$image   = get_the_post_thumbnail_url( $post_id, 'large' );

		if ( ! $image ) {
			$image = get_post_meta( $post_id, '_maa_baula_image', true );
		}

		if ( ! $image ) {
			$image = 'https://images.unsplash.com/photo-1565043589221-1a6fd9ae45c7?auto=format&fit=crop&w=900&q=82';
		}

		$slides[] = array(
			'title'   => get_the_title(),
			'excerpt' => get_the_excerpt(),
			'url'     => get_permalink(),
			'image'   => $image,
		);
	}
	wp_reset_postdata();

	ob_start();
	?>
	<div class="maa-services-carousel" data-maa-services-carousel role="region" aria-roledescription="<?php esc_attr_e( 'carousel', 'maa-baula-services-carousel' ); ?>" aria-label="<?php esc_attr_e( 'Featured services', 'maa-baula-services-carousel' ); ?>">
		<div class="maa-services-carousel__viewport">
		<div class="maa-services-carousel__slides" data-slides>
			<?php foreach ( $slides as $index => $slide ) : ?>
				<article class="maa-services-carousel__slide<?php echo $index < 3 ? ' is-visible' : ''; ?>" data-slide aria-roledescription="<?php esc_attr_e( 'slide', 'maa-baula-services-carousel' ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '%1$d of %2$d', 'maa-baula-services-carousel' ), $index + 1, count( $slides ) ) ); ?>" aria-hidden="<?php echo $index < 3 ? 'false' : 'true'; ?>"<?php echo $index < 3 ? '' : ' hidden'; ?>>
					<a class="maa-services-carousel__link" href="<?php echo esc_url( $slide['url'] ); ?>">
						<img class="maa-services-carousel__image" src="<?php echo esc_url( $slide['image'] ); ?>" alt="" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>">
						<span class="maa-services-carousel__content">
							<span class="maa-services-carousel__title"><?php echo esc_html( $slide['title'] ); ?></span>
							<span class="maa-services-carousel__excerpt"><?php echo esc_html( wp_trim_words( $slide['excerpt'], 10 ) ); ?></span>
							<span class="maa-services-carousel__more" aria-hidden="true">&rarr;</span>
						</span>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
		</div>
		<?php if ( count( $slides ) > 3 ) : ?>
			<div class="maa-services-carousel__controls">
				<span class="maa-services-carousel__counter" data-counter aria-live="polite"><?php echo esc_html( sprintf( __( '01-%1$02d / %2$02d', 'maa-baula-services-carousel' ), min( 3, count( $slides ) ), count( $slides ) ) ); ?></span>
				<div class="maa-services-carousel__buttons">
					<button class="maa-services-carousel__button" type="button" data-previous aria-label="<?php esc_attr_e( 'Show previous services', 'maa-baula-services-carousel' ); ?>">
						<span aria-hidden="true">&uarr;</span>
					</button>
					<button class="maa-services-carousel__button" type="button" data-next aria-label="<?php esc_attr_e( 'Show next services', 'maa-baula-services-carousel' ); ?>">
						<span aria-hidden="true">&darr;</span>
					</button>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'maa_baula_services_carousel', 'maa_baula_services_carousel_shortcode' );

function maa_baula_services_carousel_in_hero( $block_content, $block ) {
	if ( ! is_front_page() || empty( $block['attrs']['className'] ) || false === strpos( $block['attrs']['className'], 'hero-layout' ) || false !== strpos( $block_content, 'maa-services-carousel' ) ) {
		return $block_content;
	}

	$carousel = maa_baula_services_carousel_shortcode();
	if ( '' === $carousel ) {
		return $block_content;
	}

	$block_content = preg_replace_callback(
		'/class="([^"]*\bhero-layout\b[^"]*)"/',
		static function ( $matches ) {
			return 'class="' . esc_attr( $matches[1] . ' has-service-carousel' ) . '"';
		},
		$block_content,
		1
	);

	$closing_tag = strrpos( $block_content, '</div>' );
	if ( false === $closing_tag ) {
		return $block_content;
	}

	return substr_replace( $block_content, $carousel, $closing_tag, 0 );
}
add_filter( 'render_block', 'maa_baula_services_carousel_in_hero', 10, 2 );
