<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

use Req;

class Activity extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activity_log';

    protected $primaryKey = 'action_id';

    protected $hidden = [
    'action_type',
    'action_user_id',
    'actor_type',
    'developer',
    'details',
    'user_agent',
    'ip_address',
    ];

    public function getCreatedAtAttribute($value) {

        return \Carbon\Carbon::parse($value)->toIso8601String();
    }

    public function getUpdatedAtAttribute($value) {

        return \Carbon\Carbon::parse($value)->toIso8601String();
    }

    /**
     * Get the user that the activity belongs to.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(config('auth.model'), 'user_id');
    }

    /**
     * Create an activity log entry.
     *
     * @param  mixed    $data
     * @return boolean
     */
    public static function log($data = [])
    {
        if (is_object($data))
            $data = (array) $data;

        if (is_string($data))
            $data = ['action' => $data];

        $activity = new static;

        if (config('log.auto_set_user_id'))
        {
            $user = \Auth::user();
            $activity->actor_user_id = isset($user->id) ? (int) $user->id : null;
        }

        if (isset($data['actor_user_id']) && is_string($data['actor_user_id']))
            $activity->actor_user_id = $data['actor_user_id'];

        $activity->actor_type   = isset($data['actor_type'])   ? $data['actor_type']   : null;
        $activity->action_user_id   = isset($data['action_user_id'])   ? $data['action_user_id']   : null;
        $activity->action_id   = isset($data['action_id'])   ? $data['action_id']   : null;
        $activity->action_type = isset($data['action_type']) ? $data['action_type'] : null;
        $activity->action       = isset($data['action'])      ? $data['action']      : null;
        $activity->description  = isset($data['description']) ? $data['description'] : null;
        $activity->details      = isset($data['details'])     ? $data['details']     : null;
        $activity->source      = isset($data['source'])     ? $data['source']     : 'Web';


        if (isset($data['deleted']) && $data['deleted'])
            $activity->action = "Delete";

        //set developer flag
        $activity->developer  = !is_null(Session::get('developer')) ? true : false;
        $activity->ip_address = Req::ip();
        $activity->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'No UserAgent';
        $activity->save();

        return true;
    }


}