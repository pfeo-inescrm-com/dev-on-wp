<?php

/**
 * Main Sidebar
 */
function inesdevhub_sidebar_main()
{
    $args = array(
        'name'          => __('Sidebar Dev Hub - General', 'inesdevhub'),
        'id'            => 'inesdevhub-sidebar-main',    // ID should be LOWERCASE  ! ! !
        'description'   => __('INES Dev Hub general sidebar', 'inesdevhub'),
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        // 'after_widget'  => '</div>',
        // 'before_title'  => '<h2 class="widget-title">',
        // 'after_title'   => '</h2>',
        'before_title'  => '<a class="card-title"><h4>',
        'after_title'   => '</h4></a>',
        'after_widget'  => '</div>',
    );

    register_sidebar($args);
}

/**
 * Footer Sidebar 1st column
 */
function inesdevhub_sidebar_footer_1()
{
    $args = array(
        'name'          => __('Sidebar Dev Hub - Footer 1st column', 'inesdevhub'),
        'id'            => 'inesdevhub-sidebar-footer-1',    // ID should be LOWERCASE  ! ! !
        'description'   => __('INES Dev Hub sidebar for footer 1st column', 'inesdevhub'),
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title text--white">',
        'after_title'   => '</h4>',
    );

    register_sidebar($args);
}

/**
 * Footer Sidebar 2nd column
 */
function inesdevhub_sidebar_footer_2()
{
    $args = array(
        'name'          => __('Sidebar Dev Hub - Footer 2nd column', 'inesdevhub'),
        'id'            => 'inesdevhub-sidebar-footer-2',    // ID should be LOWERCASE  ! ! !
        'description'   => __('INES Dev Hub sidebar for footer 2nd column', 'inesdevhub'),
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title text--white">',
        'after_title'   => '</h4>',
    );

    register_sidebar($args);
}

/**
 * Footer Sidebar 3rd column
 */
function inesdevhub_sidebar_footer_3()
{
    $args = array(
        'name'          => __('Sidebar Dev Hub - Footer 3rd column', 'inesdevhub'),
        'id'            => 'inesdevhub-sidebar-footer-3',    // ID should be LOWERCASE  ! ! !
        'description'   => __('INES Dev Hub sidebar for footer 3rd column', 'inesdevhub'),
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title text--white">',
        'after_title'   => '</h4>',
    );

    register_sidebar($args);
}

/**
 * Footer Sidebar 4th column
 */
function inesdevhub_sidebar_footer_4()
{
    $args = array(
        'name'          => __('Sidebar Dev Hub - Footer 4th column', 'inesdevhub'),
        'id'            => 'inesdevhub-sidebar-footer-4',    // ID should be LOWERCASE  ! ! !
        'description'   => __('INES Dev Hub sidebar for footer 4th column', 'inesdevhub'),
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title text--white">',
        'after_title'   => '</h4>',
    );

    register_sidebar($args);
}