<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class AboutBox extends Eloquent
{
	protected $table = "aboutPageBoxes";
	public function scopeActive($query){
		$query->where(['display'=>1]);
	}
}
