<?php

namespace App\Services;

use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountService
{

    public function getAll()
    {
        try {
            $accounts = Account::query()->paginate(1);
            return [
              'success' => true,
              'data' => $accounts,
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
              'success' => false,
              'message' => $e->getMessage(),
            ];
        }
    }

    public function getById($id)
    {
        try {
            $account = Account::query()->find($id);
            if (!$account) {
                return [
                    'success' => false,
                    'message' => 'Account not found',
                ];
            }

            return [
                'success' => true,
                'data' => new AccountResource($account),
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $account = Account::query()->create([
                'login' => $request->login,
                'password' => $request->password,
                'phone' => $request->phone ?? null,
            ]);
            DB::commit();

            return [
                'success' => true,
                'data' => new AccountResource($account),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $account = Account::query()->find($id);
            if (!$account) {
                return [
                    'success' => false,
                    'message' => 'Account not found',
                ];
            }

            $account->update([
               'login' => $request->login,
               'password' => $request->password,
               'phone' => $request->phone ?? null,
            ]);
            DB::commit();

            return [
                'success' => true,
                'data' => new AccountResource($account),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $account = Account::query()->find($id);
            if (!$account) {
                return [
                    'success' => false,
                    'message' => 'Account not found',
                ];
            }
            $account->delete();
            DB::commit();
            return [
                'success' => true,
                'message' => 'Account deleted successfully',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
