<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // todo: prikazi sve komentare
    /*
    **
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index() {
        $user = Auth::user();
        if(!is_null($user)){
            $comments = Comment::where('user_id', $user->id)->where('is_Active', true)->get();
            if(count($comments) > 0 ) {
                return response()->json(['status' => 'success', 'count' => count($comments), 'data' => $comments], 200);
            } else {
                return response()->json(['status' => 'failed', 'count' => count($comments), 'message' => 'Failed! no comments found'], 204);
            }
        }
    }
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
    public function update(Request $request, Comment $comment) {
        $input = $request->all();
        $user = Auth::user();

        if(!is_null($user)) {
            $validator = Validator::make($request->all(), [
                'comment' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['status' => 'failed', 'validation_errors' => $validator->errors()]);
            }
            $update = $comment->update($request->all());

            return response()->json(['status' => 'success', 'message' => 'Success! comment updated', 'data' => $comment], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Un-authorized user'], 403);
        }
    }
    //todo: obrisi komentar
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Comment $comment) {
        $user = Auth::user();
        $comment['is_Active'] = false;
        if(!is_null($user)){
            $comment->update();
            return response()->json(['status' => 'success', 'message' => 'Success!! comment deleted'], 200);
        } else
        {
            return response()->json(['status' => 'failed', 'message' => 'Un-authorized user'], 403);
        }
    }
    public function showTweetComments($id) {
        $user = Auth::user();

        if(!is_null($user)) {
            $comments = Comment::where('tweet_id', $id)->where('is_Active', true)->get();
            if(!is_null($comments)) {
                return response()->json(['status' => 'success', 'data' => $comments], 200);
            } else {
                return response()->json(['status' => 'failed','message' => 'Failed no comment found for this tweet'], 200);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Un-authorized'], 403);
        }
    }
}
