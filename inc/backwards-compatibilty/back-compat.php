<?php
/**
 * Tanganyika back compat functionality
 *
 * Prevents Tanganyika from running on WordPress versions prior to 4.7,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.7.
 *
 * @subpackage Tanganyika
 * @since 1.0.0
 */


/**
 * Prevent switching to Tanganyika on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since 1.0.0
 */
function raven_theme_switch_theme() {
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'raven_theme_upgrade_notice' );
}
add_action( 'after_switch_theme', 'raven_theme_switch_theme' );


/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Tanganyika on WordPress versions prior to 4.7.
 *
 * @since 1.0.0
 *
 * @global string $wp_version WordPress version.
 */
function raven_theme_upgrade_notice() {
	$message = sprintf( __( 'Tanganyika requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'raven_theme' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}


/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 4.7.
 *
 * @since 1.0.0
 */
function raven_theme_customize() {
	wp_die(
		sprintf(
			__( 'Tanganyika requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'raven_theme' ),
			$GLOBALS['wp_version']
		),
		'',
		array(
			'back_link' => true,
		)
	);
}
add_action( 'load-customize.php', 'raven_theme_customize' );


/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.7.
 *
 * @since 1.0.0
 */
function raven_theme_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( __( 'Tanganyika requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'raven_theme' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'raven_theme_preview' );


/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @since 1.0.0
 */
function raven_theme_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'raven_theme_skip_link_focus_fix' );