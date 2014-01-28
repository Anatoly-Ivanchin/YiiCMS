<?php echo CHtml::beginForm('','post',array('id'=>'enroll','enctype'=>'application/x-www-form-urlencoded')); ?>
<?php echo CHtml::errorSummary($model); ?>
<div id="tip"><span class="required">*</span> &mdash; поля, обязательные для заполнения</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'lastName'); ?>
<?php echo CHtml::activeTextField($model, 'lastName'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'name'); ?>
<?php echo CHtml::activeTextField($model, 'name'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'middleName'); ?>
<?php echo CHtml::activeTextField($model, 'middleName'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'phone'); ?>
<?php echo CHtml::activeTextField($model, 'phone'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'email'); ?>
<?php echo CHtml::activeTextField($model, 'email'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'programmId'); ?>
<?php echo CHtml::activeDropDownList($model,'programmId', $model->getProgrammList()); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'schoolId'); ?>
<?php echo CHtml::activeDropDownList($model,'schoolId', $model->getSchoolsList()); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'daypartId'); ?>
<?php echo CHtml::activeDropDownList($model,'daypartId', $model->getDayPartsList()); ?>
</div>
<div id="enroll_ads" class="simple">
<?php echo CHtml::activeLabelEx($model,'adId'); ?>
<div class="radiogroup">
<?php echo CHtml::activeRadioButtonList($model, 'adId',$model->getAdsList(),array('id'=>'_ad')); ?>
</div>
</div>
<?php if(Yii::app()->getUser()->isGuest):?>
<div class="row captcha">
<?php echo CHtml::activeLabelEx($model,'captcha'); ?>
<div class="captchacode"><?php $this->widget('CCaptcha'); ?></div><?php echo CHtml::activeTextField($model,'captcha'); ?>
</div>
<?php endif;?>
<script>jQuery("#lang_list").trigger("change");</script>

<div class="simple action">
<?php $this->renderPartial('application.views.forms.buttonsets.savecancel');?>
</div>

<?php echo CHtml::endForm(); ?>
<!-- form -->