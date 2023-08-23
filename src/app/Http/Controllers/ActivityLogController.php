<?php

namespace CodeFun\Activitylog\App\Http\Controllers;

use App\Http\Controllers\Controller;
use CodeFun\Activitylog\App\Models\ActivityLog;
use CodeFun\Activitylog\Facade\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ActivityLogController extends Controller
{
    /**
     * Show User Activity List
     */
    public function showActivityList(Request $request){
        $activities = ActivityLog::orderBy("id", "DESC")->paginate();
        $params = [
            "activities"    => $activities
        ];
        /**
         * Check View Files are Published Or Not
         * If View File are not published then Load Default Package's View 
         */
        if( !View::exists('vendor.codefun.activity.index') ){
            return view("Activity::activity.index", $params);
        }
        return view('vendor.codefun.activity.index', $params);
    }

    /**
     * View Activity Log Details
     */
    public function showActivity(Request $request){
        $activity = ActivityLog::findOrFail($request->uuid);
        $is_change = false;

        $changes_val = [];
        if( is_array($activity->old_data) && count($activity->old_data) > 0 ){
            foreach($activity->old_data as $key => $value){
                if($value !== $activity->updated_data[$key] ){
                    $changes_val[$key] = Activity::difference($value, $activity->updated_data[$key]);
                    $is_change = true;
                }
            }
        }

        $params = [
            "activity"  => $activity,
            "is_change" => $is_change,
            "update"    => $changes_val,
        ];

        if( !View::exists('vendor.codefun.activity.view') ){
            return view("Activity::activity.view", $params);
        }
        return view('vendor.codefun.activity.view', $params);
    }
}
