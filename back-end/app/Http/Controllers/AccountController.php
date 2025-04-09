<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index(Request $request)
    {
        $result = $this->accountService->getAll($request);
        if (isset($result['success']) && !$result['success']) {
            return response()->json(['msg' => $result['message']], 400);
        }

        return response()->json($result['data']);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|unique:accounts',
            'password' => 'required',
            'phone' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $result = $this->accountService->create($request);

        if (isset($result['success']) && !$result['success']) {
            return response()->json(['msg' => $result['message']], 400);
        }

        return response()->json($result['data']);
    }

    public function detail($id)
    {
        $result = $this->accountService->getById($id);

        if (isset($result['success']) && !$result['success']) {
            return response()->json(['msg' => $result['message']], 400);
        }

        return response()->json($result['data']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'login' => [
                'required',
                'string',
                Rule::unique('accounts')->where('login', request()->login)
                    ->ignore($id, 'register_id'),
            ],
            'password' => 'required|string',
            'phone' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $result = $this->accountService->update($request, $id);

        if (isset($result['success']) && !$result['success']) {
            return response()->json(['msg' => $result['message']], 400);
        }

        return response()->json($result['data']);
    }

    public function delete($id)
    {
        $result = $this->accountService->delete($id);
        if (isset($result['success']) && !$result['success']) {
            return response()->json(['msg' => $result['message']], 400);
        }

        return response()->json(['msg' => $result['message']]);
    }
}
