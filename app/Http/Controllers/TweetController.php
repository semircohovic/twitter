<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    /*
    **
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        // check logged user
        $user=Auth::user();
        if(!is_null($user)) {
            $tweets=Tweet::where("user_id", $user->id)->where("is_Active", true)->get();
            if(count($tweets) > 0) {
                return response()->json(["status" => "success", "count" => count($tweets), "data" => $tweets], 200);
            }

            else {
                return response()->json(["status" => "failed", "count" => count($tweets), "message" => "Failed! no tweet found"], 200);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!is_null($user)) {
            $validator = Validator::make($request->all(), [
                'tweet' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
            }
            $tweetInput = $request->all();
            $tweetInput['user_id'] = $user->id;
            $tweetInput['is_Active'] = true;

            $tweet = Tweet::create($tweetInput);
            if (!is_null($tweet)) {
                return response()->json(['status' => 'success', 'message' => 'Success! tweet created', 'data' => $tweet]);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Whoops! task not created"']);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        if (!is_null($user)) {
            $tweet = Tweet::where('user_id', $user->id)->where("id", $id)->where('is_Active', true)->first();
            if (!is_null($tweet)) {
                return response()->json(['status' => "success", 'data' => $tweet], 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Failed! no tweet found'], 200);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Un-authorized user'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tweet $tweet)
    {
        $input = $request->all();
        $user = Auth::user();

        if (!is_null($user)) {
            $validator = Validator::make($request->all(), [
                'tweet' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'validation_errors' => $validator->errors()]);
            }
            $update = $tweet->update($request->all());

            return response()->json(['status' => 'success', 'message' => 'Success! tweet updated', 'data' => $tweet], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Un-authorized user'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tweet $tweet)
    {
        $user = Auth::user();
        $tweet['is_Active'] = false;
        if (!is_null($user)) {
//            $tweet = Tweet::where('id', $tweet)->where('user_id', $user->id)->first();
//            $update = $tweet->update($tweetU);
            $tweet->update();
            return response()->json(['status' => 'success', 'message' => 'Success!! task deleted'], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Un-authorized user"], 403);
        }
    }

}
