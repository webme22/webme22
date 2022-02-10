<div class="col-12 col-md-6 col-lg-3 mb-5 mb-lg-0 overflow-hidden joinus-plan <?=isset($active)?($active['id'] == $plan['id'] ? 'active-plan' : ''):''?> joinus-plan-<?=$plan['id']?> <?=($plan['popular'] == true)?'has-mostpopular':''?>" >
	<i class="active-hand fa fa-2x fa-hand-o-down mb-1"></i>
	<div class="plan ml-md-1 mr-md-1 h-100 w-100">
		<div class="head_npl <?=$plan['styling']?>_bg">
			<h3 class="plan-title_1" data-value="<?=$plan['id']?>"><?=db_trans($plan, 'name')?></h3>
		</div>
		<div class=" main_npl">
			<p class="plan-price" data-value="<?=$plan['price']?>"><span class="text-dark d-block rtl-dir-reverse"><?php
					if($plan['price'] == 0){
						echo trans('free_trial');
					} else {
						echo $plan['price'] . " " . trans("usd") . " " . trans('annually');
					}
					?></span></p>
			<ul class="plan-features">
				<li class="plan-feature text-center rtl-dir-reverse"><?php echo $plan['members'];?><br> <span class="plan-feature-name"><?=trans('family_mem_tree')?></span></li>
				<li class="plan-feature text-center rtl-dir-reverse"><?php echo $plan['media'];?> <?=trans('gb')?><br><span class="plan-feature-name"><?=trans('family_media_uploads')?></span></li>
				<?php if($plan['price'] == 0){ ?>
					<li class="plan-feature text-center rtl-dir-reverse">- <span class="plan-feature-name"><?=trans('for_3_months')?></span></li>
				<?php } else {?>
				<?php if($plan['popular'] == true){ ?>
					<li class="plan-feature text-center rtl-dir-reverse">-
                        <span class="plan-feature-name">
                            <a href="faq.php?tab=profile&q=MPF" target="_blank"><?=trans('join_most_pop')?></a>
                        </span></li>
					<?php } else {?>
						<li class="plan-feature text-center rtl-dir-reverse"><br></li>
					<?php } } ?>
			</ul>
		</div>
	</div>
</div>
