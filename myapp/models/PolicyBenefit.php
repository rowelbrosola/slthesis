<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class PolicyBenefit extends Eloquent
{
    protected $fillable = ['policy_id', 'benefits_id', 'user_id'];
}