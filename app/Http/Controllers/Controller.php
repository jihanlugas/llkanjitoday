<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Cookie;

class Controller extends BaseController
{
    public function response($data, $http_code, $token = null)
    {

        if ($token !== null){
            return response()->json($data, $http_code, [
                'Accept' => 'application/json',
                'Content-Type' => 'aplication/json',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Credentials' => true,
            ])
            ->withCookie(Cookie::create('Authorization', 'Bearer ' . $token, time() * (60 * env('JWT_TTL', 60))))
            ;
        } else {
            return response()->json($data, $http_code, [
                'Accept' => 'application/json',
                'Content-Type' => 'aplication/json',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Credentials' => true,
            ]);
        }


    }

    public function responseNotFound($message)
    {

    }


    public function responseWithToken($payload, $token){
        return response()->json($payload, 200, [
                'Accept' => 'application/json',
                'Content-Type' => 'aplication/json',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Credentials' => true,
            ])
            ->withCookie(Cookie::create('Authorization', 'Bearer ' . $token, time() * (60 * env('JWT_TTL', 5))))
            ;
    }

    public function responseWithoutToken($payload = []){
        return response()->json($payload, 200, [
                'Accept' => 'application/json',
                'Content-Type' => 'aplication/json',
                'Access-Control-Allow-Credentials' => true,
            ]);
    }
}
