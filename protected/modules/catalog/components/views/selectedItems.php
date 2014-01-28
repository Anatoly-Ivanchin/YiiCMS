<div id="catalog_selected_items">
	<?php foreach ($dataSet as $i=>$row): ?>
	<div id="csi_<?php echo $row->id; ?>" class="catalog_selected_item">
		<?php echo CHtml::link(CHtml::image($row->getSingleImageUrl('small')),$row->fullUrl); ?>
		<h2 class="catalog_header"><?php echo CHtml::link($row->title,$row->fullUrl); ?></h2>
		<div class="catalog_content"><?php echo $row->getAnotation()?></div>
	</div>
	<?php endforeach;?>
</div>
