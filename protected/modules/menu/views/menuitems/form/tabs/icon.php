<div class="row"><?php echo CHtml::image($item->getImageUrl('preview'))?></div>
<div id="uploaded_files" class="row"></div>
<div class="row">
	<?php 
	$key=time();
	echo CHtml::hiddenField('picturesKey',$key);
	$this->widget('application.extensions.uploadify.EuploadifyWidget', 
	    array(
	        'name'=>'uploadme',
	        'options'=> array(
	            'script' => $this->createUrl('upload'), 
	            'auto' => true,
	            'multi' => false,
	            'scriptData' => array('key' => $key, 'PHPSESSID' => session_id()),
	            'buttonText' => 'Upload',
	            ),
	        'callbacks' => array( 
	           'onError' => 'function(evt,queueId,fileObj,errorObj){alert("Error: " + errorObj.type + "\nInfo: " + errorObj.info);}',
	           'onComplete' => 'function(){$.fancybox.resize;content=jQuery("#uploaded_files").load("'.$this->createUrl('upload').'",{"key":'.$key.'});}',
	        )
	    ));
	?>
</div>