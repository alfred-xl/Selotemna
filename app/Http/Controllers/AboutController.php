<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        return view('about', [
            'title' => 'About Selotemna | Real Estate Development and Construction',
            'description' => 'Meet Selotemna and explore its two divisions: Real Estate Development and Engineering & Construction.',
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
