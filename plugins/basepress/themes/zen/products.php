<?php
/*
 * This is BasePress template to show the list of Knowledge Bases
 * It is called from the shortcode
 */


//Get the Knowledge Base objects
$bpkb_knowledge_bases = basepress_kbs();

?>
<div class="bpress-wrap">
	<div class="bpress-grid">
	<?php foreach ( $bpkb_knowledge_bases as $bpkb_knowledge_base ) : ?>
		<div class="bpress-col bpress-col-<?php basepress_kb_cols(); ?>">
			<div class="bpress-card bpress-product bpress-kb fix-height">
				<a class="bpress-product-link bpress-kb-link" href="<?php echo $bpkb_knowledge_base->permalink; ?>">
					<img class="bpress-card-top-image" src="<?php echo $bpkb_knowledge_base->image->url; ?>">
					<h3 class="bpress-card-title"><?php echo $bpkb_knowledge_base->name; ?></h3>
					<?php if ( '' != $bpkb_knowledge_base->description ) : ?>
						<div class="bpress-card-body">
						<p><?php echo $bpkb_knowledge_base->description; ?></p>
						</div>
					<?php endif; ?>
					<span class="bpress-card-footer"><?php echo basepress_choose_kb_btn_text(); ?></span>
				</a>
			</div>
		</div>
	<?php endforeach; ?>
	</div>
</div>
