<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class About extends Eloquent
{
	protected $table = "aboutPage";
	public function scopeActive($query){
		$query->where(['display'=>1]);
	}
}
