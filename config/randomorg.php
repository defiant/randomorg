<?php

/*
 * Configuration file for Laravel's implementation of Random.org's JSON-RPC API.
 *
 * (c) Bobby <connect@devs.ng> [http://community.devs.ng]
 *
 */

return [

    /*
     * API key provided by Random.org
     */

    'apiKey' => env('RANDOM_ORG_API_KEY', '00000000-0000-0000-0000-000000000000'),
];
