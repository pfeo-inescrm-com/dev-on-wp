jQuery(function( $ ){

	var frame;
	$( document ).ready( function(){

		/**
		 * Toggle Settings
		 */
		$( '#enable-setting' ).change( function(){
			$( '.bpmt-card.settings-card' ).slideToggle( 'slow' );
		});

		if( $( '#enable-setting' ).prop( 'checked' )){
			$( '.bpmt-card.settings-card' ).slideToggle( 'slow' );
		}

		/**
		 * Save settings
		 */
		$( '#save-settings' ).click( function( e ){
			e.preventDefault();
			var settings = $( '#bpmt-modern-theme' ).serialize();

			$( this ).addClass( 'saving' ).prop( 'disabled', true );

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data:{
					action:	'basepress_modern_theme_save',
					settings: settings
				},

				success: function( response ){
					if( response.error ){
						console.log( response.data );
					}
					else{

					}
				},

				error: function(  jqXHR, textStatus, errorThrown){
					console.log( errorThrown );
				},

				complete: function(){
					$( '#save-settings' ).removeClass( 'saving' ).prop( 'disabled', false );
				}
			});

		});


		/**
		 * Select Header image
		 */
		$( '#select-header-image').click( function( e ){
			e.preventDefault();

			// If the media frame already exists, reopen it.
			if ( frame ) {
				frame.open();
				return;
			}

			// Create a new media frame
			frame = wp.media({
				title: 'Select Image',
				button: {
					text: 'Select'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});


			// When an image is selected in the media frame...
			frame.on( 'select', function() {

				// Get media attachment details from the frame state
				var attachment = frame.state().get('selection').first().toJSON();

				// Send the attachment URL to our custom image input field.
				$( '#header_image' ).val( attachment.id );
				$( '#header-image-preview' ).css({
					'background-image': 'url(' + attachment.url + ')'
				});
			});

			// Finally, open the modal on click
			frame.open();
		});


		/**
		 * Remove header image
		 */
		$( '#remove-header-image' ).click( function( e ){
			e.preventDefault();
			$( '#header_image' ).val( '' );
			$( '#header-image-preview' ).css({
				'background-image': 'none'
			});
		});

	});//End jQuery
});