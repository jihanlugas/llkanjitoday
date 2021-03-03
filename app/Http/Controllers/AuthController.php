<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Libraries\Response;
use App\Models\Kanji;
use App\Models\User;
use Symfony\Component\HttpFoundation\Cookie;


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('cors');
        $this->middleware('request');
        $this->middleware('jwt', ['except' => ['login', 'logout', 'debug']]);
        $this->middleware('auth:api', ['except' => ['login', 'logout', 'debug']]);
    }

    public function debug(){
//        $kanjis = Kanji::all();
        $query = Kanji::query();
//        $data->with(['kanjionyomis', 'kanjikunyomis']);
//        $data->paginate();
//        $data = new Kanji();
//        $data->setRelations(['kanjionyomis', 'kanjikunyomis']);

        $data = $query->paginate(5);

        dd($data);

        foreach ($kanjis as $kanji){
            dd($kanji->getRelations());
            foreach (array_keys($kanji->getRelations()) as $key => $relation){
                dd($relation);
            }
        }
        $token = 'Token';
        return $this->responseWithToken($payload, $token);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);
        $token = 'asd';

//        if (!$token = auth()->attempt($credentials)) {
//            return response()->json(['error' => 'Unauthorized'], 401);
//        }
//
//        $user = User::find(auth()->user()->getAuthIdentifier());
//        $payload = [
//            "user_id" => $user->user_id,
//            "email" => $user->email,
//            "name" => $user->name,
//            "role_id" => $user->role_id,
//        ];

        return $this->responseWithToken(Response::success($credentials), $token);
    }

    public function authorized(){
        $user = User::find(auth()->user()->getAuthIdentifier());

        $authMenu = [];
        if ($user->role_id == User::ROLE_ADMIN){
            $authMenu = [
                [
                    'name' => 'Dashboard',
                    'path' => '/dashboard',
                    'icon' => ['fas', 'chart-line'],
                ],
                [
                    'name' => 'Kanji',
                    'path' => '/kanji',
                    'icon' => ['fas', 'user'],
                ],
                [
                    'name' => 'Verb',
                    'path' => '/verb',
                    'icon' => ['fas', 'users'],
                ],
                [
                    'name' => 'Noun',
                    'path' => '/noun',
                    'icon' => ['fas', 'project-diagram'],
                ],
                [
                    'name' => 'Adjective',
                    'path' => '/adjective',
                    'icon' => ['fas', 'project-diagram'],
                ],
            ];
        } else if($user->role_id == User::ROLE_MANDOR) {
            $authMenu = [
                [
                    'name' => 'Dashboard',
                    'path' => '/dashboard',
                    'icon' => ['fas', 'chart-line'],
                ],
                [
                    'name' => 'Anggota',
                    'path' => '/anggota',
                    'icon' => ['fas', 'users'],
                ],
            ];
        } else if($user->role_id == User::ROLE_ANGGOTA) {
            $authMenu = [
                [
                    'name' => 'Dashboard',
                    'path' => '/dashboard',
                    'icon' => ['fas', 'chart-line'],
                ],
            ];
        }

        return $this->responseWithoutToken(Response::success($authMenu));
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    // can't logout when server not running Error (500)
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ])->withCookie(Cookie::create('Authorization', ''));
    }
}
