<?php

namespace CodeFun\Activitylog\App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use CodeFun\Activitylog\Facade\Activity;
use CodeFun\Activitylog\App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;

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
     * Show Activity Log List By APi Call
     */
    public function activityLogList(Request $request) {
        try{
            $activities = ActivityLog::with("causer", "tableable")->orderBy("id", "DESC")->paginate($request->page_size ?? 25);
            return response([
                "status"        => true,
                "message"       => "Activity list loaded successfully",
                "activities"    => $activities,
                "pagination"    => $this->getPagination($activities)
            ], 200);
        }catch(Exception $e){
            return response([
                "status"        => false,
                "message"       => $e->getMessage() . " on file " . $e->getFile() . ":" . $e->getLine(),
            ], 500);
        }
    }

    /**
     * View Activity Log Details
     */
    public function showActivity(Request $request){
        $activity = ActivityLog::where("uuid", $request->uuid)->firstOrFail();
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

    /**
     * Activity Details  Via API
     */
    public function showActivityDetails(Request $request){
        try{
            $activity = ActivityLog::with("causer", "tableable")
                ->where("uuid", $request->uuid)->firstOrFail();
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
                "status"    => true,
                "message"   => "Activity details loaded successfully",
                "activity"  => $activity,
                "is_change" => $is_change,
                "update"    => $changes_val,
            ];
            return response($params, 200);

        }catch(Exception $e){            
            return response([
                "status"    => false,
                "message"   => $e->getMessage() . " on file " . $e->getFile() . ":" . $e->getLine(),
            ], 500);
        }
    }

    /**
     * Generate Pagination
     */
    protected function getPagination($paginator){
        try{
            if($paginator instanceof LengthAwarePaginator){
                return [
                    "ItemPerPage"   => $paginator->perPage(),
                    "TotalItem"     => $paginator->total(),
                    "currentPage"   => $paginator->currentPage(),
                    "lastPage"      => $paginator->lastPage(),
                    "nextPage"      => $paginator->nextPageUrl(),
                    "previousPage"  => $paginator->previousPageUrl(),
                ];
            }
            return null;
        }
        catch(Exception $e){
            return null;
        }
    }
}
