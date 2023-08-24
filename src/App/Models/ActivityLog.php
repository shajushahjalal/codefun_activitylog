<?php

namespace CodeFun\Activitylog\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $casts = [
        "old_data"          => "array",
        "updated_data"      => "array",
    ];

    public function causer(){
        return $this->morphTo(__FUNCTION__, "causerable_type", "causerable_id");
    }

    public function tableable(){
        return $this->morphTo(__FUNCTION__, "tableable_type", "tableable_id");
    }
}
