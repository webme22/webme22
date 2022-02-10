<div class="row p-2 p-lg-4 bg-light">
	<h4 class="w-100 mb-5"><?=trans('make_transfer')?></h4>
	<div class="col-12">
		<table class="table table d-table table-bordered table-hover">
			<thead></thead>
			<tbody>
			<tr>
				<th>
					<?=trans('bank_info')?>
				</th>
				<td>
					BAHRAIN ISLAMIC BANK / DIPLOMATIC AREA
				</td>
			</tr>
			<tr>
				<th>
					<?=trans('account_number')?>
				</th>
				<td>
					100000185731
				</td>
			</tr>
			<tr>
				<th>
					<?=trans('iban')?>
				</th>
				<td>
					BH20BIBB00100000185731
				</td>
			</tr>

			</tbody>
			<tfoot>
			<tr class="table-secondary">
				<th> <?=trans('amount_of')?>: </th>
				<td>
					<b>
						<span class="payment-plan-price">
							<?php
							if(isset($_SESSION['family_id']) && ! empty($row)){
								echo $family_plan->upgrade_price($row);
							} else {
								echo $row['price'];
							}
							?>
						</span><?=" " . trans("usd")?>
					</b>
					<?=trans('for')?> 1 <?=trans('year_of')?>
					<?php if ($lang == 'ar') {?>
						<?=trans('plan')?> <b><u><span class="payment-plan-name"><?=db_trans($row, 'name')?></span></u></b>
					<?php } else { ?>
						<b><u><span class="payment-plan-name"><?=db_trans($row, 'name')?></span></u></b> <?=trans('plan')?>
					<?php } ?>
					<span class="most-popular-pricing d-none">
						<br>
						<b>
							<span class="mostpopular-plan-price">
								1<?=" " . trans("usd")?>
							</span>
						</b>
						<?=trans('for')?> 1 <?=trans('year_of')?> <u><?=trans('most_popular')?></u>
						<hr>
						<b>
							<span class="total-price">
								<span class="total-price-with-mostpopular"><?=($row['price']+1)?></span> <?=" " . trans("usd")?>
							</span>
							<?=trans('total')?>
						</b>
					</span>
				</td>
			</tr>
			</tfoot>
		</table>
		<div class="texts">
			<label><?=trans('transaction_number')?>: </label>
			<input type="text" placeholder="<?=trans('enter')?> <?=trans('transaction_number')?>" name="wire_transfer">
		</div>
	</div>
</div>
