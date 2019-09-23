<?php
/**
 * This is the class that handles the default taxonomy edit screen
 */

// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Default_Terms_Edit_Screen' ) ){

	class Basepress_Default_Terms_Edit_Screen{

		/**
		 * Basepress_Default_Terms_Edit_Screen constructor.
		 *
		 * @since 2.4.0
		 */
		public function __construct(){

			//Redirect on main Taxonomy page
			add_action( 'current_screen', array( $this, 'redirect_taxonomy_page' ) );

			add_action( 'admin_print_styles-edit-tags.php', array( $this, 'edit_screen_styles' ), 100 );

			add_action( 'admin_print_styles-term.php', array( $this, 'edit_screen_styles' ), 100 );
		}



		/**
		 * Redirect the page if default taxonomy edit screen is not enabled
		 *
		 * @since 2.4.0
		 * @param $current_screen
		 */
		public function redirect_taxonomy_page( $current_screen ){
			global $basepress_utils;

			if( 'edit-knowledgebase_cat' == $current_screen->id && ! isset( $_REQUEST['tag_ID'] ) ){
				$options = $basepress_utils->get_options();

				if( ! class_exists( 'Basepress_WPML_Support' )
					|| class_exists( 'Basepress_WPML_Support' ) && ! isset( $options['category_screen'] ) && ! isset( $_REQUEST['tag_ID'] ) ){
					wp_die( __( 'This page is disabled!<br>To edit the Knowledge Base taxonomies use the pages available under the Knowledge Base menu.', 'basepress' ) );
				}
			}

			return;
		}


		/**
		 * Hides unwanted fields in the Taxonomy Term edit screen, to prevent the user from creating new Term from this screen.
		 *
		 * @since 2.4.0
		 */
		public function edit_screen_styles() {
			global $current_screen, $basepress_utils;
			if ( 'edit-knowledgebase_cat' == $current_screen->id ) :
				$options = $basepress_utils->get_options();
				if( ! isset( $_REQUEST['tag_ID'] ) ) :
				?>
				<style type="text/css">
					#col-left,
					.actions.bulkactions,
					.row-actions{
						display: none;
					}
					#col-right{
						width: 100% !important;
					}
				</style>
				<?php endif; ?>
				<?php if( isset( $_REQUEST['tag_ID'] ) && ( ! isset( $options['category_screen'] ) || ! class_exists('Basepress_WPML_Support' ) ) ) : ?>
				<style>
					.term-parent-wrap{
						display: none;
					}
				<?php endif; ?>
				</style>
			<?php
			endif;
		}
	}

	new Basepress_Default_Terms_Edit_Screen;
}