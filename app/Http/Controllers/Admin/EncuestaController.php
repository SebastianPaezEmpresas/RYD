<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EncuestasController extends Controller
{
    public function index()
    {
        return view('admin.encuestas.index');
    }
}