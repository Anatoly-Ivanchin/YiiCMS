
<div class="simple">
<?php echo CHtml::activeLabel($item,'picture'); ?>
<div>
<div id="uploaded_picture" style="margin: 0px;">
<?php echo CHtml::image($item->getImageUrl('small')); ?>
</div>
<?php
if($item->isNewRecord)
    $uploadUrlData=array();
else
    $uploadUrlData=array('id'=>$item->id);
$this->widget('application.extensions.uploadify.EuploadifyWidget', 
    array(
        'name'=>'uploadme',
        'options'=> array(
            //'uploader' => '/js/uploadify.swf',
            'script' => $this->createUrl('/catalog/items/upload',$uploadUrlData), 
            //'cancelImg' => '/css/img/logo.gif',
            'auto' => true,
            'multi' => false,
            'folder' => 'upload/img',
            'scriptData' => array('extraVar' => 1234, 'PHPSESSID' => session_id()),
            //'fileDesc' => 'Declaratiebestanden',
            //'fileExt' => '*.*',
            'buttonText' => 'Upload',
            ),
        'callbacks' => array( 
           'onError' => 'function(evt,queueId,fileObj,errorObj){alert("Error: " + errorObj.type + "\nInfo: " + errorObj.info);}',
           'onComplete' => 'function(){content=jQuery("#uploaded_picture").load("'.$this->createUrl('/catalog/items/picture',$uploadUrlData).'");}',
           'onCancel' => 'function(evt,queueId,fileObj,data){alert("Cancelled");}',
        )
    )); 
?>
</div>
</div>

