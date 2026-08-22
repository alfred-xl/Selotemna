<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function index(): View
    {
        return view('home', [
            'title' => 'Selotemna | Real Estate Development, Engineering & Construction',
            'description' => 'Explore Selotemna’s real estate development, engineering and construction direction, projects and the Omu Creek land opportunity.',
            'featuredProperty' => $this->content->featuredProperty(),
            'projectGroups' => $this->content->projectGroups(),
            'faqs' => $this->content->homepageFaqs(),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
