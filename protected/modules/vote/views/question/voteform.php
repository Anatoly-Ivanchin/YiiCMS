<h2><? echo $q->text; ?></h2>
<? echo CHtml::beginForm(); ?>
    <? echo CHtml::RadioButtonList('answer',0,$answers); ?>
    <? echo CHtml::hiddenField('question_form_submitted',$q->id); ?>
    <? echo CHtml::button('Голосовать',array('class'=>'button','id'=>'vote_button')); ?>
    <script type="text/javascript">
    <!--
        jQuery('#vote_button').live('click',function(){
            <? echo CHtml::ajax(
                array(
                    'url'=>array('/vote/question/vote'),
                    'update'=>'#vote_block_content',
                    'type'=>'post',
                )
            ); ?>
            return false;
        }); 
    //-->
    </script>
<? echo chtml::endForm(); ?>