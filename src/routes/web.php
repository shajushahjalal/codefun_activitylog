<?php

use CodeFun\Activitylog\App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::prefix("activity-log")->name("activity_log.")->group(function(){
    Route::get("/", [ActivityLogController::class, "showActivityList"])->name('list');
    Route::get("{uuid}/view", [ActivityLogController::class, "showActivity"])->name('view');
});