<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $data['page_title'] = "Profile";
        return view('page.profile', $data);
    }
}
