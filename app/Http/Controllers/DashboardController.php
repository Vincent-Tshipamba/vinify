<?php

namespace App\Http\Controllers;

use App\Models\TextAnalysis;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = count(User::all());
        $nbrAnalyses = count(TextAnalysis::all());
        return view('dashboard', compact('users', 'nbrAnalyses'));
    }
}
