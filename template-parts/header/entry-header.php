<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */
?>

<?php echo the_post_thumbnail(); ?>

<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
<?php raven_theme_post_categories(); ?>

<?php if ( ! is_page() ) : ?>
<div class="entry-meta">
	<?php raven_theme_posted_by(); ?>
	<?php raven_theme_posted_on(); ?>
	<span class="comment-count">
		<?php raven_theme_comment_count(); ?>
	</span>
	<?php
	// Edit post link.
		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers. */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'twentynineteen' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
		);
	?>
</div><!-- .entry-meta -->
<?php endif; ?>
