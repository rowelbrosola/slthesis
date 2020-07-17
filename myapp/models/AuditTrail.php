<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;

class AuditTrail extends Eloquent
{
    protected $fillable = ['user_id', 'action'];

    public static function add($action) {
        self::create([
            'user_id' => Session::get('user_id'),
            'action' => $action
        ]);
    }

    public static function get($id) {
        return self::where('user_id', $id)->take(50)->get();
    }
}
