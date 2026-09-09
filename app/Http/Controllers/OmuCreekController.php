<?php

namespace App\Http\Controllers;

use App\Support\ProjectPageResolver;
use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OmuCreekController extends Controller
{
    public function __construct(
        private readonly SelotemnaContent $content,
        private readonly ProjectPageResolver $projects,
    ) {}

    public function __invoke(Request $request): View
    {
        $featuredProperty = $this->projects->resolve($request, 'omu-creek');
        abort_if($featuredProperty === [], 404);

        $media = $this->content->siteMedia();
        $heroImage = $featuredProperty['hero_image'] ?? $featuredProperty['short_video_poster'] ?? $media['development_aerial'] ?? null;
        $galleryImage = $featuredProperty['gallery_image'] ?? null;

        return view('omu-creek', [
            'title' => ($featuredProperty['seo_title'] ?? null) ?: 'Omu Creek Upcoming Project | Selotemna',
            'description' => ($featuredProperty['seo_description'] ?? null) ?: 'Review the Omu Creek Upcoming Project, including its land title, plot sizes, current prices, charges, allocation terms and inspection options.',
            'canonicalUrl' => url($featuredProperty['canonical_path'] ?? route('omu-creek')),
            'featuredProperty' => $featuredProperty,
            'heroImage' => $heroImage,
            'videoPoster' => $featuredProperty['video_poster'] ?? $galleryImage ?? $heroImage,
            'closingImage' => $galleryImage ?? $heroImage,
            'locationCoverage' => collect($featuredProperty['locations'] ?? [])
                ->map(fn (string $location): string => str_replace(' LGA', '', $location))
                ->join(', ', ' and '),
            'testimonials' => $this->content->testimonials('Real Estate Development', 'Omu Creek'),
            'contact' => $this->content->contactDetails(),
            'isPreview' => $request->filled('preview'),
        ]);
    }
}
