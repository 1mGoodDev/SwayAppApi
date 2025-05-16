<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchUser(Request $request)
    {
        $query = $request->input('search');

        $user = User::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%');
            })->get();

        return response()->json([
            'id'    =>  $user->id,
            'name'  =>  $user->name,
            'job'   =>  $user->job,
            'status'    =>  $user->status,
        ]);
    }
}
