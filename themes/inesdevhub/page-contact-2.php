<?php
/**
 * Template Name: Contact #2
 * 
 * @package Ines CRM Developer Hub
 * @subpackage inesdevhub
 * @since version 0.1.1
 */

?>

<?php get_header(); ?>

<?php //get_template_part('template-parts/breadcrum-area-fullwidth'); ?>

<!--================================
    START MAIN WRAPPER
    =================================-->
<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <section class="contact-area section--padding">
            <div class="container">

                <!-- start page title -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title">
                            <h1>
                                <?php //_e('How can We <span class="highlighted">Help?</span>', 'inesdevhub'); ?>
                                <?php the_title(); ?>
                            </h1>
                            <?php if (function_exists('the_subtitle')) : ?>
                        <!-- WP Subtitle Plugin needed for this to work
                                        https://wordpress.org/plugins/wp-subtitle/ -->
                        <h2 style="color:#0066ac;"><?php the_subtitle(); ?></h2>
                    <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="contact_tile">
                            <span class="tiles__icon lnr lnr-map-marker"></span>
                            <h4 class="tiles__title">
                                <?php _e('Office Address', 'inesdevhub'); ?>
                            </h4>
                            <div class="tiles__content">

                                <p><?php _e('54 bis rue Sala, 69002 Lyon', 'inesdevhub'); ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- end /.col-lg-4 col-md-6 -->

                    <div class="col-lg-4 col-md-6">
                        <div class="contact_tile">
                            <span class="tiles__icon lnr lnr-phone"></span>
                            <h4 class="tiles__title">
                                <?php _e('Phone Number', 'inesdevhub'); ?>
                            </h4>
                            <div class="tiles__content">
                                <p>0 825 157 825</p>
                            </div>
                        </div>
                        <!-- end /.contact_tile -->
                    </div>
                    <!-- end /.col-lg-4 col-md-6 -->

                    <div class="col-lg-4 col-md-6">
                        <div class="contact_tile">
                            <span class="tiles__icon lnr lnr-inbox"></span>
                            <h4 class="tiles__title">
                                <?php _e('E-Mail', 'inesdevhub'); ?>
                            </h4>
                            <div class="tiles__content">
                                <p>bonjour@inescrm.com</p>
                            </div>
                        </div>
                        <!-- end /.contact_tile -->
                    </div>
                    <!-- end /.col-lg-4 col-md-6 -->
                </div>
                <!-- end /.row -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="contact_form cardify">
                            <div class="contact_form__title">
                                <h3><?php _e('Leave Your Messages', 'inesdevhub'); ?></h3>
                            </div>

                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <div class="contact_form--wrapper">
                                        <?php
                                        /**
                                         * Ninja Forms plugin
                                         * required
                                         */
                                        echo do_shortcode('[ninja_form id=3]');
                                        ?>
                                    </div>
                                </div>
                                <!-- end /.col-md-8 -->
                            </div>
                            <!-- end /.row -->
                        </div>
                        <!-- end /.contact_form -->
                    </div>
                    <!-- end /.col-md-12 -->
                </div>
                <!-- end /.row -->

            </div>
            <!-- end /.container -->
        </section>

    </main>
</div>
<!--================================
                    END MAIN WRAPPER
                    =================================-->

<?php get_footer(); ?>