<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\UserProfile;
use App\Session;

class Event extends Eloquent
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id', 'title', 'description', 'audience', 'start_date', 'end_date'];

    const ALL_UNITS = 'All Units';
    const MY_UNIT = 'My Unit Only';
    const ME = 'Only Me';

    public static function loadEvents() {
        // get unit of the poster
        $unit = self::select('user_profiles.unit_id')
            ->join('user_profiles', 'events.user_id', 'user_profiles.user_id')
            ->where('audience', self::MY_UNIT)
            ->first();

        // then we fetch all members of the unit of the poster
        $unit_members = UserProfile::select('user_id')->where('unit_id', $unit->unit_id)->get();
        // we then get all the user IDs of the member
        $members = [];
        foreach ($unit_members as $key => $value) {
            $members[] = $value->user_id;
        }

        // Fetch all unit and only me posts
        $events = self::where('audience', self::ALL_UNITS)
            ->orWhere(function ($query) {
                $query->where('audience', self::ME)
                    ->where('events.user_id', Session::get('user_id'));
            })
            ->get();
        // we check if the logged user is a member of the unit's poster
        $merge = [];
        if (array_search(Session::get('user_id'), $members) !== FALSE) {
            $merge = self::whereIn('user_id', $members)->where('audience', self::MY_UNIT)->get();

            if ($merge) {
                foreach ($merge as $key => $value) {
                    $events[] = $value;
                }
            }
        }

        return $events;
    }
}
