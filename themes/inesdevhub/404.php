<?php get_header(); ?>

<!--================================
    START MAIN WRAPPER
    =================================-->
<div id="primary" class="content-area">
    <main id="main" class="site-main">
    <section class="four_o_four_area section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 text-center">
                    <img src="<?php echo get_template_directory_uri() . '/assets/images/ohno404.png';  ?>" alt="404 page">
                    <div class="not_found">
                        <h3>
                            <?php _e('Oops! Page Not Found', 'inesmktplc') ?>
                        </h3>
                        <a href="<?php echo get_home_url() ?>" class="btn btn--round btn--default">
                            <?php _e('Back to Home', 'inesmktplc') ?> 
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>
</div>
<!--================================
    END MAIN WRAPPER
    =================================-->

<?php //get_template_part('template-parts/call-to-action-fullwidth'); ?>

<?php get_footer(); ?>