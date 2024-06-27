<?php

// TimetableController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Lecture;
use Illuminate\Support\Facades\Log;


class TimetableController extends Controller
{
    // Other methods...

    /**
     * Получить группы с пустыми годами.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGroupsWithEmptyYear()
    {
        try {
            $groups = Group::whereNull('year_ot')->get();
            foreach ($groups as $group) {
                $group->subjects = Subject::where('group_id', $group->id)->get();
            }
            return response()->json($groups, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch groups'], 500);
        }
    }

    /**
     * Получить расписание по дате.
     *
     * @param  string  $date
     * @return \Illuminate\Http\Response
     */
    public function getScheduleByDate($date)
    {
        try {
            $schedule = Schedule::where('date', $date)->with(['group', 'subject', 'lecture'])->get();
            return response()->json($schedule, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch schedule'], 500);
        }
    }

    public function getTeacherSchedule($teacherId, $date)
    {
        try {
            $schedule = Schedule::where(function ($query) use ($teacherId) {
                                $query->where('teacher_one', $teacherId)
                                      ->orWhere('teacher_two', $teacherId);
                            })
                            ->where('date', $date)
                            ->with(['subject', 'group', 'teacher1', 'teacher2'])
                            ->get();

            return response()->json($schedule);
        } catch (\Exception $e) {
            \Log::error('Error fetching teacher schedule: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch schedule'], 500);
        }
    }

    public function getGroupSchedule($groupId, $date)
    {
        try {
            $schedule = Schedule::where('group_id', $groupId)
                                ->where('date', $date)
                                ->with(['subject', 'group', 'teacher1', 'teacher2'])
                                ->get();

            return response()->json($schedule);
        } catch (\Exception $e) {
            \Log::error('Error fetching group schedule: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch schedule'], 500);
        }
    }

    /**
     * Сохранить расписание.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveSchedule(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'groups' => 'required|array'
        ]);

        try {
            foreach ($data['groups'] as $group) {
                foreach ($group['lessons'] as $index => $lesson) {
                    // Сохранение записи в расписании
                    $schedule = Schedule::updateOrCreate(
                        [
                            'date' => $data['date'],
                            'number_sub' => $index + 1,
                            'group_id' => $group['id']
                        ],
                        [
                            'time' => $lesson['start_time'] ?? null,
                            'subject_id' => $lesson['subject_id'],
                            'lecture_id' => $lesson['lecture_id'] ?? null,
                            'type' => !empty($lesson['teacher_2_id']) && !empty($lesson['audience_2']) ? 'Практическая' : 'Лекция',
                            'class_1' => $lesson['audience_1'] ?? null,
                            'class_2' => $lesson['audience_2'] ?? null,
                            'teacher_one' => $lesson['teacher_1_id'] ?? null,
                            'teacher_two' => $lesson['teacher_2_id'] ?? null
                        ]
                    );

                    // Сохранение записи лекции
                    $lecture = Lecture::updateOrCreate(
                        [
                            'timetable_id' => $schedule->id,
                            'type' => 'Лекция'
                        ],
                        [
                            'hours' => $lesson['hours'] ?? 2,
                            'teacher_1_id' => $lesson['teacher_1_id'],
                            'date' => $data['date'],
                            'subject_id' => $lesson['subject_id']
                        ]
                    );

                    // Проверка на наличие двух преподавателей и двух аудиторий
                    if (!empty($lesson['teacher_2_id']) && !empty($lesson['audience_2'])) {
                        Lecture::updateOrCreate(
                            [
                                'timetable_id' => $schedule->id,
                                'type' => 'Практическая'
                            ],
                            [
                                'hours' => 1,
                                'teacher_1_id' => $lesson['teacher_1_id'],
                                'teacher_2_id' => $lesson['teacher_2_id'],
                                'date' => $data['date'],
                                'subject_id' => $lesson['subject_id']
                            ]
                        );
                    }
                }
            }

            return response()->json(['message' => 'Schedule saved successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Unable to save schedule: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to save schedule'], 500);
        }
    }

}

