<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\Group;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return response()->json($subjects);
    }

    public function show($id)
    {
        $subject = Subject::findOrFail($id);
        return response()->json($subject);
    }
    
    public function getSubjectWithLectures($id)
    {
        // Найти предмет по id и загрузить лекции
        $subject = Subject::with('lectures')->find($id);

        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        return response()->json($subject);
    }

    public function getMarks($id)
    {
        // Fetch the subject
        $subject = Subject::findOrFail($id);
        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        // Fetch the marks for all students in the subject
        $marks = Mark::where('subject_id', $id)
                     ->with('student') // Assuming you have a relationship defined
                     ->get();

        // Format the marks as needed for the frontend
        $formattedMarks = [];
        foreach ($marks as $mark) {
            $formattedMarks[] = [
                'student_id' => $mark->student_id,
                'lecture_id' => $mark->lecture_id,
                'hour' => $mark->hour,
                'type' => $mark->type,
                'mark' => $mark->mark
            ];
        }

        return response()->json([
            'marks' => $formattedMarks
        ]);
    }

    public function getSubjectsByGroupId($groupId)
    {
        // Найти группу по ID
        $group = Group::findOrFail($groupId);

        // Получить все предметы, связанные с этой группой
        $subjects = Subject::where('group_id', $groupId)->get();

        return response()->json($subjects);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'short_name' => 'required|string|max:255',
                'semester' => 'required|integer',
                'period_start' => 'required|integer',
                'period_end' => 'required|integer',
                'hours' => 'required|integer',
                'teacher1' => 'nullable|integer',
                'teacher2' => 'nullable|integer',
                'spec' => 'required|integer',
                'group_id' => 'required|integer',
            ]);

            $subject = Subject::create($data);
            return response()->json(['subject' => $subject], 201);
        } catch (\Exception $e) {
            Log::error('Ошибка при добавлении предмета: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка при добавлении предмета'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'semester' => 'required|integer',
            'period_start' => 'required|integer',
            'period_end' => 'required|integer',
            'hours' => 'required|integer',
            'teacher1' => 'nullable|integer',
            'teacher2' => 'nullable|integer',
            'spec' => 'required|integer',
            'group_id' => 'required|integer',
        ]);

        $subject->update($data);
        return response()->json(['subject' => $subject]);
    }

    public function destroy($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->delete();

            return response()->json(['message' => 'Предмет успешно удалён'], 200);
        } catch (\Exception $e) {
            \Log::error('Ошибка при удалении предмета: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка при удалении предмета'], 500);
        }
    }

}
