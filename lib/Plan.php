<?php
include_once (__DIR__."/../functions/helpers.php");
class Plan
{
	private $family_id;
	private $family;
	private $family_plan;
	private $plan;
	public $plan_id;
	function __construct($family_id)
	{
		$this->family_id = $family_id;
		$this->family = Family::find($this->family_id);
		$this->plan = $this->family->plan;
	}
	public function plan(){
		return $this->family->plan;
	}
	public function isTrial(){
		return $this->plan->price > 0 ? false : true;
	}
	public function current_plan(){
		return $this->family->plan;
	}
	public function upgrade_price(array $plan){
		if (in_array($plan, $this->upgradeable())){
			if($this->isTrial()){
				return $plan['price'];
			}
			else {
				return ceil($plan['price'] - ($this->remaining_days()*($this->plan['price']/365)));
			}
		}
		return false;
	}
	public function upgradeable(){
		$next_plans = [];
		if($this->family->plan) {
			$next_plans = DBPlan::where('price', '>', $this->family->plan->price)->get();
		}
		return empty($next_plans) || count($next_plans)  == 0 ? false : $next_plans->toArray();
	}
	public function renewable(){
		return $this->remaining_days() < 31 ? true : false;
	}
	public function renewable_plans (){
		$members = $this->usedMembers();
		$media = ceil($this->usedMedia() / 1000000 );
		return DBPlan::where([['price', '>', 0], ['members', '>=', $members], ['media', '>', $media]])->orderBy('price')->get();
	}
	public function remaining_days(){
		if ($this->plan){
			$now = time(); // or your date as well
			$your_date = strtotime($this->plan->pivot['end_date']);
			$datediff = $your_date - $now;
			return round($datediff / (60 * 60 * 24));
		}
		return -1;
	}
	public function plan_name(){
		return db_trans($this->plan, 'name');
	}
	public function isValid(){
		if ($this->remaining_days() > 0){
			if($this->plan['price'] > 0){
				$payment = $this->family->payment;
				if ($payment && !empty($payment)){
					if ($payment['confirmed'] == false){
						return false;
					}
				}
			}
			return true;
		}
		return false;
	}
	public function allowedMembers () {
		return $this->plan['members'];
	}
	public function usedMembers(){
		return $this->family->users()->member()->count();

	}
	public function usedMembersPercentage(){
		return ceil($this->usedMembers() / $this->allowedMembers() * 100);
	}
	public function total_media_topup(){
		return $this->family->topups()->whereHas('payment', function(Illuminate\Database\Eloquent\Builder $q){
			$q->where(['confirmed'=>true]);
		})->media()->sum('value');
	}
	public function total_nodes_topup(){
		return $this->family->topups()->whereHas('payment', function(Illuminate\Database\Eloquent\Builder $q){
			$q->where(['confirmed'=>true]);
		})->nodes()->sum('value');
	}
	function availableMembers(){
		return $this->allowedMembers() + $this->total_nodes_topup() - $this->usedMembers();
	}
	public function allowedMedia(){
		return $this->plan['media'];
	}
	public function usedMedia(){
		return $this->family->media()->sum('size');
	}
	public function usedMediaPercentage(){
		return $this->allowedMedia() > 0 ?ceil($this->usedMedia() / 1000000 / $this->allowedMedia() * 100): 0;
	}
	public function availableMedia(){
		return  ($this->allowedMedia() * 1000000) + ($this->total_media_topup() * 1000000) - $this->usedMedia();
	}
	public function getFamily(){
		return $this->family;
	}
}
