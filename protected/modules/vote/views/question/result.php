<h2><? echo $q->text; ?></h2>
<div class="answers">
<? foreach ($q->answers as $a): ?>
<div class="answer">
    <span>
        <? echo $a->text; ?>
    </span>
    <div>
    <div class="percent"><? echo $a->getAbsolutePercent(); ?>%</div>
    <div class="vote_meter">
        <div class="indicator" style="width:<? echo $a->getPercent(); ?>%;"></div>
    </div>
    <div class="clear"></div>
    </div>
</div>
<? endforeach; ?>
</div>
<!-- <span>Всего проголосовало: <strong>(<? echo count($q->getVotesCount()); ?>)</strong></span> -->