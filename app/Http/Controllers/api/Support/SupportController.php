<?php

namespace App\Http\Controllers\api\Support;

use App\Http\Controllers\Controller;
use App\Http\Resources\Faq\FaqResource;
use App\Models\ContactInfo;
use App\Models\FAQ;
use App\Models\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Psy\Util\Json;

class SupportController extends Controller
{
    public function __construct(){}

    public function contactInfo(): JsonResponse
    {
        $data = ContactInfo::query()->first();

        return response()->json($data);
    }

    public function faqs(): AnonymousResourceCollection
    {
        $faqs = FAQ::query()->orderBy('sort_order')->get();

        return FaqResource::collection($faqs);
    }

    public function privacyAndPolicy(): JsonResponse
    {
        return response()->json(Term::query()->get());
    }
}
