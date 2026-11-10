<?php
/**
 * Maa Baula theme bootstrap.
 *
 * @package Maa_Baula_TwentyTwentyFive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MAA_BAULA_TT5_VERSION', '2.7.8' );

require_once get_stylesheet_directory() . '/includes/theme-setup.php';
require_once get_stylesheet_directory() . '/includes/content-types.php';
require_once get_stylesheet_directory() . '/includes/section-shortcodes.php';
require_once get_stylesheet_directory() . '/includes/admin/section-meta-boxes.php';
require_once get_stylesheet_directory() . '/includes/admin/section-meta-save.php';
require_once get_stylesheet_directory() . '/includes/admin/section-meta-assets.php';
require_once get_stylesheet_directory() . '/includes/migrations/section-content.php';
require_once get_stylesheet_directory() . '/includes/services.php';
require_once get_stylesheet_directory() . '/includes/homepage.php';
require_once get_stylesheet_directory() . '/includes/migrations/homepage-sections.php';
require_once get_stylesheet_directory() . '/includes/navigation.php';
require_once get_stylesheet_directory() . '/includes/shortcodes.php';
