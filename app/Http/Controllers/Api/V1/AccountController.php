<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $accounts = Account::with(['user', 'institution'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $accounts,
            'message' => 'Accounts retrieved successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $account = Account::with(['user', 'institution'])
            ->find($id);

        if (!$account) {
            return response()->json([
                'success' => false,
                'message' => 'Account not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $account,
            'message' => 'Account retrieved successfully'
        ]);
    }
}
