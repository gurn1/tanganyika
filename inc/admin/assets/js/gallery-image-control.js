/**
 * Part of the Raven Framework
 *
 * Controls upload, edit, & removal of gallery image fields
 * Author: Luke Clifton
 *
 * @since 1.0.0
 */

( function ( $ ) {

	// Set all variables to be used in scope
	var frame = null,
		metaBox = $('.raven-gallery-metabox'),
		addImgLink = metaBox.find('#raven_insert_gallery_button'),
		imgContainer = metaBox.find( '.raven-galleries-list ul'),
		imgIdInput = metaBox.find( '.raven-gallery-image-ids' ),
		overlayButton = $('.raven-overlay-gallery-button');
	
	$( document ).on( 'ready', function () {
	
		media_gallery_sortable( imgContainer, imgIdInput, 'media' );
	
	} );

	// Add Images
	addImgLink.add(overlayButton).on( 'click', function( event ){

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( frame ) {
			if( $('.media-modal').hasClass('raven-edit-modal') ) {
				frame.detach();
				frame.dispose();
				frame = null;
			}else{
				frame.open();
				return;
			}
		}
		
		$( '.media-modal' ).removeClass( 'raven-edit-modal' );

		// Create a new media frame
		frame = wp.media( {
			title: raven_gallery_control_args.textOpenTitle,
			multiple: 'add',
			autoSelect: true,
			library: {
				type: 'image'
			},
			button: {
				text: raven_gallery_control_args.textUseImages
			},
		} );
		
		frame.on( 'open', function() {
			var selection = frame.state().get( 'selection' ),
				attachment_ids = get_current_attachments( imgIdInput );

			// deselect all attachments
			selection.reset();

			$.each( attachment_ids, function () {
				// prepare attachment
				attachment = wp.media.attachment( this );

				// select attachment
				selection.add( attachment ? [ attachment ] : [ ] );
			} );
			
		});

		// When an image is selected in the media frame...
		frame.on( 'select', function() {

			// Get media attachment details from the frame state
			var selection 		= frame.state().get('selection'),
				attachment_ids 	= get_current_attachments( imgIdInput ),
				selected_ids 	= [ ];

			if( selection ) {
				selection.map( function ( attachment ) {
					if ( attachment.id ) {
						// add attachment
						selected_ids.push( attachment.id );

						// is image already in gallery?
						if ( $.inArray( attachment.id, attachment_ids ) !== -1 ) {
							return;
						}

						// add attachment
						attachment_ids.push( attachment.id );
						attachment = attachment.toJSON();

						// is preview size available?
						if ( attachment.sizes && attachment.sizes['thumbnail'] ) {
							attachment.url = attachment.sizes['thumbnail'].url;
						}

						imgContainer.append( raven_gallery_control_args.mediaItemTemplate
							.replace( /__IMAGE_ID__/g, attachment.id )
							.replace( /__IMAGE_CLASS__/g, '' )
							.replace( /__IMAGE__/g, '<img width="150" height="150" src="' + attachment.url + '" class="attachment-thumbnail size-thumbnail" alt="" />' ) );
						
					}
				} );

				// assign copy of attachment ids
				var copy = attachment_ids;

				for ( var i = 0; i < attachment_ids.length; i++ ) {
					// unselected image?
					if ( $.inArray( attachment_ids[i], selected_ids ) === -1 ) {
						imgContainer.find( 'li.raven-gallery-image[data-attachment_id="' + attachment_ids[i] + '"]' ).remove();

						copy = _.without( copy, attachment_ids[i] );
					}
				}

				imgIdInput.val( $.unique( copy ).join( ',' ) );

				overlayButton.hide();
				
			}
			
		});

		// Finally, open the modal on click
		frame.open();
		
	});


	// remove image
	$( document ).on( 'click', '.raven-image-remove', function ( e ) {
		e.preventDefault();

		// prevent featured images being removed
		if ( $( this ).closest( '.raven-gallery-images-featured' ).length === 1 ) {
			return false;
		}

		var li = $( this ).closest( 'li.raven-gallery-image' ),
			attachment_ids = get_current_attachments( imgIdInput ),
			cover_id = $('.raven-gallery-cover-id');
		
		if( li.data( 'attachment_id' ) == cover_id.val() ){
			cover_id.val('');
		}

		// remove id
		attachment_ids = _.without( attachment_ids, parseInt( li.data( 'attachment_id' ) ) );
		
		// remove attachment
		li.remove();

		// update attachment ids
		imgIdInput.val( $.unique( attachment_ids ).join( ',' ) );
		
		if( attachment_ids.length === 0 ){
			overlayButton.css('display', 'flex');
		}
			
		return false;
	} );
	
	
	// edit image
	$( '.raven-galleries-list' ).on( 'click', '.raven-image-edit', function ( e ) {
		e.preventDefault();

		var li = $( this ).closest( 'li.raven-gallery-image' ),
			attachment_id = parseInt( li.data( 'attachment_id' ) ),
			attachment_changed = false;

		// frame already exists?
		if ( frame !== null ) {
			frame.detach();
			frame.dispose();
			frame = null;
		}

		// create new frame
		frame = wp.media( {
			id: 'raven-edit-attachment-modal',
			frame: 'select',
			uploader: false,
			multiple: false,
			title: raven_gallery_control_args.editTitle,
			library: {
				post__in: attachment_id
			},
			button: {
				text: raven_gallery_control_args.buttonEditFile
			}
		} ).on( 'open', function () {
			var attachment = wp.media.attachment( attachment_id ),
				selection = frame.state().get( 'selection' );

			frame.$el.closest( '.media-modal' ).addClass( 'raven-edit-modal' );
			

			// get attachment
			attachment.fetch();

			// reset selection
			//selection.reset();

			// add attachment
			selection.add( attachment );
		
		} );

		frame.open();

		return false;
	} );
	
	
	// edit image options attributes
	$( '.raven-galleries-list' ).on( 'click', '.raven-image-options', function ( e ) {
		e.preventDefault();

		var li = $( this ).closest( 'li.raven-gallery-image' ),
			attachment_id = parseInt( li.data( 'attachment_id' ) );
		
		$('body').append('<div class="media-modal-backdrop"></div>');
	
		if( !$('.raven-gallery-image-options-template').length > 0 ){
			$.ajax({
				type: "POST",
				url: raven_gallery_control_args.ajaxurl,
				data: {
					action: "raven_gallery_image_options_template",
					//security: $("input[name='raven_simplify_nonce']").val(),
					attachment_id: attachment_id,
					post_id: raven_gallery_control_args.post_id
				},
				success: function(template) {

					$('body').append(template);
					
					// Set the colour picker fields
					$('#hero_gallery_text_color').wpColorPicker();
					
					
				},
			});	
			
			$('body').addClass('modal-open');
		}
		
		// Submit hero media panel
		$( document ).on( 'click', '.raven-gallery-image-options-submit', function(e) {
			
			var formdata = $('#raven_gallery_image_options_form').serialize(),
				panel = $('.raven-gallery-image-options-template'),
				backdrop 	= $('.media-modal-backdrop'),
				titleframe = $('.media-frame-title h1'),
				spinner	= titleframe.find('.spinner');
			
			spinner.css( 'visibility', 'visible' );
			
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: {
					action: "raven_gallery_image_options_panel_save_actions",
					security: $("input[name='raven_gallery_image_options_nonce']").val(),
					attachment_id: attachment_id,
					post_id: raven_gallery_control_args.post_id,
					form: formdata
				},
				datatype : 'html',
				success: function(data) {
					
					panel.remove();
					backdrop.remove();

					$('body').removeClass('modal-open');
					
				},
			});	
			
		});
			
		// Close hero media panel
		$( document ).on('click', '.raven-gallery-image-options-close, .media-modal-backdrop', function(e) {
			e.preventDefault();

			var panel 		= $('.raven-gallery-image-options-template'),
				backdrop 	= $('.media-modal-backdrop');

			panel.remove();
			backdrop.remove();

			$('body').removeClass('modal-open');

		});
				
		return false;
	} );
	
	
	// Set cover image
	$( document ).on( 'click', '.raven-gallery-image-cover', function ( e ) {
		e.preventDefault();
		
		var li = $( this ).closest( 'li.raven-gallery-image' ),
			attachment_id = parseInt( li.data( 'attachment_id' ) ),
			cover_id = $('.raven-gallery-cover-id'),
			cover_class = 'raven-gallery-cover';
			
			if( cover_id.val() == attachment_id ) {
				cover_id.val('');
			}else{
				cover_id.val(attachment_id);
			}
		
		
			if( li.hasClass(cover_class) ) {
				
				li.removeClass(cover_class);
			}else{
				
				$('.raven-gallery-image').removeClass(cover_class);
				li.addClass(cover_class);
			}
				
	} );
	
	
	// Display meta options based on gallery type selected
	// May need update for gutenburg
	$( document ).on( 'change', 'input[name=raven_gallery_type]', function() {
		var optionsPanel = '.' + $(this).data('options'),
			featuredimage = $('#postimagediv'),
			herosection = $('#casestudy-hero'),
			content	= $('#postdivrich');
	
		$('#gallery_options').find('.raven-fieldset').slideUp();
		$(optionsPanel).slideDown();
		
		// Hide panels used for stanndard gallery option
		if( optionsPanel != '.raven-gallery-standard-options') {
			// Hide featured image
			featuredimage.hide();
			// Hide hero section
			herosection.hide();
		}else{
			// Show content
			content.show();
			// Show featured image
			featuredimage.show();
			// Show hero section
			herosection.show();
		}
		
		// Hide panels for homepage slider
		$( document ).on( 'change', '#raven_gallery_make_home', function() {
			
			if( $(this).is(':checked') ) {
				// Hide content
				content.hide();
				// Hide featured image
				featuredimage.hide();
				// Hide hero section
				herosection.hide();
			}else{
				// Show content
				content.show();
				// Show featured image
				featuredimage.show();
				// Show hero section
				herosection.show();
			}
			
		});
	
	} );
	

	// get attachment ids
	function get_current_attachments( gallery_ids ) {
		var attachments = gallery_ids.val();

		// return integer image ids or empty array
		return attachments !== '' ? attachments.split( ',' ).map( function ( i ) {
			return parseInt( i )
		} ) : [ ];
	}
	
	
	// 
	function media_gallery_sortable( gallery, ids, type ) {
		if ( type === 'media' ) {
			// images order
			gallery.sortable( {
				items: 'li.raven-gallery-image',
				cursor: 'move',
				scrollSensitivity: 40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'raven-gallery-sortable-placeholder',
				tolerance: 'pointer',
				start: function ( event, ui ) {
					ui.item.css( 'border-color', '#f6f6f6' );
				},
				stop: function ( event, ui ) {
					ui.item.removeAttr( 'style' );
				},
				update: function ( event, ui ) {
					var attachment_ids = [ ];

					gallery.find( 'li.raven-gallery-image' ).each( function () {
						attachment_ids.push( parseInt( $( this ).attr( 'data-attachment_id' ) ) );
					} );

					ids.val( $.unique( attachment_ids ).join( ',' ) );
				}
			} );
		}
	}
	
} )( jQuery );	