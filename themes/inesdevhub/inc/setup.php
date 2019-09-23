<?php

function inesdevhub_setup_theme()
{

    //setup i18n
    load_theme_textdomain('inesdevhub', get_template_directory() . '/languages');

    // set up menus
    add_theme_support('menus');
    register_nav_menu('primary', __('Primary Menu', 'inesdevhub'));
    // register_nav_menu('woocommerce1', __('Woocommerce #1 Menu', 'inesdevhub'));

    // setup  featured images
    // add_theme_support( 'post_thumbnails' );

    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'gallery', 'caption'));
    // add_theme_support('woocommerce');
}

// function register_second_featured_image()
// {
//   if (class_exists('MultiPostThumbnails')) {
//     $postTypes = array('product', 'page', 'post');
//     foreach ($postTypes as $postType) {
//       new MultiPostThumbnails(array(
//         'label' => 'Secondary Image',
//         'id' => 'secondary-image',
//         'post_type' => $postType,
//       ));
//     }
//   }
//   add_image_size('cart-secondary-thumbnail', 80, 55);
// }
