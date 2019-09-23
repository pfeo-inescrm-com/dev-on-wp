<?php
/**
 * Template Name: Presentation
 *
 * @package Ines CRM Marketplace
 * @subpackage inesmktplc
 * @since version 0.1.0
 */

?>

<?php get_header(); ?>

<?php get_template_part('template-parts/breadcrum-area-fullwidth'); ?>


<!--================================
    START MAIN WRAPPER
    =================================-->
<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <!--================================
    START TIMELINE AREA
    =================================-->
        <section class="timeline_area section--padding">
            <div class="container">

                <div class="row">

                    <?php if (is_active_sidebar('inesmktplc-sidebar-main')) : ?>
                        <!-- start col-lg-9 -->
                        <div class="col-lg-9 col-12">
                        <?php else : ?>
                            <!-- start col-12 -->
                            <div class="col-12">
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="section-title text-left">
                                        <h1>
                                            Comment ça <span class="highlighted">fonctionne ?</span>
                                        </h1>
                                        <!-- <p>Laborum dolo rumes fugats untras. Etharums ser quidem rerum facilis dolores nemis omnis fugats. Lid
                            est laborum dolo rumes fugats untras.</p> -->
                                    </div>
                                </div>
                                <!-- end /.col-md-12 -->
                            </div>
                            <!-- end /.row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="timeline">
                                        <li class="happening">
                                            <div class="happening--period">
                                                <p>Explorez nos apps</p>
                                            </div>
                                            <div class="happening--detail">
                                                <h4 class="title">Un catalogue d’intégrations</h4>
                                                <p>Marketing Automation, ERP, solutions métiers, solutions de
                                                    messagerie, emails marketing … Découvrez notre catalogue
                                                    d'intégrations et renforcez votre expérience INES en connectant
                                                    simplement votre CRM à vos outils.</p>
                                                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn btn--lg btn--round btn--white callto-action-btn">Catalogue</a>
                                            </div>
                                        </li>
                                        <li class="happening">
                                            <div class="happening--period">
                                                <p>Augmentez le potentiel de vos solutions</p>
                                            </div>
                                            <div class="happening--detail">
                                                <h4 class="title">Intégrez votre CRM à votre écosytème</h4>
                                                <p>Développez les possibilités de vos solutions INES en connectant votre CRM avec les applications les plus populaires du marché. Vous centralisez et gérez tous vos outils du même endroit pour simplifier la collaboration de vos équipes, gagner en efficacité et booster votre productivité commerciale.</p>
                                                <a href="https://www.inescrm.fr/decouvrir/solutions-metiers" class="btn btn--lg btn--round btn--white callto-action-btn" target="_blank">Solutions INES</a>
                                            </div>
                                        </li>
                                        <li class="happening">
                                            <div class="happening--period">
                                                <p>Vous ne trouvez pas l’application qu’il vous faut ?</p>
                                            </div>
                                            <div class="happening--detail">
                                                <h4 class="title">Développez votre intégration</h4>
                                                <p>Vous souhaitez nous proposer une intégration ? Nos API's publiques vous permettent de construire simplement votre intégration et de la tester en environnement réel via un compte de Sandbox. Intégrez notre programme développeurs et accédez à notre documentation.</p>
                                                <!-- <a href="#" class="btn btn--lg btn--round btn--white callto-action-btn">Espace API</a> -->
                                            </div>
                                        </li>
                                    </ul>
                                    <!-- end /.timeline -->
                                </div>
                                <!-- end /.col-md-12 -->
                            </div>
                            <!-- end /.row -->

                        </div>
                        <!-- end /.col if -->


                        <?php if (is_active_sidebar('inesmktplc-sidebar-main')) : ?>
                            <!-- start .col-lg-3 -->
                            <div class="col-lg-3 col-12 mt-5 mt-lg-0">
                                <!-- start aside -->
                                <aside class="sidebar product--sidebar">
                                    <?php dynamic_sidebar('inesmktplc-sidebar-main'); ?>
                                </aside>
                                <!-- end aside -->
                            </div>
                            <!-- end /.col-lg-3 -->
                        <?php endif; ?>



                    </div>

                </div>
                <!-- end /.container -->
        </section>
        <!--================================
    END TIMELINE AREA
=================================-->


    </main>
</div>
<!--================================
                    END MAIN WRAPPER
                    =================================-->

<?php get_template_part('template-parts/call-to-action-fullwidth'); ?>

<?php get_footer(); ?>