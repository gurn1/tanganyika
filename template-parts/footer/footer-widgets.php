<?php
/**
 * Displays the footer widget area
 *
 * @package WordPress
 * @subpackage Tanganyika
 * @since 1.0.0
 */

if ( is_active_sidebar( 'footer' ) ) : ?>

	<aside class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Footer', 'raven_theme' ); ?>">
		<?php
		if ( is_active_sidebar( 'footer' ) ) {
			?>
					<div class="widget-column footer-widgets">
					<?php dynamic_sidebar( 'footer' ); ?>
					</div>
				<?php
		}
		?>
	</aside><!-- .widget-area -->

<?php endif; ?>
