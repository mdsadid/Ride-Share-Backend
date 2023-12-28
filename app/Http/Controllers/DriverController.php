<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load('driver');

        return response()->json([
            'data' => new UserResource($user),
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'year'          => 'required|numeric|between:2010,2030',
            'make'          => 'required',
            'model'         => 'required',
            'color'         => 'required|alpha',
            'license_plate' => 'required',
            'name'          => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors occurred',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $user->update($request->only('name'));

        $user->driver()->updateOrCreate(
            $request->only([
                'year',
                'make',
                'model',
                'color',
                'license_plate',
            ])
        );

        $user->load('driver');

        return response()->json([
            'message' => 'Driver information has been updated',
            'data'    => new UserResource($user),
        ], 201);
    }
}
