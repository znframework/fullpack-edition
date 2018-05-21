<?php return
[
    /*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    |
    | Contains settings about which routes to show the results based on URL 
    | requests.
    |
    | openController: Default boot controller.
    | openFunction: It is the default method of operation of the controller.
    | show404: Forwards the invalid request to the specified URI.
    | requestMethods: Which URI specifies which request methods are valid or 
    |                 invalid.
    | patternType: Regex type for route.
    | changeUri: Used to create a route. ['new regex' => 'old uri']
    |
    */

    'openController' => 'home',
    'openFunction'   => 'main',
    'show404'        => '',
    'requestMethods' =>
    [
        'page'            => '',
        'disallowMethods' => [],
        'allowMethods'    => []  
    ],
    'patternType'    => 'classic',
    'changeUri'      => []
];
