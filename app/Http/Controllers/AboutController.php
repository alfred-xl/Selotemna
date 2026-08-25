<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        $media = $this->content->siteMedia();

        return view('about', [
            'title' => 'About Selotemna | Real Estate Development, Engineering & Construction',
            'description' => 'Learn about Selotemna’s real estate development, engineering and construction work, including current property opportunities and project enquiry pathways.',
            'heroImage' => $media['development_aerial'] ?? null,
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
