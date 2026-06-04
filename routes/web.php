<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::redirect('/', '/admin/login');

Route::get('/magic-login/{user}', function (Request $request, \App\Models\User $user) {
    if (! $request->hasValidSignature()) {
        abort(401);
    }
    Auth::login($user);
    return redirect('/admin');
})->name('magic.login');