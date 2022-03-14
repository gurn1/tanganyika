<?php
/**
 * Customizer controller
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}


if( ! class_exists('Raven_theme_customizer_controller') ) {
	
	class Raven_customizer_controller {
		
		/**
		 * Construct
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			
			// After theme setup
			add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
			
			// Enqueue supplemental block editor styles.
			add_action( 'enqueue_block_editor_assets', array( $this, 'editor_customizer_styles' ) );
			
			//  Display custom color CSS in customizer and on frontend.
			add_action( 'wp_head', array( $this, 'colors_css_wrap' ) );
			
			/**
			 * Customizer additions.
			 */
			require RAVEN_DIR . '/inc/customizer/customizer.php';
			
		}
		

		/**
		 * After theme setup
		 *
		 * @since 1.0.0
		 */
		public function theme_setup() {

			// Add custom editor font sizes.
			add_theme_support(
				'editor-font-sizes',
				array(
					array(
						'name'      => __( 'Small', 'raven_theme' ),
						'shortName' => __( 'S', 'raven_theme' ),
						'size'      => 19.5,
						'slug'      => 'small',
					),
					array(
						'name'      => __( 'Normal', 'raven_theme' ),
						'shortName' => __( 'M', 'raven_theme' ),
						'size'      => 22,
						'slug'      => 'normal',
					),
					array(
						'name'      => __( 'Large', 'raven_theme' ),
						'shortName' => __( 'L', 'raven_theme' ),
						'size'      => 36.5,
						'slug'      => 'large',
					),
					array(
						'name'      => __( 'Huge', 'raven_theme' ),
						'shortName' => __( 'XL', 'raven_theme' ),
						'size'      => 49.5,
						'slug'      => 'huge',
					),
				)
			);

			// Editor color palette.
			add_theme_support(
				'editor-color-palette',
				array(
					array(
						'name'  => __( 'Primary', 'raven_theme' ),
						'slug'  => 'primary',
						'color' => raven_theme_hsl_hex( 'default' === get_theme_mod( 'primary_color' ) ? 199 : get_theme_mod( 'primary_color_hue', 199 ), 100, 33 ),
					),
					array(
						'name'  => __( 'Secondary', 'raven_theme' ),
						'slug'  => 'secondary',
						'color' => raven_theme_hsl_hex( 'default' === get_theme_mod( 'primary_color' ) ? 199 : get_theme_mod( 'primary_color_hue', 199 ), 100, 23 ),
					),
					array(
						'name'  => __( 'Dark Gray', 'raven_theme' ),
						'slug'  => 'dark-gray',
						'color' => '#111',
					),
					array(
						'name'  => __( 'Light Gray', 'raven_theme' ),
						'slug'  => 'light-gray',
						'color' => '#767676',
					),
					array(
						'name'  => __( 'White', 'raven_theme' ),
						'slug'  => 'white',
						'color' => '#FFF',
					),
				)
			);

			// Add support for responsive embedded content.
			add_theme_support( 'responsive-embeds' );
			
		}
		
		
		/**
		 * Enqueue supplemental block editor styles.
		 *
		 * @since 1.0.0
		 */
		public function editor_customizer_styles() {

			wp_enqueue_style( 'raven_theme-editor-customizer-styles', RAVEN_DIR . 'inc/customizer/css/style-editor-customizer.css', false, '1.1', 'all' );

			if ( 'custom' === get_theme_mod( 'primary_color' ) ) {
				
				// Include color patterns.
				require_once RAVEN_DIR . 'inc/customizer/color-patterns.php';
				
				wp_add_inline_style( 'raven_theme-editor-customizer-styles', raven_theme_custom_colors_css() );
				
			}
			
		}
		
		
		/**
		 * Display custom color CSS in customizer and on frontend.
		 *
		 * @since 1.0.0
		 */
		public function colors_css_wrap() {

			// Only include custom colors in customizer or frontend.
			if ( ( ! is_customize_preview() && 'default' === get_theme_mod( 'primary_color', 'default' ) ) || is_admin() ) {
				return;
			}

			// Include color patterns.
			require_once RAVEN_DIR . '/inc/customizer/color-patterns.php';

			$primary_color = 199;
			if ( 'default' !== get_theme_mod( 'primary_color', 'default' ) ) {
				$primary_color = get_theme_mod( 'primary_color_hue', 199 );
			}
			?>

			<style type="text/css" id="custom-theme-colors" <?php echo is_customize_preview() ? 'data-hue="' . absint( $primary_color ) . '"' : ''; ?>>
				<?php echo raven_theme_custom_colors_css(); ?>
			</style>
			<?php
		}
		
	}	
}		