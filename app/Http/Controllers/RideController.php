<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AccountType;
use App\Http\Requests\StoreRideRequest;
use App\Http\Resources\RideResource;
use App\Models\Account;
use App\Models\Resident;
use App\Models\Ride;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RideController extends Controller
{

    /**
     * @param Account $account
     *
     * @return JsonResponse
     */
    public function index(Account $account): JsonResponse
    {
        $rides = Ride::query();

        if ($account->type === AccountType::TAXI) {
            $rides->whereHas('resident', function (Builder $query) use ($account) {
                $query->where('area', '=', $account->area);
            });
        }

        return RideResource::collection($rides->get())
            ->response();
    }

    /**
     * @param StoreRideRequest $request
     *
     * @return RideResource
     * @throws ValidationException
     */
    public function store(StoreRideRequest $request): RideResource
    {
        $validated = $request->validated();

        $resident = Resident::find($validated['resident_id']);
        $decision = $resident->decision;

        if (!$decision->is_active) {
            throw ValidationException::withMessages([
                'resident_id' => 'Ride can not be booked. The decision of resident ' . $resident->name . ' is not active.',
            ]);
        }

        if (($balance = $decision->balance) < $validated['distance']) {
            throw ValidationException::withMessages([
                'distance' => 'Ride can not be booked. The given distance exceeds budget. Current balance is ' . $balance . ' km.',
            ]);
        }

        $ride = new Ride();
        DB::transaction(function () use ($ride, $validated, $decision) {
            $ride->pickup_moment = $validated['pickup_moment'];
            $ride->resident_id = $validated['resident_id'];
            $ride->from = $validated['from'];
            $ride->to = $validated['to'];
            $ride->distance = $validated['distance'];
            $ride->save();
            $ride->refresh();
            $decision->balance = $decision->balance - $validated['distance'];
            $decision->save();
        });

        return new RideResource($ride);
    }
}
