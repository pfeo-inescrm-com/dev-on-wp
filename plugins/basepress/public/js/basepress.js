jQuery( document ).ready( function($){

	var basepressForms = [];

	function basepressSearch( element ){
		this.mainElement           = element;
		this.form                  = element.find( '.bpress-search-form' );
		this.searchField           = element.find( '.bpress-search-field' );
		this.suggestions           = element.find( '.bpress-search-suggest' );
		this.product               = this.searchField.data( 'product' );
		this.deviceHeight          = screen.height;
		this.minDeviceHeight       = this.suggestions.data( 'minscreen' );
		this.isTouchDevice         = (('ontouchstart' in window) || (navigator.MaxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));
		this.suggestEnabled        = this.suggestions.length;
		this.skipSearchSuggestions = this.isTouchDevice && (this.deviceHeight < this.minDeviceHeight);
		this.oldSearchInputValue   = '';
		this.timer;
		this.selection             = -1;
		this.searchTerm            = '';
		this.language              = this.form.find( 'input[name="lang"]' ).val();
		this.foundPosts           = 0;


		/*
		 * If there is a search suggest element declare all functionalities
		 */
		if( this.suggestEnabled ){

			this.delay = function( callback, ms ){
				clearTimeout( this.timer );
				this.timer = setTimeout( callback, ms );
			};

			this.searchField.on( 'keyup', this, function( e ){
				//Prevent search suggestions on touch devices
				if( this.skipSearchSuggestions || ! e.keyCode ) return;

				e.preventDefault();

				switch( e.keyCode ){
					case 13: //Enter
					case 38: //Up
					case 40: //Down
					case 91: //Left window key
						return;
					case 27: //Esc
						e.data.suggestions.hide();
						e.data.selection = -1;
						e.data.updateSelection();
						break;
					default:
						e.data.searchTerm = $( this ).val();
						if( e.data.searchTerm == e.data.oldSearchInputValue ) return;
						e.data.oldSearchInputValue = e.data.searchTerm;

						if( e.data.searchTerm && e.data.searchTerm.length > 2 ){
							e.data.basepressGetSuggestions( e.data.searchTerm, e.data.product );
						}else{
							e.data.suggestions.html( '' ).hide();
						}

				}
			} );


			/*
			 * Hide search results if clicked outside
			 */
			$( document ).on( 'mouseup', this, function( e ){
				//Prevent search suggestions on touch devices
				if( e.data.skipSearchSuggestions ) return;

				// if the target of the click isn't the container nor a descendant of the container
				if( ! e.data.suggestions.is( e.target ) && e.data.suggestions.has( e.target ).length === 0 ){
					e.data.selection = -1;
					e.data.updateSelection();
					e.data.suggestions.hide();
				}
			} );

			/*
			 * Reopen search suggestions on click.
			 */
			this.searchField.on( 'click', this, function( e ){
				//Prevent search suggestions on touch devices
				if( e.data.skipSearchSuggestions ) return;

				e.data.searchTerm = $( this ).val();
				if( e.data.searchTerm && e.data.searchTerm.length > 2 && e.data.suggestions.children().length ){
					e.data.suggestions.show();
					return;
				}
				else if( e.data.searchTerm && e.data.searchTerm.length > 2 && 0 == e.data.suggestions.children().length ){
					e.data.basepressGetSuggestions( e.data.searchTerm, e.data.product );
					return;
				}
				$( this ).keyup();
			} );


			/*
			 * Handle key interaction with search results
			 */
			this.mainElement.on( 'keydown', this, function( e ){
				//Prevent search suggestions on touch devices
				if( e.data.skipSearchSuggestions ) return;

				if( e.which != 38 && e.which != 40 && e.which != 13 ){
					return;
				}
				e.preventDefault();

				e.data.lastItem = e.data.suggestions.find( 'li' ).length - 1;
				switch( e.which ){
					case 38: //Up
						e.data.selection = (e.data.selection - 1) < -1 ? -1 : e.data.selection -= 1;
						e.data.updateSelection();
						break;

					case 40: //Down
						e.data.selection = (e.data.selection + 1) > e.data.lastItem ? e.data.lastItem : e.data.selection += 1;
						e.data.updateSelection();
						break;

					case 13: //Enter
						if( e.data.selection != -1 ){
							e.data.link = e.data.suggestions.find( 'li' ).eq( e.data.selection ).find( 'a' );
							e.data.link[ 0 ].click();
							break;
						}
						e.data.form.submit();
						break;
				}

			} );

			/*
			 *	Submit search form if suggest more is clicked
			 */
			this.suggestions.on( 'click', '.bpress-search-suggest-more span', this, function( e ){
				e.data.form.submit();
			} );
		}//End if

		/*
		 * Update selection on search suggestion
		 */
		this.updateSelection = function(){
			var els = this.suggestions.find( 'li' );
			els.removeClass( 'selected' );
			if( this.selection != -1 ){
				var currentSelection = els.eq( this.selection );
				currentSelection.addClass( 'selected' );
				let listContainer = this.suggestions.find('ul');
				listContainer.scrollTop( currentSelection[0].offsetTop - ( listContainer.height() / 2 ) );
			}
		}


		/*
		 * Get suggestions via Ajax
		 */
		this.basepressGetSuggestions = function( searchTerm, product ){

			this.form.addClass( 'searching' );
			that = this;

			$.ajax( {
				type: 'GET',
				url: basepress_vars.ajax_url,
				data: {
					action: 'basepress_smart_search',
					terms: searchTerm,
					product: product,
					lang: that.language
				},
				success: function( response ){
					if( response.html ){
						that.suggestions.html( response.html ).show();
						that.foundPosts = Number( response.foundPosts );
					}else{
						that.suggestions.html( '' ).hide();
						that.foundPosts = 0;
					}
				},
				complete: function(){
					that.form.removeClass( 'searching' );
					
/* Premium Code Stripped by Freemius */

				}
			} );
		}

		
/* Premium Code Stripped by Freemius */

	}

	$('.bpress-search').each( function(i){
		basepressForms[i] = new basepressSearch( $(this) );
	});

	//Count post views
	if( basepress_vars.postID ){
		$.ajax( {
			type: 'POST',
			url: basepress_vars.ajax_url,
			data: {
				action: 'basepress_update_views',
				postID: basepress_vars.postID,
				productID: basepress_vars.productID,
			}
		});
	}

	
/* Premium Code Stripped by Freemius */

});// End jQuery