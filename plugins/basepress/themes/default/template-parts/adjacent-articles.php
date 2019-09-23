<?php
/*
 *	This is the template that renders the previous and next articles
 *
 */

if ( basepress_show_adjacent_articles() ) {

	//Get Previous and Next articles
	$bpkb_prev_article = basepress_prev_article();
	$bpkb_next_article = basepress_next_article();

	if ( $bpkb_prev_article || $bpkb_next_article ) {

		echo '<div class="bpress-grid">';

		echo '<div class="bpress-col bpress-col-2">';
		echo '<div class="bpress-prev-post">';
		if ( $bpkb_prev_article ) {
			$bpkb_prev_link = get_permalink( $bpkb_prev_article->ID );
			echo '<a href="' . $bpkb_prev_link . '">';
			echo '<span class="bpress-adjacent-title">' . basepress_prev_article_text() . '</span>';
			echo '<h4>';
			if ( basepress_show_post_icon() ) {
				echo '<span class="bp-icon ' . $bpkb_prev_article->icon . '"></span>';
			}
			echo $bpkb_prev_article->post_title . '</h4>';
			echo '</a>';
		}
		echo '</div>';
		echo '</div>';

		echo '<div class="bpress-col bpress-col-2">';
		echo '<div class="bpress-next-post">';
		if ( $bpkb_next_article ) {
			$bpkb_next_link = get_permalink( $bpkb_next_article->ID );
			echo '<a href="' . $bpkb_next_link . '">';
			echo '<span class="bpress-adjacent-title">' . basepress_next_article_text() . '</span>';
			echo '<h4>';
			echo $bpkb_next_article->post_title;
			if ( basepress_show_post_icon() ) {
				echo '<span class="bp-icon ' . $bpkb_next_article->icon . '"></span>';
			}
			echo '</h4>';
			echo '</a>';
		}
		echo '</div>';
		echo '</div>';

		echo '</div>';
	}
}
