jQuery( 'window' ).ready( function($){

	new function(){
		this.lastSelection = "";
		this.iconsList = $( '#icons-list' );
		this.iconsContext = $( '#icons-context' ).find( ':selected' ).val();
		this.iconsOptions = [];
		this.iconsClasses = [];
		this.iconSetHasDefault = ['sections', 'post'];
		this.iconSetMaxSelection = {'sections': 9999, 'post': 9999, 'breadcrumbs': 1, 'votes': 2, 'pagination': 2, 'postmeta': 9999 };
		this.getSavedIcons = true;
		this.notices = $( '#notices' );

		var self = this;

		/**
		 * Load icons
		 */
		$( '#load-icons' ).click( function(){
			self.notices.html( '' );
			self.loadIcons();
		});

		self.loadIcons = function(){
			$( '#ajax-loader' ).show();

			if( ! $( '#css-uri' ).val() ){
				$( '#icons-extra-classes' ).val( '' );
			}

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data:{
					action: 'basepress_load_icons',
					form: $( '#icons-manager-data' ).serializeArray()
						.reduce(function(a,x){a[x.name] = x.value; return a;}, {}),
					'get-saved-icons': self.getSavedIcons,
				},


				success: function( response ){
					if( response.error ){
						console.log( response.error );
						self.displayNotice( response.error, 'error' );
					}
					else{
						self.iconsList.html( response.icons_html );
						self.loadIconsCss( response.icons_css );
						self.iconsOptions = response.icons_options;
						self.iconsClasses = response.icons_classes;
						$( '#icons-context' ).val( 'post' );
						self.updateIconsView();
						self.sortIcons();
						self.getSavedIcons = false;
						self.displayNotice( response.success, 'success' );
						$( '#css-uri' ).val( response.form.css_uri );
						$( '#icons-extra-classes' ).val( response.form.extra_classes );
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



		/**
		 * Save icons
		 */
		$( '#save-icons' ).click( function(){
			self.notices.html( '' );
			self.saveIcons();
		});

		self.saveIcons = function(){
			$( '#ajax-loader' ).show();

			var form = $( '#icons-manager-data' ).serializeArray()
				.reduce(function(a,x){a[x.name] = x.value; return a;}, {});

			var iconsOptions = self.iconsOptions;

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data:{
					action: 'basepress_save_icons_option',
					form: form,
					iconsOptions: JSON.stringify(iconsOptions),
				},


				success: function( response ){
					if( response.error ){
						console.log( response.error );
						self.displayNotice( response.error, 'error' );
					}
					else{
						console.log( response.success );
						self.displayNotice( response.success, 'success' );
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

		/**
		 * Restor Saved Icons
		 */
		$( '#restore-icons' ).click( function(){
			self.getSavedIcons = true;
			$( '#css-uri' ).val( '' )
			$( '#icons-extra-classes' ).val( '' );
			self.loadIcons();
		});

		/**
		 * Restore Default icons
		 */
		$( '#restore-default-icons').click( function(){
			$( '#ajax-loader' ).show();

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data:{
					action: 'basepress_restore_default_icons',
				},


				success: function( response ){
					if( response.error ){
						console.log( response.error );
						self.displayNotice( response.error, 'error' );
					}
					else{
						console.log( response.success );
						self.displayNotice( response.success, 'success' );
						$( '#restore-icons' ).click();
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



		/**
		 * Toggle icon selection
		 */
		self.iconsList.on( 'click', 'li', function( event ){

			//If shift and alt are pressed together return
			if( event.shiftKey && event.altKey ){
				return;
			}

			var thisIcon = $( this );
			var iconClass = thisIcon.data( 'icon' );
			var isIconDefault = self.iconsOptions[self.iconsContext]['default'] == iconClass;
			var iconIndex = self.iconsOptions[self.iconsContext]['icon'].indexOf( iconClass );
			var isIconSelected = iconIndex !== -1;

			//If shift is pressed select range
			if( event.shiftKey ){
				self.selectRange( thisIcon );
			}

			//If alt is pressed select default
			if( event.altKey ){
				self.selectDefault( thisIcon, iconClass, isIconDefault, iconIndex, isIconSelected );
			}
			//If alt was not pressed select icon
			else{
				self.selectIcon( thisIcon, iconClass, isIconDefault, iconIndex, isIconSelected );
			}

			self.lastSelection = thisIcon;
			self.updateIconsView();
		});


		/**
		 * Toggle selection within an icon range
		 *
		 * @param selection
		 */
		this.selectRange = function( selection ){
			var lastSelectionIndex = self.lastSelection.index();
			var selectionIndex = selection.index();

			if( selectionIndex > lastSelectionIndex ){
				startElement = self.lastSelection;
				stopElement = selection;
			}
			else{
				startElement = selection;
				stopElement = self.lastSelection;
			}
			var range = startElement.nextUntil( stopElement );

			range.each( function( index ){
				var thisIcon = $( this );
				var iconClass = thisIcon.data( 'icon' );
				var isIconDefault = self.iconsOptions[self.iconsContext]['default'] == iconClass;
				var iconIndex = self.iconsOptions[self.iconsContext]['icon'].indexOf( iconClass );
				var isIconSelected = iconIndex !== -1;
				self.selectIcon( thisIcon, iconClass, isIconDefault, iconIndex, isIconSelected );
			});
		}


		/**
		 * Select icon
		 *
		 * @param element
		 * @param iconClass
		 * @param isIconDefault
		 * @param iconIndex
		 * @param isIconSelected
		 */
		this.selectIcon = function( element, iconClass, isIconDefault, iconIndex, isIconSelected ){

			if( isIconSelected ){
				if( ! isIconDefault ){
					self.iconsOptions[ self.iconsContext ][ 'icon' ].splice( iconIndex, 1 );
				}
			}
			else{
				if( self.iconsOptions[self.iconsContext]['icon'].length < self.iconSetMaxSelection[self.iconsContext] ){
					self.iconsOptions[ self.iconsContext ][ 'icon' ].push( iconClass );
				}
			}
		}


		/**
		 * Select default icon
		 *
		 * @param element
		 * @param iconClass
		 * @param isIconDefault
		 * @param iconIndex
		 * @param isIconSelected
		 */
		this.selectDefault = function( element, iconClass, isIconDefault, iconIndex, isIconSelected ){
			var setHasDefault = $.inArray( self.iconsContext, self.iconSetHasDefault ) !== -1;

			//Remove default class
			if( isIconDefault ){
				self.iconsOptions[self.iconsContext]['default'] = '';
			}
			//Or add if possible
			else{
				if(	setHasDefault && self.iconsOptions[self.iconsContext]['default'] == '' ){
					self.iconsOptions[self.iconsContext]['default'] = iconClass;

					if( ! isIconSelected ){
						self.iconsOptions[self.iconsContext]['icon'].push( iconClass );
					}
				}
			}
		}



		/**
		 * Load icons Css file to Html Head
		 *
		 * @param path
		 */
		this.loadIconsCss = function(path) {
			var similar = 0;

			$("link").each(function(e) {
				if ($(this).attr("href") == path) similar++;
			});

			if ( similar == 0 ) {
				$('<link>').attr('rel', 'stylesheet')
					.attr('type', 'text/css')
					.attr('href', path)
					.appendTo('head');
			}
		}


		/**
		 * Updates icons view
		 */
		self.updateIconsView = function(){

			this.iconsContext = $( '#icons-context' ).find( ':selected' ).val();
			var title = $('#icons-context option:selected').text();
			$('#icons-context-title').html( title );

			var iconElements = this.iconsList.find( 'li' );
			var selectedContextIcons = this.iconsOptions[this.iconsContext];

			iconElements.removeClass( 'selected default' );

			if( selectedContextIcons && selectedContextIcons.hasOwnProperty( 'icon') && selectedContextIcons.icon.length ){
				iconElements.each( function(){
					var iconData = $(this).data( 'icon' );
					if( -1 !== $.inArray( iconData, selectedContextIcons.icon ) ){
						$(this).addClass( 'selected' );
					}
					if( iconData == selectedContextIcons.default ){
						$(this).addClass( 'selected default' );
					}
				});
			}
		}


		/**
		 * Restore Icons to original position (as ordered in css file)
		 */
		self.restoreIconsPosition = function(){

			var elements = [];
			var classes = self.iconsClasses;

			var icons = $('#icons-list li');

			$.each( classes, function( i, iconData ){
				elements.push( icons.filter( '[data-icon="' + iconData + '"]' ) );
			} );

			$( '#icons-list' ).prepend( elements );
		}


		/**
		 * Select icons on context change
		 */
		$( '#icons-context' ).change( function(){
			self.updateIconsView();
			self.restoreIconsPosition();
			self.sortIcons();
		});


		/**
		 * Sort icons
		 */
		$( '#sort-icons' ).click( function( event ){
			event.preventDefault();
			self.sortIcons();
		});

		self.sortIcons = function(){
			var selected = $('#icons-list li.selected');

			if( selected.length ){
				var elements = [];
				var order = self.iconsOptions[ self.iconsContext ];
				$.each( order.icon, function( i, iconData ){
					elements.push( selected.filter( '[data-icon="' + iconData + '"]' ) );
				} );

				$( '#icons-list' ).prepend( elements );
			}
		}

		/**
		 * Make icons sortable
		 */
		self.iconsList.sortable({
			delay: 150,
			stop: function( event, ui ){
				self.lastSelection = null;
				var iconClass = ui.item.data('icon');
				var oldPosition = self.iconsOptions[self.iconsContext].icon.indexOf(iconClass);
				var newPosition = ui.item.index();
				var iconSet = self.iconsOptions[self.iconsContext].icon;
				iconSet.splice(newPosition, 0, iconSet.splice( oldPosition, 1 )[0]);
			}
		});


		/**
		 * Display Notices
		 *
		 * @param notice
		 * @param type
		 */
		self.displayNotice = function( notice, type ){
			if( notice ){
				self.notices.show().html( '<div class="notice notice-' + type + '"><p>' + notice + '</p></div>' );
				setTimeout( function(){
					self.notices.hide( 'slow', function(){
						$(this).html('');
					});
				}, 4000 );
			}
		}

		/**
		 * Load icons on startup
		 */
		$( '#load-icons' ).click();
	}
});