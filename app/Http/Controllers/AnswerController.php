<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnswerController extends Controller
{
    public function index()
    {
        $answers = Answer::where('user_id', auth()->user()->id)->get();
        if ($answers) {
            $wrongAnswers = $answers->where('is_correct', 0)->count();
            $correctAnswers = $answers->where('is_correct', 1)->count();
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'get data successfully',
                'data' => ['wrong_answers' => $wrongAnswers, 'correct_answers' => $correctAnswers]
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Error when get data',
                'data' => []
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'question_id' => 'required | numeric',
            'answer' => 'required | string'
        ]);

        if (!$validator->fails()) {
            $question = Question::where('id', $request->question_id)->first();
            if ($question) {
                if ($question->currect_answer === $request->answer) {
                    $request->merge(['user_id' => auth()->user()->id, 'is_correct' => 1]);
                } else {
                    $request->merge(['user_id' => auth()->user()->id, 'is_correct' => 0]);
                }
            } else {
                return response()->json([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'question not found',
                    'data' => [],
                ]);
            }
            $answer = Answer::create($request->all());
            return response()->json([
                'code' => Response::HTTP_CREATED,
                'message' => 'store data successfully',
                'data' => $answer,
            ]);
        } else {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $validator->getMessageBag(),
                'data' => [],
            ]);
        }
    }
}
