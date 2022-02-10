<div class="Sponser Sponser--<?=$plan['styling']?> position-relative">
    <h3 class="Sponser__header"><?=db_trans($plan, 'name')?></h3>
    <p class="plan-price"><span class="d-block rtl-dir-reverse"><?php
			if($plan['price'] == 0){
				echo trans('free_trial');
			} else {
				echo $plan['price'] . " " . trans("usd") . ' ' . trans('annually');
			}
			?></span></p>
    <br>
    <ul class="Sponser__advantages">
        <li><span class="bold"><?php echo $plan['members'];?></span> <?=trans('family_mem_tree')?></li>
        <li><span class="bold"><?php echo $plan['media'];?> <?=trans('gb')?></span> <?=trans('family_media_uploads')?></li>
		<?php if($plan['price'] == 0){ ?>
            <li class="bold"><?=trans('for_3_months')?></li>
		<?php } else if ($plan['popular']) {?>
            <li class="bold"><a href="<?=$siteUrl.$RELATIVE_PATH?>faq.php?tab=profile&q=MPF" target="_blank" class="text-light"><?=trans('join_most_pop')?></a></li>
		<?php } else {?>
            <li class="bold"></li>
		<?php } ?>
    </ul>
    <br>
    <div class="w-100 confirm-div text-center">
        <a class="Sponser__confirm d-inline-block" href="<?=isset($_SESSION['family_id']) ? 'upgrade_plan.php' : 'signup.php'?>?plan=<?=$plan['id']?>">
            <span><?=trans('register_now')?></span></a>
    </div>
</div>

