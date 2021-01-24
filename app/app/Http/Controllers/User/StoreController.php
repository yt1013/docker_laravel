<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Log;
use Session;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function __invoke(StoreRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'role' => $request['role']
            ]);

            abort(404);

            DB::commit();

            Session::flash('status', 'Create user is succeeded');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            Session::flash('status', 'Create user is failed');
        }

        return redirect()->route('user.index');
    }
}
