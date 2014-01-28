<div id="exists_files" class="row">
<?php foreach ($item->getRelated(Items::IMAGES_RELATION) as $img):?>
<div>
<?php 
echo CHtml::image($item->getImageUrl($img->filePath,'preview'));
echo CHtml::activeHiddenField($img,'id');
echo CHtml::link(CHtml::image('/images/admin/actions/delete.png','Удалить'),'#',array());
?>
</div>
<?php endforeach; ?>
<script type="text/javascript">
$("#exists_files a").click(
		function(event){
			hidden=$(this).parent().find("input");
			newHidden=$("<input>",{
				"type":"hidden",
				"name":"Images[delete]["+hidden.val()+"]",
				"value":"1"
			});
			$(this).parent().replaceWith(newHidden);
			event.preventDefault();
		}
);
</script>
</div>
<div id="uploaded_files" class="row"></div>
<div class="row">
	<?php 
	$key=time();
	echo CHtml::hiddenField('picturesKey',$key);
	$this->widget('ext.EFineUploader.EFineUploader',array(
		'config'=>array(
			'request'=>array(
				'endpoint'=>$this->createUrl('upload'),
				'params'=>array('YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken,'key'=>$key),
			),
		)
	));?>
</div>