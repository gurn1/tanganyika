<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WordPress
 * @subpackage Tanganyika
 * @since 1.0.0
 */

get_header();
?>

	<main id="main" class="site-main">

	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<h1 class="page-title">
				<?php _e( 'Search results for:', 'raven_theme' ); ?>
			</h1>
			<div class="page-description"><?php echo get_search_query(); ?></div>
		</header><!-- .page-header -->

		<?php
		// Start the Loop.
		while ( have_posts() ) :
			the_post();

			/*
			 * Include the Post-Format-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			get_template_part( 'template-parts/content/content', 'excerpt' );

			// End the loop.
		endwhile;

		// Previous/next page navigation.
		raven_theme_the_posts_navigation();

		// If no content, include the "No posts found" template.
	else :
		get_template_part( 'template-parts/content/content', 'none' );

	endif;
	?>
	</main><!-- #main -->

<?php
get_footer();
