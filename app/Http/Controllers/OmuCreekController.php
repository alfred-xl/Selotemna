<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class OmuCreekController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        $featuredProperty = $this->content->featuredProperty();
        $media = $this->content->siteMedia();
        $heroImage = $featuredProperty['short_video_poster'] ?? $media['development_aerial'] ?? null;

        return view('omu-creek', [
            'title' => 'Omu Creek Upcoming Project | Selotemna',
            'description' => 'Review the Omu Creek Upcoming Project, including its land title, plot sizes, current prices, charges, allocation terms and inspection options.',
            'featuredProperty' => $featuredProperty,
            'heroImage' => $heroImage,
            'videoPoster' => $featuredProperty['video_poster'] ?? $heroImage,
            'locationCoverage' => collect($featuredProperty['locations'] ?? [])
                ->map(fn (string $location): string => str_replace(' LGA', '', $location))
                ->join(', ', ' and '),
            'testimonials' => $this->content->testimonials('Real Estate Development', 'Omu Creek'),
            'faqs' => $this->content->faqsByIds([
                'omu-creek-title',
                'omu-creek-pricing',
                'omu-creek-installments',
                'omu-creek-charges',
                'omu-creek-allocation',
                'omu-creek-construction',
            ]),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
