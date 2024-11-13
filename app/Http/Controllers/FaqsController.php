<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class FaqsController extends Controller
{
    public function index()
    {
        return Inertia::render('Faqs/Index', [
            'title' => 'FAQs'
        ]);
    }
}
