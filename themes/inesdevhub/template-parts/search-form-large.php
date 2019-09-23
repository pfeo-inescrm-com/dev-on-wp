<?php $unique_id = esc_attr( uniqid('search-form-') ); ?>

<!-- <div class="search__field"> -->
    <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
        <div class="field-wrapper">
            <input 
                type="search"
                id="<?php echo $unique_id; ?>"
                class="relative-field rounded"
                placeholder="<?php echo esc_attr_x( 'Find your tools...', '', 'inesmktplc' ) ?>"
                value="<?php echo get_search_query() ?>"
                name="s"
                title="<?php echo esc_attr_x( 'Find your tools...', '', 'inesmktplc' ) ?>"
            />
            <!-- limit the search only to woocommerce products -->
            <!-- <input type="hidden" name="post_type" value="product"> -->
            <!-- Recherchez vos outils ... -->
            <button class="btn btn--round" type="submit"><?php _e( 'Search...', 'inesmktplc' ) ?></button>
            <!-- Recherchez -->
        </div>
    </form>
<!-- </div> -->