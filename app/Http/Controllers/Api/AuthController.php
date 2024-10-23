<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * Register New User
     * @param App\Requests\RegisterRequest $request
     * @return JSONResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);

            if ($user) {
                return ResponseHelper::success(message: 'User has been registered successfully!', data: $user, statusCode: 201);
            }
            return ResponseHelper::error(message: 'Unable to register user! Please try again.', statusCode: 400);
        } catch (Exception $e) {
            \Log::error('Unable to register User : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to register user! Please try again. ' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Login User
     * @param App\Requests\LoginRequest $request
     * @return JSONResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            // if credentials are incorrect
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return ResponseHelper::error(message: 'Unable to login due to invalid credentials.', statusCode: 400);
            }
            $user = Auth::user();

            if($user->type == 1) {
                $token = $user->createToken('Super Admin API Token', ['supadmin'])->plainTextToken;
            } elseif($user->type == 2) {
                $token = $user->createToken('Admin API Token', ['admin'], now()->addMonths(6))->plainTextToken;
            } else {
                $token = $user->createToken('User API Token', ['user'], now()->addMonths(3))->plainTextToken;
            }
            // Create API Token
            // $token = $user->createToken('My API Token', ['*'], now()->addSecond(400))->plainTextToken;
            $authUser =  [
                'user' => $user,
                'token' => $token
            ];
            return ResponseHelper::success(message: 'You are logged in successfully!', data: $authUser, statusCode: 200);
        } catch (Exception $e) {
            \Log::error('Unable to login User : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to login user! Please try again. ' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function : Auth user data / profile data
     * @param NA
     * @return JSONResponse
     */
    public function userProfile()
    {
        try {
            $user = Auth::user();

            if ($user) {
                return ResponseHelper::success(message: 'User profile fetched successfully!', data: $user, statusCode: 200);
            }

            return ResponseHelper::error(message: 'Unable to fetch user data due to invalid token.', statusCode: 400);
        } catch (Exception $e) {
            \Log::error('Unable to fetch User Profile : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to fetch user profile! Please try again. ' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function : User Logout
     * @param NA
     * @return JSONResponse
     */
    public function userLogout()
    {
        try {
            $user = Auth::user();

            if ($user) {
                // // Revoke all tokens...
                // $user->tokens()->delete();
                $user->currentAccessToken()->delete();
                return ResponseHelper::success(message: 'User logout successfully!', statusCode: 200);
            }

            return ResponseHelper::error(message: 'Unable to logout user data due to invalid token.', statusCode: 400);
        } catch (Exception $e) {
            \Log::error('Unable to logout due to some exception : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to logout due to some exception! Please try again. ' . $e->getMessage(), statusCode: 500);
        }
    }
}
