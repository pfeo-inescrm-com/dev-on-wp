<?php
/*
*	This template lists all top sections with a list style
 *
 */

//Get the sections object
$bpkb_sections = basepress_sections();

?>
<div class="bpress-grid">

	<?php
	//We can iterate through the sections
	foreach ( $bpkb_sections as $bpkb_section ) :
		?>

		<div class="bpress-section bpress-col bpress-col-<?php basepress_section_cols(); ?>">

			<!-- Section Title -->
			<?php
			$bpkb_show_icon = basepress_show_section_icon();
			$bpkb_section_class = $bpkb_show_icon ? ' show-icon' : '';
			?>
			<h2 class="bpress-section-title<?php echo $bpkb_section_class; ?>">
				<?php if ( $bpkb_show_icon ) { ?>
					<span aria-hidden="true" class="bpress-section-icon <?php echo $bpkb_section->icon; ?>"></span>
				<?php } ?>

				<a href="<?php echo $bpkb_section->permalink; ?>">
					<?php echo $bpkb_section->name; ?>
					<!-- Posts count -->
					<?php if ( basepress_show_section_post_count() ) { ?>
						<span class="bpress-post-count">(<?php echo $bpkb_section->posts_count; ?>)</span>
					<?php } ?>
				</a>
			</h2>


			<!-- Post list -->
			<ul class="bpress-section-list">
				<?php
				foreach ( $bpkb_section->posts as $bpkb_article ) :

					$bpkb_show_post_icon = basepress_show_post_icon();
					$bpkb_post_class = $bpkb_show_post_icon ? ' show-icon' : '';
					?>

					<li class="bpress-post-link<?php echo $bpkb_post_class; ?>">

						<!-- Post permalink -->
						<a href="<?php echo get_the_permalink( $bpkb_article->ID ); ?>">

							<!-- Post icon -->
							<?php if ( $bpkb_show_post_icon ) { ?>
								<span aria-hidden="true" class="<?php echo $bpkb_article->icon; ?>"></span>
							<?php } ?>

							<!-- Post title -->
							<?php echo $bpkb_article->post_title; ?>
						</a>
					</li>

				<?php endforeach; ?>

				<?php
				//Sub-sections list
				foreach( $bpkb_section->subsections as $bpkb_subsection ) :
					?>
					<li class="bpress-post-link show-icon">
						<!-- Sub-section permalink -->
						<a href="<?php echo $bpkb_subsection->permalink; ?>">

							<!-- Sub-section icon -->
							<span aria-hidden="true" class="<?php echo $bpkb_subsection->default_icon; ?>"></span>

							<!-- Sub-section title -->
							<?php echo $bpkb_subsection->name; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<!-- Section View All -->
			<?php if ( $bpkb_section->posts_count > count( $bpkb_section->posts ) ) { ?>
				<br>
				<a href="<?php echo $bpkb_section->permalink; ?>" class="bpress-viewall">
					<?php printf( _n( 'View %d article', 'View all %d articles', $bpkb_section->posts_count, 'basepress' ), $bpkb_section->posts_count ); ?>
				</a>
			<?php } ?>

		</div><!-- End section -->

	<?php endforeach; ?>

</div><!-- End grid -->
