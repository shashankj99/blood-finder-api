<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'mobile' => 'required|numeric|digits:10',
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
}
