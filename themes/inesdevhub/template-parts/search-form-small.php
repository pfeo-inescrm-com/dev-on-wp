<?php $unique_id = esc_attr( uniqid('search-form-') ); ?>

<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
    <div class="searc-wrap">
        <input 
            type="search"
            id="<?php echo $unique_id; ?>"
            class="search-field"
            placeholder="<?php echo esc_attr_x( 'Search...', '', 'inesmktplc' ) ?>"
            value="<?php echo get_search_query() ?>"
            name="s"
            title="<?php echo esc_attr_x( 'Search for:', '', 'inesmktplc' ) ?>" />
        <button type="submit" class="search-wrap__btn">
            <span class="lnr lnr-magnifier"></span>
        </button>
    </div>
</form>