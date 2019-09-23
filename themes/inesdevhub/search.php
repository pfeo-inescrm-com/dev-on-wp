<?php
global $wp_query;
// number of search results found
$result_count = $wp_query->found_posts;
?>

<?php get_header(); ?>

        <!--================================
        START SEARCH AREA
    =================================-->
    <section class="search-wrapper">
            <div class="search-area2 bgimage">
                <div class="bg_image_holder">
                    <img src="" alt="">
                </div>
                <div class="container content_above">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <div class="search">
                                <div class="search__title">
                                    <h3>
                                        <?php echo $result_count ?>
                                        <?php _e('results found based on keyword(s):', 'inesmktplc'); ?>
                                        <?php echo get_search_query(); ?>
                                </div>
                                <?php
                                ?>
                                <div class="search__field">
                                    <?php
                                    get_template_part('template-parts/search-form-large');
                                    ?>
                                </div>
                                <div class="breadcrumb-wrapper">
                                    <?php woocommerce_breadcrumb(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end /.row -->
                </div>
                <!-- end /.container -->
            </div>
            <!-- end /.search-area2 -->
        </section>
        <!--================================
        END SEARCH AREA
    =================================-->


<!--================================
    START MAIN WRAPPER
    =================================-->
<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <div class="container">
            <div class="row search-results-wrapper">
                <?php
                if (have_posts()) {
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();
                        get_template_part('woocommerce/content-product');
                    endwhile;
                } else {
                    ?>
                <div class="col-12" style="padding-top: 100px; padding-bottom: 100px; text-align: center;">
                    <?php _e('Nothing found. Please try a different search', 'inesmktplc'); ?>
                </div>
                <?php
            }

            wp_reset_postdata();

            ?>
            </div>
        </div>

    </main>
</div>
<!--================================
                    END MAIN WRAPPER
                    =================================-->

<?php get_footer(); ?>