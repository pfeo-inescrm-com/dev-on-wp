<?php
/**
* This is the class that sets all front end variables
*/

// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
exit;
}

if ( ! class_exists( 'Basepress_Variables' ) ){

	class Basepress_Variables{

		private $is_pretty_permalinks = null;
		private $is_single_product_mode = null;
		private $entry_page = null;
		private $is_front_page = null;
		private $is_products_page = null;
		private $is_knowledgebase = null;
		private $is_product = null;
		private $is_section = null;
		private $is_search = null;
		private $is_global_search = null;
		private $is_article = null;

		/**
		 * Basepress_Variables constructor.
		 *
		 * #since 2.2.0
		 *
		 * @param $query
		 */
		public function __construct( $query ){
			global $wp_rewrite, $basepress_utils;

			$options = $basepress_utils->get_options();

			$this->is_pretty_permalinks =  !empty( $wp_rewrite->permalink_structure );
			$this->is_single_product_mode = isset( $options['single_product_mode'] );
			$this->entry_page = isset( $options['entry_page'] ) ? $options['entry_page'] : '';

			if( $this->is_pretty_permalinks ){
				if( $this->is_single_product_mode ){
					$this->get_variables_pretty_permalink_single( $query );
				}
				else{
					$this->get_variables_pretty_permalink_multi( $query );
				}
			}
			else{
				if( $this->is_single_product_mode ){
					$this->get_variables_plain_permalinks_single( $query );
				}
				else{
					$this->get_variables_plain_permalinks_multi( $query );
				}
			}

			$basepress_utils->is_knowledgebase = $this->is_knowledgebase;
			$basepress_utils->is_products_page = $this->is_products_page;
			$basepress_utils->is_product = $this->is_product;
			$basepress_utils->is_section = $this->is_section;
			$basepress_utils->is_article = $this->is_article;
			$basepress_utils->is_search = $this->is_search;
			$basepress_utils->is_global_search = $this->is_global_search;
		}


		/**
		 * Variables for pretty permalinks single KB
		 *
		 * @since 2.2.0
		 *
		 * @param $query
		 */
		private function get_variables_pretty_permalink_single( $query ){
			global $basepress_utils;

			$is_front_page = $this->is_front_page = $basepress_utils->is_kb_on_front_page() && $this->is_home_page();
			$is_post_type = ( isset( $_GET['post_type'] ) && 'knowledgebase' == $_GET['post_type'] )
				|| ( isset( $query['post_type'] ) && 'knowledgebase' == $query['post_type'] );
			$is_taxonomy_term = isset( $_GET['knowledgebase_cat'] ) || isset( $query['knowledgebase_cat'] );
			$is_search = isset( $_GET['s'] ) || isset( $query['s'] );

			//This must run before $is_knowledgebase is set
			$is_kb_page = $is_post_type	|| $is_taxonomy_term;

			$this->is_products_page = false;

			$this->is_product = isset( $query['is_knowledgebase_product'] ) || $is_front_page;
			$this->is_section = ! $this->is_product && ( isset( $_GET['knowledgebase_cat'] ) || isset( $query['knowledgebase_cat'] ) );
			$this->is_article = isset( $query['knowledgebase'] );

			$this->is_search = $is_kb_page && $is_search;
			$this->is_global_search = false;

			//Must run after $is_entry_page to work
			$this->is_knowledgebase = ( $is_kb_page || $this->is_products_page ) || $is_front_page;
		}


		/**
		 * Variables for pretty permalinks multi KB
		 *
		 * @since 2.2.0
		 *
		 * @param $query
		 */
		private function get_variables_pretty_permalink_multi( $query ){
			global $basepress_utils;

			$is_front_page = $this->is_front_page = $basepress_utils->is_kb_on_front_page() && $this->is_home_page();
			$is_products_page = ( isset( $query['page_id'] ) && $this->entry_page && ! isset( $options['single_product_mode'] ) ) || $is_front_page;
			$is_post_type = ( isset( $_GET['post_type'] ) && 'knowledgebase' == $_GET['post_type'] )
				|| ( isset( $query['post_type'] ) && 'knowledgebase' == $query['post_type'] );
			$is_taxonomy_term = isset( $_GET['knowledgebase_cat'] ) || isset( $query['knowledgebase_cat'] );

			$is_search = isset( $_GET['s'] ) || isset( $query['s'] );

			//This must run before $is_knowledgebase is set
			$is_kb_page = $is_post_type	|| $is_taxonomy_term || ( $is_products_page && $is_search );

			$this->is_products_page = $is_products_page && ! isset( $query['s'] );

			$this->is_product = isset( $query['is_knowledgebase_product'] ) || ( isset( $query['knowledgebase_cat'] ) && isset( $query['s'] ) );
			$this->is_section = ! $this->is_product && ( isset( $_GET['knowledgebase_cat'] ) || isset( $query['knowledgebase_cat'] ) );
			$this->is_article = isset( $query['knowledgebase'] );

			$this->is_search = $is_kb_page && $is_search;
			$this->is_global_search = $this->is_search && ! $this->is_product;

			//Must run after $is_entry_page to work
			$this->is_knowledgebase = ( $is_kb_page || $this->is_products_page ) || $is_front_page;
		}


		/**
		 * Variables for plain permalinks single KB
		 *
		 * @since 2.2.0
		 *
		 * @param $query
		 */
		private function get_variables_plain_permalinks_single( $query ){
			global $basepress_utils;

			$is_front_page = $this->is_front_page = $basepress_utils->is_kb_on_front_page() && ( $this->is_home_page() || ( isset( $query['s'] ) && isset( $query['post_type'] ) && 'knowledgebase' == $query['post_type'] ) );
			$is_products_page = false;
			$is_taxonomy_term = isset( $_GET['knowledgebase_cat'] ) || isset( $query['knowledgebase_cat'] );
			$is_post_type = ( isset( $_GET['post_type'] ) && 'knowledgebase' == $_GET['post_type'] )
				|| ( isset( $query['post_type'] ) && 'knowledgebase' == $query['post_type'] ) || $is_taxonomy_term;
			$is_search = ( isset( $_GET['s'] ) || isset( $query['s'] ) ) && ( $is_post_type || $is_taxonomy_term );


			$is_product = $is_front_page || ( $is_post_type && $is_search ) ? true : false;
			$is_section = $is_taxonomy_term ? true : false;

			if( ( isset( $query['page_id'] ) && ! empty( $this->entry_page ) && $query['page_id'] == $this->entry_page ) ){
				$is_product = true;
			}

			//This must run before $is_knowledgebase is set
			$is_kb_page = $is_post_type	|| $is_taxonomy_term || $is_search || $is_product;

			$this->is_products_page = $is_products_page && ! isset( $query['s'] );
			$this->is_product = $is_product;
			$this->is_section = $is_section;
			$this->is_article = isset( $query['knowledgebase'] );
			$this->is_search = ( $is_kb_page || $is_products_page ) && $is_search;
			$this->is_global_search = ! $this->is_single_product_mode && $this->is_search && ! $this->is_product;

			//Must run after $is_entry_page to work
			$this->is_knowledgebase = ( $is_kb_page || $this->is_products_page ) || $is_front_page;
		}


		/**
		 * Variables for plain permalinks multi KB
		 *
		 * @since 2.2.0
		 *
		 * @param $query
		 */
		private function get_variables_plain_permalinks_multi( $query ){
			global $basepress_utils;

			$is_front_page = $this->is_front_page = $basepress_utils->is_kb_on_front_page() && $this->is_home_page();
			$is_products_page = ( ( isset( $query['page_id'] ) && ! empty( $this->entry_page ) )
				&& $query['page_id'] == $this->entry_page && ! $this->is_single_product_mode )
				|| $is_front_page;

			$is_search = isset( $_GET['s'] ) || isset( $query['s'] );
			$is_taxonomy_term = isset( $_GET['knowledgebase_cat'] ) || isset( $query['knowledgebase_cat'] );

			$is_product = false;
			$is_section = false;
			if( $is_taxonomy_term ){
				$term_slug = isset( $_GET['knowledgebase_cat'] ) ? $_GET['knowledgebase_cat'] : $query['knowledgebase_cat'];
				$term = get_term_by( 'slug', $term_slug, 'knowledgebase_cat' );
				if( 0 == $term->parent ){
					$is_product = true;
				}
				else{
					$is_section = true;
				}
			}

			//This must run before $is_knowledgebase is set
			$is_kb_post_type = ( isset( $_GET['post_type'] ) && 'knowledgebase' == $_GET['post_type'] )
				|| ( isset( $query['post_type'] ) && 'knowledgebase' == $query['post_type'] )
				|| isset( $query['knowledgebase_cat'] )
				|| ( $is_products_page && isset( $query['s'] )
				|| $is_product );

			$this->is_products_page = $is_products_page && ! isset( $query['s'] );
			$this->is_product = $is_product;
			$this->is_section = $is_section;
			$this->is_article = isset( $query['knowledgebase'] );
			$this->is_search = ( $is_kb_post_type || $is_products_page ) && $is_search;
			$this->is_global_search = ! $this->is_single_product_mode && $this->is_search && ! $this->is_product;

			//Must run after $is_entry_page to work
			$this->is_knowledgebase = ( $is_kb_post_type || $this->is_products_page ) || $is_front_page;
		}


		/**
		 * Returns true if the current page is the home page
		 *
		 * @since 2.2.0
		 *
		 * @return bool
		 */
		private function is_home_page(){
			$home_url = trailingslashit( preg_replace("(^https?://)", "", get_home_url() ) );
			$current_url = trailingslashit( $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] );

			return $home_url == $current_url;
		}
	}
}