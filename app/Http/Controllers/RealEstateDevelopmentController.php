<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class RealEstateDevelopmentController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        return view('real-estate-development', [
            'title' => 'Real Estate Development | Selotemna',
            'description' => 'Explore Selotemna’s Real Estate Development division and the Omu Creek Upcoming Project and land opportunity.',
            'featuredProperty' => $this->content->featuredProperty(),
            'testimonials' => $this->content->testimonials('Real Estate Development'),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
