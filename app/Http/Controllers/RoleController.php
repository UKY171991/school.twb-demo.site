<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = [
            ['name' => 'Master', 'description' => 'Can create admin and user, and can see everything.'],
            ['name' => 'Admin', 'description' => 'Can create user, and can see own data and data of users they created.'],
            ['name' => 'User', 'description' => 'Can only see their own data.'],
        ];

        return view('roles.index', compact('roles'));
    }
}
