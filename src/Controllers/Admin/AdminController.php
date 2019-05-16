<?php

namespace App\Http\Controllers\Admin;

use App\Aluno;
use App\Http\Controllers\Controller;
use App\Professor;
use App\User;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $total = [];
        $total['users'] = User::all()->count();
        $total['professores'] = Professor::all()->count();
        $total['alunos'] = Aluno::all()->count();
        $total['turmas'] = 0;
        return view('admin.dashboard', compact('total'));
    }
}
