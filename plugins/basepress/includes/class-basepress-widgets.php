<?php

/**
 * This is the class that adds the main sidebar and declares the widgets
 */
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
include_once 'class-basepress-products-widget.php';
include_once 'class-basepress-sections-widget.php';
include_once 'class-basepress-related-articles-widget.php';

if ( !class_exists( 'Basepress_Widgets' ) ) {
    class Basepress_Widgets
    {
        /**
         * basepress_widgets constructor.
         *
         * @since 1.0.0
         */
        public function __construct()
        {
            //Add widget area
            add_action( 'widgets_init', array( $this, 'create_sidebars' ), 99 );
            //Add Products Widget
            add_action( 'widgets_init', array( $this, 'register_widgets' ), 99 );
        }
        
        /**
         * Creates Basepress sidebars
         *
         * @since 1.0.0
         */
        public function create_sidebars()
        {
            register_sidebar( array(
                'name'          => __( 'Knowledge Base Sidebar', 'basepress' ),
                'id'            => 'basepress-sidebar',
                'description'   => __( 'Add here the widgets to appear on the knowledge base pages', 'basepress' ),
                'class'         => '',
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) );
        }
        
        /**
         * Registers Basepress Widgets
         *
         * @since 1.0.0
         */
        public function register_widgets()
        {
            register_widget( 'basepress_products_widget' );
            register_widget( 'basepress_sections_widget' );
            register_widget( 'basepress_related_articles_widget' );
        }
    
    }
    // End of Class
    new Basepress_Widgets();
}
