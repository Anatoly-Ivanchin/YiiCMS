<?php foreach ($category->getParams() as $param): ?>
<div class="simple">
<?php 
	$id='params_'.$param->id;
	$name='params['.$param->id.']';
	$val=$item->getParamValue($param,false,true);$val=$val[0]->value;
	echo CHtml::label($param->name.($param->unit?', '.$param->unit:''),$id);
	switch ($param->inputType)
	{
		case Params::INPUT_TYPE_CHECK:
			echo CHtml::checkBox($name, $val,array('uncheckValue'=>0));
			break;
		case Params::INPUT_TYPE_LIST: ?>
			<ul class="param_val_list">
			<?php foreach ($param->getValues($item->id) as $val):?>
			<li id="<?php echo $val->id;?>" name="<?php echo $name;?>" class="exist_param_value">
				<span><?php echo $val->value; ?></span>
				<a href="#" title="Удалить"><img src="/images/admin/actions/delete.png" alt="Удалить" /></a>
			</li>
			<?php  endforeach;?>
			</ul>
			<input class="param_id" type="hidden" value="<?php echo $param->id; ?>">
			<span class="new_param_val">
				<input type="text" class="new_param_val_field"/>
				<input type="button" value="Добавить" class="new_param_val_button" />
			</span>
			<?php break;
		default:
			echo CHtml::textField($name, $val);
	}	
?>
</div>
<?php endforeach;?>
<script type="text/javascript">
jQuery(".exist_param_value>a").click(function(event){
	event.preventDefault();
	parent=jQuery(this).parent();
	parent.replaceWith('<input type="hidden" name="'+parent.attr("name")+'[delete]['+parent.attr("id")+']" value="1"/>');
});
jQuery(".new_param_val_button").click(function(){
	container=jQuery(this).parents(".simple");
	val=container.find(".new_param_val_field").val();
	if(val=="")
		return;

	li=jQuery("<li class='exist_param_value'>");
	
	deleteButton=jQuery('<a href="#" title="Удалить"><img src="/images/admin/actions/delete.png" alt="Удалить" /></a>');
	deleteButton.click(function(event){
		event.preventDefault();
		parent=jQuery(this).parent();
		parent.remove();
	});

	param_id=container.find(".param_id").val();
	counter=Number(container.data("counter"));

	li.append("<span>"+val+"</span>")
		.append("<input type='hidden' name='params["+param_id+"][add]["+counter+"]' value='"+val+"'/>")
		.append(deleteButton);
	container.find(".param_val_list").append(li);
	container.data("counter",counter+1);
	container.find(".new_param_val_field").val("");
});
</script>
