<div id="vote_block">
    <h1>Опрос</h1>
    <div class="block-mini-head"></div>
    <div id="vote_block_content" class="block-mini-cont">
    </div>
    <div class="block-mini-foot"></div>
        <script type="text/javascript">
            $(document).ready(function () {
            <?php echo CHtml::ajax(array(
                  'update'=>'#vote_block_content',
                  'type'=>'post',
                  'url'=>Yii::app()->controller->createUrl('/vote/question/vote'),
            ));?>
            });
        </script>
</div>