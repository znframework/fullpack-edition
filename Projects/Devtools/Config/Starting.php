<?php return
[
    /*
    |--------------------------------------------------------------------------
    | Constructor Controllers
    |--------------------------------------------------------------------------
    | 
    | Controllers that will run before the system and before the controllers.
    |
    | Example: 
    | [
    |     'starting/starting:main', 'starting:otherMethod',
    |     'otherController/otherController:main', 'otherController:otherMethod'
    | ]
    |
    */

    'constructors' => ['initialize'],

    /*
    |--------------------------------------------------------------------------
    | Destructor Controllers
    |--------------------------------------------------------------------------
    |
    | It is the controllers that will go into the circuit after the 
    | controllers work.
    |
    */

    'destructors' => [],

    /*
    |--------------------------------------------------------------------------
    | Extract View Data
    |--------------------------------------------------------------------------
    |
    | It sends data to the controllers working under it with the View library.
    | Sent data is accessed with [$this] object.
    |
    */

    'extractViewData' => false,

    /*
    |--------------------------------------------------------------------------
    | View Name Type
    |--------------------------------------------------------------------------
    |
    | If the views are created in a certain standard, they are automatically 
    | loaded by the controllers. There are 2 options for this;
    |
    | directory: Views/controllerName/methodName.php
    |
    | file: Views/controllerName-methodName.php
    |
    */

    'viewNameType' => 'file',

    /*
    |--------------------------------------------------------------------------
    | Autoload
    |--------------------------------------------------------------------------
    |
    | Allows the files in the Starting/Autoload/ folder to be installed 
    | automatically.
    |
    */

    'autoload' =>
    [
        'status'    => true,
        'recursive' => false
    ]
];
