<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class Message extends Eloquent
{
	protected $table = "messages";
	protected $fillable = ['type', 'client_name', 'client_email', 'content', 'viewed', 'reply', 'date'];
}
