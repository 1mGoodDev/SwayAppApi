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

        $data = $user->map(function ($u) {
            return [
                'id'    =>  $u->id,
                'name'  =>  $u->name,
                'job'   =>  $u->job,
                'status'    =>  $u->status,
            ];
        });

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Data successfully found',
            'data'  =>  $data
        ]);
    }
}
