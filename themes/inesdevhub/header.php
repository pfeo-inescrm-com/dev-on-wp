<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">

    <!-- viewport meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="INES CRM Marketplace - more description here">
    <meta name="keywords" content="marketplace, ines crm">

    <?php wp_head() ?>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
</head>


<body <?php
        if (is_home()) :
            echo body_class('preload home1 mutlti-vendor');
        else :
            echo body_class('preload');
        endif;
        ?>>


    <!-- ================================
    START MENU AREA
    ================================= -->
    <!-- start menu-area -->
    <div class="menu-area">
        <!-- start .top-menu-area -->
        <div class="top-menu-area">
            <!-- start .container -->
            <div class="container pt-2 pb-2">
                <!-- start .row -->
                <div class="row align-content-center align-items-center">
                    <!-- start .col-md-3 -->
                    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                        <!-- start .logo -->
                        <div class="logo">
                            <?php
                            if (has_custom_logo()) :
                                the_custom_logo();
                            else :
                                ?>
                                <a href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a>
                            <?php endif; ?>
                        </div>
                        <!-- <h1><strong>THIS IS DEV GENERIC</strong></h1> -->
                        <!-- end /.logo -->
                    </div>
                    <!-- end /.col-md-3 -->

                    <!-- start .col-md-9 -->
                    <div class="col-lg-8 offset-lg-1 col-md-9 col-sm-6 col-12 d-sm-block d-flex flex-column align-items-start">
                        <div class="h2 m-0 site-name text-sm-right"><?php bloginfo('name'); ?></div>
                        <!-- start .author-area logged-in -->
                        <?php
                        // if ( is_user_logged_in() ) :
                        //     get_template_part( 'template-parts/author-area-loggedin' );
                        ?>                        
                        <!-- end .author-area logged-in -->
                        <?php //else : ?>
                        <!-- start .author-area .not_logged_in-->
                        <?php //get_template_part( 'template-parts/author-area-not-loggedin' ); ?>
                        <!-- end .author-area not_logged_in -->
                        <?php //endif; ?>
                    </div>
                    <!-- end /.col-md-5 -->
                </div>
                <!-- end /.row -->
            </div>
            <!-- end /.container -->
        </div>
        <!-- end /.top-menu-area  -->
    </div>
    <!-- end  -->

    <!-- start .mainmenu_area -->
    <div class="mainmenu">
        <!-- start .container -->
        <div class="container">
            <!-- start .row-->
            <div class="row">
                <!-- start .col-md-12 -->
                <div class="col-md-12">
                    <div class="navbar-header">
                        <!-- start mainmenu__search -->
                        <div class="mainmenu__search">
                            <?php 
                            // get_search_form();
                            // get_template_part('template-parts/search-form-small');
                            ?>
                        </div>
                        <!-- start mainmenu__search -->
                    </div>

                    <nav class="navbar navbar-expand-md navbar-light mainmenu__menu">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <?php
                            if (has_nav_menu('primary')) {
                                wp_nav_menu(array(
                                    'theme_location' => 'primary',
                                    'depth' => 0,
                                    'container' => 'div',
                                    'container_class' => 'collapse navbar-collapse',
                                    'container_id' => 'navbarNav',
                                    'menu_class' => 'navbar-nav',
                                    'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
                                    'walker' => new WP_Bootstrap_Navwalker(),
                                ));
                            }
                        ?>
                        <!-- /.navbar-collapse -->
                    </nav>
                </div>
                <!-- end /.col-md-12 -->
            </div>
            <!-- end /.row-->
        </div>
        <!-- start .container -->
    </div>
    <!-- end /.mainmenu-->
    </div>
    <!-- end /.menu-area -->
    <!--================================
    END MENU AREA
    =================================-->