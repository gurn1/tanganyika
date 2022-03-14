<?php
/**
 * Tanganyika functions and definitions
 *
 * @subpackage Tanganyika
 * @since 1.0.0
 * @version 1.0.0
 *
 * Author URI: https://lscwebdesign.co.uk/
 * Copyright: 2022 Luke Clifton.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tanganyika only works in WordPress 4.9 or later.
 *
 * @since 1.0.0
 */
if ( version_compare( $GLOBALS['wp_version'], '4.9', '<' ) ) {
	require get_template_directory() . 'inc/backwards-compatibility/back-compat.php';
	return;
}

require_once get_template_directory() . '/inc/class-raven-framework.php';

/**
 *  Returns the main instance
 * 
 * @since 1.0.0
 */
function raven_framework() {
	return Raven_framework::instance();
}
raven_framework();