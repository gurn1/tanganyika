<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Tanganyika
 * @since 1.0.0
 */

get_header();
?>

	<main id="main" class="site-main">

		<div class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php _e( 'Oops!', RAVEN_DOMAIN ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p>
					<?php _e( 'We can\'t seem to find the page you\'re looking for', RAVEN_DOMAIN ); ?><br>
					<b>Error 404</b>
				</p>
				
				<?php if( class_exists('woocommerce') ) : ?>
					<div class="shop-categories">
						<h3>Shop Categories</h3>
						<?php 
						/**
						 * Get categories
						 * 
						 * @since 1.0.0
						 */
						raven_framework()->taxonomies()->display_product_categories(4); 
						?>
					</div>
				<?php endif; ?>
				
			</div><!-- .page-content -->
		</div><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
