<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * AuthController constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Method to register a new user
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'mobile' => 'required|numeric|digits:10|unique:users',
                'password' => 'required|string'
            ]);

            $user = $this->authService->register($request);

            return response()->json([
                'status' => 200,
                'message' => $user
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to login the user
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'mobile' => 'required|numeric|digits:10',
                'password' => 'required|string'
            ]);

            $user = $this->authService->login($request);

            if ($user['status'] == 200)
                return response()->json([
                    'status' => 200,
                    'message' => $user['message'],
                    'access_token' => $user['access_token']
                ], 200);

            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['status']);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 404,
                'message' => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Method to verify the user
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyOTP(Request $request)
    {
        try {
            $this->validate($request, [
                'otp' => 'required|numeric|digits:6'
            ]);

            $verify = $this->authService->verifyOTP($request);

            return response()->json([
                'status' => 200,
                'message' => $verify['message'],
                'access_token' => $verify['access_token']
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 422,
                'errors' => $exception->errors()
            ], 422);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 404,
                'message' => $exception->getMessage()
            ], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
