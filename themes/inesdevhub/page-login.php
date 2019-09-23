<?php
/**
 * Template Name: Login
 * 
 * @package Ines CRM Developer Hub
 * @subpackage inesdevhub
 * @since version 0.1.0
 */

get_header();

// get_template_part('template-parts/breadcrum-area-fullwidth');

?>



<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
		$args = array();
		// get login string from url
		$login  = (isset($_GET['login'])) ? $_GET['login'] : 0;
		?>

        <!--================================
            START LOGIN AREA
    =================================-->
        <section class="login_area section--padding2">
            <div class="container">
                <?php if ($login === "failed" || $login === "empty" || $login === "false") : ?>
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="alert alert-danger" role="alert">
                            <span class="alert_icon lnr lnr-warning"></span>

                            <?php if ($login === "failed") {
									_e('<strong>Oh snap!</strong> Invalid username and/or password.', 'inesdevhub');
								} elseif ($login === "empty") {
									_e('<strong>Oh snap!</strong> Username and/or Password is empty.', 'inesdevhub');
								} elseif ($login === "false") {
									_e('<strong>Oh snap!</strong> You are logged out.', 'inesdevhub');
								}
								?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span class="lnr lnr-cross" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="cardify login">
                            <div class="login--header">
                                <h3>Welcome Back</h3>
                                <p>You can sign in with your username</p>
                            </div>
                            <!-- end .login_header -->

                            <div class="login--form">

                                <?php wp_login_form($args); ?>

                                <div class="login_assist">
                                    <p class="recover">Lost your
                                        <a href="pass-recovery.html">username</a> or
                                        <a href="pass-recovery.html">password</a>?</p>
                                    <p class="signup">Don't have an
                                        <a href="signup.html">account</a>?</p>
                                </div>

                            </div>
                            <!-- end .login--form -->



                        </div>
                        <!-- end .cardify -->
                    </div>
                    <!-- end .col-md-6 -->
                </div>
                <!-- end .row -->
            </div>
            <!-- end .container -->
        </section>
        <!--================================
            END LOGIN AREA
    =================================-->





    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();