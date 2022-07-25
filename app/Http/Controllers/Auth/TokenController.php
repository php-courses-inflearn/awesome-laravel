<?php

namespace App\Http\Controllers\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTokenRequest;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TokenController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tokens.create', [
            'abilities' => TokenAbility::cases()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTokenRequest $request)
    {
        $token = $request->user()
            ->createToken($request->name, $request->abilities);

        return back()
            ->with('status', $token->plainTextToken);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PersonalAccessToken $token)
    {
        $token->delete();

        return back();
    }
}
