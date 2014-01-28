<div id="htmlbanner">
    <?php foreach ($dataSet as $i=>$row): ?>
        <div id="slide_<?php echo $row->id; ?>" class="banner_slide" style="display:<?php echo ($i==0)?'block':'none';?>;">
	        <img class="slide_picture" src="<?php echo $row->getImageUrl('small'); ?>" alt="<?php echo $row->title; ?>" />
		        <div class="slide_right">        
	            <h3><?php echo $row->title; ?></h3>
	            <div class="slide_content"><?php echo $row->description;?></div> 
	            <div class="slide_buttons">
	            <?php for($j=0; $j<count($dataSet);$j++):?>
	            <a href="#"><img src="<?php echo Yii::app()->theme->baseUrl?>/img/icons/<?php echo $j==$i?'red':'grey'?>_btn.gif" alt="Показать слайд №<?php echo $j; ?>"/></a> 
	            <?php endfor; ?>
            </div>
            </div>
            <div class="clear"></div>      
        </div>
    <?php endforeach;?>
</div>
