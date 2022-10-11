<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Slider\SliderResource;
use App\Http\Resources\Api\V1\Slider\SliderCollection;
use App\Models\Slider;
use Illuminate\Http\Request;

class SlidersController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->per_page ?: 8;
        $order = $request->query("order", "asc");
        $group = $request->query("group", "main_sliders");

        $sliders = Slider::detectLang()
            ->where(['published' => true, "group" => $group])
            ->orderBy('ordering', $order)
            ->paginate($per_page);

        return $this->respondWithResourceCollection(new SliderCollection($sliders));
    }

    public function show($slider_id)
    {
        $slider = Slider::findOrFail($slider_id);
        return $this->respondWithResource(new SliderResource($slider));
    }
}
