<?php

/**
 * Plugin Name: BasePress
 * Plug URI: https://www.codesavory.com
 * Description: The perfect Knowledge Base plugin for WordPress
 * Version: 2.4.3
 * Author: codeSavory
 * Author URI: https://www.codesavory.com
 * Text Domain: basepress
 * Domain Path: /languages
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */
// Exit if called directly.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'base_fs' ) ) {
    base_fs()->set_basename( false, __FILE__ );
    return;
}


if ( !function_exists( 'base_fs' ) ) {
    // Create a helper function for easy SDK access.
    function base_fs()
    {
        global  $base_fs ;
        
        if ( !isset( $base_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_1252_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_1252_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $base_fs = fs_dynamic_init( array(
                'id'             => '1252',
                'slug'           => 'basepress',
                'type'           => 'plugin',
                'public_key'     => 'pk_4f447df1ddff73b6182b7022a1e7d',
                'is_premium'     => false,
                'premium_suffix' => '',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'       => 'basepress',
                'first-path' => 'admin.php?page=basepress_manual',
                'support'    => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $base_fs;
    }
    
    // Init Freemius.
    base_fs();
    // Signal that SDK was initiated.
    do_action( 'base_fs_loaded' );
    //Add logo image on freemius optin screen
    function my_fs_custom_icon()
    {
        return dirname( __FILE__ ) . '/assets/img/logo.png';
    }
    
    //Freemius GDPR Admin notice
    base_fs()->add_filter( 'handle_gdpr_admin_notice', '__return_true' );
    base_fs()->add_filter( 'plugin_icon', 'my_fs_custom_icon' );
    //Freemius unistall
    base_fs()->add_action( 'after_uninstall', 'base_fs_uninstall_cleanup' );
    define( 'BASEPRESS_DIR', plugin_dir_path( __FILE__ ) );
    define( 'BASEPRESS_URI', plugin_dir_url( __FILE__ ) );
    
    if ( !class_exists( 'Basepress' ) ) {
        class Basepress
        {
            /**
             * Plugin version
             *
             * @var string
             */
            public  $ver = '2.4.3' ;
            /**
             * Database version
             *
             * @var int
             */
            public  $db_ver = 2.1 ;
            /**
             * Boot strap the plugin
             *
             * @since 1.0.0
             * @updated 1.4.0, 1.5.0, 1.7.10
             */
            public function bootstrap()
            {
                $this->define_constants();
                //Add plugin icon on admin menu
                add_action( 'admin_head', array( $this, 'add_plugin_icon' ) );
                //Load text domain
                add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ), 10 );
                //Register the function to run on activation
                register_activation_hook( __FILE__, array( $this, 'activate' ) );
                //Register the function to run on deactivation
                register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
                //Add knowledge base post type
                add_action( 'init', array( $this, 'register_post_type' ) );
                //Adds correct links on front-end admin bar to edit KBs and Sections
                add_action( 'admin_bar_menu', array( $this, 'basepress_admin_bar_edit' ), 81 );
                //Add basepress_variables class
                require_once BASEPRESS_DIR . 'includes/class-basepress-variables.php';
                //Add basepress_utils class
                require_once BASEPRESS_DIR . 'includes/class-basepress-utils.php';
                
                if ( is_admin() ) {
                    //Check if settings needs updating
                    add_action( 'init', array( $this, 'init_options' ) );
                    //Add Help screen
                    require_once 'admin/class-basepress-manual.php';
                    //Add basepress template metabox for posts
                    require_once 'admin/class-basepress-template-metabox.php';
                    //Add basepress product metabox for posts
                    require_once 'admin/class-basepress-product-metabox.php';
                    //Add basepress section metabox for posts
                    require_once 'admin/class-basepress-section-metabox.php';
                    //Add basepress icon metabox for posts
                    require_once 'admin/class-basepress-post-icon-metabox.php';
                    //Add admin options page
                    require_once 'admin/class-basepress-settings.php';
                    //Add admin products page
                    require_once 'admin/class-basepress-products-page.php';
                    //Add admin sections page
                    require_once 'admin/class-basepress-sections-page.php';
                    //Manage the default terms edit screen for our taxonomy
                    require_once 'admin/class-basepress-terms-edit.php';
                    //Add icon manager page
                    require_once 'admin/icons-manager.php';
                    //Enqueue admin scripts and styles
                    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 99 );
                }
                
                //Add Ajax Search
                require_once BASEPRESS_DIR . 'includes/class-basepress-search.php';
                //Add Views count
                require_once BASEPRESS_DIR . 'includes/class-basepress-post-views.php';
                //Add widgets
                require_once 'includes/class-basepress-widgets.php';
                //Add BasePress shortcodes
                require_once 'includes/class-basepress-shortcodes.php';
                //Add Walker Comment Class
                require_once BASEPRESS_DIR . 'includes/class-basepress-walker-comment.php';
                //Add Public functions
                require_once BASEPRESS_DIR . 'public/public-functions.php';
                //Gutenberg blocks
                require_once 'blocks/gb-blocks.php';
                //BasePress Wizard
                require_once 'admin/wizard.php';
                //BasePress Debug
                require_once 'includes/class-basepress-debug-output.php';
            }
            
            /**
             * Define BasePress constants
             *
             * @since 1.7.10
             */
            private function define_constants()
            {
                $this->define( 'BASEPRESS_DIR', plugin_dir_path( __FILE__ ) );
                $this->define( 'BASEPRESS_URI', plugin_dir_url( __FILE__ ) );
                $this->define( 'BASEPRESS_VER', $this->ver );
                $this->define( 'BASEPRESS_DB_VER', $this->db_ver );
                $this->define( 'BASEPRESS_PLAN', 'lite' );
            }
            
            /**
             * Define constant if not already defined
             *
             * @since 1.7.10
             *
             * @param $name
             * @param $value
             */
            private function define( $name, $value )
            {
                if ( !defined( $name ) ) {
                    define( $name, $value );
                }
            }
            
            /**
             * Adds plugin icon on Admin screen menu
             *
             * @since 1.7.0
             *
             * @return mixed
             */
            public function add_plugin_icon()
            {
                echo  '<style type="text/css">' ;
                echo  '#toplevel_page_basepress .wp-menu-image{background-repeat:no-repeat;background-position:center;background-image:  url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABHFJREFUeNq0VktsVGUU/u575t559jEdph1oGexYcTAUbHFhBA0sWLgybAxocMcCF6gxcWvcuiBxUcUHEhdKYmKjMRGJggtCrE1KKLS1LcNAp89poZ12nvd6/v/ezkxxWlzIzT1z507Of77z+M45I+DMxR4A50ni+P+vEZITIn2ce0IAcOyeZyC78WSvuEwfgv3dopvELJOYVRWB/BBF+8k1hZrzFr9hmbbUnmNnRInUBci1+q/sasSb+7ZjYWUV6cUVTC1lMTrzAOMLWcznyICk2EIHmUONLhE7gy50NhuI+NxoC+jY5tfR98cILo1MA6oOyEotiIlEyINjPTGoG7wFhlKz+LD/T1wZncJMNotQ0IuXOyM4ffBpdLeHoEkiSqaFEkyoFPHQeBKXBjKAR+DRbABZXVvjHl6bTGNw+G+EG/1oC4XwfGwbvj11FMP35tF3+S+cPLQXe6LN/NiDXAG/DU1gNDWN/c/E8MKuKDIL80B2CXB5yJ5eC2JxIObJ2Z+u45sLX0PTvWhqCSPeHsHR3j14+9WX8PHxIzxbp/u+R29XOxLREI69+xEerhVw7oNTOEAgEqtrIQeUS9yuWI8OLT7KpaYj7w7gflHB5bF5vPdFP7785RoH+KT/d5z9/ALuJlNoDfrwME9GjQAMr+GQRagSiS65HkiD4Yao+2B6GgBfE+VVhplbodSUeO4Xl5eJADJF2AqPYUANNKMguyEomkNAcQML64IUGRWZIh2CRt6pbtJUoZJBC6zAEmfODwNj+Pn2DKXdT46osESxJhJsDVKp0foBdpg8Z8AivVuSxB346soN2wnNy1lkPcLKx4PUwyWRCERab07FzWvBI6YiC0J9kLqFFzbxaP1ifWGDqHbDaURVWds0krogq4UCTQizDng1Iv4iOGlkQukql83/AOKMonjYD79bhWIVie/UoMVChfN2ucxq3eh3xSzCJ5bh1SSnr83Na6LJItasMt442I1EpAFXx2dwZ3ENg/eWMDCZwVg6w/VmM8vQRAHdHU14Lt6BznAQCXLsxXgbd1KVBHtg1gORZJl3vCVY2BffiZ6uWCU9fb8Owq8ryJfL1Okd2L3jdZw8cqCSQpOiYj3E3jVFcSa34KT5zEU7B+UidugmDkcNzM3NoTVA0zXSjGg4hEN7uxDUXVxthdLjkWzfCvT9x+s3cSc9i8n0HCbSC2jxaLg6ksLYMuvqCKAHayKhCJJLOXw2NUuWFoF8lgPrioRokx+J2Ha889ph9D7Vhlt3p/H+p99hdCKJFKUuWyI/JWIa6x+2CmgkwRO0KU4RVSNxiogiDbZingPALDm/5Tlo2FCwn0BuDN9GcpomrZua0O2zG5ImAtsdbARxINY7ioszrwoC52E6W86yKpOZAzKgXLYSYcVjl2EbE+XKJrTF2ahO4S2b9U4JmSKkR3jtsucX2w+lvO0IHzU13v9rNW+k8E2SZzfvbwefr17ZTkvt/t/C+PrfIhbPWw6Q9fjpZa/TinCKCluNOmb3xD8CDAAY1Iv4LsN16QAAAABJRU5ErkJggg==");}' ;
                echo  '</style>' ;
            }
            
            /**
             * Load text domain
             *
             * @since 1.0.0
             */
            public function load_plugin_textdomain()
            {
                load_plugin_textdomain( 'basepress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
            }
            
            /**
             * Functions to run only upon activation of the plugin
             *
             * @since 1.0.0
             */
            public function activate()
            {
                $this->init_options();
                $this->register_post_type();
                add_action( 'shutdown', array( $this, 'flush_rewrite_rules' ) );
            }
            
            /**
             * Functions to run only upon deactivation of the plugin
             *
             * @since 1.0.0
             */
            public function deactivate()
            {
                flush_rewrite_rules();
            }
            
            /**
             * Flushes rewrite rules. Called during activation from add_action
             *
             * @since 1.0.0
             */
            public function flush_rewrite_rules()
            {
                flush_rewrite_rules();
            }
            
            /**
             * Registers the knowledge base post type
             *
             * @since 1.0.0
             */
            public function register_post_type()
            {
                include_once 'includes/class-basepress-cpt.php';
            }
            
            /**
             * Saves the plugin version on WP options table on activation
             *
             * @since 1.0.0
             * @updated 1.4.0
             */
            public function init_options()
            {
                //Initialize default option values
                $options = get_option( 'basepress_settings' );
                
                if ( empty($options) ) {
                    $options = (include_once BASEPRESS_DIR . 'options.php');
                    update_option( 'basepress_settings', $options );
                    update_option( 'basepress_ver', BASEPRESS_VER, true );
                    update_option( 'basepress_db_ver', BASEPRESS_DB_VER, true );
                    update_option( 'basepress_plan', BASEPRESS_PLAN, true );
                    update_option( 'basepress_run_wizard', 1, true );
                }
                
                $this->maybe_update();
            }
            
            /**
             * Function to run on plugin update
             *
             * @since 1.7.10
             */
            public function maybe_update()
            {
                //Load the update file
                require_once __DIR__ . '/update.php';
                //If this is not an ajax call trigger the update function in the included file
                
                if ( !wp_doing_ajax() ) {
                    $old_ver = get_option( 'basepress_ver' );
                    $old_db_ver = get_option( 'basepress_db_ver' );
                    $old_plan = get_option( 'basepress_plan' );
                    if ( empty($old_ver) || $old_ver != BASEPRESS_VER || $old_db_ver != BASEPRESS_DB_VER || $old_plan != BASEPRESS_PLAN ) {
                        basepress_update(
                            $old_ver,
                            $old_db_ver,
                            $old_plan,
                            BASEPRESS_VER,
                            BASEPRESS_DB_VER,
                            BASEPRESS_PLAN
                        );
                    }
                }
            
            }
            
            /**
             * Enqueue scripts for back end
             *
             * @since 1.0.0
             *
             * @param $screen
             */
            public function enqueue_admin_scripts( $screen )
            {
                global  $basepress_utils ;
                $post_screens = array( 'edit.php', 'post.php', 'post-new.php' );
                $style_screens = array(
                    'knowledgebase_page_basepress_manage_kbs',
                    'knowledgebase_page_basepress_sections',
                    'knowledgebase_page_basepress_postorder',
                    'knowledgebase_page_basepress_feedback',
                    'settings_page_basepress',
                    'settings_page_basepress_network',
                    'toplevel_page_basepress',
                    'basepress_page_basepress_icons_manager',
                    'basepress_page_basepress_shortcode_editor'
                );
                $icons_screens = array(
                    'post.php',
                    'post-new.php',
                    'knowledgebase_page_basepress_sections',
                    'settings_page_basepress',
                    'settings_page_basepress_network',
                    'toplevel_page_basepress',
                    'basepress_page_basepress_icons_manager'
                );
                if ( 'knowledgebase' == get_post_type() && in_array( $screen, $post_screens ) ) {
                    wp_enqueue_style(
                        'basepress-post-editor',
                        plugins_url( 'admin/css/post-editor.css', __FILE__ ),
                        array(),
                        BASEPRESS_VER
                    );
                }
                if ( in_array( $screen, $style_screens ) ) {
                    wp_enqueue_style(
                        'basepress-admin',
                        plugins_url( 'style.css', __FILE__ ),
                        array(),
                        BASEPRESS_VER
                    );
                }
                
                if ( in_array( $screen, $icons_screens ) ) {
                    //Get icons url from active theme
                    $theme_icons = $basepress_utils->get_icons_uri();
                    wp_enqueue_style(
                        'basepress-icons',
                        $theme_icons,
                        array(),
                        BASEPRESS_VER
                    );
                }
            
            }
            
            /**
             * Modifies the edit links on front-end admin bar to point to BasePress custom pages to edit KBs and Sections
             *
             * @since 1.0.0
             *
             * @param $wp_admin_bar
             */
            public function basepress_admin_bar_edit( $wp_admin_bar )
            {
                
                if ( is_tax( 'knowledgebase_cat' ) ) {
                    //If there is no edit button on the admin bar return
                    if ( !$wp_admin_bar->get_node( 'edit' ) ) {
                        return;
                    }
                    $queried_object = get_queried_object();
                    //Edit menu for products
                    
                    if ( 0 == $queried_object->parent ) {
                        $href = get_admin_url() . 'edit.php?post_type=knowledgebase&page=basepress_manage_kbs&product=' . $queried_object->term_id;
                        $title = __( 'Edit Knowledge Base', 'basepress' );
                    } else {
                        //Edit menu for sections
                        $href = get_admin_url() . 'edit.php?post_type=knowledgebase&page=basepress_sections&section=' . $queried_object->term_id;
                        $title = __( 'Edit Section', 'basepress' );
                    }
                    
                    //Modify the admin menu
                    $wp_admin_bar->add_node( array(
                        'id'    => 'edit',
                        'title' => $title,
                        'href'  => $href,
                    ) );
                }
            
            }
            
            /**
             * Add WPML support
             *
             * @since 1.5.0
             */
            public function load_wpml_support()
            {
                require_once BASEPRESS_DIR . 'includes/premium/class-basepress-wpml-support.php';
            }
        
        }
        //Class end
        global  $basepress ;
        $basepress = new Basepress();
        $basepress->bootstrap();
    }

}

/**
 * Unistallation function. Must be outside of class
 *
 * @since 1.6.0
 *
 * @return mixed
 */
if ( !function_exists( 'base_fs_uninstall_cleanup' ) ) {
    function base_fs_uninstall_cleanup()
    {
        global  $wpdb ;
        $options = get_site_option( 'basepress_settings' );
        $remove_all = ( isset( $options['remove_all_unistall'] ) ? true : false );
        if ( !$remove_all ) {
            return;
        }
        
        if ( !is_multisite() ) {
            /*
             * Delete all Articles from database
             */
            $args = array(
                'post_type'              => 'knowledgebase',
                'post_status'            => array(
                'publish',
                'pending',
                'draft',
                'auto-draft',
                'future',
                'private',
                'inherit',
                'trash'
            ),
                'suppress_filters'       => true,
                'cache_results'          => false,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'no_found_rows'          => true,
                'fields'                 => 'ids',
            );
            $wp_query = new WP_Query();
            $bp_posts = $wp_query->query( $args );
            foreach ( $bp_posts as $post ) {
                wp_delete_post( $post, true );
            }
            /*
             * Remove all Products and Sections
             */
            foreach ( array( 'knowledgebase_cat' ) as $taxonomy ) {
                // Prepare & excecute SQL
                $terms = $wpdb->get_results( $wpdb->prepare( "SELECT t.*, tt.* FROM {$wpdb->terms} AS t INNER JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('%s') ORDER BY t.name ASC", $taxonomy ) );
                // Delete Terms
                if ( $terms ) {
                    foreach ( $terms as $term ) {
                        $wpdb->delete( $wpdb->term_taxonomy, array(
                            'term_taxonomy_id' => $term->term_taxonomy_id,
                        ) );
                        $wpdb->delete( $wpdb->term_relationships, array(
                            'term_taxonomy_id' => $term->term_taxonomy_id,
                        ) );
                        $wpdb->delete( $wpdb->termmeta, array(
                            'term_id' => $term->term_id,
                        ) );
                        $wpdb->delete( $wpdb->terms, array(
                            'term_id' => $term->term_id,
                        ) );
                    }
                }
                // Delete Taxonomy
                $wpdb->delete( $wpdb->term_taxonomy, array(
                    'taxonomy' => $taxonomy,
                ), array( '%s' ) );
            }
            /*
             * Remove single site options
             */
            delete_option( 'widget_basepress_products_widget' );
            delete_option( 'widget_basepress_sections_widget' );
            delete_option( 'widget_basepress_related_articles_widget' );
            delete_option( 'widget_basepress_popular_articles_widget' );
            delete_option( 'widget_basepress_toc_widget' );
            delete_option( 'knowledgebase_cat_children' );
            delete_option( 'basepress_modern_theme' );
            delete_option( 'basepress_run_wizard' );
            //Remove sidebars widgets
            $sidebars = get_option( 'sidebars_widgets' );
            if ( isset( $sidebars['basepress-sidebar'] ) ) {
                unset( $sidebars['basepress-sidebar'] );
            }
            update_option( 'sidebars_widgets', $sidebars );
        }
        
        /*
         * Remove single and multisite options
         */
        delete_site_option( 'basepress_settings' );
        delete_site_option( 'basepress_ver' );
        delete_site_option( 'basepress_db_ver' );
        delete_site_option( 'basepress_plan' );
        delete_site_option( 'basepress_run_wizard' );
    }

}