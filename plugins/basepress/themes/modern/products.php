<?php
/*
 * This template shows the list of Knowledge Bases
 * It is called from the shortcode
 */


//Get the Knowledge Base objects
$bpkb_knowledge_bases = basepress_kbs();

?>
<div class="bpress-wrap">
	<div class="bpress-content-wrap">
		<div class="bpress-grid" data-cols="<?php basepress_kb_cols(); ?>">

		<?php foreach ( $bpkb_knowledge_bases as $bpkb_knowledge_base ) : ?>
			<div class="bpress-col bpress-col-<?php basepress_kb_cols(); ?>">
				<div class="bpress-product bpress-kb fix-height">
					<a class="bpress-product-link bpress-kb-link" href="<?php echo $bpkb_knowledge_base->permalink; ?>">
						<img class="bpress-product-image bpress-kb-image" src="<?php echo $bpkb_knowledge_base->image->url; ?>">
						<h3 class="bpress-product-title bpress-kb-title"><?php echo $bpkb_knowledge_base->name; ?></h3>

						<?php if ( '' != $bpkb_knowledge_base->description ) : ?>
							<p class="bpress-product-description bpress-kb-description"><?php echo $bpkb_knowledge_base->description; ?></p>
						<?php endif; ?>

						<button class="bpress-btn bpress-btn-product bpress-btn-kb" href="<?php echo $bpkb_knowledge_base->permalink; ?>"><?php echo basepress_choose_kb_btn_text(); ?></button>
					</a>
				</div>
			</div>
		<?php endforeach; ?>

		</div>
	</div>
</div>
