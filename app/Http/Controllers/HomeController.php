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
            'description' => 'Explore Selotemna’s two divisions, its wider development work and Omu Creek, the company’s latest project and current detailed opportunity.',
            'featuredProperty' => $this->content->featuredProperty(),
            'media' => $this->content->siteMedia(),
            'testimonials' => $this->content->testimonials(),
            'faqs' => $this->content->homepageFaqs(),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
