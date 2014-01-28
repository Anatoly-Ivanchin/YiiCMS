<h1 id="pageTitle"><?php echo CHtml::encode($item->title); ?></h1>
<?php $this->widget('CatalogBreadCrumbs',array('model'=>$item)); ?>
<div id="si_<?php echo $item->id ?>" class="single_item">
    <div class="pictures">
	    <?php foreach ($item->pictures as $img): ?>
	        <a class="box" href="<?php echo $item->getImageUrl($img->filePath,'full');?>">
	        <?php echo CHtml::image($item->getImageUrl($img->filePath,'small'));?>
	        </a>
        <?php endforeach;?>
    </div>
    <div class="description">
        <div class="text"><?php echo ($item->getFullDescription());?></div>
        <div class="params">
            <?php foreach ($item->category->getParams() as $param): ?>
                <span class="catalog_param_name"><?php echo $param->name; ?>:</span>
                <span class="catalog_param_value"><?php echo $item->getParamValue($param,true) ?></span>
            <?php endforeach; ?>
            <?php if($this->getModule()->itemsPrice):?>
				<span class="catalog_price">цена:</span><span class="catalog_price_value"><?php echo $item->price; ?></span>
				<span class="catalog_oldprice">старая цена:</span><span class="catalog_oldprice_value"><?php echo $item->oldPrice; ?></span>
			<?php endif;?>
			<?php if($this->getModule()->itemsCount):?>
				<span class="catalog_count">количество:</span><span class="catalog_count_value"><?php echo $item->count; ?></span>
			<?php endif;?>
        </div>
    </div>
    <?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('id'=>$item->id),
			'urlPattern'=>'items/??',
			'actions'=>array('delete'=>false),
		)); ?>
    <?php echo CHtml::link('Вернуться к списку товаров',$item->category->getFullUrl(),array ('id'=>'back_link')) ?>
</div>