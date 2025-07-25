<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::where('user_id', auth()->id())->latest()->get();

        return view('vinify.documents.index', compact('documents'));
    }
}
