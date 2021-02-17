<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    //todo: kreiraj komentar
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $user = Auth::user();

        if(!is_null($user)) {
            $validator = Validator::make($request->all(), [
                'comment' => 'required'
            ]);

            if($validator->fails()) {
                return response()->json(['status' => 'failed', 'validation_errors' => $validator->errors()]);
            }
            $commentInput = $request->all();
            $commentInput['user_id'] = $user->id;

            $commentInput['is_Active'] = true;

            $comment = Comment::create($commentInput);

            if(!is_null($comment)) {
                return response()->json(['status' => 'success', 'message'=>'Success! comment created', 'data' => $comment]);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Whoops! comment not created']);
            }
        }
    }
    //todo: izmijeni komentar
    //todo: obrisi komentar
}
