<?php echo CHtml::errorSummary($comment); ?>
<?php if(Yii::app()->user->getIsGuest()):?>
<div class="simple">
<?php echo CHtml::activeLabelEx($comment,'name'); ?>
<?php echo CHtml::activeTextField($comment,'name'); ?>
</div>
<div class="simple captcha">
<?php echo CHtml::activeLabelEx($comment,'captcha'); ?>
<div class="captchacode"><?php $this->widget('CCaptcha'); ?><?php echo CHtml::activeTextField($comment,'captcha'); ?></div>
</div>
<?php endif;?>
<div class="simple">
<?php echo CHtml::activeLabelEx($comment,'text'); ?>
<?php echo CHtml::activeTextArea($comment,'text'); ?>
</div>