<?php get_header(); ?>

<!--================================
    START MAIN WRAPPER
    =================================-->
<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <?php
                    if (have_posts()) :

                        /* Start the Loop */
                        while (have_posts()) :
                            the_post();
                        endwhile;
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <header class="entry-header">
                                <?php
                                if (is_singular()) :
                                    the_title('<h1 class="entry-title">', '</h1>');
                                else :
                                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                                endif;

                                if ('post' === get_post_type()) :
                                    ?>
                                    <div class="entry-meta">
                                        <?php
                                        // inesmktplc_posted_on();
                                        // inesmktplc_posted_by();
                                        ?>
                                    </div><!-- .entry-meta -->
                                <?php endif; ?>
                            </header><!-- .entry-header -->

                            <?php //inesmktplc_post_thumbnail(); ?>

                    <?php endif; ?>
                </div> <!-- end /.col-md-9 -->
                <div class="col-md-3">
                    <!-- start aside -->
                    <aside class="sidebar product--sidebar">
                        <?php 
                            if (is_active_sidebar('inesmktplc-sidebar-product')) {
                                dynamic_sidebar('inesmktplc-sidebar-product');
                            }
                        // get_sidebar('inesmktplc-sidebar-product'); 
                        ?>
                    </aside>
                    <!-- end aside -->
                </div> <!-- end /.col-md-3 -->
            </div>
        </div>

    </main>
</div>
<!--================================
                END MAIN WRAPPER
                =================================-->

<?php get_footer(); ?>