<?php

namespace App\Http\Controllers;

use App\Models\Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ErrorController extends Controller
{
    public function index()
    {
        $errors = Error::orderBy('created_at', 'desc')->get();
        return response()->json($errors);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:500',
            'files.*' => 'nullable|image|max:2048', // Допустимые файлы только изображения, максимальный размер 2 МБ
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $error = new Error();
            $error->text = $request->description;
            $error->user_id = auth()->id();
            $error->device_info_user = $request->systemInfoAllowed ? $request->header('User-Agent') : null;
            $error->save();

            if ($request->hasFile('files')) {
                $filePaths = [];
                foreach ($request->file('files') as $file) {
                    $filePath = $file->store('public/errors');
                    $filePaths[] = Storage::url($filePath);
                }
                $error->files = $filePaths;
                $error->save();
            }

            return response()->json(['message' => 'Отчет об ошибке отправлен успешно'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Произошла ошибка при сохранении отчета', 'error' => $e->getMessage()], 500);
        }
    }
}
