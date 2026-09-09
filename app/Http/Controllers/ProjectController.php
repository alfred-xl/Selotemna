<?php

namespace App\Http\Controllers;

use App\Support\ProjectPageResolver;
use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        private readonly SelotemnaContent $content,
        private readonly ProjectPageResolver $projects,
    ) {}

    public function index(): View
    {
        $featuredProperty = $this->content->featuredProperty();
        $media = $this->content->siteMedia();

        return view('projects.index', [
            'title' => 'Projects | Selotemna',
            'description' => 'Explore Selotemna projects by stage, including Omu Creek, the company’s latest project and current opportunity with detailed public information.',
            'heroImage' => $featuredProperty['hero_image'] ?? $featuredProperty['short_video_poster'] ?? $media['development_aerial'] ?? null,
            'featuredProperty' => $featuredProperty,
            'projectGroups' => $this->content->projectGroups(includeEmptyGroups: true),
            'contact' => $this->content->contactDetails(),
        ]);
    }

    public function show(Request $request, string $slug): View|RedirectResponse
    {
        if ($slug === 'omu-creek' && ! $request->filled('preview')) {
            return redirect()->route('omu-creek', status: 301);
        }

        $project = $this->projects->resolve($request, $slug);
        abort_if($project === [], 404);

        $heroImage = $project['hero_image'] ?? $project['short_video_poster'] ?? $this->content->siteMedia()['development_aerial'] ?? null;

        return view('projects.show', [
            'title' => ($project['seo_title'] ?? null) ?: $project['name'].' | Selotemna',
            'description' => ($project['seo_description'] ?? null) ?: $project['summary'],
            'canonicalUrl' => url($project['canonical_path'] ?: route('projects.show', ['slug' => $project['slug']])),
            'project' => $project,
            'heroImage' => $heroImage,
            'testimonials' => $this->content->testimonials($project['division'], $project['name']),
            'contact' => $this->content->contactDetails(),
            'isPreview' => $request->filled('preview'),
        ]);
    }
}
