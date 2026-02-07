<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    public function destroy(int $userId)
    {
        $admin = auth()->user();

        abort_if($admin->role !== 1, 403);
        abort_if($admin->id === $userId, 403);

        $user = User::where('id', $userId)->firstOrFail();

        abort_if($user->role === 1, 403);

        DB::transaction(function () use ($user) {
            $user->books()->delete();
            $user->delete();
        });

        return redirect()->back();
    }
}