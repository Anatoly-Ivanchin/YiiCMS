<div id="last_news_<?php echo $block->url?> " class="last_news">
    <?php if($title!=''):?>
        <h2><?php echo $title; ?></h2>
    <?php endif;?>
    <div class="last_news_content">
	    <?php foreach ($dataSet as $row): ?>
	        <div id="n_<?php echo $row->id; ?>" class="news">
	            <?php echo CHtml::link(CHtml::tag('h3',array(),$row->title),array('/news/default/show','id'=>$row->id)); ?>
	            <span class="news_date"><?php echo date('d.m.y',$row->createTime); ?></span>
	            <div class="news_content"><?php echo $row->getAnotation();?></div>        
	        </div>
	    <?php endforeach;?>
	</div>
</div>
