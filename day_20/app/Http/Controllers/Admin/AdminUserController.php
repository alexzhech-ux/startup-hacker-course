<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use Inertia\Inertia;
class AdminUserController extends Controller
{
    public function destroy($id)
{
    abort_unless(auth()->user()->role === 1, 403);

    $user = User::findOrFail($id);

    abort_if($user->id === auth()->id(), 403);

    $user->books()->delete();
    $user->delete();

    return redirect('/admin/books')
        ->with('success', 'Удаление завершено');
}
}