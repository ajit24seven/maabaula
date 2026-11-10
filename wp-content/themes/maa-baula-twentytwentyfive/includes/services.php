<?php
/**
 * Service presentation.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}

function maa_baula_tt5_service_image( $post_id ) {
	return get_the_post_thumbnail_url( $post_id, 'large' );
}

function maa_baula_tt5_service_excerpt( $post_id ) {
	$content = get_post_field( 'post_content', $post_id );
	$content = strip_shortcodes( $content );
	$paragraphs = array();
	preg_match_all( '/<p\b[^>]*>(.*?)<\/p>/is', wpautop( $content ), $paragraphs );
	if ( ! empty( $paragraphs[1] ) ) {
		foreach ( $paragraphs[1] as $paragraph ) {
			$paragraph = trim( wp_strip_all_tags( $paragraph ) );
			if ( '' !== $paragraph ) {
				$content = $paragraph;
				break;
			}
		}
	}
	$content = wp_strip_all_tags( $content );

	return wp_trim_words( $content, 26 );
}

function maa_baula_tt5_home_services_shortcode() {
	$query = new WP_Query( array(
		'post_type'      => 'maa_service',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
	) );

	ob_start();
	?>
	<div class="service-grid">
		<?php
		$index = 0;
		while ( $query->have_posts() ) :
			$query->the_post();
			$index++;
			$post_id   = get_the_ID();
			$image     = maa_baula_tt5_service_image( $post_id );
			$card_class = 1 === $index ? 'service-card service-card-featured' : 'service-card';
			?>
			<article class="<?php echo esc_attr( $card_class ); ?>">
				<a class="service-image" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" aria-label="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
					<?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>" loading="lazy"><?php endif; ?>
					<span class="service-number"><?php echo esc_html( sprintf( '%02d', $index ) ); ?></span>
				</a>
				<div class="service-card-copy">
					<h3><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h3>
					<p><?php echo esc_html( maa_baula_tt5_service_excerpt( $post_id ) ); ?></p>
					<a class="service-more" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php esc_html_e( 'Explore service', 'maa-baula-twentytwentyfive' ); ?><span aria-hidden="true">↗</span></a>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
	<?php
	wp_reset_postdata();
	$output = ob_get_clean();
	return preg_replace( '/>\s+</', '><', $output );
}
add_shortcode( 'maa_baula_home_services', 'maa_baula_tt5_home_services_shortcode' );
