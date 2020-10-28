<?php
/**
 * Template Name: Home
 *
 * @package Ines CRM Developer
 * @subpackage inesdevhub
 * @since version 0.1.0
 */
?>

<?php
$home_url = home_url('/'); 
?>

<?php
//get the shop page url to use it in link below
// $shop_page_url = get_permalink(wc_get_page_id('shop'));
?>

<?php get_header(); ?>

<?php
// while (have_posts()) {
//     the_post();
// }


// the_title(
//     '<h1>' . __( 'title text', 'textdomain' ),
//     ' ',
//     '</h1>'
// );




?>


<!--================================
    START ABOUT HERO AREA
    =================================-->
<!-- <section class="about_hero bgimage">
    <div class="bg_image_holder">
        <?php //if (has_post_thumbnail()) {
            //the_post_thumbnail();
        //} else { 
            ?>
        <img src="<?php //echo get_template_directory_uri() . '/assets/images/57342418_l.png';  ?>"
            alt="<?php //_e('home background image', 'inesdevhub'); ?>">
        <?php //} ?>

    </div>

    <div class="container content_above">
        <div class="row">
            <div class="col-md-12">
                <div class="about_hero_contents">
                    <h1><?php //_e('Welcome to INES Marketplace!', 'inesdevhub') ?></h1>
                    <?php //if (function_exists('the_subtitle')) : ?>
                    <p><?php //the_subtitle(); ?></p>
                    <?php //else : ?>
                    <p><?php //_e('Simply connect your <span>CRM</span> to your Tools', 'inesdevhub') ?></p>
                    <?php //endif; ?>
                    <div class="about_hero_btns">
                        <a href="<?php //echo $shop_page_url; ?>" class="btn btn--white btn--lg btn--round">
                            <?php //_e('Integration catalogue', 'inesdevhub') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

<section class="hero-area" style="height: 287px;">
    <div class="hero-content search-area2" style="padding: 0;">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="hero__content__title">
                            <h1 style="font-size: 50px;">
                                <!-- <span class="light"><?php //_e('Welcome to INES Marketplace!', 'inesdevhub') ?></span> -->
                                <!-- Bienvenue sur la Marketplace INES ! -->
                                <span class="light">Intégrez la solution INES</span>
                                <!-- <span class="bold"><?php //_e('Simply connect your <span>CRM</span> to your Tools', 'inesdevhub') ?></span> -->
                                <!-- Connectez simplement votre CRM à vos Outils -->
                                <span class="bold">à votre écosystème</span>
                            </h1>
                            <?php if ( function_exists('the_subtitle') && !empty(the_subtitle()) ) : ?>
                            <!-- WP Subtitle Plugin needed for this to work
                                        https://wordpress.org/plugins/wp-subtitle/ -->
                            <p class="tagline"><?php the_subtitle(); ?></p>
                            <?php endif; ?>
                        </div>
                        <!-- <div class="hero__btn-area">
                  <a href="all-products.html" class="btn btn--round btn--lg">View All Products</a>
                  <a href="all-products.html" class="btn btn--round btn--lg">Popular Products</a>
                </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================================
    END ABOUT HERO AREA
    =================================-->




<!--================================
    START MAIN WRAPPER
    =================================-->
<div id="primary" class="content-area">
    <main id="main" class="site-main">


        <section class="intro">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h2>Intégrez le programme développeur</h2>
                        <br>
                        <p>Notre programme développeur vous permet de créer votre propre intégration avec les solutions
                            INES ! En
                            contact direct avec nos équipes pour vous aider avec notre API vous travaillez en
                            environnement réel et
                            disposez de tous les outils pour construire vos workflows. Une fois votre développement
                            terminé et après
                            validation de nos équipes vous avez la possibilité de soumettre votre intégration au
                            catalogue de notre
                            Marketplace. </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="card-area">
            <div class="container">
                <div class="row">

                    <div class="col-md-12 col-lg-4">
                        <div class="news text-center">
                            <div class="news__thumbnail">
                                <img src="<?php echo get_template_directory_uri() . '/assets/images/feat-card-1.png';  ?>"
                                    alt="">
                            </div>
                            <div class="news__content text-center">
                                <h4>Demandez<br>votre Sandbox</h4>
                                <p>Créez votre Sandbox pour explorer,<br>développer et tester<br>en environnement réel</p>
                                <a href="/creer-sandbox">
                                    <button class="btn btn-lg btn-warning">Créer sandbox</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-4">
                        <div class="news text-center">
                            <div class="news__thumbnail">
                                <img src="<?php echo get_template_directory_uri() . '/assets/images/feat-card-2.png';  ?>"
                                    alt="">
                            </div>
                            <div class="news__content text-center">
                                <h4>Développez<br>votre intégration</h4>
                                <p>Accédez à la documentation<br>de nos webservices pour<br>intégrer les API INES</p>
                                <!-- <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="#"><button type="button" class="btn btn-lg btn-warning">REST</button></a>
                                    <a href="#"><button type="button" class="btn btn-lg btn-warning">SOAP</button></a>
                                </div> -->
                                <div class="row buttons-group">
                                <div class="col-12 col-lg-6"><a href="<?php echo get_site_url(); ?>/api-rest"><button type="button" class="btn btn-block btn-warning">REST</button></a></div>
                                <div class="col-12 col-lg-6"><a href="<?php echo get_site_url(); ?>/docs/api-soap"><button type="button" class="btn btn-block btn-warning">SOAP</button></a></div>
                                </div>
                                
                                <!-- <a href="#"><button type="button" class="btn btn-block btn-warning">REST</button></a>
                                <a href="#"><button type="button" class="btn btn-block btn-warning">SOAP</button></a> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-4">
                        <div class="news text-center">
                            <div class="news__thumbnail">
                                <img src="<?php echo get_template_directory_uri() . '/assets/images/feat-card-3.png';  ?>"
                                    alt="">
                            </div>
                            <div class="news__content text-center">
                                <h4>Intégrez<br>notre marketplace</h4>
                                <p>Vous souhaitez référencer votre<br>intégration sur la Marketplace INES ?<br>Contactez nos
                                    équipes</p>
                                <a href="https://marketplace.inescrm.com/contactez-nous/">
                                    <button class="btn btn-lg btn-warning">Contact</button>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="intro-features">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Disponible en REST et SOAP</h3>
                        <br>
                        <p>L'API INES CRM vous permet de connecter votre CRM à votre système d'information. Flux de
                            données entrant et sortant, workflows et automatismes, vous pouvez intégrez les API's INES
                            REST et SOAP dans n'importe quelle application, site Web ou système embarqué. Découvrez
                            notre documentation et développez en totale autonomie vos interfaces.</p>
                        <br>
                        <div class="cta-group text-left">
                            <a href="<?php echo $home_url . 'api-rest' ?>"><button
                                    class="btn btn-md btn--round mr-3">REST API</button></a>
                            <a href="<?php echo $home_url . 'docs/api-soap' ?>"><button
                                    class="btn btn-md btn--round">SOAP API</button></a>
                        </div>
                    </div>
                    <div class="col-md-6 mt-5 mt-sm-0">
                        <img src="<?php echo get_template_directory_uri() . '/assets/images/rest-347x260.png';  ?>"
                            alt="" style="width: 300px;">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-6 mt-5 mt-sm-0 order-12 order-sm-0">
                        <img src="<?php echo get_template_directory_uri() . '/assets/images/soap-347x260.png';  ?>"
                            alt="" style="width: 300px;">
                    </div>
                    <div class="col-md-6">
                        <h3>Flexible et évolutive</h3>
                        <br>
                        <p>De la création automatisée de contact à la création d'un interface avec une solution métier,
                            vous accédez en lecture, création ou modification à tous les flux INES. Vous digitalisez
                            tous vos workflows et utilisez la plateforme CRM pour construire un écosystème agile et
                            personnalisé. Bénéficiez d'une assistance de nos équipes à chaque étape de votre
                            développement et référencez votre intégration sur notre Marketplace. </p>
                        <!-- <br>
            <div class="cta-group text-right">
              <button class="btn btn-lg btn--round">REST API</button>
              <button class="btn btn-lg btn--round">SOAP API</button>
            </div> -->
                    </div>
                </div>
                <!-- <div class="row">
          <div class="col-md-6">
            <h3>Disponible en REST et SOAP</h3>
            <br>
            <p>Notre programme développeur vous permet de créer votre propre intégration avec les solutions INES ! En
              contact direct avec nos équipes pour vous aider avec notre API vous travaillez en environnement réel et
              disposez de tous les outils pour construire vos workflows. </p>
            <br>
            <div class="cta-group text-right">
              <button class="btn btn-lg btn--round">REST API</button>
              <button class="btn btn-lg btn--round">SOAP API</button>
            </div>
          </div>
          <div class="col-md-6">
            <img src="https://via.placeholder.com/600x300.png" alt="">
          </div>
        </div> -->
            </div>
        </section>

        <!--================================
    START CALL TO ACTION AREA
    =================================-->

        <section class="call-to-action bgimage" style="height:300px;">
            <div class="bg_image_holder">
                <!-- <img src="images/calltobg.jpg" alt=""> -->
            </div>
            <div class="container content_above">
                <div class="row">
                    <div class="col-md-12">
                        <div class="call-to-wrap">
                            <p class="h1 text--white">
                                <?php _e('Do you want to join our Marketplace?', 'inesdevhub'); ?>
                                <!-- Vous souhaitez rejoindre notre Marketplace ? -->
                            </p>
                            <!-- <h4 class="text--white"> -->
                            <?php //_e('Increase efficiency. Get the most out of your tools in a few clicks.', 'inesdevhub'); ?>
                            <!-- Gagnez en efficacité. Tirez le meilleur de vos outils en quelques clics. -->
                            <!-- </h4> -->
                            <a href="<?php //echo $home_url . _x('contact-us', 'url', 'inesdevhub');?>https://marketplace.inescrm.com/contactez-nous/"
                                class="btn btn--lg btn--round btn--white callto-action-btn">
                                <strong>
                                    <?php _e('Contact us', 'inesdevhub'); ?>
                                </strong>
                                <!-- Contactez-nous -->
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================================
    END CALL TO ACTION AREA
    =================================-->











        <!--================================
    START BLOCKS CONTENT AREA
    =================================-->
        <!-- <section class="section--padding">

            <div class="content_block3">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7 col-sm-12 align-self-center">
                            <div class="area_image offset-image-bottom">
                                <img src="<?php echo get_template_directory_uri() . '/assets/images/spec-viuel-home-market-place-4.png';  ?>"
                                    alt="area images">
                            </div>
                        </div>


                        <div class="col-md-5 col-sm-12 align-self-center">
                            <div class="area_content">
                                <h2 class="content_area--title">Un catalogue d'intégrations</h2>
                                <p>Marketing Automation, ERP, solutions métiers, solutions de messagerie, emails
                                    marketing … Découvrez notre catalogue d'intégrations et renforcez votre expérience
                                    INES en connectant simplement votre CRM à vos outils.</p>
                                <a href="#" class="btn btn--lg btn--round btn--white callto-action-btn">Découvrir
                                    Apps</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="content_block3 bgcolor">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 col-sm-12 align-self-center">
                            <div class="area_content">
                                <h2>Intégrez votre CRM à votre écosystème</h2>
                                <p>
                                    Développez les possibilités de vos solutions INES en connectant votre CRM avec les
                                    applications les plus populaires du marché. Vous centralisez et gérez tous vos
                                    outils du même endroit pour simplifier la collaboration de vos équipes, gagner en
                                    efficacité et booster votre productivité commerciale.
                                </p>
                                <a href="#" class="btn btn--white btn--default btn--round">Solutions INES</a>
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-12 align-self-center">
                            <div class="area_image">
                                <img src="<?php echo get_template_directory_uri() . '/assets/images/spec-viuel-home-market-place-2.png';  ?>"
                                    alt="area images">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content_block3">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7 col-sm-12 align-self-center">
                            <div class="area_image">
                                <img src="<?php echo get_template_directory_uri() . '/assets/images/spec-viuel-home-market-place-3.png';  ?>"
                                    alt="area images">
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12 align-self-center">
                            <div class="area_content">
                                <h2>Développez votre intégration</h2>
                                <p>
                                    Vous souhaitez nous proposer une intégration ? Nos API's publiques vous permettent
                                    de construire simplement votre intégration et de la tester en environnement réel via
                                    un compte de Sandbox. Intégrez notre programme développeurs et accédez à notre
                                    documentation.
                                </p>
                                <a href="#" class="btn btn--default btn--white btn--round">En savoir plus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section> -->
        <!--================================
    END BLOCKS CONTENT AREA
    =================================-->

        <!--================================
    START SPACER
    =================================-->
        <!-- <div class="clearfix mt-5 mb-5"></div> -->
        <!--================================
    END SPACER
    =================================-->
    </main>
</div>
<!--================================
    END MAIN WRAPPER
    =================================-->

<?php //get_template_part('template-parts/call-to-action-fullwidth'); ?>

<?php get_footer(); ?>