<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function profile()
    {
        $user = User::where('id', auth()->user()->id)->first();
        if ($user) {
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'get user data successfully',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'user not found',
                'data' => []
            ]);
        }
    }

    public function update(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $validator = Validator($request->all(), [
            'name' => 'string | min:3 | max:100',
            'email' => 'email | unique:users,email,' . $user->email
        ]);

        if ($user) {
            $user->update($request->all());
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'update data successfully',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'user not found',
                'data' => []
            ]);
        }
    }

    public function courses()
    {
        $user = User::where('id', auth()->user()->id)->first();
        if ($user) {
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'get user courses data successfully',
                'data' => $user->courses
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'user not found',
                'data' => []
            ]);
        }
    }
}
