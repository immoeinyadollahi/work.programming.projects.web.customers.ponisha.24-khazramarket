<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Gateway\GatewayCollection;
use App\Models\Gateway;
use Illuminate\Http\Request;

class GatewaysController extends Controller
{
    public function index()
    {
        $gateways = Gateway::active()->orderBy('ordering')->get();
        return $this->respondWithResourceCollection(new GatewayCollection($gateways));
    }
}
