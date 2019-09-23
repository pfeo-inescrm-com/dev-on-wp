<?php
/**
 * This is the class that handles the post views
 */

// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Post_Views' ) ) {

	class Basepress_Post_Views {

		/**
		 * basepress_post_views constructor.
		 *
		 * @since 1.0.0
		 *
		 * @updated 1.7.12
		 */
		public function __construct() {
			add_action( 'wp_ajax_nopriv_basepress_update_views', array( $this, 'basepress_update_views' ));
			add_action( 'wp_ajax_basepress_update_views', array( $this, 'basepress_update_views' ));

			//Add Views column to posts
			add_filter( 'manage_knowledgebase_posts_columns' , array( $this, 'add_custom_columns' ) );
			add_action( 'manage_knowledgebase_posts_custom_column' , array( $this, 'manage_custom_columns' ), 10, 2 );
			add_filter( 'manage_edit-knowledgebase_sortable_columns', array( $this, 'sortable_custom_columns' ) );
			add_action( 'pre_get_posts', array( $this, 'set_sortable_meta_key' ) );

			//Add Bulk Action to reset views
			add_action( 'admin_footer-edit.php', array( $this, 'reset_views_bulk_action_option' ) );
			add_action( 'load-edit.php', array( $this, 'reset_views_bulk_action' ), 99 );
			add_action( 'admin_notices', array( $this, 'reset_views_bulk_action_notices' ) );
		}



		/**
		 * Increments the post counter on post visit
		 * Triggered by ajax on page load
		 *
		 * @since 1.7.12
		 */
		public function basepress_update_views(){

			$referrer = $_SERVER['HTTP_REFERER'];

			//Get the page number from url for pretty permalinks or default ones
			preg_match( '/.+\/([0-9]+)\/?$/', $referrer, $matches );
			if( empty( $matches ) ){
				preg_match( '/page=([0-9]+)/', $referrer, $matches );
			}

			$paged = ! empty( $matches ) ? $matches[1] : 0;

			//If this is not the first page don't count visit
			if( $paged > 1 ){
				return;
			}

			//Don't count views for administrators
			$user = wp_get_current_user();
			if( $user->ID ){
				if( in_array( 'administrator', $user->roles ) ){
					return;
				}
			}

			if( $this->is_ip_excluded() ) return;

			$post_id = $_POST['postID'];
			$product_id = $_POST['productID'];
			if( $post_id ){
				$count = get_post_meta( $post_id, 'basepress_views', true );
				$count += 1;

				update_post_meta( $post_id, 'basepress_views', $count );

				/**
				 * Action to do more processing after updating post views meta
				 */
				do_action( 'basepress_post_view_counter', $post_id, $product_id );
			}
			wp_die();
		}



		/**
		 * Checks if the user IP address is in the excluded list
		 *
		 * @since 1.0.0
		 *
		 * @return bool
		 */
		public function is_ip_excluded() {
			global $basepress_utils;
			$options = $basepress_utils->get_options();

			if ( empty( $options['post_count_ip_exclude'] ) ) {
				return false;
			} else {

				$ips = explode( ' ', $options['post_count_ip_exclude'] );
				$referer_ip = $_SERVER['REMOTE_ADDR'];

				if ( in_array( $referer_ip, $ips ) ) {
					return true;
				}

				return false;
			}
		}




		/**
		 * Adds views and score columns on post edit screen
		 *
		 * @since 1.0.0
		 *
		 * @param $columns
		 * @return array
		 */
		public function add_custom_columns( $columns ) {
			$last_column = array_slice( $columns, count( $columns ) - 1, 1, true );
			array_pop( $columns );
			$columns['basepress-views'] = __( 'Views', 'basepress' );
			$columns = array_merge( $columns, $last_column );
			return $columns;
		}



		/**
		 * Prints the views and score on post edit screen
		 *
		 * @since 1.0.0
		 *
		 * @param $column
		 * @param $post_id
		 */
		public function manage_custom_columns( $column, $post_id ) {
			switch ( $column ) {
				case 'basepress-views':
					$views = get_post_meta( $post_id, 'basepress_views', true );
					$views = $views ? $views : 0;
					echo $views;
					break;
			}
		}




		/**
		 * Makes views column sortable on post edit screen
		 *
		 * @since 1.0.0
		 *
		 * @param $columns
		 * @return mixed
		 */
		public function sortable_custom_columns( $columns ) {
			$columns['basepress-views'] = 'basepress_views';
			return $columns;
		}



		/**
		 * Sorts posts by views on post edit screen
		 *
		 * @since 1.0.0
		 *
		 * @param $query
		 */
		public function set_sortable_meta_key( $query ) {
			if ( is_admin() ) {
				$orderby = $query->get( 'orderby' );

				if ( 'basepress_views' == $orderby ) {
					$query->set( 'meta_key', 'basepress_views' );
					$query->set( 'orderby', 'meta_value_num' );
				}
			}
		}




		/**
		 * Adds Bulk Action to reset post votes on edit screen
		 *
		 * @since 1.0.0
		 */
		public function reset_views_bulk_action_option() {
			global $post_type;

			if ( 'knowledgebase' == $post_type ) {
				if ( ! current_user_can( 'manage_categories' ) ) {
					return;
				}
				?>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery( '<option>' ).val( '' ).text( '─────────').prop( 'disabled', true ).appendTo( "select[name='action']" );
						jQuery( '<option>' ).val( 'reset_views' ).text( '<?php _e( 'Reset Views (Selected)', 'basepress' ); ?>').appendTo( "select[name='action']" );
						jQuery( '<option>' ).val( 'reset_views_all' ).text( '<?php _e( 'Reset Views (All)', 'basepress' ); ?>').appendTo( "select[name='action']" );

						jQuery( '<option>' ).val( '' ).text( '─────────').prop( 'disabled', true ).appendTo( "select[name='action2']" );
						jQuery( '<option>' ).val( 'reset_views' ).text( '<?php _e( 'Reset Views (Selected)', 'basepress' ); ?>').appendTo( "select[name='action2']" );
						jQuery( '<option>' ).val( 'reset_views_all' ).text( '<?php _e( 'Reset Views (All)', 'basepress' ); ?>').appendTo( "select[name='action2']" );
					});
				</script>
				<?php
			}
		}



		/**
		 * Resets the votes according to bulk action
		 *
		 * @since 1.0.0
		 */
		public function reset_views_bulk_action() {
			global $typenow, $wpdb;
			$post_type = $typenow;

			if ( 'knowledgebase' == $post_type ) {

				// get the action
				$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );

				$action = $wp_list_table->current_action();

				//If the called action is not the following return
				$allowed_actions = array( 'reset_views', 'reset_views_all' );
				if ( ! in_array( $action, $allowed_actions ) ) {
					return;
				}

				// security check
				check_admin_referer( 'bulk-posts' );

				//Get post ids
				if ( isset( $_REQUEST['post'] ) ) {
					$post_ids = array_map( 'intval', $_REQUEST['post'] );
				}

				if ( empty( $post_ids ) && 'reset_views_all' == $action ) {
					$post_ids = 'all';
				};

				if ( empty( $post_ids ) ) {
					return;
				}

				// this is based on wp-admin/edit.php
				$sendback = remove_query_arg( array( 'reset_votes', 'reset_views', 'untrashed', 'deleted', 'ids' ), wp_get_referer() );
				if ( ! $sendback ) {
					$sendback = admin_url( "edit.php?post_type=$post_type" );
				}

				$pagenum = $wp_list_table->get_pagenum();
				$sendback = add_query_arg( 'paged', $pagenum, $sendback );

				switch ( $action ) {

					//Reset votes for selected articles
					case 'reset_views':
						$reset = 0;
						foreach ( $post_ids as $post_id ) {

							if ( ! $this->reset_views( $post_id ) ) {
								wp_die( __( 'Error resetting views.', 'basepress' ) );
							}

							$reset++;
						}

						$sendback = add_query_arg(
							array(
								'reset_views' => $reset,
								'ids' => join( ',', $post_ids ),
							),
							$sendback
						);
						break;

					//Reset votes for all articles
					case 'reset_views_all':
						$post_ids = array();

						$query = "
							SELECT ID
							FROM $wpdb->posts
							WHERE post_type = 'knowledgebase'
						";

						/**
						 * Fires before querying the database to get all posts that need to be reset
						 */
						$query = apply_filters( 'basepress_views_reset', $query );

						$all_posts = $wpdb->get_results( $query );

						$reset = 0;

						foreach ( $all_posts as $post ) {
							$post_id = $post->ID;
							$post_ids[] = $post_id;

							if ( ! $this->reset_views( $post_id ) ) {
								wp_die( __( 'Error resetting votes.', 'basepress' ) );
							}

							$reset++;
						}

						$sendback = add_query_arg(
							array(
								'reset_views' => $reset,
								'ids' => join( ',', $post_ids ),
							),
							$sendback
						);
						break;

					default:
						return;
				}

				$sendback = remove_query_arg(
					array(
						'action',
						'action2',
						'tags_input',
						'post_author',
						'comment_status',
						'ping_status',
						'_status',
						'post',
						'bulk_edit',
						'post_view',
					),
					$sendback
				);

				wp_redirect( $sendback );
				exit();
			}
		}




		/**
		 * Generates Response message on edit screen
		 *
		 * @since 1.0.0
		 */
		public function reset_views_bulk_action_notices() {
			global $post_type, $pagenow;

			if ( 'edit.php' == $pagenow
				&& 'knowledgebase' == $post_type
				&& isset( $_REQUEST['reset_views'] )
				&& (int) $_REQUEST['reset_views'] ) {

				// If there are no ids there were no resets skip message
				if ( isset( $_REQUEST['ids'] ) ) {
					$message = sprintf( '%s Post views reset.', number_format_i18n( $_REQUEST['reset_views'] ) );
					echo "<div class='notice notice-success'><p>{$message}</p></div>";
				}
			}
		}




		/**
		 * Resets the views
		 *
		 * @since 1.0.0
		 *
		 * @param $post
		 * @return bool
		 */
		private function reset_views( $post ) {

			$update = update_post_meta( $post, 'basepress_views', 0 );

			return true;
		}
	}// Class End

	new Basepress_Post_Views;
}
