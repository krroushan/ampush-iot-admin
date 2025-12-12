<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    /**
     * Display the privacy policy page
     */
    public function index()
    {
        return view('privacy-policy');
    }
}

