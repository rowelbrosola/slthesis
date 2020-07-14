<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\Redirect;

class People extends Eloquent
{
    protected $table = 'people';

    protected $fillable = ['firstname', 'middlename', 'lastname', 'address', 'birthdate', 'status'];

    const FOLLOW_UP = 'follow_up';
    const PROSPECT = 'prospect';

    public static function followUps() {
        $follow_ups = People::where('status', self::FOLLOW_UP)->get();
        return $follow_ups;
    }

    public static function prospects() {
        $prospects = People::where('status', self::PROSPECT)->get();
        return $prospects;
    }

    public static function add($request) {
        if (!empty($request['firstname']) &&
            !empty($request['middlename']) &&
            !empty($request['lastname']) &&
            !empty($request['address'])  &&
            !empty($request['birthdate'])
        ) {
            People::create([
                'firstname' => $request['firstname'],
                'middlename' => $request['middlename'],
                'lastname' => $request['lastname'],
                'address' => $request['address'],
                'birthdate' => date('Y-m-d', strtotime($request['birthdate'])),
                'status' => $request['status']
            ]);
    
            Session:: flash('success', 'Succesfully added!');
        } else {
            Session:: flash('error', 'Error encountred! Please try again.');
        }
        Redirect::to('index.php');
    }

    public static function followUpsThisMonth() {
        $currentMonth = date('m');
        $follow_ups = People::whereRaw('MONTH(created_at) = ?',[$currentMonth])->where('status', 'follow_up')->get();

        return $follow_ups->count();
    }

    public static function prospectsThisMonth() {
        $currentMonth = date('m');
        $prospects = People::whereRaw('MONTH(created_at) = ?',[$currentMonth])->where('status', 'prospect')->get();

        return $prospects->count();
    }

}