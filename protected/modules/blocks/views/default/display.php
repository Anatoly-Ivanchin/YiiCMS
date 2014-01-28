<?php if($block->displayTitle): ?>
        <h2 class="block_title"><?php echo CHtml::encode($block->title); ?></h2>
<?php endif;?>
<div class="block_content"><?php echo $block->content; ?></div>

