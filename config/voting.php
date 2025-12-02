<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Voting Enabled
    |--------------------------------------------------------------------------
    |
    | This option controls whether voting is currently enabled. When set to
    | false, users can still upload doors and view results, but the voting
    | form will be hidden and vote submissions will be rejected.
    |
    */

    'enabled' => env('VOTING_ENABLED', false),

];
