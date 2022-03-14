/**
 * Password renewal checker
 * 
 * @since 1.0.0
 */
function raven_password_renewal_checker($) {
	let emailField 	= $('.woocommerce-Input[name="username"]'),
		button		= $('.woocommerce-form-login__submit');	

	emailField.on('focusin', function() {
		let halfHeight = this.offsetHeight / 2;

		button.prop('disabled', true).addClass('disabled');

		if( ! $('.spinner').length ) {
			$(this).closest('.form-row').append(`<span class="spinner" style="top: auto; bottom: ${halfHeight}px"><i class="fas fa-spinner fa-spin"></i></span>`);
		}
	});

	emailField.on('focusout', function() {
		let el				= $(this),
			emailAddress 	= el.val();

		if(emailAddress.length && isEmail(emailAddress)) {
			$.ajax({
				type: "POST",
				url: ravenObject.ajaxurl,
				data: {
					action: "password_renewal_checker",
					emailAddress: emailAddress
				},
				success: function(result) {
					if(result != 1) {

						if( ! $('.password-update-required').length ) {
							$('.woocommerce-form-message').append(
								`<span class="woocommerce-error password-update-required"><span class="error-header">Password Update Required</span>Please follow the link to change your password. <a href="${ravenObject.lostpwordURL}?email=${emailAddress}">Update Password</a></span>`
							);
							console.log(el.closest('.woocommerce-form-message'));
						}
					} else {
						button.prop('disabled', false).removeClass('disabled');
					}
					el.closest('.form-row').find('.spinner').remove();
				},
				error: function(jqXHR, exception) {
					error_report(jqXHR, exception);
				}
			});
		} else {
			button.prop('disabled', false).removeClass('disabled');
			$(this).closest('.form-row').find('.spinner').remove();
		}
	});
}

/**
 * Email checker
 * 
 * @since 1.0.0
 */
function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

/**
 * Error tracking
 * 
 * @since 1.0.0
 */
 function error_report(jqXHR, exception) {
    if (jqXHR.status === 0) {
        console.log('Not connect.\n Verify Network.');
    } else if (jqXHR.status == 404) {
        console.log('Requested page not found. [404]');
    } else if (jqXHR.status == 500) {
        console.log('Internal Server Error [500].');
    } else if (exception === 'parsererror') {
        console.log('Requested JSON parse failed.');
    } else if (exception === 'timeout') {
        console.log('Time out error.');
    } else if (exception === 'abort') {
        console.log('Ajax request aborted.');
    } else {
        console.log('Uncaught Error.\n' + jqXHR.responseText);
    }
}