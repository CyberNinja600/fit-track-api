<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = auth('api')->user();
        $program = Program::create([
            ...$validated,
            'created_by' => $user->username ?? $user->name,
            'admin' => $user->username ?? $user->name,
            'enrollment_code' => Str::upper(Str::random(8)),
        ]);

        return response()->json([
            'data' => $program,
            'message' => 'Program created successfully.',
        ], 201);
    }

    public function read(Request $request): JsonResponse
    {
        $program = Program::findOrFail($request->input('id'));

        return response()->json(['data' => $program]);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'id' => ['required',
                'integer',
                'exists:programs,id'],
        ]);

        $program = Program::findOrFail(
            $request->input('id')
        );

        $program->delete();

        return response()->json([
            'message' => 'Program deleted successfully.',
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:programs,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $program = Program::findOrFail($request->input('id'));

        $data = array_filter(
            $request->only(['name', 'description']),
            fn ($value) => $value !== null
        );
        $program->update($data);

        return response()->json([
            'data' => $program,
            'message' => 'Program updated successfully.',
        ]);
    }
}
