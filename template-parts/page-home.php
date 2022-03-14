<?php
/**
 * Template name: Home Page
 *
 * @since 1.0.0
 */

get_header(); ?>

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

<section id="hero_home" class="flexslider">
	<?php 
	/**
	 * Image Slider (uses ACF)
	 *
	 * @since 1.0.0
	 */
	raven_theme_hero_slider('hero_slider'); 
	?>
</section>

<section id="home_about">
	<div class="container large">
		<?php
		/**
		 * Get welcome excerpt
		 *
		 * @since 1.0.0
		 */
		echo the_content();
		?>
	</div>
	
</section>

<section id="home_categories">
	<div class="container xlarge centered">
		<?php 
		/**
		 * Get categories
		 * 
		 * @since 1.0.0
		 */
		//raven_framework()->taxonomies()->display_product_categories(4); 
		?>
	</div>
</section>

<section id="latest_items">
	<div class="container xlarge centered entry">
	
		<header class="entry-header block">
			<h2 class="entry-title">Latest Items</h2>
		</header>

		<div>
			<?php
			/**
			 * Home latest products loop
			 *
			 * @since 1.0.0
			 */
			raven_framework()->loops()->home_latest_products();
			?>
		</div>
		
	</div>
	
</section>

<?php endwhile; endif; ?>

<?php
get_footer();