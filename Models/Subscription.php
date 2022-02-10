<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class Subscription extends Eloquent
{
    protected $fillable = ['name', 'email', 'date'];
}
