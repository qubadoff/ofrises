<?php

namespace App\Http\Controllers\api\Banner;

use App\Http\Controllers\Controller;
use App\Http\Resources\Banner\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BannerController extends Controller
{
    public function list(): AnonymousResourceCollection
    {
        $banners = Banner::query()->orderBy('created_at', 'desc')->get();
        return BannerResource::collection($banners);
    }
}
