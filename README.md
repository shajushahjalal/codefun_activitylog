# About Laravel CodeFun Activity Log

* This package will automatically _**Observe**_ your Model class and track _**every**_ data manupulation history like create, update, delete.
* It will automatically store both record _**(Previous record and Current Record)**_. Also it can show the diffrences between previous and updated record of Model information along with user record of who manupulated the data.

# Installation Process

* ```composer require codefun/activitylog```

# Run this migration command 
* ```php artisan migrate```

* Optionally use: ```php artisan vendor:publish --tag=codefun_activity``` 

## Before Laravel 5.7 

Add the following into your _**providers**_ array on ```config\app.php```:

* ```CodeFun\Activitylog\App\Providers\ActivityServiceProvider```

then add This alias into _**alias**_ array on ```config\app.php```:

* ```"Activity" => CodeFun\Activitylog\Facade\Activity::class```

## Not necessary from Laravel 5.7 onwards

# Publish Resource File

* ```php artisan vendor:publish --tag=codefun_activity``` 

By default codefun provides a basic blade file with bootstrap(v5) for viewing Activity Log and Log Details. 
You can to customize this blade page design by publishing the blade file.
It can be located at ```resources/views/vendor/codefun/```

# How To Use?

Go to your Model and use the trait file: 
```
use CodeFun\Activitylog\App\Component\Traits\ModelActivity;
class AnyModel extends Model
{
    use ModelActivity;
}
```
## If you want to _**set custom message**_ while data manipulation, override this method inside your model as shown:
```
use CodeFun\Activitylog\App\Component\Traits\ModelActivity;
class AnyModel extends Model
{
    use ModelActivity;

    public function getDescriptionForEvent($event_name) : string{
        return "Information has been ". $event_name; 
    }
}
```


