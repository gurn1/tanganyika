/**
 * Part of the Raven Framework
 *
 * Controls upload, edit, & removal of singular image fields
 * Author: Luke Clifton
 *
 * @since 1.0.0
 */

( function ( $ ) {

	// Set all variables to be used in scope
	var frame = null,
		container = $('.raven-metabox-image-wrapper'),
		addImgLink = container.find('.raven-image-button');
	

	// Add Images
	addImgLink.add('.raven-overlay-image-button').on( 'click', function( event ){
		var container 		= $(this).closest( '.raven-metabox-image-wrapper' ),
			imgContainer 	= container.find( '.raven-image-single-thumb' ),
			imgIdInput 		= container.find( '.raven-image-id' ),
			addImgLink 		= container.find( '.raven-image-button' );
		
		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.detach();
			frame.dispose();
			frame = null;
		}
	
		// Create a new media frame
		frame = wp.media( {
			title: 'Add Image',
			library: {
				type: 'image'
			},
			button: {
				text: 'Add Image'
			},
			multiple: false
		} );
		
		frame.on( 'open', function() {
			var selection = frame.state().get( 'selection' ),
				attachment_ids = get_current_attachment( imgIdInput );

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
			var attachment 		= frame.state().get('selection').first().toJSON(),
				attachment_ids 	= get_current_attachment( imgIdInput ),
				selected_ids 	= [ ];


			if ( attachment.id ) {
				
				// is preview size available?
				if ( attachment.sizes && attachment.sizes['medium'] ) {
					
					if( container.hasClass('raven-hero-image-upload') || container.hasClass('raven-image-large-upload') ){
						// display larger imager for hero upload
						attachment.url = attachment.sizes['large'].url;	
					}else if( container.hasClass('raven-image-welcome-upload') ) {
						attachment.url = attachment.sizes['full'].url;	 
					}else{
						attachment.url = attachment.sizes['medium'].url;
					}
					
				}
				
				imgContainer.append( raven_image_control_args.mediaItemTemplate
					.replace( / __IMAGE_ID__/g, attachment.id )
					.replace( /__IMAGE_URL__/g, attachment.url )
					.replace( /__IMAGE_ALT__/g, attachment.alt )
				);

				imgIdInput.val( attachment.id );
				
				addImgLink.addClass('raven-hidden');
				
				$('.raven-overlay-image-button').hide();

			}
			
		});

		// Finally, open the modal on click
		frame.open();
		
	});


	// remove image
	$( document ).on( 'click', '.raven-single-image-remove', function ( e ) {
		e.preventDefault();

		var container 		= $(this).closest( '.raven-metabox-image-wrapper' ),
			imgContainer 	= container.find( '.raven-image-single-thumb' ),
			imgFigure		= imgContainer.find('.raven-image-metabox-preview'),
			imgIdInput 		= container.find( '.raven-image-id' ),
			addImgLink 		= container.find('.raven-image-button');
	
		// remove id
		imgIdInput.val( '' );

		// remove attachment
		imgFigure.remove();
		addImgLink.removeClass('raven-hidden');
		
		$('.raven-overlay-image-button').css('display', 'flex');

		return false;
	} );
	
	
	// edit image
	$( '.raven-image-single-thumb' ).on( 'click', '.raven-image-edit', function ( e ) {
		e.preventDefault();

		var imgContainer = $( this ).closest( '.raven-image-single-thumb > figure' ),
			attachment_id = parseInt( imgContainer.data( 'attachment_id' ) ),
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
			title: 'Edit Image',
			library: {
				post__in: attachment_id
			},
			button: {
				text: 'Update Image'
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
	

	// get attachment ids
	function get_current_attachment( gallery_ids ) {
		var attachments = gallery_ids.val();

		// return integer image ids or empty array
		return attachments !== '' ? attachments.split( ',' ).map( function ( i ) {
			return parseInt( i )
		} ) : [ ];
	}
	
	
} )( jQuery );	