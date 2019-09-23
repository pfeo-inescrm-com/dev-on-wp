<?php

/**
 * This class declares and handles the shortcodes.
 */
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'Basepress_Shortcodes' ) ) {
    class Basepress_Shortcodes
    {
        /**
         * basepress_shortcodes constructor.
         *
         * @since 1.0.0
         */
        public function __construct()
        {
            // register basepress shortcode
            add_shortcode( 'basepress', array( $this, 'shortcodes_router' ) );
        }
        
        /**
         * Calls the shortcode function according to the short code attributes
         * If there are not attributes it will generate the product list
         *
         * @since 1.0.0
         *
         * @param $atts
         * @param $content
         * @param $tag
         * @return string
         */
        public function shortcodes_router( $atts, $content, $tag )
        {
            global  $post, $basepress_shortcode_editor ;
            
            if ( '' == $atts ) {
                return $this->show_products();
            } else {
                return;
            }
        
        }
        
        /**
         * Generates the products page calling the products.php template in the theme
         *
         * @since 1.0.0
         *
         * @updaed 2.1.0
         *
         * @return string
         */
        public function show_products()
        {
            global  $basepress_utils ;
            $products = $basepress_utils->get_products();
            if ( empty($products) ) {
                return $basepress_utils->no_products_message();
            }
            ob_start();
            $products_template = $basepress_utils->get_theme_file_path( 'products.php' );
            if ( $products_template ) {
                include $products_template;
            }
            return ob_get_clean();
        }
    
    }
    // End class
    new basepress_shortcodes();
}
