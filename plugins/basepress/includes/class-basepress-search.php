<?php
/**
 * This is the class that creates and handles the search bar
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Basepress_Search' ) ) {

	class Basepress_Search {

		private $product = null;

		private $word_boundary = null;

		/**
		 * basepress_search constructor.
		 *
		 * @since 1.0.0
		 *
		 * @updated 1.4.0
		 */
		public function __construct() {

			//Define the ajax call for front end
			add_action( 'wp_ajax_nopriv_basepress_smart_search', array( $this, 'basepress_smart_search' ) );
			add_action( 'wp_ajax_basepress_smart_search', array( $this, 'basepress_smart_search' ) );

			//Redirect page in case the request is coming from the search form
			add_action( 'get_header' , array( $this, 'headers' ) );

			//Modifies WordPress query for search pages in front end
			if ( ! is_admin() ) {
				add_filter( 'posts_clauses', array( $this, 'search_page_query' ), 20, 2 );
			}

			// register basepress-searchbar shortcode
			add_shortcode( 'basepress-search', array( $this, 'do_shortcode' ) );

			//Enqueue scripts if shortcode is present
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_shortcode_scripts' ), 90 );

			//Adds meta tags to prevent browsers caching search results pages
			add_action( 'wp_head', array( $this, 'prevent_browser_caching' ) );
		}




		/**
		 * Redirects the page in case the request is coming from the search form
		 * Creating the correct permalink
		 *
		 * @since 1.0.0
		 */
		public function headers() {
			global $wp, $wp_rewrite, $basepress_utils;
			$options = $basepress_utils->get_options();

			if( ! isset( $_GET['s'] ) || ( isset( $options['search_use_url_parameters'] ) && $options['search_use_url_parameters'] ) ){
				return;
			}

			$rewrite_rules = get_option( 'rewrite_rules' );

			//If plain permalink are used return
			if ( empty( $rewrite_rules ) ) {
				return;
			}

			if ( $basepress_utils->is_knowledgebase	) {
				$redirect = untrailingslashit( home_url() ) . '/'. $wp->request . '/' . $wp_rewrite->search_base . '/' . urlencode( $_GET['s'] );
				/**
				 * Filters the redirect url
				 */
				$redirect = apply_filters( 'basepress_search_redirect', $redirect );

				wp_redirect( $redirect );
				exit;
			}
		}




		/**
		 * Renders the search bar
		 *
		 * @since 1.0.0
		 *
		 * @updated 1.4.0
		 */
		public function render_searchbar( $product = '' ) {
			global $basepress_utils;

			if ( ! $product ) {
				$product = $basepress_utils->get_product();
			}
			$options = $basepress_utils->get_options();

			$placeholder = isset( $options['search_field_placeholder'] ) ? $options['search_field_placeholder'] : '';
			$search_terms = stripslashes( get_search_query() );
			$kb_slug = $basepress_utils->get_kb_slug();

			/**
			 * Filter the kb_slug before generating the action link
			 * @since 1.5.0
			 */
			$kb_slug = apply_filters( 'basepress_kb_slug', $kb_slug, $options['entry_page'] );

			$permalink_opt = get_option( 'permalink_structure' );
			//Search action if ugly permalinks are used
			if ( '' == $permalink_opt ) {
				$action = '';
			} else {
				//Search actions for all pretty permalinks
				$action = esc_url( home_url( '/' ) );
				//Find URL parameters
				preg_match( '/.*(\?.*)/', $action, $action_args );

				if( empty( $action_args ) ){
					$action_args = array( 1 => '' );
				}
				else{
					//Remove URL parameters from URL. They will be added afterwords
					$action = str_replace( $action_args[1], '', $action );
				}
				if ( isset( $options['single_product_mode'] ) ) {
					$action = trailingslashit( $action . $kb_slug ) . $action_args[1];
				} else {
					$action = trailingslashit( $action . $kb_slug . '/' . $product->slug ) . $action_args[1];
				}
			}

			//Creates extra classes for the search input field. At the moment we have only the class stating the presence of the submit button.
			$input_classes = isset( $options['show_search_submit'] ) ? ' show-submit' : '';
			?>
			<div class="bpress-search">
				<form class="bpress-search-form<?php echo $input_classes; ?>" method="get" action="<?php echo esc_url( $action ); ?>">
					<input type="text" class="bpress-search-field<?php echo $input_classes; ?>" placeholder="<?php echo $placeholder; ?>" autocomplete="off" value="<?php echo $search_terms; ?>" name="s" data-product="<?php echo $product->slug; ?>">
					<?php if ( '' == $permalink_opt ) { ?>
						<?php if( $product->slug && ! isset( $options['single_product_mode'] ) ){ ?>
							<input type="hidden" name="knowledgebase_cat" value="<?php echo $product->slug; ?>">
						<?php } else{ ?>
							<input type="hidden" name="post_type" value="knowledgebase">
						<?php }
					}


					/**
					 * This action can be used to add fields to the search form
					 */
					do_action( 'basepress_search_fields', $product );
					?>
					
					<?php
					if ( isset( $options['show_search_submit'] ) ) {
						$submit_txt = isset( $options['search_submit_text'] ) ? $options['search_submit_text'] : '';
					?>
					
					<span class="bpress-search-submit">
						<input type="submit" value="<?php echo $submit_txt; ?>">
					</span>
					
				<?php } ?>
				
				</form>
				<?php
				if ( isset( $options['show_search_suggest'] ) ) :
					$min_screen = isset( $options['min_search_suggest_screen'] ) ? $options['min_search_suggest_screen'] : '';
				?>

				<div class="bpress-search-suggest" data-minscreen="<?php echo $min_screen; ?>"></div>
				<?php endif; ?>
			</div>
			
			<?php
		}


		/**
		 * Retrieve search term if any
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_search_term() {
			$terms = stripslashes( get_search_query( false ) );
			return $terms;
		}




		/**
		 * Generates the results for the smart search
		 * Used by Ajax calls and by render_searchbar()
		 *
		 * @since 1.0.0
		 * @updated 1.7.5
		 *
		 * @param string $product
		 * @param string $terms
		 */
		public function basepress_smart_search( $product = '', $terms = '' ){
			global $wpdb, $basepress_utils;

			$found_posts = 0;

			//Get suggestion's limit from options
			$options = $basepress_utils->get_options();
			$limit = isset( $options['search_suggest_count'] ) && '' != $options['search_suggest_count'] ? $options['search_suggest_count'] : PHP_INT_MAX;

			//Get the product and terms
			$get_product = isset( $_GET['product'] ) ? $_GET['product'] : '';
			$product_slug = $product ? $product : $get_product;
			$terms = $terms ? trim( $terms ) : trim( $_GET['terms'] );

			$product = $this->product ? $this->product : get_term_by( 'slug', $product_slug, 'knowledgebase_cat' );

			if( ! apply_filters( 'basepress_custom_smart_search', false ) ){
				$query_pieces = $this->query_pieces( $product, $terms );
				if( $query_pieces ){

					$query_string = "SELECT DISTINCT SQL_CALC_FOUND_ROWS $wpdb->posts.ID, $wpdb->posts.post_title, $wpdb->posts.post_content";
					$query_string .= " FROM $wpdb->posts";
					$query_string .= $query_pieces['join'];
					$query_string .= ' WHERE 1=1' . $query_pieces['where'];
					$query_string .= ' ORDER BY ';
					$query_string .= $query_pieces['orderby'];
					$query_string .= ' LIMIT ' . $limit;

					$posts = $wpdb->get_results( $query_string );
					$wpdb->query( "SELECT FOUND_ROWS() as found_posts" );
					$found_posts = $wpdb->last_result[0]->found_posts;
				}
			}else{
				$custom_results = apply_filters( 'basepress_custom_smart_search_results', array( 'posts' => array(), 'found_posts' => 0 ), $terms, $product, $limit );
				$posts = $custom_results['posts'];
				$found_posts = $custom_results['found_posts'];
			}

			$sql_terms = explode( ' ', $terms );

			//Generates results from the found articles
			$results = array();
			$word_boundary = apply_filters( 'basepress_snippet_word_boundary', '\b' );

			if( ! empty( $posts ) ){
				foreach( $posts as $post ){

					$content = $post->post_content;

					//Strip shortcodes from content
					$content = $this->strip_shortcodes( $content );

					//Strip html tags from content
					$content = strip_tags( $content );

					//Get the snipped for the article
					$snippet = $this->get_snippet( $terms, $content, 200, 30 );

					//Highlight the found terms
					$search_terms = $sql_terms;
					foreach( $search_terms as $term ){
						if( strlen( $term ) > apply_filters( 'basepress_search_min_char_length', 2 ) - 1 ){
							$snippet = preg_replace( '@(' . $word_boundary . preg_quote( stripslashes( $term ), '@' ) . ')@i', "<b>\\1</b>", $snippet );
						}
					}
					$results[] = array(
						'title' => $post->post_title,
						'text'  => $snippet,
						'link'  => get_permalink( $post->ID ),
					);
				}
			}

			ob_start();

			//Echo the results list
			if ( count( $results ) ) {

				echo '<ul>';

				foreach ( $results as $result ) {
					echo '<li>';
					echo '<a href="' . $result['link'] . '"><p>';
					echo '<span class="bpress-search-suggest-title">' . $result['title'] . '</span><br>';
					echo $result['text'];
					echo '</p></a>';
					echo '</li>';
				}

				echo '</ul>';

				//Add the suggest more link
				$suggest_more = isset( $options['search_suggest_more_text'] ) ? $options['search_suggest_more_text'] : '';
				$suggest_more = str_replace( '%number%', $found_posts, $suggest_more );
				$suggest_more_class = '' == $suggest_more ? ' empty' : ' text';

				echo '<div class="bpress-search-suggest-more' . $suggest_more_class . '">';

				echo '<span>' . $suggest_more . '</span>';

				echo '</div>';
			}
			else{
				if( isset( $options['smartsearch_no_results_message'] ) && '' != $options['smartsearch_no_results_message'] ){
					echo '<ul>';
					echo '<li>' . $options['smartsearch_no_results_message'] . '</li>';
					echo '</ul>';
				}
			}
			$response = ob_get_clean();


			//If it was an ajax request die
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				wp_send_json( array( 'html' => $response, 'foundPosts' => $found_posts ) );
			}
			else{
				echo $response;
			}
		}


		/**
		 * Modifies WordPress main query for search pages
		 *
		 * @since 1.2.1
		 * @updated 1.4.0, 1.7.5
		 *
		 * @param $pieces
		 * @return mixed
		 */
		public function search_page_query( $pieces, $wp_query ) {
			global $basepress_utils;

			if( apply_filters( 'basepress_custom_smart_search', false ) ){
				return $pieces;
			}

			$is_main_query = isset( $wp_query ) && $wp_query->is_main_query();

			if( $is_main_query && $basepress_utils->is_search ){

				//Redirect
				$this->headers();

				$product_slug = isset( $wp_query->query['knowledgebase_cat'] ) ? $wp_query->query['knowledgebase_cat'] : '';

				//Get product term and save it for later use like in search bar
				$product = get_term_by( 'slug', $product_slug, 'knowledgebase_cat' );
				$this->product = $product;

				$terms = urldecode( $wp_query->query['s'] );

				$terms = str_replace( '+', ' ', $terms );
				$query_pieces = $this->query_pieces( $product, $terms, $pieces );

				if ( $query_pieces ) {

					$pieces['join'] = $query_pieces['join'];

					$pieces['where'] = $query_pieces['where'];

					$pieces['orderby'] = $query_pieces['orderby'];
					$pieces['groupby'] = '';
				} else {

					$pieces['join'] = '';

					$pieces['where'] = 'AND 1=2';

					$pieces['orderby'] = '';
					$pieces['groupby'] = '';
				}
				$GLOBALS['basepress_search_query'] = true;

				$pieces['distinct'] = "DISTINCT";

				do_action( 'basepress_made_search', $product, $terms );
			}


			return $pieces;
		}

		/**
		 * Generates the query pieces
		 *
		 * @since 1.2.1
		 * @updated 1.4.0, 1.7.5, 1.7.11, 1.8.1, 1.8.6
		 *
		 * @param $product
		 * @param $terms
		 * @return array|null|object
		 */
		private function query_pieces( $product, $terms, $pieces = array() ) {

			global $wpdb;

			$word_boundary = $this->get_regex_word_boundary();
			$word_boundary = apply_filters( 'basepress_word_boundary', $word_boundary );


			//Make all terms lowercase
			if( function_exists( 'mb_strtolower' ) ){
				$terms = mb_strtolower( $terms );
			}
			else{
				$terms = strtolower( $terms );
			}

			//Set exact matches terms before filtering the query
			$exact_match_terms = $terms;

			//Filter out unwanted terms
			$terms = $this->filter_terms( $terms );

			//If we have some terms proceed with the query
			if ( $terms ) {

				//Crates the multi-terms string to use in query

				$sql_terms = explode( ' ', $terms );

				$multi_terms = $word_boundary . str_replace( ' ', '|' . $word_boundary, $terms );

				//Gets the list of all sections for the product
				if( $product ){
					//Get all sections for the current product
					$parent_ids = get_terms(
						array(
							'taxonomy'  => 'knowledgebase_cat',
							'child_of'  => $product->term_id,
							'fields'    => 'ids',
						)
					);
					$parent_ids = implode(',', $parent_ids );

					//Query to get the term_taxonomy_id to use in final query for term_relationship
					$sections_query = "SELECT t.term_taxonomy_id FROM $wpdb->term_taxonomy AS t WHERE t.taxonomy='knowledgebase_cat' AND t.term_id IN ( $parent_ids )";
				}
				else{
					$sections_query = "SELECT t.term_taxonomy_id FROM $wpdb->term_taxonomy AS t WHERE t.taxonomy='knowledgebase_cat' AND t.parent<>0 AND t.count<>0";

					/**
					 * Use this filter to change the query used to collect all sections from all products
					 */
					$sections_query = apply_filters( 'basepress_search_all_sections_query', $sections_query );
				}

				$_sections_ids = $wpdb->get_results( $sections_query, ARRAY_A );

				$sections_ids = array();

				foreach( $_sections_ids as $section_id ){
					$sections_ids[] = $section_id['term_taxonomy_id'];
				}

				$sections_ids = implode( ',', $sections_ids );

				//Prepare matches portion of the query
				$order_index = 1;
				$orderby = '';

				//Exact matches
				$orderby .= " WHEN $wpdb->posts.post_title REGEXP '$word_boundary$exact_match_terms' THEN $order_index";
				$order_index++;
				$orderby .= " WHEN $wpdb->posts.post_content REGEXP '$word_boundary$exact_match_terms' THEN $order_index";
				$order_index++;

				//AND logic
				foreach( array( 'title', 'content' ) as $field ){
					$orderby .= ' WHEN';
					foreach( $sql_terms as $index => $sql_term ){
						$sql_term = $sql_term;
						$orderby .= $index != 0 ? ' AND' : '';
						$orderby .= " $wpdb->posts.post_$field REGEXP '$word_boundary$sql_term'";
					}
					$orderby .= " THEN $order_index";
					$order_index++;
				}

				//OR logic
				foreach( array( 'title', 'content' ) as $field ){
					$orderby .= ' WHEN';
					foreach( $sql_terms as $index => $sql_term ){
						$sql_term = $sql_term;
						$orderby .= $index != 0 ? ' OR' : '';
						$orderby .= " $wpdb->posts.post_$field REGEXP '$word_boundary$sql_term'";
					}
					$orderby .= " THEN $order_index";
					$order_index++;
				}

				//Close ORDER BY clause
				$orderby = '(CASE ' . ltrim( $orderby, ' + ' ) . " ELSE $order_index END)";

				//Join Clause
				$pieces['join'] = " LEFT JOIN $wpdb->term_relationships AS t ON ($wpdb->posts.ID = t.object_id)";

				//Where Clause
				$pieces['where'] = " AND t.term_taxonomy_id IN ($sections_ids)";
				$pieces['where'] .= " AND ( LOWER($wpdb->posts.post_content) REGEXP '$multi_terms' OR LOWER($wpdb->posts.post_title) REGEXP '$multi_terms' )";
				$pieces['where'] .= " AND $wpdb->posts.post_type = 'knowledgebase'";
				$pieces['where'] .= " AND $wpdb->posts.post_status = 'publish'";

				$pieces['orderby'] = ' ' . $orderby;

				/**
				 * Filter to alter the query pieces for the search query
				 */
				$pieces = apply_filters( 'basepress_search_query_pieces', $pieces );

				$pieces['orderby'] .= ", $wpdb->posts.post_date DESC";

				return $pieces;
			}
			return false;
		}


		/**
		 * Get word boundary to use on search queries
		 * Since version 8.0.4 MySQL REGEX has changed so we need to test which implementation is using
		 *
		 * @since 2.3.0
		 *
		 * @return mixed|string|void|null
		 */
		private function get_regex_word_boundary(){
			global $wpdb;

			if( $this->word_boundary ){
				return $this->word_boundary;
			}

			$this->word_boundary = get_option( 'basepress_regex_word_boundary', false );
			if( ! $this->word_boundary ){
				$is_icu_regexp = $wpdb->query("SELECT 1 FROM DUAL WHERE 'icu regex' REGEXP '\\\\bregex';");
				$this->word_boundary = $is_icu_regexp	?	'\\\\b' : '[[:<:]]';
				update_option( 'basepress_regex_word_boundary', $this->word_boundary, true );
			}
			return $this->word_boundary;
		}



		/**
		 * Filters the terms removing non alphanumeric chars and terms shorter then 3 chars
		 *
		 * @since 1.2.1
		 *
		 * @param $terms
		 * @return mixed|string
		 */
		public function filter_terms( $terms ) {
			//Return if there are no terms
			if ( ! isset( $terms ) || ! strlen( $terms ) ) {
				return $terms;
			}

			$terms = sanitize_text_field( $terms );

			//Remove all non alphanumeric character
			//$terms = preg_replace( '/[^[:alnum:][:space:]]/u', ' ', $terms );

			//Split the terms into array of terms
			$single_terms = explode( ' ', $terms );

			//Remove all terms shorter then 3 characters
			foreach ( $single_terms as $key => $single_term ) {
				if ( strlen( $single_term ) < apply_filters( 'basepress_search_min_char_length', 2 ) ) {
					unset( $single_terms[ $key ] );
				}
			}

			return implode( ' ', $single_terms );
		}


		/**
		 * Strip shortcodes from content
		 *
		 * @since 1.8.5
		 *
		 * @param $content
		 * @return null|string|string[]
		 */
		public function strip_shortcodes( $content ){
			if( apply_filters( 'basepress_search_strip_shortcodes', true ) ){
				if( apply_filters( 'basepress_search_force_strip_shortcodes', false ) ){
					$content = preg_replace( '~(?:\[/?)[^/\]]+/?\]~s', '', $content );
				}
				else{
					$content = strip_shortcodes( $content );
				}
			}
			return $content;
		}



		/**
		 * Copyright (c) 2015, Ben Boyter All rights reserved.
		 * Redistribution and use in source and binary forms, with or without modification,
		 * are permitted provided that the following conditions are met:
		 * * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
		 * * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
		 * * Neither the name of Ben Boyter nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
		 *
		 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
		 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
		 * IN NO EVENT SHALL BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
		 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
		 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
		 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
		 */

		// find the locations of each of the words
		// Nothing exciting here. The array_unique is required
		// unless you decide to make the words unique before passing in
		private function get_terms_locations( $terms, $content ) {
			$locations = array();
			$terms = explode( ' ', trim( $terms ) );
			foreach ( $terms as $term ) {
				$term_len = strlen( $term );
				$loc = stripos( $content, ' ' . $term );

				while ( false !== $loc ) {
					$locations[] = $loc;
					$loc = stripos( $content, $term, $loc + $term_len );
				}
			}
			$locations = array_unique( $locations );
			sort( $locations );

			return $locations;
		}

		// Work out which is the most relevant portion to display
		// This is done by looping over each match and finding the smallest distance between two found
		// strings. The idea being that the closer the terms are the better match the snippet would be.
		// When checking for matches we only change the location if there is a better match.
		// The only exception is where we have only two matches in which case we just take the
		// first as will be equally distant.
		private function get_best_match( $locations, $prevcount ) {
			// If we only have 1 match we dont actually do the for loop so set to the first
			$startpos = isset( $locations[0] ) ? $locations[0] : 0;
			$loccount = count( $locations );
			$smallestdiff = PHP_INT_MAX;

			// If we only have 2 skip as its probably equally relevant
			if ( count( $locations ) > 2 ) {
				// skip the first as we check 1 behind
				for ( $i = 1; $i < $loccount; $i++ ) {
					if ( $i == $loccount - 1 ) { // at the end
						$diff = $locations[ $i ] - $locations[ $i - 1 ];
					} else {
						$diff = $locations[ $i + 1 ] - $locations[ $i ];
					}

					if ( $smallestdiff > $diff ) {
						$smallestdiff = $diff;
						$startpos = $locations[ $i ];
					}
				}
			}

			$startpos = $startpos > $prevcount ? $startpos - $prevcount : 0;
			return $startpos;
		}

		// 1/6 ratio on prevcount tends to work pretty well and puts the terms
		// in the middle of the extract
		public function get_snippet( $terms, $content, $rellength = 300, $prevcount = 50, $indicator = '...' ) {

			$textlength = strlen( $content );
			if ( $textlength <= $rellength ) {
				return $content;
			}

			$locations = $this->get_terms_locations( $terms, $content );
			$startpos  = $this->get_best_match( $locations, $prevcount );

			// if we are going to snip too much...
			if ( $textlength - $startpos < $rellength ) {
				$startpos = $startpos - ( $textlength - $startpos ) / 2;
			}

			$reltext = substr( $content, $startpos, $rellength );

			// check to ensure we dont snip the last word if thats the match
			if ( $startpos + $rellength < $textlength ) {
				$reltext = substr( $reltext, 0, strrpos( $reltext, ' ' ) ) . $indicator; // remove last word
			}

			// If we trimmed from the front add ...
			if ( 0 != $startpos ) {
				$reltext = $indicator . substr( $reltext, strpos( $reltext, ' ' ) + 1 ); // remove first word
			}

			return $reltext;
		}


		/**
		 * Renders search bar on shortcode
		 *
		 * @since 1.4.0
		 *
		 * @param $atts
		 * @param $content
		 * @param $tag
		 * @return false|string|void
		 */
		public function do_shortcode( $atts ) {
			$product = false;
			if ( isset( $atts['product'] ) && '' != $atts['product'] ) {
				$product = get_term_by( 'slug', $atts['product'], 'knowledgebase_cat' );
			}
			$width = isset( $atts['width'] ) && $atts['width'] ? ' style="max-width:' . $atts['width'] . '"' : '';

			ob_start();
			echo '<div class="bpress-search-shortcode"' . $width . '>';
			$this->render_searchbar( $product );
			echo '</div>';
			return ob_get_clean();
		}


		/**
		 * Enqueue the css file when search bar is rendered by shortcode
		 *
		 * @since 1.4.0
		 */
		public function enqueue_shortcode_scripts() {
			global $post, $basepress_utils;

			if ( is_singular() && has_shortcode( $post->post_content, 'basepress-search' ) ) {
				$options = $basepress_utils->get_options();
				$enqueue_style = isset( $options['searchbar_style'] );

				/**
				 * This filter can be used to alter the enqueueing of styles
				 */
				$enqueue_style = apply_filters( 'basepress_enqueue_shortcode_style', $enqueue_style );

				if ( $enqueue_style ) {
					wp_enqueue_style( 'basepress-styles' );
				}

				wp_enqueue_script( 'basepress-js' );
				wp_localize_script(
					'basepress-js', 'basepress_vars',
					array(
						'ajax_url'       => admin_url( 'admin-ajax.php' ),
						'basepress_ajax' => plugins_url() . '/basepress/public/smart-search.php',
					)
				);

			}
		}


		/**
		 * Adds meta tags to prevent browsers caching search results pages
		 *
		 * @since 2.0.0
		 */
		public function prevent_browser_caching(){
			global $wp_query;
			$is_search = is_search();
			$is_kb_post_type = isset( $wp_query->query['post_type'] ) && 'knowledgebase' == $wp_query->query['post_type'] ? true : false;
			$is_kb_tax = is_tax( 'knowledgebase_cat' );

			if( $is_search && ( $is_kb_post_type || $is_kb_tax ) ){
				?>
				<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
				<meta http-equiv="Pragma" content="no-cache" />
				<meta http-equiv="Expires" content="0" />
				<?php
			}
		}

	} //Class End


	global $basepress_search;
	$basepress_search = new Basepress_Search;
}
?>