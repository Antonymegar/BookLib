<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelperService
{
    public function getCurrentUserId(Request $request)
    {
        $user = Auth::guard('api')->user();
        if($user){
            return $user->id;
        }
        return null;
    }
}
