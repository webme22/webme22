<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class Payment extends Eloquent
{
	protected $table = 'payment';
	protected $guarded = [];
	protected $casts = [
	    'confirmed' => 'boolean'
	];
}
