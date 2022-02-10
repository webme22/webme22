<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Feature extends Eloquent
{
	protected $fillable = ['type', 'name', 'email', 'phone', 'message', 'date'];
}
