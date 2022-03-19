/**
 * Product list layout (grid/list switcher)
 *
 * @since 1.0.0
 */
 function raven_list_layout($) {

    var COOKIE_NAME 	= 'raven_product_layout_switcher',
		mobileBreak		= 700;
	
	// Toggle sub menu > level 1
	var parent			= '.product-list-content',
		container 	    = '.product-layout',
		button 		    = '.product-selector',
        switcherPanel   = '.products',
		viewportWidth 	= $(window).width();
	
    $(container).on('click', button, function(e) {
        var type        = $(this).data('target'),
            sibling     = $(this).siblings();
            siblingData = sibling.data('target');

        setCookie(COOKIE_NAME, type, 10);
        $(switcherPanel).addClass(type).removeClass(siblingData);
        
        sibling.removeClass('selected');
        $(this).addClass('selected');

    });

	if( viewportWidth <= mobileBreak && $(switcherPanel).hasClass('list') ) {
		$(switcherPanel).addClass('grid').removeClass('list');
	}
	
	$(window).resize(function () {
		if( getCookie(COOKIE_NAME) == 'list' && $(parent)[0] ) {
			viewportWidth = $(window).width();
			if( viewportWidth <= mobileBreak ) {
				$(switcherPanel).addClass('grid').removeClass('list');
			} else {
				$(switcherPanel).addClass('list').removeClass('grid');
			}
		}
	});
}

/**
 * Set a cookie
 * 
 * @since 1.0.0
 */
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    
    let expires = "expires="+ d.toUTCString();
    
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

/**
 * Get a Cookie 
 * 
 * @since 1.0.0
 */
function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    
    for(let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }

    return "";
}