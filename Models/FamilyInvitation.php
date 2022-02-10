<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class FamilyInvitation extends Eloquent
{
	protected $table = "familyInvitations";
	protected $guarded = [];

	public function scopeToday($query)
	{
		return $query->where('date', 'like', '%'. date('Y-m-d') .'%');
	}
}
