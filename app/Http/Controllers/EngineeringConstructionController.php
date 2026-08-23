<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class EngineeringConstructionController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        return view('engineering-construction', [
            'title' => 'Engineering & Construction | Selotemna',
            'description' => 'Bring an engineering or construction requirement to Selotemna for a focused project conversation and clear next step.',
            'media' => $this->content->siteMedia(),
            'testimonials' => $this->content->testimonials('Engineering & Construction'),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
