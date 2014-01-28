<div class="simple">
<?php echo CHtml::activeLabelEx($item,'title'); ?>
<?php echo CHtml::activeTextField($item,'title',array('maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'description'); ?>
<?php echo CHtml::activeTextArea($item,'description'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'pattern'); ?>
<?php echo CHtml::activeTextField($item,'pattern',array('maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'inNewWindow'); ?>
<?php echo CHtml::activeCheckBox($item,'inNewWindow'); ?>
</div>

<script type="text/javascript">
<!--
function updateTitle(str){
	if(jQuery("#MenuItems_page").data("changed")==true){
		title=jQuery("#MenuItems_title");
		if(title.data("lastVal")==null && title.val()!=''){
			oldTitle=title.val();
			title.data("lastVal",oldTitle);
		} else
			title.val(str);
	}
}
function returnTitle(){
	lastVal=jQuery("#MenuItems_title").data("lastVal");
	jQuery("#MenuItems_title").val(lastVal);
}
function hideStaticAttr(){
	jQuery("#static_attr").hide().find("input").attr("disabled","disabled");
	jQuery("#MenuItems_page").trigger("change");
}
function hideModuleAttr(){
	jQuery("#module_attr").hide().find("input").attr("disabled","disabled");
	if(jQuery("#MenuItems_page").data("changed")==true)returnTitle();;
}
function showStaticAttr(){
	jQuery("#static_attr").show().find("input").removeAttr("disabled");
}
function showModuleAttr(){
	jQuery("#module_attr").show().find("input").removeAttr("disabled");
	jQuery("#MenuItems_page").data("changed",true);
}
//-->
</script>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'type'); ?>
<?php echo CHtml::activeDropDownList($item, 'type', $item->getTypeList(),array(
	'onchange'=>'switch(this.value){
		case "'.MenuItems::TYPE_MODULE_PAGE.'" :
			showModuleAttr();hideStaticAttr();break;
		case "'.MenuItems::TYPE_STATIC.'" :
			showStaticAttr();hideModuleAttr();break;
		default:
			hideStaticAttr();hideModuleAttr();break;
	}'
)); ?>
</div>
<div id="module_attr">
<hr>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'module'); ?>
<?php echo CHtml::activeDropDownList($item,'module', $this->getModule()->getModulesList(),
	array('onchange'=>'jQuery("#MenuItems_page").load("'.$this->createUrl('modulepages').'",{"mid":this.value},function(){jQuery(this).trigger("change")});')); ?>
</div>
<div id="module_pages" class="simple">
<?php echo CHtml::activeLabelEx($item,'page'); ?>
<?php echo CHtml::activedropDownList($item, 'page', $this->getModule()->getModuleMenuItems($item->module),
	array('onchange'=>'updateTitle(jQuery(this).children("option[value="+this.value+"]").html())')); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'withChildren'); ?>
<?php echo CHtml::activeCheckBox($item,'withChildren'); ?>
</div>
</div>
<div id="static_attr">
<hr>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'url'); ?>
<?php echo CHtml::activeTextField($item,'url',array('maxlength'=>128,'disabled'=>'disabled')); ?>
</div>
</div>
<?php echo CHtml::activeHiddenField($item, 'menuId');?>

<script type="text/javascript">
<!--
jQuery("#MenuItems_type").trigger("change");
//-->
</script>