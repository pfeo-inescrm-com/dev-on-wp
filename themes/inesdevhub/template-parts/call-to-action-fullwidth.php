<?php
$home_url = home_url('/'); 
?>
<!--================================
    START CALL TO ACTION AREA
    =================================-->
    <section class="call-to-action bgimage">
    <div class="bg_image_holder">
        <!-- <img src="images/calltobg.jpg" alt=""> -->
    </div>
    <div class="container content_above">
        <div class="row">
            <div class="col-md-12">
                <div class="call-to-wrap">
                    <p class="h1 text--white">
                        <?php _e('Do you want to join our Marketplace?', 'inesmktplc'); ?>
                    <!-- Vous souhaitez rejoindre notre Marketplace ? -->
                    </p>                    
                    <h4 class="text--white">
                        <?php _e('Increase efficiency. Get the most out of your tools in a few clicks.', 'inesmktplc'); ?>
                    <!-- Gagnez en efficacité. Tirez le meilleur de vos outils en quelques clics. -->
                    </h4>
                    <a href="<?php echo $home_url . _x('contact-us', 'url', 'inesmktplc');  ?>" class="btn btn--lg btn--round btn--white callto-action-btn">
                        <?php _e('Contact us', 'inesmktplc'); ?>
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