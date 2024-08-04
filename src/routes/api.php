<?php

use CodeFun\Activitylog\App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::prefix("api/activity-log")->group(function(){
    Route::get("/list", [ActivityLogController::class, "activityLogList"]);
    Route::get("/view/{uuid}", [ActivityLogController::class, "showActivityDetails"]);
});