<?php

namespace CodeFun\Activitylog\App\Component\Traits;

use App\Models\User;
use CodeFun\Activitylog\App\Models\ActivityLog;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait ModelActivity{

    use DetectChange;
    /**
     * Ignore Model load Trigger
     * Accept target Changes Only For  Created | Updated | Deleted
     */
    protected $ignore_changes = [];


    /**
      * Default Description
      * Implement This Method into your Model To Customize Activity Description
      * @return String      
    */
    public function getDescriptionForEvent($event_name) : string{
        return "Information has been ". $event_name; 
    }



    /**
     * Auto Load Method
     */
    public static function bootModelActivity(){
        (new self())->convertLowercase()->detectChange();
    }

}