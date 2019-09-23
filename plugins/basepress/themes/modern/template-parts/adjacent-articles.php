<?php
/*
 *	This is the template that renders the previous and next articles
 *
 */

if ( basepress_show_adjacent_articles() ) {

	//Get Previous and Next articles
	$bpkb_prev_article = basepress_prev_article();
	$bpkb_next_article = basepress_next_article();
	$bpkb_show_icon = basepress_show_post_icon();
	$bpkb_post_class = $bpkb_show_icon ? ' show-icon' : '';
	$bpkb_grid_align = $bpkb_next_article && ! $bpkb_prev_article ? ' bpress-align-right' : '';

	if ( $bpkb_prev_article || $bpkb_next_article ) { ?>

		<div class="bpress-grid<?php echo $bpkb_grid_align; ?>">

			<?php
			if ( $bpkb_prev_article ) {
				$bpkb_prev_link = get_permalink( $bpkb_prev_article->ID );
			?>
			<div class="bpress-col bpress-col-2">
				<div class="bpress-prev-post">
					<span class="bpress-adjacent-title"><?php echo basepress_prev_article_text(); ?></span>

					<div class="bpress-adjacent-post<?php echo $bpkb_post_class; ?>">
						<?php if ( basepress_show_post_icon() ) { ?>
							<span class="bp-icon <?php echo $bpkb_prev_article->icon; ?>"></span>
						<?php } ?>
						<h4>
							<a href="<?php echo $bpkb_prev_link; ?>"><?php echo $bpkb_prev_article->post_title; ?></a>
						</h4>
					</div>
				</div>
			</div>
			<?php } ?>




		<?php
		if ( $bpkb_next_article ) {
			$bpkb_next_link = get_permalink( $bpkb_next_article->ID );
		?>
		<div class="bpress-col bpress-col-2">
			<div class="bpress-next-post">
				<span class="bpress-adjacent-title"><?php echo basepress_next_article_text(); ?></span>

				<div class="bpress-adjacent-post<?php echo $bpkb_post_class; ?>">
					<?php if ( basepress_show_post_icon() ) { ?>
						<span class="bp-icon <?php echo $bpkb_next_article->icon; ?>"></span>
					<?php } ?>
					<h4>
						<a href="<?php echo $bpkb_next_link; ?>"><?php echo $bpkb_next_article->post_title; ?></a>
					</h4>
				</div>
			</div>
		</div>
		<?php } ?>

	</div>
	<?php } ?>

<?php } ?>
