<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    public function __invoke()
    {
        $req = request();
        Artisan::call($req->input("command"), $req->input("params", []));
    }
}
