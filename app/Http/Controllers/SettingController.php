<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index($id)
    {
        session(['selected_project_id' => $id]);
        return view('settings.index');
    }
}
