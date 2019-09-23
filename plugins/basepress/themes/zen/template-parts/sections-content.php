<?php
/*
 *	This template lists all sections for a product
 * 	or sub-sections for a section
 *
 */

//Get the sections object
$bpkb_sections = basepress_sections();
$bpkb_show_icon = basepress_show_section_icon();
$bpkb_section_class = $bpkb_show_icon ? ' show-icon' : '';
?>

<div class="bpress-grid">

	<?php
	//We can iterate through the sections
	foreach ( $bpkb_sections as $bpkb_section ) :
		?>

		<div class="bpress-section bpress-col bpress-col-<?php basepress_section_cols(); ?>">

			<div class="bpress-card bpress-section fix-height">

				<!-- Section Title -->
				<h2 class="bpress-card-header<?php echo $bpkb_section_class; ?>">
					<?php if ( $bpkb_show_icon ) { ?>
						<span aria-hidden="true" class="bp-icon <?php echo $bpkb_section->icon; ?>"></span>
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
				<ul class="bpress-section-list bpress-card-body">
					<?php
					foreach ( $bpkb_section->posts as $bpkb_article ) :
						$bpkb_show_post_icon = basepress_show_post_icon();
						$bpkb_post_class = $bpkb_show_post_icon ? ' show-icon' : '';
						?>

						<li class="bpress-post-link<?php echo $bpkb_post_class; ?>">

							<!-- Post icon -->
							<?php if ( $bpkb_show_post_icon ) { ?>
								<span aria-hidden="true" class="bp-icon <?php echo $bpkb_article->icon; ?>"></span>
							<?php } ?>

							<!-- Post permalink -->
							<a href="<?php echo get_the_permalink( $bpkb_article->ID ); ?>">

								<!-- Post title -->
								<?php echo $bpkb_article->post_title; ?>
							</a>
						</li>

					<?php endforeach; ?>

					<?php
					//Sub sections list
					foreach( $bpkb_section->subsections as $bpkb_subsection ) :
						?>
						<li class="bpress-post-link show-icon">

							<!-- Sub-section icon -->
							<span aria-hidden="true" class="bp-icon <?php echo $bpkb_subsection->default_icon; ?>"></span>

							<!-- Sub-section permalink -->
							<a href="<?php echo $bpkb_subsection->permalink; ?>">
								<?php echo $bpkb_subsection->name; ?>
							</a>

						</li>
					<?php endforeach; ?>
				</ul>

				<!-- Section View All -->

				<a href="<?php echo $bpkb_section->permalink; ?>">
					<span class="bpress-card-footer">
					<?php printf( _n( 'View %d article', 'View all %d articles', $bpkb_section->posts_count, 'basepress' ), $bpkb_section->posts_count ); ?>
					</span>
				</a>


			</div>
		</div><!-- End section -->

	<?php endforeach; ?>

</div><!-- End grid -->
