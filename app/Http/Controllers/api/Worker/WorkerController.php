<?php

namespace App\Http\Controllers\api\Worker;

use App\Http\Controllers\Controller;
use App\Models\WorkArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function workArea(): JsonResponse
    {
        return response()->json(WorkArea::with('children')
            ->whereNull('parent_id')
            ->get());
    }
}
