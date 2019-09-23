<?php
/*
 * This template lists all top sections with a list style
 *
 */

//Get the sections object
$bpkb_sections = basepress_sections();
?>
<div class="bpress-grid" data-cols="<?php basepress_section_cols(); ?>">

	<?php
	//We can iterate through the sections
	foreach ( $bpkb_sections as $bpkb_section ) :
		?>

		<div class="bpress-col bpress-col-<?php basepress_section_cols(); ?>">
			<div class="bpress-section fix-height">

				<!-- Section Title -->
				<?php
				$bpkb_show_icon = basepress_show_section_icon();
				$bpkb_section_class = $bpkb_show_icon ? ' show-icon' : '';
				?>
				<div class="bpress-heading<?php echo $bpkb_section_class; ?>">
					<?php if ( $bpkb_show_icon ) { ?>
						<span aria-hidden="true" class="bpress-heading-icon <?php echo $bpkb_section->icon; ?> colored"></span>
					<?php } ?>
					<h2>
						<a href="<?php echo $bpkb_section->permalink; ?>"><?php echo $bpkb_section->name; ?></a>
					</h2>
				</div>

				<?php if ( basepress_show_section_post_count() ) { ?>
					<span class="bpress-post-count"><?php echo $bpkb_section->posts_count; ?></span>
				<?php } ?>

				<!-- Post list -->
				<ul class="bpress-section-list">
					<?php
					foreach ( $bpkb_section->posts as $bpkb_post ) :
						$bpkb_show_post_icon = basepress_show_post_icon();
						$bpkb_post_class = $bpkb_show_post_icon ? ' show-icon' : '';
						?>

						<li class="bpress-post-link">

							<div class="bpress-heading<?php echo $bpkb_post_class; ?>">
								<!-- Post icon -->
								<?php if ( $bpkb_show_post_icon ) { ?>
									<span aria-hidden="true" class="bpress-heading-icon <?php echo $bpkb_post->icon; ?>"></span>
								<?php } ?>

								<!-- Post permalink -->
								<a href="<?php echo get_the_permalink( $bpkb_post->ID ); ?>">
									<?php echo $bpkb_post->post_title; ?>
								</a>
							</div>
						</li>

					<?php endforeach; ?>

					<?php
					//Sub sections list
					foreach( $bpkb_section->subsections as $bpkb_subsection ) :
						?>
						<li class="bpress-post-link">
							<div class="bpress-heading show-icon">

								<!-- Sub-section icon -->
								<span aria-hidden="true" class="bpress-heading-icon <?php echo $bpkb_subsection->default_icon; ?> colored"></span>

								<!-- Sub-section permalink -->
								<a href="<?php echo $bpkb_subsection->permalink; ?>">
									<?php echo $bpkb_subsection->name; ?>
								</a>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>

				<!-- Section View All -->
				<a href="<?php echo $bpkb_section->permalink; ?>" class="bpress-viewall"><?php printf( _n( 'View %d article', 'View all %d articles', $bpkb_section->posts_count, 'basepress' ), $bpkb_section->posts_count ); ?></a>

			</div>
		</div>

	<?php endforeach; ?>

</div><!-- End grid -->
