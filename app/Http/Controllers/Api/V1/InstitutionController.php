<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $institutions = Institution::with(['accounts'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $institutions,
            'message' => 'Institutions retrieved successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $institution = Institution::with(['accounts'])
            ->find($id);

        if (!$institution) {
            return response()->json([
                'success' => false,
                'message' => 'Institution not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $institution,
            'message' => 'Institution retrieved successfully'
        ]);
    }
}
