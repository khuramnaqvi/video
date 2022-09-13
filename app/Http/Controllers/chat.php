<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use OpenTok\OpenTok;
use OpenTok\MediaMode;


class chat extends Controller
{
    public function chat(Request $request) 
    {
           // initialze api using api key/secret
           $openTokAPI = new OpenTok(env('OPENTOK_API_KEY'), env('OPENTOK_API_SECRET'));

           // let's cache the session for an hour i.e. 60 mins
           $session_token = \Cache::remember('open_tok_session_key', 60, function () use ($openTokAPI) {             
                return $openTokAPI->createSession(['mediaMode' => MediaMode::ROUTED]);
           });

           // now, that we have session token we generate opentok token
           $opentok_token = $openTokAPI->generateToken($session->session_id, [
                'exerciseireTime' => time()+60,
                'data'       => "Some sample metadata to pass"
           ]);

           return view('chat', [
              'session_token' => $session_token,
              'opentok_token' => $opentok_token
           ]);
    }
}
