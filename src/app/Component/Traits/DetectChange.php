<?php

namespace CodeFun\Activitylog\App\Component\Traits;

use App\Models\User;
use CodeFun\Activitylog\App\Models\ActivityLog;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait DetectChange{
    /**
     * Define Variables
     */
    protected $old_data;
    protected $updated_data;
    protected $event_name;
    protected $model;

    /**
     * Convert Ignore Changes to Lowercase Word
     */
    private function convertLowercase(){
        try{
            foreach($this->ignore_changes as $key => $ignore){
                $this->ignore_changes[$key] = strtolower($ignore);
            }
            return $this;
        }catch(Exception $e){
            $this->errorLog($e);
        }
    }

    /**
     * Detecact Change and Trigger Target Changes method.
     */
    private function detectChange(){
        
        try{
            if( !in_array("created", $this->ignore_changes) ){
                self::created(function($model){
                    $this->getModelData($model, "Created")->storeActivityLog();
                });
            }
            if( !in_array("updated", $this->ignore_changes) ){
                self::updated(function($model){ 
                    $this->getModelData($model, "Updated")->storeActivityLog();
                });
            }
            if( !in_array("deleted", $this->ignore_changes) ){
                self::deleted(function($model){ 
                    // dd(3);
                    $this->getModelData($model, "Deleted")->storeActivityLog();
                });
            }else{
                //
            }
            // dd(0);
        }catch(Exception $e){
            $this->errorLog($e);
        }
    }

    /**
     * Get Exception Message and Rewrite Into Log File
     */
    private function errorLog($e){
        Log::info(
            $e->getMessage() . ' On file ' . $e->getFile() . ':'.$e->getLine()
        );
    }

    

     /**
      * Assign Old & New Data
      */
    private function getModelData($model, $event_name = "Created"){
        $this->model = $model;
        if (method_exists(Model::class, 'getRawOriginal')) {
            // Laravel >7.0
            $this->old_data = $this->setRawAttributes($model->getRawOriginal());
        } else {
            // Laravel <7.0
            $this->old_data = $this->setRawAttributes($model->getOriginal());
        }
        $this->updated_data = $model->toArray();
        $this->event_name = $event_name;
        
        return $this;
    }
    
         
    
    /**
      * Get Authenticate User
    */
    public function getAuthenticateUser(){
        $authenticate_user = Auth::user();
        if($authenticate_user instanceof Model){
            return $authenticate_user;
        }
        elseif( empty($authenticate_user) ){
            return;
        }
        else{
            if( property_exists($authenticate_user, "associate_model") ){
                return $authenticate_user->associate_model::where("id", $authenticate_user->id)->first();
            }
            else{
                $authenticate_user = User::where("id", $authenticate_user->id)->first();
            }
        }
        return $authenticate_user;
    }
    
    /**
      * Store Activity Log Data
        */
    public function storeActivityLog(){

        $authenticate_user = $this->getAuthenticateUser();
        $description = $this->replacePlaceholders($this->getDescriptionForEvent($this->event_name));

        $activity = New ActivityLog();
        $activity->uuid             = Str::uuid();
        $activity->event_name       = $this->event_name;
        $activity->description      = $description;
        $activity->tableable_type   = $this->model->getMorphClass();
        $activity->tableable_id     = $this->model->id;
        
        if( !empty($authenticate_user) ){
            $activity->causerable_type  = $authenticate_user->getMorphClass();
            $activity->causerable_id    = $authenticate_user->id;
        }
        $activity->old_data         = $this->old_data;
        $activity->updated_data     = $this->updated_data;
        $activity->save();
    }
    
    /**
     * Replace Text
     */
    protected function replacePlaceholders(string $description): string
    {
        return preg_replace_callback('/:[a-z0-9._-]+/i', function ($match) {
            $match = $match[0];
            return $match;
        }, $description);
    }


}