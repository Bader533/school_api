<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $course = Course::get();
        if ($course) {
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'get data successfully',
                'data' => $course
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'no data found',
                'data' => []
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'name' => 'required | string | min:3 | max:100',
            'image' => 'required | mimes:jpg,png,jpeg'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $validator->getMessageBag(),
                'data' => []
            ]);
        } else {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/images', $fileName);
                $request->merge(['image_path' => $path]);
            }
            $course = Course::create([
                'name' => $request->name,
                'image' => $request->image_path,
            ]);

            if ($course) {
                return response()->json([
                    'code' => Response::HTTP_CREATED,
                    'message' => 'store data successfully',
                    'data' => $course
                ]);
            } else {
                return response()->json([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Error when store course',
                    'data' => []
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $course = Course::find($id);
        if (!empty($course)) {
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'get data successfully',
                'data' => $course
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Error when get data',
                'data' => []
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $Course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator($request->all(), [
            'name' => 'string | min:3 | max:100',
            'image' => 'mimes:png,jpg,jpeg'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $validator->getMessageBag(),
                'data' => []
            ]);
        } else {
            $course = Course::find($id);
            if (empty($course)) {
                return response()->json([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Error when get data',
                    'data' => []
                ]);
            } else {
                if ($request->hasFile('image')) {
                    Storage::delete($course->image);
                    $file = $request->file('image');
                    $fileName = time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('public/images', $fileName);
                    $request->merge(['image_path' => $path]);
                }
                $course->update([
                    'name' => $request->get('name'),
                    'image' => $request->image_path,
                ]);
                return response()->json([
                    'code' => Response::HTTP_CREATED,
                    'message' => 'update data successfully',
                    'data' => $course
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course = Course::find($id);
        if ($course) {
            Storage::delete($course->image);
            $course->delete();
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'delete data successfully',
                'data' => []
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Error when get data',
                'data' => []
            ]);
        }
    }
}
