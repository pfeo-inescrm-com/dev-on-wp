<?php

/**
 * Include all Gutenberg Blocks
 */
// Exit if called directly.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//Exit if Gutenberg editor is not active
if ( !class_exists( 'WP_Block_Type_Registry' ) ) {
    return;
}

if ( !class_exists( 'BasePress_Editor_Blocks' ) ) {
    class BasePress_Editor_Blocks
    {
        function __construct()
        {
            //Add Knowledge Base category for Gutenberg Blocks
            add_filter(
                'block_categories',
                array( $this, 'add_block_categories' ),
                10,
                2
            );
            add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
            //Include the Products block
            require_once 'gutenberg-products-block.php';
            require_once 'gutenberg-searchbar-block.php';
        }
        
        /**
         * @param $categories
         * @param $post
         * @return array
         */
        public function add_block_categories( $categories, $post )
        {
            return array_merge( $categories, array( array(
                'slug'  => 'basepress-kb-block-cat',
                'title' => __( 'Knowledge Base', 'basepress' ),
                'icon'  => '',
            ) ) );
        }
        
        /**
         * Register API end point to fetch KB Categories
         *
         * @since 2.1.0
         */
        public function register_rest_routes()
        {
            register_rest_route( 'basepress_kb/v1', '/kb_categories/', array(
                'methods'  => WP_REST_Server::READABLE,
                'callback' => array( $this, 'get_kb_categories' ),
            ) );
            register_rest_route( 'basepress_kb/v1', '/kb_css_url', array(
                'methods'  => WP_REST_Server::READABLE,
                'callback' => array( $this, 'get_css_url' ),
            ) );
        }
        
        /**
         * API endpoint function to get the list of products and sections for the block settings
         *
         * @since 2.1.0
         */
        public function get_kb_categories( $request )
        {
            $include_sections = ( isset( $_REQUEST['products'] ) && 'true' === $_REQUEST['products'] ? false : true );
            $response = array();
            $products = array( array(
                'value' => 0,
                'label' => __( 'Select KB', 'basepress' ),
            ) );
            //Get all products terms in knowledgebase_cat
            $args = array(
                'taxonomy'   => 'knowledgebase_cat',
                'hide_empty' => true,
                'parent'     => 0,
                'meta_key'   => 'basepress_position',
                'orderby'    => 'meta_value_num',
                'order'      => 'ASC',
            );
            $product_terms = get_terms( $args );
            foreach ( $product_terms as $product ) {
                $products[] = array(
                    'value' => $product->term_id,
                    'label' => $product->name,
                );
            }
            $response['products'] = $products;
            //Sections data
            
            if ( $include_sections ) {
                $sections = array(
                    0 => array( array(
                    'value' => 0,
                    'label' => __( 'Select Section', 'basepress' ),
                ) ),
                );
                foreach ( $product_terms as $product ) {
                    $sections[$product->term_id] = array( array(
                        'value' => 0,
                        'label' => __( 'Select Section', 'basepress' ),
                    ) );
                    //Get all sections terms in knowledgebase_cat
                    $args = array(
                        'taxonomy'   => 'knowledgebase_cat',
                        'hide_empty' => false,
                        'parent'     => $product->term_id,
                        'meta_key'   => 'basepress_position',
                        'orderby'    => 'meta_value_num',
                        'order'      => 'ASC',
                    );
                    $sections_terms = get_terms( $args );
                    foreach ( $sections_terms as $section ) {
                        $sections[$product->term_id][] = array(
                            'value' => $section->term_id,
                            'label' => $section->name,
                        );
                        $sub_sections = get_term_children( $section->term_id, 'knowledgebase_cat' );
                        foreach ( $sub_sections as $sub_section ) {
                            $term = get_term( $sub_section, 'knowledgebase_cat' );
                            $sections[$product->term_id][] = array(
                                'value' => $term->term_id,
                                'label' => '- ' . $term->name,
                            );
                        }
                    }
                }
                $response['sections'] = $sections;
            }
            
            return $response;
        }
        
        public function get_css_url()
        {
            global  $basepress_utils ;
            $stylesheet = apply_filters( 'basepress_theme_style', 'style.css' );
            $theme_css = $basepress_utils->get_theme_file_uri( 'css/' . $stylesheet );
            return $theme_css;
        }
    
    }
    new BasePress_Editor_Blocks();
}
