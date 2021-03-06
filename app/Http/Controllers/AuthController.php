<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Libraries\Response;
use App\Models\Kanji;
use App\Models\User;
use Illuminate\Support\Facades\Http;
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
        $this->middleware('jwt', ['except' => ['login', 'debug']]);
        $this->middleware('auth:api', ['except' => ['login', 'debug']]);
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

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['errors' => [
                'message' => 'Unauthorized',
                'code' => 401,
            ]], 401);
        }

//        $user = User::find(auth()->user()->getAuthIdentifier());

        $payload = [
            'success' => true,
            'message' => 'Success Login'
        ];

        return $this->response($payload, 200, $token);
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
                    'name' => 'Kanji Example',
                    'path' => '/kanjiexample',
                    'icon' => ['fas', 'user'],
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

        $payload = [
            'success' => true,
            'data' => $authMenu
        ];

        return $this->response($payload, 200);
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
