<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ContactController extends Controller
{
    /**
     * Show the contact page
     */
    public function index(): View
    {
        return view('contact', ['breadcrumb' => [
            'name' => 'contact'
        ]]);
    }
}
