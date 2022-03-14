/**
 * Forms
 *
 * @since 1.0.0
 */


/**
 * Submit primary contact form
 *
 * @since 1.0.0
 */
 function raven_primary_contact_form($) {
	var button 	= '.button',
		form	= '#raven_primary_contact_form',
		notice	= '.notice-container',
		timer;
	
	$(form).on('click', button, function(e) {
		e.preventDefault();
		
		var nonce			= $(this).closest(form).find('input[name="_wpnonce"]').val(),
			button			= $(this),
			buttonIcon		= $(this).find('.icon-box').html(),
			form_data		= $(this).closest(form).serialize();

		// Validate required fields
		if( raven_validate($, form) == true ) {
			return false;
		}
		
		// Disable form
		raven_submit_animations($, form, 'add', buttonIcon);
		
		$.ajax( {
			type: 'POST',
			url: ravenObject.ajaxurl,
			data: {
				action: 	'submit_primary_form',
				security: 	nonce,
				form_data:	form_data,
			},
			success: function(data) {
				if ( undefined != data && '' != data && 0 != data && data.length > 0 ) {
					var data = $.parseJSON(data);

					// Add notice
					$(notice).append().html(data.html);
					
					// scroll to notice
					$('html, body').animate({
						scrollTop: $(notice).offset().top - 50
					}, 800);

					// Add wait icon to button
					button.find('.icon-box').html('<i class="far fa-hourglass"></i>');

					timer = setTimeout(function () {

						// Restore form
						raven_submit_animations($, form, 'remove', buttonIcon, data.response);

					}, 1800);
					
				}		
			}
		} );
		
	} );
	
}


/**
 * Validate fields
 *
 * @since 1.0.0
 */
function raven_validate($, form) {
	var fields 				= $(form).find('input,textarea,select').filter('[required]:visible'),
		button				= $(form).find(':submit'),
		validationFailed	= false,
		emailValidated		= true,
		timer;
	
	// Remove other error text
	$(form).find('.field > .error').remove();
	
	if( fields ) {
		fields.each( function() {
			if( $(this).attr('type') == 'email' ) {
				emailValidated = raven_validate_email_address($(this).val());
			}
			
			if( $(this).val() == '' || $(this).val() == 'Please Select' || emailValidated == false ) {
				
				$('html, body').stop().animate({
					scrollTop: $(this).offset().top - 80
				}, 800);
				
				$(this).focus();

				// Add error text
				if( emailValidated != false ) {
					$(this).parent().append('<span class="error">This is a required field</span>');
				} else {
					$(this).parent().append('<span class="error">Please enter an email address</span>');
				}
				
				validationFailed = true;
				
				return false;
			}
			
		} );
	}
	
	return validationFailed;
}


/**
 * Conditional Fields
 *
 * @since 1.0.0
 */
function raven_conditional_fields($) {
	
	var container = '.conditional',
		condition = '.condition';
	
	$(container + ' input[type="radio"]').on('change', function() {
		var value 		= $(this).val(),
			theParent 	= $(this).closest(container);
		
		theParent.find(condition).removeClass('visible');
		
		theParent.find('[data-option="'+ value +'"]').addClass('visible');
		
	} );
	
}


/**
 * Submission animations
 *
 * @since 1.0.0
 */
function raven_submit_animations($, form, process, buttonIcon, response) {
	
	var fields 				= $(form).find('input,textarea,select').filter(':visible'),
		button				= $(form).find(':submit');
	
	// Input fields
	if( fields ) {
		fields.each( function() {
			
			if(process == 'add') {
				$(this).attr( 'disabled', true );
			} else if(process == 'remove') {
				$(this).attr( 'disabled', false );
				
				if(response == 'sent' ) {
					$(this).val('');

					if( $(this).is(':radio') || $(this).is(':checkbox') ) {
						$(this).prop('checked', false);
					}
				}
			}
			
		} );
		
	}
	
	// rehide all panels
	if(process == 'remove') {
		$('.condition').removeClass('visible');
	}
	
	// Submit button
	if(process == 'add') {
		// Add loading icon to button
		button.find('.icon-box').html('<i class="fas fa-spinner fa-pulse"></i>');
		button.attr('disabled', true);
	} else if(process == 'remove') {
		// Restore button
		button.find('.icon-box').html(buttonIcon);
		button.attr('disabled', false);
	}
	
}


/**
 * Validate email address
 *
 * @since 1.0.0
 */
function raven_validate_email_address(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
	
    return pattern.test(emailAddress);	
}
