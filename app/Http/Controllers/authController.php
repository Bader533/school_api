<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class authController extends Controller
{
    
    public function login(Request $request)
    {
        $validator = Validator($request->all(), [
            'email' => 'required | email',
            'password' => 'required '
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $validator->getMessageBag()
            ]);
        } else {
            $user = User::where('email', $request->get('email'))->first();
            if ($user) {
                if (Hash::check($request->get('password'), $user->password)) {
                    $token = $user->createToken('user')->accessToken;
                    $user->setAttribute('token', $token);
                    return response()->json([
                        'code' => Response::HTTP_OK,
                        'data' => $user
                    ]);
                } else {
                    return response()->json([
                        'code' => Response::HTTP_BAD_REQUEST,
                        'message' => 'wrong password'
                    ]);
                }
            } else {
                return response()->json([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'wrong email'
                ]);
            }
        }
    }

    public function register(Request $request)
    {
        $validator = Validator($request->all(), [
            'name' => 'required | string | min:3 | max:50',
            'email' => 'required | email | unique:users,email',
            'password' => 'required | confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $validator->getMessageBag()
            ]);
        } else {
            $user = User::create($request->toArray());
            $token = $user->createToken('user')->accessToken;
            $user->setAttribute('token', $token);
            return response()->json([
                'code' => Response::HTTP_OK,
                'data' => $user
            ]);
        }
    }
}
