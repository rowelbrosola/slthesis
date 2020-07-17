<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Session;
use App\Redirect;
use App\AuditTrail;

class People extends Eloquent
{
    use SoftDeletes;
    protected $table = 'people';

    protected $fillable = ['user_id', 'firstname', 'middlename', 'lastname', 'contact', 'address', 'birthdate', 'status'];

    const FOLLOW_UP = 'follow_up';
    const PROSPECT = 'prospect';

    public static function followUps() {
        $follow_ups = People::where('status', self::FOLLOW_UP)->where('user_id', Session::get('user_id'))->get();
        return $follow_ups;
    }

    public static function prospects() {
        $prospects = People::where('status', self::PROSPECT)->where('user_id', Session::get('user_id'))->get();
        return $prospects;
    }

    public static function add($request) {
        if (!empty($request['firstname']) &&
            !empty($request['middlename']) &&
            !empty($request['lastname']) &&
            !empty($request['contact']) &&
            !empty($request['address'])  &&
            !empty($request['birthdate'])
        ) {
            People::create([
                'user_id' => Session::get('user_id'),
                'firstname' => $request['firstname'],
                'middlename' => $request['middlename'],
                'contact' => $request['contact'],
                'lastname' => $request['lastname'],
                'address' => $request['address'],
                'birthdate' => date('Y-m-d', strtotime($request['birthdate'])),
                'status' => $request['status']
            ]);

            AuditTrail::add('Added '.$request['status']);
    
            Session:: flash('success', 'Succesfully added!');
        } else {
            Session:: flash('error', 'Fill all required fields! Please try again.');
        }
        Redirect::to('index.php');
    }

    public static function deleteRecord($request) {
        $id = $request['id'];
        $people = People::find($id);
        $people->delete();

        AuditTrail::add('Deleted record from dashboard');
        Session:: flash('success', 'Succesfully deleted!');
        Redirect::to('index.php');
    }

    public static function updateRecord($request) {
        $people = People::find($request['id']);
        $people->firstname = $request['firstname'];
        $people->middlename = $request['middlename'];
        $people->lastname = $request['lastname'];
        $people->contact = $request['contact'];
        $people->address = $request['address'];
        $people->birthdate = date('Y-m-d', strtotime($request['birthdate']));
        $people->status = $request['status'];
        $people->save();

        AuditTrail::add('Updated '.$request['status'].'record');
        Session:: flash('success', 'Succesfully updated!');
        Redirect::to('index.php');
    }

    public static function moveRecord($request) {
        $people = People::find($request['id']);
        $people->status = self::FOLLOW_UP;
        $people->save();

        AuditTrail::add('Moved prospect to follow up');
        Session:: flash('success', 'Succesfully moved to follow up!');
        Redirect::to('index.php');
    }

    public static function followUpsThisMonth() {
        $currentMonth = date('m');
        $follow_ups = People::whereRaw('MONTH(created_at) = ?',[$currentMonth])
            ->where('status', 'follow_up')
            ->where('user_id', Session::get('user_id'))
            ->get();

        return $follow_ups->count();
    }

    public static function prospectsThisMonth() {
        $currentMonth = date('m');
        $prospects = People::whereRaw('MONTH(created_at) = ?',[$currentMonth])
            ->where('status', 'prospect')
            ->where('user_id', Session::get('user_id'))
            ->get();

        return $prospects->count();
    }

}