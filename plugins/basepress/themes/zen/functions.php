<?php
/**
 * Functions to extend the Zen Theme
 */

// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Basepress_Zen_Theme {

	function __construct() {
		//Replace sidebar to iclude the card class
		add_action( 'widgets_init', array( $this, 'theme_widget_areas' ), 100 );

		add_action( 'wp_head', array( $this, 'print_scripts' ) );
	}


	/**
	 * Defines new widget areas for the theme
	 */
	public function theme_widget_areas() {
		unregister_sidebar( 'basepress-sidebar' );

		register_sidebar( array(
			'name'          => __( 'Knowledge Base Sidebar', 'basepress' ),
			'id'            => 'basepress-sidebar',
			'description'   => __( 'Add here the widgets to appear on the knowledge base pages', 'basepress' ),
			'class'         => '',
			'before_widget' => '<section id="%1$s" class="bpress-widget bpress-card %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="bpress-card-header small">',
			'after_title'   => '</h2>',
		));
	}


	public function print_scripts() {
		?>
		<script type="text/javascript">
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
		
		jQuery(window).load(function() {
			equalheight('.fix-height');
		});
		
		jQuery(window).resize(function(){
			equalheight('.fix-height');
		});
		</script>
		<?php

	}

} //End Class

new Basepress_Zen_Theme;
