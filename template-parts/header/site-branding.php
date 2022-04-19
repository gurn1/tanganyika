<?php
/**
 * Displays header site branding
 *
 * @package WordPress
 * @subpackage Tanganyika
 * @since 1.0.0
 */
$description = get_bloginfo( 'description', 'display' );
$blog_info = get_bloginfo( 'name' );

?>

<?php if ( has_custom_logo() ) : ?>

	<div class="site-logo"><?php the_custom_logo(); ?></div>

<?php else : ?>

	<div class="site-branding">
		
		<?php if ( ! empty( $blog_info ) ) : ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php endif; ?>

		<?php if ( $description || is_customize_preview() ) : ?>
				<p class="site-description">
					<?php echo $description; ?>
				</p>
		<?php endif; ?>
	
	</div><!-- .site-branding -->

<?php endif; ?>
		

<?php if ( has_nav_menu( 'primary' ) ) : ?>
	<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'raven_theme' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'menu_class'     => 'main-menu',
				'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'walker'		 => new raven_walker_navigation_controller()
			)
		);
		?>
	</nav><!-- #site-navigation -->
<?php endif; ?>

<div class="header-search-cart">
	<?php if( is_woocommerce_activated() ) : ?>
		<div class="search-container">
			<?php get_product_search_form(); ?>
		</div>
		<span class="search-toggle button"><i class="fas fa-search"></i></span>
	<?php endif; ?>
	
	<?php raven_theme_header_basket(); ?>
	
	<span class="menu-toggle"><i class="fas fa-bars"></i></span>
</div>
