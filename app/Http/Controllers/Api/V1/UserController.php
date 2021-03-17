<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\UserDetailsResource;
use App\Models\User;
use App\Models\User_details;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function Sodium\add;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return UserResource::collection($user->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, User_details $details, Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $address = $request->input('address');

        if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($password)) {
            $newUser = $user->create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $password
            ]);
        }

        if (!empty($address)) {
            $details->create([
                'address' => $address,
                'user_id' => $newUser->id
            ]);
        } else {
            $details->create([
                'address' => '',
                'user_id' => $newUser->id
            ]);
        }

        return new UserResource($newUser);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, User_details $details, Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $address = $request->input('address');

        if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($password))
        {
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->email = $email;
            $user->password = $password;
            $user->save();
        }

        $details_row = DB::table('user_details')->where('user_id', '=', $user->getKey());

        if (!is_null($details_row) && !empty($address)) {

            $user->user_details()->update([
                'address' => $address
            ]);
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->user_details()->delete();
        $user->delete();
        return response()->noContent();
    }
}
