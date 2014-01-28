<div class="simple">
<?php echo CHtml::activeLabelEx($model,'allowTags'); ?>
<?php echo CHtml::activeCheckBox($model,'allowTags'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'allowComments'); ?>
<?php echo CHtml::activeCheckBox($model,'allowComments'); ?>
</div>
<?php if($this->getModule()->galleryMode):?>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'isGallery'); ?>
<?php echo CHtml::activeCheckBox($model,'isGallery'); ?>
</div>
<?php endif;?>