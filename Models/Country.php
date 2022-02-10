<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class Country extends Eloquent
{
	public function scopeActive($query){
		$query->where(['display'=>1]);
	}
}
