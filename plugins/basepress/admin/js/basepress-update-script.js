jQuery( 'window' ).ready( function($){

	function updater(){
		var updateItems = [];
		var status = null;
		var currentItem = null;
		var totalItems = 0;
		var processedItems = 0;
		var progressValue = 0;
		var progressSteps = null;
		var itemsQtyPerStep = 5;
		var transient = 0;
		var errorStrings = '';

		$( '#basepress-update-db' ).click( function( e ){
			e.preventDefault();
			progressValue = 0;
			progressSteps = 0;
			transient = 0;
			updateStatus( 'get_update_objects' );
		});




		function updateProcess(){

			switch( status ){
				case 'get_update_objects':
					$( '#basepress-update-spinner').addClass('spinning');
					$( '#basepress-update-db' ).prop("disabled",true);
					doAjax( 'get_update_objects', '', '', setUpdateItems );
					break;

				case 'process_item':
					processNextItem();
					break;

				case 'process_interrupted':
					$( '#basepress-update-spinner').removeClass('spinning');
					alert( errorStrings );
					break;
				case 'process_finished':
					doAjax( 'process_finished', '', transient, '' );
					$( '#basepress-update-db, #basepress-notice-text' ).remove();
					$( '#basepress-notice-success' ).show();
					$( '#basepress-db-update-notice')
						.removeClass('notice-error')
						.addClass('notice-success');
					log( '\n\n\n' );
					break;

				default:
					return;
			}
			return;
		}




		function doAjax( process, packet, transient, callBack ){

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'basepress_db_posts_update',
					process: process,
					packet: packet,
					transient: transient
				},
				success: function( response ){
					if( 'function' == typeof callBack ){
						callBack( response );
					}
				},
				error: function( jqXHR, textStatus, errorThrown ){
					log( errorThrown );
				},
				complete: function(){

				}
			});
		}


		function updateStatus( newStatus ){

			if( 'item_processed' == newStatus ){
				status = processedItems < totalItems ? 'process_item' : 'process_finished';
			}
			else{
				status = newStatus;
			}

			updateProcess();
		}


		function setUpdateItems( response ){
			if (typeof response === 'string' || response instanceof String){
				errorStrings = response;
				log( response );
				updateStatus( 'process_interrupted' );
				return;
			}

			updateItems = response.items;
			currentItem = 0;
			processedItems = 0;

			totalItems = updateItems.length;
			progressSteps = 100 / totalItems;

			transient = response.transient;

			log( 'Found: ' + updateItems.length );

			updateStatus( 'process_item' );
		}



		function processNextItem(){
			var items = updateItems.slice( currentItem, currentItem + itemsQtyPerStep );
			log( 'Processing: ' + items );
			doAjax( 'update_items', items, transient, itemProcessed );

		}


		function itemProcessed( response ){
			var itemsProcessedNow = response.length;
			currentItem += itemsProcessedNow;
			var progressValue = progressSteps * currentItem;
			var percentage = Math.round(( progressValue * 100) / 100 ) + '%';
			processedItems += itemsProcessedNow;

			log( 'Processed ' + itemsProcessedNow + ' ' + ' (' + response + ')' );
			log( 'Total Processed: ' + processedItems + ' / ' + totalItems + ' - ' + percentage + '\n\n' );

			updateStatus( 'item_processed' );
		}


		function log( logTxt ){
			if( logTxt ){
				console.log( logTxt );
			}
		}
	}

	var updater = new updater;


}); //jQuery Closure