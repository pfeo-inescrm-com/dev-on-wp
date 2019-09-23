jQuery( 'document' ).ready( function(){
	
	
	/*
	 *	Add product image
	 */
	jQuery( '#select-image' ).click( function(){
		var frame;

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Create a new media frame
		frame = new wp.media({
			multiple: false,
			library: { type: 'image' }
		});

		frame.open();

		frame.on( 'select', function(){
			var image = frame.state().get( 'selection' ).toJSON();
			var imageWidth = image[0].width;
			var imageHeight = image[0].height;
			var orientation = imageWidth < imageHeight ? 'vertical' : 'horizontal';
			jQuery( '#new-basepress-product img' )
				.attr( 'src', image[0].url )
				.removeClass()
				.addClass( orientation )
				.show();
			
			jQuery( '#product-image-url' ).attr( 'value', image[0].url );
			jQuery( '#product-image-width' ).attr( 'value', image[0].width );
			jQuery( '#product-image-height' ).attr( 'value', image[0].height );
		});
	});
	
	
	/*
	 *	Remove product image
	 */
	jQuery( '#remove-image' ).click( function(){
		jQuery( '#new-basepress-product img' )
			.attr( 'src', '' )
			.removeClass()
			.hide();
		
		jQuery( '#product-image-url' ).attr( 'value', '' );
		jQuery( '#product-image-width' ).attr( 'value', '' );
		jQuery( '#product-image-height' ).attr( 'value', '' );
	});
	
	
	/*
	 *	Add product
	 */
	jQuery( '#add-product' ).click( function( event ){
		event.preventDefault();
		
		if( isFormValid( 'add' ) ){
			jQuery( '#ajax-loader' ).show();
			
			var form = jQuery( '#new-basepress-product' ).serialize();
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data:{
					action:	'basepress_new_product',
					form:	form,
				},

				success: function( response ){
					if( response.error ){
						console.log( response.data );
					}
					else{
						jQuery( '#new-basepress-product' ).trigger( 'reset' );
						jQuery( '#remove-image' ).click();
						jQuery( '#products-table ul' ).append( response.data );
						//Refresh sortable items to include the new product
						jQuery('#products-table ul').sortable( 'refresh' );
					}
				},

				error: function(  jqXHR, textStatus, errorThrown){
					console.log( errorThrown );
				},

				complete: function(){
					jQuery( '#ajax-loader' ).hide();
				}
			});
		}
	});
	
	/*
	 *	Form validation function
	 */
	function isFormValid( form ){
		var title;
		if( form == 'add' ){
			title = jQuery( '#product-name' );
		}
		else{
			title = jQuery( '#product-name-edit' );
		}
		
		if( title.val() === '' ){
			title.parents( '.form-field' ).addClass( 'form-invalid' );
			return false;
		}
		else{
			title.parents( '.form-field' ).removeClass( 'form-invalid' );
			return true;
		}
	}
	
	/*
	 *	Delete Product
	 */
	
	jQuery( '#products-table ul' ).on( 'click', '.product-actions .dashicons-trash', function(){
		var element = jQuery( this );
		var productRow = element.parents( '.product-row' );
		var product = productRow.data( 'product' );
		var productName = productRow.data( 'productname' );
		
		var confirmed = confirm( 'Are you sure you want to delete this product?' + productName );
		if( !confirmed ) return;
		
		jQuery( '#ajax-loader' ).show();
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{
				action:	'basepress_delete_product',
				product:	product,	
			},
			success: function( response ){
				if( response === '1' ){
					productRow.fadeOut( 'normal', function(){
						jQuery( this ).remove();
					});
				}
				else{
					
				}
			},
			
			complete: function(){
				jQuery( '#ajax-loader' ).hide();
			}
		});
	});
	
	
	
	
	/*
	 *	Edit Product
	 */
	
	jQuery( '#products-table ul' ).on( 'click', '.product-actions .dashicons-edit', function(){
		var element = jQuery( this );
		var productRow = element.parents( '.product-row' );
		var product = productRow.data( 'product' );
		
		jQuery( '#edit-product-wrap' ).show();
		
		jQuery( '#ajax-loader' ).show();
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{
				action:	'basepress_get_product_data',
				product: product,	
			},
			success: function( response ){
				var orientation = response.image.image_width < response.image.image_height ? 'vertical' : 'horizontal';
				jQuery( '#product-id' ).attr( 'value', response.id );
				jQuery( '#product-name-edit' ).val( response.name );
				jQuery( '#product-slug-edit' ).val( response.slug );
				jQuery( '#product-description-edit' ).val( response.description );
				jQuery( '#edit-basepress-product img' )
					.attr( 'src', response.image.image_url )
					.removeClass()
					.addClass( orientation );
				if( response.image.image_url ){
					jQuery( '#edit-basepress-product img' ).show();
				}
				jQuery( '#product-image-url-edit' ).attr( 'value', response.image.image_url );
				jQuery( '#product-image-width-edit' ).attr( 'value', response.image.image_width );
				jQuery( '#product-image-height-edit' ).attr( 'value', response.image.image_height );
				var visibility = Number( response.visibility ) ? 'checked' : '';
				jQuery( '#product-visibility' ).prop( 'checked', visibility );
				jQuery( '#section-style-edit' ).val( response.sections_style.sections );
				jQuery( '#subsection-style-edit' ).val( response.sections_style.sub_sections );
				jQuery( '#default-category-edit a' ).attr( 'href', response.default_edit_link );
			},
			error: function( XMLHttpRequest, textStatus, errorThrown ){
				console.log( errorThrown );
			},
			complete: function(){
				jQuery( '#ajax-loader' ).hide();
			}
		});
		
	});
	
	
	
	/*
	 *	Add product image on Edit form
	 */
	jQuery( '#select-image-edit' ).click( function(){
		var frame;

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Create a new media frame
		frame = new wp.media({
			multiple: false,
			library: { type: 'image' }
		});

		frame.open();

		frame.on( 'select', function(){
			var image = frame.state().get( 'selection' ).toJSON();
			var imageWidth = image[0].width;
			var imageHeight = image[0].height;
			var orientation = imageWidth < imageHeight ? 'vertical' : 'horizontal';
			jQuery( '#edit-basepress-product img' )
				.attr( 'src', image[0].url )
				.removeClass()
				.addClass( orientation )
				.show();
			
			jQuery( '#product-image-url-edit' ).attr( 'value', image[0].url );
			jQuery( '#product-image-width-edit' ).attr( 'value', image[0].width );
			jQuery( '#product-image-height-edit' ).attr( 'value', image[0].height );
		});
	});
	
	
	/*
	 *	Remove product image on Edit form
	 */
	jQuery( '#remove-image-edit' ).click( function(){
		jQuery( '#edit-basepress-product img' )
			.attr( 'src', '' )
			.removeClass()
			.hide();
		
		jQuery( '#product-image-url-edit' ).attr( 'value', '' );
		jQuery( '#product-image-width-edit' ).attr( 'value', '' );
		jQuery( '#product-image-height-edit' ).attr( 'value', '' );
	});
	
	
	/*
	 *	Cancel Product Edit
	 */
	jQuery( '#cancel-change' ).click( function( event ){
		event.preventDefault();
		jQuery( '#edit-basepress-product' ).trigger( 'reset' );
		jQuery( '#remove-image-edit' ).click();
		jQuery( '#edit-basepress-product .form-invalid' ).removeClass( 'form-invalid' );
		jQuery( '#edit-product-wrap' ).hide();
	});
	
	
	/*
	 *	Update Product Edit form
	 */
	jQuery( '#save-change' ).click( function( event ){
		event.preventDefault();
		
		if( isFormValid( 'edit' ) ){
			jQuery( '#ajax-loader' ).show();
			var form = jQuery( '#edit-basepress-product' ).serialize();
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data:{
					action:	'basepress_update_product',
					form:	form
				},
				
				success: function( response ){
					if( response.error ){
						console.log( response.data );
					}
					else{
						var productRow = jQuery( '#products-table li[data-product="' + response.id + '"]');
						productRow.find( '.product-image' ).css( 'background-image', 'url(' + response.image.image_url + ')' );
						productRow.find( '.product-name div' ).html( response.name);
						productRow.find( '.product-description div' ).html( response.description );
						productRow.find( '.product-slug div' ).html( response.slug );
						if( Number( response.visibility )){
							productRow.removeClass( 'invisible' );
						}
						else{
							productRow.addClass( 'invisible' );
						}
						jQuery( '#cancel-change' ).click();
					}
				},
				
				error: function(  jqXHR, textStatus, errorThrown){
					console.log( errorThrown );
				},
				
				complete: function(){
					jQuery( '#ajax-loader' ).hide();
				}
			});
		}
	});
	
	
	/*
	 *	Save Products Order
	 */
	jQuery( '#save-product-order' ).click( function( event ){
		event.preventDefault();
		var elements = jQuery( '#products-table li' );
		if( elements.length === 0 ) return;
		
		var order = [];
		elements.each( function( index, element ){
			order[ index ] = jQuery( element ).data( 'product' );
		});
		
		jQuery( '#ajax-loader' ).show();
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{
				action:	'basepress_update_product_order',
				order: order,	
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
				jQuery( '#ajax-loader' ).hide();
			}
	});
		
	});
	
	/*
	 *	Make table items sortable
	 */
	jQuery('#products-table ul').sortable({
		axis: 'y',
		delay: 150,
		helper: function(event, ui){
			var $clone =  jQuery(ui).clone();
			$clone .css('position','absolute');
			return $clone.get(0);
		}
	});
	
	
	/*
	 * If we have a product id passed in the url we can edit it
	 */
	var editProduct = jQuery( '#edit-basepress-product #product-id' ).val();
	if( editProduct ){
		var el = jQuery( '#products-table ul li[data-product="' + editProduct + '"] .dashicons-edit' );
		el.click();
	}
}); //jQuery Closure