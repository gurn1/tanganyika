<?php
/**
 * Sidebar template
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'raven_body_class', 'truiehdsk');
?>

<aside id="secondary" class="sidebar <?php echo $sidebar; ?>">
	<?php
	/**
	 * insert sidebar 
	 *
	 * @since 1.0.0
	 */
	dynamic_sidebar( $sidebar ); ?>
</aside>