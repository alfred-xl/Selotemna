<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function index(): View
    {
        return view('projects.index', [
            'title' => 'Projects | Selotemna',
            'description' => 'Explore Selotemna project categories across Real Estate Development and Engineering & Construction.',
            'projectGroups' => $this->content->projectGroups(),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
