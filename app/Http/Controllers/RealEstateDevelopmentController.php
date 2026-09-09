<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class RealEstateDevelopmentController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        $featuredProperty = $this->content->project('omu-creek');
        $media = $this->content->siteMedia();

        return view('real-estate-development', [
            'title' => 'Real Estate Development | Selotemna',
            'description' => 'Explore Selotemna’s real estate development work and Omu Creek, its latest project and current property opportunity with detailed public information.',
            'featuredProperty' => $featuredProperty,
            'heroImage' => $featuredProperty['hero_image'] ?? $featuredProperty['short_video_poster'] ?? $media['development_aerial'] ?? null,
            'testimonials' => $this->content->testimonials('Real Estate Development'),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
