<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginNeedsVerification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error occurred',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $user = User::query()->firstOrCreate([
                'phone' => $request->get('phone')
            ]);

            $user->notify(new LoginNeedsVerification());

            return response()->json([
                'message' => 'A login code has been sent'
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Could not process a user with that phone number',
                'error'   => $exception,
            ], 401);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone'      => 'required|numeric',
            'login_code' => 'required|numeric|between:111111,999999',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error occurred',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::query()->where('phone', '=', $request->get('phone'))
            ->where('login_code', '=', $request->get('login_code'))
            ->first();

        if ($user) {
            $user->update([
                'login_code' => null
            ]);

            return response()->json([
                'message' => 'Welcome!',
                'token'   => $user->createToken("API token of " . $request->get('phone'))->plainTextToken,
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid verification code'
        ], 401);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Goodbye!'
        ], 200);
    }
}
