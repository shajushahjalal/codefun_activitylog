<?php 

namespace CodeFun\Activitylog\Facade;

use CodeFun\Activitylog\App\Component\Classes\Activity as ActivityClass;
use Illuminate\Support\Facades\Facade;

class Activity extends Facade{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return ActivityClass::class;
    }
}