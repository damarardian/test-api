// api/migrate.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

// Load Laravel's autoload file
require __DIR__ . '/../vendor/autoload.php';

// Load the environment variables
require __DIR__ . '/../bootstrap/app.php';

// Define a handler function for Vercel
return function (Request $request) {
    try {
        Artisan::call('migrate', ["--force" => true]);
        return json_encode(['status' => 'success', 'message' => 'Migrations ran successfully']);
    } catch (\Exception $e) {
        return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
};
