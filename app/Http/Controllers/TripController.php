<?php

namespace App\Http\Controllers;

use App\Http\Resources\TripResource;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'origin'           => 'required',
            'destination'      => 'required',
            'destination_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors occurred.',
                'errors'  => $validator->errors(),
            ]);
        }

        $user = $request->user();
        $trip = $user->trips()->create(
            $request->only([
                'origin',
                'destination',
                'destination_name',
            ])
        );

        $trip->load('user');

        return response()->json([
            'message' => 'Trip has been created.',
            'trip'    => new TripResource($trip),
        ], 201);
    }

    /**
     * @param Request $request
     * @param Trip $trip
     * @return JsonResponse
     */
    public function show(Request $request, Trip $trip): JsonResponse
    {
        // for passenger
        if ($request->user()->id === $trip->user->id) {
            return response()->json([
                'trip' => new TripResource($trip),
            ]);
        }

        // for driver
        if ($request->user()->driver->id === $trip->driver->id) {
            return response()->json([
                'trip' => new TripResource($trip),
            ]);
        }

        return response()->json([
            'message' => 'Cannot find this trip.'
        ], 404);
    }

    /**
     * @param Request $request
     * @param Trip $trip
     * @return JsonResponse
     */
    public function accept(Request $request, Trip $trip): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'driver_location' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors occurred',
                'errors'  => $validator->errors(),
            ]);
        }

        $trip->update([
            'driver_id'       => $request->user()->id,
            'driver_location' => $request->get('driver_location'),
        ]);

        $trip->load('driver.user');

        return response()->json([
            'data' => new TripResource($trip),
        ]);
    }

    /**
     * @param Request $request
     * @param Trip $trip
     * @return JsonResponse
     */
    public function start(Request $request, Trip $trip): JsonResponse
    {
        $trip->update([
            'is_started' => true,
        ]);

        $trip->load('driver.user');

        return response()->json([
            'data' => new TripResource($trip),
        ]);
    }

    /**
     * @param Request $request
     * @param Trip $trip
     * @return JsonResponse
     */
    public function end(Request $request, Trip $trip): JsonResponse
    {
        $trip->update([
            'is_completed' => true,
        ]);

        $trip->load('driver.user');

        return response()->json([
            'data' => new TripResource($trip),
        ]);
    }

    /**
     * @param Request $request
     * @param Trip $trip
     * @return JsonResponse
     */
    public function location(Request $request, Trip $trip): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'driver_location' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors occurred.',
                'errors'  => $validator->errors(),
            ]);
        }

        $trip->update($request->only('driver_location'));
        $trip->load('driver.user');

        return response()->json([
            'data' => new TripResource($trip),
        ]);
    }
}
