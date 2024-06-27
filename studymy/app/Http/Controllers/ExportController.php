<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function downloadStatement($subjectId, $groupId)
    {
        try {
            // Найдите предмет и группу по ID
            $subject = Subject::findOrFail($subjectId);
            $group = Group::findOrFail($groupId);

            // Загрузите существующий шаблон Excel
            $templatePath = resource_path('assets/documents/exampel5.xlsx');
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Найдите преподавателя и специальность по ID
            $teacher = User::findOrFail($subject->teacher1);
            $speciality = Specialty::findOrFail($group->speciality_id);

            // Заполните данные предмета и группы
            $sheet->setCellValue('A7', $this->appendCellValue($sheet->getCell('A7')->getValue(), $subject->name));
            $sheet->setCellValue('H5', $subject->semester);
            $sheet->setCellValue('P5', $subject->period_start);
            $sheet->setCellValue('T5', $subject->period_end);
            $sheet->setCellValue('D9', $group->kyrs);
            $sheet->setCellValue('V9', $group->name_group);
            $sheet->setCellValue('A10', $this->appendCellValue($sheet->getCell('A10')->getValue(), $speciality->full_name));
            $sheet->setCellValue('H12', "{$teacher->sname} {$teacher->name} {$teacher->patronymic}");

            // Запрос данных студентов
            $students = User::with(['marks' => function($query) use ($subjectId) {
                $query->where('subject_id', $subjectId);
            }])->where('group_id', $groupId)->orderBy('sname')->get();

            // Заполните данные студентов
            $startRow = 21; // Начальная строка для студентов
            foreach ($students as $index => $student) {
                $row = $startRow + $index;

                $sourceRow = 21;
                $targetRow = $startRow + $index;

                // Скопировать стиль ячеек
                $sheet->duplicateStyle($sheet->getStyle("A{$sourceRow}:AG{$sourceRow}"), "A{$targetRow}:AG{$targetRow}");

                // Скопировать высоту строки
                $sheet->getRowDimension($targetRow)->setRowHeight(
                    $sheet->getRowDimension($sourceRow)->getRowHeight()
                );

                // Принудительно объединить ячейки, если они были объединены в исходной строке
                $mergedCells = $sheet->getMergeCells();
                foreach ($mergedCells as $mergeCell) {
                    // Проверяем, есть ли в объединенной ячейке исходная строка
                    if (strpos($mergeCell, "{$sourceRow}") !== false) {
                        // Находим диапазон ячеек для объединения
                        [$startCell, $endCell] = explode(":", $mergeCell);

                        // Создаем новые ячейки для объединения на целевой строке
                        $newStartCell = preg_replace('/\d+/', $targetRow, $startCell);
                        $newEndCell = preg_replace('/\d+/', $targetRow, $endCell);

                        // Объединяем ячейки
                        $sheet->mergeCells("{$newStartCell}:{$newEndCell}");
                    }
                }

                // Заполнить данные в строку
                $sheet->setCellValue("A{$targetRow}", $index + 1);
                $sheet->setCellValue("C{$targetRow}", "{$student->sname} {$student->name} {$student->patronymic}");
                $sheet->setCellValue("J{$targetRow}", $this->getOkrStatus($student));
                $sheet->setCellValue("O{$targetRow}", $this->getPracticalCompletionStatus($student, $subjectId));
                $sheet->setCellValue("U{$targetRow}", $this->getCourseMark($student));
                $sheet->setCellValue("Z{$targetRow}", $this->getSemesterMark($student));
            }

            // Добавьте текущую дату через одну строку после последнего студента
            $dateRow = $startRow + count($students) + 1;
            $currentDate = Carbon::now()->locale('ru')->isoFormat('D     MMMM      YYYY г.'); // Формат даты на русском
            $sheet->setCellValue("A{$dateRow}", $currentDate);

            // Задать размер шрифта 14 пт и нижнее подчеркивание
            $style = $sheet->getStyle("A{$dateRow}")->getFont();
            $style->setSize(14);
            $style->setUnderline(\PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_SINGLE);

            // Создайте временный файл для загрузки
            $tempFile = tempnam(sys_get_temp_dir(), 'statement') . '.xlsx';
            $writer = new Xlsx($spreadsheet);
            $writer->save($tempFile);

            // Отправьте файл пользователю
            return response()->download($tempFile)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Ошибка при генерации отчета: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка при генерации отчета. Пожалуйста, попробуйте еще раз позже.'], 500);
        }
    }

    private function appendCellValue($currentValue, $newValue)
    {
        if (empty($currentValue)) {
            return $newValue;
        }
        return $currentValue . ' ' . $newValue;
    }

    private function getOkrStatus($student)
    {
        if (!$student || !$student->marks) return '-';
        $okrMark = $student->marks->firstWhere('type', 'ОКР');
        return ($okrMark && $okrMark->mark > 2 && $okrMark->mark !== 'н' && $okrMark->mark !== 'зач') ? 'Выполнено' : 'Не выполнено';
    }

    private function getPracticalCompletionStatus($student, $subjectId)
    {
        if (!$student || !$student->marks) return '-';

        // Получить количество практических лекций по предмету
        $practicalLecturesCount = Subject::findOrFail($subjectId)
                                        ->lectures()
                                        ->where('type', 'Практическая')
                                        ->count();

        // Получить оценки студента по практическим лекциям
        $practicalMarks = $student->marks->filter(function ($mark) {
            return $mark->type === 'Практическая';
        });

        // Проверить количество зачтённых или оценённых (кроме "н") практических лекций
        $completedPracticalsCount = $practicalMarks->filter(function ($mark) {
            return $mark->mark !== 'н';
        })->count();

        // Определить статус выполнения
        return ($completedPracticalsCount === $practicalLecturesCount) ? 'Выполнено' : 'Не выполнено';
    }

    private function getSemesterMark($student)
    {
        if (!$student || !$student->marks) return '-';
        $semesterMark = $student->marks->firstWhere('type', 'Семестровая');
        return $semesterMark ? $semesterMark->mark : '-';
    }

    private function getCourseMark($student)
    {
        if (!$student || !$student->marks) return '-';
        $courseMark = $student->marks->firstWhere('type', 'Курсовая');
        return $courseMark ? $courseMark->mark : '-';
    }
}
