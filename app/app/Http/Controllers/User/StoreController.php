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
        $parameters = $this->makeParameter($request);

        DB::beginTransaction();
        try {
            (new User())->createUser($parameters);

            DB::commit();

            \Session::flash('status', 'Create user is succeeded');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());

            \Session::flash('status', 'Create user is failed');
        }

        return redirect()->route('user.index');
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    private function makeParameter(StoreRequest $request): array
    {
        return [
            'name' => $request['name'],
            'email' => $request['email'],
            'role' => $request['role'],
            'password' => $request['password']
        ];
    }
}
