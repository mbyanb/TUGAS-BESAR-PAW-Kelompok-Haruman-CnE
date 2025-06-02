<?php
// app/Http/Controllers/Web/AdminController.php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // HAPUS CONSTRUCTOR
    
    public function dashboard()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        
        return view('admin.dashboard');
    }
}