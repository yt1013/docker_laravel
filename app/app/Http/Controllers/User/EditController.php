<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\View\View;

class EditController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param User $user
     * @return View
     */
    public function __invoke(User $user): View
    {
        return view('user.edit', compact('user'));
    }
}
