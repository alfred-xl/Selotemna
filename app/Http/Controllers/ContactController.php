<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class ContactController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        return view('contact', [
            'title' => 'Contact Selotemna',
            'description' => 'Contact Selotemna about real estate development, Omu Creek inspections, engineering or construction requirements.',
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
