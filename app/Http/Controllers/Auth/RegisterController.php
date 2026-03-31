<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:365'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:manager,master,mechanic,painter'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $activeId = Status::query()
            ->where('entity_key', 'users')
            ->where('slug', 'active')
            ->value('id');

        $user = User::query()->create([
            ...$data,
            'status_id' => $activeId,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('ok', 'Аккаунт создан.');
    }
}
