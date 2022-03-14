<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Tanganyika
 * @since 1.0.0
 */

?>

<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<figure class="entry-thumbnail">
		<?php raven_theme_post_thumbnail(); ?>
	</figure>
	
	<div class="entry-container">
	
		<header class="entry-header">
			<?php
			if ( is_sticky() && is_home() && ! is_paged() ) {
				printf( '<span class="sticky-post">%s</span>', _x( 'Featured', 'post', 'raven_theme' ) );
			}
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				raven_theme_post_categories();
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
			?>
		</header>

		<div class="entry-content">
			<?php
			sprintf( wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'raven_theme' ),
				array( 'span' => array( 'class' => array(), ), ) 
			), get_the_title() );
				
			echo '<p>' . raven_trauncate( get_the_content(), 20 ) . '</p>';

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'raven_theme' ),
					'after'  => '</div>',
				)
			);
			?>
		</div>

		<footer class="entry-footer">
			<?php raven_theme_entry_footer(); ?>
		</footer>
		
	</div>
	
</li>
