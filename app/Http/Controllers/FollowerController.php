<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Follower;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FollowerController extends Controller {
    //    todo: prikazi koga sve pratis
    public function index(){
        $user = Auth::user();
        if(!is_null($user)){
            $following = Follower::where('user_1', $user->id)->where('is_Active', true)->get();
            if(!is_null($following)){
                return response()->json(['status' => 'success', 'count' => count($following),  'data' => $following], 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Whoops! You dont follow anyone'], 203);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' =>'Un-authorized'], 403);
        }
    }
        //todo: kreiraj vezu
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function follow($id) {
        $user = Auth::user();

        if(!is_null($user)){

            $follow['user_1'] = $user->id;
            $follow['user_2'] = $id;
            $follow['is_Active'] = true;
            $followw = Follower::create($follow);

            if(!is_null($followw)) {
                return response()->json(['status' => 'success', 'message' => 'Success! follow created', 'data' => $followw], 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Whoops! follow not created'], 200);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Un-authorized'], 403);
        }
    }
    //todo: obrisi vezu
    public function unfollow($id) {
        $user = Auth::user();
        if(!is_null($user)) {
            $unfollow = Follower::where('user_1', $user->id)->where('user_2', $id)->where('is_Active', true)->first();
            if(!is_null($unfollow)) {
                $update = Follower::find($unfollow->id);
//                $id->save($unfollow);
                $update['is_Active'] = false;
                $update->update();
                return response()->json(['status' => 'success', 'message'=> 'Success! You unfollowed', 'data' => $update], 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'whoops! Cant unfollow ']);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Un-authorized'], 403);
        }
    }

}
