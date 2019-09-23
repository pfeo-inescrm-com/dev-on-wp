<?php

/**
 * INES CRM DEVELOPER HUB 
 * functions and definitions
 *
 * @since 0.1.0
 * 
 * DO NOT INCLUDE TRANSLATIONS STRINGS HERE
 * SINCE LOCO TRANSLATE PLUGIN DOES NOT
 * PROCESS FILES MORE THAN 100KB
 * 
 * 
 */

/**
 *  SETUP
 */

/* -------------------------------------------------------------
------------------------------------------------------------- */


/**
 *  INCLUDES
 */
include(get_template_directory() . '/inc/enqueue.php');
include(get_template_directory() . '/inc/setup.php');
include(get_template_directory() . '/inc/sidebars.php');
include(get_template_directory() . '/inc/widgets.php');
// include(get_template_directory() . '/inc/widgets/class-inesdevhub-widget-categories.php');
//include(get_template_directory() . '/inc/widgets/class-inesdevhub-widget-filter-products-checkbox.php');
// include(get_template_directory() . '/inc/widgets/class-inesdevhub-widget-product-modules.php');
// include(get_template_directory() . '/inc/widgets/class-inesdevhub-widget-product-pricing.php');
// include(get_template_directory() . '/inc/inesdevhub-wc-template-functions.php');
// Register Custom Navigation Walker
require_once(get_template_directory() . '/inc/wp-bootstrap-navwalker.php');
// library for bundling required plugins
require_once(get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php');
include(get_template_directory() . '/inc/inesdevhub-register-required-plugins.php');

/* -------------------------------------------------------------
------------------------------------------------------------- */

/**
 *  HOOKS
 */

// inject styles and scripts
// for theme
add_action('wp_enqueue_scripts', 'inesdevhub_enqueue');
// for login page
add_action('login_enqueue_scripts', 'inesdevhub_enqueue_login');

// initial theme setup
add_action('after_setup_theme', 'inesdevhub_setup_theme');

// start sidebars
add_action('widgets_init', 'inesdevhub_sidebar_main');
// add_action('widgets_init', 'inesdevhub_sidebar_wc_shop');
add_action('widgets_init', 'inesdevhub_sidebar_footer_1');
add_action('widgets_init', 'inesdevhub_sidebar_footer_2');
add_action('widgets_init', 'inesdevhub_sidebar_footer_3');
add_action('widgets_init', 'inesdevhub_sidebar_footer_4');
// add_action('widgets_init', 'inesdevhub_sidebar_wc_single_product');

// start widgets
// add_action('widgets_init', 'inesdevhub_widget_init');

// ask for required plugins
add_action('tgmpa_register', 'inesdevhub_register_required_plugins');

// allow Multi Post Thumbnails plugin to start
// add_action('wp_loaded', 'register_second_featured_image');

/* -------------------------------------------------------------
------------------------------------------------------------- */


/**
 *  SHORTCODES
 */

/* -------------------------------------------------------------
------------------------------------------------------------- */


/**
 *  OTHER FUNCTIONS
 */

/**
 * this is a modification of
 * wordpress get_the_term_list()
 * given a taxonomy
 * it returns just the first result
 * it is intendend to show just one category
 * on product cards
 * 
 */
// function inesdevhub_get_the_term_list_first_result($id, $taxonomy)
// {
//   $terms = get_the_terms($id, $taxonomy);

//   if (is_wp_error($terms)) {
//     return $terms;
//   }

//   if (empty($terms)) {
//     return false;
//   }

//   $links = array();

//   foreach ($terms as $term) {
//     $link = get_term_link($term, $taxonomy);
//     if (is_wp_error($link)) {
//       return $link;
//     }
//     $links[] = '<a href="' . esc_url($link) . '" rel="tag">' . $term->name . '</a>';
//   }

//   /**
//    * Filters the term links for a given taxonomy.
//    *
//    * The dynamic portion of the filter name, `$taxonomy`, refers
//    * to the taxonomy slug.
//    *
//    * @since 2.5.0
//    *
//    * @param string[] $links An array of term links.
//    */
//   $term_links = apply_filters("term_links-{$taxonomy}", $links);

//   return $term_links[0];
// }

/**
 * hide login page
 * to prevent attacks
 */
// function inesdevhub_redirect_to_nonexistent_page()
// {
//   $new_login =  'newlogin';
//   if (strpos($_SERVER['REQUEST_URI'], $new_login) === false) {
//     wp_safe_redirect(home_url('access-denied'), 302);
//     exit();
//   }
// }
// add_action('login_head', 'inesdevhub_redirect_to_nonexistent_page');

/**
 * send users to custom login url
 */
// function inesdevhub_redirect_to_actual_login()
// {
//   $new_login =  'newlogin';
//   if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) == $new_login && ($_GET['redirect'] !== false)) {
//     wp_safe_redirect(home_url("login?$new_login&redirect=false"));
//     exit();
//   }
// }
// add_action('init', 'inesdevhub_redirect_to_actual_login');

// function login_failed()
// {
//   $login_page  = home_url('/login/');
//   wp_redirect($login_page . '?login=failed');
//   exit;
// }
// add_action('wp_login_failed', 'login_failed');

// function verify_username_password($user, $username, $password)
// {
//   $login_page  = home_url('/login/');
//   if ($username == "" || $password == "") {
//     wp_redirect($login_page . "?login=empty");
//     exit;
//   }
// }
// add_filter('authenticate', 'verify_username_password', 1, 3);

// // redirect user to homepage after login
// function inesdevhub_redirect_upon_login()
// {
//   return home_url();
//   exit();
// }
// add_filter('login_redirect', 'inesdevhub_redirect_upon_login');

// // redirect user to homepage after logout
// function inesdevhub_auto_redirect_after_logout()
// {
//   wp_redirect(home_url());
//   exit();
// }
// add_action('wp_logout', 'inesdevhub_auto_redirect_after_logout');

/**
 * Customize login page
 */
// function inesdevhub_login_stylesheet() {
//   wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/style-login.css' );
// }
// //This loads the function above on the login page
// add_action( 'login_enqueue_scripts', 'inesdevhub_login_stylesheet' );
