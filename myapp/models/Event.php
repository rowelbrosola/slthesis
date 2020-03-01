<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Eloquent
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'start_date', 'end_date'];
}
