<li class="has_dropdown">
    <div class="icon_wrap">
        <span class="lnr lnr-cart"></span>
        <span class="notification_count purch">
            <?php echo WC()->cart->get_cart_contents_count(); ?>
        </span>
    </div>

    <div class="dropdowns dropdown--cart">

        <?php if (WC()->cart->get_cart_contents_count() !== 0) : ?>
            <div class="cart_area">

                <?php


                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);


                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                        ?>

                        <div class="cart_product">
                            <div class="product__info">
                                <div class="thumbn">
                                    <?php
                                    // main featured image
                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                    // secondary featured image
                                    $second_thumb = MultiPostThumbnails::get_the_post_thumbnail('product', 'secondary-image', $product_id, 'cart-secondary-thumbnail');

                                    if (!$product_permalink) {
                                        if (class_exists('MultiPostThumbnails') && $second_thumb) {
                                            print_r($second_thumb);
                                        } else {
                                            echo $thumbnail;
                                        }
                                    } elseif (class_exists('MultiPostThumbnails') && $second_thumb) {
                                        printf('<a href="%s">%s</a>', esc_url($product_permalink), $second_thumb);
                                    } else {
                                        printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                    }
                                    ?>
                                </div>

                                <div class="info">
                                    <span class="title" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                                        <?php
                                        if (!$product_permalink) {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                        } else {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                        }

                                        do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);
                                        ?>
                                    </span>
                                    <!-- displays product category -->
                                    <!-- <div class="cat">
                                                                                                                                                                <a href="#">
                                                                                                                                                                    <img src="images/catword.png" alt="">
                                                                                                                                                                    Wordpress
                                                                                                                                                                </a>
                                                                                                                                                            </div> -->
                                </div>
                            </div>

                            <div class="product__action">
                                <!-- <a href="#"><span class="lnr lnr-trash"> -->
                                <?php
                                // @codingStandardsIgnoreLine
                                echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                                    '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><span class="lnr lnr-trash"></span></a>',
                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                    __('Remove this item', 'woocommerce'),
                                    esc_attr($product_id),
                                    esc_attr($_product->get_sku())
                                ), $cart_item_key);
                                ?>
                                <!-- </span></a> -->
                                <p data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
                                    <?php
                                    echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                    ?>
                                </p>
                            </div>
                        </div>

                    <?php
                }
            }
            ?>

                <div class="total">
                    <p data-title="<?php esc_attr_e('Sub-total', 'woocommerce'); ?>">
                        <span><?php _e('Sub-total :', 'inesmktplc'); ?></span>
                        <?php
                        echo WC()->cart->get_cart_total();
                        ?>
                    </p>
                </div>
                <div class="cart_action">
                    <a class="go_cart" href="<?php echo wc_get_cart_url(); ?>">
                        <?php _e('View Cart', 'inesmktplc'); ?>
                    </a>
                    <a class="go_checkout" href="<?php echo wc_get_checkout_url(); ?>">
                        <?php _e('Checkout', 'inesmktplc'); ?>
                    </a>
                </div>
            </div>
            <!-- end /.cart_area -->

        <?php else : ?>
            <div class="cart_area">
                <p class="empty-cart-info">
                    <?php _e('Your cart is empty', 'inesmktplc'); ?>
                </p>
            </div>

        <?php endif; ?>
    </div>
    <!-- end /.dropdowns.dropdown--cart -->
</li>