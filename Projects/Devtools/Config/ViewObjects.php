<?php return
[
    /*
    |--------------------------------------------------------------------------
    | Wizard
    |--------------------------------------------------------------------------
    |
    | The template wizard specifies what to compile.
    |
    */
    
    'wizard' =>
    [
        'keywords'  => true,
        'printable' => true,
        'functions' => true,
        'comments'  => true,
        'tags'      => true,
        'jsdata'    => false,
        'html'      => true
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Includes default settings for the paging view.
    |
    */

    'pagination' =>
    [
        'prevName'      => '<',
        'nextName'      => '>',
        'firstName'     => '<<',
        'lastName'      => '>>',

        'totalRows'     => 50,
        'start'         => NULL,
        'limit'         => 10,
        'countLinks'    => 10,
        'type'          => 'classic', // classic, ajax

        'class' =>
        [
            'current'   => '',
            'links'     => '',
            'prev'      => '',
            'next'      => '',
            'last'      => '',
            'first'     => ''
        ],

        'style' =>
        [
            'current'   => '',
            'links'     => '',
            'prev'      => '',
            'next'      => '',
            'last'      => '',
            'first'     => ''
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Captcha
    |--------------------------------------------------------------------------
    |
    | Includes default settings for the captcha.
    |
    */

    'captcha' =>
    [
        'text' =>
        [
            'length' => 6,
            'color'  => '255|255|255',
            'size'   => 10,
            'x'      => 65,
            'y'      => 13,
            'angle'  => 0,
            'ttf'    => []
        ],

        'background' =>
        [
            'color' => '80|80|80',
            'image' => []
        ],

        'border' =>
        [
            'status' => false,
            'color'  => '0|0|0'
        ],

        'size' =>
        [
            'width'  => 180,
            'height' => 40
        ],

        'grid' =>
        [
            'status' => true,
            'color'  => '50|50|50',
            'spaceX' => 12,
            'spaceY' => 4
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Terminal
    |--------------------------------------------------------------------------
    |
    | Includes default settings for the terminal.
    |
    */

    'terminal' =>
    [
    	'width' 		=> '800px',
    	'height' 		=> '350px',
    	'bgColor' 		=> '#000',
    	'barBgColor' 	=> '#222',
    	'textColor' 	=> '#ccc',
    	'textType' 		=> 'Consolas, monospace',
    	'textSize' 		=> '14px'
    ],

    /*
    |--------------------------------------------------------------------------
    | Datagrid
    |--------------------------------------------------------------------------
    |
    | Includes default settings for the datagrids.
    |
    */

    'dbgrid' =>
    [
        'styleElement' =>
        [
            //'#DBGRID_TABLE tr:nth-child(even)' => ['background' => '#E6F9FF'],
            //'#DBGRID_TABLE tr:nth-child(odd)'  => ['background' => '#FFF']
        ],

        'attributes'    =>
        [
            'table'         => ['class' => 'table table-bordered table-hover table-striped'],
            'editTables'    => [],
            'columns'       => [],
            'search'        => ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'add'           => ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'deleteSelected'=> ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'deleteAll'     => ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'save'          => ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'update'        => ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'delete'        => ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'edit'          => ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'listTables'    => [],
            'inputs'        =>
            [
                'text'      => ['style' => 'height:30px; color:#0085B2; border:solid 1px #ccc; text-indent:10px; border-radius:4px'],
                'textarea'  => ['style' => 'height:100px; width:300px; color:#0085B2; border:solid 1px #ccc; text-indent:10px; border-radius:4px'],
                'radio'     => [],
                'checkbox'  => [],
                'select'    => []
            ]
        ],

        'pagination' =>
        [
            'style' =>
            [
                'links' => 'color:#0085B2;
                            width:20px; height:20px;
                            text-align:center;
                            padding-top:4px;
                            display:inline-block;
                            background:white;
                            border:solid 1px #ddd;
                            border-radius: 4px;
                            -webkit-border-radius: 4px;
                            -moz-border-radius: 4px;
                            text-decoration:none;',

                'current' => 'font-weight:bold;'
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | ML Grid
    |--------------------------------------------------------------------------
    |
    | It edits the table created by the ML::table() method.
    |
    | styleElement: Used to give built-in style to the table.
    | attributes  : Used to add attributes to objects in the table.
    | pagination  : It arranges the pagination bar on the table.
    |
    */

    'mlgrid' =>
    [
        'styleElement' =>
        [
            #'#ML_TABLE tr:nth-child(even)' => ['background' => '#E6F9FF'],
            #'#ML_TABLE tr:nth-child(odd)'  => ['background' => '#FFF']
        ],
        'attributes'    =>
        [
            'table'   => ['class' => 'table table-bordered table-hover table-striped'],
            'add'     => ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'update'  => ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'delete'  => ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'clear'   => ['style' => 'height:30px; color:#0085B2; background:none; border:solid 1px #ccc; cursor:pointer; border-radius:4px'],
            'textbox' => ['style' => 'height:30px; color:#0085B2; border:solid 1px #ccc; text-indent:10px; border-radius:4px']
        ],
        'pagination' =>
        [
            'style' =>
            [
                'links' => '',

                'current' => 'font-weight:bold;'
            ],
            'class' => []
        ]
    ]
];
