<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class ContactController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        $editorialMedia = $this->content->editorialMedia();

        return view('contact', [
            'title' => 'Contact Selotemna | Property and Project Enquiries',
            'description' => 'Contact Selotemna about Omu Creek, Real Estate Development, Engineering & Construction or an inspection request.',
            'contact' => $this->content->contactDetails(),
            'contactMedia' => $editorialMedia['contact'] ?? null,
        ]);
    }
}
