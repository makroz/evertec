<?php

use Illuminate\Support\Str;
return [

/*
|--------------------------------------------------------------------------
| Default Database Connection Name
|--------------------------------------------------------------------------
|
| Here you may specify which of the database connections below you wish
| to use as your default connection for all database work. Of course
| you may use many connections at once using the Database library.
|
*/

'login' => env('DNETIX_LOGIN', ''),
'trankey' => env('DNETIX_TRANKEY', ''),
'url' => env('DNETIX_URL', ''),
];