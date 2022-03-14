<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Tanganyika
 * @since 1.0.0
 */

get_header();
?>

	<main id="main" class="site-main">
		
		<?php
		if ( have_posts() ) {
			
			if( is_home() ) :
				echo '<ul class="grid four-col">';
			endif;

			// Load posts loop.
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content/content' );
			}
			
			if( is_home() ) :
				echo '</ul>';
			endif;

			// Previous/next page navigation.
			raven_theme_the_posts_navigation();

		} else {

			// If no content, include the "No posts found" template.
			get_template_part( 'template-parts/content/content', 'none' );

		}
		?>

	</main><!-- .site-main -->

<?php
get_footer();
