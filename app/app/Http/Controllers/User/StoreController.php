<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function __invoke(StoreRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $user = new User();

            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->role = $request['role'];
            $user->password = Hash::make($request['password']);

            $user->save();

            DB::commit();

            \Session::flash('status', 'Create user is succeeded');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());

            \Session::flash('status', 'Create user is failed');
        }

        return redirect()->route('user.index');
    }
}
