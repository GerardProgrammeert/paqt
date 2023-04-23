<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ResidentResource;
use App\Models\Resident;
use Illuminate\Http\JsonResponse;

class ResidentController extends Controller
{
    public function index(): JsonResponse
    {
        return ResidentResource::collection(Resident::all())->response();
    }
}
