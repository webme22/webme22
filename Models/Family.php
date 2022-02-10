<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class Family extends Eloquent
{
	protected $table = 'family';
	protected $guarded = [];
	protected $appends = ['family_plan_id', 'plan', 'payment', 'last_plan', 'first_father', 'right_first_father'];
	public function getFirstFatherAttribute(){
		return $this->users()->where(['parent_id'=> 'alpha'])->first();
	}
	public function getRightFirstFatherAttribute(){
		return $this->first_father->gender == 'Male';
	}
	public function scopeHasRightFirstFather($query){
		$query->whereHas('users', function ($q){
			$q->where(['parent_id'=>'alpha'])->where(['gender'=>'Male']);
		});
	}
	public function creator(){
		return $this->belongsTo('User', 'user_id', 'user_id');
	}
	public function topups(){
		return $this->hasMany('Topup', 'family_id', 'id');
	}
	public function country(){
		return $this->belongsTo('Country');
	}
	public function users(){
		return $this->hasMany('User', 'family_id', 'id')->orderBy('name');
	}
	public function usersOrderByDOB(){
		return $this->hasMany('User', 'family_id', 'id')->orderBy('date_of_birth');
	}
	public function assistants(){
		return $this->hasMany('User', 'family_id', 'id')->where(['role'=>'assistant'])->orderBy('name');
	}
	public function assistantsOrderedByDesc(){
		return $this->hasMany('User', 'family_id', 'id')->where(['role'=>'assistant'])->orderBy('user_id', 'desc');
	}
	public function media(){
		return $this->hasMany('FamilyMedia', 'family_id', 'id');
	}
	public function scopeCreatorVerified($query){
		$query->whereHas('creator', function(Illuminate\Database\Eloquent\Builder $q) {
			$q->where(['verified'=>true])->where('family_id', '!=', '-1');
		});
	}
	public function scopeActive($query){
		$query->where(['display'=>1]);
	}
	public function scopePopular($query){
		$query->where(['mostpopular'=>1]);
	}
	public function scopeTrial($query){
		$query->whereHas('plans', function (Illuminate\Database\Eloquent\Builder $q){
			$q->where(['price'=>0]);
		});
	}
	public function scopePremium($query){
		$query->whereHas('plans', function (Illuminate\Database\Eloquent\Builder $q){
			$q->where('price', '>', 0);
		})->whereHas('payments', function (Illuminate\Database\Eloquent\Builder $q){
			$q->where(['confirmed'=> true]);
		});
	}
	public function scopePlanValid($query){
		$query->where(function($q){$q->trial()->orWhere->premium();});
	}
	public function scopeValid($query){
		$query->creatorVerified()->PlanValid()->active();
	}
	public function plans(){
		return $this->belongsToMany('DBPlan', 'family_plans', 'family_id', 'plan_id')
				->whereDate('family_plans.end_date', '>=', date('Y-m-d'))
				->orderBy('family_plans.end_date', 'desc')
				->orderBy('family_plans.created_at', 'desc')
				->withPivot(['payment_id', 'start_date', 'end_date'])
				->limit(1);
	}
	public function all_plans(){
		return $this->belongsToMany('DBPlan', 'family_plans', 'family_id', 'plan_id')
				->orderBy('family_plans.end_date', 'desc')
				->orderBy('family_plans.created_at', 'desc')
				->withPivot(['payment_id', 'start_date', 'end_date']);
	}
	public function getPlanAttribute(){
		// return $this->plans()->confirmed()->first();
		// return $this->plans()->whereHas('payments', function(Illuminate\Database\Eloquent\Builder $q){
		// 	$q->where(['confirmed'=>true]);
		// })->first();
		$all_plans = $this->all_plans()->get();
		foreach($all_plans as $each){
			$payment_id = $each->pivot['payment_id'];
			if(Payment::where(['id'=>$payment_id, 'confirmed'=>true])->exists()){
				return $each;
			}
		}
	}
	public function getLastPlanAttribute(){
		return $this->all_plans()->first();
	}
	public function getFamilyPlanIdAttribute(){
		return $this->plan ? $this->plan->id : 0;
	}
	public function payments(){
		return $this->belongsToMany('Payment', 'family_plans', 'family_id', 'payment_id')
				->whereDate('family_plans.end_date', '>=', date('Y-m-d'))
				->orderBy('family_plans.end_date', 'desc')
				->orderBy('family_plans.created_at', 'desc')
				->withPivot('plan_id', 'end_date');
	}
	public function getPaymentAttribute(){
		return $this->plan ? $this->payments()->where(['payment.id'=>$this->plan->pivot->payment_id])->first(): null;
	}
}
