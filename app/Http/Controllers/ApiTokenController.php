<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiTokenController extends Controller
{
  /**

     * Update the authenticated user's API token.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return array

     */
    public function update(Request $request){
        $request->validate([
            'api_token' => 'nullable|string|max:80',
        ]);

        $user = $request->user();

        if ($request->filled('api_token')) {
            $user->api_token = $request->input('api_token');
        } else {
            $user->api_token = Str::random(80);
        }

        $user->save();

        return response()->json([
            'message' => 'API token updated successfully.',
            'api_token' => $user->api_token,
        ]);
    }
}
