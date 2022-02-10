<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class FamilyAccess extends Eloquent
{
	protected $table = "familyAccess";
	protected $fillable = ['family_id', 'name', 'email', 'accept', 'acceptedBy', 'expire_date', 'date'];
	public function scopePending($query){
		$query->where(['accept'=>2]);
	}
	public function family(){
		return $this->belongsTo('Family', 'family_id', 'id');
	}

	public function scopeToday($query)
	{
		return $query->where('date', 'like', '%'. date('Y-m-d') .'%');
	}
}
