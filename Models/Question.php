<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class Question extends Eloquent
{
	protected $guarded = [];
	protected $table = "questions_and_answers";
}
