<h2><?php echo $this->pageTitle='Результаты поиска "'.implode(' ',$patterns).'"'; ?></h2>

<div class="content_center">
<div class="content_left">
<div class="content_right">

<?php foreach($items as $n=>$item): ?>
    <div id="ci_<?php echo $item->id ?>" class="catalog_item">
        <div class="catalog_item_left">
            <?php if($item->picture)
                      $path='/upload/image/t_'.$item->picture;
                  else
                      $path='/images/no_img.gif';
            echo CHtml::link(CHtml::image($path),$item->fullUrl);?>
        </div>
        <div class="catalog_item_right">
            <h2><?php echo CHtml::link(CHtml::encode($item->title),$item->fullUrl); ?></h2>
            <div class="catalog_item_cat">
	            Категория: <?php echo CHtml::link($item->category->title, $item->category->fullUrl); ?>
	        </div>
	        <div class="catalog_item_text">
	            <?php echo ($item->getAnotation());?>
            </div>
            <?php $this->renderPartial('_admin',array('item'=>$item)); ?>
            <div class="clear"></div>
        </div>
    </div>
<?php endforeach; ?>


<?php if(count($items)<1): ?>
<p class="noItems">По данному запросу совпадений не обнаруженно!</p>
<?php endif; ?>

<?php $this->widget('CLinkPager',array(
    'pages'=>$pages,
    'nextPageLabel'=>'>',
    'prevPageLabel'=>'<',
    'firstPageLabel'=>'первая',
    'lastPageLabel'=>'последняя',
    'header'=>'',
)); ?>

</div>
</div>
</div>