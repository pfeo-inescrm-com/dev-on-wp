<?php

/**
 * This is the class that adds and handles BasePress custom post type and taxonomies
 */
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'Basepress_CPT' ) ) {
    class Basepress_CPT
    {
        /**
         * basepress_cpt constructor.
         *
         * @since 1.0.0
         */
        public function __construct()
        {
            global  $basepress_utils ;
            //Add rewrite rules to handle links properly
            add_filter( 'rewrite_rules_array', array( $this, 'rewrite_rules' ) );
            $this->add_rewrite_tags();
            add_filter( 'knowledgebase_cat_rewrite_rules', array( $this, 'clean_rewrite_rules' ) );
            add_filter( 'knowledgebase_rewrite_rules', array( $this, 'clean_rewrite_rules' ) );
            //Add the product and section name on the post permalink
            add_filter(
                'post_type_link',
                array( $this, 'post_permalinks' ),
                1,
                2
            );
            //Add the product name on the archive permalink
            add_filter(
                'term_link',
                array( $this, 'sections_permalink' ),
                99,
                3
            );
            //Add Product filter dropdown on post list table
            add_action( 'restrict_manage_posts', array( $this, 'filter_list_dropdowns' ) );
            //Filter Articles by products and sections
            add_filter( 'parse_query', array( $this, 'filter_list_query' ) );
            //Add and manage Product and Section columns
            add_filter( 'manage_knowledgebase_posts_columns', array( $this, 'add_custom_columns' ) );
            add_action(
                'manage_knowledgebase_posts_custom_column',
                array( $this, 'manage_custom_columns' ),
                10,
                2
            );
            //Add views, votes and score metafields default values on post save
            add_action(
                'wp_insert_post',
                array( $this, 'save_basepress_metas' ),
                10,
                3
            );
            //Add admin notice for articles with missing data
            add_action( 'admin_notices', array( $this, 'missing_data_notice' ) );
            //Remove Feeds and oEmbed links in site header
            add_action( 'template_redirect', array( $this, 'remove_cpt_feeds' ) );
            //Add Knowledge Base state to entry page in page list screen
            add_filter(
                'display_post_states',
                array( $this, 'set_display_post_states' ),
                10,
                2
            );
            add_action( 'wp_ajax_basepress_get_sections_filter', array( $this, 'basepress_get_sections_filter' ) );
            $this->options = get_option( 'basepress_settings' );
            $this->kb_slug = $basepress_utils->get_kb_slug();
            $this->register_taxonomy();
            $this->register_post_type();
        }
        
        public function add_rewrite_tags()
        {
            add_rewrite_tag( '%is_knowledgebase_product%', '([^/]+)' );
        }
        
        /**
         * Removes default rewrite rules for our CPT and Taxonomy
         *
         * @since 1.9.0
         *
         * @param $rules
         * @return array
         */
        public function clean_rewrite_rules( $rules )
        {
            return array();
        }
        
        /**
         * Adds rewrite rules for Basepress post type
         * Called by flush_rewrite rules
         *
         * @since 1.0.0
         * @updated 1.4.0
         *
         * @param $rules
         * @return array
         */
        public function rewrite_rules( $rules )
        {
            global  $wp_rewrite, $basepress_utils ;
            $options = get_option( 'basepress_settings' );
            //If the entry page has not been set skip the rewrite rules
            if ( !isset( $options['entry_page'] ) ) {
                return $rules;
            }
            //Force Utils to refresh the options to make sure we have updated ones when creating our rewrite rules
            //Essential for the Wizard
            $basepress_utils->load_options();
            $kb_slug = $basepress_utils->get_kb_slug( true );
            /**
             * Filter the kb_slug before generating the rewrite rules
             * @since 1.5.0
             */
            $kb_slug = apply_filters( 'basepress_rewrite_rules_kb_slug', $kb_slug, $this->options['entry_page'] );
            $search_base = $wp_rewrite->search_base;
            $page_base = $wp_rewrite->pagination_base;
            $comment_page_base = $wp_rewrite->comments_pagination_base;
            $new_rules = array();
            $product = get_terms( array(
                'taxonomy'   => 'knowledgebase_cat',
                'parent'     => 0,
                'hide_empty' => false,
                'meta_key'   => 'basepress_position',
                'orderby'    => 'meta_value_num',
                'order'      => 'ASC',
                'number'     => 1,
            ) );
            $product_slug = '';
            if ( !empty($product) && !is_a( $product, 'WP_Error' ) ) {
                $product_slug = $product[0]->slug;
            }
            
            if ( isset( $options['single_product_mode'] ) ) {
                //entry page
                $new_rules[$kb_slug . '/?$'] = 'index.php?is_knowledgebase_product=true&knowledgebase_cat=' . $product_slug;
                //Search
                $new_rules[$kb_slug . '/' . $search_base . '/(.+)/page/?([0-9]{1,})/?$'] = 'index.php?s=$matches[1]&post_type=knowledgebase&knowledgebase_cat=' . $product_slug . '&paged=$matches[2]';
                $new_rules[$kb_slug . '/' . $search_base . '/(.+)/?$'] = 'index.php?s=$matches[1]&post_type=knowledgebase&knowledgebase_cat=' . $product_slug;
                //Search with parameters
                $new_rules[$kb_slug . '/' . $page_base . '/(.+)/?$'] = 'index.php?post_type=knowledgebase&knowledgebase_cat=' . $product_slug . '&paged=$matches[1]';
                //Paged archives
                $new_rules[$kb_slug . '/(.+)/' . $page_base . '/(.+)/?$'] = 'index.php?post_type=knowledgebase&knowledgebase_cat=$matches[1]&paged=$matches[2]';
                //Paged posts
                $new_rules[$kb_slug . '/(.+)/(.+)/([0-9]+)?/?$'] = 'index.php?post_type=knowledgebase&knowledgebase=$matches[2]&page=$matches[3]';
                //Paged Comments
                $new_rules[$kb_slug . '/(.+)/(.+)/' . $comment_page_base . '-([0-9]{1,})/?$'] = 'index.php?post_type=knowledgebase&knowledgebase=$matches[2]&cpage=$matches[3]';
                //Article
                $new_rules[$kb_slug . '/(.+)/(.+)/?$'] = 'index.php?post_type=knowledgebase&knowledgebase=$matches[2]';
                //Category
                $new_rules[$kb_slug . '/(.+)/?$'] = 'index.php?post_type=knowledgebase&knowledgebase_cat=$matches[1]';
            } else {
                //entry page
                $new_rules[$kb_slug . '/?$'] = 'index.php?page_id=' . $options['entry_page'];
                //Search
                $new_rules[$kb_slug . '/' . $search_base . '/(.+)/page/?([0-9]{1,})/?$'] = 'index.php?s=$matches[1]&post_type=knowledgebase&paged=$matches[2]';
                $new_rules[$kb_slug . '/' . $search_base . '/(.+)/?$'] = 'index.php?s=$matches[1]&post_type=knowledgebase';
                $new_rules[$kb_slug . '/(.+)/' . $search_base . '/(.+)/page/?([0-9]{1,})/?$'] = 'index.php?s=$matches[2]&post_type=knowledgebase&knowledgebase_cat=$matches[1]&paged=$matches[3]';
                $new_rules[$kb_slug . '/(.+)/' . $search_base . '/(.+)/?$'] = 'index.php?s=$matches[2]&post_type=knowledgebase&knowledgebase_cat=$matches[1]';
                //Paged archives
                $new_rules[$kb_slug . '/(.+)/(.+)/' . $page_base . '/(.+)/?$'] = 'index.php?post_type=knowledgebase&knowledgebase_cat=$matches[2]&paged=$matches[3]';
                //Paged posts
                $new_rules[$kb_slug . '/(.+)/(.+)/(.+)/([0-9]+)?/?$'] = 'index.php?post_type=knowledgebase&knowledgebase=$matches[3]&page=$matches[4]';
                //Paged Comments
                $new_rules[$kb_slug . '/(.+)/(.+)/(.+)/' . $comment_page_base . '-([0-9]{1,})/?$'] = 'index.php?post_type=knowledgebase&knowledgebase=$matches[3]&cpage=$matches[4]';
                //Article
                $new_rules[$kb_slug . '/(.+)/(.+)/(.+)/?$'] = 'index.php?post_type=knowledgebase&knowledgebase=$matches[3]';
                //Category
                $new_rules[$kb_slug . '/(.+)/(.+)/?$'] = 'index.php?post_type=knowledgebase&knowledgebase_cat=$matches[2]';
                //Product
                $new_rules[$kb_slug . '/(.+)/?$'] = 'index.php?post_type=knowledgebase&is_knowledgebase_product=true&knowledgebase_cat=$matches[1]';
            }
            
            //IMPORTANT: the new rules must be added at the top of the array to have higher priority
            return array_merge( $new_rules, $rules );
        }
        
        /**
         * Registers the taxonomy 'knowledgebase_cat'
         *
         * @since version 1.0.0
         */
        public function register_taxonomy()
        {
            register_taxonomy( 'knowledgebase_cat', 'knowledgebase', array(
                'labels'            => array(
                'name'          => __( 'Knowledge Base categories', 'basepress' ),
                'singular_name' => __( 'Knowledge Base category', 'basepress' ),
                'menu_name'     => __( 'Knowledge Base categories', 'basepress' ),
            ),
                'hierarchical'      => true,
                'query_var'         => true,
                'public'            => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud'     => false,
                'meta_box_cb'       => false,
                'rewrite'           => array(
                'slug'       => $this->kb_slug . '/%kb_product%',
                'with_front' => false,
                'feeds'      => false,
            ),
            ) );
        }
        
        /**
         * Registers Basepress post type
         *
         * @since version 1.0.0
         */
        public function register_post_type()
        {
            global  $basepress_utils ;
            $options = $basepress_utils->get_options();
            $exclude_from_search = ( isset( $options['exclude_from_wp_search'] ) ? true : false );
            register_post_type( 'knowledgebase', array(
                'label'               => __( 'Knowledge Base', 'basepress' ),
                'labels'              => array(
                'name'          => __( 'Knowledge Base', 'basepress' ),
                'singular_name' => __( 'Knowledge Base Article', 'basepress' ),
                'all_items'     => __( 'All Articles', 'basepress' ),
                'edit_item'     => __( 'Edit Article', 'basepress' ),
                'view_item'     => __( 'View Article', 'basepress' ),
                'search_items'  => __( 'Search Articles', 'basepress' ),
            ),
                'description'         => __( 'These are the Knowledge base articles from BasePress.', 'basepress' ),
                'supports'            => array(
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'trackbacks',
                'revisions',
                'comments'
            ),
                'taxonomies'          => array( 'knowledgebase_cat' ),
                'hierarchical'        => false,
                'query_var'           => true,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => true,
                'menu_position'       => 25,
                'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode( '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="rgb(135,135,135)" d="M 13.65 1 L 17.756 1 L 17.756 1 L 17.756 6.008 L 15.784 3.089 L 13.65 6.008 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 L 13.65 1 Z  M 2.768 22.951 C 1.463 22.951 1.05 22.221 1.05 20.911 L 1.05 3.089 C 1.05 1.578 1.428 1.049 2.768 1.049 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 L 2.768 22.951 Z  M 5 9 L 17.756 9 L 17.756 11 L 5 11 L 5 9 L 5 9 L 5 9 L 5 9 L 5 9 L 5 9 L 5 9 L 5 9 Z  M 5 12 L 12 12 L 12 14 L 5 14 L 5 12 L 5 12 L 5 12 L 5 12 L 5 12 L 5 12 L 5 12 Z  M 13.65 0 L 2.415 0 L 2.415 0 L 2.415 0 L 2.415 0 L 2.415 0 C 1.082 0 0 1.031 0 2.3 L 0 21.7 C 0 22.969 1.082 24 2.415 24 L 18.585 24 C 19.918 24 21 22.969 21 21.7 L 21 2.3 C 21 1.031 19.918 0 18.585 0 L 17.756 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 L 13.65 0 Z"/></svg>' ),
                'can_export'          => true,
                'has_archive'         => false,
                'exclude_from_search' => $exclude_from_search,
                'publicly_queryable'  => true,
                'capability'          => 'edit_post',
                'show_in_rest'        => true,
                'rewrite'             => array(
                'slug'       => $this->kb_slug . '/%taxonomies%',
                'with_front' => false,
                'feeds'      => false,
            ),
            ) );
        }
        
        /**
         * Adds the product and section name on the post permalink
         *
         * @since version 1.0.0
         *
         * @param $link
         * @param $post
         * @return mixed
         */
        public function post_permalinks( $link, $post )
        {
            //Return if this is not a basepress post
            if ( 'knowledgebase' != $post->post_type ) {
                return $link;
            }
            $terms = get_the_terms( $post->ID, 'knowledgebase_cat' );
            
            if ( $terms ) {
                //replace '%taxonomies%' with the appropriate product and sections names
                $link = str_replace( '%taxonomies%', $this->get_taxonomies( $terms ), $link );
            } else {
                $link = str_replace( '/%taxonomies%', '/…/…', $link );
            }
            
            /**
             * Filters the section permalink before returning it
             */
            $link = apply_filters( 'basepress_post_permalink', $link, $post );
            return $link;
        }
        
        /**
         * Gets the product and section for the post permalink
         * It iterates through the current post terms and sorts them as product/section
         *
         * @since version 1.0.0
         *
         * @param $terms
         * @return string
         */
        public function get_taxonomies( $terms )
        {
            global  $basepress_utils ;
            $options = $basepress_utils->get_options();
            $term_product = $this->get_product( $terms[0] );
            $section = $terms[0];
            $taxonomies = '';
            if ( isset( $section ) ) {
                
                if ( isset( $options['single_product_mode'] ) ) {
                    $taxonomies = $section->slug;
                } else {
                    $taxonomies = $term_product->slug . '/' . $section->slug;
                }
            
            }
            return $taxonomies;
        }
        
        /**
         * Adds the product name on the archive permalink
         *
         * @since version 1.0.0
         *
         * @param $termlink
         * @param $term
         * @param $taxonomy
         * @return mixed
         */
        public function sections_permalink( $termlink, $term, $taxonomy )
        {
            global  $basepress_utils ;
            //If this term is not a basepress product return the $termlink unchanged
            if ( 'knowledgebase_cat' != $term->taxonomy ) {
                return $termlink;
            }
            
            if ( 0 != $term->parent ) {
                
                if ( !isset( $this->options['single_product_mode'] ) ) {
                    //If this is not a parent term, we are on a section archive page. We need to retrieve the product
                    $sections = $basepress_utils->get_sections_tree( $term );
                    $product_slug = $sections[0]->slug;
                } else {
                    $product_slug = '';
                }
                
                //Replace the '%product%' placeholder with the product name
                
                if ( !isset( $this->options['single_product_mode'] ) ) {
                    $termlink = str_replace( '%kb_product%', $product_slug, $termlink );
                } else {
                    $termlink = str_replace( '/%kb_product%', $product_slug, $termlink );
                }
            
            } else {
                //If this term is the parent term (a product), remove the '/%kb_product%' placeholder from the link
                $termlink = str_replace( '/%kb_product%', '', $termlink );
                //If single product mode in on we remove the product name from the link as well
                
                if ( isset( $this->options['single_product_mode'] ) ) {
                    $termlink = str_replace( '/' . $term->slug, '', $termlink );
                    //If the KB is in the front page the link is the home URL
                    if ( $basepress_utils->is_kb_on_front_page() ) {
                        $termlink = str_replace( '/' . $basepress_utils->get_kb_slug(), '', $termlink );
                    }
                }
            
            }
            
            /**
             * Filters the section permalink before returning it
             */
            $termlink = apply_filters( 'basepress_sections_permalink', $termlink, $term );
            return $termlink;
        }
        
        /**
         * Adds articles filtering on post list table
         *
         * @since 1.0.0
         *
         * @updated 2.1.0
         */
        public function filter_list_dropdowns()
        {
            global  $typenow ;
            
            if ( 'knowledgebase' == $typenow ) {
                $selected_product = ( isset( $_GET['kb_product'] ) ? $_GET['kb_product'] : '' );
                wp_dropdown_categories( array(
                    'show_option_all' => __( 'Show all Knowledge Bases', 'basepress' ),
                    'taxonomy'        => 'knowledgebase_cat',
                    'name'            => 'kb_product',
                    'orderby'         => 'name',
                    'selected'        => $selected_product,
                    'show_count'      => true,
                    'hide_empty'      => false,
                    'hierarchical'    => true,
                    'depth'           => 1,
                ) );
                $this->get_sections_dropdown_filter( $selected_product );
                $this->echo_filter_script();
            }
        
        }
        
        /**
         * Get sections dropdown list. This is used during Ajax as well
         *
         * @since 2.1.0
         *
         * @param $selected_product
         */
        private function get_sections_dropdown_filter( $selected_product )
        {
            $selected_product = ( $selected_product ? $selected_product : -1 );
            $selected_section = ( isset( $_GET['kb_section'] ) ? $_GET['kb_section'] : '' );
            $list = wp_dropdown_categories( array(
                'taxonomy'     => 'knowledgebase_cat',
                'name'         => 'kb_section',
                'child_of'     => $selected_product,
                'orderby'      => 'name',
                'selected'     => $selected_section,
                'show_count'   => true,
                'pad_counts'   => false,
                'hide_empty'   => false,
                'hierarchical' => true,
                'depth'        => 10,
                'echo'         => 0,
            ) );
            $option_all_text = __( 'Show all sections', 'basepress' );
            $selected = ( !$selected_section ? ' selected' : '' );
            $list = preg_replace( '/(<select.*>)/', "\$0\n\t<option value='0'{$selected}>{$option_all_text}</option>", $list );
            echo  $list ;
        }
        
        /**
         * Get sections dropdown list during Ajax
         *
         * @since 2.1.0
         */
        public function basepress_get_sections_filter()
        {
            $selected_product = ( isset( $_REQUEST['selected_product'] ) ? $_REQUEST['selected_product'] : '' );
            ob_start();
            $this->get_sections_dropdown_filter( $selected_product );
            echo  ob_get_clean() ;
            wp_die();
        }
        
        /**
         * Echoes the JS for the articles filtering
         *
         * @since 2.1.0
         */
        private function echo_filter_script()
        {
            ?>
			<script type="text/javascript">
				jQuery( '#kb_product' ).change( function(){
					jQuery( '#kb_section option:first').attr('selected','selected');

					var product = jQuery( this ).val();

					jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'basepress_get_sections_filter',
							selected_product: product
						},
						success: function( response ){
							jQuery( '#kb_section' ).replaceWith( response );
						}
					});
				});
			</script>
			<?php 
        }
        
        /**
         * Filters articles by products on post list table
         *
         * @since version 1.0.0
         *
         * @updated 2.1.0
         *
         * @param $query
         * @return mixed
         */
        public function filter_list_query( $query )
        {
            global  $pagenow ;
            $post_type = 'knowledgebase';
            $taxonomy = 'knowledgebase_cat';
            $q_vars =& $query->query_vars;
            
            if ( 'edit.php' == $pagenow && isset( $q_vars['post_type'] ) && $q_vars['post_type'] == $post_type && (isset( $_REQUEST['kb_section'] ) || isset( $_REQUEST['kb_product'] )) ) {
                $product = ( isset( $_REQUEST['kb_product'] ) && 0 != (int) $_REQUEST['kb_product'] ? (int) $_REQUEST['kb_product'] : 0 );
                $section = ( isset( $_REQUEST['kb_section'] ) && 0 != (int) $_REQUEST['kb_section'] ? (int) $_REQUEST['kb_section'] : 0 );
                
                if ( $product || $section ) {
                    $term_id = ( $section ? $section : $product );
                    $include_children = ( $section ? false : true );
                    $q_vars['tax_query'] = array( array(
                        'taxonomy'         => $taxonomy,
                        'field'            => 'term_id',
                        'terms'            => $term_id,
                        'include_children' => $include_children,
                    ) );
                }
            
            }
            
            return $query;
        }
        
        /**
         * Adds the Product and Section columns
         *
         * @since version 1.0.0
         *
         * @param $columns
         * @return array
         */
        public function add_custom_columns( $columns )
        {
            unset( $columns['taxonomy-knowledgebase_cat'] );
            $first_columns = array_slice(
                $columns,
                0,
                3,
                true
            );
            $last_columns = array_slice(
                $columns,
                3,
                null,
                true
            );
            $new_columns = array();
            $new_columns['basepress-product'] = __( 'Knowledge Base', 'basepress' );
            $new_columns['basepress-section'] = __( 'Section', 'basepress' );
            $columns = array_merge( $first_columns, $new_columns, $last_columns );
            return $columns;
        }
        
        /**
         * Generates the values for the Product and Section columns
         *
         * @since version 1.0.0
         *
         * @updated 2.1.0
         *
         * @param $column
         * @param $post_id
         */
        public function manage_custom_columns( $column, $post_id )
        {
            switch ( $column ) {
                case 'basepress-product':
                    $term = get_the_terms( $post_id, 'knowledgebase_cat' );
                    
                    if ( $term ) {
                        $product = $this->get_product( $term[0] );
                        $link = get_admin_url() . 'edit.php?post_type=knowledgebase&kb_product=' . $product->term_id;
                        $link .= '&kb_section=0';
                        echo  '<a href="' . $link . '">' . $product->name . '</a>' ;
                    }
                    
                    break;
                case 'basepress-section':
                    $term = wp_get_post_terms( $post_id, 'knowledgebase_cat', array() );
                    if ( empty($term) ) {
                        break;
                    }
                    //Skip terms with parent 0 as they are products
                    if ( 0 == $term[0]->parent ) {
                        break;
                    }
                    $product = $this->get_product( $term[0] );
                    $link = get_admin_url() . 'edit.php?post_type=knowledgebase&kb_product=' . $product->term_id;
                    $link .= '&kb_section=' . $term[0]->term_id;
                    $section = '<a href="' . $link . '">' . $term[0]->name . '</a>';
                    echo  $section ;
                    break;
            }
        }
        
        /**
         * Finds the product from the section
         *
         * @since version 1.0.0
         *
         * @param $term
         * @return array|null|WP_Error|WP_Term
         */
        private function get_product( $term )
        {
            while ( 0 != $term->parent ) {
                $term = get_term( $term->parent, 'knowledgebase_cat' );
            }
            return $term;
        }
        
        /**
         * Saves knowledge base post metas
         * This function gets called every time a post is created.
         * We can then save the default meta values for the Views, Votes and Scores and menu_order
         *
         * @since version 1.0.0
         * @updated 1.6.0
         *
         * @param $post_id
         * @param $post
         * @param $update
         */
        public function save_basepress_metas( $post_id, $post, $update )
        {
            global  $wpdb ;
            // If this isn't a knowledgebase post return.
            if ( 'knowledgebase' != $post->post_type ) {
                return;
            }
            
            if ( 'publish' == $post->post_status ) {
                $views = get_post_meta( $post_id, 'basepress_views', true );
                if ( !$views ) {
                    update_post_meta( $post_id, 'basepress_views', 0 );
                }
                //Set the menu order in case this is a new article
                
                if ( !$post->menu_order ) {
                    $post_terms = get_the_terms( $post, 'knowledgebase_cat' );
                    
                    if ( !empty($post_terms) ) {
                        $section = $post_terms[0];
                        $menu_order = $wpdb->get_var( "\n\t\t\t\t\t\t\tSELECT MAX(menu_order)+1 AS menu_order FROM {$wpdb->posts} AS p\n\t\t\t\t\t\t\tLEFT JOIN {$wpdb->term_relationships} AS t ON (p.ID = t.object_id)\n\t\t\t\t\t\t\tWHERE t.term_taxonomy_id = {$section->term_id}\n\t\t\t\t\t\t\tAND p.post_status = 'publish'\n\t\t\t\t\t\t\t" );
                        $menu_order = ( $menu_order ? $menu_order : 1 );
                        $wpdb->query( "UPDATE {$wpdb->posts} AS p SET p.menu_order = {$menu_order} WHERE p.ID = {$post_id};" );
                    }
                
                }
            
            }
        
        }
        
        /**
         * Add admin notice if the articles has missing data like section and template
         *
         * @since 1.7.6
         *
         * @return mixed 
         */
        public function missing_data_notice()
        {
            global  $post, $basepress_utils ;
            $action = ( isset( $_GET['action'] ) ? $_GET['action'] : '' );
            
            if ( 'edit' == $action && $post && 'knowledgebase' == $post->post_type && 'auto-draft' != $post->post_status ) {
                $post_type = $post->post_type;
                
                if ( 'edit' == $action && 'knowledgebase' == $post_type ) {
                    $options = $basepress_utils->get_options();
                    $missing_options = array();
                    $post_terms = get_the_terms( $post->ID, 'knowledgebase_cat' )[0];
                    $post_meta = get_post_meta( $post->ID, 'basepress_template_name', true );
                    if ( empty($post_terms) && !apply_filters( 'basepress_remove_missing_section_notice', false ) ) {
                        $missing_options[] = __( 'Section', 'basepress' );
                    }
                    if ( empty($post_meta) && !apply_filters( 'basepress_remove_missing_template_notice', false ) ) {
                        if ( !isset( $options['force_sidebar_position'] ) ) {
                            $missing_options[] = __( 'Template', 'basepress' );
                        }
                    }
                    
                    if ( !empty($missing_options) ) {
                        $class = 'notice notice-error is-dismissible';
                        $message = __( 'This article was saved without the following data:', 'basepress' ) . ' ';
                        $missing_options = implode( ', ', $missing_options );
                        printf(
                            '<div class="%1$s"><p>%2$s%3$s</p></div>',
                            esc_attr( $class ),
                            esc_html( $message ),
                            $missing_options
                        );
                    }
                
                }
            
            }
        
        }
        
        /**
         * Removes Feeds and oEmbed links in site header
         *
         * @since 1.8.9
         */
        public function remove_cpt_feeds()
        {
            global  $wp_query ;
            
            if ( 'knowledgebase' == get_post_type() || isset( $wp_query->query_vars['post_type'] ) && 'knowledgebase' == $wp_query->query_vars['post_type'] || is_tax( 'knowledgebase_cat' ) ) {
                remove_action( 'wp_head', 'feed_links_extra', 3 );
                remove_action( 'wp_head', 'feed_links', 2 );
                remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
            }
        
        }
        
        /**
         * Add Knowledge Base state to entry page in page list screen
         *
         * @since 2.1.0
         *
         * @param $post_states
         * @param $post
         * @return mixed
         */
        public function set_display_post_states( $post_states, $post )
        {
            global  $basepress_utils ;
            $options = $basepress_utils->get_options();
            if ( isset( $options['entry_page'] ) && $options['entry_page'] == $post->ID ) {
                $post_states['basepress_entry_page'] = __( 'Knowledge Base Page', 'basepress' );
            }
            return $post_states;
        }
    
    }
    //End Class
    new Basepress_CPT();
}
