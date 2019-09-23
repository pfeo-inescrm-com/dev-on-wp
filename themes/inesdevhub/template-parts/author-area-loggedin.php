<div class="author-area">
    <!-- <a href="signup.html" class="author-area__seller-btn inline">Become a Seller</a> -->

    <div class="author__notification_area">
        <ul>

        <?php get_template_part( 'template-parts/author-area-cart'); ?>

        </ul>
    </div>
    <!--start .author__notification_area -->

    <!--start .author-author__info-->
    <div class="author-author__info inline has_dropdown">
        <!-- <div class="author__avatar">
            <img src="images/usr_avatar.png" alt="user avatar">
        </div> -->
        <div class="autor__info">
            <p class="name">
                <?php 
                if ( WC()->customer->get_first_name() !== null || WC()->customer->get_last_name() !== null ) {
                    echo WC()->customer->get_first_name() . ' ' .WC()->customer->get_last_name();
                } else {
                    echo WC()->customer->get_username();
                }
                 ?>
            </p>
            <p class="ammount">
                <?php
                    echo WC()->cart->get_cart_total();
                ?>
            </p>
        </div>

        <!-- <div class="dropdowns dropdown--author">
            <ul>
                <li>
                    <a href="author.html">
                        <span class="lnr lnr-user"></span>Profile</a>
                </li>
                <li>
                    <a href="dashboard.html">
                        <span class="lnr lnr-home"></span>
                        Dashboard</a>
                </li>
                <li>
                    <a href="dashboard-setting.html">
                        <span class="lnr lnr-cog"></span>
                        Setting</a>
                </li>
                <li>
                    <a href="cart.html">
                        <span class="lnr lnr-cart"></span>Purchases</a>
                </li>
                <li>
                    <a href="favourites.html">
                        <span class="lnr lnr-heart"></span>
                        Favourite</a>
                </li>
                <li>
                    <a href="dashboard-add-credit.html">
                        <span class="lnr lnr-dice"></span>Add Credits</a>
                </li>
                <li>
                    <a href="dashboard-statement.html">
                        <span class="lnr lnr-chart-bars"></span>Sale Statement</a>
                </li>
                <li>
                    <a href="dashboard-upload.html">
                        <span class="lnr lnr-upload"></span>Upload Item</a>
                </li>
                <li>
                    <a href="dashboard-manage-item.html">
                        <span class="lnr lnr-book"></span>Manage Item</a>
                </li>
                <li>
                    <a href="dashboard-withdrawal.html">
                        <span class="lnr lnr-briefcase"></span>Withdrawals</a>
                </li>
                <li>
                    <a href="#">
                        <span class="lnr lnr-exit"></span>Logout</a>
                </li>
            </ul>
        </div> -->
    </div>
    <!--end /.author-author__info-->
</div>

<!-- author area restructured for mobile -->
<div class="mobile_content">
    <span class="lnr lnr-user menu_icon"></span>

    <!-- offcanvas menu -->
    <div class="offcanvas-menu closed">
        <span class="lnr lnr-cross close_menu"></span>
        <div class="author-author__info">
            <!-- <div class="author__avatar v_middle">
                <img src="images/usr_avatar.png" alt="user avatar">
            </div> -->
            <div class="autor__info v_middle">
                <p class="name">
                <?php 
                if ( WC()->customer->get_first_name() !== null || WC()->customer->get_last_name() !== null ) {
                    echo WC()->customer->get_first_name() . ' ' .WC()->customer->get_last_name();
                } else {
                    echo WC()->customer->get_username();
                }
                 ?>
                </p>
                <p class="ammount">
                <?php
                    echo WC()->cart->get_cart_total();
                ?>
                </p>
            </div>
        </div>
        <!--end /.author-author__info-->

        <div class="author__notification_area">
            <ul>
                <li>
                    <a href="cart.html">
                        <div class="icon_wrap">
                            <span class="lnr lnr-cart"></span>
                            <span class="notification_count purch">
                                <?php echo WC()->cart->get_cart_contents_count(); ?>
                            </span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <!--start .author__notification_area -->

        <!-- <div class="dropdowns dropdown--author">
            <ul>
                <li>
                    <a href="author.html">
                        <span class="lnr lnr-user"></span>Profile</a>
                </li>
                <li>
                    <a href="dashboard.html">
                        <span class="lnr lnr-home"></span>
                        Dashboard</a>
                </li>
                <li>
                    <a href="dashboard-setting.html">
                        <span class="lnr lnr-cog"></span>
                        Setting</a>
                </li>
                <li>
                    <a href="cart.html">
                        <span class="lnr lnr-cart"></span>Purchases</a>
                </li>
                <li>
                    <a href="favourites.html">
                        <span class="lnr lnr-heart"></span>
                        Favourite</a>
                </li>
                <li>
                    <a href="dashboard-add-credit.html">
                        <span class="lnr lnr-dice"></span>Add Credits</a>
                </li>
                <li>
                    <a href="dashboard-statement.html">
                        <span class="lnr lnr-chart-bars"></span>Sale Statement</a>
                </li>
                <li>
                    <a href="dashboard-upload.html">
                        <span class="lnr lnr-upload"></span>Upload Item</a>
                </li>
                <li>
                    <a href="dashboard-manage-item.html">
                        <span class="lnr lnr-book"></span>Manage Item</a>
                </li>
                <li>
                    <a href="dashboard-withdrawal.html">
                        <span class="lnr lnr-briefcase"></span>Withdrawals</a>
                </li>
                <li>
                    <a href="#">
                        <span class="lnr lnr-exit"></span>Logout</a>
                </li>
            </ul>
        </div> -->

        <!-- <div class="text-center">
            <a href="signup.html" class="author-area__seller-btn inline">Become a Seller</a>
        </div> -->
    </div>
</div>
<!-- end /.mobile_content -->