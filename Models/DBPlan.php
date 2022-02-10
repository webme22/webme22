<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class DBPlan extends Eloquent
{
	protected $table = 'plans';
	protected $casts = [
	'popular' => 'boolean',
	'highlight' => 'boolean',
	];
	public function scopeActive($query){
		$query->where(['display'=>1]);
	}
	public function scopeHighlight($query){
		$query->where(['highlight'=>true]);
	}
	public function scopeConfirmed($query){
		$query->where(['price'=>0])->orWhereHas('payments', function ($q){
			$q->where(['confirmed'=>true]);
		});
	}
	public function payments(){
		return $this->belongsToMany('Payment', 'family_plans', 'plan_id', 'payment_id')
				->whereDate('family_plans.end_date', '>=', date('Y-m-d'))
				->orderBy('family_plans.end_date', 'desc')
				->orderBy('family_plans.created_at', 'desc')
				->withPivot('family_id', 'end_date');
	}
}
