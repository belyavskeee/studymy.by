<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;

class GroupController extends Controller
{
    public function index()
    {
        try {
            $groups = Group::all();
            return response()->json(['groups' => $groups]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при получении учебных групп'], 500);
        }
    }

    public function show($id)
    {
        try {
            $group = Group::findOrFail($id);
            return response()->json(['group' => $group]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Группа не найдена'], 404);
        }
    }

    public function getStudentsInGroup($groupId)
    {
        try {
            $group = Group::findOrFail($groupId);
            $students = User::where('group_id', $groupId)
                            ->orderBy('sname')
                            ->get();
            return response()->json(['students' => $students]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при получении студентов группы'], 500);
        }
    }

    /**
     * Store a newly created group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name_group' => 'required|string|max:7',
            'kyrs' => 'required|integer',
            'speciality_id' => 'required|exists:specialties,id',
            'audience' => 'nullable|string|max:10',
            'type_education' => 'nullable|string|max:15',
            'after_school' => 'nullable|integer',
            'year_z' => 'nullable|string',
        ]);

        $group = Group::create($validatedData);

        return response()->json($group, 201);
    }

    /**
     * Update the specified group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name_group' => 'required|string|max:7',
            'kyrs' => 'required|integer',
            'speciality_id' => 'required|exists:specialties,id',
            'audience' => 'nullable|string|max:10',
            'type_education' => 'nullable|string|max:15',
            'after_school' => 'nullable|integer',
            'year_z' => 'nullable|string',
        ]);

        $group = Group::findOrFail($id);
        $group->update($validatedData);

        return response()->json($group, 200);
    }
}
