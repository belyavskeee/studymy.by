<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecture;

class LectureController extends Controller
{
    // Метод для получения лекции по ID
    public function show($id)
    {
        $lecture = Lecture::with('subject')->find($id);

        if (!$lecture) {
            return response()->json(['error' => 'Lecture not found'], 404);
        }

        return response()->json($lecture);
    }

    // Метод для обновления данных лекции
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'theme' => 'nullable|string|max:255',
            'homework' => 'nullable|string|max:255',
            'OKR' => 'nullable|string|max:255',
        ]);

        try {
            $lecture = Lecture::findOrFail($id);
            $lecture->update($validatedData);

            return response()->json($lecture);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при обновлении лекции'], 500);
        }
    }

    // Метод для создания новой лекции (курсовой работы)
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'date' => 'required|date',
            'subject_id' => 'required|integer|exists:subjects,id',
            'theme' => 'nullable|string',
            'homework' => 'nullable|string',
            'hours' => 'nullable|integer',
            'OKR' => 'nullable|string'
        ]);

        try {
            $lecture = new Lecture();
            $lecture->type = $request->input('type');
            $lecture->date = $request->input('date');
            $lecture->subject_id = $request->input('subject_id');
            $lecture->theme = $request->input('theme', '');
            $lecture->homework = $request->input('homework', '');
            $lecture->hours = $request->input('hours', 0);
            $lecture->OKR = $request->input('OKR', null);
            $lecture->save();

            return response()->json($lecture, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при создании лекции'], 500);
        }
    }

    // Метод для удаления лекции
    public function destroy($id)
    {
        try {
            $lecture = Lecture::findOrFail($id);
            $lecture->delete();

            return response()->json(['message' => 'Lecture deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при удалении лекции'], 500);
        }
    }
}
