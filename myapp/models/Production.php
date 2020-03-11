<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\Redirect;
class Production extends Eloquent
{
    protected $table = 'production';

    protected $fillable = ['user_id', 'start', 'amount', 'end', 'created_by'];

    public static function add($request) {
        self::create([
            'user_id' => $request['user_id'],
            'amount' => $request['amount'],
            'start' => $request['start'],
            'end' => $request['end'],
            'created_by' => Session::get('user_id')
        ]);

        Session::flash('success', 'You have successfully added production');
        Redirect::to('sales-production.php?id='.$request['user_id']);
    }
}