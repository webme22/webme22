<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class FamilyJoinRequest extends Eloquent
{
	protected $table = "join_family_requests";
	protected $guarded = [];
	public function scopePending($query){
		$query->where(['status'=>2]);
	}

	public function scopeToday($query)
	{
		return $query->where('date', 'like', '%'. date('Y-m-d') .'%');
	}
}
