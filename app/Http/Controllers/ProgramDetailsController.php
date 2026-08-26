<?php

namespace App\Http\Controllers;

use App\Models\ProgramDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramDetailsController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'program_id' => ['required', 'integer', 'exists:programs,id'],
            'start_date' => ['nullable', 'date'],
            'weeks' => ['nullable', 'integer', 'between:0,15'],
            'program_timeline' => ['nullable', 'string'],
            'user_timeline' => ['nullable', 'string'],
            'completion_status' => ['nullable', 'numeric', 'between:0,100'],
        ]);

        $programDetails = ProgramDetails::create($validated);

        return response()->json([
            'data' => $programDetails,
            'message' => 'Program details created successfully.',
        ], 201);
    }

    public function read(Request $request): JsonResponse
    {
        $programDetails = ProgramDetails::findOrFail($request->input('id'));

        return response()->json(['data' => $programDetails]);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:program_details,id'],
        ]);
        $programDetails = ProgramDetails::findOrFail($request->input('id'));
        $programDetails->delete();

        return response()->json([
            'message' => 'Program details deleted successfully.',
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:program_details,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'start_date' => ['nullable', 'date'],
            'weeks' => ['nullable', 'integer', 'between:0,15'],
            'program_timeline' => ['nullable', 'string'],
            'user_timeline' => ['nullable', 'string'],
            'completion_status' => ['nullable', 'numeric', 'between:0,100'],
        ]);

        $programDetails = ProgramDetails::findOrFail($request->input('id'));
        $programDetails->update($request->only([
            'user_id',
            'program_id',
            'start_date',
            'weeks',
            'program_timeline',
            'user_timeline',
            'completion_status',
        ]));

        return response()->json([
            'data' => $programDetails,
            'message' => 'Program details updated successfully.',
        ]);
    }
}
