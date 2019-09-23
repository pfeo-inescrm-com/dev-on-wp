jQuery( 'document' ).ready( function(){
	
	var form = '';
	var currentProduct = '';
	var currentSection = 0;
	var currentSectionName = '';
	var currentParent = '';
	
	/*
	 *	Load section table when a product is selected
	 */
	jQuery( '#product-select' ).change( function(){
		currentProduct = jQuery( this ).val();
		currentSection = 0;
		
		loadSections( currentProduct, currentSection );
		jQuery( '#section-breadcrumb ul li').not( 'li:first' ).remove();
	});


	/*
	 * If there is a product already selected load the sections
	 * This is true if we are in single product mode
	 */
	if( jQuery( '#product-select' ).find(":selected").val() != 0 ){
		jQuery( '#product-select' ).change();
	}


	/*
	 *	Load Sub Sections on section's name click
	 */
	jQuery( '#sections-table ul' ).on( 'click', '.section-name > div', function(){
		
		var selectedSection = jQuery( this ).parents( 'li' );
		currentSection = selectedSection.data( 'section' );
		currentSectionName = selectedSection.data( 'name' );

		var breadcrumbs = jQuery( '#section-breadcrumb ul li');
		if( breadcrumbs.length > 1 ){
			var sectionName = breadcrumbs.last().data( 'name' );
			breadcrumbs.last().html( '<a href="#">' + sectionName + '</a>' );
		}

		loadSections( currentProduct, currentSection );

		jQuery( '#section-breadcrumb ul' ).append( '<li data-section="' + currentSection + '" data-name="' + currentSectionName + '">' + currentSectionName + '</li>' );
	});
	
	
	/*
	 *	Load Sections on breadcrumb click
	 */
	jQuery( '#section-breadcrumb ul' ).on( 'click', 'li', function( event ){
		event.preventDefault();
		var selectedSection = jQuery( this );
		var selectedSectionId = selectedSection.data( 'section' );

		if( selectedSectionId == currentSection ) return;

		currentSection = selectedSectionId;

		loadSections( currentProduct, currentSection );

		selectedSection.nextAll().remove();

		if( currentSection !== 0 ){
			currentSectionName = selectedSection.data( 'name' );
			selectedSection.html( currentSectionName );
		}
	});
		

	/*
	 * Load subsection for the current section
	 */
	function loadSections( product, section ){
		jQuery( '#ajax-loader' ).show();
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{
				action:	'basepress_get_section_list',
				product: product,
				section: section
			},
			
			success: function( response ){
				if( response.error ){
					console.log( response.data );
				}
				else{
					jQuery( '#product-select' ).parent().removeClass( 'form-invalid' );
					jQuery( '#sections-table ul' ).html( response.data );
					//Refresh sortable items to include the new section
					jQuery('#sections-table ul').sortable( 'refresh' );
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
	
	
	/*
	 *	Add section image
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
			image = frame.state().get( 'selection' ).toJSON();
			var imageWidth = image[0].width;
			var imageHeight = image[0].height;
			var orientation = imageWidth < imageHeight ? 'vertical' : 'horizontal';
			jQuery( '#new-basepress-section img' )
				.attr( 'src', image[0].url )
				.removeClass()
				.addClass( orientation )
				.show();
			
			jQuery( '#section-image-url' ).attr( 'value', image[0].url );
			jQuery( '#section-image-width' ).attr( 'value', image[0].width );
			jQuery( '#section-image-height' ).attr( 'value', image[0].height );
		});
	});
	
	
	/*
	 *	Remove section image
	 */
	jQuery( '#remove-image' ).click( function(){
		jQuery( '#new-basepress-section img' )
			.attr( 'src', '' )
			.removeClass()
			.hide();
		
		jQuery( '#section-image-url' ).attr( 'value', '' );
		jQuery( '#section-image-width' ).attr( 'value', '' );
		jQuery( '#section-image-height' ).attr( 'value', '' );
	});
	
	
	
	/*
	 *	Add section Icon
	 */
	
	//Open Icon Selector Panel
	jQuery( '#select-icon' ).click( function(){
		//Stores witch form has requested an icon
		form = 'add-section';
		
		var selection = jQuery( '#section-icon-class' ).attr( 'value' );
		if( selection ){
			jQuery( '#icon-selector .basepress-icon[data-icon="' + selection +'"]' ).addClass( 'selected' );
		}
		jQuery( '#icon-selector-wrap' ).show();
	});
	
	//Select Icon on Panel
	jQuery( '.basepress-icon' ).click( function(){
		if( form != 'add-section' ) return;
		var iconClass = jQuery( this ).data( 'icon' );
		jQuery( '#icon-selector .basepress-icon.selected' ).removeClass( 'selected' );
		jQuery( '#section-icon-class' ).attr( 'value', iconClass ).change();
		jQuery( '#icon-selector-wrap' ).hide();
		form = '';
	});
	
	//Cancel Icon Selection
	jQuery( '#cancel-icon-select' ).click( function(){
		jQuery( '#icon-selector-wrap' ).hide();
	});
	
	//Update Icon
	jQuery( '#section-icon-class' ).change( function(){
		var icon = jQuery( this ).attr( 'value' );
		jQuery( '#section-icon span' ).removeClass().addClass( icon );
	});
	
	//Remove section Icon
	jQuery( '#remove-icon' ).click( function(){
		jQuery( '#section-icon-class' ).attr( 'value', '' ).change();
	});
	
	
	/*
	 *	Add section
	 */
	jQuery( '#add-section' ).click( function( event ){
		event.preventDefault();
		
		if( isFormValid( 'add' ) ){
			jQuery( '#ajax-loader' ).show();
			
			var form = jQuery( '#new-basepress-section' ).serialize();
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data:{
						action:	'basepress_new_section',
						product: currentProduct,
						section: currentSection,
						form:	form
					},
					
					
					success: function( response ){
						if( response.error ){
							console.log( response.data );
						}
						else{
							jQuery( '#new-basepress-section' ).trigger( 'reset' );
							jQuery( '#remove-icon' ).click();
							jQuery( '#sections-table ul' ).append( response.data );
							//Refresh sortable items to include the new section
							jQuery('#sections-table ul').sortable( 'refresh' );
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
		var product = jQuery( '#product-select' ).val();
		var title = '';
		
		
		if( form == 'add' ){
			if( !product ){
				jQuery( '#product-select' ).parent().addClass( 'form-invalid' );
				return false;
			}
			title = jQuery( '#section-name' );
		}
		else{
			title = jQuery( '#section-name-edit' );
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
	 *	Delete Section
	 */
	jQuery( '#sections-table ul' ).on( 'click', '.section-actions .dashicons-trash', function(){
		var element = jQuery( this );
		var sectionRow = element.parents( '.section-row' );
		var section = sectionRow.data( 'section' );
		var sectionName = sectionRow.data( 'name' );
		
		var confirmed = confirm( 'Are you sure you want to delete this section?\n' + sectionName );
		if( !confirmed ) return;
		
		jQuery( '#ajax-loader' ).show();
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{
				action:	'basepress_delete_section',
				section:	section,	
			},
			success: function( response ){
				if( response === '1' ){
					sectionRow.fadeOut( 'normal', function(){
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
	 *	Edit Section form
	 */
	jQuery( '#sections-table ul' ).on( 'click', '.section-actions .dashicons-edit', function(){
		var element = jQuery( this );
		var sectionRow = element.parents( '.section-row' );
		var section = sectionRow.data( 'section' );
		editSection( section );
	});
	
	function editSection( section ){
		
		jQuery( '#edit-section-wrap' ).show();
		
		jQuery( '#ajax-loader' ).show();
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{
				action:	'basepress_get_section_data',
				section: section,	
			},
			success: function( response ){
				var orientation = response.section.image.image_width < response.section.image.image_height ? 'vertical' : 'horizontal';
				jQuery( '#section-id' ).attr( 'value', response.section.id );
				jQuery( '#section-name-edit' ).val( response.section.name );
				jQuery( '#section-slug-edit' ).val( response.section.slug );
				jQuery( '#section-description-edit' ).val( response.section.description );
				jQuery( '#section-icon-class-edit' ).attr( 'value', response.section.icon ).change();
				jQuery( '#edit-basepress-section img' )
					.attr( 'src', response.section.image.image_url )
					.removeClass()
					.addClass( orientation );
				if( response.section.image.image_url ){
					jQuery( '#edit-basepress-section img' ).show();
				}
				jQuery( '#section-image-url-edit' ).attr( 'value', response.section.image.image_url );
				jQuery( '#section-image-width-edit' ).attr( 'value', response.section.image.image_width );
				jQuery( '#section-image-height-edit' ).attr( 'value', response.section.image.image_height );
				jQuery( '#section-parent-edit' ).html( response.parents );
				jQuery( '#default-category-edit a' ).attr( 'href', response.section.default_edit_link );
				currentParent = jQuery( '#section-parent-edit select' ).val();
			},
			error: function( XMLHttpRequest, textStatus, errorThrown ){
				console.log( errorThrown );
			},
			complete: function(){
				jQuery( '#ajax-loader' ).hide();
			}
		});

	}
	
	/*
	 *	Add section Icon on edit form
	 */
	
	//Open Icon Selector Panel
	jQuery( '#select-icon-edit' ).click( function(){
		//Stores witch form has requested an icon
		form = 'edit-section';
		
		var selection = jQuery( '#section-icon-class-edit' ).attr( 'value' );
		if( selection ){
			jQuery( '#icon-selector .basepress-icon[data-icon="' + selection +'"]' ).addClass( 'selected' );
		}
		jQuery( '#icon-selector-wrap' ).show();
	});
	
	//Select Icon on Panel
	jQuery( '.basepress-icon' ).click( function(){
		if( form != 'edit-section' ) return;
		var iconClass = jQuery( this ).data( 'icon' );
		jQuery( '#icon-selector .basepress-icon.selected' ).removeClass( 'selected' );
		jQuery( '#section-icon-class-edit' ).attr( 'value', iconClass ).change();
		jQuery( '#icon-selector-wrap' ).hide();
		form = '';
	});
	
	//Cancel Icon Selection
	jQuery( '#cancel-icon-select' ).click( function(){
		jQuery( '#icon-selector-wrap' ).hide();
	});
	
	//Update Icon
	jQuery( '#section-icon-class-edit' ).change( function(){
		var icon = jQuery( this ).attr( 'value' );
		jQuery( '#section-icon-edit span' ).removeClass().addClass( icon );
	});
	
	//Remove section Icon
	jQuery( '#remove-icon-edit' ).click( function(){
		jQuery( '#section-icon-class-edit' ).attr( 'value', '' ).change();
	});
	
	
	
	/*
	 *	Add section image on Edit form
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
			image = frame.state().get( 'selection' ).toJSON();
			var imageWidth = image[0].width;
			var imageHeight = image[0].height;
			var orientation = imageWidth < imageHeight ? 'vertical' : 'horizontal';
			jQuery( '#edit-basepress-section img' )
				.attr( 'src', image[0].url )
				.removeClass()
				.addClass( orientation )
				.show();
			
			jQuery( '#section-image-url-edit' ).attr( 'value', image[0].url );
			jQuery( '#section-image-width-edit' ).attr( 'value', image[0].width );
			jQuery( '#section-image-height-edit' ).attr( 'value', image[0].height );
		});
	});
	
	
	/*
	 *	Remove section image on Edit form
	 */
	jQuery( '#remove-image-edit' ).click( function(){
		jQuery( '#edit-basepress-section img' )
			.attr( 'src', '' )
			.removeClass()
			.hide();
		
		jQuery( '#section-image-url-edit' ).attr( 'value', '' );
		jQuery( '#section-image-width-edit' ).attr( 'value', '' );
		jQuery( '#section-image-height-edit' ).attr( 'value', '' );
	});
	
	
	
	/*
	 *	Cancel Section Edit
	 */
	jQuery( '#cancel-change' ).click( function( event ){
		event.preventDefault();
		jQuery( '#edit-basepress-section' ).trigger( 'reset' );
		jQuery( '#remove-icon-edit' ).click();
		jQuery( '#edit-basepress-section .form-invalid' ).removeClass( 'form-invalid' );
		jQuery( '#edit-section-wrap' ).hide();
	});
	
	
	/*
	 *	Update Section Edit form
	 */
	jQuery( '#save-change' ).click( function( event ){
		event.preventDefault();
		
		if( isFormValid( 'edit' ) ){
			jQuery( '#ajax-loader' ).show();
			var form = jQuery( '#edit-basepress-section' ).serialize();
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data:{
					action:	'basepress_update_section',
					form:	form,	
				},
				
				
				success: function( response ){
					if( response.error ){
						console.log( response.data );
					}
					else{
						var newParent = jQuery( '#section-parent-edit select' ).val();
						var sectionRow = jQuery( '#sections-table li[data-section="' + response.id + '"]');
						if( newParent == currentParent ){
							sectionRow.find( '.section-icon > div span' ).removeClass().addClass( response.icon );
							sectionRow.find( '.section-image > div' ).css( 'background-image', 'url(' + response.image.image_url + ')' );
							sectionRow.find( '.section-name div' ).html( response.name);
							sectionRow.find( '.section-description div' ).html( response.description );
							sectionRow.find( '.section-slug div' ).html( response.slug );
						}
						else{
							sectionRow.remove();
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
	 *	Save Section Order
	 */
	jQuery( '#save-section-order' ).click( function( event ){
		event.preventDefault();
		var elements = jQuery( '#sections-table li' );
		if( elements.length === 0 ) return;
		
		var order = [];
		elements.each( function( index, element ){
			order[ index ] = jQuery( element ).data( 'section' );
		});
		
		jQuery( '#ajax-loader' ).show();
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data:{
				action:	'basepress_update_section_order',
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
	jQuery('#sections-table ul').sortable({
		axis: 'y',
		delay: 350,
		helper: function(event, ui){
			var $clone =  jQuery(ui).clone();
			$clone .css('position','absolute');
			return $clone.get(0);
		}
	});
	
	/*
	 * If we have a product id passed in the url we can edit it
	 */
	var editSectionID = jQuery( '#edit-basepress-section #section-id' ).val();
	var editSectionParentID = jQuery( '#edit-basepress-section #parent-id' ).val();
	var editSectionProductID = jQuery( '#edit-basepress-section #product-id' ).val();
	
	if( editSectionID ){
		currentProduct = editSectionProductID;
		currentSection = editSectionParentID;
		loadSections( currentProduct, currentSection );
		jQuery( '#product-select' ).val( currentProduct );
		jQuery( '#section-breadcrumb ul li').not( 'li:first' ).remove();
		if( editSectionParentID != editSectionProductID ){
			currentSectionName = jQuery( '#edit-basepress-section #parent-name' ).val();
			jQuery( '#section-breadcrumb ul').append( '<li data-section="' + currentSection + '">> ' + currentSectionName + '</li>' );
		}
		editSection( editSectionID );
	}
	
}); //jQuery Closure