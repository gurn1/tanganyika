<?php
/**
 * Template name: Sidebar Right
 *
 * @since 1.0.0
 */


get_header();
?>

	<main id="main" class="site-main sidebar-right">

		<?php

		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
	
	<?php raven_get_sidebar('sidebar-product'); ?>

<?php
get_footer();
