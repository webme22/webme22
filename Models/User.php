<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class User extends Eloquent
{

	protected $guarded = [];
	protected $primaryKey = "user_id";
	protected  $casts = [
			'verified'=> 'boolean'
	];
	protected $attributes = [
			'verified' => false
	];
	protected $hidden = ['user_password'];
	public function scopeActive($query){
		$query->where(['display'=>1]);
	}
	public function scopeNotactive($query){
		$query->where(['display'=>0]);
	}
	public function scopeVerified($query){
		$query->where(['verified'=>true]);
	}
	public function scopeNotVerified($query){
		$query->where(['verified'=>false]);
	}
	public function scopeMale($query){
		$query->where(['gender'=>'Male']);
	}
	public function scopeFemale($query){
		$query->where(['gender'=>'Female']);
	}
	public function scopeMember($query){
		$query->where('member', '!=', '0');
	}
	public function scopeResponsible($query){
		$query->where('role', '!=', 'user');
	}
	public function family(){
		return $this->belongsTo('Family', 'family_id', 'id');
	}
	public function country(){
		return $this->belongsTo('Country');
	}
	public function scopeCreator($query){
        $query->where('role', '!=', 'user')->where('role', '!=', 'assistant');
    }
	public function scopeAssistantOnly($query){
        $query->where(['role' => 'assistant'])->where(['member' => 0]);
    }
	public function scopeMemberOnly($query){
        $query->where(['role' => 'user']);
    }
	public function scopeMemberAndAssistant($query){
        $query->where(['role' => 'assistant'])->where(['member' => 1]);
    }
	public function scopeToday($query)
	{
		return $query->where('date', 'like', '%'. date('Y-m-d') .'%');
	}

}
