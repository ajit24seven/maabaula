<?php
/**
 * Theme presentation shortcodes.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}

function maa_baula_tt5_homepage_sections_shortcode() {
	$pages = maa_baula_tt5_get_section_pages();
	if ( empty( $pages ) ) {
		return '';
	}

	$content = '';
	foreach ( $pages as $key => $page ) {
		$section_content = apply_filters( 'the_content', $page->post_content );
		$section_id      = esc_attr( $key );

		if ( false === strpos( $section_content, 'id="' . $section_id . '"' ) ) {
			$section_content = preg_replace( '/<section\b/', '<section id="' . $section_id . '"', $section_content, 1 );
		}

		$content .= $section_content;
	}

	return $content;
}
add_shortcode( 'maa_baula_homepage_sections', 'maa_baula_tt5_homepage_sections_shortcode' );

function maa_baula_tt5_enquiry_form_shortcode() {
	$form_id = absint( get_option( 'maa_baula_business_enquiry_form_id' ) );
	$wpforms = function_exists( 'wpforms' ) ? wpforms() : null;
	$is_sent = isset( $_GET['enquiry'] ) && 'sent' === sanitize_key( wp_unslash( $_GET['enquiry'] ) );
	ob_start();
	?>
	<div class="enquiry-form">
		<div class="form-heading"><span><?php esc_html_e( 'BUSINESS ENQUIRY', 'maa-baula-twentytwentyfive' ); ?></span><p><?php esc_html_e( 'Share a few details and outline your requirement.', 'maa-baula-twentytwentyfive' ); ?></p></div>
		<?php if ( $is_sent ) : ?>
			<p class="form-status" role="status"><?php esc_html_e( 'Thank you for your enquiry. Your message has been sent successfully. We will be in touch shortly.', 'maa-baula-twentytwentyfive' ); ?></p>
		<?php endif; ?>
		<?php if ( $form_id && $wpforms && function_exists( 'wpforms_display' ) ) : ?>
			<?php wpforms_display( $form_id, false, false ); ?>
		<?php else : ?>
			<p class="form-unavailable" role="status"><?php esc_html_e( 'The enquiry form is temporarily unavailable. Please email info@maabaula.com directly.', 'maa-baula-twentytwentyfive' ); ?></p>
		<?php endif; ?>
		<p class="form-disclaimer"><?php esc_html_e( 'Your enquiry will be emailed to info@maabaula.com. Email delivery depends on the WordPress hosting mail configuration.', 'maa-baula-twentytwentyfive' ); ?></p>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'maa_baula_enquiry_form', 'maa_baula_tt5_enquiry_form_shortcode' );

function maa_baula_tt5_header_shortcode() {
	$pages = maa_baula_tt5_get_navigation_pages();
	ob_start();
	?>
	<header class="site-header">
		<div class="topline"><div class="topline-inner"><span><?php esc_html_e( 'Joda, Keonjhar, Odisha, India', 'maa-baula-twentytwentyfive' ); ?></span><div class="topline-links"><a href="mailto:info@maabaula.com">info@maabaula.com</a><a href="tel:+917847889188">+91 78478 89188</a></div></div></div>
		<div class="nav-wrap"><div class="nav-inner">
			<a class="brand" href="<?php echo esc_url( home_url( '/#home' ) ); ?>" aria-label="<?php esc_attr_e( 'Maa Baula Industries and Services home', 'maa-baula-twentytwentyfive' ); ?>"><img class="brand-mark" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/maa-baula-mark-extracted.png' ); ?>" alt=""><span class="brand-copy"><strong><?php esc_html_e( 'MAA BAULA', 'maa-baula-twentytwentyfive' ); ?></strong><small><?php esc_html_e( 'INDUSTRIES & SERVICES', 'maa-baula-twentytwentyfive' ); ?></small><small class="brand-private"><?php esc_html_e( 'PRIVATE LIMITED', 'maa-baula-twentytwentyfive' ); ?></small></span></a>
			<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" aria-label="<?php esc_attr_e( 'Open navigation menu', 'maa-baula-twentytwentyfive' ); ?>"><span></span><span></span><span></span></button>
			<nav id="primary-navigation" class="primary-navigation" aria-label="<?php esc_attr_e( 'Main navigation', 'maa-baula-twentytwentyfive' ); ?>"><div class="fallback-menu">
				<?php foreach ( $pages as $page ) : ?>
					<?php
					$is_quote_page = 'quote' === get_post_meta( $page->ID, '_maa_baula_section_key', true );
					$link_classes  = $is_quote_page ? 'button button-gold' : '';
					?>
					<a<?php if ( '' !== $link_classes ) : ?> class="<?php echo esc_attr( $link_classes ); ?>"<?php endif; ?> href="<?php echo esc_url( maa_baula_tt5_navigation_page_url( $page ) ); ?>"><?php echo esc_html( maa_baula_tt5_navigation_label( $page ) ); ?><?php if ( $is_quote_page ) : ?><svg class="arrow" aria-hidden="true" viewBox="0 0 20 20"><path d="M3.5 10h12M10 4.5l5.5 5.5-5.5 5.5"></path></svg><?php endif; ?></a>
				<?php endforeach; ?>
			</div></nav>
		</div></div>
	</header>
	<?php
	return ob_get_clean();
}
add_shortcode( 'maa_baula_header', 'maa_baula_tt5_header_shortcode' );

function maa_baula_tt5_footer_shortcode() {
	$pages         = maa_baula_tt5_get_section_pages();
	$explore_keys  = array( 'about', 'services', 'industries', 'careers' );
	$solution_keys = array( 'materials', 'infrastructure' );
	ob_start();
	?>
	<footer class="site-footer">
		<div class="page-width footer-main">
			<div class="footer-brand"><a class="brand" href="<?php echo esc_url( home_url( '/#home' ) ); ?>"><img class="brand-mark" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/maa-baula-mark-extracted.png' ); ?>" alt=""><span class="brand-copy"><strong><?php esc_html_e( 'MAA BAULA', 'maa-baula-twentytwentyfive' ); ?></strong><small><?php esc_html_e( 'INDUSTRIES & SERVICES', 'maa-baula-twentytwentyfive' ); ?></small><small class="brand-private"><?php esc_html_e( 'PRIVATE LIMITED', 'maa-baula-twentytwentyfive' ); ?></small></span></a><p><?php esc_html_e( 'Your Single-Window Partner for Complete Industrial, Mining & Infrastructure Solutions.', 'maa-baula-twentytwentyfive' ); ?></p><span class="footer-location"><?php esc_html_e( 'Joda, Keonjhar, Odisha, India', 'maa-baula-twentytwentyfive' ); ?></span></div>
			<div class="footer-column"><h3><?php esc_html_e( 'Explore', 'maa-baula-twentytwentyfive' ); ?></h3><?php foreach ( $explore_keys as $key ) : ?><?php if ( isset( $pages[ $key ] ) ) : ?><a href="<?php echo esc_url( maa_baula_tt5_section_page_url( $key ) ); ?>"><?php echo esc_html( maa_baula_tt5_navigation_label( $pages[ $key ] ) ); ?></a><?php endif; ?><?php endforeach; ?></div>
			<div class="footer-column"><h3><?php esc_html_e( 'Solutions', 'maa-baula-twentytwentyfive' ); ?></h3><?php foreach ( $solution_keys as $key ) : ?><?php if ( isset( $pages[ $key ] ) ) : ?><a href="<?php echo esc_url( maa_baula_tt5_section_page_url( $key ) ); ?>"><?php echo esc_html( maa_baula_tt5_navigation_label( $pages[ $key ] ) ); ?></a><?php endif; ?><?php endforeach; ?><a href="<?php echo esc_url( get_post_type_archive_link( 'maa_service' ) ); ?>"><?php esc_html_e( 'All services', 'maa-baula-twentytwentyfive' ); ?></a><a href="<?php echo esc_url( maa_baula_tt5_section_page_url( 'quote' ) ); ?>"><?php esc_html_e( 'Request a quote', 'maa-baula-twentytwentyfive' ); ?></a></div>
			<div class="footer-column"><h3><?php esc_html_e( 'Get in touch', 'maa-baula-twentytwentyfive' ); ?></h3><a href="mailto:info@maabaula.com">info@maabaula.com</a><a href="tel:+917847889188">+91 78478 89188</a><a href="https://www.maabaula.com" target="_blank" rel="noopener noreferrer">www.maabaula.com</a><a href="<?php echo esc_url( maa_baula_tt5_section_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Business enquiry', 'maa-baula-twentytwentyfive' ); ?></a></div>
		</div>
		<div class="footer-bottom"><div class="page-width footer-bottom-inner"><span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php esc_html_e( 'M/s Maa Baula Industries and Services Private Limited', 'maa-baula-twentytwentyfive' ); ?></span><span><?php esc_html_e( 'ONE PARTNER. EVERY INDUSTRIAL SOLUTION.', 'maa-baula-twentytwentyfive' ); ?></span></div></div>
	</footer>
	<a class="scroll-to-top" href="<?php echo esc_url( home_url( '/#home' ) ); ?>" aria-label="<?php esc_attr_e( 'Scroll to top', 'maa-baula-twentytwentyfive' ); ?>" title="<?php esc_attr_e( 'Scroll to top', 'maa-baula-twentytwentyfive' ); ?>"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="M12 19V5m-7 7 7-7 7 7"></path></svg></a>
	<?php
	return ob_get_clean();
}
add_shortcode( 'maa_baula_footer', 'maa_baula_tt5_footer_shortcode' );

function maa_baula_tt5_service_archive_shortcode() {
	$query = new WP_Query( array(
		'post_type'      => 'maa_service',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	) );
	ob_start();
	?>
	<main>
		<section class="archive-intro">
			<img src="https://images.unsplash.com/photo-1516937941344-00b4e0337589?auto=format&amp;fit=crop&amp;w=1800&amp;q=82" alt="">
			<div class="page-width archive-intro-content"><span class="eyebrow"><?php esc_html_e( 'What we do', 'maa-baula-twentytwentyfive' ); ?></span><h1><?php esc_html_e( 'Industrial capability, brought together.', 'maa-baula-twentytwentyfive' ); ?></h1><p><?php esc_html_e( 'Explore our complete range of industrial, infrastructure, manpower, procurement and project support services.', 'maa-baula-twentytwentyfive' ); ?></p></div>
		</section>
		<section class="section-pad"><div class="page-width"><div class="service-grid">
			<?php
			$index = 0;
			while ( $query->have_posts() ) :
				$query->the_post();
				$index++;
				$post_id  = get_the_ID();
				$image    = maa_baula_tt5_service_image( $post_id );
				?>
				<a class="service-card" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><div class="service-image"><?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>" loading="lazy"><?php endif; ?><span class="service-number"><?php echo esc_html( sprintf( '%02d', $index ) ); ?></span></div><div class="service-card-copy"><h2><?php echo esc_html( get_the_title( $post_id ) ); ?></h2><p><?php echo esc_html( maa_baula_tt5_service_excerpt( $post_id ) ); ?></p><span class="service-more"><?php esc_html_e( 'Explore service', 'maa-baula-twentytwentyfive' ); ?></span></div></a>
			<?php endwhile; ?>
		</div><?php wp_reset_postdata(); ?><p class="service-note"><?php esc_html_e( 'Specialised and regulated services are subject to applicable licences, registrations and statutory approvals.', 'maa-baula-twentytwentyfive' ); ?></p></div></section>
	</main>
	<?php
	$output = ob_get_clean();
	return preg_replace( '/>\s+</', '><', $output );
}
add_shortcode( 'maa_baula_services_archive', 'maa_baula_tt5_service_archive_shortcode' );

function maa_baula_tt5_single_service_shortcode() {
	$post_id = get_queried_object_id();
	if ( ! $post_id ) {
		return '';
	}
	$image = maa_baula_tt5_service_image( $post_id );
	ob_start();
	?>
	<main>
		<section class="service-detail-hero"><?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" alt=""><?php endif; ?><div class="page-width service-detail-content"><a href="<?php echo esc_url( maa_baula_tt5_section_page_url( 'services' ) ); ?>" class="service-back"><?php esc_html_e( '← All services', 'maa-baula-twentytwentyfive' ); ?></a><span class="eyebrow"><?php esc_html_e( 'Maa Baula service portfolio', 'maa-baula-twentytwentyfive' ); ?></span><h1><?php echo esc_html( get_the_title( $post_id ) ); ?></h1><p><?php echo esc_html( maa_baula_tt5_service_excerpt( $post_id ) ); ?></p><a class="button button-gold" href="<?php echo esc_url( maa_baula_tt5_section_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Discuss this requirement', 'maa-baula-twentytwentyfive' ); ?></a></div></section>
		<section class="section-pad"><div class="page-width service-detail-body"><div><span class="eyebrow"><?php esc_html_e( 'Scope of support', 'maa-baula-twentytwentyfive' ); ?></span><h2 class="section-title"><?php esc_html_e( 'Support aligned to your requirements.', 'maa-baula-twentytwentyfive' ); ?></h2></div><div class="service-content"><?php echo apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) ); ?><a href="<?php echo esc_url( maa_baula_tt5_section_page_url( 'contact' ) ); ?>" class="button button-navy"><?php esc_html_e( 'Make a business enquiry', 'maa-baula-twentytwentyfive' ); ?></a></div></div></section>
	</main>
	<?php
	return ob_get_clean();
}
add_shortcode( 'maa_baula_service_detail', 'maa_baula_tt5_single_service_shortcode' );
