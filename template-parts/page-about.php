<?php
/**
 * Template name: About Page
 *
 * @since 1.0.0
 */

get_header(); ?>

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

	<section id="hero_about">

		<header class="page-header">
			<h1 class="page-title"><?php echo get_the_title(); ?></h1>
		</header>

	</section>

	<section id="about_content">

		<div class="container large">
			<?php echo the_content(); ?>
		</div>

	</section>


<?php endwhile; endif; ?>

<?php
get_footer();