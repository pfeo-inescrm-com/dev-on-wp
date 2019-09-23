FixedSticky.tests.sticky = false;
jQuery( document ).ready( function(){


	/**
	 * Sticky sidebar
	 */
	var sidebar = jQuery( ".bpress-sidebar" );

	if( sidebar.css( 'position') == 'sticky' ){

		var innerSidebar = jQuery( ".hide-scrollbars" );
		if( !innerSidebar.length ) return;
		var barWidth = getScrollBarWidth();
		var topOffset = parseInt( sidebar.css( 'top' ), 10 ) || 0;

		sidebar.fixedsticky();

		sidebar.on( 'classChanged', function(){
			if( sidebar.hasClass( 'fixedsticky-on' ) ){
				sidebarScrollOn();
			}
			else{
				sidebarScrollOff();
			}
		});

		sidebar.mouseenter( function(){
			if( sidebar.hasClass( 'fixedsticky-on' ) ){
				sidebarScrollOn();
			}
		});

		sidebar.mouseleave( function(){
			sidebarScrollOff();
		});
	}

	function sidebarScrollOn(){
		var height = jQuery( window ).height() - topOffset;
		innerSidebar.css( {
			'max-height': height + 'px',
			'width': 'calc(100% + ' + barWidth + 'px)',
			'overflow-y': 'scroll',
			'padding-bottom': '25px'
		});
	}

	function sidebarScrollOff(){
		innerSidebar.css({
			'max-height': 'initial',
			'width': '100%',
			'overflow-y': 'hidden',
			'padding-bottom': 'initial'
		});
	}

	/**
	 * Triggers event on class change
	 */
	(function( func ) {
		jQuery.fn.addClass = function() {
			func.apply( this, arguments );
			this.trigger('classChanged');
			return this;
		};
	})(jQuery.fn.addClass);

	(function( func ) {
		jQuery.fn.removeClass = function() {
			func.apply( this, arguments );
			this.trigger('classChanged');
			return this;
		};
	})(jQuery.fn.removeClass);


	/**
	 * Determins scrollbar width
	 *
	 * @returns {number}
	 */
	function getScrollBarWidth() {
		var inner = document.createElement('p');
		inner.style.width = "100%";
		inner.style.height = "200px";

		var outer = document.createElement('div');
		outer.style.position = "absolute";
		outer.style.top = "0px";
		outer.style.left = "0px";
		outer.style.visibility = "hidden";
		outer.style.width = "200px";
		outer.style.height = "150px";
		outer.style.overflow = "hidden";
		outer.appendChild(inner);

		document.body.appendChild(outer);
		var w1 = inner.offsetWidth;
		outer.style.overflow = 'scroll';
		var w2 = inner.offsetWidth;

		if (w1 == w2) {
			w2 = outer.clientWidth;
		}

		document.body.removeChild(outer);

		return (w1 - w2);
	}
} );


/**
 * Makes grid elements the same height
 */
var equalheight = function(container){

	var currentTallest = 0,
		currentRowStart = 0,
		rowDivs = [],
		$el,
		topPosition = 0,
		currentDiv;

	jQuery(container).each(function() {

		$el = jQuery(this);
		jQuery($el).height('auto');
		topPosition = $el.position().top;

		if (currentRowStart != topPosition) {
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
			rowDivs.length = 0; // empty the array
			currentRowStart = topPosition;
			currentTallest = $el.height();
			rowDivs.push($el);
		} else {
			rowDivs.push($el);
			currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
		}
		for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			rowDivs[currentDiv].height(currentTallest);
		}
	});
};

jQuery(window).load(function(){
	let cols = jQuery( '.bpress-grid' ).data( 'cols' );
	if( typeof cols == 'undefined' || cols > 1 ){
		equalheight( '.fix-height' );
		jQuery( window ).resize( function(){
			equalheight( '.fix-height' );
		} );
	}
} );
