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
        return BannerResource::collection(Banner::all());
    }
}
