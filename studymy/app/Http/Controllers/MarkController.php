<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mark;
use Illuminate\Support\Facades\Log;

class MarkController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'student_id' => 'required|integer|exists:users,id',
                'teacher_id' => 'required|integer|exists:users,id',
                'lecture_id' => 'required|integer|exists:lectures,id',
                'subject_id' => 'required|integer|exists:subjects,id',
                'hour' => 'required|integer',
                'type' => 'required|string',
                'mark' => 'nullable|sometimes|string'
            ]);

            $mark = Mark::updateOrCreate(
                [
                    'student_id' => $validatedData['student_id'],
                    'lecture_id' => $validatedData['lecture_id'],
                    'subject_id' => $validatedData['subject_id'],
                    'hour' => $validatedData['hour']
                ],
                [
                    'teacher_id' => $validatedData['teacher_id'],
                    'type' => $validatedData['type'],
                    'mark' => $validatedData['mark']
                ]
            );

            return response()->json(['message' => 'Mark saved successfully', 'mark' => $mark]);
        } catch (\Exception $e) {
            // Логирование ошибки
            Log::error('Error updating mark: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Ошибка при создании или обновлении отметки'], 500);
        }
    }
}
