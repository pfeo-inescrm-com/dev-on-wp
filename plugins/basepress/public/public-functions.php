<?php

/*
 *	Core Theming functions
 */
// Exit if called directly.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Function to check if the single product mode is activated
 *
 * @since 2.3.0
 *
 * @return bool
 */
function basepress_is_single_kb()
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    return ( isset( $options['single_product_mode'] ) ? true : false );
}

/**
 * Gets all knowledge base objects
 *
 * @since 2.3.0
 *
 * @return mixed
 */
function basepress_kbs()
{
    global  $basepress_utils ;
    return $basepress_utils->get_products();
}

/**
 * Gets single current product
 *
 * @since 2.3.0
 *
 * @return mixed
 */
function basepress_kb()
{
    global  $basepress_utils ;
    return $basepress_utils->get_product();
}

/**
 * Get the text for the KB selection button
 *
 * @since 2.3.0
 *
 * @return string|void
 */
function basepress_choose_kb_btn_text()
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    $text = ( isset( $options['kbs_choose_text'] ) ? $options['kbs_choose_text'] : '' );
    return ( !empty($text) ? $text : __( 'Choose', 'basepress' ) );
}

/**
 * Get the sidebar position from settings
 *
 * @since 2.3.0
 *
 * @return string
 */
function basepress_sidebar_position( $reverse = false )
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    $position = ( isset( $options['sidebar_position'] ) && $options['sidebar_position'] ? $options['sidebar_position'] : 'right' );
    if ( $reverse && 'none' != $position ) {
        $position = ( 'right' == $position ? 'left' : 'right' );
    }
    return $position;
}

/**
 * Gets sections for current product
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function basepress_sections()
{
    global  $basepress_utils ;
    return $basepress_utils->get_sections();
}

/**
 * Returns sub-section style for the current product
 *
 * @since 1.3.0
 *
 * @return mixed
 */
function basepress_subsection_style()
{
    global  $basepress_utils ;
    $product = $basepress_utils->get_product();
    $sections_style = get_term_meta( $product->id, 'sections_style', true );
    return $sections_style['sub_sections'];
}

/**
 * Gets breadcrumbs
 *
 * @since 1.0.0
 *
 * @return mixed
 */
function basepress_breadcrumbs()
{
    global  $basepress_utils ;
    return $basepress_utils->get_breadcrumbs();
}

/**
 * Replaces WP get_template part function.
 * Retrieves template part inside the currently used theme
 *
 * @since 1.0.0
 *
 * @param $slug
 * @param null $name
 * @return mixed
 */
function basepress_get_template_part( $slug, $name = null )
{
    global  $basepress_utils ;
    return $basepress_utils->get_template_part( $slug, null );
}

/**
 * Echos the number of columns set in the options for the products page
 *
 * @since 2.3.0
 */
function basepress_kb_cols()
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    echo  $options['products_cols'] ;
}

/**
 * Echos the number of columns set in the options for the sections page
 *
 * @since 1.0.0
 */
function basepress_section_cols()
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    echo  $options['sections_cols'] ;
}

/**
 * Returns whether section icons should be displayed or not according to options
 *
 * @since 1.0.0
 *
 * @return bool
 */
function basepress_show_section_icon()
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    return ( isset( $options['show_section_icon'] ) ? true : false );
}

/**
 * Returns whether post icons should be displayed or not according to options
 *
 * @since 1.0.0
 *
 * @return bool
 */
function basepress_show_post_icon()
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    return ( isset( $options['show_post_icon'] ) ? true : false );
}

/**
 * Returns the array of post meta icons
 *
 * @since 1.7.8
 *
 * @return mixed
 */
function basepress_get_post_meta_icons()
{
    global  $basepress_utils ;
    $icons = $basepress_utils->icons;
    return $icons->postmeta->icon;
}

/**
 * Returns post icon if set or default one
 *
 * @since 1.0.0
 *
 * @updated 1.7.8
 *
 * @param string $post_id
 * @return mixed|string
 */
function basepress_post_icon( $post_id = '' )
{
    global  $basepress_utils ;
    $icon = '';
    if ( $post_id ) {
        $icon = get_post_meta( $post_id, 'basepress_post_icon', true );
    }
    $icon = ( !empty($icon) ? $icon : $basepress_utils->icons->post->default );
    return $icon;
}

/**
 * Returns all post meta data
 *
 * @since 1.3.0
 *
 * @param string $post_id
 * @return array|bool
 */
function basepress_get_post_meta( $post_id = '' )
{
    global  $basepress_utils ;
    
    if ( $post_id ) {
        $metas = get_post_meta( $post_id, '', true );
        $post_metas = array();
        $post_metas['icon'] = ( !empty($metas['basepress_post_icon'][0]) ? $metas['basepress_post_icon'][0] : $basepress_utils->icons->post->default );
        $post_metas['views'] = ( isset( $metas['basepress_views'][0] ) ? $metas['basepress_views'][0] : 0 );
        return $post_metas;
    }
    
    return false;
}

/**
 * Returns whether post count in section should be displayed or not according to options
 *
 * @since 1.0.0
 *
 * @return bool
 */
function basepress_show_section_post_count()
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    return ( isset( $options['show_section_post_count'] ) ? true : false );
}

/**
 * Renders the section pagination
 *
 * @since 1.0.0
 *
 * @updated 1.7.8
 */
function basepress_pagination()
{
    global  $wp_query, $basepress_utils ;
    $icons = $basepress_utils->icons->pagination;
    $prev_icon = ( isset( $icons->icon[0] ) ? $icons->icon[0] : '' );
    $next_icon = ( isset( $icons->icon[1] ) ? $icons->icon[1] : '' );
    $base = html_entity_decode( str_replace( 99999999, '%#%', get_pagenum_link( 99999999 ) ) );
    $format = '%#%';
    
    if ( $wp_query->is_search && isset( $basepress_utils->get_options()['search_use_url_parameters'] ) ) {
        $base = @add_query_arg( 'paged', '%#%' );
        $format = '';
    }
    
    $args = array(
        'base'               => $base,
        'format'             => $format,
        'total'              => $wp_query->max_num_pages,
        'current'            => max( 1, get_query_var( 'paged' ) ),
        'show_all'           => true,
        'prev_next'          => true,
        'prev_text'          => '<span class="' . $prev_icon . '"></span>',
        'next_text'          => '<span class="' . $next_icon . '"></span>',
        'type'               => 'array',
        'add_args'           => false,
        'add_fragment'       => '',
        'before_page_number' => '',
        'after_page_number'  => '',
    );
    $links = paginate_links( $args );
    
    if ( is_array( $links ) ) {
        echo  '<ul>' ;
        foreach ( $links as $link ) {
            echo  '<li>' ;
            echo  $link ;
            echo  '</li>' ;
        }
        echo  '</ul>' ;
    }

}

/**
 * Renders the article pagination
 *
 * @since 1.2.0
 *
 * @updated 1.7.8
 */
function basepress_post_pagination()
{
    global  $basepress_utils ;
    global 
        $page,
        $numpages,
        $multipage,
        $more,
        $post,
        $wp_rewrite
    ;
    $icons = $basepress_utils->icons->pagination;
    $prev_icon = ( isset( $icons->icon[0] ) ? $icons->icon[0] : '' );
    $next_icon = ( isset( $icons->icon[1] ) ? $icons->icon[1] : '' );
    $permalink = get_permalink();
    
    if ( $multipage ) {
        echo  '<ul>' ;
        if ( $page != 1 ) {
            //Previous arrow
            echo  '<li><a class="prev page-numbers" href="' . $permalink . ($page - 1) . '/"><span class="' . $prev_icon . '"></span></a></li>' ;
        }
        for ( $i = 1 ;  $i <= $numpages ;  $i++ ) {
            
            if ( $i == $page ) {
                $url = $permalink;
            } else {
                
                if ( '' == get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending' ) ) ) {
                    $url = add_query_arg( 'page', $i, $permalink );
                } elseif ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_on_front' ) == $post->ID ) {
                    $url = trailingslashit( get_permalink() ) . user_trailingslashit( "{$wp_rewrite->pagination_base}/" . $i, 'single_paged' );
                } else {
                    $url = trailingslashit( get_permalink() ) . user_trailingslashit( $i, 'single_paged' );
                }
            
            }
            
            echo  '<li>' ;
            
            if ( $i == $page ) {
                echo  '<span class="page-numbers current">' . $i . '</span>' ;
            } else {
                echo  '<a class="page-numbers" href="' . $url . '">' . $i . '</a>' ;
            }
            
            echo  '</li>' ;
        }
        if ( $page != $numpages ) {
            //Next arrow
            echo  '<li><a class="next page-numbers" href="' . $permalink . ($page + 1) . '/"><span class="' . $next_icon . '"></span></a></li>' ;
        }
    }
    
    echo  '</ul>' ;
}

/*
 * Search
 */
/**
 * Calls the render function on search bar class
 *
 * @since 1.0.0
 */
function basepress_searchbar()
{
    global  $basepress_search ;
    $basepress_search->render_searchbar();
}

/**
 * Returns search term from search bar class
 *
 * @since 1.0.0
 *
 * @return string
 */
function basepress_search_term()
{
    global  $basepress_search ;
    return $basepress_search->get_search_term();
}

/**
 * Returns search result page title from options
 *
 * @since 1.2.0
 *
 * @return string
 */
function basepress_search_page_title()
{
    global  $basepress_utils, $wp_the_query ;
    $options = $basepress_utils->get_options();
    $title = '';
    if ( isset( $options['search_page_title'] ) ) {
        $title = str_replace( '%number%', $wp_the_query->found_posts, $options['search_page_title'] );
    }
    return $title;
}

function basepress_search_page_no_results_title()
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    $title = ( isset( $options['search_page_no_results_title'] ) ? $options['search_page_no_results_title'] : '' );
    return $title;
}

/**
 * Echos the post snippets for the search page results
 *
 * @since 1.2.1
 * @updated 1.8.5
 */
function basepress_search_post_snippet()
{
    global  $basepress_search ;
    $terms = $basepress_search->get_search_term();
    
    if ( '' != $terms ) {
        $terms = $basepress_search->filter_terms( $terms );
        $search_terms = explode( ' ', $terms );
        $content = get_the_content();
        //Strip shortcodes from content
        $content = $basepress_search->strip_shortcodes( $content );
        //Strip html tags from content
        $content = strip_tags( $content );
        $snippet = $basepress_search->get_snippet( $terms, $content );
        $word_boundary = apply_filters( 'basepress_snippet_word_boundary', '\\b' );
        foreach ( $search_terms as $term ) {
            if ( strlen( $term ) > apply_filters( 'basepress_search_min_char_length', 2 ) - 1 ) {
                $snippet = preg_replace( '@(' . $word_boundary . preg_quote( $term, '@' ) . ')@i', "<b>\\1</b>", $snippet );
            }
        }
        echo  $snippet ;
    }

}

/*
 *	Votes
 */
/**
 * Calls the render function on the vote class
 *
 * @since 1.0.0
 */
function basepress_votes()
{
    return;
}

/**
 * Return true if Votes are active. False otherwise.
 *
 * @since 2.3.2
 *
 * @return bool
 */
function basepress_show_post_votes()
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    return isset( $options['voting_active'] );
}

/*
 *  Previous and next articles
 */
/**
 * Checks if Previous and Next article links should be shown
 *
 * @since 1.2.0
 *
 * @return bool
 */
function basepress_show_adjacent_articles()
{
    return false;
}

/**
 * Return the previous article object
 *
 * @since 1.2.0
 *
 * @return mixed
 */
function basepress_prev_article()
{
    return;
}

/**
 * Return the next article object
 *
 * @since 1.2.0
 *
 * @return mixed
 */
function basepress_next_article()
{
    return;
}

/**
 * Return the title to display on previous article link as per options
 *
 * @since 1.2.0
 *
 * @return string
 */
function basepress_prev_article_text()
{
    return;
}

/**
 * Return the title to display on next article link as per options
 *
 * @since 1.2.0
 *
 * @return string
 */
function basepress_next_article_text()
{
    return;
}

/*
 *  Table of Content
 */
/**
 * Check if the Table of Content should be displayed according to options
 *
 * @since 1.2.0
 *
 * @return bool
 */
function basepress_show_table_of_content()
{
    return false;
}

/**
 * Renders the Table of Content title as specified in the options
 *
 * @since 1.2.0
 */
function basepress_table_of_content_title()
{
    return;
}

/**
 * Renders the Table of Content which is stored already in $GLOBALS
 */
function basepress_table_of_content()
{
    return;
}

/*
 * Restricted Content
 */
/**
 * Return the restricted content notice set in the options
 *
 * @since 1.6.0
 *
 * @return mixed
 */
function basepress_restricted_notice()
{
    return;
}

/**
 * Checks if the restricted content teaser should be shown
 *
 * @since 1.6.0
 *
 * @return mixed
 */
function basepress_show_restricted_teaser()
{
    return false;
}

/**
 * Return the restricted content teaser
 *
 * @since 1.6.0
 *
 * @updated 2.3.0
 *
 * @return mixed
 */
function basepress_article_teaser()
{
    return;
}

/**
 * Truncates HTML content to the length
 *
 * @since 2.3.0
 *
 * @param $content
 * @param $length
 * @return string
 */
function basepress_truncate_HTML( $content, $length )
{
    $trimmed_length = 0;
    $trimmed_content = '';
    //preg_match_all( '/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $content, $tags, PREG_SET_ORDER );
    preg_match_all(
        '/(<\\/?([\\w+]+)[^>]*>)?([^<>]*)/',
        $content,
        $tags,
        PREG_SET_ORDER
    );
    foreach ( $tags as $tag ) {
        //If the tag has no content continue
        $cleaned_tag = trim( $tag[3], "\n.\r.\n\r.\r\n." );
        
        if ( !empty($cleaned_tag) ) {
            $tag_stack = explode( ' ', $cleaned_tag );
            foreach ( $tag_stack as $index => $word ) {
                $trimmed_length += mb_strlen( $word );
                //If we are over the length break out
                if ( $trimmed_length > $length ) {
                    break 2;
                }
                $trimmed_content .= ( !$index ? $tag[1] : '' );
                $trimmed_content .= $word;
            }
        } else {
            $trimmed_content .= $tag[0];
        }
    
    }
    return $trimmed_content;
}

/**
 * Closes all HTML tags
 *
 * @since 2.3.0
 *
 * @param $html
 * @return string
 */
function basepress_close_HTML_tags( $html )
{
    preg_match_all( '#<(?!meta|img|br|hr|input\\b)\\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result );
    $openedtags = $result[1];
    preg_match_all( '#</([a-z]+)>#iU', $html, $result );
    $closedtags = $result[1];
    $len_opened = count( $openedtags );
    if ( count( $closedtags ) == $len_opened ) {
        return $html;
    }
    $openedtags = array_reverse( $openedtags );
    for ( $i = 0 ;  $i < $len_opened ;  $i++ ) {
        
        if ( !in_array( $openedtags[$i], $closedtags ) ) {
            $html .= '</' . $openedtags[$i] . '>';
        } else {
            unset( $closedtags[array_search( $openedtags[$i], $closedtags )] );
        }
    
    }
    return $html;
}

/**
 * Checks if the login form should be shown on restricted content
 * @since 1.6.0
 * @return mixed
 */
function basepress_show_restricted_login()
{
    return false;
}

/**
 * Loads the header according to settings
 *
 * @since 2.4.1
 *
 * @param $name
 */
function basepress_get_header( $name )
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    if ( !isset( $options['skip_header_footer'] ) ) {
        get_header( $name );
    }
}

/**
 * Loads of the footer according to settings
 *
 * @since 2.4.1
 *
 * @param $name
 */
function basepress_get_footer( $name )
{
    global  $basepress_utils ;
    $options = $basepress_utils->get_options();
    if ( !isset( $options['skip_header_footer'] ) ) {
        get_footer( $name );
    }
}

/**
 * Include deprecated functions
 */
require_once 'public-functions-deprecated.php';