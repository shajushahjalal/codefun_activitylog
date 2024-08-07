<?php

namespace CodeFun\Activitylog\App\Component\Traits;

use Exception;

trait CompareChange{
    /**
    * Get Text Diffrent
    * @return String
    */
    public function difference($old_data, $new_data){
        try{
            $old_data = $this->arrayToString($old_data, ", ");
            $new_data = $this->arrayToString($new_data, ", ");
            
            $from_start = strspn($old_data ^ $new_data, "\0");    
            $from_end = strspn(strrev($old_data) ^ strrev($new_data), "\0");
        
            $old_data_end = strlen($old_data) - $from_end;
            $new_data_end = strlen($new_data) - $from_end;
        
            $start = substr($new_data, 0, $from_start);
            $end = substr($new_data, $new_data_end);
            $new_data_diff = substr($new_data, $from_start, $new_data_end - $from_start); 
            $old_data_diff = substr($old_data, $from_start, $old_data_end - $from_start);
      
            return "$start<del style='background-color:#ffcccc'>$old_data_diff</del><ins style='background-color:#ccffcc'>$new_data_diff</ins>$end"; 
        }catch(Exception $e){
            return "<ins style='background-color:#ccffcc'>$new_data</ins>";
        }
    }

    protected function arrayToString($data, $seperator = ",",){
        if( !is_array($data) ){
            return $data;
        }
        $data = implode($seperator, $data);
        return $this->arrayToString($data);
    }
}