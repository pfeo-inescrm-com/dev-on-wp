<?php
/**
 * This is the class that handles the build mode
 */

// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Build_Mode' ) ){

	class Basepress_Build_Mode{

		/**
		 * Basepress_Build_Mode constructor.
		 */
		function __construct(){
			//Remove menu item
			add_filter( 'wp_nav_menu_objects', array( $this, 'hide_menu_item' ), 999, 1 );

			//Prevent access and trigger a 404
			add_filter( 'request', array( $this, 'prevent_access' ), 10, 1 );

			add_action( 'admin_bar_menu',   array( $this, 'admin_bar_menu' ), 9999 );

			add_action( 'wp_head', array( $this, 'add_notice_styles' ) );
			add_action( 'admin_head', array( $this, 'add_notice_styles' ) );

		}


		public function admin_bar_menu(){
			global $wp_admin_bar;

			if( ! is_user_logged_in() ){
				return;
			}
			if( ! is_admin_bar_showing() ){
				return;
			}

			if( ! current_user_can('manage_options') ){
				return;
			}

			$wp_admin_bar->add_menu(
				array(
					'id'    => 'basepress-build-mode',
					'title' => __( 'BasePress Build Mode is ON!', 'basepress' )
				)
			);

			$wp_admin_bar->add_node(
				array(
					'id'     => 'basepress-build-mode-notice',
					'title'  => __( 'The knowledge base is visible to Admin users only!', 'basepress' ),
					'parent' => 'basepress-build-mode'
				)
			);
		}

		public function add_notice_styles(){
			?>
			<style>
				#wp-admin-bar-basepress-build-mode{
					background-color:#c11111 !important;
					color:#fff !important;
				}
			</style>
			<?php
		}

		/**
		 * Hide menu Item
		 *
		 * @since 2.1.0
		 * @param $menu_items
		 * @return mixed
		 */
		public function hide_menu_item( $menu_items ){
			global $basepress_utils;
			$options = $basepress_utils->get_options();

			if( isset( $options['build_mode'] ) && isset( $options['entry_page'] ) && ! current_user_can( 'manage_options' ) ){
				$entry_page = (int)$options['entry_page'];

				foreach( $menu_items as $index => $menu_item ){
					if( $entry_page == (int)$menu_item->object_id ){
						unset( $menu_items[$index] );
					}
				}
			}

			return $menu_items;
		}


		/**
		 * Prevent access to KB pages
		 *
		 * @since 2.1.0
		 *
		 * @param $request
		 * @return mixed
		 */
		public function prevent_access( $request ){
			global $basepress_utils;
			$options = $basepress_utils->get_options();

			if( ! is_admin() && ! current_user_can( 'manage_options' ) ){
				if( ( isset( $request['page_id'] ) && $request['page_id'] == $options['entry_page'] )
					|| isset( $request['knowledgebase_cat'] )
					|| ( isset( $request['post_type'] ) && 'knowledgebase' == $request['post_type'] ) ){
					wp_safe_redirect( home_url() );
					exit();
				}
			}
			return $request;
		}
	}

	new Basepress_Build_Mode();
}
