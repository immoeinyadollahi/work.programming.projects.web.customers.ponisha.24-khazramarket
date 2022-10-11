<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Banner\BannerResource;
use App\Http\Resources\Api\V1\Banner\BannerCollection;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->per_page ?: 8;
        $order = $request->query("order", "asc");
        $group = $request->query("group", "index_slider_banners");

        $sliders = Banner::detectLang()
            ->where(['published' => true, "group" => $group])
            ->orderBy('ordering', $order)
            ->paginate($per_page);

        return $this->respondWithResourceCollection(new BannerCollection($sliders));
    }

    public function show($banner_id)
    {
        $banner = Banner::findOrFail($banner_id);
        return $this->respondWithResource(new BannerResource($banner));
    }
}
