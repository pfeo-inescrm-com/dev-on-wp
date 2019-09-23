jQuery( 'window' ).ready( function( $ ){
	$( '.basepress_product_mb' ).change( function(){
		var product = $( this ).val();

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
					action: 'basepress_get_product_sections',
					product: product
				},
			success: function( response ){
				var sections = $( '.basepress_section_mb' );
				sections.replaceWith( response );
			}
		});
	});
});