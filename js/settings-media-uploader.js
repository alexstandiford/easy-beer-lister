  jQuery( document ).ready( function( $ ) {
			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			jQuery('#ebl_default_menu_image').on('click', function( event ){
				event.preventDefault();
				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					file_frame.uploader.uploader.param( 'post_id', 0 );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = 0;
				}
				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});
				// When an image is selected, run a callback. 
				file_frame.on( 'select', function() {
					var selection = file_frame.state().get('selection');
					selection.map( function( attachment ) {		
					attachment = attachment.toJSON();
                console.log(attachment);
					
					// Do something with attachment.id and/or attachment.url here
					jQuery( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#image_attachment_id' ).val(attachment.url);
						jQuery( '.ebl_default_menu_image' ).attr("src",attachment.url);
				});
					attachments = [];
					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});
					// Finally, open the modal
					file_frame.open();
			});
			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});