<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param User $user
     * @param UpdateRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function __invoke(User $user, UpdateRequest $request): RedirectResponse
    {
        $parameters = $this->makeParameter($request);

        DB::beginTransaction();
        try {
            $user->updateUser($parameters);

            DB::commit();

            \Session::flash('status', 'Update user is succeeded');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());

            \Session::flash('status', 'Update user is failed');
        }

        return redirect()->route('user.index');
    }

    /**
     * @param UpdateRequest $request
     * @return array
     */
    private function makeParameter(UpdateRequest $request): array
    {
        return [
            'name' => $request['name'],
            'email' => $request['email'],
            'role' => $request['role']
        ];
    }
}
