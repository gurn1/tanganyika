/**
 * Main navigation
 *
 * @since 1.0.0
 */
function raven_main_navigation($) {
	
	var $primaryNavigation 		= $('#site-navigation'),
		$menuToggle				= $('.menu-toggle'),
		$secondaryLayerToggle	= $('.menu-item-toggle'),
		$speed					= '300',
		closeall      			= $primaryNavigation.is(":visible");

	/** Primary Menu */
	$menuToggle.on('click', function(e) {

		var visible 		= $primaryNavigation.is(':visible'),
			$primaryToggle	= $(this).find('i');

		$primaryNavigation.slideUp($speed);
		$primaryToggle.removeClass('fa-times');

		if( ! visible ) {
			$primaryNavigation.slideDown($speed);
			$primaryToggle.addClass('fa-times');
		}

		e.stopPropagation();
	} );
	
	if( ! closeall ) {
		$(document).on('click touchstart', function() {
			$primaryNavigation.slideUp($speed);
			$menuToggle.find('i').removeClass('fa-times');
		});
	}

	$primaryNavigation.on('click touchstart', function(e) {
		e.stopPropagation();
	} );
	
	
	/** Children */
	$secondaryLayerToggle.on( 'click', function() {

		var allSubMenu		= $('.sub-menu'),
			parent 			= $(this).parent(),
			subMenu			= parent.find('.sub-menu'),
			visible			= subMenu.is(':visible'),
			toggleButton	= $(this).find('i'),
			alltoggleButton	= $secondaryLayerToggle.find('i');

		allSubMenu.slideUp($speed);
		alltoggleButton.removeClass('fa-minus-square').addClass('fa-plus-square');

		if( ! visible ) {
			subMenu.slideDown($speed);
			toggleButton.addClass('fa-minus-square').removeClass('fa-plus-square');
		}

	} );
	
}


/**
 * Touch device search bar
 *
 * @since 1.0.0
 */
function raven_search_bar($) {

	var $headerSearch 	= $('.search-container'),
		$searchToggle	= $('.search-toggle'),
		$speed			= '300',
		closeall      	= $headerSearch.is(":visible");

	/** Primary Menu */
	$searchToggle.on('click', function(e) {

		var visible 		= $headerSearch.is(':visible'),
			$searchToggle	= $(this).find('i');

		$headerSearch.slideUp($speed);
		$searchToggle.removeClass('fa-times');

		if( ! visible ) {
			$headerSearch.slideDown($speed);
			$searchToggle.addClass('fa-times');
		}

		e.stopPropagation();
	} );

	$headerSearch.on('click touchstart', function(e) {
		e.stopPropagation();
	} );

	if( ! closeall ) {
		$(document).on('click touchstart', function() {
			$headerSearch.slideUp($speed);
			$('#site-navigation').slideUp($speed);
			$searchToggle.find('i').removeClass('fa-times');
			$('.menu-toggle').find('i').removeClass('fa-times');
		});
	}
	
}


/** 
 * Widget categories 
 *
 * @since 1.0.0
 */
function raven_widget_navigation($) {
	
	var allChildterms		= $('.child-terms'),
		productCategories 	= $('#product_categories_list'),
		categoryToggle 		= $('.category-icon'),
		hasChildren			= $('.has-children'),
		allcategoryToggle	= $('#product_categories_list > li > a .category-icon');

	hasChildren.find(categoryToggle).on('click', function(e) {
		e.preventDefault();

		var dropdown 		= $(this).parent().parent().find('.child-terms'),
			categoryToggle	= $(this).parent().find('> .category-icon > i'),
			visible 		= dropdown.is(':visible');

		allChildterms.slideUp($speed);
		allcategoryToggle.find('i').removeClass('fa-minus-square').addClass('fa-plus-square');

		if( ! visible ) {
			dropdown.slideDown($speed);
			categoryToggle.removeClass('fa-plus-square').addClass('fa-minus-square');
		}

	} );
	
}