<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $result = auth()->user()->courses->where('id', $id)->first();
        if ($result) {
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'get data successfully',
                'data' => $result->questions
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'question not found',
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
            'course_id' => 'required | numeric',
            'question' => 'required | string | min:10 | max:200',
            'options' => 'nullable',
            'answer' => 'nullable | string',
            'currect_answer' => 'nullable | string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $validator->getMessageBag(),
                'data' => []
            ]);
        } else {
            $question = Question::create($request->all());
            if ($question) {
                return response()->json([
                    'code' => Response::HTTP_CREATED,
                    'message' => 'store data successfully',
                    'data' => $question
                ]);
            } else {
                return response()->json([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Error when store data',
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
        $question = Question::where('id', $id)->first();
        if ($question) {
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'get question successfully',
                'data' => $question
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'question not exist',
                'data' => []
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator($request->all(), [
            'course_id' => 'numeric',
            'question' => 'string | min:10 | max:200',
            'options' => 'nullable',
            'answer' => 'nullable | string',
            'currect_answer' => 'nullable | string'
        ]);

        if (!$validator->fails()) {
            $question = Question::where('id', $id)->first();
            if ($question) {
                $question->update($request->all());
                return response()->json([
                    'code' => Response::HTTP_OK,
                    'message' => 'update data successfully',
                    'data' => $question
                ]);
            } else {
                return response()->json([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'question not found',
                    'data' => []
                ]);
            }
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $validator->getMessageBag(),
                'data' => []
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $question = Question::where('id', $id)->first();
        if ($question) {
            $question->delete();
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'delete data successfully',
                'data' => []
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'error when delete data',
                'data' => []
            ]);
        }
    }
}
