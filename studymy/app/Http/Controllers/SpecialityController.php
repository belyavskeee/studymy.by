<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use App\Models\Specialty;

class SpecialityController extends Controller
{
    public function index()
    {
        try {
            $specs = Specialty::all();
            return response()->json(['specs' => $specs]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при получении специальностей'], 500);
        }
    }

}
