<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function index(): View
    {
        $featuredProperty = $this->content->featuredProperty();
        $media = $this->content->siteMedia();

        return view('projects.index', [
            'title' => 'Projects | Selotemna',
            'description' => 'Explore Selotemna projects by stage, including Omu Creek, the company’s latest project and current opportunity with detailed public information.',
            'heroImage' => $featuredProperty['short_video_poster'] ?? $media['development_aerial'] ?? null,
            'projectGroups' => $this->content->projectGroups(includeEmptyGroups: true),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
