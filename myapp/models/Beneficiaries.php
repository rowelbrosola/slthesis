<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiaries extends Eloquent
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'name', 'relationship', 'birthdate', 'designation'];
}
